-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2026 at 04:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `verdex_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar_tasks`
--

CREATE TABLE `calendar_tasks` (
  `task_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `task_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `task_type` enum('plant','irrigation','fertilizer','inspection','maintenance','other') NOT NULL DEFAULT 'other',
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `calendar_tasks`
--

INSERT INTO `calendar_tasks` (`task_id`, `title`, `task_date`, `start_time`, `end_time`, `task_type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'test', '2026-09-24', '11:11:00', '12:15:00', 'plant', 'test', '2026-09-23 21:23:31', '2026-09-23 21:23:31'),
(2, 'sam', '2026-09-26', '11:11:00', '12:00:00', 'irrigation', 'test', '2026-09-23 21:24:47', '2026-09-23 21:24:47'),
(3, 'asasasas', '2026-09-24', '15:37:00', '16:39:00', 'fertilizer', 'testing', '2026-09-23 21:36:07', '2026-09-23 21:36:07');

-- --------------------------------------------------------

--
-- Table structure for table `current_sensor_status`
--

CREATE TABLE `current_sensor_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `temperature` decimal(5,2) DEFAULT NULL,
  `humidity` decimal(5,2) DEFAULT NULL,
  `soil_moisture` decimal(5,2) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `current_sensor_status`
--

INSERT INTO `current_sensor_status` (`id`, `temperature`, `humidity`, `soil_moisture`, `updated_at`) VALUES
(1, 30.90, 85.10, 0.00, '2026-09-30 07:21:37');

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_catalog`
--

CREATE TABLE `fertilizer_catalog` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `nutrient_info` text DEFAULT NULL,
  `application_info` text DEFAULT NULL,
  `suitable_plants` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fertilizer_catalog`
--

INSERT INTO `fertilizer_catalog` (`id`, `name`, `description`, `nutrient_info`, `application_info`, `suitable_plants`, `notes`, `created_at`) VALUES
(1, 'Hydroponic A/B Nutrient Solution', 'A two-part nutrient solution designed for hydroponic plant growth.', 'Provides essential macro and micronutrients needed for plant development.', 'Mix Part A and Part B separately according to the manufacturer instructions before adding to the hydroponic system.', 'Lettuce, Basil, Spinach, Pechay, and other leafy greens.', 'Monitor nutrient concentration and pH regularly.', '2026-09-23 19:47:19'),
(2, 'Hydroponic Leafy Greens Nutrient', 'A nutrient formulation intended for leafy vegetables grown in hydroponic systems.', 'Provides nutrients that support healthy leaf and stem development.', 'Dilute according to the recommended concentration and monitor plant response.', 'Lettuce, Spinach, Pechay, Basil, and other leafy vegetables.', 'Adjust nutrient concentration based on plant growth stage and system conditions.', '2026-09-23 19:47:19');

-- --------------------------------------------------------

--
-- Table structure for table `forum_categories`
--

CREATE TABLE `forum_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `forum_categories`
--

INSERT INTO `forum_categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Crop Care', 'Discussion about plant care, growing conditions, and crop maintenance.', '2026-09-23 21:32:06'),
(2, 'Farming Tips', 'Useful farming techniques, experiences, and practical advice.', '2026-09-23 21:32:06'),
(3, 'Pest Control', 'Discussion about preventing and managing pests in the farm.', '2026-09-23 21:32:06'),
(4, 'General', 'General discussions related to farming and the VerdEX community.', '2026-09-23 21:32:06');

-- --------------------------------------------------------

--
-- Table structure for table `forum_comments`
--

CREATE TABLE `forum_comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `author_role` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `author_role` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`post_id`, `category_id`, `author_name`, `author_role`, `title`, `content`, `likes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Test User', 'Farm Owner', 'Test Post', 'This is a test post for VerdEX.', 0, '2026-09-23 22:28:12', '2026-09-23 22:28:12'),
(2, 4, 'zandro', 'Project Manager / Data Analyst', 'vERDEX tEST', 'testt', 0, '2026-09-24 00:45:44', '2026-09-24 00:45:44'),
(3, 2, 'zandro', 'Project Manager / Data Analyst', 'lorenzo', 'testt', 0, '2026-09-24 00:46:46', '2026-09-24 00:46:46'),
(4, 1, 'zandro', 'Project Manager / Data Analyst', 'sam', 'test', 0, '2026-09-24 00:50:50', '2026-09-24 00:50:50');

-- --------------------------------------------------------

--
-- Table structure for table `helper_profiles`
--

CREATE TABLE `helper_profiles` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(10) UNSIGNED NOT NULL,
  `profile_name` varchar(100) NOT NULL,
  `pin_hash` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `helper_profiles`
--

INSERT INTO `helper_profiles` (`id`, `owner_id`, `profile_name`, `pin_hash`, `avatar`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, 'Lorenzo', '$2y$10$q3qy60BhRvH/4yPa2HU6V.HOu7AUUyadMfXm5UOjCLQwi63tuc9XK', NULL, 1, '2026-09-30 07:02:09', '2026-09-30 15:12:13'),
(2, 2, 'Zandro', '$2y$10$koK8k81A6f..g.rVlcj1pOFm5ePnWOoZPaQ2Sb8fSj5tck9zjYFqq', NULL, 1, '2026-09-30 14:46:04', '2026-09-30 14:46:04'),
(3, 2, 'Jaypee', '$2y$10$pZZEynZcF5HEPeiVBWTPjey5sC8FhqNJTc5VCcIZESxcTNtPP.nby', NULL, 1, '2026-09-30 14:46:36', '2026-09-30 14:46:36');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_categories`
--

CREATE TABLE `inventory_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `inventory_categories`
--

INSERT INTO `inventory_categories` (`category_id`, `category_name`) VALUES
(1, 'Hydroponic Plant'),
(2, 'Fertilizer'),
(3, 'Tool');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `inventory_id` int(11) NOT NULL,
  `catalog_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(30) NOT NULL DEFAULT 'pcs',
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`inventory_id`, `catalog_id`, `category_id`, `item_name`, `picture`, `quantity`, `unit`, `notes`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Pechay', 'uploads/inventory/item_6ab49cf50ac726.36320548.png', 30.00, 'pcs', 'test', 1, '2026-09-23 20:45:57', '2026-09-28 09:24:29'),
(2, 1, 2, 'Hydroponic A/B Nutrient Solution', 'uploads/inventory/item_6ab49d2543dd89.23088769.png', 100.00, 'pcs', '1', 1, '2026-09-23 20:46:46', '2026-09-28 09:24:00'),
(3, 2, 3, 'EC Meter', NULL, 100.00, 'pcs', 'for sale', 1, '2026-09-28 09:26:01', '2026-09-28 09:26:01');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `transaction_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `transaction_type` enum('IN','OUT') NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `inventory_transactions`
--

INSERT INTO `inventory_transactions` (`transaction_id`, `inventory_id`, `transaction_type`, `quantity`, `reason`, `created_at`) VALUES
(1, 1, 'IN', 1.00, 'Initial stock', '2026-09-23 20:45:57'),
(2, 2, 'IN', 1.00, 'Initial stock', '2026-09-23 20:46:46'),
(3, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 08:10:26'),
(4, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 08:10:27'),
(5, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 08:10:28'),
(6, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 08:10:29'),
(7, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 08:10:29'),
(8, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 08:10:30'),
(9, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 08:10:31'),
(10, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 08:10:33'),
(11, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 08:10:34'),
(12, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 08:10:34'),
(13, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 08:10:35'),
(14, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 08:10:36'),
(15, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 08:10:37'),
(16, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 08:11:35'),
(17, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 08:11:36'),
(18, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 08:11:37'),
(19, 1, 'IN', 97.00, 'Stock increased', '2026-09-28 09:22:40'),
(20, 1, 'IN', 5.00, 'Stock increased', '2026-09-28 09:22:42'),
(21, 1, 'OUT', 4.00, 'Stock decreased', '2026-09-28 09:22:43'),
(22, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 09:22:43'),
(23, 1, 'IN', 2.00, 'Stock increased', '2026-09-28 09:22:58'),
(24, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 09:22:59'),
(25, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 09:23:00'),
(26, 1, 'OUT', 40.00, 'Stock decreased', '2026-09-28 09:23:05'),
(27, 2, 'IN', 99.00, 'Stock increased', '2026-09-28 09:24:00'),
(28, 1, 'OUT', 25.00, 'Stock decreased', '2026-09-28 09:24:12'),
(29, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 09:24:13'),
(30, 1, 'OUT', 24.00, 'Stock decreased', '2026-09-28 09:24:18'),
(31, 1, 'OUT', 5.00, 'Stock decreased', '2026-09-28 09:24:20'),
(32, 1, 'OUT', 1.00, 'Stock decreased', '2026-09-28 09:24:21'),
(33, 1, 'IN', 11.00, 'Stock increased', '2026-09-28 09:24:24'),
(34, 1, 'IN', 12.00, 'Stock increased', '2026-09-28 09:24:27'),
(35, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 09:24:28'),
(36, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 09:24:28'),
(37, 1, 'IN', 1.00, 'Stock increased', '2026-09-28 09:24:29'),
(38, 3, 'IN', 100.00, 'Initial stock', '2026-09-28 09:26:01');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `type` enum('sensor_alert','inventory_alert','calendar_reminder','system') NOT NULL DEFAULT 'system',
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plant_catalog`
--

CREATE TABLE `plant_catalog` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `growing_method` text DEFAULT NULL,
  `growing_media` text DEFAULT NULL,
  `fertilizer_info` text DEFAULT NULL,
  `harvest_time` varchar(100) DEFAULT NULL,
  `care` text DEFAULT NULL,
  `temperature_range` varchar(100) DEFAULT NULL,
  `humidity_range` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `plant_catalog`
--

INSERT INTO `plant_catalog` (`id`, `name`, `description`, `growing_method`, `growing_media`, `fertilizer_info`, `harvest_time`, `care`, `temperature_range`, `humidity_range`, `created_at`) VALUES
(1, 'Pechay', 'A leafy vegetable commonly grown in hydroponic systems and suitable for controlled environments.', 'NFT or Deep Water Culture (DWC)', 'Rockwool or coco coir', 'Use a balanced hydroponic nutrient solution suitable for leafy greens.', 'Approximately 30 to 45 days', 'Provide adequate light, water, and nutrients. Monitor pH, nutrient concentration, temperature, humidity, and water level regularly.', '18°C to 28°C', '50% to 70%', '2026-09-23 19:46:08'),
(2, 'Lettuce', 'A leafy vegetable commonly grown in hydroponic systems.', 'NFT, DWC, or other hydroponic methods.', 'Rockwool, coco coir, or similar hydroponic media.', 'Use a balanced hydroponic nutrient solution suitable for leafy greens.', 'Approximately 30 to 45 days.', 'Maintain proper water level, temperature, humidity, pH, and nutrient concentration.', '18°C to 24°C', '50% to 70%', '2026-09-23 20:30:40'),
(3, 'Basil', 'An aromatic herb that grows well in controlled hydroponic environments.', 'NFT, DWC, or similar hydroponic methods.', 'Rockwool, coco coir, or similar hydroponic media.', 'Use a hydroponic nutrient solution appropriate for herbs.', 'Approximately 30 to 60 days.', 'Provide sufficient light and monitor water level, temperature, humidity, pH, and nutrient concentration.', '18°C to 30°C', '40% to 60%', '2026-09-23 20:30:40'),
(4, 'Spinach', 'A leafy vegetable suitable for hydroponic cultivation.', 'NFT, DWC, or similar hydroponic methods.', 'Rockwool, coco coir, or similar hydroponic media.', 'Use a balanced nutrient solution suitable for leafy vegetables.', 'Approximately 30 to 45 days.', 'Maintain cool temperatures and monitor water level, humidity, pH, and nutrient concentration.', '15°C to 24°C', '50% to 70%', '2026-09-23 20:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `crop_name` varchar(100) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(30) NOT NULL DEFAULT 'kg',
  `price_per_unit` decimal(10,2) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `buyer_name` varchar(150) DEFAULT NULL,
  `sale_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `recorded_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sensor_readings`
--

CREATE TABLE `sensor_readings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `temperature` decimal(5,2) DEFAULT NULL,
  `humidity` decimal(5,2) DEFAULT NULL,
  `water_level` decimal(6,2) DEFAULT NULL,
  `soil_moisture` decimal(5,2) DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor_readings`
--

INSERT INTO `sensor_readings` (`id`, `temperature`, `humidity`, `water_level`, `soil_moisture`, `recorded_at`) VALUES
(1, 28.80, 79.50, NULL, 40.00, '2026-09-24 17:20:13'),
(2, 30.70, 73.70, NULL, 0.00, '2026-09-30 03:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `tool_catalog`
--

CREATE TABLE `tool_catalog` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `usage_info` text DEFAULT NULL,
  `maintenance` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tool_catalog`
--

INSERT INTO `tool_catalog` (`id`, `name`, `description`, `purpose`, `usage_info`, `maintenance`, `notes`, `created_at`) VALUES
(1, 'pH Meter', 'A digital measuring device used to determine the acidity or alkalinity of the hydroponic nutrient solution.', 'Used to monitor and maintain the proper pH level of the hydroponic solution.', 'Place the calibrated probe into the nutrient solution and wait for the reading to stabilize.', 'Rinse the probe with clean water after use and store it according to the manufacturer instructions.', 'Regular calibration is recommended for accurate readings.', '2026-09-23 19:48:33'),
(2, 'EC Meter', 'A digital device used to measure the electrical conductivity of the hydroponic nutrient solution.', 'Used to monitor nutrient concentration in the hydroponic system.', 'Place the probe into the nutrient solution and check the EC reading.', 'Rinse the probe with clean water after use and keep the sensor clean.', 'Use together with plant growth requirements to maintain suitable nutrient concentration.', '2026-09-23 19:48:33'),
(3, 'Net Pot', 'A perforated container used to support hydroponic plants while allowing roots to grow through the openings.', 'Provides physical support for plants in hydroponic systems such as NFT and DWC.', 'Place the plant and growing media inside the net pot and position it properly in the hydroponic system.', 'Clean and sanitize the net pot before reuse.', 'Commonly used with rockwool, coco coir, clay pebbles, and other hydroponic growing media.', '2026-09-23 19:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('owner','helper') NOT NULL DEFAULT 'helper',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `role`, `created_at`) VALUES
(1, 'renzo', '$2y$10$kJ6/zs6buxxVqEdkBw4xpeNPMXE0.WH8JMk2wt1mSiYG4JKGF1rD.', 'Lorenzo Chavez', 'helper', '2026-09-30 05:55:53'),
(2, 'Sam', '$2y$10$JSvd6QCDHIWNh/5RjwM1v.ReeGpygX14fUVUDzSzfsXcK1qgXAHGm', 'Sam Austria', 'owner', '2026-09-30 06:47:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar_tasks`
--
ALTER TABLE `calendar_tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `current_sensor_status`
--
ALTER TABLE `current_sensor_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fertilizer_catalog`
--
ALTER TABLE `fertilizer_catalog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `forum_categories`
--
ALTER TABLE `forum_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `helper_profiles`
--
ALTER TABLE `helper_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_owner_profile` (`owner_id`,`profile_name`);

--
-- Indexes for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`inventory_id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notification_user` (`user_id`);

--
-- Indexes for table `plant_catalog`
--
ALTER TABLE `plant_catalog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sales_user` (`recorded_by`);

--
-- Indexes for table `sensor_readings`
--
ALTER TABLE `sensor_readings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_recorded_at` (`recorded_at`);

--
-- Indexes for table `tool_catalog`
--
ALTER TABLE `tool_catalog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar_tasks`
--
ALTER TABLE `calendar_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fertilizer_catalog`
--
ALTER TABLE `fertilizer_catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_categories`
--
ALTER TABLE `forum_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `helper_profiles`
--
ALTER TABLE `helper_profiles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plant_catalog`
--
ALTER TABLE `plant_catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sensor_readings`
--
ALTER TABLE `sensor_readings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tool_catalog`
--
ALTER TABLE `tool_catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `helper_profiles`
--
ALTER TABLE `helper_profiles`
  ADD CONSTRAINT `fk_helper_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_user` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
