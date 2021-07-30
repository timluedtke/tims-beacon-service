-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Server Version: 5.5.55-0+deb8u1
-- PHP-Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `tim_beacon`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `beacons`
--

CREATE TABLE IF NOT EXISTS `beacons` (
`b_id` int(11) NOT NULL COMMENT 'primarykey',
  `b_datetime_tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `b_devicename` varchar(255) NOT NULL,
  `b_ip` varchar(100) NOT NULL COMMENT 'public IP',
  `b_privateip` varchar(100) DEFAULT NULL,
  `b_cputemp` varchar(255) DEFAULT NULL COMMENT 'CPU-Temperature',
  `b_payload` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14989 DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `beacons`
--
ALTER TABLE `beacons`
 ADD PRIMARY KEY (`b_id`), ADD KEY `b_datetime_tstamp` (`b_datetime_tstamp`), ADD KEY `b_devicename` (`b_devicename`), ADD KEY `b_datetime_tstamp_2` (`b_datetime_tstamp`,`b_devicename`), ADD KEY `b_id` (`b_id`), ADD KEY `b_id_2` (`b_id`), ADD KEY `b_id_3` (`b_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `beacons`
--
ALTER TABLE `beacons`
MODIFY `b_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primarykey',AUTO_INCREMENT=14989;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
