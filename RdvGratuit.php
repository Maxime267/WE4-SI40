<?php
session_start();
require_once 'db.php';

// ✅ Générer un token CSRF
if (empty($_SESSION['csrf_token_gratuit'])) {
    $_SESSION['csrf_token_gratuit'] = bin2hex(random_bytes(32));
}

$message     = '';
$messageType = 'error';
$currentDate = date('Y-m-d');

// Conserver les valeurs saisies en cas d'erreur
$formName    = '';
$formEmail   = '';
$formPhone   = '';
$formMsg     = '';

// ✅ Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token_gratuit']) {
        $message = '❌ Erreur de sécurité : token invalide.';
    } else {
        $formName  = trim($_POST['name']    ?? '');
        $formEmail = trim($_POST['email']   ?? '');
        $formPhone = trim($_POST['phone']   ?? '');
        $date      = $_POST['date']         ?? '';
        $time      = $_POST['time']         ?? '';
        $formMsg   = trim($_POST['message'] ?? '');

        if (empty($formName) || empty($formEmail) || empty($date) || empty($time)) {
            $message = '❌ Veuillez remplir tous les champs obligatoires.';
        } elseif (!filter_var($formEmail, FILTER_VALIDATE_EMAIL)) {
            $message = '❌ Adresse email invalide.';
        } else {
            try {
                // Vérifier la disponibilité du créneau
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE date = ? AND time = ?");
                $stmt->execute([$date, $time]);

                if ($stmt->fetchColumn() > 0) {
                    $message = '❌ Ce créneau est déjà réservé. Veuillez en choisir un autre.';
                } else {
                    // Insérer avec type = 'gratuit' et user_id = NULL
                    $stmt = $pdo->prepare(
                        "INSERT INTO appointments (user_id, name, email, phone, date, time, message, type, created_at)
                         VALUES (NULL, ?, ?, ?, ?, ?, ?, 'gratuit', NOW())"
                    );
                    if ($stmt->execute([$formName, $formEmail, $formPhone, $date, $time, $formMsg])) {
                        $message     = '✅ Votre séance découverte a bien été réservée ! Vous recevrez un email de confirmation.';
                        $messageType = 'success';
                        $formName = $formEmail = $formPhone = $formMsg = '';
                        $_SESSION['csrf_token_gratuit'] = bin2hex(random_bytes(32));
                    }
                }
            } catch (PDOException $e) {
                $message = '❌ Erreur lors de la réservation.';
                error_log("DB Error RdvGratuit: " . $e->getMessage());
            }
        }
    }
}

// ✅ Créneaux disponibles (30 jours)
$availableSlots = [];
for ($i = 0; $i < 30; $i++) {
    $d = date('Y-m-d', strtotime("+$i days", strtotime($currentDate)));
    $dow = date('w', strtotime($d));
    if ($dow != 0) { // Pas le dimanche
        $availableSlots[$d] = [];
        for ($hour = 9; $hour <= 17; $hour++) {
            $t = sprintf('%02d:00:00', $hour);
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE date = ? AND time = ?");
            $stmt->execute([$d, $t]);
            if ($stmt->fetchColumn() == 0) {
                $availableSlots[$d][] = $t;
            }
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="/Style/rdv_gratuit.css">

<main>

    <!-- ✅ HERO SECTION -->
    <section class="hero-gratuit">
        <div class="hero-gratuit__badge">🎁 100% Gratuit — Sans engagement</div>
        <h2>Votre Séance Découverte Offerte</h2>
        <p>30 minutes pour faire connaissance, définir vos besoins et explorer comment le coaching peut transformer votre vie.</p>
        <a href="#formulaire-gratuit" class="cta-gratuit-btn">Réserver ma séance gratuite ↓</a>
    </section>

    <!-- ✅ AVANTAGES -->
    <section class="avantages-gratuit">
        <div class="avantages-grid">
            <div class="avantage-item">
                <span class="avantage-icon">🕐</span>
                <h3>30 minutes offertes</h3>
                <p>Un premier échange sans pression pour découvrir le coaching.</p>
            </div>
            <div class="avantage-item">
                <span class="avantage-icon">🔒</span>
                <h3>Sans engagement</h3>
                <p>Aucune obligation de suite. Vous décidez librement.</p>
            </div>
            <div class="avantage-item">
                <span class="avantage-icon">💻</span>
                <h3>En visioconférence</h3>
                <p>Depuis chez vous, en toute simplicité et confidentialité.</p>
            </div>
            <div class="avantage-item">
                <span class="avantage-icon">🎯</span>
                <h3>Personnalisé</h3>
                <p>Chaque séance est adaptée à votre situation et vos objectifs.</p>
            </div>
        </div>
    </section>

    <!-- ✅ FORMULAIRE -->
    <section class="contact" id="formulaire-gratuit">
        <h2>Réserver ma séance découverte gratuite</h2>
        <p style="color:#4a5568; margin-bottom: 10px;">Aucun compte nécessaire — remplissez simplement le formulaire ci-dessous.</p>

        <?php if ($message): ?>
            <div class="form-message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="#formulaire-gratuit">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token_gratuit']); ?>">

            <div class="form-group">
                <label for="name">👤 Votre prénom et nom <span style="color:red">*</span></label>
                <input type="text" id="name" name="name" maxlength="100" required
                       placeholder="Ex : Marie Dupont"
                       value="<?php echo htmlspecialchars($formName); ?>">
            </div>

            <div class="form-group">
                <label for="email">📧 Votre email <span style="color:red">*</span></label>
                <input type="email" id="email" name="email" maxlength="100" required
                       placeholder="votre@email.com"
                       value="<?php echo htmlspecialchars($formEmail); ?>">
            </div>

            <div class="form-group">
                <label for="phone">📞 Téléphone (optionnel)</label>
                <input type="tel" id="phone" name="phone" maxlength="20"
                       placeholder="06 00 00 00 00"
                       value="<?php echo htmlspecialchars($formPhone); ?>">
            </div>

            <div class="form-group calendar-section">
                <label for="date">📅 Choisissez une date <span style="color:red">*</span></label>
                <input type="date" id="date" name="date"
                       min="<?php echo htmlspecialchars($currentDate); ?>"
                       required
                       onchange="updateTimesGratuit()">
            </div>

            <div class="form-group">
                <label for="time">⏰ Choisissez un créneau <span style="color:red">*</span></label>
                <select id="time" name="time" required>
                    <option value="">-- Sélectionnez d'abord une date --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">💬 En quelques mots, qu'attendez-vous de ce coaching ? (optionnel)</label>
                <textarea id="message" name="message" maxlength="500" rows="3"
                          placeholder="Ex : Je voudrais reprendre confiance en moi et changer de carrière..."><?php echo htmlspecialchars($formMsg); ?></textarea>
            </div>

            <button type="submit" class="cta cta-gratuit">🎁 Réserver ma séance gratuite</button>

            <p style="font-size:13px; color:#718096; margin-top: 15px; text-align:center;">
                Vous avez déjà un compte ?
                <a href="/Auth/connexion.php" style="color:#38b2ac; font-weight:600;">Connectez-vous</a>
                pour accéder à votre espace personnel.
            </p>
        </form>
    </section>

</main>

<script>
    const slotsGratuit = <?php echo json_encode($availableSlots); ?>;

    function updateTimesGratuit() {
        const dateVal  = document.getElementById('date').value;
        const timesSel = document.getElementById('time');
        timesSel.innerHTML = '<option value="">-- Choisissez un créneau --</option>';

        if (dateVal && slotsGratuit[dateVal]) {
            if (slotsGratuit[dateVal].length === 0) {
                const opt = document.createElement('option');
                opt.disabled = true;
                opt.textContent = 'Aucun créneau disponible ce jour';
                timesSel.appendChild(opt);
            } else {
                slotsGratuit[dateVal].forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t;
                    opt.textContent = t.slice(0, 5);
                    timesSel.appendChild(opt);
                });
            }
        }
    }
</script>

<?php include 'includes/footer.php'; ?>
