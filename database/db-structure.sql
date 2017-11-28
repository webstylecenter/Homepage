SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `checklist` (
  `id` int(11) NOT NULL,
  `item` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `checked` tinyint(1) NOT NULL,
  `lastUpdated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `feeds` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `feedUrl` varchar(255) DEFAULT NULL,
  `color` varchar(6) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `feed_data` (
  `id` int(11) NOT NULL,
  `feed` int(11) NOT NULL,
  `guid` varchar(255) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateAdded` datetime NOT NULL,
  `viewed` tinyint(1) NOT NULL,
  `pinned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `cache`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cache_id` (`cache_id`);

ALTER TABLE `checklist`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `feeds`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `feed_data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guid` (`guid`),
  ADD KEY `dateAdded` (`dateAdded`),
  ADD KEY `title` (`title`(191)),
  ADD KEY `feed` (`feed`),
  ADD KEY `dateAdded_2` (`dateAdded`,`pinned`);
ALTER TABLE `feed_data` ADD FULLTEXT KEY `description` (`description`,`title`);

ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `cache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `checklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `feeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `feed_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


ALTER TABLE `feed_data`
  ADD CONSTRAINT `feed_data_ibfk_1` FOREIGN KEY (`feed`) REFERENCES `feeds` (`id`) ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
