-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-07-14 22:10:43
-- サーバのバージョン： 10.4.27-MariaDB
-- PHP のバージョン: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `migrations`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `projects`
--

CREATE TABLE `projects` (
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `project_name` varchar(60) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `color_type` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `projects`
--

INSERT INTO `projects` (`user_id`, `project_id`, `project_name`, `description`, `color_type`, `created_at`) VALUES
(16, 10, '試運転', '', NULL, '2023-05-27 16:01:59'),
(16, 11, 'db', '', NULL, '2023-05-27 16:02:10'),
(17, 12, '試運転', '', 'red', '2023-05-27 16:13:05'),
(17, 13, 'db', '', NULL, '2023-05-27 16:13:26'),
(17, 14, 'プロジェクト', '概要', 'red', '2023-05-27 16:38:10'),
(16, 15, 'プロジェクト', '赤', 'red', '2023-05-30 07:16:42'),
(16, 16, 'iro ', 'ki ', 'yellow', '2023-05-31 18:35:33'),
(16, 17, 'dsv', 'ｄｓｖ', 'green', '2023-07-07 21:23:24'),
(27, 18, 'tamago', 'tamago', 'yellow', '2023-07-10 23:32:32');

-- --------------------------------------------------------

--
-- テーブルの構造 `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `task_description` varchar(500) NOT NULL,
  `order_num` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `title`, `task_description`, `order_num`, `status`, `created_at`, `updated_at`) VALUES
(161, 10, 'ｓｄｃ', 'ｓｄｃ', 2, 'not_started', '2023-07-07 21:13:21', '2023-07-14 13:02:35'),
(168, 10, 'sdvssdv', 'ｓｄｖ', 2, 'in_progress', '2023-07-08 14:18:57', '2023-07-14 13:03:28'),
(169, 10, 'html', 'html', 2, 'completed', '2023-07-08 14:19:36', '2023-07-14 13:03:29'),
(170, 10, 'z', 'ｚｘ', 3, 'in_progress', '2023-07-08 15:25:58', '2023-07-14 13:03:28'),
(172, 17, 'vsdv', 'sdv', 1, 'not_started', '2023-07-10 15:38:33', '2023-07-14 12:24:45'),
(174, 18, 'tamago', 'tamago', 1, 'not_started', '2023-07-10 23:32:43', '2023-07-10 23:33:06'),
(175, 18, 'tamago', 'tamago', 2, 'not_started', '2023-07-10 23:32:58', '2023-07-10 23:33:06'),
(211, 10, 'さかｓｃ', 'さｃさ', 1, 'completed', '2023-07-14 03:00:37', '2023-07-14 13:03:29'),
(224, 10, 'acsa', 'sacsac', 3, 'not_started', '2023-07-14 12:07:10', '2023-07-14 13:02:35');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `pass` varchar(260) NOT NULL,
  `path` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `mail`, `pass`, `path`, `created_at`) VALUES
(16, '崎長雅史', 'masashi.pc1110@gmail.com', '$2y$10$/Xxt0LAx7HJF.KIwOxZkWeiP.DRn81YI2eLHEo9JLiMQxea1Kt8gu', 'saruiwa.jpg', '2023-05-23 09:25:31'),
(17, 'sakinaga masashi', 'dokushiyorunner@gmail.com', '$2y$10$O0qvBZtp7az8XydjOG4lX.g2szlqqqqRa2kHRbu94cLnh7BptA45u', 'zikosilyoukai.jpg', '2023-05-23 10:38:52'),
(27, 'たまご', 'tamago@gmail.com', '$2y$10$F6gFzeazZ3w3wlU2GAKAEOXlRtIg.YxWZa4jFhvJdOA.IGMkMx/H2', 'lunch-01.jpg', '2023-07-10 23:31:59');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `project_id` (`project_id`);

--
-- テーブルのインデックス `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`mail`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- テーブルの AUTO_INCREMENT `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
