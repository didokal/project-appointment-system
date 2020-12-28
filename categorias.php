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
    <title>Админ панел - Категории</title>
    <link rel="stylesheet" href="css/styles.css">

    <!-- js para los iconos fontawesome.com -->
    <script src="https://kit.fontawesome.com/f2714199ff.js" crossorigin="anonymous"></script>
</head>
<body style="margin-top: 22px;">
<div>
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
    <h1>Категории</h1>
    <table id="tabla">
        <tr>
            <th>Име</th>
            <th></th>
        </tr>
        <?php
        $resultado = mysqli_query($conexion, "SELECT * FROM `categorias`");
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<tr class='trr' id='cat$row[0]'>";
            echo "<td>$row[1]</td>";
            echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]'></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <div style="margin-top: 20px">
        <button id="buton_borrar"><i class="fas fa-trash-alt"></i><span>  Изтрий категория</span></button>
        <button id="buton_anadir"><i class="fas fa-plus"></i><span>  Добави категория</span></button>
    </div>


    <div id="miAlerta" class="alerta">
        <div class="alerta-content2">
            <div id="titulo">
                <h2>Добавяне на категория</h2>
            </div>
            <span id="alert-text">
                Име на категория:<br><br>
                <input type="text" id="categoria">
            </span>
            <div id="alert-footer">
                <span id="alert_anadir">Добави</span>
                <span id="alert_cancelar">Отмени</span>
            </div>
        </div>
    </div>


    <div id="miAlerta3" class="alerta">
        <div class="alerta-content">

            <span id="alert-text5"></span>
            <div id="alert-footer">
                <span id="alert_actualizar" onclick="alert_back2()">Връщане за коригиране на въведените данни</span>
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
        var categoria_para_borrar = [];

        function alert_ok() {
            document.getElementById("miAlerta2").style.display = "none";
        }

        function alert_cancel() {
            document.getElementById("miAlerta3").style.display = "none";
        }

        function alert_back2() {
            document.getElementById("miAlerta3").style.display = "none";
            document.getElementById("miAlerta").style.display = "block";
        }

        //BORRAR CATEGORIA
        document.getElementById("buton_borrar").addEventListener("click", function () {
            let checked = Array.prototype.slice.call(document.querySelectorAll("input[type='checkbox']:checked"));
            checked.forEach(function (cb) {
                console.log("Datos antes de enviar AJAX " + cb.value);
                categoria_para_borrar.push("cat" + cb.value);
                datos += cb.value + ",";
            });

            //comprobamos si el array tiene datos, si no tiene no tiene sentido hacer la peticion al servidor...
            if (categoria_para_borrar.length > 0) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        console.log(categoria_para_borrar);

                        //eliminamos los divs con los empleados seleccionados
                        for (var x = 0; x < categoria_para_borrar.length; x++) {
                            document.getElementById(categoria_para_borrar[x]).innerHTML = '';
                        }
                        document.getElementById("alert-text4").innerHTML = 'Категорията бе изтрита услушно!';
                        document.getElementById("miAlerta2").style.display = "block";
                    }
                };
                xhttp.open("POST", "ajax2.php?motivo=eliminar_categoria" + "&categorias=" + datos);
                xhttp.send();
            }
        });


        //ABRIR POPUP PARA INTRODUCIR DATOS DE LA NUEVA CATEGORIA
        document.getElementById("buton_anadir").addEventListener("click", function () {
            document.getElementById("miAlerta").style.display = "block";
        });

        //CERRAR POPUP AL PULSAR CANCELAR
        document.getElementById("alert_cancelar").addEventListener("click", function () {
            document.getElementById("miAlerta").style.display = "none";
        });


        document.getElementById("alert_anadir").addEventListener("click", function () {
            var categoria = document.getElementById("categoria").value;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("miAlerta").style.display = "none";

                    if (this.responseText.includes("ГРЕШКА")) {
                        document.getElementById("alert-text5").innerHTML = this.responseText;
                        document.getElementById("miAlerta3").style.display = "block";
                    }else if(this.responseText.includes("Не сте попълнили всички полета")){
                        document.getElementById("alert-text5").innerHTML = "Не сте попълнили всички полета!";
                        document.getElementById("miAlerta3").style.display = "block";
                    } else {
                        var xhttp2 = new XMLHttpRequest();
                        xhttp2.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                document.getElementById("tabla").innerHTML = this.responseText;

                                document.getElementById("alert-text4").innerHTML = "Категорията бе добавена успешно!";
                                document.getElementById("miAlerta2").style.display = "block";
                            }
                        };
                        xhttp2.open("POST", "ajax2.php?motivo=mostrar_categorias");
                        xhttp2.send();
                    }
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=anadir_categoria" + "&categoria=" + categoria);
            xhttp.send();
        });
    </script>
</div>
</body>