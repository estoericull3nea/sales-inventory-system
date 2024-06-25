-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2024 at 10:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `posnic`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_details`
--

CREATE TABLE `category_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(120) NOT NULL,
  `category_description` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_details`
--

CREATE TABLE `customer_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_name` varchar(200) NOT NULL,
  `customer_address` varchar(500) NOT NULL,
  `customer_contact1` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer_details`
--

INSERT INTO `customer_details` (`id`, `customer_name`, `customer_address`, `customer_contact1`) VALUES
(29, 'qwe qweqwe', 'qwe', 'qwe'),
(30, 'qwe', 'qwe', 'qwe'),
(33, 'qwe', 'qwe', 'qwe'),
(34, 'Miah  qweqwe', 'qwe', 'qwe');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `stock_name` varchar(120) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `supplier_name` varchar(250) NOT NULL,
  `price` int(11) NOT NULL,
  `category` varchar(120) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `stock_name`, `stock_quantity`, `supplier_name`, `price`, `category`, `created_at`) VALUES
(61, 'Kape', 85, 'Ericson Palisoc', 500, '', '2024-06-25 15:52:24'),
(62, 'Milktea', 495, 'Miah Moore', 100, '', '2024-06-25 15:52:55');

-- --------------------------------------------------------

--
-- Table structure for table `stock_avail`
--

CREATE TABLE `stock_avail` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock_avail`
--

INSERT INTO `stock_avail` (`id`, `name`, `quantity`) VALUES
(47, 'stock name 1', 199);

-- --------------------------------------------------------

--
-- Table structure for table `stock_entries`
--

CREATE TABLE `stock_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `stock_name` varchar(260) NOT NULL,
  `quantity` int(11) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `username` varchar(120) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `contact` varchar(200) DEFAULT NULL,
  `payment` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock_entries`
--

INSERT INTO `stock_entries` (`id`, `stock_name`, `quantity`, `selling_price`, `username`, `total`, `address`, `contact`, `payment`, `created_at`) VALUES
(453, 'test', 1, 1.00, 'qwe', 1.00, 'qwe', '123', 0, '2024-06-25 12:01:59'),
(454, 'test', 1, 1.00, 'qwe', 1.00, 'qwe', '123', 0, '2024-06-25 12:02:00'),
(455, 'test', 1, 1.00, 'qwe', 1.00, 'qwe', '123', 0, '2024-06-25 12:02:46'),
(456, 'test', 1, 1.00, 'qwe', 1.00, 'qwe', '123', 0, '2024-06-25 12:03:02'),
(457, 'test', 1, 1.00, 'qwe', 1.00, 'qwe', '123', 0, '2024-06-25 12:03:40'),
(458, 'test', 50, 1.00, 'qwe', 50.00, 'qwe', '123', 0, '2024-06-25 15:09:56'),
(459, 'test', 50, 1.00, 'qwe', 50.00, 'qwe', '123', 0, '2024-06-25 15:47:45'),
(461, 'Kape', 5, 500.00, 'qwe', 2500.00, 'qwe', '123', 0, '2024-06-25 15:53:54'),
(462, 'Kape', 5, 500.00, 'qwe', 2500.00, 'qwe', '123', 6, '2024-06-25 16:07:15'),
(463, 'Kape', 5, 500.00, 'qwe', 2500.00, 'qwe', '123', 500, '2024-06-25 16:07:49'),
(464, 'Milktea', 5, 100.00, 'qwe', 500.00, 'qwe', '123', 500, '2024-06-25 16:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `stock_sales`
--

CREATE TABLE `stock_sales` (
  `id` int(10) NOT NULL,
  `transactionid` varchar(250) NOT NULL,
  `stock_name` varchar(200) NOT NULL,
  `category` varchar(120) NOT NULL,
  `supplier_name` varchar(200) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `username` varchar(120) NOT NULL,
  `customer_id` varchar(120) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `discount` decimal(10,0) NOT NULL,
  `tax` decimal(10,0) NOT NULL,
  `tax_dis` varchar(100) NOT NULL,
  `dis_amount` decimal(10,0) NOT NULL,
  `grand_total` decimal(10,0) NOT NULL,
  `due` date NOT NULL,
  `mode` varchar(250) NOT NULL,
  `description` varchar(500) NOT NULL,
  `count1` int(11) NOT NULL,
  `billnumber` varchar(120) NOT NULL,
  `opp` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_user`
--

CREATE TABLE `stock_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(120) NOT NULL,
  `password` varchar(120) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `answer` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock_user`
--

INSERT INTO `stock_user` (`id`, `username`, `password`, `user_type`, `answer`) VALUES
(1, 'admin', 'admin', 'admin', 'awarapan');

-- --------------------------------------------------------

--
-- Table structure for table `store_details`
--

CREATE TABLE `store_details` (
  `name` varchar(100) NOT NULL,
  `log` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `place` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `web` varchar(100) NOT NULL,
  `pin` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `store_details`
--

INSERT INTO `store_details` (`name`, `log`, `type`, `address`, `place`, `city`, `phone`, `email`, `web`, `pin`) VALUES
('Hafiez Super Store qwe', 'Hafiz Super Store.png', 'image/png', 'Bahawalchowk', 'Near Special Branch Police', 'Bahawalpur', '03453552884', 'ahsnariaz37@yahoo.com', 'Hafiez super Store', '60080'),
('Hafiez Super Store qwe', 'Hafiz Super Store.png', 'image/png', 'Bahawalchowk', 'Near Special Branch Police', 'Bahawalpur', '03453552884', 'ahsnariaz37@yahoo.com', 'Hafiez super Store', '60080');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_details`
--

CREATE TABLE `supplier_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `supplier_name` varchar(200) NOT NULL,
  `supplier_address` varchar(500) NOT NULL,
  `supplier_contact1` varchar(100) NOT NULL,
  `supplier_contact2` varchar(100) NOT NULL,
  `balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supplier_details`
--

INSERT INTO `supplier_details` (`id`, `supplier_name`, `supplier_address`, `supplier_contact1`, `supplier_contact2`, `balance`) VALUES
(53, 'qweqw', 'qweqwe', 'sdfasdA2312', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `customer` varchar(250) NOT NULL,
  `supplier` varchar(250) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `due` datetime NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rid` varchar(120) NOT NULL,
  `receiptid` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uom_details`
--

CREATE TABLE `uom_details` (
  `id` int(10) UNSIGNED ZEROFILL NOT NULL,
  `name` varchar(120) NOT NULL,
  `spec` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_details`
--
ALTER TABLE `category_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_details`
--
ALTER TABLE `customer_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_avail`
--
ALTER TABLE `stock_avail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_entries`
--
ALTER TABLE `stock_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_sales`
--
ALTER TABLE `stock_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_user`
--
ALTER TABLE `stock_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_details`
--
ALTER TABLE `supplier_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uom_details`
--
ALTER TABLE `uom_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_details`
--
ALTER TABLE `category_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `customer_details`
--
ALTER TABLE `customer_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `stock_avail`
--
ALTER TABLE `stock_avail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `stock_entries`
--
ALTER TABLE `stock_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=465;

--
-- AUTO_INCREMENT for table `stock_sales`
--
ALTER TABLE `stock_sales`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `stock_user`
--
ALTER TABLE `stock_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_details`
--
ALTER TABLE `supplier_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `uom_details`
--
ALTER TABLE `uom_details`
  MODIFY `id` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
