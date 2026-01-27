<?php
/**
 * SCRIPT DE CORRECTION AUTOMATIQUE
 * Remplace tous les BASE_URL par des chemins relatifs corrects
 */

// Fonction pour corriger un fichier
function fixFile($filePath, $replacements)
{
    if (!file_exists($filePath)) {
        echo "❌ Fichier introuvable : $filePath\n";
        return false;
    }

    $content = file_get_contents($filePath);
    $originalContent = $content;

    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }

    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "✅ Corrigé : $filePath\n";
        return true;
    }

    echo "ℹ️  Aucun changement : $filePath\n";
    return false;
}

echo "🔧 CORRECTION AUTOMATIQUE DES LIENS DE NAVIGATION\n";
echo "================================================\n\n";

// Définir les remplacements selon le type de fichier
$baseDir = __DIR__;

// 1. NAVBAR.PHP - Chemins depuis includes/
$navbarReplacements = [
    '<?= BASE_URL ?>/index.php' => '../index.php',
    '<?= BASE_URL ?>/auth/login.php' => '../auth/login.php',
    '<?= BASE_URL ?>/auth/logout.php' => '../auth/logout.php',
    '<?= BASE_URL ?>/admin/index.php' => '../admin/index.php',
    '<?= BASE_URL ?>/admin/classes/liste.php' => '../admin/classes/liste.php',
    '<?= BASE_URL ?>/admin/matieres/liste.php' => '../admin/matieres/liste.php',
    '<?= BASE_URL ?>/admin/periodes/liste.php' => '../admin/periodes/liste.php',
    '<?= BASE_URL ?>/admin/enseignants/liste.php' => '../admin/enseignants/liste.php',
    '<?= BASE_URL ?>/admin/etudiants/liste.php' => '../admin/etudiants/liste.php',
    '<?= BASE_URL ?>/admin/inscriptions/liste.php' => '../admin/inscriptions/liste.php',
    '<?= BASE_URL ?>/admin/parametrage/enseignement.php' => '../admin/parametrage/enseignement.php',
    '<?= BASE_URL ?>/admin/parametrage/matiere_classe.php' => '../admin/parametrage/matiere_classe.php',
    '<?= BASE_URL ?>/enseignant/index.php' => '../enseignant/index.php',
    '<?= BASE_URL ?>/enseignant/saisir_notes.php' => '../enseignant/saisir_notes.php',
    '<?= BASE_URL ?>/enseignant/mes_notes.php' => '../enseignant/mes_notes.php',
    '<?= BASE_URL ?>/etudiant/index.php' => '../etudiant/index.php',
    '<?= BASE_URL ?>/etudiant/mes_notes.php' => '../etudiant/mes_notes.php',
];

// 2. FOOTER.PHP - Chemins depuis includes/
$footerReplacements = [
    '<?= BASE_URL ?>/index.php' => '../index.php',
    '<?= BASE_URL ?>/auth/login.php' => '../auth/login.php',
    '<?= BASE_URL ?>/assets/js/animations.js' => '../assets/js/animations.js',
];

// 3. HEADER.PHP - Chemins depuis includes/
$headerReplacements = [
    '<?= BASE_URL ?>/assets/css/style.css' => '../assets/css/style.css',
    '<?= BASE_URL ?>/assets/images/favicon.png' => '../assets/images/favicon.png',
];

echo "📝 Correction de navbar.php...\n";
fixFile($baseDir . '/includes/navbar.php', $navbarReplacements);

echo "\n📝 Correction de footer.php...\n";
fixFile($baseDir . '/includes/footer.php', $footerReplacements);

echo "\n📝 Correction de header.php...\n";
fixFile($baseDir . '/includes/header.php', $headerReplacements);

echo "\n✅ CORRECTION TERMINÉE !\n";
echo "================================================\n";
echo "🧪 Testez maintenant : http://localhost/gestion-notes-iua%20-%20Copy/index.php\n";
?>