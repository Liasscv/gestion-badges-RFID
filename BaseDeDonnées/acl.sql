-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 26 mai 2025 à 16:16
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
-- Base de données : `acl`
--

-- --------------------------------------------------------

--
-- Structure de la table `badge`
--

CREATE TABLE `badge` (
  `id_badge` int(11) NOT NULL,
  `code_badge` varchar(10) NOT NULL,
  `etat` tinyint(1) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `badge`
--

INSERT INTO `badge` (`id_badge`, `code_badge`, `etat`, `id_utilisateur`) VALUES
(10, 'D57B9AC3', 1, 11),
(12, '704EE2E9', 1, 13),
(13, '01221', 0, 14),
(14, 'EA451571', 1, 15),
(15, '6643DE9D', 0, 16);

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `id_historique` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `statut` tinyint(1) NOT NULL,
  `id_badge` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `historique`
--

INSERT INTO `historique` (`id_historique`, `date`, `heure`, `statut`, `id_badge`) VALUES
(19, '2025-04-16', '10:28:26', 1, 13),
(20, '2025-04-16', '11:36:15', 1, 15),
(21, '2025-04-16', '11:36:18', 1, 15),
(22, '2025-04-16', '11:36:18', 1, 15),
(23, '2025-04-16', '11:36:21', 1, 15),
(24, '2025-04-17', '17:14:27', 1, 14),
(25, '2025-04-17', '17:14:36', 1, 12),
(26, '2025-04-17', '17:14:41', 0, 13),
(27, '2025-04-17', '17:14:44', 1, 10),
(28, '2025-04-17', '17:14:50', 0, 15),
(29, '2025-05-26', '15:01:06', 0, 13),
(30, '2025-05-26', '15:01:13', 1, 14),
(31, '2025-05-26', '15:01:23', 1, 12),
(32, '2025-05-26', '15:01:29', 1, 10),
(33, '2025-05-26', '15:01:39', 1, 10),
(34, '2025-05-26', '15:01:51', 0, 15);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `nom`, `heure_debut`, `heure_fin`) VALUES
(1, 'Lycéen', '08:00:00', '18:00:00'),
(2, 'Administration', '00:00:00', '23:59:59'),
(3, 'Personnel', '06:00:00', '19:00:00'),
(4, 'Etudiant', '08:00:00', '18:00:00'),
(5, 'Agent', '07:00:00', '18:30:00'),
(6, 'Technicien', '06:00:00', '22:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `id_role`) VALUES
(11, 'Cosme Vinou', 'Elias', 3),
(13, 'Diango', 'Akouman', 2),
(14, 'Clement', 'Ethan', 1),
(15, 'Gauthier', 'Elias', 5),
(16, 'Metais', 'Romain', 4);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_web`
--

CREATE TABLE `utilisateur_web` (
  `id_utilisateur_web` int(11) NOT NULL,
  `login` varchar(40) NOT NULL,
  `mot_de_passe` varchar(64) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur_web`
--

INSERT INTO `utilisateur_web` (`id_utilisateur_web`, `login`, `mot_de_passe`, `role`) VALUES
(1, 'agent@lp2i-poitiers.fr', '$2y$10$ZPElnqDFT1jaceZmHdBeuufuVda46q40WP5viP20IfZ1ZwPMs0ksa', 'Agent'),
(2, 'technicien@lp2i-poitiers.fr', '$2y$10$ZOngAdQJbhzaqaa73F7O5uv1GaCiEPTOSz4hUon3dqwvScJMurEuy', 'Technicien');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `badge`
--
ALTER TABLE `badge`
  ADD PRIMARY KEY (`id_badge`),
  ADD KEY `Badge_Utilisateur_FK` (`id_utilisateur`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`id_historique`),
  ADD KEY `Historique_Badge_FK` (`id_badge`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD KEY `Utilisateur_Role_FK` (`id_role`);

--
-- Index pour la table `utilisateur_web`
--
ALTER TABLE `utilisateur_web`
  ADD PRIMARY KEY (`id_utilisateur_web`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `badge`
--
ALTER TABLE `badge`
  MODIFY `id_badge` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
  MODIFY `id_historique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `utilisateur_web`
--
ALTER TABLE `utilisateur_web`
  MODIFY `id_utilisateur_web` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `badge`
--
ALTER TABLE `badge`
  ADD CONSTRAINT `Badge_Utilisateur_FK` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `Historique_Badge_FK` FOREIGN KEY (`id_badge`) REFERENCES `badge` (`id_badge`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `Utilisateur_Role_FK` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
