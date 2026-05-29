<?php
$page_title = 'Connection Error – Carthage Eagles';
include 'includes/header.php';
?>

<div class="error-page" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; text-align: center; background: #fff;">
  <div class="error-content" style="max-width: 600px; padding: 40px;">
    <div class="error-icon" style="font-size: 80px; margin-bottom: 20px;">🏟️</div>
    <h1 style="font-family: var(--h1-font-family); color: #81000B; font-size: 48px; margin-bottom: 16px;">STADIUM MAINTENANCE</h1>
    <p style="font-family: var(--paragraph-font-family); color: #666; font-size: 18px; line-height: 1.6; letter-spacing: 0.02em;">
      We're currently experiencing some technical difficulties connecting to our base camp. 
      Please hold steady while our team works to restore the connection.
    </p>
    <div style="margin-top: 40px;">
      <a href="index.php" style="
        display: inline-block;
        padding: 14px 32px;
        background: #0a0a0a;
        color: #fff;
        text-decoration: none;
        font-family: var(--paragraph-font-family);
        font-weight: 700;
        letter-spacing: 0.2em;
        font-size: 13px;
        transition: background 0.3s ease;
      " onmouseover="this.style.background='#81000B'" onmouseout="this.style.background='#0a0a0a'">
        RETURN TO HOME
      </a>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
