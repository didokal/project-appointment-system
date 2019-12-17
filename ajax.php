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


    //mostramos cuantas casillas ocupa el servicio seleccionado
    $resultado = mysqli_query($conexion, "SELECT duracionServ FROM `servicios` WHERE `nombreServ` = '$servicio'");
    while ($row = mysqli_fetch_array($resultado)) {
        $duracion_servicio_seleccionado = $row[0];
    }
    $duracion_servicio_seleccionado = $duracion_servicio_seleccionado / 15;
    $duracion_servicio_seleccionado = ($duracion_servicio_seleccionado + 1);  //le sumo uno para el descanso de 15min
    echo "Nombre del servicio = " . $servicio . " con duracion de = $duracion_servicio_seleccionado<br>";


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


            $indice_inicio = 0;
            $indice_fin = 0;
            $hora_inicio = 0;
            $hora_fin = 0;
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




        //en esta parte lo que hacemos es meter en el array $array_conjunto_huecos_libres todos los huecos libres existentes para ese dia
        //$array_conjunto_huecos_libres tendra dentro tantos arrays como huecos libres de varios fragmentos haya el dia seleccionado
        $array_conjunto_huecos_libres_temporal = array("inicio" => "", "fin" => "", "contador" => "");
        $array_conjunto_huecos_libres = array();
        $primera_vez = true;
        $asignado = false;
        $contador = 0;

        for ($x = 0; $x < count($array_horario); $x++) {
            if (array_key_exists($x, $array_horario_horas_libres)) {
                echo $x . " = $array_horario_horas_libres[$x]<br>";
                $contador++;

                if ($primera_vez == true) {
                    $array_conjunto_huecos_libres_temporal["inicio"] = $array_horario_horas_libres[$x];
                    $primera_vez = false;
                } else {
                    $array_conjunto_huecos_libres_temporal["fin"] = $array_horario_horas_libres[$x];
                    $array_conjunto_huecos_libres_temporal["contador"] = $contador;
                    $asignado = false;
                }
            } else {
                if ($asignado == false) {
                    array_push($array_conjunto_huecos_libres, $array_conjunto_huecos_libres_temporal);
                    $contador = 0;
                    $asignado = true;
                    $primera_vez = true;
                }
                echo $x . " esta vacio<br>";
                $primera_vez = true;
            }
            //si me queda en algun sitio una casilla solita el contador la va a aumentar, entonces me rompe las reglas
            //con este if emitamos
            if ($primera_vez == true && $contador >= 1) {
                $contador = 0;
            }
        }

        //el ultimo intervalo de casillas libres hay que añadirlo aqui porque en cuento llega al maximo el for se sale del for
        //y no lo añade
        if ($asignado == false) {
            array_push($array_conjunto_huecos_libres, $array_conjunto_huecos_libres_temporal);
            $contador = 0;
            $asignado = true;
            $primera_vez = true;
        }

        var_dump($array_conjunto_huecos_libres);
        $ultima_reserva_fin = "";


        //ahora con este for lo que haremos es lo siguiente:
        //si tenemos un hueco libre con 5 fragmentos (10:00, 10:15, 10:30, 10:45, 11:00) y el servicio que hemos seleccionado ocupa 2 gragmentos + 1 gragmento de descanso, total 3 gragmentos
        //con este codigo lo que hacemos es contar de atras 3 veces para borrar las 3 ultimas fragmentos (11:00, 10:45, 10:30) para asi poder elejir para hacer la reserva unicamente los fragmentos
        //10:00, 10:15.... asi los tres ultimos fragmentos queda claro que en caso de que se haga una reserva quedaran ocupados por ese servicio
        for ($x = 0; $x < count($array_conjunto_huecos_libres); $x++) {
            echo $array_conjunto_huecos_libres[$x]["inicio"] . " = " . $array_conjunto_huecos_libres[$x]["fin"] . " = " . $array_conjunto_huecos_libres[$x]["contador"] . "<br>";
            //guardamos el
            $ultima_reserva_fin = $array_conjunto_huecos_libres[$x]["fin"];

            $num_horas_que_no_borramos = ($array_conjunto_huecos_libres[$x]["contador"] - $duracion_servicio_seleccionado);
            echo "quedan libres " . $num_horas_que_no_borramos;
            echo "<br>";


            $contador = 0;      //$otro_contador
            echo "CONTADOR = " . $contador . "<br>";
            foreach (intervaloHora_alreves($array_conjunto_huecos_libres[$x]["fin"], $array_conjunto_huecos_libres[$x]["inicio"], $intervalo = 15) as $hora) {
                if ($contador + 1 < $duracion_servicio_seleccionado) {
                    $contador++;
                    echo "BORRAMOS = " . $hora . "<br>";
                    array_push($array_horas_para_eliminar, $hora);
                } else {
                    echo "$hora <br>";
                }

            }
            echo "<br><br>";

        }



        echo "<h2>Eliminamos las casillas solitarias... ahi donde hay una casilla soletaria...</h2>";
        $indice_con_un_paso_adelante = 0;
        $siguiente_indice = 0;
        $first_time = true;

        /*
                for ($x = 0; $x < count($array_horario); $x++) {

                    if($first_time == true){
                        if (array_key_exists($x, $array_horario_horas_libres)) {
                            echo $x . " = $array_horario_horas_libres[$x]<br>";
                            $indice_con_un_paso_adelante = $x+1;
                            echo "<br><br>";
                            $first_time = false;
                        }
                    }else{
                        if($indice_con_un_paso_adelante == $x){
                            echo "$x BIEN<br>";
                        }else{
                            echo "$x BORRAR<br>";
                        }



                    }

                }


        */
        //aqui envitamos que se muestren aquellas casillas solitarias que hay en el horario, ejemplo
        //si en el horario de casillas libres nos encontramos de repente una casilla solitaria con la cual no se puede hacer una grupo de casillas como puede ser
        // un ejemplo (10:00, 10:15, 10:30), aqui tenemos un grupo de tres casillas libres, pero si nos entonctramos (10:00, 10:15, 10:30, 11:00, 12:00, 12:15),
        //como se puede observar la casilla 11:00 esta solitaria por lo que el codigo de abajo la borrara de las posibles casillas donde podemos seleccionar para haccer la reserva
        //porque un una unica casilla no cabe ningun servicio
        $hora_para_borrar = 0;
        $primera_vez_v2 = true;
        $contador_v2 = 0;

        for ($x = 0; $x < count($array_horario); $x++) {
            if (array_key_exists($x, $array_horario_horas_libres)) {
                echo $x . " = $array_horario_horas_libres[$x]<br>";
                $contador_v2++;

                if ($primera_vez_v2 == true) {
                    $hora_para_borrar = $array_horario_horas_libres[$x];
                    $primera_vez_v2 = false;
                }
            } else {
                if($contador_v2 == 1){
                    array_push($array_horas_para_eliminar, $hora_para_borrar);
                }
                $primera_vez_v2 = true;
                $contador_v2 = 0;
            }
        }









        /*
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

        */




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

        //filtramos que cuando no haya ninguna reserva, no puede dejarnos seleccionar 22:00h (hora por defecto de fin de dia de trabajo),
        // tiene que dejarnos seleccionar la hora descontando las casillas que ocupa el servicio seleccionado para hacer la reserva
        $contador_sin_reservas = 0;      //$otro_contador
        echo "CONTADOR = " . $contador_sin_reservas . "<br>";

        foreach (intervaloHora_alreves($horario_empleado_fin, $horario_empleado_start, $intervalo = 15) as $hora) {
            if ($contador_sin_reservas +1 < $duracion_servicio_seleccionado) {
                $contador_sin_reservas++;
                echo "BORRAMOS = " . $hora . "<br>";
                array_push($array_horas_para_eliminar, $hora);
            } else {
                echo "$hora <br>";
            }
        }

        $array_horario_horas_libres = array_diff($array_horario, $array_horas_para_eliminar);

        echo "<div id='diaSeleccionado'></div>";
        for ($x = 0; $x < count($array_horario_horas_libres); $x++) {

            echo "<button value='$array_horario_horas_libres[$x]' id='hora_opcion' onclick='show(3);guardar_valor_cookie_hora(this.value)'>" .
                "<span id='hora_numeric'>" . $array_horario_horas_libres[$x] . "</span>" .
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


    $comprobar_duracion_servicio = mysqli_query($conexion, "SELECT duracionServ FROM `servicios` WHERE `nombreServ` = '$servicio'");
    while ($row = mysqli_fetch_array($comprobar_duracion_servicio)) {
        $duracion_servicio_seleccionado = $row[0];
    }
    $duracion_servicio_seleccionado = $duracion_servicio_seleccionado / 15;
    $duracion_servicio_seleccionado = ($duracion_servicio_seleccionado + 1);  //le sumo uno para el descanso de 15min
    echo "Nombre del servicio = " . $servicio . " con duracion de = $duracion_servicio_seleccionado<br>";



    $hora_seleccionada = "09:00";
    $contador_duracion_servicio = 0;
    $pasar = false;
    $hora_fin = '00:00';
    foreach (intervaloHora('05:00', '23:00', $intervalo = 15) as $hora_array) {


        if($hora == $hora_array){
            $pasar = true;
        }

        if($contador_duracion_servicio <= $duracion_servicio_seleccionado && $pasar == true){
            $contador_duracion_servicio++;
            $hora_fin = $hora_array;
        }
    }

    echo "<h2>La hora_fin es = $hora_fin</h2>";






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
        $insertar_reserva = "INSERT INTO citas (fecha, hora, hora_fin, nombreEmpleado, nombreServicio, idCliente) VALUES ('$fecha', '$hora', '$hora_fin', '$empleado', '$servicio', '$idCliente')";
        $conexion->query($insertar_reserva);

        echo "HAS AÑADIDO LA NUEVA CITA";
    }


    //////////////////////////////////////////////////////////////////////
    ////////////COMPRUEBO SI LOS DATOS INTRODUCIDOS COINCIDEN/////////////
    //////////////////////////////////////////////////////////////////////
    /// LA VALIDACION se hace con el telefono, si el telefono existe en la BD pero los otros datos que se han metido son distintos a los datos
    /// que estan asociados al telefono en la BD, me dara alerta diciendo que el nombre y/o coreo estan mal
    //pero si meto un telefono que no existe en la BD con el mismo correo y nombre que si existen en la BD entonces se creara nuevo usuario

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
        $insertar_reserva = "INSERT INTO citas (fecha, hora, hora_fin, nombreEmpleado, nombreServicio, idCliente) VALUES ('$fecha', '$hora', '$hora_fin', '$empleado', '$servicio', '$idCliente')";
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