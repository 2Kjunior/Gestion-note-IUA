<?php
/*********************************************************
 * PAGE : Ajouter un étudiant
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

$uploadDir = $_SERVER['DOCUMENT_ROOT'] . BASE_URL . '/assets/images/etudiants/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricule = trim($_POST['Matriculation'] ?? '');
    $nom       = trim($_POST['nomEt'] ?? '');
    $prenom    = trim($_POST['prenomEt'] ?? '');
    $email     = trim($_POST['Email'] ?? '');

    if ($matricule === '' || $nom === '' || $prenom === '') {
        $erreur = "Matricule, nom et prénom sont obligatoires.";
    } else {
        $check = $pdo->prepare(
            "SELECT COUNT(*) FROM etudiant WHERE Matriculation = ?"
        );
        $check->execute([$matricule]);

        if ($check->fetchColumn() > 0) {
            $erreur = "Ce matricule étudiant existe déjà.";
        } else {
            $photoName = null;

            if (!empty($_FILES['photo']['name'])) {
                $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $extensionsAutorisees = ['jpg', 'jpeg', 'png'];

                if (!in_array($extension, $extensionsAutorisees)) {
                    $erreur = "Format de photo non autorisé (jpg, jpeg, png uniquement).";
                } else {
                    $photoName = $matricule . '_' . time() . '.' . $extension;
                    $destination = $uploadDir . $photoName;

                    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                        $erreur = "Erreur lors de l'upload de la photo.";
                    }
                }
            }

            if ($erreur === null) {
                $stmt = $pdo->prepare(
                    "INSERT INTO etudiant
                     (Matriculation, nomEt, prenomEt, Email, photo)
                     VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->execute([
                    $matricule,
                    $nom,
                    $prenom,
                    $email,
                    $photoName
                ]);

                // Création automatique du compte étudiant
                if (!empty($email)) {
                    $login = $email;
                    $password_hash = password_hash($matricule, PASSWORD_DEFAULT);
                    $role = 'etudiant';
                    $id_reference = $matricule;

                    $checkUser = $pdo->prepare(
                        "SELECT COUNT(*) FROM utilisateurs WHERE login = ?"
                    );
                    $checkUser->execute([$login]);
                    
                    if ($checkUser->fetchColumn() == 0) {
                        $insertUser = $pdo->prepare(
                            "INSERT INTO utilisateurs (login, password_hash, role, id_reference)
                             VALUES (?, ?, ?, ?)"
                        );
                
                        $insertUser->execute([
                            $login,
                            $password_hash,
                            $role,
                            $id_reference
                        ]);
                    }
                }

                redirect('/admin/etudiants/liste.php');
            }
        }
    }
}

$title = 'Ajouter un étudiant - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">➕ Ajouter un étudiant</h2>
            <p class="text-gray-600">Remplissez le formulaire pour créer un nouvel étudiant</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <form method="post" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="Matriculation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Matricule étudiant <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="Matriculation"
                        name="Matriculation" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        placeholder="Ex: 25INF00001"
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        placeholder="exemple@iua.ci"
                    >
                    <p class="mt-1 text-xs text-gray-500">Un compte utilisateur sera créé automatiquement avec cet email</p>
                </div>

                <div>
                    <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">
                        Photo
                    </label>
                    <input 
                        type="file" 
                        id="photo"
                        name="photo" 
                        accept=".jpg,.jpeg,.png"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                    >
                    <p class="mt-1 text-xs text-gray-500">Formats acceptés : JPG, JPEG, PNG</p>
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
