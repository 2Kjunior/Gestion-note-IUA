<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/functions.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/check_auth.php';

if ($_SESSION['role'] !== ROLE_ADMIN) {
    redirect('/auth/login.php');
}

$pdo = Database::getInstance()->getConnection();

$code = $_GET['code'] ?? null;

if (!$code) {
    redirect('/admin/classes/liste.php');
}

// Suppression
$stmt = $pdo->prepare("DELETE FROM classe WHERE codecl = :code");
$stmt->execute(['code' => $code]);

redirect('/admin/classes/liste.php');
