<?php
/*********************************************************
 * PAGE : Liste des inscriptions
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

$stmt = $pdo->query(
    "SELECT 
        i.id_inscription,
        e.Matriculation,
        e.nomEt,
        e.prenomEt,
        c.libellecl,
        p.libellePeriode
     FROM inscription i
     JOIN etudiant e ON e.Matriculation = i.Matriculation
     JOIN classe c ON c.codecl = i.codecl
     JOIN periode p ON p.codePeriode = i.codePeriode
     ORDER BY p.codePeriode DESC, c.libellecl, e.nomEt"
);

$inscriptions = $stmt->fetchAll();

$title = 'Liste des inscriptions - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <!-- En-tête avec bouton -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">📋 Liste des inscriptions</h2>
        <a href="<?= BASE_URL ?>/admin/inscriptions/ajouter.php"
            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-bold transition shadow-lg hover:shadow-xl">
            ➕ Nouvelle inscription
        </a>
    </div>

    <!-- Tableau moderne -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden table-modern">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">#</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Matricule</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Étudiant</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Classe
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Période
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($inscriptions)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Aucune inscription enregistrée.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($inscriptions as $i): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900"><?= (int) $i['id_inscription'] ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700"><?= htmlspecialchars($i['Matriculation']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="text-sm text-gray-700"><?= htmlspecialchars($i['nomEt'] . ' ' . $i['prenomEt']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600"><?= htmlspecialchars($i['libellecl']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <?= htmlspecialchars($i['libellePeriode']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= BASE_URL ?>/admin/inscriptions/supprimer.php?id=<?= $i['id_inscription'] ?>"
                                    onclick="return confirm('Supprimer cette inscription ?');"
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
            ⬅ Retour dashboard
        </a>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>