<?php
require_once("config.php");
$conexion = new mysqli(conexion_host, conexion_user, conexion_pass, conexion_bbdd, conexion_port);

if ($_REQUEST["motivo"] == "eliminar_empleado") {

    $array_empleados_seleccionados = array();

    if (isset($_REQUEST["empleados"])) {
        echo $_REQUEST["empleados"];
        $empleados = $_REQUEST["empleados"];

        $contador_empleados = 0;

        $empleado = "";
        for ($x = 0; $x < strlen($empleados); $x++) {
            //echo $x;
            if ($empleados[$x] == ",") {
                //echo $empleados[$x] ;
                $contador_empleados++;
                array_push($array_empleados_seleccionados, $empleado);
                $empleado = "";
            } else {
                $empleado .= $empleados[$x];
            }
        }

        //print_r($array_empleados_seleccionados);
        //echo "HAS SELECIONADO $contador_empleados EMPLEADOS PARA BORRAR!";

        for ($x = 0; $x < count($array_empleados_seleccionados); $x++) {
            $borrar = "DELETE FROM empleados WHERE idEmpleado = $array_empleados_seleccionados[$x]";     //le pasamas el insert a la variable inset
            $conexion->query($borrar);
        }
    }

} else if ($_REQUEST["motivo"] == "anadir_empleado") {
    if (empty($_REQUEST["nombre"]) || empty($_REQUEST["telefono"]) || empty($_REQUEST["correo"]) || empty($_REQUEST["contrasena"])) {
        echo "Не сте попълнили всички полета!";
    }
    else{
        $nombre = $_REQUEST["nombre"];
        $telefono = $_REQUEST["telefono"];
        $correo = $_REQUEST["correo"];
        $contrasena = $_REQUEST["contrasena"];

        $query = mysqli_query($conexion, "SELECT nombreEmpleado FROM empleados WHERE telefonoEmpleado = $telefono");
        $check_telefono = mysqli_num_rows($query);

        $query = mysqli_query($conexion, "SELECT nombreEmpleado FROM empleados WHERE emailEmpleado = '$correo'");
        $check_correo = mysqli_num_rows($query);

        if ($check_telefono == 0 && $check_correo == 0) {
            $insertar = "INSERT INTO empleados (nombreEmpleado, telefonoEmpleado, emailEmpleado, contrasena) VALUES ('$nombre', '$telefono', '$correo', '$contrasena')";     //le pasamas el insert a la variable inset
            $conexion->query($insertar);
            if ($insertar) {
                echo "<tr><th>Име</th><th>Телефон</th><th>Имейл адрес</th><th>Парола</th><th></th><th></th></tr>";

                $resultado = mysqli_query($conexion, "SELECT * FROM `empleados`");
                while ($row = mysqli_fetch_array($resultado)) {
                    echo "<tr class='trr' id='emple$row[0]'>";
                    echo "<td>$row[1]</td>";
                    echo "<td>$row[2]</td>";
                    echo "<td>$row[3]</td>";
                    echo "<td>";
                    for($x = 0; $x < strlen($row[4]); $x++){
                        echo "•";
                    }
                    echo "</td>";
                    echo "<td><button type='button' id='buton_tabla' onclick='editar_servicio(\"$row[0]\")'><i class='button_imagen'></td>";
                    echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]'></td>";
                    echo "</tr>";
                }

            } else {
                echo "ERROR al intentar insertar el empleado en la BD!";
            }
        } else {
            echo "ГРЕШКА: Въведеният телефон и/или имейл адрес вече пренадлежат на друг служител!";
            mysqli_close($conexion);
        }
    }

} else if ($_REQUEST["motivo"] == "editar_empleado") {


    if (isset($_REQUEST["empleado"])) {
        $empleado = $_REQUEST["empleado"];


        $query = mysqli_query($conexion, "SELECT * FROM empleados WHERE idEmpleado = '$empleado'");
        $contador = mysqli_num_rows($query);

        if ($contador == 1) {
            while ($row = mysqli_fetch_array($query)) {
                echo "Име:<br><br>";
                echo "<input type='text' id='nombre_empleado2' value='$row[1]'><br><br>";

                echo "Телефон:<br><br>";
                echo "<input type='number' id='telefono_empleado2' value='$row[2]'><br><br>";

                echo "Имейл адрес:<br><br>";
                echo "<input type='text' id='correo_empleado2' value='$row[3]'><br><br>";

                echo "Парола:<br><br>";
                echo "<input type='password' id='contrasena_empleado2' value='$row[4]'><br><br>";
            }

        } //SI EL USUARIO EXISTE
        else {
            echo "ERROR";

            mysqli_close($conexion);
        }


    }


} else if ($_REQUEST["motivo"] == "actualizar_empleado") {


    if (empty($_REQUEST["nombre_empleado"]) && empty($_REQUEST("telefono_empleado")) && empty($_REQUEST("correo_empleado"))) {
        echo "No has mandado todos los datos!";
    } else {

        $idEmpleado = $_REQUEST["idEmpleado"];
        $nombre_empleado = $_REQUEST["nombre_empleado"];
        $telefono_empleado = $_REQUEST["telefono_empleado"];
        $correo_empleado = $_REQUEST["correo_empleado"];
        $contrasena_empleado = $_REQUEST["contrasena_empleado"];


        $query = mysqli_query($conexion, "SELECT nombreEmpleado FROM empleados WHERE telefonoEmpleado = $telefono_empleado AND idEmpleado != $idEmpleado");
        $check_telefono = mysqli_num_rows($query);

        $query2 = mysqli_query($conexion, "SELECT nombreEmpleado FROM empleados WHERE emailEmpleado = '$correo_empleado' AND idEmpleado != $idEmpleado");
        $check_correo = mysqli_num_rows($query2);

        if ($check_telefono == 0 && $check_correo == 0) {
            mysqli_query($conexion, "UPDATE `empleados` SET `nombreEmpleado` = '$nombre_empleado', `telefonoEmpleado` = '$telefono_empleado', `emailEmpleado` = '$correo_empleado', `contrasena` = '$contrasena_empleado'  WHERE `idEmpleado` = $idEmpleado");


            echo "<tr><th>Име</th><th>Телефон</th><th>Имейл адрес</th><th>Парола</th><th></th><th></th></tr>";

            $resultado = mysqli_query($conexion, "SELECT * FROM `empleados`");
            while ($row = mysqli_fetch_array($resultado)) {
                echo "<tr class='trr' id='emple$row[0]'>";
                echo "<td>$row[1]</td>";
                echo "<td>$row[2]</td>";
                echo "<td>$row[3]</td>";
                echo "<td>";
                for($x = 0; $x < strlen($row[4]); $x++){
                    echo "•";
                }
                echo "</td>";
                echo "<td><button type='button' id='buton_tabla' onclick='editar_servicio(\"$row[0]\")'><i class='button_imagen'></td>";
                echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]'></td>";
                echo "</tr>";
            }

        } else {
            echo "ГРЕШКА: Въведеният телефон и/или имейл адрес вече пренадлежат с друг механик!";
            mysqli_close($conexion);
        }


    }
} else if ($_REQUEST["motivo"] == "eliminar_categoria") {
    echo "categoria";

    $array_categorias_seleccionados = array();

    if (isset($_REQUEST["categorias"])) {
        echo $_REQUEST["categorias"];
        $categorias = $_REQUEST["categorias"];

        $contador_categorias = 0;

        $categoria = "";
        for ($x = 0; $x < strlen($categorias); $x++) {
            //echo $x;
            if ($categorias[$x] == ",") {
                //echo $empleados[$x] ;
                $contador_categorias++;
                array_push($array_categorias_seleccionados, $categoria);
                $categoria = "";
            } else {
                $categoria .= $categorias[$x];
            }
        }

        print_r($array_categorias_seleccionados);

        echo "HAS SELECIONADO $contador_categorias EMPLEADOS PARA BORRAR!";

        for ($x = 0; $x < count($array_categorias_seleccionados); $x++) {
            $borrar = "DELETE FROM categorias WHERE idCategory = $array_categorias_seleccionados[$x]";     //le pasamas el insert a la variable inset
            $conexion->query($borrar);
        }
    }


} else if ($_REQUEST["motivo"] == "anadir_categoria") {
    if (empty($_REQUEST["categoria"])) {
        echo "Не сте попълнили всички полета!";
    }else{
        $categoria = $_REQUEST["categoria"];
        $query = mysqli_query($conexion, "SELECT * FROM categorias WHERE nameCat = '$categoria'");
        $contador = mysqli_num_rows($query);                                                //lo que devuelve la select lo guardamos en la variable contador,
        //en nuestro caso devolver un 0 si no existe el usuarios y 1 si existe
        $categoria_existente = false;

        //SI EL USUARIO NO EXISTE
        if ($contador == 0) {
            $insertar = "INSERT INTO categorias (idCategory, nameCat) VALUES (NULL, '$categoria')";     //le pasamas el insert a la variable inset
            $conexion->query($insertar);                                                   //aqui es donde realmente insertamos el usuario en la bd            https://www.w3schools.com/php/php_mysql_insert.asp
            $categoria_existente = true;                                                     //una vez insertado el usuario ponemos la variable $usuario_insertado a true
            mysqli_close($conexion);
            echo "Категорията бе добавена успешно";
        } //SI EL USUARIO EXISTE
        else {
            echo "ГРЕШКА: Вече съществува категория с име: <b>" . $categoria . "</b>!";

            mysqli_close($conexion);
        }
    }


} else if ($_REQUEST["motivo"] == "mostrar_categorias") {
    echo "<tr><th>Име</th><th></th></tr>";

    $resultado = mysqli_query($conexion, "SELECT * FROM `categorias`");
    while ($row = mysqli_fetch_array($resultado)) {
        echo "<tr class='trr' id='cat$row[0]'>";
        echo "<td>$row[1]</td>";
        echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]'></td>";
        echo "</tr>";
    }


} else if ($_REQUEST["motivo"] == "eliminar_servicio") {
    $array_servicios_seleccionados = array();

    if (isset($_REQUEST["servicios"]) && $_REQUEST["servicios"] != "") {
        $servicios = $_REQUEST["servicios"];
        echo "ESTOS SON LOS SERVICIOS QUE HAY QUE BORRAR ==>>>  " . $servicios;

        $servicio = "";
        for ($x = 0; $x < strlen($servicios); $x++) {
            //echo $x;
            if ($servicios[$x] == ",") {
                array_push($array_servicios_seleccionados, $servicio);
                $servicio = "";
            } else {
                $servicio .= $servicios[$x];
            }
        }

        print_r($array_servicios_seleccionados);

        for ($x = 0; $x < count($array_servicios_seleccionados); $x++) {
            $borrar = "DELETE FROM servicios WHERE nombreServ = '$array_servicios_seleccionados[$x]'";     //le pasamas el insert a la variable inset
            $conexion->query($borrar);
        }
    }
} else if ($_REQUEST["motivo"] == "anadir_servicio") {
    $array_servicios_seleccionados = array();

    if (empty($_REQUEST["servicio"]) || empty($_REQUEST["duracion"]) || empty($_REQUEST["categoria"])) {
        echo "Не сте попълнили всички полета!";
    }else{
        $servicio = $_REQUEST["servicio"];
        $duracion = $_REQUEST["duracion"];
        $categoria = $_REQUEST["categoria"];

        $query = mysqli_query($conexion, "SELECT * FROM servicios WHERE nombreServ = '$servicio'");
        $contador = mysqli_num_rows($query);                                                //lo que devuelve la select lo guardamos en la variable contador,
        //en nuestro caso devolver un 0 si no existe el usuarios y 1 si existe

        //sacamos el ID de la categoria para pasarsela al insert a la hora de ejecutar la SQL
        $query2 = mysqli_query($conexion, "SELECT idCategory FROM categorias WHERE nameCat = '$categoria'");
        $row = mysqli_fetch_assoc($query2);
        $id_category = $row['idCategory'];

        //SI EL USUARIO NO EXISTE
        if ($contador == 0) {
            $insertar = "INSERT INTO servicios (nombreServ, duracionServ, idCategoria) VALUES ('$servicio', $duracion, $id_category)";     //le pasamas el insert a la variable inset
            $conexion->query($insertar);
            if ($insertar) {
                //aqui es donde realmente insertamos el usuario en la bd            https://www.w3schools.com/php/php_mysql_insert.asp
                echo "Se ha añadido el servicio <b>" . $servicio . "</b> con exito!";
            } else {
                echo "ERROR al intentar insertar el servicio en la tabla!";
            }

        } //SI EL USUARIO EXISTE
        else {
            echo "ГРЕШКА: Вече съществува услуга с име: <b>" . $servicio . "</b>!";


            mysqli_close($conexion);
        }


    }
} else if ($_REQUEST["motivo"] == "mostrar_servicios") {
    echo "<tr><th>Име</th><th>Продължителност</th><th>Категория:</th><th></th><th></th></tr>";

    $resultado = mysqli_query($conexion, "SELECT * FROM `servicios`");
    while ($row = mysqli_fetch_array($resultado)) {
        echo "<tr class='trr' id='serv$row[1]'>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[2]</td>";

        $query2 = mysqli_query($conexion, "SELECT nameCat FROM categorias WHERE idCategory = '$row[3]'");
        $row2 = mysqli_fetch_assoc($query2);
        $id_category = $row2['nameCat'];
        echo "<td>$id_category</td>";

        echo "<td><button type='button' id='buton_tabla' onclick='editar_servicio(\"$row[0]\")'><i class='button_imagen'></td>";
        echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[1]'></td>";
        echo "</tr>";
    }


} else if ($_REQUEST["motivo"] == "editar_servicio") {


    if (isset($_REQUEST["servicio"])) {
        $servicio = $_REQUEST["servicio"];


        $query = mysqli_query($conexion, "SELECT * FROM servicios WHERE idServicio = '$servicio'");
        $contador = mysqli_num_rows($query);

        if ($contador == 1) {


            while ($row = mysqli_fetch_array($query)) {
                echo "Име:<br><br>";
                echo "<input type='text' id='nombre_servicio2' value='$row[1]'><br><br>";

                echo "Продължителност:<br><br>";
                echo "<input type='number' id='duracion_servicio2' value='$row[2]'><br><br>";

                echo "Избери категория:<br><br>";
                echo "<select name='categoria_escogida' id='categoria_escogida2'>";
                $resultado = mysqli_query($conexion, "SELECT * FROM `categorias`");
                while ($row2 = mysqli_fetch_array($resultado)) {
                    if ($row[3] == $row2[0]) {
                        echo "<option value='$row2[1]' selected>" . $row2[1] . "</option>";
                    } else {
                        echo "<option value='$row2[1]'>" . $row2[1] . "</option>";
                    }
                }
                echo "</select>";
            }

        } //SI EL USUARIO EXISTE
        else {
            echo "ГРЕШКА: Вече съществува услуга с име: <b>" . $servicio . "</b>!";


            mysqli_close($conexion);
        }
    }
} else if ($_REQUEST["motivo"] == "actualizar_servicio") {


    if (empty($_REQUEST["nombre_servicio"]) && empty($_REQUEST("duracion_servicio")) && empty($_REQUEST("categoria_servicio"))) {
        echo "No has mandado todos los datos!";
    } else {

        $idServicio = $_REQUEST["idServicio"];
        $nombre_serv = $_REQUEST["nombre_servicio"];
        $duracion_serv = $_REQUEST["duracion_servicio"];
        $categoria_serv = $_REQUEST["categoria_servicio"];

        $query2 = mysqli_query($conexion, "SELECT idCategory FROM categorias WHERE nameCat = '$categoria_serv'");
        $row = mysqli_fetch_assoc($query2);
        $id_category = $row['idCategory'];


        $query = mysqli_query($conexion, "SELECT idServicio FROM servicios WHERE nombreServ = '$nombre_serv' AND idServicio != $idServicio");
        $contador = mysqli_num_rows($query);

        if ($contador == 0) {
            mysqli_query($conexion, "UPDATE `servicios` SET `nombreServ` = '$nombre_serv', `duracionServ` = '$duracion_serv', `idCategoria` = '$id_category' WHERE `idServicio` = $idServicio");

            echo "<tr><th>Име</th><th>Продължителност</th><th>Категория:</th><th></th><th></th></tr>";

            $resultado = mysqli_query($conexion, "SELECT * FROM `servicios`");
            while ($row = mysqli_fetch_array($resultado)) {
                echo "<tr class='trr' id='serv$row[1]'>";
                echo "<td>$row[1]</td>";
                echo "<td>$row[2]</td>";

                $query2 = mysqli_query($conexion, "SELECT nameCat FROM categorias WHERE idCategory = '$row[3]'");
                $row2 = mysqli_fetch_assoc($query2);
                $id_category = $row2['nameCat'];
                echo "<td>$id_category</td>";

                echo "<td><button type='button' id='buton_tabla' onclick='editar_servicio(\"$row[0]\")'><i class='button_imagen'></td>";
                echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[1]'></td>";
                echo "</tr>";
            }

        } else {
            echo "ГРЕШКА: Вече съществува услуга с име: <b>" . $nombre_serv . "</b>!";
            mysqli_close($conexion);
        }
    }
} else if ($_REQUEST["motivo"] == "mostrar_servicios2") {
    if (!empty($_REQUEST["idEmpleado"])) {
        $idEmpleado = $_REQUEST["idEmpleado"];


        echo "<table id=\"tabla\">";
        echo "<tr><td>Да/Не</td><td>Услуга</td><td class='input_numer_precio'>Цена</td></tr>";


        $resultado = mysqli_query($conexion, "SELECT * FROM `servicios`");
        while ($row = mysqli_fetch_array($resultado)) {

            $comprobar_servicios_empleado = mysqli_query($conexion, "SELECT precio FROM servicios_empleados WHERE nombreServ = '$row[1]' AND idEmpleado = $idEmpleado");
            $contador = mysqli_num_rows($comprobar_servicios_empleado);


            //echo $contador;


            if ($contador == 0) {
                echo "<tr><td class='input_ckeckbox_emple'><input type='checkbox' name='servicios[]' value='$row[1]'></td>";
                echo "<td class='input_ckeckbox_nobre_ser' value='$row[1]'>" . $row[1] . "</td>";
                echo "<td class='input_numer_precio'><input id='precio_$row[1]' type='number' name='precios[]' value='0.00'></td>";
                echo "</tr>";
            } //si el empleado tiene ese servicio
            else {
                echo "<tr><td class='input_ckeckbox_emple'><input type='checkbox' name='servicios[]' value='$row[1]' checked></td>";
                echo "<td class='input_ckeckbox_nobre_ser' value='$row[1]'>" . $row[1] . "</td>";

                while ($row2 = mysqli_fetch_array($comprobar_servicios_empleado)) {
                    echo "<td class='input_numer_precio'><input id='precio_$row[1]' type='number' name='precios[]' value='$row2[0]'></td>";
                }
                echo "</tr>";
            }
        }
        echo "</table><br><br>";
    }


} else if ($_REQUEST["motivo"] == "actualizar_servicios_asignados_emple") {
    if (empty($_REQUEST["array_servicios_asignados_nombre"]) && empty($_REQUEST["array_servicios_asignados_precio"]) && empty($_REQUEST["idEmpleado"])) {

    } else {
        $array_servicios_asignados_nombre = $_REQUEST["array_servicios_asignados_nombre"];
        $array_servicios_asignados_precio = $_REQUEST["array_servicios_asignados_precio"];
        $idEmpleadooo = $_REQUEST["idEmpleado"];

        $array_servicios_asignados_nombre = substr($array_servicios_asignados_nombre, 0, -2);
        $array_servicios_asignados_precio = substr($array_servicios_asignados_precio, 0, -2);

        $array_servicios_asignados_nombre = (explode("--,", $array_servicios_asignados_nombre));
        $array_servicios_asignados_precio = (explode("--,", $array_servicios_asignados_precio));


        //borramos cuando quitamos el checked de un servicio que ya estaba insertado en la BD
        $array_servicos_en_bd = [];
        $resultado = mysqli_query($conexion, "SELECT `nombreServ` FROM servicios_empleados WHERE idEmpleado = '$idEmpleadooo'");

        while ($servicio_bd = mysqli_fetch_array($resultado)) {
            array_push($array_servicos_en_bd, $servicio_bd[0]);
        }

        $result = array_diff($array_servicos_en_bd, $array_servicios_asignados_nombre);
        print_r($result);

        if (count($result) != 0) {
            foreach ($result as $servicio_para_borrar) {
                $borrar = "DELETE FROM servicios_empleados WHERE `idEmpleado` = '$idEmpleadooo' AND `nombreServ` = '$servicio_para_borrar'";     //le pasamas el insert a la variable inset
                $conexion->query($borrar);
            }
        }


        for ($x = 0; $x < count($array_servicios_asignados_nombre); $x++) {

            $query = mysqli_query($conexion, "SELECT * FROM servicios_empleados WHERE idEmpleado = '$idEmpleadooo' && nombreServ ='$array_servicios_asignados_nombre[$x]'");
            $contador = mysqli_num_rows($query);

            if ($contador == 1) {
                //si esta se hace update
                //echo $array_servicios_asignados_nombre[$x] . " SI ESTA con precio " . $array_servicios_asignados_precio[$x];
                mysqli_query($conexion, "UPDATE `servicios_empleados` SET `precio` = '$array_servicios_asignados_precio[$x]' WHERE `nombreServ` = '$array_servicios_asignados_nombre[$x]' AND `idEmpleado` = '$idEmpleadooo'");
            } else {
                //si no esta se hace insert
                echo $idEmpleadooo;
                //echo $array_servicios_asignados_nombre[$x] . " NO ESTA con precio " . $array_servicios_asignados_precio[$x];
                mysqli_query($conexion, "INSERT INTO `servicios_empleados` (`idServEmple`, `precio`, `idEmpleado`, `nombreServ`) VALUES ('0', '$array_servicios_asignados_precio[$x]', '$idEmpleadooo', '$array_servicios_asignados_nombre[$x]')");
                ECHO "ASD";
            }
        }
    }
} else if ($_REQUEST["motivo"] == "mostrar_horarios") {
    if (!empty($_REQUEST["idEmpleado"])) {
        $idEmpleado = $_REQUEST["idEmpleado"];
        $array_dias_semana = array(
            //"Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"
            "Понеделник", "Вторник", "Сряда", "Четвъртък", "Петък", "Събота", "Неделя"
        );
        $array_horario_primera_vez = array(
            "08:00", "08:15", "08:30", "08:45",
            "09:00", "09:15", "09:30", "09:45",
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
            "21:00", "21:15", "21:30", "21:45", "22:00"
        );


        $comprobar_horarios_empleado = mysqli_query($conexion, "SELECT `horaStart`, `horaFin`, `diaSemana` FROM horario_semanal WHERE idEmple = $idEmpleado");
        $contador123123 = mysqli_num_rows($comprobar_horarios_empleado);

        //si no estan asignados los horarios para el empleado
        if ($contador123123 == 0) {
            for ($y = 0; $y < count($array_dias_semana); $y++) {
                echo "<span style=\"width:130px; display:inline-block\"><b>$array_dias_semana[$y]</b></span>";

                echo "<span style=\"width:100px; display:inline-block\">";
                echo "<select style='width: 80px; display: inline-block' id='start_$array_dias_semana[$y]'>";
                for ($x = 0; $x < count($array_horario_primera_vez); $x++) {
                    echo "<option value='$array_horario_primera_vez[$x]'>$array_horario_primera_vez[$x]</option>";
                }
                echo "</select>";
                echo "</span>";
                echo "<span style=\"width:37px; display:inline-block\">до</span>";


                echo "<span style=\"width:100px; display:inline-block\">";
                echo "<select style='width: 80px; display: inline-block' id='fin_$array_dias_semana[$y]'>";
                for ($x = count($array_horario_primera_vez) - 1; $x >= 0; $x--) {
                    echo "<option value='$array_horario_primera_vez[$x]'>$array_horario_primera_vez[$x]</option>";
                }
                echo "</select>";
                echo "</span>";
                echo "<br><br>";
            }
            //si los horarios estan ya asignados los mostramos de la BD
        } else {
            while ($datos = mysqli_fetch_array($comprobar_horarios_empleado)) {
                $datos[0] = substr($datos[0], 0, -10);
                $datos[1] = substr($datos[1], 0, -10);

                for ($y = 0; $y < count($array_dias_semana); $y++) {
                    if ($datos[2] == $array_dias_semana[$y]) {
                        echo "<span style=\"width:130px; display:inline-block\"><b>$array_dias_semana[$y]</b></span>";
                        echo "<span style=\"width:100px; display:inline-block\">";
                        echo "<select style='width: 80px; display: inline-block' id='start_$array_dias_semana[$y]'>";

                        for ($x = 0; $x < count($array_horario_primera_vez); $x++) {
                            if ($array_horario_primera_vez[$x] == $datos[0]) {
                                echo "<option value='$array_horario_primera_vez[$x]' selected>$array_horario_primera_vez[$x]</option>";
                            } else {
                                echo "<option value='$array_horario_primera_vez[$x]'>$array_horario_primera_vez[$x]</option>";
                            }
                        }
                        echo "</select>";
                        echo "</span>";
                        echo "<span style=\"width:37px; display:inline-block\">до</span>";

                        echo "<span style=\"width:100px; display:inline-block\">";
                        echo "<select style='width: 80px; display: inline-block' id='fin_$array_dias_semana[$y]'>";
                        for ($x = count($array_horario_primera_vez) - 1; $x >= 0; $x--) {
                            if ($array_horario_primera_vez[$x] == $datos[1]) {
                                echo "<option value='$array_horario_primera_vez[$x]' selected>$array_horario_primera_vez[$x]</option>";
                            } else {
                                echo "<option value='$array_horario_primera_vez[$x]'>$array_horario_primera_vez[$x]</option>";
                            }
                        }
                        echo "</select>";
                        echo "</span>";
                        echo "<br><br>";
                    }
                }
            }
        }
    }
} else if ($_REQUEST["motivo"] == "anadir_horarios") {
    if (empty($_REQUEST["idEmpleado"]) && empty($_REQUEST["array_horarios_start"]) && empty($_REQUEST["array_horarios_fin"])) {


    } else {
        $idEmpleado = $_REQUEST["idEmpleado"];
        $array_horarios_start_js = $_REQUEST["array_horarios_start"];
        $array_horarios_fin_js = $_REQUEST["array_horarios_fin"];


        $array_horarios_start_convertido = explode(",", $array_horarios_start_js);
        $array_horarios_fin_convertido = explode(",", $array_horarios_fin_js);


        //print_r($array_horarios_start_convertido);
        //print_r($array_horarios_fin_convertido);


        //comprobamos si ya estan asignados los hroarios para el empleado
        $comprobar_horarios_empleado = mysqli_query($conexion, "SELECT `horaStart`, `horaFin`, `diaSemana` FROM horario_semanal WHERE idEmple = $idEmpleado");
        $contador123123 = mysqli_num_rows($comprobar_horarios_empleado);

        //si no estan asignados los horarios para el empleado
        if ($contador123123 == 0) {
            for ($d = 0; $d < count($array_horarios_start_convertido); $d++) {
                $dia = "";
                echo count($array_horarios_start_convertido);

                if ($d == 0) {
                    $dia = "Понеделник";
                } elseif ($d == 1) {
                    $dia = "Вторник";
                } elseif ($d == 2) {
                    $dia = "Сряда";
                } elseif ($d == 3) {
                    $dia = "Четвъртък";
                } elseif ($d == 4) {
                    $dia = "Петък";
                } elseif ($d == 5) {
                    $dia = "Събота";
                } elseif ($d == 6) {
                    $dia = "Неделя";
                }

                mysqli_query($conexion, "INSERT INTO `horario_semanal` (`idHorarioSem`, `horaStart`, `horaFin`, `diaSemana`, `idEmple`) VALUES (NULL, '$array_horarios_start_convertido[$d]', '$array_horarios_fin_convertido[$d]', '$dia', '$idEmpleado')");
            }
        } else {
            for ($d = 0; $d < count($array_horarios_start_convertido); $d++) {
                $dia = "";

                if ($d == 0) {
                    $dia = "Понеделник";
                } elseif ($d == 1) {
                    $dia = "Вторник";
                } elseif ($d == 2) {
                    $dia = "Сряда";
                } elseif ($d == 3) {
                    $dia = "Четвъртък";
                } elseif ($d == 4) {
                    $dia = "Петък";
                } elseif ($d == 5) {
                    $dia = "Събота";
                } elseif ($d == 6) {
                    $dia = "Неделя";
                }
                //echo "$d = $dia = update => $array_horarios_start_convertido[$d]<br>";
                mysqli_query($conexion, "UPDATE `horario_semanal` SET `horaStart` = '$array_horarios_start_convertido[$d]', `horaFin` = '$array_horarios_fin_convertido[$d]' WHERE `idEmple` = '$idEmpleado' AND `diaSemana` = '$dia'");
            }
        }
    }
} else if ($_REQUEST["motivo"] == "eliminar_cliente") {
    if (isset($_REQUEST["clientes"]) && $_REQUEST["clientes"] != "") {
        $clientes = json_decode($_REQUEST["clientes"]);
        echo "ESTOS SON LOS CLIENTES QUE HAY QUE BORRAR ==>>>  ";

        echo count($clientes) . "<br>";
        print_r($clientes);

        for ($x = 0; $x < count($clientes); $x++) {
            $id = $clientes[$x][0];
            $tel = $clientes[$x][1];
            mysqli_query($conexion, "DELETE FROM `clientes` WHERE `idCliente` = '$id' AND `telefonoCliente` = '$tel'");
        }


    }
} else if ($_REQUEST["motivo"] == "editar_cliente") {


    if (isset($_REQUEST["cliente"])) {
        $cliente = $_REQUEST["cliente"];


        $query = mysqli_query($conexion, "SELECT * FROM clientes WHERE idCliente = '$cliente'");
        $contador = mysqli_num_rows($query);                                                //lo que devuelve la select lo guardamos en la variable contador,
        //en nuestro caso devolver un 0 si no existe el usuarios y 1 si existe

        //SI EL USUARIO NO EXISTE
        if ($contador == 1) {


            while ($row = mysqli_fetch_array($query)) {
                echo "Име:<br><br>";
                echo "<input type='text' id='nombre_cliente2' value='$row[1]'><br><br>";

                echo "Телефон:<br><br>";
                echo "<input type='number' id='telefono_cliente2' value='$row[2]'><br><br>";

                echo "Имейл адрес:<br><br>";
                echo "<input type='text' id='correo_electronico_cliente2' value='$row[3]'>";
            }

        }
    }
} else if ($_REQUEST["motivo"] == "actualizar_cliente") {

    if (empty($_REQUEST["nombre_cliente"]) && empty($_REQUEST("telefono_cliente")) && empty($_REQUEST("correo_electronico"))) {
        echo "No has mandado todos los datos!";
    } else {

        $idCliente = $_REQUEST["idCliente"];
        $nombre_cliente = $_REQUEST["nombre_cliente"];
        $telefono_cliente = $_REQUEST["telefono_cliente"];
        $correo_electronico = $_REQUEST["correo_electronico"];

        $query = mysqli_query($conexion, "SELECT idCliente FROM clientes WHERE telefonoCliente = '$telefono_cliente' AND idCliente != $idCliente");
        $contador = mysqli_num_rows($query);

        if ($contador == 0) {
            mysqli_query($conexion, "UPDATE `clientes` SET `nombreCliente` = '$nombre_cliente', `telefonoCliente` = '$telefono_cliente', `correoCliente` = '$correo_electronico' WHERE `idCliente` = $idCliente");


            echo "<tr><th>Име</th><th>Телефон</th><th>Имейл адрес</th><th>Парола</th><th></th><th></th></tr>";

            $resultado = mysqli_query($conexion, "SELECT * FROM `clientes`");
            while ($row = mysqli_fetch_array($resultado)) {
                echo "<tr class='trr' id='cliente$row[0]'>";
                echo "<td>$row[1]</td>";
                echo "<td>$row[2]</td>";
                echo "<td>$row[3]</td>";
                echo "<td>";
                for($x = 0; $x < strlen($row[4]); $x++){
                    echo "•";
                }

                echo "<td><button type='button' id='buton_tabla' onclick='editar_cliente(\"$row[0]\")'><i class='button_imagen'></td>";
                echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
                echo "</tr>";
            }
        } else {
            echo "ГРЕШКА: Въведеният телефон <b>" . $telefono_cliente . "</b>, вече пренадлежат на друг клиент!";
            mysqli_close($conexion);
        }


    }
} else if ($_REQUEST["motivo"] == "anadir_cliente") {
    $array_servicios_seleccionados = array();

    if (empty($_REQUEST["nombre"]) || empty($_REQUEST["telefono"]) || empty($_REQUEST["correo_electronico"])) {
        echo "Не сте попълнили всички полета!";
    } else {
        $nombre = $_REQUEST["nombre"];
        $telefono = $_REQUEST["telefono"];
        $correo_electronico = $_REQUEST["correo_electronico"];
        $nombre_check = true;
        $correo_check = true;
        $telefono_check = true;
        /*
                $resultado2 = mysqli_query($conexion, "SELECT * from clientes WHERE nombreCliente = '$nombre' AND telefonoCliente = '$telefono' AND correoCliente = '$correo_electronico'");
                $contador2 = mysqli_num_rows($resultado2);
                if ($contador2 == 0) {
                    echo "NO EXISTE!";
                    //INSERTARMOS EL CLIENTE
                    $insertar_reserva = "INSERT INTO clientes (nombreCliente, telefonoCliente, correoCliente) VALUES ('$nombre', '$telefono', '$correo_electronico')";
                    $conexion->query($insertar_reserva);

                    echo "HAS AÑADIDO EL NUEVO USUARIO";
                }

        */

        //////////////////////////////////////////////////////////////////////
        ////////////COMPRUEBO SI LOS DATOS INTRODUCIDOS COINCIDEN/////////////
        //////////////////////////////////////////////////////////////////////


        $resultado = mysqli_query($conexion, "SELECT nombreCliente, telefonoCliente, correoCliente from clientes WHERE nombreCliente = '$nombre' AND telefonoCliente = '$telefono' AND correoCliente = '$correo_electronico'");
        $contador1 = mysqli_num_rows($resultado);
        if ($contador1 == 1) {
            echo "Вече съществува клиент с въведените от Вас данни!";
        } else {
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
                    if ($correo_electronico != $row[1]) {
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
                $insertar = "INSERT INTO clientes (nombreCliente, telefonoCliente, correoCliente) VALUES ('$nombre', '$telefono', '$correo_electronico')";
                $conexion->query($insertar);
                echo "Se ha añadido el cliente <b>" . $nombre . "</b> con exito!<br><br>";

            } else {
                //correo mal escrito
                if ($nombre_check == true && $correo_check == false) {
                    //español
                    //echo "El telefono: " . $telefono . " ya esta asociado con otro correo electronico.<br><br>Presione <b>Actualizar</b> si desea actualizar el correo electronico, o presiones </b>Cancelar</b> para editar los datos ingresados";
                    echo "Въведеният телефон " . $telefono . " е свързан с друг имейл адрес!<br><br>Натиснете <b>Обнови</b>, ако искате да обновите старият имейл адрес с новият въведен от Вас сега, или натиснете <b>Отказ</b>, за да коригирате въведените данни";
                    //nombre mal escrito
                } else if ($nombre_check == false && $correo_check == false) {
                    //español
                    //echo "El telefono: " . $telefono . " ya esta asociado con otro nombre y correo electronico.<br><br>Presione <b>Actualizar</b> si desea actualizar su nombre y correo electronico, o presiones <b>Cancelar</b> para editar los datos ingresados";
                    echo "Въведеният телефон " . $telefono . " е свързан с друго име и имейл адрес.<br><br>Натиснете <b>Обнови</b>, ако искате да обновите старият имейл адрес и старото име с въведените от Вас сега, или натиснете <b>Отказ</b>, за да коригирате въведените данни";
                    //nombre y correo mal escritos
                } else if ($nombre_check == false && $correo_check == true) {
                    //español
                    //echo "El telefono: " . $telefono . " ya esta asociado con otro nombre.<br><br>Presione <b>Actualizar</b> si desea actualizar su nombre, o presiones <b>Cancelar</b> para editar los datos ingresados";
                    echo "Въведеният телефон " . $telefono . " е свързан с друго име<br><br>Натиснете <b>Обнови</b>, ако искате да обновите старото име с новото въведено от Вас сега, или натиснете <b>Отказ</b>, за да коригирате въведените данни";
                }
            }
        }
    }
} else if ($_REQUEST["motivo"] == "mostrar_clientes") {
    echo "<tr><th>Име</th><th>Телефон</th><th>Имейл адрес</th><th>Парола</th><th></th><th></th></tr>";

    $resultado = mysqli_query($conexion, "SELECT * FROM `clientes`");
    while ($row = mysqli_fetch_array($resultado)) {
        echo "<tr class='trr' id='cliente$row[0]'>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[2]</td>";
        echo "<td>$row[3]</td>";
        echo "<td>";
        for($x = 0; $x < strlen($row[4]); $x++){
            echo "•";
        }

        echo "<td><button type='button' id='buton_tabla' onclick='editar_cliente(\"$row[0]\")'><i class='button_imagen'></td>";
        echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
        echo "</tr>";
    }


} else if ($_REQUEST["motivo"] == "actualizar_cliente_de_alerta") {

    if (empty($_REQUEST["nombre_cliente"]) && empty($_REQUEST("telefono_cliente")) && empty($_REQUEST("correo_electronico"))) {
        echo "No has mandado todos los datos!";
    } else {
        $nombre_cliente = $_REQUEST["nombre_cliente"];
        $telefono_cliente = $_REQUEST["telefono_cliente"];
        $correo_electronico = $_REQUEST["correo_electronico"];

        mysqli_query($conexion, "UPDATE `clientes` SET `nombreCliente` = '$nombre_cliente', `correoCliente` = '$correo_electronico' WHERE `telefonoCliente` = $telefono_cliente");


        echo "<tr><th>Име</th><th>Телефон</th><th>Имейл адрес</th><th>Парола</th><th></th><th></th></tr>";

        $resultado = mysqli_query($conexion, "SELECT * FROM `clientes`");
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<tr class='trr' id='cliente$row[0]'>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td>";
            for($x = 0; $x < strlen($row[4]); $x++){
                echo "•";
            }

            echo "<td><button type='button' id='buton_tabla' onclick='editar_cliente(\"$row[0]\")'><i class='button_imagen'></td>";
            echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
            echo "</tr>";
        }
    }
} else if ($_REQUEST["motivo"] == "buscador") {
    if (!empty($_REQUEST["cadena"])) {
        $cadena_a_buscar = $_REQUEST["cadena"];
        $tipo_de_valor_a_buscar = $_REQUEST["tipo_de_valor"];

        if ($tipo_de_valor_a_buscar == "Telefono") {
            $tipo_de_valor_a_buscar = "telefonoCliente";
        } elseif ($tipo_de_valor_a_buscar == "Nombre") {
            $tipo_de_valor_a_buscar = "nombreCliente";
        } else {
            $tipo_de_valor_a_buscar = "correoCliente";
        }

        echo "<tr><th>Име</th><th>Телефон</th><th>Имейл адрес</th><th>Парола</th><th></th><th></th></tr>";

        $resultado = mysqli_query($conexion, "SELECT * FROM clientes WHERE $tipo_de_valor_a_buscar = '$cadena_a_buscar'");
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<tr class='trr' id='cliente$row[0]'>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td>";
            for($x = 0; $x < strlen($row[4]); $x++){
                echo "•";
            }

            echo "<td><button type='button' id='buton_tabla' onclick='editar_cliente(\"$row[0]\")'><i class='button_imagen'></td>";
            echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><th>Име</th><th>Телефон</th><th>Имейл адрес</th><th>Парола</th><th></th><th></th></tr>";
        $resultado = mysqli_query($conexion, "SELECT * FROM clientes");
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<tr class='trr' id='cliente$row[0]'>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td>";
            for($x = 0; $x < strlen($row[4]); $x++){
                echo "•";
            }

            echo "<td><button type='button' id='buton_tabla' onclick='editar_cliente(\"$row[0]\")'><i class='button_imagen'></td>";
            echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
            echo "</tr>";
        }
    }
} else if ($_REQUEST["motivo"] == "editar_cita") {
    if (isset($_REQUEST["cita"])) {
        $cita = $_REQUEST["cita"];


        $query = mysqli_query($conexion, "SELECT * FROM citas WHERE idCita = '$cita'");
        $contador = mysqli_num_rows($query);

        if ($contador == 1) {
            while ($row = mysqli_fetch_array($query)) {
                $hora_inicio = substr($row[2], 0, 5);
                $hora_fin = substr($row[3], 0, 5);
                echo "<div id=\"titulo\"><h2>Редактиране на резервация</h2></div>";



                echo "Категория:<br><br>";
                echo "<select id='categoria' name='categoria_escogida' onchange='sacar_servicios_por_categoria(1)'>";
                echo "<option>Изберете категория</option>";
                $resultado = mysqli_query($conexion, "SELECT * FROM `categorias`");
                while ($roww = mysqli_fetch_array($resultado)) {
                    echo "<option value='$roww[1]'>" . $roww[1] . "</option>";
                }
                echo "</select><br><br>";

                echo "Услуга:<br><br>";
                echo "<div id='servicios'></div>";
                echo "<select id='servicio' name='categoria_escogida' onchange=\"sacar_empleados_por_servicio(1)\">";
                echo "<option>Изберете услуга</option>";
                echo "</select><br><br>";



                echo "Служител:<br><br>";
                echo "<div id='empleados'></div>";
                echo "<select id='empleado'>";
                echo "<option>Изберете служител</option>";
                echo "</select><br><br>";

                echo "Дата:<br><br>";
                echo "<input type='date' id='fecha2' value=''><br><br>";


                echo "<div id=\"title-hora-cita\">Час:<br><br></div>";
                echo "<div id=\"scroll-content5\"></div>";


                echo "<div id=\"alert-footer\">";
                echo "<span id=\"comprobar_horario\" style=\"float: left;\" onclick='fecha_cambiada_dame_horario(\"$cita\", \"$hora_inicio\", \"$hora_fin\")'>Проверка на часове</span>";
                echo "<span id=\"alert_cancelar5\" onclick='cerrar_windows()'>Отказ</span>";
                echo "</div>";
            }

        }
    }
} else if($_REQUEST["motivo"] == "editar_cita_dame_horario"){
    $cita = $_REQUEST["cita"];
    $servicio = $_REQUEST["servicio"];
    $empleado = $_REQUEST["empleado"];
    $fecha = $_REQUEST["fecha"];
    $hora_inicio = $_REQUEST["hora_inicio"];
    $hora_fin = $_REQUEST["hora_fin"];


    //var_dump($_REQUEST);

    editar_cita($fecha, $empleado, $servicio, $hora_inicio, $hora_fin, $cita);
    //fecha, empleado, servicio, hora inicio cita, hora fin cita
    // para poder decirle que desde la hora de inicio hasta la hora de fin que esas casillas si que las muestre
}
else if ($_REQUEST["motivo"] == "eliminar_cita") {
    if (isset($_REQUEST["citas"]) && $_REQUEST["citas"] != "") {
        $citas = json_decode($_REQUEST["citas"]);
        echo "ESTOS SON LAS CITAS QUE HAY QUE BORRAR ==>>>  ";

        echo count($citas) . "<br>";
        print_r($citas);

        for ($x = 0; $x < count($citas); $x++) {
            $id = $citas[$x][0];
            mysqli_query($conexion, "DELETE FROM `citas` WHERE `idCita` = '$id'");
        }
    }
} else if ($_REQUEST["motivo"] == "buscador_citas") {
    if (!empty($_REQUEST["cadena"])) {
        $cadena_a_buscar = $_REQUEST["cadena"];
        $tipo_de_valor_a_buscar = $_REQUEST["tipo_de_valor"];
        $array_ids_clientes = array();

        if ($tipo_de_valor_a_buscar == "Fecha") {
            $tipo_de_valor_a_buscar = "fecha";
        } elseif ($tipo_de_valor_a_buscar == "Nombre empleado") {
            $tipo_de_valor_a_buscar = "nombreEmpleado";
        } elseif ($tipo_de_valor_a_buscar == "Servicio") {
            $tipo_de_valor_a_buscar = "nombreServicio";
        } elseif ($tipo_de_valor_a_buscar == "Nombre cliente") {

            //sacamos todos los ids de todos los clientes que tengan el nombre tecleado y los metemos en un array que vamos a recorrer despues
            $nombre = mysqli_query($conexion, "SELECT * FROM clientes WHERE nombreCliente = '$cadena_a_buscar'");
            while ($row2 = mysqli_fetch_array($nombre)) {
                array_push($array_ids_clientes, $row2[0]);
            }
        }

        echo "<tr><th>Дата</th><th>Час от</th><th>Час до</th><th>Име служител</th><th>Услуга</th><th>Име клиент</th><th>Редак. резервация</th><th>Редак. фактура</th><th>Изтрий резервация</th></tr>";

        //si estamos buscando por nombre de cliente la busqueda en la tabla de citas se realiza de otra forma
        //si buscamos juan y en la tabla de clientes tenemos 10 usuarios con el nombre juan, la tabla nos devuelve todos los ids de todos los clientes con ese nombre
        //despues por cada id de cliente realizamos una busqueda en la tabla de citas y sacamos las citas que tiene ese id
        //finalmente se muestran todas las citas de todos los usuarios con ese nombre
        if ($tipo_de_valor_a_buscar == "Nombre cliente") {
            for ($x = 0; $x < count($array_ids_clientes); $x++) {
                $resultado = mysqli_query($conexion, "SELECT * FROM citas WHERE idCliente = $array_ids_clientes[$x]");
                $contador = mysqli_num_rows($resultado);

                while ($row3 = mysqli_fetch_array($resultado)) {
                    echo "<tr class='trr' id='cita$row3[0]'>";
                    echo "<td>$row3[1]</td>";
                    echo "<td>" . substr($row3[2], 0, 5) . "</td>";
                    echo "<td>" . substr($row3[3], 0, 5) . "</td>";
                    echo "<td>$row3[4]</td>";
                    echo "<td>$row3[5]</td>";

                    $resultado2 = mysqli_query($conexion, "SELECT * FROM `clientes` WHERE `idCliente` = $row3[6]");
                    if (mysqli_num_rows($resultado2) == 0) {
                        echo "<td>Cliente borrado</td>";
                    } else {
                        $row2 = mysqli_fetch_array($resultado2);
                        echo "<td>$row2[1]</td>";
                    }

                    echo "<td><button type='button' id='buton_tabla' onclick='editar_cita(\"$row3[0]\")'><i class='button_imagen'></td>";
                    echo "<td><button type='button' id='buton_tabla' onclick='editar_hoja(\"$row3[0]\")'><i class='button_imagen2'></td>";
                    echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row3[0]|.|$row3[2]'></td>";
                    echo "</tr>";
                }
            }
        } elseif($tipo_de_valor_a_buscar == "VIN" || $tipo_de_valor_a_buscar == "Matricula"){
            //si estamos buscando por VIN o MATRICULA

            if($tipo_de_valor_a_buscar == "VIN"){
                $tipo_de_valor_a_buscar = "vin";
            }else{
                $tipo_de_valor_a_buscar = "matricula";
            }
            $resultado = mysqli_query($conexion, "SELECT * FROM hojas_de_trabajo WHERE $tipo_de_valor_a_buscar = '$cadena_a_buscar'");
            while ($row = mysqli_fetch_array($resultado)) {

                //echo "EXISTE - $row[22]";

                $resultado2 = mysqli_query($conexion, "SELECT * FROM citas WHERE idCita = $row[21]");
                $contador = mysqli_num_rows($resultado2);

                while ($row3 = mysqli_fetch_array($resultado2)) {
                    echo "<tr class='trr' id='cita$row3[0]'>";
                    echo "<td>$row3[1]</td>";
                    echo "<td>" . substr($row3[2], 0, 5) . "</td>";
                    echo "<td>" . substr($row3[3], 0, 5) . "</td>";
                    echo "<td>$row3[4]</td>";
                    echo "<td>$row3[5]</td>";

                    $resultado3 = mysqli_query($conexion, "SELECT * FROM `clientes` WHERE `idCliente` = $row3[6]");
                    if (mysqli_num_rows($resultado3) == 0) {
                        echo "<td>Изтрит клиент</td>";
                    } else {
                        $row4 = mysqli_fetch_array($resultado3);
                        echo "<td>$row4[1]</td>";
                    }

                    echo "<td><button type='button' id='buton_tabla' onclick='editar_cita(\"$row3[0]\")'><i class='button_imagen'></td>";
                    echo "<td><button type='button' id='buton_tabla' onclick='editar_hoja(\"$row3[0]\")'><i class='button_imagen2'></td>";
                    echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row3[0]|.|$row3[2]'></td>";
                    echo "</tr>";
                }
            }
        }
        else {
            //si estamos buscando por fecha, nombre de empleado o servicio entra en este else
            $resultado = mysqli_query($conexion, "SELECT * FROM citas WHERE $tipo_de_valor_a_buscar = '$cadena_a_buscar'");
            while ($row = mysqli_fetch_array($resultado)) {
                echo "<tr class='trr' id='cita$row[0]'>";
                echo "<td>$row[1]</td>";
                echo "<td>" . substr($row[2], 0, 5) . "</td>";
                echo "<td>" . substr($row[3], 0, 5) . "</td>";
                echo "<td>$row[4]</td>";
                echo "<td>$row[5]</td>";

                $resultado2 = mysqli_query($conexion, "SELECT * FROM `clientes` WHERE `idCliente` = $row[6]");
                if (mysqli_num_rows($resultado2) == 0) {
                    echo "<td>Изтрит клиент</td>";
                } else {
                    $row2 = mysqli_fetch_array($resultado2);
                    echo "<td>$row2[1]</td>";
                }
                echo "<td><button type='button' id='buton_tabla' onclick='editar_cita(\"$row[0]\")'><i class='button_imagen'></td>";
                echo "<td><button type='button' id='buton_tabla' onclick='editar_hoja(\"$row[0]\")'><i class='button_imagen2'></td>";
                echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
                echo "</tr>";
            }
        }
    } else {
        echo "<tr><th>Fecha</th><th>Hora inicio</th><th>Hora fin</th><th>Nombre empleado</th><th>Servicio</th><th>Nombre cliente</th><th>Editar cita</th><th>Editar hoja</th><th>Borrar cita</th></tr>";
        $resultado = mysqli_query($conexion, "SELECT * FROM citas");
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<tr class='trr' id='cita$row[0]'>";
            echo "<td>$row[1]</td>";
            echo "<td>" . substr($row[2], 0, 5) . "</td>";
            echo "<td>" . substr($row[3], 0, 5) . "</td>";
            echo "<td>$row[4]</td>";
            echo "<td>$row[5]</td>";

            $resultado2 = mysqli_query($conexion, "SELECT * FROM `clientes` WHERE `idCliente` = $row[6]");
            if (mysqli_num_rows($resultado2) == 0) {
                echo "<td>Изтрит клиент</td>";
            } else {
                $row2 = mysqli_fetch_array($resultado2);
                echo "<td>$row2[1]</td>";
            }
            echo "<td><button type='button' id='buton_tabla' onclick='editar_cita(\"$row[0]\")'><i class='button_imagen'></td>";
            echo "<td><button type='button' id='buton_tabla' onclick='editar_hoja(\"$row[0]\")'><i class='button_imagen2'></td>";
            echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
            echo "</tr>";
        }
    }
} else if ($_REQUEST["motivo"] == "actualizar_cliente_desde_resrv_cita") {

    if (empty($_REQUEST["nombre_cliente"]) && empty($_REQUEST("telefono_cliente")) && empty($_REQUEST("correo_electronico"))) {
        echo "No has mandado todos los datos!";
    } else {
        $nombre_cliente = $_REQUEST["nombre_cliente"];
        $telefono_cliente = $_REQUEST["telefono_cliente"];
        $correo_electronico = $_REQUEST["correo_electronico"];

        mysqli_query($conexion, "UPDATE `clientes` SET `correoCliente` = '$correo_electronico' WHERE `telefonoCliente` = $telefono_cliente");


        echo "Cliente actualizado";
    }
} else if ($_REQUEST["motivo"] == "editar_hoja") {
    if (isset($_REQUEST["cita"])) {
        $cita = $_REQUEST["cita"];

        $query = mysqli_query($conexion, "SELECT * FROM hojas_de_trabajo WHERE idCita = '$cita'");
        $contador = mysqli_num_rows($query);


        //dian - esto se visualiza en la seccion de citas cuando pulsas sobre el buton de la hoja de trabajo
        if ($contador == 1) {
            while ($row = mysqli_fetch_array($query)) {
                echo "<div id=\"datos_taller\">";
                echo "<div style=\"width:120px; float: left; padding-top: 16px;\">";
                echo "<img src=\"design/logo.jpg\" width=\"200\">";
                echo "</div>";
                echo "<div style=\"width:228px; float: right; margin-top: -30px;\">";
                echo "<div style=\"line-height: 23pt; font-weight: bold; font-size: 18px;\">Авто Павлов 94 ЕООД</div>";
                echo "<div style=\"line-height: 23pt;\">Бул. Източен 22, Пловдив</div>";
                echo "<div style=\"line-height: 23pt;\">Булстат 205269686</div>";
                echo "<div style=\"line-height: 23pt;\">М.О.Л. Велизар Павлов</div>";
                echo "<div style=\"line-height: 23pt;\">087 6363 610</div>";
                echo "</div>";
                echo "</div>";

                echo "<div id=\"datos_id_fechas\"'>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 248px;\">Номер на фактура:</span>";
                echo "<span style=\"display: inline-block; width: 146px\">";
                echo "<input type=\"text\" name=\"num_factura\" disabled value=\"$cita\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";

                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 248px;\">Дата получаване:</span>";

                echo "<span style=\"display: inline-block; width: 146px\">";
                echo "<input type=\"date\" name=\"fecha_entrada\" value=\"$row[1]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";

                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 248px;\">Предвидена дата издаване:</span>";

                echo "<span style=\"display: inline-block; width: 146px\">";
                echo "<input type=\"date\" name=\"fecha_salida\" value=\"$row[2]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";
                echo "<div class=\"clear\"></div>";
                echo "<br>";
                echo "<h3>Лични данни на собственика и/или представителя</h3>";
                echo "<div style=\"width:420px; height: 111px; float:left\">";
                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 85px;\">Имена: </span>";

                echo "<span style=\"display: inline-block; width: 300px\">";
                echo "<input type=\"text\" name=\"nombre_representante\"  value=\"$row[3]\"  style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 85px;\">Фирма:</span>";

                echo "<span style=\"display: inline-block; width: 300px\">";
                echo "<input type=\"text\" name=\"empresa\" value=\"$row[5]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 85px;\">Улица:</span>";

                echo "<span style=\"display: inline-block; width: 300px\">";
                echo "<input type=\"text\" name=\"diraccion\"  value=\"$row[8]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:450px; height: 111px; float:right; \">";
                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 42px;\">ЕГН:</span>";

                echo "<span style=\"display: inline-block; width: 397px\">";
                echo "<input type=\"text\" name=\"cif_nif\"  value=\"$row[4]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:210px;  float:left;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 75px;\">Домашен:</span>";

                echo "<span style=\"display: inline-block; width: 120px\">";
                echo "<input type=\"number\" name=\"telefono\" value=\"$row[6]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:232px;  float:right\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 84px;\">Мобилен:</span>";

                echo "<span style=\"display: inline-block; width: 137px\">";
                echo "<input type=\"number\" name=\"movil\"  value=\"$row[7]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:210px;  float:left;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 30px;\">ПК:</span>";

                echo "<span style=\"display: inline-block; width: 165px\">";
                echo "<input type=\"number\" name=\"cp\"  value=\"$row[9]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:232px;  float:right;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 84px;\">Град:</span>";

                echo "<span style=\"display: inline-block; width: 137px\">";
                echo "<input type=\"text\" name=\"poblacion\"  value=\"$row[10]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "<div class=\"clear\"></div>";
                echo "<br>";
                echo "<h3>Данни на превозното средство</h3>";
                echo "<div style=\"width:420px; height: 68px; float:left\">";
                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 140px;\">Марка и модел: </span>";

                echo "<span style=\"display: inline-block; width: 245px\">";
                echo "<input type=\"text\" name=\"marca_modelo\"  value=\"$row[11]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 40px;\">VIN: </span>";

                echo "<span style=\"display: inline-block; width: 345px\">";
                echo "<input type=\"text\" name=\"vin\"  value=\"$row[14]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
                echo "<div style=\"width:450px; height: 68px; float:right\">";
                echo "<div style=\"width:210px;  float:left;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 84px;\">Р. номер:</span>";

                echo "<span style=\"display: inline-block; width: 111px\">";
                echo "<input type=\"text\" name=\"matricula\"  value=\"$row[12]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:232px;  float:right;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 35px;\">Км:</span>";

                echo "<span style=\"display: inline-block; width: 186px\">";
                echo "<input type=\"number\" name=\"km\"  value=\"$row[13]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:450px;  float:left;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 110px;\">Гориво:</span>";
                echo "<span style=\"display: inline-block; width: 328px\">";

                //$row[15]
                echo "<input type=\"radio\" name=\"combustible\"" . ($row[15]===0 ? "checked":"") . "value=\"0\" style=\"display: inline-block; width:13px; margin-left:20px\"/>0";
                echo "<input type=\"radio\" name=\"combustible\"" . ($row[15]==="1/4" ? "checked":"") . " value=\"1/4\" style=\"display: inline-block; width:13px; margin-left:21px\"/>1/4";
                echo "<input type=\"radio\" name=\"combustible\"" . ($row[15]==="1/2" ? "checked":"") . " value=\"1/2\" style=\"display: inline-block; width:13px; margin-left:21px\"/>1/2";
                echo "<input type=\"radio\" name=\"combustible\"" . ($row[15]==="3/4" ? "checked":"") . " value=\"3/4\" style=\"display: inline-block; width:13px; margin-left:21px\"/>3/4";
                echo "<input type=\"radio\" name=\"combustible\"" . ($row[15]==="4/4" ? "checked":"") . " value=\"4/4\" style=\"display: inline-block; width:13px; margin-left:21px\"/>4/4";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "</div>";

                echo "<div class=\"clear\"></div>";
                echo "<br>";
                echo "<h3>Услуги или авточасти</h3>";







                echo "<span id=\"trabajos\">";
                echo "<table style=\"width:100%\" id=\"tabla_trabajos\">";
                echo "<tr>";
                echo "<td style='padding: 0;'>Описание</td>";
                echo "<td style='padding: 0; width: 13%;'>Количество</td>";
                echo "<td style='padding: 0; width: 13%;'>Цена бройка</td>";
                echo "<td style='padding: 0; width: 16%;'>Обща стойност</td>";
                echo "</tr>";

                $query_hoja_de_trabajo = mysqli_query($conexion, "SELECT * FROM hojas_de_trabajo_servicio_producto WHERE id_hoja_trabajo = '$cita'");
                $contador_hoja_de_trabajo = mysqli_num_rows($query_hoja_de_trabajo);
                $contador_trabajo_realizar = 1;

                if ($contador_hoja_de_trabajo >= 1) {
                    while ($row2 = mysqli_fetch_array($query_hoja_de_trabajo)) {
                        echo "<tr>";
                        echo "<td style='padding: 7px 10px 7px 0;'><input type=\"text\" name=\"trabajo_realizar$contador_trabajo_realizar\" class=\"trabajo_a_realizar\" value=\"$row2[2]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\"></td>";
                        echo "<td style='padding: 7px 10px 7px 0; width: 13%;'><input type=\"number\" name=\"trabajo_realizar_cantidad$contador_trabajo_realizar\" class=\"trabajo_a_realizar_cantidad\" value=\"$row2[3]\" onchange=\"calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()\" style=\"background - color: #eeeeee; border:1px solid lightgrey\"></td>";
                        echo "<td style='padding: 7px 10px 7px 0; width: 13%;'><input type=\"number\" name=\"trabajo_realizar_precio_u$contador_trabajo_realizar\" class=\"trabajo_realizar_precio_u\" value=\"$row2[4]\" onchange=\"calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()\" style=\"background - color: #eeeeee; border:1px solid lightgrey\"></td>";
                        echo "<td style='padding: 7px 0 7px 0; width: 16%;'><input type=\"number\" disabled name=\"trabajo_realizar_total$contador_trabajo_realizar\" class=\"trabajo_realizar_total\" value=\"$row2[5]\" style=\"background - color: #eeeeee; border:1px solid lightgrey\"></td>";
                        echo "</tr>";

                        $contador_trabajo_realizar++;
                    }
                }
                echo "</table>";
                echo "</span>";

                echo "<span style=\"color: #b8b8b8\" onclick=\"comprobar_lineas()\"><i class=\"fas fa-plus-square fa-2x\"></i></span>";

                echo "<br>";
                echo "<div style=\"width:420px; height: 215px; float:left\">";
                echo "<h3>Бележки автосервис</h3>";
                echo "<textarea name=\"otras_observaciones\" placeholder=\"Въведи бележка\" style=\"height: 153px; width: 391px;\">$row[17]</textarea>";
                echo "</div>";

                echo "<div style=\"width:254px; height: 215px; float:right\">";

                echo "<h3>Разходи</h3>";

                echo "<div style=\"margin-bottom:20px; float:right\">";
                echo "<span style=\"display: inline-block; width: 168px;\">Общо:</span>";
                echo "<span style=\"display: inline-block; width: 80px\">";
                echo "<input type=\"number\" name=\"total_sin_iva\"  value=\"$row[18]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";

                echo "<div style=\"margin-bottom:20px; float:right\">";
                echo "<span style=\"display: inline-block; width: 168px;\">IVA 20%:</span>";
                echo "<span style=\"display: inline-block; width: 80px\">";
                echo "<input type=\"number\" name=\"iva\"  value=\"$row[19]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "<br>";
                echo "<span style=\"display: inline-block;width: 13px;margin-top: 12px;margin-right: 14px;margin-left: -4px;\"><input type=\"checkbox\" id=\"anular_iva\" onclick=\"quitar_iva()\"></span>";
                echo "<span style=\"display: inline-block;width: 222px;font-size: 12px;text-align: justify;\">Занули ДДС на основание чл.113, ал.9 или друго основание</span>";
                echo "</div>";
                echo "<br><br>";

                echo "<div style=\"margin-bottom:20px; float:right\">";
                echo "<span style=\"display: inline-block; width: 168px; font-weight: bold\">Сума за плащане:</span>";
                echo "<span style=\"display: inline-block; width: 80px\">";
                echo "<input type=\"number\" name=\"total_factura\"  value=\"$row[20]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "Ненамерена фактура";
        }
    }
} else if ($_REQUEST["motivo"] == "actualizar_hoja_trabajo") {
    $fecha_entrada = $_REQUEST["fecha_entrada"];
    $fecha_salida = $_REQUEST["fecha_salida"];
    $nombre_representante = $_REQUEST["nombre_representante"];
    $cif_nif = $_REQUEST["cif_nif"];
    $empresa = $_REQUEST["empresa"];
    $telefono = $_REQUEST["telefono"];
    $movil = $_REQUEST["movil"];
    $diraccion = $_REQUEST["diraccion"];
    $cp = $_REQUEST["cp"];
    $poblacion = $_REQUEST["poblacion"];
    $marca_modelo = $_REQUEST["marca_modelo"];
    $matricula = $_REQUEST["matricula"];
    $km = $_REQUEST["km"];
    $vin = $_REQUEST["vin"];
    $mi_combustible = $_REQUEST["mi_combustible"];
    $texto_trabajo_a_realizar = $_REQUEST["texto_trabajo_a_realizar"];
    $otras_observaciones = $_REQUEST["otras_observaciones"];
    $total_sin_iva = $_REQUEST["total_sin_iva"];
    $iva = $_REQUEST["iva"];
    $total_factura = $_REQUEST["total_factura"];
    $idCita = $_REQUEST["idCita"];

    //echo "estamos aqui";
    //var_dump($texto_trabajo_a_realizar);
    $query = mysqli_query($conexion, "SELECT idCita FROM hojas_de_trabajo WHERE idCita = '$idCita'");
    $contador = mysqli_num_rows($query);



    //dian aqui - una vez que he entrado en la hoja de trabajo y PULSO SOBRE actualizar viene aqui
    $array_trabajos_realizar = explode("fin_fin.,_", $texto_trabajo_a_realizar);
    $contar_trabajos_realizar = count($array_trabajos_realizar);




    if($contar_trabajos_realizar != 0){
        $query_borrar_de_hojas_de_trabajo_servicio_producto = "DELETE FROM `hojas_de_trabajo_servicio_producto` WHERE id_hoja_trabajo = '$idCita'";
        $conexion->query($query_borrar_de_hojas_de_trabajo_servicio_producto);


        //echo "vamos a hacer " . $contar_trabajos_realizar . " inserrciones en la tabla de hojas_de_trabajo_servicio_producto";
        for($x = 0; $x < $contar_trabajos_realizar-1; $x++){


            $array_linea = explode("sig.,_", $array_trabajos_realizar[$x]);


            //var_dump($array_linea);


            $insertar_hojas_de_trabajo_servicio_producto = "INSERT INTO `hojas_de_trabajo_servicio_producto` (`id_hoja_trabajo`, `titulo`, `cantidad`, `precio_unidad`, `cantidad_total`) VALUES ( '$idCita', '$array_linea[0]', $array_linea[1], $array_linea[2], $array_linea[3])";
            $conexion->query($insertar_hojas_de_trabajo_servicio_producto);
            if($conexion){
                echo "bien";
            }else{
                echo "mal";
            }
        }
    }




    if ($contador == 0) {
        $insertar = "INSERT INTO `hojas_de_trabajo` (`fechaEntrada`, `fechaPrevistaEntrega`, `nombres`, `cif_Nif`, `empresa`, `telefono`, `movil`, `direccion`, `cp`, `poblacion`, `marcaModelo`, `matricula`, `km`, `vin`, `combustible`, `notaTaller`, `totalSinIva`, `iva`, `totalFactura`, `idCita`) VALUES ('$fecha_entrada', '$fecha_salida', '$nombre_representante', '$cif_nif', '$empresa', '$telefono', '$movil', '$diraccion', '$cp', '$poblacion', '$marca_modelo', '$matricula', '$km', '$vin', '$mi_combustible', '$otras_observaciones', '$total_sin_iva', '$iva', '$total_factura', '$idCita')";
        $conexion->query($insertar);

        
    } else {
                mysqli_query($conexion, "UPDATE `hojas_de_trabajo` SET `fechaEntrada` = '$fecha_entrada', "
                . "`fechaPrevistaEntrega` = '$fecha_salida', "
                . "`nombres` = '$nombre_representante', "
                . "`cif_Nif` = '$cif_nif', "
                . "`empresa` = '$empresa', "
                . "`telefono` = '$telefono',"
                . "`movil` = '$movil', "
                . "`direccion` = '$diraccion', "
                . "`cp` = '$cp', "
                . "`poblacion` = '$poblacion', "
                . "`marcaModelo` = '$marca_modelo', "
                . "`matricula` = '$matricula', "
                . "`km` = '$km', "
                . "`vin` = '$vin', "
                . "`combustible` = '$mi_combustible', "
                . "`notaTaller` = '$otras_observaciones', "
                . "`totalSinIva` = '$total_sin_iva', "
                . "`iva` = '$iva', "
                . "`totalFactura` = '$total_factura' "
                . "WHERE `idCita` = $idCita");


        //echo "ERROR: El telefono <b>" . $telefono_cliente . "</b> ya esta asociado a otro cliente!";
        //mysqli_close($conexion);
    }

} else if ($_REQUEST["motivo"] == "visualizar_hoja_cliente") {
    if (isset($_REQUEST["cita"])) {
        $cita = $_REQUEST["cita"];

        $query = mysqli_query($conexion, "SELECT * FROM hojas_de_trabajo WHERE idCita = '$cita'");
        $contador = mysqli_num_rows($query);

        //dian - esto se visualiza en la cuenta del cliente donde puede ver todas sus facturas
//        $query_hoja_de_trabajo = mysqli_query($conexion, "SELECT * FROM hojas_de_trabajo_servicio_producto WHERE id_hoja_trabajo = '$cita'");
//        $contador_hoja_de_trabajo = mysqli_num_rows($query_hoja_de_trabajo);
//
//        echo "sacamos datos";
//        if ($contador_hoja_de_trabajo >= 1) {
//            while ($row = mysqli_fetch_array($query_hoja_de_trabajo)) {
//                var_dump($row);
//            }
//        }

        if ($contador == 1) {
            while ($row = mysqli_fetch_array($query)) {
                echo "<div id=\"datos_taller\">";
                echo "<div style=\"width:120px; float: left; padding-top: 16px;\">";
                echo "<img src=\"design/logo.jpg\" width=\"200\">";
                echo "</div>";
                echo "<div style=\"width:228px; float: right; margin-top: -30px;\">";
                echo "<div style=\"line-height: 23pt; font-weight: bold; font-size: 18px;\">Авто Павлов 94 ЕООД</div>";
                echo "<div style=\"line-height: 23pt;\">Бул. Източен 22, Пловдив</div>";
                echo "<div style=\"line-height: 23pt;\">Булстат 205269686</div>";
                echo "<div style=\"line-height: 23pt;\">М.О.Л. Велизар Павлов</div>";
                echo "<div style=\"line-height: 23pt;\">087 6363 610</div>";
                echo "</div>";
                echo "</div>";

                echo "<div id=\"datos_id_fechas\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 248px;\">Nº фактура:</span>";
                echo "<span style=\"display: inline-block; width: 146px\">";
                echo "<input type=\"text\" name=\"num_factura\" disabled value=\"$cita\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";

                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 248px;\">Дата получаване:</span>";

                echo "<span style=\"display: inline-block; width: 146px\">";
                echo "<input type=\"date\" name=\"fecha_entrada\" disabled value=\"$row[1]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";

                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 248px;\">Предвидена дата издаване:</span>";

                echo "<span style=\"display: inline-block; width: 146px\">";
                echo "<input type=\"date\" name=\"fecha_salida\" disabled value=\"$row[2]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";
                echo "<div class=\"clear\"></div>";
                echo "<br>";
                echo "<h3>Лични данни на собственика и/или представителя</h3>";
                echo "<div style=\"width:420px; height: 111px; float:left\">";
                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 63px;\">Имена: </span>";

                echo "<span style=\"display: inline-block; width: 322px\">";
                echo "<input type=\"text\" name=\"nombre_representante\" disabled  value=\"$row[3]\"  style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 63px;\">Фирма:</span>";

                echo "<span style=\"display: inline-block; width: 322px\">";
                echo "<input type=\"text\" name=\"empresa\" value=\"$row[5]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 63px;\">Улица:</span>";

                echo "<span style=\"display: inline-block; width: 322px\">";
                echo "<input type=\"text\" name=\"diraccion\"  value=\"$row[8]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:470px; height: 111px; float:right; \">";
                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 42px;\">ЕГН: </span>";

                echo "<span style=\"display: inline-block; width:427px;\">";
                echo "<input type=\"text\" name=\"cif_nif\"  value=\"$row[4]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:210px;  float:left;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 80px;\">Домашен:</span>";

                echo "<span style=\"display: inline-block; width: 115px\">";
                echo "<input type=\"number\" name=\"telefono\" value=\"$row[6]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:233px;  float:right\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 101px;\">Мобилен:</span>";

                echo "<span style=\"display: inline-block; width: 131px\">";
                echo "<input type=\"number\" name=\"movil\"  value=\"$row[7]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:210px;  float:left;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 35px;\">ПК:</span>";

                echo "<span style=\"display: inline-block; width: 160px\">";
                echo "<input type=\"number\" name=\"cp\"  value=\"$row[9]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:232px;  float:right;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 49px;\">Град:</span>";

                echo "<span style=\"display: inline-block; width: 181px\">";
                echo "<input type=\"text\" name=\"poblacion\"  value=\"$row[10]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "<div class=\"clear\"></div>";
                echo "<br><br>";
                echo "<h3>Данни на МПС</h3>";
                echo "<div style=\"width:420px; height: 68px; float:left\">";
                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 130px;\">Марка и модел: </span>";

                echo "<span style=\"display: inline-block; width: 255px\">";
                echo "<input type=\"text\" name=\"marca_modelo\"  value=\"$row[11]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div>";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 45px;\">ВИН: </span>";

                echo "<span style=\"display: inline-block; width: 340px\">";
                echo "<input type=\"text\" name=\"vin\"  value=\"$row[14]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
                echo "<div style=\"width:470px; height: 68px; float:right\">";
                echo "<div style=\"width:210px;  float:left;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 80px;\">Р. номер:</span>";

                echo "<span style=\"display: inline-block; width: 117px\">";
                echo "<input type=\"text\" name=\"matricula\"  value=\"$row[12]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:232px;  float:right;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 35px;\">Км:</span>";

                echo "<span style=\"display: inline-block; width: 196px\">";
                echo "<input type=\"number\" name=\"km\"  value=\"$row[13]\" disabled style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "<div style=\"width:450px;  float:left;\">";
                echo "<div style=\"margin-bottom:20px\">";
                echo "<span style=\"display: inline-block; width: 65px;\">Гориво:</span>";
                echo "<span style=\"display: inline-block; width: 370px\">";

                //$row[15]
                echo "<input type=\"radio\" disabled name=\"combustible\"" . ($row[15]===0 ? "checked":"") . "value=\"0\" style=\"display: inline-block; width:13px;\"/>0";
                echo "<input type=\"radio\" disabled name=\"combustible\"" . ($row[15]==="1/4" ? "checked":"") . " value=\"1/4\" style=\"display: inline-block; width:13px; margin-left:35px\"/>1/4";
                echo "<input type=\"radio\" disabled name=\"combustible\"" . ($row[15]==="1/2" ? "checked":"") . " value=\"1/2\" style=\"display: inline-block; width:13px; margin-left:35px\"/>1/2";
                echo "<input type=\"radio\" disabled name=\"combustible\"" . ($row[15]==="3/4" ? "checked":"") . " value=\"3/4\" style=\"display: inline-block; width:13px; margin-left:35px\"/>3/4";
                echo "<input type=\"radio\" disabled name=\"combustible\"" . ($row[15]==="4/4" ? "checked":"") . " value=\"4/4\" style=\"display: inline-block; width:13px; margin-left:35px\"/>4/4";
                echo "</span>";
                echo "</div>";
                echo "</div>";

                echo "</div>";

                echo "<div class=\"clear\"></div>";
                echo "<br>";
                echo "<h3>Услуги/стоки</h3>";

                echo "<span id=\"trabajos\">";
                echo "<table style=\"width:100%\" id=\"tabla_trabajos\">";
                echo "<tr>";
                echo "<td style='padding: 0;'>Описание</td>";
                echo "<td style='padding: 0; width: 13%;'>Количество</td>";
                echo "<td style='padding: 0; width: 13%;'>Цена бройка</td>";
                echo "<td style='padding: 0; width: 16%;'>Обща стойност</td>";
                echo "</tr>";

                $query_hoja_de_trabajo = mysqli_query($conexion, "SELECT * FROM hojas_de_trabajo_servicio_producto WHERE id_hoja_trabajo = '$cita'");
                $contador_hoja_de_trabajo = mysqli_num_rows($query_hoja_de_trabajo);
                $contador_trabajo_realizar = 1;

                if ($contador_hoja_de_trabajo >= 1) {
                    while ($roww = mysqli_fetch_array($query_hoja_de_trabajo)) {
                        echo "<tr>";
                        echo "<td style='padding: 7px 10px 7px 0;'><input type=\"text\" disabled name=\"trabajo_realizar$contador_trabajo_realizar\" class=\"trabajo_a_realizar\" value=\"$roww[2]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\"></td>";
                        echo "<td style='padding: 7px 10px 7px 0; width: 13%;'><input type=\"number\" disabled name=\"trabajo_realizar_cantidad$contador_trabajo_realizar\" class=\"trabajo_a_realizar_cantidad\" value=\"$roww[3]\" style=\"background - color: #eeeeee; border:1px solid lightgrey\"></td>";
                        echo "<td style='padding: 7px 10px 7px 0; width: 13%;'><input type=\"number\" disabled name=\"trabajo_realizar_precio_u$contador_trabajo_realizar\" class=\"trabajo_realizar_precio_u\" value=\"$roww[4]\" style=\"background - color: #eeeeee; border:1px solid lightgrey\"></td>";
                        echo "<td style='padding: 7px 0 7px 0; width: 16%;'><input type=\"number\" disabled name=\"trabajo_realizar_total$contador_trabajo_realizar\" class=\"trabajo_realizar_total\" value=\"$roww[5]\" style=\"background - color: #eeeeee; border:1px solid lightgrey\"></td>";
                        echo "</tr>";
                        $contador_trabajo_realizar++;
                    }
                }
                echo "</table>";
                echo "</span>";
                echo "<span style=\"color: #b8b8b8\" onclick=\"comprobar_lineas()\"><i class=\"fas fa-plus-square fa-2x\"></i></span>";

                echo "<br>";
                echo "<div style=\"width:420px; height: 215px; float:left\">";
                echo "<h3>Бележки</h3>";
                echo "<textarea name=\"otras_observaciones\" disabled style=\"height: 153px; width: 391px;\">$row[17]</textarea>";
                echo "</div>";

                echo "<div style=\"width:254px; height: 215px; float:right\">";

                echo "<h3>Разходи</h3>";
                echo "<div style=\"margin-bottom:20px; float:right\">";
                echo "<span style=\"display: inline-block; width: 168px;\">Общо:</span>";
                echo "<span style=\"display: inline-block; width: 80px\">";
                echo "<input type=\"number\" name=\"total_sin_iva\" disabled value=\"$row[18]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";


                echo "<div style=\"margin-bottom:20px; float:right\">";
                echo "<span style=\"display: inline-block; width: 168px;\">IVA 20%:</span>";
                echo "<span style=\"display: inline-block; width: 80px\">";
                echo "<input type=\"number\" name=\"iva\" disabled value=\"$row[19]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "<br>";
                echo "<span style=\"display: inline-block;width: 13px;margin-top: 12px;margin-right: 14px;margin-left: -4px;\"><input type=\"checkbox\" id=\"anular_iva\" disabled onclick=\"quitar_iva()\"></span>";
                echo "<span style=\"display: inline-block;width: 222px;font-size: 12px;text-align: justify;\">Занули ДДС на основание чл.113, ал.9 или друго основание</span>";
                echo "</div>";
                echo "<br><br>";

                echo "<div style=\"margin-bottom:20px; float:right\">";
                echo "<span style=\"display: inline-block; width: 168px; font-weight: bold\">Сума за плащане:</span>";
                echo "<span style=\"display: inline-block; width: 80px\">";
                echo "<input type=\"number\" name=\"total_factura\" disabled value=\"$row[20]\" style=\"background-color: #eeeeee; border:1px solid lightgrey\">";
                echo "</span>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "Ненамерена фактура";
        }
    }
} else if ($_REQUEST["motivo"] == "actualizar_cita") {
    $cita = $_REQUEST["cita"];
    $hora = $_REQUEST["hora"];
    $categoria = $_REQUEST["categoria"];
    $servicio = $_REQUEST["servicio"];
    $empleado = $_REQUEST["empleado"];
    $fecha = $_REQUEST["fecha"];



    //var_dump($_REQUEST);



    $comprobar_duracion_servicio = mysqli_query($conexion, "SELECT duracionServ FROM `servicios` WHERE `nombreServ` = '$servicio'");

    while ($row = mysqli_fetch_array($comprobar_duracion_servicio)) {
        $duracion_servicio_seleccionado = $row[0];
    }

    $duracion_servicio_seleccionado = $duracion_servicio_seleccionado / 15;
    $duracion_servicio_seleccionado = ($duracion_servicio_seleccionado + 1);  //le sumo uno para el descanso de 15min
    //echo "Nombre del servicio = " . $servicio . " con duracion de = $duracion_servicio_seleccionado<br>";


    $hora_seleccionada = "08:00";
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









    $resultado = mysqli_query($conexion, "UPDATE `citas` SET `fecha` = '$fecha', `hora` = '$hora', `hora_fin` = '$hora_fin', `nombreEmpleado` = '$empleado', `nombreServicio` = '$servicio' WHERE `idCita` = '$cita'");

    echo $resultado;
} else if ($_REQUEST["motivo"] == "mostrar_intervalos_crear_cita_manual") {
    $empleado = $_REQUEST["empleado"];
    $servicio = $_REQUEST["servicio"];
    $fecha = $_REQUEST["fecha"];
    $diaSemana = getDiaSemana($fecha);
    $duracion_servicio = 0;
    $duracion_prueba = 0;
    $array_horas_para_eliminar = array();
    $array_horario = array();                   //full horario sin tocar
    $array_reservas_duracion = array();
    $idEmple = "";

    //var_dump($_REQUEST);

    //sacamos el idEmple del empleado
    //hacemos select para sacar el idCliente
    $resultado_id_emple = mysqli_query($conexion, "SELECT idEmpleado from empleados WHERE nombreEmpleado = '$empleado'");
    $contador_id_emple = mysqli_num_rows($resultado_id_emple);
    if ($contador_id_emple != 0) {
        $idEmple = mysqli_fetch_array($resultado_id_emple)[0];
    }
    //echo "ID EMPLEADO =>> $idEmple<br><br>";


    //mostramos cuantas casillas ocupa el servicio seleccionado
    $resultado = mysqli_query($conexion, "SELECT duracionServ FROM `servicios` WHERE `nombreServ` = '$servicio'");
    while ($row = mysqli_fetch_array($resultado)) {
        $duracion_servicio_seleccionado = $row[0];
    }
    $duracion_servicio_seleccionado = $duracion_servicio_seleccionado / 15;
    $duracion_servicio_seleccionado = ($duracion_servicio_seleccionado + 1);  //le sumo uno para el descanso de 15min
    //echo "Nombre del servicio = " . $servicio . " con duracion de = $duracion_servicio_seleccionado<br>";


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
    //echo "HORARIO SELECCIONADO EMPLEADO START =>> $horario_empleado_start<br>";
    //echo "HORARIO SELECCIONADO EMPLEADO FIN =>> $horario_empleado_fin<br>";


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

            //echo "<span style='color:red'>DURACION PRUEBA SIN PARTIR: $duracion_servicio</span><br>";
            $duracion_prueba = $duracion_servicio / 15;
            //echo "DURACION PRUEBA DIVIDIDA: $duracion_prueba<br><br>";


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
                    //echo "DURACION INICIO: $indice_inicio<br>";
                    //echo "HORA INICIO: $hora_inicio<br><br>";
                    $variable_para_anadir_array_reservas_duracion["posicion1"] = $indice_inicio;
                }

                //si el indice del array el el mismo que el indice de inicio sumando lo que dura el servicio ($duracion_servicio DIVIDIDO entre 15minutos en este caso)
                //sabremos cuantas casillas tenemos que saltar
                if ($indice == ($indice_inicio + $duracion_prueba) /* -1 pongo MENOS UNO y ya no habra descansos*/) {
                    //aqui controlo que no sea la primera vez que entra porque me agregaba un campo de mas
                    //if ($indice_inicio != 0) {
                    $hora_fin = $hora;
                    //echo "DURACION FIN: $indice<br>";
                    //echo "HORA FIN: $hora_fin<br><br>";
                    $variable_para_anadir_array_reservas_duracion["posicion2"] = $indice;
                    array_push($array_reservas_duracion, $variable_para_anadir_array_reservas_duracion);
                    // }
                }
            }


            //rellenamos el array con las horas (los intervalos de horas) que hay QUE NO HAY QUE MOSTRAR
            //hora inicio sera porejemplo 11:00 y hora fin sera 12:00
            //lo dificil es sacar la hora fin ya que no la sabesmos pero con lo realizado arriba funciona perfectamente
            foreach (intervaloHora($hora_inicio, $hora_fin, $intervalo = 15) as $hora) {

                if ($hora == $hora_fin) {
                    //echo "<b>Hora para eliminar descanso: $hora, hasta +15min</b><br>";
                } else {
                    //echo "Hora para eliminar: $hora<br>";
                }

                array_push($array_horas_para_eliminar, $hora);
            }
            //echo "<br><br><br>";
        }
        // "<br><br><br>Array con horas eliminadas<br>";
        //print_r($array_horas_para_eliminar);
        //echo "<br><br>";
        $array_horario_horas_libres = array();
        $array_horario_horas_libres = array_diff($array_horario, $array_horas_para_eliminar);

        //echo "Array con horas que se muestran<br>";
        //print_r($array_horario_horas_libres);
        //echo "<h1>CONTINUAMOS...</h1>";
        //echo count($array_horario_horas_libres);
        //echo "<br>";

        //en esta parte lo que hacemos es meter en el array $array_conjunto_huecos_libres todos los huecos libres existentes para ese dia
        //$array_conjunto_huecos_libres tendra dentro tantos arrays como huecos libres de varios fragmentos haya el dia seleccionado
        $array_conjunto_huecos_libres_temporal = array("inicio" => "", "fin" => "", "contador" => "");
        $array_conjunto_huecos_libres = array();
        $primera_vez = true;
        $asignado = false;
        $contador = 0;

        for ($x = 0; $x < count($array_horario); $x++) {
            if (array_key_exists($x, $array_horario_horas_libres)) {
                //echo $x . " = $array_horario_horas_libres[$x]<br>";
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

                    if($array_conjunto_huecos_libres_temporal["inicio"] != "" && $array_conjunto_huecos_libres_temporal["fin"] != ""){
                        array_push($array_conjunto_huecos_libres, $array_conjunto_huecos_libres_temporal);
                    }
                    $contador = 0;
                    $asignado = true;
                    $primera_vez = true;
                }
                //echo $x . " esta vacio<br>";
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

        //var_dump($array_conjunto_huecos_libres);
        $ultima_reserva_fin = "";


        //ahora con este for lo que haremos es lo siguiente:
        //si tenemos un hueco libre con 5 fragmentos (10:00, 10:15, 10:30, 10:45, 11:00) y el servicio que hemos seleccionado ocupa 2 gragmentos + 1 gragmento de descanso, total 3 gragmentos
        //con este codigo lo que hacemos es contar de atras 3 veces para borrar las 3 ultimas fragmentos (11:00, 10:45, 10:30) para asi poder elejir para hacer la reserva unicamente los fragmentos
        //10:00, 10:15.... asi los tres ultimos fragmentos queda claro que en caso de que se haga una reserva quedaran ocupados por ese servicio
        for ($x = 0; $x < count($array_conjunto_huecos_libres); $x++) {
            //echo $array_conjunto_huecos_libres[$x]["inicio"] . " = " . $array_conjunto_huecos_libres[$x]["fin"] . " = " . $array_conjunto_huecos_libres[$x]["contador"] . "<br>";
            //guardamos el
            $ultima_reserva_fin = $array_conjunto_huecos_libres[$x]["fin"];

            $num_horas_que_no_borramos = ((int)$array_conjunto_huecos_libres[$x]["contador"] - (int)$duracion_servicio_seleccionado);
            //echo "quedan libres " . $num_horas_que_no_borramos;
            //echo "<br>";


            $contador = 0;      //$otro_contador
            //echo "CONTADOR = " . $contador . "<br>";
            foreach (intervaloHora_alreves($array_conjunto_huecos_libres[$x]["fin"], $array_conjunto_huecos_libres[$x]["inicio"], $intervalo = 15) as $hora) {
                if ($contador + 1 < $duracion_servicio_seleccionado) {
                    $contador++;
                    //echo "BORRAMOS = " . $hora . "<br>";
                    array_push($array_horas_para_eliminar, $hora);
                } else {
                    //echo "$hora <br>";
                }

            }
            //echo "<br><br>";

        }

        //echo "<h2>Eliminamos las casillas solitarias... ahi donde hay una casilla soletaria...</h2>";
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
                //echo $x . " = $array_horario_horas_libres[$x]<br>";
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
            echo "<button value='$valor' id='hora_opcion' class='$indice' onclick='insertar_nueva_cita_manual(this.value)'>" .
                "<span id='hora_numeric'>" . $valor . "</span>" .
                "<span id='buton_reservar'>Резервирай!</span>" .
                "</button><br>";
        }
        //echo "HAY CITAS";
    } else {
        //echo "NO HAY CITAS";

        //filtramos que cuando no haya ninguna reserva, no puede dejarnos seleccionar 22:00h (hora por defecto de fin de dia de trabajo),
        // tiene que dejarnos seleccionar la hora descontando las casillas que ocupa el servicio seleccionado para hacer la reserva
        $contador_sin_reservas = 0;      //$otro_contador
        //echo "CONTADOR = " . $contador_sin_reservas . "<br>";

        foreach (intervaloHora_alreves($horario_empleado_fin, $horario_empleado_start, $intervalo = 15) as $hora) {
            if ($contador_sin_reservas +1 < $duracion_servicio_seleccionado) {
                $contador_sin_reservas++;
                //echo "BORRAMOS = " . $hora . "<br>";
                array_push($array_horas_para_eliminar, $hora);
            } else {
                //echo "$hora <br>";
            }
        }

        $array_horario_horas_libres = array_diff($array_horario, $array_horas_para_eliminar);

        echo "<div id='diaSeleccionado'></div>";
        for ($x = 0; $x < count($array_horario_horas_libres); $x++) {

            echo "<button value='$array_horario_horas_libres[$x]' id='hora_opcion' onclick='insertar_nueva_cita_manual(this.value)'>" .
                "<span id='hora_numeric'>" . $array_horario_horas_libres[$x] . "</span>" .
                "<span id='buton_reservar'>Резервирай!</span>" .
                "</button><br>";
        }
    }
    //print_r($array_reservas_duracion);
} else if ($_REQUEST["motivo"] == "insertar_nueva_cita_manual") {
    $nombre = $_REQUEST["nombre"];
    $telefono = $_REQUEST["telefono"];
    $empleado = $_REQUEST["empleado"];
    $servicio = $_REQUEST["servicio"];
    $fecha = $_REQUEST["fecha"];
    $hora = $_REQUEST["hora"];
    $idCliente = "";

    $comprobar_duracion_servicio = mysqli_query($conexion, "SELECT duracionServ FROM `servicios` WHERE `nombreServ` = '$servicio'");

    while ($row = mysqli_fetch_array($comprobar_duracion_servicio)) {
        $duracion_servicio_seleccionado = $row[0];
    }

    $duracion_servicio_seleccionado = $duracion_servicio_seleccionado / 15;
    $duracion_servicio_seleccionado = ($duracion_servicio_seleccionado + 1);  //le sumo uno para el descanso de 15min
    //echo "Nombre del servicio = " . $servicio . " con duracion de = $duracion_servicio_seleccionado<br>";


    $hora_seleccionada = "08:00";
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


    //////////////////////////////////////////////////////////////////////
    ////////////COMPRUEBO SI LOS DATOS INTRODUCIDOS COINCIDEN/////////////
    //////////////////////////////////////////////////////////////////////
    $resultado2 = mysqli_query($conexion, "SELECT idCliente from clientes WHERE telefonoCliente = '$telefono'");
    $contador2 = mysqli_num_rows($resultado2);
    if ($contador2 != 0) {
        //echo "EXISTE!";

        while ($row = mysqli_fetch_array($resultado2)) {
            $idCliente = $row[0];
        }

        //INSERTAR LA RESERVA
        $resultado = mysqli_query($conexion, "INSERT INTO citas (fecha, hora, hora_fin, nombreEmpleado, nombreServicio, idCliente) VALUES ('$fecha', '$hora', '$hora_fin', '$empleado', '$servicio', '$idCliente')");
        echo $resultado;

//        $insertar_reserva = "INSERT INTO citas (fecha, hora, hora_fin, nombreEmpleado, nombreServicio, idCliente) VALUES ('$fecha', '$hora', '$hora_fin', '$empleado', '$servicio', '$idCliente')";
//        $conexion->query($insertar_reserva);

        //echo "HAS AÑADIDO LA NUEVA CITA";
    }else{
        //echo "TELEFONO NO EXISTE! crearemos el usuario y despues la cita";

        $insertar = "INSERT INTO clientes (nombreCliente, telefonoCliente) VALUES ('$nombre', '$telefono')";
        $conexion->query($insertar);
        //echo "Se ha añadido el cliente <b>" . $nombre . "</b> con exito!<br><br>";


        //hacemos select para sacar el idCliente
        $resultado = mysqli_query($conexion, "SELECT idCliente from clientes WHERE telefonoCliente = '$telefono'");
        $contador = mysqli_num_rows($resultado);                                                //lo que devuelve la select lo guardamos en la variable contador,
        if ($contador != 0) {
            while ($row = mysqli_fetch_array($resultado)) {
                $idCliente = $row[0];
            }
        }

        //INSERTAR LA RESERVA
        $resultado = mysqli_query($conexion, "INSERT INTO citas (fecha, hora, hora_fin, nombreEmpleado, nombreServicio, idCliente) VALUES ('$fecha', '$hora', '$hora_fin', '$empleado', '$servicio', '$idCliente')");
        echo $resultado;

//        $insertar_reserva = "INSERT INTO citas (fecha, hora, hora_fin, nombreEmpleado, nombreServicio, idCliente) VALUES ('$fecha', '$hora', '$hora_fin', '$empleado', '$servicio', '$idCliente')";
//        $conexion->query($insertar_reserva);
    }
}







//fecha, empleado, servicio
function editar_cita($fecha, $empleado, $servicio, $hora_inicio, $hora_fin, $cita){
    //fecha
    //empleado
    //servicio
    $diaSemana = getDiaSemana($fecha);
    $duracion_servicio = 0;
    $duracion_prueba = 0;
    $array_horas_para_eliminar = array();
    $array_horario = array();                   //full horario sin tocar
    $array_reservas_duracion = array();
    $idEmple = "";
    $array_horas_intervalos_cita_actual = array();
    $conexion = new mysqli(conexion_host, conexion_user, conexion_pass, conexion_bbdd, conexion_port);
    $fecha_actual_bbdd_cita = "";
    $empleado_actual_bbdd_cita = "";


    //hacemos select para sacar la fecha y el empleado de la cita que hemos dado click para cambiar
    $comprobacion_fecha_emple = mysqli_query($conexion, "SELECT fecha, nombreEmpleado from citas WHERE idCita = '$cita'");
    $contador_fecha_emple = mysqli_num_rows($comprobacion_fecha_emple);
    if ($contador_fecha_emple != 0) {
        while ($row = mysqli_fetch_array($comprobacion_fecha_emple)) {
            $fecha_actual_bbdd_cita = $row[0];
            $empleado_actual_bbdd_cita = $row[1];
        }
    }

    //si la fecha y empleado que hay en bbdd para esa cita y la fecha y el empleado que hemos marcado en el formulario coinciden
    //entonces si que debemos eliminar las posiciones que ocupan
    if($fecha_actual_bbdd_cita == $fecha && $empleado_actual_bbdd_cita == $empleado){
        foreach (intervaloHora($hora_inicio, $hora_fin, $intervalo = 15) as $intervalo) {
            array_push($array_horas_intervalos_cita_actual, $intervalo);
        }
        //elimino la ultima posicion del array porque si no lo hacemos cogemos una posicion de mas
        array_pop($array_horas_intervalos_cita_actual);

        //ya me he sacado los intervalos de la cita actual porque si o si me las tiene que mostrar porque podria querer retrasar 15minutos o adelantar 15 minutos...

        //print_r($array_horas_intervalos_cita_actual);
    }









    //sacamos el idEmple del empleado
    //hacemos select para sacar el idCliente
    $resultado_id_emple = mysqli_query($conexion, "SELECT idEmpleado from empleados WHERE nombreEmpleado = '$empleado'");
    $contador_id_emple = mysqli_num_rows($resultado_id_emple);
    if ($contador_id_emple != 0) {
        $idEmple = mysqli_fetch_array($resultado_id_emple)[0];
    }
    //echo "ID EMPLEADO =>> $idEmple<br><br>";


    //mostramos cuantas casillas ocupa el servicio seleccionado
    $resultado = mysqli_query($conexion, "SELECT duracionServ FROM `servicios` WHERE `nombreServ` = '$servicio'");
    while ($row = mysqli_fetch_array($resultado)) {
        $duracion_servicio_seleccionado = $row[0];
    }
    $duracion_servicio_seleccionado = $duracion_servicio_seleccionado / 15;
    $duracion_servicio_seleccionado = ($duracion_servicio_seleccionado + 1);  //le sumo uno para el descanso de 15min
    //echo "Nombre del servicio = " . $servicio . " con duracion de = $duracion_servicio_seleccionado<br>";




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
    //echo "HORARIO SELECCIONADO EMPLEADO START =>> $horario_empleado_start<br>";
    //echo "HORARIO SELECCIONADO EMPLEADO FIN =>> $horario_empleado_fin<br>";


    //rellenamos el horario sin tocarlo, es el horario entre el START y el FIN
    foreach (intervaloHora($horario_empleado_start, $horario_empleado_fin, $intervalo = 15) as $hora) {
        array_push($array_horario, $hora);
    }
    //var_dump($array_horario);


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

            //echo "<span style='color:red'>DURACION PRUEBA SIN PARTIR: $duracion_servicio</span><br>";
            $duracion_prueba = $duracion_servicio / 15;
            //echo "DURACION PRUEBA DIVIDIDA: $duracion_prueba<br><br>";


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
                    //echo "DURACION INICIO: $indice_inicio<br>";
                    //echo "HORA INICIO: $hora_inicio<br><br>";
                    $variable_para_anadir_array_reservas_duracion["posicion1"] = $indice_inicio;
                }

                //si el indice del array el el mismo que el indice de inicio sumando lo que dura el servicio ($duracion_servicio DIVIDIDO entre 15minutos en este caso)
                //sabremos cuantas casillas tenemos que saltar
                if ($indice == ($indice_inicio + $duracion_prueba) /* -1 pongo MENOS UNO y ya no habra descansos*/) {
                    //aqui controlo que no sea la primera vez que entra porque me agregaba un campo de mas
                    //if ($indice_inicio != 0) {
                    $hora_fin = $hora;
                    //echo "DURACION FIN: $indice<br>";
                    //echo "HORA FIN: $hora_fin<br><br>";
                    $variable_para_anadir_array_reservas_duracion["posicion2"] = $indice;
                    array_push($array_reservas_duracion, $variable_para_anadir_array_reservas_duracion);
                    // }
                }
            }


            //rellenamos el array con las horas (los intervalos de horas) que hay QUE NO HAY QUE MOSTRAR
            //hora inicio sera porejemplo 11:00 y hora fin sera 12:00
            //lo dificil es sacar la hora fin ya que no la sabesmos pero con lo realizado arriba funciona perfectamente
            foreach (intervaloHora($hora_inicio, $hora_fin, $intervalo = 15) as $hora) {

                if ($hora == $hora_fin) {
                    //echo "<b>Hora para eliminar descanso: $hora, hasta +15min</b><br>";
                } else {
                    //echo "Hora para eliminar: $hora<br>";
                }


                array_push($array_horas_para_eliminar, $hora);
            }
            //echo "<br><br><br>";
        }

        // "<br><br><br>Array con horas eliminadas<br>";
        //var_dump($array_horas_para_eliminar);
        //echo "<br>";
        //var_dump($array_horas_intervalos_cita_actual);

        //aqui lo que hacemos es quitar del array_horas_para_eliminar aquellos intervalos que pertenecen a la cita seleccionada
        //porque tal vez queremos mover la cita 15minutos hacia atras o hacia delante por lo que es imprescindible esta parte
        foreach ($array_horas_para_eliminar as $position => $item) {
            //echo $item . " para eliminar<br>";
            foreach ($array_horas_intervalos_cita_actual as $item2) {
                //echo $item2 . "intervalo cita actual<br>";
                if($item == $item2){
                    //echo "borrado " . $item . "<br>";
                    unset($array_horas_para_eliminar[$position]);
                }
            }
            //echo "<br><br>";


        }
        // "<br><br><br>Array con horas eliminadas, le he hemos quitado los intervalos que pertenecen a la cita seleccionada<br>";
        //var_dump($array_horas_para_eliminar);




        //print_r($array_horas_para_eliminar);
        //echo "<br><br>";
        $array_horario_horas_libres = array();
        $array_horario_horas_libres = array_diff($array_horario, $array_horas_para_eliminar);

        //echo "Array con horas que se muestran<br>";
        //print_r($array_horario_horas_libres);


        //echo "<h1>CONTINUAMOS...</h1>";
        //echo count($array_horario_horas_libres);
        //echo "<br>";




        //en esta parte lo que hacemos es meter en el array $array_conjunto_huecos_libres todos los huecos libres existentes para ese dia
        //$array_conjunto_huecos_libres tendra dentro tantos arrays como huecos libres de varios fragmentos haya el dia seleccionado
        $array_conjunto_huecos_libres_temporal = array("inicio" => "", "fin" => "", "contador" => "");
        $array_conjunto_huecos_libres = array();
        $primera_vez = true;
        $asignado = false;
        $contador = 0;

        for ($x = 0; $x < count($array_horario); $x++) {
            if (array_key_exists($x, $array_horario_horas_libres)) {
                //echo $x . " = $array_horario_horas_libres[$x]<br>";
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

                    if($array_conjunto_huecos_libres_temporal["inicio"] != "" && $array_conjunto_huecos_libres_temporal["fin"] != ""){
                        array_push($array_conjunto_huecos_libres, $array_conjunto_huecos_libres_temporal);
                    }
                    $contador = 0;
                    $asignado = true;
                    $primera_vez = true;
                }
                //echo $x . " esta vacio<br>";
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
        //var_dump($array_conjunto_huecos_libres);
        //var_dump($array_conjunto_huecos_libres);
        $ultima_reserva_fin = "";


        //ahora con este for lo que haremos es lo siguiente:
        //si tenemos un hueco libre con 5 fragmentos (10:00, 10:15, 10:30, 10:45, 11:00) y el servicio que hemos seleccionado ocupa 2 gragmentos + 1 gragmento de descanso, total 3 gragmentos
        //con este codigo lo que hacemos es contar de atras 3 veces para borrar las 3 ultimas fragmentos (11:00, 10:45, 10:30) para asi poder elejir para hacer la reserva unicamente los fragmentos
        //10:00, 10:15.... asi los tres ultimos fragmentos queda claro que en caso de que se haga una reserva quedaran ocupados por ese servicio
        for ($x = 0; $x < count($array_conjunto_huecos_libres); $x++) {
            //echo $array_conjunto_huecos_libres[$x]["inicio"] . " = " . $array_conjunto_huecos_libres[$x]["fin"] . " = " . $array_conjunto_huecos_libres[$x]["contador"] . "<br>";
            //guardamos el
            $ultima_reserva_fin = $array_conjunto_huecos_libres[$x]["fin"];

            $num_horas_que_no_borramos = ((int)$array_conjunto_huecos_libres[$x]["contador"] - (int)$duracion_servicio_seleccionado);
            //echo "quedan libres " . $num_horas_que_no_borramos;
            //echo "<br>";


            $contador = 0;      //$otro_contador
            //echo "CONTADOR = " . $contador . "<br>";
            foreach (intervaloHora_alreves($array_conjunto_huecos_libres[$x]["fin"], $array_conjunto_huecos_libres[$x]["inicio"], $intervalo = 15) as $hora) {
                if ($contador + 1 < $duracion_servicio_seleccionado) {
                    $contador++;
                    //echo "BORRAMOS = " . $hora . "<br>";
                    array_push($array_horas_para_eliminar, $hora);
                } else {
                    //echo "$hora <br>";
                }

            }
            //echo "<br><br>";
        }

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
                //echo $x . " = $array_horario_horas_libres[$x]<br>";
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

        $array_horario_horas_libres = array_diff($array_horario, $array_horas_para_eliminar);

        echo "<div id='diaSeleccionado'></div>";
        foreach ($array_horario_horas_libres as $indice => $valor) {
            echo "<button value='$valor' id='hora_opcion' class='$indice' onclick='actualizar_cita(this.value, $cita, )'>" .
                "<span id='hora_numeric'>" . $valor . "</span>" .
                "<span id='buton_reservar'>Обнови резервацията</span>" .
                "</button><br>";
        }


        //echo "HAY CITAS";
    } else {
        //echo "NO HAY CITAS";

        //filtramos que cuando no haya ninguna reserva, no puede dejarnos seleccionar 22:00h (hora por defecto de fin de dia de trabajo),
        // tiene que dejarnos seleccionar la hora descontando las casillas que ocupa el servicio seleccionado para hacer la reserva
        $contador_sin_reservas = 0;      //$otro_contador
        //echo "CONTADOR = " . $contador_sin_reservas . "<br>";

        foreach (intervaloHora_alreves($horario_empleado_fin, $horario_empleado_start, $intervalo = 15) as $hora) {
            if ($contador_sin_reservas +1 < $duracion_servicio_seleccionado) {
                $contador_sin_reservas++;
                //echo "BORRAMOS = " . $hora . "<br>";
                array_push($array_horas_para_eliminar, $hora);
            } else {
                //echo "$hora <br>";
            }
        }

        $array_horario_horas_libres = array_diff($array_horario, $array_horas_para_eliminar);

        echo "<div id='diaSeleccionado'></div>";
        for ($x = 0; $x < count($array_horario_horas_libres); $x++) {

            echo "<button value='$array_horario_horas_libres[$x]' id='hora_opcion' onclick='actualizar_cita(this.value, $cita)'>" .
                "<span id='hora_numeric'>" . $array_horario_horas_libres[$x] . "</span>" .
                "<span id='buton_reservar'>Обнови резервацията</span>" .
                "</button><br>";
        }
    }
}




function getDiaSemana($date){
    $scheduled_day = $date;

    $days = ["Неделя", "Понеделник", "Вторник", "Сряда", "Четвъртък", "Петък", "Събота"];
    $day = date('w',strtotime($scheduled_day));
    return $days[$day];
}


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

    // Sacamos los periodos entres las horas
    $periodo = new DatePeriod($hora_inicio, $intervalo, $hora_fin);

    foreach ($periodo as $hora) {
        array_push($array_d, $hora->format('H:i'));
    }

    for ($x = count($array_d) - 1; $x >= 0; $x--) {

        array_push($array_d2, $array_d[$x]);
    }

    return $array_d2;
}






?>