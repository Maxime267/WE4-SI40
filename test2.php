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
<div id="time-slots"></div>

<script>
    fetch("Coaching/api/blocked_dates.php")
        .then(res => res.json())
        .then(data => {
            const rules = data.rules;
            const blockedDates = data.blockedDates.map(dateStr => new Date(dateStr));

            const calendar = flatpickr("#calendar", {
                inline: true,
                locale: { firstDayOfWeek: 1 },
                dateFormat: "Y-m-d",
                enable: [
                    function(date) {
                        // autoriser uniquement les jours selon rules
                        return rules.some(rule => date.getDay() === parseInt(rule.day_of_week));
                    }
                ],
                disable: blockedDates,
                onChange: function(selectedDates) {
                    const selectedDate = selectedDates[0];
                    if (!selectedDate) return;

                    // nettoyer l'ancien contenu
                    const slotsContainer = document.getElementById("time-slots");
                    slotsContainer.innerHTML = "";

                    // trouver les règles pour le jour sélectionné
                    const dayRules = rules.filter(rule => rule.day_of_week == selectedDate.getDay());

                    dayRules.forEach(rule => {
                        // créer les boutons horaires
                        const startHour = parseInt(rule.start_time);
                        const endHour = parseInt(rule.end_time);

                        for (let hour = startHour; hour <= endHour; hour++) {
                            const btn = document.createElement("button");
                            btn.textContent = hour + ":00";
                            btn.classList.add("time-btn");
                            btn.addEventListener("click", () => {
                                alert("Vous avez sélectionné : " + selectedDate.toISOString().split('T')[0] + " à " + hour + ":00");
                            });
                            slotsContainer.appendChild(btn);
                        }
                    });
                }
            });
        })
        .catch(err => console.error("Erreur fetch:", err));
</script>

</body>
</html>
