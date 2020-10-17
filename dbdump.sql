-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2020 at 04:01 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chaters`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_id` int(11) NOT NULL,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `seen1` tinyint(1) NOT NULL DEFAULT 0,
  `seen2` tinyint(1) NOT NULL DEFAULT 0,
  `last_message` timestamp NOT NULL DEFAULT current_timestamp(),
  `loaded` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`chat_id`, `user1`, `user2`, `seen1`, `seen2`, `last_message`, `loaded`) VALUES
(1, 4, 5, 0, 0, '2020-10-16 10:22:14', 1),
(2, 4, 10, 0, 0, '2020-10-16 12:20:53', 1),
(3, 3, 4, 0, 1, '2020-10-16 12:43:06', 1),
(4, 3, 10, 0, 0, '2020-10-17 12:22:23', 1),
(5, 5, 3, 0, 0, '2020-10-17 12:31:56', 1),
(6, 3, 6, 0, 0, '2020-10-17 12:37:15', 1),
(7, 10, 5, 0, 0, '2020-10-17 12:49:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `seen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `chat_id`, `sender_id`, `message`, `time`, `seen`) VALUES
(1, 1, 4, '2qh8YfG575BE8susER7bkFhDH01sn8pkwekXxfS+kRFCN3NaYuM03YQE2h9T/aPuP0vdvo8Pow==', '2020-10-17 09:01:04', 1),
(2, 2, 4, 'R2DRROOc/ML0Q0VYyb7vARUDeumhSNe3kGTPm3+6kpzLvh9GCOJUdEZ6XOKEbDKuLh1eMifckA==', '2020-10-17 09:01:10', 1),
(3, 2, 10, 'r+rUiPuhFYed4kpKGqfecIVzd2r+7kO+doPuEPuX3c/mEOLK5bEDssqNWuuEXUr717RuBw==', '2020-10-17 09:01:14', 1),
(4, 2, 10, 'r1A6zhWIM9NzdRD51V05UeGelMJihS2HykWYgoWjD7YWi5fE0aJO6OXLX3RdfxMjQh6el/ZGxQc+Tgk=', '2020-10-17 09:01:18', 1),
(5, 2, 10, '0i5yEzMGoxxi0FHTP2/yuqLOcG6dS3tgLb/lWY1ZAi2Z3X/r/OnwOqhLCloUXbqe', '2020-10-17 09:01:22', 1),
(6, 2, 10, '8BFmt21+ruyy2dVcwhEDkmb9V3oEULk4LvUOvggrBWxaxv9GW5akLohU', '2020-10-17 09:01:27', 1),
(7, 2, 4, 'Z7XUDxYCEgjcl4qNps952TKEjqEOLn6miuJZo2fDNTsrLG+OtosELzhe6QcWeJHwn85HhAaC4RTlh2U=', '2020-10-17 09:01:33', 1),
(8, 3, 3, 'cCb1YJE97sjy3R0rTtdhvEWOC460jafh86UKgKSqrKJ3YdmTuox3O6sUiYVp9g==', '2020-10-17 11:33:15', 1),
(9, 4, 3, 'ZXC1tLxuxspA0uJBlRvR2I0cv/8fIlF1pmlgtqtWjct7HJI4kqPx/x4/Wuaq', '2020-10-17 11:12:51', 1),
(10, 4, 10, 'EfXCru+CEgDfHuQBgEvipEoC2Bipp/NG0+zMhMjjQcxOSkydmxonDrRVbBE0', '2020-10-17 11:18:34', 1),
(11, 4, 10, 'bPTzyv2z5Q+/e+MJeOv31tq/pC4fP55yA4A2nd0yhkRZJ8heVzKSXBhJcHwAv/EcyHyI', '2020-10-17 11:31:44', 1),
(12, 4, 3, 'Br0tYcUXzpRShCqPoDAfY8BYtrVlpeLRE0KBjSwi3UQ03jmAuClEZWTSGIcslRKePQ==', '2020-10-17 11:32:06', 1),
(13, 4, 10, '6u9mk9OsaB+MCe9oo3ACKhWzqe+cKnPmbE8Z9ZWxHY6aajYJ8TV96AzwqWGM0ntllYaEnZge', '2020-10-17 11:37:19', 1),
(14, 4, 3, '8DFNiz2vfukaKajN6Ing4lGuFkZDppV+P74xwBAR2dirwlMfL/9GzA08qBNsVQsFUNC3JyCIO5T3Ksl7uM9z8aNbIOZiSYh9Qg==', '2020-10-17 11:50:34', 1),
(15, 4, 10, 'Fjy+MgnV/W9q1yOOR/3nAkMZ5PVws+6eTu6CirmVAr+7p4KfP4BMDhMY+g==', '2020-10-17 11:53:41', 1),
(16, 4, 3, 'N230GekHNh5AkoQ2PQxpyJOi9CdfKqiViD5mlwlTfrmbrLSA8ZLOBH/gNRUv', '2020-10-17 11:54:59', 1),
(17, 4, 10, '8g7OR6SjO5Ol7HspllY8XWbA1fLSakNT4QhvhvLMkNHQFUkdHF1QgmovewnoYc5olg==', '2020-10-17 11:58:51', 1),
(18, 4, 3, 'ZNroV+ntHPpvg9uB/kNsWUn0KYPxYBF2L3ObIOeRw1jONK3J2WDj7NTDF6ldnJ9iqVaiekdDzvO+owJsEGo=', '2020-10-17 12:22:24', 1),
(19, 5, 5, 'gx+uymz3nSwCYYYF5ZwkLNxkofOUj+e7zkkC4ObyPA0OuXxPg6adzxgLqjMK', '2020-10-17 12:31:44', 1),
(20, 5, 5, 'pz+TcnJfXE+d1jdaWmKWNKhQmRdljb4z/vgRmMSlA5OCTy/ZMT5teI+3l5lj0eFsFrKfZdi5', '2020-10-17 12:31:44', 1),
(21, 5, 3, 'vlnDe53mTdzznZx/w5h7Mjg1wtTlOJj6PfVUiVjTiUwoaYqfwudbCxmOLl+V4YEGc/7DcGM=', '2020-10-17 12:31:57', 1),
(22, 6, 3, 'E1zDWXd2bg6rLU1U6m40aObHTVkISjJ9DQ4s8I0+01CVRlZoJbt+V/a3sYkb', '2020-10-17 12:37:28', 1),
(23, 7, 10, 'AitS6esDpkQ7cOJrsZw7wt8NnmsVQybPuvh9Q95f7PGCdnmVSeXaB/d7C8kk', '2020-10-17 12:52:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `Q_id` int(2) NOT NULL,
  `question` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`Q_id`, `question`) VALUES
(1, 'What was the last name of your third-grade teacher?'),
(2, 'What was the name of your second pet?'),
(3, 'what was the name of third best friend?'),
(4, 'Where were you on New Year’s Eve in 2015?');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `Q_id` int(2) NOT NULL,
  `Q_answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `Q_id`, `Q_answer`) VALUES
(3, 'mahmoud', 'ma1270056@gmail.com', '$2y$10$uILHPQS142o2zoDkwSvgE.4Yy1HBvvsQlKcdMrrngQ1gP3PtoVMpK', 4, '$2y$10$P6BWC9FM/PQTbcBwsvbro.VjZHMAKY5ADnHgKI6cbHMO.ErQdUT62'),
(4, 'mohamed', 'ma12700056@gmail.com', '$2y$10$XDRdPzkXnda59eAkzBYmDeeE2UAbWC3zBWWPugBJKDruiMfMoGkJ6', 4, '$2y$10$d0ox28qZIOVISEaN5uXCpOUSq8u8PEUrsi7HU350TFDOxO22rgE3K'),
(5, 'simulation', 'mohamedabdullah1270056@gmail.com', '$2y$10$TkP5v3L5lhL7Nc4bQqflsuB3lL.wN0R.lxLjT/9BS/rIVsUL7zj6S', 4, '$2y$10$YXl064qwT7zi6b9aXeJL3epd8r9lQ6fy7qQyD68tTldw3WLkgUe86'),
(6, 'khaled', 'khaled946@gmail.com', '$2y$10$PzvhQnQ.OrUpyEhkc4THROcBktSkMNBwX6C0/nwqBfmIXCEGz0r.u', 4, '$2y$10$68fbMWtl2nlvG0P8yiP8uesI1sVsfyiUQWRXmAHz4SR/LZABkT7Mq'),
(10, 'khaled mosad', 'kadkdf@yahoo.com', '$2y$10$4IPO4Et5a/M2rz/2kYFkr.vRJecMg5DIuYOUF160Ysg0U.LrSCeba', 1, '$2y$10$W.lS27IGuXDVyiFNsyrzy.BcnBqQaKN5bvdYJsqoAVyHveXw4vRJ6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `user1Key` (`user1`),
  ADD KEY `user2Key` (`user2`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `chatKey` (`chat_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`Q_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`) USING HASH,
  ADD KEY `question` (`Q_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `Q_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `user1Key` FOREIGN KEY (`user1`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user2Key` FOREIGN KEY (`user2`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `chatKey` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`chat_id`),
  ADD CONSTRAINT `sender_id` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `question` FOREIGN KEY (`Q_id`) REFERENCES `questions` (`Q_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
