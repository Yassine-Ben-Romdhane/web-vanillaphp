-- ============================================================
-- Carthage Eagles — Supabase (PostgreSQL) Schema
-- Run this in Supabase Dashboard → SQL Editor
-- ============================================================

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id      SERIAL PRIMARY KEY,
    name     VARCHAR(255) NOT NULL,
    category TEXT NOT NULL CHECK (category IN ('jersey', 'training', 'accessories')),
    price    NUMERIC(10, 2) NOT NULL,
    year     VARCHAR(10),
    badge    VARCHAR(50),
    img_class VARCHAR(100)
);

INSERT INTO products (name, category, price, year, badge, img_class) VALUES
('Home Kit 2024/25',       'jersey',      89.99, '2024', 'BESTSELLER', 'product-img--home-jersey'),
('Away Kit 2024/25',       'jersey',      89.99, '2024', NULL,         'product-img--away-jersey'),
('Training Top',           'training',    59.99, '2024', NULL,         'product-img--training'),
('Eagles Cap',             'accessories', 29.99, NULL,   NULL,         'product-img--cap'),
('2026 World Cup Scarf',   'accessories', 24.99, '2026', 'NEW',        'product-img--scarf'),
('Training Shorts',        'training',    39.99, '2024', NULL,         'product-img--shorts');


-- Players Table
CREATE TABLE IF NOT EXISTS players (
    id         SERIAL PRIMARY KEY,
    number     INT,
    name       VARCHAR(255) NOT NULL,
    position   TEXT NOT NULL CHECK (position IN ('GK', 'DEF', 'MID', 'FWD')),
    club       VARCHAR(255),
    age        INT,
    caps       INT,
    img_class  VARCHAR(100),
    is_captain BOOLEAN DEFAULT FALSE
);

INSERT INTO players (number, name, position, club, age, caps, img_class, is_captain) VALUES
(1,  'Aymen Dahmen',           'GK',  'Burnley FC',          28, 41,  'player-img--gk1-dahmen',  FALSE),
(16, 'Farouk Ben Mustapha',    'GK',  'Espérance ST',        35, 74,  'player-img--gk2-ben-mustapha',  FALSE),
(23, 'Moez Ben Cherifia',      'GK',  'CA Bizertin',         26, 7,   'player-img--gk3-ben-cherifia',  FALSE),
(2,  'Mohamed Dräger',         'DEF', 'Sporting CP',         27, 55,  'player-img--def1-talbi', FALSE), -- Placeholder mapping
(3,  'Montassar Talbi',        'DEF', 'Lorient FC',          24, 32,  'player-img--def1-talbi', FALSE),
(5,  'Nader Ghandri',          'DEF', 'AEK Athens',          29, 43,  'player-img--def3-rekik', FALSE), -- Mapping to existing rekik
(6,  'Dylan Bronn',            'DEF', 'Salernitana',         28, 37,  'player-img--def2-bronn', FALSE),
(12, 'Wajdi Kechrida',         'DEF', 'Espérance ST',        30, 61,  'player-img--def4-arous', FALSE), -- Placeholder mapping
(4,  'Ellyes Skhiri',          'MID', 'Eintracht Frankfurt', 29, 71,  'player-img--mid1-skhiri', FALSE),
(8,  'Aïssa Laïdouni',         'MID', 'Union Berlin',        26, 51,  'player-img--mid2-laidouni', FALSE),
(14, 'Hannibal Mejbri',        'MID', 'Sevilla FC',          21, 22,  'player-img--mid3-mejbri', FALSE),
(17, 'Ferjani Sassi',          'MID', 'Al-Duhail SC',        33, 83,  'player-img--mid4-khedira', FALSE),
(7,  'Wahbi Khazri',           'FWD', 'Al-Qadsiah',          33, 97,  'player-img--fwd1-khazri', TRUE),
(9,  'Youssef Msakni',         'FWD', 'Al-Arabi SC',         34, 113, 'player-img--fwd2-msakni', FALSE),
(10, 'Seifeddine Jaziri',      'FWD', 'Antalyaspor',         31, 59,  'player-img--fwd3-saad', FALSE),
(11, 'Taha Yassine Khenissi',  'FWD', 'CS Sfaxien',          32, 68,  'player-img--fwd4-khenissi', FALSE);


-- Stats Table
CREATE TABLE IF NOT EXISTS stats (
    id    SERIAL PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    value VARCHAR(50)  NOT NULL,
    trend VARCHAR(50)
);

INSERT INTO stats (label, value, trend) VALUES
('FIFA RANKING',    '25',    '↑ 3'),
('TOTAL GOALS',     '1,241', NULL),
('WC APPEARANCES',  '6',     NULL),
('WIN RATIO',       '44.8%', NULL);


-- Honors Table
CREATE TABLE IF NOT EXISTS honors (
    id           SERIAL PRIMARY KEY,
    year         INT,
    name         VARCHAR(255),
    description  TEXT,
    trophy_type  TEXT CHECK (trophy_type IN ('gold', 'silver', 'bronze'))
);

INSERT INTO honors (year, name, description, trophy_type) VALUES
(2004, 'AFRICAN CHAMPIONS', 'Winners of AFCON hosted in Tunisia.',          'gold'),
(1996, 'AFCON FINALISTS',   'Runners up in Johannesburg.',                  'silver'),
(2011, 'CHAN CHAMPIONS',    'African Nations Championship winners.',         'bronze');


-- Legends Table
CREATE TABLE IF NOT EXISTS legends (
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(255),
    role        VARCHAR(255),
    description TEXT,
    img_class   VARCHAR(100)
);

INSERT INTO legends (name, role, description, img_class) VALUES
('Tarak Dhiab',    'THE MAESTRO', 'Tunisia''s only Ballon d''Or winner (1977) and the engine behind the 1978 success.',          'legend-img--dhiab'),
('Radhi Jaïdi',    'THE WALL',    'The most capped player in history and a pillar of the 2004 Championship winning squad.',      'legend-img--jaidi'),
('Youssef Msakni', 'THE MAGICIAN','A modern icon whose skill and goals have carried the team across a decade of competition.',   'legend-img--msakni');


-- Staff Table
CREATE TABLE IF NOT EXISTS staff (
    id        SERIAL PRIMARY KEY,
    name      VARCHAR(255) NOT NULL,
    role      VARCHAR(255) NOT NULL,
    img_class VARCHAR(100)
);

INSERT INTO staff (name, role, img_class) VALUES
('Montassar Losfar', 'HEAD COACH',        'staff-avatar--coach1'),
('Sofien Losfar',    'ASSISTANT COACH',   'staff-avatar--coach2'),
('Ali Zitouni',      'GOALKEEPER COACH',  'staff-avatar--coach3');


-- Timeline Events Table
CREATE TABLE IF NOT EXISTS timeline_events (
    id           SERIAL PRIMARY KEY,
    year         INT  NOT NULL,
    title        VARCHAR(255) NOT NULL,
    description  TEXT,
    reveal_side  TEXT DEFAULT 'left' CHECK (reveal_side IN ('left', 'right'))
);

INSERT INTO timeline_events (year, title, description, reveal_side) VALUES
(1956, 'The Foundation',        'Following Tunisia''s independence, the FTF was founded, marking the beginning of our official footballing journey.',         'left'),
(1978, 'The Argentinian Miracle','Tunisia became the first African nation to win a World Cup match, defeating Mexico 3-1. A global statement.',               'right'),
(1996, 'The Resurgence',        'After years of building, the Eagles reached the AFCON final in South Africa, announcing their return to greatness.',         'left'),
(2004, 'Continental Glory',     'Winning the AFCON on home soil against Morocco. The absolute peak of Tunisian sporting history.',                            'right'),
(2022, 'The Giant Slays',       'A legendary victory against reigning World Champions France in Qatar. Pride restored on the world stage.',                   'left');


-- ============================================================
-- Auth & Commerce Tables
-- ============================================================

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id            SERIAL PRIMARY KEY,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name    VARCHAR(100) NOT NULL,
    last_name     VARCHAR(100) NOT NULL,
    created_at    TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- Orders Table (one row per completed checkout)
CREATE TABLE IF NOT EXISTS orders (
    id           SERIAL PRIMARY KEY,
    user_id      INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    total_amount NUMERIC(10, 2) NOT NULL,
    status       TEXT NOT NULL DEFAULT 'confirmed'
                     CHECK (status IN ('confirmed', 'processing', 'shipped', 'delivered')),
    created_at   TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- Order Items Table (one row per product line in an order)
CREATE TABLE IF NOT EXISTS order_items (
    id           SERIAL PRIMARY KEY,
    order_id     INTEGER NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    product_id   INTEGER REFERENCES products(id) ON DELETE SET NULL,
    product_name VARCHAR(255) NOT NULL,   -- snapshot: name at time of purchase
    unit_price   NUMERIC(10, 2) NOT NULL, -- snapshot: price at time of purchase
    quantity     INTEGER NOT NULL CHECK (quantity > 0)
);
