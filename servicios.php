<html lang="en">
<?php
session_start();

require_once("config.php");
$conexion = new mysqli(conexion_host, conexion_user, conexion_pass, conexion_bbdd, conexion_port);

if ($conexion->connect_error) {
    die("Error conexion bd: " . $conexion->connect_error);
}

if(!isset($_SESSION["permiso"]) || ($_SESSION["permiso"] != null && $_SESSION["permiso"] != "admin")){
    die("<h1 style='color:darkslateblue'>Забранен достъп!</h1>");
}
if (isset($_GET["exit"])) {
    salir();
}
function salir()
{
    unset($_SESSION["permiso"]);
    unset($_SESSION["user"]);
    unset($_SESSION["pass"]);
    session_unset();
    session_destroy();
    header('Location: admin-login.html');
}
?>

<head>
    <meta charset="UTF-8">
    <title>Админ панел - Услуги</title>
    <link rel="stylesheet" href="css/styles.css">

    <!-- js para los iconos fontawesome.com -->
    <script src="https://kit.fontawesome.com/f2714199ff.js" crossorigin="anonymous"></script>
</head>
<body style="margin-top: 22px;">
<h1>Меню</h1>
<ul id="temporal_ul">
    <?php
    if ($_SESSION["permiso"] == "admin") {
        echo '<li><a href="empleados.php">Служители</a></li>
              <li><a href="categorias.php">Категории</a></li>
              <li><a href="servicios.php">Услуги</a></li>
              <li><a href="clientes.php">Клиенти</a></li>
              <li><a href="calendario_show_appointments.php">Календар с резервации</a></li>
              <li><a href="citas.php">Резервации</a></li>
              <li><a href="?exit">Изход</a></li>';
    } else {
        echo '<li><a href="calendario_show_appointments.php">Календар с резервации</a></li>
              <li><a href="citas.php">Резервации</a></li>
              <li><a href="?exit">Изход</a></li>';
    }
    ?>
</ul>
<hr style="color: #0056b2"/>
<br><br>
<h1>Услуги</h1>
<table id="tabla">
    <tr>
        <th>Име</th>
        <th>Продължителност</th>
        <th>Категория</th>
        <th></th>
        <th></th>
    </tr>


    <?php
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
    <button id="buton_borrar"><i class="fas fa-trash-alt"></i><span>  Изтрий услуга</span></button>
    <button id="buton_anadir"><i class="fas fa-plus"></i><span>  Добави услуга</span></button>
</div>


<div id="miSlidePanel" class="slidePanel">
    <div class="slidePanel-content">
        <div id="titulo">
            <h2>Добавяне на услуга</h2>
        </div>
        <span id="alert-text">
                Име:<br><br>
                <input type="text" id="nombre_servicio"><br><br>

                Продължителност:<br><br>
                <input type="number" id="duracion_servicio"><br><br>

                Избери категория:<br><br>
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
            <span id="alert_anadir">Добави</span>
            <span id="alert_cancelar">Отмени</span>
        </div>
    </div>
</div>


<div id="miSlidePanel2" class="slidePanel2">
    <div class="slidePanel-content2">
        <div id="titulo">
            <h2>Обновяване на услуга</h2>
        </div>
        <span id="alert-text2">
                Име:<br><br>
                <input type="text" id="nombre_servicio2"><br><br>

                Продължителност:<br><br>
                <input type="number" id="duracion_servicio2"><br><br>

                Избери категория:<br><br>
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
            <span id="alert_actualizar2">Обнови</span>
            <span id="alert_cancelar2">Отмени</span>
        </div>
    </div>
</div>


<div id="miAlerta3" class="alerta">
    <div class="alerta-content">

        <span id="alert-text5"></span>
        <div id="alert-footer">
            <span id="alert_actualizar" onclick="alert_back2()">Връщане за поправка на въведените данни</span>
            <span id="alert_cancelar" onclick="alert_cancel()">Отмени</span>
        </div>
    </div>
</div>

<div id="miAlerta4" class="alerta">
    <div class="alerta-content">

        <span id="alert-text6"></span>
        <div id="alert-footer">
            <span id="alert_actualizar" onclick="alert_back3()">Връщане за поправка на въведените данни</span>
            <span id="alert_cancelar" onclick="alert_cancel()">Отмени</span>
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
    var servicios_para_borrar = [];
    var id_servicio = "";

    function alert_ok() {
        document.getElementById("miAlerta2").style.display = "none";
    }

    function alert_cancel() {
        document.getElementById("miAlerta3").style.display = "none";
        document.getElementById("miAlerta4").style.display = "none";
    }

    function alert_back2() {
        document.getElementById("miAlerta3").style.display = "none";
        document.getElementById("miSlidePanel").style.display = "block";
    }

    function alert_back3() {
        document.getElementById("miAlerta4").style.display = "none";
        document.getElementById("miSlidePanel2").style.display = "block";
    }


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

        if (servicios_para_borrar.length > 0) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    console.log(servicios_para_borrar);

                    //eliminamos los divs con los servicios seleccionados
                    for (var x = 0; x < servicios_para_borrar.length; x++) {
                        document.getElementById(servicios_para_borrar[x]).innerHTML = '';
                    }

                    document.getElementById("alert-text4").innerHTML = 'Услугата бе изтрита успешно!';
                    document.getElementById("miAlerta2").style.display = "block";
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=eliminar_servicio" + "&servicios=" + datos);
            xhttp.send();
        }
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


                if (this.responseText.includes("ГРЕШКА")) {
                    document.getElementById("alert-text5").innerHTML = this.responseText;
                    document.getElementById("miAlerta3").style.display = "block";
                }else if(this.responseText.includes("Не сте попълнили всички полета!")){
                    document.getElementById("alert-text5").innerHTML = this.responseText;
                    document.getElementById("miAlerta3").style.display = "block";
                } else {
                    var xhttp2 = new XMLHttpRequest();
                    xhttp2.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("alert-text4").innerHTML = "Услугата бе добавена услушно!";
                            document.getElementById("miAlerta2").style.display = "block";
                            document.getElementById("tabla").innerHTML = this.responseText;
                        }
                    };
                    xhttp2.open("POST", "ajax2.php?motivo=mostrar_servicios");
                    xhttp2.send();
                }
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
                document.getElementById("miSlidePanel2").style.display = "none";

                if (this.responseText.includes("ГРЕШКА")) {
                    document.getElementById("alert-text6").innerHTML = this.responseText;
                    document.getElementById("miAlerta4").style.display = "block";
                }else{
                    document.getElementById("alert-text4").innerHTML = "Данните на услугата бяха обновени успешно!";
                    document.getElementById("miAlerta2").style.display = "block";

                    document.getElementById("tabla").innerHTML = '';
                    document.getElementById("tabla").innerHTML = this.responseText;
                }
            }
        };
        xhttp2.open("POST", "ajax2.php?motivo=actualizar_servicio" + "&nombre_servicio=" + nombreserv + "&duracion_servicio=" + duracionserv + "&categoria_servicio=" + categoriaserv + "&idServicio=" + id_servicio);
        xhttp2.send();
    });
</script>
</body>
