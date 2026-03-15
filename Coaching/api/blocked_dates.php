<?php
require_once('../db.php');

// Récupérer toutes les règles
$stmt = $pdo->prepare("SELECT day_of_week, start_time, end_time FROM schedule_free_appointement");
$stmt->execute();
$rules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer toutes les dates bloquées
$stmt = $pdo->prepare("SELECT start_date, end_date FROM block_dates");
$stmt->execute();
$blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Générer un tableau de toutes les dates bloquées
$blockedDates = [];

foreach ($blocks as $row) {
    $start = new DateTime($row['start_date']);
    $end = new DateTime($row['end_date']);
    while ($start <= $end) {
        $blockedDates[] = $start->format('Y-m-d');
        $start->modify('+1 day');
    }
}

// Ici, on peut retourner JSON avec les infos
header('Content-Type: application/json');

echo json_encode([
    'rules' => $rules,           // disponibilités récurrentes
    'blockedDates' => $blockedDates  // dates complètes bloquées
]);