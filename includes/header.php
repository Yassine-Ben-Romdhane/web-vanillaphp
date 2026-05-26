<?php require_once __DIR__ . '/../auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title><?php echo isset($page_title) ? $page_title : 'Carthage Eagles'; ?></title>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="styleguide.css" />
    <link rel="stylesheet" href="style.css" />
    <?php if (isset($extra_css)): ?>
    <link rel="stylesheet" href="<?php echo $extra_css; ?>" />
    <?php endif; ?>
  </head>
  <body>
    <!-- Page Wrapper -->
    <div class="<?php echo isset($active_page) ? $active_page . '-page' : 'page-wrapper'; ?>">
      <!-- Navbar -->
    <div class="navbar">
      <div class="logo"><a href="index.php"><img src="img/logo.png" alt="Logo" /></a></div>
      <button class="hamburger" id="hamburger" aria-label="Toggle menu">
        <span></span><span></span><span></span>
      </button>
      <div class="navbar-2" id="navMenu">
        <a href="index.php"><div class="text-wrapper-3 <?php echo ($active_page == 'home') ? 'nav-active' : ''; ?>">HOME</div></a>
        <a href="stats.php"><div class="text-wrapper-3 <?php echo ($active_page == 'stats') ? 'nav-active' : ''; ?>">STATS</div></a>
        <a href="team.php"><div class="text-wrapper-3 <?php echo ($active_page == 'team') ? 'nav-active' : ''; ?>">TEAM</div></a>
        <a href="book.php"><div class="text-wrapper-3 <?php echo ($active_page == 'book') ? 'nav-active' : ''; ?>">BOOK</div></a>
        <a href="store.php"><div class="text-wrapper-3 <?php echo ($active_page == 'store') ? 'nav-active' : ''; ?>">STORE</div></a>
        <?php if (is_logged_in()): $u = current_user(); ?>
          <div class="nav-user">
            <span class="nav-user__greeting text-wrapper-3">Hi, <?php echo htmlspecialchars($u['first_name'], ENT_QUOTES, 'UTF-8'); ?></span>
            <a href="logout.php"><div class="text-wrapper-3">LOGOUT</div></a>
          </div>
        <?php else: ?>
          <a href="login.php"><div class="text-wrapper-3 <?php echo ($active_page == 'login') ? 'nav-active' : ''; ?>">LOGIN</div></a>
        <?php endif; ?>
      </div>
    </div>
