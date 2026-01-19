<?php
/*********************************************************
 * PAGE : Attribution matières ↔ classes
 * Rôle : Admin uniquement
 *********************************************************/

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../config/constants.php';
require_once '../../auth/check_auth.php';

if ($_SESSION['role'] !== 'admin') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();

$classes = $pdo->query("SELECT codecl, libellecl FROM classe ORDER BY libellecl")->fetchAll();
$matieres = $pdo->query("SELECT codeMat, libelleMat FROM matiere ORDER BY libelleMat")->fetchAll();

$classe_selectionnee = $_GET['codecl'] ?? null;
$matieres_attribuees = [];

if ($classe_selectionnee) {
    $stmt = $pdo->prepare(
        "SELECT codeMat FROM matiere_classe WHERE codecl = ?"
    );
    $stmt->execute([$classe_selectionnee]);
    $matieres_attribuees = array_column($stmt->fetchAll(), 'codeMat');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codecl = $_POST['codecl'];
    $matieres_selectionnees = $_POST['matieres'] ?? [];

    $stmt = $pdo->prepare("DELETE FROM matiere_classe WHERE codecl = ?");
    $stmt->execute([$codecl]);

    $stmt = $pdo->prepare(
        "INSERT INTO matiere_classe (codeMat, codecl) VALUES (?, ?)"
    );

    foreach ($matieres_selectionnees as $codeMat) {
        $stmt->execute([$codeMat, $codecl]);
    }

    redirect("/admin/parametrage/matiere_classe.php?codecl=$codecl");
}

$title = 'Attribution Matières / Classes - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">📘 Attribution des matières aux classes</h2>
        <p class="text-gray-600">Sélectionnez les matières enseignées dans chaque classe</p>
    </div>

    <!-- Sélection classe -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 form-card">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">1️⃣ Choisir une classe</h3>
        <form method="get">
            <select name="codecl" onchange="this.form.submit()"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition">
                <option value="">-- Choisir une classe --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['codecl'] ?>"
                        <?= $classe_selectionnee === $c['codecl'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['libellecl']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($classe_selectionnee): ?>
        <!-- Checklist des matières -->
        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">
                Matières disponibles pour : 
                <span class="text-navy"><?= htmlspecialchars($classes[array_search($classe_selectionnee, array_column($classes, 'codecl'))]['libellecl']) ?></span>
            </h3>
            
            <form method="post">
                <input type="hidden" name="codecl" value="<?= $classe_selectionnee ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <?php foreach ($matieres as $m): ?>
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition <?php
                            echo in_array($m['codeMat'], $matieres_attribuees) 
                                ? 'border-emerald-500 bg-emerald-50' 
                                : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50';
                        ?>">
                            <input type="checkbox" 
                                   name="matieres[]"
                                   value="<?= $m['codeMat'] ?>"
                                   <?= in_array($m['codeMat'], $matieres_attribuees) ? 'checked' : '' ?>
                                   class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">
                                <?= htmlspecialchars($m['libelleMat']) ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit"
                            class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg">
                        💾 Enregistrer
                    </button>
                    <a href="<?= BASE_URL ?>/admin/index.php"
                       class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition">
                        ❌ Annuler
                    </a>
                </div>
            </form>
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
