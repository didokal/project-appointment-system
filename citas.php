<html lang="en">
<?php
session_start();

require_once("config.php");
$conexion = new mysqli(conexion_host, conexion_user, conexion_pass, conexion_bbdd, conexion_port);

if ($conexion->connect_error) {
    die("Error conexion bd: " . $conexion->connect_error);
}

if (!isset($_SESSION["permiso"]) || ($_SESSION["permiso"] != null && $_SESSION["permiso"] != "admin" && $_SESSION["permiso"] != "user")) {
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
    <title>Админ панел - Резервации</title>
    <link rel="stylesheet" href="css/styles.css">

    <!-- js para los iconos fontawesome.com -->
    <script src="https://kit.fontawesome.com/f2714199ff.js" crossorigin="anonymous"></script>

    <style>
        span:empty {
            background: lightgrey;
            vertical-align: middle;
            display: inline-block
        }
    </style>
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
    <h1 style="float: left">Резервации</h1>
    <div style="float: right; padding-top: 20px;">
        <span style="display: inline-block;">
        <select id="tipo_valor_buscar">
            <option value="Buscar por">Търсене по</option>
            <option value="Fecha">Дата</option>
            <option value="Nombre empleado">Име на работник</option>
            <option value="Servicio">Услуга</option>
            <option value="Nombre cliente">Име на клиент</option>
            <option value="VIN">ВИН</option>
            <option value="Matricula">Регистрационен номер</option>
        </select>
            </span>
        <span style="display: inline-block;"><input style="font-size: 15px; height: 30px; padding-top: 1px;" type="text" id="buscador"
                                                    onkeypress="miBuscador()"
                                                    onKeyUp="miBuscador()"></span>
    </div>
</div>
<table id="tabla">
    <tr>
        <th>Дата</th>
        <th>Час от</Ч></th>
        <th>Час до</th>
        <th>Име на служител</th>
        <th>Услуга</th>
        <th>Име на клиент</th>
        <th>Редак. резервация</th>
        <th>Редак. фактура</th>
        <th>Изтрий резервация</th>
    </tr>


    <script>

        var tipo_valor_a_buscar_escogido;
        var clon_realizado = false;
        var clon;
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
                        document.getElementById("tabla").innerHTML = 'Без резултати!';
                    }
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=buscador_citas" + "&cadena=" + s + "&tipo_de_valor=" + tipo_valor_a_buscar_escogido);
            xhttp.send();
        }


        function comprobar_lineas() {
            console.log(document.querySelectorAll('.trabajo_a_realizar').length);

            var contador = document.querySelectorAll('.trabajo_a_realizar').length;
            contador++;


            var tr = document.createElement("tr");
            tr.id = "tr_anadido" + contador;
            document.getElementById("tabla_trabajos").appendChild(tr);


            var td1 = document.createElement("td");
            td1.id = "td_anadido"+contador;
            td1.style = "padding: 7px 10px 7px 0";
            document.getElementById("tr_anadido"+contador).appendChild(td1);

            var input1 = document.createElement("input");
            input1.type = "text";
            input1.name = "trabajo_realizar" + contador;
            input1.className = "trabajo_a_realizar";
            input1.style = "background-color: #eeeeee; border:1px solid lightgrey";
            document.getElementById("td_anadido"+contador).appendChild(input1);



            var td2 = document.createElement("td");
            td2.id = "td_anadido"+contador+1;
            td2.style = "padding: 7px 10px 7px 0; width: 13%;";
            document.getElementById("tr_anadido"+contador).appendChild(td2);

            var input2 = document.createElement("input");
            input2.type = "number";
            input2.name = "trabajo_realizar_cantidad" + contador;
            input2.className = "trabajo_a_realizar_cantidad";
            input2.style = "background-color: #eeeeee; border:1px solid lightgrey";
            input2.setAttribute("onchange", "calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()");
            document.getElementById("td_anadido"+contador+1).appendChild(input2);


            var td3 = document.createElement("td");
            td3.id = "td_anadido"+contador+2;
            td3.style = "padding: 7px 10px 7px 0; width: 13%;";
            document.getElementById("tr_anadido"+contador).appendChild(td3);

            var input3 = document.createElement("input");
            input3.type = "number";
            input3.name = "trabajo_realizar_precio_u" + contador;
            input3.className = "trabajo_realizar_precio_u";
            input3.style = "background-color: #eeeeee; border:1px solid lightgrey";
            input3.setAttribute("onchange", "calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()");
            document.getElementById("td_anadido"+contador+2).appendChild(input3);



            var td4 = document.createElement("td");
            td4.id = "td_anadido"+contador+3;
            td4.style = "padding: 7px 10px 7px 0; width: 16%;";
            document.getElementById("tr_anadido"+contador).appendChild(td4);

            var input4 = document.createElement("input");
            input4.type = "number";
            input4.name = "trabajo_realizar_total" + contador;
            input4.className = "trabajo_realizar_total";
            input4.style = "background-color: #eeeeee; border:1px solid lightgrey";
            document.getElementById("td_anadido"+contador+3).appendChild(input4);



            var alto = document.getElementsByClassName('slidePanel-content3')[0].clientHeight;
            alto = alto + 20 + "px";

            document.getElementsByClassName('slidePanel-content3')[0].style.height = alto;
        }


    </script>
    <?php
    $nombreEmple = $_SESSION["nombreEmpleado"];
    $sentencia = "";
    if ($nombreEmple == "") {
        $sentencia = "SELECT * FROM `citas`";
    } else {
        $sentencia = "SELECT * FROM `citas` WHERE nombreEmpleado = '$nombreEmple'";
    }

    $resultado = mysqli_query($conexion, $sentencia);
    while ($row = mysqli_fetch_array($resultado)) {
        echo "<tr class='trr' id='cita$row[0]'>";
        echo "<td>$row[1]</td>";
        echo "<td>" . substr($row[2], 0, 5) . "</td>";
        echo "<td>" . substr($row[3], 0, 5) . "</td>";
        echo "<td>$row[4]</td>";
        echo "<td>$row[5]</td>";

        $resultado2 = mysqli_query($conexion, "SELECT * FROM `clientes` WHERE `idCliente` = $row[6]");
        if (mysqli_num_rows($resultado2) == 0) {
            echo "<td>Изтрит клиент</td>";
        } else {
            $row2 = mysqli_fetch_array($resultado2);
            echo "<td>$row2[1]</td>";
        }
        echo "<td><button type='button' id='buton_tabla' onclick='editar_cita(\"$row[0]\")'><i class='button_imagen'></td>";
        echo "<td><button type='button' id='buton_tabla' onclick='editar_hoja(\"$row[0]\")'><i class='button_imagen2'></td>";
        echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]|.|$row[2]'></td>";
        echo "</tr>";
    }
    ?>
</table>
<div style="margin-top: 20px">
    <button id="buton_borrar"><i class="fas fa-trash-alt"></i><span>  Изтрий резервация</span></button>
    <button id="buton_anadir"><i class="fas fa-plus"></i><span>  Добави резервация</span></button>
</div>

<div id="miSlidePanel5" class="slidePanel5">
    <div class="slidePanel-content5">
        <div id="slidePanel-content5-inside"></div>
    </div>
</div>



<div id="miSlidePanel4" class="slidePanel4">
    <div class="slidePanel-content">
        <div id="titulo">
            <h2>Добавяне на резервация</h2>
        </div>
        <span id="alert-text">
                Име на клиент:<br><br>
                <input type="text" id="cliente_nombre"><br><br>

                Телефон на клиент:<br><br>
                <input type="text" id="cliente_telefono"><br><br>

                Услуга: <br><br>
                <select name="servicio" id="servicio" onchange="sacar_empleados_por_servicio(1)">
                    <?php
                    echo "<option>Избери услуга</option>";
                    $resultado = mysqli_query($conexion, "SELECT nombreServ FROM servicios");
                    while ($row = mysqli_fetch_array($resultado)) {
                    echo "<option value='$row[0]'>" . $row[0] . "</option>";
                    }
                    ?>
                </select><br><br>

                Механик: <br><br>
                <select name="empleado" id="empleado">
                    <option>Избери механик</option>
                </select><br><br>

                Дата:<br><br>
                <input type="date" id="fecha3" value=""><br><br>

                <div id="title-hora-cita">Час<br><br></div>
        </span>
        <span id="alert-text2-2">
            <div id="title-hora-cita2">Час:<br><br></div>
            <div id='scroll-content3'></div>
        </span>
        <div id="alert-footer">
            <span id="comprobar_horario" style="float: left;" onclick="mostrar_intervalos_crear_cita()">Провери свободни часове</span>
            <span id="alert_cancelar">Отмени</span>
        </div>
    </div>
</div>

<div id="miSlidePanel3" class="slidePanel3">
    <div class="slidePanel-content3">
        <div id="titulo">
            <h2>Редактиране на фактура</h2>
        </div>
        <div id="alert-text3">
            <div id="datos_taller">
                <div style="width:120px; float: left; padding-top: 16px;">
                    <img src="design/logo.jpg" width="200">
                </div>
                <div style="width:228px; float: right; margin-top: -30px;">
                    <div style="line-height: 23pt; font-weight: bold; font-size: 18px;">Авто Павлов 94 ЕООД</div>
                    <div style="line-height: 23pt;">Бул. Източен 22, Пловдив</div>
                    <div style="line-height: 23pt;">Булстат 205269686</div>
                    <div style="line-height: 23pt;">М.О.Л. Велизар Павлов</div>
                    <div style="line-height: 23pt;">087 6363 610</div>
                </div>
            </div>

            <div id="datos_id_fechas">
                <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 248px;">
                            Nº фактура:
                        </span>

                    <span style="display: inline-block; width: 146px">
                            <input type="text" name="num_factura" disabled
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                </div>

                <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 248px;">
                            Дата получаване:
                        </span>

                    <span style="display: inline-block; width: 146px">
                            <input type="date" name="fecha_entrada"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                </div>

                <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 248px;">
                            Предвидена дата издаване:
                        </span>

                    <span style="display: inline-block; width: 146px">
                            <input type="date" name="fecha_salida"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <h3>Лични данни на собственика и/или представителя</h3>
            <div style="width:420px; height: 111px; float:left">
                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 85px;">Имена: </span>

                        <span style="display: inline-block; width: 300px">
                            <input type="text" name="nombre_representante"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 85px;">Фирма:</span>

                        <span style="display: inline-block; width: 300px">
                            <input type="text" name="empresa"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 85px;">Улица:</span>

                        <span style="display: inline-block; width: 300px">
                            <input type="text" name="diraccion"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>
            </div>


            <div style="width:450px; height: 111px; float:right; ">
                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 42px;">ЕГН:</span>

                        <span style="display: inline-block; width: 397px">
                            <input type="text" name="cif_nif"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:210px;  float:left;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 75px;">Домашен:</span>

                        <span style="display: inline-block; width: 120px">
                            <input type="number" name="telefono"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:232px;  float:right">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 84px;">Мобилен:</span>

                        <span style="display: inline-block; width: 137px">
                            <input type="number" name="movil"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:210px;  float:left;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 30px;">ПК:</span>

                        <span style="display: inline-block; width: 165px">
                            <input type="number" name="cp"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:232px;  float:right;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 84px;">Град:</span>

                        <span style="display: inline-block; width: 137px">
                            <input type="text" name="poblacion"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <h3>Данни на превозното средство</h3>
            <div style="width:420px; height: 68px; float:left">
                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 140px;">Марка и модел: </span>

                        <span style="display: inline-block; width: 245px">
                            <input type="text" name="marca_modelo"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 40px;">VIN: </span>

                        <span style="display: inline-block; width: 345px">
                            <input type="text" name="vin"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

            </div>
            <div style="width:450px; height: 68px; float:right">
                <div style="width:210px;  float:left;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 84px;">Р. номер:</span>

                        <span style="display: inline-block; width: 111px">
                            <input type="text" name="matricula"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:232px;  float:right;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 35px;">Км:</span>

                        <span style="display: inline-block; width: 186px">
                            <input type="number" name="km"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:450px;  float:left;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 110px;">Гориво:</span>
                        <span style="display: inline-block; width: 328px">
                            <input type="radio" name="combustible" value="0"
                                   style="display: inline-block; width:13px; margin-left:20px"/>0
                            <input type="radio" name="combustible" value="1/4"
                                   style="display: inline-block; width:13px; margin-left:21px"/>1/4
                            <input type="radio" name="combustible" value="1/2"
                                   style="display: inline-block; width:13px; margin-left:21px"/>1/2
                            <input type="radio" name="combustible" value="3/4"
                                   style="display: inline-block; width:13px; margin-left:21px"/>3/4
                            <input type="radio" name="combustible" value="4/4"
                                   style="display: inline-block; width:13px; margin-left:21px"/>4/4
                        </span>
                    </div>
                </div>

            </div>

            <div class="clear"></div>
            <br>
            <h3>Услуги или авточасти</h3>
            <span id="trabajos">
                <table style="width:100%" id="tabla_trabajos">
                    <tr>
                        <td style='padding: 0;'>Описание</td>
                        <td style='padding: 0; width: 13%;'>Количество</td>
                        <td style='padding: 0; width: 13%;'>Цена бройка</td>
                        <td style='padding: 0; width: 16%;'>Обща стойност</td>
                    </tr>
                    <tr>
                        <td style='padding: 7px 10px 7px 0;'><input type="text" name="trabajo_realizar1" class="trabajo_a_realizar" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input type="number" name="trabajo_realizar_cantidad1" class="trabajo_a_realizar_cantidad" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input type="number" name="trabajo_realizar_precio_u1" class="trabajo_realizar_precio_u" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 0 7px 0;'><input type="number" disabled name="trabajo_realizar_total1" class="trabajo_realizar_total" onchange="" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                    </tr>
                    <tr>
                        <td style='padding: 7px 10px 7px 0;'><input type="text" name="trabajo_realizar2" class="trabajo_a_realizar" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input type="number" name="trabajo_realizar_cantidad2" class="trabajo_a_realizar_cantidad" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input type="number" name="trabajo_realizar_precio_u2" class="trabajo_realizar_precio_u" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 0 7px 0;'><input type="number" disabled name="trabajo_realizar_total2" class="trabajo_realizar_total" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                    </tr>
                    <tr>
                        <td style='padding: 7px 10px 7px 0;'><input type="text" name="trabajo_realizar3" class="trabajo_a_realizar" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input type="number" name="trabajo_realizar_cantidad3" class="trabajo_a_realizar_cantidad" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input type="number" name="trabajo_realizar_precio_u3" class="trabajo_realizar_precio_u" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 0 7px 0;'><input type="number" disabled name="trabajo_realizar_total3" class="trabajo_realizar_total" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                    </tr>
                </table>
            </span>
            <span style="color: #b8b8b8" onclick="comprobar_lineas()"><i class="fas fa-plus-square fa-2x"></i></span>

            <br>
            <div style="width:420px; height: 215px; float:left">
                <h3>Бележки автосервис</h3>
                <textarea name="otras_observaciones" placeholder="Въведи бележка"
                          style="height: 153px; width: 391px;"></textarea>
            </div>

            <div style="width:254px; height: 215px; float:right">

                <h3>Разходи</h3>
                <div style="margin-bottom:20px; float:right">
                    <span style="display: inline-block; width: 168px;">Общо:</span>
                    <span style="display: inline-block; width: 80px">
                            <input type="number" name="total_sin_iva" style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                </div>

                <div style="margin-bottom:20px; float:right">
                    <span style="display: inline-block; width: 168px;">ДДС 20%:</span>
                    <span style="display: inline-block; width: 80px">
                            <input type="number" name="iva" disabled
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    <br>
                    <span style="display: inline-block;width: 13px;margin-top: 12px;margin-right: 14px;margin-left: -4px;"><input type="checkbox" id="anular_iva" onclick="quitar_iva()"></span>
                    <span style="display: inline-block;width: 222px;font-size: 12px;text-align: justify;">Занули ДДС на основание чл.113, ал.9 или друго основание</span>
                </div>
                <br><br>

                <div style="margin-bottom:20px; float:right">
                    <span style="display: inline-block; width: 168px; font-weight: bold">Сума за плащане:</span>
                    <span style="display: inline-block; width: 80px">
                            <input type="number" name="total_factura" disabled
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div id="alert-footer">
            <div style="float:left">
                <span id="alert_imprimir">Принтирай</span>
            </div>
            <div style="float:right">
                <span id="alert_actualizar3">Обнови</span>
                <span id="alert_cancelar3">Отказ</span>
            </div>
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

<div id="miAlerta6" class="alerta">
    <div class="alerta-content">

        <span id="alert-text6"></span>
        <div id="alert-footer">
            <span id="alert_ok" onclick="alert_ok_sin_cancelar()">OK</span>
        </div>
    </div>
</div>


    <script>
        var datos = "";
        var citas_para_borrar = [];
        var id_cita = "";
        var valParam;


        // function alert_update() {
        //     document.getElementById("miAlerta").style.display = "none";
        //
        //     var nombrecliente = document.getElementById('nombre_cliente').value;
        //     var telefonocliente = document.getElementById('telefono_cliente').value;
        //     var correoelectronico = document.getElementById('correo_electronico_cliente').value;
        //
        //     console.log(nombrecliente);
        //     console.log(telefonocliente);
        //     console.log(correoelectronico);
        //
        //     var xhttp2 = new XMLHttpRequest();
        //     xhttp2.onreadystatechange = function () {
        //         if (this.readyState == 4 && this.status == 200) {
        //             console.log(this.responseText);
        //
        //             document.getElementById("tabla").innerHTML = '';
        //             document.getElementById("tabla").innerHTML = this.responseText;
        //
        //             document.getElementById("alert-text4").innerHTML = "Los datos del usuario con telefono " + telefonocliente + " se actualizarón correctamente!";
        //             document.getElementById("miAlerta2").style.display = "block";
        //         }
        //     };
        //     xhttp2.open("POST", "ajax2.php?motivo=actualizar_cliente_de_alerta" + "&nombre_cliente=" + nombrecliente + "&telefono_cliente=" + telefonocliente + "&correo_electronico=" + correoelectronico);
        //     xhttp2.send();
        // }

        function alert_ok() {
            document.getElementById("miAlerta2").style.display = "none";
            location.reload();
        }

        function alert_ok_sin_cancelar() {
            document.getElementById("miAlerta6").style.display = "none";
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

            document.getElementById("alert-text4").innerHTML = "Резервацията бе изтрита успешно!";
            document.getElementById("miAlerta2").style.display = "block";
        });


        document.getElementById("buton_anadir").addEventListener("click", function () {
            document.getElementById("miSlidePanel4").style.display = "block";
        });


        document.getElementById("alert_cancelar").addEventListener("click", function () {
            document.getElementById("miSlidePanel4").style.display = "none";
        });

        document.getElementById("alert_cancelar3").addEventListener("click", function () {
            document.getElementById("miSlidePanel3").style.display = "none";
            location.reload();        //ver porque cuando abro una hoja de trabajo y la cierro y abro otra, el id de la hoja de trabajo no lo coge bien
        });

        document.getElementById("alert_imprimir").addEventListener("click", function () {
            var divToPrint = document.getElementsByClassName('slidePanel-content3')[0];


            //var divToPrint = document.getElementById('datos_taller');
            var popupWin = window.open('', '_blank', 'width=1000,height=1200');

            popupWin.document.open();
            popupWin.document.write('<html><head><link rel="stylesheet" href="css/styles-print.css"></head><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
            popupWin.document.close();
        });


        function cerrar_windows() {
            document.getElementById("miSlidePanel5").style.display = "none";
            location.reload();
        }

        //dian - pulsando sobre la hoja esta es la funciona la que nos saca la vista
        function editar_hoja(cita) {
            id_cita = cita;
            console.log("HOJA QUE VAS A EDITAR >>> " + id_cita);
            //cuando abrimos una hoja rellenada y despues abrimos una no rellenada el id es el mismo que el de la hoja rellenada, la segunda vez esta bien corregir
            document.getElementsByName("num_factura")[0].value = id_cita;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);

                    if (this.responseText.includes("Ненамерена фактура")) {
                        if (clon_realizado == true) {
                            document.getElementById("alert-text3").innerHTML = "";
                            document.getElementById("alert-text3").appendChild(clon);
                            document.getElementById("miSlidePanel3").style.display = "block";
                        } else {
                            document.getElementById("miSlidePanel3").style.display = "block";
                        }

                    } else {
                        clon = document.getElementById("alert-text3").cloneNode(true);
                        clon_realizado = true;
                        document.getElementById("alert-text3").innerHTML = "";
                        document.getElementById("alert-text3").innerHTML = this.responseText;
                        document.getElementById("miSlidePanel3").style.display = "block";
                        comprobar_iva();
                    }
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=editar_hoja" + "&cita=" + cita);
            xhttp.send();
        }


        document.getElementById("alert_actualizar3").addEventListener("click", function () {
            var fecha_entrada = document.getElementsByName('fecha_entrada')[0].value;
            var fecha_salida = document.getElementsByName('fecha_salida')[0].value;
            var nombre_representante = document.getElementsByName('nombre_representante')[0].value;
            var cif_nif = document.getElementsByName('cif_nif')[0].value;
            var empresa = document.getElementsByName('empresa')[0].value;
            var telefono = document.getElementsByName('telefono')[0].value;
            var movil = document.getElementsByName('movil')[0].value;
            var diraccion = document.getElementsByName('diraccion')[0].value;
            var cp = document.getElementsByName('cp')[0].value;
            var poblacion = document.getElementsByName('poblacion')[0].value;
            var marca_modelo = document.getElementsByName('marca_modelo')[0].value;
            var matricula = document.getElementsByName('matricula')[0].value;
            var km = document.getElementsByName('km')[0].value;
            var vin = document.getElementsByName('vin')[0].value;
            var combustible = document.getElementsByName('combustible');
            var trabajo_a_realizar = document.querySelectorAll('.trabajo_a_realizar').length;
            var otras_observaciones = document.getElementsByName('otras_observaciones')[0].value;
            var total_sin_iva = document.getElementsByName('total_sin_iva')[0].value;
            var iva = document.getElementsByName('iva')[0].value;
            var total_factura = document.getElementsByName('total_factura')[0].value;


            var mi_combustible = "";
            for (var x = 0; x < 5; x++) {
                if (combustible[x].checked) {
                    console.log(mi_combustible = combustible[x].value);
                }
            }
            //////////////////////////DIAN GUARDAR trabajo_a_realizar


            var array_servicios_piezas = "";
            for (var x = 1; x <= trabajo_a_realizar; x++) {
                array_servicios_piezas = array_servicios_piezas +
                    document.getElementsByName('trabajo_realizar'+x)[0].value + "sig.,_" +
                    document.getElementsByName('trabajo_realizar_cantidad'+x)[0].value + "sig.,_" +
                    document.getElementsByName('trabajo_realizar_precio_u'+x)[0].value + "sig.,_" +
                    document.getElementsByName('trabajo_realizar_total'+x)[0].value + "fin_fin.,_";
            }


            console.log(fecha_entrada);
            console.log(fecha_salida);
            console.log(nombre_representante);
            console.log(cif_nif);
            console.log(empresa);
            console.log(telefono);
            console.log(movil);
            console.log(diraccion);
            console.log(cp);
            console.log(poblacion);
            console.log(marca_modelo);
            console.log(matricula);
            console.log(km);
            console.log(vin);
            console.log("Combustible => " + mi_combustible);
            console.log(otras_observaciones);
            console.log(total_sin_iva);
            console.log(iva);
            console.log(total_factura);


            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {

                    console.log(this.responseText);

                    document.getElementById("miSlidePanel3").style.display = "none";
                    document.getElementById("alert-text4").innerHTML = "Фактурата бе обновена успешно!";
                    document.getElementById("miAlerta2").style.display = "block";
                }
            };
            xhttp2.open("POST", "ajax2.php?motivo=actualizar_hoja_trabajo" +
                "&fecha_entrada=" + fecha_entrada +
                "&fecha_salida=" + fecha_salida +
                "&nombre_representante=" + nombre_representante +
                "&cif_nif=" + cif_nif +
                "&empresa=" + empresa +
                "&telefono=" + telefono +
                "&movil=" + movil +
                "&diraccion=" + diraccion +
                "&cp=" + cp +
                "&poblacion=" + poblacion +
                "&marca_modelo=" + marca_modelo +
                "&matricula=" + matricula +
                "&km=" + km +
                "&vin=" + vin +
                "&mi_combustible=" + mi_combustible +
                "&texto_trabajo_a_realizar=" + array_servicios_piezas +
                "&otras_observaciones=" + otras_observaciones +
                "&total_sin_iva=" + total_sin_iva +
                "&iva=" + iva +
                "&total_factura=" + total_factura +
                "&idCita=" + id_cita);
            xhttp2.send();
        });



        function calcularTotalServicioPiezas(identificador) {
            identificador = identificador[identificador.length-1];
            var cantidad = document.getElementsByName('trabajo_realizar_cantidad' + identificador)[0].value;
            var precio_unidad = document.getElementsByName('trabajo_realizar_precio_u' + identificador)[0].value;

           document.getElementsByName('trabajo_realizar_total' + identificador)[0].value = (parseFloat(cantidad)*parseFloat(precio_unidad)).toFixed(2);
        }

        function calcularTotal(){
            var cantidad2 = document.querySelectorAll('.trabajo_a_realizar').length;
            var suma = 0;
            for(var x = 1; x <= cantidad2; x++){
                if(document.getElementsByName('trabajo_realizar_total'+x)[0].value != ""){
                    suma += parseFloat(document.getElementsByName('trabajo_realizar_total'+x)[0].value);
                }
            }
            document.getElementsByName('total_sin_iva')[0].value = parseFloat(suma).toFixed(2);
        }

        function calcularIva() {
            var checkBox = document.getElementById("anular_iva");

            //solo calculamos el iva en el caso de que no este checked
            if(checkBox.checked == false) {
                var valor = document.getElementsByName('total_sin_iva')[0].value;
                document.getElementsByName('iva')[0].value = parseFloat(valor * 0.20).toFixed(2);
            }
        }

        function calcularTotalFactura() {
            var total_recambios = 0;

            var total_sin_iva = document.getElementsByName('total_sin_iva')[0].value;
            var iva = document.getElementsByName('iva')[0].value;
            var valor_final = parseFloat(total_sin_iva) + parseFloat(iva);

            document.getElementsByName('total_factura')[0].value = parseFloat(valor_final).toFixed(2);
        }

        
        function quitar_iva() {
            var checkBox = document.getElementById("anular_iva");
            if(checkBox.checked == true){
                document.getElementsByName('iva')[0].value = 0;
                calcularTotalFactura();
            }else{
                calcularTotal();
                calcularIva();
                calcularTotalFactura();
            }
        }

        function comprobar_iva(){
            var iva = document.getElementsByName('iva')[0].value;
            if(iva == 0){
                document.getElementById("anular_iva").checked = true
            }
        }



        function sacar_servicios_por_categoria(pagina) {
            categoria = document.getElementById('categoria');
            console.log(categoria.value);

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("servicio").innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "ajax.php?pagg=" + pagina + "&categoria=" + categoria.value, true);
            xhttp.send();
        }


        function sacar_empleados_por_servicio(pagina) {
            servicio = document.getElementById('servicio');
            console.log(servicio.value);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("empleado").innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "ajax.php?pagg=" + pagina + "&servicio=" + servicio.value, true);
            xhttp.send();
        }



        function editar_cita(cita) {
            id_cita = cita;
            console.log("CITA QUE VAS A EDITAR >>> " + id_cita);

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    document.getElementById("slidePanel-content5-inside").innerHTML = "";
                    document.getElementById("slidePanel-content5-inside").innerHTML = this.responseText;

                    document.getElementById("miSlidePanel5").style.display = "block";
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=editar_cita" + "&cita=" + cita);
            xhttp.send();
        }




        function fecha_cambiada_dame_horario(cita, hora_inicio, hora_fin){
            servicio = document.getElementById("servicio").value;
            empleado = document.getElementById("empleado").value;
            fecha = document.getElementById("fecha2").value;
            categoria = document.getElementById("categoria").value;

            console.log("CITA QUE VAS A EDITAR >>> " + cita);
            console.log("servicio >>> " + servicio);
            console.log("empleado >>> " + empleado);
            console.log("fecha >>> " + fecha);
            console.log("hora_inicio >>> " + hora_inicio);
            console.log("hora_fin >>> " + hora_fin);



            if(categoria == "" || servicio == "" || empleado == "Selecciona un empleado" || fecha == ""){
                document.getElementById("alert-text6").innerHTML = "Трябва да попълниш всичките полета!";
                document.getElementById("miAlerta6").style.display = "block";
            }else{
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);

                        document.getElementById("scroll-content5").innerHTML = "";
                        document.getElementById("scroll-content5").innerHTML = this.responseText;
                        document.getElementById("scroll-content5").style.display = "block";
                        document.getElementById("title-hora-cita").style.display = "block";
                    }
                };
                xhttp.open("POST", "ajax2.php?motivo=editar_cita_dame_horario" + "&cita=" + cita + "&servicio=" + servicio + "&empleado=" + empleado + "&fecha=" + fecha + "&hora_inicio=" + hora_inicio + "&hora_fin=" + hora_fin);
                xhttp.send();
            }
        }



        function actualizar_cita(hora, cita) {
            console.log(hora);
            console.log(cita);
            categoria = document.getElementById("categoria").value;
            servicio = document.getElementById("servicio").value;
            empleado = document.getElementById("empleado").value;
            fecha = document.getElementById("fecha2").value;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);

                    if(this.responseText == "1"){
                        document.getElementById("miSlidePanel5").style.display = "none";
                        document.getElementById("alert-text4").innerHTML = "Резервацията бе обновена услушно!";
                        document.getElementById("miAlerta2").style.display = "block";
                    }

                    //document.getElementById("empleado").innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=actualizar_cita" + "&cita=" + cita + "&hora=" + hora + "&categoria=" + categoria + "&servicio=" + servicio + "&empleado=" + empleado + "&fecha=" + fecha);
            xhttp.send();
        }

        function mostrar_intervalos_crear_cita() {
            cliente_nombre = document.getElementById("cliente_nombre").value;
            cliente_telefono = document.getElementById("cliente_telefono").value;
            empleado = document.getElementById("empleado").value;
            servicio = document.getElementById("servicio").value;
            fecha = document.getElementById("fecha3").value;

            if(cliente_nombre == "" || cliente_telefono == "" || servicio == "Избери услуга" || empleado == "Избери механик"){
                document.getElementById("alert-text6").innerHTML = "Трябва да попълниш всичките полета!";
                document.getElementById("miAlerta6").style.display = "block";
            }else{

                console.log(cliente_nombre);
                console.log(cliente_telefono);
                console.log(empleado);
                console.log(servicio);
                console.log(fecha);


                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);


                        document.getElementById("scroll-content3").innerHTML = "";
                        document.getElementById("scroll-content3").innerHTML = this.responseText;
                        document.getElementById("scroll-content3").style.display = "block";
                        document.getElementById("title-hora-cita2").style.display = "block";


                    }
                };
                xhttp.open("POST", "ajax2.php?motivo=mostrar_intervalos_crear_cita_manual" + "&empleado=" + empleado + "&servicio=" + servicio + "&fecha=" + fecha);
                xhttp.send();
            }
        }

        function insertar_nueva_cita_manual(hora) {
            console.log(hora);
            nombre = document.getElementById("cliente_nombre").value;
            telefono = document.getElementById("cliente_telefono").value;
            empleado = document.getElementById("empleado").value;
            servicio = document.getElementById("servicio").value;
            fecha = document.getElementById("fecha3").value;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);

                    if(this.responseText == "1"){
                        document.getElementById("miSlidePanel4").style.display = "none";
                        document.getElementById("alert-text4").innerHTML = "Резервацията бе добавена успешно!";
                        document.getElementById("miAlerta2").style.display = "block";
                    }

                    //document.getElementById("empleado").innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=insertar_nueva_cita_manual" + "&nombre=" + nombre + "&telefono=" + telefono + "&empleado=" + empleado + "&servicio=" + servicio + "&hora=" + hora + "&fecha=" + fecha);
            xhttp.send();
        }
    </script>
</body>