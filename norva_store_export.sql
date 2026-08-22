CREATE DATABASE IF NOT EXISTS norva_store;
USE norva_store;

-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: norva_store
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
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `sort_order` int(5) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'Top Up Game Termurah & Tercepat 24 Jam','Proses otomatis 1-5 detik langsung masuk ke akun game Anda!','https://res.cloudinary.com/norva-media/image/upload/v1/banners/banner1.jpg','#games',1,'active','2026-08-23 00:26:20','2026-08-23 00:26:20'),(2,'Flash Sale Mobile Legends & Free Fire','Diskon spesial Weekly Diamond Pass dan Diamonds setiap hari.','https://res.cloudinary.com/norva-media/image/upload/v1/banners/banner2.jpg','/order/mobile-legends',2,'active','2026-08-23 00:26:20','2026-08-23 00:26:20');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_categories`
--

DROP TABLE IF EXISTS `game_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `sort_order` int(5) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_categories`
--

LOCK TABLES `game_categories` WRITE;
/*!40000 ALTER TABLE `game_categories` DISABLE KEYS */;
INSERT INTO `game_categories` VALUES (1,'Games Mobile','games-mobile','smartphone',1,'active','2026-08-23 00:26:20','2026-08-23 00:26:20'),(2,'Games PC','games-pc','monitor',2,'active','2026-08-23 00:26:20','2026-08-23 00:26:20'),(3,'Voucher','voucher','ticket',3,'active','2026-08-23 00:26:20','2026-08-23 00:26:20'),(4,'Entertainment','entertainment','tv',4,'active','2026-08-23 00:26:20','2026-08-23 00:26:20'),(5,'Pulsa & PLN','pulsa-pln','zap',5,'active','2026-08-23 00:26:20','2026-08-23 00:26:20');
/*!40000 ALTER TABLE `game_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `developer` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `target_input_type` varchar(50) NOT NULL DEFAULT 'single',
  `target_input_label_1` varchar(100) NOT NULL DEFAULT 'User ID',
  `target_input_label_2` varchar(100) DEFAULT NULL,
  `target_input_placeholder_1` varchar(150) NOT NULL DEFAULT 'Masukkan User ID',
  `target_input_placeholder_2` varchar(150) DEFAULT NULL,
  `server_list` text DEFAULT NULL,
  `check_id_endpoint` varchar(50) DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(5) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (1,1,'Mobile Legends: Bang Bang','mobile-legends',NULL,'Moonton','/uploads/games/logo-ml.png','https://assets.lapaktrip.com/banners/banner-mlbb.jpg','Untuk mengetahui User ID Anda, silakan klik menu profile di bagian kiri atas pada menu utama game. Contoh: 12345678 (1234). User ID adalah 12345678 dan Zone ID adalah 1234.','double','User ID','Zone ID','',NULL,NULL,'mlbb',1,1,1,'2026-08-23 00:26:20','2026-08-23 02:32:30'),(2,1,'Free Fire','free-fire','Garena','Garena','https://cdn.unipin.com/images/icon_product_pages/1579591461-Free-Fire.png','https://assets.lapaktrip.com/banners/banner-ff.jpg','Untuk menemukan Player ID Anda, klik avatar Anda di pojok kiri atas layar game. Player ID Anda akan terlihat di bawah nama panggilan Anda.','single','Player ID',NULL,'Masukkan Player ID (Contoh: 123456789)',NULL,NULL,'ff',1,1,2,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(3,1,'Genshin Impact','genshin-impact','HoYoverse','HoYoverse','https://cdn.unipin.com/images/icon_product_pages/1601366113-Genshin-Impact.png','https://assets.lapaktrip.com/banners/banner-genshin.jpg','Buka menu Paimon di pojok kiri atas. UID Anda tertera di bawah avatar profil karakter atau di pojok kanan bawah layar game.','server_dropdown','UID HoYoverse','Server','Contoh: 812345678','Pilih Server','[{\"code\":\"os_asia\",\"name\":\"Asia\"},{\"code\":\"os_usa\",\"name\":\"America\"},{\"code\":\"os_euro\",\"name\":\"Europe\"},{\"code\":\"os_cht\",\"name\":\"TW \\/ HK \\/ MO\"}]','genshin',1,1,3,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(4,2,'Valorant','valorant','Riot Games','Riot Games','https://cdn.unipin.com/images/icon_product_pages/1591176251-Valorant.png','https://assets.lapaktrip.com/banners/banner-valorant.jpg','Masukkan Riot ID lengkap beserta Tagline. Contoh: NorvaGamer#IND','single','Riot ID & Tagline',NULL,'Contoh: Username#TAG',NULL,NULL,'valorant',1,1,4,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(5,1,'Honor of Kings','honor-of-kings','Level Infinite','Tencent Games','https://cdn.unipin.com/images/icon_product_pages/1718873721-Honor-of-Kings.png','https://assets.lapaktrip.com/banners/banner-hok.jpg','Buka profil game Anda, Player ID dapat disalin dari menu profile setting.','single','Player ID',NULL,'Masukkan Player ID HoK',NULL,NULL,'hok',1,1,5,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(6,1,'PUBG Mobile','pubg-mobile','Tencent / Krafton','Level Infinite','https://cdn.unipin.com/images/icon_product_pages/1579591834-PUBG-Mobile.png','https://assets.lapaktrip.com/banners/banner-pubgm.jpg','Buka profil akun game PUBG Mobile Anda di pojok kanan atas untuk melihat Nomor ID Karakter.','single','User ID Karakter',NULL,'Contoh: 5123456789',NULL,NULL,'pubgm',1,1,6,'2026-08-23 00:26:20','2026-08-23 00:26:20');
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026-08-23-000001','App\\Database\\Migrations\\CreateNorvaStoreSchema','default','App',1787419528,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(50) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `game_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `payment_method_id` int(11) unsigned NOT NULL,
  `target_user_id` varchar(100) NOT NULL,
  `target_zone_id` varchar(100) DEFAULT NULL,
  `target_server` varchar(100) DEFAULT NULL,
  `target_nickname` varchar(150) DEFAULT NULL,
  `customer_phone` varchar(30) NOT NULL,
  `price_product` decimal(15,2) NOT NULL DEFAULT 0.00,
  `price_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `unique_code` int(5) NOT NULL DEFAULT 0,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','paid','expired','cancelled','refunded') NOT NULL DEFAULT 'unpaid',
  `delivery_status` enum('pending','processing','success','failed') NOT NULL DEFAULT 'pending',
  `provider_name` varchar(50) NOT NULL DEFAULT 'manual',
  `provider_trx_id` varchar(100) DEFAULT NULL,
  `provider_response` text DEFAULT NULL,
  `provider_sn` varchar(255) DEFAULT NULL,
  `qris_payload` text DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_no` (`invoice_no`),
  KEY `payment_status` (`payment_status`),
  KEY `delivery_status` (`delivery_status`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (12,'INV-20260823-F5B641',NULL,1,15,1,'580003892','8335',NULL,'FA 🦋','085157352214',1000.00,0.00,1,0.00,1001.00,'unpaid','pending','manual',NULL,NULL,NULL,'00020101021226610014COM.GO-JEK.WWW01189360091439291749270210G9291749270303UMI51440014ID.CO.QRIS.WWW0215ID10265758171390303UMI520457325303360540410015802ID5923Norva Store, Elektronik6005BOGOR61051635062070703A016304A32B','2026-08-23 03:29:39',NULL,NULL,'2026-08-23 03:14:39','2026-08-23 03:14:39'),(13,'INV-20260823-36DA0B',NULL,1,15,1,'580003892','8335',NULL,NULL,'085157352214',1000.00,0.00,2,0.00,1002.00,'unpaid','pending','manual',NULL,NULL,NULL,'00020101021226610014COM.GO-JEK.WWW01189360091439291749270210G9291749270303UMI51440014ID.CO.QRIS.WWW0215ID10265758171390303UMI520457325303360540410025802ID5923Norva Store, Elektronik6005BOGOR61051635062070703A01630499F3','2026-08-23 03:35:19',NULL,NULL,'2026-08-23 03:20:19','2026-08-23 03:20:19'),(14,'INV-20260823-F2E91D',NULL,1,15,1,'580003892','8335',NULL,'FA 🦋','085157352214',1000.00,0.00,1,0.00,1001.00,'unpaid','pending','manual',NULL,NULL,NULL,'00020101021226610014COM.GO-JEK.WWW01189360091439291749270210G9291749270303UMI51440014ID.CO.QRIS.WWW0215ID10265758171390303UMI520457325303360540410015802ID5923Norva Store, Elektronik6005BOGOR61051635062070703A016304A32B','2026-08-23 03:45:07',NULL,NULL,'2026-08-23 03:30:07','2026-08-23 03:30:07');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_methods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `group_name` varchar(50) NOT NULL DEFAULT 'QRIS',
  `type` varchar(50) NOT NULL DEFAULT 'qris',
  `fee_flat` decimal(15,2) NOT NULL DEFAULT 0.00,
  `fee_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `min_amount` decimal(15,2) NOT NULL DEFAULT 1000.00,
  `max_amount` decimal(15,2) NOT NULL DEFAULT 10000000.00,
  `icon_url` varchar(255) DEFAULT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `sort_order` int(5) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_methods`
--

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
INSERT INTO `payment_methods` VALUES (1,'QRIS Dinamis (All Payment Realtime)','qris_auto','QRIS Realtime','qris',0.00,0.00,0.00,0.00,'https://res.cloudinary.com/norva-media/image/upload/v1/payments/qris.png','QRIS All Payment','Norva Store, Elektronik','1. Buka aplikasi E-Wallet (GoPay, OVO, DANA, ShopeePay, LinkAja) atau Mobile Banking (BCA, Mandiri, BRI, BNI, Jago, dll).\r\n2. Pilih menu Bayar / Scan QRIS.\r\n3. Scan Kode QRIS yang muncul di layar.\r\n4. Nominal akan otomatis terisi sesuai dengan total invoice termasuk kode unik.\r\n5. Masukkan PIN Anda dan konfirmasi pembayaran.\r\n6. Sistem akan otomatis memproses pesanan Anda dalam hitungan detik!','active',0,'2026-08-23 00:26:20','2026-08-23 03:07:14'),(2,'Transfer Bank BCA (Otomatis)','bca_auto','Bank Transfer','bank_transfer',0.00,0.00,10000.00,50000000.00,'https://res.cloudinary.com/norva-media/image/upload/v1/payments/bca.png','1234567890','NORVAGO','1. Transfer ke rekening BCA: 1234567890 a/n NORVAGO.\n2. Pastikan nominal transfer PERSIS sesuai total tagihan hingga 3 digit terakhir (kode unik).\n3. Pembayaran akan terverifikasi secara otomatis.','inactive',2,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(3,'Transfer Bank Mandiri (Otomatis)','mandiri_auto','Bank Transfer','bank_transfer',0.00,0.00,10000.00,50000000.00,'https://res.cloudinary.com/norva-media/image/upload/v1/payments/mandiri.png','1370012345678','NORVAGO','1. Transfer ke rekening Mandiri: 1370012345678 a/n NORVAGO.\n2. Transfer tepat sesuai nominal hingga 3 digit terakhir.','inactive',3,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(4,'DANA Transfer (Otomatis)','dana_auto','E-Wallet','ewallet',0.00,0.00,5000.00,10000000.00,'https://res.cloudinary.com/norva-media/image/upload/v1/payments/dana.png','081234567890','NORVAGO','1. Buka aplikasi DANA -> Kirim Uang.\n2. Masukkan nomor HP: 081234567890.\n3. Masukkan nominal PERSIS hingga 3 digit terakhir kode unik.','inactive',4,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(5,'Saldo Akun Member (Potong Saldo)','balance_instant','Saldo Member','balance',0.00,0.00,1000.00,50000000.00,'https://res.cloudinary.com/norva-media/image/upload/v1/payments/wallet.png','Internal Wallet','Saldo Akun','1. Pembayaran akan dipotong langsung dari saldo akun Anda.\n2. Pesanan akan langsung diproses detik ini juga tanpa antre.','inactive',5,'2026-08-23 00:26:20','2026-08-23 00:26:20');
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `sort_order` int(5) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (1,1,'Diamonds Fast Server',1,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(2,1,'Membership & Pass',2,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(3,2,'Diamonds Garena',1,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(4,3,'Genesis Crystals & Blessing',1,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(5,4,'Valorant Points (VP)',1,'2026-08-23 00:26:20','2026-08-23 00:26:20');
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `provider_code` varchar(50) NOT NULL DEFAULT 'manual',
  `provider_sku` varchar(100) DEFAULT NULL,
  `price_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `price_normal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `price_gold` decimal(15,2) NOT NULL DEFAULT 0.00,
  `price_reseller` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_flash_sale` tinyint(1) NOT NULL DEFAULT 0,
  `flash_sale_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `flash_sale_end` datetime DEFAULT NULL,
  `status` enum('available','empty') NOT NULL DEFAULT 'available',
  `icon_url` varchar(255) DEFAULT NULL,
  `sort_order` int(5) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `game_id` (`game_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,1,'86 Diamonds (78+8 Bonus)','MLBB-86','manual','MLBB86',18500.00,20500.00,19500.00,18800.00,0,0.00,NULL,'available','diamond',1,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(2,1,1,'172 Diamonds (156+16 Bonus)','MLBB-172','manual','MLBB172',37000.00,41000.00,39000.00,37500.00,0,0.00,NULL,'available','diamond',2,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(3,1,1,'257 Diamonds (234+23 Bonus)','MLBB-257','manual','MLBB257',55000.00,61000.00,58000.00,56000.00,1,57500.00,'2026-08-30 00:26:20','available','diamond',3,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(4,1,1,'344 Diamonds','MLBB-344','manual','MLBB344',74000.00,81500.00,78000.00,75000.00,0,0.00,NULL,'available','diamond',4,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(5,1,1,'706 Diamonds','MLBB-706','manual','MLBB706',148000.00,163000.00,156000.00,150000.00,0,0.00,NULL,'available','diamond',5,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(6,1,2,'Weekly Diamond Pass (WDP)','MLBB-WDP','manual','MLBBWDP',25000.00,27500.00,26500.00,25500.00,1,26900.00,'2026-08-30 00:26:20','available','pass',6,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(7,1,2,'Twilight Pass','MLBB-TWILIGHT','manual','MLBBTWILIGHT',135000.00,148000.00,142000.00,137000.00,0,0.00,NULL,'available','pass',7,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(8,2,3,'70 Diamonds','FF-70','manual','FF70',9000.00,10000.00,9500.00,9200.00,0,0.00,NULL,'available','diamond',1,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(9,2,3,'140 Diamonds','FF-140','manual','FF140',18000.00,20000.00,19000.00,18400.00,0,0.00,NULL,'available','diamond',2,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(10,2,3,'355 Diamonds','FF-355','manual','FF355',45000.00,50000.00,47500.00,46000.00,1,47900.00,'2026-08-30 00:26:20','available','diamond',3,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(11,3,4,'Blessing of the Welkin Moon','GI-WELKIN','manual','GIWELKIN',65000.00,73000.00,69000.00,66500.00,0,0.00,NULL,'available','welkin',1,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(12,3,4,'300+30 Genesis Crystals','GI-330','manual','GI330',65000.00,73000.00,69000.00,66500.00,0,0.00,NULL,'available','crystal',2,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(13,4,5,'475 Valorant Points','VAL-475','manual','VAL475',47000.00,53000.00,50000.00,48500.00,0,0.00,NULL,'available','vp',1,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(14,4,5,'1000 Valorant Points','VAL-1000','manual','VAL1000',97000.00,108000.00,103000.00,99500.00,0,0.00,NULL,'available','vp',2,'2026-08-23 00:26:20','2026-08-23 00:26:20'),(15,1,1,'1 Diamond','MLBB-1','manual','ML-1',500.00,1000.00,0.00,0.00,0,0.00,NULL,'available',NULL,0,'2026-08-23 01:59:24','2026-08-23 02:58:16');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qris_mutations`
--

DROP TABLE IF EXISTS `qris_mutations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qris_mutations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `source` varchar(50) NOT NULL DEFAULT 'webhook',
  `raw_content` text DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `matched_order_id` int(11) unsigned DEFAULT NULL,
  `status` enum('matched','unmatched','ignored') NOT NULL DEFAULT 'unmatched',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `amount` (`amount`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qris_mutations`
--

LOCK TABLES `qris_mutations` WRITE;
/*!40000 ALTER TABLE `qris_mutations` DISABLE KEYS */;
INSERT INTO `qris_mutations` VALUES (1,'DANA_QRIS_REALTIME','{\"amount\":21382,\"source\":\"DANA_QRIS_REALTIME\",\"description\":\"QRIS DANA Dana Masuk Rp 21382.00\"}',21382.00,'QRIS DANA Dana Masuk Rp 21382.00',NULL,'unmatched','2026-08-23 00:37:37'),(2,'GOPAY_MERCHANT','{\"amount\":20501,\"source\":\"GOPAY_MERCHANT\",\"description\":\"GoPay Merchant Dana Masuk Rp 20.501\"}',20501.00,'GoPay Merchant Dana Masuk Rp 20.501',NULL,'unmatched','2026-08-23 02:14:06');
/*!40000 ALTER TABLE `qris_mutations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) NOT NULL DEFAULT 'general',
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','Norvago','general'),(2,'site_tagline','Platform Top Up Game Tercepat & Terpercaya','general'),(3,'site_description','Platform top up game online terpercaya nomor 1 di Indonesia dengan harga murah, proses instan 1-5 detik dan pembayaran QRIS All Payment.','general'),(4,'site_logo','','general'),(5,'site_favicon','','general'),(6,'whatsapp_cs','6281234567890','contact'),(7,'instagram_url','https://instagram.com/norvastore','contact'),(8,'telegram_channel','https://t.me/norvastore','contact'),(9,'qris_static_payload','00020101021126610014COM.GO-JEK.WWW01189360091439291749270210G9291749270303UMI51440014ID.CO.QRIS.WWW0215ID10265758171390303UMI5204573253033605802ID5923Norva Store, Elektronik6005BOGOR61051635062070703A016304EDAA','qris'),(10,'qris_merchant_name','NORVA STORE','qris'),(11,'qris_city','KP.CISENTUL','qris'),(12,'webhook_secret_key','880fe3cf673e3194a7bdae8c236d51ed','qris'),(13,'order_expiry_minutes','15','order'),(14,'auto_fulfill_provider','1','provider'),(15,'provider_digiflazz_user','','provider'),(16,'provider_digiflazz_key','','provider'),(17,'provider_vip_api_id','','provider'),(18,'provider_vip_api_key','','provider'),(20,'check_id_provider','apigames','general'),(21,'apigames_merchant_id','M260823MXSZ5532UD','general'),(22,'apigames_secret_key','d1b4d876a2153ce44633e28cad467c10f9f3c48605f65bc1b57531ef67296289','general'),(23,'custom_check_id_url','','general');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','member','reseller') NOT NULL DEFAULT 'member',
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tier` varchar(30) NOT NULL DEFAULT 'basic',
  `status` enum('active','suspended') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator Norva','admin','admin@norvago.id','081234567890','$2y$10$h7eVn4o30DNco93aUxhuM.ZxLqXV4OYbusTLjT3ecjE4zWTUMU.em','admin',1000000.00,'vip','active','2026-08-23 00:26:20','2026-08-23 00:26:20'),(2,'John Reseller','reseller','reseller@norvago.id','089876543210','$2y$10$.BbLb.ImNIGHMLJ3ubZ4r.q.qNEw4KRNOHnrxLGjimsst7feQ8HUe','reseller',500000.00,'gold','active','2026-08-23 00:26:20','2026-08-23 00:26:20'),(3,'adi','adi123','adispreadsheet@gmail.com','085157352214','$2y$10$LecSNYA9e92tm4jkDMxa1eAAUCqcAHhxjS9EkC/R.Rr2lZwrDIE0y','member',0.00,'basic','active','2026-08-23 03:34:24','2026-08-23 03:34:24');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vouchers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('fixed','percent') NOT NULL DEFAULT 'percent',
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `min_purchase` decimal(15,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `quota` int(11) NOT NULL DEFAULT 100,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `valid_until` datetime DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vouchers`
--

LOCK TABLES `vouchers` WRITE;
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;
INSERT INTO `vouchers` VALUES (1,'NORVAGAMING','Diskon Pengguna Baru Rp 2.000','fixed',2000.00,20000.00,2000.00,500,0,'2026-09-22 00:26:20','active','2026-08-23 00:26:20','2026-08-23 00:26:20'),(2,'HEMAT5','Diskon 5% Semua Game','percent',5.00,50000.00,10000.00,200,0,'2026-09-22 00:26:20','active','2026-08-23 00:26:20','2026-08-23 00:26:20');
/*!40000 ALTER TABLE `vouchers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-23  4:17:23
