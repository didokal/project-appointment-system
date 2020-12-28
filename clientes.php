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
    <title>Админ панел - Клиенти</title>
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
<div>
    <h1 style="float: left">Клиенти</h1>
    <div style="float: right; padding-top: 20px;">
        <span style="display: inline-block;">
        <select id="tipo_valor_buscar">
            <option>Търсачка</option>
            <option value="Nombre">Име</option>
            <option value="Telefono">Телефон</option>
            <option value="Correo">Имейл адрес</option>
        </select>
            </span>
        <span style="display: inline-block;"><input style="font-size: 15px; height: 30px; padding-top: 1px;" type="text" id="buscador" onkeypress="miBuscador()"
                                                    onKeyUp="miBuscador()"></span>
    </div>
</div>
<table id="tabla">
    <tr>
        <th>Име</th>
        <th>Телефон</th>
        <th>Имейл адрес</th>
        <th>Парола</th>
        <th></th>
        <th></th>
    </tr>

    <?php
    $resultado = mysqli_query($conexion, "SELECT * FROM `clientes`");
    while ($row = mysqli_fetch_array($resultado)) {
        echo "<tr class='trr' id='cliente$row[0]'>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[2]</td>";
        echo "<td>$row[3]</td>";
        echo "<td>";
        for($x = 0; $x < strlen($row[4]); $x++){
            echo "•";
        }
        echo "</td>";

        echo "<td><button type='button' id='buton_tabla' onclick='editar_cliente(\"$row[0]\")'><i class='button_imagen'></td>";
        echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
        echo "</tr>";
    }
    ?>
</table>
<div style="margin-top: 20px">
    <button id="buton_borrar"><i class="fas fa-trash-alt"></i><span>  Изтрий клиент</span></button>
    <button id="buton_anadir"><i class="fas fa-plus"></i><span>  Добави клиент</span></button>
</div>


<div id="miSlidePanel" class="slidePanel">
    <div class="slidePanel-content">
        <div id="titulo">
            <h2>Добавяне на клиент</h2>
        </div>
        <span id="alert-text">
                Име:<br><br>
                <input type="text" id="nombre_cliente"><br><br>

                Телефон:<br><br>
                <input type="number" id="telefono_cliente"><br><br>

                Имейл адрес:<br><br>
                <input type="text" id="correo_electronico_cliente">
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
            <h2>Реадктиране на профил</h2>
        </div>
        <span id="alert-text2">
                Име:<br><br>
                <input type="text" id="nombre_cliente2"><br><br>

                Телефон:<br><br>
                <input type="number" id="telefono_cliente2"><br><br>

                Имейл адрес:<br><br>
                <input type="text" id="correo_electronico_cliente2">
            <br>
        </span>
        <div id="alert-footer">
            <span id="alert_actualizar2">Обнови</span>
            <span id="alert_cancelar2">Отмени</span>
        </div>
    </div>
</div>


<div id="miAlerta" class="alerta">
    <div class="alerta-content">

        <span id="alert-text3"></span>
        <div id="alert-footer">
            <span id="alert_actualizar" onclick="alert_update()">Обнови</span>
            <span id="alert_cancelar" onclick="alert_cancel()">Отмени</span>
        </div>
    </div>
</div>


<div id="miAlerta4" class="alerta">
    <div class="alerta-content">
        <span id="alert-text6"></span>
        <div id="alert-footer">
            <span id="alert_actualizar" onclick="alert_back3()">Връщане за коригиране на въведените данни</span>
            <span id="alert_cancelar" onclick="alert_cancel()">Отмени</span>
        </div>
    </div>
</div>


<div id="miAlerta7" class="alerta">
    <div class="alerta-content">
        <span id="alert-text7"></span>
        <div id="alert-footer">
            <span id="alert_actualizar" onclick="alert_back7()">Връщане за коригиране на въведените данни</span>
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
    var clientes_para_borrar = [];
    var id_cliente = "";
    var valParam;


    //BUSCADOR
    var tipo_valor_a_buscar_escogido = '';

    var select = document.querySelector('#tipo_valor_buscar'),
        input = document.querySelector('input[type="button"]');
    select.addEventListener('change', function () {
        tipo_valor_a_buscar_escogido = document.getElementById("tipo_valor_buscar").value;
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
                    document.getElementById("tabla").innerHTML = 'Без резултати!';
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

                document.getElementById("alert-text4").innerHTML = "Потребителските данни на клиента с телефон " + telefonocliente + " бяха обновени успешно!";
                document.getElementById("miAlerta2").style.display = "block";
            }
        };
        xhttp2.open("POST", "ajax2.php?motivo=actualizar_cliente_de_alerta" + "&nombre_cliente=" + nombrecliente + "&telefono_cliente=" + telefonocliente + "&correo_electronico=" + correoelectronico);
        xhttp2.send();
    }


    function alert_cancel() {
        document.getElementById("miAlerta").style.display = "none";
        document.getElementById("miAlerta4").style.display = "none";
        document.getElementById("miAlerta7").style.display = "none";
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

        document.getElementById("alert-text4").innerHTML = "Клиентът бе изтрит успешно!";
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

                if (this.responseText.includes("Въведеният телефон")) {
                    document.getElementById("miSlidePanel").style.display = "none";
                    document.getElementById("alert-text3").innerHTML = this.responseText;
                    document.getElementById("miAlerta").style.display = "block";
                } else if(this.responseText.includes("Вече съществува клиент")) {
                    document.getElementById("miSlidePanel").style.display = "none";
                    document.getElementById("alert-text7").innerHTML = this.responseText;
                    document.getElementById("miAlerta7").style.display = "block";
                }else if(this.responseText.includes("Не сте попълнили всички полета")){
                    document.getElementById("miSlidePanel").style.display = "none";
                    document.getElementById("alert-text7").innerHTML = this.responseText;
                    document.getElementById("miAlerta7").style.display = "block";
                }else {
                    document.getElementById("miSlidePanel").style.display = "none";
                    document.getElementById("alert-text4").innerHTML = "Клиентът бе добавен услушно!";
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

                if (this.responseText.includes("ГРЕШКА")) {
                    document.getElementById("alert-text6").innerHTML = this.responseText;
                    document.getElementById("miAlerta4").style.display = "block";
                } else {
                    document.getElementById("alert-text4").innerHTML = "Данните на клиента бяха обновени успешно!";
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
