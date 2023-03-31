-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 31 mars 2023 à 21:06
-- Version du serveur : 10.6.10-MariaDB-cll-lve
-- Version de PHP : 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `u563109936_Blog_FI`
--

-- --------------------------------------------------------

--
-- Structure de la table `Articles`
--

CREATE TABLE `Articles` (
  `IdArticle` int(11) NOT NULL,
  `Title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `DateModif` date DEFAULT NULL,
  `DatePub` date NOT NULL,
  `IdUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Articles`
--

INSERT INTO `Articles` (`IdArticle`, `Title`, `Content`, `DateModif`, `DatePub`, `IdUser`) VALUES
(1, 'Top 5 des Langages de programmation en 2023', 'L’institute of Electrical and Electronics Enginees (IEEE) a publié son classement annuel pour déterminer les meilleurs langages de programmation. Il est temps d’y jeter un œil pour avoir une idée du nouveau langage de programmation sur lequel se former cette nouvelle année. En 2021, Python arrivait en tête du classement. Cette année, c’est... Python, qui une nouvelle fois l’emporte. Explications\nAfin de mesurer la demande employeur de tel ou tel langage, l’organisation s’est basée sur les données de grands sites de recrutement tel que CareerBuilder et Dice. Il est donc important de prendre du recul par rapport aux observations de l’IEEE, car celles-ci se basent sur les offres d’emploi aux Etats-Unis uniquement, et ne reflète donc pas forcément fidèlement la demande française. Cela étant, puisque le pays a généralement une longueur d’avance sur le reste du monde en la matière, il demeure très intéressant de se pencher sur les résultats pour s’attendre aux mêmes phénomènes de croissance en France dans les années à venir', NULL, '2023-03-15', 4),
(2, 'Comment installer Git sur Ubuntu', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', NULL, '2023-03-05', 2),
(3, 'L\'IUT Informatique', 'Texte pour affichage', '2023-03-30', '2023-03-24', 2),
(4, 'Bonjour à tous', 'les amis', NULL, '2023-03-30', 5),
(5, 'Test de l\'api publiée !', 'la modification marche aussi !', '2023-03-31', '2023-03-31', 2),
(6, 'Pourquoi le BUT Informatique est une bonne formation', 'On y apprend le JavaScript... etc', NULL, '2023-03-31', 2),
(7, 'test', 'mytest', NULL, '2023-03-31', 2),
(8, 'I am a test', 'testing', NULL, '2023-03-31', 2),
(9, 'I am another test', 'Hello there are you wondering what I am doing?', NULL, '2023-03-31', 2),
(10, 'testing', 'dont worry this is the last one', NULL, '2023-03-31', 2);

-- --------------------------------------------------------

--
-- Structure de la table `Evaluate`
--

CREATE TABLE `Evaluate` (
  `IdArticle` int(11) NOT NULL,
  `IdUser` int(11) NOT NULL,
  `Liked` tinyint(1) DEFAULT NULL,
  `Disliked` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Evaluate`
--

INSERT INTO `Evaluate` (`IdArticle`, `IdUser`, `Liked`, `Disliked`) VALUES
(1, 5, 0, 1),
(2, 2, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `IdUser` int(11) NOT NULL,
  `UserName` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Role` char(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ;

--
-- Déchargement des données de la table `Users`
--

INSERT INTO `Users` (`IdUser`, `UserName`, `Password`, `Role`) VALUES
(1, 'Fabio', 'pwdFabio123', 'Moderator'),
(2, 'Ismail', 'pwsIsmail234', 'Publisher'),
(3, 'Yael', 'pwdYael567', 'Moderator'),
(4, 'Florent', 'pwdFlorent', 'Publisher'),
(5, 'Yato', 'pwsYato234', 'Publisher'),
(6, 'CrousResto', 'pwsCrous485', 'Moderator');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Articles`
--
ALTER TABLE `Articles`
  ADD PRIMARY KEY (`IdArticle`),
  ADD KEY `IdUser` (`IdUser`);

--
-- Index pour la table `Evaluate`
--
ALTER TABLE `Evaluate`
  ADD PRIMARY KEY (`IdArticle`,`IdUser`),
  ADD KEY `IdUser` (`IdUser`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`IdUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Articles`
--
ALTER TABLE `Articles`
  MODIFY `IdArticle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `IdUser` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Articles`
--
ALTER TABLE `Articles`
  ADD CONSTRAINT `Articles_ibfk_1` FOREIGN KEY (`IdUser`) REFERENCES `Users` (`IdUser`);

--
-- Contraintes pour la table `Evaluate`
--
ALTER TABLE `Evaluate`
  ADD CONSTRAINT `Evaluate_ibfk_1` FOREIGN KEY (`IdArticle`) REFERENCES `Articles` (`IdArticle`),
  ADD CONSTRAINT `Evaluate_ibfk_2` FOREIGN KEY (`IdUser`) REFERENCES `Users` (`IdUser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
