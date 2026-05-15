-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2026 at 09:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lv4_filmovi`
--

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `year` int(11) NOT NULL,
  `genre` varchar(50) NOT NULL,
  `duration` int(11) NOT NULL,
  `country` varchar(80) NOT NULL,
  `rating` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `year`, `genre`, `duration`, `country`, `rating`) VALUES
(1, 'The Shawshank Redemption', 1994, 'Drama', 142, 'USA', 9.3),
(2, 'The Godfather', 1972, 'Crime, Drama', 175, 'USA', 9.2),
(3, 'The Dark Knight', 2008, 'Action, Crime', 152, 'UK/USA', 9.0),
(4, 'Schindler\'s List', 1993, 'Biography, Drama', 195, 'USA', 9.0),
(5, '12 Angry Men', 1957, 'Crime, Drama', 96, 'USA', 9.0),
(6, 'Pulp Fiction', 1994, 'Crime, Drama', 154, 'USA', 8.9),
(7, 'The Lord of the Rings: The Return of the King', 2003, 'Action, Adventure', 201, 'NZ/USA', 9.0),
(8, 'Il Buono, il Brutto, il Cattivo', 1966, 'Western', 161, 'Italy', 8.8),
(9, 'Fight Club', 1999, 'Drama', 139, 'USA', 8.8),
(10, 'Inception', 2010, 'Action, Adventure', 148, 'USA/UK', 8.8),
(11, 'The Matrix', 1999, 'Action, Sci-Fi', 136, 'USA', 8.7),
(12, 'Goodfellas', 1990, 'Biography, Crime', 145, 'USA', 8.7),
(13, 'One Flew Over the Cuckoo\'s Nest', 1975, 'Drama', 133, 'USA', 8.7),
(14, 'Seven Samurai', 1954, 'Action, Drama', 207, 'Japan', 8.6),
(15, 'Se7en', 1995, 'Crime, Drama', 127, 'USA', 8.6),
(16, 'The Silence of the Lambs', 1991, 'Crime, Drama', 118, 'USA', 8.6),
(17, 'City of God', 2002, 'Crime, Drama', 130, 'Brazil', 8.6),
(18, 'Life Is Beautiful', 1997, 'Comedy, Drama', 116, 'Italy', 8.6),
(19, 'Interstellar', 2014, 'Adventure, Drama', 169, 'USA/UK', 8.7),
(20, 'Saving Private Ryan', 1998, 'Drama, War', 169, 'USA', 8.6),
(21, 'Parasite', 2019, 'Drama, Thriller', 132, 'South Korea', 8.5),
(22, 'The Green Mile', 1999, 'Crime, Drama', 189, 'USA', 8.6),
(23, 'Star Wars: Episode IV - A New Hope', 1977, 'Action, Adventure', 121, 'USA', 8.6),
(24, 'Terminator 2: Judgment Day', 1991, 'Action, Sci-Fi', 137, 'USA', 8.6),
(25, 'Back to the Future', 1985, 'Adventure, Comedy', 116, 'USA', 8.5),
(26, 'The Pianist', 2002, 'Biography, Drama', 150, 'France/Poland', 8.5),
(27, 'Psycho', 1960, 'Horror, Mystery', 109, 'USA', 8.5),
(28, 'Gladiator', 2000, 'Action, Adventure', 155, 'USA/UK', 8.5),
(29, 'The Lion King', 1994, 'Animation, Adventure', 88, 'USA', 8.5),
(30, 'The Departed', 2006, 'Crime, Drama', 151, 'USA', 8.5);

-- --------------------------------------------------------

--
-- Table structure for table `ocjene_slika`
--

CREATE TABLE `ocjene_slika` (
  `id` int(11) NOT NULL,
  `id_korisnik` int(11) NOT NULL,
  `id_slika` int(11) NOT NULL,
  `ocjena` int(11) NOT NULL CHECK (`ocjena` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ocjene_slika`
--

INSERT INTO `ocjene_slika` (`id`, `id_korisnik`, `id_slika`, `ocjena`, `created_at`) VALUES
(1, 1, 1, 4, '2026-05-14 13:30:54');

-- --------------------------------------------------------

--
-- Table structure for table `slike`
--

CREATE TABLE `slike` (
  `id` int(11) NOT NULL,
  `naziv_datoteke` varchar(255) NOT NULL,
  `opis` varchar(255) DEFAULT NULL,
  `putanja` varchar(255) NOT NULL,
  `izvor` varchar(50) DEFAULT 'api'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slike`
--

INSERT INTO `slike` (`id`, `naziv_datoteke`, `opis`, `putanja`, `izvor`) VALUES
(1, 'Slika 1', 'Slika 1', 'https://unsplash.it/300/200?random=1', 'api'),
(2, 'Slika 2', 'Slika 2', 'https://unsplash.it/300/200?random=2', 'api'),
(3, 'Slika 3', 'Slika 3', 'https://unsplash.it/300/200?random=3', 'api'),
(4, 'Slika 4', 'Slika 4', 'https://unsplash.it/300/200?random=4', 'api'),
(5, 'Slika 5', 'Slika 5', 'https://unsplash.it/300/200?random=5', 'api'),
(6, 'Slika 6', 'Slika 6', 'https://unsplash.it/300/200?random=6', 'api'),
(7, 'Slika 7', 'Slika 7', 'https://unsplash.it/300/200?random=7', 'api'),
(8, 'Slika 8', 'Slika 8', 'https://unsplash.it/300/200?random=8', 'api'),
(9, 'Slika 9', 'Slika 9', 'https://unsplash.it/300/200?random=9', 'api'),
(10, 'Slika 10', 'Slika 10', 'https://unsplash.it/300/200?random=10', 'api'),
(11, 'Slika 11', 'Slika 11', 'https://unsplash.it/300/200?random=11', 'api'),
(12, 'Slika 12', 'Slika 12', 'https://unsplash.it/300/200?random=12', 'api'),
(13, 'Slika 13', 'Slika 13', 'https://unsplash.it/300/200?random=13', 'api'),
(14, 'Slika 14', 'Slika 14', 'https://unsplash.it/300/200?random=14', 'api'),
(15, 'Slika 15', 'Slika 15', 'https://unsplash.it/300/200?random=15', 'api'),
(16, 'Slika 16', 'Slika 16', 'https://unsplash.it/300/200?random=16', 'api'),
(17, 'maleficent.jpg', 'Film', 'public/images/maleficent.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','administrator') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'lorena', 'lorena@gmail.com', '$2y$10$qZAZri3pF8ByL/IgOPLYuu1nLv0iGrrr.Al1EY2KH6i1Rh28/Z6NG', 'administrator', '2026-05-14 10:45:59'),
(2, 'lorena 2', 'lorena2@gmail.com', '$2y$10$Mon7Datsn/F/qYBF/6jui.dugY7W3yXKsTfrehbTQdgkRIn7VPNb.', 'user', '2026-05-14 12:29:59');

-- --------------------------------------------------------

--
-- Table structure for table `wanted_movies`
--

CREATE TABLE `wanted_movies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wanted_movies`
--

INSERT INTO `wanted_movies` (`id`, `user_id`, `movie_id`, `created_at`) VALUES
(2, 1, 1, '2026-05-14 12:29:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocjene_slika`
--
ALTER TABLE `ocjene_slika`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ocjena` (`id_korisnik`,`id_slika`);

--
-- Indexes for table `slike`
--
ALTER TABLE `slike`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wanted_movies`
--
ALTER TABLE `wanted_movies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_movie` (`user_id`,`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `ocjene_slika`
--
ALTER TABLE `ocjene_slika`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `slike`
--
ALTER TABLE `slike`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wanted_movies`
--
ALTER TABLE `wanted_movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `wanted_movies`
--
ALTER TABLE `wanted_movies`
  ADD CONSTRAINT `wanted_movies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wanted_movies_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
