<?php
/*********************************************************
 * MODIFIER UNE PÉRIODE
 *********************************************************/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../config/constants.php';
require_once '../../auth/check_auth.php';

if ($_SESSION['role'] !== 'admin') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();

if (!isset($_GET['codePeriode'])) {
    redirect('/admin/periodes/liste.php');
}

$codePeriode = $_GET['codePeriode'];
$erreur = null;

$stmt = $pdo->prepare("SELECT * FROM periode WHERE codePeriode = ?");
$stmt->execute([$codePeriode]);
$periode = $stmt->fetch();

if (!$periode) {
    redirect('/admin/periodes/liste.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = trim($_POST['libellePeriode']);
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];

    if ($libelle === '' || $dateDebut === '' || $dateFin === '') {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif ($dateDebut > $dateFin) {
        $erreur = "Date début > date fin.";
    } else {
        $pdo->prepare(
            "UPDATE periode
             SET libellePeriode=?, dateDebut=?, dateFin=?
             WHERE codePeriode=?"
        )->execute([$libelle, $dateDebut, $dateFin, $codePeriode]);

        redirect('/admin/periodes/liste.php');
    }
}

$title = 'Modifier période - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">✏️ Modifier période</h2>
            <p class="text-gray-600">Modifiez les informations de la période</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <form method="post" class="space-y-6">
                <div>
                    <label for="codePeriode" class="block text-sm font-semibold text-gray-700 mb-2">
                        Code (non modifiable)
                    </label>
                    <input 
                        type="text" 
                        id="codePeriode"
                        value="<?= htmlspecialchars($periode['codePeriode']) ?>" 
                        disabled
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600"
                    >
                </div>

                <div>
                    <label for="libellePeriode" class="block text-sm font-semibold text-gray-700 mb-2">
                        Libellé <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="libellePeriode"
                        name="libellePeriode"
                        value="<?= htmlspecialchars($periode['libellePeriode']) ?>" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="dateDebut" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date début <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="dateDebut"
                            name="dateDebut"
                            value="<?= htmlspecialchars($periode['dateDebut']) ?>" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        >
                    </div>

                    <div>
                        <label for="dateFin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date fin <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="dateFin"
                            name="dateFin"
                            value="<?= htmlspecialchars($periode['dateFin']) ?>" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        >
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg"
                    >
                        💾 Enregistrer
                    </button>
                    <a href="<?= BASE_URL ?>/admin/periodes/liste.php" 
                       class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition text-center">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <a href="<?= BASE_URL ?>/admin/periodes/liste.php" 
               class="text-gray-600 hover:text-navy transition">
                ⬅️ Retour à la liste
            </a>
        </div>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>
