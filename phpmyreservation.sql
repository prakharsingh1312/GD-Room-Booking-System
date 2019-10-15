-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 15, 2019 at 11:45 AM
-- Server version: 5.7.27-0ubuntu0.16.04.1
-- PHP Version: 5.6.40-8+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpmyreservation`
--

-- --------------------------------------------------------

--
-- Table structure for table `gd_rooms_facility_detail`
--

CREATE TABLE `gd_rooms_facility_detail` (
  `room_name` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `room_id` int(11) NOT NULL,
  `Floor` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `TV` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `Projector` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `Seating_Capacity` int(11) DEFAULT NULL,
  `HDMI` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `VGA` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `STATUS` varchar(1) NOT NULL DEFAULT 'Y',
  `room_code` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gd_rooms_facility_detail`
--

INSERT INTO `gd_rooms_facility_detail` (`room_name`, `room_id`, `Floor`, `TV`, `Projector`, `Seating_Capacity`, `HDMI`, `VGA`, `STATUS`, `room_code`) VALUES
('Ganga', 1, 'Ground', 'Yes', 'No', 8, 'NO', 'YES', 'Y', 'GD-01'),
('Alaknanda', 2, 'Fourth', 'no', 'no', 8, 'no', 'yes', 'Y', 'GD-03'),
('Yamuna', 3, 'First', 'No', 'Yes', 8, 'NO', 'NO', 'Y', 'GD-02'),
('Godavari', 4, 'First', 'Yes', 'No', 8, 'YES', 'NO', 'Y', 'GD-04'),
('Cauvery', 5, 'First', 'No', 'No', 8, 'NO', 'NO', 'Y', 'GD-05'),
('Brahamputra', 6, 'Second', 'No', 'Yes', 8, 'NO', 'NO', 'Y', 'GD-06'),
('Krishna', 7, 'Second', 'No', 'No', 8, 'NO', 'NO', 'Y', 'GD-07'),
('Narmada', 9, 'Second', 'Yes', 'No', 8, 'NO', 'YES', 'Y', 'GD-09'),
('Saraswati', 10, 'Third', 'No', 'Yes', 8, 'YES', 'NO', 'Y', 'GD-10'),
('Sabarmati', 11, 'Third', 'No', 'No', 8, 'NO', 'NO', 'Y', 'GD-11'),
('Beas', 12, 'Third', 'No', 'No', 8, 'NO', 'NO', 'Y', 'GD-12'),
('Chenab', 13, 'Third', 'Yes', 'No', 8, 'NO', 'YES', 'Y', 'GD-13'),
('Jhelam', 14, 'Third', 'Yes', 'No', 8, 'YES', 'YES', 'Y', 'GD-14');

-- --------------------------------------------------------

--
-- Table structure for table `phpmyreservation_branches`
--

CREATE TABLE `phpmyreservation_branches` (
  `branch_id` int(3) NOT NULL,
  `branch_code` varchar(5) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `STATUS` varchar(1) NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpmyreservation_branches`
--

INSERT INTO `phpmyreservation_branches` (`branch_id`, `branch_code`, `branch_name`, `STATUS`) VALUES
(1, 'COE', 'Computer Engineering', 'Y'),
(2, 'MEE', 'Mechanical Engineering', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `phpmyreservation_configuration`
--

CREATE TABLE `phpmyreservation_configuration` (
  `id` int(10) NOT NULL,
  `price` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `phpmyreservation_configuration`
--

INSERT INTO `phpmyreservation_configuration` (`id`, `price`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `phpmyreservation_groups`
--

CREATE TABLE `phpmyreservation_groups` (
  `group_id` int(10) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `group_admin_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpmyreservation_groups`
--

INSERT INTO `phpmyreservation_groups` (`group_id`, `group_name`, `group_admin_id`) VALUES
(6, 'Hello', 1),
(7, 'JASPS', 1),
(8, '123', 1),
(9, 'nncl', 10),
(10, 'aa', 11),
(11, 'Research Groupt', 12);

-- --------------------------------------------------------

--
-- Table structure for table `phpmyreservation_group_members`
--

CREATE TABLE `phpmyreservation_group_members` (
  `member_id` int(10) NOT NULL,
  `member_group_id` int(10) NOT NULL,
  `member_user_id` int(10) NOT NULL,
  `member_status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpmyreservation_group_members`
--

INSERT INTO `phpmyreservation_group_members` (`member_id`, `member_group_id`, `member_user_id`, `member_status`) VALUES
(1, 6, 1, 1),
(2, 7, 1, 1),
(3, 8, 1, 1),
(5, 7, 9, 1),
(6, 8, 9, 1),
(7, 9, 10, 1),
(8, 9, 9, 1),
(9, 10, 11, 1),
(10, 10, 10, 1),
(11, 11, 12, 1),
(12, 11, 10, 1),
(13, 8, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `phpmyreservation_reservations`
--

CREATE TABLE `phpmyreservation_reservations` (
  `reservation_id` int(10) NOT NULL,
  `reservation_made_time` datetime NOT NULL,
  `reservation_year` smallint(4) NOT NULL,
  `reservation_week` tinyint(2) NOT NULL,
  `reservation_day` tinyint(1) NOT NULL,
  `reservation_time` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `reservation_price` float NOT NULL,
  `reservation_group_id` int(10) NOT NULL,
  `reservation_room_id` int(3) NOT NULL,
  `reservation_check_in` timestamp NULL DEFAULT NULL,
  `reservation_check_out` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `phpmyreservation_reservations`
--

INSERT INTO `phpmyreservation_reservations` (`reservation_id`, `reservation_made_time`, `reservation_year`, `reservation_week`, `reservation_day`, `reservation_time`, `reservation_price`, `reservation_group_id`, `reservation_room_id`, `reservation_check_in`, `reservation_check_out`) VALUES
(31, '2019-08-26 14:51:24', 2019, 35, 2, '08-10', 0, 7, 1, '2019-10-12 11:26:44', '2019-10-12 11:32:20'),
(45, '2019-08-29 07:09:40', 2019, 35, 5, '10-12', 0, 9, 7, '2019-10-13 07:12:26', '2019-10-12 11:32:16'),
(49, '2019-10-14 05:07:10', 2019, 42, 3, '08-10', 0, 7, 6, '2019-10-14 05:29:40', '2019-10-14 05:07:51'),
(54, '2019-10-15 06:14:29', 2019, 42, 2, '08-10', 0, 8, 2, NULL, NULL),
(51, '2019-10-14 05:41:23', 2019, 42, 1, '12-14', 0, 11, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `phpmyreservation_roomdetails`
--

CREATE TABLE `phpmyreservation_roomdetails` (
  `room_id` int(3) NOT NULL,
  `room_code` varchar(10) NOT NULL,
  `room_name` varchar(10) NOT NULL,
  `room_desc` varchar(10000) NOT NULL,
  `STATUS` varchar(1) NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phpmyreservation_roomdetails`
--

INSERT INTO `phpmyreservation_roomdetails` (`room_id`, `room_code`, `room_name`, `room_desc`, `STATUS`) VALUES
(1, 'GD-01', 'GANGA', 'Ground', 'Y'),
(2, 'GD-02', 'YAMUNA', 'First', 'Y'),
(3, 'GD-04', 'GODAVARY', 'First', 'Y'),
(4, 'GD-05', 'CAUVERY', 'First', 'Y'),
(5, 'GD-06', 'BRAHMPUTRA', 'Second', 'Y'),
(6, 'GD-07', 'KRISHNA', 'Second', 'Y'),
(7, 'GD-09', 'NARMADA', 'Second', 'Y'),
(8, 'GD-10', 'SARASWATI', 'Third', 'Y'),
(9, 'GD-11', 'SABARMATI', 'Third', 'Y'),
(10, 'GD-12', 'BEAS', 'Third', 'Y'),
(11, 'GD-13', 'CHENAB', 'Third', 'Y'),
(12, 'GD-14', 'JHELAM', 'Third', 'Y'),
(13, 'GD-03', 'Alaknanda', 'Fourth', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `phpmyreservation_users`
--

CREATE TABLE `phpmyreservation_users` (
  `user_id` int(10) NOT NULL,
  `user_is_admin` tinyint(1) NOT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_password` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_reservation_reminder` tinyint(1) NOT NULL,
  `user_branch_id` int(3) NOT NULL,
  `user_roll_no` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `user_mobile_no` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `user_hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_activated` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `phpmyreservation_users`
--

INSERT INTO `phpmyreservation_users` (`user_id`, `user_is_admin`, `user_email`, `user_password`, `user_name`, `user_reservation_reminder`, `user_branch_id`, `user_roll_no`, `user_mobile_no`, `user_hash`, `user_activated`) VALUES
(1, 1, 'prakharsingh13@gmail.com', '$1$k4i8pa2m$MjVL.etgy0xEurzNy.ORp1', 'Prakhar', 0, 0, '', '', '', 1),
(2, 0, 'rvirmani9@gmail.com', '$1$k4i8pa2m$PcNGP2jrXXKre6RjQo06s0', 'Raghav', 0, 0, '101803657', '', '', 1),
(3, 0, '123@gmail.com', '$1$k4i8pa2m$PcNGP2jrXXKre6RjQo06s0', 'abcd', 0, 0, '', '', '', 0),
(4, 0, 'abc123@gmail.com', '$1$k4i8pa2m$PcNGP2jrXXKre6RjQo06s0', 'abc', 0, 0, '', '', '', 0),
(9, 0, 'psingh4_be18@thapar.edu', '$1$k4i8pa2m$PcNGP2jrXXKre6RjQo06s0', 'PrakharSI', 0, 1, '101803291', '9650998499', 'a47931eb706578ccf0ae4333801679dc', 1),
(10, 1, 'ananda@thapar.edu', '$1$k4i8pa2m$PcNGP2jrXXKre6RjQo06s0', 'Archana', 0, 1, '001200818', '0987654321', '', 1),
(11, 0, 'rakesh_cl@thapar.edu', '$1$k4i8pa2m$MhPa2rKHec8wGi/HBacs6/', 'Rakesh', 0, 1, '000211091', '9888677868', '', 1),
(12, 1, 'shriram@thapar.edu', '$1$k4i8pa2m$bunO21O.qGxvIvzMrb8gG.', 'Shri Ram', 0, 1, '001215638', '8288083191', '', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gd_rooms_facility_detail`
--
ALTER TABLE `gd_rooms_facility_detail`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `phpmyreservation_branches`
--
ALTER TABLE `phpmyreservation_branches`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `phpmyreservation_configuration`
--
ALTER TABLE `phpmyreservation_configuration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phpmyreservation_groups`
--
ALTER TABLE `phpmyreservation_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `phpmyreservation_group_members`
--
ALTER TABLE `phpmyreservation_group_members`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `phpmyreservation_reservations`
--
ALTER TABLE `phpmyreservation_reservations`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `phpmyreservation_roomdetails`
--
ALTER TABLE `phpmyreservation_roomdetails`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `phpmyreservation_users`
--
ALTER TABLE `phpmyreservation_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `phpmyreservation_branches`
--
ALTER TABLE `phpmyreservation_branches`
  MODIFY `branch_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `phpmyreservation_configuration`
--
ALTER TABLE `phpmyreservation_configuration`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `phpmyreservation_groups`
--
ALTER TABLE `phpmyreservation_groups`
  MODIFY `group_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `phpmyreservation_group_members`
--
ALTER TABLE `phpmyreservation_group_members`
  MODIFY `member_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `phpmyreservation_reservations`
--
ALTER TABLE `phpmyreservation_reservations`
  MODIFY `reservation_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `phpmyreservation_roomdetails`
--
ALTER TABLE `phpmyreservation_roomdetails`
  MODIFY `room_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `phpmyreservation_users`
--
ALTER TABLE `phpmyreservation_users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
