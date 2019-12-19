<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
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
    <h1 style="float: left">Clientes</h1>
    <div style="float: right; padding-top: 20px;">
        <span style="display: inline-block;">
        <select id="tipo_valor_buscar">
            <option>Buscar por</option>
            <option>Nombre</option>
            <option>Telefono</option>
            <option>Correo</option>
        </select>
            </span>
        <span style="display: inline-block;"><input type="text" id="buscador" onkeypress="miBuscador()"
                                                    onKeyUp="miBuscador()"></span>
    </div>
</div>
<table id="tabla">
    <tr>
        <th>Nombre</th>
        <th>Telefono</th>
        <th>Correo electronico</th>
        <th></th>
        <th></th>
    </tr>

    <?php
    session_start();
    $conexion = new mysqli("localhost", "root", "", "citas2", "3306");

    if ($conexion->connect_error) {
        die("Error conexion bd: " . $conexion->connect_error);
    }


    $resultado = mysqli_query($conexion, "SELECT * FROM `clientes`");
    while ($row = mysqli_fetch_array($resultado)) {
        echo "<tr class='trr' id='cliente$row[0]'>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[2]</td>";
        echo "<td>$row[3]</td>";

        echo "<td><button type='button' id='buton_tabla' onclick='editar_cliente(\"$row[0]\")'><i class='button_imagen'></td>";
        echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
        echo "</tr>";
    }
    ?>
</table>
<div style="margin-top: 20px">
    <button id="buton_borrar"><span class="icon_borrar">  Eliminar cliente</span></button>
    <button id="buton_anadir"><span class="icon_anadir">  Añadir cliente</span></button>

</div>


<div id="miSlidePanel" class="slidePanel">
    <div class="slidePanel-content">
        <div id="titulo">
            <h2>Añadir cliente</h2>
        </div>
        <span id="alert-text">
                Nombre cliente:<br><br>
                <input type="text" id="nombre_cliente"><br><br>

                Telefono:<br><br>
                <input type="number" id="telefono_cliente"><br><br>

                Correo electronico:<br><br>
                <input type="text" id="correo_electronico_cliente">
            <br>
        </span>
        <div id="alert-footer">
            <span id="alert_anadir">Añadir</span>
            <span id="alert_cancelar">Cancelar</span>
        </div>
    </div>
</div>


<div id="miSlidePanel2" class="slidePanel2">
    <div class="slidePanel-content2">
        <div id="titulo">
            <h2>Actualizar cliente</h2>
        </div>
        <span id="alert-text2">
                Nombre servicio:<br><br>
                <input type="text" id="nombre_cliente2"><br><br>

                Telefono:<br><br>
                <input type="number" id="telefono_cliente2"><br><br>

                Correo electronico:<br><br>
                <input type="text" id="correo_electronico_cliente2">
            <br>
        </span>
        <div id="alert-footer">
            <span id="alert_actualizar2">Actualizar</span>
            <span id="alert_cancelar2">Cancelar</span>
        </div>
    </div>
</div>


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


<div id="miAlerta7" class="alerta">
    <div class="alerta-content">
        <span id="alert-text7"></span>
        <div id="alert-footer">
            <span id="alert_actualizar" onclick="alert_back7()">Volver y corregir los datos introducidos</span>
            <span id="alert_cancelar" onclick="alert_cancel()">Cancelar</span>
        </div>
    </div>
</div>


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
    var clientes_para_borrar = [];
    var id_cliente = "";
    var valParam;


    //BUSCADOR
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
        xhttp.open("POST", "ajax2.php?motivo=buscador" + "&cadena=" + s + "&tipo_de_valor=" + tipo_valor_a_buscar_escogido);
        xhttp.send();
    }


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


    function alert_cancel() {
        document.getElementById("miAlerta").style.display = "none";
    }

    function alert_back3() {
        document.getElementById("miAlerta4").style.display = "none";
        document.getElementById("miSlidePanel2").style.display = "block";
    }


    function alert_back7() {
        document.getElementById("miAlerta7").style.display = "none";
        document.getElementById("miSlidePanel").style.display = "block";
    }


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
            clientes_para_borrar.push(datos_separados);

            //convertimos el array en json para poder enviarlo por el post de ajax
            valParam = JSON.stringify(clientes_para_borrar);
        });

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                console.log(clientes_para_borrar);

                //eliminamos los divs con los servicios seleccionados
                for (var x = 0; x < clientes_para_borrar.length; x++) {
                    document.getElementById("cliente" + clientes_para_borrar[x][0]).innerHTML = '';
                }
            }
        };
        xhttp.open("POST", "ajax2.php?motivo=eliminar_cliente" + "&clientes=" + valParam);
        xhttp.send();

        document.getElementById("alert-text4").innerHTML = "La operación ha sido realizada con exito!";
        document.getElementById("miAlerta2").style.display = "block";
    });


    //AÑADIR CATEGORIA
    document.getElementById("buton_anadir").addEventListener("click", function () {
        document.getElementById("miSlidePanel").style.display = "block";
    });

    document.getElementById("alert_cancelar").addEventListener("click", function () {
        document.getElementById("miSlidePanel").style.display = "none";
    });

    document.getElementById("alert_cancelar2").addEventListener("click", function () {
        document.getElementById("miSlidePanel2").style.display = "none";
    });


    document.getElementById("alert_anadir").addEventListener("click", function () {
        document.getElementById("miSlidePanel").style.display = "block";

        var nombre = document.getElementById("nombre_cliente").value;
        var telefono = document.getElementById("telefono_cliente").value;
        var correo_electronico = document.getElementById("correo_electronico_cliente").value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);

                if (this.responseText.includes("Su telefono")) {
                    document.getElementById("miSlidePanel").style.display = "none";
                    document.getElementById("alert-text3").innerHTML = this.responseText;
                    document.getElementById("miAlerta").style.display = "block";
                } else if(this.responseText.includes("Ya existe un usuario")){
                    document.getElementById("miSlidePanel").style.display = "none";
                    document.getElementById("alert-text7").innerHTML = this.responseText;
                    document.getElementById("miAlerta7").style.display = "block";
                }else {
                    document.getElementById("miSlidePanel").style.display = "none";
                    document.getElementById("alert-text4").innerHTML = "El cliente ha sido creado satisfactoriamente!";
                    document.getElementById("miAlerta2").style.display = "block";

                    var xhttp2 = new XMLHttpRequest();
                    xhttp2.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("tabla").innerHTML = '';
                            document.getElementById("tabla").innerHTML = this.responseText;
                        }
                    };
                    xhttp2.open("POST", "ajax2.php?motivo=mostrar_clientes");
                    xhttp2.send();
                }
            }
        };
        xhttp.open("POST", "ajax2.php?motivo=anadir_cliente" + "&nombre=" + nombre + "&telefono=" + telefono + "&correo_electronico=" + correo_electronico);
        xhttp.send();
    });


    function editar_cliente(cliente) {
        id_cliente = cliente;
        console.log("CLIENTE QUE VAS A EDITAR >>> " + id_cliente);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {


                console.log(this.responseText);
                document.getElementById("alert-text2").innerHTML = "";
                document.getElementById("alert-text2").innerHTML = this.responseText;

                document.getElementById("miSlidePanel2").style.display = "block";
            }
        };
        xhttp.open("POST", "ajax2.php?motivo=editar_cliente" + "&cliente=" + cliente);
        xhttp.send();
    }


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
</script>
</body>
