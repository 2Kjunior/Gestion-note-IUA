<?php
/*********************************************************
 * SUPPRIMER UNE PÉRIODE
 *********************************************************/

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/database.php';
require_once '../../config/functions.php';
require_once '../../auth/check_auth.php';

if ($_SESSION['role'] !== 'admin') {
    redirect('/index.php');
}

$pdo = Database::getInstance()->getConnection();

if (!isset($_GET['codePeriode'])) {
    redirect('/admin/periodes/liste.php');
}

$codePeriode = $_GET['codePeriode'];

// Sécurité : période utilisée ?
$check = $pdo->prepare(
    "SELECT COUNT(*) FROM evaluer WHERE codePeriode = ?"
);
$check->execute([$codePeriode]);

if ($check->fetchColumn() > 0) {
    redirect('/admin/periodes/liste.php?error=utilisee');
}

// Suppression
$pdo->prepare(
    "DELETE FROM periode WHERE codePeriode = ?"
)->execute([$codePeriode]);

redirect('/admin/periodes/liste.php');
