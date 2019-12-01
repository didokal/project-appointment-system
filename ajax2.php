<?php
$conexion = new mysqli("localhost", "root", "", "citas2", "3306");


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

        print_r($array_empleados_seleccionados);

        echo "HAS SELECIONADO $contador_empleados EMPLEADOS PARA BORRAR!";

        for ($x = 0; $x < count($array_empleados_seleccionados); $x++) {
            $borrar = "DELETE FROM empleados WHERE idEmpleado = $array_empleados_seleccionados[$x]";     //le pasamas el insert a la variable inset
            $conexion->query($borrar);
        }
    }

} else if ($_REQUEST["motivo"] == "anadir_empleado") {
    $nombre = $_REQUEST["nombre"];
    $telefono = $_REQUEST["telefono"];
    $correo = $_REQUEST["correo"];

    $query = mysqli_query($conexion, "SELECT * FROM empleados WHERE nombreEmpleado = '$nombre' AND telefonoEmpleado = $telefono AND emailEmpleado = '$correo'");
    $contador = mysqli_num_rows($query);                                                //lo que devuelve la select lo guardamos en la variable contador,

    if ($contador == 0) {
        $insertar = "INSERT INTO empleados (nombreEmpleado, telefonoEmpleado, emailEmpleado) VALUES ('$nombre', '$telefono', '$correo')";     //le pasamas el insert a la variable inset
        $conexion->query($insertar);
        if ($insertar) {
            echo "<tr><th>Nombre</th><th>Telefono</th><th>Correo electronico</th><th></th><th></th></tr>";

            $resultado = mysqli_query($conexion, "SELECT * FROM `empleados`");
            while ($row = mysqli_fetch_array($resultado)) {
                echo "<tr class='trr' id='emple$row[0]'>";
                echo "<td>$row[1]</td>";
                echo "<td>$row[2]</td>";
                echo "<td>$row[3]</td>";
                echo "<td><button type='button' id='buton_tabla' onclick='editar_servicio(\"$row[0]\")'><i class='button_imagen'></td>";
                echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]'></td>";
                echo "</tr>";
            }

        } else {
            echo "ERROR al intentar insertar el empleado en la tabla!";
        }

    } //SI EL USUARIO EXISTE
    else {
        echo "ERROR: El servicio con nombre <b>" . $nombre . "</b> ya existe!";

        mysqli_close($conexion);
    }

} else if ($_REQUEST["motivo"] == "editar_empleado") {


    if (isset($_REQUEST["empleado"])) {
        $empleado = $_REQUEST["empleado"];


        $query = mysqli_query($conexion, "SELECT * FROM empleados WHERE idEmpleado = '$empleado'");
        $contador = mysqli_num_rows($query);

        if ($contador == 1) {
            while ($row = mysqli_fetch_array($query)) {
                echo "Nombre empleado:<br><br>";
                echo "<input type='text' id='nombre_empleado2' value='$row[1]'><br><br>";

                echo "Telefono:<br><br>";
                echo "<input type='number' id='telefono_empleado2' value='$row[2]'><br><br>";

                echo "Correo electronico:<br><br>";
                echo "<input type='text' id='correo_empleado2' value='$row[3]'><br><br>";
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

        mysqli_query($conexion, "UPDATE `empleados` SET `nombreEmpleado` = '$nombre_empleado', `telefonoEmpleado` = '$telefono_empleado', `emailEmpleado` = '$correo_empleado' WHERE `idEmpleado` = $idEmpleado");


        echo "<tr><th>Nombre</th><th>Telefono</th><th>Correo electronico</th><th></th><th></th></tr>";

        $resultado = mysqli_query($conexion, "SELECT * FROM `empleados`");
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<tr class='trr' id='emple$row[0]'>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td><button type='button' id='buton_tabla' onclick='editar_servicio(\"$row[0]\")'><i class='button_imagen'></td>";
            echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]'></td>";
            echo "</tr>";
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
    echo "anadir categoria";

    if (isset($_REQUEST["categoria"])) {
        echo $_REQUEST["categoria"];
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
            echo "Se ha añadido la categoria <b>" . $categoria . "</b> con exito!";
        } //SI EL USUARIO EXISTE
        else {
            echo "ERROR: La categoria co nombre <b>" . $categoria . "</b> ya existe!";

            mysqli_close($conexion);
        }
    }


} else if ($_REQUEST["motivo"] == "mostrar_categorias") {
    echo "<tr><th>Nombre</th><th></th></tr>";

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

    if (isset($_REQUEST["servicio"])) {
        $servicio = $_REQUEST["servicio"];
        $duracion = $_REQUEST["duracion"];
        $categoria = $_REQUEST["categoria"];

        echo $servicio;
        echo $duracion;
        echo $categoria;
        echo "<br><br>";


        $query = mysqli_query($conexion, "SELECT * FROM servicios WHERE nombreServ = '$servicio'");
        $contador = mysqli_num_rows($query);                                                //lo que devuelve la select lo guardamos en la variable contador,
        //en nuestro caso devolver un 0 si no existe el usuarios y 1 si existe

        //sacamos el ID de la categoria para pasarsela al insert a la hora de ejecutar la SQL
        $query2 = mysqli_query($conexion, "SELECT idCategory FROM categorias WHERE nameCat = '$categoria'");
        $row = mysqli_fetch_assoc($query2);
        echo "<h1>" . $row['idCategory'] . "</h1>";
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
            echo "ERROR: El servicio con nombre <b>" . $servicio . "</b> ya existe!";

            mysqli_close($conexion);
        }


    }
} else if ($_REQUEST["motivo"] == "mostrar_servicios") {
    echo "<tr><th>Nombre</th><th>Duracion</th><th>Categoria</th><th></th><th></th></tr>";

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
        $contador = mysqli_num_rows($query);                                                //lo que devuelve la select lo guardamos en la variable contador,
        //en nuestro caso devolver un 0 si no existe el usuarios y 1 si existe

        //SI EL USUARIO NO EXISTE
        if ($contador == 1) {


            while ($row = mysqli_fetch_array($query)) {
                echo "Nombre servicio:<br><br>";
                echo "<input type='text' id='nombre_servicio2' value='$row[1]'><br><br>";

                echo "Duracion servicio:<br><br>";
                echo "<input type='number' id='duracion_servicio2' value='$row[2]'><br><br>";

                echo "Elige categoria:<br><br>";
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
            echo "ERROR: El servicio con nombre <b>" . $servicio . "</b> ya existe!";

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


        mysqli_query($conexion, "UPDATE `servicios` SET `nombreServ` = '$nombre_serv', `duracionServ` = '$duracion_serv', `idCategoria` = '$id_category' WHERE `idServicio` = $idServicio");


        echo "<tr><th>Nombre</th><th>Duracion</th><th>Categoria</th><th></th><th></th></tr>";

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
    }
} else if ($_REQUEST["motivo"] == "mostrar_servicios2") {
    if (!empty($_REQUEST["idEmpleado"])) {
        $idEmpleado = $_REQUEST["idEmpleado"];


        echo "<table id=\"tabla\">";
        echo "<tr><th>Si/No</th><th>Nombre servicio</th><th class='input_numer_precio'>Precio</th></tr>";


        $resultado = mysqli_query($conexion, "SELECT * FROM `servicios`");
        while ($row = mysqli_fetch_array($resultado)) {

            $comprobar_servicios_empleado = mysqli_query($conexion, "SELECT precio FROM servicios_empleados WHERE nombreServ = '$row[1]' AND idEmpleado = $idEmpleado");
            $contador = mysqli_num_rows($comprobar_servicios_empleado);


            echo $contador;


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
            "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"
        );
        $array_horario_primera_vez = array(
            "08:00", "09:00",
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
                echo "<span style=\"width:100px; display:inline-block\"><b>$array_dias_semana[$y]</b></span>";

                echo "<span style=\"width:130px; display:inline-block\">";
                echo "<select style='width: 80px; display: inline-block' id='start_$array_dias_semana[$y]'>";
                for ($x = 0; $x < count($array_horario_primera_vez); $x++) {
                    echo "<option value='$array_horario_primera_vez[$x]'>$array_horario_primera_vez[$x]</option>";
                }
                echo "</select>";
                echo "</span>";
                echo "<span style=\"width:100px; display:inline-block\">hasta</span>";


                echo "<span style=\"width:100px; display:inline-block\">";
                echo "<select style='width: 80px; display: inline-block' id='fin_$array_dias_semana[$y]'>";
                for ($x = count($array_horario_primera_vez)-1 ; $x >=0 ; $x--) {
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
                        echo "<span style=\"width:100px; display:inline-block\"><b>$array_dias_semana[$y]</b></span>";
                        echo "<span style=\"width:130px; display:inline-block\">";
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
                        echo "<span style=\"width:100px; display:inline-block\">hasta</span>";

                        echo "<span style=\"width:100px; display:inline-block\">";
                        echo "<select style='width: 80px; display: inline-block' id='fin_$array_dias_semana[$y]'>";
                        for ($x = count($array_horario_primera_vez)-1 ; $x >=0 ; $x--) {
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
                    $dia = "Lunes";
                } elseif ($d == 1) {
                    $dia = "Martes";
                } elseif ($d == 2) {
                    $dia = "Miercoles";
                } elseif ($d == 3) {
                    $dia = "Jueves";
                } elseif ($d == 4) {
                    $dia = "Viernes";
                } elseif ($d == 5) {
                    $dia = "Sabado";
                } elseif ($d == 6) {
                    $dia = "Domingo";
                }

                mysqli_query($conexion, "INSERT INTO `horario_semanal` (`idHorarioSem`, `horaStart`, `horaFin`, `diaSemana`, `idEmple`) VALUES (NULL, '$array_horarios_start_convertido[$d]', '$array_horarios_fin_convertido[$d]', '$dia', '$idEmpleado')");
            }
        } else {
            for ($d = 0; $d < count($array_horarios_start_convertido); $d++) {
                $dia = "";

                if ($d == 0) {
                    $dia = "Lunes";
                } elseif ($d == 1) {
                    $dia = "Martes";
                } elseif ($d == 2) {
                    $dia = "Miercoles";
                } elseif ($d == 3) {
                    $dia = "Jueves";
                } elseif ($d == 4) {
                    $dia = "Viernes";
                } elseif ($d == 5) {
                    $dia = "Sabado";
                } elseif ($d == 6) {
                    $dia = "Domingo";
                }
                //echo "$d = $dia = update => $array_horarios_start_convertido[$d]<br>";
                mysqli_query($conexion, "UPDATE `horario_semanal` SET `horaStart` = '$array_horarios_start_convertido[$d]', `horaFin` = '$array_horarios_fin_convertido[$d]' WHERE `idEmple` = '$idEmpleado' AND `diaSemana` = '$dia'");
            }
        }
    }
}





