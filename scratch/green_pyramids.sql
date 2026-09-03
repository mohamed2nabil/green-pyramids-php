-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2026 at 10:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `green_pyramids`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action_type` enum('LOGIN','PRODUCT_CREATED','PRODUCT_UPDATED','PRODUCT_DELETED','INQUIRY_REPLIED','INQUIRY_ARCHIVED','SLIDE_PUBLISHED','SETTINGS_UPDATED','CONTACT_UPDATED') NOT NULL,
  `target_table` varchar(50) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Logistics Director','Export Admin','Verified Executive','Admin Portal','Super Admin') DEFAULT 'Logistics Director',
  `avatar_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `full_name`, `username`, `email`, `password_hash`, `role`, `avatar_url`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(3, 'moo', 'moo', 'medo@gmail.com', '$2y$12$pFvtFhCab3Qk7Jrt1phL6.gMPvVzFzHFz2TIvLpD7W4unJuUR5GNO', 'Export Admin', NULL, 1, NULL, '2026-04-22 14:39:12', '2026-08-29 16:25:46'),
(4, 'mohamed', 'mohamed', 'mohamed@gmail.com', '$2y$10$qo1cdfThGYtPA/cLpVxpcO3w5cLJJwdnBGqdthp3D6uXjTpu.Eegm', '', NULL, 1, NULL, '2026-04-22 17:04:05', '2026-08-29 17:59:49'),
(6, 'mohamed', 'mohamed1', 'mohamed2nabil5@gmail.com', '$2y$10$FEpKy/0YvirGIEBLqBGQ8OkYZ7iYHmf/1CbYmSz1iNroUkNIGSlzW', 'Logistics Director', NULL, 1, NULL, '2026-05-03 06:07:31', '2026-05-03 06:07:31');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `badge_style` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `badge_style`, `created_at`, `image_path`) VALUES
(1, 'Fresh Fruits', 'badge-blue', '2026-04-21 19:41:54', 'assets/images/categories/cat_7c2d47da7945b6c0.png'),
(3, 'Fresh Vegetables', 'badge-green', '2026-04-21 19:41:54', 'assets/images/categories/cat_c63920269ba8d4fe.png'),
(8, 'citrus', NULL, '2026-05-03 18:45:50', 'assets/images/categories/cat_af5e33ec5a7a7297.png'),
(9, 'Dates', NULL, '2026-05-03 18:46:20', 'assets/images/categories/cat_1470424f90f045b7.png');

-- --------------------------------------------------------

--
-- Table structure for table `contact_settings`
--

CREATE TABLE `contact_settings` (
  `id` int(11) NOT NULL,
  `primary_email` varchar(100) DEFAULT NULL,
  `sales_email` varchar(100) DEFAULT NULL,
  `general_phone` varchar(100) DEFAULT NULL,
  `whatsapp_number` varchar(30) DEFAULT NULL,
  `physical_address` text DEFAULT NULL,
  `google_maps_embed` text DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_settings`
--

INSERT INTO `contact_settings` (`id`, `primary_email`, `sales_email`, `general_phone`, `whatsapp_number`, `physical_address`, `google_maps_embed`, `facebook_url`, `instagram_url`, `linkedin_url`, `updated_at`) VALUES
(1, 'export@greenlight-eg.net', 'export@greenlight-eg.net', '01555518060 - 01000473373 - 01007956131 ', '201555518060', 'Area 12, Sadat City, Menoufia, Egypt\r\n', '', 'https://greenlight-eg.net/', 'https://greenlight-eg.net/', 'https://greenlight-eg.net/', '2026-05-04 18:57:38');

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `slide_id` int(11) NOT NULL,
  `sort_order` tinyint(4) DEFAULT 0,
  `heading` varchar(200) DEFAULT NULL,
  `subtext` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`slide_id`, `sort_order`, `heading`, `subtext`, `image_path`, `is_active`, `updated_at`, `is_visible`) VALUES
(1, 1, 'Quality You Can Trust', 'Bridging the gap between Egypt\'s rich agricultural heritage and global markets.', 'assets/images/hero/slide_1_1777239307_69ee850ba4ef9.jpeg', 1, '2026-08-29 15:30:36', 1),
(3, 3, 'Sustainable Sourcing', 'Our commitment to excellence ensures the highest international standards.', 'assets/images/hero/slide_3_1777240481_69ee89a17b26a.jpg', 1, '2026-08-29 15:30:36', 1),
(4, 3, 'Restore Slide 3', 'Default description', 'assets/images/hero/slide_4_1777715681_69f5c9e14dc15.jpg', 0, '2026-08-29 15:30:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `inquiry_id` int(11) NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `sender_email` varchar(100) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `avatar_initials` char(2) DEFAULT NULL,
  `avatar_color` varchar(7) DEFAULT NULL,
  `status` enum('New','Pending','Replied','Archived') DEFAULT 'New',
  `reply_text` text DEFAULT NULL,
  `replied_at` datetime DEFAULT NULL,
  `replied_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`inquiry_id`, `sender_name`, `sender_email`, `company_name`, `subject`, `message`, `country_code`, `avatar_initials`, `avatar_color`, `status`, `reply_text`, `replied_at`, `replied_by`, `created_at`) VALUES
(2, 'Sarah Lund', 'sarah@example.com', NULL, 'Sustainability Certification Request', NULL, NULL, 'SL', '#2196F3', 'New', NULL, NULL, NULL, '2024-10-23 13:45:00'),
(3, 'Julian Pierce', 'julian@example.com', NULL, 'Shipping rates to Rotterdam Port', NULL, NULL, 'JP', '#FF9800', 'New', NULL, NULL, NULL, '2024-10-23 08:30:00'),
(10, 'madddddddd', 'mohamed2nabil5@gmail.com', 'colledge', 'cazy', 'djfka', 'Eg', 'M', '#F44336', 'New', NULL, NULL, NULL, '2026-05-02 09:39:56');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL,
  `page_slug` varchar(50) NOT NULL,
  `page_title` varchar(100) NOT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`page_id`, `page_slug`, `page_title`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 'home', 'Home Page', 1, '2026-04-21 19:41:55', '2026-04-21 19:41:55'),
(2, 'about', 'About Page', 0, '2026-04-21 19:41:55', '2026-04-21 19:41:55'),
(3, 'logistics-profile', 'Logistics Profile', 0, '2026-04-21 19:41:55', '2026-04-21 19:41:55');

-- --------------------------------------------------------

--
-- Table structure for table `page_sections`
--

CREATE TABLE `page_sections` (
  `section_id` int(11) NOT NULL,
  `page` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `heading` varchar(200) DEFAULT NULL,
  `subtext` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_visible` tinyint(1) DEFAULT 1,
  `sort_order` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_sections`
--

INSERT INTO `page_sections` (`section_id`, `page`, `section`, `heading`, `subtext`, `image_path`, `updated_at`, `is_visible`, `sort_order`) VALUES
(1, 'about', 'process', 'Our Story', 'How we bring your vision to life.', 'assets/images/pages/about_process_1777718945_69f5d6a1d972d.jpg', '2026-08-29 15:30:37', 1, 0),
(2, 'process', 'hero', 'Our Process', '', 'assets/images/pages/process_hero_1777718767_69f5d5ef2bc69.jpg', '2026-08-29 15:30:37', 1, 0),
(4, 'process', 'intro', 'From Farm to Your Door', 'Every product we export goes through a carefully managed journey\nfrom sourcing the finest local farms to delivering fresh, certified produce to your door. Our process is built on \nprecision, transparency, and an unwavering commitment to quality \nat every step.', NULL, '2026-05-03 03:33:13', 1, 0),
(5, 'production', 'hero', 'Fresh Produce for Global Export', 'We provide a carefully selected range of fresh fruits and vegetables, harvested from fertile farms and packed according to international export standards to ensure maximum freshness upon delivery', 'assets/images/pages/production_hero_1777782279_69f6ce0725165.png', '2026-08-29 15:30:37', 1, 0),
(7, 'home', 'hero_slide', 'Quality You Can Trust', 'Bridging the gap between Egypt\'s rich agricultural heritage and global markets.', 'assets/images/pages/slide_1_1777239307_69ee850ba4ef9.jpeg', '2026-08-29 15:30:37', 1, 1),
(19, 'contact', 'hero', 'Contact Us', 'Get in touch with our team', NULL, '2026-05-02 11:59:02', 1, 0),
(30, 'production', 'hero_origin', '', '', NULL, '2026-05-02 12:20:28', 1, 0),
(35, 'production', 'hero_badge', 'Explore Our Premium Selection', '', NULL, '2026-05-02 12:26:43', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `hs_code` varchar(50) DEFAULT NULL,
  `variety` varchar(150) DEFAULT NULL,
  `sizes` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `export_grade` enum('A Grade','AA Grade','Premium') NOT NULL,
  `description` text DEFAULT NULL,
  `origin_region` varchar(100) DEFAULT NULL,
  `moisture_content` decimal(5,2) DEFAULT NULL,
  `packaging_specs` varchar(255) DEFAULT NULL,
  `packaging_types` text DEFAULT NULL,
  `shipping_method` varchar(255) DEFAULT NULL,
  `container_capacity` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `avail_jan` tinyint(1) DEFAULT 0,
  `avail_feb` tinyint(1) DEFAULT 0,
  `avail_mar` tinyint(1) DEFAULT 0,
  `avail_apr` tinyint(1) DEFAULT 0,
  `avail_may` tinyint(1) DEFAULT 0,
  `avail_jun` tinyint(1) DEFAULT 0,
  `avail_jul` tinyint(1) DEFAULT 0,
  `avail_aug` tinyint(1) DEFAULT 0,
  `avail_sep` tinyint(1) DEFAULT 0,
  `avail_oct` tinyint(1) DEFAULT 0,
  `avail_nov` tinyint(1) DEFAULT 0,
  `avail_dec` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `sku`, `name`, `slug`, `hs_code`, `variety`, `sizes`, `category_id`, `export_grade`, `description`, `origin_region`, `moisture_content`, `packaging_specs`, `packaging_types`, `shipping_method`, `container_capacity`, `stock_quantity`, `image_path`, `is_active`, `created_at`, `updated_at`, `is_visible`, `avail_jan`, `avail_feb`, `avail_mar`, `avail_apr`, `avail_may`, `avail_jun`, `avail_jul`, `avail_aug`, `avail_sep`, `avail_oct`, `avail_nov`, `avail_dec`) VALUES
(10, 'SKU-D692BC40', 'Grape fruit', 'grape-fruit', '080540', 'Star Ruby (Dark Red) Grapefruit', '30,36,40,42,48,56,64,72', 8, 'Premium', '-', 'N/A', 0.00, 'N/A', 'Carton Or plastic box 15Kg.\r\nCarton 8Kg.\r\nOpen Top carton 15Kg.\r\nOpen Top carton 7.5Kg.', 'Sea Shipmen', '', 0, 'assets/images/products/product_d7f24acf9cd6ad57e6c7.jpg', 1, '2026-05-01 09:01:58', '2026-08-29 15:34:05', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
(18, 'SKU-4C74FDBA', 'Navel Orange', 'navel-orange', '080510', 'Navel , Egg Shape', '40, 48, 56, 64, 72, 80, 88,100,113', 8, 'Premium', '-', NULL, NULL, NULL, 'Carton Or plastic box 15Kg.\r\nCarton 8Kg.\r\nOpen Top carton 15Kg.\r\nOpen Top carton 7.5Kg', 'Sea Shipment', '', 0, 'assets/images/products/prod_9e28510b_1778185776.webp', 1, '2026-05-07 18:53:25', '2026-08-29 15:34:05', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
(19, 'SKU-AD04B8AB', 'Valencia', 'valencia', '080510', 'Valencia', '36 , 40 , 48 , 56 , 64 , 72 , 80 , 88 , 100 , 113', 8, 'Premium', '-', NULL, NULL, NULL, 'Carton Or plastic box 15Kg.\r\nCarton 8Kg.\r\nOpen Top carton 15Kg.\r\nOpen Top carton 7.5Kg.', 'Sea Shipment', '', 0, 'assets/images/products/prod_e4384090_1778185536.webp', 1, '2026-05-07 19:00:39', '2026-08-29 15:34:05', 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1),
(21, 'SKU-C51DC047', 'Early Valencia', 'early-valencia', '080510', 'Early Valencia', '40, 48, 56, 64, 72, 80, 88 ,100 , 113.', 8, 'Premium', '-', NULL, NULL, NULL, 'Carton Or plastic box 15Kg.\r\nCarton 8Kg.\r\nOpen Top carton 15Kg.\r\nOpen Top carton 7.5Kg.', 'Container.', '', 0, 'assets/images/products/prod_84173b8b_1778186190.webp', 1, '2026-05-07 20:36:30', '2026-08-29 15:34:05', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
(22, 'SKU-188E6E09', 'Lane Late', 'lane-late', '080510.', 'Lane Late', '40, 48 , 56 , 64 , 72,  80 , 88 , 100 , 113', 8, 'Premium', '-', NULL, NULL, NULL, 'Carton Or plastic box 15Kg.\r\nCarton 8Kg.\r\nOpen Top carton 15Kg.\r\nOpen Top carton 7.5Kg.', '', '', 0, 'assets/images/products/prod_534038e5_1778186604.webp', 1, '2026-05-07 20:43:24', '2026-08-29 15:34:05', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 'SKU-85317229', 'Baladi', 'baladi', '080510', 'Round Shape', '56 , 64 , 72 , 80 , 88 , 100 , 113 , 125 , 136', 8, 'Premium', '-', NULL, NULL, NULL, 'Carton Or plastic box 10Kg.\r\nPlastic box 6Kg Or Carton 8Kg.', 'Sea Shipment', '', 0, 'assets/images/products/prod_69dc92c2_1778186860.webp', 1, '2026-05-07 20:47:40', '2026-08-29 15:34:05', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(24, 'SKU-196471FF', 'Murcott', 'murcott', '080521', 'Honey Murcott', '32, 36, 48, 54, 70, 90, 100, 110', 8, 'Premium', '-', NULL, NULL, NULL, 'Carton Or plastic box 10Kg\r\nplastic box 6Kg Or Carton 8Kg.', 'Sea Shipment', '', 0, 'assets/images/products/prod_edfbd2d7_1778187159.webp', 1, '2026-05-07 20:52:39', '2026-08-29 15:34:05', 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0),
(25, 'SKU-5E5FCC2D', 'Clementine', 'clementine', '080521', 'Mandarins', '30,36,40,42,48,56,64,72', 8, 'Premium', '-', NULL, NULL, NULL, 'Carton Or plastic box 10Kg\r\nplastic box 6Kg.\r\nCarton 8Kg.', 'Sea Shipment', '', 0, 'assets/images/products/prod_d15e91e2_1778187339.webp', 1, '2026-05-07 20:55:39', '2026-08-29 15:34:05', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
(27, 'SKU-921DDCD8', 'Fermont', 'fermont', '080521', 'Mandarins', '32 , 36 , 48 , 54 , 70 , 90 , 100 , 110', 8, 'Premium', '-', NULL, NULL, NULL, 'Carton Or plastic box 10Kg\r\nPlastic box 6Kg Or Carton 8Kg', 'Sea Shipment', '', 0, 'assets/images/products/prod_462acf81_1778187706.webp', 1, '2026-05-07 21:01:46', '2026-08-29 15:34:05', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
(28, 'SKU-75D8D2A9', 'Mirav / Maraf', 'mirav-maraf', '080520', 'Mandarin', '36 , 40 , 48 , 54 , 60.', 8, 'Premium', '-', NULL, NULL, NULL, 'cartons: 5 KG, 8 KG, 10 KG', '', '', 0, 'assets/images/products/prod_0ac9b98a_1778189256.webp', 1, '2026-05-07 21:27:36', '2026-08-29 15:34:05', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(29, 'SKU-04B3A68C', 'Minneola', 'minneola', '080521', 'Minneola', '32, 36, 48, 54, 70, 90, 100, 110', 8, 'Premium', '-', NULL, NULL, NULL, 'Carton Or plastic box 10Kg\r\nplastic box 6Kg Or Carton 8Kg', 'Sea Shipment', '', 0, 'assets/images/products/prod_7533304b_1778189418.webp', 1, '2026-05-07 21:30:18', '2026-08-29 15:34:05', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1),
(30, 'SKU-3D3FAE56', 'Christina', 'christina', '080521.', 'Christina mandarin', '32, 36, 48, 54, 70, 90, 100, 110', 8, 'Premium', '', NULL, NULL, NULL, 'Carton Or plastic box 10Kg\r\nplastic box 6Kg Or Carton 8Kg.', 'Sea Shipment.', '', 0, 'assets/images/products/prod_f37213d3_1778189928.webp', 1, '2026-05-07 21:38:48', '2026-08-29 15:34:05', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0),
(31, 'SKU-5819DA36', 'Gold Murcott', 'gold-murcott', '080521', 'Gold murcott', '32, 36, 48, 54, 70, 90, 100, 110', 8, 'Premium', '', NULL, NULL, NULL, 'Carton Or plastic box 10Kg\r\nplastic box 6Kg Or Carton 8Kg.', 'Sea Shipment.', '', 0, 'assets/images/products/prod_89fee780_1778190081.webp', 1, '2026-05-07 21:41:21', '2026-08-29 15:34:05', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(32, 'SKU-4F3FB53D', 'Mikhal', 'mikhal', '080521.', 'Mikhal mandarin', '32, 36, 48, 54, 70, 90, 100, 110', 8, 'Premium', '', NULL, NULL, NULL, 'Carton Or plastic box 10Kg\r\nplastic box 6Kg Or Carton 8Kg.', 'Sea Shipment.', '', 0, 'assets/images/products/prod_a5b4a7fe_1778190173.webp', 1, '2026-05-07 21:42:53', '2026-08-29 15:34:05', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
(33, 'SKU-A47FA568', 'Kinnow', 'kinnow', '080521', 'kinnow mandarin (Kini)', '32, 36, 48, 54, 70, 90, 100, 110', 8, 'Premium', '', NULL, NULL, NULL, 'Carton Or plastic box 10Kg\r\nplastic box 6Kg Or Carton 8Kg.', 'Sea Shipment.', '', 0, 'assets/images/products/prod_31002982_1778190281.webp', 1, '2026-05-07 21:44:41', '2026-08-29 15:34:05', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(34, 'SKU-BF29DBB0', 'Lemon', 'lemon', '080550', 'Eureka, Adalia.', '72 , 80 , 88 , 100 , 113 , 125 , 138 , 150', 8, 'Premium', '', NULL, NULL, NULL, '15 KG N.W.-16 KG G.W. Standard carton.', '72/80/88/100/113/125/138/150', '', 0, 'assets/images/products/prod_32b442d1_1778190485.webp', 1, '2026-05-07 21:48:05', '2026-08-29 15:34:05', 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0),
(35, 'SKU-1F78B449', 'Lime', 'lime', '080530', 'Banzahir, Bearss', '125, 138, 165, 185, 200', 8, 'Premium', '', NULL, NULL, NULL, '5 Kg Carton', 'Air Shipment, Sea Shipment .', '', 0, 'assets/images/products/prod_86716291_1778190596.webp', 1, '2026-05-07 21:49:56', '2026-08-29 15:34:05', 1, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0),
(36, 'SKU-99ED6FEE', 'Zaghloul', 'zaghloul', '080410', 'Fresh Zaghloul Dates (Red Dates)', 'Large and medium', 9, 'Premium', '', NULL, NULL, NULL, '5 kg carton', 'Air Shipment', '', 0, 'assets/images/products/prod_5c6e5391_1778191223.webp', 1, '2026-05-07 22:00:23', '2026-08-29 15:34:05', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0),
(37, 'SKU-6D756F1E', 'Fresh Barhi Dates', 'fresh-barhi-dates', '080410', 'Barhi Dates', 'Small – Medium – Large', 9, 'Premium', '', NULL, NULL, NULL, '5 kg carton', 'Air Shipment.', '', 0, 'assets/images/products/prod_49489f2f_1778191453.webp', 1, '2026-05-07 22:04:13', '2026-08-29 15:34:05', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0),
(38, 'SKU-8BDEC7C6', 'Fresh Samani Dates', 'fresh-samani-dates', '080410', 'Fresh Samani Dates (Yellow Dates)', 'Large and Medium.', 9, 'Premium', '', NULL, NULL, NULL, '5 kg Carton', 'Air Shipment.', '', 0, 'assets/images/products/prod_680b6edf_1778191640.webp', 1, '2026-05-07 22:07:20', '2026-08-29 15:34:05', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0),
(39, 'SKU-D563DDB6', 'Semi-dried Dates', 'semi-dried-dates', '080410', 'Medjool (Majhool), Sewi (Wadi), and Wahati', 'Small, Medium, Large, Jumbo', 9, 'Premium', '', NULL, NULL, NULL, '5 kg carton', '40 ft Container, 20 ft Container, and Air Shipment.', '', 0, 'assets/images/products/prod_e3e25b7a_1778191765.webp', 1, '2026-05-07 22:09:25', '2026-08-29 15:34:05', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(41, 'SKU-7A035D71', 'Red Onion Onion', 'red-onion-onion', '070310', 'Egyptian Red Onions.', '(40/60), (50/70), (70/90), (90/120).', 3, 'Premium', '-', NULL, NULL, NULL, '10 Kg /  Mesh bag\r\n25 Kg /  Mesh bag\r\n550 Kg / Wooden bins\r\n1250 Kg / Jumbo bag', 'Sea shipment.', '', 0, 'assets/images/products/prod_93018c70_1778192783.jpg', 1, '2026-05-07 22:21:04', '2026-08-29 15:34:05', 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 0, 0, 0),
(42, 'SKU-5F73F1FA', 'Yellow Onion', 'yellow-onion', '070310', 'Golden Onions', '40/60), (50/70), (70/90), (90/120).', 3, 'Premium', '-', NULL, NULL, NULL, '10 Kg / Mesh bag\r\n25 Kg / Mesh bag\r\n550 Kg / Wooden bins\r\n1250 Kg / Jumbo bag', 'Sea shipment', '', 0, 'assets/images/products/prod_5ac540c1_1778193573.jpg', 1, '2026-05-07 22:29:37', '2026-08-29 15:34:05', 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0),
(43, 'SKU-130ED173', 'Sweet Potatoes', 'sweet-potatoes', '071420', 'Beauregard', '300 gm /  600 gm.', 3, 'Premium', '', NULL, NULL, NULL, '5 KG /CARTON (BY AIR )\r\n6 KG  / CRTN (3400 CRTN / 40”FT Container)\r\n10 KG mash BAGS (25 tons in a container)', 'Air Shipment Sea Shipment', '', 0, 'assets/images/products/prod_dc438db5_1778193382.webp', 1, '2026-05-07 22:36:22', '2026-08-29 15:34:05', 1, 0, 0, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0),
(44, 'SKU-2D6F1A68', 'Carrots', 'carrots', '070610', 'Orange Carrots', 'Length 10  25 CM', 3, 'Premium', '', NULL, NULL, NULL, '6 Kg Carton Bulk pack', 'Air Shipment', '', 0, 'assets/images/products/prod_d22f1480_1778193732.webp', 1, '2026-05-07 22:42:12', '2026-08-29 15:34:05', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(45, 'SKU-CE13D3EE', 'Lettuce Iceberg', 'lettuce-iceberg', '070511', 'Egyptian Iceberg Lettuce', '9: 12 Pieces per carton', 3, 'Premium', '-', NULL, NULL, NULL, '7 kg/ carton', 'Sea & Air Shipment', '', 0, 'assets/images/products/prod_d84231c4_1778194121.webp', 1, '2026-05-07 22:45:43', '2026-08-29 15:34:05', 1, 1, 1, 1, 1, 0, 0, 0, 0, 1, 1, 1, 1),
(46, 'SKU-8A8D2F43', 'Pepper Capsicum', 'pepper-capsicum', '080930', 'Sweet\\Bell Pepper', '2, 15, or 18 pieces per carton', 3, 'Premium', '', NULL, NULL, NULL, '5 kg standard carton', 'Sea Shipment & Air Shipment', '', 0, 'assets/images/products/prod_ef44e197_1778194299.jpg', 1, '2026-05-07 22:51:39', '2026-08-29 15:34:05', 1, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0),
(47, 'SKU-3A9FC0B4', 'Garlic', 'garlic', '07032000', 'Fresh Garlic & Dry Garlic', 'Small   Medium	 Large    Extra Large', 3, 'Premium', '', NULL, NULL, NULL, '', 'Sea & Air Shipment', '', 0, 'assets/images/products/prod_ba6ad602_1778194459.webp', 1, '2026-05-07 22:54:19', '2026-08-29 15:34:05', 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0),
(48, 'SKU-E5C44946', 'Potatoes', 'potatoes', '070190', 'Spunta / Nicola / Diamond / Kara / Lady Rosetta / Santana / Synergy / Panba / Silani', '40+ /45+ / 40-60 /50+ /55+ /60+', 3, 'Premium', '', NULL, NULL, NULL, '1250 kg in Jumbo Bags\r\n10 / 25 kg Gott Bags', 'Sea  Shipment', '', 0, 'assets/images/products/prod_2b2ff631_1778195316.jpg', 1, '2026-05-07 23:08:36', '2026-08-29 15:34:05', 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(49, 'SKU-A45B51AD', 'Strawberry', 'strawberry', '081010', 'Fortuna, Sensation, Festival, and Florida', '25–35 mm \\ piece.', 1, 'Premium', '', NULL, NULL, NULL, 'High Punnet:\r\noption 1: 20 Punnet x 225 Gm = 4.50 Kg N.W  / CRTN\r\noption 2: 12 Punnet x 400 Gm = 4.80 kg N.W / CRTN\r\nFlat Punnet:\r\noption 1: 10 Punnet x 250 Gm = 2.50 kg N.W / CRTN\r\noption 2: 8 Punnet x 250 Gm = 2.00 kg N.W / CRTN', 'Air Shipment', '', 0, 'assets/images/products/prod_8761621f_1778195799.jpg', 1, '2026-05-07 23:16:39', '2026-08-29 15:34:06', 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1),
(50, 'SKU-38CD922E', 'Mango', 'mango', '080450', '5 Kg standard carton, Plastic box. 3 Kg Carton Open top, Plastic box.', 'Medium Sizes 7/8 pieces per 1 KG', 1, 'Premium', '', NULL, NULL, NULL, 'Keitt, Kent, Naomi, Crimson Pride, Osteen, R2 (R2E2), Tommy, Sideeka, Tymor, Awees, Indian, Alfons, Zebdia, Sukarya, Senara, Zebdya, Mabrouka, and Fagr Kelan', 'Air Shipment & Sea Shipment', '', 0, 'assets/images/products/prod_0a014882_1778196200.jpg', 1, '2026-05-07 23:23:20', '2026-08-29 15:34:06', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 0),
(51, 'SKU-A73C6000', 'Pomegranate', 'pomegranate', '08109010', 'Wonderful. Manfaluti, Baladi, Early 116', '6,7,8,9,10,11,12,13,14', 1, 'Premium', '', NULL, NULL, NULL, '1) 4.50 Kg N.W carton (label & Tray)\r\n2) 4.50 Kg N.W Carton (label & Tray & P.bag)\r\n3) 4.50 Kg Plastic Tray (label & Tray)\r\n4) 5 Kg Plastic Tray (label & Tray)', 'Sea Shipment', '', 0, 'assets/images/products/prod_3523d673_1778196451.webp', 1, '2026-05-07 23:27:31', '2026-08-29 15:34:06', 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1),
(52, 'SKU-01749E20', 'Grapes', 'grapes', '080610', 'Early Sweet, Prime, Superior, Thompson, Red Flame, Starlight, Red Globe, Crimson, Black Magic, Autumn Royal.', '', 1, 'Premium', '', NULL, NULL, NULL, '5 Kg Carton contains 8-9 plastic bags X 500 Gm.\r\n5.5 kg Carton contains 10 punnets X 500 Gm', 'Air Shipment & Sea Shipment', '', 0, 'assets/images/products/prod_e1a909fd_1778196618.webp', 1, '2026-05-07 23:30:18', '2026-08-29 15:34:06', 1, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_title` varchar(255) DEFAULT 'Green Light for Export',
  `hero_title` text DEFAULT NULL,
  `hero_subtitle` text DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `about_text` text DEFAULT NULL,
  `footer_email` varchar(255) DEFAULT NULL,
  `footer_phone` varchar(50) DEFAULT NULL,
  `footer_location` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_title`, `hero_title`, `hero_subtitle`, `hero_image`, `about_text`, `footer_email`, `footer_phone`, `footer_location`, `updated_at`) VALUES
(1, 'Green Light for Export', 'Elevating Freshness...', 'Premium Egyptian produce, harvested at peak ripeness and frozen with advanced IQF technology to preserve every bit of flavor and nutrition.', 'assets/images/hero-fruits.jpg', 'We help importers secure premium Egyptian fruits and vegetables \nwith guaranteed quality, consistent supply, and on-time delivery \nwithout the risks of delays, inconsistency, or unreliable partners.\n\nFrom farm selection to final shipment, every step is controlled\n to ensure your business receives exactly what it expects every time.', 'M.hasaan@example.com', '+20 10 07956131', 'alex, Egypt', '2026-04-30 08:50:36');

-- --------------------------------------------------------

--
-- Table structure for table `stats_strip`
--

CREATE TABLE `stats_strip` (
  `stat_id` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `label` varchar(100) NOT NULL,
  `value` varchar(50) NOT NULL,
  `unit` varchar(30) DEFAULT NULL,
  `sort_order` tinyint(4) DEFAULT 0,
  `is_visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `review` text NOT NULL,
  `rating` int(11) DEFAULT 5,
  `status` enum('pending','approved','hidden') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `client_name`, `company`, `country`, `review`, `rating`, `status`, `created_at`) VALUES
(1, 'Mark J.', 'Importer', 'UK', 'Green Pyramids delivers exceptional quality citrus every season. Their packing and cold-chain logistics ensure the fruit arrives in Europe perfectly fresh.', 3, 'approved', '2026-09-01 09:12:52'),
(2, 'Hassan A.', 'Distributor', 'UAE', 'We switched to Green Pyramids for our onion and potato supply. Reliable volume, consistent sizing, and highly professional service.', 5, 'approved', '2026-09-01 09:12:52'),
(3, 'Sophie L.', 'Wholesaler', 'France', 'The traceability from farm to port is what sets them apart. We always know exactly what we are getting. A premium supplier indeed.', 5, 'approved', '2026-09-01 09:12:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `contact_settings`
--
ALTER TABLE `contact_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`slide_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`inquiry_id`),
  ADD KEY `replied_by` (`replied_by`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`),
  ADD UNIQUE KEY `page_slug` (`page_slug`);

--
-- Indexes for table `page_sections`
--
ALTER TABLE `page_sections`
  ADD PRIMARY KEY (`section_id`),
  ADD UNIQUE KEY `page_section` (`page`,`section`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `sku_2` (`sku`),
  ADD UNIQUE KEY `sku_3` (`sku`),
  ADD UNIQUE KEY `sku_4` (`sku`),
  ADD UNIQUE KEY `sku_5` (`sku`),
  ADD UNIQUE KEY `sku_6` (`sku`),
  ADD UNIQUE KEY `sku_7` (`sku`),
  ADD UNIQUE KEY `sku_8` (`sku`),
  ADD UNIQUE KEY `sku_9` (`sku`),
  ADD UNIQUE KEY `sku_10` (`sku`),
  ADD UNIQUE KEY `sku_11` (`sku`),
  ADD UNIQUE KEY `sku_12` (`sku`),
  ADD UNIQUE KEY `sku_13` (`sku`),
  ADD UNIQUE KEY `sku_14` (`sku`),
  ADD UNIQUE KEY `sku_15` (`sku`),
  ADD UNIQUE KEY `sku_16` (`sku`),
  ADD UNIQUE KEY `sku_17` (`sku`),
  ADD UNIQUE KEY `sku_18` (`sku`),
  ADD UNIQUE KEY `sku_19` (`sku`),
  ADD UNIQUE KEY `sku_20` (`sku`),
  ADD UNIQUE KEY `sku_21` (`sku`),
  ADD UNIQUE KEY `sku_22` (`sku`),
  ADD UNIQUE KEY `sku_23` (`sku`),
  ADD UNIQUE KEY `sku_24` (`sku`),
  ADD UNIQUE KEY `sku_25` (`sku`),
  ADD UNIQUE KEY `sku_26` (`sku`),
  ADD UNIQUE KEY `sku_27` (`sku`),
  ADD UNIQUE KEY `sku_28` (`sku`),
  ADD UNIQUE KEY `sku_29` (`sku`),
  ADD UNIQUE KEY `sku_30` (`sku`),
  ADD UNIQUE KEY `sku_31` (`sku`),
  ADD UNIQUE KEY `sku_32` (`sku`),
  ADD UNIQUE KEY `sku_33` (`sku`),
  ADD UNIQUE KEY `sku_34` (`sku`),
  ADD UNIQUE KEY `sku_35` (`sku`),
  ADD UNIQUE KEY `sku_36` (`sku`),
  ADD UNIQUE KEY `sku_37` (`sku`),
  ADD UNIQUE KEY `sku_38` (`sku`),
  ADD UNIQUE KEY `sku_39` (`sku`),
  ADD UNIQUE KEY `sku_40` (`sku`),
  ADD UNIQUE KEY `sku_41` (`sku`),
  ADD UNIQUE KEY `sku_42` (`sku`),
  ADD UNIQUE KEY `sku_43` (`sku`),
  ADD UNIQUE KEY `sku_44` (`sku`),
  ADD UNIQUE KEY `sku_45` (`sku`),
  ADD UNIQUE KEY `sku_46` (`sku`),
  ADD UNIQUE KEY `sku_47` (`sku`),
  ADD UNIQUE KEY `sku_48` (`sku`),
  ADD UNIQUE KEY `sku_49` (`sku`),
  ADD UNIQUE KEY `sku_50` (`sku`),
  ADD UNIQUE KEY `sku_51` (`sku`),
  ADD UNIQUE KEY `sku_52` (`sku`),
  ADD UNIQUE KEY `sku_53` (`sku`),
  ADD UNIQUE KEY `sku_54` (`sku`),
  ADD UNIQUE KEY `sku_55` (`sku`),
  ADD UNIQUE KEY `sku_56` (`sku`),
  ADD UNIQUE KEY `sku_57` (`sku`),
  ADD UNIQUE KEY `sku_58` (`sku`),
  ADD UNIQUE KEY `sku_59` (`sku`),
  ADD UNIQUE KEY `sku_60` (`sku`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stats_strip`
--
ALTER TABLE `stats_strip`
  ADD PRIMARY KEY (`stat_id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_settings`
--
ALTER TABLE `contact_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `slide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `inquiry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `page_sections`
--
ALTER TABLE `page_sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stats_strip`
--
ALTER TABLE `stats_strip`
  MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL;

--
-- Constraints for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`replied_by`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `stats_strip`
--
ALTER TABLE `stats_strip`
  ADD CONSTRAINT `stats_strip_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`page_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
