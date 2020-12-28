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
    <title>Админ панел - Служители</title>
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
<h1>Служители</h1>
<div>
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
        $resultado = mysqli_query($conexion, "SELECT * FROM `empleados`");
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<tr class='trr' id='emple$row[0]'>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td>";
            for($x = 0; $x < strlen($row[4]); $x++){
                echo "•";
            }
            echo "</td>";
            echo "<td><button type='button' id='buton_tabla' onclick='editar_empleado(\"$row[0]\")'><i class='button_imagen'></td>";
            echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]'></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <div style="margin-top: 20px">
        <button id="buton_borrar_emple"><i class="fas fa-trash-alt"></i><span>  Изтрий служител</span></button>
        <button id="buton_anadir_emple"><i class="fas fa-plus"></i><span>  Дабави служител</span></button>
    </div>


    <div id="popUp_anadir_emple" class="popUp_anadir_emple">
        <div class="popUp_anadir_emple_content">
            <div id="titulo">
                <h2>Добавяне на служител</h2>
            </div>
            <span id="alert-text">
                Име<br><br>
                <input type="text" id="nombre_empleado"><br><br>

                Телефон:<br><br>
                <input type="number" id="telefono_empleado"><br><br>

                Имейл адрес:<br><br>
                <input type="text" name="correo_empleado" id="correo_empleado"><br><br>

                Парола:<br><br>
                <input type="password" name="contrasena_empleado" id="contrasena_empleado">
            <br>
            </span>
            <div id="alert-footer">
                <span id="buton_anadir_emple_interno">Добави</span>
                <span id="buton_cancelar_emple_interno">Отмени</span>
            </div>
        </div>
    </div>


    <br><br><br><br><br>


    <div id="popUp_editar_emple_datos" class="popUp_editar_emple_datos">
        <div class="popUp_editar_emple_datos_content">
            <div id="titulo2">
                <h2>Реадктиране на профил</h2>
            </div>
            <div id="menu_actualizar_empleado">
                <ul>
                    <li id="datos_emple" class="datos_emple" onclick="abrir_datos_emple()">Лични данни</li>
                    <li id="servicios_emple" class="servicios_emple" onclick="abrir_servicios_emple()">Извършващи услуги</li>
                    <li id="horarios_emple" class="horarios_emple" onclick="abrir_horarios_emple()">Работно време</li>
                </ul>
            </div>
            <span id="popUp_editar_emple_datos_info"></span>
            <div id="alert-footer2">
                <span id="buton_actualizar_emple_interno_datos">Обнови</span>
                <span id="buton_cancelar_emple_interno_datos">Отмени</span>
            </div>
        </div>
    </div>


    <div id="popUp_editar_emple_servicios" class="popUp_editar_emple_servicios">
        <div class="popUp_editar_emple_servicios_content">
            <div id="titulo2">
                <h2>Реадктиране на профила</h2>
            </div>
            <div id="menu_actualizar_empleado">
                <ul>
                    <li id="datos_emple" class="datos_emple" onclick="abrir_datos_emple()">Лични данни</li>
                    <li id="servicios_emple" class="servicios_emple" onclick="abrir_servicios_emple()">Извършващи услуги</li>
                    <li id="horarios_emple2" class="horarios_emple" onclick="abrir_horarios_emple()">Работно време</li>
                </ul>
            </div>
            <span id="popUp_editar_emple_servicios_info"></span>
            <div id="alert-footer2">
                <span id="buton_actualizar_emple_interno_servicios">Обнови</span>
                <span id="buton_cancelar_emple_interno_servicios">Отмени</span>
            </div>
        </div>
    </div>


    <div id="popUp_editar_emple_horarios" class="popUp_editar_emple_horarios">
        <div class="popUp_editar_emple_horarios_content">
            <div id="titulo2">
                <h2>Реадктиране на профила</h2>
            </div>
            <div id="menu_actualizar_empleado">
                <ul>
                    <li id="datos_emple" class="datos_emple" onclick="abrir_datos_emple()">Лични данни</li>
                    <li id="servicios_emple2" class="servicios_emple" onclick="abrir_servicios_emple()">Извършващи услуги</li>
                    <li id="horarios_emple" class="horarios_emple" onclick="abrir_horarios_emple()">Работно време</li>
                </ul>
            </div>
            <span id="popUp_editar_emple_horarios_info"></span>
            <div id="alert-footer2">
                <span id="buton_actualizar_emple_interno_horarios">Обнови</span>
                <span id="buton_cancelar_emple_interno_horarios">Отмени</span>
            </div>
        </div>
    </div>


    <div id="miAlerta" class="alerta">
        <div class="alerta-content">

            <span id="alert-text3"></span>
            <div id="alert-footer">
                <span id="alert_actualizar" onclick="alert_back()">Връщане за коригиране на въведените данни</span>
                <span id="alert_cancelar" onclick="alert_cancel()">Отмени</span>
            </div>
        </div>
    </div>


    <div id="miAlerta2" class="alerta">
        <div class="alerta-content">

            <span id="alert-text4"></span>
            <div id="alert-footer">
                <span id="alert_ok" onclick="alert_ok()">ОК</span>
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


    <script>
        var datos = "";
        var empleados_para_borrar = [];
        var id_empleado = "";


        function alert_cancel() {
            document.getElementById("miAlerta").style.display = "none";
        }

        function alert_ok() {
            document.getElementById("miAlerta2").style.display = "none";
        }

        function alert_back() {
            document.getElementById("miAlerta").style.display = "none";
            document.getElementById("popUp_anadir_emple").style.display = "block";
        }

        function alert_back2() {
            document.getElementById("miAlerta3").style.display = "none";
            document.getElementById("popUp_editar_emple_datos").style.display = "block";
        }


        document.getElementById("buton_anadir_emple").addEventListener("click", function () {
            document.getElementById("popUp_anadir_emple").style.display = "block";
        });


        document.getElementById("buton_cancelar_emple_interno").addEventListener("click", function () {
            document.getElementById("popUp_anadir_emple").style.display = "none";
        });


        document.getElementById("buton_cancelar_emple_interno_datos").addEventListener("click", function () {
            document.getElementById("popUp_editar_emple_datos").style.display = "none";
        });


        document.getElementById("buton_cancelar_emple_interno_servicios").addEventListener("click", function () {
            document.getElementById("popUp_editar_emple_servicios").style.display = "none";
        });


        document.getElementById("buton_cancelar_emple_interno_horarios").addEventListener("click", function () {
            document.getElementById("popUp_editar_emple_horarios").style.display = "none";
        });


        //se a?ade empleado al pulsar
        document.getElementById("buton_anadir_emple_interno").addEventListener("click", function () {
            var nombre = document.getElementById("nombre_empleado").value;
            var telefono = document.getElementById("telefono_empleado").value;
            var correo = document.getElementById("correo_empleado").value;
            var contrasena = document.getElementById("contrasena_empleado").value;

            console.log(nombre);
            console.log(telefono);
            console.log(correo);
            console.log(contrasena);

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        document.getElementById("popUp_anadir_emple").style.display = "none";



                        if (this.responseText.includes("ГРЕШКА")) {
                            document.getElementById("alert-text3").innerHTML = this.responseText;
                            //mostramos la alerta
                            document.getElementById("miAlerta").style.display = "block";
                        }else if(this.responseText.includes("Не сте попълнили всички полета")){
                            document.getElementById("alert-text3").innerHTML = this.responseText;
                            document.getElementById("miAlerta").style.display = "block";
                        } else {
                            document.getElementById("tabla").innerHTML = '';
                            document.getElementById("tabla").innerHTML = this.responseText;

                            document.getElementById("alert-text4").innerHTML = 'Служителят бе добавен услушно!';
                            document.getElementById("miAlerta2").style.display = "block";
                        }
                    }
                };
                xhttp.open("POST", "ajax2.php?motivo=anadir_empleado" + "&nombre=" + nombre + "&telefono=" + telefono + "&correo=" + correo + "&contrasena=" + contrasena);
                xhttp.send();
          });

        //se borra empleado al pulsar
        document.getElementById("buton_borrar_emple").addEventListener("click", function () {
            // Query for only the checked checkboxes and put the result in an array
            let checked = Array.prototype.slice.call(document.querySelectorAll("input[type='checkbox']:checked"));
            console.clear();
            // Loop over the array and inspect contents
            checked.forEach(function (cb) {
                //console.log("Datos antes de enviar AJAX " + cb.value);

                empleados_para_borrar.push("emple" + cb.value);
                datos += cb.value + ",";
            });
            if (empleados_para_borrar.length > 0) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        console.log(empleados_para_borrar);

                        //eliminamos los divs con los empleados seleccionados
                        for (var x = 0; x < empleados_para_borrar.length; x++) {
                            document.getElementById(empleados_para_borrar[x]).innerHTML = '';
                        }
                        document.getElementById("alert-text4").innerHTML = 'Служителят бе изтрит успешно!';
                        document.getElementById("miAlerta2").style.display = "block";
                    }
                };
                xhttp.open("POST", "ajax2.php?motivo=eliminar_empleado" + "&empleados=" + datos);
                xhttp.send();
            }
        });


        //se muestra el popUp con los datos del empleado sobre el que se ha pusaldo
        function editar_empleado(empleado) {
            document.getElementsByClassName("datos_emple")[0].classList.add("active");
            document.getElementsByClassName("servicios_emple")[0].classList.remove("active");
            document.getElementsByClassName("horarios_emple")[0].classList.remove("active");
            document.getElementById("popUp_editar_emple_datos").style.display = "block";

            //console.log("EMPLEADO QUE VAS A EDITAR >>> " + empleado);
            id_empleado = empleado;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {

                    console.log(this.responseText);
                    document.getElementById("popUp_editar_emple_datos_info").innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=editar_empleado" + "&empleado=" + empleado);
            xhttp.send();
        }


        //se actualizan los datos del empleado al pulsar
        document.getElementById("buton_actualizar_emple_interno_datos").addEventListener("click", function () {
            console.log("SERVICIO QUE VAS A EDITAR >>> " + id_empleado);

            var nombre_empleado = document.getElementById('nombre_empleado2').value;
            var telefono_empleado = document.getElementById('telefono_empleado2').value;
            var correo_empleado = document.getElementById('correo_empleado2').value;
            var contrasena_empleado = document.getElementById('contrasena_empleado2').value;

            console.log(nombre_empleado);
            console.log(telefono_empleado);
            console.log(correo_empleado);

            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("popUp_editar_emple_datos").style.display = "none";

                    if (this.responseText.includes("ГРЕШКА")) {
                        document.getElementById("alert-text5").innerHTML = this.responseText;
                        //mostramos la alerta
                        document.getElementById("miAlerta3").style.display = "block";
                    } else {
                        document.getElementById("tabla").innerHTML = '';
                        document.getElementById("tabla").innerHTML = this.responseText;

                        document.getElementById("alert-text4").innerHTML = "Данните на служителя бяха обновени успешно!";
                        document.getElementById("miAlerta2").style.display = "block";
                    }
                }
            };
            xhttp2.open("POST", "ajax2.php?motivo=actualizar_empleado" + "&nombre_empleado=" + nombre_empleado + "&telefono_empleado=" + telefono_empleado + "&correo_empleado=" + correo_empleado + "&contrasena_empleado=" + contrasena_empleado + "&idEmpleado=" + id_empleado);
            xhttp2.send();
        });

        //lo llamamos cuando pulsamos en servicios_empleado
        document.getElementById("servicios_emple").addEventListener("click", function () {

            document.getElementById("popUp_editar_emple_servicios").style.display = "block";

            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    document.getElementById("popUp_editar_emple_servicios_info").innerHTML = this.responseText;
                }
            };
            xhttp2.open("POST", "ajax2.php?motivo=mostrar_servicios2" + "&idEmpleado=" + id_empleado);
            xhttp2.send();
        });

        //lo llamamos cuando pulsamos en horario_empleado
        document.getElementById("servicios_emple2").addEventListener("click", function () {

            document.getElementById("popUp_editar_emple_servicios").style.display = "block";

            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    document.getElementById("popUp_editar_emple_servicios_info").innerHTML = this.responseText;
                }
            };
            xhttp2.open("POST", "ajax2.php?motivo=mostrar_servicios2" + "&idEmpleado=" + id_empleado);
            xhttp2.send();
        });


        document.getElementById("buton_actualizar_emple_interno_servicios").addEventListener("click", function () {

            array_servicios_asignados_nombre = [];
            array_servicios_asignados_precio = [];

            var inputElements = document.getElementsByName("servicios[]");
            for (var i = 0; i < inputElements.length; i++) {
                if (inputElements[i].checked) {
                    console.log(inputElements[i].value);

                    var nombre_servicio = inputElements[i].value;

                    var precio = document.getElementById("precio_" + nombre_servicio).value;

                    array_servicios_asignados_nombre.push(nombre_servicio + "--");
                    array_servicios_asignados_precio.push(precio + "--");

                } else {
                    console.log(inputElements[i].value);
                }
            }

            console.log(array_servicios_asignados_nombre);
            console.log(array_servicios_asignados_precio);


            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    document.getElementById("popUp_editar_emple_servicios").style.display = "none";
                    document.getElementById("alert-text4").innerHTML = "Услугите на служителя бяха обновени успешно!";
                    document.getElementById("miAlerta2").style.display = "block";
                }
            };
            xhttp2.open("POST", "ajax2.php?motivo=actualizar_servicios_asignados_emple" + "&array_servicios_asignados_nombre=" + array_servicios_asignados_nombre + "&array_servicios_asignados_precio=" + array_servicios_asignados_precio + "&idEmpleado=" + id_empleado);
            xhttp2.send();
        });


        document.getElementById("horarios_emple").addEventListener("click", function () {
            document.getElementById("popUp_editar_emple_horarios_info").style.display = "block";

            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    document.getElementById("popUp_editar_emple_horarios_info").innerHTML = this.responseText;
                }
            };
            xhttp2.open("POST", "ajax2.php?motivo=mostrar_horarios" + "&idEmpleado=" + id_empleado);
            xhttp2.send();
        });


        //he tenido que dublicar porque al tener dos IDs iguales, al hacer click por segunda vez en el mismo Id pero en el otro sitio ya no reaccionaba
        document.getElementById("horarios_emple2").addEventListener("click", function () {
            document.getElementById("popUp_editar_emple_horarios_info").style.display = "block";

            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    document.getElementById("popUp_editar_emple_horarios_info").innerHTML = this.responseText;
                }
            };
            xhttp2.open("POST", "ajax2.php?motivo=mostrar_horarios" + "&idEmpleado=" + id_empleado);
            xhttp2.send();
        });


        document.getElementById("buton_actualizar_emple_interno_horarios").addEventListener("click", function () {
            console.log("SERVICIO QUE VAS A EDITAR >>> " + id_empleado);

            array_horarios_start = [];
            array_horarios_fin = [];

            var start_Понеделник = document.getElementById('start_Понеделник').value;
            var start_Вторник = document.getElementById('start_Вторник').value;
            var start_Сряда = document.getElementById('start_Сряда').value;
            var start_Четвъртък = document.getElementById('start_Четвъртък').value;
            var start_Петък = document.getElementById('start_Петък').value;
            var start_Събота = document.getElementById('start_Събота').value;
            var start_Неделя = document.getElementById('start_Неделя').value;

            array_horarios_start.push(start_Понеделник);
            array_horarios_start.push(start_Вторник);
            array_horarios_start.push(start_Сряда);
            array_horarios_start.push(start_Четвъртък);
            array_horarios_start.push(start_Петък);
            array_horarios_start.push(start_Събота);
            array_horarios_start.push(start_Неделя);

            var fin_Понеделник = document.getElementById('fin_Понеделник').value;
            var fin_Вторник = document.getElementById('fin_Вторник').value;
            var fin_Сряда = document.getElementById('fin_Сряда').value;
            var fin_Четвъртък = document.getElementById('fin_Четвъртък').value;
            var fin_Петък = document.getElementById('fin_Петък').value;
            var fin_Събота = document.getElementById('fin_Събота').value;
            var fin_Неделя = document.getElementById('fin_Неделя').value;

            array_horarios_fin.push(fin_Понеделник);
            array_horarios_fin.push(fin_Вторник);
            array_horarios_fin.push(fin_Сряда);
            array_horarios_fin.push(fin_Четвъртък);
            array_horarios_fin.push(fin_Петък);
            array_horarios_fin.push(fin_Събота);
            array_horarios_fin.push(fin_Неделя);

            //console.log(array_horarios_start);
            //console.log(array_horarios_fin);


            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("popUp_editar_emple_horarios").style.display = "none";
                    document.getElementById("alert-text4").innerHTML = "Работното време на служителя бе обновено успешно!";
                    document.getElementById("miAlerta2").style.display = "block";
                }
            };
            xhttp2.open("POST", "ajax2.php?motivo=anadir_horarios" + "&idEmpleado=" + id_empleado + "&array_horarios_start=" + array_horarios_start + "&array_horarios_fin=" + array_horarios_fin);
            xhttp2.send();
        });


        function abrir_datos_emple() {
            document.getElementById("popUp_editar_emple_servicios").style.display = "none";
            document.getElementById("popUp_editar_emple_horarios").style.display = "none";
            document.getElementById("popUp_editar_emple_datos").style.display = "block";

            document.getElementsByClassName("datos_emple")[0].classList.add("active");
            document.getElementsByClassName("datos_emple")[1].classList.add("active");
            document.getElementsByClassName("datos_emple")[2].classList.add("active");

            document.getElementsByClassName("servicios_emple")[0].classList.remove("active");
            document.getElementsByClassName("servicios_emple")[1].classList.remove("active");
            document.getElementsByClassName("servicios_emple")[2].classList.remove("active");

            document.getElementsByClassName("horarios_emple")[0].classList.remove("active");
            document.getElementsByClassName("horarios_emple")[1].classList.remove("active");
            document.getElementsByClassName("horarios_emple")[2].classList.remove("active");
        }


        function abrir_servicios_emple() {

            document.getElementById("popUp_editar_emple_datos").style.display = "none";
            document.getElementById("popUp_editar_emple_horarios").style.display = "none";
            document.getElementById("popUp_editar_emple_servicios").style.display = "block";

            document.getElementsByClassName("servicios_emple")[0].classList.add("active");
            document.getElementsByClassName("servicios_emple")[1].classList.add("active");
            document.getElementsByClassName("servicios_emple")[2].classList.add("active");

            document.getElementsByClassName("datos_emple")[0].classList.remove("active");
            document.getElementsByClassName("datos_emple")[1].classList.remove("active");
            document.getElementsByClassName("datos_emple")[2].classList.remove("active");

            document.getElementsByClassName("horarios_emple")[0].classList.remove("active");
            document.getElementsByClassName("horarios_emple")[1].classList.remove("active");
            document.getElementsByClassName("horarios_emple")[2].classList.remove("active");
        }


        function abrir_horarios_emple() {
            document.getElementById("popUp_editar_emple_servicios").style.display = "none";
            document.getElementById("popUp_editar_emple_datos").style.display = "none";
            document.getElementById("popUp_editar_emple_horarios").style.display = "block";

            document.getElementsByClassName("horarios_emple")[0].classList.add("active");
            document.getElementsByClassName("horarios_emple")[1].classList.add("active");
            document.getElementsByClassName("horarios_emple")[2].classList.add("active");

            document.getElementsByClassName("servicios_emple")[0].classList.remove("active");
            document.getElementsByClassName("servicios_emple")[1].classList.remove("active");
            document.getElementsByClassName("servicios_emple")[2].classList.remove("active");

            document.getElementsByClassName("datos_emple")[0].classList.remove("active");
            document.getElementsByClassName("datos_emple")[1].classList.remove("active");
            document.getElementsByClassName("datos_emple")[2].classList.remove("active");
        }
    </script>
</div>
</body>
