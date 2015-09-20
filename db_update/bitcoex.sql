-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Сен 20 2015 г., 20:45
-- Версия сервера: 5.5.42
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `bitcoex`
--

-- --------------------------------------------------------

--
-- Структура таблицы `authassignment`
--

CREATE TABLE IF NOT EXISTS `authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `authassignment`
--

INSERT INTO `authassignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('Admin', '1', NULL, 'N;');

-- --------------------------------------------------------

--
-- Структура таблицы `authitem`
--

CREATE TABLE IF NOT EXISTS `authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `authitem`
--

INSERT INTO `authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('Admin', 2, NULL, NULL, 'N;'),
('Authenticated', 2, NULL, NULL, 'N;'),
('Guest', 2, NULL, NULL, 'N;');

-- --------------------------------------------------------

--
-- Структура таблицы `authitemchild`
--

CREATE TABLE IF NOT EXISTS `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_order`
--

CREATE TABLE IF NOT EXISTS `tbl_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `src_wallet` int(11) NOT NULL,
  `summ` double NOT NULL,
  `price` double NOT NULL,
  `dst_wallet` int(11) NOT NULL,
  `rest` double NOT NULL,
  `date` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `src_wallet_type` int(11) NOT NULL,
  `dst_wallet_type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Дамп данных таблицы `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `user`, `src_wallet`, `summ`, `price`, `dst_wallet`, `rest`, `date`, `status`, `src_wallet_type`, `dst_wallet_type`) VALUES
(2, 12, 2, 2, 222, 3, 0, 1440698716, 1, 1, 2),
(3, 12, 3, 2, 222, 2, 0, 1440699657, 1, 2, 1),
(4, 14, 8, 1, 222, 9, 0, 1440700614, 1, 1, 2),
(5, 14, 8, 1, 222, 9, 0, 1440700935, 1, 1, 2),
(6, 14, 8, 1, 222, 9, 0, 1440700963, 1, 1, 2),
(7, 14, 8, 1, 222, 9, 0, 1440701201, 1, 1, 2),
(8, 14, 8, 1, 222, 9, 0, 1440979733, 1, 1, 2),
(9, 14, 8, 1, 222, 9, 0, 1440979868, 1, 1, 2),
(10, 12, 3, 1, 222, 2, 0, 1441111647, 1, 2, 1),
(11, 12, 2, 1, 222, 3, 2, 1441112096, 0, 1, 2),
(12, 12, 3, 1, 200, 2, 1, 1441122689, 0, 2, 1),
(13, 12, 3, 1, 222, 2, 1, 1441122861, 0, 2, 1),
(14, 12, 3, 1, 222, 2, 1, 1441123415, 0, 2, 1),
(15, 12, 3, 1, 222, 2, 1, 1441123473, 0, 2, 1),
(16, 12, 3, 1, 222, 2, 1, 1442442946, 0, 2, 1),
(17, 12, 3, 1, 222, 2, 1, 1442444236, 0, 2, 1),
(18, 12, 3, 1, 222, 2, 1, 1442444274, 0, 2, 1),
(19, 12, 3, 1, 222, 2, 0, 1442444430, 1, 2, 1),
(20, 12, 3, 0, 222, 2, 0, 1442444492, 1, 2, 1),
(21, 12, 3, 0, 222, 2, 0, 1442444510, 1, 2, 1),
(22, 12, 3, 1, 222, 2, 0, 1442444518, 1, 2, 1),
(23, 12, 3, 1, 222, 2, 0, 1442444531, 1, 2, 1),
(24, 12, 3, 1, 222, 2, 1, 1442444700, 0, 2, 1),
(25, 12, 3, 1, 222, 2, 1, 1442444770, 0, 2, 1),
(26, 12, 3, 0, 222, 2, 0, 1442444949, 1, 2, 1),
(27, 12, 3, 1, 222, 2, 1, 1442444967, 0, 2, 1),
(28, 12, 3, 1, 222, 2, 0, 1442445158, 1, 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_profiles`
--

CREATE TABLE IF NOT EXISTS `tbl_profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `tbl_profiles`
--

INSERT INTO `tbl_profiles` (`user_id`, `lastname`, `firstname`) VALUES
(1, 'Admin', 'Administrator'),
(2, 'Demo', 'Demo'),
(12, 'trader6', 'trader6'),
(13, 'trader5', 'trader'),
(14, 'trader4', 'trader');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_profiles_fields`
--

CREATE TABLE IF NOT EXISTS `tbl_profiles_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` varchar(15) NOT NULL DEFAULT '0',
  `field_size_min` varchar(15) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` varchar(5000) NOT NULL DEFAULT '',
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`,`widget`,`visible`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `tbl_profiles_fields`
--

INSERT INTO `tbl_profiles_fields` (`id`, `varname`, `title`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `widget`, `widgetparams`, `position`, `visible`) VALUES
(1, 'lastname', 'Last Name', 'VARCHAR', '50', '3', 1, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 1, 3),
(2, 'firstname', 'First Name', 'VARCHAR', '50', '3', 1, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 0, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_transaction`
--

CREATE TABLE IF NOT EXISTS `tbl_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src_wallet` int(11) NOT NULL,
  `src_count` double NOT NULL,
  `src_price` double NOT NULL,
  `dst_wallet` int(11) NOT NULL,
  `dst_count` double NOT NULL,
  `dst_price` double NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Дамп данных таблицы `tbl_transaction`
--

INSERT INTO `tbl_transaction` (`id`, `src_wallet`, `src_count`, `src_price`, `dst_wallet`, `dst_count`, `dst_price`, `date`, `order`) VALUES
(3, 8, 1, 222, 2, 1, 222, '2015-08-27 18:46:56', 7),
(4, 3, 0.0045045045045045, 222, 9, 0.0045045045045045, 222, '2015-08-27 18:46:56', 3),
(5, 8, 1, 222, 2, 1, 222, '2015-08-31 00:08:56', 8),
(6, 3, 0.0045045045045045, 222, 9, 0.0045045045045045, 222, '2015-08-31 00:08:56', 3),
(7, 8, 1, 222, 2, 1, 222, '2015-08-31 00:11:08', 9),
(8, 3, 0.0045045045045045, 222, 9, 0.0045045045045045, 222, '2015-08-31 00:11:08', 3),
(9, 2, 2, 222, 2, 2, 222, '2015-09-01 12:49:06', 2),
(10, 3, 0.009009009009009, 222, 3, 0.009009009009009, 222, '2015-09-01 12:49:06', 10),
(11, 3, -1, 222, 3, -1, 222, '2015-09-01 12:55:26', 10),
(12, 2, -222, 222, 2, -222, 222, '2015-09-01 12:55:26', 11),
(13, 8, 1, 222, 2, 1, 222, '2015-09-01 16:03:36', 4),
(14, 3, 0.0045045045045045, 222, 9, 0.0045045045045045, 222, '2015-09-01 16:03:36', 14),
(15, 3, 1, 222, 9, 1, 222, '2015-09-16 22:36:12', 16),
(16, 8, 222, 222, 2, 222, 222, '2015-09-16 22:36:12', 4),
(17, 3, 1, 222, 9, 1, 222, '2015-09-16 22:57:16', 17),
(18, 8, 222, 222, 2, 222, 222, '2015-09-16 22:57:16', 4),
(19, 3, 1, 222, 9, 1, 222, '2015-09-16 22:57:54', 18),
(20, 8, 222, 222, 2, 222, 222, '2015-09-16 22:57:54', 4),
(21, 3, 1, 222, 9, 1, 222, '2015-09-16 23:00:45', 19),
(22, 8, 222, 222, 2, 222, 222, '2015-09-16 23:00:45', 4),
(23, 3, 1, 222, 9, 1, 222, '2015-09-16 23:01:58', 22),
(24, 8, 222, 222, 2, 222, 222, '2015-09-16 23:01:58', 5),
(25, 3, 1, 222, 9, 1, 222, '2015-09-16 23:03:05', 23),
(26, 8, 222, 222, 2, 222, 222, '2015-09-16 23:03:05', 6),
(27, 3, 1, 222, 9, 1, 222, '2015-09-16 23:05:00', 24),
(28, 3, 1, 222, 9, 1, 222, '2015-09-16 23:07:01', 25),
(29, 3, 1, 222, 9, 1, 222, '2015-09-16 23:09:46', 27),
(30, 3, 1, 222, 9, 1, 222, '2015-09-16 23:12:44', 28),
(31, 8, 222, 222, 2, 222, 222, '2015-09-16 23:12:44', 7);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `superuser` (`superuser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Дамп данных таблицы `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `password`, `email`, `activkey`, `create_at`, `lastvisit_at`, `superuser`, `status`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'webmaster@example.com', '9a24eff8c15a6a141ece27eb6947da0f', '2015-08-22 21:40:33', '2015-08-22 21:50:31', 1, 1),
(2, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'demo@example.com', '099f825543f7850cc038b90aaff39fac', '2015-08-22 21:40:33', '0000-00-00 00:00:00', 0, 1),
(12, 'trader6', '057a00ebe4c35f8e72be9349834dc619', 'trader6@bitcoex.int', '60505d23f0b0eeab7461033a478ccf68', '2015-08-24 23:34:29', '2015-09-16 22:33:40', 0, 1),
(13, 'trader5', '057a00ebe4c35f8e72be9349834dc619', 'trader5@bitcoex.int', 'fb27e5cf7b356b4820de2d7873a682ed', '2015-08-27 18:22:27', '2015-08-27 18:23:15', 0, 1),
(14, 'trader4', '057a00ebe4c35f8e72be9349834dc619', 'trader4@bitcoex.int', '1a473f73e4af09e51ec047b726e8bead', '2015-08-27 18:27:56', '2015-08-27 18:29:49', 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_wallet`
--

CREATE TABLE IF NOT EXISTS `tbl_wallet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `money` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `tbl_wallet`
--

INSERT INTO `tbl_wallet` (`id`, `user_id`, `type`, `money`) VALUES
(2, 12, 1, 2467),
(3, 12, 2, 39.972972972971),
(4, 13, 1, 415),
(5, 13, 2, 89),
(6, 13, 1, 1364),
(7, 13, 2, 149),
(8, 14, 1, 1234),
(9, 14, 2, 20.98198198198);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_wallet_type`
--

CREATE TABLE IF NOT EXISTS `tbl_wallet_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `authassignment`
--
ALTER TABLE `authassignment`
  ADD CONSTRAINT `authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `authitemchild`
--
ALTER TABLE `authitemchild`
  ADD CONSTRAINT `authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rights`
--
ALTER TABLE `rights`
  ADD CONSTRAINT `rights_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_profiles`
--
ALTER TABLE `tbl_profiles`
  ADD CONSTRAINT `user_profile_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
