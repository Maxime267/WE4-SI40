<?php
session_start();

// Récupérer les erreurs et anciennes valeurs stockées par process_inscription.php
$erreurs       = $_SESSION['erreurs']       ?? [];
$old_email     = $_SESSION['old_email']     ?? '';
$old_nom       = $_SESSION['old_nom']       ?? '';
$old_prenom    = $_SESSION['old_prenom']    ?? '';
$old_naissance = $_SESSION['old_naissance'] ?? '';

// Vider la session après lecture
unset($_SESSION['erreurs'], $_SESSION['old_email'], $_SESSION['old_nom'],
      $_SESSION['old_prenom'], $_SESSION['old_naissance']);

include('../includes/header.php');
?>

<link rel="stylesheet" href="../Style/connexion.css">
<section class="contact">

    <h2>Inscription</h2>

    <?php if (!empty($erreurs)): ?>
        <div style="background:#fff5f5; border:1px solid #fc8181; border-radius:8px; padding:12px 16px; margin-bottom:16px; color:#c53030; font-size:14px;">
            <ul style="margin:0; padding-left:18px;">
                <?php foreach ($erreurs as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="process_inscription.php">

        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" placeholder="exemple@mail.com" required
                value="<?php echo htmlspecialchars($old_email); ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" placeholder="Dupont" required
                    value="<?php echo htmlspecialchars($old_nom); ?>">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" placeholder="Jean" required
                    value="<?php echo htmlspecialchars($old_prenom); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="birthdate">Date de naissance</label>
            <input type="date" id="birthdate" name="date_naissance" required
                value="<?php echo htmlspecialchars($old_naissance); ?>"
                min="1900-01-01" max="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="••••••••" required>
                <button type="button" class="btn-eye" id="toggle-password" aria-label="Afficher le mot de passe">
                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="password-confirm">Confirmation du mot de passe</label>
            <div class="password-wrapper">
                <input type="password" id="password-confirm" name="confirm_mot_de_passe"
                    placeholder="Saisissez le même mot de passe" required>
                <button type="button" class="btn-eye" id="toggle-password-confirm"
                    aria-label="Afficher la confirmation">
                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>
            <span id="password-check" style="display:block; margin-top:6px; font-size:13px; font-weight:500;"></span>
        </div>

        <div class="form-group">
            <button type="submit" class="btn-rdv"
                style="width:100%; font-size:16px; padding:12px; border:none; cursor:pointer;">
                S'inscrire
            </button>
        </div>

        <p style="margin-top:10px; font-size:14px; color:#4a5568;">
            Déjà inscrit ? <a href="connexion.php" style="color:#38b2ac; font-weight:600;">Se connecter</a>
        </p>

    </form>

</section>

<script src="../Script/password.js"></script>
<?php include('../includes/footer.php'); ?>