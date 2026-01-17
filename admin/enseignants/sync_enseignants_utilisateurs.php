<?php
/*********************************************************
 * SCRIPT : Synchronisation enseignants → utilisateurs
 * À exécuter UNE SEULE FOIS
 *********************************************************/

require_once '../../config/database.php';

$pdo = Database::getInstance()->getConnection();

// Récupérer tous les enseignants
$enseignants = $pdo->query(
    "SELECT MatriculeEns, Email FROM enseignant"
)->fetchAll();

$ajoutes = 0;

foreach ($enseignants as $ens) {

    $login = $ens['Email'];
    $matricule = $ens['MatriculeEns'];

    // Vérifier existence utilisateur
    $check = $pdo->prepare(
        "SELECT COUNT(*) FROM utilisateurs WHERE login = ?"
    );
    $check->execute([$login]);

    if ($check->fetchColumn() == 0) {

        $password_hash = password_hash($matricule, PASSWORD_DEFAULT);

        $insert = $pdo->prepare(
            "INSERT INTO utilisateurs (login, password_hash, role, id_reference)
             VALUES (?, ?, 'enseignant', ?)"
        );

        $insert->execute([
            $login,
            $password_hash,
            $matricule
        ]);

        $ajoutes++;
    }
}

echo "✅ Synchronisation terminée : $ajoutes comptes enseignants créés.";
