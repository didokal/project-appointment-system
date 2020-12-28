<?php
//documento ajax que se utiliza solo por el fichero "cliente-cuenta.php" o dicho en otras palabras, por el login que hay en la pagina principal
require_once("config.php");
$conexion = new mysqli(conexion_host, conexion_user, conexion_pass, conexion_bbdd, conexion_port);

if ($_REQUEST["motivo"] == "cambiar_contraseña") {
    session_start();

    if (empty($_REQUEST["pass_actual"]) && empty($_REQUEST("pass_nuevo1")) && empty($_REQUEST("pass_nuevo2"))) {
        echo "No has mandado todos los datos!";
    } else {
        if ($_REQUEST["pass_actual"] == $_SESSION["pass"] and $_REQUEST["pass_nuevo1"] == $_REQUEST["pass_nuevo2"]) {
            $correo = $_SESSION["email"];
            $new_pass = $_REQUEST["pass_nuevo1"];

            $update = "update `clientes` set contrasena='$new_pass' where correoCliente = '$correo'";

            $result = mysqli_query($conexion, $update);

            echo "La contrasena ha sido cambiada!";

        } else {
            echo "Has introducido mal la contrasena vieja o la nueva contrasena no coninciden!";
        }


    }
} else if ($_REQUEST["motivo"] == "crear_cuenta") {


    if (empty($_REQUEST["nombre"]) || empty($_REQUEST["password"]) || empty($_REQUEST["correo"]) || empty($_REQUEST["telefono"])) {
        echo "Не сте попълнили всички полета!";
    } else {
        $nombre = $_REQUEST["nombre"];
        $password = $_REQUEST["password"];
        $correo = $_REQUEST["correo"];
        $telefono = $_REQUEST["telefono"];

        $query = mysqli_query($conexion, "SELECT * FROM clientes WHERE correoCliente = '$correo'");
        $contador = mysqli_num_rows($query);


        if ($contador == 0) {
            $insertar = "INSERT INTO clientes (nombreCliente, telefonoCliente, correoCliente, contrasena) VALUES ('$nombre', '$telefono', '$correo', '$password')";     //le pasamas el insert a la variable inset
            $conexion->query($insertar);
            echo "El usuario ha sido creado";
        }else{
            $update = "update `clientes` set contrasena='$password' where correoCliente = '$correo'";

            $result = mysqli_query($conexion, $update);
            echo "La contaseña ha sido agregada";
        }

    }
}


?>