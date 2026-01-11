-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2026 at 04:05 PM
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
-- Database: `wallet`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('deposit','expense') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `title`, `amount`, `type`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'cafe', 1200.00, 'expense', '2026-01-08 16:38:34', NULL, NULL),
(2, 1, 'loyer', 1500.00, 'expense', '2026-01-08 16:38:56', NULL, NULL),
(3, 3, 'dsfg', 15555.00, 'expense', '2026-01-09 09:30:18', NULL, NULL),
(4, 3, 'u', 544554.00, 'expense', '2026-01-09 09:47:27', NULL, NULL),
(5, 3, 'sdfasdg', 150.00, 'expense', '2026-01-09 09:56:02', NULL, NULL),
(6, 3, 'kjhg', 555.00, 'expense', '2026-01-09 10:11:24', NULL, NULL),
(7, 3, 'asdf', 4444.00, 'expense', '2026-01-09 10:27:20', NULL, NULL),
(8, 3, 'asdfsadf', 11111111.00, 'expense', '2026-01-09 10:27:47', NULL, NULL),
(9, 4, 'cafe', 30.00, 'expense', '2026-01-09 10:38:01', NULL, NULL),
(10, 4, 'salaire', 1550.00, 'deposit', '2026-01-09 10:40:21', NULL, NULL),
(11, 4, 'bonus', 1000.00, 'deposit', '2026-01-09 10:41:50', NULL, NULL),
(12, 4, 'ajounda', 500.00, 'expense', '2026-01-09 10:42:24', NULL, NULL),
(13, 4, 'cafe', 200.00, 'expense', '2026-01-09 11:13:49', NULL, NULL),
(14, 4, 'qw', 120.00, 'deposit', '2026-01-09 13:51:58', NULL, NULL),
(15, 4, 'loyer', 200.00, 'expense', '2026-01-09 13:52:15', NULL, NULL),
(16, 4, 'CAFI', 20.00, 'expense', '2026-01-09 14:47:37', NULL, NULL),
(17, 4, 'CAFI', 20.00, 'expense', '2026-01-09 14:51:16', NULL, NULL),
(18, 4, 'BOUS', 155.00, 'deposit', '2026-01-09 14:51:31', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `password_hash`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Sit facere eu unde', 'jodihuf@mailinator.com', '$2y$10$FUsiC2dfyR.i2VbYi22dDuzQzZXXpnvCJSrz6lpVee1gbVS0Hep8W', '2026-01-08 10:54:25', NULL, NULL),
(2, 'Doloribus quo eos it', 'gihaqunac@mailinator.com', '$2y$10$zuE6fUiJekQI/z.EyK1hweEsQ6UGpV.iPOddgf9GVQZBskIj3Lxqy', '2026-01-08 11:23:20', NULL, NULL),
(3, 'meski', 'meskiabdelilah10@gmail.com', '$2y$10$VfVeXKDPXPHOOMOBPgzAVOv8k.Dck.4mxMIiKtKMukwGDP33k6bPa', '2026-01-09 08:25:07', NULL, NULL),
(4, 'Iusto fuga Ea solut', 'pevoh@mailinator.com', '$2y$10$1fpzLcrCWUhDGi.GzlI2zOTA/LUF4LPlLE4S/FajTPPxwbFtJX1f2', '2026-01-09 10:37:38', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `budget` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
