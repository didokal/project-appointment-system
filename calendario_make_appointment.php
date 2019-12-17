<script>

    function diaSemana(dia,mes,anio){
        var dias=["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
        var dt = new Date(mes+' '+dia+', '+anio+' 12:00:00');
        //document.getElementById('div1').innerHTML = "Dia de la semana : " + dias[dt.getUTCDay()];

        //alert(dias[dt.getUTCDay()]);
        return dias[dt.getUTCDay()];
    };


    function dosomething(dia){
        var mes = document.getElementById("month").innerHTML;
        var anio = document.getElementById("year").innerHTML;

        console.log(mes);
        console.log(anio);
        console.log(dia);

        var mes_numero = 0;
        var mes_en_ingles = "";
        if(mes == "Enero"){
            mes_en_ingles = "January"
            mes_numero = 1;
        }else if(mes == "Febrero"){
            mes_en_ingles = "February"
            mes_numero = 2;
        }else if(mes == "Marzo"){
            mes_en_ingles = "March"
            mes_numero = 3;
        }else if(mes == "Abril"){
            mes_en_ingles = "April"
            mes_numero = 4;
        }else if(mes == "Mayo"){
            mes_en_ingles = "May"
            mes_numero = 5;
        }else if(mes == "Junio"){
            mes_en_ingles = "June"
            mes_numero = 6;
        }else if(mes == "Julio"){
            mes_en_ingles = "July"
            mes_numero = 7;
        }else if(mes == "Agosto"){
            mes_en_ingles = "August"
            mes_numero = 8;
        }else if(mes == "Septiembre"){
            mes_en_ingles = "September"
            mes_numero = 9;
        }else if(mes == "Octubre"){
            mes_en_ingles = "October"
            mes_numero = 10;
        }else if(mes == "Noviembre"){
            mes_en_ingles = "November"
            mes_numero = 11;
        }else if(mes == "Diciembre"){
            mes_en_ingles = "December"
            mes_numero = 12;
        }

        document.cookie = "fecha=" + anio + "-" + mes_numero + "-" + dia;
        document.cookie = "diaSemana=" + diaSemana(dia, mes_en_ingles, anio);
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
        $sMonthName = "Enero";
        break;
    case "February" :
        $sMonthName = "Febrero";
        break;
    case "March" :
        $sMonthName = "Marzo";
        break;
    case "April" :
        $sMonthName = "Abril";
        break;
    case "May" :
        $sMonthName = "Mayo";
        break;
    case "June" :
        $sMonthName = "Junio";
        break;
    case "July" :
        $sMonthName = "Julio";
        break;
    case "August" :
        $sMonthName = "Agosto";
        break;
    case "September":
        $sMonthName = "Semptiembre";
        break;
    case "October":
        $sMonthName = "Octubre";
        break;
    case "November" :
        $sMonthName = "Noviembre";
        break;
    case "December" :
        $sMonthName = "Diciembre";
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
                $sCalTblRows .= '<td id="asd" class="actualMonthPastDays" value="'.$iCurrentDay.'"><a href="javascript: void(0)">'.$iCurrentDay.'</a></td>';
            }else{
                $sCalTblRows .= '<td id="asd" onclick="dosomething(this.innerText)" class="'.$sClass.'" value="'.$iNowDay.'"><a href="javascript: void(0)">'.$iCurrentDay.'</a></td>';
            }

        }else{
            $sCalTblRows .= '<td id="asd" class="'.$sClass.'" value="'.$iCurrentDay.'"><a href="javascript: void(0)">'.$iCurrentDay.'</a></td>';
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


