-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 01, 2025 at 03:49 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `socialboost`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(25) NOT NULL,
  `pass` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `pass`) VALUES
(1, 'admin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055');

-- --------------------------------------------------------

--
-- Table structure for table `adminservice`
--

DROP TABLE IF EXISTS `adminservice`;
CREATE TABLE IF NOT EXISTS `adminservice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  `icon` varchar(25) NOT NULL,
  `iconColour` varchar(25) NOT NULL,
  `iconBg` varchar(25) NOT NULL,
  `highLight` varchar(25) NOT NULL,
  `highLightBg` varchar(25) NOT NULL,
  `highLightColour` varchar(25) NOT NULL,
  `des` text NOT NULL,
  `platform` varchar(25) NOT NULL,
  `platformColour` varchar(25) NOT NULL,
  `platformBg` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `socialUrl` varchar(25) NOT NULL,
  `postUrl` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adminservice`
--

INSERT INTO `adminservice` (`id`, `title`, `icon`, `iconColour`, `iconBg`, `highLight`, `highLightBg`, `highLightColour`, `des`, `platform`, `platformColour`, `platformBg`, `socialUrl`, `postUrl`) VALUES
(3, 'Activate Instagram Tasks', 'fas fa-star', 'text-red-500', 'bg-red-100', 'Admin Task', 'bg-blue-100', 'text-blue-800', 'follow socialboost,\r\nLike this socialboost post,\r\nshare this socialboost post,\r\npost this comment on the video\r\n', 'Instagram', 'text-blue-800', 'bg-blue-100', 'https://web.facebook.com/', 'https://web.facebook.com/'),
(4, 'Activate Facebook Tasks', 'fas fa-star', 'text-red-500', 'bg-red-100', 'Admin Task', 'bg-blue-100', 'text-blue-800', 'follow socialboost,\r\nLike this socialboost post,\r\nShare post,\r\npost this comment on the video\r\n', 'Facebook', 'text-blue-800', 'bg-blue-100', 'https://web.facebook.com/', 'https://web.facebook.com/'),
(5, 'Activate Youtube Task', 'fas fa-star', 'text-red-500', 'bg-red-100', 'Admin Task', 'bg-blue-100', 'text-blue-800', 'Subscribe to Socialboost Youtube Channel,\r\nLike this socialboost video,\r\nShare this socialboost video,\r\npost this comment on the video\r\n', 'Youtube', 'text-blue-800', 'bg-blue-100', 'https://web.facebook.com/', 'https://web.facebook.com/'),
(6, 'Activate Whatsapp Tasks', 'fas fa-star', 'text-red-500', 'bg-red-100', 'Admin Task', 'bg-blue-100', 'text-blue-800', 'Post this Socialboost Video on your WhatsApp status for 24 hrs\r\n', 'Whatsapp', 'text-blue-800', 'bg-blue-100', 'https://web.facebook.com/', 'https://web.facebook.com/'),
(7, 'Activate Twitter Tasks', 'fas fa-star', 'text-red-500', 'bg-red-100', 'Admin Task', 'bg-blue-100', 'text-blue-800', 'follow socialboost,\r\nLike this socialboost post,\r\nretweet post,\r\npost this comment on the video\r\n', 'Twitter', 'text-blue-800', 'bg-blue-100', 'https://web.facebook.com/', 'https://web.facebook.com/'),
(8, 'Activate Apple Tasks', 'fas fa-star', 'text-red-500', 'bg-red-100', 'Admin Task', 'bg-blue-100', 'text-blue-800', 'Install this App,\r\nLeave a 5 star rating and post this review on the Apps review section', 'Apple', 'text-blue-800', 'bg-blue-100', 'https://web.facebook.com/', 'https://web.facebook.com/'),
(9, 'Activate Google Tasks', 'fas fa-star', 'text-red-500', 'bg-red-100', 'Admin Task', 'bg-blue-100', 'text-blue-800', 'Install this App,\r\nLeave a 5 star rating and post this review on the Apps review section', 'Google', 'text-blue-800', 'bg-blue-100', 'https://web.facebook.com/', 'https://web.facebook.com/');

-- --------------------------------------------------------

--
-- Table structure for table `admintask`
--

DROP TABLE IF EXISTS `admintask`;
CREATE TABLE IF NOT EXISTS `admintask` (
  `id` int NOT NULL AUTO_INCREMENT,
  `adminServiceId` int NOT NULL,
  `earnerId` int NOT NULL,
  `receipt` varchar(255) NOT NULL,
  `status` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'pending',
  `taskDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `adminserviceId` (`adminServiceId`),
  KEY `earnerId` (`earnerId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admintask`
--

INSERT INTO `admintask` (`id`, `adminServiceId`, `earnerId`, `receipt`, `status`, `taskDate`) VALUES
(2, 3, 17, 'admin_screenshot/receipt_68b5b8805544a6.11455285.png', 'Completed', '2025-09-01 12:47:31'),
(3, 4, 17, '', 'pending', '2025-09-01 12:47:31'),
(4, 5, 17, '', 'pending', '2025-09-01 12:47:31'),
(5, 6, 17, '', 'pending', '2025-09-01 12:47:31'),
(6, 7, 17, '', 'pending', '2025-09-01 12:47:31'),
(7, 8, 17, '', 'pending', '2025-09-01 12:47:31'),
(8, 9, 17, '', 'pending', '2025-09-01 12:47:31');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `description` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'reviews', 'App Reviews'),
(2, 'followers', 'Followers'),
(3, 'likes', 'Likes'),
(4, 'views', 'Views');

-- --------------------------------------------------------

--
-- Table structure for table `earnerwallet`
--

DROP TABLE IF EXISTS `earnerwallet`;
CREATE TABLE IF NOT EXISTS `earnerwallet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `balance` decimal(11,2) NOT NULL DEFAULT '0.00',
  `bankCode` varchar(25) NOT NULL,
  `accountNumber` varchar(25) NOT NULL,
  `bankName` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `earnerwallet`
--

INSERT INTO `earnerwallet` (`id`, `userId`, `balance`, `bankCode`, `accountNumber`, `bankName`) VALUES
(7, 17, 0.00, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `earning`
--

DROP TABLE IF EXISTS `earning`;
CREATE TABLE IF NOT EXISTS `earning` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `balance` decimal(11,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `referral`
--

DROP TABLE IF EXISTS `referral`;
CREATE TABLE IF NOT EXISTS `referral` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(35) NOT NULL,
  `advDes` text NOT NULL,
  `earnerDes` text NOT NULL,
  `advPrice` decimal(11,2) NOT NULL,
  `earnerPrice` decimal(11,2) NOT NULL,
  `per` int NOT NULL,
  `platform` varchar(25) NOT NULL,
  `imgUrl` varchar(65) NOT NULL,
  `imgBg` varchar(25) NOT NULL,
  `icon` varchar(25) NOT NULL,
  `iconBg` varchar(25) NOT NULL,
  `iconColour` varchar(25) NOT NULL,
  `rating` decimal(5,2) NOT NULL,
  `ratingCount` int NOT NULL,
  `platformBg` varchar(25) NOT NULL,
  `platformColour` varchar(25) NOT NULL,
  `highLight` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `highLightColour` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `highLightBg` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `categoryId` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categoryId` (`categoryId`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `title`, `advDes`, `earnerDes`, `advPrice`, `earnerPrice`, `per`, `platform`, `imgUrl`, `imgBg`, `icon`, `iconBg`, `iconColour`, `rating`, `ratingCount`, `platformBg`, `platformColour`, `highLight`, `highLightColour`, `highLightBg`, `categoryId`) VALUES
(3, 'Instagram Followers', 'Real, active Instagram followers to grow your profile and increase engagement.', 'Follow the specified Instagram account and stay following for at least 7 days.', 28.80, 2.70, 10, 'Instagram', 'https://cdn-icons-png.flaticon.com/512/2111/2111463.png', 'bg-blue-100', 'fab fa-instagram', 'bg-purple-100', 'text-purple-500', 4.70, 1500, 'bg-purple-100', 'text-purple-800', 'LIMITED', 'text-red-800', 'bg-red-100', 2),
(4, 'YouTube Views', 'High-retention YouTube views to boost your video\'s ranking and visibility.', 'Watch the YouTube video provided on the advertiser\'s link.', 19.30, 0.93, 100, 'Youtube', 'https://cdn-icons-png.flaticon.com/512/1384/1384060.png', 'bg-red-100', 'fas fa-star', 'bg-red-100', 'text-red-500', 4.90, 1300, 'bg-blue-100', 'text-blue-800', 'TRENDING', 'text-yellow-800', 'bg-yellow-100', 4),
(5, 'WhatsApp Group Members', 'Active members for your WhatsApp groups to increase engagement and visibility.', 'Join the WhatsApp Group with the link provided.', 14.50, 0.45, 100, 'WhatsApp', 'https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg', 'bg-green-100', 'fab fa-whatsapp', 'bg-green-100', 'text-green-500', 4.70, 1300, 'bg-green-100 ', 'text-blue-800', 'POPULAR', 'text-yellow-500', 'text-blue-800', 2),
(6, 'Twitter Followers', 'Real Twitter followers to increase your social proof and engagement rate.', 'Follow the specified twitter account and stay following for at least 7 days.', 17.60, 0.69, 100, 'Twitter', 'https://toppng.com/uploads/preview/twitter-icon-transparent-11549', 'bg-blue-100', 'fab fa-twitter', 'bg-blue-100', 'text-blue-500', 4.50, 987, 'bg-blue-100', 'text-blue-800', 'HOT', 'text-purple-800', 'bg-purple-100', 2);

-- --------------------------------------------------------

--
-- Table structure for table `socialorder`
--

DROP TABLE IF EXISTS `socialorder`;
CREATE TABLE IF NOT EXISTS `socialorder` (
  `id` varchar(25) NOT NULL,
  `serviceId` int NOT NULL,
  `advId` int NOT NULL,
  `amountSpent` decimal(10,2) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'pending',
  `orderDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quantity` int NOT NULL,
  `orderCountTrack` int NOT NULL,
  `socialUrl` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `serviceId` (`serviceId`),
  KEY `advId` (`advId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `socialorder`
--

INSERT INTO `socialorder` (`id`, `serviceId`, `advId`, `amountSpent`, `status`, `orderDate`, `quantity`, `orderCountTrack`, `socialUrl`) VALUES
('IF-61607', 3, 15, 29.00, 'In Progress', '2025-08-28 07:54:55', 10, 8, 'https://web.facebook.com/akubue.alex1'),
('IF-80217', 3, 15, 28.80, 'In Progress', '2025-09-01 07:43:38', 10, 9, 'https://web.facebook.com/akubue.alex1'),
('WGM-40528', 5, 15, 44.00, 'In Progress', '2025-08-12 09:32:17', 300, 299, 'https://web.facebook.com/akubue.alex1'),
('WGM-83801', 5, 15, 15.00, 'In Progress', '2025-09-01 07:36:16', 100, 99, 'https://web.facebook.com/akubue.alex1');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` varchar(25) NOT NULL,
  `orderId` varchar(25) NOT NULL,
  `earnerId` int NOT NULL,
  `status` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'In Progress',
  `receipt` varchar(255) NOT NULL,
  `taskDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `serviceId` (`orderId`),
  KEY `earnerId` (`earnerId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `orderId`, `earnerId`, `status`, `receipt`, `taskDate`) VALUES
('IF-51144', 'IF-61607', 17, 'Pending Approval', 'screenshot/receipt_68b5c02a97ca55.07877121.JPG', '2025-09-01 15:46:21');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fullName` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pass` varchar(35) NOT NULL,
  `userType` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `profilePicture` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `userName` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(18) NOT NULL,
  `otp` int NOT NULL,
  `verifiedStatus` tinyint(1) NOT NULL DEFAULT '0',
  `resetToken` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fullName`, `pass`, `userType`, `profilePicture`, `userName`, `email`, `phone`, `otp`, `verifiedStatus`, `resetToken`) VALUES
(15, 'lexcyd Xino', 'a4241229e67258cfcbb7fdd683d14277', 'advertiser', '', 'lexcyd', 'lexcyd@gmail.com', '09065151127', 87517, 1, ''),
(17, 'Earner Tito', 'a4241229e67258cfcbb7fdd683d14277', 'earner', '', 'Tito', 'earner@gmail.com', '09065151126', 32640, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

DROP TABLE IF EXISTS `wallet`;
CREATE TABLE IF NOT EXISTS `wallet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `balance` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`id`, `userId`, `balance`) VALUES
(4, 15, 231.55);

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal`
--

DROP TABLE IF EXISTS `withdrawal`;
CREATE TABLE IF NOT EXISTS `withdrawal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'pending',
  `withdrawDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admintask`
--
ALTER TABLE `admintask`
  ADD CONSTRAINT `admintask_ibfk_1` FOREIGN KEY (`adminServiceId`) REFERENCES `adminservice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admintask_ibfk_2` FOREIGN KEY (`earnerId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `earnerwallet`
--
ALTER TABLE `earnerwallet`
  ADD CONSTRAINT `earnerwallet_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `earning`
--
ALTER TABLE `earning`
  ADD CONSTRAINT `earning_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `referral`
--
ALTER TABLE `referral`
  ADD CONSTRAINT `referral_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `socialorder`
--
ALTER TABLE `socialorder`
  ADD CONSTRAINT `socialorder_ibfk_1` FOREIGN KEY (`advId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `socialorder_ibfk_2` FOREIGN KEY (`serviceId`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`earnerId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`orderId`) REFERENCES `socialorder` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wallet`
--
ALTER TABLE `wallet`
  ADD CONSTRAINT `wallet_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `withdrawal`
--
ALTER TABLE `withdrawal`
  ADD CONSTRAINT `withdrawal_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
