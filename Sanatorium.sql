-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 30 2026 г., 18:24
-- Версия сервера: 8.0.30
-- Версия PHP: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Sanatorium`
--

-- --------------------------------------------------------

--
-- Структура таблицы `booking`
--

CREATE TABLE `booking` (
  `id` int UNSIGNED NOT NULL,
  `room_id` int UNSIGNED NOT NULL,
  `arrival_date` date NOT NULL,
  `departure_date` date NOT NULL,
  `contact_phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status_booking_id` int UNSIGNED NOT NULL,
  `amount_residents` int NOT NULL,
  `pay_type_id` int UNSIGNED NOT NULL,
  `payment_status` int UNSIGNED NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `booking`
--

INSERT INTO `booking` (`id`, `room_id`, `arrival_date`, `departure_date`, `contact_phone`, `price`, `status_booking_id`, `amount_residents`, `pay_type_id`, `payment_status`, `payment_amount`, `comment`) VALUES
(45, 8, '2026-05-17', '2026-05-24', '8(999)999-99-20', '25200.00', 2, 1, 1, 2, '7560.00', ''),
(46, 11, '2026-05-23', '2026-05-29', '8(999)999-99-99', '13200.00', 2, 1, 3, 2, '3960.00', ''),
(47, 13, '2026-06-05', '2026-06-12', '8(999)999-99-99', '67550.00', 3, 1, 1, 2, '20265.00', ''),
(48, 13, '2026-05-24', '2026-05-31', '8(999)999-99-99', '67550.00', 2, 1, 1, 2, '20265.00', ''),
(49, 10, '2026-05-24', '2026-05-31', '8(999)999-99-98', '30100.00', 2, 1, 3, 2, '9030.00', ''),
(51, 8, '2026-05-30', '2026-06-06', '8(999)999-99-20', '25200.00', 2, 1, 3, 2, '7560.00', ''),
(52, 10, '2026-12-12', '2026-12-24', '8(999)888-77-66', '51600.00', 3, 1, 3, 2, '15480.00', ''),
(53, 13, '2026-05-31', '2026-06-07', '8(999)888-77-66', '67550.00', 1, 1, 1, 1, '20265.00', ''),
(54, 13, '2026-06-08', '2026-07-13', '8(999)999-99-99', '337750.00', 1, 1, 3, 2, '101325.00', ''),
(55, 15, '2026-06-06', '2026-06-10', '8(999)888-77-66', '21000.00', 5, 3, 1, 2, '6300.00', ''),
(57, 8, '2026-06-14', '2026-06-17', '8(999)888-77-66', '5400.00', 4, 1, 1, 2, '1620.00', 'плед, халат и фен дл волос'),
(58, 14, '2026-06-30', '2026-07-04', '8(999)888-77-66', '18000.00', 4, 2, 3, 2, '5400.00', 'принесите плед, фен и утюг');

-- --------------------------------------------------------

--
-- Структура таблицы `booking_user`
--

CREATE TABLE `booking_user` (
  `id` int UNSIGNED NOT NULL,
  `resident_id` int UNSIGNED NOT NULL,
  `booking_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `booking_user`
--

INSERT INTO `booking_user` (`id`, `resident_id`, `booking_id`) VALUES
(54, 54, 45),
(55, 55, 46),
(56, 56, 47),
(57, 57, 48),
(58, 58, 49),
(60, 60, 51),
(61, 61, 52),
(62, 62, 53),
(63, 63, 54),
(64, 64, 55),
(65, 65, 55),
(66, 66, 55),
(67, 67, 57),
(68, 68, 58),
(69, 69, 58);

-- --------------------------------------------------------

--
-- Структура таблицы `guest_profile`
--

CREATE TABLE `guest_profile` (
  `id` int NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `birth_date` date NOT NULL,
  `passport_series` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `passport_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `guest_profile`
--

INSERT INTO `guest_profile` (`id`, `user_id`, `phone`, `birth_date`, `passport_series`, `passport_number`) VALUES
(1, 6, '8(999)999-99-99', '1966-04-24', '4021', '998544'),
(2, 2, '8(999)999-99-99', '2001-12-12', '4021', '998544'),
(3, NULL, '8(955)922-78-14', '2002-08-17', '4712', '457913');

-- --------------------------------------------------------

--
-- Структура таблицы `level`
--

CREATE TABLE `level` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `level`
--

INSERT INTO `level` (`id`, `title`) VALUES
(1, 'Легкая'),
(2, 'Средняя');

-- --------------------------------------------------------

--
-- Структура таблицы `payment_status`
--

CREATE TABLE `payment_status` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `payment_status`
--

INSERT INTO `payment_status` (`id`, `title`, `alias`) VALUES
(1, 'pending', 'Ожидает оплаты'),
(2, 'paid', 'Оплачено');

-- --------------------------------------------------------

--
-- Структура таблицы `pay_type`
--

CREATE TABLE `pay_type` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `pay_type`
--

INSERT INTO `pay_type` (`id`, `title`) VALUES
(1, 'QR-код'),
(3, 'Банковская карта');

-- --------------------------------------------------------

--
-- Структура таблицы `raiting`
--

CREATE TABLE `raiting` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `route_id` int UNSIGNED NOT NULL,
  `stars` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `raiting`
--

INSERT INTO `raiting` (`id`, `user_id`, `route_id`, `stars`) VALUES
(1, 2, 4, '4.00'),
(2, 8, 4, '5.00');

-- --------------------------------------------------------

--
-- Структура таблицы `reason`
--

CREATE TABLE `reason` (
  `booking_id` int UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `reason`
--

INSERT INTO `reason` (`booking_id`, `comment`) VALUES
(47, 'Не приехали люди в наш прекрасный санаторий');

-- --------------------------------------------------------

--
-- Структура таблицы `resident`
--

CREATE TABLE `resident` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `patronymic` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `wellness_program_id` int UNSIGNED DEFAULT NULL,
  `birth_date` date NOT NULL,
  `is_main_guest` tinyint NOT NULL DEFAULT '0' COMMENT 'главный гость в бронировании',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `resident`
--

INSERT INTO `resident` (`id`, `user_id`, `name`, `surname`, `patronymic`, `wellness_program_id`, `birth_date`, `is_main_guest`, `created_at`) VALUES
(25, 6, 'Кирилл', 'Кириллов', 'Кириллович', 3, '1966-04-24', 1, '2026-04-20 13:38:32'),
(26, 8, 'Марина', 'Шестакович', 'Геннадьевна', 3, '1987-06-12', 1, '2026-04-20 17:40:17'),
(27, NULL, 'Кира', 'Шестакович', 'Кирилловна', 4, '2010-02-17', 0, '2026-04-20 17:40:17'),
(28, 6, 'Марина', 'Шестакович', 'Геннадьевна', 3, '1987-12-12', 1, '2026-04-22 12:11:16'),
(29, NULL, 'Кира', 'Кириллова', 'Кирилловна', 4, '1978-05-14', 0, '2026-04-22 12:11:16'),
(30, 2, 'Ирина', 'Дементьева', 'Григорьевна', 4, '2001-12-12', 1, '2026-04-27 15:54:43'),
(31, NULL, 'Анастасия', 'Алекова', 'Ивановна', 4, '2002-08-17', 0, '2026-04-27 15:54:43'),
(32, 2, 'Анна', 'Дементьева', 'Иоановна', 3, '1999-03-12', 1, '2026-05-07 11:55:45'),
(33, 8, 'Евгения', 'Куимова', 'Николаевна', 4, '2002-02-04', 1, '2026-05-07 12:55:28'),
(34, 6, 'Кирилл', 'Дементьева', 'Кириллович', 4, '1987-12-09', 1, '2026-05-08 08:08:34'),
(35, NULL, 'Кира', 'Кириллова', 'Ивановна', 3, '1987-05-22', 0, '2026-05-08 08:08:34'),
(36, 2, 'Ирина', 'Шестакович', 'Геннадьевна', 3, '1988-05-16', 1, '2026-05-08 08:22:43'),
(37, 2, 'Евгения', 'Дементьева', 'Григорьевна', 4, '1987-12-12', 1, '2026-05-09 19:22:19'),
(38, NULL, 'Кира', 'Кириллова', 'Кирилловна', 3, '1966-04-04', 0, '2026-05-09 19:22:19'),
(39, 6, 'Анна', 'Куимова', 'Геннадьевна', 3, '1912-12-12', 1, '2026-05-11 11:03:52'),
(40, 8, 'Евгений', 'Кириллов', 'Кириллович', 3, '1995-12-12', 1, '2026-05-12 08:30:10'),
(41, 8, 'Ирина', 'Дементьева', 'Ивановна', 3, '1999-01-01', 1, '2026-05-12 08:34:25'),
(42, 8, 'Марина', 'Шестакович', 'Геннадьевна', 3, '1996-06-06', 1, '2026-05-12 08:40:34'),
(43, 8, 'Кирилл', 'Кириллов', 'Кириллович', 3, '1997-11-12', 1, '2026-05-12 08:45:04'),
(44, NULL, 'Кира', 'Шестакович', 'Кирилловна', 4, '1999-02-17', 0, '2026-05-12 08:45:04'),
(47, 8, 'Наталья', 'Кириллова', 'Ивановна', 3, '2012-12-12', 1, '2026-05-14 09:56:10'),
(48, NULL, 'Анастасия', 'Алекова', 'Ивановна', 4, '1999-01-14', 0, '2026-05-14 09:56:10'),
(49, 8, 'Юлия', 'Новикова', 'Андреевна', 3, '2006-05-02', 1, '2026-05-14 10:45:37'),
(50, 2, 'Анна', 'Дементьева', 'Иоановна', 4, '2002-01-14', 1, '2026-05-14 11:52:34'),
(54, 2, 'Анна', 'Дементьева', 'Иоановна', 3, '2001-01-12', 1, '2026-05-14 12:13:06'),
(55, 2, 'Анна', 'Дементьева', 'Иоановна', 3, '2001-01-12', 1, '2026-05-14 12:26:59'),
(56, 2, 'ф', 'ффф', 'ф', 4, '2012-12-12', 1, '2026-05-18 10:14:59'),
(57, 6, 'Дарья', 'Левченко', 'Максимовна', 3, '2006-06-15', 1, '2026-05-19 11:01:38'),
(58, 6, 'Дарья', 'Никифорова', 'Ивановна', 3, '2002-12-12', 1, '2026-05-19 12:06:17'),
(59, 6, 'Екатерина', 'Панова', 'Никитишна', 3, '2001-01-01', 1, '2026-05-22 12:33:26'),
(60, 6, 'Анна', 'Куимова', 'Ивановна', 4, '2001-01-12', 1, '2026-05-22 12:43:31'),
(61, 8, 'Наталья', 'Томова', 'Николаевна', 3, '1995-11-14', 1, '2026-05-26 13:18:58'),
(62, 8, 'Наталья', 'Томова', 'Николаевна', 4, '1995-11-14', 1, '2026-05-27 12:15:06'),
(63, 2, 'Кирилл', 'Кириллов', 'Кириллович', 3, '1995-11-12', 1, '2026-05-27 12:40:16'),
(64, 2, 'Наталья', 'Шек', 'Ивановна', 4, '2001-01-01', 1, '2026-05-29 17:10:05'),
(65, NULL, 'Анастасия', 'Шек', 'Анатольевна', 3, '1977-09-06', 0, '2026-05-29 17:10:05'),
(66, NULL, 'Иван', 'Шек', 'Иванович', 4, '1981-11-14', 0, '2026-05-29 17:10:05'),
(67, 2, 'Наталья', 'Шек', 'Ивановна', 3, '2001-01-01', 1, '2026-05-29 18:49:52'),
(68, 2, '1', '1', '1', 4, '2001-01-01', 1, '2026-05-30 13:24:27'),
(69, NULL, '2', '2', '2', 3, '2002-02-02', 0, '2026-05-30 13:24:27');

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `title`, `alias`) VALUES
(1, 'Admin', 'Администратор'),
(2, 'User', 'Клиент'),
(3, 'Reception', 'Ресепшн');

-- --------------------------------------------------------

--
-- Структура таблицы `room`
--

CREATE TABLE `room` (
  `id` int UNSIGNED NOT NULL,
  `room_type_id` int UNSIGNED NOT NULL,
  `number` int NOT NULL,
  `floor` int NOT NULL,
  `status_room_id` int UNSIGNED NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price_per_day` int NOT NULL,
  `number_guests` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `room`
--

INSERT INTO `room` (`id`, `room_type_id`, `number`, `floor`, `status_room_id`, `description`, `price_per_day`, `number_guests`) VALUES
(8, 1, 20, 2, 1, 'В однокомнатном номере есть всё необходимое для вашего комфортного проживания: односпальная кровать, письменный стол, стул, багажная тумба, вешалка для одежды, телевизор. В ванной комнате: душ и туалет\r\n\r\nВид из окон: на парк или во внутренний двор отеля. Окна в комнатах звукоизолирующие, с москитными сетками. По просьбе бесплатно предоставляются фен и утюг', 1800, 1),
(10, 1, 21, 2, 1, 'В однокомнатном номере есть всё необходимое для вашего комфортного проживания: две односпальные кровати, письменный стол, стул, багажная тумба, вешалка для одежды, телевизор\r\n\r\nВид из окон: на городской парк. Окна в комнатах звукоизолирующие, с москитными сетками. По просьбе бесплатно предоставляются фен и утюг.', 2200, 2),
(11, 1, 23, 2, 1, 'В однокомнатном номере есть всё необходимое для вашего комфортного проживания: две односпальные кровати, письменный стол, стул, багажная тумба, вешалка для одежды, телевизор. В ванной комнате: душ и туалет', 2200, 2),
(12, 3, 24, 2, 1, 'Просторный номер с улучшенной меблировкой и выходом окон на парк или внутренний двор отеля. Две кровати, письменный стол, стул, багажная тумба, вешалка для одежды, телевизор\r\n\r\nВ санузле - полноценная ванная и туалет', 8570, 2),
(13, 3, 25, 2, 1, 'Номер имеет двуспальную кровать и подойдет требовательным к комфорту путешественникам. В стоимость включены чайная станция, сейф, мини-холодильник, плазменный телевизор 43”, выделенная зона для стоянки транспорта и завтрак «шведский стол». Номер оборудован эргономичным санузлом с ванной, кондиционером, USB-розетками, гардеробной комнатой и рабочим местом.', 9650, 2),
(14, 2, 31, 3, 1, 'Просторный номер с улучшенной меблировкой и выходом окон на парк или внутренний двор отеля. Две кровати, письменный стол, стул, багажная тумба, вешалка для одежды, телевизор.', 4500, 2),
(15, 2, 32, 3, 2, 'Просторный номер с улучшенной меблировкой и выходом окон на парк или внутренний двор отеля. Три кровати, письменный стол, стул, багажная тумба, вешалка для одежды, телевизор', 5250, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `room_image`
--

CREATE TABLE `room_image` (
  `id` int UNSIGNED NOT NULL,
  `room_id` int UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `room_image`
--

INSERT INTO `room_image` (`id`, `room_id`, `image`) VALUES
(1, 8, 'img/room/69c2754c581cc.webp'),
(2, 8, 'img/room/69c2754c56eee.webp'),
(3, 8, 'img/room/69c2754c57943.webp'),
(4, 8, 'img/room/69c2754c55529.webp'),
(9, 10, 'img/room/69c2aa258e65e.webp'),
(10, 10, 'img/room/69c2aa259034c.webp'),
(11, 10, 'img/room/69c2aa2590c8c.webp'),
(12, 10, 'img/room/69c2aa25915bf.webp'),
(13, 11, 'img/room/69c3eb94e5547.webp'),
(14, 11, 'img/room/69c3eb94e8b99.webp'),
(15, 11, 'img/room/69c3eb94e9b9f.webp'),
(16, 11, 'img/room/69c3eb94eaced.webp'),
(17, 12, 'img/room/69fc85a07a973.jpg'),
(18, 12, 'img/room/69fc85a07b26c.jpg'),
(19, 12, 'img/room/69fc85a078e27.jpg'),
(21, 13, 'img/room/69fc878670cd4.jpg'),
(22, 13, 'img/room/69fc87867165f.jpg'),
(23, 13, 'img/room/69fc878671f4f.jpg'),
(24, 13, 'img/room/69fc878672890.webp'),
(25, 13, 'img/room/69fc87867356b.jpg'),
(26, 14, 'img/room/6a18768fe959b.jpg'),
(27, 14, 'img/room/6a18768fe7891.jpg'),
(28, 14, 'img/room/6a18768fe9e94.jpg'),
(31, 15, 'img/room/6a187c0ea65de.jpg'),
(32, 15, 'img/room/6a187c0ea9195.jpg'),
(33, 15, 'img/room/6a187c0eaa07f.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `room_type`
--

CREATE TABLE `room_type` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `room_type`
--

INSERT INTO `room_type` (`id`, `name`) VALUES
(1, 'Эконом'),
(2, 'Стандарт'),
(3, 'Премиум');

-- --------------------------------------------------------

--
-- Структура таблицы `route`
--

CREATE TABLE `route` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `length` int NOT NULL,
  `duration` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `outfit` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_start` date NOT NULL,
  `time_start` time NOT NULL,
  `number_participant` int NOT NULL,
  `level_id` int UNSIGNED NOT NULL,
  `price` int NOT NULL,
  `stars` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `route`
--

INSERT INTO `route` (`id`, `name`, `description`, `length`, `duration`, `outfit`, `date_start`, `time_start`, `number_participant`, `level_id`, `price`, `stars`, `created_at`) VALUES
(3, 'Тропа Листвянка – Большие Коты', 'Первые 3,5 км тропа идёт в гору. Этот подъём – самая тяжёлая часть маршрута. Не доходя до вершины, тропа разделяется. Здесь необходимо выбрать правую ветку.\r\nНа вершине (860 м над уровнем моря, 404 м над уровнем озера) вас ждёт прекрасный вид на Байкал, к которому вы вскоре спуститесь.\r\nПосле серпантина тропа выходит к берегу. Тут есть место стоянки со столом.\r\nСтоянки разрешены только в специально отведенных местах, которые можно легко найти вдоль тропы. На некоторых участках тропа проходит вдоль крутых обрывов, высоко над водой. Местами она сужается, под ногами каменные осыпи.\r\nБудьте внимательны и осторожны. За 3 км до п. Большие Коты тропа спускается на галечный берег пади Черной и переходит в грунтовую дорогу.', 24, '6-8 часов', 'Удобная закрытая обувь, головной убор, одежда с длинным рукавом и длинные штаны (при желании шорты можно взять с собой), купальный костюм, желателен перекус и питье, репелленты (июнь-сентябрь), средства для отпугивания медведей. Сотовая связь на маршруте отсутствует.', '2026-06-18', '10:00:00', 10, 1, 720, '0.00', '2026-03-23 11:31:19'),
(4, 'Большие Коты – Большое Голоустное', 'От Больших Котов до мыса Скрипер тропа идет вдоль берега. В одном из мест (перед табличкой \"опасная тропа\") стоит спуститься по ступенькам, построенным волонтерами ББТ, на каменистый пляж и пройти 1-2 км. Затем можно подняться опять на берег.\r\n\r\nВ пади Сенной тропа серпантином поднимается на мыс Скрипер, с которого открывается прекрасный вид на Байкал и живописные берега.\r\n\r\nДалее через осиновый лес тропа спускается по распадку к Байкалу, где стоит сразу спуститься к берегу (тропа наверху частично осыпалась, спуски с нее к берегу крутые и сыпучие). На Мысе Соболев тропа снова поднимается наверх.\r\n\r\nНа Чаячьем Утесе тропа снова поднимается выше каменистого пляжа и проходит достаточно опасное место \"Чертов Мост\".\r\n\r\nДалее идет вдоль берега до пади Большой Кадильной, где расположен кордон Прибайкальского национального парка.\r\nДалее грунтовая дорога приведет вас в падь Малая Кадильная, где вы можете посетить пещеру Часовня.\r\n\r\nИдти по верхней тропе не стоит, тропа частично обрушилась. Лучше спуститься на каменистый пляж. Здесь через 700 м Вы найдете деревянную лестницу, забравшись по которой Вы выйдете на нормальную тропу.\r\n\r\nПосле лестницы тропа ведет вдоль берега, иногда спускаясь к Байкалу. Последние несколько километров до п. Большое Голоустное нужно пройти по грунтовой дороге.', 30, '8-14 часов', 'удобная закрытая обувь, головной убор, одежда с длинным рукавом и длинные штаны (при желании шорты можно взять с собой), купальный костюм, желателен перекус и питье, репелленты (июнь-сентябрь), средства для отпугивания медведей. Сотовая связь на маршруте отсутствует.', '2026-05-10', '10:00:00', 10, 2, 820, '0.00', '2026-05-06 18:22:01'),
(5, 'Устье р. Верхняя Ангара – бухта Аяя – Хакусы', 'Маршрут проходит по местам, мало затронутым деятельностью человека, вдалеке от поселений и автомобильных дорог.\r\n\r\nСтарт маршрута – кордон Верхнеангарского заказника, в устье одного из крупных притоков Байкала – Верхней Ангары. Тропа по большей части идет вдоль берега: частично по песчаным и каменистым пляжам, взбирается по каменистым утесам, а в некоторых местах уводит вглубь леса, где стоит быть особенно внимательным, чтобы не потеряться.\r\n\r\nБольшинство рек можно пересечь вброд. Однако примерно на половине пути, в дельте реки Фролиха, вам придется пересечь устье шириной 65 м. Через эту реку нет мостов и обходных путей. Лучше всего взять с собой небольшую резиновую надувную лодку.\r\n\r\nВ один момент тропа заворачивает к глубокую бухту Аяя. Чтобы добраться до бухты, вы должны пройти один из самых сложных участков тропы – каменные россыпи. Здесь придется перепрыгивать с камня на камень, которые иногда лежат в самом озере. Наградой за усилия станет прекрасный вид и песчаный пляж. В бухте начинается другая тропа, ведущая к горному озеру Фролиха (около 8 км в гору, или около 2-х часов пути).', 32, '10-12 часов', 'Удобная закрытая обувь, головной убор, одежда с длинным рукавом и длинные штаны (при желании шорты можно взять с собой), купальный костюм, желателен перекус и питье, репелленты (июнь-сентябрь), средства для отпугивания медведей. Сотовая связь на маршруте отсутствует.', '2026-06-19', '10:00:00', 8, 2, 950, '0.00', '2026-05-28 12:16:46');

-- --------------------------------------------------------

--
-- Структура таблицы `route_image`
--

CREATE TABLE `route_image` (
  `route_id` int UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `route_image`
--

INSERT INTO `route_image` (`route_id`, `image`) VALUES
(3, 'img/routes/69c12487dfe43.jfif'),
(4, 'img/routes/69fb86c902392.jpg'),
(5, 'img/routes/6a18322e93530.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `route_resident`
--

CREATE TABLE `route_resident` (
  `id` int NOT NULL,
  `route_id` int UNSIGNED NOT NULL,
  `resident_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `route_resident`
--

INSERT INTO `route_resident` (`id`, `route_id`, `resident_id`, `created_at`) VALUES
(17, 3, 64, '2026-05-29 17:33:24'),
(18, 3, 65, '2026-05-29 17:33:24'),
(19, 3, 66, '2026-05-29 17:33:24'),
(20, 4, 64, '2026-05-30 13:31:46');

-- --------------------------------------------------------

--
-- Структура таблицы `status_booking`
--

CREATE TABLE `status_booking` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `status_booking`
--

INSERT INTO `status_booking` (`id`, `title`, `alias`) VALUES
(1, 'Предстоящая поездка', 'new'),
(2, 'Прошедшая поездка', 'past'),
(3, 'Отмена', 'cancelled'),
(4, 'В обработке', 'pending'),
(5, 'Активная поездка', 'active');

-- --------------------------------------------------------

--
-- Структура таблицы `status_room`
--

CREATE TABLE `status_room` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `status_room`
--

INSERT INTO `status_room` (`id`, `title`, `alias`) VALUES
(1, 'Свободен', 'unbound'),
(2, 'Занят', 'occupied');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `surname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `patronymic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `auth_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `surname`, `name`, `patronymic`, `role_id`, `auth_key`) VALUES
(2, 'demo@demo.demo', '$2y$13$tzgIsjfY6kLY/rZxqjmh4uoLF9gvu1NEwfPqEsK8Yy2TpARqAhyo6', 'Дементьева', 'Анна', 'Николаевна', 2, 'w3YXslv8SPe8pjJ60o89-PWBKr1E1ZaN'),
(3, 'admin@admin.ru', '$2y$13$b551x/1ghQFCyRo550zEVuddIVyWtrMB6sCiLaV2FS7qtDbMU7JJG', 'Адамантьева', 'Админа', 'Михайловна', 1, '5xs-_vDJDjvfouCuyMuH1rNrXFYjC_l-'),
(6, 'web@web.ru', '$2y$13$2tZLzQ/2f12oGFVpFJsYjO/RMkJAat3j0eAuXcGdCIAKmP3zeTPK6', 'Вебер', 'Наталья', 'Николоевна', 2, 'J0F1CRxAX9fUwLXRu37GUeOhYMJ3X4C0'),
(8, 'q@q.q', '$2y$13$RdXP/xgpczXsrXoZpmljd.hI45ALzWKY7uiBr/8Xoy4nLhMdQD3ly', 'q', 'q', 'q', 2, '8j9JsdYQN6IZFcdrQdtVpkqXw3dLdJKc'),
(9, 'reception@dog.ru', '$2y$13$mLzJVHFogyF64GZgWR4/t.gnrHo4Z7fjLMPGVBpfa/CAF1UXYT7IG', 'Достаянов', 'Дмитрий', 'Денисович', 3, 'lkdU5jbgB60r3gyHJVpT15XLurC3GAzH');

-- --------------------------------------------------------

--
-- Структура таблицы `wellness_image`
--

CREATE TABLE `wellness_image` (
  `wellness_id` int UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `wellness_image`
--

INSERT INTO `wellness_image` (`wellness_id`, `image`) VALUES
(3, 'img/wellness/69c2826161ab4.png'),
(4, 'img/wellness/69e61c7a87dbc.png');

-- --------------------------------------------------------

--
-- Структура таблицы `wellness_program`
--

CREATE TABLE `wellness_program` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `wellness_program`
--

INSERT INTO `wellness_program` (`id`, `title`, `duration`, `description`) VALUES
(3, 'Общеоздоровительная', 'от 7 до 14 дней', 'Если вы чувствуете усталость, нервное напряжение или просто хотите восстановить силы, отдых в санатории — идеальное решение. На первичном приёме лечащий врач составит точный перечень необходимых процедур и подготовит индивидуальный план антистресс-программы в нашем санатории. Программа полезна для всех, кто нуждается в ментальной перезагрузке. Восстановите душевное равновесие и наполнитесь энергией в комфортной обстановке!'),
(4, 'Энергия', 'от 5 до 14 дней', 'Подойдёт тем, кто ощущает упадок сил, устал от спешки и постоянных перегрузок. Если вы просыпаетесь без чувства отдыха, чувствуете раздражение, апатию или признаки эмоционального выгорания.\r\n\r\nПрограмма поможет восстановить внутренние ресурсы и почувствовать себя живым.');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_booking_id` (`status_booking_id`),
  ADD KEY `booking_ibfk_2` (`room_id`),
  ADD KEY `pay_type_id` (`pay_type_id`),
  ADD KEY `payment_status` (`payment_status`);

--
-- Индексы таблицы `booking_user`
--
ALTER TABLE `booking_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`resident_id`);

--
-- Индексы таблицы `guest_profile`
--
ALTER TABLE `guest_profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Индексы таблицы `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `payment_status`
--
ALTER TABLE `payment_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pay_type`
--
ALTER TABLE `pay_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `raiting`
--
ALTER TABLE `raiting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `route_id` (`route_id`);

--
-- Индексы таблицы `reason`
--
ALTER TABLE `reason`
  ADD PRIMARY KEY (`booking_id`);

--
-- Индексы таблицы `resident`
--
ALTER TABLE `resident`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `wellness_program_id` (`wellness_program_id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_type_id` (`room_type_id`),
  ADD KEY `status_room_id` (`status_room_id`),
  ADD KEY `floor_id` (`floor`);

--
-- Индексы таблицы `room_image`
--
ALTER TABLE `room_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_type_id` (`room_id`);

--
-- Индексы таблицы `room_type`
--
ALTER TABLE `room_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level_id` (`level_id`);

--
-- Индексы таблицы `route_image`
--
ALTER TABLE `route_image`
  ADD PRIMARY KEY (`route_id`);

--
-- Индексы таблицы `route_resident`
--
ALTER TABLE `route_resident`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resident_id` (`resident_id`),
  ADD KEY `route_id` (`route_id`);

--
-- Индексы таблицы `status_booking`
--
ALTER TABLE `status_booking`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `status_room`
--
ALTER TABLE `status_room`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Индексы таблицы `wellness_image`
--
ALTER TABLE `wellness_image`
  ADD PRIMARY KEY (`wellness_id`);

--
-- Индексы таблицы `wellness_program`
--
ALTER TABLE `wellness_program`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT для таблицы `booking_user`
--
ALTER TABLE `booking_user`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT для таблицы `guest_profile`
--
ALTER TABLE `guest_profile`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `level`
--
ALTER TABLE `level`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `payment_status`
--
ALTER TABLE `payment_status`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `pay_type`
--
ALTER TABLE `pay_type`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `raiting`
--
ALTER TABLE `raiting`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `reason`
--
ALTER TABLE `reason`
  MODIFY `booking_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT для таблицы `resident`
--
ALTER TABLE `resident`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `room`
--
ALTER TABLE `room`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `room_image`
--
ALTER TABLE `room_image`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `room_type`
--
ALTER TABLE `room_type`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `route`
--
ALTER TABLE `route`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `route_image`
--
ALTER TABLE `route_image`
  MODIFY `route_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `route_resident`
--
ALTER TABLE `route_resident`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `status_booking`
--
ALTER TABLE `status_booking`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `status_room`
--
ALTER TABLE `status_room`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `wellness_image`
--
ALTER TABLE `wellness_image`
  MODIFY `wellness_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `wellness_program`
--
ALTER TABLE `wellness_program`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`status_booking_id`) REFERENCES `status_booking` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_4` FOREIGN KEY (`pay_type_id`) REFERENCES `pay_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_5` FOREIGN KEY (`payment_status`) REFERENCES `payment_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `booking_user`
--
ALTER TABLE `booking_user`
  ADD CONSTRAINT `booking_user_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_user_ibfk_2` FOREIGN KEY (`resident_id`) REFERENCES `resident` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `guest_profile`
--
ALTER TABLE `guest_profile`
  ADD CONSTRAINT `guest_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `raiting`
--
ALTER TABLE `raiting`
  ADD CONSTRAINT `raiting_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `raiting_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `route` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `reason`
--
ALTER TABLE `reason`
  ADD CONSTRAINT `reason_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `resident`
--
ALTER TABLE `resident`
  ADD CONSTRAINT `resident_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `resident_ibfk_3` FOREIGN KEY (`wellness_program_id`) REFERENCES `wellness_program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_ibfk_2` FOREIGN KEY (`status_room_id`) REFERENCES `status_room` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `room_image`
--
ALTER TABLE `room_image`
  ADD CONSTRAINT `room_image_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `route`
--
ALTER TABLE `route`
  ADD CONSTRAINT `route_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `route_image`
--
ALTER TABLE `route_image`
  ADD CONSTRAINT `route_image_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `route` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `route_resident`
--
ALTER TABLE `route_resident`
  ADD CONSTRAINT `route_resident_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `resident` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `route_resident_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `route` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `wellness_image`
--
ALTER TABLE `wellness_image`
  ADD CONSTRAINT `wellness_image_ibfk_1` FOREIGN KEY (`wellness_id`) REFERENCES `wellness_program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
