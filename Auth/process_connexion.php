<?php

session_start();

require_once('../db.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: connexion.php');
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$mot_de_passe = isset($_POST['password']) ? $_POST['password'] : '';

$erreurs = [];

if (empty($email)) {
    $erreurs[] = "L'adresse email est obligatoire.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erreurs[] = "L'adresse email n'est pas valide.";
}

if (empty($mot_de_passe)) {
    $erreurs[] = "Le mot de passe est obligatoire.";
}

if (!empty($erreurs)) {
    $_SESSION['erreurs'] = $erreurs;
    $_SESSION['old_email'] = $email;
    header('Location: connexion.php');
    exit;
}


$stmt = $pdo->prepare("SELECT id_utilisateur, prenom, mot_de_passe FROM utilisateur WHERE email = :email");
$stmt->execute([':email' => $email]);
$utilisateur = $stmt->fetch();

if (!$utilisateur) {
    $_SESSION['erreurs'] = ["Email ou mot de passe incorrect."];
    $_SESSION['old_email'] = $email;
    header('Location: connexion.php');
    exit;
}

if (!password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
    $_SESSION['erreurs'] = ["Email ou mot de passe incorrect."];
    $_SESSION['old_email'] = $email;
    header('Location: connexion.php');
    exit;
}

$_SESSION['utilisateur_id'] = $utilisateur['id_utilisateur'];
$_SESSION['email']           = $email;
$_SESSION['succes']          = "Bienvenue, " . htmlspecialchars($utilisateur['prenom']) . " ! Vous êtes connecté(e).";

header('Location: ../dashboard.php');
exit;
?>