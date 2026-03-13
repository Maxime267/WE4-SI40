<?php
// session_start() est appelé par chaque page appelante avant cet include
$connecte = !empty($_SESSION['utilisateur_id']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Site de coaching personnel - Réalisez vos rêves et passez à l'action.">
    <title>Rendez vos Rêves Réalité - Coaching</title>
    <link rel="stylesheet" href="/Style/style.css">
</head>

<body>

    <header class="site-header">

        <div class="logo">
            <h1>Coaching : Rendez-vos rêve réalité</h1>
        </div>

        <nav class="menu">

            <a href="/index.php">Accueil</a>
            <a href="/Qui_suis_je.php">Qui suis-je</a>

            <?php if ($connecte): ?>
                <a href="/dashboard.php">Mon profil</a>
            <?php else: ?>
                <a href="/Auth/connexion.php">Connexion</a>
            <?php endif; ?>

            <a href="/Rdv.php" class="btn-rdv">Prendre RDV</a>

        </nav>

    </header>