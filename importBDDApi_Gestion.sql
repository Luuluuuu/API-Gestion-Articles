-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 31 mars 2023 à 11:56
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `api_gestion`
--
CREATE DATABASE IF NOT EXISTS api_gestion;
-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `IdArticle` int(11) NOT NULL,
  `DatePublication` datetime NOT NULL,
  `Contenu` text NOT NULL,
  `IdUtilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`IdArticle`, `DatePublication`, `Contenu`, `IdUtilisateur`) VALUES
(1, '2023-03-10 10:40:35', 'Bonjour ! C\'est moi', 2),
(2, '2023-03-10 10:41:03', 'Bonjour ! C\'est pas moi', 1),
(3, '2023-03-10 10:41:22', 'It\'s me !', 4),
(4, '2023-03-10 10:41:35', 'Fantin approuve', 3),
(5, '2023-03-10 10:41:42', 'Waaaaa', 4),
(6, '2023-03-10 10:41:51', 'Il faut travailler', 5),
(7, '2023-03-10 10:41:58', 'J\'ai besoin d\'aide', 3),
(8, '2023-03-10 10:42:06', 'Quoi ?', 1),
(9, '2023-03-10 10:42:23', 'FEUR !! ', 3),
(10, '2023-03-22 14:45:03', 'yry', 1),
(23, '2023-03-30 08:52:12', 'test client', 3);

-- --------------------------------------------------------

--
-- Structure de la table `manipuler`
--

CREATE TABLE `manipuler` (
  `IdUtilisateur` int(11) NOT NULL,
  `IdArticle` int(11) NOT NULL,
  `ALike` tinyint(1) NOT NULL DEFAULT 0,
  `ADislike` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `manipuler`
--

INSERT INTO `manipuler` (`IdUtilisateur`, `IdArticle`, `ALike`, `ADislike`) VALUES
(2, 9, 1, 0),
(3, 6, 0, 1),
(3, 9, 1, 0),
(3, 23, 0, 1),
(4, 1, 1, 0),
(5, 6, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `IdUtilisateur` int(11) NOT NULL,
  `NomUtilisateur` varchar(50) NOT NULL,
  `MotDePasse` varchar(50) NOT NULL,
  `RoleU` enum('Moderator','Publisher') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`IdUtilisateur`, `NomUtilisateur`, `MotDePasse`, `RoleU`) VALUES
(1, 'TataB0b0', 'tuttut', 'Moderator'),
(2, 'Lulu', 'uwu', 'Moderator'),
(3, 'Fantin', 'pwet', 'Publisher'),
(4, 'Mario', 'wihi', 'Publisher'),
(5, 'Prof', 'travail', 'Publisher');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`IdArticle`),
  ADD KEY `IdUtilisateur` (`IdUtilisateur`);

--
-- Index pour la table `manipuler`
--
ALTER TABLE `manipuler`
  ADD PRIMARY KEY (`IdUtilisateur`,`IdArticle`),
  ADD KEY `IdArticle` (`IdArticle`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`IdUtilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `IdArticle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `IdUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`IdUtilisateur`) REFERENCES `utilisateur` (`IdUtilisateur`);

--
-- Contraintes pour la table `manipuler`
--
ALTER TABLE `manipuler`
  ADD CONSTRAINT `manipuler_ibfk_1` FOREIGN KEY (`IdUtilisateur`) REFERENCES `utilisateur` (`IdUtilisateur`),
  ADD CONSTRAINT `manipuler_ibfk_2` FOREIGN KEY (`IdArticle`) REFERENCES `article` (`IdArticle`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
