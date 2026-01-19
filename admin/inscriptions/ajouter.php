<?php
/*********************************************************
 * PAGE : Inscription d'un étudiant
 * Rôle : Admin uniquement
 *********************************************************/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../config/constants.php';
require_once '../../auth/check_auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    redirect('/auth/login.php');
}

$pdo = Database::getInstance()->getConnection();

$message = null;
$erreur  = null;

$etudiants = $pdo->query(
    "SELECT Matriculation, nomEt, prenomEt
     FROM etudiant
     ORDER BY nomEt, prenomEt"
)->fetchAll();

$classes = $pdo->query(
    "SELECT codecl, libellecl
     FROM classe
     ORDER BY libellecl"
)->fetchAll();

$periodes = $pdo->query(
    "SELECT codePeriode, libellePeriode
     FROM periode
     ORDER BY dateDebut DESC"
)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricule   = $_POST['Matriculation'] ?? '';
    $codecl      = $_POST['codecl'] ?? '';
    $codePeriode = $_POST['codePeriode'] ?? '';

    if ($matricule === '' || $codecl === '' || $codePeriode === '') {
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        $check = $pdo->prepare(
            "SELECT COUNT(*) FROM inscription
             WHERE Matriculation = ?
               AND codecl = ?
               AND codePeriode = ?"
        );
        $check->execute([$matricule, $codecl, $codePeriode]);

        if ($check->fetchColumn() > 0) {
            $erreur = "Cet étudiant est déjà inscrit dans cette classe pour cette période.";
        } else {
            $insert = $pdo->prepare(
                "INSERT INTO inscription (Matriculation, codecl, codePeriode)
                 VALUES (?, ?, ?)"
            );

            $insert->execute([
                $matricule,
                $codecl,
                $codePeriode
            ]);

            $message = "✅ Inscription effectuée avec succès.";
        }
    }
}

$title = 'Inscription étudiant - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">📝 Inscription d'un étudiant</h2>
            <p class="text-gray-600">Inscrivez un étudiant dans une classe pour une période donnée</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <form method="post" class="space-y-6">
                <div>
                    <label for="Matriculation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Étudiant <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="Matriculation"
                        name="Matriculation" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                    >
                        <option value="">-- Choisir un étudiant --</option>
                        <?php foreach ($etudiants as $e): ?>
                            <option value="<?= e($e['Matriculation']) ?>">
                                <?= e($e['nomEt'] . ' ' . $e['prenomEt']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="codecl" class="block text-sm font-semibold text-gray-700 mb-2">
                        Classe <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="codecl"
                        name="codecl" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                    >
                        <option value="">-- Choisir une classe --</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= e($c['codecl']) ?>">
                                <?= e($c['libellecl']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="codePeriode" class="block text-sm font-semibold text-gray-700 mb-2">
                        Période académique <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="codePeriode"
                        name="codePeriode" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                    >
                        <option value="">-- Choisir une période --</option>
                        <?php foreach ($periodes as $p): ?>
                            <option value="<?= e($p['codePeriode']) ?>">
                                <?= e($p['libellePeriode']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg"
                    >
                        💾 Inscrire
                    </button>
                    <a href="<?= BASE_URL ?>/admin/inscriptions/liste.php" 
                       class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition text-center">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <a href="<?= BASE_URL ?>/admin/inscriptions/liste.php" 
               class="text-gray-600 hover:text-navy transition">
                ⬅️ Retour à la liste
            </a>
        </div>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>
