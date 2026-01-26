<?php
/*********************************************************
 * PAGE PROFIL ÉTUDIANT
 * Affichage et modification des informations personnelles
 *********************************************************/

require_once '../config/database.php';
require_once '../config/functions.php';
require_once '../config/constants.php';
require_once '../auth/check_auth.php';

// 🔐 Sécurité : étudiant uniquement
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();

// 🔑 Matricule étudiant depuis la session
if (!isset($_SESSION['id_reference'])) {
    die("❌ Étudiant non identifié (id_reference manquant).");
}

$matriculation = $_SESSION['id_reference'];
$success = null;
$error = null;

/* =========================
   TRAITEMENT DU FORMULAIRE
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomEt = trim($_POST['nomEt'] ?? '');
    $prenomEt = trim($_POST['prenomEt'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validation
    if (empty($nomEt) || empty($prenomEt) || empty($email)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } else {
        try {
            // Mise à jour des informations
            $stmt = $pdo->prepare("
                UPDATE etudiant 
                SET nomEt = ?, prenomEt = ?, Email = ?
                WHERE Matriculation = ?
            ");
            $stmt->execute([$nomEt, $prenomEt, $email, $matriculation]);

            $success = "✅ Profil mis à jour avec succès !";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Cette adresse email est déjà utilisée.";
            } else {
                $error = "Erreur lors de la mise à jour : " . $e->getMessage();
            }
        }
    }
}

/* =========================
   RÉCUPÉRATION DES INFOS
   ========================= */
$stmt = $pdo->prepare("
    SELECT Matriculation, nomEt, prenomEt, Email, photo
    FROM etudiant
    WHERE Matriculation = ?
");
$stmt->execute([$matriculation]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    die("❌ Étudiant introuvable en base.");
}

/* =========================
   STATISTIQUES ÉTUDIANT
   ========================= */

// Nombre de notes
$stmt = $pdo->prepare("SELECT COUNT(*) FROM evaluer WHERE Matriculation = ?");
$stmt->execute([$matriculation]);
$nbNotes = $stmt->fetchColumn();

// Moyenne générale
$stmt = $pdo->prepare("SELECT AVG(note) FROM evaluer WHERE Matriculation = ?");
$stmt->execute([$matriculation]);
$moyenneGenerale = $stmt->fetchColumn();

// Nombre de matières
$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT codeMat) 
    FROM evaluer 
    WHERE Matriculation = ?
");
$stmt->execute([$matriculation]);
$nbMatieres = $stmt->fetchColumn();

// Classe actuelle
$stmt = $pdo->prepare("
    SELECT c.libellecl, p.libellePeriode
    FROM inscription i
    JOIN classe c ON i.codecl = c.codecl
    JOIN periode p ON i.codePeriode = p.codePeriode
    WHERE i.Matriculation = ?
    ORDER BY p.dateDebut DESC
    LIMIT 1
");
$stmt->execute([$matriculation]);
$inscription = $stmt->fetch();

$title = 'Mon Profil - Étudiant IUA';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="container mx-auto px-4 py-8 flex-1">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            👤 Mon Profil
        </h1>
        <p class="text-gray-600">
            Consultez et modifiez vos informations personnelles
        </p>
    </div>

    <?php if ($success): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
            <p class="text-green-700 font-medium">
                <?= $success ?>
            </p>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <p class="text-red-700 font-medium">
                <?= $error ?>
            </p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne Gauche : Photo et Stats -->
        <div class="lg:col-span-1">
            <!-- Card Photo de Profil -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6 text-center">
                <div class="mb-4">
                    <div
                        class="w-32 h-32 mx-auto bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white text-5xl font-bold shadow-xl">
                        <?= strtoupper(substr($etudiant['prenomEt'], 0, 1) . substr($etudiant['nomEt'], 0, 1)) ?>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-1">
                    <?= htmlspecialchars($etudiant['prenomEt'] . ' ' . $etudiant['nomEt']) ?>
                </h2>
                <p class="text-gray-600 mb-4">
                    🎓 Étudiant
                </p>
                <div class="bg-purple-50 rounded-lg p-3">
                    <p class="text-sm text-gray-600">Matricule</p>
                    <p class="text-lg font-bold text-purple-600">
                        <?= htmlspecialchars($etudiant['Matriculation']) ?>
                    </p>
                </div>
            </div>

            <!-- Card Statistiques -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="text-2xl mr-2">📊</span>
                    Statistiques
                </h3>

                <div class="space-y-4">
                    <!-- Moyenne Générale -->
                    <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Moyenne Générale</p>
                        <p class="text-3xl font-bold text-emerald-600">
                            <?= $moyenneGenerale ? number_format($moyenneGenerale, 2) : 'N/A' ?>
                            <?php if ($moyenneGenerale): ?>
                                <span class="text-lg">/20</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Nombre de Notes -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Notes Enregistrées</p>
                        <p class="text-3xl font-bold text-blue-600">
                            <?= $nbNotes ?>
                        </p>
                    </div>

                    <!-- Nombre de Matières -->
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Matières Suivies</p>
                        <p class="text-3xl font-bold text-purple-600">
                            <?= $nbMatieres ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne Droite : Informations et Formulaire -->
        <div class="lg:col-span-2">
            <!-- Card Informations Académiques -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="text-2xl mr-2">🎓</span>
                    Informations Académiques
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Classe Actuelle</p>
                        <p class="text-lg font-semibold text-gray-800">
                            <?= $inscription ? htmlspecialchars($inscription['libellecl']) : 'Non inscrit' ?>
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Période</p>
                        <p class="text-lg font-semibold text-gray-800">
                            <?= $inscription ? htmlspecialchars($inscription['libellePeriode']) : 'N/A' ?>
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Statut</p>
                        <p class="text-lg font-semibold text-green-600">
                            ✓ Actif
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="text-lg font-semibold text-gray-800 break-all">
                            <?= htmlspecialchars($etudiant['Email']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card Formulaire de Modification -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="text-2xl mr-2">✏️</span>
                    Modifier Mes Informations
                </h3>

                <form method="POST" class="space-y-4">
                    <!-- Nom -->
                    <div>
                        <label for="nomEt" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nomEt" name="nomEt" value="<?= htmlspecialchars($etudiant['nomEt']) ?>"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Prénom -->
                    <div>
                        <label for="prenomEt" class="block text-sm font-semibold text-gray-700 mb-2">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="prenomEt" name="prenomEt"
                            value="<?= htmlspecialchars($etudiant['prenomEt']) ?>" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Adresse Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($etudiant['Email']) ?>"
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Matricule (lecture seule) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Matricule (non modifiable)
                        </label>
                        <input type="text" value="<?= htmlspecialchars($etudiant['Matriculation']) ?>" disabled
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            💾 Enregistrer les Modifications
                        </button>
                        <a href="/gestion-notes-iua - Copy/etudiant/index.php"
                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors text-center">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>

            <!-- Card Sécurité -->
            <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="text-2xl mr-2">🔒</span>
                    Sécurité du Compte
                </h3>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <p class="text-blue-800 mb-2">
                        <strong>💡 Conseil de sécurité :</strong>
                    </p>
                    <ul class="list-disc list-inside text-blue-700 space-y-1 text-sm">
                        <li>Ne partagez jamais votre mot de passe</li>
                        <li>Déconnectez-vous après chaque session</li>
                        <li>Vérifiez régulièrement vos notes</li>
                        <li>Contactez l'administration en cas de problème</li>
                    </ul>
                </div>

                <div class="mt-4">
                    <p class="text-sm text-gray-600 mb-2">
                        Pour modifier votre mot de passe, contactez l'administration.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>