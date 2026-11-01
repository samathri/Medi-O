-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: medio
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `medio`
--

/*!40000 DROP DATABASE IF EXISTS `medio`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `medio` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `medio`;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cart_user_product` (`user_id`,`product_id`),
  KEY `fk_cart_product` (`product_id`),
  CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `icon` varchar(60) NOT NULL DEFAULT 'bi-capsule',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Medicines','bi-capsule-pill'),(2,'Vitamins & Supplements','bi-prescription2'),(3,'Hair Care','bi-scissors'),(4,'Skin Care','bi-droplet-half'),(5,'Oral Care','bi-emoji-smile'),(6,'Sun Protection','bi-sun'),(7,'Women\'s Personal Care','bi-gender-female'),(8,'Men\'s Personal Care','bi-gender-male'),(9,'Sexual Wellness','bi-heart-pulse'),(10,'Medical Devices','bi-heart-pulse-fill'),(11,'Hand & Foot Care','bi-hand-index'),(12,'Nail Care','bi-brush'),(13,'Skin, Hair & Nails','bi-stars'),(14,'Other','bi-bag-plus'),(15,'Other Accessories','bi-capsule'),(16,'Spirit','bi-capsule'),(17,'Rossmax','bi-capsule');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_conversations`
--

DROP TABLE IF EXISTS `chat_conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_conversations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `last_message_at` datetime DEFAULT NULL,
  `user_unread` int(11) NOT NULL DEFAULT 0,
  `staff_unread` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_chat_user` (`user_id`),
  CONSTRAINT `fk_conv_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_conversations`
--

LOCK TABLES `chat_conversations` WRITE;
/*!40000 ALTER TABLE `chat_conversations` DISABLE KEYS */;
INSERT INTO `chat_conversations` VALUES (1,7,'2026-09-06 13:59:29',0,0),(2,3,'2026-11-01 19:59:16',0,0);
/*!40000 ALTER TABLE `chat_conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(10) unsigned NOT NULL,
  `sender_id` int(10) unsigned NOT NULL,
  `body` text DEFAULT NULL,
  `attachment` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_msg_conv` (`conversation_id`,`id`),
  KEY `fk_msg_sender` (`sender_id`),
  CONSTRAINT `fk_msg_conv` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_msg_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
INSERT INTO `chat_messages` VALUES (1,1,7,'hey',NULL,'2026-09-06 07:57:23'),(2,1,1,'hello',NULL,'2026-09-06 08:29:29'),(3,2,3,'Audit test message 1793543356276',NULL,'2026-11-01 14:29:16');
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `prescription_id` int(10) unsigned DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_col_user` (`user_id`),
  KEY `fk_col_staff` (`staff_id`),
  KEY `fk_col_order` (`order_id`),
  KEY `fk_col_rx` (`prescription_id`),
  CONSTRAINT `fk_col_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_col_rx` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_col_staff` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_col_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collections`
--

LOCK TABLES `collections` WRITE;
/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
INSERT INTO `collections` VALUES (1,3,2,1,NULL,'Order collected via QR','2026-11-01 14:30:00');
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medication_logs`
--

DROP TABLE IF EXISTS `medication_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medication_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `medication_id` int(10) unsigned NOT NULL,
  `medication_time_id` int(10) unsigned DEFAULT NULL,
  `scheduled_for` datetime NOT NULL,
  `status` enum('scheduled','sent','taken','skipped') NOT NULL DEFAULT 'scheduled',
  `channel` varchar(20) DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `taken_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_log_dose` (`medication_id`,`medication_time_id`,`scheduled_for`),
  KEY `fk_log_time` (`medication_time_id`),
  CONSTRAINT `fk_log_med` FOREIGN KEY (`medication_id`) REFERENCES `medications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_log_time` FOREIGN KEY (`medication_time_id`) REFERENCES `medication_times` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medication_logs`
--

LOCK TABLES `medication_logs` WRITE;
/*!40000 ALTER TABLE `medication_logs` DISABLE KEYS */;
INSERT INTO `medication_logs` VALUES (1,3,5,'2026-11-01 08:00:00','scheduled',NULL,NULL,NULL),(2,4,6,'2026-11-01 21:00:00','scheduled',NULL,NULL,NULL),(3,5,7,'2026-11-01 08:00:00','scheduled',NULL,NULL,NULL),(4,6,8,'2026-11-01 21:00:00','scheduled',NULL,NULL,NULL),(5,7,9,'2026-11-01 08:00:00','scheduled',NULL,NULL,NULL),(6,8,10,'2026-11-01 08:00:00','scheduled',NULL,NULL,NULL),(7,9,11,'2026-11-01 21:00:00','scheduled',NULL,NULL,NULL),(8,10,12,'2026-11-01 08:00:00','scheduled',NULL,NULL,NULL),(9,10,13,'2026-11-01 20:00:00','scheduled',NULL,NULL,NULL);
/*!40000 ALTER TABLE `medication_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medication_times`
--

DROP TABLE IF EXISTS `medication_times`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medication_times` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `medication_id` int(10) unsigned NOT NULL,
  `time_of_day` time NOT NULL,
  `label` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mt_med` (`medication_id`),
  CONSTRAINT `fk_mt_med` FOREIGN KEY (`medication_id`) REFERENCES `medications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medication_times`
--

LOCK TABLES `medication_times` WRITE;
/*!40000 ALTER TABLE `medication_times` DISABLE KEYS */;
INSERT INTO `medication_times` VALUES (1,1,'08:00:00',NULL),(2,1,'13:00:00',NULL),(3,1,'20:00:00',NULL),(4,2,'07:30:00',NULL),(5,3,'08:00:00',NULL),(6,4,'21:00:00',NULL),(7,5,'08:00:00',NULL),(8,6,'21:00:00',NULL),(9,7,'08:00:00',NULL),(10,8,'08:00:00',NULL),(11,9,'21:00:00',NULL),(12,10,'08:00:00',NULL),(13,10,'20:00:00',NULL);
/*!40000 ALTER TABLE `medication_times` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medications`
--

DROP TABLE IF EXISTS `medications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `prescription_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_med_user` (`user_id`),
  KEY `fk_med_rx` (`prescription_id`),
  KEY `fk_med_creator` (`created_by`),
  CONSTRAINT `fk_med_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_med_rx` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_med_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medications`
--

LOCK TABLES `medications` WRITE;
/*!40000 ALTER TABLE `medications` DISABLE KEYS */;
INSERT INTO `medications` VALUES (1,3,1,'Amoxicillin 500mg','1 capsule','After meals, full course','2026-09-20','2026-09-26',1,2,'2026-09-20 06:28:08'),(2,3,1,'Omeprazole 20mg','1 capsule','Before breakfast','2026-09-20','2026-10-03',1,2,'2026-09-20 06:28:08'),(3,9,3,'Purifying Mousse','1 application','Apply in the morning (mane)','2026-11-01',NULL,1,2,'2026-11-01 17:22:30'),(4,9,3,'Epiduo Gel','1 application','Apply at night (nocte)','2026-11-01',NULL,1,2,'2026-11-01 17:22:30'),(5,9,3,'Spironolactone 50mg','1 tablet','Take in the morning (mane)','2026-11-01',NULL,1,2,'2026-11-01 17:22:30'),(6,9,3,'Microdox 100mg','1 capsule','Take at night for 6 weeks','2026-11-01','2026-12-12',1,2,'2026-11-01 17:22:30'),(7,9,3,'Salic Leave-on Gel','1 application','Apply in the morning (mane)','2026-11-01',NULL,1,2,'2026-11-01 17:22:30'),(8,9,3,'SunRx42 SC Sunscreen','1 application','Apply in the morning','2026-11-01',NULL,1,2,'2026-11-01 17:22:30'),(9,9,3,'Yasmin','1 tablet','3 cards','2026-11-01',NULL,1,2,'2026-11-01 17:22:30'),(10,9,3,'Opti-Lumineux','1 capsule','Take twice daily (bd)','2026-11-01',NULL,1,2,'2026-11-01 17:22:30');
/*!40000 ALTER TABLE `medications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `type` varchar(30) NOT NULL,
  `title` varchar(200) NOT NULL,
  `body` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notif_user` (`user_id`,`is_read`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (3,7,'welcome','Welcome to Medi-O!','Explore the shop or upload your first prescription.','http://localhost/medi-o/shop.php',0,'2026-09-06 07:41:09'),(4,7,'chat','New message from the pharmacy','hello','http://localhost/medi-o/chat.php',0,'2026-09-06 08:29:29'),(5,1,'prescription','New prescription from Demo Customer','Waiting for review.','http://localhost/medio/staff/prescriptions.php',0,'2026-09-20 06:27:19'),(6,2,'prescription','New prescription from Demo Customer','Waiting for review.','http://localhost/medio/staff/prescriptions.php',0,'2026-09-20 06:27:19'),(7,3,'prescription','Prescription approved ✅','Your prescription was verified by our pharmacist. Your medication plan (2 medicines) is now in your account.','http://localhost/medio/prescriptions.php',0,'2026-09-20 06:28:08'),(8,3,'payment','Payment received for #MO2611017F2B5','Rs. 1,450.00 paid successfully.','http://localhost/medio/order.php?id=1',0,'2026-11-01 14:29:06'),(9,3,'order','Order #MO2611017F2B5 collected','Thanks for shopping with Medi-O. Get well soon!','http://localhost/medio/order.php?id=1',0,'2026-11-01 14:30:00'),(11,3,'payment','Payment received for #MO26110188B33','Rs. 900.00 paid successfully.','http://localhost/medio/order.php?id=2',0,'2026-11-01 17:13:52'),(12,3,'order','Order #MO26110188B33 — Processing','Your order is being prepared.','http://localhost/medio/order.php?id=2',0,'2026-11-01 17:14:47'),(13,3,'order','Order #MO26110188B33 — Ready','Your order is out for delivery!','http://localhost/medio/order.php?id=2',0,'2026-11-01 17:14:56'),(14,9,'welcome','Welcome to Medi-O!','Explore the shop or upload your first prescription.','http://localhost/medio/shop.php',0,'2026-11-01 17:18:23'),(15,1,'prescription','New prescription from Kithmini Yasasvi','Waiting for review.','http://localhost/medio/staff/prescriptions.php',0,'2026-11-01 17:20:48'),(16,2,'prescription','New prescription from Kithmini Yasasvi','Waiting for review.','http://localhost/medio/staff/prescriptions.php',0,'2026-11-01 17:20:48'),(17,9,'prescription','Prescription approved ✅','Your prescription was verified by our pharmacist. Your medication plan (8 medicines) is now in your account.','http://localhost/medio/prescriptions.php',0,'2026-11-01 17:22:30'),(18,9,'payment','Payment received for #MO261101F5D9B','Rs. 14,100.00 paid successfully.','http://localhost/medio/order.php?id=3',0,'2026-11-01 17:24:02'),(19,9,'order','Order #MO261101F5D9B — Ready','Your order is out for delivery!','http://localhost/medio/order.php?id=3',0,'2026-11-01 17:24:42'),(20,9,'order','Order #MO261101F5D9B — Completed','Your order is complete. Get well soon!','http://localhost/medio/order.php?id=3',0,'2026-11-01 17:25:11');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_oi_order` (`order_id`),
  KEY `fk_oi_product` (`product_id`),
  CONSTRAINT `fk_oi_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_oi_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,7,'ORS (Jeevani) — 5 sachets',250.00,2),(2,1,8,'Vitamin C 1000mg — 30 effervescent tablets',950.00,1),(3,2,3,'Amoxicillin 500mg — 21 capsules',650.00,1),(4,3,83,'Epiduo Gel 30g (Adapalene + Benzoyl Peroxide)',7200.00,1),(5,3,85,'Microdox 100mg (Doxycycline) — 30 capsules',1500.00,1),(6,3,87,'SunRx 42 SC Sunscreen SPF42 50ml',5400.00,1);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `prescription_id` int(10) unsigned DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('payhere','cash') NOT NULL DEFAULT 'cash',
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `status` enum('placed','processing','ready','collected','completed','cancelled') NOT NULL DEFAULT 'placed',
  `delivery_method` enum('pickup','delivery') NOT NULL DEFAULT 'pickup',
  `address` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `qr_token` char(40) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_orders_no` (`order_no`),
  UNIQUE KEY `uq_orders_qr` (`qr_token`),
  KEY `idx_orders_user` (`user_id`),
  KEY `idx_orders_status` (`status`),
  KEY `fk_orders_rx` (`prescription_id`),
  CONSTRAINT `fk_orders_rx` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'MO2611017F2B5',3,NULL,1450.00,0.00,1450.00,'payhere','paid','collected','pickup',NULL,NULL,'076561688c35f44ddad085bffc075dafaae681ee','2026-11-01 14:29:01','2026-11-01 14:30:00'),(2,'MO26110188B33',3,1,650.00,250.00,900.00,'payhere','paid','ready','delivery','42 Galle Road, Colombo 03',NULL,'0b9c2047803e877e847900eae765f3c78b627404','2026-11-01 17:13:37','2026-11-01 17:14:56'),(3,'MO261101F5D9B',9,3,14100.00,0.00,14100.00,'payhere','paid','completed','delivery','85/1 Horana Road, Bandaragama',NULL,'57c6b8abcead5a60fe1a82daffaaecbb2d4b0e41','2026-11-01 17:23:54','2026-11-01 17:25:11');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `method` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` char(3) NOT NULL DEFAULT 'LKR',
  `status` varchar(20) NOT NULL,
  `gateway_ref` varchar(100) DEFAULT NULL,
  `payload` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_payments_order` (`order_id`),
  CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,'demo-gateway',1450.00,'LKR','paid','DEMO-5E4B45A9',NULL,'2026-11-01 14:29:06'),(2,2,'demo-gateway',900.00,'LKR','paid','DEMO-3B39386C',NULL,'2026-11-01 17:13:52'),(3,3,'demo-gateway',14100.00,'LKR','paid','DEMO-ED6E7631',NULL,'2026-11-01 17:24:02');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescription_items`
--

DROP TABLE IF EXISTS `prescription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prescription_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prescription_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `dosage` varchar(120) DEFAULT NULL,
  `times` varchar(255) DEFAULT NULL,
  `duration_days` int(11) DEFAULT NULL,
  `instructions` varchar(255) DEFAULT NULL,
  `in_stock` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_pi_rx` (`prescription_id`),
  KEY `fk_pi_product` (`product_id`),
  CONSTRAINT `fk_pi_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pi_rx` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescription_items`
--

LOCK TABLES `prescription_items` WRITE;
/*!40000 ALTER TABLE `prescription_items` DISABLE KEYS */;
INSERT INTO `prescription_items` VALUES (1,1,3,'Amoxicillin 500mg','1 capsule','08:00,13:00,20:00',7,'After meals, full course',1,'2026-09-20 06:28:08'),(2,1,6,'Omeprazole 20mg','1 capsule','07:30',14,'Before breakfast',1,'2026-09-20 06:28:08'),(14,3,82,'Purifying Mousse','1 application','08:00',NULL,'Apply in the morning (mane)',1,'2026-11-01 17:22:30'),(15,3,83,'Epiduo Gel','1 application','21:00',NULL,'Apply at night (nocte)',1,'2026-11-01 17:22:30'),(16,3,84,'Spironolactone 50mg','1 tablet','08:00',NULL,'Take in the morning (mane)',1,'2026-11-01 17:22:30'),(17,3,85,'Microdox 100mg','1 capsule','21:00',42,'Take at night for 6 weeks',1,'2026-11-01 17:22:30'),(18,3,86,'Salic Leave-on Gel','1 application','08:00',NULL,'Apply in the morning (mane)',1,'2026-11-01 17:22:30'),(19,3,87,'SunRx42 SC Sunscreen','1 application','08:00',NULL,'Apply in the morning',1,'2026-11-01 17:22:30'),(20,3,88,'Yasmin','1 tablet','21:00',NULL,'3 cards',1,'2026-11-01 17:22:30'),(21,3,89,'Opti-Lumineux','1 capsule','08:00,20:00',NULL,'Take twice daily (bd)',1,'2026-11-01 17:22:30');
/*!40000 ALTER TABLE `prescription_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prescriptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','ready','collected') NOT NULL DEFAULT 'pending',
  `pharmacist_id` int(10) unsigned DEFAULT NULL,
  `review_note` text DEFAULT NULL,
  `ai_summary` text DEFAULT NULL,
  `ai_analyzed_at` datetime DEFAULT NULL,
  `verify_token` char(40) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_prescriptions_token` (`verify_token`),
  KEY `idx_prescriptions_status` (`status`),
  KEY `fk_rx_user` (`user_id`),
  KEY `fk_rx_pharmacist` (`pharmacist_id`),
  CONSTRAINT `fk_rx_pharmacist` FOREIGN KEY (`pharmacist_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_rx_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prescriptions`
--

LOCK TABLES `prescriptions` WRITE;
/*!40000 ALTER TABLE `prescriptions` DISABLE KEYS */;
INSERT INTO `prescriptions` VALUES (1,3,'uploads/prescriptions/rx_3_c16006b208918a20.png','clinic-prescription.png','Antibiotic course from City Clinic','approved',2,NULL,NULL,NULL,'2089611066188f6ce226b445e69d0e183e74fcae','2026-09-20 06:27:19','2026-09-20 11:58:08'),(3,9,'uploads/prescriptions/rx_9_f6b8bda80386a3df.webp','medihelp-prescription.webp','Dermatology prescription from Medihelp Hospitals','approved',2,NULL,'Prescription from Medihelp Hospitals for Mrs. Kithmini Yasasvi (31 years old) dated 2025/09/04, containing dermatological and acne treatment medications.','2026-11-01 22:51:48','f3f6a6cb44cda6ac52ac2265d33b795317ef3292','2026-11-01 17:20:48','2026-11-01 22:52:30');
/*!40000 ALTER TABLE `prescriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image_path` varchar(500) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `requires_prescription` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_products_category` (`category_id`),
  KEY `idx_products_active` (`is_active`),
  FULLTEXT KEY `ft_products_name` (`name`,`description`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Paracetamol 500mg (Panadol) — 24 tablets','Pain relief and fever reducer. Adults: 1–2 tablets every 4–6 hours as needed. Max 8 tablets in 24 hours.',180.00,120,NULL,4.80,0,1,'2026-08-16 17:20:09'),(2,1,'Cetirizine 10mg — 10 tablets','Antihistamine for allergy relief: hay fever, urticaria, allergic rhinitis. One tablet daily.',150.00,80,NULL,4.60,0,1,'2026-08-16 17:20:09'),(3,1,'Amoxicillin 500mg — 21 capsules','Broad-spectrum antibiotic. PRESCRIPTION REQUIRED. Complete the full course as directed by your doctor.',650.00,39,NULL,4.70,1,1,'2026-08-16 17:20:09'),(4,1,'Metformin 500mg — 28 tablets','For type 2 diabetes management. PRESCRIPTION REQUIRED. Take with meals as directed.',420.00,60,NULL,4.50,1,1,'2026-08-16 17:20:09'),(5,1,'Losartan 50mg — 28 tablets','For high blood pressure. PRESCRIPTION REQUIRED. Take once daily as directed.',540.00,50,NULL,4.40,1,1,'2026-08-16 17:20:09'),(6,1,'Omeprazole 20mg — 14 capsules','For acid reflux and gastritis. Take one capsule before breakfast.',380.00,90,NULL,4.60,0,1,'2026-08-16 17:20:09'),(7,1,'ORS (Jeevani) — 5 sachets','Oral rehydration salts for dehydration. Dissolve one sachet in 1L of clean water.',250.00,148,NULL,4.90,0,1,'2026-08-16 17:20:09'),(8,2,'Vitamin C 1000mg — 30 effervescent tablets','Immune support. Dissolve one tablet in a glass of water daily.',950.00,69,NULL,4.70,0,1,'2026-08-16 17:20:09'),(9,2,'Vitamin D3 1000IU — 60 softgels','Supports bone health and immunity. One softgel daily with food.',1250.00,45,NULL,4.60,0,1,'2026-08-16 17:20:09'),(10,1,'Salbutamol Inhaler 100mcg','Reliever inhaler for asthma. PRESCRIPTION REQUIRED.',780.00,30,NULL,4.80,1,1,'2026-08-16 17:20:09'),(11,15,'3M™ LITTMANN® STETHOSCOPE SPARE PARTS KITS, 40016 CLASSIC III™, CARDIOLOGY IV™ & CORE, BLACK','Quality and reliability in one convienient package\r\nContains one tunable single piece diaphragm for the adult side of the stethoscope chest piece, Black\r\nContains one tunable single piece diaphragm for the pediatric side of the stethoscope chest piece, Black\r\nContains one non-chill bell sleeve, Black',11390.00,1,'uploads/products/40005-600x450.jpg',3.39,0,1,'2026-08-16 17:20:29'),(12,15,'3M™ LITTMANN® STETHOSCOPE SPARE PARTS KIT, CLASSIC II S.E., GRAY, 40006','Quality and reliability in one convienient package\r\nContains Small (1/2 inch) non chill bell sleeve, Gray\r\nContains one tunable diaphragm and rim assembly for the stethoscope chest piece, Gray',16690.00,1,'uploads/products/40006-600x450.jpg',3.99,0,1,'2026-08-16 17:20:29'),(13,15,'3M™ LITTMANN® STETHOSCOPE SPARE PARTS KIT, CLASSIC II S.E., BLACK, 40005','Quality and reliability in one convenient package\r\nContains Small (1/2 inch) non-chill bell sleeve, Black\r\nContains one tunable diaphragm and rim assembly for the stethoscope chest piece, Black',16390.00,10,'uploads/products/40012-600x450.jpg',4.77,0,1,'2026-08-16 17:20:29'),(14,15,'3M™ LITTMANN® STETHOSCOPE SPARE PARTS KIT, CLASSIC II PEDIATRIC DIAPHRAGM ASSEMBLY, 40012','A dual-sided chest piece with a small 3.3cm diaphragm is ideal for pediatric patients.\r\nContains one floating diaphragm for the stethoscope chest piece\r\nContains a black rim for assembly of the diaphragm to the chest piece\r\nContains a Gray rim for assembly of the diaphragm to the chest piece',9190.00,1,'uploads/products/40016-600x450.jpg',4.86,0,1,'2026-08-16 17:20:29'),(15,7,'WELLWOMAN SPORT','(Per pack)\r\n\r\nPremium nutritional support for women actively involved in fitness, sports, and gym. Includes 28 nutrients that support performance, including iron which contributes to the reduction of tiredness and fatigue. From Wellwoman, the UK’s number 1 women’s supplement brand.\r\n\r\nEnergy Levels – contains vitamin B6 and B12 which contribute to normal energy release and red blood cell formation.\r\nAll-round Health & Vitality – Wellwoman sport also doubles up as a comprehensive multivitamin to support general health & vitality.\r\nTested by LGC, a world-class anti-doping laboratory.',5000.00,2,'uploads/products/Wellwoman-Sport-600x600.jpg',3.01,0,1,'2026-08-16 17:20:29'),(16,7,'WELLWOMAN 70+','(Per card of 15 capsules)\r\n\r\nA carefully formulated supplement for women 70 and over, scientifically developed to support overall health & vitality, along with areas of health particularly relevant to those in the later stages of life, such as heart, brain, vision, immune, and energy. From the UK’s No.1 women’s supplement brand.\r\n\r\nImmune Support – contains vitamin C and D which contribute to the normal function of the immune system\r\nCognitive Function – with iron, iodine, and zinc which contribute to normal cognitive function\r\nHeart & Circulation – includes vitamin B1 which contributes to the normal function of the heart\r\nVision – vitamins C and E plus mineral zinc contributes to the maintenance of normal vision\r\nEnergy levels – with thiamin, copper, and iron which contribute to normal energy release',4859.00,3,'uploads/products/Wellwoman-70-600x600.jpg',3.47,0,1,'2026-08-16 17:20:29'),(17,7,'WELLWOMAN 50+','(Per pack)\r\n\r\nOur comprehensive vitamin & mineral formula for women over 50, is specially developed to help maintain overall vitality and areas of health particularly relevant to those in middle age, such as heart, brain, energy, and immune. From the UK’s No.1 women’s supplement brand.\r\n\r\nBrain – with pantothenic acid which contributes to normal mental performance.\r\nEnergy levels – with thiamin, copper, and iron which contribute to normal energy release.\r\nHeart & Circulation – includes vitamin B1 which helps maintain normal heart function.\r\nImmune Support – contains zinc, selenium, and vitamin D which contribute to normal immune system function.',4759.00,4,'uploads/products/Wellwoman-50-600x600.jpg',3.32,0,1,'2026-08-16 17:20:29'),(18,7,'WELLTEEN HER','(Per pack)\r\nWhen nutrient requirements are changing fast, Wellteen Her safeguards the nutrition that helps young women take the world on…\r\n\r\nExtra dietary help for sports, studies and busy social lives.\r\nWith Iron which contributes to normal formation of red blood cells & hemoglobin.\r\nHelps maintain vitality and wellness from 13 to 19 years.\r\nFrom the UK’s No.1 vitamin company.',6248.00,2,'uploads/products/Wellteen-Her-600x600.jpg',3.18,0,1,'2026-08-16 17:20:29'),(19,6,'SUNCOTE GEL 100g','Suncote gel is a water-based gel with hydro technology that protects the skin against UVA and UVB rays by providing a chemical barrier to sunlight. It is non-comedogenic with a high SPF value of 30.',5117.50,10,'uploads/products/Suncote-600x611.jpg',4.94,0,1,'2026-08-16 17:20:29'),(20,6,'SOLARIS SC SPF40 TRANSPARENT SUN SCREEN GEL 45ml','Transparent Sun Screen Gel for oily/combination skin types.\r\n\r\nNon Whitening.\r\n\r\nNon Tanning.\r\n\r\nNon Greasy.\r\n\r\nNon Comedogenic.\r\n\r\nParaben Free.\r\n\r\nWater Resistant.\r\n\r\nHypoallergenic.\r\n\r\n(Sebum Control Treatment)',5400.00,3,'uploads/products/SOLARISsc-GEL-600x600.jpeg',4.18,0,1,'2026-08-16 17:20:29'),(21,6,'SCAR-FADE UV GEL With SPF 30','Most people who use lighteners do so to treat skin problems such as age spots, acne scars, or discoloration related to hormones.',12600.00,1,'uploads/products/Scar-Fade-600x600.jpg',3.09,0,1,'2026-08-16 17:20:29'),(22,6,'Rowill Sunscreen Gel SPF 50 60ml','50 SPF Sunscreen gel, Non-Comedogenic, Oil Free. Suitable for all skin Types. For Face & Body.',3100.00,10,'uploads/products/Rowill-Sunscreen-Gel-SPF-50-60ml-600x600.jpg',3.89,0,1,'2026-08-16 17:20:29'),(23,13,'PERFECTIL HAIR EXTRA SUPPORT 60’s','With Perfectil Hair’s advanced nutrition, get the comprehensive support of Perfectil while giving your hair the special attention it deserves.\r\n\r\n28 micronutrients, optimized to give your hair extra support\r\nWith extra biotin & selenium which contribute to normal hair\r\nWith copper which contributes to normal hair pigmentation\r\nThe UK’s No.1 beauty supplement brand',9800.00,2,'uploads/products/Perfectil-Hair-600x600.jpg',3.20,0,1,'2026-08-16 17:20:29'),(24,13,'NEOCELL® SUPER COLLAGEN+ C WITH BIOTIN – 90 TABLETS','Collagen type 1 & 3\r\n\r\nVitamin C\r\n\r\nBiotin\r\nAids in collagen formation for healthy looking hair , skin & nails.',7500.00,10,'uploads/products/NEOCELL-SUPER-COLLAGEN.jpg',3.31,0,1,'2026-08-16 17:20:29'),(25,13,'NEOCELL MARINE COLLAGEN 120 CAPSULES','NeoCell Marine Collagen offers a premium blend of Collagen Types 1 & 3 combined with Hyaluronic Acid, designed to promote strong, healthy, and hydrated skin. This supplement is enriched with a vita-mineral blend that works synergistically to support healthy collagen formation, contributing to youthful and radiant skin. Certified Paleo-Friendly and Gluten-Free, NeoCell Marine Collagen is free from soy, lactose, and artificial flavors, and contains only non-GMO ingredients. Incorporate 4 capsules daily into your routine to experience the beauty and wellness benefits from within.',10200.00,10,'uploads/products/Neocell-marine-collagen-600x600.png',3.97,0,1,'2026-08-16 17:20:29'),(26,13,'NATURE’S WAY BEAUTY HAIR,SCALP,SKIN & NAILS 60 Capsules','Nature’s Way Hair, Scalp, Skin & Nails is an innovative 4-in-1 beauty complex that deeply nourishes and supports luscious looking hair, healthy glowing skin and strong nails with the added benefit of supporting a healthy scalp.*',10900.00,10,'uploads/products/Natures-way-beauty-hair-skin-nails.webp',4.92,0,1,'2026-08-16 17:20:29'),(27,4,'CETAPHIL MOISTURISING CREAM 80g','Moisturizing Cream For Face & Body.\r\n\r\nAll Skin Types.\r\n\r\nNon-comedogenic.\r\n\r\nDermatologically Tested.',3571.00,10,'uploads/products/CETAPHIL-MOISTURISING-CREAM-80g-3.jpg',3.68,0,1,'2026-08-16 17:20:29'),(28,4,'CETAPHIL GENTLE SKIN CLEANSER','Gentle Skin Cleanser For Face & Body.\r\n\r\nAll Skin Types\r\n\r\npH Balanced.\r\n\r\nFragrance-Free.\r\n\r\nNon-Comedogenic.\r\n\r\nDermatologically Tested.',3040.00,5,'uploads/products/CETAPHIL-GENTLE-SKIN-CLEANSER-2.jpg',4.62,0,1,'2026-08-16 17:20:29'),(29,4,'CETAPHIL DAM ULTRA HYDRATING LOTION 100g','Daily Advance Ultra Hydrating Lotion For Face & Body.\r\nAll Skin Types.\r\nNon-Comedogenic.\r\nNon-Irritation.\r\nFragrance-free.\r\nDermatologically Tested.',4767.00,1,'uploads/products/CETAPHIL-DAM-ULTRA-HYDRATING-LOTION-100g-1.jpg',3.09,0,1,'2026-08-16 17:20:29'),(30,4,'CETAPHIL CENTLE FOAMING CLEANSER 236ML','For dry to normal, sensitive skin\r\nA gentle daily cleanser that rinses away dirt, oil and makeup without stripping skin’s natural moisture balance.\r\nFormulated with glycerin, pro-vitamin B5 and essential vitamin E to soften as it cleans\r\nGently cleanses without stripping skin of its natural moisture balance\r\nDermatologist tested to be gentle on sensitive skin',9168.00,2,'uploads/products/Cetaphil-GFC-236ml-1-600x600.png',4.59,0,1,'2026-08-16 17:20:29'),(31,4,'CETAPHIL BRIGHT HEALTHY RADIANCE BRIGHTNESS REVEAL CREAMY CLEANSER 100g','',7316.00,2,'uploads/products/CETAPHIL-BRIGHT-HEALTHY-RADIANCE-BRIGHTNESS-REVEAL-CREAMY-CLEANSER-100g-1.jpg',4.66,0,1,'2026-08-16 17:20:29'),(32,4,'CETAPHIL BRIGHT HEALTHY RADIANCE BRIGHTNESS REFRESH TONER 150ml','Instant luminosity and hydration for a soft and smooth skin',8055.00,1,'uploads/products/Cetaphil-toner-600x600.webp',4.56,0,1,'2026-08-16 17:20:29'),(33,4,'CETAPHIL BRIGHT HEALTHY RADIANCE BRIGHTENING NIGHT COMFORT CREAM 50g','',9897.00,1,'uploads/products/CETAPHIL-BRIGHT-HEALTHY-RADIANCE-BRIGHTENING-NIGHT-COMFORT-CREAM-50g-1.jpg',3.80,0,1,'2026-08-16 17:20:29'),(34,4,'CETAPHIL BRIGHT HEALTHY RADIANCE BRIGHTENING LOTION 245ml','',11759.00,1,'uploads/products/CETAPHIL-BRIGHT-HEALTHY-RADIANCE-BRIGHTENING-LOTION-245ml-1.jpg',4.31,0,1,'2026-08-16 17:20:29'),(35,9,'STAMINA PLUS -3 Dotted Condoms','Stamina Plus for longer lasting pleasure condom in a natural rubber latex, 4.5% benzocaine lubricated male dotted condom with reservoir end that helps delay the climax and prolong sexual excitement for longer-lasting lovemaking. Benzocaine also helps prevent premature ejaculation and provides greater staying power.',300.00,9,'uploads/products/stamina-plus-600x623.jpg',3.18,0,1,'2026-08-16 17:20:29'),(36,9,'ROUGH RIDER CONDOMS','3 Individually Tested Latex Condoms',250.00,12,'uploads/products/ROUGH-RIDER-600x600.jpg',3.94,0,1,'2026-08-16 17:20:29'),(37,9,'PREETHI SUPER','Studded condoms made with natural latex rubber, with over 100 raised rubber “studs” in all the right places to intensify friction and sensitivity. There is more stimulation and satisfaction for both parties.',120.00,1,'uploads/products/7f889e4f7dc2652333ffb8ff855a6c4b-1-600x600.png',3.18,0,1,'2026-08-16 17:20:29'),(38,9,'NATURES AID ACTIVE MAN ADVANCED FORMULA (60 TABLETS)','An advanced formulation to help aid sexual wellness.\r\nContains\r\nArginine\r\nDamiana\r\nKorean Ginseng\r\nMaca',7800.00,1,'uploads/products/Active-Man-Natures-Aid-600x600.jpg',3.06,0,1,'2026-08-16 17:20:29'),(39,9,'MOODS CONDOMS-DOTTED (3’s)','3 Condoms for new dimensions of pleasure',308.00,10,'uploads/products/moods-dotted-3-600x600.jpg',4.76,0,1,'2026-08-16 17:20:29'),(40,5,'SENSODYNE WHITENING 70g','Dentists recommend Sensodyne Toothpaste for sensitivity relief\r\nSensodyne Whitening toothpaste helps in relieving tooth sensitivity while restoring natural whiteness of the teeth\r\nIt has a formula that delivers sensitive teeth whitening by polishing away the surface stains, while working deep inside the teeth to build soothing protection around the nerves\r\nBrushing with this toothpaste leaves you with a pleasant taste, fresh breath and a whiter smile\r\nThis toothpaste also contains fluoride that strengthens teeth and prevents tooth decay\r\nProven to provide all the benefits of a regular toothpaste',900.00,1,'uploads/products/41tvUeOhFcL._SX522_.jpg',3.63,0,1,'2026-08-16 17:20:29'),(41,5,'REXIDIN MOUTH WASH 100ml','This medication is a chemical antiseptic, prescribed for gingivitis, cleansing skin and wound areas. It decreases the amount of bacteria in the mouth.',594.00,2,'uploads/products/Rexidine-Mouth-Wash-600x600.jpg',4.86,0,1,'2026-08-16 17:20:29'),(42,5,'ORREPASTE MOUTH ULCER GEL TREATMENT 5g','',470.34,2,'uploads/products/s-l500.jpg',4.43,0,1,'2026-08-16 17:20:29'),(43,5,'Oraleez Gel 10g','',370.00,1,'uploads/products/e23bf5a4abed09937c4765286a66e15b.png',4.57,0,1,'2026-08-16 17:20:29'),(44,5,'HEXIDINE® MOUTHWASH 160ml','Chlorhexidine Mouthwash B.P',857.00,10,'uploads/products/Hexidine-600x800.jpg',4.56,0,1,'2026-08-16 17:20:29'),(45,5,'CORSODYL MINT MOUTHWASH 200ml','Treats gum problems\r\nBleeding gums\r\nIrritated gums\r\nMouth ulcers',625.00,1,'uploads/products/corsodyl-mint-mouthwash-200ml-600x600.jpg',4.09,0,1,'2026-08-16 17:20:29'),(46,5,'BIOREPAIR PLUS SENSITIVE TEETH – 75ml','Forms a natural barrier against hypersensitivity on the tooth\r\nGives a quick relief from the pain caused by a high dentinal sensitivity\r\nCloses the dentinal tubules reducing the sensitivity from the first application',2260.00,1,'uploads/products/Biorepair_Plus_Denti_Sensibili_astuccio_-1-600x257.png',3.75,0,1,'2026-08-16 17:20:29'),(47,5,'BIOREPAIR PLUS PROTEZIONE TOTALE 75 ml TOOTHPASTE','Total protection toothpaste, every day protects the natural health of the teeth from the erosion of acids and bacteria that cause plaque, tartar and caries. Without fluoride',2260.00,1,'uploads/products/Biorepair_Plus_ProtezioneTotale_astuccio-600x257.png',3.50,0,1,'2026-08-16 17:20:29'),(48,12,'NATUROPATHICA COLLAGENIX BEAUTY SHOT 10,000MG (10 x 50ml)','Discover the secret to beautiful skin with Collagenix Beauty Shot 10,000mg – ultimate strength collagen in a delicious peach drink.\r\nCollagenix Beauty Shot 10000mg Can Help:\r\n-Boost and re-build collagen\r\n-Keep skin firm, plump and hydrated\r\n-Support collagen repair from within\r\n-Increase skin moisture and elasticity\r\n-Reduce wrinklesdone Beautiful hair and stronger nails',17900.00,10,'uploads/products/Collagenix-beauty-shot.webp',3.25,0,1,'2026-08-16 17:20:29'),(49,8,'WELLTEEN HIM','(Per pack)\r\nFrom studies to sports, Wellteen Him provides a broad range of important vitamins and minerals for increasingly hectic lifestyles.\r\nExclusively formulated for men aged 13 to 19.\r\n24 nutrients to help maintain vitality and wellness.\r\nIncludes Vitamins B6 and B12 which contribute to the reduction of tiredness and fatigue.\r\nFrom the UK’s No.1 vitamin company.',6248.00,2,'uploads/products/Wellteen-Him-600x600.jpg',4.75,0,1,'2026-08-16 17:20:29'),(50,8,'WELLMAN SPORT','(Per pack)\r\nA specialist supplement developed for men involved in sports, fitness, and gym. Includes 27 nutrients that support performance, including iron which contributes to the reduction of tiredness and fatigue. Wellman is also the UK’s No.1 men’s supplement brand.\r\nEnergy Levels – Contains vitamin B6 and B12 which contribute to normal energy release and red blood cell formation.\r\nAll Round Health & Vitality – Wellman sport doubles up as a comprehensive multivitamin to support general health & vitality.\r\nLGC Tested – Tested by LGC, a world-class anti-doping laboratory.',4500.00,10,'uploads/products/Wellwoman-Sport-600x600.jpg',3.02,0,1,'2026-08-16 17:20:29'),(51,8,'WELLMAN ORIGINAL','(Per pack)\r\nOur flagship multivitamin for men with 29 nutrients, specially developed to help maintain overall health and vitality. Wellman is also the UK’s No.1 men’s supplement brand.\r\nEnergy levels – with vitamins B6 and B12 which contribute to normal energy release.\r\nImmune system – with vitamins C and D which contribute to normal immune system function.',5775.00,7,'uploads/products/Wellman-Original-2-1-600x600.jpg',3.83,0,1,'2026-08-16 17:20:29'),(52,8,'WELLMAN 70+','(Per card of 15 capsules)\r\nA carefully formulated supplement for men 70 and over, specially developed to support areas of health particularly relevant to men in the later stages of life, such as; heart, brain, energy, vision, immune, and general health & vitality. From Wellman, the UK’s number 1 men’s supplement brand.\r\nBrain Function – With iron, iodine, and zinc contribute to normal cognitive function.\r\nHeart & Circulation – Includes vitamin B1 which contributes to the normal function of the heart.\r\nImmune Support – Contains vitamins C and D which contribute to the normal function of the immune system.\r\nVision – Contains vitamins C, E, and zinc which contribute to the maintenance of normal vision.',4884.00,1,'uploads/products/Wellman-70-600x600.jpg',3.07,0,1,'2026-08-16 17:20:29'),(53,11,'CERAVE THERAPEUTIC HAND CREAM 85g','CeraVe Therapeutic Hand Cream is a fast-absorbing, non-greasy formula that helps prevent and temporarily protect chafed, chapped, or cracked skin and contains three essential ceramides and hyaluronic acid to deliver intense moisture and help restore the protective skin barrier.\r\nMade For: Normal to Dry Skin\r\nHelps With: Dry Skin',6250.00,4,'uploads/products/cerave-hand-cream.png',4.89,0,1,'2026-08-16 17:20:29'),(54,3,'Triticum Biotin Plus Advanced Hair Serum 50ml','\"✓ Infused with natural oils: Rosemary, Castor, Jojoba, Peppermint, Eucalyptus, Coconut & Aloe Extract.\r\n✓ Deeply nourishes and hydrates the scalp.\r\n✓ Revitalizes hair follicles for healthy growth.\r\n✓ Enhanced with Ginger, Lavender & Biotin for hair vitality.\r\n✓ Fortified with essential Vitamins & Antioxidants.\r\n✓ Strengthens hair from root to tip.\r\n✓ Restores natural thickness, shine & softness.\"',6300.00,2,'uploads/products/T_01-600x600.webp',4.24,0,1,'2026-08-16 17:20:29'),(55,3,'SATINY 30ml','SATINY is a proven product that combines traditional wisdom and modern technology. Developed through research with the University of Colombo, this unique hair stimulator has nourishing ingredients. Its pleasant fragrance, inspired by global aroma therapy, promotes safer and healthier hair growth, improving overall quality of life.',3900.00,3,'uploads/products/Satiny-20ml-600x600.jpg',3.50,0,1,'2026-08-16 17:20:29'),(56,3,'Rowill Energizing Shampoo 150ml','To maintain healthy Hair and Scalp. Reduce Hair Thinning, For Weeks and Falling Hair, and Improves Hair texture and thickness.',2594.00,10,'uploads/products/Rowill-Energizing-Shampoo-150ml-600x600.jpg',3.81,0,1,'2026-08-16 17:20:29'),(57,3,'REDWIN COAL TAR FRAGRANCE SHAMPOO 250ml','Dry Scalp Management\r\nFor Healthy Hair and scalp\r\nRemoves Flacking Scalp\r\nRestores and maintains healthy clean hair and scalp\r\nSoothes and moisturises an itchy/dry scalp\r\npH balanced\r\nExtra Soothing, contains Aloe Vera',5930.00,10,'uploads/products/redwin-coal-tar-shampoo-600x600.webp',3.52,0,1,'2026-08-16 17:20:29'),(58,3,'PILOPEPTAN® WOMAN PROTEOKEL','Pilopeptan® Woman Proteokel\r\nRegenerative Anti-Hair Loss Ampoules\r\nPilopeptan® Woman Proteokel is a revolutionary topical treatment designed to effectively combat hair loss and regenerate hair follicle health.\r\nIts new mechanism of action, with patented technology based on proteoglycans, makes it an innovative option.\r\nIts ingredients also include Inositol and Caffeine, known for stimulating hair growth, and red apple extract, which acts as a powerful antioxidant, promoting stronger and healthier hair and preventing hair aging.\r\nIts convenient ampoule format to stimulate hair growth contains a non-greasy formula that is easily absorbed, leaving no residue on the scalp and without the need to rinse.\r\nIn addition to being the perfect complement to your hair anti-aging routine, this innovative hair regeneration treatment is especially suitable for people with sensitive scalps and is safe for use during pregnancy and breastfeeding.',19890.00,1,'uploads/products/PROTEOKEL-600x600.jpg',3.19,0,1,'2026-08-16 17:20:29'),(59,3,'PHOTONIC TAR SCALP TREATMENT 120ml','Fights Itching, scaling, and redness\r\nTreats Psoriasis, Dandruff, and Seborrheic dermatitis\r\nHelps treat Psoriasis symptoms recurrence\r\nSlows down production and controls the buildup of new scales on the scalp',4950.00,1,'uploads/products/Photonic-Tar-Shampoo-100ml-600x600.jpg',4.41,0,1,'2026-08-16 17:20:29'),(60,3,'PHOTONIC MAX SHAMPOO 120ml','Hair fall prevention energizing shampoo\r\nSmooth & Silky long Hair\r\nMaximum conditioning effect\r\nMoisturizer for daily use',4950.00,1,'uploads/products/Photonic-Max-1-600x600.jpg',3.45,0,1,'2026-08-16 17:20:29'),(61,3,'PHARMACERIS SPECIALIST ANTI-DANDRUFF SHAMPOO 250ml H-PURIN SPECIAL','Detail\r\nHigh tolerance and efficacy\r\nClinically and dermatologically tested\r\nDry and Oily Dandruff',5750.00,10,'uploads/products/H-Purin-special.jpg',3.04,0,1,'2026-08-16 17:20:29'),(62,3,'PHARMACERIS SOOTHING AND  MOISTURIZING MICELLAR SHAMPOO 250ML H-SENSITONIN','Detail\r\n86% soothes irritations\r\n79% relieves scalp itching\r\nhigh tolerance and efficacy\r\ndermatologically tested\r\nINDICATIONS\r\nThe shampoo is recommended for daily care of sensitive scalp prone to irritation and fine and delicate hair.',5950.00,10,'uploads/products/H-Sensitonin-600x800.jpg',3.82,0,1,'2026-08-16 17:20:29'),(63,3,'PHARMACERIS PROFESSIONAL DOUBLE ACTION SHAMPOO  250ml H-STIMUCLARIS','Professional Double action Shampoo\r\nHair Growth Stimulating\r\nAnti-Dandruff\r\nHigh Tolerance and efficacy\r\nDermatology Tested\r\nAdvanced formula designed for Very Sensitive skin\r\nEfficacy confirmed by tests on scalps with a tendency for dandruff and hair loss\r\nContains:\r\nnatural Growth factor FGF\r\nCaffeine\r\nPiroctone Olamine\r\nCitric Acid\r\nD-Panthenol',6100.00,10,'uploads/products/photo_2021-11-04_09-35-51-600x800.jpg',3.00,0,1,'2026-08-16 17:20:29'),(64,3,'PHARMACERIS INTENSIVE HAIR GROWTH STIMULATING SPRAY 125ml H-STIMUFORTEN','For weak & falling hair\r\nStops periodic and premature hair loss in men and women.\r\nHigh tolerance and efficacy.\r\nClinically and dermatologically tested.\r\nAdvanced formula designed for very sensitive skin/scalp.\r\n79% inhibits hair loss.\r\n75% increases hair volume.\r\n67% the scalp becomes less visible.\r\n63% stimulates the hair regrowth.\r\n83% does not electrify the hair.',7000.00,10,'uploads/products/Pharmaceris-Intensive-Hair-Growth-Stimulating-Spray-1-600x600.jpg',4.54,0,1,'2026-08-16 17:20:29'),(65,16,'Spirit Table Top Large Face Aneroid Sphygmomanometer (CK-143)','CK-143\r\nTable Top Model\r\nFeatures a space-saving designed metal base and a convenient storage for inflation system.\r\nSpecifications\r\nMeasurement Range: 0-300 mmHg\r\nInflation Method: Inflation and Air Release by Manual\r\nDisplay: Round Aneroid Scale (0-300mmHg)\r\nScale: Glow-in-Dark\r\nCuff Size: 511 x 145mm (Nurse Type)\r\nCuff Material: Deluxe Nylon\r\nBase color: Black\r\nSelf-zero function\r\nSpecialize: Heavy Base and Save Lots of Space\r\n \r\nWarranty: 6 months',28980.00,10,'uploads/products/Table-Top-Large-Face-Aneroid-Sphygmomanometer-CK-143-600x400.jpg',4.69,0,1,'2026-08-16 17:20:29'),(66,3,'PHARMACERIS HAIR GROWTH STIMULATING CONDITIONER 150ml H-STIMULINUM','Stimulates hir growth & density\r\nStops temporary or Premature balding\r\nStimulates Healthy, Thicker hair Growth\r\nContains:\r\nNatural Growth Factor FGF\r\nCaffeine\r\nBiotin\r\nD-Panthenol\r\nVitamin PP',5900.00,1,'uploads/products/photo_2021-11-04_09-35-51-600x800.jpg',4.83,0,1,'2026-08-16 17:20:29'),(67,16,'SPIRIT SPHYGMOMANOMETER – CK 153','CK-153\r\nTable Top Model\r\nIdentical features as CK-151A, with heavy base with perfect size less space usage',30000.00,10,'uploads/products/CK-153-600x450.jpg',3.09,0,1,'2026-08-16 17:20:29'),(68,16,'Spirit Majestic Series Adult Dual Head Stethoscope (CK-601P) – Black','CK-601P',9360.00,3,'uploads/products/Spirit-CK-601P-Black-2-600x600.jpg',3.96,0,1,'2026-08-16 17:20:29'),(69,16,'Spirit Majestic Series Adult Dual Head Stethoscope (CK-601P) – Gray','CK-601P',9360.00,1,'uploads/products/CK-601P.jpg',3.54,0,1,'2026-08-16 17:20:29'),(70,17,'Rossmax V3 Suction Unit','Ideal for tracheostomized patients, minor surgical applications, and post-operative therapy in home care and hospital.\r\nComfortable\r\nThe high-performance pump delivers -a 550mmHg vacuum and 16 liter/min flow rate.\r\nTriple Protection\r\nOverflow valve plus anti-bacterial filter and fool-proof design that prevents backflow of fluid and airborne contaminants from entering the pump.\r\nConvenient\r\nEasy to store using tubing holder and accessory compartment.\r\nErgonomic\r\nA comprehensive dashboard with an ergonomic view angle ensures smooth operation.\r\n2 years warranty\r\nThe catheter is not included',68400.00,3,'uploads/products/Suction-Unit-V3-600x400.jpg',4.80,0,1,'2026-08-16 17:20:29'),(71,17,'Rossmax Stethoscope','EB600',10800.00,1,'uploads/products/Rossmax-EB600-3-600x733.png',4.40,0,1,'2026-08-16 17:20:29'),(72,17,'Rossmax Portable Mesh Nebulizer NC200','MMAD ≤4 μm; Fine Particle Dose (FPD): 70-75%\r\nNebulization Rate ≥ 0.30 ml/min\r\nConsistent fine particle size for efficient respiratory treatment\r\nMESH Technology\r\nNoise level lower than 20dB\r\nHandheld design (42 x 70 x 117 mm)\r\nMouthpiece for adult and mask for child included\r\nCompatible with rechargeable batteries\r\nMicroUSB port\r\nBPA free medication bottle',32400.00,1,'uploads/products/NC200MeshNebulizer-600x600.webp',4.60,0,1,'2026-08-16 17:20:29'),(73,17,'Rossmax Piston Nebulizer NE 100','NE100\r\n– 2in1 cup for easy cleaning, assembly and disassembly\r\n– MMAD ≤2.6 μm; Fine Particle Dose (FPD): 80-85%\r\n– Nebulization Rate ≥ 0.30 ml/min\r\n– Consistent fine particle size for efficient respiratory treatment\r\n– Powerful Piston compressor\r\n– Mouthpiece and masks for adult and child included\r\n– Economic Design',13200.00,2,'uploads/products/Piston-Nebulizer-NE100-600x401.jpg',4.77,0,1,'2026-08-16 17:20:29'),(74,17,'Rossmax Piston Nebulizer NA 100','NA100\r\nParticle size created by our VAT bottle is around 2.19 μm (MMAD) tested by Cascade Impactor and 3.221 μm tested at Dv(50) by Malvern Spraytec. Compared to most bottles in the market, our proprietary bottle ensures more efficient and effective performance.\r\n– MMAD ≤2.2 μm; Fine Particle Dose (FPD): 80-85%\r\n– Consistent fine particle size for efficient respiratory treatment\r\n– Patented Valve Adjustable nebulizer bottle (VAT)\r\n– Powerful Piston compressor\r\n– Mouthpiece and masks for adult and child included\r\n– Built-in Compartment for Accessories',17040.00,4,'uploads/products/Piston-Nebulizer-NA100-600x400.jpg',3.08,0,1,'2026-08-16 17:20:29'),(75,17,'Rossmax Peak Flow Meter (Child)','PF120C\r\n– Compact, lightweight and portable\r\n– Built-in flexible three –zone management system\r\n– Easy – to- read numbers\r\n– Measurement Range 50-400 L/min\r\n– Accuracy and repeatability assurance\r\n– Suitable for use with disposable mouthpiece\r\n– Design for child\r\n– Washable',1920.00,1,'uploads/products/Rossmax-Peak-Flow-Meter-PF120C-Child-600x400.jpg',4.08,0,1,'2026-08-16 17:20:29'),(76,17,'ROSSMAX HANDHELD PULSE OXIMETER SA120','%Spo2 & Pulse Rate – Backlight LCD for easy reading %Spo2 & Pulse Rate\r\nPersonal SpO2, Pulse Rate, Memory Interval thresholds setting\r\nShielded Design blocks Ambient Light\r\nBiocompatibility & Anti-Allergic Design\r\nAlarm Function – The device delivers an audible warning when the measurement result exceeds the previously set SpO2/Pulse rate thresholds.\r\nSilent mode – Disables the beep sound during the measurement by pressing the “Sound” button.\r\nUp to 288 Memories – Could be programmed with 5 intervals: 0/5/10/30/60-minutes (“0” means no memory setup)\r\nPulse Strength Indicator\r\nIdeal for oxygen therapy assessment\r\nSuitable for sleep apnea screening\r\nIdeal for health management\r\nIdeal for spot-check & continuous monitoring',83100.00,1,'uploads/products/Rossmax-SA120-600x600.jpg',4.17,0,1,'2026-08-16 17:20:29'),(77,17,'Rossmax HA500 Infrared Non-Contact Thermometer','HA500\r\n-Measurement distance within 5cm\r\n-1-second measurement\r\n-Temple Temperature\r\n-Object temperature\r\n-Fever alarm\r\n-9 memories\r\n-°C / °F switchable\r\n-Backlight\r\n-Auto shut-off\r\n-Low battery indicator\r\n-Self-diagnosis for malfunction',12000.00,1,'uploads/products/Rossmax-HA500-600x600.jpg',3.59,0,1,'2026-08-16 17:20:29'),(78,9,'MOODS CONDOMS-DOTTED (12’s)','12 Condoms for new dimensions of pleasure',840.00,10,'uploads/products/moods-dotted-600x600.jpg',4.45,0,1,'2026-08-16 17:20:29'),(79,9,'MOODS CONDOMS-CHOCO (3’s)','3 condoms for the explorer at heart.',308.00,1,'uploads/products/moods-dotted-600x600.jpg',4.47,0,1,'2026-08-16 17:20:29'),(80,9,'MOODS CONDOMS-BANANA (3’s)','3 Condoms for surprising pleasures.',300.00,10,'uploads/products/moods-banana-600x600.jpg',4.02,0,1,'2026-08-16 17:20:29'),(81,4,'EVENTONE-C CREAM 30g','L-Glutathione + Vitamin C Cream\r\nTo treat Skin Darkness\r\nIntensive skin fairness\r\nFor Men & Women',6750.00,12,'uploads/products/Eventone-C-Cream.jpg',5.00,0,1,'2026-08-16 17:20:29'),(82,8,'Purifying Cleansing Mousse 150ml','Gentle facial cleanser. Added from dermatology prescription range.',4500.00,25,'uploads/products/rx-seed-1.png',4.50,0,1,'2026-11-01 17:17:40'),(83,1,'Epiduo Gel 30g (Adapalene + Benzoyl Peroxide)','Acne treatment gel. Added from dermatology prescription range.',7200.00,17,'uploads/products/rx-seed-2.png',4.50,1,1,'2026-11-01 17:17:40'),(84,1,'Spironolactone 50mg — 28 tablets','Tablets 50mg. Added from dermatology prescription range.',1800.00,40,'uploads/products/rx-seed-3.png',4.50,1,1,'2026-11-01 17:17:40'),(85,1,'Microdox 100mg (Doxycycline) — 30 capsules','Antibiotic capsules. Added from dermatology prescription range.',1500.00,34,'uploads/products/rx-seed-4.png',4.50,1,1,'2026-11-01 17:17:40'),(86,8,'Salic Leave-On Gel 2% 50ml','Salicylic acid gel. Added from dermatology prescription range.',3900.00,22,'uploads/products/rx-seed-5.png',4.50,0,1,'2026-11-01 17:17:40'),(87,8,'SunRx 42 SC Sunscreen SPF42 50ml','Broad spectrum SPF42. Added from dermatology prescription range.',5400.00,29,'uploads/products/rx-seed-6.png',4.50,0,1,'2026-11-01 17:17:40'),(88,1,'Yasmin Tablets — 21s (1 card)','Oral tablets. Added from dermatology prescription range.',2950.00,24,'uploads/products/rx-seed-7.png',4.50,1,1,'2026-11-01 17:17:40'),(89,2,'Opti-Lumineux Brightening Capsules — 30s','Skin brightening. Added from dermatology prescription range.',6500.00,20,'uploads/products/rx-seed-8.png',4.50,0,1,'2026-11-01 17:17:40');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `name` varchar(60) NOT NULL,
  `value` text DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('currency','LKR'),('free_shipping_over','5000'),('pharmacy_address','123 Galle Road, Colombo 03, Sri Lanka'),('pharmacy_hours','Mon–Sat 8.00am – 10.00pm, Sun 9.00am – 6.00pm'),('pharmacy_name','Medi-O Pharmacy'),('pharmacy_phone','0112345678'),('pharmacy_whatsapp','94771234567'),('shipping_fee','250');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `google_id` varchar(64) DEFAULT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('customer','pharmacist','admin') NOT NULL DEFAULT 'customer',
  `qr_token` char(40) NOT NULL,
  `whatsapp_opt_in` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `phone_verified` tinyint(1) NOT NULL DEFAULT 0,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_qr` (`qr_token`),
  UNIQUE KEY `uq_users_email` (`email`),
  UNIQUE KEY `uq_users_phone` (`phone`),
  UNIQUE KEY `uq_users_google` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Medi-O Admin','admin@medi-o.lk','94770000001','$2y$10$QkDhbDjFJjIMddjcEShD8ujX7U1.f/S.xC/fl.0hxw1Geqdbx42uu',NULL,NULL,NULL,'admin','c473a0656ff3e06b11e8cedfc146c35a289d2e94',1,1,1,NULL,NULL,'2026-08-16 17:20:09'),(2,'Nimal Perera (Pharmacist)','pharmacist@medi-o.lk','94770000002','$2y$10$9yBsSFAO3WK3LuAyZkJP5.twa65.SdhuoUOm/F3rW2hcQoRvU/h1.',NULL,NULL,NULL,'pharmacist','cdb5cdf2e6fbc7569cd1a7b9936ba7c5b153028b',1,1,1,NULL,NULL,'2026-08-16 17:20:09'),(3,'Demo Customer','demo@medi-o.lk','94771234567','$2y$10$MbGYRpHVhYXjeS25fmYkU.gQOO4M7d1R02vPMn6DE4TscSMQUwtDC',NULL,NULL,NULL,'customer','eb0da471511e6e4585228472510790084ca2f5af',1,1,1,NULL,NULL,'2026-08-16 17:20:09'),(7,'Samathri Abhayapala','samathri.15@gmail.com','94767170438','$2y$10$BUNkmjXOQfgqMjqsbSIV3uxf9Xr4W4TzwwelXUnWML6/xAOZMpVgK',NULL,NULL,NULL,'customer','8fff7704b2f7128c4812de33ddb91fe63f9f3631',1,1,0,NULL,NULL,'2026-09-06 07:41:01'),(9,'Kithmini Yasasvi','kithmini.demo@medi-o.lk','94761234999','$2y$10$TpwsSOR06067BTJgR48Hq.gPT51FNKP3qpO1uv3szyyaaa9/bREu2',NULL,NULL,NULL,'customer','50793093980ce937491bba55e960c6fabeca4c72',1,1,0,NULL,NULL,'2026-11-01 17:18:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verification_codes`
--

DROP TABLE IF EXISTS `verification_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verification_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `channel` enum('email','phone','reset') NOT NULL,
  `code_hash` char(64) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_vc_user_channel` (`user_id`,`channel`),
  CONSTRAINT `fk_vc_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verification_codes`
--

LOCK TABLES `verification_codes` WRITE;
/*!40000 ALTER TABLE `verification_codes` DISABLE KEYS */;
INSERT INTO `verification_codes` VALUES (7,7,'phone','e98af2bd49c23741f96c73e4807a44838ad14d4a45b47c1d7be413e7ca2d3c3e',0,'2026-09-06 13:22:26','2026-09-06 07:42:26'),(9,7,'reset','ac3f5632a2d377da96f07803ea501eaa50ebb35f7380b24dcc4ab071c1b4dcfa',0,'2026-10-18 11:05:44','2026-10-18 05:25:44');
/*!40000 ALTER TABLE `verification_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `whatsapp_outbox`
--

DROP TABLE IF EXISTS `whatsapp_outbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `whatsapp_outbox` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `status` enum('queued','sent','failed','logged') NOT NULL DEFAULT 'queued',
  `response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_wa_user` (`user_id`),
  CONSTRAINT `fk_wa_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `whatsapp_outbox`
--

LOCK TABLES `whatsapp_outbox` WRITE;
/*!40000 ALTER TABLE `whatsapp_outbox` DISABLE KEYS */;
INSERT INTO `whatsapp_outbox` VALUES (1,NULL,'94759876543','Medi-O: Your phone verification code is 051652. It expires in 10 minutes. Never share this code.','logged',NULL,'2026-08-30 07:30:37',NULL),(2,NULL,'94759876543','Medi-O: Your password reset code is 124767. It expires in 10 minutes. Never share this code.','logged',NULL,'2026-08-30 07:31:31',NULL),(3,NULL,'94767170438','Medi-O: Your phone verification code is 040789. It expires in 10 minutes. Never share this code.','logged',NULL,'2026-08-30 08:33:59',NULL),(4,NULL,'94767170438','Medi-O test: your pharmacy system\'s WhatsApp integration is working! 💊','sent','{\"messaging_product\":\"whatsapp\",\"contacts\":[{\"input\":\"94767170438\",\"wa_id\":\"94767170438\"}],\"messages\":[{\"id\":\"wamid.HBgLOTQ3NjcxNzA0MzgVAgARGBIxRjhEQjlFMEUzMkNDRTA4OEUA\"}]}','2026-09-06 07:39:32','2026-09-06 13:09:34'),(5,7,'94767170438','Medi-O: Your phone verification code is 336273. It expires in 10 minutes. Never share this code.','sent','{\"messaging_product\":\"whatsapp\",\"contacts\":[{\"input\":\"94767170438\",\"wa_id\":\"94767170438\"}],\"messages\":[{\"id\":\"wamid.HBgLOTQ3NjcxNzA0MzgVAgARGBI0MDVFRDlBQ0I3QjRGNzU3MzYA\"}]}','2026-09-06 07:41:15','2026-09-06 13:11:16'),(6,7,'94767170438','Medi-O: Your phone verification code is 605094. It expires in 10 minutes. Never share this code.','sent','{\"messaging_product\":\"whatsapp\",\"contacts\":[{\"input\":\"94767170438\",\"wa_id\":\"94767170438\"}],\"messages\":[{\"id\":\"wamid.HBgLOTQ3NjcxNzA0MzgVAgARGBI2QjYxODAzRUVFREExRTMwMUYA\"}]}','2026-09-06 07:42:26','2026-09-06 13:12:33'),(7,3,'94771234567','Medi-O: Prescription approved ✅\nYour prescription was verified by our pharmacist. Your medication plan (2 medicines) is now in your account.','failed','{\"error\":{\"message\":\"Authentication Error\",\"code\":190,\"type\":\"OAuthException\",\"fbtrace_id\":\"AZ17fm__C2bZD4V9J5ETsaU\"}}','2026-09-20 06:28:14','2026-09-20 11:58:15'),(8,7,'94767170438','Medi-O: Your verification code is 469698. It expires in 10 minutes. Never share this code.','failed','{\"error\":{\"message\":\"Authentication Error\",\"code\":190,\"type\":\"OAuthException\",\"fbtrace_id\":\"AI3FzjuM0cEz7kGrySVCDoT\"}}','2026-10-18 05:25:44','2026-10-18 10:55:47'),(9,3,'94771234567','Medi-O: Your order #MO2611017F2B5 was collected. Thank you! 💊','failed','{\"error\":{\"message\":\"Authentication Error\",\"code\":190,\"type\":\"OAuthException\",\"fbtrace_id\":\"A2xpLXOOELKqjjddYhukgw2\"}}','2026-11-01 14:30:00','2026-11-01 20:00:01'),(10,NULL,'94781112233','Medi-O: Your verification code is 578520. It expires in 10 minutes. Never share this code.','failed','{\"error\":{\"message\":\"Authentication Error\",\"code\":190,\"type\":\"OAuthException\",\"fbtrace_id\":\"AmPnWaSm_tXIqyC4ljGYbHY\"}}','2026-11-01 14:32:41','2026-11-01 20:02:43'),(11,3,'94771234567','Medi-O: Your verification code is 484709. It expires in 10 minutes. Never share this code.','failed','{\"error\":{\"message\":\"Authentication Error\",\"code\":190,\"type\":\"OAuthException\",\"fbtrace_id\":\"AKDjcxlmItNTZjHH6hvol8V\"}}','2026-11-01 14:36:55','2026-11-01 20:06:59'),(12,NULL,'94767170438','Medi-O: WhatsApp connection restored ✅ (token test)','sent','{\"messaging_product\":\"whatsapp\",\"contacts\":[{\"input\":\"94767170438\",\"wa_id\":\"94767170438\"}],\"messages\":[{\"id\":\"wamid.HBgLOTQ3NjcxNzA0MzgVAgARGBI0OUI1ODZFNDEyMkM2RjY4Q0YA\"}]}','2026-11-01 16:52:50','2026-11-01 22:22:53'),(13,3,'94771234567','Medi-O: Order #MO26110188B33: Your order is being prepared.','failed','{\"error\":{\"message\":\"(#131030) Recipient phone number not in allowed list\",\"code\":131030,\"type\":\"OAuthException\",\"error_data\":{\"messaging_product\":\"whatsapp\",\"details\":\"Recipient phone number not in allowed list: Add recipient phone number to recipient list and try again.\"},\"fbtrace_id\":\"AMqasGOcZBdL6If3vqLreQt\"}}','2026-11-01 17:14:54','2026-11-01 22:44:55'),(14,3,'94771234567','Medi-O: Order #MO26110188B33: Your order is out for delivery!','failed','{\"error\":{\"message\":\"(#131030) Recipient phone number not in allowed list\",\"code\":131030,\"type\":\"OAuthException\",\"error_data\":{\"messaging_product\":\"whatsapp\",\"details\":\"Recipient phone number not in allowed list: Add recipient phone number to recipient list and try again.\"},\"fbtrace_id\":\"ArJkxICb3awSCCQErH2vBQP\"}}','2026-11-01 17:15:03','2026-11-01 22:45:04'),(15,9,'94761234999','Medi-O: Your verification code is 515722. It expires in 10 minutes. Never share this code.','failed','{\"error\":{\"message\":\"(#131030) Recipient phone number not in allowed list\",\"code\":131030,\"type\":\"OAuthException\",\"error_data\":{\"messaging_product\":\"whatsapp\",\"details\":\"Recipient phone number not in allowed list: Add recipient phone number to recipient list and try again.\"},\"fbtrace_id\":\"AVXSSjgBzg7ALH0dlYZ3yEE\"}}','2026-11-01 17:18:29','2026-11-01 22:48:30'),(16,9,'94761234999','Medi-O: Your verification code is 894665. It expires in 10 minutes. Never share this code.','failed','{\"error\":{\"message\":\"(#131030) Recipient phone number not in allowed list\",\"code\":131030,\"type\":\"OAuthException\",\"error_data\":{\"messaging_product\":\"whatsapp\",\"details\":\"Recipient phone number not in allowed list: Add recipient phone number to recipient list and try again.\"},\"fbtrace_id\":\"AzF6MJRt1O6o7hhSA-NQtmX\"}}','2026-11-01 17:20:06','2026-11-01 22:50:07'),(17,9,'94761234999','Medi-O: Your verification code is 071622. It expires in 10 minutes. Never share this code.','failed','{\"error\":{\"message\":\"(#131030) Recipient phone number not in allowed list\",\"code\":131030,\"type\":\"OAuthException\",\"error_data\":{\"messaging_product\":\"whatsapp\",\"details\":\"Recipient phone number not in allowed list: Add recipient phone number to recipient list and try again.\"},\"fbtrace_id\":\"Amv9HR7lmnwxq1Q5CX4h6ld\"}}','2026-11-01 17:21:44','2026-11-01 22:51:45'),(18,9,'94761234999','Medi-O: Prescription approved ✅\nYour prescription was verified by our pharmacist. Your medication plan (8 medicines) is now in your account.','failed','{\"error\":{\"message\":\"(#131030) Recipient phone number not in allowed list\",\"code\":131030,\"type\":\"OAuthException\",\"error_data\":{\"messaging_product\":\"whatsapp\",\"details\":\"Recipient phone number not in allowed list: Add recipient phone number to recipient list and try again.\"},\"fbtrace_id\":\"AtyaF9cBlL7AL09CNlR2NJm\"}}','2026-11-01 17:22:38','2026-11-01 22:52:39'),(19,9,'94761234999','Medi-O: Order #MO261101F5D9B: Your order is out for delivery!','failed','{\"error\":{\"message\":\"(#131030) Recipient phone number not in allowed list\",\"code\":131030,\"type\":\"OAuthException\",\"error_data\":{\"messaging_product\":\"whatsapp\",\"details\":\"Recipient phone number not in allowed list: Add recipient phone number to recipient list and try again.\"},\"fbtrace_id\":\"AIs14lWUqbsGX8RrzDkrk8H\"}}','2026-11-01 17:24:49','2026-11-01 22:54:50'),(20,9,'94761234999','Medi-O: Order #MO261101F5D9B: Your order is complete. Get well soon!','failed','{\"error\":{\"message\":\"(#131030) Recipient phone number not in allowed list\",\"code\":131030,\"type\":\"OAuthException\",\"error_data\":{\"messaging_product\":\"whatsapp\",\"details\":\"Recipient phone number not in allowed list: Add recipient phone number to recipient list and try again.\"},\"fbtrace_id\":\"AlhdvsMFI6EWiPmN_k6Fu0N\"}}','2026-11-01 17:25:18','2026-11-01 22:55:19');
/*!40000 ALTER TABLE `whatsapp_outbox` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-01 22:57:07
