<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "citas2", "3306");

if ($conexion->connect_error) {
    die("Error conexion bd: " . $conexion->connect_error);
}
//echo "Conexion realazada con exito";
$separador_id_nombre_empleado = array();

//array donde vamos a guardar los servicios disponibles en la tabla de servicios acompañados de un numero asociado a cada servicio
//lo necesito a la hora te asignar servicios, porque al enviar el formulario el array esta formada por tantos campos como haya marcado
$contar_servicios_asignar_num_correspondiente_precio = array();

?>

<html>
<head>
    <style>
        tr {
            border: 0
        }

        td {
            border: 0;
            padding-right: 15px
        }

        th {
            border: 0
        }
    </style>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h1>Menu</h1>
<ul id="temporal_ul">
    <li><a href="admin-panel.php">Admin panel</a></li>
    <li><a href="pedir_cita.php">Pedir cita</a></li>
    <li><a href="empleados.php">Empleados</a></li>
    <li><a href="categorias.php">Categorias</a></li>
    <li><a href="servicios.php">Servicios</a></li>
    <li><a href="calendario_show_appointments.html">Calendario con citas</a></li>
    <li><a href="clientes.php">Clientes</a></li>
    <li><a href="citas.php">Citas</a></li>
</ul>
<hr style="color: #0056b2" />
<br><br><br>
<table border="3" style="padding: 15px; margin-bottom:50px">
    <tr>
        <td>
            <h2 style="color: #00a699"><u>Añadir categoria</u></h2>
        </td>
    </tr>
    <tr>
        <form action="admin-panel.php" method="post">
            <td>Nombre de la categoria:
                <input type="text" name="nom_categoria" required>
                <input type="submit" value="Añadir">
            </td>
        </form>
    </tr>
</table>


<table border="3" style="padding: 15px; margin-bottom:50px">
    <tr>
        <td>
            <h2 style="color: #00a699"><u>Añadir servicio</u></h2>
        </td>
    </tr>
    <tr>
        <form action="admin-panel.php" method="post">
            <td>Nombre del servicio:
                <input type="text" name="nom_servicio" required>
            </td>
            <td>Duracion del servicio:
                <input type="number" name="duracion_servicio" required>
            </td>
            <td>Categoria:
                <select name="categoria_escogida">
                    <?php
                    $resultado = mysqli_query($conexion, "SELECT * FROM `categorias`");
                    while ($row = mysqli_fetch_array($resultado)) {
                        echo "<option value='$row[1]'>" . $row[1] . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Añadir">
            </td>


        </form>
    </tr>
</table>


<table border="3" style="padding: 15px; margin-bottom:50px">
    <tr>
        <td>
            <h2 style="color: #00a699"><u>Añadir empleado</u></h2>
        </td>
    </tr>
    <tr>
        <form action="admin-panel.php" method="post">
            <td>Nombre completo:
                <input type="text" name="nombre_empleado" required>
            </td>
            <td>Telefono:
                <input type="number" name="telefono_empleado" required>
            </td>
            <td>Correo electronico:
                <input type="email" name="correo_empleado" required>
                <input type="submit" value="Añadir">
            </td>
        </form>
    </tr>
</table>


<table border="3" style="padding: 15px; margin-bottom:50px">
    <tr>
        <td>
            <h2 style="color: #00a699"><u>Asignar servicios a un empleado</u></h2>
        </td>
    </tr>
    <tr>
        <form action="admin-panel.php" method="post">
            <td>Selecciona empleado:
                <select name='empleado_seleccionado'>
                    <?php
                    $resultado = mysqli_query($conexion, "SELECT * FROM `empleados`");
                    while ($row = mysqli_fetch_array($resultado)) {
                        echo "<option  value='$row[0]-$row[1]'>" . $row[1] . "</option >";
                    }
                    ?>
                </select>
                <input type="submit" value="Seleccionar">
            </td>
        </form>
    </tr>
</table>


<?php
$mensaje = "";
$update = false;
$insert = false;

const SERVICIO_CORTAR_PELO = 1;

/// borrar un servicio asignado
/// problema precio si seleccionas el ultimo servicio al ser un foreach que empieza por 0 1 2 3 ...
/// problema con el idEmpleado, que lo he puesto manualmente...

if (isset($_POST["empleado_seleccionado"])) {
    //por el formulario me llega el idEmpleado y nombreEmpleado en el mismo value, lo que hago aqui es seprarlos
    $_SESSION['empleado_seleccionado'] = $_POST["empleado_seleccionado"];
    $separador_id_nombre_empleado = explode("-", $_POST["empleado_seleccionado"]);
    echo $separador_id_nombre_empleado[0]; // idEmpleado seleccionado
    echo $separador_id_nombre_empleado[1]; // nombreEmpleado seleccionado
    $mensaje = $mensaje . $_POST["empleado_seleccionado"];  //aqui vemos todo junto

    echo '<form action="admin-panel.php" method="post">';
    echo '<table id="tabla" border="1">';
    echo '<tr>';
    echo '<th>Si/No</th>';
    echo '<th>Nonbre servicio</th>';
    echo '<th>Precio</th>';
    echo '</tr>';

    //comprobamos todos los servicios existentes en la tabla se de servicios que los mostraremos mas adelante
    $resultado = mysqli_query($conexion, "SELECT * FROM `servicios`");
    //ponemos un contador para poder asociar servicio con un numero
    $x = 1;

    //utilizaremos los datos de la select de arriba para rellenar el array multidimensional para poder asignar a cada servicio un numero que viene del contador
    //el contador ira aumentando tantas veces como servicios haya en la tabla de servicios
    while ($row = mysqli_fetch_array($resultado)) {
        $contar_servicios_asignar_num_correspondiente_precio[] = array("nomb_serv" => $row[1], "num_corrspndent" => $x);
        //array_push($contar_servicios_asignar_num_correspondiente_precio, array("nomb_serv" => $row[0], "num_corrspndent" => $x));


        echo "<tr>asdaasdasdasdadssd";
        echo "<td>";
        //se comprueba si el servicio que viene del form ya se encuentra en la tabla de servicios_empleados asociado al idEmpleado
        $query = mysqli_query($conexion, "SELECT precio FROM servicios_empleados WHERE nombreServ = '$row[1]' AND idEmpleado = $separador_id_nombre_empleado[0]");
        $contador = mysqli_num_rows($query);                                                //lo que devuelve la select lo guardamos en la variable contador,

        //si el empleado no tiene ese servicio
        if ($contador == 0) {
            echo "<input type='checkbox' name='servicios[]' value='$row[1]'></td>";
            echo "<td value='$row[1]'>" . $row[1] . "</td>";
            echo "<td><input type='number' name='precios[]' value='0.00'></td>";
            echo "</tr>";
        } //si el empleado tiene ese servicio
        else {
            echo "<input type='checkbox' name='servicios[]' value='$row[1]' checked></td>";     //el servicio se marcara como asignado
            echo "<td value='$row[1]'>" . $row[1] . "</td>";
            while ($row = mysqli_fetch_array($query)) {
                echo "<td><input type='number' name='precios[]' value='$row[0]'></td>";         //mostramos el precio corrspondiente de ese servicio
            }
            echo "</tr>";
        }
        $x++;       //aumentamos la x
    }


    $_SESSION['contar_servicios_asignar_num_correspondiente_precio'] = $contar_servicios_asignar_num_correspondiente_precio;


    echo '<tr>';
    echo '<td><input type="submit" value="Guardar cambios" name="asignar_servicios"></td>';
    echo '</tr>';
    echo "</table>";
    echo '</form>';
    //echo $contar_servicios_asignar_num_correspondiente_precio[0]["nomb_serv"]. "<br>";          // arreglo barba
    //echo $contar_servicios_asignar_num_correspondiente_precio[0]["num_corrspndent"]. "<br><br><br>";    // 1

    //echo $contar_servicios_asignar_num_correspondiente_precio[1]["nomb_serv"]. "<br>";          // cortar pelo
    //echo $contar_servicios_asignar_num_correspondiente_precio[1]["num_corrspndent"]. "<br>";    // 2
}


if (isset($_POST["asignar_servicios"])) {


    echo "---------------------------------------------------------------------";
    $contar_servicios_asignar_num_correspondiente_precio = $_SESSION['contar_servicios_asignar_num_correspondiente_precio'];


    $separador_id_nombre_empleado = explode("-", $_SESSION['empleado_seleccionado']);
    echo $separador_id_nombre_empleado[0]; // idEmpleado seleccionado
    echo $separador_id_nombre_empleado[1]; // nombreEmpleado seleccionado



    print_r($contar_servicios_asignar_num_correspondiente_precio);


    echo "---------------------------------------------------------------------";






    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo $_POST["precios"][0];      //muestra EL PRECIO QUE ESCRIBO del servicio >>> arreglo barba
    echo "<br>";
    echo $_POST["precios"][1];      //muestra EL PRECIO QUE ESCRIBO del servicio >>> cortar pelo
    echo "<br>";
    echo "<br>";


    echo count($_POST["servicios"]);
    print_r($_POST["servicios"]);





    ///codigo que eliminar servicios que ya estaban asignados a un empleado
    ///obligatorio que una vez enviado el formulario primero se borren los servicios de la tabla que han sido desmarcados con el checked y despues se haga la insercion de los nuevos servicios en caso de que haya
    /// si colo el codigo despues de la insercion, la insercion nunca se hara
    $resultado = mysqli_query($conexion, "SELECT * FROM servicios_empleados WHERE idEmpleado = 5");

    while ($row = mysqli_fetch_array($resultado)) {

        foreach($_POST["servicios"] as $valor){

            if($valor == $row[3]){
                echo '<span style="color:green">' .$valor . ' y '  . $row[3] . ' SON IGUALES</span><br>';



            }else{
                echo '<span style="color:red">' .$valor . ' y '  . $row[3] . ' NO SON IGUALES</span><br>';
                $borrar = "DELETE FROM servicios_empleados WHERE idEmpleado = $separador_id_nombre_empleado[0] AND nombreServ = '$row[3]'";     //le pasamas el insert a la variable inset
                $conexion->query($borrar);
            }

        }




    }








    for ($x = 0; $x < count($_POST["servicios"]); $x++) {
        echo $nombre_servicio = $_POST["servicios"][$x];
        echo $precio = $_POST["precios"][$x];               //https://www.php.net/manual/es/function.array-reverse.php          o usar FOR MEJHOR




        for ($y = 0; $y <= count($contar_servicios_asignar_num_correspondiente_precio) - 1; $y++) {
            if ($nombre_servicio == $contar_servicios_asignar_num_correspondiente_precio[$y]['nomb_serv']) {

                echo "<ul>";
                echo "<li>$nombre_servicio</li>";
                echo "<li> " . $nummmmmmm = $contar_servicios_asignar_num_correspondiente_precio[$y]['num_corrspndent'] . "</li>";
                echo "<li>" . $precio = $_POST["precios"][$y] . "</li>";
                echo "</ul>";


                /////comprobar si existe el servicio en la tabla de servicios_empleados
                $query = mysqli_query($conexion, "SELECT * FROM servicios_empleados WHERE nombreServ = '$nombre_servicio' AND idEmpleado = $separador_id_nombre_empleado[0]");
                $contador = mysqli_num_rows($query);





                //SI EL SERVICIO NO EXISTE
                if ($contador == 0) {
                    echo "<ul>";
                    echo "<li>$precio</li>";
                    echo "<li>$separador_id_nombre_empleado[0]</li>";
                    echo "<li>$nombre_servicio</li>";
                    echo "</ul>";


                    $insertar = "INSERT INTO servicios_empleados (idServEmple, precio, idEmpleado, nombreServ) VALUES (0, '$precio', $separador_id_nombre_empleado[0], '$nombre_servicio')";     //le pasamas el insert a la variable inset


                    $conexion->query($insertar);
                    echo "<h3>INSERT " . $precio . "</h3>";
                    if ($insertar) {

                        echo "Añadido con exito";
                    } else {
                        echo "Error al añadir!";
                    }
                }

                //SI EL SERVICIO EXISTE
                else {
                    $actualizar = "UPDATE servicios_empleados SET precio = '$precio' WHERE idEmpleado = $separador_id_nombre_empleado[0] AND nombreServ = '$nombre_servicio'";     //le pasamas el insert a la variable inset
                    $conexion->query($actualizar);
                    echo "<h3>UPDATE " . $precio . "</h3>";
                    if ($actualizar) {
                        echo "Cambio realizado con exito";
                    } else {
                        echo "Error al realizar el cambio!";
                    }
                }







            }
            else {
                echo "no es";
            }





        }







    }




}

?>










<?php
if (isset($_POST["nom_categoria"])) {
    $nom_categoria = $_POST["nom_categoria"];

    $query = mysqli_query($conexion, "SELECT * FROM categorias WHERE nameCat = '$nom_categoria'");
    $contador = mysqli_num_rows($query);                                                //lo que devuelve la select lo guardamos en la variable contador,
    //en nuestro caso devolver un 0 si no existe el usuarios y 1 si existe
    $categoria_existente = false;

    //SI EL USUARIO NO EXISTE
    if ($contador == 0) {
        $insertar = "INSERT INTO categorias (idCategory, nameCat) VALUES (NULL, '$nom_categoria')";     //le pasamas el insert a la variable inset
        $conexion->query($insertar);                                                   //aqui es donde realmente insertamos el usuario en la bd            https://www.w3schools.com/php/php_mysql_insert.asp
        $categoria_existente = true;                                                     //una vez insertado el usuario ponemos la variable $usuario_insertado a true
        mysqli_close($conexion);
        $mensaje = $mensaje . "Se ha añadido la categoria <b>" . $nom_categoria . "</b> con exito!";
    } //SI EL USUARIO EXISTE
    else {
        $mensaje = $mensaje . "ERROR: La categoria co nombre <b>" . $nom_categoria . "</b> ya existe!";

        mysqli_close($conexion);
    }
}


///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////


if (isset($_POST["nom_servicio"])) {
    $mensaje = $mensaje . $nom_servicio = $_POST["nom_servicio"];
    $mensaje = $mensaje . "<br>";
    $mensaje = $mensaje . $duracion_servicio = $_POST['duracion_servicio'];
    $mensaje = $mensaje . "<br>";
    $mensaje = $mensaje . $categoria_escogida = $_POST['categoria_escogida'];
    $mensaje = $mensaje . "<br>";

    $query = mysqli_query($conexion, "SELECT * FROM servicios WHERE nombreServ = '$nom_servicio'");
    $contador = mysqli_num_rows($query);                                                //lo que devuelve la select lo guardamos en la variable contador,
    //en nuestro caso devolver un 0 si no existe el usuarios y 1 si existe
    echo $contador;

    //sacamos el ID de la categoria para pasarsela al insert a la hora de ejecutar la SQL
    $query2 = mysqli_query($conexion, "SELECT idCategory FROM categorias WHERE nameCat = '$categoria_escogida'");
    $row = mysqli_fetch_assoc($query2);
    echo "<h1>" . $row['idCategory'] . "</h1>";
    $id_category = $row['idCategory'];

    //SI EL USUARIO NO EXISTE
    if ($contador == 0) {
        $insertar = "INSERT INTO servicios (nombreServ, duracionServ, idCategoria) VALUES ('$nom_servicio', $duracion_servicio, $id_category)";     //le pasamas el insert a la variable inset
        $conexion->query($insertar);
        if ($insertar) {
            //aqui es donde realmente insertamos el usuario en la bd            https://www.w3schools.com/php/php_mysql_insert.asp
            $mensaje = $mensaje . "Se ha añadido el servicio <b>" . $nom_servicio . "</b> con exito!";
        } else {
            $mensaje = $mensaje . "ERROR al intentar insertar el servicio en la tabla!";
        }

    } //SI EL USUARIO EXISTE
    else {
        $mensaje = $mensaje . "ERROR: El servicio con nombre <b>" . $nom_servicio . "</b> ya existe!";

        mysqli_close($conexion);
    }
}


///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////


if (isset($_POST["nombre_empleado"])) {
    $mensaje = $mensaje . $nombre_empleado = $_POST["nombre_empleado"];
    $mensaje = $mensaje . "<br>";
    $mensaje = $mensaje . $telefono_empleado = $_POST['telefono_empleado'];
    $mensaje = $mensaje . "<br>";
    $mensaje = $mensaje . $correo_empleado = $_POST['correo_empleado'];
    $mensaje = $mensaje . "<br>";

    $query = mysqli_query($conexion, "SELECT * FROM empleados WHERE telefonoEmpleado = '$telefono_empleado' OR emailEmpleado = '$correo_empleado'");

    $contador = mysqli_num_rows($query);                                                //lo que devuelve la select lo guardamos en la variable contador,
    //en nuestro caso devolver un 0 si no existe el usuarios y 1 si existe
    $categoria_existente = false;

    //SI EL USUARIO NO EXISTE
    if ($contador == 0) {
        $insertar = "INSERT INTO empleados (idEmpleado, nombreEmpleado, telefonoEmpleado, emailEmpleado) VALUES (NULL, '$nombre_empleado', '$telefono_empleado', '$correo_empleado')";     //le pasamas el insert a la variable inset
        $conexion->query($insertar);                                                   //aqui es donde realmente insertamos el usuario en la bd            https://www.w3schools.com/php/php_mysql_insert.asp
        $categoria_existente = true;                                                     //una vez insertado el usuario ponemos la variable $usuario_insertado a true
        mysqli_close($conexion);
        $mensaje = $mensaje . "Se ha añadido el empleado con nombre <b>" . $nombre_empleado . "</b> con exito!";
    } //SI EL USUARIO EXISTE
    else {
        $mensaje = $mensaje . "ERROR: Ya existe un empleado con alguno/s de los datos introducidos! Comprueba los empleados existentes!";

        mysqli_close($conexion);
    }
}
?>


<table border="3" style="padding: 15px; margin-top:210px; position: absolute; top: -40px; left: 50%">
    <tr>
        <td>
            <h1 style="color: #b43e36"><u>Mensaje</u></h1>
        </td>
    </tr>
    <tr>
        <td>
            <?php
            echo $mensaje;
            ?>
        </td>
    </tr>
</table>




</body>
</html>