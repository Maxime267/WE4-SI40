<?php
session_start();

require_once('../db.php'); // fournit déjà $pdo connecté à coaching_db

// Accepter uniquement les requêtes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inscription.php');
    exit;
}

// --- 1. Récupération et nettoyage des données ---
$email                = isset($_POST['email'])                ? trim($_POST['email'])                : '';
$nom                  = isset($_POST['nom'])                  ? trim($_POST['nom'])                  : '';
$prenom               = isset($_POST['prenom'])               ? trim($_POST['prenom'])               : '';
$date_naissance       = isset($_POST['date_naissance'])       ? trim($_POST['date_naissance'])       : '';
$mot_de_passe         = isset($_POST['password'])             ? $_POST['password']                   : '';
$confirm_mot_de_passe = isset($_POST['confirm_mot_de_passe']) ? $_POST['confirm_mot_de_passe']       : '';

// --- 2. Validation ---
$erreurs = [];

if (empty($email)) {
    $erreurs[] = "L'adresse email est obligatoire.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erreurs[] = "L'adresse email n'est pas valide.";
}

if (empty($nom)) {
    $erreurs[] = "Le nom est obligatoire.";
}

if (empty($prenom)) {
    $erreurs[] = "Le prénom est obligatoire.";
}

if (empty($date_naissance)) {
    $erreurs[] = "La date de naissance est obligatoire.";
}

if (empty($mot_de_passe)) {
    $erreurs[] = "Le mot de passe est obligatoire.";
} elseif (strlen($mot_de_passe) < 8) {
    $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères.";
}

if ($mot_de_passe !== $confirm_mot_de_passe) {
    $erreurs[] = "Les mots de passe ne correspondent pas.";
}

// --- 3. Erreurs → retour au formulaire avec les anciennes valeurs ---
if (!empty($erreurs)) {
    $_SESSION['erreurs']       = $erreurs;
    $_SESSION['old_email']     = $email;
    $_SESSION['old_nom']       = $nom;
    $_SESSION['old_prenom']    = $prenom;
    $_SESSION['old_naissance'] = $date_naissance;
    header('Location: inscription.php');
    exit;
}

// --- 4. Hashage du mot de passe ---
$hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

// --- 5. Vérifier que l'email n'est pas déjà utilisé ---
$stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = :email");
$stmt->execute([':email' => $email]);

if ($stmt->fetch()) {
    $_SESSION['erreurs']   = ["Cette adresse email est déjà utilisée."];
    $_SESSION['old_email'] = $email;
    header('Location: inscription.php');
    exit;
}

// --- 6. Insertion en base de données ---
$insert = $pdo->prepare("
    INSERT INTO utilisateur (email, nom, prenom, mot_de_passe, date_naissance)
    VALUES (:email, :nom, :prenom, :mot_de_passe, :date_naissance)
");

$insert->execute([
    ':email'          => $email,
    ':nom'            => $nom,
    ':prenom'         => $prenom,
    ':mot_de_passe'   => $hash,
    ':date_naissance' => $date_naissance,
]);

// --- 7. Redirection vers la page de connexion ---
header('Location: connexion.php?inscription=success');
exit;
?>