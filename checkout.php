<?php
require_once 'auth.php';
require_once 'db.php';
require_login('login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['cart_json'])) {
    header('Location: store.php');
    exit;
}

verify_csrf();

$raw_cart = json_decode($_POST['cart_json'], true);
if (!is_array($raw_cart) || count($raw_cart) === 0) {
    header('Location: store.php');
    exit;
}

// Prices come from the DB, not the client, so the user can't fake a cheaper cart
$placeholders = implode(',', array_fill(0, count($raw_cart), '?'));
$stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE name IN ($placeholders)");
$stmt->execute(array_column($raw_cart, 'name'));
$db_products = [];
foreach ($stmt->fetchAll() as $row) {
    $db_products[$row['name']] = $row;
}

$validated_cart = [];
foreach ($raw_cart as $item) {
    $name = trim($item['name'] ?? '');
    $qty  = (int)($item['qty'] ?? 0);
  $size = strtoupper(trim($item['size'] ?? ''));
  $allowed_sizes = ['S', 'M', 'L', 'XL'];
  if ($size && !in_array($size, $allowed_sizes, true)) {
    $size = '';
  }
    if (!$name || $qty < 1 || !isset($db_products[$name])) continue;

    $validated_cart[] = [
        'product_id'   => $db_products[$name]['id'],
        'product_name' => $name,
        'unit_price'   => (float)$db_products[$name]['price'],
        'quantity'     => $qty,
    'size'         => $size,
    ];
}

if (count($validated_cart) === 0) {
    header('Location: store.php');
    exit;
}

$total = 0.0;
foreach ($validated_cart as $item) {
    $total += $item['unit_price'] * $item['quantity'];
}

// Store validated cart in session so process_order.php can read it
$_SESSION['pending_cart']  = $validated_cart;
$_SESSION['pending_total'] = $total;

$page_title  = 'Checkout – Carthage Eagles';
$active_page = 'store';
$extra_css   = 'checkout.css';
include 'includes/header.php';
$user = current_user();
?>

<div class="checkout-page">
  <div class="checkout-hero">
    <div class="checkout-hero__bg"></div>
    <div class="checkout-hero__content">
      <p class="checkout-hero__sub">SECURE CHECKOUT</p>
      <h1 class="checkout-hero__title">YOUR<br/>ORDER</h1>
    </div>
  </div>

  <div class="checkout-container">

    <!-- Order Summary -->
    <div class="checkout-summary">
      <h2 class="checkout-section-title">ORDER SUMMARY</h2>
      <div class="checkout-items">
        <?php foreach ($validated_cart as $item): ?>
        <div class="checkout-item">
          <div class="checkout-item__name"><?php echo htmlspecialchars($item['product_name'], ENT_QUOTES, 'UTF-8'); ?></div>
          <?php if (!empty($item['size'])): ?>
          <div class="checkout-item__size">Size: <?php echo htmlspecialchars($item['size'], ENT_QUOTES, 'UTF-8'); ?></div>
          <?php endif; ?>
          <div class="checkout-item__meta">
            <span class="checkout-item__qty">Qty: <?php echo $item['quantity']; ?></span>
            <span class="checkout-item__price"><?php echo number_format($item['unit_price'] * $item['quantity'], 2); ?> TND</span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="checkout-total">
        <span class="checkout-total__label">TOTAL</span>
        <span class="checkout-total__value"><?php echo number_format($total, 2); ?> TND</span>
      </div>
    </div>

    <!-- Delivery Info -->
    <div class="checkout-info">
      <h2 class="checkout-section-title">DELIVERY DETAILS</h2>
      <div class="checkout-account">
        <div class="checkout-account__icon">&#9654;</div>
        <div>
          <div class="checkout-account__name"><?php echo htmlspecialchars($user['first_name'], ENT_QUOTES, 'UTF-8'); ?></div>
          <div class="checkout-account__email"><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
      </div>
      <p class="checkout-notice">
        Your order will be dispatched to the address associated with your account.
        A confirmation will be sent to your email once placed.
      </p>

      <!-- Payment Details -->
      <h2 class="checkout-section-title" style="margin-top: 32px;">PAYMENT DETAILS</h2>
      <form method="POST" action="process_order.php" class="checkout-form">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
        
        <div class="checkout-field">
          <label class="checkout-label">CARDHOLDER NAME</label>
          <input type="text" name="card_name" class="checkout-input" placeholder="Aymen Ben Ali" required>
        </div>

        <div class="checkout-field">
          <label class="checkout-label">CARD NUMBER</label>
          <input type="text" name="card_number" class="checkout-input" placeholder="0000 0000 0000 0000" pattern="[0-9]{16}" maxlength="16" required>
        </div>

        <div class="checkout-row">
          <div class="checkout-field">
            <label class="checkout-label">EXPIRY DATE</label>
            <input type="text" name="card_expiry" class="checkout-input" placeholder="MM/YY" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" maxlength="5" required>
          </div>
          <div class="checkout-field">
            <label class="checkout-label">CVV</label>
            <input type="password" name="card_cvv" class="checkout-input" placeholder="•••" pattern="[0-9]{3}" maxlength="3" required>
          </div>
        </div>

        <button class="checkout-confirm-btn" type="submit">CONFIRM ORDER</button>
      </form>

      <a href="store.php" class="checkout-back">&#8592; Back to Store</a>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
