-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 28 fév. 2026 à 11:11
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ripaweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `action_utilisateur`
--

CREATE TABLE `action_utilisateur` (
  `id_action_utilisateur` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_entreprise_cliente` int(11) NOT NULL DEFAULT 1,
  `nom_table` varchar(255) DEFAULT NULL,
  `id_champ` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `text_descriptif` text DEFAULT NULL,
  `date_heure` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `action_utilisateur`
--

INSERT INTO `action_utilisateur` (`id_action_utilisateur`, `id_utilisateur`, `id_entreprise_cliente`, `nom_table`, `id_champ`, `action`, `text_descriptif`, `date_heure`) VALUES
(1, 1, 1, 'null', NULL, 'loggin', 'loggin to system', '2024-04-17 16:04:25'),
(2, 1, 1, 'null', NULL, 'loggin', 'loggin to system', '2024-04-17 16:04:30'),
(3, 1, 1, 'Utilisateur', 9, 'insertion', 'ajout de l\'Utilisateur Root', '2024-04-17 16:04:34'),
(4, 1, 1, 'Utilisateur', 1, 'mise à jour', 'mise à jour de l\'utilisateur Admin', '2024-04-17 16:04:35'),
(5, 1, 1, 'Utilisateur', 8, 'suppression', 'suppression de l\'utilisateur Manika', '2024-04-17 16:04:35'),
(6, 1, 1, 'Utilisateur', 7, 'suppression', 'suppression de l\'utilisateur Kalenga', '2024-04-17 16:04:37'),
(7, 1, 1, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Super Admin Dee-pay ', '2024-04-17 16:37:48'),
(8, 1, 1, 'role ', 16, 'insertion', 'ajout d\'un Role  admin', '2024-04-17 16:04:38'),
(9, 1, 1, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle admin', '2024-04-17 16:48:43'),
(10, 1, 1, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle admin', '2024-04-17 16:48:45'),
(11, 1, 1, 'role', 16, 'mise à jour', 'mise à jour du role  Admin', '2024-04-17 16:04:48'),
(12, 1, 1, 'Publicite ', 1, 'insertion', 'ajout d\'une publicité : Deepay Intro', '2024-04-17 17:04:12'),
(13, 1, 1, 'Entreprise', 6, 'mise à jour', 'suppression du Marchand Congo Astral Company', '2024-04-17 18:04:17'),
(14, 1, 1, 'Entreprise', 5, 'mise à jour', 'suppression du Marchand Dee services engineering', '2024-04-17 18:04:17'),
(15, 1, 1, 'Entreprise', 8, 'insertion', 'ajout du Marchand Laboratoire Medical Dee Services', '2024-04-17 18:04:22'),
(16, 9, 1, 'null', NULL, 'loggin', 'loggin to system', '2024-04-18 09:04:10'),
(17, 9, 0, 'Entreprise', 9, 'insertion', 'ajout du Marchand Cadeau Mart', '2024-04-18 09:04:19'),
(18, 9, 0, 'Entreprise', 10, 'insertion', 'ajout du Marchand Nella Shop', '2024-04-18 09:04:25'),
(19, 9, 1, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Admin', '2024-04-18 09:39:37'),
(20, 9, 1, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Admin', '2024-04-18 09:39:59'),
(21, 9, 1, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Admin', '2024-04-18 09:41:19'),
(22, 9, 1, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Admin', '2024-04-18 10:26:59'),
(23, 9, 1, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Autre Role', '2024-04-18 10:27:18'),
(24, 9, 0, 'taux_echange', 3, 'insertion', 'ajout d\'un taux d\'échange  2 790,00 usd-cdf', '2024-04-18 10:04:31'),
(25, 13, 1, 'Utilisateur', 1, 'mise à jour', 'mise à jour de l\'utilisateur Admin', '2024-04-18 10:04:46'),
(26, 13, 1, 'Utilisateur', 1, 'mise à jour', 'mise à jour de l\'utilisateur Admin', '2024-04-18 10:04:46'),
(27, 13, 1, 'Utilisateur', 10, 'insertion', 'ajout de l\'Utilisateur Admin', '2024-04-18 10:04:51'),
(28, 13, 1, 'Utilisateur', 11, 'insertion', 'ajout de l\'Utilisateur Admin', '2024-04-18 11:04:00'),
(29, 9, 1, 'null', NULL, 'loggin', 'loggin to system', '2024-04-19 08:04:16'),
(30, 9, 0, 'null', NULL, 'loggout', 'loggout from system', '2024-04-19 08:04:17'),
(31, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-19 08:04:17'),
(32, 9, 0, 'null', NULL, 'loggout', 'loggout from system', '2024-04-19 08:04:20'),
(33, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-19 08:04:20'),
(34, 9, 0, 'role', 16, 'mise à jour', 'mise à jour du role  Admin', '2024-04-19 08:04:26'),
(35, 9, 0, 'Utilisateur', 1, 'mise à jour', 'mise à jour de l\'utilisateur Admin', '2024-04-19 08:04:27'),
(36, 9, 0, 'Publicite', 1, 'mise à jour', 'mise à jour de la publicité  Deepay Intro', '2024-04-19 08:04:27'),
(37, 9, 0, 'Entreprise', 10, 'mise à jour', 'mise à jour du Marchand Nella Shop', '2024-04-19 08:04:28'),
(38, 9, 0, 'taux_echange', 2, 'mise à jour', 'suppression du taux d\'échange 2000.00 usd-cdf', '2024-04-19 08:04:28'),
(39, 9, 0, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Admin', '2024-04-19 08:32:29'),
(40, 9, 0, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Admin', '2024-04-19 08:33:34'),
(41, 9, 0, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Admin', '2024-04-19 09:12:59'),
(42, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-19 10:04:47'),
(43, 9, 0, 'compte_money_entreprise', 1, 'insertion', 'ajout d\'un Compte de paiement  +243825947253', '2024-04-19 10:04:59'),
(44, 9, 0, 'compte_money_entreprise', 2, 'insertion', 'ajout d\'un Compte de paiement  +243970404494', '2024-04-19 11:04:02'),
(45, 9, 0, 'compte_money_entreprise', 3, 'insertion', 'ajout d\'un Compte de paiement  +243810274370', '2024-04-19 11:04:03'),
(46, 1, 8, 'null', NULL, 'loggin', 'loggin to system', '2024-04-19 11:04:04'),
(47, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-19 15:04:07'),
(48, 9, 0, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Super Admin Dee-pay ', '2024-04-19 15:08:37'),
(49, 9, 0, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Super Admin Dee-pay ', '2024-04-19 15:08:38'),
(50, 9, 0, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Admin', '2024-04-19 15:10:08'),
(51, 9, 0, 'facture_index ', 4, 'insertion', 'ajout d\'Un index de facture 123000', '2024-04-19 15:04:47'),
(52, 9, 0, 'facture_index ', 1, 'insertion', 'ajout d\'Un index de facture 123000', '2024-04-19 15:04:47'),
(53, 9, 0, 'facture_index ', 2, 'insertion', 'ajout d\'Un index de facture 123567', '2024-04-19 15:04:48'),
(54, 9, 0, 'facture_index ', 3, 'insertion', 'ajout d\'Un index de facture 7654321', '2024-04-19 15:04:48'),
(55, 10, 10, 'null', NULL, 'loggin', 'loggin to system', '2024-04-19 15:04:49'),
(56, 9, 0, 'reference_index ', 1, 'insertion', 'ajout d\'Un index de reference 000012345', '2024-04-19 16:04:02'),
(57, 9, 0, 'reference_index', 1, 'mise à jour', 'mise à jour de l\' index de reference  000012345', '2024-04-19 16:04:02'),
(58, 10, 10, 'null', NULL, 'loggin', 'loggin to system', '2024-04-19 18:04:41'),
(59, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-19 19:04:27'),
(60, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-20 08:04:12'),
(61, 9, 0, 'facture ', 1, 'insertion', 'ajout d\'Un Facture INV-CADMART-0007654322', '2024-04-20 09:04:30'),
(62, 9, 0, 'facture ', 2, 'insertion', 'ajout d\'Un Facture INV-NSHOP-0000123568', '2024-04-20 09:04:32'),
(63, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-20 09:04:39'),
(64, 9, 0, 'facture ', 3, 'insertion', 'ajout d\'Un Facture INV-LABODEES-0000123001', '2024-04-20 10:04:02'),
(65, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-20 10:04:10'),
(66, 9, 0, 'facture', 1, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-20 10:04:10'),
(67, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-20 10:04:10'),
(68, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-20 10:04:10'),
(69, 9, 0, 'facture', 3, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-20 10:04:11'),
(70, 9, 0, 'facture ', 4, 'insertion', 'ajout d\'Un Facture INV-LABODEES-0000123002', '2024-04-20 10:04:42'),
(71, 9, 0, 'facture', 4, 'suppression', 'suppression de la Facture INV-LABODEES-0000123002', '2024-04-20 10:04:42'),
(72, 10, 10, 'null', NULL, 'loggin', 'loggin to system', '2024-04-20 12:04:41'),
(73, 10, 10, 'facture ', 5, 'insertion', 'ajout d\'Un Facture INV-NSHOP-0000123569', '2024-04-20 12:04:43'),
(74, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-20 14:04:09'),
(75, 10, 10, 'null', NULL, 'loggin', 'loggin to system', '2024-04-20 17:04:08'),
(76, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-20 17:04:21'),
(77, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-22 13:04:21'),
(78, 9, 0, 'facture', 5, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:32'),
(79, 9, 0, 'facture', 3, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:32'),
(80, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:32'),
(81, 9, 0, 'facture', 1, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:32'),
(82, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:33'),
(83, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:34'),
(84, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:34'),
(85, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:34'),
(86, 9, 0, 'facture', 1, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:34'),
(87, 9, 0, 'facture', 5, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:42'),
(88, 9, 0, 'facture', 3, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:42'),
(89, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:42'),
(90, 9, 0, 'facture', 1, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:42'),
(91, 9, 0, 'facture', 5, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:49'),
(92, 9, 0, 'facture', 3, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:49'),
(93, 9, 0, 'facture', 2, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:49'),
(94, 9, 0, 'facture', 1, 'mise à jour', 'mise à jour de la Facture  ', '2024-04-22 13:04:49'),
(95, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-22 17:04:54'),
(96, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-23 08:04:26'),
(97, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-23 10:04:50'),
(98, 9, 0, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Super Admin Dee-pay ', '2024-04-23 10:50:44'),
(99, 9, 0, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Admin', '2024-04-23 10:51:37'),
(100, 9, 0, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Super Admin Dee-pay ', '2024-04-23 10:56:47'),
(101, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-23 11:04:26'),
(102, 9, 0, 'commission', 1, 'insertion', 'ajout de la commission Commision sur deepay', '2024-04-23 11:04:27'),
(103, 9, 0, 'commission', 1, 'mise à jour', 'mise à jour de la commission Commision sur deepay Mobile Money', '2024-04-23 11:04:34'),
(104, 9, 0, 'commission', 2, 'insertion', 'ajout de la commission Commision sur deepay Carte bancaire', '2024-04-23 11:04:35'),
(105, 9, 0, 'commission', 2, 'mise à jour', 'mise à jour de la commission Commision sur deepay Carte bancaire', '2024-04-23 12:04:04'),
(106, 9, 0, 'commission', 2, 'mise à jour', 'mise à jour de la commission Commision sur deepay Carte bancaire', '2024-04-23 12:04:04'),
(107, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-24 12:04:04'),
(108, 10, 10, 'null', NULL, 'loggin', 'loggin to system', '2024-04-24 15:04:07'),
(109, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-25 10:04:13'),
(110, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-29 09:04:37'),
(111, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-29 17:04:21'),
(112, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-30 10:04:26'),
(113, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-04-30 16:04:42'),
(114, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-05-02 10:05:51'),
(115, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-05-02 10:05:57'),
(116, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-05-15 12:05:04'),
(117, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-05-15 15:05:55'),
(118, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-05-15 18:05:04'),
(119, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-05-16 08:05:25'),
(120, 10, 10, 'null', NULL, 'loggin', 'loggin to system', '2024-05-16 08:05:31'),
(121, 9, 0, 'null', NULL, 'loggout', 'loggout from system', '2024-05-16 08:05:41'),
(122, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-05-16 08:05:41'),
(123, 1, 8, 'null', NULL, 'loggin', 'loggin to system', '2024-05-16 13:05:04'),
(124, 1, 8, 'null', NULL, 'loggin', 'loggin to system', '2024-05-16 15:05:30'),
(125, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-05-16 15:05:40'),
(126, 9, 0, 'transaction', 72, 'suppression', 'suppression de la transaction de sorti ref 1715868646', '2024-05-16 16:05:18'),
(127, 9, 0, 'transaction ', 73, 'insertion', 'ajout d\'Une transaction de sorti ref 1715869114', '2024-05-16 16:05:18'),
(128, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2024-05-25 13:05:58'),
(129, 9, 0, 'facture ', 6, 'insertion', 'ajout d\'Un Facture INV-LABODEES-0000123003', '2024-05-25 14:05:03'),
(130, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2025-06-10 19:06:54'),
(131, 9, 0, 'role', 1, 'mise à jour', 'mise à jour du role  Super AdminRIPA', '2025-06-10 19:06:55'),
(132, 9, 0, 'role', 1, 'mise à jour', 'mise à jour du role  Super Admin RIPA', '2025-06-10 19:06:55'),
(133, 9, 0, 'role_permission', 0, 'Insertion ou mise à jour', 'Mise à jour des permissions du rôle Super Admin RIPA', '2025-06-10 19:57:33'),
(134, 9, 0, 'commission', 2, 'mise à jour', 'mise à jour de la commission Commision sur RIPA Carte bancaire', '2025-06-10 20:06:28'),
(135, 9, 0, 'commission', 1, 'mise à jour', 'mise à jour de la commission Commision sur RIPA Mobile Money', '2025-06-10 20:06:29'),
(136, 9, 0, 'null', NULL, 'loggout', 'loggout from system', '2025-06-10 20:06:33'),
(137, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2025-06-12 18:06:42'),
(138, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2025-06-15 12:06:38'),
(139, 9, 0, 'null', NULL, 'loggout', 'loggout from system', '2025-06-15 12:06:39'),
(140, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2025-09-06 20:09:13'),
(141, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2025-10-11 08:10:42'),
(142, 9, 0, 'null', NULL, 'loggin', 'loggin to system', '2025-11-11 13:11:17');

-- --------------------------------------------------------

--
-- Structure de la table `api_logs`
--

CREATE TABLE `api_logs` (
  `id` int(11) NOT NULL,
  `id_utilisateur_application` int(11) DEFAULT NULL,
  `endpoint` varchar(255) NOT NULL,
  `method` varchar(10) NOT NULL,
  `request_body` text DEFAULT NULL,
  `response_code` int(11) NOT NULL,
  `response_body` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `execution_time` decimal(10,4) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commission`
--

CREATE TABLE `commission` (
  `id_commission` int(11) NOT NULL,
  `nom_commission` varchar(100) DEFAULT NULL,
  `commission_dee_pay` varchar(100) DEFAULT NULL,
  `commission_network` varchar(100) DEFAULT NULL,
  `id_foreign_type_commission` int(11) DEFAULT NULL,
  `date_enregistrement` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commission`
--

INSERT INTO `commission` (`id_commission`, `nom_commission`, `commission_dee_pay`, `commission_network`, `id_foreign_type_commission`, `date_enregistrement`) VALUES
(1, 'Commision sur RIPA Mobile Money', '1', '3.5', 1, '2024-04-23'),
(2, 'Commision sur RIPA Carte bancaire', '1', '4.5', 2, '2024-04-23');

-- --------------------------------------------------------

--
-- Structure de la table `compte_financier_utilisateur_application`
--

CREATE TABLE `compte_financier_utilisateur_application` (
  `id_compte_financier_utilisateur_application` int(11) NOT NULL,
  `num_compte_financier` varchar(100) DEFAULT NULL,
  `id_foreign_type_mobile_money` int(11) DEFAULT NULL,
  `id_foreign_utilisateur_application` int(11) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `date_compte_financier_utilisateur_application` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compte_financier_utilisateur_application`
--

INSERT INTO `compte_financier_utilisateur_application` (`id_compte_financier_utilisateur_application`, `num_compte_financier`, `id_foreign_type_mobile_money`, `id_foreign_utilisateur_application`, `is_default`, `is_active`, `date_compte_financier_utilisateur_application`) VALUES
(1, '+243971403075', 1, 3, 1, 1, '2025-10-18'),
(2, '+243852721157', 2, 4, 1, 1, '2025-10-18'),
(3, '+243971403075', 1, 5, 1, 1, '2025-10-18'),
(4, '+243971403075', 1, 6, 1, 1, '2025-10-18'),
(5, '+243971403075', 1, 7, 1, 1, '2025-10-18'),
(6, '+243971403075', 1, 8, 1, 1, '2025-10-18'),
(7, '+243971403075', 1, 9, 1, 1, '2025-10-18'),
(8, '+243971403075', 1, 10, 1, 1, '2025-10-18'),
(9, '+243971403075', 1, 11, 1, 1, '2025-10-18'),
(10, '+243971403075', 1, 12, 1, 1, '2025-10-18');

-- --------------------------------------------------------

--
-- Structure de la table `compte_money_entreprise`
--

CREATE TABLE `compte_money_entreprise` (
  `id_compte_money_entreprise` int(11) NOT NULL,
  `id_entreprise_cliente` int(11) NOT NULL,
  `num_mobile_money` varchar(100) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 0,
  `id_type_money` int(11) DEFAULT NULL,
  `id_foreign_devise` int(100) DEFAULT NULL,
  `date_enregistrement` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compte_money_entreprise`
--

INSERT INTO `compte_money_entreprise` (`id_compte_money_entreprise`, `id_entreprise_cliente`, `num_mobile_money`, `is_active`, `id_type_money`, `id_foreign_devise`, `date_enregistrement`) VALUES
(1, 8, '+243825947253', 1, 3, 3, '2024-04-19'),
(2, 10, '+243970404494', 1, 1, 3, '2024-04-19'),
(3, 9, '+243810274370', 1, 3, 3, '2024-04-19');

-- --------------------------------------------------------

--
-- Structure de la table `devise`
--

CREATE TABLE `devise` (
  `id_devise` int(11) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `abreviation` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `devise`
--

INSERT INTO `devise` (`id_devise`, `designation`, `abreviation`) VALUES
(1, 'Franc Congolais', 'CDF'),
(2, 'Dollars Américains', 'USD'),
(3, 'Francs Congolais CDF et Dollars USD ', 'CDF / USD');

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `id_entreprise` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone_contact` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `is_systeme` int(11) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `nom_marchand` varchar(100) DEFAULT NULL,
  `date_enregistrement` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`id_entreprise`, `nom`, `email`, `telephone_contact`, `logo`, `is_systeme`, `token`, `nom_marchand`, `date_enregistrement`) VALUES
(8, 'Laboratoire Medical Dee Services', 'admin@lmds.co', '+243825947253', '813f3-lmds-logo.jpg', 2, '<p>\r\n	Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJcL2xvZ2luIiwicm9sZXMiOlsiTUVSQ0hBTlQiXSwiZXhwIjoxNzc2MzQ2OTIxLCJzdWIiOiI5ZGY4YjM0N2RlNjhmODYyNGMzMTNjMTY4ZTdkMDU0YyJ9._mYq0Q0YqUgS-E_Sxj3b8KsX092nWYvh4UfzGNAYPcA</p>\r\n', 'LABODEES', '2024-04-17'),
(9, 'Cadeau Mart', '', '+243810274370', 'a04af-logo.png', 2, '<p>\r\n	N/A</p>\r\n', 'CADMART', '2024-04-18'),
(10, 'Nella Shop', '', '+243970404494', '2abaa-logo_noir_sans_slogan.png', 2, '<p>\r\n	N/A</p>\r\n', 'NSHOP', '2024-04-18');

-- --------------------------------------------------------

--
-- Structure de la table `etat_bloquer`
--

CREATE TABLE `etat_bloquer` (
  `id_etat_bloquer` int(11) NOT NULL,
  `designation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etat_bloquer`
--

INSERT INTO `etat_bloquer` (`id_etat_bloquer`, `designation`) VALUES
(1, 'bloqué'),
(2, 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `etat_civil`
--

CREATE TABLE `etat_civil` (
  `id_etat_civil` int(11) NOT NULL,
  `designation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `etat_civil`
--

INSERT INTO `etat_civil` (`id_etat_civil`, `designation`) VALUES
(1, 'Célibataire'),
(2, 'Marié(e)'),
(3, 'Divorcé(e)'),
(4, 'Veuf(ve)');

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `id_facture` int(11) NOT NULL,
  `num_facture` varchar(200) DEFAULT NULL,
  `num_reference` varchar(200) DEFAULT NULL,
  `montant` int(11) DEFAULT NULL,
  `id_foreign_devise` int(11) DEFAULT NULL,
  `qr_code_facture` varchar(100) DEFAULT NULL,
  `id_foreign_compte_money_entreprise` int(11) DEFAULT NULL,
  `id_foreign_status_facture` int(11) DEFAULT NULL,
  `id_foreign_entreprise` int(11) DEFAULT NULL,
  `date_facture` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `facture`
--

INSERT INTO `facture` (`id_facture`, `num_facture`, `num_reference`, `montant`, `id_foreign_devise`, `qr_code_facture`, `id_foreign_compte_money_entreprise`, `id_foreign_status_facture`, `id_foreign_entreprise`, `date_facture`) VALUES
(1, 'INV-CADMART-0007654322', 'REF-CADMART-0000000001', 100, 2, '+243810274370deepay100deepayUSDdeepayREF-CADMART-0000000001.png', NULL, 1, 9, '2024-04-20'),
(2, 'INV-NSHOP-0000123568', 'REF-NSHOP-0000000001', 400, 2, '+243970404494deepay400deepayUSDdeepayREF-NSHOP-0000000001.png', NULL, 0, 10, '2024-04-20'),
(3, 'INV-LABODEES-0000123001', 'REF-LABODEES-0000012346', 120, 2, '+243825947253deepay120deepayUSDdeepayREF-LABODEES-0000012346.png', NULL, 0, 8, '2024-04-20'),
(5, 'INV-NSHOP-0000123569', 'REF-NSHOP-0000000002', 300000, 1, '+243970404494deepay300000deepayCDFdeepayREF-NSHOP-0000000002.png', NULL, 1, 10, '2024-04-20'),
(6, 'INV-LABODEES-0000123003', 'REF-LABODEES-0000012348', 100, 1, '+243825947253deepay100deepayCDFdeepayREF-LABODEES-0000012348.png', NULL, 0, 8, '2024-05-25');

-- --------------------------------------------------------

--
-- Structure de la table `facture_index`
--

CREATE TABLE `facture_index` (
  `id_facture_index` int(11) NOT NULL,
  `num_start_facture` varchar(100) DEFAULT NULL,
  `num_facture_index` varchar(100) DEFAULT NULL,
  `id_foreign_entreprise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `facture_index`
--

INSERT INTO `facture_index` (`id_facture_index`, `num_start_facture`, `num_facture_index`, `id_foreign_entreprise`) VALUES
(1, '123000', '123003', 8),
(2, '123567', '123569', 10),
(3, '7654321', '7654322', 9);

-- --------------------------------------------------------

--
-- Structure de la table `fonctionnalite`
--

CREATE TABLE `fonctionnalite` (
  `id_fonctionnalite` int(11) NOT NULL,
  `short_code` varchar(50) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `id_group_fonctionnalite` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `system` int(11) DEFAULT NULL,
  `date_heure` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `fonctionnalite`
--

INSERT INTO `fonctionnalite` (`id_fonctionnalite`, `short_code`, `designation`, `id_group_fonctionnalite`, `is_active`, `system`, `date_heure`) VALUES
(1, 'compte', 'Gérer les Compte des utilisateurs ', 1, 1, 1, '2020-10-30 16:29:31'),
(2, 'transaction', 'Voir les transactions', 1, 1, 1, '2020-10-30 16:29:31'),
(8, 'role', 'Gérer les Rôles utilisateurs', 1, 1, 1, '2020-10-30 16:29:31'),
(10, 'taux', 'Configurer les taux d\'échange', 1, 1, 1, '2020-10-30 16:29:31'),
(12, 'transfert', 'Faire des transactions dans le système', 1, 1, 1, '2020-10-30 16:29:31'),
(14, 'activite_user', 'Voir les journaux des utilisateurs', 1, 1, 1, '2020-10-30 16:29:31'),
(15, 'entreprise', 'Gérer les Entreprises clientes (Marchands)', 1, 1, 1, '2020-10-30 16:29:31'),
(30, 'role_permission', 'Gérer les permissions sur les rôles', 1, 1, 1, '2021-02-07 22:39:00'),
(31, 'activite_user', 'Voir les Activités des utilisateurs', 1, 1, 1, '2021-02-07 22:45:13'),
(101, 'profil_utilisateur', 'Peut modifier son profil', 1, 1, 1, '2021-07-27 14:20:27'),
(102, 'facture', 'Gérer les factures dans le système', 1, 1, 1, '2021-07-27 14:58:38'),
(103, 'compte_money', 'Gérer les comptes de paiement', 1, 1, 1, '2021-07-28 12:46:21'),
(104, 'finance', 'Voir les resumés financiers du système. ', 1, 1, 1, '2021-09-14 11:55:52'),
(105, 'pub', 'Gérer les publicités dans le système', 1, 1, 1, '2024-04-17 13:11:47'),
(106, 'facture_index', 'Gérer l\'incrémentation des numéros des factures', 1, 1, 1, '2024-04-19 13:15:01'),
(107, 'reference_index', 'Gérer l\'incrémentation des numéros des références ', 1, 1, 1, '2024-04-19 13:15:01'),
(108, 'commission', 'Gérer les commissions sur les transactions', 1, 1, 1, '2024-04-23 08:34:41'),
(109, 'marchand_fiche', 'Gérer les fiches des marchands ', 1, 1, 1, '2025-06-10 19:57:21');

-- --------------------------------------------------------

--
-- Structure de la table `group_fonctionnalite`
--

CREATE TABLE `group_fonctionnalite` (
  `id_group_fonctionnalite` int(11) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `token_group_fonctionnalite` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `group_fonctionnalite`
--

INSERT INTO `group_fonctionnalite` (`id_group_fonctionnalite`, `designation`, `token_group_fonctionnalite`) VALUES
(1, 'Fonc_Système', 'wef5r5ereg1r51g5rfe5qaw5f1ergr56fed');

-- --------------------------------------------------------

--
-- Structure de la table `jwt_sessions`
--

CREATE TABLE `jwt_sessions` (
  `id` int(11) NOT NULL,
  `id_utilisateur_application` int(11) NOT NULL,
  `token` text NOT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_activity` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `user_id`, `otp_code`, `expires_at`, `is_used`, `created_at`) VALUES
(15, 12, '2767', '2025-10-18 16:05:30', 1, '2025-10-18 15:55:30');

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

CREATE TABLE `pays` (
  `id_pays` int(11) NOT NULL,
  `designation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `pays`
--

INSERT INTO `pays` (`id_pays`, `designation`) VALUES
(1, 'RDC'),
(2, 'Zambie'),
(245, 'Afghanistan'),
(246, 'Albanie'),
(247, 'Antarctique'),
(248, 'Algérie'),
(249, 'Samoa Américaines'),
(250, 'Andorre'),
(251, 'Angola'),
(252, 'Antigua-et-Barbuda'),
(253, 'Azerbaïdjan'),
(254, 'Argentine'),
(255, 'Australie'),
(256, 'Autriche'),
(257, 'Bahamas'),
(258, 'Bahreïn'),
(259, 'Bangladesh'),
(260, 'Arménie'),
(261, 'Barbade'),
(262, 'Belgique'),
(263, 'Bermudes'),
(264, 'Bhoutan'),
(265, 'Bolivie'),
(266, 'Bosnie-Herzégovine'),
(267, 'Botswana'),
(268, 'ïle Bouvet'),
(269, 'Brésil'),
(270, 'Belize'),
(271, 'Territoire Britannique de l\'Océan Indien'),
(272, 'ïles Salomon'),
(273, 'ïles Vierges Britanniques'),
(274, 'Brunéi Darussalam'),
(275, 'Bulgarie'),
(276, 'Myanmar'),
(277, 'Burundi'),
(278, 'Bélarus'),
(279, 'Cambodge'),
(280, 'Cameroun'),
(281, 'Canada'),
(282, 'Cap-vert'),
(283, 'ïles Caïmanes'),
(284, 'République Centrafricaine'),
(285, 'Sri Lanka'),
(286, 'Tchad'),
(287, 'Chili'),
(288, 'Chine'),
(289, 'Taïwan'),
(290, 'ïle Christmas'),
(291, 'ïles Cocos (Keeling)'),
(292, 'Colombie'),
(293, 'Comores'),
(294, 'Mayotte'),
(295, 'République du Congo'),
(296, 'ïles Cook'),
(297, 'Costa Rica'),
(298, 'Croatie'),
(299, 'Cuba'),
(300, 'Chypre'),
(301, 'République Tchèque'),
(302, 'Bénin'),
(303, 'Danemark'),
(304, 'Dominique'),
(305, 'République Dominicaine'),
(306, 'équateur'),
(307, 'El Salvador'),
(308, 'Guinéeéquatoriale'),
(309, 'éthiopie'),
(310, 'érythrée'),
(311, 'Estonie'),
(312, 'ïles Féroé'),
(313, 'ïles (malvinas) Falkland'),
(314, 'Géorgie du Sud et les ïles Sandwich du Sud'),
(315, 'Fidji'),
(316, 'Finlande'),
(317, 'ïles irland'),
(318, 'France'),
(319, 'Guyane Française'),
(320, 'Polynésie Française'),
(321, 'Terres Australes Françaises'),
(322, 'Djibouti'),
(323, 'Gabon'),
(324, 'Géorgie'),
(325, 'Gambie'),
(326, 'Territoire Palestinien Occupé'),
(327, 'Allemagne'),
(328, 'Ghana'),
(329, 'Gibraltar'),
(330, 'Kiribati'),
(331, 'Grèce'),
(332, 'Groenland'),
(333, 'Grenade'),
(334, 'Guadeloupe'),
(335, 'Guam'),
(336, 'Guatemala'),
(337, 'Guinée'),
(338, 'Guyana'),
(339, 'Haïti'),
(340, 'ïles Heard et Mcdonald'),
(341, 'Saint-Siège (état de la Cité du Vatican)'),
(342, 'Honduras'),
(343, 'Hong-Kong'),
(344, 'Hongrie'),
(345, 'Islande'),
(346, 'Inde'),
(347, 'Indonésie'),
(348, 'République Islamique d\'Iran'),
(349, 'Iraq'),
(350, 'Irlande'),
(351, 'Israél'),
(352, 'Italie'),
(353, 'Cote d\'Ivoire'),
(354, 'Jamaïque'),
(355, 'Japon'),
(356, 'Kazakhstan'),
(357, 'Jordanie'),
(358, 'Kenya'),
(359, 'République Populaire Démocratique de Corée'),
(360, 'République de Corée'),
(361, 'Koweït'),
(362, 'Kirghizistan'),
(363, 'République Démocratique Populaire Lao'),
(364, 'Liban'),
(365, 'Lesotho'),
(366, 'Lettonie'),
(367, 'Libéria'),
(368, 'Jamahiriya Arabe Libyenne'),
(369, 'Liechtenstein'),
(370, 'Lituanie'),
(371, 'Luxembourg'),
(372, 'Macao'),
(373, 'Madagascar'),
(374, 'Malawi'),
(375, 'Malaisie'),
(376, 'Maldives'),
(377, 'Mali'),
(378, 'Malte'),
(379, 'Martinique'),
(380, 'Mauritanie'),
(381, 'Maurice'),
(382, 'Mexique'),
(383, 'Monaco'),
(384, 'Mongolie'),
(385, 'République de Moldova'),
(386, 'Montserrat'),
(387, 'Maroc'),
(388, 'Mozambique'),
(389, 'Oman'),
(390, 'Namibie'),
(391, 'Nauru'),
(392, 'Népal'),
(393, 'Pays-Bas'),
(394, 'Antilles Néerlandaises'),
(395, 'Aruba'),
(396, 'Nouvelle-Calédonie'),
(397, 'Vanuatu'),
(398, 'Nouvelle-Zélande'),
(399, 'Nicaragua'),
(400, 'Niger'),
(401, 'Nigéria'),
(402, 'Niué'),
(403, 'ïle Norfolk'),
(404, 'Norvège'),
(405, 'ïles Mariannes du Nord'),
(406, 'ïles Mineureséloignées desétats-Unis'),
(407, 'états Fédérés de Micronésie'),
(408, 'ïles Marshall'),
(409, 'Palaos'),
(410, 'Pakistan'),
(411, 'Panama'),
(412, 'Papouasie-Nouvelle-Guinée'),
(413, 'Paraguay'),
(414, 'Pérou'),
(415, 'Philippines'),
(416, 'Pitcairn'),
(417, 'Pologne'),
(418, 'Portugal'),
(419, 'Guinée-Bissau'),
(420, 'Timor-Leste'),
(421, 'Porto Rico'),
(422, 'Qatar'),
(423, 'Réunion'),
(424, 'Roumanie'),
(425, 'Fédération de Russie'),
(426, 'Rwanda'),
(427, 'Sainte-Hélène'),
(428, 'Saint-Kitts-et-Nevis'),
(429, 'Anguilla'),
(430, 'Sainte-Lucie'),
(431, 'Saint-Pierre-et-Miquelon'),
(432, 'Saint-Vincent-et-les Grenadines'),
(433, 'Saint-Marin'),
(434, 'Sao Tomé-et-Principe'),
(435, 'Arabie Saoudite'),
(436, 'Sénégal'),
(437, 'Seychelles'),
(438, 'Sierra Leone'),
(439, 'Singapour'),
(440, 'Slovaquie'),
(441, 'Viet Nam'),
(442, 'Slovénie'),
(443, 'Somalie'),
(444, 'Afrique du Sud'),
(445, 'Zimbabwe'),
(446, 'Espagne'),
(447, 'Sahara Occidental'),
(448, 'Soudan'),
(449, 'Suriname'),
(450, 'Svalbard etïle Jan Mayen'),
(451, 'Swaziland'),
(452, 'Suède'),
(453, 'Suisse'),
(454, 'République Arabe Syrienne'),
(455, 'Tadjikistan'),
(456, 'Thaïlande'),
(457, 'Togo'),
(458, 'Tokelau'),
(459, 'Tonga'),
(460, 'Trinité-et-Tobago'),
(461, 'émirats Arabes Unis'),
(462, 'Tunisie'),
(463, 'Turquie'),
(464, 'Turkménistan'),
(465, 'ïles Turks et Caïques'),
(466, 'Tuvalu'),
(467, 'Ouganda'),
(468, 'Ukraine'),
(469, 'L\'ex-République Yougoslave de Macédoine'),
(470, 'égypte'),
(471, 'Royaume-Uni'),
(472, 'ïle de Man'),
(473, 'République-Unie de Tanzanie'),
(474, 'états-Unis'),
(475, 'ïles Vierges des états-Unis '),
(476, 'Burkina Faso'),
(477, 'Uruguay'),
(478, 'Ouzbékistan'),
(479, 'Venezuela'),
(480, 'Wallis et Futuna'),
(481, 'Samoa'),
(482, 'Yémen'),
(483, 'Serbie-et-Monténégro');

-- --------------------------------------------------------

--
-- Structure de la table `publicite`
--

CREATE TABLE `publicite` (
  `id_publicite` int(11) NOT NULL,
  `nom_publicite` varchar(100) DEFAULT NULL,
  `nom_client_pub` varchar(100) DEFAULT NULL,
  `image_publicite` varchar(100) DEFAULT NULL,
  `video_publicite` varchar(100) DEFAULT NULL,
  `lien_publicite` varchar(255) DEFAULT NULL,
  `date_enregistrement_publicite` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `publicite`
--

INSERT INTO `publicite` (`id_publicite`, `nom_publicite`, `nom_client_pub`, `image_publicite`, `video_publicite`, `lien_publicite`, `date_enregistrement_publicite`) VALUES
(1, 'Deepay Intro', 'Deepay', 'f338a-deepay-logo.png', 'b18cf-download.mp4', '#', '2024-04-17');

-- --------------------------------------------------------

--
-- Structure de la table `reference_index`
--

CREATE TABLE `reference_index` (
  `id_reference_index` int(11) NOT NULL,
  `num_start_reference` varchar(100) DEFAULT NULL,
  `num_reference_index` varchar(100) DEFAULT NULL,
  `id_foreign_entreprise` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reference_index`
--

INSERT INTO `reference_index` (`id_reference_index`, `num_start_reference`, `num_reference_index`, `id_foreign_entreprise`) VALUES
(1, '000012345', '12348', 8),
(2, '0', '1', 9),
(3, '0', '2', 10);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `system` int(11) DEFAULT NULL,
  `id_entreprise_cliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `designation`, `system`, `id_entreprise_cliente`) VALUES
(1, 'Super Admin RIPA', 1, 1),
(15, 'Autre Role', 1, 1),
(16, 'Admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `role_permission`
--

CREATE TABLE `role_permission` (
  `id_role_permission` int(11) NOT NULL,
  `id_role` int(11) DEFAULT NULL,
  `id_fonctionnalite` int(11) DEFAULT NULL,
  `peux_voir` int(11) DEFAULT NULL,
  `peux_ajouter` int(11) DEFAULT NULL,
  `peux_editer` int(11) DEFAULT NULL,
  `peux_supprimer` int(11) DEFAULT NULL,
  `date_heure` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `role_permission`
--

INSERT INTO `role_permission` (`id_role_permission`, `id_role`, `id_fonctionnalite`, `peux_voir`, `peux_ajouter`, `peux_editer`, `peux_supprimer`, `date_heure`) VALUES
(1, 1, 1, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(2, 1, 2, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(3, 1, 3, 1, 1, 1, 1, '2020-11-27 07:58:11'),
(4, 1, 4, 1, 1, 1, 1, '2020-11-27 07:58:11'),
(5, 1, 5, 1, 1, 1, 1, '2020-11-27 07:58:11'),
(6, 1, 6, 1, 1, 1, 1, '2020-11-27 07:58:11'),
(7, 1, 7, 1, 1, 1, 1, '2020-11-27 07:58:11'),
(8, 1, 8, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(9, 1, 9, 1, 1, 1, 1, '2020-11-27 07:58:11'),
(10, 1, 10, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(11, 1, 11, 1, 1, 1, 1, '2020-11-27 07:58:11'),
(12, 1, 12, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(13, 1, 13, 1, 1, 1, 1, '2020-11-27 07:58:11'),
(14, 1, 14, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(15, 1, 15, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(16, 1, 16, 1, 1, 1, 1, '2021-02-07 21:40:30'),
(17, 1, 17, 1, 1, 1, 1, '2021-02-07 21:40:30'),
(18, 1, 18, 1, 1, 1, 1, '2021-02-07 21:40:30'),
(19, 1, 19, 1, 1, 1, 1, '2021-02-07 21:40:30'),
(20, 1, 20, 1, 1, 1, 1, '2021-02-07 21:40:30'),
(21, 1, 21, 1, 1, 1, 1, '2021-02-07 21:40:30'),
(22, 1, 22, 1, 1, 1, 1, '2021-02-07 21:40:30'),
(23, 1, 23, 1, 1, 1, 1, '2021-04-08 01:40:21'),
(24, 10, 1, 1, 1, 1, 1, '2020-12-11 13:57:57'),
(25, 10, 2, 1, 1, 1, 1, '2020-12-11 13:57:58'),
(26, 10, 3, 1, 1, 1, 1, '2020-12-11 13:57:58'),
(27, 10, 4, 1, 1, 1, 1, '2020-12-11 13:57:58'),
(28, 10, 5, 1, 1, 1, 1, '2020-12-11 13:57:58'),
(29, 10, 6, 1, 1, 1, 1, '2020-12-11 13:57:58'),
(30, 10, 7, 1, 1, 1, 1, '2020-12-11 13:57:58'),
(31, 10, 8, 1, 1, 1, 1, '2020-12-11 13:57:58'),
(32, 10, 9, 1, 1, 1, 1, '2020-12-11 13:57:58'),
(33, 10, 10, 0, 0, 0, 0, '2020-12-11 13:57:58'),
(34, 10, 11, 0, 0, 0, 0, '2020-12-11 13:57:58'),
(35, 10, 12, 1, 1, 1, 1, '2020-12-11 13:57:58'),
(36, 10, 13, 1, 0, 0, 0, '2020-12-11 13:57:58'),
(37, 10, 14, 1, 0, 0, 0, '2020-12-11 13:57:58'),
(38, 10, 15, 1, 0, 0, 0, '2020-12-11 13:57:58'),
(39, 10, 16, 0, 0, 0, 0, '2020-12-11 13:57:58'),
(40, 10, 17, 0, 0, 0, 0, '2020-12-11 13:57:58'),
(41, 10, 18, 0, 0, 0, 0, '2020-12-11 13:57:58'),
(42, 10, 19, 0, 0, 0, 0, '2020-12-11 13:57:58'),
(43, 10, 20, 0, 0, 0, 0, '2020-12-11 13:57:59'),
(44, 10, 21, 0, 0, 0, 0, '2020-12-11 13:57:59'),
(45, 10, 22, 0, 0, 0, 0, '2020-12-11 13:57:59'),
(46, 10, 23, 1, 0, 0, 0, '2020-12-11 13:57:59'),
(47, 1, 24, 1, 1, 1, 1, '2021-02-07 21:40:30'),
(48, 1, 25, 1, 1, 1, 1, '2020-11-27 07:58:12'),
(49, 1, 26, 1, 1, 1, 1, '2020-11-27 07:58:12'),
(50, 5, 1, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(51, 5, 2, 1, 1, 1, 1, '2020-12-09 15:59:08'),
(52, 5, 3, 1, 1, 1, 1, '2020-12-09 15:59:08'),
(53, 5, 4, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(54, 5, 5, 1, 1, 1, 1, '2020-12-09 15:59:08'),
(55, 5, 6, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(56, 5, 7, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(57, 5, 8, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(58, 5, 9, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(59, 5, 10, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(60, 5, 11, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(61, 5, 12, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(62, 5, 13, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(63, 5, 14, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(64, 5, 15, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(65, 5, 16, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(66, 5, 17, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(67, 5, 18, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(68, 5, 19, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(69, 5, 20, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(70, 5, 21, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(71, 5, 22, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(72, 5, 23, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(73, 5, 24, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(74, 5, 25, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(75, 5, 26, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(76, 2, 1, 1, 1, 1, 1, '2020-11-07 11:33:00'),
(77, 2, 2, 1, 1, 1, 1, '2020-11-07 11:33:00'),
(78, 2, 3, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(79, 2, 4, 1, 1, 1, 1, '2020-11-07 11:33:00'),
(80, 2, 5, 1, 1, 1, 1, '2020-11-07 11:33:00'),
(81, 2, 6, 1, 1, 1, 1, '2020-11-07 11:33:00'),
(82, 2, 7, 1, 1, 1, 1, '2020-11-07 11:33:00'),
(83, 2, 8, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(84, 2, 9, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(85, 2, 10, 1, 1, 1, 1, '2020-11-07 11:33:00'),
(86, 2, 11, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(87, 2, 12, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(88, 2, 13, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(89, 2, 14, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(90, 2, 15, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(91, 2, 16, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(92, 2, 17, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(93, 2, 18, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(94, 2, 19, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(95, 2, 20, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(96, 2, 21, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(97, 2, 22, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(98, 2, 23, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(99, 2, 24, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(100, 2, 25, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(101, 2, 26, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(102, 1, 27, 1, 1, 1, 1, '2020-11-27 07:58:12'),
(103, 2, 27, 0, 0, 0, 0, '2020-11-07 11:33:00'),
(104, 1, 28, 1, 1, 1, 1, '2020-11-27 07:58:12'),
(105, 2, 28, 1, 1, 1, 1, '2020-11-07 11:33:00'),
(106, 6, 1, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(107, 6, 2, 1, 1, 1, 1, '2020-11-12 10:26:41'),
(108, 6, 3, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(109, 6, 4, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(110, 6, 5, 1, 1, 1, 1, '2020-11-12 10:26:41'),
(111, 6, 6, 1, 1, 1, 1, '2020-11-12 10:26:41'),
(112, 6, 7, 1, 1, 1, 1, '2020-11-12 10:26:41'),
(113, 6, 8, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(114, 6, 9, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(115, 6, 10, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(116, 6, 11, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(117, 6, 12, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(118, 6, 13, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(119, 6, 14, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(120, 6, 15, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(121, 6, 16, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(122, 6, 17, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(123, 6, 18, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(124, 6, 19, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(125, 6, 20, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(126, 6, 21, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(127, 6, 22, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(128, 6, 23, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(129, 6, 24, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(130, 6, 25, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(131, 6, 26, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(132, 6, 27, 0, 0, 0, 0, '2020-11-12 10:26:41'),
(133, 6, 28, 1, 1, 1, 0, '2020-11-12 10:26:41'),
(134, 1, 29, 1, 1, 1, 1, '2020-11-27 07:58:12'),
(135, 10, 24, 1, 0, 0, 0, '2020-12-11 13:57:59'),
(136, 10, 25, 0, 0, 0, 0, '2020-12-11 13:57:59'),
(137, 10, 26, 0, 0, 0, 0, '2020-12-11 13:57:59'),
(138, 10, 27, 0, 0, 0, 0, '2020-12-11 13:57:59'),
(139, 10, 28, 1, 0, 0, 0, '2020-12-11 13:57:59'),
(140, 10, 29, 0, 0, 0, 0, '2020-12-11 13:57:59'),
(141, 5, 27, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(142, 5, 28, 1, 1, 1, 1, '2020-12-09 15:59:08'),
(143, 5, 29, 0, 0, 0, 0, '2020-12-09 15:59:08'),
(144, 12, 1, 1, 0, 0, 0, '2021-02-07 23:22:32'),
(145, 12, 2, 1, 1, 1, 0, '2021-02-07 23:22:32'),
(146, 12, 8, 0, 0, 0, 0, '2021-02-07 23:22:32'),
(147, 12, 9, 0, 0, 0, 0, '2021-02-07 21:33:51'),
(148, 12, 10, 1, 1, 1, 0, '2021-02-07 23:22:32'),
(149, 12, 12, 1, 1, 1, 0, '2021-02-07 23:22:32'),
(150, 12, 14, 1, 1, 1, 0, '2021-02-07 23:22:32'),
(151, 12, 15, 1, 0, 0, 0, '2021-02-07 23:22:32'),
(152, 12, 16, 0, 0, 0, 0, '2021-02-07 21:33:52'),
(153, 12, 17, 0, 0, 0, 0, '2021-02-07 21:33:52'),
(154, 12, 18, 0, 0, 0, 0, '2021-02-07 21:33:52'),
(155, 12, 19, 0, 0, 0, 0, '2021-02-07 21:33:52'),
(156, 12, 20, 1, 1, 0, 0, '2021-02-07 21:33:52'),
(157, 12, 21, 0, 0, 0, 0, '2021-02-07 21:33:52'),
(158, 12, 22, 0, 0, 0, 0, '2021-02-07 21:33:52'),
(159, 12, 23, 1, 1, 1, 0, '2021-02-07 23:22:32'),
(160, 12, 24, 0, 0, 0, 0, '2021-02-07 21:33:52'),
(161, 1, 30, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(162, 1, 31, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(163, 1, 32, 1, 1, 1, 1, '2021-04-08 01:40:21'),
(164, 12, 30, 0, 0, 0, 0, '2021-02-07 23:22:32'),
(165, 12, 31, 0, 0, 0, 0, '2021-02-07 23:22:32'),
(166, 12, 32, 0, 0, 0, 0, '2021-02-07 23:22:32'),
(167, 1, 33, 1, 1, 1, 1, '2021-04-08 01:40:21'),
(168, 1, 34, 1, 1, 1, 1, '2021-04-08 01:40:21'),
(169, 1, 35, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(170, 1, 36, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(171, 1, 37, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(172, 1, 38, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(173, 1, 39, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(174, 1, 40, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(175, 1, 41, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(176, 1, 42, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(177, 1, 43, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(178, 1, 44, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(179, 1, 45, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(180, 1, 46, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(181, 1, 47, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(182, 1, 48, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(183, 1, 49, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(184, 1, 50, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(185, 1, 51, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(186, 1, 52, 1, 1, 1, 1, '2021-04-07 16:16:39'),
(187, 1, 53, 1, 1, 1, 1, '2021-04-07 16:16:39'),
(188, 1, 57, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(189, 1, 58, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(190, 1, 59, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(191, 1, 60, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(192, 1, 61, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(193, 1, 62, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(194, 1, 63, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(195, 1, 64, 1, 1, 1, 1, '2021-04-08 01:40:22'),
(196, 1, 65, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(197, 1, 66, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(198, 1, 67, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(199, 1, 68, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(200, 1, 69, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(201, 1, 70, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(202, 1, 71, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(203, 1, 72, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(204, 1, 73, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(205, 1, 74, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(206, 1, 75, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(207, 1, 76, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(208, 1, 77, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(209, 1, 78, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(210, 1, 79, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(211, 1, 80, 1, 1, 1, 1, '2021-04-08 01:40:23'),
(212, 1, 81, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(213, 1, 82, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(214, 1, 83, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(215, 1, 84, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(216, 1, 85, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(217, 1, 86, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(218, 1, 87, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(219, 1, 88, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(220, 1, 89, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(221, 1, 90, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(222, 1, 91, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(223, 1, 92, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(224, 1, 93, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(225, 1, 94, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(226, 1, 95, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(227, 1, 96, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(228, 1, 97, 1, 1, 1, 1, '2021-04-08 01:40:24'),
(229, 1, 98, 1, 1, 1, 1, '2021-04-08 01:40:25'),
(230, 1, 99, 1, 1, 1, 1, '2021-04-08 01:40:25'),
(231, 1, 100, 1, 1, 1, 1, '2021-04-08 01:40:25'),
(232, 1, 101, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(233, 1, 102, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(234, 15, 1, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(235, 15, 2, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(236, 15, 8, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(237, 15, 10, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(238, 15, 12, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(239, 15, 14, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(240, 15, 15, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(241, 15, 30, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(242, 15, 31, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(243, 15, 101, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(244, 15, 102, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(245, 13, 1, 1, 1, 1, 1, '2021-07-27 16:15:48'),
(246, 13, 2, 1, 1, 1, 1, '2021-07-27 16:15:49'),
(247, 13, 8, 1, 1, 1, 1, '2021-07-27 16:15:49'),
(248, 13, 10, 0, 0, 0, 0, '2021-07-27 16:15:49'),
(249, 13, 12, 1, 1, 1, 1, '2021-07-27 16:15:49'),
(250, 13, 14, 1, 1, 1, 1, '2021-07-27 16:15:49'),
(251, 13, 15, 0, 0, 0, 0, '2021-07-27 16:15:49'),
(252, 13, 30, 1, 1, 1, 1, '2021-07-27 16:15:49'),
(253, 13, 31, 0, 0, 0, 0, '2021-07-27 16:15:49'),
(254, 13, 101, 1, 1, 1, 1, '2021-07-27 16:15:49'),
(255, 13, 102, 1, 1, 1, 1, '2021-07-27 16:15:49'),
(256, 1, 103, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(257, 1, 104, 1, 1, 1, 1, '2025-06-10 19:57:32'),
(258, 1, 105, 1, 1, 1, 1, '2025-06-10 19:57:33'),
(259, 16, 1, 1, 0, 0, 0, '2024-04-23 10:51:37'),
(260, 16, 2, 1, 1, 1, 1, '2024-04-23 10:51:37'),
(261, 16, 8, 0, 0, 0, 0, '2024-04-23 10:51:37'),
(262, 16, 10, 0, 0, 0, 0, '2024-04-23 10:51:37'),
(263, 16, 12, 1, 1, 1, 1, '2024-04-23 10:51:37'),
(264, 16, 14, 1, 1, 1, 1, '2024-04-23 10:51:37'),
(265, 16, 15, 0, 0, 0, 0, '2024-04-23 10:51:37'),
(266, 16, 30, 1, 1, 1, 1, '2024-04-23 10:51:37'),
(267, 16, 31, 1, 1, 1, 1, '2024-04-23 10:51:37'),
(268, 16, 101, 1, 1, 1, 1, '2024-04-23 10:51:37'),
(269, 16, 102, 1, 1, 1, 1, '2024-04-23 10:51:37'),
(270, 16, 103, 1, 0, 0, 0, '2024-04-23 10:51:37'),
(271, 16, 104, 1, 1, 1, 1, '2024-04-23 10:51:37'),
(272, 16, 105, 0, 0, 0, 0, '2024-04-23 10:51:37'),
(273, 15, 103, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(274, 15, 104, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(275, 15, 105, 0, 0, 0, 0, '2024-04-18 10:27:18'),
(276, 1, 106, 1, 1, 1, 1, '2025-06-10 19:57:33'),
(277, 1, 107, 1, 1, 1, 1, '2025-06-10 19:57:33'),
(278, 16, 106, 1, 0, 0, 0, '2024-04-23 10:51:37'),
(279, 16, 107, 1, 0, 0, 0, '2024-04-23 10:51:37'),
(280, 1, 108, 1, 1, 1, 1, '2025-06-10 19:57:33'),
(281, 16, 108, 1, 0, 0, 0, '2024-04-23 10:51:37'),
(282, 1, 109, 1, 1, 1, 1, '2025-06-10 19:57:33');

-- --------------------------------------------------------

--
-- Structure de la table `sexe`
--

CREATE TABLE `sexe` (
  `id_sexe` int(11) NOT NULL,
  `designation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `sexe`
--

INSERT INTO `sexe` (`id_sexe`, `designation`) VALUES
(1, 'Masculin'),
(2, 'Féminin'),
(4, 'Autres');

-- --------------------------------------------------------

--
-- Structure de la table `taux_echange`
--

CREATE TABLE `taux_echange` (
  `id_taux_echange` int(11) NOT NULL,
  `usd_cdf` varchar(50) NOT NULL,
  `cdf_usd` varchar(50) NOT NULL,
  `date_mis_a_jour` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `taux_echange`
--

INSERT INTO `taux_echange` (`id_taux_echange`, `usd_cdf`, `cdf_usd`, `date_mis_a_jour`) VALUES
(3, '2 790,00', '0,00036', '2024-04-18');

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `id_transaction` int(11) NOT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `id_devise` int(11) DEFAULT NULL,
  `deepay_transaction_ref` varchar(255) DEFAULT NULL,
  `network_transaction_ref` varchar(255) DEFAULT NULL,
  `transaction_type` int(11) DEFAULT NULL,
  `from_num` varchar(100) DEFAULT NULL,
  `id_foreign_entreprise` int(11) DEFAULT NULL,
  `dee_pay_commission` decimal(10,5) DEFAULT NULL,
  `network_commission` decimal(10,5) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `date_transaction` date DEFAULT NULL,
  `time_transaction` time DEFAULT NULL,
  `id_foreign_statut_transaction` int(11) DEFAULT 0,
  `id_foreign_statut_execution` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `transaction`
--

INSERT INTO `transaction` (`id_transaction`, `montant`, `id_devise`, `deepay_transaction_ref`, `network_transaction_ref`, `transaction_type`, `from_num`, `id_foreign_entreprise`, `dee_pay_commission`, `network_commission`, `description`, `date_transaction`, `time_transaction`, `id_foreign_statut_transaction`, `id_foreign_statut_execution`) VALUES
(1, 10.00, 2, '0987654321234567', '0987654321234568', 1, '+243971403075', 10, NULL, NULL, 'Paiement minerval mois de d\'avril. Elève Marcel Mitewu 3ieme HSC Imara', '2024-04-20', '17:18:04', 1, 1),
(2, 30.00, 2, '0987654321234569', '09876543212345610', 1, '+243971403075', 9, NULL, NULL, 'Paiement minerval deuxième tranche. Etudiant Pascal Mitewu L2 ISI', '2024-04-20', '17:18:06', 1, 1),
(3, 100.00, 2, '1713626930', '1713626930', 2, '+243970404494', 8, 2.00000, 2.00000, 'Paiement serigue', '2024-04-20', '17:28:50', NULL, 1),
(4, 50.00, 2, '1713627408', '1713627408', 2, '+243970404494', 8, 2.00000, 2.00000, 'Paiement cadeau Pour anniversaire ', '2024-04-20', '17:36:48', NULL, NULL),
(5, 60000.00, 1, '1713626220', '1713626220', 2, '+243900974775', 9, 2.00000, 2.00000, 'Paiement vernis', '2024-04-20', '17:17:00', NULL, 1),
(6, 12.00, 2, '09876543212345691', '09876543212345692', 1, '+243971403075', 10, NULL, NULL, 'Paiement', '2024-04-20', '17:18:04', 0, 0),
(7, 50000.00, 1, '0987654321234568', '0987654321234567', 2, '+243971403075', 10, NULL, NULL, 'Paiemnent', '2024-04-20', '17:18:06', 0, 1),
(8, 100.00, 2, '09876543212345694', '09876543212345697', 1, '+243852721157', 9, NULL, NULL, 'Paiement', '2024-04-20', '17:18:04', 0, 0),
(13, 300000.00, 1, 'REF-NSHOP-0000000002', NULL, 1, '+243971403075', 10, 3000.00000, 10500.00000, '', '2024-04-24', '16:02:31', 1, 1),
(14, 723000.00, 1, 'QUOT00001', NULL, 1, '+243971403075', 8, 7230.00000, 25305.00000, '', '2024-04-28', '14:04:39', 1, 1),
(15, 100000.00, 1, '1000001714389208090', NULL, 1, '+243971403075', 0, 1000.00000, 3500.00000, '', '2024-04-29', '13:14:00', 1, 1),
(16, 258000.00, 1, '1000001714389243616', NULL, 1, '+243971403075', 8, 2580.00000, 9030.00000, '', '2024-04-29', '13:14:57', 1, 1),
(17, 1258000.00, 1, '1000001714389555544', NULL, 1, '+243971403075', 8, 12580.00000, 44030.00000, '', '2024-04-29', '13:20:59', 1, 1),
(18, 120000.00, 1, '1000001714403946359', NULL, 1, '+243971403075', 8, 1200.00000, 4200.00000, '', '2024-04-29', '17:21:19', 0, 1),
(19, 50.00, 2, '1000001714406208053', NULL, 1, '+243971403075', 8, 0.50000, 1.75000, '', '2024-04-29', '17:57:32', 0, 1),
(20, 50.00, 2, '1000001714406253175', NULL, 1, '+243971403075', 8, 0.50000, 1.75000, '', '2024-04-29', '18:03:08', 0, 1),
(21, 50.00, 2, '1000001714406589709', NULL, 1, '+243971403075', 8, 0.50000, 1.75000, '', '2024-04-29', '18:09:07', 0, 1),
(22, 50.00, 1, '1000001714408701462', NULL, 1, '+243971403075', 8, 0.50000, 1.75000, '', '2024-04-29', '18:40:03', 0, 1),
(23, 50.00, 1, '1000001714408701462', NULL, 1, '+243971403075', 8, 0.50000, 1.75000, '', '2024-04-29', '18:41:03', 0, 1),
(24, 10000.00, 1, '1000001714408866246', NULL, 1, '+243971403075', 8, 100.00000, 350.00000, '', '2024-04-29', '18:41:39', 0, 1),
(25, 10000.00, 1, '1000001714408908992', NULL, 1, '+243971403075', 8, 100.00000, 350.00000, '', '2024-04-29', '18:42:20', 0, 1),
(26, 2000.00, 1, '1000001714408948368', NULL, 1, '+243971403075', 8, 20.00000, 70.00000, '', '2024-04-29', '18:43:19', 0, 1),
(27, 100.00, 1, '1000001714463865782', NULL, 1, '+243971403075', 8, 1.00000, 3.50000, '', '2024-04-30', '10:00:39', 0, 1),
(28, 151.00, 1, 'QUOT00005', 'gWbEtrFRngl8243814206248', 1, '+243814206248', 8, 1.51000, 5.28500, '', '2024-04-30', '12:25:40', 0, 1),
(29, 100.00, 1, 'QUOT00006', 'TYOO4bFmJuAq243814206248', 1, '+243814206248', 8, 1.00000, 3.50000, '\n', '2024-04-30', '12:28:24', 0, 1),
(30, 100.00, 1, '1000001714487740454', 'E6W7f2NbKw6z243852721157', 1, '+243852721157', 8, 1.00000, 3.50000, '', '2024-04-30', '16:36:22', 0, 1),
(31, 55.93, 2, 'QUOT000057', '4SAFKniuWUxd243814206248', 1, '+243814206248', 8, 0.55930, 1.95755, '', '2024-04-30', '16:40:22', 0, 1),
(32, 55.93, 2, 'QUOT0000234', 'kIiXbFOTxxGZ243814206248', 1, '+243814206248', 8, 0.55930, 1.95755, '', '2024-04-30', '16:41:06', 0, 1),
(33, 100.00, 1, 'QUOT00002024', 'elYvSLNK8ILN243970404494', 1, '+243970404494', 8, 1.00000, 3.50000, 'Paiement frais scolaire ', '2024-05-06', '14:28:51', 0, 1),
(34, 100.00, 1, 'INV137075', 'wmc5aUu7pFTg243810274370', 1, '+243810274370', 8, 1.00000, 3.50000, '', '2024-05-06', '14:42:23', 0, 1),
(35, 100.00, 1, 'INV137075', 'wmc5aUu7pFTg243810274370', 1, '+243810274370', 8, 1.00000, 3.50000, '', '2024-05-06', '15:13:11', 0, 1),
(36, 10.00, 1, 'INV137075', NULL, 1, '+243810274370', 8, 0.10000, 0.35000, '', '2024-05-06', '18:02:29', 0, 1),
(37, 10.00, 1, 'INV137075', NULL, 1, '+243810274370', 8, 0.10000, 0.35000, '', '2024-05-06', '18:03:48', 0, 1),
(38, 151000.00, 1, 'QUOT00001009', 'VDwlYo5phVWX243810274370', 1, '+243810274370', 8, 1510.00000, 5285.00000, 'Paiement analyse médicale', '2024-05-06', '18:49:48', 0, 1),
(39, 60.00, 2, 'INV137105', 'L42PDAdUcd4y243897774057', 1, '+243897774057', 8, 0.60000, 2.10000, '', '2024-05-10', '09:13:43', 0, 1),
(40, 135000.00, 1, 'INV137154', 'Qnyz3EumRlb8243835669290', 1, '+243835669290', 8, 1350.00000, 4725.00000, '', '2024-05-10', '14:39:04', 0, 1),
(41, 100000.00, 1, 'INV137006', 'Lx86uCFlenwN243992194192', 1, '+243992194192', 8, 1000.00000, 3500.00000, '', '2024-05-10', '16:18:26', 0, 1),
(42, 81000.00, 1, 'INV137135', 'hVaElylScGW8243897774057', 1, '+243897774057', 8, 810.00000, 2835.00000, '', '2024-05-10', '16:21:42', 0, 1),
(43, 13500.00, 1, 'INV137166', 'DwYIvsN0iGkX243992194192', 1, '+243992194192', 8, 135.00000, 472.50000, '', '2024-05-10', '16:24:57', 0, 1),
(44, 13500.00, 1, 'INV137166', 'DwYIvsN0iGkX243992194192', 1, '+243992194192', 8, 135.00000, 472.50000, '', '2024-05-10', '16:25:35', 0, 1),
(45, 13500.00, 1, 'INV137166', 'DwYIvsN0iGkX243992194192', 1, '+243992194192', 8, 135.00000, 472.50000, '', '2024-05-10', '16:27:18', 0, 1),
(46, 13500.00, 1, 'INV137166', NULL, 1, '+243858461400', 8, 135.00000, 472.50000, '', '2024-05-10', '16:33:00', 0, 1),
(47, 13500.00, 1, 'INV137165', NULL, 1, '+243858461400', 8, 135.00000, 472.50000, '', '2024-05-10', '16:34:34', 0, 1),
(48, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 880.00000, 3080.00000, '', '2024-05-14', '14:43:47', 0, 1),
(49, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 880.00000, 3080.00000, '', '2024-05-14', '14:44:03', 0, 1),
(50, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 880.00000, 3080.00000, '', '2024-05-14', '14:44:55', 0, 1),
(51, 32.59, 2, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 0.32590, 1.14065, '', '2024-05-14', '14:46:31', 0, 1),
(52, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243810274370', 8, 880.00000, 3080.00000, '', '2024-05-14', '14:48:19', 0, 1),
(53, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243814206248', 8, 880.00000, 3080.00000, '', '2024-05-14', '14:52:00', 0, 1),
(54, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243814206248', 8, 880.00000, 3080.00000, '', '2024-05-14', '15:49:51', 0, 1),
(55, 32.59, 2, 'INV137185', 'rQgR34gRtb62243897774057', 1, '+243814206248', 8, 0.32590, 1.14065, '', '2024-05-15', '09:44:58', 0, 1),
(56, 88000.00, 1, 'INV137185', 'rQgR34gRtb62243897774057', 1, '+243897774057', 8, 880.00000, 3080.00000, '', '2024-05-15', '09:46:31', 0, 1),
(57, 32.59, 2, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243814206248', 8, 0.32590, 1.14065, '', '2024-05-15', '09:58:38', 0, 1),
(58, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243810274370', 8, 880.00000, 3080.00000, '', '2024-05-15', '12:03:50', 0, 1),
(59, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 880.00000, 3080.00000, '', '2024-05-15', '12:08:48', 0, 1),
(60, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 880.00000, 3080.00000, '', '2024-05-15', '12:09:14', 0, 1),
(61, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 880.00000, 3080.00000, '', '2024-05-15', '12:09:33', 0, 1),
(62, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 880.00000, 3080.00000, '', '2024-05-15', '12:25:28', 0, 1),
(63, 32.59, 2, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 0.32590, 1.14065, '', '2024-05-15', '12:32:15', 0, 1),
(64, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243810274370', 8, 880.00000, 3080.00000, '', '2024-05-15', '12:41:00', 0, 1),
(65, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 880.00000, 3080.00000, '', '2024-05-15', '12:41:30', 0, 1),
(66, 88000.00, 1, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243852721157', 8, 880.00000, 3080.00000, '', '2024-05-15', '12:42:28', 0, 1),
(67, 32.59, 2, 'INV137186', 'msl5wnvL2djw243971403075', 1, '+243971403075', 8, 0.32590, 1.14065, '', '2024-05-15', '12:51:25', 0, 1),
(68, 35.00, 2, 'INV137230', 'icm90gk8ypT2243835669290', 1, '+243971403075', 8, 0.35000, 1.22500, '', '2024-05-15', '12:55:25', 0, 1),
(69, 35.00, 2, 'INV137230', 'icm90gk8ypT2243835669290', 1, '+243810274370', 8, 0.35000, 1.22500, '', '2024-05-15', '12:58:20', 0, 1),
(70, 35.00, 2, 'INV137230', 'icm90gk8ypT2243835669290', 1, '+243897774057', 8, 0.35000, 1.22500, '', '2024-05-15', '13:18:25', 0, 1),
(71, 35.00, 2, 'INV137230', 'icm90gk8ypT2243835669290', 1, '+243835669290', 8, 0.35000, 1.22500, '', '2024-05-15', '13:40:51', 0, 1),
(73, 100.00, 1, '1715869114', 'Z7MAGCZG2Y3y243970404494', 2, '+243970404494', 8, 2.00000, 2.00000, 'Paiement cadeau Pour anniversaire ', '2024-05-16', '16:18:34', NULL, 1),
(74, 36.11, 2, 'INV137323', 'NpNq5m4WVBeH243852721157', 1, '+243971403075', 8, 0.36110, 1.26385, '', '2024-05-20', '14:51:38', 0, 1),
(75, 36.11, 2, 'INV137323', 'NpNq5m4WVBeH243852721157', 1, '+243852721157', 8, 0.36110, 1.26385, '', '2024-05-20', '14:52:40', 0, 1),
(76, 131.30, 2, 'INV137301', 't8hCsZChwvVm243835669290', 1, '+243835669290', 8, 1.31300, 4.59550, '', '2024-05-20', '15:26:04', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `transaction_application`
--

CREATE TABLE `transaction_application` (
  `id_transaction_application` int(11) NOT NULL,
  `id_utilisateur_application` int(11) NOT NULL,
  `id_compte_source` int(11) NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `id_devise` int(11) NOT NULL,
  `type_transaction` enum('payment','recharge','transfer','received') NOT NULL,
  `recipient_phone` varchar(20) DEFAULT NULL,
  `recipient_name` varchar(200) DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','completed','failed','cancelled') NOT NULL DEFAULT 'pending',
  `transaction_ref` varchar(100) NOT NULL,
  `network_ref` varchar(100) DEFAULT NULL,
  `commission_ripa` decimal(10,2) DEFAULT 0.00,
  `commission_network` decimal(10,2) DEFAULT 0.00,
  `failure_reason` varchar(255) DEFAULT NULL,
  `date_transaction` date NOT NULL,
  `heure_transaction` time NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_commission`
--

CREATE TABLE `type_commission` (
  `id_type_commission` int(11) NOT NULL,
  `nom_type_commission` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type_commission`
--

INSERT INTO `type_commission` (`id_type_commission`, `nom_type_commission`) VALUES
(1, 'Commission Mobile Money'),
(2, 'Commission Carte Bancaire');

-- --------------------------------------------------------

--
-- Structure de la table `type_entreprise`
--

CREATE TABLE `type_entreprise` (
  `id_type_entreprise` int(11) NOT NULL,
  `designation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type_entreprise`
--

INSERT INTO `type_entreprise` (`id_type_entreprise`, `designation`) VALUES
(1, 'Système'),
(2, 'Non Système');

-- --------------------------------------------------------

--
-- Structure de la table `type_mobile_money`
--

CREATE TABLE `type_mobile_money` (
  `id_type_mobile_money` int(11) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type_mobile_money`
--

INSERT INTO `type_mobile_money` (`id_type_mobile_money`, `designation`, `logo`) VALUES
(1, 'Airtel Money', 'airtel_money.png'),
(2, 'Orange Money', 'orange_money.png'),
(3, 'M-Pesa', 'voda_mpesa.png'),
(4, 'Afri Money', 'africell.png'),
(5, 'Compte Bancaire', 'bankicon.png');

-- --------------------------------------------------------

--
-- Structure de la table `type_transaction`
--

CREATE TABLE `type_transaction` (
  `id_type_transaction` int(11) NOT NULL,
  `nom_type_transaction` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type_transaction`
--

INSERT INTO `type_transaction` (`id_type_transaction`, `nom_type_transaction`) VALUES
(1, 'Entrée'),
(2, 'Sortie');

-- --------------------------------------------------------

--
-- Structure de la table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` int(11) NOT NULL,
  `id_utilisateur_application` int(11) NOT NULL,
  `default_currency` varchar(3) NOT NULL DEFAULT 'CDF',
  `language` varchar(2) NOT NULL DEFAULT 'fr',
  `notifications_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `biometric_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `show_balance` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `id_sexe` int(11) DEFAULT NULL,
  `id_etat_civil` int(11) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(255) DEFAULT NULL,
  `phone1` varchar(255) DEFAULT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `id_role` int(11) DEFAULT NULL,
  `id_entreprise_utilisateur` int(11) NOT NULL DEFAULT 0,
  `id_etat` int(11) NOT NULL,
  `post_nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_enregistrement_utilisateur` date NOT NULL,
  `is_root` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `id_sexe`, `id_etat_civil`, `date_naissance`, `lieu_naissance`, `phone1`, `phone2`, `email`, `password`, `photo`, `id_role`, `id_entreprise_utilisateur`, `id_etat`, `post_nom`, `prenom`, `date_enregistrement_utilisateur`, `is_root`) VALUES
(1, 'Admin', 1, 2, '2020-12-19', 'Lubumbashi', '0852721157', '0824881290', 'admin@lmds.com', '$2y$10$llxnJ5t2vG4cCg3TI08iMejnP74BFx6hrFs3aPZm7Ojhc98J4ZsxW', 'a19cf-favicon.png', 16, 8, 2, 'admin', 'admin', '0000-00-00', 1),
(9, 'Root', 1, 1, NULL, NULL, '0971403075', NULL, 'root@admin.com', '$2y$10$QhwJdrDTW.0dKAKRkN/WZ./8d6KUsk1m0Zqft3LUuRzP62hVytvey', '02fa5-favicon.png', 1, 0, 0, 'Admin', 'Admin', '0000-00-00', 1),
(10, 'Admin', 2, 2, NULL, NULL, '0900974775', NULL, 'admin@nellashop.com', '$2y$10$2sj2Y/yxNGfStytO5M5J9eZ44LlAs2Yo36lKc8IZPO9AkURDU8Mzy', 'ecab9-logo_noir_sans_slogan.png', 16, 10, 0, 'Nella', 'Shop', '0000-00-00', 1),
(11, 'Admin', 2, 2, NULL, NULL, '+27622050574', NULL, 'admin@cadeaumart.com', '$2y$10$NKk70A2yeYHeGkE9O6ZlTO/eGhmvLGk5oPKVzppDrWLscebKfl.ZO', '58757-logo.png', 16, 9, 0, 'Cadeau', 'mart', '0000-00-00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_application`
--

CREATE TABLE `utilisateur_application` (
  `id_utilisateur_application` int(11) NOT NULL,
  `nom_complet` varchar(200) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `mot_passe_pin` varchar(255) NOT NULL,
  `date_enregistrement` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur_application`
--

INSERT INTO `utilisateur_application` (`id_utilisateur_application`, `nom_complet`, `phone`, `mot_passe_pin`, `date_enregistrement`) VALUES
(12, 'Pascal Mitewu', '+243971403075', '$2y$10$lxEKQ38PtP0aezODL6LXGuOzlFAznhHVWSgcb3WgeotiWybwX.RcC', '2025-10-18');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_recent_transactions`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `v_recent_transactions` (
`id_transaction_application` int(11)
,`id_utilisateur_application` int(11)
,`nom_complet` varchar(200)
,`montant` decimal(15,2)
,`devise` varchar(15)
,`type_transaction` enum('payment','recharge','transfer','received')
,`status` enum('pending','completed','failed','cancelled')
,`recipient_phone` varchar(20)
,`date_transaction` date
,`heure_transaction` time
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_user_transactions_stats`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `v_user_transactions_stats` (
`id_utilisateur_application` int(11)
,`total_transactions` bigint(21)
,`completed_transactions` decimal(22,0)
,`failed_transactions` decimal(22,0)
,`total_amount` decimal(37,2)
,`last_transaction_date` date
);

-- --------------------------------------------------------

--
-- Structure de la vue `v_recent_transactions`
--
DROP TABLE IF EXISTS `v_recent_transactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_recent_transactions`  AS SELECT `t`.`id_transaction_application` AS `id_transaction_application`, `t`.`id_utilisateur_application` AS `id_utilisateur_application`, `u`.`nom_complet` AS `nom_complet`, `t`.`montant` AS `montant`, `d`.`abreviation` AS `devise`, `t`.`type_transaction` AS `type_transaction`, `t`.`status` AS `status`, `t`.`recipient_phone` AS `recipient_phone`, `t`.`date_transaction` AS `date_transaction`, `t`.`heure_transaction` AS `heure_transaction` FROM ((`transaction_application` `t` left join `utilisateur_application` `u` on(`t`.`id_utilisateur_application` = `u`.`id_utilisateur_application`)) left join `devise` `d` on(`t`.`id_devise` = `d`.`id_devise`)) ORDER BY `t`.`created_at` DESC LIMIT 0, 100 ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_user_transactions_stats`
--
DROP TABLE IF EXISTS `v_user_transactions_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_transactions_stats`  AS SELECT `transaction_application`.`id_utilisateur_application` AS `id_utilisateur_application`, count(0) AS `total_transactions`, sum(case when `transaction_application`.`status` = 'completed' then 1 else 0 end) AS `completed_transactions`, sum(case when `transaction_application`.`status` = 'failed' then 1 else 0 end) AS `failed_transactions`, sum(case when `transaction_application`.`status` = 'completed' then `transaction_application`.`montant` else 0 end) AS `total_amount`, max(`transaction_application`.`date_transaction`) AS `last_transaction_date` FROM `transaction_application` GROUP BY `transaction_application`.`id_utilisateur_application` ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `action_utilisateur`
--
ALTER TABLE `action_utilisateur`
  ADD PRIMARY KEY (`id_action_utilisateur`);

--
-- Index pour la table `api_logs`
--
ALTER TABLE `api_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_utilisateur` (`id_utilisateur_application`),
  ADD KEY `idx_endpoint` (`endpoint`),
  ADD KEY `idx_date` (`created_at`),
  ADD KEY `idx_response_code` (`response_code`);

--
-- Index pour la table `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`id_commission`);

--
-- Index pour la table `compte_financier_utilisateur_application`
--
ALTER TABLE `compte_financier_utilisateur_application`
  ADD PRIMARY KEY (`id_compte_financier_utilisateur_application`),
  ADD KEY `idx_utilisateur` (`id_foreign_utilisateur_application`);

--
-- Index pour la table `compte_money_entreprise`
--
ALTER TABLE `compte_money_entreprise`
  ADD PRIMARY KEY (`id_compte_money_entreprise`);

--
-- Index pour la table `devise`
--
ALTER TABLE `devise`
  ADD PRIMARY KEY (`id_devise`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id_entreprise`);

--
-- Index pour la table `etat_bloquer`
--
ALTER TABLE `etat_bloquer`
  ADD PRIMARY KEY (`id_etat_bloquer`);

--
-- Index pour la table `etat_civil`
--
ALTER TABLE `etat_civil`
  ADD PRIMARY KEY (`id_etat_civil`);

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id_facture`);

--
-- Index pour la table `facture_index`
--
ALTER TABLE `facture_index`
  ADD PRIMARY KEY (`id_facture_index`);

--
-- Index pour la table `fonctionnalite`
--
ALTER TABLE `fonctionnalite`
  ADD PRIMARY KEY (`id_fonctionnalite`);

--
-- Index pour la table `group_fonctionnalite`
--
ALTER TABLE `group_fonctionnalite`
  ADD PRIMARY KEY (`id_group_fonctionnalite`);

--
-- Index pour la table `jwt_sessions`
--
ALTER TABLE `jwt_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_utilisateur` (`id_utilisateur_application`),
  ADD KEY `idx_expires` (`expires_at`),
  ADD KEY `idx_active` (`is_active`);

--
-- Index pour la table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_otp_code` (`otp_code`),
  ADD KEY `idx_expires_at` (`expires_at`);

--
-- Index pour la table `pays`
--
ALTER TABLE `pays`
  ADD PRIMARY KEY (`id_pays`);

--
-- Index pour la table `publicite`
--
ALTER TABLE `publicite`
  ADD PRIMARY KEY (`id_publicite`);

--
-- Index pour la table `reference_index`
--
ALTER TABLE `reference_index`
  ADD PRIMARY KEY (`id_reference_index`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id_role_permission`);

--
-- Index pour la table `sexe`
--
ALTER TABLE `sexe`
  ADD PRIMARY KEY (`id_sexe`);

--
-- Index pour la table `taux_echange`
--
ALTER TABLE `taux_echange`
  ADD PRIMARY KEY (`id_taux_echange`);

--
-- Index pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id_transaction`);

--
-- Index pour la table `transaction_application`
--
ALTER TABLE `transaction_application`
  ADD PRIMARY KEY (`id_transaction_application`),
  ADD UNIQUE KEY `transaction_ref_unique` (`transaction_ref`),
  ADD KEY `idx_utilisateur` (`id_utilisateur_application`),
  ADD KEY `idx_compte_source` (`id_compte_source`),
  ADD KEY `idx_date` (`date_transaction`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_type` (`type_transaction`),
  ADD KEY `id_devise` (`id_devise`);

--
-- Index pour la table `type_commission`
--
ALTER TABLE `type_commission`
  ADD PRIMARY KEY (`id_type_commission`);

--
-- Index pour la table `type_entreprise`
--
ALTER TABLE `type_entreprise`
  ADD PRIMARY KEY (`id_type_entreprise`);

--
-- Index pour la table `type_mobile_money`
--
ALTER TABLE `type_mobile_money`
  ADD PRIMARY KEY (`id_type_mobile_money`);

--
-- Index pour la table `type_transaction`
--
ALTER TABLE `type_transaction`
  ADD PRIMARY KEY (`id_type_transaction`);

--
-- Index pour la table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_unique` (`id_utilisateur_application`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- Index pour la table `utilisateur_application`
--
ALTER TABLE `utilisateur_application`
  ADD PRIMARY KEY (`id_utilisateur_application`),
  ADD UNIQUE KEY `phone_unique` (`phone`),
  ADD KEY `idx_date_enregistrement` (`date_enregistrement`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `action_utilisateur`
--
ALTER TABLE `action_utilisateur`
  MODIFY `id_action_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT pour la table `api_logs`
--
ALTER TABLE `api_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commission`
--
ALTER TABLE `commission`
  MODIFY `id_commission` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `compte_financier_utilisateur_application`
--
ALTER TABLE `compte_financier_utilisateur_application`
  MODIFY `id_compte_financier_utilisateur_application` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `compte_money_entreprise`
--
ALTER TABLE `compte_money_entreprise`
  MODIFY `id_compte_money_entreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `devise`
--
ALTER TABLE `devise`
  MODIFY `id_devise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `id_entreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `etat_bloquer`
--
ALTER TABLE `etat_bloquer`
  MODIFY `id_etat_bloquer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `etat_civil`
--
ALTER TABLE `etat_civil`
  MODIFY `id_etat_civil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id_facture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `facture_index`
--
ALTER TABLE `facture_index`
  MODIFY `id_facture_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `fonctionnalite`
--
ALTER TABLE `fonctionnalite`
  MODIFY `id_fonctionnalite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT pour la table `group_fonctionnalite`
--
ALTER TABLE `group_fonctionnalite`
  MODIFY `id_group_fonctionnalite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `jwt_sessions`
--
ALTER TABLE `jwt_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `pays`
--
ALTER TABLE `pays`
  MODIFY `id_pays` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;

--
-- AUTO_INCREMENT pour la table `publicite`
--
ALTER TABLE `publicite`
  MODIFY `id_publicite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `reference_index`
--
ALTER TABLE `reference_index`
  MODIFY `id_reference_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id_role_permission` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT pour la table `sexe`
--
ALTER TABLE `sexe`
  MODIFY `id_sexe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `taux_echange`
--
ALTER TABLE `taux_echange`
  MODIFY `id_taux_echange` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id_transaction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT pour la table `transaction_application`
--
ALTER TABLE `transaction_application`
  MODIFY `id_transaction_application` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type_commission`
--
ALTER TABLE `type_commission`
  MODIFY `id_type_commission` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `type_entreprise`
--
ALTER TABLE `type_entreprise`
  MODIFY `id_type_entreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `type_mobile_money`
--
ALTER TABLE `type_mobile_money`
  MODIFY `id_type_mobile_money` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `type_transaction`
--
ALTER TABLE `type_transaction`
  MODIFY `id_type_transaction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `utilisateur_application`
--
ALTER TABLE `utilisateur_application`
  MODIFY `id_utilisateur_application` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `jwt_sessions`
--
ALTER TABLE `jwt_sessions`
  ADD CONSTRAINT `jwt_sessions_ibfk_1` FOREIGN KEY (`id_utilisateur_application`) REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE;

--
-- Contraintes pour la table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD CONSTRAINT `otp_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE;

--
-- Contraintes pour la table `transaction_application`
--
ALTER TABLE `transaction_application`
  ADD CONSTRAINT `transaction_application_ibfk_1` FOREIGN KEY (`id_utilisateur_application`) REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_application_ibfk_2` FOREIGN KEY (`id_compte_source`) REFERENCES `compte_financier_utilisateur_application` (`id_compte_financier_utilisateur_application`),
  ADD CONSTRAINT `transaction_application_ibfk_3` FOREIGN KEY (`id_devise`) REFERENCES `devise` (`id_devise`);

--
-- Contraintes pour la table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`id_utilisateur_application`) REFERENCES `utilisateur_application` (`id_utilisateur_application`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
