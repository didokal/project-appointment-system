<?php
require_once("config.php");
$conexion = new mysqli(conexion_host, conexion_user, conexion_pass, conexion_bbdd, conexion_port);

$array_final = array();
$empleados_array = array();

//sacamos todos los empleados y los agregamos en un array para poder mostrar la vista vertical para cada empleado
$empleados = mysqli_query($conexion, "SELECT `idEmpleado`, `nombreEmpleado` FROM `empleados`");
$resultado_empleados = mysqli_num_rows($empleados);
while ($row2 = mysqli_fetch_array($empleados)) {
    array_push($empleados_array, $row2[0] . "-" . $row2[1]);
}

//sacamos todas las citas
$query = mysqli_query($conexion, "SELECT * FROM `citas`");
$contador = mysqli_num_rows($query);

while ($row = mysqli_fetch_array($query)) {

    //aqui comparamos quien realiza el servicio de la cita y la comparamos con el array de empleados para finalmente saber que RESOURCEID ponerle,
    //que seria el modificado por mi que consta del id_empleado . "-" . y nombre_empleado
    foreach ($empleados_array as $empleado) {
        if (strpos($empleado, $row[4])) {
            $row[4] = $empleado;
        }
    }

    $info = "";
    //aqui saco el nombre y telefono del cliente que ha hecho la cita para poder añadirlo como descripcion en la cita que se mostrara en el calendario
    $empleados = mysqli_query($conexion, "SELECT `nombreCliente`, `telefonoCliente` FROM `clientes` WHERE `idCliente` = $row[6]");
    $resultado_empleados = mysqli_num_rows($empleados);
    while ($row3 = mysqli_fetch_array($empleados)) {
        $info .= 'Nombre: ' . $row3[0] . "  |   Telefono: " . $row3[1];
    }



    $array = [
        "title" => $row[5] . "\n" .$info,
        "start" => $row[1] . "T" . $row[2],
        "end" => $row[1] . "T" . $row[3],
        "description" => $info,
        "resourceId" => $row[4]
    ];

    array_push($array_final, $array);
}
//print_r($array_final);
//print_r($empleados_array);

echo json_encode($array_final);