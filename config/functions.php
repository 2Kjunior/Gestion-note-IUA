<?php

/**
 * Vérifie si l'utilisateur est connecté
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Redirection sécurisée
 */
function redirect($path)
{
    // Encoder l'URL pour gérer les espaces dans les noms de dossiers
    $url = BASE_URL . $path;
    // Encoder les espaces et autres caractères spéciaux dans le chemin
    $url = str_replace(' ', '%20', $url);
    header("Location: " . $url);
    exit;
}

/**
 * Protection XSS à l'affichage
 */
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
