// Hamburger menu
document.querySelector('.hamburger').addEventListener('click', function () {
  document.querySelector('.navbar-2').classList.toggle('active');
});

// Cart state
var cart = [];

function getTotal() {
  return cart.reduce(function (sum, item) {
    return sum + item.price * item.qty;
  }, 0);
}

function updateCartCount() {
  var total = cart.reduce(function (s, i) { return s + i.qty; }, 0);
  var countEl = document.getElementById('cartCount');
  countEl.textContent = total;
  countEl.classList.remove('bump');
  void countEl.offsetWidth; // reflow
  countEl.classList.add('bump');
}

function renderCart() {
  var itemsEl = document.getElementById('cartItems');
  var totalEl = document.getElementById('cartTotal');

  if (cart.length === 0) {
    itemsEl.innerHTML = '<p class="cart-sidebar__empty">Your cart is empty.</p>';
    totalEl.textContent = '0.00 TND';
    return;
  }

  itemsEl.innerHTML = '';
  cart.forEach(function (item, idx) {
    var row = document.createElement('div');
    row.className = 'cart-item';
    row.innerHTML =
      '<div class="cart-item__thumb"></div>' +
      '<div class="cart-item__details">' +
        '<div class="cart-item__name">' + item.name + '</div>' +
        '<div class="cart-item__price">' + (item.price * item.qty).toFixed(2) + ' TND</div>' +
        '<div class="cart-item__qty">' +
          '<button class="cart-item__qty-btn" data-action="dec" data-idx="' + idx + '">&#8722;</button>' +
          '<span class="cart-item__qty-val">' + item.qty + '</span>' +
          '<button class="cart-item__qty-btn" data-action="inc" data-idx="' + idx + '">&#43;</button>' +
        '</div>' +
      '</div>' +
      '<button class="cart-item__remove" data-idx="' + idx + '" aria-label="Remove">&#x2715;</button>';
    itemsEl.appendChild(row);
  });

  totalEl.textContent = getTotal().toFixed(2) + ' TND';
}

function addToCart(name, price) {
  var existing = cart.find(function (i) { return i.name === name; });
  if (existing) {
    existing.qty++;
  } else {
    cart.push({ name: name, price: parseFloat(price), qty: 1 });
  }
  updateCartCount();
  renderCart();
}

// Delegated qty / remove clicks inside cart
document.getElementById('cartItems').addEventListener('click', function (e) {
  var btn = e.target.closest('[data-action]');
  var rem = e.target.closest('.cart-item__remove');

  if (btn) {
    var idx = parseInt(btn.getAttribute('data-idx'));
    if (btn.getAttribute('data-action') === 'inc') {
      cart[idx].qty++;
    } else {
      cart[idx].qty--;
      if (cart[idx].qty <= 0) cart.splice(idx, 1);
    }
    updateCartCount();
    renderCart();
  }

  if (rem) {
    var ridx = parseInt(rem.getAttribute('data-idx'));
    cart.splice(ridx, 1);
    updateCartCount();
    renderCart();
  }
});

// Add to cart buttons (grid + quick-add)
document.querySelectorAll('.product-card__btn, .product-card__quick-add').forEach(function (btn) {
  btn.addEventListener('click', function () {
    addToCart(this.getAttribute('data-product'), this.getAttribute('data-price'));

    // Visual feedback on grid button only
    if (this.classList.contains('product-card__btn')) {
      var orig = this.textContent;
      this.textContent = 'ADDED ✓';
      this.classList.add('added');
      var self = this;
      setTimeout(function () {
        self.textContent = orig;
        self.classList.remove('added');
      }, 1400);
    }

    openCart();
  });
});

// Cart open / close
var sidebar  = document.getElementById('cartSidebar');
var overlay  = document.getElementById('cartOverlay');

function openCart()  { sidebar.classList.add('open'); overlay.classList.add('open'); }
function closeCart() { sidebar.classList.remove('open'); overlay.classList.remove('open'); }

document.getElementById('cartFab').addEventListener('click', openCart);
document.getElementById('cartClose').addEventListener('click', closeCart);
overlay.addEventListener('click', closeCart);

// Checkout button
document.querySelector('.cart-sidebar__checkout').addEventListener('click', function () {
  if (cart.length === 0) return;

  if (!IS_LOGGED_IN) {
    window.location.href = 'login.php?redirect=' + encodeURIComponent('checkout.php');
    return;
  }

  // POST cart JSON to checkout.php via a hidden form
  var form = document.createElement('form');
  form.method = 'POST';
  form.action = 'checkout.php';

  var cartInput = document.createElement('input');
  cartInput.type  = 'hidden';
  cartInput.name  = 'cart_json';
  cartInput.value = JSON.stringify(cart);
  form.appendChild(cartInput);

  var csrfInput = document.createElement('input');
  csrfInput.type  = 'hidden';
  csrfInput.name  = 'csrf_token';
  csrfInput.value = CSRF_TOKEN;
  form.appendChild(csrfInput);

  document.body.appendChild(form);
  form.submit();
});

// Filter bar
document.querySelectorAll('.filter-btn').forEach(function (btn) {
  btn.addEventListener('click', function () {
    document.querySelectorAll('.filter-btn').forEach(function (b) { b.classList.remove('active'); });
    this.classList.add('active');

    var filter = this.getAttribute('data-filter');
    document.querySelectorAll('.product-card').forEach(function (card) {
      if (filter === 'all' || card.getAttribute('data-category') === filter) {
        card.classList.remove('hidden');
      } else {
        card.classList.add('hidden');
      }
    });
  });
});
