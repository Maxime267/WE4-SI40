<?php
session_start();
$base = '';

// ✅ Connexion à la base de données
require_once 'db.php';

// 🔒 Vérifier que l'utilisateur est connecté
if (empty($_SESSION['utilisateur_id'])) {
    // Sauvegarder l'URL de destination pour rediriger après login
    $_SESSION['redirect_after_login'] = '/Rdv.php';
    header('Location: ' . $base . 'Auth/connexion.php');
    exit;
}

// ✅ Récupérer les informations de l'utilisateur connecté
$userId = $_SESSION['utilisateur_id'];
$stmt = $pdo->prepare("SELECT nom, prenom, email FROM utilisateur WHERE id_utilisateur = ?");
$stmt->execute([$userId]);
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userInfo) {
    // Session invalide, déconnecter
    session_destroy();
    header('Location: ' . $base . 'Auth/connexion.php');
    exit;
}

$userName  = $userInfo['prenom'] . ' ' . $userInfo['nom'];
$userEmail = $userInfo['email'];

// ✅ Générer un token CSRF pour la sécurité
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$messageType = 'error';
$currentDate = date('Y-m-d');

// ✅ Traiter le formulaire de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier le token CSRF
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = '❌ Erreur de sécurité : token invalide.';
    } else {
        // Les infos nom/email viennent du compte connecté (non modifiables)
        $name        = $userName;
        $email       = $userEmail;
        $phone       = trim($_POST['phone'] ?? '');
        $date        = $_POST['date'] ?? '';
        $time        = $_POST['time'] ?? '';
        $userMessage = trim($_POST['message'] ?? '');

        // ✅ Validation
        if (empty($date) || empty($time)) {
            $message = '❌ Veuillez choisir une date et une heure.';
        } else {
            try {
                // ✅ Vérifier que le créneau n'est pas déjà réservé
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE date = ? AND time = ?");
                $stmt->execute([$date, $time]);

                if ($stmt->fetchColumn() > 0) {
                    $message = '❌ Ce créneau est déjà réservé !';
                } else {
                    // ✅ Insérer le rendez-vous avec le user_id
                    $stmt = $pdo->prepare(
                        "INSERT INTO appointments (user_id, name, email, phone, date, time, message, created_at)
                         VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
                    );

                    if ($stmt->execute([$userId, $name, $email, $phone, $date, $time, $userMessage])) {
                        $message = '✅ Rendez-vous réservé avec succès ! Un email de confirmation sera envoyé.';
                        $messageType = 'success';

                        // Nettoyer les champs de saisie
                        $date = $time = $phone = $userMessage = '';

                        // Régénérer le token CSRF
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    }
                }
            } catch (PDOException $e) {
                $message = '❌ Erreur lors de la réservation.';
                error_log("DB Error: " . $e->getMessage());
            }
        }
    }
}

// ✅ Récupérer les créneaux disponibles (30 jours à l'avance)
$availableSlots = [];
for ($i = 0; $i < 30; $i++) {
    $date = date('Y-m-d', strtotime("+$i days", strtotime($currentDate)));
    $dayOfWeek = date('w', strtotime($date)); // 0=dimanche, 6=samedi

    // ✅ Ne pas proposer les dimanches (0)
    if ($dayOfWeek != 0) {
        $availableSlots[$date] = [];

        // Créneaux de 9h à 17h
        for ($hour = 9; $hour <= 17; $hour++) {
            $time = sprintf('%02d:00:00', $hour);

            // Vérifier si le créneau est libre
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE date = ? AND time = ?");
            $stmt->execute([$date, $time]);

            if ($stmt->fetchColumn() == 0) {
                $availableSlots[$date][] = $time;
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<main>
    <section class="hero">
        <h2>Prendre un Rendez-vous</h2>
        <p>Réservez votre séance de coaching en ligne. Choisissez une date et une heure disponibles.</p>
    </section>

    <section class="contact">
        <h2>Formulaire de Réservation</h2>

        <?php if ($message): ?>
            <p style="color: <?php echo $messageType === 'success' ? 'green' : 'red'; ?>;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <!-- ✅ Info utilisateur connecté (lecture seule) -->
        <div style="background: #f0fff4; border: 1px solid #68d391; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; color: #276749; font-size: 14px;">
            👤 Connecté en tant que : <strong><?php echo htmlspecialchars($userName); ?></strong>
            (<?php echo htmlspecialchars($userEmail); ?>)
        </div>

        <form method="POST" action="">
            <!-- ✅ Token CSRF caché -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <!-- ✅ Champ téléphone optionnel -->
            <div class="form-group">
                <label for="phone">📞 Téléphone (optionnel) :</label>
                <input type="tel" id="phone" name="phone" maxlength="20"
                       value="<?php echo htmlspecialchars($phone ?? ''); ?>">
            </div>

            <!-- ✅ Calendrier visuel -->
            <div class="form-group calendar-section">
                <label for="date">📅 Choisissez une date :</label>
                <input type="date" id="date" name="date"
                       min="<?php echo htmlspecialchars($currentDate); ?>"
                       value="<?php echo htmlspecialchars($date ?? ''); ?>"
                       required
                       onchange="updateTimes()">
            </div>

            <!-- ✅ Sélection de l'heure -->
            <div class="form-group">
                <label for="time">⏰ Sélectionnez l'heure :</label>
                <select id="time" name="time" required>
                    <option value="">-- Choisissez une heure --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">💬 Message (optionnel) :</label>
                <textarea id="message" name="message" maxlength="500" rows="4"><?php echo htmlspecialchars($userMessage ?? ''); ?></textarea>
            </div>

            <button type="submit" class="cta">✅ Réserver mon créneau</button>
        </form>

        <h3>Créneaux Disponibles (Calendrier Simplifié)</h3>
        <p>Sélectionnez une date ci-dessus pour voir les heures libres.</p>
    </section>
</main>

<script>
    // JS pour mettre à jour les heures disponibles en fonction de la date sélectionnée
    function updateTimes() {
        const dateInput = document.getElementById('date').value;
        const timeSelect = document.getElementById('time');
        timeSelect.innerHTML = '<option value="">Sélectionnez une heure</option>';

        if (dateInput && window.availableSlots && window.availableSlots[dateInput]) {
            window.availableSlots[dateInput].forEach(time => {
                const option = document.createElement('option');
                option.value = time;
                option.textContent = time.slice(0, 5);  // HH:MM
                timeSelect.appendChild(option);
            });
        }
    }

    // Passer les créneaux disponibles à JS
    window.availableSlots = <?php echo json_encode($availableSlots); ?>;
</script>

<?php include 'includes/footer.php'; ?>
