-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2020 at 12:14 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accommo_venientdb`
--

 DROP DATABASE IF EXISTS accommo_venientdb ;
    CREATE DATABASE accommo_venientdb ;
    USE accommo_venientdb;

    DROP TABLE IF EXISTS accommo_venientdb.users,
                         accommo_venientdb.house,
                         accommo_venientdb.pictures,
                         accommo_venientdb.comments;


-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `user_id` int(11) NOT NULL,
  `house_id` int(11) NOT NULL,
  `comment_text` varchar(6000) NOT NULL,
  `comment_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_alias` varchar(20) DEFAULT NULL,
  `rating_score` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`user_id`, `house_id`, `comment_text`, `comment_time`, `user_alias`, `rating_score`) VALUES
(4, 6, 'well this sure was a good place and i had a good memory staying here. I enjoyed the place , most of all the services were really good and everything was catered for and looked upon .I admire this place and i would like every one to enjoy this place', '2020-05-13 13:47:38', 'La Popolia', 4),
(25, 6, 'i like the house, the equipment worked very well and i am happy with the service. I highly recommend it to any new student.', '2020-05-13 17:25:38', NULL, 4),
(11, 6, ' im not happy with the house. There was no air conditioning and the power went out a lot, the wifi was weak .I cant believe they didnt even offer breakfast, i am super pissed !!!!!!!. At least they had a decent garage space', '2020-05-13 17:31:30', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `house`
--

CREATE TABLE `house` (
  `house_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `physical_address` varchar(255) NOT NULL,
  `accomm_type` enum('boys','girls','both') NOT NULL,
  `accommodates` tinyint(2) NOT NULL,
  `ratings_total` int(14) DEFAULT NULL,
  `ratings_awarded` int(14) DEFAULT NULL,
  `rent` decimal(10,0) DEFAULT NULL,
  `essentials` varchar(255) DEFAULT NULL,
  `offered` varchar(255) DEFAULT NULL,
  `map_long` float DEFAULT NULL,
  `map_lat` float DEFAULT NULL,
  `geo_location` varchar(95) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `house`
--

INSERT INTO `house` (`house_id`, `user_id`, `physical_address`, `accomm_type`, `accommodates`, `ratings_total`, `ratings_awarded`, `rent`, `essentials`, `offered`, `map_long`, `map_lat`, `geo_location`) VALUES
(1, 22, '23 2nd Avenue,Elven Park 1,Harare,Zimbabwe', 'both', 2, 135, 109, '40', 'bring your own pot, gas', 'wifi, breakfast', 0, 0, 'Elven Park'),
(2, 22, '32 4th avenue,SwintonVale, Hararez,Zimbabwe', 'both', 1, 500, 356, '90', 'pot, bed sheets, blankets, bucket', 'wifi, supper , transport', NULL, NULL, 'SwintonVale'),
(3, 20, '34 Hugh Havens, Matopo, Le Fuhr', 'boys', 2, 400, 298, '75', 'pots, plates, blankets, mosquito net', 'wifi, oven, microwave, garage, pool', NULL, NULL, 'Matopo'),
(4, 19, '72 Amazon Eden, Future ,Essence View', 'girls', 2, 425, 359, '100', 'blankets, bed mattress, stove', 'coffee , tea, weekend lunch, standard toiletries, microwave, vacuum cleaner ', NULL, NULL, 'Essence View'),
(5, 3, '97 Le Hople, Science Thoughts, Atom way', 'both', 3, 600, 427, '90', 'Blankets, car, dust coat, pots', 'garage, wifi, bbq grill, small library, swimming pool', NULL, NULL, 'Atom Way'),
(6, 22, '45 1st Drive,Mount Pleasant,Harare', 'girls', 2, 200, 103, '95', 'kitchen utensils, toiletries, bedding', 'wifi , garage', NULL, NULL, 'Mount Pleasant');

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `user_id` int(11) NOT NULL,
  `img_desc` varchar(45) NOT NULL,
  `house_id` int(11) DEFAULT NULL,
  `img_location` varchar(255) NOT NULL,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`user_id`, `img_desc`, `house_id`, `img_location`, `last_update`) VALUES
(22, 'house bedroom', 1, 'resources/logo3.jpg', '2020-05-05 21:35:39'),
(22, 'house bathroom', 1, 'media\\pics\\IMG-20200319-WA0049.jpg', '2020-05-05 21:44:39'),
(22, 'house', 1, 'media\\pics\\IMG-20200319-WA0056.jpg', '2020-05-05 21:44:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_type` enum('landlord','student') NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `mid_name` varchar(20) DEFAULT NULL,
  `surname` varchar(20) NOT NULL,
  `gender` enum('female','male') NOT NULL,
  `birthdate` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `passkey` varchar(100) NOT NULL,
  `phone` varchar(14) NOT NULL,
  `physical_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_type`, `first_name`, `mid_name`, `surname`, `gender`, `birthdate`, `email`, `passkey`, `phone`, `physical_address`) VALUES
(1, 'student', 'JOHN', 'ALEXANDRA', 'DOE', 'male', '2020-04-08', 'johndoe@gmail.com', 'a3424f4e7846497d6733a70bd3bfe01a', '77555971', '13 Avenue ,16th City,USJD'),
(2, 'landlord', 'JANE', 'FIONA', 'DOE', 'female', '2020-01-15', 'janedoe@outlook.com', '7468656A616E656B6579', '+26786485867', '167 jane cresent,albao,Janeland'),
(3, 'landlord', 'JANE', 'FIONA', 'DOE', 'female', '2020-01-15', 'janedoe@outlook.com', 'b4d5e8549d4bd4a37a329739e76be255', '+26786485867', '167 jane cresent,albao,Janeland'),
(4, 'student', 'Tom', NULL, 'Hugh', 'male', '1998-10-18', 'tomh14@mailo.org', 'a nice pw', '+767438786', '2323 houtsernburg,LiaHu'),
(5, 'student', 'Bill', NULL, 'bobbie', 'male', '1943-02-24', 'tick@fun.co', '1234--5678p', '+26786485867', '1010 Binary,Good place,United Sates of Joy'),
(6, 'student', 'Del', NULL, 'ale jandre', 'male', '1973-01-24', 'tiie@fun.co', '12345 678p', '+26786485867', '1010 Binary,Good place,United Sates of Yeah'),
(7, 'student', 'Cuz', NULL, 'dumalng', 'male', '1993-10-24', 'tickie@fun.co', '12 3 4 5 678p', '+26786485867', '1010 Binary,Good place,United Sates of Nani'),
(8, 'student', 'Drion', NULL, 'le papa', 'male', '1953-11-24', 'ticke@fun.co', '123 -45678p', '+26786485867', '1010 Binary,Good place,United Sates of Sup'),
(9, 'student', 'Melissa', NULL, 'thelaz', 'female', '1993-05-24', 'tie@fun.co', '123/#45678p', '+26786485867', '1010 Binary,Good place,United Sates of the Place'),
(10, 'student', 'jean', NULL, 'hiyok', 'female', '1963-03-24', 'tkie@fun.co', '1234tj5678p', '+26786485867', '1010 Binary,Good place,United Sates of Lux'),
(11, 'student', 'Alexis', NULL, 'delpapioz', 'female', '1993-07-24', 'tikie@fun.co', '1234rehr5678p', '+26786485867', '1010 Binary,Good place,United Sates of ookay'),
(12, 'student', 'Fedora', NULL, 'ulio yeah', 'female', '1973-09-24', 'kie@fun.co', '123456776h8p', '+26786485867', '1010 Binary,Good place,United Sates of Uhuh'),
(13, 'student', 'Swiss', NULL, 'stein', 'female', '1976-04-24', 'tice@fun.co', '1234567fg8p', '+26786485867', '1010 Binary,Good place,United Sates of HA ha'),
(14, 'student', 'Chelsea', NULL, 'dela cruz', 'female', '1958-11-24', 'tiie@fun.co', '1r234rge5678p', '+26786485867', '1010 Binary,Good place,United Sates of the heck'),
(15, 'student', 'Bunnie', NULL, 'django', 'female', '1994-07-24', 'tice@fun.co', '123jrjh45678p', '+26786485867', '1010 Binary,Good place,United Sates of oh'),
(16, 'landlord', 'Bella', NULL, 'tennod', 'female', '1985-04-24', 'ticki@fun.co', '44f46ec753b59b82eca94982317a9852', '+26786485867', '1010 Binary,Good place,Delrio'),
(17, 'landlord', 'Bommy', NULL, 'tfood', 'male', '1976-09-24', 'ticki1@fun.co', '12345678p', '+26786485867', '1010 Binary,Good place,UPapel Al'),
(18, 'landlord', 'Babes', NULL, 'ten', 'female', '1989-12-24', 'ticki2@fun.co', '12345678p', '+26786485867', '1010 Binary,Good place,Utopic Code'),
(19, 'landlord', 'Girly', NULL, 'tennofoo', 'female', '1981-02-24', 'ticki3@fun.co', '12345678p', '+26786485867', '1010 Binary,Good place,Isotope'),
(20, 'landlord', 'Zack', NULL, 'nofoo', 'male', '1988-11-24', 'ticki5@fun.co', '12345678p', '+26786485867', '1010 Binary,Good place,Quark'),
(21, 'landlord', 'Maya', NULL, 'Thelma', 'male', '2020-04-08', 'helloMaya@gmail.com', 'mayasecretp', '07542763562', '12 road,67 place,Harare,Zimbabwe'),
(22, 'landlord', 'Raul', NULL, 'Swiift', 'male', '2013-04-24', 'swiftmail@mail.com', '5f4dcc3b5aa765d61d8327deb882cf99', '+26377534568', '7th house, Whiterun,Windhelm'),
(23, 'student', 'Raul', NULL, 'Swiift', 'male', '2013-04-24', 'swiftmail01@mail.com', '25d55ad283aa400af464c76d713c07ad', '+26377534568', '7th house, Whiterun,Windhelm'),
(24, 'student', 'Mc Samuel', NULL, 'Shoko', 'male', '1998-12-04', 'mcsam07@outlook.com', '0bc30dc513b18a4a1b82cb28009add6d', '0776666641', '127 7st cresent ,Regime Park 1,Harare'),
(25, 'student', 'Tamarin', NULL, 'Johns', 'female', '2000-05-13', 'tamajohns@gmail.com', 'd064a16da4b0fd7999bf1fb85e3ea41b', '+17624356546', '57 Tamia Road, TamiView, Harare');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `house_id` (`house_id`);

--
-- Indexes for table `house`
--
ALTER TABLE `house`
  ADD PRIMARY KEY (`house_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `house_id` (`house_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `house`
--
ALTER TABLE `house`
  MODIFY `house_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`house_id`) REFERENCES `house` (`house_id`) ON DELETE CASCADE;

--
-- Constraints for table `house`
--
ALTER TABLE `house`
  ADD CONSTRAINT `house_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `pictures_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pictures_ibfk_2` FOREIGN KEY (`house_id`) REFERENCES `house` (`house_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
