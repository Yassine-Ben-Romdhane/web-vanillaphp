<?php
require_once 'auth.php';
// Already logged in — redirect away
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}
$page_title  = 'Login – Carthage Eagles';
$active_page = 'login';
$extra_css   = 'login.css';
include 'includes/header.php';

$redirect  = htmlspecialchars(safe_redirect($_GET['redirect'] ?? 'index.php'), ENT_QUOTES, 'UTF-8');
$error     = $_GET['error'] ?? '';
$tab       = $_GET['tab']   ?? 'login'; // 'login' or 'register'

$error_messages = [
    'invalid'        => 'Incorrect email or password.',
    'email_taken'    => 'An account with that email already exists.',
    'weak_password'  => 'Password must be at least 8 characters.',
    'invalid_email'  => 'Please enter a valid email address.',
    'missing_fields' => 'Please fill in all required fields.',
    'db_error'       => 'Something went wrong. Please try again.',
];
$error_text = $error_messages[$error] ?? '';
?>

<div class="auth-page">
  <div class="auth-bg"></div>

  <div class="auth-card">
    <!-- Logo -->
    <div class="auth-logo">
      <img src="img/logo.png" alt="Carthage Eagles" />
    </div>

    <!-- Tab Switcher -->
    <div class="auth-tabs">
      <button class="auth-tab <?php echo $tab === 'login' ? 'auth-tab--active' : ''; ?>" data-tab="login">LOGIN</button>
      <button class="auth-tab <?php echo $tab === 'register' ? 'auth-tab--active' : ''; ?>" data-tab="register">JOIN THE EAGLES</button>
    </div>

    <?php if ($error_text): ?>
    <div class="auth-error"><?php echo htmlspecialchars($error_text, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <!-- Login Form -->
    <form class="auth-form <?php echo $tab === 'register' ? 'auth-form--hidden' : ''; ?>" id="form-login"
          method="POST" action="login_action.php">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="redirect"   value="<?php echo $redirect; ?>">

      <div class="auth-field">
        <label class="auth-label" for="login-email">Email</label>
        <input class="auth-input" type="email" id="login-email" name="email"
               placeholder="your@email.com" required autocomplete="email">
      </div>
      <div class="auth-field">
        <label class="auth-label" for="login-password">Password</label>
        <input class="auth-input" type="password" id="login-password" name="password"
               placeholder="••••••••" required autocomplete="current-password">
      </div>
      <button class="auth-submit" type="submit">SIGN IN</button>
    </form>

    <!-- Register Form -->
    <form class="auth-form <?php echo $tab === 'login' ? 'auth-form--hidden' : ''; ?>" id="form-register"
          method="POST" action="register_action.php">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
      <input type="hidden" name="redirect"   value="<?php echo $redirect; ?>">

      <div class="auth-row">
        <div class="auth-field">
          <label class="auth-label" for="reg-first">First Name</label>
          <input class="auth-input" type="text" id="reg-first" name="first_name"
                 placeholder="Ahmed" required autocomplete="given-name">
        </div>
        <div class="auth-field">
          <label class="auth-label" for="reg-last">Last Name</label>
          <input class="auth-input" type="text" id="reg-last" name="last_name"
                 placeholder="Ben Ali" required autocomplete="family-name">
        </div>
      </div>
      <div class="auth-field">
        <label class="auth-label" for="reg-email">Email</label>
        <input class="auth-input" type="email" id="reg-email" name="email"
               placeholder="your@email.com" required autocomplete="email">
      </div>
      <div class="auth-field">
        <label class="auth-label" for="reg-password">Password</label>
        <input class="auth-input" type="password" id="reg-password" name="password"
               placeholder="Min. 8 characters" required autocomplete="new-password" minlength="8">
      </div>
      <button class="auth-submit" type="submit">CREATE ACCOUNT</button>
    </form>
  </div>
</div>

<script>
  document.querySelectorAll('.auth-tab').forEach(function(btn) {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.auth-tab').forEach(function(b) { b.classList.remove('auth-tab--active'); });
      btn.classList.add('auth-tab--active');
      var target = btn.getAttribute('data-tab');
      document.querySelectorAll('.auth-form').forEach(function(f) {
        f.classList.toggle('auth-form--hidden', f.id !== 'form-' + target);
      });
    });
  });
</script>

<?php include 'includes/footer.php'; ?>
