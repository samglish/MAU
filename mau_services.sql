-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 23 fév. 2026 à 07:02
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
-- Base de données : `mau_services`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `service` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'En attente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `user_id`, `service`, `description`, `statut`, `created_at`) VALUES
(1, 5, 'sam', 'sdisdsidsd', 'Validée', '2026-02-23 01:09:36');

-- --------------------------------------------------------

--
-- Structure de la table `demandes_dossier`
--

CREATE TABLE `demandes_dossier` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type_dossier` varchar(100) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'En attente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demandes_dossier`
--

INSERT INTO `demandes_dossier` (`id`, `user_id`, `type_dossier`, `document`, `statut`, `created_at`) VALUES
(1, 5, 'j', '../uploads/1771808851_index.php', 'En attente', '2026-02-23 01:07:31'),
(2, 5, 'Master', '../uploads/1771822347_FICHE_D_IDENTIFICATION_ATG _ BOUIMON EMMANUEL.pdf', 'En attente', '2026-02-23 04:52:27'),
(3, 5, 'Master', '../uploads/1771822386_FICHE_D_IDENTIFICATION_ATG _ BOUIMON EMMANUEL.pdf', 'Validé', '2026-02-23 04:53:06');

-- --------------------------------------------------------

--
-- Structure de la table `departements`
--

CREATE TABLE `departements` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) DEFAULT NULL,
  `faculte` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `departements`
--

INSERT INTO `departements` (`id`, `nom`, `faculte`, `description`, `latitude`, `longitude`) VALUES
(4, 'Computer Science', 'Faculty of Computing', 'Computer Science Department', '9.2658', '12.4539'),
(5, 'Physics', 'Faculty of Physical Sciences', 'Physics Department', '9.2660', '12.4542'),
(6, 'Mathematics', 'Faculty of Physical Sciences', 'Mathematics Department', '9.2655', '12.4540'),
(7, 'Chemistry', 'Faculty of Physical Sciences', 'Chemistry Department', '9.2662', '12.4545'),
(8, 'Mechanical Engineering', 'Faculty of Engineering', 'Mechanical Engineering Department', '9.2670', '12.4550'),
(9, 'Civil Engineering', 'Faculty of Engineering', 'Civil Engineering Department', '9.2674', '12.4548'),
(10, 'Electrical Engineering', 'Faculty of Engineering', 'Electrical & Electronics Engineering', '9.2672', '12.4553'),
(11, 'Agricultural & Environmental Engineering', 'Faculty of Engineering', 'Agri & Env Eng', '9.2668', '12.4546'),
(12, 'Accounting', 'Faculty of Social & Management Sciences', 'Accounting Department', '9.2659', '12.4537'),
(13, 'Economics', 'Faculty of Social & Management Sciences', 'Economics Department', '9.2657', '12.4535'),
(14, 'Business Administration', 'Faculty of Social & Management Sciences', 'Business Administration Dept', '9.2656', '12.4538'),
(15, 'Banking & Finance', 'Faculty of Social & Management Sciences', 'Banking & Finance Dept', '9.2654', '12.4536'),
(16, 'Biochemistry', 'Faculty of Life Sciences', 'Biochemistry Department', '9.2661', '12.4543'),
(17, 'Microbiology', 'Faculty of Life Sciences', 'Microbiology Department', '9.2663', '12.4544'),
(18, 'Plant Science', 'Faculty of Life Sciences', 'Plant Science Department', '9.2665', '12.4541'),
(19, 'Zoology', 'Faculty of Life Sciences', 'Zoology Department', '9.2667', '12.4547'),
(20, 'Architecture', 'Faculty of Environmental Sciences', 'Architecture Department', '9.2680', '12.4560'),
(21, 'Surveying & Geoinformatics', 'Faculty of Environmental Sciences', 'Surveying & Geoinformatics Dept', '9.2682', '12.4562'),
(22, 'Urban & Regional Planning', 'Faculty of Environmental Sciences', 'Urban & Regional Planning Dept', '9.2684', '12.4564'),
(23, 'Soil Science', 'Faculty of Agriculture', 'Soil Science Dept', '9.2690', '12.4570'),
(24, 'Animal Science', 'Faculty of Agriculture', 'Animal Science Department', '9.2688', '12.4568'),
(25, 'Crop Production & Horticulture', 'Faculty of Agriculture', 'Crop Production & Horticulture Dept', '9.2692', '12.4572');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'BEIDI', 'beidisamuel11@gmail.com', '1234', 'admin', '2026-02-23 00:06:11'),
(5, 'b', 'sam@gmail.com', '$2y$10$DFWMMoHVQ7pZPNhTJWDNfOM2ZFFPCW1t2O/KDuFzhgHHbNi0g3maa', 'admin', '2026-02-23 01:06:07');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demandes_dossier`
--
ALTER TABLE `demandes_dossier`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `demandes_dossier`
--
ALTER TABLE `demandes_dossier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `departements`
--
ALTER TABLE `departements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
