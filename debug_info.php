<?php
// Afficher toutes les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Informations de Debug</h1>";

echo "<h2>1. PHP Version</h2>";
echo "<p>Version PHP : " . phpversion() . "</p>";

echo "<h2>2. Extensions PHP chargées</h2>";
echo "<pre>";
print_r(get_loaded_extensions());
echo "</pre>";

echo "<h2>3. Configuration PDO</h2>";
if (extension_loaded('pdo')) {
    echo "<p style='color:green;'>✅ PDO est activé</p>";
    echo "<p>Drivers disponibles : " . implode(', ', PDO::getAvailableDrivers()) . "</p>";
} else {
    echo "<p style='color:red;'>❌ PDO n'est PAS activé</p>";
}

echo "<h2>4. Session PHP</h2>";
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>5. Variables serveur importantes</h2>";
echo "<p>DOCUMENT_ROOT : " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>REQUEST_URI : " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>SCRIPT_FILENAME : " . $_SERVER['SCRIPT_FILENAME'] . "</p>";

echo "<h2>6. Chemins PHP</h2>";
echo "<p>Include path : " . get_include_path() . "</p>";
echo "<p>__FILE__ : " . __FILE__ . "</p>";
echo "<p>__DIR__ : " . __DIR__ . "</p>";

echo "<h2 style='color:green;'>✅ PHP fonctionne correctement</h2>";
