<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/check_auth.php';

if ($_SESSION['role'] !== ROLE_ADMIN) {
    redirect('/auth/login.php');
}

$pdo = Database::getInstance()->getConnection();

$code = $_GET['code'] ?? null;
$error = null;

if (!$code) {
    redirect('/admin/classes/liste.php');
}

$stmt = $pdo->prepare("SELECT * FROM classe WHERE codecl = :code");
$stmt->execute(['code' => $code]);
$classe = $stmt->fetch();

if (!$classe) {
    redirect('/admin/classes/liste.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = trim($_POST['libellecl'] ?? '');

    if ($libelle === '') {
        $error = "Le libellé est obligatoire.";
    } else {
        $update = $pdo->prepare(
            "UPDATE classe SET libellecl = :libelle WHERE codecl = :code"
        );
        $update->execute([
            'libelle' => $libelle,
            'code' => $code
        ]);

        redirect('/admin/classes/liste.php');
    }
}

$title = 'Modifier une classe - Admin';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">✏️ Modifier la classe</h2>
            <p class="text-gray-600">Modifiez les informations de la classe</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <form method="post" class="space-y-6">
                <div>
                    <label for="codecl" class="block text-sm font-semibold text-gray-700 mb-2">
                        Code classe (non modifiable)
                    </label>
                    <input 
                        type="text" 
                        id="codecl"
                        value="<?= e($classe['codecl']) ?>" 
                        disabled
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600"
                    >
                </div>

                <div>
                    <label for="libellecl" class="block text-sm font-semibold text-gray-700 mb-2">
                        Libellé classe <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="libellecl"
                        name="libellecl" 
                        value="<?= e($classe['libellecl']) ?>"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                    >
                </div>

                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg"
                    >
                        💾 Mettre à jour
                    </button>
                    <a href="<?= BASE_URL ?>/admin/classes/liste.php" 
                       class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition text-center">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <a href="<?= BASE_URL ?>/admin/classes/liste.php" 
               class="text-gray-600 hover:text-navy transition">
                ⬅️ Retour à la liste
            </a>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
