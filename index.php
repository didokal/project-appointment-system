<?php
session_start();
?>
<html>
<head>
    <title>Автосервиз Авто Павлов 94 ЕООД</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="http://fonts.googleapis.com/css?family=Work+Sans&amp;subset=latin,cyrillic" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/styles-index.css">
    <script src="https://kit.fontawesome.com/f2714199ff.js" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" media="screen and (min-width: 641px) and (max-width: 1400px)" href="css/style-tablet.css">  <!--tablet-->
    <link rel="stylesheet" media="screen and (min-width: 641px) and (max-width: 1200px)" href="css/style-tablet.css">  <!--tablet-->
    <link rel="stylesheet" media="screen and (min-width: 100px) and (max-width: 640px)" href="css/style-movil.css">  <!--movil-->
    
    <script>
        function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display === 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
    </script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <!--<script src="https://www.google.com/recaptcha/api.js?render=6LcKgPsUAAAAANG3y0j28WpnFIvxjAMKFubVEwHd"></script>-->
</head>
<body>
    <span id="logo_and_but_menu_respons">
        <img height="65" src="design/logo.png">
        <img height=30" src="design/menu_button.png" id="butt_menu" onclick="toggle_visibility('menuu_movil');"/>
    </span>
  
<nav id="menuu">
    <ul>
        <li><a href="index.php">Начало</a></li>
        <li><a onclick="menuScrollSmooth('historia');">История</a></li>
        <li><a onclick="menuScrollSmooth('servicioss');">Услуги</a></li>
        <li style="position: relative; top: 0px;"><img src="design/logo.png" height="85px";></li>
        <li><a onclick="menuScrollSmooth('pedir-cita');">Запази час</a></li>
        <li><a onclick="menuScrollSmooth('contactoss');">Контакти</a></li>
        <?php

        if (isset($_SESSION["permiso"]) && $_SESSION["permiso"] == "cliente" && isset($_SESSION["email"]) && $_SESSION["email"] != "" && isset($_SESSION["pass"]) && $_SESSION["pass"] != "") {
            echo '<li><a href="cliente-cuenta.php" class="perfil">Моят профил</a></li>';
        }else{
            echo '<li><a class="entrar">Вход</a></li>';
        }
        ?>
</nav>
    
<nav id="menuu_movil">
    <ul>
        <li><a class="btn_close" href="index.php">Начало</a></li>
        <li><a class="btn_close" onclick="menuScrollSmooth('historia');">История</a></li>
        <li><a class="btn_close" onclick="menuScrollSmooth('servicioss');">Услуги</a></li>
        <li><a class="btn_close" onclick="menuScrollSmooth('pedir-cita');">Запази час</a></li>
        <li><a class="btn_close" onclick="menuScrollSmooth('contactoss');">Контакти</a></li>
        <?php

        if (isset($_SESSION["permiso"]) && $_SESSION["permiso"] == "cliente" && isset($_SESSION["email"]) && $_SESSION["email"] != "" && isset($_SESSION["pass"]) && $_SESSION["pass"] != "") {
            echo '<li><a href="cliente-cuenta.php" class="btn_close perfil">Моят профил</a></li>';
        }else{
            echo '<li><a class="btn_close entrar">Вход</a></li>';
        }
        ?>
</nav>
    
    
    <script>
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
    
<nav class="buton_pedir_cita">
    <h3 style="color:white; padding-bottom:25px">Искате ли Вашето превозно средтво да се обслужва от професионалисти?</h3>
    <a onclick="menuScrollSmooth('pedir-cita');"><span id="buton_pedir_cita_interior">ЗАПАЗИ ЧАС!</span></a>
</nav>

<!-- Slide Show -->
<section>
    <img class="home_page_img" src="design\2-2.jpg">
</section>

<!-- Band Description -->
<div id="historia">
    <h2 style="text-align: center; margin-bottom: 30px">История</h2>
    <p style="text-align: justify">Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de
        texto.</p>
    <p style="text-align: justify">Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto.
        Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del
        T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que
        logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de
        relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la
        creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software
        de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.</p>
</div>
<div id="espacio1"></div>

<div id="servicioss">
    <h2 style="text-align: center; margin-bottom: 30px">Услуги</h2>
    <div class="service">
        <img src="design/service_1.jpg" style="display: block; margin-left: auto; margin-right: auto; width: 22%; max-width: 80px;">
        <h3>Преглед</h3>
        Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el
        texto de relleno estándar de las industrias desde el año 1500, cuando un impresor
    </div>

    <span class="service">
        <img src="design/service_4.jpg" style="display: block; margin-left: auto; margin-right: auto; width: 22%; max-width: 80px;">
        <h3>Ремот</h3>
        Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor
    </span>

    <span class="service">
        <img src="design/service_2.jpg" style="display: block; margin-left: auto; margin-right: auto; width: 22%; max-width: 80px;">
        <h3>Диагностика</h3>
        Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor
    </span>


    <span class="service">
        <img src="design/service_3.jpg" style="display: block; margin-left: auto; margin-right: auto; width: 22%; max-width: 80px;">
        <h3>Стенд</h3>
        Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor
    </span>

</div>
<div id="espacio2"></div>

<div id="pedir-cita">
    <h2 style="text-align: center; margin-bottom: 40px">Запази час</h2>
    <?php
    require_once('pedir_cita.php');
    ?>
</div>
<div id="espacio3"></div>

<div id="contactoss">
    <span class="circulo">
        <img src="https://colorlib.com/etc/cf/ContactFrom_v20/images/icons/symbol-01.png">
    </span>
    <div>
        <h2 class="titulo-seccion" style="margin-bottom: 10px">Пишете ни</h2>
        <form id="formulario_correo" action="enviar_correo.php" method="post">
        <div class="input1_div">
            <input id="mensaje_nombre" class="input1_input" type="text" name="nombre" placeholder="Име">
        </div>

        <div class="input2_div">
            <input id="mensaje_correo" class="input2_input" type="text" name="correo" placeholder="Имейл адрес">
        </div>

        <div class="input3_div">
            <textarea id="mensaje_mensaje" class="input3_input" type="text" name="mensaje" placeholder="Съобщение"></textarea>
        </div>

        <div class="input4_div">
            <button class="input4_input" name="enviar_formulario_contacto">Изпрати</button>
        </div>
        </form>
    </div>
</div>
    <!--<script>
        $('#formulario_correo').submit(function(event) {
            event.preventDefault();
            var email = $('#mensaje_correo').val();

            grecaptcha.ready(function() {
                grecaptcha.execute('6LcKgPsUAAAAANG3y0j28WpnFIvxjAMKFubVEwHd', {action: 'subscribe_newsletter'}).then(function(token) {
                    $('#formulario_correo').prepend('<input type="hidden" name="token" value="' + token + '">');
                    $('#formulario_correo').prepend('<input type="hidden" name="action" value="subscribe_newsletter">');
                    $('#formulario_correo').unbind('submit').submit();
                });;
            });
        });
    </script>-->
<div id="footer">
    <div style="padding-top: 70px">
        <h2>За връзка с нас</h2>
        <div>Бул. Източен 22, гр. Пловдив - 087 6363 610</div>
    </div>
</div>
<div id="login">

    <div id="login_content">
        <span id="buton_cerrar"><i class="fas fa-times-circle"></i></span>
        <div>
            <h3>ВХОД КЪМ ПРОФИЛА</h3>
        </div>
        <form id="loginForm" method="post" onsubmit="login(this); return false;">
            <input type="hidden" name="function" value="loginCliente"/>
            <span id="alerta-error"></span>
            <br>
            <div style="padding-bottom:0px">Имейл:</div>
            <input type="text" id="correo1" name="correo1" class="input1">


            <div style="float:left; padding-top:17px">Парола:</div>
            <input type="password" id="password" name="pass" class="input2">

            <br>
            <div class="table_buttons">
                <div style="" id="buton_registrar" class="btn">Регистрация</div>
                <div style="width: 128px;" id="buton_resetear" class="btn">Зануляване</div>
                <input type="submit" style="width: 81px; float:right" value="Влез" id="buton_entrar" class="btn">
            </div>
        </form>
    </div>
</div>

<div id="registro">
    <div id="registro_content">
        <span id="buton_cerrar2"><i class="fas fa-times-circle"></i></span>
        <div>
            <h3>РЕГИСТРАЦИЯ</h3>
        </div>
        <div style="padding-top:0px">Име:</div>
        <input type="text" id="usuario_nombre" name="nombre" class="input1">

        <div style="padding-top:17px">Имейл адрес:</div>
        <input type="text" id="usuario_correo" name="correo2" class="input1">

        <div style="padding-top:17px">Телефонен:</div>
        <input type="text" id="usuario_telefono" name="telefono" class="input1">

        <div style="padding-top:17px">Парола:</div>
        <input type="password" id="password_registro" name="pass" class="input2">

        <br>
        <div class="table_buttons">
            <div style="width: 128px;" id="buton_resetear2" class="btn">Зануляване</div>
            <div style="float:right" id="buton_crear" class="btn">Създай профил</div>
        </div>
    </div>
</div>
<script>
    document.getElementsByClassName("entrar")[0].addEventListener("click", function () {
        document.getElementById("login").style.display = "block";
    });
    
    document.getElementsByClassName("entrar")[1].addEventListener("click", function () {
        document.getElementById("login").style.display = "block";
    });


    document.getElementById("buton_resetear").addEventListener("click", function () {
        document.getElementsByName("correo1")[0].value = "";
        document.getElementById("password").value = "";

    });

    document.getElementById("buton_resetear2").addEventListener("click", function () {
        document.getElementById("usuario_nombre").value = "";
        document.getElementById("usuario_correo").value = "";
        document.getElementById("password_registro").value = "";
        document.getElementById("usuario_telefono").value = "";
    });

    document.getElementById("buton_cerrar").addEventListener("click", function () {
        document.getElementById("login").style.display = "none";
        document.getElementById("alerta-error").innerHTML = "";
    });

    document.getElementById("buton_cerrar2").addEventListener("click", function () {
        document.getElementById("registro").style.display = "none";
        document.getElementById("login").style.display = "none";
    });

    document.getElementById("buton_registrar").addEventListener("click", function () {
        document.getElementById("login").style.display = "none";
        document.getElementById("registro").style.display = "block";
    });

    document.getElementById("buton_crear").addEventListener("click", function () {

        var nombre2 = document.getElementById("usuario_nombre").value;
        var password2 = document.getElementById("password_registro").value;
        var correo2 = document.getElementById("usuario_correo").value;
        var telefono2 = document.getElementById("usuario_telefono").value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                alert(this.responseText);
            }
        };
        xhttp.open("POST", "ajax3.php?motivo=crear_cuenta" + "&nombre=" + nombre2 + "&password=" + password2 + "&correo="+ correo2 + "&telefono=" + telefono2);
        xhttp.send();
    });

    function login(formIn) {
        var correo_login_entrar = document.getElementById("correo1").value;
        var contrasena_login_entrar = document.getElementById("password").value;

        if(correo_login_entrar == "" || contrasena_login_entrar == "") {
            document.getElementById("alerta-error").innerHTML = "Грешен имейл адрес или парола!";
        }else{
            var form = new FormData(formIn);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "login-config.php");

            xhr.addEventListener('loadend', () => {
                if (xhr.responseText !== "1") {
                    console.log(xhr.responseText);
                    document.getElementById("alerta-error").innerHTML = "Грешен имейл адрес или парола!";

                } else {
                    window.location = "./cliente-cuenta.php";
                }
            }, false);

            xhr.send(form);
        }
    }

    function menuScrollSmooth(id){
        document.getElementById(id).scrollIntoView({ behavior: "smooth" });
    }
</script>
</body>
</html>