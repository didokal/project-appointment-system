<script>

    function diaSemana(dia,mes,anio){
        //var dias=["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
        var dias=["Неделя", "Понеделник", "Вторник", "Сряда", "Четвъртък", "Петък", "Събота"];
        var dt = new Date(mes+' '+dia+', '+anio+' 12:00:00');
        //document.getElementById('div1').innerHTML = "Dia de la semana : " + dias[dt.getUTCDay()];

        //alert(dias[dt.getUTCDay()]);
        return dias[dt.getUTCDay()];
    };


    function dosomething(dia){
        var mes = document.getElementById("month").innerHTML;
        var anio = document.getElementById("year").innerHTML;

        //quitar el color de fondo cuando hacemos clieck sobre una fecha
        var numEleme = document.getElementsByClassName("asd2");
        for(b = 0; b < numEleme.length; b++) {
            numEleme[b].style.backgroundColor = "transparent";
        }



        //poner color de fondo cuando hacemos click sobre una fecha
        var x = document.getElementsByClassName("asd2");

        for(a = 0; a < x.length; a++) {
            if (x[a].textContent == dia) {
                x[a].style.borderRadius = "99%";
                x[a].style.color = "blue";
                x[a].style.backgroundColor = "#b34f00";
            }
        }

        console.log(mes);
        console.log(anio);
        console.log(dia);

        var mes_numero = 0;
        var mes_en_ingles = "";
        if(mes == "Януари"){
            mes_en_ingles = "January"
            mes_numero = 1;
        }else if(mes == "Февруари"){
            mes_en_ingles = "February"
            mes_numero = 2;
        }else if(mes == "Март"){
            mes_en_ingles = "March"
            mes_numero = 3;
        }else if(mes == "Април"){
            mes_en_ingles = "April"
            mes_numero = 4;
        }else if(mes == "Май"){
            mes_en_ingles = "May"
            mes_numero = 5;
        }else if(mes == "Юни"){
            mes_en_ingles = "June"
            mes_numero = 6;
        }else if(mes == "Юли"){
            mes_en_ingles = "July"
            mes_numero = 7;
        }else if(mes == "Август"){
            mes_en_ingles = "August"
            mes_numero = 8;
        }else if(mes == "Септември"){
            mes_en_ingles = "September"
            mes_numero = 9;
        }else if(mes == "Октомври"){
            mes_en_ingles = "October"
            mes_numero = 10;
        }else if(mes == "Ноември"){
            mes_en_ingles = "November"
            mes_numero = 11;
        }else if(mes == "Декември"){
            mes_en_ingles = "December"
            mes_numero = 12;
        }

        var now = new Date();
        var time = now.getTime();
        time += 360 * 1000;
        now.setTime(time);

        document.cookie = "fecha=" + anio + "-" + mes_numero + "-" + dia + '; expires=' + now.toUTCString();
        document.cookie = "diaSemana=" + diaSemana(dia, mes_en_ingles, anio) + '; expires=' + now.toUTCString();
    }
</script>


<?php
// Get current year, month and day
list($iNowYear, $iNowMonth, $iNowDay) = explode('-', date('Y-m-d'));
// Get current year and month depending on possible GET parameters
if (isset($_GET['month'])) {
    list($iMonth, $iYear) = explode('-', $_GET['month']);
    $iMonth = (int)$iMonth;
    $iYear = (int)$iYear;
} else {
    list($iMonth, $iYear) = explode('-', date('n-Y'));
}
// Get name and number of days of specified month
$iTimestamp = mktime(0, 0, 0, $iMonth, 1, $iYear);
list($sMonthName, $iDaysInMonth) = explode('-', date('F-t', $iTimestamp));

switch ($sMonthName){
    case "January" :
        $sMonthName = "Януари";
        break;
    case "February" :
        $sMonthName = "Февруари";
        break;
    case "March" :
        $sMonthName = "Март";
        break;
    case "April" :
        $sMonthName = "Април";
        break;
    case "May" :
        $sMonthName = "Май";
        break;
    case "June" :
        $sMonthName = "Юни";
        break;
    case "July" :
        $sMonthName = "Юли";
        break;
    case "August" :
        $sMonthName = "Август";
        break;
    case "September":
        $sMonthName = "Септември";
        break;
    case "October":
        $sMonthName = "Октомври";
        break;
    case "November" :
        $sMonthName = "Ноември";
        break;
    case "December" :
        $sMonthName = "Декември";
        break;
}

// Get previous year and month
$iPrevYear = $iYear;
$iPrevMonth = $iMonth - 1;
if ($iPrevMonth <= 0) {
    $iPrevYear--;
    $iPrevMonth = 12; // set to December
}

//comprobamos si el mes anterior es el mes anterior del mes actual de nuestra vida
if($iPrevMonth == $iNowMonth-1 && $iNowYear == $iYear){
    $iPrevMonth = $iNowMonth-1;
}
//si es asi ocultamos el boton para poder retroceder
if($iPrevMonth == $iNowMonth-1){
    echo "<style>#calendar .navigation .prev {display: none}</style>";
}else{
    echo "<style>#calendar .navigation .prev {display: block}</style>";
}




// Get next year and month
$iNextYear = $iYear;
$iNextMonth = $iMonth + 1;
if ($iNextMonth > 12) {
    $iNextYear++;
    $iNextMonth = 1;
}
// Get number of days of previous month
$iPrevDaysInMonth = (int)date('t', mktime(0, 0, 0, $iPrevMonth, 1, $iPrevYear));
// Get numeric representation of the day of the week of the first day of specified (current) month
$iFirstDayDow = (int)date('w', mktime(0, 0, 0, $iMonth, 0, $iYear));
// On what day the previous month begins
$iPrevShowFrom = $iPrevDaysInMonth - $iFirstDayDow + 1;
// If previous month
$bPreviousMonth = ($iFirstDayDow > 0);
// Initial day
$iCurrentDay = ($bPreviousMonth) ? $iPrevShowFrom : 1;          //me da el dia actual
$bNextMonth = false;
$sCalTblRows = '';


// Generate rows for the calendar
for ($i = 0; $i < 6; $i++) { // 6-weeks range
    $sCalTblRows .= '<tr>';
    for ($j = 0; $j < 7; $j++) { // 7 days a week
        $sClass = 'otherMonth';
        if ($iNowYear == $iYear && $iNowMonth == $iMonth && $iNowDay == $iCurrentDay && !$bPreviousMonth && !$bNextMonth) {
            $sClass = 'today';
        } elseif (!$bPreviousMonth && !$bNextMonth) {
            $sClass = 'current';
        }

        if($sClass != 'otherMonth') {
            //si estamos en el dia 9 del mes de noviembre no queremos que los dias anterior de este mes sean seleccionables
            if ($iCurrentDay < $iNowDay && $iNowYear == $iYear && $iNowMonth == $iMonth){
                echo "<script>console.log($iCurrentDay)</script>";
                $sCalTblRows .= '<td class="asd" id="actualMonthPastDays" value="'.$iCurrentDay.'"><a href="javascript: void(0)">'.$iCurrentDay.'</a></td>';
            }else{
                $sCalTblRows .= '<td class="asd2" onclick="dosomething(this.innerText)" id="'.$sClass.'" value="'.$iNowDay.'"><a href="javascript: void(0)">'.$iCurrentDay.'</a></td>';
            }

        }else{
            $sCalTblRows .= '<td class="asd" id="'.$sClass.'" value="'.$iCurrentDay.'"><a href="javascript: void(0)">'.$iCurrentDay.'</a></td>';
        }



        // Next day
        $iCurrentDay++;
        if ($bPreviousMonth && $iCurrentDay > $iPrevDaysInMonth) {
            $bPreviousMonth = false;
            $iCurrentDay = 1;
        }
        if (!$bPreviousMonth && !$bNextMonth && $iCurrentDay > $iDaysInMonth) {
            $bNextMonth = true;
            $iCurrentDay = 1;
        }
    }
    $sCalTblRows .= '</tr>';
}
// Prepare replacement keys and generate the calendar
$aKeys = array(
    '__prev_month__' => "{$iPrevMonth}-{$iPrevYear}",
    '__next_month__' => "{$iNextMonth}-{$iNextYear}",
    '__cal_caption__' => $sMonthName,
    '__cal_caption2__' => $iYear,
    '__cal_rows__' => $sCalTblRows,
);
$sCalendarItself = strtr(file_get_contents('calendar_make_appointment/calendar.html'), $aKeys);
// AJAX requests - return the calendar
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_GET['month'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo $sCalendarItself;
    exit;
}
$aVariables = array(
    '__calendar__' => $sCalendarItself,
);

echo strtr(file_get_contents('calendar_make_appointment/index.html'), $aVariables);


