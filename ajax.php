<?php
$conexion = new mysqli("localhost", "root", "", "citas2", "3306");


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
        $horas[] = $hora->format('H:i');
    }

    return $horas;
}


function intervaloHora_alreves($hora_fin, $hora_inicio, $intervalo = 15)
{
    $array_d = array();
    $array_d2 = array();
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
        array_push($array_d, $hora->format('H:i'));
    }

    for ($x = count($array_d) - 1; $x >= 0; $x--) {

        array_push($array_d2, $array_d[$x]);
    }

    return $array_d2;
}


if ($_REQUEST["pagg"] == 1) {


    if (isset($_REQUEST["categoria"])) {
        $categoria = $_REQUEST["categoria"];


        echo "<option>Selecciona un servicio</option>";
        $resultado = mysqli_query($conexion, "SELECT servicios.nombreServ from servicios INNER JOIN categorias ON servicios.idCategoria = categorias.idCategory WHERE categorias.nameCat = '$categoria'");
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<option value='$row[0]'>" . $row[0] . "</option>";
        }


    } else {
        $servicio = $_REQUEST["servicio"];

        echo "<option>Selecciona un empleado</option>";
        $resultado = mysqli_query($conexion, "SELECT empleados.nombreEmpleado from empleados INNER JOIN servicios_empleados ON empleados.idEmpleado = servicios_empleados.idEmpleado WHERE servicios_empleados.nombreServ = '$servicio'");
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<option value='$row[0]'>" . $row[0] . "</option>";
        }
    }


} elseif ($_REQUEST["pagg"] == 2) {
    $fecha = $_REQUEST["fecha"];
    $empleado = $_REQUEST["empleado"];
    $servicio = $_REQUEST["servicio"];
    $diaSemana = $_REQUEST["diaSemana"];
    echo $diaSemana . "<br>";


    $resultado = mysqli_query($conexion, "SELECT duracionServ FROM `servicios` WHERE `nombreServ` = '$servicio'");
    while ($row = mysqli_fetch_array($resultado)) {
        $duracion_servicio_seleccionado = $row[0];
    }
    $duracion_servicio_seleccionado = $duracion_servicio_seleccionado / 15;
    $duracion_servicio_seleccionado = ($duracion_servicio_seleccionado + 1);  //le sumo uno para el descanso de 15min
    echo "Nombre del servicio = " . $servicio . " con duracion de = $duracion_servicio_seleccionado<br>";


    $duracion_servicio = 0;
    $duracion_prueba = 0;
    $array_horas_para_eliminar = array();
    $array_horario = array();                   //full horario sin tocar
    $array_reservas_duracion = array();


    //sacamos el idEmple del empleado
    $idEmple = "";
    //hacemos select para sacar el idCliente
    $resultado_id_emple = mysqli_query($conexion, "SELECT idEmpleado from empleados WHERE nombreEmpleado = '$empleado'");
    $contador_id_emple = mysqli_num_rows($resultado_id_emple);
    if ($contador_id_emple != 0) {
        $idEmple = mysqli_fetch_array($resultado_id_emple)[0];
    }
    echo "ID EMPLEADO =>> $idEmple<br><br>";


    //sacamos el horario del empleado del dia seleccionado
    $horario_empleado_start = "";
    $horario_empleado_fin = "";
    $comprobar_horario_dia_seleccionado = mysqli_query($conexion, "SELECT horaStart, horaFin from horario_semanal WHERE diaSemana = '$diaSemana' AND idEmple ='$idEmple'");
    $contador55 = mysqli_num_rows($comprobar_horario_dia_seleccionado);
    if ($contador55 != 0) {
        while ($row = mysqli_fetch_array($comprobar_horario_dia_seleccionado)) {
            $horario_empleado_start = $row[0];
            $horario_empleado_fin = $row[1];
        }
    }
    echo "HORARIO SELECCIONADO EMPLEADO START =>> $horario_empleado_start<br>";
    echo "HORARIO SELECCIONADO EMPLEADO FIN =>> $horario_empleado_fin<br>";


    //rellenamos el horario sin tocarlo, es el horario entre el START y el FIN
    foreach (intervaloHora($horario_empleado_start, $horario_empleado_fin, $intervalo = 15) as $hora) {
        array_push($array_horario, $hora);
    }


    //comprobamos si hay citas el empleado seleccionado en el dia seleccionado
    $resultado = mysqli_query($conexion, "SELECT hora, nombreServicio from citas WHERE fecha = '$fecha' AND nombreEmpleado = '$empleado' ORDER BY `hora`");
    $contador2 = mysqli_num_rows($resultado);

    if ($contador2 != 0) {
        while ($row = mysqli_fetch_array($resultado)) {

            //cortamos la hora porque solo necesitamos los 6 primeros digitos
            $hora_cita = $row[0];
            $hora_cita = substr($hora_cita, 0, 5);

            //sacamos la duracion que tiene el servicio seleccionado
            $resultado4 = mysqli_query($conexion, "SELECT duracionServ from servicios WHERE nombreServ = '$row[1]'");
            $contador5 = mysqli_num_rows($resultado4);
            if ($contador5 != 0) {
                $duracion_servicio = mysqli_fetch_array($resultado4)[0];
            }


            echo "<span style='color:red'>DURACION PRUEBA SIN PARTIR: $duracion_servicio</span><br>";
            $duracion_prueba = $duracion_servicio / 15;
            echo "DURACION PRUEBA DIVIDIDA: $duracion_prueba<br><br>";


            $indice_inicio = "";
            $indice_fin = "";
            $hora_inicio = "";
            $hora_fin = "";
            $variable_para_anadir_array_reservas_duracion = array("posicion1" => "", "posicion2" => "", "hora" => "");
            foreach (intervaloHora($horario_empleado_start, $horario_empleado_fin, $intervalo = 15) as $indice => $hora) {
                //echo "<b>indice = $indice</b><br>";
                //echo "<b>indice_inicio = $hora</b><br>";
                //echo "<b>duracion dividida = $duracion_prueba</b><br><br>";


                //si la hora coincide con la hora de la cita de la BD
                if ($hora == $hora_cita) {
                    //guardamos el indice inicio
                    $indice_inicio = $indice;
                    //la hora de inicio
                    $hora_inicio = $hora;
                    echo "DURACION INICIO: $indice_inicio<br>";
                    echo "HORA INICIO: $hora_inicio<br><br>";
                    $variable_para_anadir_array_reservas_duracion["posicion1"] = $indice_inicio;
                }

                //si el indice del array el el mismo que el indice de inicio sumando lo que dura el servicio ($duracion_servicio DIVIDIDO entre 15minutos en este caso)
                //sabremos cuantas casillas tenemos que saltar
                if ($indice == ($indice_inicio + $duracion_prueba) /* -1 pongo MENOS UNO y ya no habra descansos*/) {
                    //aqui controlo que no sea la primera vez que entra porque me agregaba un campo de mas
                    if ($indice_inicio != 0) {
                        $hora_fin = $hora;
                        echo "DURACION FIN: $indice<br>";
                        echo "HORA FIN: $hora_fin<br><br>";
                        $variable_para_anadir_array_reservas_duracion["posicion2"] = $indice;
                        array_push($array_reservas_duracion, $variable_para_anadir_array_reservas_duracion);
                    }
                }
            }


            //rellenamos el array con las horas (los intervalos de horas) que hay QUE NO HAY QUE MOSTRAR
            //hora inicio sera porejemplo 11:00 y hora fin sera 12:00
            //lo dificil es sacar la hora fin ya que no la sabesmos pero con lo realizado arriba funciona perfectamente
            foreach (intervaloHora($hora_inicio, $hora_fin, $intervalo = 15) as $hora) {

                if ($hora == $hora_fin) {
                    echo "<b>Hora para eliminar descanso: $hora, hasta +15min</b><br>";
                } else {
                    echo "Hora para eliminar: $hora<br>";
                }

                array_push($array_horas_para_eliminar, $hora);
            }
            echo "<br><br><br>";
        }

        echo "<br><br><br>";


        ///este es el codigo que me hace la comprobacion si el servicio escogido por su duración cabe entre las reservas
        /// ya existentes

        /*
        $diferencia = 0;
        $indice_para_borar = "";

        echo "CONTADOR: " . count($array_reservas_duracion);
        for($x = 0; $x < count($array_reservas_duracion); $x++){
            if($x < count($array_reservas_duracion)-1){
                echo "<h2>$x</h2>";
                echo "<h4>" . $array_reservas_duracion[$x+1]["posicion1"] . "</h4>";
                echo "<h4>" . $array_reservas_duracion[$x]["posicion2"] . "</h4>";

                $diferencia = $array_reservas_duracion[$x+1]["posicion1"] - $array_reservas_duracion[$x]["posicion2"];
                echo "DIFERENCIA ". $diferencia . "<br>";

                if($diferencia <= $duracion_servicio_seleccionado){
                    echo "HAY QUE BORRARRR!<br>";

                    echo $mayor = $array_reservas_duracion[$x]["posicion2"]+1;
                    echo "<br>";
                    echo $menor = $array_reservas_duracion[$x+1]["posicion1"]-1;
                    echo "<br>";

                    for($i = $mayor; $i <= $menor; $i++){
                        echo "<b> esta posicion $i</b>";
                        for($o = 0; $o < count($array_horario); $o++){
                            if($o == $i){
                                array_push($array_horas_para_eliminar, $array_horario[$o]);
                                echo "borrado!";
                            }
                        }
                    }
                }
            }
        }
*/


        echo "<br><br><br>Array con horas eliminadas<br>";
        print_r($array_horas_para_eliminar);
        echo "<br><br>";
        $array_horario_horas_libres = array();
        $array_horario_horas_libres = array_diff($array_horario, $array_horas_para_eliminar);

        echo "Array con horas que se muestran<br>";
        print_r($array_horario_horas_libres);


        echo "<h1>CONTINUAMOS...</h1>";
        echo count($array_horario_horas_libres);
        echo "<br>";


        $array_prueba = array("inicio" => "", "fin" => "", "contador" => "", "contador2" => "");
        $array_prueba_llenado = array();
        $primera_vez = true;
        $asignado = false;
        $contador = 0;

        for ($x = 0; $x < count($array_horario); $x++) {

            if (array_key_exists($x, $array_horario_horas_libres)) {
                echo $x . " = $array_horario_horas_libres[$x]<br>";
                $contador++;

                if ($primera_vez == true) {
                    $array_prueba["inicio"] = $array_horario_horas_libres[$x];
                    $primera_vez = false;
                } else {
                    $array_prueba["fin"] = $array_horario_horas_libres[$x];
                    $array_prueba["contador"] = $contador;
                    $asignado = false;

                }

            } else {
                if ($asignado == false) {
                    array_push($array_prueba_llenado, $array_prueba);
                    $contador = 0;
                    $asignado = true;
                    $primera_vez = true;
                }

                echo $x . " esta vacio<br>";
                $primera_vez = true;
            }


        }

        var_dump($array_prueba_llenado);
        $ultima_reserva_fin = "";

        for ($x = 0; $x < count($array_prueba_llenado); $x++) {
            echo $array_prueba_llenado[$x]["inicio"] . " = " . $array_prueba_llenado[$x]["fin"] . " = " . $array_prueba_llenado[$x]["contador"] . "<br>";
            //guardamos el
            $ultima_reserva_fin = $array_prueba_llenado[$x]["fin"];

            $num_horas_que_no_borramos = ($array_prueba_llenado[$x]["contador"] - $duracion_servicio_seleccionado);
            echo "quedan libres " . $num_horas_que_no_borramos;
            echo "<br>";


            $contador = 0;
            echo "CONTADOR = " . $contador . "<br>";
            foreach (intervaloHora_alreves($array_prueba_llenado[$x]["fin"], $array_prueba_llenado[$x]["inicio"], $intervalo = 15) as $hora) {

                //filtramos para que si se da el caso de que la duración del servicio es de 2 franjas y el hueco que tenemos libre es de 3 franjas
                //pues que elimine solo la ultima y nos deje elejir entre la primera franja y la ultima
                if ($duracion_servicio_seleccionado == 2 && $array_prueba_llenado[$x]["contador"] == 3) {
                    if ($contador + 1 < $duracion_servicio_seleccionado) {
                        $contador++;
                        echo "BORRAMOS = " . $hora . "<br>";
                        array_push($array_horas_para_eliminar, $hora);
                    } else {
                        echo "$hora <br>";
                    }
                } else {
                    if ($contador < $duracion_servicio_seleccionado) {
                        $contador++;
                        echo "BORRAMOS = " . $hora . "<br>";
                        array_push($array_horas_para_eliminar, $hora);
                    } else {
                        echo "$hora <br>";
                    }
                }
            }
            echo "<br><br>";

        }


        echo "<h1>EL ULTIMO!</h1>";
        $contador_el_ultimo = 0;
        foreach (intervaloHora_alreves($horario_empleado_fin, $ultima_reserva_fin, $intervalo = 15) as $hora) {
            $contador_el_ultimo++;
        }

        echo $contador_el_ultimo . "<br>";

        $contador_igualacion = 1;

        foreach (intervaloHora_alreves($horario_empleado_fin, $ultima_reserva_fin, $intervalo = 15) as $hora) {
            $contador_igualacion++;
            if ($contador_igualacion <= $duracion_servicio_seleccionado) {
                echo "PARA BORRAR => " . $hora . "<br>";
                array_push($array_horas_para_eliminar, $hora);
            } else {
                echo "$hora <br>";
            }
        }


        $array_horario_horas_libres = array_diff($array_horario, $array_horas_para_eliminar);


        echo "<div id='diaSeleccionado'></div>";
        foreach ($array_horario_horas_libres as $indice => $valor) {
            echo "<button value='$valor' id='hora_opcion' class='$indice' onclick='show(3);guardar_valor_cookie_hora(this.value)'>" .
                "<span id='hora_numeric'>" . $valor . "</span>" .
                "<span id='buton_reservar'>Reserva ahora!</span>" .
                "</button><br>";
        }


        echo "HAY CITAS";
    } else {
        echo "NO HAY CITAS";


        echo "<div id='diaSeleccionado'></div>";
        for ($x = 0; $x < count($array_horario); $x++) {

            echo "<button value='$array_horario[$x]' id='hora_opcion' onclick='show(3);guardar_valor_cookie_hora(this.value)'>" .
                "<span id='hora_numeric'>" . $array_horario[$x] . "</span>" .
                "<span id='buton_reservar'>Reserva ahora!</span>" .
                "</button><br>";

        }

    }

    print_r($array_reservas_duracion);


} elseif ($_REQUEST["pagg"] == 3) {

    $nombre = $_REQUEST["nombre"];

    $telefono = $_REQUEST["telefono"];

    $correo = $_REQUEST["correo"];

    $_REQUEST["nota"];

    $fecha = $_REQUEST["fecha"];
    echo $fecha;
    $hora = $_REQUEST["hora"];

    $empleado = $_REQUEST["empleado"];

    $servicio = $_REQUEST["servicio"];


    $nombre_check = true;
    $correo_check = true;
    $telefono_check = true;


    //////////////////////////////////////////////////////////////////////
    ////////////COMPRUEBO SI LOS DATOS INTRODUCIDOS COINCIDEN/////////////
    //////////////////////////////////////////////////////////////////////
    $resultado2 = mysqli_query($conexion, "SELECT * from clientes WHERE nombreCliente = '$nombre' AND telefonoCliente = '$telefono' AND correoCliente = '$correo'");
    $contador2 = mysqli_num_rows($resultado2);
    if ($contador2 != 0) {
        echo "EXISTE!";


        $idCliente = "";
        //hacemos select para sacar el idCliente
        $resultado = mysqli_query($conexion, "SELECT idCliente from clientes WHERE telefonoCliente = '$telefono'");
        $contador = mysqli_num_rows($resultado);                                                //lo que devuelve la select lo guardamos en la variable contador,
        if ($contador != 0) {
            while ($row = mysqli_fetch_array($resultado)) {
                $idCliente = $row[0];
            }
        }

        //INSERTAR LA RESERVA
        $insertar_reserva = "INSERT INTO citas (fecha, hora, nombreEmpleado, nombreServicio, idCliente) VALUES ('$fecha', '$hora', '$empleado', '$servicio', '$idCliente')";
        $conexion->query($insertar_reserva);

        echo "HAS AÑADIDO LA NUEVA CITA";
    }


    //////////////////////////////////////////////////////////////////////
    ////////////COMPRUEBO SI LOS DATOS INTRODUCIDOS COINCIDEN/////////////
    //////////////////////////////////////////////////////////////////////
    $resultado = mysqli_query($conexion, "SELECT nombreCliente, correoCliente from clientes WHERE telefonoCliente = '$telefono'");
    $contador1 = mysqli_num_rows($resultado);
    if ($contador1 != 0) {
        while ($row = mysqli_fetch_array($resultado)) {

            if ($nombre != $row[0]) {
                $nombre_check = false;
            }

            if ($correo != $row[1]) {
                $correo_check = false;
            }
        }
    } else {
        $nombre_check = false;
        $correo_check = false;
        $telefono_check = false;
    }


    //si todos los datos son nuevos para el servidor INSERTAMOS EL NUEVO USUARIO
    if ($nombre_check == false && $telefono_check == false && $correo_check == false) {
        echo "asd";

        $insertar = "INSERT INTO clientes (nombreCliente, telefonoCliente, correoCliente) VALUES ('$nombre', '$telefono', '$correo')";
        $conexion->query($insertar);
        echo "Se ha añadido el cliente <b>" . $nombre . "</b> con exito!<br><br>";


        $idCliente = "";
        //hacemos select para sacar el idCliente
        $resultado = mysqli_query($conexion, "SELECT idCliente from clientes WHERE telefonoCliente = '$telefono'");
        $contador = mysqli_num_rows($resultado);                                                //lo que devuelve la select lo guardamos en la variable contador,
        if ($contador != 0) {
            while ($row = mysqli_fetch_array($resultado)) {
                $idCliente = $row[0];
            }
        }

        //INSERTAR LA RESERVA
        $insertar_reserva = "INSERT INTO citas (fecha, hora, nombreEmpleado, nombreServicio, idCliente) VALUES ('$fecha', '$hora', '$empleado', '$servicio', '$idCliente')";
        $conexion->query($insertar_reserva);

        echo "HAS AÑADIDO LA NUEVA CITA";


    } else {
        //correo mal escrito
        if ($nombre_check == true && $correo_check == false) {
            echo "Su telefono: " . $telefono . " ya esta asociado con otro correo electronico.<br><br>Presione <b>Actualizar</b> si desea actualizar el correo electronico, o presiones </b>Cancelar</b> para editar los datos ingresados";
            //nombre mal escrito
        } else if ($nombre_check == false && $correo_check == false) {
            echo "Su telefono: " . $telefono . " ya esta asociado con otro nombre y correo electronico.<br><br>Presione <b>Actualizar</b> si desea actualizar su nombre y correo electronico, o presiones <b>Cancelar</b> para editar los datos ingresados";
            //nombre y correo mal escritos
        } else if ($nombre_check == false && $correo_check == true) {
            echo "Su telefono: " . $telefono . " ya esta asociado con otro nombre.<br><br>Presione <b>Actualizar</b> si desea actualizar su nombre, o presiones <b>Cancelar</b> para editar los datos ingresados";
        }
    }


} else {

}


?>