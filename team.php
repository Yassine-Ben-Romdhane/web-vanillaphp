<?php
require_once 'db.php';
$page_title = 'Team – Carthage Eagles';
$active_page = 'team';
$extra_css = 'team.css';
include 'includes/header.php';

// Fetch Staff
if (!$pdo) {
  header('Location: error_db.php');
  exit;
}

// Fetch Staff
$stmtStaff = $pdo->query("SELECT * FROM staff");
$staff = $stmtStaff->fetchAll();
$stmtPlayers = $pdo->query("SELECT * FROM players ORDER BY CASE position WHEN 'GK' THEN 1 WHEN 'DEF' THEN 2 WHEN 'MID' THEN 3 WHEN 'FWD' THEN 4 END, number");
$players = $stmtPlayers->fetchAll();

// Calculate Squad Stats
$totalPlayers = count($players);
$avgAge = 0;
$avgCaps = 0;
if ($totalPlayers > 0) {
  $sumAge = array_sum(array_column($players, 'age'));
  $sumCaps = array_sum(array_column($players, 'caps'));
  $avgAge = round($sumAge / $totalPlayers);
  $avgCaps = round($sumCaps / $totalPlayers);
}

// Unique clubs count
$uniqueClubs = count(array_unique(array_column($players, 'club')));
?>


<!-- Hero -->
<div class="team-hero">
  <div class="team-hero__bg"></div>
  <div class="team-hero__content">
    <p class="team-hero__sub">2025 / 26 SEASON</p>
    <h1 class="team-hero__title">THE<br />SQUAD</h1>
    <p class="team-hero__desc">Strength. Unity. Tunisia.</p>
  </div>
  <div class="team-hero__scroll-hint">
    <img src="img/arrow.svg" alt="" />
  </div>
</div>

<!-- Staff -->
<div class="team-section">
  <div class="team-section__header">
    <span class="team-section__label">COACHING STAFF</span>
    <h2 class="team-section__title">THE COACHES</h2>
  </div>
  <div class="staff-grid">
    <?php foreach ($staff as $s): ?>
      <div class="staff-card">
        <div class="staff-card__avatar <?php echo htmlspecialchars($s['img_class']); ?>"></div>
        <div class="staff-card__info">
          <span class="staff-card__role"><?php echo htmlspecialchars($s['role']); ?></span>
          <h3 class="staff-card__name"><?php echo htmlspecialchars($s['name']); ?></h3>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Filter -->
<div class="player-filters">
  <button class="pfilter-btn active" data-pos="all">ALL</button>
  <button class="pfilter-btn" data-pos="GK">GOALKEEPERS</button>
  <button class="pfilter-btn" data-pos="DEF">DEFENDERS</button>
  <button class="pfilter-btn" data-pos="MID">MIDFIELDERS</button>
  <button class="pfilter-btn" data-pos="FWD">FORWARDS</button>
</div>

<!-- Player Grid -->
<div class="player-grid" id="playerGrid">
  <?php foreach ($players as $player):
    $rating = min(99, max(65, 70 + (int) round($player['caps'] * 0.35)));
    ?>
    <div class="player-card <?php echo $player['is_captain'] ? 'player-card--captain' : ''; ?>"
      data-pos="<?php echo htmlspecialchars($player['position']); ?>">

      <!-- Rating + Position + Flag (top-left) -->
      <div class="pcard-top">
        <div class="pcard-rating"><?php echo $rating; ?></div>
        <div class="pcard-position"><?php echo htmlspecialchars($player['position']); ?></div>
      </div>

      <!-- Jersey / Captain badge (top-right) -->
      <?php if ($player['is_captain']): ?>
        <div class="pcard-captain">C</div>
      <?php else: ?>
        <div class="pcard-jersey">#<?php echo htmlspecialchars($player['number']); ?></div>
      <?php endif; ?>

      <!-- Player photo -->
      <div class="pcard-photo <?php echo htmlspecialchars($player['img_class']); ?>"></div>

      <!-- Bottom info strip -->
      <div class="pcard-bottom">
        <div class="pcard-name"><?php echo htmlspecialchars($player['name']); ?></div>
        <div class="pcard-divider">· · ·</div>
        <div class="pcard-stats">
          <div class="pcard-stat">
            <span class="pstat-val"><?php echo $player['age']; ?></span>
            <span class="pstat-lbl">AGE</span>
          </div>
          <div class="pcard-sep">|</div>
          <div class="pcard-stat">
            <span class="pstat-val"><?php echo $player['caps']; ?></span>
            <span class="pstat-lbl">CAPS</span>
          </div>
        </div>
        <div class="pcard-club"><?php echo htmlspecialchars($player['club']); ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Squad Stats Banner -->
<div class="squad-stats">
  <div class="squad-stat">
    <span class="squad-stat__val"><?php echo $totalPlayers; ?></span>
    <span class="squad-stat__label">PLAYERS</span>
  </div>
  <div class="squad-stat">
    <span class="squad-stat__val"><?php echo $uniqueClubs; ?></span>
    <span class="squad-stat__label">CLUBS</span>
  </div>
  <div class="squad-stat">
    <span class="squad-stat__val"><?php echo $avgCaps; ?></span>
    <span class="squad-stat__label">AVG. CAPS</span>
  </div>
  <div class="squad-stat">
    <span class="squad-stat__val"><?php echo $avgAge; ?></span>
    <span class="squad-stat__label">AVG. AGE</span>
  </div>
</div>

<script>
  // Position filter
  document.querySelectorAll('.pfilter-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('.pfilter-btn').forEach(function (b) { b.classList.remove('active'); });
      this.classList.add('active');
      var pos = this.getAttribute('data-pos');
      document.querySelectorAll('.player-card').forEach(function (card) {
        if (pos === 'all' || card.getAttribute('data-pos') === pos) {
          card.classList.remove('hidden');
        } else {
          card.classList.add('hidden');
        }
      });
    });
  });

  // Scroll Reveal
  var observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('reveal');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.player-card, .staff-card').forEach(card => {
    observer.observe(card);
  });
</script>

<?php include 'includes/footer.php'; ?>