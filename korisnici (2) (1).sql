-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 29, 2025 at 05:00 PM
-- Server version: 5.7.36
-- PHP Version: 8.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `korisnici`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(2048) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `like_count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `post_id`, `content`, `image`, `created_at`, `updated_at`, `status`, `reference`, `like_count`) VALUES
(3, 14, 31, 'da', NULL, '2025-01-28 23:28:06', '2025-01-28 23:28:06', NULL, NULL, 0),
(4, 14, 22, 'te', NULL, '2025-01-28 23:30:50', '2025-01-28 23:30:50', NULL, NULL, 1),
(5, 14, 22, 'test', NULL, '2025-01-28 23:34:59', '2025-01-28 23:34:59', NULL, NULL, 1),
(6, 14, 22, 'tetetetete', NULL, '2025-01-28 23:49:39', '2025-01-28 23:49:39', NULL, NULL, 1),
(7, 14, 36, 'maker', NULL, '2025-01-28 23:57:20', '2025-01-28 23:57:20', NULL, NULL, 0),
(8, 14, 38, 'da', NULL, '2025-01-29 00:29:13', '2025-01-29 00:29:13', NULL, NULL, 1),
(9, 14, 41, 'Ð´Ð°Ð´Ð°', NULL, '2025-01-29 15:56:04', '2025-01-29 15:56:04', NULL, NULL, 1),
(10, 15, 46, 'dede', NULL, '2025-01-29 16:40:22', '2025-01-29 16:40:22', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes`
--

CREATE TABLE `comment_likes` (
  `like_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment_likes`
--

INSERT INTO `comment_likes` (`like_id`, `comment_id`, `user_id`) VALUES
(15, 6, 14),
(16, 8, 14),
(19, 9, 14),
(20, 4, 14),
(21, 10, 15),
(22, 5, 14);

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `status` enum('pending','accepted') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `user_id`, `friend_id`, `status`) VALUES
(1, 14, 12, 'pending'),
(2, 14, 15, 'pending'),
(3, 15, 14, 'pending'),
(4, 15, 13, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `member_count` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `priority` tinyint(1) DEFAULT NULL,
  `privacy` tinyint(1) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `user_id`, `name`, `description`, `member_count`, `created_at`, `updated_at`, `status`, `priority`, `privacy`, `profile_picture`) VALUES
(7, 12, 'Andrej Group', 'Andrej grupa', NULL, '2025-01-28 18:31:06', '2025-01-28 18:31:06', 'active', NULL, 0, 'uploads/profile.jpg'),
(8, 14, 'KEke', 'yryryryr', NULL, '2025-01-28 23:52:32', '2025-01-28 23:52:32', 'active', NULL, 0, 'uploads/david.png');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `group_member_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`group_member_id`, `group_id`, `user_id`, `joined_at`) VALUES
(7, 7, 14, '2025-01-28 23:52:15'),
(8, 7, 14, '2025-01-28 23:52:17'),
(9, 8, 14, '2025-01-28 23:52:35'),
(10, 8, 14, '2025-01-29 17:35:52'),
(11, 7, 14, '2025-01-29 17:38:00'),
(12, 7, 14, '2025-01-29 17:38:04'),
(13, 8, 14, '2025-01-29 17:52:14');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `post_id`, `user_id`, `updated_at`, `status`, `type`) VALUES
(26, 22, 14, NULL, NULL, NULL),
(29, 28, 14, NULL, NULL, NULL),
(30, 27, 14, NULL, NULL, NULL),
(31, 26, 14, NULL, NULL, NULL),
(32, 40, 14, NULL, NULL, NULL),
(33, 25, 14, NULL, NULL, NULL),
(46, 45, 14, NULL, NULL, NULL),
(47, 23, 14, NULL, NULL, NULL),
(48, 23, 15, NULL, NULL, NULL),
(49, 45, 15, NULL, NULL, NULL),
(50, 24, 15, NULL, NULL, NULL),
(51, 40, 15, NULL, NULL, NULL),
(52, 46, 15, NULL, NULL, NULL),
(53, 47, 15, NULL, NULL, NULL),
(54, 47, 14, NULL, NULL, NULL),
(55, 48, 14, NULL, NULL, NULL),
(56, 49, 14, NULL, NULL, NULL),
(57, 50, 14, NULL, NULL, NULL),
(58, 51, 14, NULL, NULL, NULL),
(59, 52, 14, NULL, NULL, NULL),
(60, 53, 14, NULL, NULL, NULL),
(61, 54, 14, NULL, NULL, NULL),
(62, 55, 14, NULL, NULL, NULL),
(63, 56, 14, NULL, NULL, NULL),
(64, 57, 14, NULL, NULL, NULL),
(65, 59, 14, NULL, NULL, NULL),
(66, 60, 14, NULL, NULL, NULL),
(67, 58, 14, NULL, NULL, NULL),
(68, 62, 14, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `recipient_id`, `content`, `created_at`) VALUES
(40, 14, 12, 'test', '2025-01-28 23:50:15');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `content` text,
  `type` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `priority` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `post_id`, `tag_id`, `content`, `type`, `created_at`, `updated_at`, `status`, `priority`) VALUES
(1, 12, 28, NULL, 'David Gerasimov liked your post.', 'post_liked', '2025-01-29 00:32:19', NULL, 'unread', NULL),
(2, 12, 27, NULL, 'David Gerasimov liked your post.', 'post_liked', '2025-01-29 00:32:34', NULL, 'unread', NULL),
(3, 12, 26, NULL, 'David Gerasimov liked your post.', 'post_liked', '2025-01-29 00:32:36', NULL, 'unread', NULL),
(4, 12, 25, NULL, 'David Gerasimov liked your post.', 'post_liked', '2025-01-29 00:32:55', NULL, 'unread', NULL),
(5, 12, 24, NULL, 'Your post has been liked.', 'post_liked', '2025-01-29 00:33:05', NULL, 'unread', NULL),
(6, 12, NULL, NULL, 'User 15 liked your post.', NULL, NULL, NULL, 'unread', NULL),
(7, 14, NULL, NULL, 'User 15 liked your post.', NULL, NULL, NULL, 'read', NULL),
(8, 15, NULL, NULL, 'User 15 liked your post.', NULL, NULL, NULL, 'read', NULL),
(9, 15, NULL, NULL, 'User 15 liked your comment.', NULL, NULL, NULL, 'unread', NULL),
(10, 15, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'unread', NULL),
(11, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'read', NULL),
(12, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'read', NULL),
(13, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'read', NULL),
(14, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'read', NULL),
(15, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'read', NULL),
(16, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'read', NULL),
(17, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'read', NULL),
(18, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'read', NULL),
(19, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'unread', NULL),
(20, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'unread', NULL),
(21, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'unread', NULL),
(22, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'unread', NULL),
(23, 14, NULL, NULL, 'User 14 liked your post.', NULL, NULL, NULL, 'unread', NULL),
(24, 12, NULL, NULL, 'User 14 removed you as a friend.', NULL, NULL, NULL, 'unread', NULL),
(25, 12, NULL, NULL, 'User 14 added you as a friend.', NULL, NULL, NULL, 'unread', NULL),
(26, 15, NULL, NULL, 'User 14 added you as a friend.', NULL, NULL, NULL, 'unread', NULL),
(27, 14, NULL, NULL, 'User 15 added you as a friend.', NULL, NULL, NULL, 'unread', NULL),
(28, 13, NULL, NULL, 'User 15 added you as a friend.', NULL, NULL, NULL, 'unread', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `content` text,
  `reference` varchar(2048) DEFAULT NULL,
  `image` varchar(2048) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `like_count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `group_id`, `content`, `reference`, `image`, `status`, `created_at`, `like_count`) VALUES
(22, 12, NULL, 'asd', '', '', 'active', '2025-01-28', 1),
(23, 12, NULL, 'asd', '', '', 'active', '2025-01-28', 2),
(24, 12, NULL, 'asd', '', '', 'active', '2025-01-28', 1),
(25, 12, NULL, 'asd', '', '', 'active', '2025-01-28', 1),
(26, 12, NULL, 'asd', '', '', 'active', '2025-01-28', 1),
(27, 12, NULL, '', '', '', 'active', '2025-01-28', 1),
(28, 12, NULL, 'dsa', '', '', 'active', '2025-01-28', 1),
(40, 14, NULL, 'te', '', '', 'active', '2025-01-28', 2),
(45, 14, NULL, 'Ovoa e novio post za da se testira', '', '', 'active', '2025-01-29', 2),
(46, 15, NULL, 'gegege', '', '', 'active', '2025-01-29', 1),
(47, 14, NULL, 'KKKKKKKKKKK', '', '', 'active', '2025-01-29', 2),
(48, 15, NULL, 'tetetetetetetetet', '', '', 'active', '2025-01-29', 1),
(49, 14, NULL, 'tetetetete', '', '', 'active', '2025-01-29', 1),
(50, 14, NULL, 'gegegegegegegetretetete', '', '', 'active', '2025-01-29', 1),
(51, 14, NULL, 'gegeg', '', '', 'active', '2025-01-29', 1),
(52, 14, NULL, 'tumut', '', '', 'active', '2025-01-29', 1),
(53, 14, NULL, 'bono  gleas li go voa', '', '', 'active', '2025-01-29', 1),
(54, 14, NULL, 'nnnnnn', '', '', 'active', '2025-01-29', 1),
(55, 14, NULL, 'a', '', '', 'active', '2025-01-29', 1),
(56, 14, NULL, 'gy', '', '', 'active', '2025-01-29', 1),
(57, 14, NULL, 'he', '', '', 'active', '2025-01-29', 1),
(58, 14, NULL, 'gegegege', '', '', 'active', '2025-01-29', 1),
(59, 14, NULL, 'te', '', '', 'active', '2025-01-29', 1),
(60, 14, NULL, 'gegegegetetetetetetwqweq', '', '', 'active', '2025-01-29', 1),
(61, 14, NULL, 'gegegegegererere', '', '', 'active', '2025-01-29', 0),
(62, 14, NULL, 'nenenenenenen', '', '', 'active', '2025-01-29', 1),
(63, 14, NULL, 'hehehe', '', '', 'active', '2025-01-29', 0),
(64, 14, 8, 'ge', NULL, NULL, NULL, '2025-01-29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `birthdate`, `created_at`, `pp`) VALUES
(12, 'Andrej', 'Georgiev', 'andrejgeorgiev@gmail.com', '$2y$10$rpslpOzN3UuT/JHGj5/Im./5EtI/1CS3Migj4geEuAijljmetV8Ve', '2003-08-26', '2025-01-28 16:30:41', 'uploads/andrej.jpg'),
(13, 'asd', 'asd', 'asd@gmail.com', '$2y$10$RivUh7Jnn0gTHZdH0vswp.m0wjgdVcjX1.E5HD58pXkepgI6oGJ2S', '1000-10-10', '2025-01-28 16:48:53', NULL),
(14, 'David', 'Gerasimov', 'thisemaildoesntexist@thismail.com', '$2y$10$7p2FFLMN1W1OJYhp/lCUEee/KArhI9CdKUAqaVA3OY7knVllGuEd6', '2003-10-14', '2025-01-28 22:16:05', 'uploads/david.png'),
(15, 'Bono', 'Bogdanski', 'bonobono@bono', '$2y$10$gxqwfbzqXQHk4uqwRS/dH.KmurHG4hKng6d4hntNd8ncTY6kwu6wG', '2000-10-20', '2025-01-29 15:02:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`group_member_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user` (`user_id`),
  ADD KEY `posts_ibfk_2` (`group_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `group_member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `comment_likes_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
