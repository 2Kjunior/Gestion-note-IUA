<?php
/*********************************************************
 * SYNC ÉTUDIANTS → UTILISATEURS
 * Crée les comptes manquants
 *********************************************************/

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../auth/check_auth.php';

if ($_SESSION['role'] !== 'admin') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();

// Étudiants sans compte utilisateur
$stmt = $pdo->query("
    SELECT e.Matriculation, e.Email
    FROM etudiant e
    LEFT JOIN utilisateurs u ON u.id_reference = e.Matriculation
    WHERE u.id_user IS NULL
");

$etudiants = $stmt->fetchAll();
$nb = 0;

foreach ($etudiants as $e) {

    $login = $e['Email'];
    $password_hash = password_hash($e['Matriculation'], PASSWORD_DEFAULT);
    $role = 'etudiant';
    $id_reference = $e['Matriculation'];

    $insert = $pdo->prepare(
        "INSERT INTO utilisateurs (login, password_hash, role, id_reference)
         VALUES (?, ?, ?, ?)"
    );

    $insert->execute([
        $login,
        $password_hash,
        $role,
        $id_reference
    ]);

    $nb++;
}

echo "✅ $nb comptes étudiants créés avec succès.";
