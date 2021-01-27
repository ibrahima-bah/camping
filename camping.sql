-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 27, 2021 at 12:17 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `camping`
--

-- --------------------------------------------------------

--
-- Table structure for table `avis`
--

CREATE TABLE `avis` (
  `id_avis` int(11) NOT NULL,
  `notre_sejour` int(11) NOT NULL,
  `titre_avis` varchar(60) NOT NULL,
  `texte_avis` varchar(600) NOT NULL,
  `post_date` date NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `avis`
--

INSERT INTO `avis` (`id_avis`, `notre_sejour`, `titre_avis`, `texte_avis`, `post_date`, `id_utilisateur`, `id_reservation`) VALUES
(1, 5, 'au top', 'ce cemping est trop bien', '2021-01-19', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `detail_lieux`
--

CREATE TABLE `detail_lieux` (
  `id_detail_lieu` int(11) NOT NULL,
  `nom_lieu` varchar(255) NOT NULL,
  `prix_journalier` decimal(10,0) NOT NULL,
  `id_reservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `detail_lieux`
--

INSERT INTO `detail_lieux` (`id_detail_lieu`, `nom_lieu`, `prix_journalier`, `id_reservation`) VALUES
(1, 'maquis', '10', 2),
(2, 'la plage', '10', 3),
(3, 'les goudes', '10', 4),
(4, 'la plage', '10', 5),
(5, 'la plage', '10', 6),
(6, 'les goudes', '10', 7),
(7, 'la plage', '10', 8),
(8, 'la plage', '10', 9),
(9, 'les goudes', '10', 10),
(10, 'terrasse', '12', 11),
(11, 'la plage', '10', 12),
(12, 'la plage', '10', 13),
(13, 'la plage', '10', 14),
(14, 'les goudes', '10', 15),
(15, 'la plage', '10', 16),
(16, 'les goudes', '10', 17),
(17, 'la plage', '10', 18),
(18, 'les goudes', '10', 19);

-- --------------------------------------------------------

--
-- Table structure for table `detail_options`
--

CREATE TABLE `detail_options` (
  `id_detail_option` int(11) NOT NULL,
  `nom_option` varchar(255) NOT NULL,
  `prix_option` decimal(10,0) NOT NULL,
  `id_reservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `detail_options`
--

INSERT INTO `detail_options` (`id_detail_option`, `nom_option`, `prix_option`, `id_reservation`) VALUES
(1, 'danse', '5', 2),
(2, 'zumba', '5', 3),
(3, 'velo', '30', 4),
(4, 'velo', '30', 5),
(5, 'zumba', '17', 6),
(6, 'boite de nuit', '2', 7),
(7, 'velo', '30', 8),
(8, 'boite de nuit', '2', 9),
(9, 'zumba', '17', 10),
(10, 'zumba', '17', 11),
(11, 'sans option', '0', 12),
(12, 'velo', '30', 13),
(13, 'sans option', '0', 14),
(14, 'velo', '30', 15),
(15, 'sans option', '0', 16),
(16, 'sans option', '0', 17),
(17, 'sans option', '0', 18),
(18, 'zumba', '17', 19);

-- --------------------------------------------------------

--
-- Table structure for table `detail_types_emplacement`
--

CREATE TABLE `detail_types_emplacement` (
  `id_detail_type_emplacement` int(11) NOT NULL,
  `nom_type_emplacement` varchar(255) NOT NULL,
  `nb_emplacements_reserves` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `detail_types_emplacement`
--

INSERT INTO `detail_types_emplacement` (`id_detail_type_emplacement`, `nom_type_emplacement`, `nb_emplacements_reserves`, `id_reservation`) VALUES
(8, 'tente', 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `lieux`
--

CREATE TABLE `lieux` (
  `id_lieu` int(11) NOT NULL,
  `nom_lieu` varchar(150) NOT NULL,
  `emplacements_disponibles` int(11) NOT NULL,
  `prix_journalier` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lieux`
--

INSERT INTO `lieux` (`id_lieu`, `nom_lieu`, `emplacements_disponibles`, `prix_journalier`) VALUES
(1, 'la plage', 4, '10'),
(2, 'les goudes', 4, '10'),
(3, 'terrasse', 4, '10');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `id_newsletter` int(11) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id_option` int(11) NOT NULL,
  `nom_option` varchar(150) NOT NULL,
  `prix_option` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id_option`, `nom_option`, `prix_option`) VALUES
(2, 'boite de nuit', '2'),
(3, 'zumba', '17'),
(4, 'velo', '30');

-- --------------------------------------------------------

--
-- Table structure for table `prix_detail`
--

CREATE TABLE `prix_detail` (
  `id_prix_detail` int(11) NOT NULL,
  `nb_emplacement` int(11) NOT NULL,
  `prix_journalier` decimal(10,0) NOT NULL,
  `prix_options` decimal(10,0) NOT NULL,
  `nb_jours` int(11) NOT NULL,
  `prix_total` decimal(10,0) NOT NULL,
  `id_reservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prix_detail`
--

INSERT INTO `prix_detail` (`id_prix_detail`, `nb_emplacement`, `prix_journalier`, `prix_options`, `nb_jours`, `prix_total`, `id_reservation`) VALUES
(2, 2, '30', '47', 1, '154', 8);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `date_debut`, `date_fin`, `id_utilisateur`) VALUES
(1, '2021-01-20', '2021-01-21', 1),
(2, '2021-01-27', '2021-01-29', 2),
(3, '2021-02-17', '2021-02-19', 3),
(4, '2021-01-26', '2021-01-28', 5),
(5, '2021-02-18', '2021-02-19', 5),
(6, '2021-01-27', '2021-01-28', 5),
(7, '2021-02-10', '2021-02-12', 5),
(8, '2021-02-24', '2021-02-26', 5),
(9, '2021-02-03', '2021-02-04', 5),
(10, '2021-01-27', '2021-01-28', 5),
(11, '2021-01-27', '2021-01-28', 5),
(12, '2021-01-28', '2021-01-29', 5),
(13, '2021-01-28', '2021-01-29', 5),
(14, '2021-01-25', '2021-01-26', 5),
(15, '2021-01-27', '2021-01-28', 5),
(16, '2021-01-27', '2021-01-28', 5),
(17, '2021-01-27', '2021-01-28', 5),
(18, '2021-01-27', '2021-01-28', 5),
(19, '2021-01-26', '2021-01-27', 5);

-- --------------------------------------------------------

--
-- Table structure for table `types_emplacement`
--

CREATE TABLE `types_emplacement` (
  `id_type_emplacement` int(11) NOT NULL,
  `nom_type_emplacement` varchar(150) NOT NULL,
  `nb_emplacements` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `types_emplacement`
--

INSERT INTO `types_emplacement` (`id_type_emplacement`, `nom_type_emplacement`, `nb_emplacements`) VALUES
(1, 'tente', 1),
(2, 'camping', 2);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(55) NOT NULL,
  `prenom` varchar(55) NOT NULL,
  `email` varchar(100) NOT NULL,
  `register_date` datetime NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` int(11) NOT NULL,
  `num_tel` varchar(60) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `register_date`, `password`, `is_admin`, `num_tel`, `gender`, `avatar`) VALUES
(2, 'yaya', 'zaza', 'yahoo@yahoo.fr', '2021-01-14 14:45:28', '$2y$10$k56AUPW5X5NAVl/FwUSc2eI8nyLUxaRPBudJvsLNeyxouETnxHR1S', 0, '0615421545', 'Homme', 'images/op.jpg'),
(3, 'barry', 'zlatan', 'gmail@gmail.com', '2021-01-19 13:47:27', '$2y$10$R.Mx1W2Q5t7kZ91f.HcTZuTEd5ELQjve.hs27l2vqYK1raKGY0PlC', 0, '0645124512', 'Homme', 'images/op.jpg'),
(4, 'admin', 'admin', 'admin@admin.fr', '2021-01-19 14:56:06', '$2y$10$xHBGqhnLM32kpNmJaCa34OCqT5iaUQBqkDsfZ/W.gl/x8a4.6fXku', 1, '0000000000', 'Non genré', 'images/op.jpg'),
(5, 'pipi', 'zizi', 'caca@caca.fr', '2021-01-22 14:57:35', '$2y$10$4F4pUvE3cVxXBpYk1o91f.m3hnLdlKQuQd5.g/f3FyrI1cSwdR1oG', 0, '0652124512', 'Homme', 'images/op.jpg'),
(6, 'bah', 'alex', 'tuilletcatalant@yahoo.fr', '2021-01-26 12:03:34', '$2y$10$/V/0oJgeB/2CKDCYI60e6uPaVdMaoxjEGM6mtCYk4RNI7jH888GQ6', 0, '0660166869', 'Homme', 'images/no-image.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id_avis`);

--
-- Indexes for table `detail_lieux`
--
ALTER TABLE `detail_lieux`
  ADD PRIMARY KEY (`id_detail_lieu`);

--
-- Indexes for table `detail_options`
--
ALTER TABLE `detail_options`
  ADD PRIMARY KEY (`id_detail_option`);

--
-- Indexes for table `detail_types_emplacement`
--
ALTER TABLE `detail_types_emplacement`
  ADD UNIQUE KEY `id_detail_type_emplacement` (`id_detail_type_emplacement`);

--
-- Indexes for table `lieux`
--
ALTER TABLE `lieux`
  ADD PRIMARY KEY (`id_lieu`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id_newsletter`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id_option`);

--
-- Indexes for table `prix_detail`
--
ALTER TABLE `prix_detail`
  ADD PRIMARY KEY (`id_prix_detail`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`);

--
-- Indexes for table `types_emplacement`
--
ALTER TABLE `types_emplacement`
  ADD PRIMARY KEY (`id_type_emplacement`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avis`
--
ALTER TABLE `avis`
  MODIFY `id_avis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detail_lieux`
--
ALTER TABLE `detail_lieux`
  MODIFY `id_detail_lieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `detail_options`
--
ALTER TABLE `detail_options`
  MODIFY `id_detail_option` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `lieux`
--
ALTER TABLE `lieux`
  MODIFY `id_lieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id_newsletter` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id_option` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prix_detail`
--
ALTER TABLE `prix_detail`
  MODIFY `id_prix_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `types_emplacement`
--
ALTER TABLE `types_emplacement`
  MODIFY `id_type_emplacement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
