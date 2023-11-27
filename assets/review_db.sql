-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-11-27 13:21:58
-- サーバのバージョン： 10.4.28-MariaDB
-- PHP のバージョン: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `review_db`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `posts`
--

CREATE TABLE `posts` (
  `id` varchar(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `image` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `posts`
--

INSERT INTO `posts` (`id`, `title`, `image`) VALUES
('h12FAnxY6JoXb51iDpda', '01 example post title', 'post_1.webp'),
('sRKX0vSREJbBzO07wM1H', '02 example post title', 'post_2.webp'),
('G6zDaxTTS0fV5UT4BQ46', '03 example post title', 'post_3.webp'),
('6zQRsklaYIO38cLIgYZN', '04 example post title', 'post_4.webp'),
('mMj2FWPRVWZPsfOsjSUL', '05 example post title', 'post_5.webp'),
('hK2tgabAaK1c1FAak6UW', '06 example post title', 'post_6.webp');

-- --------------------------------------------------------

--
-- テーブルの構造 `reviews`
--

CREATE TABLE `reviews` (
  `id` varchar(20) NOT NULL,
  `post_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `rating` varchar(1) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `reviews`
--

INSERT INTO `reviews` (`id`, `post_id`, `user_id`, `rating`, `title`, `description`, `date`) VALUES
('FkpArKDRtQsk2bfeB02q', 'h12FAnxY6JoXb51iDpda', '5', '2', 'good!', 'good review', '2023-11-27'),
('pP4ev04sWVZA7iedvjEj', 'sRKX0vSREJbBzO07wM1H', '5', '5', 'great', 'great!', '2023-11-27'),
('7xGD8AeD6UUtWXa4Nydm', 'h12FAnxY6JoXb51iDpda', '6', '2', 'least', 'least', '2023-11-27'),
('vSG6nJdKzHkDhgvbjyLP', 'sRKX0vSREJbBzO07wM1H', '6', '3', 'normal', 'narmal', '2023-11-27'),
('TZ5k48urRtUdGixFKrkW', 'h12FAnxY6JoXb51iDpda', '7', '5', 'amazing', 'amazing!', '2023-11-27'),
('gDi859GK5NNm1BgKwKk3', 'sRKX0vSREJbBzO07wM1H', '7', '4', 'good', 'good!', '2023-11-27'),
('jVBTEChr5E2dnBkd15e7', 'h12FAnxY6JoXb51iDpda', '8', '4', 'good', 'good', '2023-11-27');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `image` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`) VALUES
(5, 'userA', 'userA@gmail.com', '$2y$10$NAK/mrDh.G8JfniipT2DtOSYSQQ68MnWPm.w2rPTEShLvn3wIjC0C', '1pa0eMC2X9M0PPDivijZ.png'),
(6, 'userB', 'userB@gmail.com', '$2y$10$gfU5fg/xA6Q5.UDpjhoNKeq.RYREmjqQXaHotAAQFGOEqZE.f728e', 'Lx80B5sBrXr7BXXxwcS6.png'),
(7, 'userC', 'userC@gmail.com', '$2y$10$yv9TwZbOJkCHVK8feqD4ZulCEjGcFz0JwpgwspdsvrBUJIDK82DQ.', 'SizgMHuw1QxDsuQ6SU4A.png'),
(8, 'user01', 'user01@gmail.com', '$2y$10$HUlBzELJ5edSpbJBM9v/.uTLUI1z462T9OzqGM5RnQVk43/6Gtxu2', '');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
