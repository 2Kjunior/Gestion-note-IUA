<?php
/*********************************************************
 * PAGE : Supprimer une inscription
 * Rôle : Admin uniquement
 *********************************************************/

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../auth/check_auth.php';

// Sécurité : admin uniquement
if ($_SESSION['role'] !== 'admin') {
    redirect('/admin/index.php');
}

$pdo = Database::getInstance()->getConnection();

// Vérifier l’ID de l’inscription
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('/admin/inscriptions/liste.php');
}

$id_inscription = (int) $_GET['id'];

// Vérifier existence
$check = $pdo->prepare(
    "SELECT COUNT(*) FROM inscription WHERE id_inscription = ?"
);
$check->execute([$id_inscription]);

if ($check->fetchColumn() == 0) {
    redirect('/admin/inscriptions/liste.php');
}

// Suppression
$delete = $pdo->prepare(
    "DELETE FROM inscription WHERE id_inscription = ?"
);
$delete->execute([$id_inscription]);

// Redirection
redirect('/admin/inscriptions/liste.php');
