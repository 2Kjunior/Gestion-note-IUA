<?php
/*********************************************************
 * PAGE : Notes saisies par l'enseignant
 * Rôle : Enseignant uniquement
 *********************************************************/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../config/constants.php';
require_once '../auth/check_auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'enseignant') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();

if (!isset($_SESSION['id_reference'])) {
    die("❌ Matricule enseignant introuvable en session.");
}

$matriculeEns = $_SESSION['id_reference'];

$stmt = $pdo->prepare("
    SELECT
        e.id_evaluation,
        et.Matriculation,
        et.nomEt,
        et.prenomEt,
        c.libellecl,
        m.libelleMat,
        p.libellePeriode,
        e.note,
        e.date_saisie
    FROM evaluer e
    JOIN etudiant et ON et.Matriculation = e.Matriculation
    JOIN inscription i ON i.Matriculation = et.Matriculation
    JOIN classe c ON c.codecl = i.codecl
    JOIN matiere m ON m.codeMat = e.codeMat
    JOIN periode p ON p.codePeriode = e.codePeriode
    WHERE e.MatriculeEns = ?
    ORDER BY e.date_saisie DESC
");

$stmt->execute([$matriculeEns]);
$notes = $stmt->fetchAll();

$title = 'Mes notes saisies - Enseignant';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
require_once '../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">📊 Notes que j'ai saisies</h2>
        <p class="text-gray-600">Consultez toutes les notes que vous avez enregistrées</p>
    </div>

    <?php if (empty($notes)): ?>
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="text-6xl mb-4">📝</div>
            <p class="text-xl text-gray-600">Aucune note saisie pour le moment.</p>
            <a href="<?= BASE_URL ?>/enseignant/saisir_notes.php" 
               class="inline-block mt-6 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
                Commencer à saisir des notes
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-md overflow-hidden table-modern">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Étudiant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Classe</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Matière</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Période</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Note</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date de saisie</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($notes as $n): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($n['nomEt'] . ' ' . $n['prenomEt']) ?></div>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($n['Matriculation']) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700"><?= htmlspecialchars($n['libellecl']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700"><?= htmlspecialchars($n['libelleMat']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <?= htmlspecialchars($n['libellePeriode']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold <?php
                                    $note = floatval($n['note']);
                                    if ($note >= 16) echo 'bg-emerald-100 text-emerald-800';
                                    elseif ($note >= 14) echo 'bg-blue-100 text-blue-800';
                                    elseif ($note >= 12) echo 'bg-yellow-100 text-yellow-800';
                                    else echo 'bg-red-100 text-red-800';
                                ?>">
                                    <?= htmlspecialchars($n['note']) ?>/20
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600"><?= htmlspecialchars($n['date_saisie']) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="mt-6">
        <a href="<?= BASE_URL ?>/enseignant/index.php" 
           class="text-gray-600 hover:text-navy transition">
            ⬅ Retour au dashboard enseignant
        </a>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>
