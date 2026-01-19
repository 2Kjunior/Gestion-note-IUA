<?php
/*********************************************************
 * PAGE : Attribution Enseignant ↔ Matière ↔ Classe
 * Rôle : Admin uniquement
 *********************************************************/

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../config/constants.php';
require_once '../../auth/check_auth.php';

if ($_SESSION['role'] !== 'admin') {
    redirect('/admin/index.php');
}

$pdo = Database::getInstance()->getConnection();

$classes = $pdo->query(
    "SELECT codecl, libellecl 
     FROM classe 
     ORDER BY libellecl"
)->fetchAll();

$enseignants = $pdo->query(
    "SELECT MatriculeEns, nomEns, prenomEns 
     FROM enseignant 
     ORDER BY nomEns, prenomEns"
)->fetchAll();

$classe_selectionnee = $_GET['codecl'] ?? null;
$matieres = [];

if ($classe_selectionnee) {
    $stmt = $pdo->prepare(
        "SELECT m.codeMat, m.libelleMat
         FROM matiere_classe mc
         JOIN matiere m ON m.codeMat = mc.codeMat
         WHERE mc.codecl = ?"
    );
    $stmt->execute([$classe_selectionnee]);
    $matieres = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codecl       = $_POST['codecl'] ?? '';
    $codeMat      = $_POST['codeMat'] ?? '';
    $MatriculeEns = $_POST['MatriculeEns'] ?? '';

    if ($codecl === '' || $codeMat === '' || $MatriculeEns === '') {
        redirect("/admin/parametrage/enseignement.php?codecl=$codecl");
    }

    $check = $pdo->prepare(
        "SELECT COUNT(*) 
         FROM enseignement
         WHERE MatriculeEns = ?
           AND codeMat = ?
           AND codecl = ?"
    );
    $check->execute([$MatriculeEns, $codeMat, $codecl]);

    if ($check->fetchColumn() == 0) {
        $insert = $pdo->prepare(
            "INSERT INTO enseignement (MatriculeEns, codeMat, codecl)
             VALUES (?, ?, ?)"
        );
        $insert->execute([$MatriculeEns, $codeMat, $codecl]);
    }

    redirect("/admin/parametrage/enseignement.php?codecl=$codecl");
}

$enseignements = [];

if ($classe_selectionnee) {
    $stmt = $pdo->prepare(
        "SELECT 
            m.libelleMat,
            ens.nomEns,
            ens.prenomEns
         FROM enseignement e
         JOIN matiere m ON m.codeMat = e.codeMat
         JOIN enseignant ens ON ens.MatriculeEns = e.MatriculeEns
         WHERE e.codecl = ?
         ORDER BY m.libelleMat, ens.nomEns"
    );
    $stmt->execute([$classe_selectionnee]);
    $enseignements = $stmt->fetchAll();
}

$title = 'Attribution Enseignement - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">👨‍🏫 Attribution Enseignant ↔ Matière ↔ Classe</h2>
        <p class="text-gray-600">Attribuez les enseignants aux matières par classe</p>
    </div>

    <!-- Sélection de la classe -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 form-card">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">1️⃣ Choisir une classe</h3>
        <form method="get">
            <select name="codecl" onchange="this.form.submit()"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition">
                <option value="">-- Choisir une classe --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= htmlspecialchars($c['codecl']) ?>"
                        <?= $classe_selectionnee === $c['codecl'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['libellecl']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($classe_selectionnee): ?>
        <!-- Formulaire d'attribution -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-6 form-card">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">2️⃣ Nouvelle attribution</h3>
            
            <form method="post" class="space-y-6">
                <input type="hidden" name="codecl" value="<?= htmlspecialchars($classe_selectionnee) ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="codeMat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Matière <span class="text-red-500">*</span>
                        </label>
                        <select id="codeMat" name="codeMat" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition">
                            <?php foreach ($matieres as $m): ?>
                                <option value="<?= htmlspecialchars($m['codeMat']) ?>">
                                    <?= htmlspecialchars($m['libelleMat']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="MatriculeEns" class="block text-sm font-semibold text-gray-700 mb-2">
                            Enseignant <span class="text-red-500">*</span>
                        </label>
                        <select id="MatriculeEns" name="MatriculeEns" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition">
                            <?php foreach ($enseignants as $ens): ?>
                                <option value="<?= htmlspecialchars($ens['MatriculeEns']) ?>">
                                    <?= htmlspecialchars($ens['nomEns'] . ' ' . $ens['prenomEns']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button type="submit"
                        class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg">
                    💾 Affecter
                </button>
            </form>
        </div>

        <!-- Liste des affectations existantes -->
        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">📋 Affectations existantes</h3>
            
            <?php if (empty($enseignements)): ?>
                <p class="text-gray-500 text-center py-4">Aucune affectation pour cette classe.</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($enseignements as $e): ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div>
                                <span class="font-semibold text-gray-800"><?= htmlspecialchars($e['libelleMat']) ?></span>
                                <span class="text-gray-600 mx-2">—</span>
                                <span class="text-gray-700"><?= htmlspecialchars($e['nomEns'] . ' ' . $e['prenomEns']) ?></span>
                            </div>
                            <span class="text-emerald-600">✓</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="mt-6">
        <a href="<?= BASE_URL ?>/admin/index.php" 
           class="text-gray-600 hover:text-navy transition">
            ⬅ Retour dashboard
        </a>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>
