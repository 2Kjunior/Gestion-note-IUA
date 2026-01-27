<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/functions.php';

// Si déjà connecté → redirection
if (isLoggedIn()) {
    redirect('/index.php');
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $pdo = Database::getInstance()->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = :login AND actif = 1");
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['id_reference'] = $user['id_reference'];


            switch ($user['role']) {
                case ROLE_ADMIN:
                    redirect('/admin/index.php');
                case ROLE_ENSEIGNANT:
                    redirect('/enseignant/index.php');
                case ROLE_ETUDIANT:
                    redirect('/etudiant/index.php');
                default:
                    $error = "Rôle utilisateur invalide.";
            }
        } else {
            $error = "Identifiants incorrects.";
        }
    }
}

$title = 'Connexion - IUA';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<!-- Page de connexion centrée -->
<div class="flex-1 flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl shadow-xl p-8 form-card">
            <div class="text-center mb-8">
                <div class="text-5xl mb-4">🔐</div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Connexion</h2>
                <p class="text-gray-600">Accédez à votre espace personnel</p>
            </div>

            <form method="post" class="space-y-6">
                <div>
                    <label for="login" class="block text-sm font-semibold text-gray-700 mb-2">
                        Identifiant (Email)
                    </label>
                    <input 
                        type="text" 
                        id="login"
                        name="login" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        placeholder="Votre email ou identifiant"
                        autocomplete="username"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Mot de passe
                    </label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy focus:border-navy transition"
                        placeholder="Votre mot de passe"
                        autocomplete="current-password"
                    >
                </div>

                <button 
                    type="submit"
                    class="w-full py-3 bg-navy hover:bg-blue-900 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg"
                >
                    Se connecter
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="<?= BASE_URL ?>/index.php" 
                   class="text-sm text-gray-600 hover:text-navy transition">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>

        <!-- Info pour les différents rôles -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-gray-700">
            <p class="font-semibold mb-2">💡 Information :</p>
            <ul class="list-disc list-inside space-y-1">
                <li><strong>Admin :</strong> Gestion complète du système</li>
                <li><strong>Enseignant :</strong> Saisie et consultation des notes</li>
                <li><strong>Étudiant :</strong> Consultation de vos notes</li>
            </ul>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/alerts.php';
require_once __DIR__ . '/../includes/footer.php';
?>
