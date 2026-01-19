<?php
/*********************************************************
 * PAGE : Modifier un étudiant
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
    redirect('/admin/etudiants/liste.php');
}

$matricule = $_GET['matricule'];
$erreur = null;

$stmt = $pdo->prepare(
    "SELECT Matriculation, nomEt, prenomEt, Email, photo
     FROM etudiant
     WHERE Matriculation = ?"
);
$stmt->execute([$matricule]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    redirect('/admin/etudiants/liste.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = trim($_POST['nomEt'] ?? '');
    $prenom = trim($_POST['prenomEt'] ?? '');
    $email  = trim($_POST['Email'] ?? '');

    if ($nom === '' || $prenom === '') {
        $erreur = "Nom et prénom sont obligatoires.";
    } else {
        $photo = $etudiant['photo'];

        if (!empty($_FILES['photo']['name'])) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/assets/images/etudiants/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {
                $erreur = "Format de photo non autorisé (jpg, jpeg, png).";
            } else {
                $photoName = $matricule . '_' . time() . '.' . $ext;
                $photoPath = $uploadDir . $photoName;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                    $photo = $photoName;
                } else {
                    $erreur = "Erreur lors de l'upload de la photo.";
                }
            }
        }

        if (!$erreur) {
            $update = $pdo->prepare(
                "UPDATE etudiant
                 SET nomEt = ?, prenomEt = ?, Email = ?, photo = ?
                 WHERE Matriculation = ?"
            );

            $update->execute([
                $nom,
                $prenom,
                $email,
                $photo,
                $matricule
            ]);

            redirect('/admin/etudiants/liste.php');
        }
    }
}

$title = 'Modifier étudiant - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">✏️ Modifier un étudiant</h2>
            <p class="text-gray-600">Modifiez les informations de l'étudiant</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <form method="post" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="Matriculation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Matricule (non modifiable)
                    </label>
                    <input 
                        type="text" 
                        id="Matriculation"
                        value="<?= e($etudiant['Matriculation']) ?>" 
                        disabled
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nomEt" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nomEt"
                            name="nomEt" 
                            value="<?= e($etudiant['nomEt']) ?>" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        >
                    </div>

                    <div>
                        <label for="prenomEt" class="block text-sm font-semibold text-gray-700 mb-2">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="prenomEt"
                            name="prenomEt" 
                            value="<?= e($etudiant['prenomEt']) ?>" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        >
                    </div>
                </div>

                <div>
                    <label for="Email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="Email"
                        name="Email" 
                        value="<?= e($etudiant['Email']) ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                    >
                </div>

                <div>
                    <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">
                        Photo
                    </label>
                    <?php if ($etudiant['photo']): ?>
                        <div class="mb-3">
                            <img src="<?= BASE_URL ?>/assets/images/etudiants/<?= e($etudiant['photo']) ?>"
                                 width="100" 
                                 alt="Photo étudiant"
                                 class="rounded-lg border-2 border-gray-200">
                        </div>
                    <?php endif; ?>
                    <input 
                        type="file" 
                        id="photo"
                        name="photo" 
                        accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                    >
                    <p class="mt-1 text-xs text-gray-500">Laisser vide pour conserver la photo actuelle</p>
                </div>

                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg"
                    >
                        💾 Enregistrer
                    </button>
                    <a href="<?= BASE_URL ?>/admin/etudiants/liste.php" 
                       class="flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition text-center">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-6">
            <a href="<?= BASE_URL ?>/admin/etudiants/liste.php" 
               class="text-gray-600 hover:text-navy transition">
                ⬅️ Retour à la liste
            </a>
        </div>
    </div>
</div>

<?php
require_once '../../includes/footer.php';
?>
