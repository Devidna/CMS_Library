-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2023 at 05:59 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `detik_cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `pdf_file_path` varchar(255) NOT NULL,
  `cover_image_path` varchar(255) NOT NULL,
  `Create_time` datetime DEFAULT current_timestamp(),
  `Update_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `category_id`, `description`, `quantity`, `pdf_file_path`, `cover_image_path`, `Create_time`, `Update_time`, `user_id`) VALUES
(1, 'Pemrograman Web PHP Dasar', 1, 'Pemrograman Web PHP Dasar Database MySQLI dengan Bootstrap ditulis oleh Agustinus Budi Santoso, S.ST., M.Cs oleh Penerbit Widina', 14, 'uploads/64c644c9d846e.pdf', 'uploads/64c644c9d899e.png', '2023-07-30 18:08:57', '2023-07-31 08:03:39', 2),
(2, 'Belajar HTML & CSS Dasar', 2, 'Belajar HTML & CSS Dasar ini disusun oleh Diki Alfarabi Hadi dan berasal dari www.malas.ngoding.com	', 14, 'uploads/64c657012c29e.pdf', 'uploads/64c657012c76a.png', '2023-07-30 19:26:41', '2023-07-31 08:16:38', 2),
(3, 'Mudah Menguasai PHP & MySQL', 1, 'Mudah Menguasai PHP & MySQL dalam 24 jam ini ditulis oleh Risawandi oleh Penerbit Unimal Press', 14, 'uploads/64c65762479e4.pdf', 'uploads/64c6576247c6d.png', '2023-07-30 19:28:18', '2023-07-30 20:19:12', 3),
(4, 'Pemrograman Mobile Berbasis Android', 4, 'Pemrograman Mobile Berbasis Android ini ditulis oleh Iwan Ady Prabowo, M.Kom, Hendro Wijayanto, M.Kom, Bramasto Wiryawan Yudanto, M.M.S.I, dan Sapto Nugroho, S.T', 14, 'uploads/64c707a2d0a0a.pdf', 'uploads/64c707a2d14dd.png', '2023-07-31 08:00:18', '2023-07-31 08:04:47', 3),
(5, 'Python ® Programming for the Absolute Beginner, Third Edition', 5, 'Python ® Programming for the Absolute Beginner, Third Edition ini berbahasa inggris dan ditulis oleh Michael Dawson sebagai Course Technology, a part of Cengage Learning di Amerika Serikat', 14, 'uploads/64c707d4d6c99.pdf', 'uploads/64c707d4d70b3.png', '2023-07-31 08:01:08', '2023-07-31 08:12:34', 3),
(6, 'Pemrograman Web dengan PHP dan MySQL', 1, 'Pemrograman Web dengan PHP dan MySQL ini ditulis oleh Achmad Solichin, S.Kom dari Universitas Budi Luhur Jakarta.', 14, 'uploads/64c7090990dfa.pdf', 'uploads/64c70909913ba.png', '2023-07-31 08:06:17', '2023-07-31 09:02:20', 4),
(7, 'Mudah membuat Web bagi Pemula: Pemrograman Web I', 2, 'Mudah membuat Web bagi Pemula: Pemrograman Web I ini ditulis oleh Moh Muthohir, S.Kom., M.Kom dan berasal dari Yayasan Prima Agus Teknik', 14, 'uploads/64c7092f23a16.pdf', 'uploads/64c7092f23d3b.png', '2023-07-31 08:06:55', '2023-07-31 08:14:08', 4),
(8, 'Java for Absolute Beginners', 4, 'Java for Absolute Beginners: Learn to Program the Fundamentals the Java 9+ Way ini ditulis oleh Uliana Cosmina dari Edinburgh University United Kingdom', 14, 'uploads/64c7098aac20b.pdf', 'uploads/64c7098aac542.png', '2023-07-31 08:08:26', '2023-07-31 08:15:26', 5),
(9, 'Beginning Oracle SQL', 3, 'ini ditulis oleh Lex de Haan di Belanda', 14, 'uploads/64c709b02966c.pdf', 'uploads/64c709b029dbb.png', '2023-07-31 08:09:04', '2023-07-31 08:16:25', 5),
(10, 'Modul Pembelajaran Praktek Basis Data', 3, 'Modul Pembelajaran Praktek Basis Data ini di edit oleh Haris Saputro', 14, 'uploads/64c709d872874.pdf', 'uploads/64c709d872dfa.png', '2023-07-31 08:09:44', '2023-07-31 08:15:55', 4),
(11, 'Data Structures and Algorithm Analysis in Java', 4, 'Data Structures and Algorithm Analysis in Java ini menggunakan bahasa Inggris dan ditulis oleh Mark Allen Weiss dari Florida International University', 14, 'uploads/64c70a0a77341.pdf', 'uploads/64c70a0a77b61.png', '2023-07-31 08:10:34', '2023-07-31 08:15:00', 5);

-- --------------------------------------------------------

--
-- Table structure for table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `borrowings`
--

INSERT INTO `borrowings` (`id`, `user_id`, `book_id`, `borrow_date`, `return_date`) VALUES
(1, 2, 3, '2023-07-30', '2023-08-02'),
(2, 4, 1, '2023-07-31', '2023-08-05'),
(3, 4, 4, '2023-07-31', '2023-08-02'),
(4, 5, 5, '2023-07-31', '2023-08-04'),
(5, 5, 7, '2023-07-31', '2023-08-03'),
(6, 4, 11, '2023-07-31', '2023-08-04'),
(7, 2, 8, '2023-07-31', '2023-08-03'),
(8, 2, 10, '2023-07-31', '2023-08-02'),
(9, 3, 9, '2023-07-31', '2023-08-03'),
(10, 3, 2, '2023-07-31', '2023-08-05'),
(11, 3, 6, '2023-08-01', '2023-08-05');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'PHP'),
(2, 'HTML & CSS'),
(3, 'SQL'),
(4, 'Java'),
(5, 'Python');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$Xer7WToJg.VO4hGJi9SVX.3/8vaPjq05uG1ESHfaGYuNFvcZZX8XK', 'admin'),
(2, 'devi', '$2y$10$YuhFME6k9.bNXvpSOPBJZ.P3/1JM5Bs7O6hEAWrpII3MurPTQ1t/C', 'user'),
(3, 'nisa', '$2y$10$jAmBlqk96JzG1I8QoXXhjOic.9F6ZJPFryG6FjM3uYr5llqKmEzba', 'user'),
(4, 'rio', '$2y$10$uk1rAVgjhSAaHZBorrgH/uXMW0a8TXRXGW.eiSMSI.B9CqPovw.tO', 'user'),
(5, 'agung', '$2y$10$ZRMkoJEZdgCVLLq0gwaGh.A.fq9dGWOZPzN7Q2GLypU0Z1Bwln3sy', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowings_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
