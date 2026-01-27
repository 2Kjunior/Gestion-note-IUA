-- ========================================
-- SCRIPT D'INSERTION DES DONNÉES DE TEST
-- Système de Gestion des Notes - IUA
-- ========================================

USE iua_gestion_notes;

-- ========================================
-- 1. CRÉATION DES UTILISATEURS
-- ========================================

-- Administrateur
-- Login: admin | Mot de passe: password
INSERT INTO utilisateurs (login, password_hash, role, id_reference, actif) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'ADM001', 1);

-- ========================================
-- 2. CRÉATION DES ENSEIGNANTS
-- ========================================

INSERT INTO enseignant (MatriculeEns, nomEns, prenomEns, telEns, Email) VALUES
('ENS001', 'KOUASSI', 'Jean', '0707070707', 'enseignant@iua.ci'),
('ENS002', 'TRAORE', 'Fatou', '0708080808', 'traore@iua.ci'),
('ENS003', 'KONE', 'Amadou', '0709090909', 'kone@iua.ci');

-- Comptes utilisateurs pour les enseignants
-- Login: enseignant@iua.ci | Mot de passe: password
INSERT INTO utilisateurs (login, password_hash, role, id_reference, actif) VALUES
('enseignant@iua.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant', 'ENS001', 1),
('traore@iua.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant', 'ENS002', 1),
('kone@iua.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant', 'ENS003', 1);

-- ========================================
-- 3. CRÉATION DES ÉTUDIANTS
-- ========================================

INSERT INTO etudiant (Matriculation, nomEt, prenomEt, Email, photo) VALUES
('ETU001', 'YAO', 'Marie', 'etudiant@iua.ci', 'default.png'),
('ETU002', 'DIALLO', 'Ibrahim', 'diallo@iua.ci', 'default.png'),
('ETU003', 'BAMBA', 'Aïcha', 'bamba@iua.ci', 'default.png'),
('ETU004', 'OUATTARA', 'Seydou', 'ouattara@iua.ci', 'default.png'),
('ETU005', 'TOURE', 'Aminata', 'toure@iua.ci', 'default.png');

-- Comptes utilisateurs pour les étudiants
-- Login: etudiant@iua.ci | Mot de passe: password
INSERT INTO utilisateurs (login, password_hash, role, id_reference, actif) VALUES
('etudiant@iua.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 'ETU001', 1),
('diallo@iua.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 'ETU002', 1),
('bamba@iua.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 'ETU003', 1),
('ouattara@iua.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 'ETU004', 1),
('toure@iua.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 'ETU005', 1);

-- ========================================
-- 4. CRÉATION DES CLASSES
-- ========================================

INSERT INTO classe (codecl, libellecl) VALUES
('L1INFO', 'Licence 1 Informatique'),
('L2INFO', 'Licence 2 Informatique'),
('L3INFO', 'Licence 3 Informatique'),
('L1GESTION', 'Licence 1 Gestion'),
('L2GESTION', 'Licence 2 Gestion');

-- ========================================
-- 5. CRÉATION DES MATIÈRES
-- ========================================

INSERT INTO matiere (codeMat, libelleMat, credits) VALUES
('PROG1', 'Programmation 1', 4),
('PROG2', 'Programmation 2', 4),
('BDD', 'Base de Données', 3),
('ALGO', 'Algorithmique', 4),
('WEB', 'Développement Web', 3),
('RESEAU', 'Réseaux Informatiques', 3),
('MATH', 'Mathématiques', 4),
('ANGLAIS', 'Anglais', 2);

-- ========================================
-- 6. CRÉATION DES PÉRIODES
-- ========================================

INSERT INTO periode (codePeriode, libellePeriode, dateDebut, dateFin) VALUES
('2025-2026-S1', 'Semestre 1 2025-2026', '2025-09-01', '2026-01-31'),
('2025-2026-S2', 'Semestre 2 2025-2026', '2026-02-01', '2026-06-30');

-- ========================================
-- 7. INSCRIPTIONS DES ÉTUDIANTS
-- ========================================

INSERT INTO inscription (Matriculation, codecl, codePeriode) VALUES
-- Licence 1 Info - Semestre 1
('ETU001', 'L1INFO', '2025-2026-S1'),
('ETU002', 'L1INFO', '2025-2026-S1'),
('ETU003', 'L1INFO', '2025-2026-S1'),
-- Licence 2 Info - Semestre 1
('ETU004', 'L2INFO', '2025-2026-S1'),
('ETU005', 'L2INFO', '2025-2026-S1');

-- ========================================
-- 8. ASSOCIATION MATIÈRES-CLASSES
-- ========================================

INSERT INTO matiere_classe (codeMat, codecl) VALUES
-- L1 Informatique
('PROG1', 'L1INFO'),
('BDD', 'L1INFO'),
('ALGO', 'L1INFO'),
('WEB', 'L1INFO'),
('MATH', 'L1INFO'),
('ANGLAIS', 'L1INFO'),
-- L2 Informatique
('PROG2', 'L2INFO'),
('BDD', 'L2INFO'),
('RESEAU', 'L2INFO'),
('WEB', 'L2INFO'),
('ANGLAIS', 'L2INFO');

-- ========================================
-- 9. CRÉATION DE LA TABLE ENSEIGNEMENT
-- (si elle n'existe pas déjà)
-- ========================================

CREATE TABLE IF NOT EXISTS `enseignement` (
  `id_enseignement` int(11) NOT NULL AUTO_INCREMENT,
  `MatriculeEns` varchar(50) DEFAULT NULL,
  `codecl` varchar(50) DEFAULT NULL,
  `codeMat` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_enseignement`),
  KEY `MatriculeEns` (`MatriculeEns`),
  KEY `codecl` (`codecl`),
  KEY `codeMat` (`codeMat`),
  CONSTRAINT `enseignement_ibfk_1` FOREIGN KEY (`MatriculeEns`) REFERENCES `enseignant` (`MatriculeEns`) ON DELETE CASCADE,
  CONSTRAINT `enseignement_ibfk_2` FOREIGN KEY (`codecl`) REFERENCES `classe` (`codecl`) ON DELETE CASCADE,
  CONSTRAINT `enseignement_ibfk_3` FOREIGN KEY (`codeMat`) REFERENCES `matiere` (`codeMat`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========================================
-- 10. AFFECTATION DES ENSEIGNEMENTS
-- ========================================

INSERT INTO enseignement (MatriculeEns, codecl, codeMat) VALUES
-- ENS001 (KOUASSI Jean) enseigne en L1 Info
('ENS001', 'L1INFO', 'PROG1'),
('ENS001', 'L1INFO', 'WEB'),
-- ENS002 (TRAORE Fatou) enseigne en L1 Info
('ENS002', 'L1INFO', 'BDD'),
('ENS002', 'L1INFO', 'ALGO'),
-- ENS003 (KONE Amadou) enseigne en L2 Info
('ENS003', 'L2INFO', 'PROG2'),
('ENS003', 'L2INFO', 'RESEAU');

-- ========================================
-- 11. INSERTION DE NOTES DE TEST
-- ========================================

INSERT INTO evaluer (MatriculeEns, Matriculation, codeMat, codePeriode, note) VALUES
-- Notes pour ETU001 (YAO Marie) - L1 Info
('ENS001', 'ETU001', 'PROG1', '2025-2026-S1', 15.50),
('ENS001', 'ETU001', 'WEB', '2025-2026-S1', 14.00),
('ENS002', 'ETU001', 'BDD', '2025-2026-S1', 16.75),
('ENS002', 'ETU001', 'ALGO', '2025-2026-S1', 13.25),

-- Notes pour ETU002 (DIALLO Ibrahim) - L1 Info
('ENS001', 'ETU002', 'PROG1', '2025-2026-S1', 12.00),
('ENS001', 'ETU002', 'WEB', '2025-2026-S1', 11.50),
('ENS002', 'ETU002', 'BDD', '2025-2026-S1', 14.00),
('ENS002', 'ETU002', 'ALGO', '2025-2026-S1', 10.75),

-- Notes pour ETU003 (BAMBA Aïcha) - L1 Info
('ENS001', 'ETU003', 'PROG1', '2025-2026-S1', 17.00),
('ENS001', 'ETU003', 'WEB', '2025-2026-S1', 16.50),
('ENS002', 'ETU003', 'BDD', '2025-2026-S1', 18.00),
('ENS002', 'ETU003', 'ALGO', '2025-2026-S1', 15.75),

-- Notes pour ETU004 (OUATTARA Seydou) - L2 Info
('ENS003', 'ETU004', 'PROG2', '2025-2026-S1', 13.50),
('ENS003', 'ETU004', 'RESEAU', '2025-2026-S1', 12.00),

-- Notes pour ETU005 (TOURE Aminata) - L2 Info
('ENS003', 'ETU005', 'PROG2', '2025-2026-S1', 16.00),
('ENS003', 'ETU005', 'RESEAU', '2025-2026-S1', 15.50);

-- ========================================
-- FIN DU SCRIPT
-- ========================================

-- Afficher un message de confirmation
SELECT 'Données de test insérées avec succès !' AS Message;

-- Afficher le résumé des données
SELECT 
    (SELECT COUNT(*) FROM utilisateurs) AS 'Utilisateurs',
    (SELECT COUNT(*) FROM enseignant) AS 'Enseignants',
    (SELECT COUNT(*) FROM etudiant) AS 'Étudiants',
    (SELECT COUNT(*) FROM classe) AS 'Classes',
    (SELECT COUNT(*) FROM matiere) AS 'Matières',
    (SELECT COUNT(*) FROM periode) AS 'Périodes',
    (SELECT COUNT(*) FROM inscription) AS 'Inscriptions',
    (SELECT COUNT(*) FROM evaluer) AS 'Notes';
