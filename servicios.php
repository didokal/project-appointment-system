<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Menu</h1>
<ul id="temporal_ul">
    <li><a href="admin-panel.php">Admin panel</a></li>
    <li><a href="pedir_cita.php">Pedir cita</a></li>
    <li><a href="empleados.php">Empleados</a></li>
    <li><a href="categorias.php">Categorias</a></li>
    <li><a href="servicios.php">Servicios</a></li>
</ul>
<hr style="color: #0056b2"/>
<br><br><br>
<h1>Servicios</h1>
<table id="tabla">
    <tr>
        <th>Nombre</th>
        <th>Duracion</th>
        <th>Categoria</th>
        <th></th>
        <th></th>
    </tr>


    <?php
    session_start();
    $conexion = new mysqli("localhost", "root", "", "citas2", "3306");

    if ($conexion->connect_error) {
        die("Error conexion bd: " . $conexion->connect_error);
    }


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


    ?>
</table>
<div style="margin-top: 20px">
    <button id="buton_borrar"><span class="icon_borrar">  Eliminar servicio</span></button>
    <button id="buton_anadir"><span class="icon_anadir">  Añadir servicio</span></button>

</div>


<div id="miSlidePanel" class="slidePanel">
    <div class="slidePanel-content">
        <div id="titulo">
            <h2>Añadir servicio</h2>
        </div>
        <span id="alert-text">
                Nombre servicio:<br><br>
                <input type="text" id="nombre_servicio"><br><br>

                Duracion servicio:<br><br>
                <input type="number" id="duracion_servicio"><br><br>

                Elige categoria:<br><br>
                <select name="categoria_escogida" id="categoria_escogida">
                    <?php
                    $resultado = mysqli_query($conexion, "SELECT * FROM `categorias`");
                    while ($row = mysqli_fetch_array($resultado)) {
                        echo "<option value='$row[1]'>" . $row[1] . "</option>";
                    }
                    ?>
                </select>
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
            <h2>Actualizar servicio</h2>
        </div>
        <span id="alert-text2">
                Nombre servicio:<br><br>
                <input type="text" id="nombre_servicio2"><br><br>

                Duracion servicio:<br><br>
                <input type="number" id="duracion_servicio2"><br><br>

                Elige categoria:<br><br>
                <select name="categoria_escogida" id="categoria_escogida2">
                    <?php
                    $resultado = mysqli_query($conexion, "SELECT * FROM `categorias`");
                    while ($row = mysqli_fetch_array($resultado)) {
                        echo "<option value='$row[1]'>" . $row[1] . "</option>";
                    }
                    ?>
                </select>
            <br>
            </span>
        <div id="alert-footer">
            <span id="alert_actualizar2">Actualizar</span>
            <span id="alert_cancelar2">Cancelar</span>
        </div>
    </div>
</div>


<script>
    var datos = "";
    var servicios_para_borrar = [];
    var id_servicio = "";

    document.getElementById("buton_borrar").addEventListener("click", function () {
        // Query for only the checked checkboxes and put the result in an array
        let checked = Array.prototype.slice.call(document.querySelectorAll("input[type='checkbox']:checked"));
        console.clear();
        // Loop over the array and inspect contents
        checked.forEach(function (cb) {
            console.log("Datos antes de enviar AJAX " + cb.value);

            servicios_para_borrar.push("serv" + cb.value);

            datos += cb.value + ",";
        });

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                console.log(servicios_para_borrar);

                //eliminamos los divs con los servicios seleccionados
                for (var x = 0; x < servicios_para_borrar.length; x++) {
                    document.getElementById(servicios_para_borrar[x]).innerHTML = '';
                }
            }
        };
        xhttp.open("POST", "ajax2.php?motivo=eliminar_servicio" + "&servicios=" + datos);
        xhttp.send();
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

        var servicio = document.getElementById("nombre_servicio").value;
        var duracion = document.getElementById("duracion_servicio").value;
        var categoria = document.getElementById("categoria_escogida").value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                document.getElementById("miSlidePanel").style.display = "none";
                document.getElementById("tabla").innerHTML = '';


                var xhttp2 = new XMLHttpRequest();
                xhttp2.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log("RESUALTADO >>> " + this.responseText);

                        document.getElementById("tabla").innerHTML = this.responseText;
                    }
                };
                xhttp2.open("POST", "ajax2.php?motivo=mostrar_servicios");
                xhttp2.send();
            }
        };
        xhttp.open("POST", "ajax2.php?motivo=anadir_servicio" + "&servicio=" + servicio + "&duracion=" + duracion + "&categoria=" + categoria);
        xhttp.send();
    });


    function editar_servicio(servicio) {
        id_servicio = servicio;

        console.log("SERVICIO QUE VAS A EDITAR >>> " + id_servicio);


        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {


                console.log(this.responseText);
                document.getElementById("alert-text2").innerHTML = "";
                document.getElementById("alert-text2").innerHTML = this.responseText;

                document.getElementById("miSlidePanel2").style.display = "block";
            }
        };
        xhttp.open("POST", "ajax2.php?motivo=editar_servicio" + "&servicio=" + servicio);
        xhttp.send();
    }


    document.getElementById("alert_actualizar2").addEventListener("click", function () {
        console.log("SERVICIO QUE VAS A EDITAR >>> " + id_servicio);
        var nombreserv = document.getElementById('nombre_servicio2').value;
        var duracionserv = document.getElementById('duracion_servicio2').value;
        var categoriaserv = document.getElementById('categoria_escogida2').value;

        console.log(nombreserv);
        console.log(duracionserv);
        console.log(categoriaserv);

        var xhttp2 = new XMLHttpRequest();
        xhttp2.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);

                document.getElementById("miSlidePanel2").style.display = "none";
                document.getElementById("tabla").innerHTML = '';
                document.getElementById("tabla").innerHTML = this.responseText;
            }
        };
        xhttp2.open("POST", "ajax2.php?motivo=actualizar_servicio" + "&nombre_servicio=" + nombreserv + "&duracion_servicio=" + duracionserv + "&categoria_servicio=" + categoriaserv + "&idServicio=" + id_servicio);
        xhttp2.send();
    });


</script>
</body>
