-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 05 oct. 2021 à 21:23
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `room`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int(5) NOT NULL AUTO_INCREMENT,
  `id_membre` int(5) NOT NULL,
  `id_salle` int(3) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_salle` (`id_salle`),
  KEY `id_membre` (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id_avis`, `id_membre`, `id_salle`, `commentaire`, `note`, `date_enregistrement`) VALUES
(12, 2, 16, 'top', 5, '2021-09-25 19:22:59'),
(15, 2, 11, 'pas mal mais bon....', 3, '2021-09-26 01:43:52'),
(20, 2, 6, 'bonne salle', 4, '2021-09-26 16:40:45'),
(23, 2, 11, 'awesome!!', 5, '2021-09-26 17:08:03'),
(24, 2, 16, 'trop cool', 5, '2021-09-27 02:07:01'),
(25, 2, 7, 'wahou', 5, '2021-09-27 14:41:28'),
(26, 2, 17, 'Très confortable', 4, '2021-09-29 01:18:14'),
(27, 2, 10, 'Presque parfait...', 3, '2021-09-29 01:18:48'),
(28, 2, 12, 'Le meilleur lieu pour vos réunions !', 5, '2021-09-29 01:19:30'),
(29, 2, 7, 'Une forte odeur dans cette salle...', 2, '2021-09-29 01:20:33'),
(30, 2, 6, 'Vraiment top', 4, '2021-09-29 01:20:57'),
(31, 2, 13, 'OK', 3, '2021-09-29 01:43:20'),
(32, 2, 14, 'Pas génial', 2, '2021-09-29 01:43:42'),
(33, 2, 6, 'Très propre :)', 5, '2021-10-02 13:54:44'),
(34, 3, 6, 'Très confortable et bien situé', 5, '2021-10-04 01:05:51'),
(35, 9, 10, 'Super lieu !', 4, '2021-10-04 01:20:24'),
(36, 2, 13, 'vraiment pas terrible', 1, '2021-10-05 00:20:28'),
(37, 2, 10, 'La meilleure salle du monde', 5, '2021-10-05 21:47:58');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(5) DEFAULT NULL,
  `id_produit` int(3) DEFAULT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_produit` (`id_produit`),
  KEY `id_membre` (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_membre`, `id_produit`, `date_enregistrement`) VALUES
(45, 2, 31, '2021-10-03 23:39:14'),
(46, 2, 10, '2021-10-04 01:01:28'),
(47, 2, 23, '2021-10-04 01:01:58'),
(49, 3, 3, '2021-10-04 01:05:00'),
(50, 3, 4, '2021-10-04 01:05:18'),
(51, 3, 34, '2021-10-04 01:06:06'),
(52, 3, 8, '2021-10-04 01:06:18'),
(53, 3, 9, '2021-10-04 01:07:20'),
(54, 6, 16, '2021-10-04 01:08:04'),
(55, 6, 17, '2021-10-04 01:08:16'),
(56, 6, 24, '2021-10-04 01:08:23'),
(57, 6, 7, '2021-10-04 01:08:32'),
(58, 6, 18, '2021-10-04 01:08:40'),
(59, 6, 5, '2021-10-04 01:08:44'),
(60, 2, 22, '2021-10-04 01:16:39'),
(61, 2, 27, '2021-10-04 01:16:47'),
(62, 9, 14, '2021-10-04 01:19:58'),
(63, 9, 25, '2021-10-04 01:20:05'),
(64, 2, 15, '2021-10-05 00:18:08'),
(65, 2, 2, '2021-10-05 21:49:15');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(5) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `civilite` enum('m','f','nb') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(2, 'admin', '$2y$10$M4As680JCz94Zk9I8EJB4uhb5bapyqiz6FfnUmLe2sNwqkG.M1j/C', 'Herou', 'Julien', 'julien.herou.a@gmail.com', 'm', 2, '2021-09-10 15:38:38'),
(3, 'test', '$2y$10$yGLO6nndQ66.N1pyRduMrePdUgTABlaZMG4oO9h0S6.q/ab3PJoTS', 'Heraud', 'Sybille', 'test@gmail.com', 'f', 1, '2021-09-10 15:39:16'),
(6, 'test2', '$2y$10$XWRFucYfTxBgu/LuUqCbUOVvDNlx/btA28dvTBxw0eV/HRRGSrBiq', 'HERAUD', 'Sybille', 'sybille.heraud@gmail.com', 'nb', 1, '2021-09-11 13:38:49'),
(8, 'BovinChevalin', '$2y$10$cSP5vhrIvOKxbSwD38cbnelH1r5ywczE08h0CyRIr0ioMRO6dvhVe', 'Bovin', 'Chevalin', 'BovinChevalin@gmail.com', 'nb', 2, '2021-09-16 00:11:41'),
(9, 'test3', '$2y$10$TBIC8l.e0FUTou0hvMATo.PWc8CpvH9VBOiOljYzcwtZsXIet9lai', 'HERAUD', 'Sybille', 'sybille.heraud@gmail.com', 'f', 1, '2021-09-25 23:02:51'),
(10, 'JulienH', '$2y$10$0oYxYzIJ9UQFS9UXiiXg2O8Br1VSswwscK8xEiGT4Q1MOlRt3Wqdu', 'Herou', 'Julien', 'julien.herou.a@gmail.com', 'm', 1, '2021-09-30 00:41:59'),
(11, 'SybilleH', '$2y$10$DM0aC/sf.4H0sHIeuUNPCe0LZVHVd8jXWVoAwdLGdkU/U50ApAMAC', 'Heraud', 'Sybille', 'sybille.heraud@gmail.com', 'f', 1, '2021-09-30 00:43:54'),
(12, 'hello', '$2y$10$XwO2o89OJoCCAjS0lSxfDe025Vst2ZTqH20hgJ3uD.3TSKHRDd.ym', 'Doe', 'John', 'hello@gmail.com', 'm', 1, '2021-10-05 01:58:41'),
(13, 'test4', '$2y$10$3ATUxMp/JJYEjU4FDQZNzuGAzPKANUldC1kUK5PBkeMGSYS1SCCg6', 'Herou', 'Julien', 'test4@mail.fr', 'm', 1, '2021-10-05 03:16:52'),
(14, 'robert', '$2y$10$Emkw.Wr54fcUQ0hSBVRYwe79kwwNp4XkFmPtouVNBUqV27WXdSR3i', 'Herou', 'Julien', 'julien.herou.a@gmail.com', 'm', 1, '2021-10-05 04:07:00'),
(15, 'test5', '$2y$10$rd7ezFJmZWjTlBBIfPsB9eSXcW49ql76.sjsLGcpR0pg0nF0AqnLy', 'HERAUD', 'Sybille', 'sybille.heraud@gmail.com', 'f', 1, '2021-10-05 04:12:48');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` int(3) NOT NULL AUTO_INCREMENT,
  `id_salle` int(3) NOT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(5) NOT NULL,
  `etat` enum('libre','reservation') NOT NULL,
  PRIMARY KEY (`id_produit`),
  KEY `produit_ibfk_1` (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `id_salle`, `date_arrivee`, `date_depart`, `prix`, `etat`) VALUES
(1, 12, '2021-10-10 00:00:00', '2021-10-13 00:00:00', 1000, 'libre'),
(2, 6, '2021-11-01 00:00:00', '2021-11-03 00:00:00', 600, 'reservation'),
(3, 6, '2021-10-24 00:00:00', '2021-10-26 00:00:00', 500, 'reservation'),
(4, 6, '2021-10-10 00:00:00', '2021-10-12 00:00:00', 700, 'reservation'),
(5, 7, '2021-10-13 00:00:00', '2021-10-21 00:00:00', 3500, 'reservation'),
(7, 10, '2021-10-17 00:00:00', '2021-10-18 00:00:00', 900, 'reservation'),
(8, 18, '2021-10-12 00:00:00', '2021-10-16 00:00:00', 500, 'reservation'),
(9, 11, '2021-12-01 00:00:00', '2021-12-02 00:00:00', 600, 'reservation'),
(10, 16, '2021-09-17 00:00:00', '2021-09-18 00:00:00', 500, 'reservation'),
(12, 17, '2021-09-21 00:00:00', '2021-09-23 00:00:00', 1200, 'libre'),
(14, 9, '2021-09-29 00:00:00', '2021-09-30 00:00:00', 900, 'reservation'),
(15, 13, '2021-10-01 00:00:00', '2021-10-03 00:00:00', 1200, 'reservation'),
(16, 14, '2021-10-02 00:00:00', '2021-10-06 00:00:00', 1400, 'reservation'),
(17, 15, '2021-10-10 00:00:00', '2021-10-11 00:00:00', 700, 'reservation'),
(18, 18, '2021-10-10 00:00:00', '2021-10-11 00:00:00', 400, 'reservation'),
(19, 7, '2021-10-24 00:00:00', '2021-10-26 00:00:00', 1200, 'libre'),
(20, 13, '2021-09-29 00:00:00', '2021-09-30 00:00:00', 1100, 'libre'),
(21, 12, '2021-10-13 00:00:00', '2021-10-15 00:00:00', 1200, 'libre'),
(22, 18, '2022-01-02 00:00:00', '2022-01-05 00:00:00', 2000, 'reservation'),
(23, 16, '2021-10-31 00:00:00', '2021-11-01 00:00:00', 950, 'reservation'),
(24, 9, '2021-11-20 00:00:00', '2021-11-22 00:00:00', 850, 'reservation'),
(25, 10, '2021-10-21 00:00:00', '2021-10-22 00:00:00', 400, 'reservation'),
(26, 10, '2021-10-22 00:00:00', '2021-10-23 00:00:00', 400, 'libre'),
(27, 11, '2021-10-25 00:00:00', '2021-10-26 00:00:00', 600, 'reservation'),
(28, 11, '2021-10-27 00:00:00', '2021-10-28 00:00:00', 600, 'libre'),
(29, 11, '2021-10-29 00:00:00', '2021-10-30 00:00:00', 600, 'libre'),
(30, 15, '2021-11-30 00:00:00', '2021-12-01 00:00:00', 700, 'libre'),
(31, 15, '2021-12-02 00:00:00', '2021-12-04 00:00:00', 700, 'reservation'),
(32, 12, '2021-12-02 00:00:00', '2021-12-04 00:00:00', 700, 'libre'),
(34, 6, '2021-10-19 00:00:00', '2021-10-23 00:00:00', 3000, 'reservation'),
(35, 18, '2021-10-01 00:00:00', '2021-10-02 00:00:00', 99, 'libre');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `id_salle` int(3) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `cp` varchar(5) NOT NULL,
  `capacite` int(3) NOT NULL,
  `categorie` enum('Réunion','Bureau','Formation') NOT NULL,
  PRIMARY KEY (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `titre`, `description`, `photo`, `pays`, `ville`, `adresse`, `cp`, `capacite`, `categorie`) VALUES
(6, 'Billabong', 'Proche de Notre Dame et du Métro Cluny La Sorbonne, l\'agence est idéalement située. Elle dispose d\'une salle de réunion à louer à l\'heure, demi-journée ou journée et bénéficie des équipements suivants : connexion Internet, paperboard, espace d\'attente, climatisation, télésurveillance.', 'Billabong-benjamin-child-0sT9YhNgSEs-unsplashcmp.jpg', 'France', 'Paris', '65 Rue Saint-Jacques', '75005', 15, 'Réunion'),
(7, 'Bodoni', 'Parfaite pour vos comités de direction stratégique. La salle Bodoni faite de fauteuils en acajou vous réunira pour prendre les décisions les plus importantes & celles qui auront le plus d’impact au sein de votre entreprise.', 'Bodoni-benjamin-child-GWe0dlVD9e0-unsplashcmp.jpg', 'France', 'Paris', '6 Rue Deguerry', '75011', 20, 'Réunion'),
(8, 'Futura', 'Organisez votre prochain événement dans une jolie salle du 2ème arrondissement de Paris. Appartenant à une société spécialisée dans le coaching professionnel, cette salle est parfaite pour une réunion, une formation ou un entretien avec vos collaborateurs. L\'espace a été complètement rénové en 2016', 'Futura-carrie-hays-mdLoaKr9vt8-unsplashcmp.jpg', 'France', 'Paris', '131 Passage du Caire', '75002', 10, 'Réunion'),
(9, 'Klavika', 'Située dans un immeuble typiquement parisien , cette salle de travail modulable est idéale pour organiser facilement votre réunion.', 'Klavika-damir-kopezhanov-VM1Voswbs0A-unsplashcmp.jpg', 'France', 'Paris', '184 Avenue Jean Jaurès', '75019', 10, 'Réunion'),
(10, 'Myriad', 'Cette résidence vous propose une salle neuve, moderne et tout équipée pour vos formations, showroom et tout autre événement professionnel. Elle se situe à Paris dans le 13ème arrondissement.', 'Myriad-dane-deaner--KLkj7onc-unsplashcmp.jpg', 'France', 'Paris', '30 Rue de Campo-Formio', '75013', 8, 'Formation'),
(11, 'Helvetica', 'Organisez votre prochaine réunion d\'entreprise à Lyon, dans cette salle atypique et chaleureuse ! Vous pourrez y accueillir jusqu\'à 15 collaborateurs en disposition table rectangulaire.', 'Helvetica-domingo-alvarez-e-Niv2v0idsv0-unsplashcmp.jpg', 'France', 'Lyon', '12 Rue de l\'Ancienne Préfecture', '69002', 15, 'Réunion'),
(12, 'Proxima', 'Optez pour la location de cette salle de formation à Lyon, et disposez d\'un espace de travail professionnel et équipé pour accueillir vos participants.', 'Proxima-jud-mackrill-wWK72ozUkI-unsplashcmp.jpg', 'France', 'Lyon', '28 Rue Vaubecour', '69002', 15, 'Formation'),
(13, 'Univers', 'Profitez du Salon Saône pour réunir vos collaborateur dans une salle lumineuse et fonctionnelle à deux stations de la gare de Lyon Perrache et à quelques minutes du stade de Gerland et de la Halle Tony Garnier.', 'Univers-nastuh-abootalebi-eHD8Y1Znfpk-unsplashcmp.jpg', 'France', 'Lyon', '66 Rue de Gerland', '69007', 35, 'Réunion'),
(14, 'Garamond', 'Ce bureau de direction situé en plein cœur de Nantes près du tramway Commerce est idéal pour vos événements professionnels. Notre journée d\'étude inclut un atelier culinaire d\'1heure, sur la thématique de votre choix ! De quoi renforcer la cohésion de votre équipe, et créer une vraie symbiose !', 'Garamond-nastuh-abootalebi-J1rNS2qv8BQ-unsplashcmp.jpg', 'France', 'Nantes', '26 Rue des Olivettes', '44000', 25, 'Bureau'),
(15, 'Rockwell', 'Venez découvrir cette salle de réunion atypique et moderne, située à l\'Ouest de Nantes, en bordure de la Loire.', 'Rockwell-neonbrand-7MKYpAA4aMw-unsplashcmp.jpg', 'France', 'Nantes', '16 Impasse des Jades', '44000', 35, 'Formation'),
(16, 'Baskerville', 'Nous vous proposons cette salle de séminaire à Bordeaux pour la mise en place de vos événements d\'entreprise en tous genres.', 'Baskerville-s-o-c-i-a-l-c-u-t-1RT4txDDAbM-unsplashcmp.jpg', 'France', 'Bordeaux', '96 Rue de la Benauge', '33100', 40, 'Réunion'),
(17, 'Frutiger', 'Nous vous proposons un espace de travail modulable selon la typologie de votre réunion et le nombre de participants. Le prix de location inclut la mise à disposition du mobilier, une connexion WIFI avec internet fibré, et un paperboard.', 'Frutiger-vaishnav-chogale-xh7EipwhE-g-unsplashcmp.jpg', 'France', 'Bordeaux', '38 Quai des Chartrons', '33000', 20, 'Bureau'),
(18, 'Didot', 'La salle de réunion est idéalement localisée, à proximité du Marché des Lices et du centre de Rennes. Les rues piétonnes avec vitrines et le Vieux-Rennes se situent aux pieds de l\'Hôtel des Lices. De plus, la gare est à moins de 5 minutes en métro.', 'Didot-zd-newmedia-CpaUIsbkfuc-unsplashcmp.jpg', 'France', 'Rennes', '3 Quai Lamennais', '35000', 5, 'Réunion');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
