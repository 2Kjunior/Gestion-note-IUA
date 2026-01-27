<?php
/**
 * SCRIPT DE CORRECTION FINALE
 * Utilise des chemins absoluts depuis la racine web
 */

function fixFileAbsolute($filePath, $replacements)
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

echo "🔧 CORRECTION FINALE - CHEMINS ABSOLUTS\n";
echo "==========================================\n\n";

$baseDir = __DIR__;
$webRoot = '/gestion-notes-iua - Copy';

// NAVBAR - Chemins absolus depuis la racine web
$navbarReplacements = [
    '../index.php' => $webRoot . '/index.php',
    '../auth/login.php' => $webRoot . '/auth/login.php',
    '../auth/logout.php' => $webRoot . '/auth/logout.php',
    '../admin/index.php' => $webRoot . '/admin/index.php',
    '../admin/classes/liste.php' => $webRoot . '/admin/classes/liste.php',
    '../admin/matieres/liste.php' => $webRoot . '/admin/matieres/liste.php',
    '../admin/periodes/liste.php' => $webRoot . '/admin/periodes/liste.php',
    '../admin/enseignants/liste.php' => $webRoot . '/admin/enseignants/liste.php',
    '../admin/etudiants/liste.php' => $webRoot . '/admin/etudiants/liste.php',
    '../admin/inscriptions/liste.php' => $webRoot . '/admin/inscriptions/liste.php',
    '../admin/parametrage/enseignement.php' => $webRoot . '/admin/parametrage/enseignement.php',
    '../admin/parametrage/matiere_classe.php' => $webRoot . '/admin/parametrage/matiere_classe.php',
    '../enseignant/index.php' => $webRoot . '/enseignant/index.php',
    '../enseignant/saisir_notes.php' => $webRoot . '/enseignant/saisir_notes.php',
    '../enseignant/mes_notes.php' => $webRoot . '/enseignant/mes_notes.php',
    '../etudiant/index.php' => $webRoot . '/etudiant/index.php',
    '../etudiant/mes_notes.php' => $webRoot . '/etudiant/mes_notes.php',
];

// INDEX.PHP - Chemins absolus
$indexReplacements = [
    'auth/login.php' => $webRoot . '/auth/login.php',
];

// FOOTER - Chemins absolus
$footerReplacements = [
    '../index.php' => $webRoot . '/index.php',
    '../auth/login.php' => $webRoot . '/auth/login.php',
    '../assets/js/animations.js' => $webRoot . '/assets/js/animations.js',
];

// HEADER - Chemins absolus
$headerReplacements = [
    '../assets/css/style.css' => $webRoot . '/assets/css/style.css',
    '../assets/images/favicon.png' => $webRoot . '/assets/images/favicon.png',
];

echo "📝 Correction de navbar.php...\n";
fixFileAbsolute($baseDir . '/includes/navbar.php', $navbarReplacements);

echo "\n📝 Correction de index.php...\n";
fixFileAbsolute($baseDir . '/index.php', $indexReplacements);

echo "\n📝 Correction de footer.php...\n";
fixFileAbsolute($baseDir . '/includes/footer.php', $footerReplacements);

echo "\n📝 Correction de header.php...\n";
fixFileAbsolute($baseDir . '/includes/header.php', $headerReplacements);

echo "\n✅ CORRECTION FINALE TERMINÉE !\n";
echo "==========================================\n";
echo "🧪 Testez : http://localhost/gestion-notes-iua%20-%20Copy/index.php\n";
echo "📌 Tous les liens utilisent maintenant : $webRoot/...\n";
?>