<?php
/*********************************************************
 * PAGE : Liste des étudiants
 * Rôle : Admin uniquement
 *********************************************************/

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../config/constants.php';
require_once '../../auth/check_auth.php';

// Sécurité : admin uniquement
if ($_SESSION['role'] !== 'admin') {
    redirect('/admin/index.php');
}

$pdo = Database::getInstance()->getConnection();

// Récupération des étudiants
$stmt = $pdo->query(
    "SELECT Matriculation, nomEt, prenomEt, Email, photo
     FROM etudiant
     ORDER BY nomEt, prenomEt"
);
$etudiants = $stmt->fetchAll();

$title = 'Gestion des étudiants - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <!-- En-tête avec bouton -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">🎓 Gestion des étudiants</h2>
        <a href="<?= BASE_URL ?>/admin/etudiants/ajouter.php"
            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-bold transition shadow-lg hover:shadow-xl">
            ➕ Ajouter un étudiant
        </a>
    </div>

    <!-- Tableau moderne -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden table-modern">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Photo
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Matricule</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nom
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Prénom
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($etudiants)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Aucun étudiant enregistré.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($etudiants as $e): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if (!empty($e['photo'])): ?>
                                    <img src="<?= BASE_URL ?>/assets/images/etudiants/<?= htmlspecialchars($e['photo']) ?>"
                                        alt="Photo" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                                <?php else: ?>
                                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-xl">
                                        🎓
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="text-sm font-medium text-gray-900"><?= htmlspecialchars($e['Matriculation']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700"><?= htmlspecialchars($e['nomEt']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700"><?= htmlspecialchars($e['prenomEt']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600"><?= htmlspecialchars($e['Email'] ?? '-') ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= BASE_URL ?>/admin/etudiants/modifier.php?matricule=<?= urlencode($e['Matriculation']) ?>"
                                    class="text-blue-600 hover:text-blue-900 mr-4">✏️ Modifier</a>
                                <a href="<?= BASE_URL ?>/admin/etudiants/supprimer.php?matricule=<?= urlencode($e['Matriculation']) ?>"
                                    onclick="return confirm('Supprimer cet étudiant ?');"
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
            ⬅ Retour dashboard admin
        </a>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>