# 🎓 Système de Gestion des Notes - IUA

## 📋 Description du Projet

Système web moderne de gestion des notes académiques développé pour l'Institut Universitaire d'Abidjan (IUA). Cette application permet la gestion complète des notes, des étudiants, des enseignants et des classes avec une interface utilisateur premium et intuitive.

### ✨ Fonctionnalités Principales

- **Gestion Multi-Rôles** : Administrateur, Enseignant, Étudiant
- **Saisie de Notes** : Interface intuitive pour les enseignants
- **Calcul Automatique** : Moyennes et bulletins générés automatiquement
- **Consultation en Temps Réel** : Étudiants peuvent consulter leurs notes instantanément
- **Design Premium** : Interface moderne avec glassmorphism et animations
- **Sécurité** : Authentification sécurisée avec hashage des mots de passe

### 🎨 Charte Graphique

Le projet dispose d'une **charte graphique complète** documentant tous les éléments visuels :
- Palette de couleurs professionnelle
- Typographie (Inter & Outfit)
- Composants UI standardisés
- Animations et transitions
- Guidelines d'accessibilité

📄 **Consulter la charte** : [CHARTE_GRAPHIQUE.md](CHARTE_GRAPHIQUE.md)

### 📘 Documentation Technique

Une **documentation technique détaillée** est disponible pour comprendre l'architecture du système :
- Architecture globale et flux de données
- Schéma de base de données avec diagrammes
- Structure du code et patterns utilisés
- Mesures de sécurité (authentification, protection XSS/SQL injection)
- API et fonctions principales
- Guide de déploiement

📄 **Consulter la documentation** : [DOCUMENTATION_TECHNIQUE.md](DOCUMENTATION_TECHNIQUE.md)

---

## 🚀 Installation Rapide

### Prérequis

- **XAMPP** (ou WAMP/LAMP) avec :
  - PHP 8.0 ou supérieur
  - MySQL 5.7 ou supérieur
  - Apache 2.4 ou supérieur
- **Navigateur Web** moderne (Chrome, Firefox, Edge)

### Étape 1 : Télécharger le Projet

```bash
# Cloner ou extraire le projet dans le dossier htdocs de XAMPP
C:\xampp\htdocs\gestion-notes-iua
```

### Étape 2 : Créer la Base de Données

1. **Démarrer XAMPP** :
   - Lancer XAMPP Control Panel
   - Démarrer **Apache** et **MySQL**

2. **Accéder à phpMyAdmin** :
   - Ouvrir le navigateur
   - Aller sur : `http://localhost/phpmyadmin`

3. **Créer la base de données** :
   - Cliquer sur "Nouvelle base de données"
   - Nom : `iua_gestion_notes`
   - Interclassement : `utf8mb4_general_ci`
   - Cliquer sur "Créer"

4. **Importer le schéma** :
   - Sélectionner la base `iua_gestion_notes`
   - Cliquer sur l'onglet "Importer"
   - Choisir le fichier : `database/iua_gestion_notes.sql`
   - Cliquer sur "Exécuter"

### Étape 3 : Configurer la Connexion

Le fichier `config/database.php` est déjà configuré avec les paramètres par défaut de XAMPP :

```php
private $host = "localhost";
private $dbname = "iua_gestion_notes";
private $username = "root";
private $password = "";
```

**Si votre configuration est différente**, modifiez ces valeurs dans `config/database.php`.

### Étape 4 : Créer les Comptes de Test

Exécutez le script SQL suivant dans phpMyAdmin pour créer les comptes de démonstration :

```sql
-- Insérer des données de test
USE iua_gestion_notes;

-- 1. Créer un administrateur
INSERT INTO utilisateurs (login, password_hash, role, id_reference, actif) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'ADM001', 1);
-- Mot de passe : password

-- 2. Créer un enseignant
INSERT INTO enseignant (MatriculeEns, nomEns, prenomEns, telEns, Email) VALUES
('ENS001', 'KOUASSI', 'Jean', '0707070707', 'enseignant@iua.ci');

INSERT INTO utilisateurs (login, password_hash, role, id_reference, actif) VALUES
('enseignant@iua.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant', 'ENS001', 1);
-- Mot de passe : password

-- 3. Créer un étudiant
INSERT INTO etudiant (Matriculation, nomEt, prenomEt, Email, photo) VALUES
('ETU001', 'YAO', 'Marie', 'etudiant@iua.ci', 'default.png');

INSERT INTO utilisateurs (login, password_hash, role, id_reference, actif) VALUES
('etudiant@iua.ci', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 'ETU001', 1);
-- Mot de passe : password

-- 4. Créer des classes
INSERT INTO classe (codecl, libellecl) VALUES
('L1INFO', 'Licence 1 Informatique'),
('L2INFO', 'Licence 2 Informatique'),
('L3INFO', 'Licence 3 Informatique');

-- 5. Créer des matières
INSERT INTO matiere (codeMat, libelleMat, credits) VALUES
('PROG1', 'Programmation 1', 4),
('BDD', 'Base de Données', 3),
('ALGO', 'Algorithmique', 4),
('WEB', 'Développement Web', 3);

-- 6. Créer une période
INSERT INTO periode (codePeriode, libellePeriode, dateDebut, dateFin) VALUES
('2025-2026-S1', 'Semestre 1 2025-2026', '2025-09-01', '2026-01-31');

-- 7. Inscrire l'étudiant dans une classe
INSERT INTO inscription (Matriculation, codecl, codePeriode) VALUES
('ETU001', 'L1INFO', '2025-2026-S1');

-- 8. Associer matières à la classe
INSERT INTO matiere_classe (codeMat, codecl) VALUES
('PROG1', 'L1INFO'),
('BDD', 'L1INFO'),
('ALGO', 'L1INFO'),
('WEB', 'L1INFO');

-- 9. Assigner enseignement
INSERT INTO enseignement (MatriculeEns, codecl, codeMat) VALUES
('ENS001', 'L1INFO', 'PROG1'),
('ENS001', 'L1INFO', 'WEB');

-- 10. Ajouter quelques notes de test
INSERT INTO evaluer (MatriculeEns, Matriculation, codeMat, codePeriode, note) VALUES
('ENS001', 'ETU001', 'PROG1', '2025-2026-S1', 15.50),
('ENS001', 'ETU001', 'WEB', '2025-2026-S1', 14.00);
```

### Étape 5 : Accéder à l'Application

Ouvrir le navigateur et aller sur :

```
http://localhost/gestion-notes-iua/index.php
```

---

## 👥 Comptes de Test

### 🔐 Administrateur

- **Login** : `admin`
- **Mot de passe** : `password`
- **Accès** : Gestion complète du système

**Fonctionnalités** :
- ✅ Gestion des classes
- ✅ Gestion des matières
- ✅ Gestion des périodes
- ✅ Gestion des enseignants
- ✅ Gestion des étudiants
- ✅ Gestion des inscriptions
- ✅ Paramétrage (enseignements, matières-classes)
- ✅ Consultation de toutes les données

### 👨‍🏫 Enseignant

- **Login** : `enseignant@iua.ci`
- **Mot de passe** : `password`
- **Matricule** : ENS001
- **Nom** : KOUASSI Jean

**Fonctionnalités** :
- ✅ Saisie des notes pour ses matières
- ✅ Consultation des notes saisies
- ✅ Calcul automatique des moyennes
- ✅ Vue des classes et matières enseignées

### 🎓 Étudiant

- **Login** : `etudiant@iua.ci`
- **Mot de passe** : `password`
- **Matricule** : ETU001
- **Nom** : YAO Marie

**Fonctionnalités** :
- ✅ Consultation des notes en temps réel
- ✅ Téléchargement des bulletins
- ✅ Vue du profil personnel
- ✅ Historique des notes par période

---

## 📁 Structure du Projet

```
gestion-notes-iua/
├── admin/                  # Module Administrateur
│   ├── classes/           # Gestion des classes
│   ├── matieres/          # Gestion des matières
│   ├── periodes/          # Gestion des périodes
│   ├── enseignants/       # Gestion des enseignants
│   ├── etudiants/         # Gestion des étudiants
│   ├── inscriptions/      # Gestion des inscriptions
│   ├── parametrage/       # Paramétrage système
│   └── index.php          # Dashboard admin
│
├── enseignant/            # Module Enseignant
│   ├── index.php          # Dashboard enseignant
│   ├── saisir_notes.php   # Saisie des notes
│   └── mes_notes.php      # Consultation notes
│
├── etudiant/              # Module Étudiant
│   ├── index.php          # Dashboard étudiant
│   └── mes_notes.php      # Consultation notes
│
├── auth/                  # Authentification
│   ├── login.php          # Page de connexion
│   ├── logout.php         # Déconnexion
│   └── check_auth.php     # Vérification session
│
├── config/                # Configuration
│   ├── database.php       # Connexion BDD (Singleton)
│   ├── constants.php      # Constantes globales
│   └── functions.php      # Fonctions utilitaires
│
├── includes/              # Composants réutilisables
│   ├── header.php         # En-tête HTML
│   ├── navbar.php         # Barre de navigation
│   ├── footer.php         # Pied de page
│   └── alerts.php         # Messages flash
│
├── assets/                # Ressources statiques
│   ├── css/
│   │   └── style.css      # Styles personnalisés
│   └── js/
│       └── animations.js  # Animations JavaScript
│
├── database/              # Base de données
│   └── iua_gestion_notes.sql  # Schéma SQL
│
├── models/                # Modèles de données
│   ├── Classe.php
│   ├── Matiere.php
│   ├── Enseignant.php
│   ├── Etudiant.php
│   └── ...
│
└── index.php              # Page d'accueil
```

---

## 🎨 Technologies Utilisées

### Backend
- **PHP 8.x** - Langage serveur
- **MySQL** - Base de données relationnelle
- **PDO** - Accès sécurisé à la base de données
- **Sessions PHP** - Gestion de l'authentification

### Frontend
- **HTML5** - Structure
- **Tailwind CSS** - Framework CSS utility-first
- **JavaScript ES6+** - Interactivité
- **Google Fonts** - Typographie premium (Inter, Outfit)

### Design
- **Glassmorphism** - Effets de transparence modernes
- **Animations CSS** - Transitions fluides
- **Intersection Observer** - Animations au scroll
- **Responsive Design** - Compatible mobile/tablette/desktop

---

## 🔒 Sécurité

- ✅ **Hashage des mots de passe** avec `password_hash()` (bcrypt)
- ✅ **Requêtes préparées** (PDO) pour prévenir les injections SQL
- ✅ **Protection XSS** avec `htmlspecialchars()`
- ✅ **Vérification des sessions** sur chaque page protégée
- ✅ **Contrôle d'accès basé sur les rôles** (RBAC)
- ✅ **Validation des données** côté serveur

---

## 📊 Schéma de la Base de Données

### Tables Principales

1. **utilisateurs** - Comptes utilisateurs (admin, enseignant, étudiant)
2. **enseignant** - Informations des enseignants
3. **etudiant** - Informations des étudiants
4. **classe** - Classes disponibles
5. **matiere** - Matières enseignées
6. **periode** - Périodes académiques (semestres)
7. **inscription** - Inscriptions étudiants/classes/périodes
8. **evaluer** - Notes des étudiants
9. **matiere_classe** - Association matières-classes
10. **enseignement** - Association enseignants-classes-matières

### Relations

- Un **étudiant** peut avoir plusieurs **inscriptions**
- Une **inscription** lie un étudiant à une classe pour une période
- Un **enseignant** peut enseigner plusieurs **matières** dans plusieurs **classes**
- Une **évaluation** lie un enseignant, un étudiant, une matière et une période

---

## 🧪 Guide de Test pour le Professeur

### Test 1 : Connexion et Navigation

1. Accéder à `http://localhost/gestion-notes-iua/index.php`
2. Vérifier que la page d'accueil s'affiche correctement
3. Cliquer sur "Se connecter"
4. Tester la connexion avec chaque rôle (voir comptes ci-dessus)

### Test 2 : Module Administrateur

1. Se connecter avec le compte **admin**
2. Tester la création d'une nouvelle classe
3. Tester la création d'une nouvelle matière
4. Tester l'ajout d'un enseignant
5. Tester l'ajout d'un étudiant
6. Vérifier les statistiques du dashboard

### Test 3 : Module Enseignant

1. Se connecter avec le compte **enseignant@iua.ci**
2. Aller sur "Saisir des notes"
3. Sélectionner une classe et une matière
4. Saisir une note pour un étudiant
5. Vérifier que la note apparaît dans "Voir mes notes"

### Test 4 : Module Étudiant

1. Se connecter avec le compte **etudiant@iua.ci**
2. Consulter les notes affichées
3. Vérifier le calcul de la moyenne
4. Tester le téléchargement du bulletin (si implémenté)

### Test 5 : Sécurité

1. Tenter d'accéder à `/admin/index.php` sans être connecté → Redirection vers login
2. Se connecter en tant qu'étudiant et tenter d'accéder à `/admin/` → Accès refusé
3. Vérifier que la déconnexion fonctionne correctement

---

## 🐛 Dépannage

### Problème : "Database connection failed"

**Solution** :
- Vérifier que MySQL est démarré dans XAMPP
- Vérifier les paramètres dans `config/database.php`
- Vérifier que la base `iua_gestion_notes` existe

### Problème : "Page blanche" ou erreurs PHP

**Solution** :
- Activer l'affichage des erreurs dans `php.ini` :
  ```ini
  display_errors = On
  error_reporting = E_ALL
  ```
- Redémarrer Apache

### Problème : Les liens ne fonctionnent pas

**Solution** :
- Vérifier que le projet est bien dans `C:\xampp\htdocs\gestion-notes-iua`
- Vider le cache du navigateur (`Ctrl + Shift + Delete`)
- Utiliser `http://localhost/gestion-notes-iua/` (pas `file://`)

### Problème : "Class 'Database' not found"

**Solution** :
- Vérifier que `config/database.php` est bien inclus
- Vérifier qu'il n'y a pas de texte avant `<?php` dans les fichiers

---

## 📝 Notes pour le Développement Futur

### Fonctionnalités à Ajouter

- [ ] Export PDF des bulletins
- [ ] Envoi d'emails aux étudiants
- [ ] Statistiques avancées (graphiques)
- [ ] Import/Export Excel
- [ ] Gestion des absences
- [ ] Calendrier académique
- [ ] Forum étudiant-enseignant
- [ ] Application mobile

### Améliorations Possibles

- [ ] Système de cache (Redis)
- [ ] API RESTful
- [ ] Tests unitaires (PHPUnit)
- [ ] CI/CD (GitHub Actions)
- [ ] Docker pour le déploiement
- [ ] Logs d'activité
- [ ] Sauvegarde automatique de la BDD

---

## 👨‍💻 Auteur

**Projet Académique - IUA**  
Système de Gestion des Notes  
Année : 2025-2026

---

## 📄 Licence

Ce projet est développé dans un cadre académique pour l'Institut Universitaire d'Abidjan (IUA).

---

## 📞 Support

Pour toute question ou problème :
1. Consulter la section **Dépannage** ci-dessus
2. Vérifier que toutes les étapes d'installation ont été suivies
3. S'assurer que XAMPP (Apache + MySQL) est bien démarré

---

**Bonne évaluation ! 🎓**
#   G e s t i o n - n o t e - I U A  
 