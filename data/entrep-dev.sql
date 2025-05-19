-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 11:30 AM
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
-- Database: `entrep-dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', '$2y$10$X6Qr0ikgA8vqK3v4t4v4wO4FoXWk7Pfz4e0QuFiHWBmXvOJx.CZJG');

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiaries`
--

INSERT INTO `beneficiaries` (`id`, `email`, `password`, `name`, `created_at`) VALUES
(1, 'beneficiary@gmail.com', '$2y$10$.O1mvOZ5BbZ8y2.oc6D0qOvY3sLItewpnzwyB8V3K02D6StqRmi0a', 'ghfgh', '2025-05-19 00:48:06');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'joshua', 'opop@gmail.com', '$2y$10$flGdGxn8pceJam96y/YHzOlEx9ZiTo97DjkBnLeUL8VYspPyvGhYy', '2025-05-16 04:24:01'),
(2, 'aa', 'donor@gmail.com', '$2y$10$Vt5fCHSs16QTrtZPMmI.u.4APWbw0cjJnCYd2bJK6xZ1Vg19Lo6XW', '2025-05-18 23:32:44'),
(3, 'jologs', 'jologs@gmail.com', '$2y$10$/8yZMT2Cht9Pu69VqYo2benDd.oPyjO0he2yPlYOPl3Y/GCo2yo.6', '2025-05-19 09:04:59');

-- --------------------------------------------------------

--
-- Table structure for table `item_donations`
--

CREATE TABLE `item_donations` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `item_type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `donation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_donations`
--

INSERT INTO `item_donations` (`id`, `donor_id`, `item_type`, `description`, `quantity`, `photo`, `donation_date`, `created_at`) VALUES
(5, 2, 'Foods', 'rice for all who need it free just take it.', 9, 'donation_682ad38bf13e26.07007791.png', '2025-05-19 06:45:31', '2025-05-19 06:45:31'),
(6, 2, 'Appliances', 'Electric para sa mga tao na walay electric fan sa ilang panimalay tabang para ma sulbad ginag may ang kaigang', 20, 'donation_682ad5865a9211.62292114.png', '2025-05-19 06:53:58', '2025-05-19 06:53:58'),
(7, 3, 'Foods', 'good for 1 month, this food is a Blessing . God Bless You!', 8, 'donation_682af580e44084.40851060.png', '2025-05-19 09:10:24', '2025-05-19 09:10:24');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `audience` enum('donor','beneficiary','all') NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `admin_id`, `audience`, `message`, `created_at`) VALUES
(4, 1, 'donor', 'palihog ko tarungon ug post sa sakto nga butang', '2025-05-19 15:50:18'),
(5, 1, 'all', 'hello users, have fun\r\n', '2025-05-19 16:47:39'),
(6, 1, 'all', 'PAHIBALO! naay panghatag sa cc karon 31, dili lang kay sa system, mulihok pod physical to help the people in need. Thank you!.', '2025-05-19 17:15:32');

-- --------------------------------------------------------

--
-- Table structure for table `report_reads`
--

CREATE TABLE `report_reads` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `read_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `beneficiary_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `beneficiary_id`, `title`, `description`, `request_date`, `status`) VALUES
(1, 1, 'sanina sabata', 'nasunogan ni moy wala mi sanina sa anak', '2025-05-19 05:13:53', 'Confirmed'),
(2, 1, 'tanduway', 'pangpawala sa kalaay wooow', '2025-05-19 08:20:40', 'Denied'),
(3, 1, 'hanger', 'para sa akong sanina ug brief', '2025-05-19 08:50:26', 'Denied'),
(4, 1, 'pintal', 'isa unta ka karton kay gamiton sa renovation sa mga pabahay', '2025-05-19 08:52:33', 'Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `resource_allocations`
--

CREATE TABLE `resource_allocations` (
  `id` int(11) NOT NULL,
  `resource_name` varchar(255) NOT NULL,
  `allocated_to` varchar(255) NOT NULL,
  `allocation_date` date NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource_requests`
--

CREATE TABLE `resource_requests` (
  `id` int(11) NOT NULL,
  `beneficiary_id` int(11) NOT NULL,
  `item_donation_id` int(11) NOT NULL,
  `quantity_requested` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resource_requests`
--

INSERT INTO `resource_requests` (`id`, `beneficiary_id`, `item_donation_id`, `quantity_requested`, `message`, `request_date`, `status`) VALUES
(5, 1, 6, 3, 'salama na gamit nako para sa among balay god bless', '2025-05-19 06:54:59', 'accepted'),
(6, 1, 7, 20, 'Salamat kaayo sa imong pagka maayo. God Bless You sad.', '2025-05-19 09:13:00', 'accepted'),
(7, 1, 7, 2, 'salamt saimo', '2025-05-19 09:17:25', 'accepted');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `item_donations`
--
ALTER TABLE `item_donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `report_reads`
--
ALTER TABLE `report_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `report_donor_unique` (`report_id`,`donor_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource_allocations`
--
ALTER TABLE `resource_allocations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource_requests`
--
ALTER TABLE `resource_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beneficiary_id` (`beneficiary_id`),
  ADD KEY `item_donation_id` (`item_donation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `item_donations`
--
ALTER TABLE `item_donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `report_reads`
--
ALTER TABLE `report_reads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `resource_allocations`
--
ALTER TABLE `resource_allocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource_requests`
--
ALTER TABLE `resource_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_donations`
--
ALTER TABLE `item_donations`
  ADD CONSTRAINT `item_donations_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);

--
-- Constraints for table `report_reads`
--
ALTER TABLE `report_reads`
  ADD CONSTRAINT `report_reads_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_reads_ibfk_2` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resource_requests`
--
ALTER TABLE `resource_requests`
  ADD CONSTRAINT `resource_requests_ibfk_1` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`),
  ADD CONSTRAINT `resource_requests_ibfk_2` FOREIGN KEY (`item_donation_id`) REFERENCES `item_donations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
