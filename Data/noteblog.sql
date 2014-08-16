-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 17, 2014 at 03:21 AM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `noteblog`
--

-- --------------------------------------------------------

--
-- Table structure for table `attaches`
--

CREATE TABLE IF NOT EXISTS `attaches` (
  `attach_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `attach_name` varchar(75) COLLATE utf8_persian_ci NOT NULL,
  `attach_body` text COLLATE utf8_persian_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `is_shared` tinyint(1) NOT NULL DEFAULT '0',
  `is_trashed` tinyint(1) NOT NULL DEFAULT '0',
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `is_starred` tinyint(1) NOT NULL DEFAULT '0',
  `last_viewed_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`attach_id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`),
  KEY `link_name` (`attach_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=114 ;

--
-- Dumping data for table `attaches`
--

INSERT INTO `attaches` (`attach_id`, `user_id`, `attach_name`, `attach_body`, `parent_id`, `is_shared`, `is_trashed`, `is_archived`, `is_starred`, `last_viewed_at`, `created_at`, `updated_at`) VALUES
(113, 15, '6-Bayesian-1.pdf', '/access/uploads/2013/08/2/pdf/d198f0e3d7c2e7ac432d178cd7a6812d.pdf', 51, 0, 0, 0, 0, '2013-08-20 00:04:17', '2013-08-15 04:22:33', '2013-08-20 00:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `user_id` int(11) NOT NULL,
  `blog_name` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  UNIQUE KEY `blog_id` (`user_id`,`blog_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`user_id`, `blog_name`, `created_at`, `updated_at`) VALUES
(15, 'dariush_blog_foo', '2013-08-16 05:18:21', '2013-08-16 05:18:33');

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE IF NOT EXISTS `bookmarks` (
  `bookmark_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `bookmark_name` varchar(75) COLLATE utf8_persian_ci NOT NULL,
  `bookmark_body` text COLLATE utf8_persian_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `is_shared` tinyint(1) NOT NULL DEFAULT '0',
  `is_trashed` tinyint(1) NOT NULL DEFAULT '0',
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `is_starred` tinyint(1) NOT NULL DEFAULT '0',
  `last_viewed_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`bookmark_id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`),
  KEY `link_name` (`bookmark_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=55 ;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`bookmark_id`, `user_id`, `bookmark_name`, `bookmark_body`, `parent_id`, `is_shared`, `is_trashed`, `is_archived`, `is_starred`, `last_viewed_at`, `created_at`, `updated_at`) VALUES
(53, 15, 'ksnak', 'http://google.com', -1, 0, 0, 0, 0, '2013-08-14 02:14:59', '2013-08-11 10:40:46', '2013-08-20 00:21:16'),
(54, 15, 'title', 'http://noteblog.local/directory/56.folders', -1, 0, 0, 0, 0, '0000-00-00 00:00:00', '2013-08-19 23:36:58', '2013-08-20 00:04:36');

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE IF NOT EXISTS `folders` (
  `folder_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `folder_name` varchar(75) COLLATE utf8_persian_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `is_shared` tinyint(1) NOT NULL DEFAULT '0',
  `is_trashed` tinyint(1) NOT NULL DEFAULT '0',
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `is_starred` tinyint(1) NOT NULL DEFAULT '0',
  `last_viewed_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`folder_id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`),
  KEY `folder_name` (`folder_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=70 ;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`folder_id`, `user_id`, `folder_name`, `parent_id`, `is_shared`, `is_trashed`, `is_archived`, `is_starred`, `last_viewed_at`, `created_at`, `updated_at`) VALUES
(-1, -1, 'ROOT', -1, 0, 0, -1, 0, '2013-08-09 19:21:42', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 15, 'sksasaAAaA', -1, 1, 0, 0, 0, '2013-08-20 00:28:07', '2013-08-11 04:23:38', '2013-08-20 00:34:40'),
(52, 15, 'sm;lsa', 51, 1, 0, 0, 0, '2013-08-19 23:54:37', '2013-08-11 12:27:55', '2013-08-19 23:54:37'),
(56, 15, 'skanslka', 68, 1, 0, 0, 0, '2013-08-20 00:11:08', '2013-08-14 02:33:08', '2013-08-20 00:11:08'),
(57, 15, 'kash-ka BH', -1, 1, 0, 0, 0, '2013-08-20 00:28:00', '2013-08-14 02:34:04', '2013-08-20 00:28:00'),
(58, 15, 'foo', 51, 1, 0, 1, 0, '2013-08-16 08:27:01', '2013-08-15 03:29:59', '2013-08-20 00:28:38'),
(59, 15, 'dkasn', 65, 1, 0, 0, 0, '2013-08-20 00:28:19', '2013-08-18 13:32:36', '2013-08-20 00:28:19'),
(60, 15, 'kskjankas', 52, 0, 0, 0, 0, '2013-08-19 23:54:38', '2013-08-19 15:56:46', '2013-08-19 23:54:38'),
(65, 15, 'smalsa', 51, 0, 0, 0, 0, '2013-08-20 00:28:08', '2013-08-19 16:23:16', '2013-08-20 00:28:17'),
(66, 15, 'new name', 51, 0, 0, 0, 0, '2013-08-20 00:34:41', '2013-08-19 16:24:58', '2013-08-20 00:34:41'),
(67, 15, 'sklasa', 59, 0, 0, 1, 0, '2013-08-20 00:28:12', '2013-08-19 16:35:39', '2013-08-20 00:28:17'),
(68, 15, 'create folder_2', 66, 0, 0, 0, 0, '2013-08-20 00:34:33', '2013-08-19 23:21:50', '2013-08-20 00:34:40'),
(69, 15, 'foo', -1, 0, 0, 0, 0, '0000-00-00 00:00:00', '2013-11-19 12:20:03', '2013-11-19 12:20:03');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `note_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `note_name` varchar(150) COLLATE utf8_persian_ci NOT NULL,
  `note_body` text COLLATE utf8_persian_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `is_shared` tinyint(1) NOT NULL DEFAULT '0',
  `is_trashed` tinyint(1) NOT NULL DEFAULT '0',
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `is_starred` tinyint(1) NOT NULL DEFAULT '0',
  `last_viewed_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`note_id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`),
  KEY `note_name` (`note_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=66 ;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`note_id`, `user_id`, `note_name`, `note_body`, `parent_id`, `is_shared`, `is_trashed`, `is_archived`, `is_starred`, `last_viewed_at`, `created_at`, `updated_at`) VALUES
(53, 15, 'I need to take studing seriously!', 'damn i am right!<br>', -1, 0, 0, 1, 0, '2013-08-10 09:06:53', '2013-08-10 08:28:38', '2013-08-20 00:34:04'),
(54, 15, '.smsa', '<span style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; display: inline !important; float: none;">Referenced variables in<span class="Apple-converted-space"> </span></span><i style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-size: medium; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><tt class="parameter">param_arr</tt></i><span style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; display: inline !important; float: none;"><span class="Apple-converted-space"> </span>are passed to the function by reference, regardless of whether the function expects the respective parameter to be passed by reference. This form of call-time pass by reference does not emit a deprecation notice, but it is nonetheless deprecated, and will most likely be removed in the next version of PHP. Furthermore, this does not apply to internal functions, for which the function signature is honored. Passing by value when the function expects a parameter by reference results in a warning and having<span class="Apple-converted-space"> </span></span><span class="function" style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><a href="function.call-user-func.html" class="function">call_user_func()</a></span><span style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; display: inline !important; float: none;">return<span class="Apple-converted-space"> </span></span><b style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-size: medium; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px;"><tt>FALSE</tt></b><span style="color: rgb(0, 0, 0); font-family: ''Times New Roman''; font-size: medium; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; display: inline !important; float: none;"><span class="Apple-converted-space"> </span>(does not apply if the passed value has a reference count = 1).</span>\r\n                            ', -1, 1, 0, 1, 0, '2013-08-14 05:02:15', '2013-08-11 04:51:44', '2013-08-20 00:34:04'),
(55, 15, 'lsm;''sma', '', -1, 1, 0, 1, 0, '2013-08-11 04:51:55', '2013-08-11 04:51:55', '2013-08-20 00:34:04'),
(56, 15, 'ksmnkalmsa', '', 52, 1, 0, 1, 0, '2013-08-11 12:28:04', '2013-08-11 12:28:03', '2013-08-16 09:07:07'),
(57, 15, 'Note', 'knaksa\r\n                            ', 51, 1, 0, 1, 0, '0000-00-00 00:00:00', '2013-08-14 02:10:04', '2013-08-16 09:07:07'),
(58, 15, ';lsmal;', 'klnslkan\r\n                            ', 51, 0, 0, 1, 0, '2013-08-15 03:30:46', '2013-08-14 02:10:54', '2013-08-20 00:34:04'),
(59, 15, 'lsa;lm', 'kmlsakmasl\r\n                            ', 51, 1, 0, 1, 0, '0000-00-00 00:00:00', '2013-08-14 02:13:45', '2013-08-16 09:07:07'),
(61, 15, 'lksalk', 'lknlknsa\r\n                            ', 52, 1, 0, 1, 0, '0000-00-00 00:00:00', '2013-08-14 02:17:04', '2013-08-16 09:07:07'),
(62, 15, 'ksmnakl', 'klnalksna\r\n                            ', 52, 1, 0, 1, 0, '0000-00-00 00:00:00', '2013-08-14 02:18:47', '2013-08-16 09:07:07'),
(63, 15, 'slaml;asm', 'xlamla;m\r\n                            ', 51, 1, 0, 1, 0, '0000-00-00 00:00:00', '2013-08-14 02:19:55', '2013-08-16 09:07:07'),
(64, 15, 'slkanlkan', '\r\n                \r\n                \r\n                lknsaklnsa <b>salsam            </b>            ', -1, 0, 0, 0, 0, '2013-08-20 00:20:17', '2013-08-20 00:15:04', '2013-08-20 00:20:30'),
(65, 15, 'slkmaslskma', '\r\n                knl<i><b>knax\r\n                                        </b></i>', 57, 0, 0, 0, 0, '2013-08-20 00:20:50', '2013-08-20 00:20:41', '2013-08-20 00:21:01');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `user_id` int(11) NOT NULL,
  `settings` text COLLATE utf8_persian_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`user_id`, `settings`, `created_at`, `updated_at`) VALUES
(15, 'O:8:"stdClass":1:{s:14:"has_blog_named";i:1;}', '0000-00-00 00:00:00', '2014-08-17 03:21:00');

-- --------------------------------------------------------

--
-- Table structure for table `userprofiles`
--

CREATE TABLE IF NOT EXISTS `userprofiles` (
  `userprofile_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `first_name` varchar(70) COLLATE utf8_persian_ci NOT NULL,
  `last_name` varchar(70) COLLATE utf8_persian_ci NOT NULL,
  `user_bio` varchar(400) COLLATE utf8_persian_ci DEFAULT NULL,
  `nick_name` varchar(255) COLLATE utf8_persian_ci DEFAULT NULL,
  `birth_year` year(4) DEFAULT NULL,
  `birth_month` int(11) DEFAULT NULL,
  `birth_day` int(11) DEFAULT NULL,
  `is_male` tinyint(1) DEFAULT NULL,
  `intro` varchar(400) COLLATE utf8_persian_ci DEFAULT NULL,
  `occu` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL,
  `edu` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_persian_ci DEFAULT NULL,
  `public_email` varchar(200) COLLATE utf8_persian_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_persian_ci DEFAULT NULL,
  `site` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL,
  `options` text COLLATE utf8_persian_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`userprofile_id`),
  UNIQUE KEY `user_id_unique` (`user_id`),
  UNIQUE KEY `public_email` (`public_email`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=28 ;

--
-- Dumping data for table `userprofiles`
--

INSERT INTO `userprofiles` (`userprofile_id`, `user_id`, `first_name`, `last_name`, `user_bio`, `nick_name`, `birth_year`, `birth_month`, `birth_day`, `is_male`, `intro`, `occu`, `edu`, `country`, `city`, `public_email`, `phone`, `site`, `options`, `created_at`, `updated_at`) VALUES
(27, 15, 'Dariush', 'Hasanpoor', NULL, 'BadGuy', 1991, 10, 5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2013-08-10 21:22:47', '2013-08-10 21:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(-1, 'ROOT', 'ROOT', '2013-08-06 00:00:00', '2013-08-06 00:00:00'),
(15, 'b.g.dariush@gmail.com', 'b434c1d19a918774224a2957a884bfd3', '2013-05-29 01:03:40', '2013-08-17 21:57:55');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attaches`
--
ALTER TABLE `attaches`
  ADD CONSTRAINT `attaches_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attaches_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `folders` (`folder_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookmarks_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `folders` (`folder_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `folders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `folders_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `folders` (`folder_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `folders` (`folder_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userprofiles`
--
ALTER TABLE `userprofiles`
  ADD CONSTRAINT `userprofiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
