<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        #diaSeleccionado{
            background: #a31e50 !important;
            border: 1px solid #a31e50 !important;
            color:white;
            padding: 6px;
        }

        #hora_opcion:hover{
            background: #efefef !important;
        }

        #hora_opcion{
            background: white !important;
            cursor: pointer;
            border: 0;
            padding: 10px;
            border-bottom: 1px solid #cccccc !important;
            width: 100%;
            text-align: left;
        }

        #buton_reservar{
            background: #a31e50;
            float: right;
            color: white !important;
            font-weight: bold !important;
            border: 1px solid #a31e50;
            padding: 6px 10px;
        }

    </style>
</head>
<body>
<div id="diaSeleccionado">Lunes, 20 de Nomviembre</div><br>
<?php

$array_horario_primera_vez = array(
    "10:00", "10:15", "10:30", "10:45",
    "11:00", "11:15", "10:30", "10:45",
    "12:00", "12:15", "12:30", "12:45",
    "13:00", "13:15", "13:30", "13:45",
    "14:00", "14:15", "14:30", "14:45",
    "15:00", "15:15", "15:30", "15:45",
    "16:00", "16:15", "16:30", "16:45",
    "17:00", "17:15", "17:30", "17:45",
    "18:00", "18:15", "18:30", "18:45",
    "19:00", "19:15", "19:30", "19:45",
    "20:00", "20:15", "20:30", "20:45",
    "21:00", "21:15", "21:30", "21:45",


);



for ($x = 0; $x < count($array_horario_primera_vez); $x++){

    echo "<button id='hora_opcion'>" .
        "<span id='hora_numeric'>" . $array_horario_primera_vez[$x] . "</span>".
        "<span id='buton_reservar'>Reserva ahora!</span>".
    "</button><br>";

}



?>

</body>
</html>