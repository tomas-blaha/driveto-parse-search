-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 25. pro 2017, 17:26
-- Verze serveru: 10.1.19-MariaDB
-- Verze PHP: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `driveto_mock`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `car`
--

CREATE TABLE `car` (
  `id` int(255) NOT NULL,
  `make_id` int(255) NOT NULL,
  `model_id` int(11) NOT NULL,
  `trim_id` int(11) NOT NULL,
  `engine_id` int(11) NOT NULL,
  `list_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `car`
--


-- --------------------------------------------------------

--
-- Struktura tabulky `engine`
--

CREATE TABLE `engine` (
  `id` int(255) NOT NULL,
  `make_id` int(255) NOT NULL,
  `volume` decimal(10,2) NOT NULL,
  `power` int(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `engine`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `make`
--

CREATE TABLE `make` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `make`
--
-- --------------------------------------------------------

--
-- Struktura tabulky `model`
--

CREATE TABLE `model` (
  `id` int(255) NOT NULL,
  `make_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `line` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `model`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `offer`
--

CREATE TABLE `offer` (
  `id` int(255) NOT NULL,
  `car_id` int(255) NOT NULL,
  `price` float NOT NULL,
  `vat_rate` int(8) NOT NULL,
  `partner_offer_id` varchar(128) NOT NULL,
  `package` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabulky `trim`
--

CREATE TABLE `trim` (
  `id` int(255) NOT NULL,
  `make_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `trim`
--

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`),
  ADD KEY `make_id` (`make_id`),
  ADD KEY `model_id` (`model_id`),
  ADD KEY `engine_id` (`engine_id`);

--
-- Klíče pro tabulku `engine`
--
ALTER TABLE `engine`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `make`
--
ALTER TABLE `make`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `model`
--
ALTER TABLE `model`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Klíče pro tabulku `trim`
--
ALTER TABLE `trim`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `car`
--
ALTER TABLE `car`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pro tabulku `engine`
--
ALTER TABLE `engine`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pro tabulku `make`
--
ALTER TABLE `make`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pro tabulku `model`
--
ALTER TABLE `model`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pro tabulku `offer`
--
ALTER TABLE `offer`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pro tabulku `trim`
--
ALTER TABLE `trim`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `car_ibfk_1` FOREIGN KEY (`make_id`) REFERENCES `make` (`id`),
  ADD CONSTRAINT `car_ibfk_2` FOREIGN KEY (`model_id`) REFERENCES `model` (`id`),
  ADD CONSTRAINT `car_ibfk_3` FOREIGN KEY (`engine_id`) REFERENCES `engine` (`id`);

--
-- Omezení pro tabulku `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `offer_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
