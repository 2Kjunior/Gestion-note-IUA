<?php
/*********************************************************
 * SAISIE DES NOTES - ENSEIGNANT (LOGIQUE UNIVERSITAIRE)
 *********************************************************/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../config/constants.php';
require_once '../auth/check_auth.php';

if ($_SESSION['role'] !== 'enseignant') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();
$matriculeEns = $_SESSION['id_reference'];

$message = null;

$periodes = $pdo->query(
    "SELECT codePeriode, libellePeriode
     FROM periode
     ORDER BY dateDebut DESC"
)->fetchAll();

$classes = $pdo->prepare(
    "SELECT DISTINCT c.codecl, c.libellecl
     FROM enseignement e
     JOIN classe c ON c.codecl = e.codecl
     WHERE e.MatriculeEns = ?"
);
$classes->execute([$matriculeEns]);
$classes = $classes->fetchAll();

$codecl = $_GET['codecl'] ?? null;
$matieres = [];

if ($codecl) {
    $stmt = $pdo->prepare(
        "SELECT m.codeMat, m.libelleMat
         FROM enseignement e
         JOIN matiere m ON m.codeMat = e.codeMat
         WHERE e.MatriculeEns = ?
           AND e.codecl = ?"
    );
    $stmt->execute([$matriculeEns, $codecl]);
    $matieres = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matriculeEtud = trim($_POST['Matriculation']);
    $codeMat       = $_POST['codeMat'];
    $codePeriode   = $_POST['codePeriode'];
    $note          = floatval($_POST['note']);
    $codecl        = $_POST['codecl'];

    if ($note < 0 || $note > 20) {
        $message = "❌ Note invalide (0 à 20)";
    } else {
        $check = $pdo->prepare(
            "SELECT COUNT(*) FROM inscription
             WHERE Matriculation = ?
               AND codecl = ?
               AND codePeriode = ?"
        );
        $check->execute([$matriculeEtud, $codecl, $codePeriode]);

        if ($check->fetchColumn() == 0) {
            $message = "❌ Étudiant non inscrit dans cette classe pour cette période.";
        } else {
            $insert = $pdo->prepare(
                "INSERT INTO evaluer
                (MatriculeEns, Matriculation, codeMat, codePeriode, note, date_saisie)
                VALUES (?, ?, ?, ?, ?, NOW())"
            );

            $insert->execute([
                $matriculeEns,
                $matriculeEtud,
                $codeMat,
                $codePeriode,
                $note
            ]);

            redirect('mes_notes.php');
        }
    }
}

$title = 'Saisie des notes - Enseignant';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
require_once '../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">📝 Saisie des notes</h2>
        <p class="text-gray-600">Enregistrez les notes des étudiants par matière et période</p>
    </div>

    <!-- Étape 1 : Sélection de la classe -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6 form-card">
        <h3 class="text-xl font-bold text-gray-800 mb-4">1️⃣ Choisir une classe</h3>
        <form method="get">
            <div class="flex gap-4">
                <select name="codecl" onchange="this.form.submit()" required
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition">
                    <option value="">-- Choisir une classe --</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['codecl'] ?>"
                            <?= $codecl === $c['codecl'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['libellecl']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <?php if ($codecl): ?>
    <!-- Étape 2 : Formulaire de saisie -->
    <div class="bg-white rounded-xl shadow-lg p-8 form-card">
        <h3 class="text-xl font-bold text-gray-800 mb-6">2️⃣ Saisir la note</h3>
        
        <form method="post" class="space-y-6">
            <input type="hidden" name="codecl" value="<?= $codecl ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="codePeriode" class="block text-sm font-semibold text-gray-700 mb-2">
                        Période <span class="text-red-500">*</span>
                    </label>
                    <select id="codePeriode" name="codePeriode" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition">
                        <?php foreach ($periodes as $p): ?>
                            <option value="<?= $p['codePeriode'] ?>">
                                <?= htmlspecialchars($p['libellePeriode']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="codeMat" class="block text-sm font-semibold text-gray-700 mb-2">
                        Matière <span class="text-red-500">*</span>
                    </label>
                    <select id="codeMat" name="codeMat" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition">
                        <?php foreach ($matieres as $m): ?>
                            <option value="<?= $m['codeMat'] ?>">
                                <?= htmlspecialchars($m['libelleMat']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label for="Matriculation" class="block text-sm font-semibold text-gray-700 mb-2">
                    Matricule étudiant <span class="text-red-500">*</span>
                </label>
                <input type="text" id="Matriculation" name="Matriculation" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                       placeholder="Ex: 25INF00001">
            </div>

            <div>
                <label for="note" class="block text-sm font-semibold text-gray-700 mb-2">
                    Note /20 <span class="text-red-500">*</span>
                </label>
                <input type="number" id="note" name="note" step="0.5" min="0" max="20" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                       placeholder="Ex: 15.5">
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit"
                        class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg">
                    💾 Enregistrer
                </button>
                <a href="<?= BASE_URL ?>/enseignant/mes_notes.php"
                   class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition text-center">
                    📄 Voir les notes
                </a>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php
require_once '../includes/footer.php';
?>
