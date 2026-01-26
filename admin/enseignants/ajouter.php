<?php
/*********************************************************
 * PAGE : Ajouter un enseignant
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
    $matricule = trim($_POST['MatriculeEns'] ?? '');
    $nom       = trim($_POST['nomEns'] ?? '');
    $prenom    = trim($_POST['prenomEns'] ?? '');
    $tel       = trim($_POST['telEns'] ?? '');
    $email     = trim($_POST['Email'] ?? '');

    if ($matricule === '' || $nom === '' || $prenom === '' || $email === '') {
        $erreur = "Matricule, nom, prénom et email sont obligatoires.";
    } else {
        $check = $pdo->prepare(
            "SELECT COUNT(*) FROM enseignant WHERE MatriculeEns = ?"
        );
        $check->execute([$matricule]);

        if ($check->fetchColumn() > 0) {
            $erreur = "Ce matricule enseignant existe déjà.";
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO enseignant 
                 (MatriculeEns, nomEns, prenomEns, telEns, Email)
                 VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $matricule,
                $nom,
                $prenom,
                $tel,
                $email
            ]);

            // Création automatique du compte utilisateur
            $login = $email;
            $password_hash = password_hash($matricule, PASSWORD_DEFAULT);
            $role = 'enseignant';
            $id_reference = $matricule;

            $checkUser = $pdo->prepare(
                "SELECT COUNT(*) FROM utilisateurs WHERE login = ?"
            );
            $checkUser->execute([$login]);

            if ($checkUser->fetchColumn() == 0) {
                $insertUser = $pdo->prepare(
                    "INSERT INTO utilisateurs 
                    (login, password_hash, role, id_reference)
                    VALUES (?, ?, ?, ?)"
                );

                $insertUser->execute([
                    $login,
                    $password_hash,
                    $role,
                    $id_reference
                ]);
            }

            redirect('/admin/enseignants/liste.php');
        }
    }
}

$title = 'Ajouter un enseignant - Admin';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/alerts.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-black mb-2">➕ Ajouter un enseignant</h2>
            <p class="text-gray-600">Remplissez le formulaire pour créer un nouvel enseignant</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 form-card">
            <form method="post" class="space-y-6">
                <div>
                    <label for="MatriculeEns" class="block text-sm font-semibold text-gray-700 mb-2">
                        Matricule enseignant <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="MatriculeEns"
                        name="MatriculeEns" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        placeholder="Ex: ENS001"
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        placeholder="Ex: +225 07 12 34 56 78"
                    >
                </div>

                <div>
                    <label for="Email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="Email"
                        name="Email"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        placeholder="exemple@iua.ci"
                    >
                    <p class="mt-1 text-xs text-gray-500">Un compte utilisateur sera créé automatiquement avec cet email</p>
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
