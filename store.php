<?php
require_once 'db.php';
$page_title = 'Store – Carthage Eagles';
$active_page = 'store';
$extra_css = 'store.css';
include 'includes/header.php';

// Inject auth state + CSRF token for store.js
$_is_logged_in = is_logged_in() ? 'true' : 'false';
echo '<script>var IS_LOGGED_IN=' . $_is_logged_in . '; var CSRF_TOKEN="' . csrf_token() . '";</script>';

// Fetch products from database
if ($pdo) {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();
} else {
    $products = [
        ['name' => "Home Jersey\n2024", 'price' => 89.99, 'category' => 'jersey', 'badge' => 'NEW', 'img_class' => 'product-img--home-jersey', 'year' => '2024'],
        ['name' => "Away Jersey\n2024", 'price' => 89.99, 'category' => 'jersey', 'badge' => '', 'img_class' => 'product-img--away-jersey', 'year' => '2024'],
        ['name' => "Training Kit\n2024", 'price' => 59.99, 'category' => 'training', 'badge' => '', 'img_class' => 'product-img--training', 'year' => null],
        ['name' => "Eagles Cap", 'price' => 29.99, 'category' => 'accessories', 'badge' => '', 'img_class' => 'product-img--cap', 'year' => null],
        ['name' => "Scarf", 'price' => 19.99, 'category' => 'accessories', 'badge' => '', 'img_class' => 'product-img--scarf', 'year' => null],
        ['name' => "Retro Jersey\n1978", 'price' => 79.99, 'category' => 'jersey', 'badge' => 'LIMITED', 'img_class' => 'product-img--jersey-retro', 'year' => '1978'],
        ['name' => "Training Shorts", 'price' => 39.99, 'category' => 'training', 'badge' => '', 'img_class' => 'product-img--shorts', 'year' => null],
    ];
}
?>


  <!-- Store Hero -->
  <div class="store-hero">
    <div class="store-hero__bg"></div>
    <div class="store-hero__content">
      <p class="store-hero__sub">OFFICIAL MERCHANDISE</p>
      <h1 class="store-hero__title">WEAR THE<br/>EAGLE</h1>
      <p class="store-hero__desc">Represent Tunisia. Wear your pride.</p>
    </div>
    <div class="store-hero__scroll-hint">
      <img src="img/arrow.svg" alt="" />
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="store-filters">
    <button class="filter-btn active" data-filter="all">ALL</button>
    <button class="filter-btn" data-filter="jersey">JERSEYS</button>
    <button class="filter-btn" data-filter="training">TRAINING</button>
    <button class="filter-btn" data-filter="accessories">ACCESSORIES</button>
  </div>

  <!-- Product Grid -->
  <div class="store-grid">
    <?php foreach ($products as $product): ?>
    <div class="product-card" data-category="<?php echo htmlspecialchars($product['category']); ?>">
      <?php if ($product['badge']): ?>
      <div class="product-card__badge <?php echo ($product['badge'] == 'NEW') ? 'product-card__badge--new' : ''; ?>">
        <?php echo htmlspecialchars($product['badge']); ?>
      </div>
      <?php endif; ?>
      <div class="product-card__img-wrap">
        <div class="product-card__img <?php echo htmlspecialchars($product['img_class']); ?>"></div>
        <div class="product-card__overlay">
          <button class="product-card__quick-add" 
                  data-product="<?php echo htmlspecialchars($product['name']); ?>" 
                  data-price="<?php echo $product['price']; ?>">QUICK ADD</button>
        </div>
      </div>
      <div class="product-card__info">
        <div class="product-card__meta">
          <span class="product-card__cat"><?php echo strtoupper(htmlspecialchars($product['category'])); ?> JERSEY</span>
          <?php if ($product['year']): ?>
          <span class="product-card__year"><?php echo htmlspecialchars($product['year']); ?></span>
          <?php endif; ?>
        </div>
        <h3 class="product-card__name"><?php echo nl2br(htmlspecialchars($product['name'])); ?></h3>
        <div class="product-card__footer">
          <span class="product-card__price"><?php echo number_format($product['price'], 2); ?> TND</span>
          <button class="product-card__btn" 
                  data-product="<?php echo htmlspecialchars($product['name']); ?>" 
                  data-price="<?php echo $product['price']; ?>">ADD TO CART</button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Cart Sidebar -->
  <div class="cart-overlay" id="cartOverlay"></div>
  <aside class="cart-sidebar" id="cartSidebar">
    <div class="cart-sidebar__header">
      <h2 class="cart-sidebar__title">YOUR CART</h2>
      <button class="cart-sidebar__close" id="cartClose" aria-label="Close cart">&#x2715;</button>
    </div>
    <div class="cart-sidebar__items" id="cartItems">
      <p class="cart-sidebar__empty">Your cart is empty.</p>
    </div>
    <div class="cart-sidebar__footer">
      <div class="cart-sidebar__total">
        <span>TOTAL</span>
        <span id="cartTotal">0.00 TND</span>
      </div>
      <button class="cart-sidebar__checkout">CHECKOUT</button>
    </div>
  </aside>

  <!-- Cart FAB -->
  <button class="cart-fab" id="cartFab" aria-label="Open cart">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
      <line x1="3" y1="6" x2="21" y2="6"/>
      <path d="M16 10a4 4 0 01-8 0"/>
    </svg>
    <span class="cart-fab__count" id="cartCount">0</span>
  </button>

<?php 
$extra_js = 'store.js';
include 'includes/footer.php'; 
?>
