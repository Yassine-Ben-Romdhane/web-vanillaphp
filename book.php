<?php
require_once 'db.php';
require_once 'auth.php';
$page_title = 'Book Tickets – Carthage Eagles';
$active_page = 'book';
$extra_css = 'book.css';
include 'includes/header.php';

// Pre-fill from session if logged in
$u = is_logged_in() ? current_user() : [];
$prefill_first = htmlspecialchars($u['first_name'] ?? '');
$prefill_last  = htmlspecialchars($u['last_name']  ?? '');
$prefill_email = htmlspecialchars($u['email']      ?? '');
$prefill_phone = htmlspecialchars($u['phone']      ?? '');

// Handle booking submission
$booking_success = false;
$booking_error   = '';
$booking_ref     = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['match_id'])) {
    $match_id    = (int)$_POST['match_id'];
    $first_name  = trim($_POST['first_name']  ?? '');
    $last_name   = trim($_POST['last_name']   ?? '');
    $email       = trim($_POST['email']       ?? '');
    $phone       = trim($_POST['phone']       ?? '');
    $num_tickets = max(1, min(10, (int)($_POST['num_tickets'] ?? 1)));

    if (!$first_name || !$last_name || !$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $booking_error = 'Please fill in all required fields with a valid email.';
    } elseif ($pdo) {
        // Get match price & check seats
        $mStmt = $pdo->prepare("SELECT * FROM matches WHERE id = ?");
        $mStmt->execute([$match_id]);
        $match = $mStmt->fetch();

        if (!$match) {
            $booking_error = 'Match not found.';
        } elseif ($match['available_seats'] < $num_tickets) {
            $booking_error = 'Not enough seats available.';
        } else {
            $total     = $match['price_per_ticket'] * $num_tickets;
            $ref       = 'CE-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            $user_id   = is_logged_in() ? current_user()['id'] : null;

            try {
                $bStmt = $pdo->prepare("INSERT INTO bookings
                    (match_id, user_id, first_name, last_name, email, phone, num_tickets, total_price, booking_ref)
                    VALUES (?,?,?,?,?,?,?,?,?)");
                $bStmt->execute([$match_id, $user_id, $first_name, $last_name, $email, $phone, $num_tickets, $total, $ref]);

                // Decrease available seats
                $pdo->prepare("UPDATE matches SET available_seats = available_seats - ? WHERE id = ?")->execute([$num_tickets, $match_id]);

                $booking_success = true;
                $booking_ref     = $ref;
            } catch (Exception $e) {
                $booking_error = 'Booking failed. Please try again.';
            }
        }
    } else {
        $booking_error = 'Database unavailable. Please try again later.';
    }
}

// Fetch upcoming matches
$matches = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT * FROM matches WHERE match_date > NOW() ORDER BY match_date ASC");
    $matches = $stmt->fetchAll();
}

if (!$matches) {
    // Fallback fixtures
    $matches = [
        ['id'=>1,'home_team'=>'Tunisia','away_team'=>'Ivory Coast',  'match_date'=>'2025-06-06 20:00:00','venue'=>'Stade de Radès, Tunis',         'competition'=>'AFCON Qualifier','available_seats'=>1200,'price_per_ticket'=>30.00,'opponent_flag'=>'🇨🇮'],
        ['id'=>2,'home_team'=>'Tunisia','away_team'=>'Zambia',        'match_date'=>'2025-06-10 19:00:00','venue'=>'Stade de Radès, Tunis',         'competition'=>'AFCON Qualifier','available_seats'=>1500,'price_per_ticket'=>25.00,'opponent_flag'=>'🇿🇲'],
        ['id'=>3,'home_team'=>'Tunisia','away_team'=>'Senegal',       'match_date'=>'2025-09-05 20:00:00','venue'=>'Stade Olympique de Sousse',     'competition'=>'Friendly',       'available_seats'=>900, 'price_per_ticket'=>20.00,'opponent_flag'=>'🇸🇳'],
        ['id'=>4,'home_team'=>'Tunisia','away_team'=>'Morocco',       'match_date'=>'2025-09-09 20:00:00','venue'=>'Stade de Radès, Tunis',         'competition'=>'Arab Cup Qual.', 'available_seats'=>2000,'price_per_ticket'=>35.00,'opponent_flag'=>'🇲🇦'],
        ['id'=>5,'home_team'=>'Tunisia','away_team'=>'Egypt',         'match_date'=>'2025-10-10 19:30:00','venue'=>'Stade Hamdi Agrebi, Radès',     'competition'=>'AFCON Qualifier','available_seats'=>1800,'price_per_ticket'=>30.00,'opponent_flag'=>'🇪🇬'],
        ['id'=>6,'home_team'=>'Tunisia','away_team'=>'Algeria',       'match_date'=>'2025-11-14 20:00:00','venue'=>'Stade de Radès, Tunis',         'competition'=>'Friendly',       'available_seats'=>2500,'price_per_ticket'=>40.00,'opponent_flag'=>'🇩🇿'],
    ];
}
?>

  <!-- Hero -->
  <div class="book-hero">
    <div class="book-hero__bg"></div>
    <div class="book-hero__content">
      <p class="book-hero__sub">FIFA WORLD CUP 2026</p>
      <h1 class="book-hero__title">BOOK<br/>YOUR SEAT</h1>
      <p class="book-hero__desc">The Eagles are at the World Cup. Be there.</p>
    </div>
    <div class="book-hero__scroll-hint">↓</div>
  </div>

  <!-- Booking Success Banner -->
  <?php if ($booking_success): ?>
  <div class="booking-success">
    <div class="booking-success__inner">
      <div class="booking-success__icon">✓</div>
      <div>
        <h3 class="booking-success__title">Booking Confirmed!</h3>
        <p class="booking-success__msg">Your reference: <strong><?php echo htmlspecialchars($booking_ref); ?></strong> — A confirmation will be sent to your email.</p>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Fixtures Section -->
  <div class="fixtures-section">
    <div class="fixtures-header">
      <span class="fixtures-label">UPCOMING FIXTURES</span>
      <h2 class="fixtures-title">CHOOSE YOUR MATCH</h2>
    </div>

    <div class="fixtures-grid">
      <?php foreach ($matches as $m):
        $date    = new DateTime($m['match_date']);
        $soldOut = $m['available_seats'] <= 0;
        $low     = !$soldOut && $m['available_seats'] < 100;
      ?>
      <div class="fixture-card <?php echo $soldOut ? 'fixture-card--sold-out' : ''; ?>">

        <!-- Competition badge -->
        <div class="fixture-card__badge"><?php echo htmlspecialchars($m['competition']); ?></div>

        <!-- Date block -->
        <div class="fixture-card__date">
          <span class="fdate-day"><?php echo $date->format('d'); ?></span>
          <span class="fdate-mon"><?php echo strtoupper($date->format('M')); ?></span>
          <span class="fdate-yr"><?php echo $date->format('Y'); ?></span>
        </div>

        <!-- Matchup -->
        <div class="fixture-card__matchup">
          <div class="fteam fteam--home">
            <span class="fteam__flag">🇹🇳</span>
            <span class="fteam__name">TUNISIA</span>
          </div>
          <div class="fmatch-vs">VS</div>
          <div class="fteam fteam--away">
            <span class="fteam__flag"><?php echo $m['opponent_flag']; ?></span>
            <span class="fteam__name"><?php echo htmlspecialchars(strtoupper($m['away_team'])); ?></span>
          </div>
        </div>

        <!-- Info row -->
        <div class="fixture-card__info">
          <div class="finfo-item">
            <span class="finfo-icon">🕐</span>
            <span><?php echo $date->format('H:i'); ?></span>
          </div>
          <div class="finfo-item">
            <span class="finfo-icon">📍</span>
            <span><?php echo htmlspecialchars($m['venue']); ?></span>
          </div>
          <div class="finfo-item">
            <span class="finfo-icon">🎫</span>
            <span><?php echo $soldOut ? 'SOLD OUT' : ($low ? 'Only ' . $m['available_seats'] . ' left!' : $m['available_seats'] . ' seats'); ?></span>
          </div>
        </div>

        <!-- Price + CTA -->
        <div class="fixture-card__footer">
          <div class="fixture-card__price">
            <span class="fprice-from">FROM</span>
            <span class="fprice-val"><?php echo number_format($m['price_per_ticket'], 0); ?> TND</span>
          </div>
          <?php if (!$soldOut): ?>
          <button class="book-btn" onclick="openBooking(<?php echo $m['id']; ?>, '<?php echo addslashes($m['away_team']); ?>', '<?php echo $date->format('d M Y · H:i'); ?>', <?php echo $m['price_per_ticket']; ?>, <?php echo $m['available_seats']; ?>)">
            BOOK NOW
          </button>
          <?php else: ?>
          <button class="book-btn book-btn--disabled" disabled>SOLD OUT</button>
          <?php endif; ?>
        </div>

        <?php if ($low && !$soldOut): ?>
        <div class="fixture-card__low-stock">⚡ Almost Full</div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Booking Modal -->
  <div class="booking-modal" id="bookingModal">
    <div class="booking-modal__backdrop" onclick="closeBooking()"></div>
    <div class="booking-modal__box">
      <button class="booking-modal__close" onclick="closeBooking()">✕</button>

      <div class="bmodal-head">
        <p class="bmodal-head__label">BOOKING</p>
        <h3 class="bmodal-head__match" id="modalMatchTitle">Tunisia vs ...</h3>
        <p class="bmodal-head__sub" id="modalMatchSub"></p>
      </div>

      <?php if ($booking_error): ?>
      <div class="bmodal-error"><?php echo htmlspecialchars($booking_error); ?></div>
      <?php endif; ?>

      <form class="bmodal-form" method="POST" action="book.php">
        <input type="hidden" name="match_id" id="modalMatchId" value="">

        <?php if (is_logged_in()): ?>
        <div class="bmodal-autofill">
          <span class="bmodal-autofill__icon">✓</span>
          Filled from your account — <a href="logout.php" class="bmodal-autofill__link">not you?</a>
        </div>
        <?php endif; ?>

        <div class="bform-row">
          <div class="bform-group">
            <label class="bform-label">FIRST NAME <span>*</span></label>
            <input class="bform-input <?php echo $prefill_first ? 'bform-input--prefilled' : ''; ?>"
                   type="text" name="first_name" required placeholder="Aymen"
                   value="<?php echo $prefill_first ?: htmlspecialchars($_POST['first_name'] ?? ''); ?>">
          </div>
          <div class="bform-group">
            <label class="bform-label">LAST NAME <span>*</span></label>
            <input class="bform-input <?php echo $prefill_last ? 'bform-input--prefilled' : ''; ?>"
                   type="text" name="last_name" required placeholder="Ben Ali"
                   value="<?php echo $prefill_last ?: htmlspecialchars($_POST['last_name'] ?? ''); ?>">
          </div>
        </div>

        <div class="bform-group">
          <label class="bform-label">EMAIL ADDRESS <span>*</span></label>
          <input class="bform-input <?php echo $prefill_email ? 'bform-input--prefilled' : ''; ?>"
                 type="email" name="email" required placeholder="you@example.com"
                 value="<?php echo $prefill_email ?: htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>

        <div class="bform-group">
          <label class="bform-label">PHONE (optional)</label>
          <input class="bform-input <?php echo $prefill_phone ? 'bform-input--prefilled' : ''; ?>"
                 type="tel" name="phone" placeholder="+216 XX XXX XXX"
                 value="<?php echo $prefill_phone ?: htmlspecialchars($_POST['phone'] ?? ''); ?>">
        </div>

        <div class="bform-group">
          <label class="bform-label">NUMBER OF TICKETS <span>*</span></label>
          <div class="bticket-picker">
            <button type="button" class="bticket-btn" onclick="changeTickets(-1)">−</button>
            <span class="bticket-count" id="ticketCount">1</span>
            <button type="button" class="bticket-btn" onclick="changeTickets(1)">+</button>
          </div>
          <input type="hidden" name="num_tickets" id="numTicketsInput" value="1">
        </div>

        <div class="bmodal-total">
          <span class="bmodal-total__label">TOTAL</span>
          <span class="bmodal-total__val" id="modalTotal">0 TND</span>
        </div>

        <button class="bmodal-submit" type="submit">CONFIRM BOOKING →</button>

        <p class="bmodal-note">Free cancellation up to 48h before kick-off · No hidden fees</p>
      </form>
    </div>
  </div>

  <!-- Info Strip -->
  <div class="book-info-strip">
    <div class="binfo-item">
      <span class="binfo-icon">🔒</span>
      <span class="binfo-text">Secure Payment</span>
    </div>
    <div class="binfo-item">
      <span class="binfo-icon">📧</span>
      <span class="binfo-text">E-Ticket by Email</span>
    </div>
    <div class="binfo-item">
      <span class="binfo-icon">↩️</span>
      <span class="binfo-text">Free Cancellation 48h Prior</span>
    </div>
    <div class="binfo-item">
      <span class="binfo-icon">🎫</span>
      <span class="binfo-text">Up to 10 Tickets / Booking</span>
    </div>
  </div>

<script>
var currentPrice = 0;
var maxTickets   = 10;
var tickets      = 1;

function openBooking(matchId, opponent, dateStr, price, seats) {
  currentPrice = price;
  maxTickets   = Math.min(10, seats);
  tickets      = 1;

  document.getElementById('modalMatchId').value    = matchId;
  document.getElementById('modalMatchTitle').textContent = 'Tunisia vs ' + opponent;
  document.getElementById('modalMatchSub').textContent   = dateStr;
  document.getElementById('ticketCount').textContent     = '1';
  document.getElementById('numTicketsInput').value       = '1';
  updateTotal();

  document.getElementById('bookingModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeBooking() {
  document.getElementById('bookingModal').classList.remove('open');
  document.body.style.overflow = '';
}

function changeTickets(delta) {
  tickets = Math.max(1, Math.min(maxTickets, tickets + delta));
  document.getElementById('ticketCount').textContent  = tickets;
  document.getElementById('numTicketsInput').value    = tickets;
  updateTotal();
}

function updateTotal() {
  document.getElementById('modalTotal').textContent = (tickets * currentPrice).toFixed(0) + ' TND';
}

// Auto-open modal if there was a booking error
<?php if ($booking_error && isset($_POST['match_id'])): ?>
document.addEventListener('DOMContentLoaded', function() {
  openBooking(
    <?php echo (int)$_POST['match_id']; ?>,
    'opponent',
    '',
    <?php echo (float)($_POST['price'] ?? 0); ?>,
    10
  );
  document.getElementById('ticketCount').textContent = '<?php echo (int)($_POST['num_tickets'] ?? 1); ?>';
  document.getElementById('numTicketsInput').value   = '<?php echo (int)($_POST['num_tickets'] ?? 1); ?>';
});
<?php endif; ?>

// Keyboard close
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeBooking();
});
</script>

<?php include 'includes/footer.php'; ?>
