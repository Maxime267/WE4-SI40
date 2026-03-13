<?php
session_start();

// Protection : rediriger vers connexion si pas connecté
if (empty($_SESSION['utilisateur_id'])) {
    header('Location: Auth/connexion.php');
    exit;
}

require_once('db.php');

// Récupérer les infos complètes de l'utilisateur
$stmt = $pdo->prepare("SELECT nom, prenom, email, date_naissance, admin, created_at FROM utilisateur WHERE id_utilisateur = :id");
$stmt->execute([':id' => $_SESSION['utilisateur_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: Auth/connexion.php');
    exit;
}

// Date d'inscription formatée
$date_inscription = (new DateTime($user['created_at']))->format('d/m/Y');

// Âge calculé
$naissance = new DateTime($user['date_naissance']);
$age = $naissance->diff(new DateTime())->y;

// Message flash de succès
$succes = $_SESSION['succes'] ?? '';
unset($_SESSION['succes']);

include('includes/header.php');
?>

<link rel="stylesheet" href="/Style/dashboard.css">

<main class="dashboard">

    <?php if ($succes): ?>
        <div class="flash-success">
            ✅ <?php echo htmlspecialchars($succes); ?>
        </div>
    <?php endif; ?>

    <!-- Bannière de bienvenue -->
    <div class="dashboard-hero">
        <div class="dashboard-avatar">
            <?php echo strtoupper(mb_substr($user['prenom'], 0, 1) . mb_substr($user['nom'], 0, 1)); ?>
        </div>
        <div class="dashboard-hero-text">
            <h2>Bonjour, <?php echo htmlspecialchars($user['prenom']); ?> 👋</h2>
            <p>Bienvenue sur votre espace personnel de coaching.</p>
        </div>
        <?php if ($user['admin']): ?>
            <span class="badge-admin">⭐ Administrateur</span>
        <?php endif; ?>
    </div>

    <!-- Cartes d'informations -->
    <div class="dashboard-grid">

        <div class="dash-card">
            <div class="dash-card-icon">👤</div>
            <div class="dash-card-content">
                <span class="dash-card-label">Nom complet</span>
                <span class="dash-card-value"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></span>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-icon">📧</div>
            <div class="dash-card-content">
                <span class="dash-card-label">Adresse e-mail</span>
                <span class="dash-card-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-icon">🎂</div>
            <div class="dash-card-content">
                <span class="dash-card-label">Âge</span>
                <span class="dash-card-value"><?php echo $age; ?> ans</span>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-icon">📅</div>
            <div class="dash-card-content">
                <span class="dash-card-label">Membre depuis</span>
                <span class="dash-card-value"><?php echo $date_inscription; ?></span>
            </div>
        </div>

    </div>

    <!-- Actions rapides -->
    <div class="dashboard-actions">
        <h3>Accès rapides</h3>
        <div class="action-grid">
            <a href="Rdv.php" class="action-card">
                <span class="action-icon">📆</span>
                <span>Prendre un RDV</span>
            </a>
            <a href="Qui_suis_je.php" class="action-card">
                <span class="action-icon">🙋</span>
                <span>Mon coach</span>
            </a>
            <a href="Auth/deconnexion.php" class="action-card action-card--danger">
                <span class="action-icon">🚪</span>
                <span>Se déconnecter</span>
            </a>
        </div>
    </div>

</main>

<?php include('includes/footer.php'); ?>
