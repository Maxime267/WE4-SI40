<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation RDV</title>

    <style>

        body{
            font-family:Arial;
            background:#f4f6fb;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .container{
            display:flex;
            width:850px;
            background:white;
            border-radius:10px;
            box-shadow:0 15px 30px rgba(0,0,0,0.1);
            overflow:hidden;
        }

        .left{
            width:35%;
            background:#f7f9fc;
            padding:30px;
        }

        .right{
            width:65%;
            padding:30px;
        }

        .slots button{
            display:block;
            width:100%;
            padding:12px;
            margin:10px 0;
            border:2px solid #3a7afe;
            background:white;
            border-radius:6px;
            cursor:pointer;
        }

        .slots button:hover{
            background:#3a7afe;
            color:white;
        }

        form{
            display:none;
            margin-top:20px;
        }

        input{
            width:100%;
            padding:10px;
            margin:8px 0;
        }

        button.submit{
            background:#3a7afe;
            color:white;
            border:none;
            padding:12px;
            cursor:pointer;
        }

    </style>
</head>

<body>

<div class="container">

    <div class="left">

        <h2>Client Check-in</h2>

        <p>⏱ 30 minutes</p>
        <p>💻 Zoom</p>

    </div>

    <div class="right">

        <h3>Choisir une date</h3>

        <input type="date" id="date">

        <h3>Créneaux disponibles</h3>

        <div class="slots">

            <button onclick="selectTime('10:00')">10:00</button>
            <button onclick="selectTime('11:00')">11:00</button>
            <button onclick="selectTime('13:00')">13:00</button>
            <button onclick="selectTime('14:30')">14:30</button>
            <button onclick="selectTime('16:00')">16:00</button>

        </div>

        <form id="form" action="save.php" method="POST">

            <input type="text" name="name" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>

            <input type="hidden" name="date" id="formDate">
            <input type="hidden" name="time" id="formTime">

            <button class="submit">Confirmer le RDV</button>

        </form>

    </div>

</div>

<script>

    function selectTime(time){

        let date=document.getElementById("date").value;

        if(!date){
            alert("Choisissez une date");
            return;
        }

        document.getElementById("form").style.display="block";

        document.getElementById("formDate").value=date;
        document.getElementById("formTime").value=time;

    }

</script>

</body>
</html>