-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 24, 2025 at 08:09 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bi-pharma`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_client` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_client` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_pharmacie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temps` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `nom_client`, `contact_client`, `code_pharmacie`, `code`, `temps`) VALUES
(1, 'NGONGA', '0820908486', '265bb1104d5f72da5046d91298cf046c', '4db944d7830d8b7836988d7f3bbcff47', 1750779477),
(2, 'lukielo', '0823147963', '265bb1104d5f72da5046d91298cf046c', '797b61f6345839c58f16729c463f2227', 1750779614),
(3, 'ngoma', '0899266979', '265bb1104d5f72da5046d91298cf046c', '3c7a52da32bfcc249c50bfe0d748c574', 1750779954),
(4, 'Leonard Yubu', '0859227635', '265bb1104d5f72da5046d91298cf046c', '27607d4f02e619eee7121cb16dc60a72', 1750793306);

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_demandeur` int NOT NULL,
  `code_depot` int NOT NULL,
  `date` int NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temps` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employe`
--

DROP TABLE IF EXISTS `employe`;
CREATE TABLE IF NOT EXISTS `employe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_pharmacie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temps` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employe`
--

INSERT INTO `employe` (`id`, `nom`, `email`, `role`, `mdp`, `code_pharmacie`, `telephone`, `code`, `temps`) VALUES
(3, 'Clark Mbianga', 'clarckmbianga@willpharma.com', 'pharmacien', '$2y$10$Tw2C/tBvpd.vZfURrUdGLOBFziAtC3Q6..Fe2Yq9CJ/.hkV5GTFIa', '824b406200fe32afb6324f1e8a338ebf', '0899266979', '952e0f7aba2a4dfa025b0c3420163dfa', 1750686844),
(2, 'Master Thsims', 'thims@willpharma.com', 'caissier', '$2y$10$ZVy/aKFipBrcKiiPf12mk.EyGAK31q3vc8EXgq4E1KJ/rA6e8K9oG', '824b406200fe32afb6324f1e8a338ebf', '0854176257', '06d1a0b0c2e6d9e2ae13c02380f546f1', 1750684002),
(4, 'Niclette Yubu', 'nickyubu@willpharma.com', 'caissier', '$2y$10$/KLojLQlNLyMtGC3XDfJ2.97vEYvsoCVOtdclQdLW9CXzDzA6AyiK', '265bb1104d5f72da5046d91298cf046c', '0823147463', '559a5915139d87aaf40e5fdbe0bfc46c', 1750689765);

-- --------------------------------------------------------

--
-- Table structure for table `factures`
--

DROP TABLE IF EXISTS `factures`;
CREATE TABLE IF NOT EXISTS `factures` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_client` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int NOT NULL,
  `code_utilisateur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_pharmacie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temps` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `factures`
--

INSERT INTO `factures` (`id`, `num_client`, `total`, `code_utilisateur`, `code_pharmacie`, `code`, `temps`) VALUES
(1, '0820908486', 18800, '559a5915139d87aaf40e5fdbe0bfc46c', '265bb1104d5f72da5046d91298cf046c', '1d849ddcdabfcb9599377fce99198fad', 1750779477),
(2, '0820908486', 9600, '559a5915139d87aaf40e5fdbe0bfc46c', '265bb1104d5f72da5046d91298cf046c', '043d89fd493e7be65371de09d5b58dc8', 1750779506),
(3, '0820908486', 6400, '559a5915139d87aaf40e5fdbe0bfc46c', '265bb1104d5f72da5046d91298cf046c', 'b02821186045ce31d146f7518caaf0b3', 1750779530),
(4, '0820908486', 26000, '559a5915139d87aaf40e5fdbe0bfc46c', '265bb1104d5f72da5046d91298cf046c', '03312eeb24818ae5e644d0bc05ee3cc8', 1750779584),
(5, '0823147963', 16400, '559a5915139d87aaf40e5fdbe0bfc46c', '265bb1104d5f72da5046d91298cf046c', '67220fe015e47659bd0608fd06f6cc4a', 1750779614),
(6, '0820908486', 22000, '559a5915139d87aaf40e5fdbe0bfc46c', '265bb1104d5f72da5046d91298cf046c', '30b77b91c52c1098af32c50573bca451', 1750779633),
(7, '0899266979', 3200, '559a5915139d87aaf40e5fdbe0bfc46c', '265bb1104d5f72da5046d91298cf046c', '34dfef095a5723fbddcd564d2f66fb60', 1750779954),
(8, '0820908486', 14900, '952e0f7aba2a4dfa025b0c3420163dfa', '824b406200fe32afb6324f1e8a338ebf', 'd634c873db0ac7fe2d94b5a846f66c6d', 1750780674),
(9, '0820908486', 6000, '559a5915139d87aaf40e5fdbe0bfc46c', '265bb1104d5f72da5046d91298cf046c', '905b5ad992f946561027e06263ca478c', 1750783273),
(10, '0859227635', 23200, '559a5915139d87aaf40e5fdbe0bfc46c', '265bb1104d5f72da5046d91298cf046c', '212a25d327c8749e7de17c5b337be768', 1750793306);

-- --------------------------------------------------------

--
-- Table structure for table `facture_produit`
--

DROP TABLE IF EXISTS `facture_produit`;
CREATE TABLE IF NOT EXISTS `facture_produit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_facture` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_produit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` int NOT NULL,
  `prix` int NOT NULL,
  `nom_produit` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facture_produit`
--

INSERT INTO `facture_produit` (`id`, `code_facture`, `code_produit`, `quantite`, `prix`, `nom_produit`) VALUES
(1, '1d849ddcdabfcb9599377fce99198fad', 'cc205c31042a4e78b40a168918a9dad0', 4, 3200, 'Doliprane'),
(2, '1d849ddcdabfcb9599377fce99198fad', '8a6c6b7db0525d88bd7e0a7cc43adf71', 3, 2000, 'Aspirine'),
(3, '043d89fd493e7be65371de09d5b58dc8', 'cc205c31042a4e78b40a168918a9dad0', 3, 3200, 'Doliprane'),
(4, 'b02821186045ce31d146f7518caaf0b3', 'cc205c31042a4e78b40a168918a9dad0', 2, 3200, 'Doliprane'),
(5, '03312eeb24818ae5e644d0bc05ee3cc8', 'cc205c31042a4e78b40a168918a9dad0', 5, 3200, 'Doliprane'),
(6, '03312eeb24818ae5e644d0bc05ee3cc8', '8a6c6b7db0525d88bd7e0a7cc43adf71', 5, 2000, 'Aspirine'),
(7, '67220fe015e47659bd0608fd06f6cc4a', '8a6c6b7db0525d88bd7e0a7cc43adf71', 5, 2000, 'Aspirine'),
(8, '67220fe015e47659bd0608fd06f6cc4a', 'cc205c31042a4e78b40a168918a9dad0', 2, 3200, 'Doliprane'),
(9, '30b77b91c52c1098af32c50573bca451', 'cc205c31042a4e78b40a168918a9dad0', 5, 3200, 'Doliprane'),
(10, '30b77b91c52c1098af32c50573bca451', '8a6c6b7db0525d88bd7e0a7cc43adf71', 3, 2000, 'Aspirine'),
(11, '34dfef095a5723fbddcd564d2f66fb60', 'cc205c31042a4e78b40a168918a9dad0', 1, 3200, 'Doliprane'),
(12, 'd634c873db0ac7fe2d94b5a846f66c6d', 'de81d0bec4d9da4ee7c3e2479e9bd8a8', 2, 5200, 'Amidol'),
(13, 'd634c873db0ac7fe2d94b5a846f66c6d', '06df018e31eb89a7ae08ad3d3cf99da7', 3, 1500, 'Ubicap'),
(14, '905b5ad992f946561027e06263ca478c', '8a6c6b7db0525d88bd7e0a7cc43adf71', 3, 2000, 'Aspirine'),
(15, '212a25d327c8749e7de17c5b337be768', 'cc205c31042a4e78b40a168918a9dad0', 1, 3200, 'Doliprane'),
(16, '212a25d327c8749e7de17c5b337be768', '8a6c6b7db0525d88bd7e0a7cc43adf71', 10, 2000, 'Aspirine');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacie`
--

DROP TABLE IF EXISTS `pharmacie`;
CREATE TABLE IF NOT EXISTS `pharmacie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_proprietaire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_pharmacie` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temps` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pharmacie`
--

INSERT INTO `pharmacie` (`id`, `nom`, `adresse`, `code_proprietaire`, `description`, `type_pharmacie`, `code`, `temps`) VALUES
(1, 'Pharborel', 'Caserne 30', 'aa74113b5cdb711d6eb615150cf5123f', 'Une description', 'depot', '824b406200fe32afb6324f1e8a338ebf', 1750630362),
(2, 'SuperPharma', 'Kinga', 'aa74113b5cdb711d6eb615150cf5123f', 'Une description autre', 'autre', '265bb1104d5f72da5046d91298cf046c', 1750631813);

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_scientifique` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` int NOT NULL,
  `prix` int NOT NULL,
  `date_peremption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categorie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_pharmacie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temps` bigint NOT NULL,
  `temps_mod` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `nom_scientifique`, `description`, `quantite`, `prix`, `date_peremption`, `categorie`, `code_pharmacie`, `code`, `temps`, `temps_mod`) VALUES
(2, 'Doliprane', 'MachinTruc', 'Une description', 1883, 3200, '', 'Anti-inflammatoire', '265bb1104d5f72da5046d91298cf046c', 'cc205c31042a4e78b40a168918a9dad0', 1750696979, 0),
(3, 'Ubicap', 'Ubiprophen', 'Description détaillée', 0, 15000, '2026-04-19', 'Anti-céphalique', '265bb1104d5f72da5046d91298cf046c', 'f2815faff8b763f93d3aee01c7377e77', 1750697373, 0),
(4, 'Aspirine', 'Aspirinione', 'Une description', 19, 2000, '2026-06-24', 'Anti-céphalique', '265bb1104d5f72da5046d91298cf046c', '8a6c6b7db0525d88bd7e0a7cc43adf71', 1750725486, 0),
(5, 'Amidol', 'Amidophiline', 'Une description sur le produit', 68, 5200, '2026-05-17', 'Anti-céphalique', '824b406200fe32afb6324f1e8a338ebf', 'de81d0bec4d9da4ee7c3e2479e9bd8a8', 1750780456, 0),
(6, 'Ubicap', 'Ubiprophen', 'Encore une description etaillée', 117, 1500, '2026-02-08', 'Anti-céphalique', '824b406200fe32afb6324f1e8a338ebf', '06df018e31eb89a7ae08ad3d3cf99da7', 1750780605, 0);

-- --------------------------------------------------------

--
-- Table structure for table `proprietaire`
--

DROP TABLE IF EXISTS `proprietaire`;
CREATE TABLE IF NOT EXISTS `proprietaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temps` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proprietaire`
--

INSERT INTO `proprietaire` (`id`, `nom`, `prenom`, `email`, `telephone`, `adresse`, `mdp`, `code`, `temps`) VALUES
(1, 'sdqsd', 'sqsqd@willpharma.com', 'sqsqd@willpharma.com', '0820908486', 'ygygi', '$2y$10$g//UDRJQEI59HISpNRd1IefvrRxWs.jpFYBkOA/RTaLI8BbeLP242', '77bc4c11c855cc7f04a6c2810e286962', 1749590911),
(2, 'Phanzu', 'gloreensmith@willpharma.com', 'gloreensmith@willpharma.com', '0820908486', 'Caserne 1518', '$2y$10$mKhvkAXkDTeqzZw6p1F3pu.ndUdOrpWReoLU5hmTGFqrlVNvSTBMO', '074bb9fc743980df683394facb02aeca', 1750617062),
(3, 'Vuvu', 'greckvuvu@willpharma.com', 'greckvuvu@willpharma.com', '0823147963', 'Mbanda', '$2y$10$oTYXol3ifsu.p5vmkGk47.2Yswr5SJMbbnOj5jML.0129mS/oN3Sa', 'aa74113b5cdb711d6eb615150cf5123f', 1750617188);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `prenom` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `telephone` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `code_pharmacie` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `temps` bigint NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `telephone` (`telephone`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `telephone`, `mdp`, `role`, `code_pharmacie`, `code`, `temps`) VALUES
(1, 'LELO', 'GRACE', 'gloreensmith@gmail.com', '0852121320', '$2y$10$QzniUl1B5kg671jYM4GTeuN/DWM4tVYn5L1AS5aW8O6B83l1RGmS6', 'proprietaire', NULL, '8309a8e828b75bde12429046eb4b0db4', 1749073789);

-- --------------------------------------------------------

--
-- Table structure for table `vente`
--

DROP TABLE IF EXISTS `vente`;
CREATE TABLE IF NOT EXISTS `vente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_employe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_pharmacie` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `temps` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
