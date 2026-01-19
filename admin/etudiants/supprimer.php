<?php
/*********************************************************
 * PAGE : Supprimer un étudiant
 * Rôle : Admin uniquement
 *********************************************************/

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../auth/check_auth.php';

// Sécurité : admin uniquement
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    redirect('/auth/login.php');
}

$pdo = Database::getInstance()->getConnection();

// Vérifier la présence du matricule
if (!isset($_GET['matricule']) || empty($_GET['matricule'])) {
    redirect('/admin/etudiants/liste.php');
}

$matricule = $_GET['matricule'];

// Récupérer l’étudiant (pour la photo)
$stmt = $pdo->prepare(
    "SELECT photo FROM etudiant WHERE Matriculation = ?"
);
$stmt->execute([$matricule]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    redirect('/admin/etudiants/liste.php');
}

// Supprimer la photo si elle existe
if (!empty($etudiant['photo'])) {
    $photoPath = $_SERVER['DOCUMENT_ROOT'] . 
        BASE_URL . '/assets/images/etudiants/' . $etudiant['photo'];

    if (file_exists($photoPath)) {
        unlink($photoPath);
    }
}

// Supprimer l’étudiant
$delete = $pdo->prepare(
    "DELETE FROM etudiant WHERE Matriculation = ?"
);
$delete->execute([$matricule]);

// Redirection
redirect('/admin/etudiants/liste.php');
