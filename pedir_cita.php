<?php
require_once("config.php");
$conexion = new mysqli(conexion_host, conexion_user, conexion_pass, conexion_bbdd, conexion_port);

if ($conexion->connect_error) {
    die("Error conexion bd: " . $conexion->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles-calendar.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" rel="stylesheet"/>
	<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
    <script>
        //metodo que me devuelve el valor de una cookie
        function getCookie(nombreCookie) {
            cName = "";
            pCOOKIES = new Array();
            pCOOKIES = document.cookie.split('; ');
            for (bb = 0; bb < pCOOKIES.length; bb++) {
                NmeVal = new Array();
                NmeVal = pCOOKIES[bb].split('=');
                if (NmeVal[0] == nombreCookie) {
                    cName = unescape(NmeVal[1]);
                }
            }
            return cName;
        }

        //metodos que me guardan los valores en cookies que usasare para hacer la select final
        function guardar_valor_cookies(valor_a_guardar) {
            var now = new Date();
            var time = now.getTime();
            time += 360 * 1000;
            now.setTime(time);

            if (valor_a_guardar === "servicio") {
                asd = document.getElementById("servicio").value;
                document.cookie = "servicio" + "=" + asd + '; expires=' + now.toUTCString();
            } else if (valor_a_guardar === "empleado") {
                asd2 = document.getElementById("empleado").value;
                document.cookie = "empleado" + "=" + asd2 + '; expires=' + now.toUTCString();
            } else if (valor_a_guardar === "fecha") {
                //document.cookie = "fecha=2019-12-01";     por ahora lo dejamos asi pero cuando pongo el calendario aqui se guardara la fecha
            }
        }


        function guardar_valor_cookie_hora(valor) {
				var now = new Date();
                var time = now.getTime();
                time += 360 * 1000;
                now.setTime(time);
			
                document.cookie = "hora" + "=" + valor + '; expires=' + now.toUTCString();
        }

        function alert_cancel() {
            document.getElementById("miAlerta").style.display = "none";
        }
        
                    //comprueba si todos los campos estan rellenados para sacar alerta o saltar a la siguiuente pantalla
            function comprobar_datos(num_contenedor) {

                if (num_contenedor === "contenedor1") {
                    if (getCookie('servicio') === "" || getCookie('empleado') === "" || getCookie('fecha') === "") {

                        document.getElementById("alert-text4").innerHTML =
                                "<h4>За да направите резервация трябва да попълните всички полета!</h4>";
                        document.getElementById("miAlerta2").style.display = "block";
                    } else {
                        show(2);
                        seleccionar_hora(2);
                        window.location="#pedir-cita";
                    }
                }
            }

            function alert_ok() {
                document.getElementById("miAlerta2").style.display = "none";
            }
    </script>
</head>
<body>
<div id="miAlerta2" class="alerta">
    <div class="alerta-content">

        <span id="alert-text4"></span>
        <div id="alert-footer">
            <span id="alert_ok2" onclick="alert_ok()">OK</span>
        </div>
    </div>
</div>
    
    
    
<div id="contenedor1" style="display: block">
    <div id="estados">
        <ul class="bookme-pro-steps">
            <li class="bookme-pro-steps-is-active">Услуга</li>
            <li>Час</li>
            <li>Данни</li>
            <li>Край</li>
        </ul>
    </div>
    <div id="content">
        <h4>Моля, попълнете всички задължителни полета, отбелязани със звездичка (*).</h4>
        <div id="left">
            <div>
                <!-- GENERAMOS CATEGORIAS -->
                <h5>Категория: *</h5>
                <select id="categoria" name="categoria_escogida" onchange="sacar_servicios_por_categoria(1)">
                    <option>Изберете категория</option>

                    <?php
                    $resultado = mysqli_query($conexion, "SELECT * FROM `categorias`");
                    while ($row = mysqli_fetch_array($resultado)) {
                        echo "<option value='$row[1]'>" . $row[1] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div><br><br>


                <script>
                    //una vez que escogemos la categoria carga este script, el script mandra mediante ajax la categoria, se hace una select de todos los servicios bajo esa categoria y se muestran
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
                </script>

                <!-- Aqui se mostraran todos los servicios de la categoria que hemos seleccionado -->
                <h5>Услуга: *</h5>
                <div id="servicios"></div>
                <select id="servicio" name="categoria_escogida"
                        onchange="sacar_empleados_por_servicio(1), guardar_valor_cookies('servicio')">
                    <option>Изберете услуга</option>
                </select>
            </div>


            <script>
                // una vez que escogemos el servicio se carga este script, el script manda median ajax el servicio, se hace una select de todos los empleados que realizan ese servicio y se muestran
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
            </script>


            <div>
                <br><br><h5>Механик: *</h5>
                <div id="empleados"></div>
                <select id="empleado" onchange="guardar_valor_cookies('empleado')">
                    <option>Изберете механик</option>
                </select>
            </div>
        </div>
        <div id="right">
            <h5>Дата: *</h5>
            <?php
            $file = file_get_contents('calendario_make_appointment.php');
            $content = eval("?>$file");
            echo $content;
            ?>
        </div>
        <div class="clear"></div>
        <div id="separador"></div>
        <span id="aviso" style="display: inline-block"></span>
        <button id="buton_siguiente" onclick="comprobar_datos('contenedor1')">Напред</button>
    </div>

</div>


<div id="contenedor2" style="display: none">
    <div id="estados">
        <ul class="bookme-pro-steps">
            <li>Услуга</li>
            <li class="bookme-pro-steps-is-active">Час</li>
            <li>Данни</li>
            <li>Край</li>
        </ul>
    </div>
    <div id="content">
        <h4>Изберете удобен час</h4>

        <div id='scroll-content'></div>


        <script>
            function seleccionar_hora(pagina) {
                console.log(getCookie('fecha'));
                console.log(getCookie('empleado'));
                console.log(getCookie('servicio'));

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {

                        document.getElementById("scroll-content").innerHTML = this.responseText;

                        var fecha_partida = getCookie('fecha').split('-');

                        document.getElementById('diaSeleccionado').innerHTML = getCookie('diaSemana') + ", " + fecha_partida[2] + " " + document.getElementById("month").innerText;
                    }
                };
                xhttp.open("POST", "ajax.php?pagg=" + pagina + "&fecha=" + getCookie('fecha') + "&empleado=" + getCookie('empleado') + "&servicio=" + getCookie('servicio') + "&diaSemana=" + getCookie('diaSemana'), true);
                xhttp.send();
            }

        </script>

        <div class="clear"></div>
        <div id="separador"></div>
        <button id="buton_atras" onclick="show(1)">Назад</button>
        <!--<button id="buton_siguiente" onclick="comprobar_datos('contenedor1')">Напред</button>-->
    </div>
</div>


<div id="contenedor3" style="display: none">
    <div id="estados">
        <ul class="bookme-pro-steps">
            <li>Услуга</li>
            <li>Час</li>
            <li class="bookme-pro-steps-is-active">Данни</li>
            <li>Край</li>
        </ul>
    </div>
    <div id="content">
        <h4 id="rellena">Моля, попълнете всички задължителни полета, отбелязани със звездичка (*).</h4>

        <div id="left">
            <h5>Вашето име: *</h5>
            <input type="text" name="nombre" id="nombre" placeholder="Вашето име"><br>

            <h5>Вашият телефон: *</h5>
            <input type="text" name="telefono" id="telefono" placeholder="Вашият телефон"><br>

            <h5>Вашият имайл адрес: *</h5>
            <input type="email" name="correo" id="correo" placeholder="Вашият телефон"><br>

            <h5>Бележка:</h5>
            <input type="nota" name="nota" id="nota" placeholder="При желание опишете повредата"><br>
        </div>
        <div id="right2">
            <h5>Въведени данни:</h5>
            <span>Услуга: </span><span id="datos_elegidos_servicio"></span><br>
            <span>Механик: </span><span id="datos_elegidos_empleado"></span><br>
            <span>Дата: </span><span id="datos_elegidos_fecha"></span><br>
            <span>Час: </span><span id="datos_elegidos_hora"></span><br>
        </div>


        <script>
            function datos_cliente(pagina) {
                nombre = document.getElementById('nombre');
                console.log(nombre.value);

                correo = document.getElementById('correo');
                console.log(correo.value);

                nota = document.getElementById('nota');
                console.log(nota.value);

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);

                        //sacamos una alerta si se han metido datos que no concuerdan con el telefono
                        if (this.responseText.includes("Вашият телефонен номер")) {
                            //agregamos el texto al id
                            document.getElementById("alert-text").innerHTML = this.responseText;
                            //mostramos la alerta
                            document.getElementById("miAlerta").style.display = "block";
                        } else {
                            //si el cliente no existe añadimos el texto que viene del ajax y sacamos la cuarta pantalla
                            document.getElementById("datos_cliente").innerHTML = this.responseText;
                            show(4);
                            window.location="#pedir-cita";
                        }
                    }
                };
                xhttp.open("POST", "ajax.php?pagg=" + pagina + "&nombre=" + nombre.value + "&telefono=" + telefono.value + "&correo=" + correo.value + "&nota=" + nota.value + "&fecha=" + getCookie('fecha') + "&hora=" + getCookie('hora') + "&empleado=" + getCookie('empleado') + "&servicio=" + getCookie('servicio'), true);
                xhttp.send();
            }



            //validar datos del cliente y si las cookies han expirado
            function validar_datos() {
                var buscarNombre = document.getElementById('nombre').value;
                var queBuscar = new RegExp("^[A-zА-я]+$");

                if (!queBuscar.test(buscarNombre)) {
                    document.getElementById("alert-text4").innerHTML = "<h4>Въведеното име е невалидно! Може да използвате само букви на кирилица!</h4>";
                    document.getElementById("miAlerta2").style.display = "block";
                    //document.getElementById('nombre').focus();
                    //document.getElementById('nombre').style.backgroundColor = "#e4b3a7";
                    return false;
                }


                var buscarTelefono = document.getElementById('telefono').value;
                var queBuscar2 = new RegExp("^[0-9]+$");
                if (!queBuscar2.test(buscarTelefono)) {
                    document.getElementById("alert-text4").innerHTML = "<h4>Въведеният телефонен номер е невалиден! Може да използвате само цифри!</h4>";
                    document.getElementById("miAlerta2").style.display = "block";
                    //document.getElementById('telefono').focus();
                    //document.getElementById('telefono').style.backgroundColor = "#e4b3a7";
                    return false;
                }


                var buscarCorreo = document.getElementById('correo').value;
                var queBuscar3 = new RegExp("^\\S+@\\S+.\\S+$");
                if (!queBuscar3.test(buscarCorreo)) {
                    document.getElementById("alert-text4").innerHTML = "<h4>Въведеният имейл адрес е невалиден!</h4>";
                    document.getElementById("miAlerta2").style.display = "block";
                    //document.getElementById('correo').focus();
                    //document.getElementById('correo').style.backgroundColor = "#e4b3a7";
                    return false;
                }


                var cookie_fecha = ("; "+document.cookie).split("; fecha=").pop().split(";").shift();
                var cookie_hora = ("; "+document.cookie).split("; hora=").pop().split(";").shift();
                var cookie_diaSemana = ("; "+document.cookie).split("; diaSemana=").pop().split(";").shift();
                var cookie_empleado = ("; "+document.cookie).split("; empleado=").pop().split(";").shift();
                var cookie_servicio = ("; "+document.cookie).split("; servicio=").pop().split(";").shift();

                if(cookie_fecha == "" || cookie_hora == "" || cookie_diaSemana == "" || cookie_empleado == "" || cookie_servicio == ""){
                    document.getElementById("alert-text4").innerHTML = "<h4>Въведеният имейл адрес е невалиден!</h4>";
                    document.getElementById("miAlerta2").style.display = "block";
                    return false;
                }

                return true;
            }


            function alert_update() {
                document.getElementById("miAlerta").style.display = "none";

                var nombrecliente = document.getElementById('nombre').value;
                var telefonocliente = document.getElementById('telefono').value;
                var correoelectronico = document.getElementById('correo').value;

                console.log(nombrecliente);
                console.log(telefonocliente);
                console.log(correoelectronico);

                var xhttp2 = new XMLHttpRequest();
                xhttp2.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                    }
                };
                xhttp2.open("POST", "ajax2.php?motivo=actualizar_cliente_desde_resrv_cita" + "&nombre_cliente=" + nombrecliente + "&telefono_cliente=" + telefonocliente + "&correo_electronico=" + correoelectronico);
                xhttp2.send();
            }
        </script>

        <div class="clear"></div>
        <div id="separador"></div>
        <button id="buton_atras" onclick="show(2); ">Назад</button>
        <button id="buton_siguiente" onclick="show(3); validar_datos(); if(validar_datos() == true){datos_cliente(3)}">Резервирай!</button>
    </div>
</div>


<div id="miAlerta" class="alerta">
    <div class="alerta-content">

        <span id="alert-text"></span>
        <div id="alert-footer">
            <span id="alert_actualizar" onclick="alert_update()">Обнови</span>
            <span id="alert_cancelar" onclick="alert_cancel()">Отмени</span>
        </div>
    </div>

</div>


<div id="contenedor4" style="display: none">
    <div id="estados">
        <ul class="bookme-pro-steps">
            <li>Услуга</li>
            <li>Час</li>
            <li>Данни</li>
            <li class="bookme-pro-steps-is-active">Край</li>
        </ul>
    </div>
    <div id="content">
        <h2>Вашата резервация беше направена успешно!</h2>
        <h5>Данните на Вашата резервация:</h5>
        <div id="datos_cliente">
            <div id="reserva_hecha_servicio"></div>
            <div id="reserva_hecha_empleado"></div>
            <div id="reserva_hecha_fecha"></div>
            <div id="reserva_hecha_hora"></div>
            <div id="reserva_hecha_nombre"></div>
            <div id="reserva_hecha_telefono"></div>
            <div id="reserva_hecha_correo"></div>
        </div>
    </div>
    <!--
    <button id="buton" onclick="show(3)">Atras</button>
    -->
</div>


<script>
    //por defecto cargamos el primer contenedor
    show(1);

    function show(i) {
        if(i == 2){
            var datos_elegidos_servicio_c = ("; "+document.cookie).split("; servicio=").pop().split(";").shift();
            var datos_elegidos_empleado_c = ("; "+document.cookie).split("; empleado=").pop().split(";").shift();
            var datos_elegidos_fecha_c = ("; "+document.cookie).split("; fecha=").pop().split(";").shift();


            document.getElementById("datos_elegidos_servicio").innerHTML = datos_elegidos_servicio_c;
            document.getElementById("datos_elegidos_empleado").innerHTML = datos_elegidos_empleado_c;
            document.getElementById("datos_elegidos_fecha").innerHTML = datos_elegidos_fecha_c;

        }else if(i == 3){
            var datos_elegidos_hora_c = ("; "+document.cookie).split("; hora=").pop().split(";").shift();
            document.getElementById("datos_elegidos_hora").innerHTML = datos_elegidos_hora_c;
        }

        document.getElementById("contenedor1").style.display = "none";
        document.getElementById("contenedor2").style.display = "none";
        document.getElementById("contenedor3").style.display = "none";
        document.getElementById("contenedor4").style.display = "none";

        try {
            document.getElementById("contenedor" + i).style.display = "block";
        } catch (ignored) {
        }
    }
</script>
</body>
</html>