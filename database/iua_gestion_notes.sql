-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 15 jan. 2026 à 04:52
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
-- Base de données : `iua_gestion_notes`
--

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

CREATE TABLE `classe` (
  `codecl` varchar(50) NOT NULL,
  `libellecl` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE `enseignant` (
  `MatriculeEns` varchar(50) NOT NULL,
  `nomEns` varchar(100) NOT NULL,
  `prenomEns` varchar(100) NOT NULL,
  `telEns` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `Matriculation` varchar(50) NOT NULL,
  `nomEt` varchar(100) NOT NULL,
  `prenomEt` varchar(100) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evaluer`
--

CREATE TABLE `evaluer` (
  `id_evaluation` int(11) NOT NULL,
  `MatriculeEns` varchar(50) DEFAULT NULL,
  `Matriculation` varchar(50) DEFAULT NULL,
  `codeMat` varchar(50) DEFAULT NULL,
  `codePeriode` varchar(50) DEFAULT NULL,
  `note` decimal(4,2) DEFAULT NULL,
  `date_saisie` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
  `id_inscription` int(11) NOT NULL,
  `Matriculation` varchar(50) DEFAULT NULL,
  `codecl` varchar(50) DEFAULT NULL,
  `codePeriode` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE `matiere` (
  `codeMat` varchar(50) NOT NULL,
  `libelleMat` varchar(100) NOT NULL,
  `credits` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matiere_classe`
--

CREATE TABLE `matiere_classe` (
  `id_mc` int(11) NOT NULL,
  `codeMat` varchar(50) DEFAULT NULL,
  `codecl` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `periode`
--

CREATE TABLE `periode` (
  `codePeriode` varchar(50) NOT NULL,
  `libellePeriode` varchar(100) NOT NULL,
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_user` int(11) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','enseignant','etudiant') NOT NULL,
  `id_reference` varchar(50) NOT NULL COMMENT 'Lien vers Matricule étudiant ou enseignant',
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`codecl`);

--
-- Index pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`MatriculeEns`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`Matriculation`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Index pour la table `evaluer`
--
ALTER TABLE `evaluer`
  ADD PRIMARY KEY (`id_evaluation`),
  ADD KEY `MatriculeEns` (`MatriculeEns`),
  ADD KEY `Matriculation` (`Matriculation`),
  ADD KEY `codeMat` (`codeMat`),
  ADD KEY `codePeriode` (`codePeriode`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`id_inscription`),
  ADD KEY `Matriculation` (`Matriculation`),
  ADD KEY `codecl` (`codecl`),
  ADD KEY `codePeriode` (`codePeriode`);

--
-- Index pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`codeMat`);

--
-- Index pour la table `matiere_classe`
--
ALTER TABLE `matiere_classe`
  ADD PRIMARY KEY (`id_mc`),
  ADD UNIQUE KEY `codeMat` (`codeMat`,`codecl`),
  ADD KEY `codecl` (`codecl`);

--
-- Index pour la table `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`codePeriode`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `evaluer`
--
ALTER TABLE `evaluer`
  MODIFY `id_evaluation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inscription`
--
ALTER TABLE `inscription`
  MODIFY `id_inscription` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matiere_classe`
--
ALTER TABLE `matiere_classe`
  MODIFY `id_mc` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `evaluer`
--
ALTER TABLE `evaluer`
  ADD CONSTRAINT `evaluer_ibfk_1` FOREIGN KEY (`MatriculeEns`) REFERENCES `enseignant` (`MatriculeEns`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluer_ibfk_2` FOREIGN KEY (`Matriculation`) REFERENCES `etudiant` (`Matriculation`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluer_ibfk_3` FOREIGN KEY (`codeMat`) REFERENCES `matiere` (`codeMat`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluer_ibfk_4` FOREIGN KEY (`codePeriode`) REFERENCES `periode` (`codePeriode`) ON DELETE CASCADE;

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `inscription_ibfk_1` FOREIGN KEY (`Matriculation`) REFERENCES `etudiant` (`Matriculation`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscription_ibfk_2` FOREIGN KEY (`codecl`) REFERENCES `classe` (`codecl`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscription_ibfk_3` FOREIGN KEY (`codePeriode`) REFERENCES `periode` (`codePeriode`) ON DELETE CASCADE;

--
-- Contraintes pour la table `matiere_classe`
--
ALTER TABLE `matiere_classe`
  ADD CONSTRAINT `matiere_classe_ibfk_1` FOREIGN KEY (`codeMat`) REFERENCES `matiere` (`codeMat`) ON DELETE CASCADE,
  ADD CONSTRAINT `matiere_classe_ibfk_2` FOREIGN KEY (`codecl`) REFERENCES `classe` (`codecl`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
