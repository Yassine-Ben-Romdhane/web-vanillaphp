<?php
require_once 'auth.php';
require_once 'db.php';
require_login('login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: store.php');
    exit;
}

verify_csrf();

$cart  = $_SESSION['pending_cart']  ?? null;
$total = $_SESSION['pending_total'] ?? null;

if (!$cart || !$total) {
    header('Location: store.php');
    exit;
}

// Payment fields (Mock validation)
$card_name   = trim($_POST['card_name']   ?? '');
$card_number = trim($_POST['card_number'] ?? '');
$card_expiry = trim($_POST['card_expiry'] ?? '');
$card_cvv    = trim($_POST['card_cvv']    ?? '');

if (!$card_name || strlen($card_number) !== 16 || !preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $card_expiry) || strlen($card_cvv) !== 3) {
    header('Location: store.php?error=payment_failed'); // Simple error handling for demo
    exit;
}

$user = current_user();

// Both inserts (order + its items) succeed together or not at all
try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'INSERT INTO orders (user_id, total_amount, status) VALUES (:user_id, :total, :status)'
    );
    $stmt->execute([
        ':user_id' => $user['id'],
        ':total'   => $total,
        ':status'  => 'confirmed',
    ]);
    $order_id = (int) $pdo->lastInsertId();

    $item_stmt = $pdo->prepare(
        'INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity)
         VALUES (:order_id, :product_id, :product_name, :unit_price, :quantity)'
    );
    foreach ($cart as $item) {
        $product_name = $item['product_name'];
        if (!empty($item['size'])) {
            $product_name .= ' (Size: ' . $item['size'] . ')';
        }
        $item_stmt->execute([
            ':order_id'     => $order_id,
            ':product_id'   => $item['product_id'],
            ':product_name' => $product_name,
            ':unit_price'   => $item['unit_price'],
            ':quantity'     => $item['quantity'],
        ]);
    }

    $pdo->commit();

    unset($_SESSION['pending_cart'], $_SESSION['pending_total']);
    $_SESSION['last_order_id']    = $order_id;
    $_SESSION['last_order_total'] = $total;

    header('Location: order_confirmation.php');
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    header('Location: checkout.php?error=db_error');
    exit;
}
