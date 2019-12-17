<?php
//Enter your code here, enjoy!

function intervaloHora($hora_inicio, $hora_fin, $intervalo = 15)
{

    $hora_inicio = new DateTime($hora_inicio);
    $hora_fin = new DateTime($hora_fin);
    $hora_fin->modify('+1 second'); // Añadimos 1 segundo para que muestre $hora_fin

    // Si la hora de inicio es superior a la hora fin
    // añadimos un día más a la hora fin
    if ($hora_inicio > $hora_fin) {

        $hora_fin->modify('+1 day');
    }

    // Establecemos el intervalo en minutos

    $intervalo = new DateInterval('PT' . $intervalo . 'M');

    // Sacamos los periodos entre las horas
    $periodo = new DatePeriod($hora_inicio, $intervalo, $hora_fin);


    foreach ($periodo as $hora) {

        // Guardamos las horas intervalos
        /*if(hora == "10:30:00"){
            $horas[] =  $hora->format('H:i:s') . "aqui";
        }
        */
        $horas[] = $hora->format('H:i');
    }

    return $horas;
}

print_r(intervaloHora('10:00:00', '12:00:00'));

echo "<br><br><br>";


$array_reservar_existentes = array(
    array("hora" => "10:00", "duracion" => 3),
    array("hora" => "12:30", "duracion" => 2),
    array("hora" => "14:00", "duracion" => 4),
    array("hora" => "17:00", "duracion" => 4),
    array("hora" => "19:30", "duracion" => 2),
);

array_push($array_reservar_existentes, array("hora" => "21:30", "duracion" => 2));


print_r($array_reservar_existentes);


echo $array_reservar_existentes[0]["hora"] . "<br>";
echo $array_reservar_existentes[1]["duracion"] . "<br>";
echo $array_reservar_existentes[2]["hora"] . "<br>";
echo "<br><br><br>";

$dur = "";
$contador = 0;
foreach (intervaloHora('10:00:00', '22:00:00') as $indice => $valor) {
    $encontrado = "";

    //recorro el array con las reservas existentes
    foreach ($array_reservar_existentes as $indice2 => $array) {

        //si la hora que me viene el generador de horas coincide con alguna hora ya existente en el array de reservas existentes
        if ($valor == $array["hora"]) {
            //echo $valor . " =  ". $array["duracion"] . " AQUI <br> ";
            $dur = $array["duracion"];
            $encontrado = $valor;

            //echo "CONTADOR =>>> " . $contador. "<br>";
            if ($contador >= $dur) {

                $contador = 0;
            }
            //echo "CONTADOR =>>> " . $contador. "<br>";
        }
    }


    if ($valor != $encontrado) {
        $contador++;
        if ($contador < $dur) {

        } else {
            echo $valor . " $dur <br>";

        }
    }
}




