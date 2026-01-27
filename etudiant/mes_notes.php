<?php
/*********************************************************
 * PAGE : Mes notes (Étudiant)
 * Rôle : Étudiant uniquement
 *********************************************************/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../config/constants.php';
require_once '../auth/check_auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();

if (!isset($_SESSION['id_reference'])) {
    die("❌ Matricule étudiant introuvable en session.");
}

$matriculation = $_SESSION['id_reference'];

$stmt = $pdo->prepare("
    SELECT
        c.libellecl,
        m.libelleMat,
        p.libellePeriode,
        e.note,
        e.date_saisie
    FROM evaluer e
    JOIN matiere m ON m.codeMat = e.codeMat
    JOIN periode p ON p.codePeriode = e.codePeriode
    JOIN inscription i ON i.Matriculation = e.Matriculation
    JOIN classe c ON c.codecl = i.codecl
    WHERE e.Matriculation = ?
    ORDER BY p.dateDebut DESC, m.libelleMat ASC
");

$stmt->execute([$matriculation]);
$notes = $stmt->fetchAll();

// Regrouper par période et classe pour affichage bulletin
$notesParPeriode = [];
foreach ($notes as $note) {
    $key = $note['libellePeriode'] . '|' . $note['libellecl'];
    if (!isset($notesParPeriode[$key])) {
        $notesParPeriode[$key] = [
            'periode' => $note['libellePeriode'],
            'classe' => $note['libellecl'],
            'notes' => []
        ];
    }
    $notesParPeriode[$key]['notes'][] = $note;
}

// Calculer les moyennes par période/classe
foreach ($notesParPeriode as &$groupe) {
    $somme = 0;
    $count = 0;
    foreach ($groupe['notes'] as $note) {
        $somme += floatval($note['note']);
        $count++;
    }
    $groupe['moyenne'] = $count > 0 ? round($somme / $count, 2) : 0;
}

$title = 'Mes notes - Étudiant';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
require_once '../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">📘 Mes notes</h2>
        <p class="text-gray-600">Consultez votre bulletin de notes par période</p>
    </div>

    <?php if (empty($notes)): ?>
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="text-6xl mb-4">📝</div>
            <p class="text-xl text-gray-600">Aucune note disponible pour le moment.</p>
        </div>
    <?php else: ?>
        <?php foreach ($notesParPeriode as $groupe): ?>
            <div class="bg-white rounded-xl shadow-lg p-8 mb-6 print-break">
                <!-- En-tête du bulletin -->
                <div class="border-b-2 border-navy pb-4 mb-6">
                    <h3 class="text-2xl font-bold text-navy mb-2">
                        📊 Bulletin de notes
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-semibold text-gray-700">Période :</span>
                            <span class="text-gray-900"><?= htmlspecialchars($groupe['periode']) ?></span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Classe :</span>
                            <span class="text-gray-900"><?= htmlspecialchars($groupe['classe']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Tableau des notes -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Matière</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Note</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($groupe['notes'] as $note): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($note['libelleMat']) ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold <?php
                                            $noteVal = floatval($note['note']);
                                            if ($noteVal >= 16) echo 'bg-emerald-100 text-emerald-800';
                                            elseif ($noteVal >= 14) echo 'bg-blue-100 text-blue-800';
                                            elseif ($noteVal >= 12) echo 'bg-yellow-100 text-yellow-800';
                                            else echo 'bg-red-100 text-red-800';
                                        ?>">
                                            <?= htmlspecialchars($note['note']) ?>/20
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600"><?= htmlspecialchars($note['date_saisie']) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-navy text-white">
                            <tr>
                                <td class="px-6 py-4 font-bold">Moyenne générale</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xl font-bold"><?= $groupe['moyenne'] ?>/20</span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="mt-6">
        <a href="<?= BASE_URL ?>/etudiant/index.php" 
           class="text-gray-600 hover:text-navy transition">
            ⬅ Retour au dashboard étudiant
        </a>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>
