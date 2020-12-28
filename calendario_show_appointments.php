<?php
session_start();

if(!isset($_SESSION["permiso"]) || ($_SESSION["permiso"] != null && $_SESSION["permiso"] != "admin" && $_SESSION["permiso"] != "user")){
    die("<h1 style='color:darkslateblue'>Забранен достъп!</h1>");
}
if (isset($_GET["exit"])) {
    salir();
}
function salir()
{
    unset($_SESSION["permiso"]);
    unset($_SESSION["user"]);
    unset($_SESSION["pass"]);
    session_unset();
    session_destroy();
    header('Location: admin-login.html');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Админ панел - Календар с резервации</title>
    <meta charset='utf-8' />
    <style>
        html, body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 16px;
        }

        #calendar {
            max-height:600px !important;;
        }

        .fc-today {
            background: transparent !important; /* hack. because demo will always start out on current day */
        }
        .fc-time-grid .fc-slats td{
            height:35px !important;

        }
        .fc-event-time,
        .fc-event-title {
            padding: 0 1px;
            float: left;
            clear: none;
            margin-right: 10px;
        }


    </style>
    <link rel="stylesheet" href="css/styles.css">
    <link href='calendar_show_appointments/core@4.3.1/main.min.css' rel='stylesheet' />
    <link href='calendar_show_appointments/daygrid@4.3.0/main.min.css' rel='stylesheet' />
    <link href='calendar_show_appointments/timegrid@4.3.0/main.min.css' rel='stylesheet' />





    <script src='calendar_show_appointments/core/locales/bg.js'></script>
    <link href='calendar_show_appointments/daygrid/main.css' rel='stylesheet' />
    <link href='calendar_show_appointments/timegrid/main.css' rel='stylesheet' />
    <script src='calendar_show_appointments/daygrid/main.js'></script>
    <script src='calendar_show_appointments/timegrid/main.js'></script>



    <script src='calendar_show_appointments/core@4.3.1/main.min.js'></script>
    <script src='calendar_show_appointments/daygrid@4.3.0/main.min.js'></script>
    <script src='calendar_show_appointments/timegrid@4.3.0/main.min.js'></script>



    <script src='calendar_show_appointments/resource-common@4.3.1/main.min.js'></script>
    <script src='calendar_show_appointments/resource-daygrid@4.3.0/main.min.js'></script>
    <script src='calendar_show_appointments/resource-timegrid@4.3.0/main.min.js'></script>

    <script src='calendar_show_appointments/core/locales/bg.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                header: { center: 'resourceTimeGridDay, timeGridWeek, dayGridMonth' },
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                allDaySlot: false,
                plugins: [ 'resourceTimeGrid', 'dayGrid', 'timeGrid' ],
                minTime: '08:00', maxTime: '22:00',
                defaultView: 'resourceTimeGridDay',
                resources: './calendario_show_appointments_get_employees.php',
                events: './calendario_show_appointments_get_appointments_json.php',
            });
            calendar.setOption('locale', 'bg');

            calendar.render();
        });
    </script>
</head>
<body style="margin-top: 22px;">
<h1>Меню</h1>
<ul id="temporal_ul">
    <?php
    if ($_SESSION["permiso"] == "admin") {
        echo '<li><a href="empleados.php">Служители</a></li>
              <li><a href="categorias.php">Категории</a></li>
              <li><a href="servicios.php">Услуги</a></li>
              <li><a href="clientes.php">Клиенти</a></li>
              <li><a href="calendario_show_appointments.php">Календар с резервации</a></li>
              <li><a href="citas.php">Резервации</a></li>
              <li><a href="?exit">Изход</a></li>';
    } else {
        echo '<li><a href="calendario_show_appointments.php">Календар с резервации</a></li>
              <li><a href="citas.php">Резервации</a></li>
              <li><a href="?exit">Изход</a></li>';
    }
    ?>
</ul>
<hr style="color: #0056b2" />
<br><br><br>
<div id='calendar'></div>
</body>
</html>
