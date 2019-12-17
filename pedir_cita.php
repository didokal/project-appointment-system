<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "citas2", "3306");

if ($conexion->connect_error) {
    die("Error conexion bd: " . $conexion->connect_error);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles-calendar.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
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
            if (valor_a_guardar == "servicio") {
                asd = document.getElementById("servicio").value;
                document.cookie = "servicio" + "=" + asd;
            } else if (valor_a_guardar == "empleado") {
                asd2 = document.getElementById("empleado").value;
                document.cookie = "empleado" + "=" + asd2;

                //document.cookie = "fecha=2019-12-01";

            } else if (valor_a_guardar == "fecha") {
                //document.cookie = "fecha=2019-12-01";     por ahora lo dejamos asi pero cuando pongo el calendario aqui se guardara la fecha
            }
        }


        function guardar_valor_cookie_hora(valor) {
            document.cookie = "hora" + "=" + valor;
        }

        function alert_cancel() {
            document.getElementById("miAlerta").style.display = "none";
        }

    </script>
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
<hr style="color: #0056b2" />
<br><br><br>
<h1>Haz tu reserva ahora</h1>

<div id="contenedor1" style="display: block">
    <div id="estados">
        <ul class="bookme-pro-steps">
            <li class="bookme-pro-steps-is-active">Servicio</li>
            <li>Hora</li>
            <li>Datos</li>
            <li>Fin</li>
        </ul>
    </div>
    <div id="content">
        <h3>Porfavor rellena todos los campos:</h3>
        <div id="left">
            <div>
                <!-- GENERAMOS CATEGORIAS -->
                Categoria:<br><br>
                <select id="categoria" name="categoria_escogida" onchange="sacar_servicios_por_categoria(1)">
                    <option>Selecciona una categoria</option>

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
                Servicios:<br><br>
                <div id="servicios"></div>
                <select id="servicio" name="categoria_escogida"
                        onchange="sacar_empleados_por_servicio(1), guardar_valor_cookies('servicio')">
                    <option>Selecciona un servicio</option>
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
                <br><br>Empleado:<br><br>
                <div id="empleados"></div>
                <select id="empleado" onchange="guardar_valor_cookies('empleado')">
                    <option>Selecciona un empleado</option>
                </select>
            </div>
        </div>
        <div id="right">
            Selecciona una fecha:
<?php
echo "asd";


$file = file_get_contents('calendario_make_appointment.php');
$content = eval("?>$file");
echo $content;


?>
        </div>
    </div>
    <button id="buton" onclick="show(2); seleccionar_hora(2)">Sigueinte</button>
</div>


<div id="contenedor2" style="display: none">
    <div id="estados">
        <ul class="bookme-pro-steps">
            <li>Servicio</li>
            <li class="bookme-pro-steps-is-active">Hora</li>
            <li>Datos</li>
            <li>Fin</li>
        </ul>
    </div>
    <div id="content">
        <h3>Selleciona una hora</h3>

        <div id='scroll-content'></div>


        <script>
            function seleccionar_hora(pagina) {
                console.log(getCookie('fecha'));
                console.log(getCookie('empleado'));
                console.log(getCookie('servicio'));

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {


                        //console.log(this.responseText);
                        document.getElementById("scroll-content").innerHTML = this.responseText;


                        var fecha_partida = getCookie('fecha').split('-');




                        document.getElementById('diaSeleccionado').innerHTML = getCookie('diaSemana') + ", " + fecha_partida[2] + " de " +document.getElementById("month").innerText;





                    }
                };
                xhttp.open("POST", "ajax.php?pagg=" + pagina + "&fecha=" + getCookie('fecha') + "&empleado=" + getCookie('empleado') + "&servicio=" + getCookie('servicio') + "&diaSemana=" + getCookie('diaSemana'), true);
                xhttp.send();
            }

        </script>

    </div>
    <button id="buton" onclick="show(1)">Atras</button>
    <button id="buton" onclick="show(3)">Sigueinte</button>
</div>


<div id="contenedor3" style="display: none">
    <div id="estados">
        <ul class="bookme-pro-steps">
            <li>Servicio</li>
            <li>Hora</li>
            <li class="bookme-pro-steps-is-active">Datos</li>
            <li>Fin</li>
        </ul>
    </div>
    <div id="content">
        <h3 id="rellena">Porfavor rellena todos los campos:</h3>

        <div id="left">
            Nombre:<br><br>
            <input type="text" name="nombre" id="nombre"><br><br>

            Telefono:<br><br>
            <input type="number" name="telefono" id="telefono"><br><br>

            Correo electronico:<br><br>
            <input type="email" name="correo" id="correo"><br><br>

            Nota:<br><br>
            <input type="nota" name="nota" id="nota"><br><br>
        </div>
        <div id="right">
            Servicio seleccionado, empleado, dia, hora
        </div>
    </div>


    <script>
        //una vez que escogemos la categoria carga este script, el script mandra mediante ajax la categoria, se hace una select de todos los servicios bajo esa categoria y se muestran
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
                    if (this.responseText.includes("telefono")) {
                        //agregamos el texto al id
                        document.getElementById("alert-text").innerHTML = this.responseText;
                        //mostramos la alerta
                        document.getElementById("miAlerta").style.display = "block";
                    } else {
                        //si el cliente no existe añadimos el texto que viene del ajax y sacamos la cuarta pantalla
                        document.getElementById("datos_cliente").innerHTML = this.responseText;
                        show(4);
                    }


                }
            };
            xhttp.open("POST", "ajax.php?pagg=" + pagina + "&nombre=" + nombre.value + "&telefono=" + telefono.value + "&correo=" + correo.value + "&nota=" + nota.value + "&fecha=" + getCookie('fecha') + "&hora=" + getCookie('hora') + "&empleado=" + getCookie('empleado') + "&servicio=" + getCookie('servicio'), true);
            xhttp.send();
        }


        //validar datos del cliente
        function validar_datos() {
            var buscarNombre = document.getElementById('nombre').value;
            var queBuscar = new RegExp("^[A-z]+$");

            if (!queBuscar.test(buscarNombre)) {
                alert("Nombre introducido INCORRECTO");
                document.getElementById('nombre').focus();
                document.getElementById('nombre').style.backgroundColor = "#e4b3a7";
                return false;
            }


            var buscarTelefono = document.getElementById('telefono').value;
            var queBuscar2 = new RegExp("^[0-9]+$");
            if (!queBuscar2.test(buscarTelefono)) {
                alert("Telefono introducido INCORRECTO");
                document.getElementById('telefono').focus();
                document.getElementById('telefono').style.backgroundColor = "#e4b3a7";
                return false;
            }


            var buscarCorreo = document.getElementById('correo').value;
            var queBuscar3 = new RegExp("^\\S+@\\S+.\\S+$");
            if (!queBuscar3.test(buscarCorreo)) {
                alert("Correo introducido INCORRECTO");
                document.getElementById('correo').focus();
                document.getElementById('correo').style.backgroundColor = "#e4b3a7";
                return false;
            }

            return true;

        }
    </script>


    <button id="buton" onclick="show(2); disminuir_pag(2)">Atras</button>
    <button id="buton" onclick="show(3); validar_datos(); if(validar_datos() == true){datos_cliente(3)}">Sigueinte
    </button>
</div>


<div id="miAlerta" class="alerta">
    <div class="alerta-content">

        <span id="alert-text"></span>
        <div id="alert-footer">
            <span id="alert_actualizar" onclick="alert_update()">Actualizar</span>
            <span id="alert_cancelar" onclick="alert_cancel()">Cancelar</span>
        </div>
    </div>

</div>


<div id="contenedor4" style="display: none">
    <div id="estados">
        <ul class="bookme-pro-steps">
            <li>Servicio</li>
            <li>Hora</li>
            <li>Datos</li>
            <li class="bookme-pro-steps-is-active">Fin</li>
        </ul>
    </div>
    <div id="content">
        <h3>La reserva fue realizada con exito!</h3>
        <div id="datos_cliente"></div>
    </div>
    <!--
    <button id="buton" onclick="show(3)">Atras</button>
    -->
</div>


<script>

    show(1);

    /*
        document.getElementById("alert_actualizar").onclick = function() {
            var modal = document.getElementById("miAlerta");
            modal.style.display = "none";
            alert("aki");
        }
    */


    function show(i) {
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