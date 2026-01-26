<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/functions.php';
require_once __DIR__ . '/../auth/check_auth.php';

// Vérification du rôle
if ($_SESSION['role'] !== ROLE_ADMIN) {
    redirect('/auth/login.php');
}

$pdo = Database::getInstance()->getConnection();

// Statistiques pour les cartes
$stats = [
    'etudiants' => $pdo->query("SELECT COUNT(*) FROM etudiant")->fetchColumn(),
    'enseignants' => $pdo->query("SELECT COUNT(*) FROM enseignant")->fetchColumn(),
    'classes' => $pdo->query("SELECT COUNT(*) FROM classe")->fetchColumn(),
    'matieres' => $pdo->query("SELECT COUNT(*) FROM matiere")->fetchColumn(),
    'periodes' => $pdo->query("SELECT COUNT(*) FROM periode")->fetchColumn(),
    'inscriptions' => $pdo->query("SELECT COUNT(*) FROM inscription")->fetchColumn(),
];

$title = 'Dashboard Administrateur - IUA';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <!-- En-tête -->
    <div class="mb-8" data-animate>
        <h1 class="text-5xl font-display font-bold text-gray-800 mb-3">
            🎓 Dashboard Administrateur
        </h1>
        <p class="text-xl text-gray-600">
            Bienvenue <strong class="text-navy"><?= e($_SESSION['login']) ?></strong>
        </p>
    </div>

    <!-- Cartes de statistiques - Premium Design -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <!-- Carte Étudiants -->
        <div data-animate class="stat-card border-blue-500 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Total Étudiants</p>
                    <p class="stat-number mt-2" data-count="<?= $stats['etudiants'] ?>">0</p>
                </div>
                <div class="text-6xl transform group-hover:scale-110 group-hover:rotate-12 transition-all">🎓</div>
            </div>
            <a href="<?= BASE_URL ?>/admin/etudiants/liste.php"
                class="mt-4 inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-semibold group">
                Voir la liste
                <span class="ml-1 transform group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>

        <!-- Carte Enseignants -->
        <div data-animate class="stat-card border-emerald-500 group stagger-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Total Enseignants</p>
                    <p class="stat-number mt-2" data-count="<?= $stats['enseignants'] ?>">0</p>
                </div>
                <div class="text-6xl transform group-hover:scale-110 group-hover:rotate-12 transition-all">👨‍🏫</div>
            </div>
            <a href="<?= BASE_URL ?>/admin/enseignants/liste.php"
                class="mt-4 inline-flex items-center text-sm text-emerald-600 hover:text-emerald-800 font-semibold group">
                Voir la liste
                <span class="ml-1 transform group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>

        <!-- Carte Classes -->
        <div data-animate class="stat-card border-purple-500 group stagger-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Total Classes</p>
                    <p class="stat-number mt-2" data-count="<?= $stats['classes'] ?>">0</p>
                </div>
                <div class="text-6xl transform group-hover:scale-110 group-hover:rotate-12 transition-all">📚</div>
            </div>
            <a href="<?= BASE_URL ?>/admin/classes/liste.php"
                class="mt-4 inline-flex items-center text-sm text-purple-600 hover:text-purple-800 font-semibold group">
                Voir la liste
                <span class="ml-1 transform group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>

        <!-- Carte Matières -->
        <div data-animate class="stat-card border-orange-500 group stagger-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Total Matières</p>
                    <p class="stat-number mt-2" data-count="<?= $stats['matieres'] ?>">0</p>
                </div>
                <div class="text-6xl transform group-hover:scale-110 group-hover:rotate-12 transition-all">📘</div>
            </div>
            <a href="<?= BASE_URL ?>/admin/matieres/liste.php"
                class="mt-4 inline-flex items-center text-sm text-orange-600 hover:text-orange-800 font-semibold group">
                Voir la liste
                <span class="ml-1 transform group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>

        <!-- Carte Périodes -->
        <div data-animate class="stat-card border-indigo-500 group stagger-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Total Périodes</p>
                    <p class="stat-number mt-2" data-count="<?= $stats['periodes'] ?>">0</p>
                </div>
                <div class="text-6xl transform group-hover:scale-110 group-hover:rotate-12 transition-all">📅</div>
            </div>
            <a href="<?= BASE_URL ?>/admin/periodes/liste.php"
                class="mt-4 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 font-semibold group">
                Voir la liste
                <span class="ml-1 transform group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>

        <!-- Carte Inscriptions -->
        <div data-animate class="stat-card border-pink-500 group stagger-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase tracking-wide">Total Inscriptions</p>
                    <p class="stat-number mt-2" data-count="<?= $stats['inscriptions'] ?>">0</p>
                </div>
                <div class="text-6xl transform group-hover:scale-110 group-hover:rotate-12 transition-all">📋</div>
            </div>
            <a href="<?= BASE_URL ?>/admin/inscriptions/liste.php"
                class="mt-4 inline-flex items-center text-sm text-pink-600 hover:text-pink-800 font-semibold group">
                Voir la liste
                <span class="ml-1 transform group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>
    </div>

    <!-- Raccourcis rapides -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Raccourcis rapides</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="<?= BASE_URL ?>/admin/classes/liste.php"
                class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <span class="text-3xl mr-4">📚</span>
                <div>
                    <p class="font-semibold text-gray-800">Gestion des classes</p>
                    <p class="text-sm text-gray-600">Ajouter, modifier, supprimer</p>
                </div>
            </a>

            <a href="<?= BASE_URL ?>/admin/matieres/liste.php"
                class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <span class="text-3xl mr-4">📘</span>
                <div>
                    <p class="font-semibold text-gray-800">Gestion des matières</p>
                    <p class="text-sm text-gray-600">Créer et gérer les matières</p>
                </div>
            </a>

            <a href="<?= BASE_URL ?>/admin/periodes/liste.php"
                class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <span class="text-3xl mr-4">📅</span>
                <div>
                    <p class="font-semibold text-gray-800">Gestion des périodes</p>
                    <p class="text-sm text-gray-600">Périodes académiques</p>
                </div>
            </a>

            <a href="<?= BASE_URL ?>/admin/enseignants/liste.php"
                class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <span class="text-3xl mr-4">👨‍🏫</span>
                <div>
                    <p class="font-semibold text-gray-800">Gestion des enseignants</p>
                    <p class="text-sm text-gray-600">Enseignants et comptes</p>
                </div>
            </a>

            <a href="<?= BASE_URL ?>/admin/etudiants/liste.php"
                class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <span class="text-3xl mr-4">🎓</span>
                <div>
                    <p class="font-semibold text-gray-800">Gestion des étudiants</p>
                    <p class="text-sm text-gray-600">Étudiants et inscriptions</p>
                </div>
            </a>

            <a href="<?= BASE_URL ?>/admin/parametrage/enseignement.php"
                class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <span class="text-3xl mr-4">⚙️</span>
                <div>
                    <p class="font-semibold text-gray-800">Paramétrage</p>
                    <p class="text-sm text-gray-600">Attributions et configurations</p>
                </div>
            </a>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>