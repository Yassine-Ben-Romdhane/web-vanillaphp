<?php
require_once 'auth.php';
require_once 'db.php';
require_login('login.php');

// Read and immediately clear the flash data
$order_id = $_SESSION['last_order_id']    ?? null;
$total    = $_SESSION['last_order_total'] ?? null;
unset($_SESSION['last_order_id'], $_SESSION['last_order_total']);

if (!$order_id) {
    header('Location: store.php');
    exit;
}

// Fetch order items for display
$stmt = $pdo->prepare(
    'SELECT product_name, unit_price, quantity FROM order_items WHERE order_id = :order_id'
);
$stmt->execute([':order_id' => $order_id]);
$items = $stmt->fetchAll();

$page_title  = 'Order Confirmed – Carthage Eagles';
$active_page = 'store';
$extra_css   = 'checkout.css';
include 'includes/header.php';
$user = current_user();
?>

<div class="checkout-page">
  <div class="checkout-hero">
    <div class="checkout-hero__bg"></div>
    <div class="checkout-hero__content">
      <p class="checkout-hero__sub">ORDER PLACED</p>
      <h1 class="checkout-hero__title">CONFIRMED<br/>#<?php echo $order_id; ?></h1>
    </div>
  </div>

  <div class="checkout-container">

    <!-- Order Summary -->
    <div class="checkout-summary">
      <h2 class="checkout-section-title">ORDER #<?php echo $order_id; ?></h2>
      <div class="checkout-items">
        <?php foreach ($items as $item): ?>
        <div class="checkout-item">
          <div class="checkout-item__name"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></div>
          <div class="checkout-item__meta">
            <span class="checkout-item__qty">Qty: <?php echo $item['quantity']; ?></span>
            <span class="checkout-item__price"><?php echo number_format($item['unit_price'] * $item['quantity'], 2); ?> TND</span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="checkout-total">
        <span class="checkout-total__label">TOTAL PAID</span>
        <span class="checkout-total__value"><?php echo number_format($total, 2); ?> TND</span>
      </div>
    </div>

    <!-- Confirmation message -->
    <div class="checkout-info">
      <div class="confirm-success">
        <div class="confirm-success__icon">&#10003;</div>
        <h2 class="confirm-success__title">THANK YOU,<br/><?php echo htmlspecialchars($user['first_name'], ENT_QUOTES, 'UTF-8'); ?>!</h2>
        <p class="confirm-success__text">
          Your order has been confirmed. We'll be in touch at
          <strong><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></strong>
          with shipping updates.
        </p>
        <a href="store.php" class="checkout-confirm-btn confirm-success__btn">CONTINUE SHOPPING</a>
        <a href="index.php" class="checkout-back" style="margin-top:12px;">&#8592; Back to Home</a>
      </div>
    </div>

  </div>
</div>
  <script>
    try {
      localStorage.setItem('lastOrderStatus', 'confirmed');
      localStorage.setItem('lastOrderId', '<?php echo htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8'); ?>');

      // Update the persistent cart: Mark all 'active' items as 'confirmed'
      var cart = JSON.parse(localStorage.getItem('cart') || '[]');
      var updated = false;
      var orderDate = '<?php echo date('Y-m-d H:i:s'); ?>';
      cart.forEach(function(item) {
        if (item.status === 'active') {
          item.status = 'confirmed';
          item.orderId = '<?php echo $order_id; ?>';
          item.orderDate = orderDate;
          updated = true;
        }
      });
      if (updated) {
        localStorage.setItem('cart', JSON.stringify(cart));
      }
    } catch (e) {
      console.error('Failed to update confirmed items in localStorage', e);
    }
  </script>
  <?php include 'includes/footer.php'; ?>
