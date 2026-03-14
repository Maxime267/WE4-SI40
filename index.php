<?php
session_start();
$succes = $_SESSION['succes'] ?? '';
unset($_SESSION['succes']);
include("includes/header.php");
?>

<link rel="stylesheet" href="/Style/index.css">

<?php if ($succes): ?>
    <div style="background:#f0fff4; border:1px solid #68d391; border-radius:8px; padding:12px 20px; margin:20px 40px; color:#276749; font-size:14px; font-weight:500;">
        ✅ <?php echo htmlspecialchars($succes); ?>
    </div>
<?php endif; ?>

<?php if (!$connecte): ?>
<!-- ✅ BANNIÈRE SÉANCE GRATUITE — visible uniquement pour les visiteurs non connectés -->
<section class="cta-gratuit-banner">
    <div class="cta-gratuit-banner__inner">
        <div class="cta-gratuit-banner__badge">🎁 Offre de bienvenue</div>
        <h2>Commencez par une séance découverte <span class="highlight-green">100% gratuite</span></h2>
        <p>30 minutes offertes, sans engagement, pour explorer comment le coaching peut transformer votre quotidien.</p>
        <a href="/RdvGratuit.php" class="cta-banner-btn">Réserver ma séance gratuite →</a>
        <p class="cta-banner-note">Aucun compte requis · Annulation libre · En visioconférence</p>
    </div>
</section>
<?php endif; ?>

<section class="services">

    <h2>Mes accompagnements</h2>

    <div class="cards">

        <div class="card">
            <h3>🌱 Coaching de vie</h3>
            <p>
                Clarifiez vos objectifs, reprenez confiance et avancez vers une vie alignée
                avec vos valeurs.
            </p>
        </div>

        <div class="card">
            <h3>💼 Coaching professionnel</h3>
            <p>
                Évolution de carrière, reconversion, gestion du stress ou leadership.
            </p>
        </div>

        <div class="card">
            <h3>🔥 Coaching motivation</h3>
            <p>
                Retrouvez votre énergie et mettez en place des actions concrètes pour avancer.
            </p>
        </div>

    </div>

</section>


<section class="temoignages">

    <h2>Témoignages</h2>

    <blockquote>
        "Grâce à ce coaching j'ai osé changer de métier et lancer mon projet."
        <br><strong>- Marie</strong>
    </blockquote>

    <blockquote>
        "Un accompagnement humain et très concret qui m'a permis de reprendre confiance."
        <br><strong>- Julien</strong>
    </blockquote>

</section>


<section class="contact">

    <h2>Contact</h2>

    <p>Email : contact@coaching-exemple.fr</p>
    <p>Téléphone : 06 00 00 00 00</p>

</section>



<?php include("includes/footer.php"); ?>