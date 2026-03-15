-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 15 mars 2026 à 20:25
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
-- Base de données : `coaching_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_naissance` date NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `email`, `nom`, `prenom`, `mot_de_passe`, `date_naissance`, `admin`, `created_at`) VALUES
(1, 'mountain.orange.418@example.com', 'Dupont', 'Jean', '$2y$10$7r.1ryRYXOs820Wve5DBaOviMP2Gxpjb/geXP9T13LpLoLfTM70Pe', '2000-02-01', 0, '2026-03-13 17:28:08'),
(2, 'groscaca@email.com', 'bla', 'bla', '$2y$10$Sxhio/0VkGM4qSrtnXg0leeSvFofGEecAOKrx8OPMboWXdoM8JNTe', '2015-02-27', 0, '2026-03-13 17:35:21');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_slot` (`date`,`time`),
  ADD KEY `fk_appointments_user` (`user_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointments_user` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
