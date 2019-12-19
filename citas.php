<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Citas</title>
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
<hr style="color: #0056b2"/>
<br><br><br>
<div>
    <h1 style="float: left">Citas</h1>
    <div style="float: right; padding-top: 20px;">
        <span style="display: inline-block;">
        <select id="tipo_valor_buscar">
            <option>Buscar por</option>
            <option>Fecha</option>
            <option>Nombre empleado</option>
            <option>Servicio</option>
            <option>Nombre cliente</option>
        </select>
            </span>
        <span style="display: inline-block;"><input type="text" id="buscador" onkeypress="miBuscador()"
                                                    onKeyUp="miBuscador()"></span>
    </div>
</div>
<table id="tabla">
    <tr>
        <th>Fecha</th>
        <th>Hora inicio</th>
        <th>Hora fin</th>
        <th>Nombre empleado</th>
        <th>Servicio</th>
        <th>Nombre cliente</th>
        <th></th>
        <th></th>
    </tr>


    <script>
        var tipo_valor_a_buscar_escogido;

        var select = document.querySelector('#tipo_valor_buscar'),
            input = document.querySelector('input[type="button"]');
        select.addEventListener('change', function () {
            tipo_valor_a_buscar_escogido = document.getElementById("tipo_valor_buscar").value
        });

        function miBuscador() {
            var edValue = document.getElementById("buscador");
            var s = edValue.value;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);

                    if (this.responseText.includes("<tr class='trr'")) {
                        document.getElementById("tabla").innerHTML = '';
                        document.getElementById("tabla").innerHTML = this.responseText;
                    } else {
                        document.getElementById("tabla").innerHTML = 'Sin resultados';
                    }
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=buscador_citas" + "&cadena=" + s + "&tipo_de_valor=" + tipo_valor_a_buscar_escogido);
            xhttp.send();
        }
    </script>
    <?php
    session_start();
    $conexion = new mysqli("localhost", "root", "", "citas2", "3306");

    if ($conexion->connect_error) {
        die("Error conexion bd: " . $conexion->connect_error);
    }


    $resultado = mysqli_query($conexion, "SELECT * FROM `citas`");
    while ($row = mysqli_fetch_array($resultado)) {
        echo "<tr class='trr' id='cita$row[0]'>";
        echo "<td>$row[1]</td>";
        echo "<td>" . substr($row[2], 0, 5) . "</td>";
        echo "<td>" . substr($row[3], 0, 5) . "</td>";
        echo "<td>$row[4]</td>";
        echo "<td>$row[5]</td>";

        $resultado2 = mysqli_query($conexion, "SELECT * FROM `clientes` WHERE `idCliente` = $row[6]");
        if (mysqli_num_rows($resultado2) == 0) {
            echo "<td>Cliente borrado</td>";
        } else {
            $row2 = mysqli_fetch_array($resultado2);
            echo "<td>$row2[1]</td>";
        }
        echo "<td><button type='button' id='buton_tabla' onclick='editar_cita(\"$row[0]\")'><i class='button_imagen'></td>";
        echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
        echo "</tr>";
    }
    ?>
</table>
<div style="margin-top: 20px">
    <button id="buton_borrar"><span class="icon_borrar">  Eliminar cita</span></button>
</div>

<div id="miSlidePanel2" class="slidePanel2">
    <div class="slidePanel-content2">
        <div id="titulo">
            <h2>Actualizar cita</h2>
        </div>
        <span style="color:darkred">AVISO: Se ha creado la ventana para cambiar la cita pero no se ha contemplado ningun posible error. Los datos que metas en muchas ocasiones generaran error al guardar. Esta funcion no funciona!</span><br><br>
        <span id="alert-text2">
                Fecha:<br><br>
                <input type="text" id="fecha2"><br><br>

                Hora inicio:<br><br>
                <input type="number" id="hora_inicio2"><br><br>

                Hora fin:<br><br>
                <input type="text" id="hora_fin2">

                Nombre empleado:<br><br>
                <input type="text" id="nombre_empleado2">

                Servicio:<br><br>
                <input type="text" id="servicio2">

                Nombre cliente:<br><br>
                <input type="text" id="nombre_cliente2">
            <br>
            </span>
        <div id="alert-footer">
            <span id="alert_actualizar2">Actualizar</span>
            <span id="alert_cancelar2">Cancelar</span>
        </div>
    </div>
</div>

<!--
<div id="miAlerta" class="alerta">
    <div class="alerta-content">

        <span id="alert-text3"></span>
        <div id="alert-footer">
            <span id="alert_actualizar" onclick="alert_update()">Actualizar</span>
            <span id="alert_cancelar" onclick="alert_cancel()">Cancelar</span>
        </div>
    </div>
</div>


<div id="miAlerta4" class="alerta">
    <div class="alerta-content">
        <span id="alert-text6"></span>
        <div id="alert-footer">
            <span id="alert_actualizar" onclick="alert_back3()">Volver y corregir los datos introducidos</span>
            <span id="alert_cancelar" onclick="alert_cancel()">Cancelar</span>
        </div>
    </div>
</div>

-->
<div id="miAlerta2" class="alerta">
    <div class="alerta-content">

        <span id="alert-text4"></span>
        <div id="alert-footer">
            <span id="alert_ok" onclick="alert_ok()">OK</span>
        </div>
    </div>
</div>

<script>
    var datos = "";
    var citas_para_borrar = [];
    var id_cita = "";
    var valParam;


    function alert_update() {
        document.getElementById("miAlerta").style.display = "none";

        var nombrecliente = document.getElementById('nombre_cliente').value;
        var telefonocliente = document.getElementById('telefono_cliente').value;
        var correoelectronico = document.getElementById('correo_electronico_cliente').value;

        console.log(nombrecliente);
        console.log(telefonocliente);
        console.log(correoelectronico);

        var xhttp2 = new XMLHttpRequest();
        xhttp2.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);

                document.getElementById("tabla").innerHTML = '';
                document.getElementById("tabla").innerHTML = this.responseText;

                document.getElementById("alert-text4").innerHTML = "Los datos del usuario con telefono " + telefonocliente + " se actualizarón correctamente!";
                document.getElementById("miAlerta2").style.display = "block";
            }
        };
        xhttp2.open("POST", "ajax2.php?motivo=actualizar_cliente_de_alerta" + "&nombre_cliente=" + nombrecliente + "&telefono_cliente=" + telefonocliente + "&correo_electronico=" + correoelectronico);
        xhttp2.send();
    }

    /*
        function alert_cancel() {
            document.getElementById("miAlerta").style.display = "none";
        }

        function alert_back3() {
            document.getElementById("miAlerta4").style.display = "none";
            document.getElementById("miSlidePanel2").style.display = "block";
        }

*/
    function alert_ok() {
        document.getElementById("miAlerta2").style.display = "none";
    }

    document.getElementById("buton_borrar").addEventListener("click", function () {
        // Query for only the checked checkboxes and put the result in an array
        let checked = Array.prototype.slice.call(document.querySelectorAll("input[type='checkbox']:checked"));
        console.clear();
        // Loop over the array and inspect contents
        checked.forEach(function (cb) {
            console.log("Datos antes de enviar AJAX " + cb.value);

            //separamos los datos que nos llegan del value en un array
            var datos_separados = cb.value.split("|.|");
            citas_para_borrar.push(datos_separados);

            //convertimos el array en json para poder enviarlo por el post de ajax
            valParam = JSON.stringify(citas_para_borrar);
        });

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                console.log(citas_para_borrar);

                //eliminamos los divs con los servicios seleccionados
                for (var x = 0; x < citas_para_borrar.length; x++) {
                    document.getElementById("cita" + citas_para_borrar[x][0]).innerHTML = '';
                }
            }
        };
        xhttp.open("POST", "ajax2.php?motivo=eliminar_cita" + "&citas=" + valParam);
        xhttp.send();

        document.getElementById("alert-text4").innerHTML = "La operación ha sido realizada con exito!";
        document.getElementById("miAlerta2").style.display = "block";
    });


    /*
        document.getElementById("alert_cancelar").addEventListener("click", function () {
            document.getElementById("miSlidePanel").style.display = "none";
        });
    */
    document.getElementById("alert_cancelar2").addEventListener("click", function () {
        document.getElementById("miSlidePanel2").style.display = "none";
    });


    function editar_cita(cita) {
        id_cita = cita;
        console.log("CITA QUE VAS A EDITAR >>> " + id_cita);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {


                console.log(this.responseText);
                document.getElementById("alert-text2").innerHTML = "";
                document.getElementById("alert-text2").innerHTML = this.responseText;

                document.getElementById("miSlidePanel2").style.display = "block";
            }
        };
        xhttp.open("POST", "ajax2.php?motivo=editar_cita" + "&cita=" + cita);
        xhttp.send();
    }

    /*
    document.getElementById("alert_actualizar2").addEventListener("click", function () {
        console.log("CLIENTE QUE VAS A ACTUALIZAR >>> " + id_cliente);
        var nombrecliente = document.getElementById('nombre_cliente2').value;
        var telefonocliente = document.getElementById('telefono_cliente2').value;
        var correoelectronico = document.getElementById('correo_electronico_cliente2').value;

        console.log(nombrecliente);
        console.log(telefonocliente);
        console.log(correoelectronico);

        var xhttp2 = new XMLHttpRequest();
        xhttp2.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("miSlidePanel2").style.display = "none";

                if (this.responseText.includes("ERROR")) {
                    document.getElementById("alert-text6").innerHTML = this.responseText;
                    document.getElementById("miAlerta4").style.display = "block";
                } else {
                    document.getElementById("alert-text4").innerHTML = "Los datos del cliente se actualizarón satisfactoriamente!";
                    document.getElementById("miAlerta2").style.display = "block";

                    document.getElementById("tabla").innerHTML = '';
                    document.getElementById("tabla").innerHTML = this.responseText;
                }
            }
        };
        xhttp2.open("POST", "ajax2.php?motivo=actualizar_cliente" + "&nombre_cliente=" + nombrecliente + "&telefono_cliente=" + telefonocliente + "&correo_electronico=" + correoelectronico + "&idCliente=" + id_cliente);
        xhttp2.send();
    });
*/

</script>
</body>
