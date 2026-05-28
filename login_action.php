<?php
require_once 'auth.php';
require_once 'db.php';

// If the DB connection failed, redirect with a friendly error
if (empty($pdo)) {
    header('Location: login.php?tab=login&error=db_error');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

verify_csrf();

$email    = trim(filter_var($_POST['email']    ?? '', FILTER_SANITIZE_EMAIL));
$password = $_POST['password'] ?? '';
$redirect = safe_redirect($_POST['redirect'] ?? 'index.php');

if (!$email || !$password) {
    header('Location: login.php?tab=login&error=missing_fields&redirect=' . urlencode($redirect));
    exit;
}

 $stmt = $pdo->prepare('SELECT id, first_name, last_name, email, password_hash FROM users WHERE email = :email LIMIT 1');
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    header('Location: login.php?tab=login&error=invalid&redirect=' . urlencode($redirect));
    exit;
}

// Regenerate the session ID on privilege change to prevent session fixation
session_regenerate_id(true);
$_SESSION['user_id']         = $user['id'];
$_SESSION['user_first_name'] = $user['first_name'];
$_SESSION['user_last_name']  = $user['last_name'] ?? '';
$_SESSION['user_email']      = $user['email'];
 $_SESSION['user_phone']      = '';

header('Location: ' . $redirect);
exit;
