-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2018 at 11:47 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ordinacija`
--

-- --------------------------------------------------------

--
-- Table structure for table `adresa`
--

CREATE TABLE `adresa` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_ulica` int(11) UNSIGNED NOT NULL,
  `broj` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `id_grad` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `adresa`
--

INSERT INTO `adresa` (`id`, `id_ulica`, `broj`, `id_grad`) VALUES
(1, 1, '4', 1),
(2, 2, '37/A', 1),
(3, 3, '16', 1),
(5, 5, '62/b', 3);

-- --------------------------------------------------------

--
-- Table structure for table `grad`
--

CREATE TABLE `grad` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grad`
--

INSERT INTO `grad` (`id`, `naziv`) VALUES
(1, 'Beograd'),
(3, 'Novi Sad'),
(4, 'NiÅ¡'),
(5, 'Sremska Mitrovica');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'vestacki id',
  `korisnik` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'korisnicko ime',
  `lozinka` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'korisnicka lozinka',
  `id_vrsta_kor` int(10) UNSIGNED NOT NULL,
  `ime_prezime` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_adresa` int(10) UNSIGNED NOT NULL,
  `telefon` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `korisnik`, `lozinka`, `id_vrsta_kor`, `ime_prezime`, `id_adresa`, `telefon`) VALUES
(3, 'ljubomir', '$2y$10$UmjqH7QpNGHSBEQrXQ8K0O/QerL4unxTd01kK3cUgo4ulq77bZZN6', 2, 'dr Ljubomir Brmbolic', 2, '064/5647889'),
(4, 'pera', '$2y$10$kZee38n9X/2Fmr0UK.1Jw.PFz1REniAHpc9w94nqHBeH5SYzUEUf.', 3, 'Pera Peric', 3, '063/3167982'),
(11, 'mirko', '$2y$10$KhatAdP2i37/ayD20XStq.QvoyYY10AVtOFHmnCA2VCFyZQL798AS', 3, 'Mirko Mirkovic', 2, '064/555333'),
(13, 'admin', '$2y$10$okUWlZFbik/qO4t0NK3y7eVfjcoJAn8RDiJntcIL1ospA5E5ztmE.', 1, 'lj b', 2, '064/7804485'),
(14, 'marko', '$2y$10$V5s6SU/z9C7.Jw6N.8KkouvWQN6OECDpm0zEqIZ6VGw7QYoRtYMe6', 2, 'dr Marko Markovic', 2, '063/8529634');

-- --------------------------------------------------------

--
-- Table structure for table `pregled_usluga`
--

CREATE TABLE `pregled_usluga` (
  `id_zakazani_pregled` int(10) UNSIGNED NOT NULL,
  `id_zakazana_usluga` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pregled_usluga`
--

INSERT INTO `pregled_usluga` (`id_zakazani_pregled`, `id_zakazana_usluga`) VALUES
(47, 1),
(47, 2),
(47, 3),
(47, 4),
(56, 3),
(56, 4),
(56, 1),
(56, 2),
(59, 1),
(59, 2),
(59, 3),
(59, 4),
(61, 5),
(61, 6),
(61, 7),
(60, 4),
(60, 1),
(60, 25),
(60, 14);

-- --------------------------------------------------------

--
-- Table structure for table `ulica`
--

CREATE TABLE `ulica` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ulica`
--

INSERT INTO `ulica` (`id`, `naziv`) VALUES
(1, 'UgrinovaÄka'),
(2, 'DobanovaÄka'),
(3, 'Nemanjina'),
(5, 'DobrovoljaÄka');

-- --------------------------------------------------------

--
-- Table structure for table `usluga`
--

CREATE TABLE `usluga` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(50) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL,
  `id_vrsta_usluge` int(10) UNSIGNED NOT NULL,
  `trajanje` int(10) UNSIGNED NOT NULL COMMENT 'u sekundama',
  `cena` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `usluga`
--

INSERT INTO `usluga` (`id`, `naziv`, `id_vrsta_usluge`, `trajanje`, `cena`) VALUES
(1, 'StomatoloÅ¡ki pregled', 1, 600, 1100),
(2, 'Redovna kontrola sa UZK ', 1, 900, 1700),
(3, 'Prva pomoÄ‡', 1, 900, 1500),
(4, 'SpecijalistiÄki pregled i konsultacije', 1, 1200, 2000),
(5, 'Uklanjanje mekih naslaga', 2, 600, 1000),
(6, 'VaÄ‘enje mleÄnog zuba', 2, 300, 1000),
(7, 'Zalivanje fisura', 2, 1800, 2500),
(8, 'Terapija dubokog karijesa', 3, 1800, 1700),
(9, 'Kompozitni ispun jednopovrÅ¡inski', 3, 1800, 2500),
(10, 'Kompozitni ispun dvopovrÅ¡inski', 3, 2400, 3000),
(11, 'Kompozitni ispun tropovrÅ¡inski', 3, 3000, 3500),
(12, 'LeÄenje gangrene, jedna seansa', 3, 1800, 1500),
(14, 'Rutinsko vaÄ‘enje zuba', 4, 900, 2400),
(15, 'Komplikovano vaÄ‘enje', 4, 1800, 6000),
(16, 'HirurÅ¡ko vaÄ‘enje zuba', 4, 3600, 12000),
(17, 'Komplikovano hirurÅ¡ko vaÄ‘enje zuba', 4, 5400, 20000),
(18, 'Uklanjanje kamenca i poliranje', 5, 1200, 3000),
(19, 'Obrada parodontalnog dÅ¾epa', 5, 900, 1700),
(20, 'MenadÅ¾ment mekog tkiva po zubu', 5, 1800, 2600),
(21, 'Skidanje krunice', 6, 600, 1100),
(22, 'ZaÅ¡titna krunica', 6, 900, 2000),
(23, 'Reparatura', 6, 1800, 4000),
(24, 'Livena nadogradnja', 6, 1500, 2000),
(25, 'Revizija punjenja', 3, 2100, 3300);

-- --------------------------------------------------------

--
-- Table structure for table `vrsta_kor`
--

CREATE TABLE `vrsta_kor` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vrsta_kor`
--

INSERT INTO `vrsta_kor` (`id`, `naziv`) VALUES
(1, 'admin'),
(2, 'doktor'),
(3, 'pacijent');

-- --------------------------------------------------------

--
-- Table structure for table `vrsta_usluge`
--

CREATE TABLE `vrsta_usluge` (
  `id` int(10) UNSIGNED NOT NULL,
  `naziv` varchar(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vrsta_usluge`
--

INSERT INTO `vrsta_usluge` (`id`, `naziv`) VALUES
(1, 'Pregledi'),
(2, 'DeÄija i preventivna stomatologija'),
(3, 'Bolesti zuba'),
(4, 'Oralna hirurgija'),
(5, 'Paradontologija'),
(6, 'Protetika');

-- --------------------------------------------------------

--
-- Table structure for table `zakazani_pregled`
--

CREATE TABLE `zakazani_pregled` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_korisnika` int(10) UNSIGNED NOT NULL,
  `id_doktora` int(10) UNSIGNED NOT NULL,
  `datum_vreme_start` datetime NOT NULL,
  `datum_vreme_kraj` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zakazani_pregled`
--

INSERT INTO `zakazani_pregled` (`id`, `id_korisnika`, `id_doktora`, `datum_vreme_start`, `datum_vreme_kraj`) VALUES
(47, 11, 3, '2018-08-22 18:00:00', '2018-08-22 19:05:00'),
(56, 4, 14, '2018-08-21 12:00:00', '2018-08-21 13:05:00'),
(59, 11, 3, '2018-08-20 13:25:00', '2018-08-20 14:30:00'),
(60, 4, 14, '2018-08-23 12:00:00', '2018-08-23 13:25:00'),
(61, 4, 3, '2018-08-28 12:15:00', '2018-08-28 13:05:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adresa`
--
ALTER TABLE `adresa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ulica` (`id_ulica`),
  ADD KEY `id_grad` (`id_grad`);

--
-- Indexes for table `grad`
--
ALTER TABLE `grad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vrsta_kor` (`id_vrsta_kor`),
  ADD KEY `id_adresa` (`id_adresa`);

--
-- Indexes for table `pregled_usluga`
--
ALTER TABLE `pregled_usluga`
  ADD KEY `id_zakazana_usluga` (`id_zakazana_usluga`),
  ADD KEY `pregled_usluga_ibfk_1` (`id_zakazani_pregled`);

--
-- Indexes for table `ulica`
--
ALTER TABLE `ulica`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usluga`
--
ALTER TABLE `usluga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_vrsta_usluge` (`id_vrsta_usluge`);

--
-- Indexes for table `vrsta_kor`
--
ALTER TABLE `vrsta_kor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vrsta_usluge`
--
ALTER TABLE `vrsta_usluge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zakazani_pregled`
--
ALTER TABLE `zakazani_pregled`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_doktora` (`id_doktora`),
  ADD KEY `id_korisnika` (`id_korisnika`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adresa`
--
ALTER TABLE `adresa`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `grad`
--
ALTER TABLE `grad`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'vestacki id', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ulica`
--
ALTER TABLE `ulica`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usluga`
--
ALTER TABLE `usluga`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `vrsta_kor`
--
ALTER TABLE `vrsta_kor`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vrsta_usluge`
--
ALTER TABLE `vrsta_usluge`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `zakazani_pregled`
--
ALTER TABLE `zakazani_pregled`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adresa`
--
ALTER TABLE `adresa`
  ADD CONSTRAINT `adresa_ibfk_1` FOREIGN KEY (`id_ulica`) REFERENCES `ulica` (`id`),
  ADD CONSTRAINT `adresa_ibfk_2` FOREIGN KEY (`id_grad`) REFERENCES `grad` (`id`);

--
-- Constraints for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD CONSTRAINT `korisnik_ibfk_1` FOREIGN KEY (`id_vrsta_kor`) REFERENCES `vrsta_kor` (`id`),
  ADD CONSTRAINT `korisnik_ibfk_2` FOREIGN KEY (`id_adresa`) REFERENCES `adresa` (`id`);

--
-- Constraints for table `pregled_usluga`
--
ALTER TABLE `pregled_usluga`
  ADD CONSTRAINT `pregled_usluga_ibfk_1` FOREIGN KEY (`id_zakazani_pregled`) REFERENCES `zakazani_pregled` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pregled_usluga_ibfk_2` FOREIGN KEY (`id_zakazana_usluga`) REFERENCES `usluga` (`id`);

--
-- Constraints for table `usluga`
--
ALTER TABLE `usluga`
  ADD CONSTRAINT `usluga_ibfk_1` FOREIGN KEY (`id_vrsta_usluge`) REFERENCES `vrsta_usluge` (`id`);

--
-- Constraints for table `zakazani_pregled`
--
ALTER TABLE `zakazani_pregled`
  ADD CONSTRAINT `zakazani_pregled_ibfk_1` FOREIGN KEY (`id_doktora`) REFERENCES `korisnik` (`id`),
  ADD CONSTRAINT `zakazani_pregled_ibfk_2` FOREIGN KEY (`id_korisnika`) REFERENCES `korisnik` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
