<?php
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation RDV</title>
</head>
<body>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    .gray-day {
        background: #eee;
        color: #999;
        pointer-events: none; /* empêche le clic */
    }
</style>

<div id="calendar"></div>

<script>

    fetch("/Coaching/api/blocked_dates.php")
    .then(res => res.json())
    .then(blockedDates => {


    flatpickr("#calendar", {
        inline: true,

        locale: {
            firstDayOfWeek: 1 // 0 = dimanche, 1 = lundi
        },


        disable: [
            function(date) {
                // Désactive samedi dimanche
                return (date.getDay() === 0 || date.getDay() === 6);
            },
            ...blockedDates.map(date => new Date(date))
        ],
    });

    });

</script>




</body>
</html>