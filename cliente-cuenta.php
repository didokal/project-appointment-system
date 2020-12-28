<?php
session_start();

require_once("config.php");
$conexion = new mysqli(conexion_host, conexion_user, conexion_pass, conexion_bbdd, conexion_port);

if ($conexion->connect_error) {
    die("Error conexion bd: " . $conexion->connect_error);
}

if (!isset($_SESSION["permiso"]) || ($_SESSION["permiso"] != null && $_SESSION["permiso"] != "cliente")) {
    die("<h1 style='color:red'>No tiene acceso a este area</h1>");
}
if (isset($_GET["exit"])) {
    salir();
}

function salir()
{
    session_start();
    unset($_SESSION["permiso"]);
    unset($_SESSION["user"]);
    unset($_SESSION["pass"]);
    session_unset();
    session_destroy();
    header('Location: index.php');
}
?>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">-->
    <link href="http://fonts.googleapis.com/css?family=Work+Sans&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/style-cuenta-cliente.css">
    <link rel="stylesheet" media="screen and (min-width: 641px) and (max-width: 1200px)" href="css/style-tablet.css">  <!--tablet-->
    <link rel="stylesheet" media="screen and (min-width: 100px) and (max-width: 640px)" href="css/style-movil.css">  <!--movil-->
</head>
<body>
<span id="logo_and_but_menu_respons" style="filter: invert();">
    <img height="85" src="design/logo.png">
    <img height=30" src="design/menu_button.png" id="butt_menu" onclick="toggle_visibility('menuu_movil');"/>
</span>
    
    
<nav id="menuu" style="filter: invert()">
    <ul style="margin-left:38px;">
        <li><a href="index.php">Начало</a></li>
        <li><a href="index.php#historia">История</a></li>
        <li><a href="index.php#servicioss">Услуги</a></li>
        <li style="position: relative; top: 0px;"><img src="design/logo.png" height="85px"></li>
        <li><a href="index.php#pedir-cita">Запизи час</a></li>
        <li><a href="index.php#contactoss">Контакти</a></li>
        <li><a href="?exit" id="salir">Изход</a></li>
    </ul>
</nav>
    
<nav id="menuu_movil">
    <ul>
        <li><a class="btn_close" href="index.php">Начало</a></li>
        <li><a class="btn_close" href="index.php#historia">История</a></li>
        <li><a class="btn_close" href="index.php#servicioss">Услуги</a></li>
        <li><a class="btn_close" href="index.php#pedir-cita">Запизи час</a></li>
        <li><a class="btn_close" href="index.php#contactoss">Контакти</a></li>
        <li><a class="btn_close" href="?exit" id="salir">Изход</a></li>
</nav>
    
    
    <script>
        function toggle_visibility(id) {
            var e = document.getElementById(id);
            if(e.style.display == 'block')
                e.style.display = 'none';
            else
                e.style.display = 'block';
        }

        function comprobar_iva(){
            var iva = document.getElementsByName('iva')[0].value;
            if(iva == 0){
                document.getElementById("anular_iva").checked = true
            }
        }
        
        var btn_closee = document.getElementsByClassName("btn_close");
        var close_window = document.getElementById("menuu_movil");
        
        btn_closee[0].addEventListener("click", function () {
            close_window.style.display = "none";  
        }); 
        
        btn_closee[1].addEventListener("click", function () {
            close_window.style.display = "none";  
        }); 
        
        btn_closee[2].addEventListener("click", function () {
            close_window.style.display = "none";  
        }); 
        
        btn_closee[3].addEventListener("click", function () {
            close_window.style.display = "none";  
        }); 
        
        btn_closee[4].addEventListener("click", function () {
            close_window.style.display = "none";  
        }); 
        
        btn_closee[5].addEventListener("click", function () {
            close_window.style.display = "none";  
        }); 
        
        

        </script>
    
<div class="clear"></div>
<div style="width: 100%; max-width: 980px; margin: 0 auto; padding: 30px;">
    <div id="content" style="height: 100%; margin-top: 230px;">
        <div id="panel-opciones" style="height: 170px; width: 100%; float: left;">
            <h2>Опции</h2>
            <div id="cambiar-contraseña">Промяна на парола</div>
            <div style="margin-top:10px" id="mostrar-citas">Покажи резервации</div>
        </div>
        <div id="panel-contenido-contrasena" style="width:100%; float:left">
            <h2>Промени своята парола</h2>

            <div style="font-size:13px; padding-bottom:3px">Актуална парола:</div>
            <input type="text" name="actual_pass" required style="width:170px; border: solid black 1px"><br>

            <div style="font-size:13px; padding-bottom:3px">Нова парола:</div>
            <input type="text" name="new_pass" required style="width:170px; border: solid black 1px"><br>

            <div style="font-size:13px; padding-bottom:3px">Повтори новата парола:</div>
            <input type="text" name="new_pass2" required style="width:170px; border: solid black 1px"><br>

            <button id="but_cambiar_contrasena" class="btn"
                    style="width: 170px; height: 36px;">Промени
            </button>
            <br><br>
            <div id="mensaje"></div>
        </div>

        <script>
            document.getElementById("but_cambiar_contrasena").addEventListener("click", function () {

                var pass_actual = document.getElementsByName("actual_pass")[0].value;
                var pass_nuevo1 = document.getElementsByName("new_pass")[0].value;
                var pass_nuevo2 = document.getElementsByName("new_pass2")[0].value;

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        document.getElementById("mensaje").innerHTML = "";
                        document.getElementById("mensaje").innerHTML = this.responseText;
                    }
                };
                xhttp.open("POST", "ajax3.php?motivo=cambiar_contraseña" + "&pass_actual=" + pass_actual + "&pass_nuevo1=" + pass_nuevo1 + "&pass_nuevo2=" + pass_nuevo2);
                xhttp.send();
            });



        </script>


        <div id="panel-contenido-citas" style="width:100%; float:left">
            <h2>История</h2>
            <?php
            $email = $_SESSION["email"];
            $resultado = mysqli_query($conexion, "SELECT * from citas INNER JOIN clientes ON citas.idCliente = clientes.idCliente WHERE correoCliente = '$email'");
            echo "<table id='tabla_citas_cliente_con_cuenta'>";
            echo "<tr><th>Дата</th><th>Час</th><th>Механик</th><th>Услуга</th><th>Покажи фактура</th></tr>";
            while ($row = mysqli_fetch_array($resultado)) {
                echo "<tr>";
                echo "<td>$row[1]</td>";
                $var1 = substr($row[2], 0, 5);
                echo "<td>$var1</td>";
                echo "<td>$row[4]</td>";
                echo "<td>$row[5]</td>";
                echo "<td><button type='button' id='buton_tabla' onclick='visualizar_hoja(\"$row[0]\")'><i class='button_imagen2'></td>";
                echo "</tr>";
            }
            echo "</table>";
            ?>
        </div>
    </div>

</div>


<div class="clear"></div>

<div id="footer">
    <div style="padding-top: 70px">
        <h2>За връзка с нас</h2>
        <div>Бул. Източен 22, гр. Пловдив - 087 6363 610</div>
    </div>
</div>

<script>
    document.getElementById("cambiar-contraseña").addEventListener("click", function () {
        document.getElementById("panel-contenido-contrasena").style.display = "block";
        document.getElementById("panel-contenido-citas").style.display = "none";
    });

    document.getElementById("mostrar-citas").addEventListener("click", function () {
        document.getElementById("panel-contenido-contrasena").style.display = "none";
        document.getElementById("panel-contenido-citas").style.display = "block";
    });


    //login

    document.getElementById("salir").addEventListener("click", function () {
        document.getElementById("login").style.display = "block";
    });

    function showDialog() {
        document.body.style.position = "fixed";
        document.getElementById("dialogsViewer").style.display = "table";
    }

    function hideDialog() {
        document.body.style.position = "unset";
        document.getElementById("dialogsViewer").style.display = "none";
    }

    function login(formIn) {
        var form = new FormData(formIn);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "./app/user_manager.php");

        xhr.addEventListener('loadend', () => {
            if (xhr.responseText !== "1") {
                console.log(xhr.responseText);
                alert("Incorrecto");
            } else {
                window.location = "./index.php";
            }
        }, false);

        xhr.send(form);
    }

    //hoja de informes
    function visualizar_hoja(cita) {
        id_cita = cita;
        console.log("HOJA QUE VAS A EDITAR >>> " + id_cita);

        document.getElementsByName("num_factura")[0].value = id_cita;


        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);

                if (this.responseText.includes("Hoja de trabajo no encontrada")) {
                    document.getElementById("miSlidePanel3").style.display = "block";
                } else {


                    document.getElementById("alert-text3").innerHTML = "";
                    document.getElementById("alert-text3").innerHTML = this.responseText;
                    document.getElementById("miSlidePanel3").style.display = "block";
                    comprobar_iva();
                }



                //document.getElementById("miSlidePanel3").style.display = "block";
            }
        };
        xhttp.open("POST", "ajax2.php?motivo=visualizar_hoja_cliente" + "&cita=" + cita);
        xhttp.send();



    }


</script>

<div id="miSlidePanel3" class="slidePanel3">
    <div class="slidePanel-content3">
        <div id="titulo">
            <h2>Фактура</h2>
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
                        <span style="display: inline-block; width: 243px;">
                            Nº фактура:
                        </span>

                    <span style="display: inline-block; width: 146px">
                            <input disabled type="text" name="num_factura" disabled
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                </div>

                <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 243px;">
                            Дата на постъпление на МПС:
                        </span>

                    <span style="display: inline-block; width: 146px">
                            <input disabled type="date" name="fecha_entrada"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                </div>

                <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 243px;">
                            Дата на издаване на МПС:
                        </span>

                    <span style="display: inline-block; width: 146px">
                            <input disabled type="date" name="fecha_salida"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <h3>Лични данни на собственика и/или представител</h3>
            <div style="width:420px; height: 111px; float:left">
                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 58px;">Имена: </span>

                        <span style="display: inline-block; width: 322px">
                            <input disabled type="text" name="nombre_representante"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 58px;">Фирма:</span>

                        <span style="display: inline-block; width: 322px">
                            <input disabled type="text" name="empresa"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 58px;">Улица:</span>

                        <span style="display: inline-block; width: 322px">
                            <input disabled type="text" name="diraccion"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>
            </div>


            <div style="width:470px; height: 111px; float:right; ">
                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 37px;">ЕГН:</span>

                        <span style="display: inline-block; width: 427px">
                            <input disabled type="text" name="cif_nif"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:210px;  float:left;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 75px;">Телефон:</span>

                        <span style="display: inline-block; width: 115px">
                            <input disabled type="number" name="telefono"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:233px;  float:right">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 97px;">М. телефон:</span>

                        <span style="display: inline-block; width: 130px">
                            <input disabled type="number" name="movil"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:210px;  float:left;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 30px;">ПК:</span>

                        <span style="display: inline-block; width: 160px">
                            <input disabled type="number" name="cp"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:232px;  float:right;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 44px;">Град:</span>

                        <span style="display: inline-block; width: 181px">
                            <input disabled type="text" name="poblacion"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <br><br>
            <h3>Данни на МПС</h3>
            <div style="width:420px; height: 68px; float:left">
                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 125px;">Марка и модел: </span>

                        <span style="display: inline-block; width: 255px">
                            <input disabled type="text" name="marca_modelo"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div>
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 40px;">ВИН: </span>

                        <span style="display: inline-block; width: 340px">
                            <input disabled type="text" name="vin"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

            </div>
            <div style="width:470px; height: 68px; float:right">
                <div style="width:210px;  float:left;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 75px;">Р. номер:</span>

                        <span style="display: inline-block; width: 117px">
                            <input disabled type="text" name="matricula"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:232px;  float:right;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 30px;">Км:</span>

                        <span style="display: inline-block; width: 196px">
                            <input disabled type="number" name="km"
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    </div>
                </div>

                <div style="width:450px;  float:left;">
                    <div style="margin-bottom:20px">
                        <span style="display: inline-block; width: 60px;">Гориво:</span>
                        <span style="display: inline-block; width: 370px">
                            <input disabled type="radio" name="combustible" value="0"
                                   style="display: inline-block; width:13px;"/>0
                            <input disabled type="radio" name="combustible" value="1/4"
                                   style="display: inline-block; width:13px; margin-left:30px"/>1/4
                            <input disabled type="radio" name="combustible" value="1/2"
                                   style="display: inline-block; width:13px; margin-left:30px"/>1/2
                            <input disabled type="radio" name="combustible" value="3/4"
                                   style="display: inline-block; width:13px; margin-left:30px"/>3/4
                            <input disabled type="radio" name="combustible" value="4/4"
                                   style="display: inline-block; width:13px; margin-left:30px"/>4/4
                        </span>
                    </div>
                </div>

            </div>

            <div class="clear"></div>
            <br>
            <h3>Услуги/стоки</h3>
            <span id="trabajos">
                <table style="width:100%" id="tabla_trabajos">
                    <tr>
                        <td style='padding: 0;'>Описание</td>
                        <td style='padding: 0; width: 13%;'>Количество</td>
                        <td style='padding: 0; width: 13%;'>Цена бройка</td>
                        <td style='padding: 0; width: 16%;'>Обща стойност</td>
                    </tr>
                    <tr>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="text" name="trabajo_realizar1" class="trabajo_a_realizar" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="number" name="trabajo_realizar_cantidad1" class="trabajo_a_realizar_cantidad" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="number" name="trabajo_realizar_precio_u1" class="trabajo_realizar_precio_u" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="number" name="trabajo_realizar_total1" class="trabajo_realizar_total" onchange="" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                    </tr>
                    <tr>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="text" name="trabajo_realizar2" class="trabajo_a_realizar" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="number" name="trabajo_realizar_cantidad2" class="trabajo_a_realizar_cantidad" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="number" name="trabajo_realizar_precio_u2" class="trabajo_realizar_precio_u" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="number" name="trabajo_realizar_total2" class="trabajo_realizar_total" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                    </tr>
                    <tr>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="text" name="trabajo_realizar3" class="trabajo_a_realizar" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="number" name="trabajo_realizar_cantidad3" class="trabajo_a_realizar_cantidad" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="number" name="trabajo_realizar_precio_u3" class="trabajo_realizar_precio_u" onchange="calcularTotalServicioPiezas(this.name); calcularTotal(); calcularIva(); calcularTotalFactura()" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                        <td style='padding: 7px 10px 7px 0;'><input disabled type="number" name="trabajo_realizar_total3" class="trabajo_realizar_total" style="background-color: #eeeeee; border:1px solid lightgrey"></td>
                    </tr>
                </table>
            </span>

            <br>
            <div style="width:420px; height: 215px; float:left">
                <h3>Бележки</h3>
                <textarea disabled name="otras_observaciones"
                          style="height: 153px; width: 391px;"></textarea>
            </div>

            <div style="width:254px; height: 215px; float:right">

                <h3>Разходи</h3>
                <div style="margin-bottom:20px; float:right">
                    <span style="display: inline-block; width: 168px;">Общо:</span>
                    <span style="display: inline-block; width: 80px">
                            <input type="number" disabled name="total_sin_iva" style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                </div>

                <div style="margin-bottom:20px; float:right">
                    <span style="display: inline-block; width: 168px;">ДДС 20%:</span>
                    <span style="display: inline-block; width: 80px">
                            <input type="number" disabled name="iva" disabled
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                    <br>
                    <span style="display: inline-block;width: 13px;margin-top: 12px;margin-right: 14px;margin-left: -4px;"><input type="checkbox" disabled id="anular_iva" onclick="quitar_iva()"></span>
                    <span style="display: inline-block;width: 222px;font-size: 12px;text-align: justify;">Занули ДДС на основание чл.113, ал.9 или друго основание</span>
                </div>
                <br><br>

                <div style="margin-bottom:20px; float:right">
                    <span style="display: inline-block; width: 168px; font-weight: bold">Сума за плащане:</span>
                    <span style="display: inline-block; width: 80px">
                            <input type="number" disabled name="total_factura" disabled
                                   style="background-color: #eeeeee; border:1px solid lightgrey">
                        </span>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div id="alert-footer" style="margin-top: 40px;">
            <span id="alert_cancelar3">Затвори</span>
        </div>
    </div>

    <script>
        document.getElementById("alert_cancelar3").addEventListener("click", function () {
            document.getElementById("miSlidePanel3").style.display = "none";
            location.reload();
        });
    </script>
</body>
</html>
