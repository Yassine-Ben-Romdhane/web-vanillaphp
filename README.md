# Carthage Eagles

A fan website for the Tunisian national football team built with plain PHP and CSS — no frameworks, no Composer, no front-end build tools. The goal was to apply every concept from the PHP course directly: PDO, sessions, forms, OOP-style auth helpers, and prepared statements.

---

## What the site does

| Page | URL | Description |
|---|---|---|
| Home | `index.php` | History of the national team with scroll-reveal sections |
| Team | `team.php` | Full squad with position filter, staff section, and squad stats |
| Book of Legends | `book.php` | Historical timeline + legendary players |
| Store | `store.php` | Merchandise shop with a cart sidebar (localStorage-based) |
| Login / Register | `login.php` | Single page with two tabs, CSRF protected |
| Checkout | `checkout.php` | Order summary + confirm button (requires login) |
| Order Confirmed | `order_confirmation.php` | Receipt page shown after a successful order |

---

## File structure

```
/
├── auth.php                # Session helpers (is_logged_in, csrf_token, etc.)
├── db.php                  # PDO connection (sets $pdo or null on failure)
├── index.php               # Home page
├── team.php                # Squad page
├── book.php                # Book of legends page
├── store.php               # Store page
├── login.php               # Login + register UI (one page, two tabs)
├── login_action.php        # POST handler for login form
├── register_action.php     # POST handler for register form
├── logout.php              # Clears session and redirects
├── checkout.php            # Checkout page (validates cart, stores in session)
├── process_order.php       # POST handler that commits the order to the DB
├── order_confirmation.php  # Receipt shown after a successful order
│
├── schema_supabase.sql     # PostgreSQL version (what's actually running)
│
├── style.css               # Main layout and component styles
├── globals.css             # CSS variables and resets
├── styleguide.css          # Typography and color tokens
├── store.css / store.js    # Store-specific styles and cart logic
├── [page].css              # Per-page stylesheets (team, stats, book, etc.)
└── img/                    # All images (hero backgrounds, logos, player photos)
```

---

## Database (PostgreSQL via Supabase)

The connection is in `db.php`. It uses `define()` for the constants and PDO with `ERRMODE_EXCEPTION`. If the connection fails, `$pdo` is set to `null` — every page checks for this and falls back to hardcoded mock data so the site stays browsable without a live DB.

### Tables

**`products`** — merchandise items shown in the store
| Column | Type | Notes |
|---|---|---|
| id | SERIAL PK | |
| name | VARCHAR | Product display name |
| category | TEXT | `jersey`, `training`, or `accessories` |
| price | NUMERIC | In TND |
| year | VARCHAR | Season year, e.g. `2024` |
| badge | VARCHAR | Label like `NEW` or `BESTSELLER` |
| img_class | VARCHAR | CSS class used to apply a background image |

**`players`** — current squad
| Column | Type | Notes |
|---|---|---|
| id | SERIAL PK | |
| number | INT | Jersey number |
| name | VARCHAR | Full name |
| position | TEXT | `GK`, `DEF`, `MID`, or `FWD` |
| club | VARCHAR | Current club |
| age / caps | INT | Age and international appearances |
| is_captain | BOOLEAN | Used to render the captain badge |

**`staff`** — coaching staff shown on the team page


**`legends`** — legendary players shown on the Book of Legends page

**`timeline_events`** — historical milestones on the Book of Legends timeline

**`users`** — registered accounts
| Column | Type | Notes |
|---|---|---|
| id | SERIAL PK | |
| email | VARCHAR UNIQUE | |
| password_hash | VARCHAR | Stored with `password_hash(..., PASSWORD_BCRYPT)` |
| first_name / last_name | VARCHAR | |
| created_at | TIMESTAMPTZ | |

**`orders`** — one row per completed checkout, linked to a user

**`order_items`** — one row per product line in an order. Stores `product_name` and `unit_price` as a snapshot so the receipt is accurate even if the product changes later.

---

## How auth works

All session logic lives in `auth.php`, which is included once by `includes/header.php` — so every page gets the helpers automatically.

```
session_start()  →  is_logged_in()  →  current_user()
                                    →  require_login()   (redirects if not logged in)
                 →  csrf_token()    (generates + stores a token in $_SESSION)
                 →  verify_csrf()   (compares POST token, then deletes it — one-use)
                 →  safe_redirect() (whitelists allowed redirect targets)
```

**Login flow (`login_action.php`):**
1. Check `$_SERVER['REQUEST_METHOD'] === 'POST'`
2. `verify_csrf()` — rejects the request if the token doesn't match
3. Sanitize email with `filter_var(..., FILTER_SANITIZE_EMAIL)`
4. `prepare()` + `execute()` to fetch the user by email (no SQL injection risk)
5. `password_verify($password, $user['password_hash'])`
6. `session_regenerate_id(true)` — prevents session fixation attacks
7. Store `user_id`, `user_first_name`, `user_email` in `$_SESSION`
8. Redirect to the page the user was trying to access

**Register flow (`register_action.php`):**
1. Same CSRF check
2. `strlen($password) < 8` check, `filter_var` email validation
3. Check email uniqueness with a prepared query
4. `password_hash($password, PASSWORD_BCRYPT)` — never store plain passwords
5. `INSERT INTO users ...` inside a `try/catch (PDOException $e)`
6. Log them in immediately (same session steps as login)

**Logout (`logout.php`):**
```php
session_unset();
session_destroy();
```
`session_unset()` clears all `$_SESSION` variables, then `session_destroy()` destroys the session file on the server.

---

## How the store & checkout work

The cart lives entirely in the browser (JavaScript + `localStorage`), so PHP doesn't touch it until the user clicks checkout.

```
store.php (browse + add to cart via JS)
    ↓  POST cart_json
checkout.php (validates cart, stores in $_SESSION)
    ↓  POST csrf_token
process_order.php (writes to DB inside a transaction)
    ↓  redirect
order_confirmation.php (reads order from $_SESSION flash)
```

**Why prices are re-fetched from the DB in `checkout.php`:**
The cart JSON comes from the client, so a user could manually set any price they wanted. `checkout.php` ignores the client-side price and re-fetches all prices from the `products` table using a prepared statement. The validated cart is then stored in `$_SESSION['pending_cart']` so `process_order.php` never has to trust the client again.

**The placeholder trick (`checkout.php` line ~20):**
```php
$placeholders = implode(',', array_fill(0, count($raw_cart), '?'));
$stmt = $pdo->prepare("SELECT ... WHERE name IN ($placeholders)");
```
`array_fill` creates an array of `?` marks — one per item. `implode` joins them with commas. This is how you safely do an `IN (...)` query with PDO when you don't know the count at write-time.

**`process_order.php` uses a transaction:**
```php
$pdo->beginTransaction();
// INSERT INTO orders ...
// INSERT INTO order_items ... (loop)
$pdo->commit();
```
If any insert fails, `rollBack()` is called in the `catch`. This guarantees you never get an order row without its items, or items without an order row.

---

## Security measures used

| Threat | Countermeasure |
|---|---|
| SQL injection | All user input goes through `prepare()` + `execute()` with named placeholders |
| XSS | All output runs through `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` |
| CSRF | Every POST form carries a server-generated token from `csrf_token()` and verified by `verify_csrf()`. Tokens are persistent for the session (not single-use) to keep the implementation simple and avoid token mismatch UX issues. |
| Password leaks | Passwords are hashed with `PASSWORD_BCRYPT` and verified with `password_verify()` |
| Session fixation | `session_regenerate_id(true)` is called on every successful login |
| Open redirect | `safe_redirect()` checks the destination against a hardcoded whitelist |
| Price tampering | Checkout re-fetches all prices from the DB, ignoring client-submitted values |

---

## PHP course concepts used — where to find them

| Concept | Where |
|---|---|
| `define()` constants | `db.php` — DB credentials |
| `$_SERVER['REQUEST_METHOD']` | `login_action.php`, `register_action.php`, `process_order.php` |
| `$_POST`, `$_GET` | All form handlers and `login.php` |
| `$_SESSION` | `auth.php`, `login_action.php`, `checkout.php`, `process_order.php` |
| `session_start()` | `auth.php` |
| `session_unset()` + `session_destroy()` | `logout.php` |
| `isset()`, `empty()` | `auth.php` (`csrf_token`), all form handlers |
| `??` null coalescing | Everywhere — `$_POST['email'] ?? ''` |
| `try` / `catch (PDOException $e)` | `db.php`, `register_action.php`, `process_order.php` |
| PDO `prepare()` + `execute()` | All DB writes and user lookups |
| PDO `query()` + `fetchAll()` | `store.php`, `team.php`, `book.php` |
| `PDO::FETCH_ASSOC` | Set globally in `db.php` options |
| `implode()` | `checkout.php` (building IN placeholders) |
| `array_fill()` | `checkout.php` (building IN placeholders) |
| `array_column()` | `checkout.php` (extracting names from cart for the query) |
| `strlen()` | `register_action.php` (password length check) |
| `trim()` | All form handlers |
| `htmlspecialchars()` | All template files that output user data |
| `filter_var()` | `login_action.php`, `register_action.php` |
| `include` / `require_once` | `header.php`, `footer.php`, `db.php`, `auth.php` |
| Functions + return values | All helpers in `auth.php` |

---

## Running locally (without a database)

```bash
cd /path/to/Carthage-Eagles
php -S localhost:8080
```

Open `http://localhost:8080`. If the Supabase database is unreachable, all display pages fall back to mock data automatically. Login, register, and checkout require a live database.

---

## Running with the database

The project connects to a Supabase (PostgreSQL) instance. The credentials are in `db.php`. To set up a fresh database, run `schema_supabase.sql` in the Supabase SQL Editor — it creates all tables and inserts the initial data.
