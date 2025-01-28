-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2021 at 03:32 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oph_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_list`
--

CREATE TABLE `company_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company_list`
--

INSERT INTO `company_list` (`id`, `name`, `description`, `status`, `date_created`, `date_updated`) VALUES
(1, 'Electric Provider Corp.', 'This is a sample Electric Provider Company', 1, '2021-10-29 09:58:11', '2021-10-29 11:37:10'),
(2, 'Water Provider Corp.', 'This is a sample Water Provider Company', 1, '2021-10-29 09:59:05', '2021-10-29 11:37:10'),
(3, 'Telecom Corp.', 'This is a sample Telecom/Internet Provider Company', 1, '2021-10-29 09:59:46', '2021-10-29 11:37:10'),
(4, 'Financing Corp', 'This is sample Financing Company', 1, '2021-10-29 10:00:21', '2021-10-29 11:37:10'),
(5, 'Lending Corp', 'This is a sample Lending Company', 1, '2021-10-29 10:00:48', '2021-10-29 11:37:10');

-- --------------------------------------------------------

--
-- Table structure for table `fee_list`
--

CREATE TABLE `fee_list` (
  `id` int(30) NOT NULL,
  `amount_from` float NOT NULL DEFAULT 0,
  `amount_to` float NOT NULL DEFAULT 0,
  `fee` float NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fee_list`
--

INSERT INTO `fee_list` (`id`, `amount_from`, `amount_to`, `fee`, `date_created`) VALUES
(1, 0.01, 5000, 15, '2021-10-29 10:05:56'),
(2, 5001, 10000, 25, '2021-10-29 10:06:34'),
(3, 10001, 25000, 35, '2021-10-29 10:06:56'),
(4, 25001, 1000000000000, 50, '2021-10-29 10:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Online Payment Hub - PHP'),
(6, 'short_name', 'OPH - PHP'),
(11, 'logo', 'uploads/logo-1635480106.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover-1635471753.png'),
(15, 'content', 'Array');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_list`
--

CREATE TABLE `transaction_list` (
  `id` int(30) NOT NULL,
  `tracking_code` varchar(50) NOT NULL,
  `company_id` int(30) DEFAULT NULL,
  `account_name` text NOT NULL,
  `account_number` varchar(250) NOT NULL,
  `amount_to_pay` float NOT NULL,
  `payable_amount` float NOT NULL DEFAULT 0,
  `fee` float NOT NULL DEFAULT 0,
  `payment_code` varchar(250) NOT NULL,
  `user_id` int(30) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_list`
--

INSERT INTO `transaction_list` (`id`, `tracking_code`, `company_id`, `account_name`, `account_number`, `amount_to_pay`, `payable_amount`, `fee`, `payment_code`, `user_id`, `date_created`, `date_updated`) VALUES
(1, 'NCK-314576340959', 3, 'John Smith', '123565465', 2500, 2515, 15, 'PAYID-MF53XEA05046959889420436', 2, '2021-10-29 17:15:32', '2021-10-29 17:35:14'),
(2, 'UHK-317568108296', 5, 'John Smith', '1234567899', 4500, 4515, 15, 'PAYID-MF6KAUA5YV50836FG238135L', 2, '2021-10-30 09:31:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_meta`
--

CREATE TABLE `transaction_meta` (
  `transaction_id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', NULL, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatar-1.png?v=1635556826', NULL, 1, '2021-01-20 14:02:37', '2021-10-30 09:20:26'),
(2, 'Johnny', 'D', 'Smith', 'jsmith@sample.com', '1254737c076cf867dc53d60a0364f38e', 'uploads/avatar-2.png?v=1635490031', NULL, 2, '2021-10-29 14:47:11', '2021-10-29 16:12:41'),
(3, 'Claire', 'D', 'Blake', 'cblake@sample.com', '4744ddea876b11dcb1d169fadf494418', 'uploads/avatar-3.png?v=1635490172', NULL, 2, '2021-10-29 14:49:32', '2021-10-29 14:58:34'),
(4, 'Claire', NULL, 'Blake', 'cblake', 'cd74fae0a3adf459f73bbf187607ccea', 'uploads/avatar-4.png?v=1635555357', NULL, 1, '2021-10-30 08:55:57', '2021-10-30 08:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

CREATE TABLE `user_meta` (
  `user_id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_meta`
--

INSERT INTO `user_meta` (`user_id`, `meta_field`, `meta_value`, `date_created`) VALUES
(2, 'dob', '1997-06-23', '2021-10-29 14:47:11'),
(2, 'contact', '09123456789', '2021-10-29 14:47:11'),
(2, 'address', 'Sample Address', '2021-10-29 14:47:11'),
(3, 'dob', '1997-10-14', '2021-10-29 14:49:32'),
(3, 'contact', '097894561335', '2021-10-29 14:49:32'),
(3, 'address', 'Sample Address', '2021-10-29 14:49:32'),
(2, 'gender', 'Male', '2021-10-29 16:11:52'),
(2, 'dob', '1997-06-23', '2021-10-29 16:11:52'),
(2, 'contact', '09123456789', '2021-10-29 16:11:52'),
(2, 'address', 'Sample Address', '2021-10-29 16:11:52'),
(2, 'gender', 'Male', '2021-10-29 16:12:41'),
(2, 'dob', '1997-06-23', '2021-10-29 16:12:41'),
(2, 'contact', '09123456789', '2021-10-29 16:12:41'),
(2, 'address', 'Sample Address', '2021-10-29 16:12:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_list`
--
ALTER TABLE `company_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_list`
--
ALTER TABLE `fee_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_list`
--
ALTER TABLE `transaction_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaction_meta`
--
ALTER TABLE `transaction_meta`
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_list`
--
ALTER TABLE `company_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fee_list`
--
ALTER TABLE `fee_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `transaction_list`
--
ALTER TABLE `transaction_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaction_list`
--
ALTER TABLE `transaction_list`
  ADD CONSTRAINT `transaction_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transaction_meta`
--
ALTER TABLE `transaction_meta`
  ADD CONSTRAINT `transaction_meta_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction_list` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
