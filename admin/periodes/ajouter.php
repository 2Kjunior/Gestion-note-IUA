<?php
/*********************************************************
 * PAGE : Ajouter une période
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

$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codePeriode    = trim($_POST['codePeriode'] ?? '');
    $libellePeriode = trim($_POST['libellePeriode'] ?? '');
    $dateDebut      = $_POST['dateDebut'] ?? '';
    $dateFin        = $_POST['dateFin'] ?? '';

    if ($codePeriode === '' || $libellePeriode === '' || $dateDebut === '' || $dateFin === '') {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif ($dateDebut >= $dateFin) {
        $erreur = "La date de début doit être antérieure à la date de fin.";
    } else {
        $check = $pdo->prepare(
            "SELECT COUNT(*) FROM periode WHERE codePeriode = ?"
        );
        $check->execute([$codePeriode]);

        if ($check->fetchColumn() > 0) {
            $erreur = "Ce code de période existe déjà.";
        } else {
            $insert = $pdo->prepare(
                "INSERT INTO periode (codePeriode, libellePeriode, dateDebut, dateFin)
                 VALUES (?, ?, ?, ?)"
            );

            $insert->execute([
                $codePeriode,
                $libellePeriode,
                $dateDebut,
                $dateFin
            ]);

            redirect('/admin/periodes/liste.php');
        }
    }
}

$title = 'Ajouter une période - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">📅 Ajouter une période académique</h2>
            <p class="text-gray-600">Remplissez le formulaire pour créer une nouvelle période</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <form method="post" class="space-y-6">
                <div>
                    <label for="codePeriode" class="block text-sm font-semibold text-gray-700 mb-2">
                        Code période <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="codePeriode"
                        name="codePeriode" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        placeholder="Ex: 2024S1"
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
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        placeholder="Ex: Année 2024–2025 Semestre 1"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="dateDebut" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de début <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="dateDebut"
                            name="dateDebut" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        >
                    </div>

                    <div>
                        <label for="dateFin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de fin <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="dateFin"
                            name="dateFin" 
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
