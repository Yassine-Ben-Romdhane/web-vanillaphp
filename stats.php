<?php
require_once 'db.php';
$page_title = 'Stats – Carthage Eagles';
$active_page = 'stats';
$extra_css = 'stats.css';
include 'includes/header.php';

// Fetch Quick Stats
if ($pdo) {
    $stmtStats = $pdo->query("SELECT * FROM stats");
    $statsData = $stmtStats->fetchAll();
    $stmtHonors = $pdo->query("SELECT * FROM honors ORDER BY year DESC");
    $honors = $stmtHonors->fetchAll();
} else {
    $statsData = [
        ['value' => '28th', 'label' => 'FIFA Ranking',           'trend' => '▲ 4'],
        ['value' => '113',  'label' => 'All-Time Caps Record',   'trend' => ''],
        ['value' => '36',   'label' => 'All-Time Goals Record',  'trend' => ''],
        ['value' => '9',    'label' => 'World Cup Appearances',  'trend' => '★'],
    ];
    $honors = [
        ['year' => 2004, 'name' => 'Africa Cup of Nations', 'description' => 'First and only AFCON title, hosted on home soil.', 'trophy_type' => 'gold'],
        ['year' => 1996, 'name' => 'AFCON Runner-Up',        'description' => 'Finalist at the 1996 Africa Cup of Nations in South Africa.', 'trophy_type' => 'silver'],
        ['year' => 2004, 'name' => 'Arab Cup',               'description' => 'Winners of the Arab Nations Cup 2004.', 'trophy_type' => 'gold'],
        ['year' => 1994, 'name' => 'Arab Cup',               'description' => 'Winners of the Arab Nations Cup 1994.', 'trophy_type' => 'gold'],
    ];
}
?>


  <!-- Hero -->
  <div class="stats-hero">
    <div class="stats-hero__bg"></div>
    <div class="stats-hero__content">
      <p class="stats-hero__sub">FIFA WORLD CUP 2026</p>
      <h1 class="stats-hero__title">RANKING &<br/>RECORDS</h1>
      <p class="stats-hero__desc">Nine World Cups. One nation. One dream.</p>
    </div>
  </div>

  <!-- Quick Stats -->
  <div class="quick-stats">
    <?php foreach ($statsData as $s): ?>
    <div class="stat-box">
      <span class="stat-box__val"><?php echo htmlspecialchars($s['value']); ?></span>
      <span class="stat-box__label"><?php echo htmlspecialchars($s['label']); ?></span>
      <?php if ($s['trend']): ?>
      <div class="stat-box__trend up"><?php echo htmlspecialchars($s['trend']); ?></div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Main Content -->
  <div class="stats-container">
    
    <!-- Recent Form (Keep static for now or add table) -->
    <div class="stats-section">
      <div class="stats-section__header">
        <h2 class="stats-section__title">RECENT FORM</h2>
        <p class="stats-section__info">LAST 5 MATCHES</p>
      </div>
      <div class="form-row">
        <div class="form-pill win">W</div>
        <div class="form-pill draw">D</div>
        <div class="form-pill win">W</div>
        <div class="form-pill win">W</div>
        <div class="form-pill loss">L</div>
      </div>
      <div class="last-match">
        <div class="match-card">
          <span class="match-card__tag">PRE-WC FRIENDLY</span>
          <div class="match-card__score">
            <span class="team">TUNISIA</span>
            <span class="score">2 - 1</span>
            <span class="team">CROATIA</span>
          </div>
          <span class="match-card__date">JUNE 4, 2026</span>
        </div>
      </div>
    </div>

    <!-- Career Records -->
    <div class="stats-section">
      <div class="stats-section__header">
        <h2 class="stats-section__title">CAREER RECORDS</h2>
      </div>
      <div class="records-grid">
        <div class="record-table-wrap">
          <h3 class="record-table-title">TOP SCORERS</h3>
          <table class="record-table">
            <thead>
              <tr><th>PLAYER</th><th>GOALS</th></tr>
            </thead>
            <tbody>
              <tr><td>Issam Jemaa</td><td>36</td></tr>
              <tr><td>Wahbi Khazri</td><td>25</td></tr>
              <tr><td>Dos Santos</td><td>22</td></tr>
              <tr><td>Ziad Jaziri</td><td>14</td></tr>
            </tbody>
          </table>
        </div>
        <div class="record-table-wrap">
          <h3 class="record-table-title">MOST CAPS</h3>
          <table class="record-table">
            <thead>
              <tr><th>PLAYER</th><th>CAPS</th></tr>
            </thead>
            <tbody>
              <tr><td>Radhi Jaïdi</td><td>105</td></tr>
              <tr><td>Chokri El Ouaer</td><td>97</td></tr>
              <tr><td>Khaled Badra</td><td>96</td></tr>
              <tr><td>Youssef Msakni</td><td>113</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Honors -->
    <div class="stats-section">
      <div class="stats-section__header">
        <h2 class="stats-section__title">MAJOR HONORS</h2>
      </div>
      <div class="honors-grid">
        <?php foreach ($honors as $h): ?>
        <div class="honor-card">
          <div class="honor-card__icon trophy--<?php echo $h['trophy_type']; ?>"></div>
          <div class="honor-card__info">
            <span class="honor-card__year"><?php echo $h['year']; ?></span>
            <h3 class="honor-card__name"><?php echo htmlspecialchars($h['name']); ?></h3>
            <p class="honor-card__desc"><?php echo htmlspecialchars($h['description']); ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>

<script>
  var observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.stat-box, .stats-section, .honor-card').forEach(el => {
    observer.observe(el);
  });
</script>

<?php include 'includes/footer.php'; ?>
