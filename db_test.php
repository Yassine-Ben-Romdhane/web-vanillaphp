<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');
if (!empty($pdo)) {
    echo json_encode(['ok' => true, 'message' => 'Connected using $pdo']);
    exit;
}

// Attempt an explicit connection to surface any exception message for debugging
$dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';sslmode=require';
try {
    $test = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo json_encode(['ok' => true, 'message' => 'Connected with explicit test PDO']);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
