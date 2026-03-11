<?php
// Connexion à la base de données (ajustez les credentials selon votre environnement)
$host = 'localhost';          // Hôte de la DB (généralement 'localhost' pour local)
$dbname = 'coaching_db';      // Nom de la base de données (créez-la via phpMyAdmin ou SQL si elle n'existe pas)
$username = 'root';           // Utilisateur MySQL (par défaut 'root' sur XAMPP/WAMP)
$password = '';               // Mot de passe (vide par défaut sur XAMPP)

try {
    // Création de l'objet PDO pour la connexion
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Mode d'erreur : lance des exceptions en cas de problème
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message et arrêter le script
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
