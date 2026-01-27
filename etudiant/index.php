<?php
/*********************************************************
 * DASHBOARD ÉTUDIANT
 *********************************************************/

// ===== DEBUG (désactiver en production) =====
error_reporting(E_ALL);
ini_set('display_errors', 1);
// ===========================================

require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../config/constants.php';
require_once '../auth/check_auth.php';

// 🔐 Sécurité : étudiant uniquement
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();

// 🔑 Matricule étudiant depuis la session
if (!isset($_SESSION['id_reference'])) {
    die("❌ Étudiant non identifié (id_reference manquant).");
}

$matriculation = $_SESSION['id_reference'];

/* =========================
   INFOS ÉTUDIANT
   ========================= */
$stmt = $pdo->prepare(
    "SELECT Matriculation, nomEt, prenomEt, Email, photo
     FROM etudiant
     WHERE Matriculation = ?"
);
$stmt->execute([$matriculation]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    die("❌ Étudiant introuvable en base.");
}

/* =========================
   CLASSES DE L'ÉTUDIANT
   ========================= */
$stmt = $pdo->prepare(
    "SELECT DISTINCT
        c.libellecl,
        p.libellePeriode
     FROM inscription i
     JOIN classe c ON c.codecl = i.codecl
     JOIN periode p ON p.codePeriode = i.codePeriode
     WHERE i.Matriculation = ?
     ORDER BY p.dateDebut DESC"
);
$stmt->execute([$matriculation]);
$classes = $stmt->fetchAll();

// Statistiques : nombre de notes
$nbNotes = $pdo->prepare(
    "SELECT COUNT(*) FROM evaluer WHERE Matriculation = ?"
);
$nbNotes->execute([$matriculation]);
$totalNotes = $nbNotes->fetchColumn();

$title = 'Dashboard Étudiant - IUA';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
require_once '../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            🎓 Tableau de bord étudiant
        </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Carte Profil -->
        <div class="lg:col-span-1 bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">👤 Mes informations</h3>
            
            <?php if (!empty($etudiant['photo'])): ?>
                <div class="mb-4">
                    <img src="<?= BASE_URL ?>/assets/images/etudiants/<?= htmlspecialchars($etudiant['photo']) ?>"
                         alt="Photo étudiant"
                         class="w-32 h-32 rounded-full object-cover mx-auto border-4 border-navy">
                </div>
            <?php else: ?>
                <div class="mb-4 text-center">
                    <div class="w-32 h-32 rounded-full bg-gray-200 mx-auto flex items-center justify-center text-5xl">
                        🎓
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="space-y-2 text-sm">
                <p><strong class="text-gray-700">Matricule :</strong> <span class="text-gray-900"><?= htmlspecialchars($etudiant['Matriculation']) ?></span></p>
                <p><strong class="text-gray-700">Nom :</strong> <span class="text-gray-900"><?= htmlspecialchars($etudiant['nomEt']) ?></span></p>
                <p><strong class="text-gray-700">Prénom :</strong> <span class="text-gray-900"><?= htmlspecialchars($etudiant['prenomEt']) ?></span></p>
                <p><strong class="text-gray-700">Email :</strong> <span class="text-gray-900"><?= htmlspecialchars($etudiant['Email']) ?></span></p>
            </div>
        </div>

        <!-- Carte Classes / Périodes -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">🏫 Mes classes / périodes</h3>

            <?php if (empty($classes)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p class="text-lg">⚠️ Vous n'êtes inscrit dans aucune classe pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($classes as $c): ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div>
                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($c['libellecl']) ?></p>
                                <p class="text-sm text-gray-600"><em><?= htmlspecialchars($c['libellePeriode']) ?></em></p>
                            </div>
                            <span class="text-2xl">📚</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Cartes de services -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Carte Mes notes -->
        <a href="<?= BASE_URL ?>/etudiant/mes_notes.php" 
           class="bg-white rounded-xl shadow-md p-8 border-l-4 border-blue-500">
            <div class="text-5xl mb-4 ">📘</div>
            <h3 class="text-2xl font-bold mb-2">Consulter mes notes</h3>
            <p class="text-emerald-100 mb-4">Accéder à votre bulletin de notes</p>
            <div class="text-3xl font-bold"><?= $totalNotes ?> notes</div>
        </a>

        <!-- Carte Mon profil -->
        <div class="bg-white rounded-xl shadow-md p-8 border-l-4 border-blue-500">
            <div class="text-5xl mb-4">⚙️</div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Mon profil</h3>
            <p class="text-gray-600 text-sm mb-4">Informations personnelles et paramètres</p>
            <a href="<?= BASE_URL ?>/etudiant/mon_profil.php" 
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Accéder
            </a>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>
