-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2025 at 01:28 AM
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
-- Database: `medi-o`
--

-- --------------------------------------------------------

--
-- Table structure for table `best_selling_items`
--

CREATE TABLE `best_selling_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image_paths` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `best_selling_items`
--

INSERT INTO `best_selling_items` (`id`, `item_name`, `image_path`, `price`, `rating`, `created_at`, `updated_at`, `image_paths`) VALUES
(1, 'Rossmax Peak Flow Meter (Child)', '', 1324.00, 4.0, '2025-08-02 22:37:53', '2025-08-02 22:37:53', 'uploads/images/688e9341b894e-photo-1590272456521-1bbe160a18ce.png,uploads/images/688e9341b8e49-Blue and White Flat Illustrative Health Products Logo.png,uploads/images/688e9341b922d-medi-o-logo.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `best_selling_items`
--
ALTER TABLE `best_selling_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `best_selling_items`
--
ALTER TABLE `best_selling_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
