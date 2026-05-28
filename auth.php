<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Strict',
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function current_user(): array {
    return [
        'id'         => $_SESSION['user_id']         ?? null,
        'first_name' => $_SESSION['user_first_name'] ?? '',
        'last_name'  => $_SESSION['user_last_name']  ?? '',
        'email'      => $_SESSION['user_email']      ?? '',
        'phone'      => $_SESSION['user_phone']      ?? '',
    ];
}

function require_login(string $redirect = 'login.php'): void {
    if (!is_logged_in()) {
        $back = urlencode($_SERVER['REQUEST_URI']);
        header("Location: {$redirect}?redirect={$back}");
        exit;
    }
}

function csrf_token(): string {
    // Store multiple outstanding, single-use tokens per session to
    // allow users to have multiple forms/tabs open while keeping
    // the single-use property for each token.
    $max_tokens = 20;
    if (!isset($_SESSION['csrf_tokens']) || !is_array($_SESSION['csrf_tokens'])) {
        $_SESSION['csrf_tokens'] = [];
    }
    $token = bin2hex(random_bytes(32));
    // store with timestamp so we can prune old tokens
    $_SESSION['csrf_tokens'][$token] = time();

    // prune old tokens if we exceed the limit
    if (count($_SESSION['csrf_tokens']) > $max_tokens) {
        // sort by timestamp ascending and remove oldest
        asort($_SESSION['csrf_tokens']);
        while (count($_SESSION['csrf_tokens']) > $max_tokens) {
            $oldest = array_key_first($_SESSION['csrf_tokens']);
            unset($_SESSION['csrf_tokens'][$oldest]);
        }
    }

    // also prune tokens older than 1 day for hygiene
    $expiry = 60 * 60 * 24; // 1 day
    foreach ($_SESSION['csrf_tokens'] as $t => $ts) {
        if ($ts < time() - $expiry) {
            unset($_SESSION['csrf_tokens'][$t]);
        }
    }

    return $token;
}

function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    $found = false;
    if (!empty($token) && !empty($_SESSION['csrf_tokens']) && is_array($_SESSION['csrf_tokens'])) {
        foreach (array_keys($_SESSION['csrf_tokens']) as $t) {
            if (hash_equals($t, $token)) {
                // single-use: remove the token after successful check
                unset($_SESSION['csrf_tokens'][$t]);
                $found = true;
                break;
            }
        }
    }
    if (!$found) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }
}

// Whitelist-based redirect to block open-redirect abuse
function safe_redirect(string $url, string $default = 'index.php'): string {
    $allowed = [
        'index.php', 'store.php', 'checkout.php',
        'book.php', 'team.php', 'stats.php',
    ];
    $path = parse_url($url, PHP_URL_PATH);
    $base = basename((string) $path);
    return in_array($base, $allowed, true) ? $url : $default;
}
