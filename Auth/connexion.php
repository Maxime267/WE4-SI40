<?php
session_start();

// Récupérer les erreurs, anciennes valeurs et succès stockés par process_connexion.php
$erreurs   = $_SESSION['erreurs']   ?? [];
$old_email = $_SESSION['old_email'] ?? '';
$succes_inscription = isset($_GET['inscription']) && $_GET['inscription'] === 'success';

// Vider la session après lecture
unset($_SESSION['erreurs'], $_SESSION['old_email']);
?>

<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="../Style/connexion.css">
<section class="contact">

    <h2>Connexion</h2>

    <?php if ($succes_inscription): ?>
        <div style="background:#f0fff4; border:1px solid #68d391; border-radius:8px; padding:12px 16px; margin-bottom:16px; color:#276749; font-size:14px;">
            ✅ Inscription réussie ! Connectez-vous maintenant.
        </div>
    <?php endif; ?>

    <?php if (!empty($erreurs)): ?>
        <div style="background:#fff5f5; border:1px solid #fc8181; border-radius:8px; padding:12px 16px; margin-bottom:16px; color:#c53030; font-size:14px;">
            <ul style="margin:0; padding-left:18px;">
                <?php foreach ($erreurs as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="process_connexion.php">

        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" placeholder="exemple@mail.com" required
                value="<?php echo htmlspecialchars($old_email); ?>">
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
            <button type="submit" class="btn-rdv"
                style="width:100%; font-size:16px; padding:12px; border:none; cursor:pointer;">
                Se connecter
            </button>
        </div>

        <p style="margin-top:10px; font-size:14px; color:#4a5568;">
            Pas encore de compte ? <a href="inscription.php" style="color:#38b2ac; font-weight:600;">S'inscrire</a>
        </p>

    </form>

</section>

<script src="../Script/password.js"></script>
<?php include('../includes/footer.php'); ?>