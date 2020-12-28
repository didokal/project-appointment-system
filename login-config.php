<?php

$function = ""; //Compruebo que es lo que quieres hacer con algun usuario
if (isset($_POST["function"])) {
    $function = $_POST["function"];
} else {
    die(http_response_code(501));
}

switch ($function) { //Elijo la opcion deseada
    case "loginEmpleado":
        $user = "";
        if (isset($_POST["user"])) {
            $user = $_POST["user"];
        } else {
            echo "u";
            die(http_response_code(501));
        }

        $password = "";
        if (isset($_POST["pass"])) {
            $password = $_POST["pass"];
        } else {
            die(http_response_code(501));
        }

        echo loginUser($user, $password, "empleados"); //Devuelvo el tipo de usuario y registro sus datos en la sesion
        break;
    case "loginCliente":
        $user = "";
        if (isset($_POST["correo1"])) {
            $user = $_POST["correo1"];
        } else {
            die(http_response_code(501));
        }

        $password = "";
        if (isset($_POST["pass"])) {
            $password = $_POST["pass"];
        } else {
            die(http_response_code(501));
        }

        echo loginUser($user, $password, "clientes"); //Devuelvo el tipo de usuario y registro sus datos en la sesion
        break;
    default:
        die(http_response_code(501));
}

function loginUser($user, $password, $type) //admin, clientes, empleados
{
    session_start();

    require_once("config.php");
    $conexion = new mysqli(conexion_host, conexion_user, conexion_pass, conexion_bbdd, conexion_port);

    if ($conexion->connect_error) {
        die("Error conexion bd: " . $conexion->connect_error);
    }

    if(isset($_POST["type"]) && $_POST["type"] == "admin"){
        if($user == "admin" && $password == "admin"){
            $_SESSION["permiso"] = "admin";
            return 2;
        }else{
            return -1;
        }
    }

    $resultado = null;
    if($type == "empleados"){
        $resultado = mysqli_query($conexion, "SELECT * FROM empleados where nombreEmpleado = '$user' and contrasena = '$password';");
    }else{
        $resultado = mysqli_query($conexion, "SELECT * FROM clientes where correoCliente = '$user' and contrasena = '$password';");
    }

    if (!$resultado || $resultado->num_rows <= 0)
        return -1;

    $res = $resultado->fetch_assoc();

    if($type == "clientes"){
        $_SESSION["permiso"] = "cliente";
        $_SESSION["email"] = $res["correoCliente"];
        $_SESSION["pass"] = $res["contrasena"];
    }else{
        $_SESSION["permiso"] = "user";
        $_SESSION["nombreEmpleado"] = "$user";
        $_SESSION["email"] = $res["emailEmpleado"];
    }

    $resultado->close();

    return 1;
}