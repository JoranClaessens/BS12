-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2016 at 06:38 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bassec`
--

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

DROP TABLE IF EXISTS `download`;
CREATE TABLE `download` (
  `id` int(11) NOT NULL,
  `id_from` int(11) NOT NULL,
  `id_to` int(11) NOT NULL,
  `bestand` varchar(12) NOT NULL,
  `type` varchar(5) NOT NULL,
  `available` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `li_user`
--

DROP TABLE IF EXISTS `li_user`;
CREATE TABLE `li_user` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(20) NOT NULL,
  `remember_me` varchar(40) DEFAULT NULL,
  `remember_me_ip` varchar(45) DEFAULT NULL,
  `voornaam` varchar(30) NOT NULL,
  `familienaam` varchar(30) NOT NULL,
  `RSA_public` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `li_user`
--

INSERT INTO `li_user` (`id`, `username`, `password`, `salt`, `remember_me`, `remember_me_ip`, `voornaam`, `familienaam`, `RSA_public`) VALUES
(1, 'Rudy Mas', 'f6c26fd69e1b8ea6ba1060cca2805bec38f5905d', 'GewugMThHD6HmhhV3FYI', 'jMyjX8uqsOg9agZHWsgN6S9Ev', '::1', 'Rudy', 'Mas', '<RSAKeyValue>\r\n  <Modulus>6Hdbv14GVInzZd6m39kgsTw5aiRP9xBWRkV+p0/Jf94Z0ZUt0RdWiiJH2z+VL0Vo5iMI24AQ2YT4HVSUQGSCm8bw2VbMtgCNxalN8CmHqPTflHkqan3e5CZSvI3krcI0FHAOMwLtdYBqUIl6xprHwLd7EgVa7fdhcVDlpwck9rJZnS7z6WkcWwa+E42rrTH5NSxS6JA2UUNCNacNvdnupBO8nhX8GLJcpVFnMavBXNssiosO1/3uKZ6myOaYbkAIonq2p9Y0ObcWwycMC0l3eg0w495vBbbigeRymGULZXhKYG3qLKOkWIZRG61tdi8+SgTza0dEIEfW/uFLed8cDQ==</Modulus>\r\n  <Exponent>AQAB</Exponent>\r\n</RSAKeyValue>'),
(2, 'Bronco', '4b15f770fe994ac557312216ceeae6a651faa31c', 'YHyY5KNaUVj5pPO74xv3', '', '', 'Peter', 'Meisters', '<RSAKeyValue>\r\n  <Modulus>tukBmnjoe9zY/MGbuwbq86xW3sky8jistDeYjyFL9GCwybvGPNopbtB83Rzc2rNgc3qv7AQ60p5NJgRzDsVQ+9fCuBr4JxzS1p8+VoB3Lz7Lr4SBCEnkd7EpeRuq6stRRxBU42WGJO3Z+owESNPWZ/wzObOr/0NJjg0U66tKj/mhI8mvjYyv3NPX65lhzTKRhceiTBpUEFKWudm8McktIzeY5mGyeXlC3z551j9rEInMXKD2Vcru8r/1VcucTsDnQ26oXWSZ7+zDQ0/3iho8pRHehKOex+AJvL66oRU9Z/8zx9l1nGiBoFfDx2iW5KGXwuIGIGUssdSx6Cz/Gw6PTQ==</Modulus>\r\n  <Exponent>AQAB</Exponent>\r\n</RSAKeyValue>');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `download`
--
ALTER TABLE `download`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `li_user`
--
ALTER TABLE `li_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `download`
--
ALTER TABLE `download`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `li_user`
--
ALTER TABLE `li_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
