<?php
$conexion = new mysqli("localhost", "root", "", "citas2", "3306");

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