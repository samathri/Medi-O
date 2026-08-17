-- =====================================================================
-- Medi-O v2 — Database schema
-- Secure Online Pharmacy with QR-Based Prescription Verification
-- Target: MySQL / MariaDB 10.4+ (XAMPP)
-- Creates database `medio` (the old `medi-o` database is left untouched).
-- Import:  mysql -u root < database/schema.sql
-- Then:    mysql -u root < database/migrate-from-old.sql   (copies products)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `medio` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `medio`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS whatsapp_outbox, notifications, collections, medication_logs,
  medication_times, medications, chat_messages, chat_conversations, payments,
  order_items, orders, prescriptions, cart_items, products, categories,
  contact_messages, settings, users;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- Users: email OR phone can be the login identifier; google_id for OAuth.
-- qr_token backs each user's permanent personal collection QR code.
-- ---------------------------------------------------------------------
CREATE TABLE users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(120)  NOT NULL,
  email         VARCHAR(190)  NULL,
  phone         VARCHAR(20)   NULL,
  password_hash VARCHAR(255)  NULL,
  google_id     VARCHAR(64)   NULL,
  avatar        VARCHAR(500)  NULL,
  address       TEXT          NULL,
  role          ENUM('customer','pharmacist','admin') NOT NULL DEFAULT 'customer',
  qr_token      CHAR(40)      NOT NULL,
  whatsapp_opt_in TINYINT(1)  NOT NULL DEFAULT 1,
  reset_token   VARCHAR(64)   NULL,
  reset_expires DATETIME      NULL,
  created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_users_email (email),
  UNIQUE KEY uq_users_phone (phone),
  UNIQUE KEY uq_users_google (google_id),
  UNIQUE KEY uq_users_qr (qr_token)
) ENGINE=InnoDB;

CREATE TABLE categories (
  id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name  VARCHAR(100) NOT NULL,
  icon  VARCHAR(60)  NOT NULL DEFAULT 'bi-capsule',
  UNIQUE KEY uq_categories_name (name)
) ENGINE=InnoDB;

CREATE TABLE products (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id  INT UNSIGNED NULL,
  name         VARCHAR(255) NOT NULL,
  description  TEXT NULL,
  price        DECIMAL(10,2) NOT NULL DEFAULT 0,
  stock        INT NOT NULL DEFAULT 0,
  image_path   VARCHAR(500) NULL,
  rating       DECIMAL(3,2) NOT NULL DEFAULT 0,
  requires_prescription TINYINT(1) NOT NULL DEFAULT 0,
  is_active    TINYINT(1) NOT NULL DEFAULT 1,
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_products_category (category_id),
  KEY idx_products_active (is_active),
  FULLTEXT KEY ft_products_name (name, description),
  CONSTRAINT fk_products_category FOREIGN KEY (category_id)
    REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE cart_items (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  quantity   INT NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_cart_user_product (user_id, product_id),
  CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_cart_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Prescriptions: uploaded by customer, reviewed by pharmacist.
-- verify_token backs the scannable QR verification link.
-- ---------------------------------------------------------------------
CREATE TABLE prescriptions (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       INT UNSIGNED NOT NULL,
  file_path     VARCHAR(500) NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  notes         TEXT NULL,
  status        ENUM('pending','approved','rejected','ready','collected') NOT NULL DEFAULT 'pending',
  pharmacist_id INT UNSIGNED NULL,
  review_note   TEXT NULL,
  verify_token  CHAR(40) NOT NULL,
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  reviewed_at   DATETIME NULL,
  UNIQUE KEY uq_prescriptions_token (verify_token),
  KEY idx_prescriptions_status (status),
  CONSTRAINT fk_rx_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_rx_pharmacist FOREIGN KEY (pharmacist_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE orders (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_no        VARCHAR(20) NOT NULL,
  user_id         INT UNSIGNED NOT NULL,
  prescription_id INT UNSIGNED NULL,
  subtotal        DECIMAL(10,2) NOT NULL DEFAULT 0,
  shipping_fee    DECIMAL(10,2) NOT NULL DEFAULT 0,
  total           DECIMAL(10,2) NOT NULL DEFAULT 0,
  payment_method  ENUM('payhere','cash') NOT NULL DEFAULT 'cash',
  payment_status  ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  status          ENUM('placed','processing','ready','collected','completed','cancelled') NOT NULL DEFAULT 'placed',
  delivery_method ENUM('pickup','delivery') NOT NULL DEFAULT 'pickup',
  address         TEXT NULL,
  note            TEXT NULL,
  qr_token        CHAR(40) NOT NULL,
  created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_orders_no (order_no),
  UNIQUE KEY uq_orders_qr (qr_token),
  KEY idx_orders_user (user_id),
  KEY idx_orders_status (status),
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_orders_rx FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id     INT UNSIGNED NOT NULL,
  product_id   INT UNSIGNED NULL,
  product_name VARCHAR(255) NOT NULL,
  price        DECIMAL(10,2) NOT NULL,
  quantity     INT NOT NULL,
  CONSTRAINT fk_oi_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_oi_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE payments (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id    INT UNSIGNED NOT NULL,
  method      VARCHAR(20) NOT NULL,
  amount      DECIMAL(10,2) NOT NULL,
  currency    CHAR(3) NOT NULL DEFAULT 'LKR',
  status      VARCHAR(20) NOT NULL,
  gateway_ref VARCHAR(100) NULL,
  payload     TEXT NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_payments_order (order_id),
  CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Chat: one conversation per customer with the pharmacy team.
-- ---------------------------------------------------------------------
CREATE TABLE chat_conversations (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id         INT UNSIGNED NOT NULL,
  last_message_at DATETIME NULL,
  user_unread     INT NOT NULL DEFAULT 0,
  staff_unread    INT NOT NULL DEFAULT 0,
  UNIQUE KEY uq_chat_user (user_id),
  CONSTRAINT fk_conv_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE chat_messages (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  conversation_id INT UNSIGNED NOT NULL,
  sender_id       INT UNSIGNED NOT NULL,
  body            TEXT NULL,
  attachment      VARCHAR(500) NULL,
  created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_msg_conv (conversation_id, id),
  CONSTRAINT fk_msg_conv FOREIGN KEY (conversation_id) REFERENCES chat_conversations(id) ON DELETE CASCADE,
  CONSTRAINT fk_msg_sender FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Medication tracker: schedules + per-dose logs, reminded via WhatsApp/email.
-- ---------------------------------------------------------------------
CREATE TABLE medications (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id         INT UNSIGNED NOT NULL,
  prescription_id INT UNSIGNED NULL,
  name            VARCHAR(200) NOT NULL,
  dosage          VARCHAR(100) NULL,
  instructions    TEXT NULL,
  start_date      DATE NOT NULL,
  end_date        DATE NULL,
  active          TINYINT(1) NOT NULL DEFAULT 1,
  created_by      INT UNSIGNED NULL,
  created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_med_user (user_id),
  CONSTRAINT fk_med_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_med_rx FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE SET NULL,
  CONSTRAINT fk_med_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE medication_times (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  medication_id INT UNSIGNED NOT NULL,
  time_of_day   TIME NOT NULL,
  label         VARCHAR(40) NULL,
  CONSTRAINT fk_mt_med FOREIGN KEY (medication_id) REFERENCES medications(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE medication_logs (
  id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  medication_id      INT UNSIGNED NOT NULL,
  medication_time_id INT UNSIGNED NULL,
  scheduled_for      DATETIME NOT NULL,
  status             ENUM('scheduled','sent','taken','skipped') NOT NULL DEFAULT 'scheduled',
  channel            VARCHAR(20) NULL,
  sent_at            DATETIME NULL,
  taken_at           DATETIME NULL,
  UNIQUE KEY uq_log_dose (medication_id, medication_time_id, scheduled_for),
  CONSTRAINT fk_log_med FOREIGN KEY (medication_id) REFERENCES medications(id) ON DELETE CASCADE,
  CONSTRAINT fk_log_time FOREIGN KEY (medication_time_id) REFERENCES medication_times(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- QR collection audit log (who collected what, verified by which staff member)
CREATE TABLE collections (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id         INT UNSIGNED NOT NULL,
  staff_id        INT UNSIGNED NULL,
  order_id        INT UNSIGNED NULL,
  prescription_id INT UNSIGNED NULL,
  note            VARCHAR(255) NULL,
  created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_col_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_col_staff FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE SET NULL,
  CONSTRAINT fk_col_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
  CONSTRAINT fk_col_rx FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE notifications (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED NOT NULL,
  type       VARCHAR(30) NOT NULL,
  title      VARCHAR(200) NOT NULL,
  body       TEXT NULL,
  link       VARCHAR(255) NULL,
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_notif_user (user_id, is_read),
  CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Outbox for WhatsApp messages: sent via Meta Cloud API when configured,
-- otherwise kept as 'logged' so the flow is demoable without credentials.
CREATE TABLE whatsapp_outbox (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED NULL,
  phone      VARCHAR(20) NOT NULL,
  message    TEXT NOT NULL,
  status     ENUM('queued','sent','failed','logged') NOT NULL DEFAULT 'queued',
  response   TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  sent_at    DATETIME NULL,
  CONSTRAINT fk_wa_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(120) NOT NULL,
  email      VARCHAR(190) NOT NULL,
  subject    VARCHAR(200) NULL,
  message    TEXT NOT NULL,
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE settings (
  name  VARCHAR(60) PRIMARY KEY,
  value TEXT NULL
) ENGINE=InnoDB;

-- =====================================================================
-- Seed data
-- =====================================================================

-- Demo accounts (change these passwords after first login!):
--   admin@medi-o.lk      / Admin@123
--   pharmacist@medi-o.lk / Pharma@123
--   demo@medi-o.lk       / Demo@123   (phone login: 0771234567)
INSERT INTO users (name, email, phone, password_hash, role, qr_token) VALUES
('Medi-O Admin', 'admin@medi-o.lk', '94770000001', '$2y$10$.ZDjd/No9H0QlBZxgftQA.KnCg2eZcHRDisI3C3L1GKQQ3591lIY2', 'admin', 'c473a0656ff3e06b11e8cedfc146c35a289d2e94'),
('Nimal Perera (Pharmacist)', 'pharmacist@medi-o.lk', '94770000002', '$2y$10$9yBsSFAO3WK3LuAyZkJP5.twa65.SdhuoUOm/F3rW2hcQoRvU/h1.', 'pharmacist', 'cdb5cdf2e6fbc7569cd1a7b9936ba7c5b153028b'),
('Demo Customer', 'demo@medi-o.lk', '94771234567', '$2y$10$MbGYRpHVhYXjeS25fmYkU.gQOO4M7d1R02vPMn6DE4TscSMQUwtDC', 'customer', 'eb0da471511e6e4585228472510790084ca2f5af');

INSERT INTO categories (name, icon) VALUES
('Medicines', 'bi-capsule-pill'),
('Vitamins & Supplements', 'bi-prescription2'),
('Hair Care', 'bi-scissors'),
('Skin Care', 'bi-droplet-half'),
('Oral Care', 'bi-emoji-smile'),
('Sun Protection', 'bi-sun'),
('Women''s Personal Care', 'bi-gender-female'),
('Men''s Personal Care', 'bi-gender-male'),
('Sexual Wellness', 'bi-heart-pulse'),
('Medical Devices', 'bi-heart-pulse-fill'),
('Hand & Foot Care', 'bi-hand-index'),
('Nail Care', 'bi-brush'),
('Skin, Hair & Nails', 'bi-stars'),
('Other', 'bi-bag-plus');

-- Demo medicines (some prescription-only) so the pharmacy flows are testable
INSERT INTO products (category_id, name, description, price, stock, image_path, rating, requires_prescription) VALUES
((SELECT id FROM categories WHERE name='Medicines'), 'Paracetamol 500mg (Panadol) — 24 tablets', 'Pain relief and fever reducer. Adults: 1–2 tablets every 4–6 hours as needed. Max 8 tablets in 24 hours.', 180.00, 120, NULL, 4.80, 0),
((SELECT id FROM categories WHERE name='Medicines'), 'Cetirizine 10mg — 10 tablets', 'Antihistamine for allergy relief: hay fever, urticaria, allergic rhinitis. One tablet daily.', 150.00, 80, NULL, 4.60, 0),
((SELECT id FROM categories WHERE name='Medicines'), 'Amoxicillin 500mg — 21 capsules', 'Broad-spectrum antibiotic. PRESCRIPTION REQUIRED. Complete the full course as directed by your doctor.', 650.00, 40, NULL, 4.70, 1),
((SELECT id FROM categories WHERE name='Medicines'), 'Metformin 500mg — 28 tablets', 'For type 2 diabetes management. PRESCRIPTION REQUIRED. Take with meals as directed.', 420.00, 60, NULL, 4.50, 1),
((SELECT id FROM categories WHERE name='Medicines'), 'Losartan 50mg — 28 tablets', 'For high blood pressure. PRESCRIPTION REQUIRED. Take once daily as directed.', 540.00, 50, NULL, 4.40, 1),
((SELECT id FROM categories WHERE name='Medicines'), 'Omeprazole 20mg — 14 capsules', 'For acid reflux and gastritis. Take one capsule before breakfast.', 380.00, 90, NULL, 4.60, 0),
((SELECT id FROM categories WHERE name='Medicines'), 'ORS (Jeevani) — 5 sachets', 'Oral rehydration salts for dehydration. Dissolve one sachet in 1L of clean water.', 250.00, 150, NULL, 4.90, 0),
((SELECT id FROM categories WHERE name='Vitamins & Supplements'), 'Vitamin C 1000mg — 30 effervescent tablets', 'Immune support. Dissolve one tablet in a glass of water daily.', 950.00, 70, NULL, 4.70, 0),
((SELECT id FROM categories WHERE name='Vitamins & Supplements'), 'Vitamin D3 1000IU — 60 softgels', 'Supports bone health and immunity. One softgel daily with food.', 1250.00, 45, NULL, 4.60, 0),
((SELECT id FROM categories WHERE name='Medicines'), 'Salbutamol Inhaler 100mcg', 'Reliever inhaler for asthma. PRESCRIPTION REQUIRED.', 780.00, 30, NULL, 4.80, 1);

INSERT INTO settings (name, value) VALUES
('pharmacy_name', 'Medi-O Pharmacy'),
('pharmacy_phone', '0112345678'),
('pharmacy_whatsapp', '94771234567'),
('pharmacy_address', '123 Galle Road, Colombo 03, Sri Lanka'),
('pharmacy_hours', 'Mon–Sat 8.00am – 10.00pm, Sun 9.00am – 6.00pm'),
('shipping_fee', '250'),
('free_shipping_over', '5000'),
('currency', 'LKR');
