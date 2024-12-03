-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2022 at 03:58 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carrental`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'admin', '5c428d8875d2948607f3e3fe134d71b4', '2017-06-18 12:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`) VALUES
(7, 273928077, 1417158928, 'hi'),
(8, 297915250, 1417158928, 'hi hello'),
(9, 1417158928, 569107226, 'anong sinabi ng orange kay apple'),
(10, 569107226, 1417158928, 'ano'),
(11, 1417158928, 569107226, 'are you pineAPPLE'),
(12, 569107226, 1417158928, 'AHAHAHAHAHA'),
(13, 569107226, 1417158928, 'ganda mo po'),
(14, 1417158928, 569107226, 'tnx');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_list`
--

CREATE TABLE `schedule_list` (
  `id` int(30) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedule_list`
--

INSERT INTO `schedule_list` (`id`, `title`, `description`, `start_datetime`, `end_datetime`) VALUES
(6, '1234', '123', '2022-11-01 03:59:00', '2022-11-05 03:59:00'),
(7, 'reynald', 'rey', '2022-11-08 11:55:00', '2022-11-10 11:55:00'),
(8, 'bmw, 123', 'booked', '2022-11-14 11:56:00', '2022-11-17 11:56:00'),
(11, 'bmw danne', 'booked', '2022-09-06 13:55:00', '2022-09-13 13:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) CHARACTER SET latin1 NOT NULL,
  `Password` varchar(100) CHARACTER SET latin1 NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'staff', 'staff123', '2022-11-30 17:50:48');

-- --------------------------------------------------------

--
-- Table structure for table `tblbooking`
--

CREATE TABLE `tblbooking` (
  `id` int(11) NOT NULL,
  `userEmail` varchar(100) DEFAULT NULL,
  `VehicleId` int(11) DEFAULT NULL,
  `FromDate` varchar(20) DEFAULT NULL,
  `ToDate` varchar(20) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `bookstatus` int(11) DEFAULT NULL,
  `image` varchar(60) CHARACTER SET utf8 NOT NULL,
  `BookingNumber` bigint(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblbooking`
--

INSERT INTO `tblbooking` (`id`, `userEmail`, `VehicleId`, `FromDate`, `ToDate`, `message`, `Status`, `PostingDate`, `bookstatus`, `image`, `BookingNumber`) VALUES
(77, 'john@gmail.com', 26, '2022-09-08', '2022-09-15', '12', 0, '2022-11-24 17:36:14', NULL, '', 273423188),
(78, 'kurt@gmail.com', 30, '2022-09-06', '2022-09-13', '123', 1, '2022-11-24 17:43:55', NULL, '', 466273068),
(79, 'kurt@gmail.com', 29, '2022-11-14', '2022-11-17', '123', 1, '2022-11-30 03:57:44', NULL, '', 511516009),
(80, 'kurt@gmail.com', 31, '2022-09-08', '2022-09-09', '123', 0, '2022-11-30 03:59:53', NULL, '', 940586296),
(81, 'danne@gmail.com', 29, '2022-11-18', '2022-11-19', '123', 2, '2022-11-30 04:02:49', NULL, '', 100455769),
(82, 'danne@gmail.com', 31, '2022-11-11', '2022-11-18', 'a', 1, '2022-11-30 07:42:38', NULL, '', 694666382);

-- --------------------------------------------------------

--
-- Table structure for table `tblbookstat`
--

CREATE TABLE `tblbookstat` (
  `id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblbookstat`
--

INSERT INTO `tblbookstat` (`id`, `status`) VALUES
(1, 0),
(2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblbrands`
--

CREATE TABLE `tblbrands` (
  `id` int(11) NOT NULL,
  `BrandName` varchar(120) NOT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblbrands`
--

INSERT INTO `tblbrands` (`id`, `BrandName`, `CreationDate`, `UpdationDate`) VALUES
(1, 'maruti', '2022-10-23 03:12:29', NULL),
(14, 'BMW', '2022-11-05 01:58:04', NULL),
(15, 'sample', '2022-11-24 15:12:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcontactusinfo`
--

CREATE TABLE `tblcontactusinfo` (
  `id` int(11) NOT NULL,
  `Address` tinytext DEFAULT NULL,
  `EmailId` varchar(255) DEFAULT NULL,
  `ContactNo` char(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcontactusinfo`
--

INSERT INTO `tblcontactusinfo` (`id`, `Address`, `EmailId`, `ContactNo`) VALUES
(1, '8643 San Jose St. Guadalupe Nuevo Makati City																								', 'kurtrivera48@gmail.com', '09199044995');

-- --------------------------------------------------------

--
-- Table structure for table `tblcontactusquery`
--

CREATE TABLE `tblcontactusquery` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `EmailId` varchar(120) DEFAULT NULL,
  `ContactNumber` char(11) DEFAULT NULL,
  `Message` longtext DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcontactusquery`
--

INSERT INTO `tblcontactusquery` (`id`, `name`, `EmailId`, `ContactNumber`, `Message`, `PostingDate`, `status`) VALUES
(2, 'Kurt', 'kurt@gmail.com', '09199044995', 'asdas', '2022-11-19 07:55:02', 1),
(3, 'Kurt', 'kurt@gmail.com', '09199044995', 'asdas', '2022-11-19 07:57:28', 1),
(4, 'Kurt', 'kurt@gmail.com', '09199044995', 'asdas', '2022-11-19 07:57:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblcsr`
--

CREATE TABLE `tblcsr` (
  `id` int(11) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `gender` varchar(6) DEFAULT NULL,
  `Password` varchar(30) DEFAULT NULL,
  `Contact` int(11) DEFAULT NULL,
  `Email` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcsr`
--

INSERT INTO `tblcsr` (`id`, `username`, `gender`, `Password`, `Contact`, `Email`) VALUES
(1, 'kurt rivera', '1', '202cb962ac59075b964b07152d234b', 919904499, '456@gmail.com'),
(2, '123456', '1', '6c14da109e294d1e8155be8aa4b1ce', 123, 'staff@gmail.com'),
(3, '123', '1', '202cb962ac59075b964b07152d234b', 123, 'kurt@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tblgender`
--

CREATE TABLE `tblgender` (
  `id` int(11) NOT NULL,
  `Gender` varchar(120) NOT NULL,
  `CreationDate` timestamp NULL DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblgender`
--

INSERT INTO `tblgender` (`id`, `Gender`, `CreationDate`, `UpdationDate`) VALUES
(1, 'Male', NULL, NULL),
(2, 'Female', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblpages`
--

CREATE TABLE `tblpages` (
  `id` int(11) NOT NULL,
  `PageName` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  `detail` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblpages`
--

INSERT INTO `tblpages` (`id`, `PageName`, `type`, `detail`) VALUES
(2, 'Privacy Policy', 'privacy', ''),
(3, 'About Us ', 'aboutus', '																																																																																Renting a car is now easier than ever, thanks to the internet\'s ability to compare prices, available vehicles, and coverage from numerous rental companies. Below, you will find some advice from frequent travelers and industry professionals on what to look out for or avoid in order to save time and money and enjoy your vacation without problems or unpleasant surprises, creating an overview of useful car hire facts. ABOUT US This company has been in operation for the past 12 years. Its birth occurred in the year 2010, when dreams first began, and it became a step toward achieving success and happiness. We are Paras Wheel Hub. With more choices within your comfort, we provide a variety of vehicles from small to large categories according to our client needs. The foundation of Paras Wheel Hub is built on a philosophy of exceptional customer service. Our attention to customer service and our high quality fleet of rental vehicles helps make both leisure vacation travel and business travel easier for our customers. Why choose us? - This will give you access to a wider selection of vehicles. - The vehicle\'s make and model will be confirmed and will not be \"similar\" to those you selected; - If you need a special or long-term rental service, you can directly negotiate payment terms. - We firmly uphold the client-agreed payment conditions and actively enforce a \"No Hidden Charges\" payment policy. - Reservations can be made \"commission-free\"; - Our mobile phone numbers are available 24/7. All of our vehicles have air conditioning, power steering, and power windows. All of our vehicles are purchased and serviced only at authorized dealerships. Automatic transmission vehicles are available in all booking classes. We take pride in providing personalized service, great cars, and low rates. To make sure the solutions we offer are the best fit for our clients\' needs, we review every recommendation before it is sent to them. In order to keep up the high caliber shipping service we guarantee to our consumers, we routinely evaluate the performance of our company. TRIVIA: Did you know? Hertz, the first rental car company, was founded in 1918. Even further back, in 1912, the German company Sixth was founded with only three cars available for rent. The global car rental industry is now valued at $92.92 billion.<br>\r\n										\r\n										\r\n										\r\n										\r\n										\r\n										\r\n										<div><br></div>\r\n										'),
(11, 'FAQs', 'faqs', '																				<p class=\"MsoNormal\">1. What are your renter requirements for renting cars? <br><span style=\"font-size: 1em;\">- To rent a car from Paras Wheel Hub, you will need the\r\nfollowing: - To be 18 years old (as long as you have a Driver\'s License and\r\nFinancial Income) - 2 Valid IDs * A&nbsp;Driver\'s License with a photo * 1\r\nValid ID (For example: Passport)</span></p><p class=\"MsoNormal\"><o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">2. What forms of payment are accepted for renting a car?<br><span style=\"font-size: 1em;\">- For Mode of Payment in Paras Wheel Hub; In CASH - You will\r\nReceive an Email with a Reference Number and Receipt once our admin Confirm the\r\nBooking and you will settle your payment at the Branch before picking up your\r\nRented Car. Note: You only have 2 days before the appointment to settle your\r\npayment. In PAYMENT FIRST *GCash Payment Only ° Full Payment ° Partial Payment\r\n- CSR will handle the booking right away when you choose the Payment First, to\r\nsettle your booking. - CSR will Send the Information Needed to settle your\r\nbooking.</span></p><p class=\"MsoNormal\"><o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">3. If I\'m a Person with Disability and DOES NOT HAVE a\r\nDriver\'s License, Can I Rent a Car From Paras Wheel Hub?<br><span style=\"font-size: 1em;\">&nbsp;- Yes!&nbsp;If due to\r\na disability you do not have a valid driver\'s license, you may still rent a car\r\nwhen accompanied by a surrogate driver who presents a valid driver\'s license.\r\nMinimum age restrictions and other normal rental qualifications apply, but\r\nthere is no additional charge for the surrogate driver.</span></p><p class=\"MsoNormal\"><o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">4. If I\'m a Person with Disability and HAVE a Driver\'s\r\nLicense, Can I Rent a Car From Paras Wheel Hub?<br><span style=\"font-size: 1em;\">- Yes! You can Rent a Car from us as long as you have a\r\nDriver\'s License, with the Agreement of the following; a. In a Emergency/Accident,\r\nthe Company will not have any Liability and Responsibility for the accident b.\r\nWhen the Emergency/Accident occur, the client will bear full responsibility for\r\nthe damages.</span></p><p class=\"MsoNormal\"><o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">5. Will There Be an Additional Charge If I am Late Returning\r\nthe Rental Car?<br><span style=\"font-size: 1em;\">- Great news! There is generally a 29 minutes grace period\r\nfor Rentals. - If the Vehicle is Returned after a 29 minute grace period time,\r\nit will cost a charge. 1-2 hours delay - 250php 3-6 hours delay - 500php 7-12\r\nhours delay - 100php 12-23 hours delay - 1500php 24 hours delay consider as\r\nmissing or stolen and will report at Police.</span></p><p class=\"MsoNormal\"><o:p></o:p></p>\r\n\r\n<p class=\"MsoNormal\">6. What is Pet Policy?<br><span style=\"font-size: 1em;\">&nbsp;- Pets are allowed in\r\nrental vehicles. Customers need to keep their pets crated and return their\r\nrental car in clean condition and free from pet hair to avoid\r\ncleaning/detailing fees. Service animals used by customers with disabilities\r\nare allowed in the vehicle without a carrier.</span></p><p class=\"MsoNormal\"><o:p></o:p></p>										\r\n										\r\n										\r\n										');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubscribers`
--

CREATE TABLE `tblsubscribers` (
  `id` int(11) NOT NULL,
  `SubscriberEmail` varchar(120) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblsubscribers`
--

INSERT INTO `tblsubscribers` (`id`, `SubscriberEmail`, `PostingDate`) VALUES
(4, 'kurt@gmail.com', '2022-11-19 08:32:48'),
(5, 'danne@gmail.com', '2022-11-23 12:54:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbltestimonial`
--

CREATE TABLE `tbltestimonial` (
  `id` int(11) NOT NULL,
  `UserEmail` varchar(100) NOT NULL,
  `Testimonial` mediumtext NOT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbltestimonial`
--

INSERT INTO `tbltestimonial` (`id`, `UserEmail`, `Testimonial`, `PostingDate`, `status`) VALUES
(3, 'danne@gmail.com', 'Great Hihi', '2022-11-23 12:54:57', 0),
(4, 'danne@gmail.com', 'Great Hihi', '2022-11-23 12:55:25', 0),
(5, 'danne@gmail.com', 'asd', '2022-11-30 12:54:29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `EmailId` varchar(100) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `ContactNo` char(11) DEFAULT NULL,
  `dob` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `gender` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `FullName`, `EmailId`, `Password`, `ContactNo`, `dob`, `Address`, `City`, `Country`, `RegDate`, `UpdationDate`, `gender`) VALUES
(41, 'kurt rivera', 'kurt@gmail.com', '202cb962ac59075b964b07152d234b70', '12345678', '2022-11-26', NULL, NULL, NULL, '2022-11-23 12:48:24', '2022-11-25 04:30:23', 1),
(42, 'danne', 'danne@gmail.com', 'caf1a3dfb505ffed0d024130f58c5cfa', '0919904499', '2022-11-11', '8686 san jose', 'guadalupe nuevo', 'guadalupe nuevo', '2022-11-23 12:49:25', '2022-11-30 04:09:39', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tblvehicles`
--

CREATE TABLE `tblvehicles` (
  `id` int(11) NOT NULL,
  `VehiclesTitle` varchar(150) DEFAULT NULL,
  `VehiclesBrand` int(11) DEFAULT NULL,
  `VehiclesOverview` longtext DEFAULT NULL,
  `PricePerDay` int(11) DEFAULT NULL,
  `FuelType` varchar(100) DEFAULT NULL,
  `ModelYear` int(6) DEFAULT NULL,
  `SeatingCapacity` int(11) DEFAULT NULL,
  `Vimage1` varchar(120) DEFAULT NULL,
  `Vimage2` varchar(120) DEFAULT NULL,
  `Vimage3` varchar(120) DEFAULT NULL,
  `Vimage4` varchar(120) DEFAULT NULL,
  `Vimage5` varchar(120) DEFAULT NULL,
  `AirConditioner` int(11) DEFAULT NULL,
  `PowerDoorLocks` int(11) DEFAULT NULL,
  `AntiLockBrakingSystem` int(11) DEFAULT NULL,
  `BrakeAssist` int(11) DEFAULT NULL,
  `PowerSteering` int(11) DEFAULT NULL,
  `DriverAirbag` int(11) DEFAULT NULL,
  `PassengerAirbag` int(11) DEFAULT NULL,
  `PowerWindows` int(11) DEFAULT NULL,
  `CDPlayer` int(11) DEFAULT NULL,
  `CentralLocking` int(11) DEFAULT NULL,
  `CrashSensor` int(11) DEFAULT NULL,
  `LeatherSeats` int(11) DEFAULT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblvehicles`
--

INSERT INTO `tblvehicles` (`id`, `VehiclesTitle`, `VehiclesBrand`, `VehiclesOverview`, `PricePerDay`, `FuelType`, `ModelYear`, `SeatingCapacity`, `Vimage1`, `Vimage2`, `Vimage3`, `Vimage4`, `Vimage5`, `AirConditioner`, `PowerDoorLocks`, `AntiLockBrakingSystem`, `BrakeAssist`, `PowerSteering`, `DriverAirbag`, `PassengerAirbag`, `PowerWindows`, `CDPlayer`, `CentralLocking`, `CrashSensor`, `LeatherSeats`, `RegDate`, `UpdationDate`, `status`) VALUES
(26, 'danne', 1, '123', 123, 'Petrol', 123, 123, 'IMG_1607.JPG', 'IMG_1607.JPG', 'IMG_1607.JPG', 'IMG_1726.JPG', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-23 03:32:10', NULL, NULL),
(27, '123', 14, '123', 123, 'Petrol', 123, 123, 'about_services_faq_bg.jpg', 'about_services_faq_bg.jpg', 'about_services_faq_bg.jpg', 'about_services_faq_bg.jpg', '', 1, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2022-11-06 07:26:06', NULL, NULL),
(28, 'sample1', 1, '123', 123, 'Petrol', 123, 123, 'about_us_img1.jpg', 'about_us_img1.jpg', 'about_us_img1.jpg', 'about_us_img1.jpg', '', 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-06 07:28:12', NULL, NULL),
(29, 'asd', 1, '213', 123, 'Petrol', 123, 12, 'IMG_1543.JPG', 'IMG_1594.JPG', 'IMG_1603.JPG', 'IMG_1607.JPG', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-22 08:26:17', NULL, NULL),
(30, 'kurt', 15, '123', 123, 'Petrol', 123, 123, 'IMG_2052.JPG', 'IMG_2033.JPG', 'IMG_1981.JPG', 'IMG_2378.JPG', '', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-24 15:13:40', NULL, NULL),
(31, 'danne', 14, '123', 123, 'Petrol', 123, 123, 'IMG_1757.JPG', 'IMG_1726.JPG', 'IMG_1543.JPG', 'IMG_1810.JPG', '', 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-24 15:18:50', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `unique_id` int(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `unique_id`, `fname`, `lname`, `email`, `password`, `img`, `status`) VALUES
(1, 1550370196, 'sample', 'sample', 'sample@gmail.com', '202cb962ac59075b964b07152d234b70', '1668605634MONEYTALKS SHIRT.png', 'Active now'),
(2, 1484454690, 'jom', 'sample', 'jom@gmail.com', '202cb962ac59075b964b07152d234b70', '1668605729MONEYTALKS SHIRT.png', 'Active now'),
(3, 524173065, 'kurt', 'sample', 'kurt@gmail.com', '202cb962ac59075b964b07152d234b70', '1668606773MONEYTALKS SHIRT.png', 'Active now'),
(4, 273928077, 'kurt', 'pogi', 'kurtpogi123@gmail.com', '202cb962ac59075b964b07152d234b70', '1669184322bg.jpg', 'Offline now'),
(5, 474750918, 'staff', ' ', 'staff@gmail.com', '202cb962ac59075b964b07152d234b70', '1669199868staff.jpg', 'Active now'),
(6, 1417158928, 'kurt', 'sample', '123@gmail.com', '202cb962ac59075b964b07152d234b70', '1669352158IMG_20221020_142102.jpg', 'Offline now'),
(7, 297915250, 'kurt', 'kurt', '1234@gmail.com', '202cb962ac59075b964b07152d234b70', '1669822957MONEYTALKS SHIRT.png', 'Active now'),
(8, 569107226, 'danne', 'rivera', 'january16@gmail.com', '2f8d81f9980eb2f3e6d9df084a9a0fed', '1669875144photo1651286722.jpeg', 'Active now');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `schedule_list`
--
ALTER TABLE `schedule_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbookstat`
--
ALTER TABLE `tblbookstat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbrands`
--
ALTER TABLE `tblbrands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcontactusinfo`
--
ALTER TABLE `tblcontactusinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcontactusquery`
--
ALTER TABLE `tblcontactusquery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcsr`
--
ALTER TABLE `tblcsr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblgender`
--
ALTER TABLE `tblgender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpages`
--
ALTER TABLE `tblpages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblsubscribers`
--
ALTER TABLE `tblsubscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbltestimonial`
--
ALTER TABLE `tbltestimonial`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblvehicles`
--
ALTER TABLE `tblvehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `schedule_list`
--
ALTER TABLE `schedule_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblbooking`
--
ALTER TABLE `tblbooking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `tblbookstat`
--
ALTER TABLE `tblbookstat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblbrands`
--
ALTER TABLE `tblbrands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tblcontactusinfo`
--
ALTER TABLE `tblcontactusinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblcontactusquery`
--
ALTER TABLE `tblcontactusquery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblcsr`
--
ALTER TABLE `tblcsr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblpages`
--
ALTER TABLE `tblpages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tblsubscribers`
--
ALTER TABLE `tblsubscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbltestimonial`
--
ALTER TABLE `tbltestimonial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tblvehicles`
--
ALTER TABLE `tblvehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
