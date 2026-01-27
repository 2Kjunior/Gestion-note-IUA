<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/check_auth.php';

if ($_SESSION['role'] !== ROLE_ADMIN) {
    redirect('/auth/login.php');
}

$pdo = Database::getInstance()->getConnection();
$matieres = $pdo->query("SELECT * FROM matiere ORDER BY libelleMat")->fetchAll();

$title = 'Matières - Admin';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <!-- En-tête avec bouton -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">📘 Gestion des matières</h2>
        <a href="<?= BASE_URL ?>/admin/matieres/ajouter.php" 
           class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition shadow-md hover:shadow-lg">
            ➕ Ajouter une matière
        </a>
    </div>

    <!-- Tableau moderne -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden table-modern">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Libellé</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Crédits</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($matieres)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Aucune matière enregistrée.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($matieres as $matiere): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900"><?= e($matiere['codeMat']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700"><?= e($matiere['libelleMat']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?= e($matiere['credits']) ?> crédits
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= BASE_URL ?>/admin/matieres/modifier.php?code=<?= urlencode($matiere['codeMat']) ?>" 
                                   class="text-blue-600 hover:text-blue-900 mr-4">✏️ Modifier</a>
                                <a href="<?= BASE_URL ?>/admin/matieres/supprimer.php?code=<?= urlencode($matiere['codeMat']) ?>"
                                   onclick="return confirm('Supprimer cette matière ?');"
                                   class="text-red-600 hover:text-red-900">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="<?= BASE_URL ?>/admin/index.php" 
           class="text-gray-600 hover:text-navy transition">
            ⬅️ Retour dashboard
        </a>
    </div>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
