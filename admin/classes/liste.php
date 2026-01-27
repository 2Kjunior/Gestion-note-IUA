<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/check_auth.php';

if ($_SESSION['role'] !== ROLE_ADMIN) {
    redirect('/auth/login.php');
}

$pdo = Database::getInstance()->getConnection();
$classes = $pdo->query("SELECT * FROM classe ORDER BY libellecl")->fetchAll();

$title = 'Classes - Admin';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <!-- En-tête avec bouton -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">📚 Gestion des classes</h2>
        <a href="<?= BASE_URL ?>/admin/classes/ajouter.php"
            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-bold transition shadow-lg hover:shadow-xl">
            ➕ Ajouter une classe
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
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($classes)): ?>
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                            Aucune classe enregistrée.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($classes as $classe): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900"><?= e($classe['codecl']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700"><?= e($classe['libellecl']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= BASE_URL ?>/admin/classes/modifier.php?code=<?= urlencode($classe['codecl']) ?>"
                                    class="text-blue-600 hover:text-blue-900 mr-4">✏️ Modifier</a>
                                <a href="<?= BASE_URL ?>/admin/classes/supprimer.php?code=<?= urlencode($classe['codecl']) ?>"
                                    onclick="return confirm('Supprimer cette classe ?');"
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
            ⬅️ Retour dashboard
        </a>
    </div>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>