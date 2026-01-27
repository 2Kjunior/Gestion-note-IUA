<?php
/*********************************************************
 * PAGE : Liste des périodes
 * Rôle : Admin uniquement
 *********************************************************/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../config/constants.php';
require_once '../../auth/check_auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();

$periodes = $pdo->query(
    "SELECT codePeriode, libellePeriode, dateDebut, dateFin
     FROM periode
     ORDER BY dateDebut DESC"
)->fetchAll();

$title = 'Liste des périodes - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <!-- En-tête avec bouton -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">📅 Liste des périodes</h2>
        <a href="<?= BASE_URL ?>/admin/periodes/ajouter.php"
            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-bold transition shadow-lg hover:shadow-xl">
            ➕ Ajouter une période
        </a>
    </div>

    <!-- Tableau moderne -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden table-modern">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Code
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Libellé
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Début
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fin
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($periodes)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Aucune période enregistrée.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($periodes as $p): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="text-sm font-medium text-gray-900"><?= htmlspecialchars($p['codePeriode']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700"><?= htmlspecialchars($p['libellePeriode']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600"><?= htmlspecialchars($p['dateDebut']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600"><?= htmlspecialchars($p['dateFin']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= BASE_URL ?>/admin/periodes/modifier.php?codePeriode=<?= urlencode($p['codePeriode']) ?>"
                                    class="text-blue-600 hover:text-blue-900 mr-4">✏️ Modifier</a>
                                <a href="<?= BASE_URL ?>/admin/periodes/supprimer.php?codePeriode=<?= urlencode($p['codePeriode']) ?>"
                                    onclick="return confirm('Supprimer cette période ?')"
                                    class="text-red-600 hover:text-red-900">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="<?= BASE_URL ?>/admin/index.php" class="text-gray-600 hover:text-navy transition">
            ⬅ Retour admin
        </a>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>