# 📘 Documentation Technique - Système de Gestion des Notes IUA

## 📋 Table des Matières

1. [Vue d'Ensemble](#1-vue-densemble)
2. [Architecture du Système](#2-architecture-du-système)
3. [Base de Données](#3-base-de-données)
4. [Structure du Code](#4-structure-du-code)
5. [Sécurité](#5-sécurité)
6. [API et Fonctions](#6-api-et-fonctions)
7. [Flux de Données](#7-flux-de-données)
8. [Gestion des Sessions](#8-gestion-des-sessions)
9. [Patterns et Bonnes Pratiques](#9-patterns-et-bonnes-pratiques)
10. [Déploiement](#10-déploiement)

---

## 1. Vue d'Ensemble

### 1.1 Description

Le **Système de Gestion des Notes IUA** est une application web développée en PHP natif permettant la gestion complète des notes académiques pour l'Institut Universitaire d'Abidjan.

### 1.2 Technologies

| Composant | Technologie | Version |
|-----------|-------------|---------|
| Backend | PHP | 8.0+ |
| Base de données | MySQL/MariaDB | 5.7+ / 10.4+ |
| Frontend | HTML5, CSS3, JavaScript | ES6+ |
| Framework CSS | Tailwind CSS | 3.x (CDN) |
| Serveur Web | Apache | 2.4+ |
| Fonts | Google Fonts | Inter, Outfit |

### 1.3 Prérequis Système

- **Serveur** : XAMPP, WAMP, ou LAMP
- **PHP** : Version 8.0 minimum avec extensions :
  - PDO
  - PDO_MySQL
  - Session
  - JSON
- **MySQL** : Version 5.7+ ou MariaDB 10.4+
- **Apache** : mod_rewrite activé
- **Navigateur** : Chrome 90+, Firefox 88+, Edge 90+

---

## 2. Architecture du Système

### 2.1 Architecture Globale

```
┌─────────────────────────────────────────────────────────┐
│                    NAVIGATEUR CLIENT                     │
│  (HTML5 + Tailwind CSS + JavaScript + Animations)       │
└────────────────────┬────────────────────────────────────┘
                     │ HTTP/HTTPS
                     ▼
┌─────────────────────────────────────────────────────────┐
│                   SERVEUR APACHE                         │
│  ┌───────────────────────────────────────────────────┐  │
│  │              APPLICATION PHP                       │  │
│  │  ┌─────────────┐  ┌──────────────┐  ┌──────────┐ │  │
│  │  │   Router    │  │ Controllers  │  │  Views   │ │  │
│  │  │  (index.php)│  │ (admin/...)  │  │ (*.php)  │ │  │
│  │  └─────────────┘  └──────────────┘  └──────────┘ │  │
│  │  ┌─────────────┐  ┌──────────────┐  ┌──────────┐ │  │
│  │  │   Models    │  │    Config    │  │ Includes │ │  │
│  │  │  (classes)  │  │ (database)   │  │ (navbar) │ │  │
│  │  └─────────────┘  └──────────────┘  └──────────┘ │  │
│  └───────────────────────────────────────────────────┘  │
└────────────────────┬────────────────────────────────────┘
                     │ PDO
                     ▼
┌─────────────────────────────────────────────────────────┐
│                   BASE DE DONNÉES                        │
│              MySQL/MariaDB (InnoDB)                      │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐  │
│  │utilisat. │ │enseignant│ │ etudiant │ │  classe  │  │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘  │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐  │
│  │ matiere  │ │ periode  │ │ evaluer  │ │inscript. │  │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘  │
└─────────────────────────────────────────────────────────┘
```

### 2.2 Pattern MVC Adapté

Le projet utilise une architecture **MVC simplifiée** :

- **Models** (`models/`) : Classes représentant les entités
- **Views** (`*.php`) : Pages HTML avec PHP intégré
- **Controllers** : Logique métier intégrée dans les pages
- **Config** (`config/`) : Configuration et utilitaires

### 2.3 Flux de Requête

```
1. Client → index.php
2. index.php → Chargement config (constants, functions, database)
3. Vérification session (check_auth.php si page protégée)
4. Chargement header.php (HTML, CSS, meta tags)
5. Chargement navbar.php (navigation dynamique selon rôle)
6. Traitement logique métier (requêtes SQL, calculs)
7. Affichage contenu (HTML + données PHP)
8. Chargement footer.php (scripts JS, fermeture HTML)
9. Réponse → Client
```

---

## 3. Base de Données

### 3.1 Schéma Relationnel

```
┌─────────────────┐
│  utilisateurs   │
│─────────────────│
│ id_user (PK)    │
│ login (UNIQUE)  │
│ password_hash   │
│ role (ENUM)     │
│ id_reference    │──┐
│ actif           │  │
│ date_creation   │  │
└─────────────────┘  │
                     │
        ┌────────────┴─────────────┐
        │                          │
        ▼                          ▼
┌─────────────────┐        ┌─────────────────┐
│   enseignant    │        │    etudiant     │
│─────────────────│        │─────────────────│
│ MatriculeEns(PK)│        │ Matriculation(PK)│
│ nomEns          │        │ nomEt           │
│ prenomEns       │        │ prenomEt        │
│ telEns          │        │ Email           │
│ Email (UNIQUE)  │        │ photo           │
└────────┬────────┘        └────────┬────────┘
         │                          │
         │                          │
         ▼                          ▼
┌─────────────────┐        ┌─────────────────┐
│  enseignement   │        │   inscription   │
│─────────────────│        │─────────────────│
│ id_enseign. (PK)│        │ id_inscr. (PK)  │
│ MatriculeEns(FK)│        │ Matriculation(FK)│
│ codecl (FK)     │        │ codecl (FK)     │
│ codeMat (FK)    │        │ codePeriode (FK)│
└─────────────────┘        └─────────────────┘
         │                          │
         │                          │
         └──────────┬───────────────┘
                    ▼
            ┌─────────────────┐
            │     evaluer     │
            │─────────────────│
            │ id_evaluation(PK)│
            │ MatriculeEns(FK)│
            │ Matriculation(FK)│
            │ codeMat (FK)    │
            │ codePeriode (FK)│
            │ note            │
            │ date_saisie     │
            └─────────────────┘
```

### 3.2 Tables Principales

#### utilisateurs
```sql
CREATE TABLE utilisateurs (
  id_user INT PRIMARY KEY AUTO_INCREMENT,
  login VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','enseignant','etudiant') NOT NULL,
  id_reference VARCHAR(50) NOT NULL,
  actif TINYINT(1) DEFAULT 1,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Rôle** : Authentification et gestion des accès

#### enseignant
```sql
CREATE TABLE enseignant (
  MatriculeEns VARCHAR(50) PRIMARY KEY,
  nomEns VARCHAR(100) NOT NULL,
  prenomEns VARCHAR(100) NOT NULL,
  telEns VARCHAR(20),
  Email VARCHAR(100) UNIQUE
);
```

**Rôle** : Informations des enseignants

#### etudiant
```sql
CREATE TABLE etudiant (
  Matriculation VARCHAR(50) PRIMARY KEY,
  nomEt VARCHAR(100) NOT NULL,
  prenomEt VARCHAR(100) NOT NULL,
  Email VARCHAR(100) UNIQUE,
  photo VARCHAR(255) DEFAULT 'default.png'
);
```

**Rôle** : Informations des étudiants

#### evaluer
```sql
CREATE TABLE evaluer (
  id_evaluation INT PRIMARY KEY AUTO_INCREMENT,
  MatriculeEns VARCHAR(50),
  Matriculation VARCHAR(50),
  codeMat VARCHAR(50),
  codePeriode VARCHAR(50),
  note DECIMAL(4,2),
  date_saisie TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (MatriculeEns) REFERENCES enseignant(MatriculeEns),
  FOREIGN KEY (Matriculation) REFERENCES etudiant(Matriculation),
  FOREIGN KEY (codeMat) REFERENCES matiere(codeMat),
  FOREIGN KEY (codePeriode) REFERENCES periode(codePeriode)
);
```

**Rôle** : Stockage des notes

### 3.3 Relations

- **1:N** : Un enseignant peut enseigner plusieurs matières
- **N:M** : Un étudiant peut être inscrit dans plusieurs classes (via inscription)
- **N:M** : Une classe peut avoir plusieurs matières (via matiere_classe)
- **1:N** : Un étudiant peut avoir plusieurs notes (via evaluer)

### 3.4 Contraintes d'Intégrité

- **CASCADE** : Suppression en cascade pour toutes les FK
- **UNIQUE** : Login, Email enseignant, Email étudiant
- **NOT NULL** : Champs obligatoires (noms, matricules)
- **CHECK** : Note entre 0 et 20 (via application)

---

## 4. Structure du Code

### 4.1 Arborescence Détaillée

```
gestion-notes-iua/
│
├── admin/                      # Module Administrateur
│   ├── classes/
│   │   ├── liste.php          # Liste des classes
│   │   ├── ajouter.php        # Ajout classe
│   │   └── modifier.php       # Modification classe
│   ├── matieres/
│   ├── periodes/
│   ├── enseignants/
│   ├── etudiants/
│   ├── inscriptions/
│   ├── parametrage/
│   │   ├── enseignement.php   # Affectation enseignements
│   │   └── matiere_classe.php # Association matières-classes
│   └── index.php              # Dashboard admin
│
├── enseignant/                # Module Enseignant
│   ├── index.php              # Dashboard
│   ├── saisir_notes.php       # Saisie des notes
│   └── mes_notes.php          # Consultation
│
├── etudiant/                  # Module Étudiant
│   ├── index.php              # Dashboard
│   └── mes_notes.php          # Consultation notes
│
├── auth/                      # Authentification
│   ├── login.php              # Page de connexion
│   ├── logout.php             # Déconnexion
│   └── check_auth.php         # Middleware auth
│
├── config/                    # Configuration
│   ├── database.php           # Singleton PDO
│   ├── constants.php          # Constantes globales
│   └── functions.php          # Fonctions utilitaires
│
├── includes/                  # Composants réutilisables
│   ├── header.php             # <head> HTML
│   ├── navbar.php             # Navigation
│   ├── footer.php             # Footer + scripts
│   └── alerts.php             # Messages flash
│
├── assets/                    # Ressources statiques
│   ├── css/
│   │   └── style.css          # Styles personnalisés
│   └── js/
│       └── animations.js      # Animations JS
│
├── database/                  # Scripts SQL
│   ├── iua_gestion_notes.sql # Schéma
│   └── test_data.sql         # Données de test
│
├── models/                    # Classes métier
│   ├── Classe.php
│   ├── Matiere.php
│   ├── Enseignant.php
│   ├── Etudiant.php
│   ├── Periode.php
│   └── Evaluation.php
│
├── index.php                  # Page d'accueil
├── README.md                  # Documentation
├── CHARTE_GRAPHIQUE.md       # Charte design
└── DOCUMENTATION_TECHNIQUE.md # Ce fichier
```

### 4.2 Fichiers Clés

#### config/database.php
```php
class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        // Connexion PDO avec gestion d'erreurs
    }
    
    public static function getInstance() {
        // Pattern Singleton
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}
```

**Pattern** : Singleton  
**Rôle** : Connexion unique à la BDD

#### config/functions.php
```php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($path) {
    header("Location: " . BASE_URL . $path);
    exit;
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
```

**Rôle** : Fonctions utilitaires globales

#### auth/check_auth.php
```php
session_start();
if (!isLoggedIn()) {
    redirect('/auth/login.php');
}
```

**Pattern** : Middleware  
**Rôle** : Protection des pages

---

## 5. Sécurité

### 5.1 Authentification

#### Hashage des Mots de Passe
```php
// Lors de l'inscription/création
$hash = password_hash($password, PASSWORD_DEFAULT);

// Lors de la connexion
if (password_verify($password, $user['password_hash'])) {
    // Connexion réussie
}
```

**Algorithme** : bcrypt (via PASSWORD_DEFAULT)  
**Coût** : 10 rounds par défaut

#### Gestion des Sessions
```php
session_start();
session_regenerate_id(true); // Après connexion

$_SESSION['user_id'] = $user['id_user'];
$_SESSION['role'] = $user['role'];
$_SESSION['login'] = $user['login'];
$_SESSION['id_reference'] = $user['id_reference'];
```

### 5.2 Protection contre les Injections SQL

```php
// ✅ BON : Requêtes préparées
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
$stmt->execute([$login]);

// ❌ MAUVAIS : Concaténation directe
$query = "SELECT * FROM utilisateurs WHERE login = '$login'";
```

**Méthode** : PDO avec requêtes préparées  
**Protection** : 100% contre SQL Injection

### 5.3 Protection XSS

```php
// Affichage sécurisé
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// Fonction helper
echo e($user_input);
```

### 5.4 Contrôle d'Accès (RBAC)

```php
// Vérification du rôle
if ($_SESSION['role'] !== ROLE_ADMIN) {
    redirect('/auth/login.php');
}
```

**Rôles** :
- `admin` : Accès complet
- `enseignant` : Saisie notes, consultation
- `etudiant` : Consultation uniquement

### 5.5 Protection CSRF (À Implémenter)

```php
// Génération token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Vérification
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token invalide');
}
```

---

## 6. API et Fonctions

### 6.1 Fonctions Globales

#### isLoggedIn()
```php
function isLoggedIn(): bool
```
**Retour** : `true` si utilisateur connecté  
**Usage** : Vérification d'authentification

#### redirect($path)
```php
function redirect(string $path): void
```
**Paramètre** : Chemin relatif  
**Usage** : Redirection HTTP

#### e($string)
```php
function e(string $string): string
```
**Retour** : Chaîne échappée  
**Usage** : Protection XSS

### 6.2 Classe Database

#### getInstance()
```php
public static function getInstance(): Database
```
**Pattern** : Singleton  
**Retour** : Instance unique

#### getConnection()
```php
public function getConnection(): PDO
```
**Retour** : Objet PDO  
**Usage** : Exécution de requêtes

### 6.3 Requêtes SQL Courantes

#### Récupérer les notes d'un étudiant
```php
$stmt = $pdo->prepare("
    SELECT e.note, m.libelleMat, p.libellePeriode
    FROM evaluer e
    JOIN matiere m ON e.codeMat = m.codeMat
    JOIN periode p ON e.codePeriode = p.codePeriode
    WHERE e.Matriculation = ?
    ORDER BY p.dateDebut DESC
");
$stmt->execute([$matricule]);
$notes = $stmt->fetchAll();
```

#### Calculer la moyenne d'un étudiant
```php
$stmt = $pdo->prepare("
    SELECT AVG(note) as moyenne
    FROM evaluer
    WHERE Matriculation = ? AND codePeriode = ?
");
$stmt->execute([$matricule, $periode]);
$moyenne = $stmt->fetchColumn();
```

---

## 7. Flux de Données

### 7.1 Flux de Connexion

```
1. Utilisateur → login.php (GET)
2. Affichage formulaire
3. Utilisateur → login.php (POST)
4. Validation données (login, password)
5. Requête SQL : SELECT * FROM utilisateurs WHERE login = ?
6. Vérification password_verify()
7. Si OK :
   - session_regenerate_id()
   - Stockage $_SESSION
   - Redirection selon rôle
8. Si KO :
   - Message d'erreur
   - Réaffichage formulaire
```

### 7.2 Flux de Saisie de Notes

```
1. Enseignant → saisir_notes.php
2. Sélection classe + matière
3. Requête : Liste étudiants inscrits
4. Affichage formulaire avec inputs
5. Soumission (POST)
6. Validation notes (0-20)
7. Pour chaque étudiant :
   - INSERT INTO evaluer (...)
   - ou UPDATE evaluer SET note = ? WHERE ...
8. Message de confirmation
9. Redirection vers mes_notes.php
```

### 7.3 Flux de Consultation (Étudiant)

```
1. Étudiant → mes_notes.php
2. Récupération Matriculation depuis $_SESSION
3. Requête SQL :
   - JOIN evaluer + matiere + periode
   - WHERE Matriculation = ?
4. Calcul moyenne générale
5. Affichage tableau notes
6. Option téléchargement bulletin (PDF)
```

---

## 8. Gestion des Sessions

### 8.1 Variables de Session

```php
$_SESSION['user_id']      // ID utilisateur
$_SESSION['role']         // admin|enseignant|etudiant
$_SESSION['login']        // Login (email)
$_SESSION['id_reference'] // Matricule enseignant/étudiant
```

### 8.2 Durée de Vie

```php
// Configuration dans php.ini ou .htaccess
session.gc_maxlifetime = 3600  // 1 heure
session.cookie_lifetime = 0     // Jusqu'à fermeture navigateur
```

### 8.3 Sécurité des Sessions

```php
// Régénération ID après connexion
session_regenerate_id(true);

// Destruction complète à la déconnexion
session_unset();
session_destroy();
```

---

## 9. Patterns et Bonnes Pratiques

### 9.1 Design Patterns Utilisés

#### Singleton (Database)
```php
private static $instance = null;

public static function getInstance() {
    if (self::$instance === null) {
        self::$instance = new Database();
    }
    return self::$instance;
}
```

#### Front Controller (index.php)
Point d'entrée unique pour la page d'accueil

#### Template Method (header/footer)
Réutilisation de composants communs

### 9.2 Conventions de Code

#### Nommage
- **Variables** : camelCase (`$userName`)
- **Fonctions** : camelCase (`getUserData()`)
- **Classes** : PascalCase (`Database`, `Etudiant`)
- **Constantes** : UPPER_SNAKE_CASE (`BASE_URL`)
- **Fichiers** : snake_case (`check_auth.php`)

#### Indentation
- **Standard** : 4 espaces
- **HTML** : 2 espaces dans les vues

#### Commentaires
```php
/**
 * Description de la fonction
 * 
 * @param string $login Login de l'utilisateur
 * @return bool True si connecté
 */
function isLoggedIn(string $login): bool {
    // Implémentation
}
```

### 9.3 Gestion des Erreurs

```php
// Mode développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mode production
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');
```

---

## 10. Déploiement

### 10.1 Checklist de Déploiement

- [ ] Désactiver `display_errors` en production
- [ ] Changer les identifiants BDD
- [ ] Activer HTTPS
- [ ] Configurer `.htaccess` pour sécurité
- [ ] Sauvegarder la base de données
- [ ] Tester tous les rôles
- [ ] Vérifier les permissions fichiers (644/755)

### 10.2 Configuration Apache (.htaccess)

```apache
# Désactiver l'affichage des répertoires
Options -Indexes

# Protection des fichiers sensibles
<FilesMatch "\.(sql|md|log)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Redirection HTTPS (production)
# RewriteEngine On
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 10.3 Optimisations

#### Base de Données
```sql
-- Index sur colonnes fréquemment recherchées
CREATE INDEX idx_login ON utilisateurs(login);
CREATE INDEX idx_matricule ON evaluer(Matriculation);
```

#### PHP
```php
// Utiliser opcache en production
opcache.enable=1
opcache.memory_consumption=128
```

---

## 11. Tests

### 11.1 Tests Fonctionnels

| Module | Test | Résultat Attendu |
|--------|------|------------------|
| Auth | Connexion admin | Redirection /admin/ |
| Auth | Connexion enseignant | Redirection /enseignant/ |
| Auth | Connexion étudiant | Redirection /etudiant/ |
| Admin | Ajout classe | Classe créée en BDD |
| Enseignant | Saisie note | Note enregistrée |
| Étudiant | Consultation | Notes affichées |

### 11.2 Tests de Sécurité

- [ ] Tentative SQL Injection → Bloquée
- [ ] Tentative XSS → Échappée
- [ ] Accès non autorisé → Redirection login
- [ ] Session expirée → Déconnexion
- [ ] CSRF (si implémenté) → Token invalide rejeté

---

## 12. Maintenance

### 12.1 Logs

```php
// Logging personnalisé
function logError($message) {
    $log = date('Y-m-d H:i:s') . " - " . $message . PHP_EOL;
    file_put_contents('logs/error.log', $log, FILE_APPEND);
}
```

### 12.2 Sauvegarde

```bash
# Sauvegarde BDD
mysqldump -u root -p iua_gestion_notes > backup_$(date +%Y%m%d).sql

# Sauvegarde fichiers
tar -czf backup_files_$(date +%Y%m%d).tar.gz /path/to/project
```

### 12.3 Mise à Jour

1. Sauvegarder BDD et fichiers
2. Tester en environnement de développement
3. Appliquer les modifications
4. Vérifier les logs
5. Tester toutes les fonctionnalités

---

## 13. Glossaire

| Terme | Définition |
|-------|------------|
| **PDO** | PHP Data Objects - Interface d'accès aux BDD |
| **RBAC** | Role-Based Access Control - Contrôle d'accès par rôles |
| **XSS** | Cross-Site Scripting - Injection de scripts malveillants |
| **CSRF** | Cross-Site Request Forgery - Falsification de requêtes |
| **Singleton** | Pattern garantissant une instance unique |
| **ORM** | Object-Relational Mapping - Mapping objet-relationnel |
| **MVC** | Model-View-Controller - Pattern architectural |

---

## 14. Références

### 14.1 Documentation PHP
- [PHP Manual](https://www.php.net/manual/fr/)
- [PDO](https://www.php.net/manual/fr/book.pdo.php)
- [Sessions](https://www.php.net/manual/fr/book.session.php)

### 14.2 Sécurité
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Guide](https://phptherightway.com/#security)

### 14.3 Bonnes Pratiques
- [PHP The Right Way](https://phptherightway.com/)
- [PSR Standards](https://www.php-fig.org/psr/)

---

**Version** : 1.0  
**Date** : Janvier 2026  
**Auteur** : Projet Académique IUA  
**Contact** : Pour questions techniques, consulter le README.md
