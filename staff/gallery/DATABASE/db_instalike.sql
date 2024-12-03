-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2019 at 03:47 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_instalike`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_photos`
--

CREATE TABLE IF NOT EXISTS `tbl_photos` (
`img_id` int(11) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `img_path` varchar(255) NOT NULL,
  `img_type` varchar(255) NOT NULL,
  `img_title` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_photos`
--

INSERT INTO `tbl_photos` (`img_id`, `img_name`, `img_path`, `img_type`, `img_title`) VALUES
(19, 'a2.jpg', 'img/a2.jpg', 'image/jpeg', 'Captain America'),
(20, 'ironSpiderPopHeadArtwork.jpg', 'img/ironSpiderPopHeadArtwork.jpg', 'image/jpeg', 'Iron Spider-Man'),
(21, 'venom.jpg', 'img/venom.jpg', 'image/jpeg', 'Venom'),
(22, 'thanosnew.jpg', 'img/thanosnew.jpg', 'image/jpeg', 'Thanos'),
(24, 'thomyorkkk.jpg', 'img/thomyorkkk.jpg', 'image/jpeg', 'Thom Yorke'),
(26, 'thorgroot.jpg', 'img/thorgroot.jpg', 'image/jpeg', 'Thor, Groot & Rocket - Wakanda Scene'),
(27, 'drstrange.jpg', 'img/drstrange.jpg', 'image/jpeg', 'Doctor Strange'),
(28, 'bp-2.jpg', 'img/bp-2.jpg', 'image/jpeg', 'Black Panther');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_photos`
--
ALTER TABLE `tbl_photos`
 ADD PRIMARY KEY (`img_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_photos`
--
ALTER TABLE `tbl_photos`
MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
