<?php
/*********************************************************
 * DASHBOARD ENSEIGNANT
 *********************************************************/

require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../config/constants.php';
require_once '../auth/check_auth.php';

// Sécurité : enseignant uniquement
if ($_SESSION['role'] !== 'enseignant') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();

// Matricule enseignant depuis utilisateurs
$matriculeEns = $_SESSION['login'];

// Récupérer infos enseignant
$stmt = $pdo->prepare(
    "SELECT MatriculeEns, nomEns, prenomEns 
     FROM enseignant 
     WHERE Email = ?"
);
$stmt->execute([$matriculeEns]);
$enseignant = $stmt->fetch();

// Classes et matières enseignées
$stmt = $pdo->prepare(
    "SELECT DISTINCT c.libellecl, m.libelleMat
     FROM enseignement e
     JOIN classe c ON c.codecl = e.codecl
     JOIN matiere m ON m.codeMat = e.codeMat
     WHERE e.MatriculeEns = ?"
);
$stmt->execute([$enseignant['MatriculeEns']]);
$enseignements = $stmt->fetchAll();

// Statistiques : nombre de notes saisies
$nbNotes = $pdo->prepare(
    "SELECT COUNT(*) FROM evaluer WHERE MatriculeEns = ?"
);
$nbNotes->execute([$enseignant['MatriculeEns']]);
$totalNotes = $nbNotes->fetchColumn();

$title = 'Dashboard Enseignant - IUA';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
require_once '../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            👨‍🏫 Dashboard Enseignant
        </h1>
        <p class="text-gray-600">
            Bienvenue <strong
                class="text-navy"><?= htmlspecialchars($enseignant['prenomEns'] . ' ' . $enseignant['nomEns']) ?></strong>
        </p>
    </div>

    <!-- Cartes d'actions rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Carte Saisir notes -->
        <a href="/gestion-notes-iua - Copy/enseignant/saisir_notes.php"
            class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="text-5xl mb-4">📝</div>
            <h3 class="text-2xl font-bold mb-2">Saisir des notes</h3>
            <p class="text-emerald-100">Enregistrer les notes des étudiants</p>
        </a>

        <!-- Carte Mes notes -->
        <a href="/gestion-notes-iua - Copy/enseignant/mes_notes.php"
            class="bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-xl shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="text-5xl mb-4">👁️</div>
            <h3 class="text-2xl font-bold mb-2">Voir mes notes</h3>
            <p class="text-blue-100">Consulter les notes saisies</p>
        </a>

        <!-- Carte Statistiques -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="text-5xl mb-4">📊</div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Statistiques</h3>
            <p class="text-3xl font-bold text-purple-600"><?= $totalNotes ?></p>
            <p class="text-sm text-gray-600 mt-1">Notes saisies au total</p>
        </div>
    </div>

    <!-- Vos enseignements -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">📚 Vos enseignements</h2>

        <?php if (empty($enseignements)): ?>
            <div class="text-center py-8 text-gray-500">
                <p class="text-lg">Aucun enseignement assigné pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Classe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Matière</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($enseignements as $e): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-sm font-medium text-gray-900"><?= htmlspecialchars($e['libellecl']) ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700"><?= htmlspecialchars($e['libelleMat']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>