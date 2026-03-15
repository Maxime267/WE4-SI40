<?php
session_start();
$base = '';
include 'includes/header.php';
?>

<!-- CSS spécifique à cette page -->
<link rel="stylesheet" href="<?php echo $base; ?>Style/qui_suis_je.css">

<main>

    <!-- HERO -->
    <section class="hero">
        <h2>Qui suis-je ?</h2>
        <p>Coach certifiée en développement personnel, je vous accompagne pour transformer vos rêves en réalité.</p>
    </section>


    <!-- PRÉSENTATION -->
    <section class="about-intro">
        <div class="about-container">

            <div class="about-avatar">
                <span class="avatar-initials"></span>
            </div>

            <div class="about-text">
                <h2>Sophie Cardin</h2>
                <p class="about-tagline">Coach certifiée · 10 ans d'expérience · + de 300 clients accompagnés</p>
                <p>
                    Ancienne cadre en entreprise, j'ai moi-même traversé une période de doutes, de remises en question
                    et de blocages. C'est en sortant de cette période grâce au coaching que j'ai décidé de me former
                    et de dédier ma vie à aider les autres à faire de même.
                </p>
                <p>
                    Aujourd'hui, j'accompagne des particuliers et des professionnels à clarifier leurs objectifs,
                    surmonter leurs freins et passer à l'action concrètement.
                </p>
                <a href="<?php echo $base; ?>Rdv.php" class="cta-link">Prendre rendez-vous →</a>
            </div>

        </div>
    </section>


    <!-- VALEURS -->
    <section class="valeurs">
        <h2>Mes valeurs</h2>
        <div class="cards">

            <div class="card valeur-card">
                <div class="valeur-icon">🎯</div>
                <h3>Engagement</h3>
                <p>Je m'investis pleinement dans chaque accompagnement pour vous aider à atteindre vos objectifs.</p>
            </div>

            <div class="card valeur-card">
                <div class="valeur-icon">🤝</div>
                <h3>Bienveillance</h3>
                <p>Un espace de confiance et sans jugement où vous pouvez vous exprimer librement.</p>
            </div>

            <div class="card valeur-card">
                <div class="valeur-icon">⚡</div>
                <h3>Action</h3>
                <p>Le coaching, c'est du concret. Chaque séance débouche sur des actions réelles et mesurables.</p>
            </div>

        </div>
    </section>


    <!-- PARCOURS -->
    <section class="parcours">
        <h2>Mon parcours</h2>
        <div class="timeline">

            <div class="timeline-item">
                <div class="timeline-year">2014</div>
                <div class="timeline-content">
                    <h3>Formation ICF</h3>
                    <p>Certification internationale de coaching (International Coaching Federation).</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-year">2015</div>
                <div class="timeline-content">
                    <h3>Lancement de mon activité</h3>
                    <p>Premiers clients, premières transformations. Le début d'une belle aventure.</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-year">2018</div>
                <div class="timeline-content">
                    <h3>Spécialisation en coaching professionnel</h3>
                    <p>Formation complémentaire orientée reconversion, leadership et gestion du stress.</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-year">2024</div>
                <div class="timeline-content">
                    <h3>+ de 300 clients accompagnés</h3>
                    <p>Des dizaines de témoignages positifs et une communauté grandissante.</p>
                </div>
            </div>

        </div>
    </section>


    <!-- CTA FINAL -->
    <section class="cta-section">
        <h2>Prêt(e) à passer à l'action ?</h2>
        <p>Réservez votre séance préliminaire gratuite — sans engagement, sans pression.</p>
        <a href="<?php echo $base; ?>Rdv.php" class="cta">📅 Réserver ma séance gratuite</a>
    </section>

</main>

<?php include 'includes/footer.php'; ?>