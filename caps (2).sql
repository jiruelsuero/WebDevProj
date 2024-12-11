-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 07:38 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `caps`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `description`, `category`, `image_url`) VALUES
(0, 'ewewwew', '2000000.00', 'wew', 'Baseball', '99.jpg'),
(1, 'PowerPlay', '12000.00', 'A lightweight baseball cap for active individuals.', 'Baseball', '1.jfif'),
(2, 'ClassicCurve', '15000.00', 'A classic baseball cap with a curved brim, perfect for casual wear.', 'Baseball', '98.jpg'),
(3, 'SunShield', '18000.00', 'A baseball cap with UV protection for sunny days.', 'Baseball', '99.jpg'),
(4, 'RetroFlat', '22000.00', 'A retro-style flat cap for timeless fashion.', 'Flat Caps', '11.jpg'),
(5, 'UrbanGent', '24000.00', 'A flat cap designed for modern gentlemen, blending tradition and style.', 'Flat Caps', '12.jpg'),
(6, 'HeritageTweed', '28000.00', 'A premium tweed flat cap inspired by heritage designs.', 'Flat Caps', '11.jpg'),
(7, 'StreetVibe', '18000.00', 'A stylish snapback cap for urban streetwear.', 'Snapback', '24.jpg'),
(8, 'MonoTone', '20000.00', 'A minimalistic snapback with monochrome tones for sleek aesthetics.', 'Snapback', '25.jpg'),
(9, 'BoldEdge', '25000.00', 'A snapback with bold designs and an edgy look.', 'Snapback', '26.jpg'),
(10, 'CozyKnits', '15000.00', 'A warm and cozy knitted beanie for chilly weather.', 'Beanie', '20.jpg'),
(11, 'ArcticWave', '22000.00', 'A beanie with thermal lining for ultimate warmth during winter.', 'Beanie', '21.jpg'),
(12, 'SlouchStyle', '18000.00', 'A trendy slouch beanie perfect for casual looks.', 'Beanie', '23.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `product_id`, `name`, `rating`, `comment`, `date`) VALUES
(1, 1, 'tato ', 1, 'i love this hat', '2024-12-03 15:22:12'),
(2, 1, 'tato ', 1, 'yes', '2024-12-03 15:22:26'),
(3, 1, 'test2', 1, 'yessir', '2024-12-03 15:23:15'),
(4, 1, 'tato ', 4, 'nice', '2024-12-03 15:26:03'),
(5, 1, 'tato ', 1, 'i like this hat', '2024-12-03 15:27:30'),
(6, 1, 'tato ', 1, 'like this hat', '2024-12-03 15:28:12'),
(7, 1, 'tato ', 1, 'love this hat', '2024-12-03 15:29:48'),
(8, 1, 'tato ', 1, 'i lvoe this hat', '2024-12-03 15:31:49'),
(9, 1, 'tato ', 5, 'love this hat', '2024-12-03 15:33:28'),
(10, 1, 'tato ', 5, 'tato', '2024-12-03 15:38:45'),
(11, 1, 'tato ', 1, '1', '2024-12-03 15:41:25'),
(12, 2, 'tato ', 5, 'i love this hat', '2024-12-03 15:42:20'),
(13, 2, 'tato ', 1, 'i dont like this hat', '2024-12-03 16:09:49'),
(14, 3, 'test2', 5, 'ok', '2024-12-03 16:40:42'),
(15, 3, 'test2', 3, 'love this', '2024-12-03 17:02:37'),
(16, 8, 'test2', 5, 'meh', '2024-12-03 17:04:21'),
(17, 4, 'test2', 5, 'i liek this hat', '2024-12-08 09:01:18'),
(18, 1, 'mel', 5, 'wow this hat is lovable', '2024-12-08 10:23:00'),
(19, 1, 'test2', 5, 'wew', '2024-12-08 15:54:40'),
(20, 4, 'test2', 5, 'wow', '2024-12-09 14:38:26'),
(21, 1, 'test2', 5, 'wow', '2024-12-09 15:07:39'),
(22, 2, 'test2', 5, 'hello', '2024-12-10 02:26:46'),
(23, 1, '1', 5, 'nice', '2024-12-10 03:33:40'),
(24, 4, '2', 1, 'wow', '2024-12-11 03:44:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `username`, `password`, `created_at`) VALUES
(1, '1', '1@gmail.com', '1', '$2y$10$GNuHrlzFEQoz8yEtuq9Mb.2QNHsL6cR8UOgzEQfpfN5L3Ts2VXA5S', '2024-12-11 03:22:28'),
(3, '2', '2@gmail.com', '2', '$2y$10$mpmegimGqVoVKMHm2LIyUuYjw8Uah3EQUBVliCOtm3yhlLc15u2K6', '2024-12-11 03:26:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `reviews_ibfk_1` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
