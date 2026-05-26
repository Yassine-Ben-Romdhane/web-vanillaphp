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
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }
    // tokens are single-use — discard after a successful check
    unset($_SESSION['csrf_token']);
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
