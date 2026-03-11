<?php
session_start();

// ✅ Connexion à la base de données
require_once 'db.php';

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
        // Récupérer et nettoyer les données
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $userMessage = trim($_POST['message'] ?? '');

        // ✅ Validation
        if (empty($name) || empty($email) || empty($date) || empty($time)) {
            $message = '❌ Tous les champs obligatoires doivent être remplis.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = '❌ Adresse email invalide.';
        } else {
            try {
                // ✅ Vérifier que le créneau n'est pas déjà réservé
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE date = ? AND time = ?");
                $stmt->execute([$date, $time]);

                if ($stmt->fetchColumn() > 0) {
                    $message = '❌ Ce créneau est déjà réservé !';
                } else {
                    // ✅ Insérer le rendez-vous
                    $stmt = $pdo->prepare(
                            "INSERT INTO appointments (name, email, phone, date, time, message, created_at) 
                         VALUES (?, ?, ?, ?, ?, ?, NOW())"
                    );

                    if ($stmt->execute([$name, $email, $phone, $date, $time, $userMessage])) {
                        $message = '✅ Rendez-vous réservé avec succès ! Un email de confirmation sera envoyé.';
                        $messageType = 'success';

                        // Nettoyer les champs
                        $name = $email = $phone = $date = $time = $userMessage = '';

                        // Régénérer le token
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
            <p style="color: <?php echo strpos($message, 'succès') !== false ? 'green' : 'red'; ?>;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- ✅ Token CSRF caché -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="form-group">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" maxlength="100" required>
            </div>

            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" maxlength="100" required>
            </div>

            <div class="form-group">
                <label for="phone">Téléphone (optionnel) :</label>
                <input type="tel" id="phone" name="phone" maxlength="20">
            </div>

            <!-- ✅ Calendrier visuel -->
            <div class="form-group calendar-section">
                <label for="date">📅 Choisissez une date :</label>
                <input type="date" id="date" name="date"
                       min="<?php echo htmlspecialchars($currentDate); ?>"
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
                <textarea id="message" name="message" maxlength="500" rows="4"></textarea>
            </div>

            <button type="submit" class="cta">✅ Réserver mon créneau</button>
        </form>


        <h3>Créneaux Disponibles (Calendrier Simplifié)</h3>
        <p>Sélectionnez une date ci-dessus pour voir les heures libres.</p>
        <!-- Ici, vous pourriez intégrer FullCalendar pour un vrai calendrier visuel -->
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
