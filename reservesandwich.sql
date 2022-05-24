-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 24 mai 2022 à 22:23
-- Version du serveur : 5.7.11
-- Version de PHP : 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `reservesandwich`
--

-- --------------------------------------------------------

--
-- Structure de la table `accueil`
--

CREATE TABLE `accueil` (
  `id_accueil` int(11) NOT NULL,
  `texte_accueil` text NOT NULL,
  `lien_pdf` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `accueil`
--

INSERT INTO `accueil` (`id_accueil`, `texte_accueil`, `lien_pdf`) VALUES
(1, 'azezeaffqsdsdqszerfsdsdfsdsfdfc:D', 'menu.pdf');

-- --------------------------------------------------------

--
-- Structure de la table `boisson`
--

CREATE TABLE `boisson` (
  `id_boisson` int(11) NOT NULL,
  `nom_boisson` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dispo_boisson` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `boisson`
--

INSERT INTO `boisson` (`id_boisson`, `nom_boisson`, `dispo_boisson`) VALUES
(1, 'Coca-Cola', 1),
(2, 'Fanta', 1),
(3, 'Eau', 1),
(4, 'Sprite', 1),
(5, 'SevenUp', 1);

-- --------------------------------------------------------

--
-- Structure de la table `chips`
--

CREATE TABLE `chips` (
  `valeur` varchar(3) NOT NULL,
  `id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `chips`
--

INSERT INTO `chips` (`valeur`, `id`) VALUES
('non', 0),
('oui', 1);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_com` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `fk_sandwich_id` int(11) NOT NULL,
  `fk_boisson_id` int(11) NOT NULL,
  `fk_dessert_id` int(11) NOT NULL,
  `chips_com` tinyint(1) NOT NULL,
  `date_heure_com` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_heure_livraison_com` datetime NOT NULL,
  `annule_com` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_com`, `fk_user_id`, `fk_sandwich_id`, `fk_boisson_id`, `fk_dessert_id`, `chips_com`, `date_heure_com`, `date_heure_livraison_com`, `annule_com`) VALUES
(27, 4, 2, 4, 2, 0, '2022-05-15 17:27:45', '2022-05-12 19:23:00', 0),
(28, 4, 2, 4, 2, 0, '2022-05-15 17:28:05', '2022-05-12 19:23:00', 0),
(29, 4, 2, 4, 2, 0, '2022-05-15 17:28:42', '2022-05-12 19:23:00', 0),
(30, 4, 2, 4, 2, 0, '2022-05-15 17:34:56', '2022-05-13 19:34:00', 0),
(31, 4, 3, 1, 5, 0, '2022-05-15 17:35:38', '2022-05-05 19:35:00', 0),
(32, 4, 3, 1, 5, 0, '2022-05-15 17:38:04', '2022-05-05 19:35:00', 0),
(34, 4, 1, 3, 1, 0, '2022-05-15 17:40:51', '2022-05-20 19:40:00', 0),
(35, 4, 1, 2, 1, 0, '2022-05-15 19:39:01', '2022-05-25 20:12:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `dessert`
--

CREATE TABLE `dessert` (
  `id_dessert` int(11) NOT NULL,
  `nom_dessert` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dispo_dessert` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dessert`
--

INSERT INTO `dessert` (`id_dessert`, `nom_dessert`, `dispo_dessert`) VALUES
(1, 'Cookie', 1),
(2, 'Brownie', 1),
(3, 'Donut\'s', 1),
(4, 'Beignet pomme', 1),
(5, 'Beignet chocolat', 1);

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `id_hist` int(11) NOT NULL,
  `dateDebut_hist` date NOT NULL,
  `dateFin_hist` date NOT NULL,
  `dateInsertion_hist` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `historique`
--

INSERT INTO `historique` (`id_hist`, `dateDebut_hist`, `dateFin_hist`, `dateInsertion_hist`, `fk_user_id`) VALUES
(3, '2022-04-09', '2022-04-07', '2022-05-15 19:38:05', 1),
(4, '2022-04-09', '2022-04-10', '2022-05-15 19:39:43', 1);

-- --------------------------------------------------------

--
-- Structure de la table `sandwich`
--

CREATE TABLE `sandwich` (
  `id_sandwich` int(11) NOT NULL,
  `nom_sandwich` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dispo_sandwich` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sandwich`
--

INSERT INTO `sandwich` (`id_sandwich`, `nom_sandwich`, `dispo_sandwich`) VALUES
(1, 'Sandwich Jambon', 1),
(2, 'Sandwich Poulet', 1),
(3, 'Sandwich Thon', 1),
(4, 'Sandwich Crudités', 0),
(5, 'Panini', 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_user` int(11) NOT NULL,
  `role_user` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_user` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active_user` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `role_user`, `email_user`, `password_user`, `nom_user`, `prenom_user`, `active_user`) VALUES
(1, 'a', 'administrateur@wanadoo.fr', '$argon2i$v=19$m=1024,t=2,p=2$bFM4ZGMwNEI3VjhhQkpBRQ$ExWXG8bO/dUOevny8qU6ig', 'IDASIAK', 'Mikael', 1),
(3, 'u', 'machin@truc.fr', '$argon2i$v=19$m=1024,t=2,p=2$aWViRXhRRnlBS21yLnlPSQ$NuHvFVx/T1qfmDaF/3sNpqo50k+MxAEJEOUE6BFn+GA', 'Truc', 'Bidule', 1),
(4, 'u', 'trucmuche@orange.fr', '$argon2i$v=19$m=1024,t=2,p=2$TnYzbkVZN0kyWktQdGhsZQ$546iPke91o1b1yXdx6NlLWz0yuWaI6foljAkx28/R00', 'Machin', 'Bidule', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accueil`
--
ALTER TABLE `accueil`
  ADD PRIMARY KEY (`id_accueil`);

--
-- Index pour la table `boisson`
--
ALTER TABLE `boisson`
  ADD PRIMARY KEY (`id_boisson`);

--
-- Index pour la table `chips`
--
ALTER TABLE `chips`
  ADD PRIMARY KEY (`valeur`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_com`),
  ADD KEY `IDX_B15F29ADCF8EC6B0` (`fk_sandwich_id`),
  ADD KEY `IDX_B15F29AD10326266` (`fk_boisson_id`),
  ADD KEY `IDX_B15F29AD83C52771` (`fk_dessert_id`),
  ADD KEY `IDX_B15F29AD996F9D6F` (`fk_user_id`);

--
-- Index pour la table `dessert`
--
ALTER TABLE `dessert`
  ADD PRIMARY KEY (`id_dessert`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`id_hist`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Index pour la table `sandwich`
--
ALTER TABLE `sandwich`
  ADD PRIMARY KEY (`id_sandwich`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email_user` (`email_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `boisson`
--
ALTER TABLE `boisson`
  MODIFY `id_boisson` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_com` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `dessert`
--
ALTER TABLE `dessert`
  MODIFY `id_dessert` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
  MODIFY `id_hist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `sandwich`
--
ALTER TABLE `sandwich`
  MODIFY `id_sandwich` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_B15F29AD10326266` FOREIGN KEY (`fk_boisson_id`) REFERENCES `boisson` (`id_boisson`),
  ADD CONSTRAINT `FK_B15F29AD83C52771` FOREIGN KEY (`fk_dessert_id`) REFERENCES `dessert` (`id_dessert`),
  ADD CONSTRAINT `FK_B15F29AD996F9D6F` FOREIGN KEY (`fk_user_id`) REFERENCES `utilisateur` (`id_user`),
  ADD CONSTRAINT `FK_B15F29ADCF8EC6B0` FOREIGN KEY (`fk_sandwich_id`) REFERENCES `sandwich` (`id_sandwich`);

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`fk_user_id`) REFERENCES `utilisateur` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
