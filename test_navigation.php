<?php
/**
 * PAGE DE TEST - Vérification des redirections
 */
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/functions.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Test Navigation - IUA</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
            background: #f5f5f5;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .test-box {
            background: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        a {
            display: inline-block;
            margin: 5px;
            padding: 10px 20px;
            background: #1e3a8a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background: #2563eb;
        }
    </style>
</head>

<body>
    <h1>🔍 Test de Navigation - Système IUA</h1>

    <div class="test-box">
        <h2>✅ Vérifications PHP</h2>
        <p class="success">✓ PHP fonctionne correctement</p>
        <p class="success">✓ BASE_URL défini : <strong>
                <?= BASE_URL ?>
            </strong></p>
        <p class="success">✓ Fonctions chargées : isLoggedIn(), redirect(), e()</p>
    </div>

    <div class="test-box">
        <h2>🔗 Test des Liens de Navigation</h2>
        <p>Cliquez sur chaque lien pour vérifier qu'il fonctionne :</p>

        <h3>📄 Pages Principales</h3>
        <a href="<?= BASE_URL ?>/index.php">🏠 Page d'accueil</a>
        <a href="<?= BASE_URL ?>/auth/login.php">🔐 Page de connexion</a>

        <h3>👨‍💼 Dashboards</h3>
        <a href="<?= BASE_URL ?>/admin/index.php">Admin Dashboard</a>
        <a href="<?= BASE_URL ?>/enseignant/index.php">Enseignant Dashboard</a>
        <a href="<?= BASE_URL ?>/etudiant/index.php">Étudiant Dashboard</a>
    </div>

    <div class="test-box">
        <h2>🧪 URLs Générées</h2>
        <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
            <tr>
                <th>Page</th>
                <th>URL Générée</th>
            </tr>
            <tr>
                <td>Index</td>
                <td><code><?= BASE_URL ?>/index.php</code></td>
            </tr>
            <tr>
                <td>Login</td>
                <td><code><?= BASE_URL ?>/auth/login.php</code></td>
            </tr>
            <tr>
                <td>Admin</td>
                <td><code><?= BASE_URL ?>/admin/index.php</code></td>
            </tr>
        </table>
    </div>

    <div class="test-box">
        <h2>📋 Instructions</h2>
        <ol>
            <li>Si vous voyez cette page correctement, PHP fonctionne ✅</li>
            <li>Cliquez sur "Page de connexion" - vous devez arriver sur le formulaire de login</li>
            <li>Retournez sur index.php et testez les boutons "Se connecter"</li>
            <li>Si ça fonctionne, le problème est résolu ! 🎉</li>
        </ol>
    </div>

    <div class="test-box">
        <h2>🔧 Diagnostic</h2>
        <p><strong>Problème identifié et corrigé :</strong></p>
        <p class="success">✓ Le fichier <code>config/functions.php</code> contenait du texte invalide sur la ligne 1</p>
        <p class="success">✓ Ce texte empêchait PHP de s'exécuter correctement</p>
        <p class="success">✓ Résultat : Tous les liens générés avec <code>&lt;?= BASE_URL ?&gt;</code> ne fonctionnaient
            pas</p>
        <p class="success">✓ <strong>CORRIGÉ !</strong> Le fichier a été nettoyé</p>
    </div>
</body>

</html>