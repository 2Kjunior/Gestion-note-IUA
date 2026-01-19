<?php
/*********************************************************
 * PAGE : Modifier un enseignant
 * Rôle : Admin uniquement
 *********************************************************/

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../config/constants.php';
require_once '../../auth/check_auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    redirect('/auth/login.php');
}

$pdo = Database::getInstance()->getConnection();

if (!isset($_GET['matricule']) || empty($_GET['matricule'])) {
    redirect('/admin/enseignants/liste.php');
}

$matricule = $_GET['matricule'];
$erreur = null;

$stmt = $pdo->prepare(
    "SELECT MatriculeEns, nomEns, prenomEns, telEns, Email
     FROM enseignant
     WHERE MatriculeEns = ?"
);
$stmt->execute([$matricule]);
$enseignant = $stmt->fetch();

if (!$enseignant) {
    redirect('/admin/enseignants/liste.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = trim($_POST['nomEns'] ?? '');
    $prenom = trim($_POST['prenomEns'] ?? '');
    $tel    = trim($_POST['telEns'] ?? '');
    $email  = trim($_POST['Email'] ?? '');

    if ($nom === '' || $prenom === '') {
        $erreur = "Nom et prénom sont obligatoires.";
    } else {
        $update = $pdo->prepare(
            "UPDATE enseignant
             SET nomEns = ?, prenomEns = ?, telEns = ?, Email = ?
             WHERE MatriculeEns = ?"
        );

        $update->execute([
            $nom,
            $prenom,
            $tel,
            $email,
            $matricule
        ]);

        redirect('/admin/enseignants/liste.php');
    }
}

$title = 'Modifier enseignant - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">✏️ Modifier un enseignant</h2>
            <p class="text-gray-600">Modifiez les informations de l'enseignant</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <form method="post" class="space-y-6">
                <div>
                    <label for="MatriculeEns" class="block text-sm font-semibold text-gray-700 mb-2">
                        Matricule (non modifiable)
                    </label>
                    <input 
                        type="text" 
                        id="MatriculeEns"
                        value="<?= htmlspecialchars($enseignant['MatriculeEns']) ?>" 
                        disabled
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nomEns" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nomEns"
                            name="nomEns"
                            value="<?= htmlspecialchars($enseignant['nomEns']) ?>" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        >
                    </div>

                    <div>
                        <label for="prenomEns" class="block text-sm font-semibold text-gray-700 mb-2">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="prenomEns"
                            name="prenomEns"
                            value="<?= htmlspecialchars($enseignant['prenomEns']) ?>" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        >
                    </div>
                </div>

                <div>
                    <label for="telEns" class="block text-sm font-semibold text-gray-700 mb-2">
                        Téléphone
                    </label>
                    <input 
                        type="text" 
                        id="telEns"
                        name="telEns"
                        value="<?= htmlspecialchars($enseignant['telEns'] ?? '') ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                    >
                </div>

                <div>
                    <label for="Email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="Email"
                        name="Email"
                        value="<?= htmlspecialchars($enseignant['Email']) ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                    >
                </div>

                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg"
                    >
                        💾 Enregistrer
                    </button>
                    <a href="<?= BASE_URL ?>/admin/enseignants/liste.php" 
                       class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition text-center">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <a href="<?= BASE_URL ?>/admin/enseignants/liste.php" 
               class="text-gray-600 hover:text-navy transition">
                ⬅️ Retour à la liste
            </a>
        </div>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>
