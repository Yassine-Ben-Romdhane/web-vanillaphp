<?php
require_once 'auth.php';
require_once 'db.php';

// If the DB connection failed, redirect with a friendly error
if (empty($pdo)) {
    header('Location: login.php?tab=register&error=db_error');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php?tab=register');
    exit;
}

verify_csrf();

$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name']  ?? '');
$email      = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$password   = $_POST['password'] ?? '';
$redirect   = safe_redirect($_POST['redirect'] ?? 'index.php');

if (!$first_name || !$last_name || !$email || !$password) {
    header('Location: login.php?tab=register&error=missing_fields&redirect=' . urlencode($redirect));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: login.php?tab=register&error=invalid_email&redirect=' . urlencode($redirect));
    exit;
}

if (strlen($password) < 8) {
    header('Location: login.php?tab=register&error=weak_password&redirect=' . urlencode($redirect));
    exit;
}

$check = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
$check->execute([':email' => $email]);
if ($check->fetch()) {
    header('Location: login.php?tab=register&error=email_taken&redirect=' . urlencode($redirect));
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);
try {
    $insert = $pdo->prepare(
        'INSERT INTO users (email, password_hash, first_name, last_name) VALUES (:email, :hash, :first, :last)'
    );
    $insert->execute([
        ':email' => $email,
        ':hash'  => $hash,
        ':first' => $first_name,
        ':last'  => $last_name,
    ]);
    $new_id = $pdo->lastInsertId();
} catch (PDOException $e) {
    header('Location: login.php?tab=register&error=db_error&redirect=' . urlencode($redirect));
    exit;
}

session_regenerate_id(true);
$_SESSION['user_id']         = $new_id;
$_SESSION['user_first_name'] = $first_name;
$_SESSION['user_last_name']  = $last_name;
$_SESSION['user_email']      = $email;
$_SESSION['user_phone']      = '';

header('Location: ' . $redirect);
exit;
