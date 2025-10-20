-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 11:30 AM
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
-- Database: `grainhub`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertSale` (IN `pid` INT, IN `Quantity` INT, IN `price` DECIMAL(10,2))   BEGIN
 INSERT INTO sales (product_id, qty, price, date)
 VALUES (pid, quantity, price, NOW());
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_authenticate_user` (IN `p_username` VARCHAR(100), IN `p_password` VARCHAR(255))   BEGIN
    DECLARE dbPass VARCHAR(255);
    DECLARE dbId INT;
    DECLARE dbUser VARCHAR(100);
    DECLARE dbLevel VARCHAR(50);

    -- Fetch user info
    SELECT id, username, password, user_level
    INTO dbId, dbUser, dbPass, dbLevel
    FROM users
    WHERE username = p_username
    LIMIT 1;

    -- Check password
    IF dbPass = SHA1(p_password) THEN
        SELECT dbId AS id, dbUser AS username, dbLevel AS user_level;
    ELSE
        SELECT NULL AS id, NULL AS username, NULL AS user_level;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(10, 'Fancy Rice'),
(9, 'Ordinary Rice'),
(14, 'Special Rice');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `file_name`, `file_type`) VALUES
(6, 'KING [Fancy Rice] [25kls].png', 'image/png'),
(7, 'KOHAKU (Pink) Special Rice [25kls].png', 'image/png'),
(8, 'KOHAKU (Red) Special Rice [25kls].png', 'image/png'),
(9, 'KOHAKU (Yellow) Special Rice [25kls].png', 'image/png'),
(10, 'V-160  (Special Rice) [25kls].png', 'image/png'),
(11, 'Red Tonner [Fancy Rice] [25kls].png', 'image/png'),
(47, 'kohaku red.jpg', 'image/jpeg'),
(48, 'kohaku pink.jpg', 'image/jpeg'),
(49, 'v 160.jpg', 'image/jpeg'),
(50, 'red toner.jpg', 'image/jpeg'),
(51, 'king.jpg', 'image/jpeg'),
(52, 'kohaku yellow.jpg', 'image/jpeg'),
(53, '7 tonner.jpg', 'image/jpeg'),
(54, 'joker.jpg', 'image/jpeg'),
(55, '14 mais.jpg', 'image/jpeg'),
(56, '12 mais.jpg', 'image/jpeg'),
(57, 'malagkit.jpg', 'image/jpeg'),
(58, 'kohaku red.jpg', 'image/jpeg'),
(59, 'kohaku pink.jpg', 'image/jpeg'),
(60, 'v 160.jpg', 'image/jpeg'),
(61, 'red toner.jpg', 'image/jpeg'),
(62, 'king.jpg', 'image/jpeg'),
(63, 'kohaku yellow.jpg', 'image/jpeg'),
(64, '7 tonner.jpg', 'image/jpeg'),
(65, 'joker.jpg', 'image/jpeg'),
(66, '14 mais.jpg', 'image/jpeg'),
(67, '12 mais.jpg', 'image/jpeg'),
(68, 'malagkit.jpg', 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(11) UNSIGNED NOT NULL,
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `categorie_id`, `media_id`, `date`, `supplier_id`) VALUES
(42, 'KOHAKU (Red) 25KLS', '49', 920.00, 970.00, 14, 58, '2025-09-24 12:51:46', 1),
(43, 'KOHAKU (Pink) 25KLS', '36', 900.00, 950.00, 14, 59, '2025-09-24 12:55:25', 1),
(45, 'V-160 25KLS', '45', 950.00, 1000.00, 14, 60, '2025-09-24 12:57:53', 1),
(46, 'RED TONNER 25KLS', '34', 990.00, 1040.00, 10, 61, '2025-09-24 13:14:03', 1),
(47, 'KING 25KLS', '34', 1180.00, 1230.00, 10, 62, '2025-09-24 13:19:19', 1),
(49, 'KOHAKU (Yellow) 25KLS', '42', 1000.00, 1050.00, 14, 63, '2025-09-26 04:17:32', 1),
(56, '7-TONNER 25KLS', '35', 820.00, 870.00, 14, 64, '2025-10-04 14:06:21', 1),
(57, 'JOKER 25KLS', '40', 1180.00, 1230.00, 10, 65, '2025-10-04 14:09:56', 1),
(58, 'MAIS (#14) 40KLS', '36', 1150.00, 1200.00, 9, 66, '2025-10-04 14:11:10', 1),
(59, 'MAIS (#12) 40KLS', '38', 1150.00, 1200.00, 9, 67, '2025-10-04 14:12:15', 1),
(60, 'MALAGKIT 25KLS', '40', 1200.00, 1250.00, 10, 68, '2025-10-04 14:13:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `date`) VALUES
(18, 45, 6, 6600.00, '2025-09-24'),
(19, 42, 4, 4280.00, '2025-09-24'),
(20, 47, 15, 18750.00, '2025-09-24'),
(21, 46, 9, 11250.00, '2025-09-24'),
(25, 42, 5, 5350.00, '2025-09-25'),
(26, 49, 15, 19500.00, '2025-09-26'),
(27, 43, 4, 3600.00, '2025-09-26'),
(28, 47, 1, 1230.00, '2025-10-02'),
(29, 49, 3, 3150.00, '2025-10-02'),
(30, 42, 1, 970.00, '2025-10-02'),
(31, 45, 5, 5000.00, '2025-10-02'),
(32, 58, 14, 16800.00, '2025-10-04'),
(33, 59, 12, 14400.00, '2025-10-04'),
(34, 56, 7, 6090.00, '2025-10-04'),
(35, 57, 10, 12300.00, '2025-10-04'),
(36, 60, 10, 12500.00, '2025-10-04'),
(37, 46, 13, 13520.00, '2025-10-04'),
(38, 56, 15, 13050.00, '2025-10-04'),
(39, 56, 4, 3480.00, '2025-10-05'),
(40, 46, 5, 5200.00, '2025-10-05'),
(41, 43, 5, 4750.00, '2025-10-06'),
(42, 46, 1, 1040.00, '2025-10-08'),
(44, 46, 1, 1040.00, '2025-10-08'),
(45, 46, 1, 1040.00, '2025-10-08');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact`, `email`, `address`) VALUES
(1, 'PENGCO ENTERPRISES', '(082) 226 4287', 'pengcoenterprise@gmail.com', 'T.Monteverde Street, Sta. Ana Ave, Davao City, 8000 Davao Del Sur');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, 'Vir Dela Pena', 'Vir', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'wwuyvxws1.png', 1, '2025-10-08 04:15:01'),
(10, 'Jeracel', 'jers', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'no_image.jpg', 1, '2025-10-08 06:44:23'),
(11, 'User', 'User', '12dea96fec20593566ab75692c9949596833adc9', 3, 'no_image.jpg', 1, '2025-10-02 17:19:55'),
(12, 'Special', 'special', 'ba36b97a41e7faf742ab09bf88405ac04f99599a', 2, 'no_image.jpg', 1, '2025-10-02 17:20:55'),
(13, 'Zurhaifa', 'kaka', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'no_image.jpg', 1, '2025-10-02 17:19:37');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(2, 'special', 2, 1),
(3, 'User', 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `media_id` (`media_id`),
  ADD KEY `FK_supplier` (`supplier_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_level` (`user_level`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
