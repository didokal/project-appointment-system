<?php
require_once("config.php");
$conexion = new mysqli(conexion_host, conexion_user, conexion_pass, conexion_bbdd, conexion_port);

$array_final = array();
$empleados_array = array();



$empleados = mysqli_query($conexion, "SELECT `idEmpleado`, `nombreEmpleado` FROM `empleados`");
$resultado_empleados = mysqli_num_rows($empleados);
while ($row2 = mysqli_fetch_array($empleados)) {

    $array = [
      "id" => $row2[0]."-".$row2[1],
       "title" => $row2[1]
    ];

    array_push($empleados_array, $array);
}

//print_r($empleados_array);



echo json_encode($empleados_array);