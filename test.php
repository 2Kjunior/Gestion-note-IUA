<?php
// Test simple pour vérifier que PHP fonctionne
echo "PHP fonctionne ! ✅<br>";
echo "BASE_URL = " . (defined('BASE_URL') ? BASE_URL : 'NON DÉFINI') . "<br>";

// Inclure les constantes
require_once __DIR__ . '/config/constants.php';
echo "Après inclusion : BASE_URL = " . BASE_URL . "<br>";

// Tester un lien
$loginUrl = BASE_URL . '/auth/login.php';
echo "URL de connexion : <a href='$loginUrl'>$loginUrl</a><br>";
?>
<!DOCTYPE html>
<html>

<head>
    <title>Test PHP</title>
</head>

<body>
    <h1>Test de Navigation</h1>
    <p><a href="<?= BASE_URL ?>/auth/login.php">Lien vers Login (avec PHP)</a></p>
    <p><a href="/gestion-notes-iua - Copy/auth/login.php">Lien vers Login (statique)</a></p>
</body>

</html>