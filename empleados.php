<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
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
<h1>Empleados</h1>
<div>
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

        $resultado = mysqli_query($conexion, "SELECT * FROM `empleados`");
        while ($row = mysqli_fetch_array($resultado)) {
            echo "<tr class='trr' id='emple$row[0]'>";
            echo "<td>$row[1]</td>";
            echo "<td>$row[2]</td>";
            echo "<td>$row[3]</td>";
            echo "<td><button type='button' id='buton_tabla' onclick='editar_empleado(\"$row[0]\")'><i class='button_imagen'></td>";
            echo "<td><input type='checkbox' id='checkbox_borrar'  name='location[]' value='$row[0]'></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <div style="margin-top: 20px">
        <button id="buton_borrar_emple"><span class="icon_borrar">  Eliminar empleado</span></button>
        <button id="buton_anadir_emple"><span class="icon_anadir">  Añadir empleado</span></button>

    </div>


    <div id="popUp_anadir_emple" class="popUp_anadir_emple">
        <div class="popUp_anadir_emple_content">
            <div id="titulo">
                <h2>Añadir empleado</h2>
            </div>
            <span id="alert-text">
                Nombre:<br><br>
                <input type="text" id="nombre_empleado"><br><br>

                Telefono:<br><br>
                <input type="number" id="telefono_empleado"><br><br>

                Correo electronico:<br><br>
                <input type="text" name="correo_empleado" id="correo_empleado">
            <br>
            </span>
            <div id="alert-footer">
                <span id="buton_anadir_emple_interno">Añadir</span>
                <span id="buton_cancelar_emple_interno">Cancelar</span>
            </div>
        </div>
    </div>


    <br><br><br><br><br>


    <div id="popUp_editar_emple_datos" class="popUp_editar_emple_datos">
        <div class="popUp_editar_emple_datos_content">
            <div id="titulo2">
                <h2>Actualizar empleado</h2>
            </div>
            <div id="menu_actualizar_empleado">
                <ul>
                    <li id="datos_emple" class="datos_emple" onclick="abrir_datos_emple()">Datos empleado</li>
                    <li id="servicios_emple" class="servicios_emple" onclick="abrir_servicios_emple()">Servicios
                        empleado
                    </li>
                    <li id="horarios_emple" class="horarios_emple" onclick="abrir_horarios_emple()">Horario empleado
                    </li>
                </ul>
            </div>
            <span id="popUp_editar_emple_datos_info"></span>
            <div id="alert-footer2">
                <span id="buton_actualizar_emple_interno_datos">Actualizar</span>
                <span id="buton_cancelar_emple_interno_datos">Cancelar</span>
            </div>
        </div>
    </div>


    <div id="popUp_editar_emple_servicios" class="popUp_editar_emple_servicios">
        <div class="popUp_editar_emple_servicios_content">
            <div id="titulo2">
                <h2>Actualizar empleado</h2>
            </div>
            <div id="menu_actualizar_empleado">
                <ul>
                    <li id="datos_emple" class="datos_emple" onclick="abrir_datos_emple()">Datos empleado</li>
                    <li id="servicios_emple" class="servicios_emple" onclick="abrir_servicios_emple()">Servicios
                        empleado
                    </li>
                    <li id="horarios_emple2" class="horarios_emple" onclick="abrir_horarios_emple()">Horario empleado
                    </li>
                </ul>
            </div>
            <span id="popUp_editar_emple_servicios_info"></span>
            <div id="alert-footer2">
                <span id="buton_actualizar_emple_interno_servicios">Actualizar</span>
                <span id="buton_cancelar_emple_interno_servicios">Cancelar</span>
            </div>
        </div>
    </div>


    <div id="popUp_editar_emple_horarios" class="popUp_editar_emple_horarios">
        <div class="popUp_editar_emple_horarios_content">
            <div id="titulo2">
                <h2>Actualizar empleado</h2>
            </div>
            <div id="menu_actualizar_empleado">
                <ul>
                    <li id="datos_emple" class="datos_emple" onclick="abrir_datos_emple()">Datos empleado</li>
                    <li id="servicios_emple2" class="servicios_emple" onclick="abrir_servicios_emple()">Servicios
                        empleado
                    </li>
                    <li id="horarios_emple" class="horarios_emple" onclick="abrir_horarios_emple()">Horario empleado
                    </li>
                </ul>
            </div>
            <span id="popUp_editar_emple_horarios_info"></span>
            <div id="alert-footer2">
                <span id="buton_actualizar_emple_interno_horarios">Actualizar</span>
                <span id="buton_cancelar_emple_interno_horarios">Cancelar</span>
            </div>
        </div>
    </div>


    <div id="miAlerta" class="alerta">
        <div class="alerta-content">

            <span id="alert-text3"></span>
            <div id="alert-footer">
                <span id="alert_actualizar" onclick="alert_back()">Volver y corregir los datos introducidos</span>
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

    <div id="miAlerta3" class="alerta">
        <div class="alerta-content">

            <span id="alert-text5"></span>
            <div id="alert-footer">
                <span id="alert_actualizar" onclick="alert_back2()">Volver y corregir los datos introducidos</span>
                <span id="alert_cancelar" onclick="alert_cancel()">Cancelar</span>
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


        //se añade empleado al pulsar
        document.getElementById("buton_anadir_emple_interno").addEventListener("click", function () {
            var nombre = document.getElementById("nombre_empleado").value;
            var telefono = document.getElementById("telefono_empleado").value;
            var correo = document.getElementById("correo_empleado").value;

            console.log(nombre);
            console.log(telefono);
            console.log(correo);

            if (nombre != "" && telefono != "" && correo != "") {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        document.getElementById("popUp_anadir_emple").style.display = "none";

                        if (this.responseText.includes("ERROR")) {
                            document.getElementById("alert-text3").innerHTML = this.responseText;
                            //mostramos la alerta
                            document.getElementById("miAlerta").style.display = "block";
                        } else {
                            document.getElementById("tabla").innerHTML = '';
                            document.getElementById("tabla").innerHTML = this.responseText;

                            document.getElementById("alert-text4").innerHTML = "El empleado ha sido creado satisfactoriamente!";
                            document.getElementById("miAlerta2").style.display = "block";
                        }
                    }
                };
                xhttp.open("POST", "ajax2.php?motivo=anadir_empleado" + "&nombre=" + nombre + "&telefono=" + telefono + "&correo=" + correo);
                xhttp.send();
            }
        });

        //se borra empleado al pulsar
        document.getElementById("buton_borrar_emple").addEventListener("click", function () {
            // Query for only the checked checkboxes and put the result in an array
            let checked = Array.prototype.slice.call(document.querySelectorAll("input[type='checkbox']:checked"));
            console.clear();
            // Loop over the array and inspect contents
            checked.forEach(function (cb) {
                console.log("Datos antes de enviar AJAX " + cb.value);

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
                        document.getElementById("alert-text4").innerHTML = "La operación ha sido realizada con exito!";
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

            console.log(nombre_empleado);
            console.log(telefono_empleado);
            console.log(correo_empleado);

            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("popUp_editar_emple_datos").style.display = "none";

                    if (this.responseText.includes("ERROR")) {
                        document.getElementById("alert-text5").innerHTML = this.responseText;
                        //mostramos la alerta
                        document.getElementById("miAlerta3").style.display = "block";
                    } else {
                        document.getElementById("tabla").innerHTML = '';
                        document.getElementById("tabla").innerHTML = this.responseText;

                        document.getElementById("alert-text4").innerHTML = "Los datos del empleado han sido actializados satisfactoriamente!";
                        document.getElementById("miAlerta2").style.display = "block";
                    }
                }
            };
            xhttp2.open("POST", "ajax2.php?motivo=actualizar_empleado" + "&nombre_empleado=" + nombre_empleado + "&telefono_empleado=" + telefono_empleado + "&correo_empleado=" + correo_empleado + "&idEmpleado=" + id_empleado);
            xhttp2.send();
        });


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
                    document.getElementById("alert-text4").innerHTML = "Los servicios del empleado han sido actializados satisfactoriamente!";
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

            var start_Lunes = document.getElementById('start_Lunes').value;
            var start_Martes = document.getElementById('start_Martes').value;
            var start_Miercoles = document.getElementById('start_Miercoles').value;
            var start_Jueves = document.getElementById('start_Jueves').value;
            var start_Viernes = document.getElementById('start_Viernes').value;
            var start_Sabado = document.getElementById('start_Sabado').value;
            var start_Domingo = document.getElementById('start_Domingo').value;

            array_horarios_start.push(start_Lunes);
            array_horarios_start.push(start_Martes);
            array_horarios_start.push(start_Miercoles);
            array_horarios_start.push(start_Jueves);
            array_horarios_start.push(start_Viernes);
            array_horarios_start.push(start_Sabado);
            array_horarios_start.push(start_Domingo);

            var fin_Lunes = document.getElementById('fin_Lunes').value;
            var fin_Martes = document.getElementById('fin_Martes').value;
            var fin_Miercoles = document.getElementById('fin_Miercoles').value;
            var fin_Jueves = document.getElementById('fin_Jueves').value;
            var fin_Viernes = document.getElementById('fin_Viernes').value;
            var fin_Sabado = document.getElementById('fin_Sabado').value;
            var fin_Domingo = document.getElementById('fin_Domingo').value;

            array_horarios_fin.push(fin_Lunes);
            array_horarios_fin.push(fin_Martes);
            array_horarios_fin.push(fin_Miercoles);
            array_horarios_fin.push(fin_Jueves);
            array_horarios_fin.push(fin_Viernes);
            array_horarios_fin.push(fin_Sabado);
            array_horarios_fin.push(fin_Domingo);

            //console.log(array_horarios_start);
            //console.log(array_horarios_fin);


            var xhttp2 = new XMLHttpRequest();
            xhttp2.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("popUp_editar_emple_horarios").style.display = "none";
                    document.getElementById("alert-text4").innerHTML = "Los horarios del empleado han sido actializados satisfactoriamente!";
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
