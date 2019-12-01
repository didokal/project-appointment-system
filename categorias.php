<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div>
    <h1>Menu</h1>
    <ul id="temporal_ul">
        <li><a href="admin-panel.php">Admin panel</a></li>
        <li><a href="pedir_cita.php">Pedir cita</a></li>
        <li><a href="empleados.php">Empleados</a></li>
        <li><a href="categorias.php">Categorias</a></li>
        <li><a href="servicios.php">Servicios</a></li>
    </ul>
    <hr style="color: #0056b2" />
    <br><br><br>


    <h1>Categorias</h1>
    <table id="tabla">
        <tr>
            <th>Nombre</th>
            <th></th>
        </tr>
        <?php
        session_start();
        $conexion = new mysqli("localhost", "root", "", "citas2", "3306");

        if ($conexion->connect_error) {
            die("Error conexion bd: " . $conexion->connect_error);
        }


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
        <button id="buton_borrar"><span class="icon_borrar">  Eliminar categoria</span></button>
        <button id="buton_anadir"><span class="icon_anadir">  Añadir categoria</span></button>

    </div>




    <div id="miAlerta" class="alerta">
        <div class="alerta-content2">
            <div id="titulo">
                <h2>Añadir categoria</h2>
            </div>
            <span id="alert-text">
                Nombre categoria:<br><br>
                <input type="text" id="categoria">
            </span>
            <div id="alert-footer">
                <span id="alert_actualizar2">Añadir</span>
                <span id="alert_cancelar">Cancelar</span>
            </div>
        </div>
    </div>



    <script>
        var datos = "";
        var categoria_para_borrar = [];

        //BORRAR CATEGORIA
        document.getElementById("buton_borrar").addEventListener("click", function(){
            let checked = Array.prototype.slice.call(document.querySelectorAll("input[type='checkbox']:checked"));
            checked.forEach(function(cb){
                console.log("Datos antes de enviar AJAX " + cb.value);
                categoria_para_borrar.push("cat"+cb.value);
                datos += cb.value + ",";
            });

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    console.log(categoria_para_borrar);

                    //eliminamos los divs con los empleados seleccionados
                    for (var x = 0; x<categoria_para_borrar.length; x++){
                        document.getElementById(categoria_para_borrar[x]).innerHTML='';
                    }
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=eliminar_categoria" + "&categorias=" + datos);
            xhttp.send();
        });


        //AÑADIR CATEGORIA
        document.getElementById("buton_anadir").addEventListener("click", function(){
            document.getElementById("miAlerta").style.display = "block";


            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    console.log(categoria_para_borrar);

                    //eliminamos los divs con los empleados seleccionados
                    for (var x = 0; x<categoria_para_borrar.length; x++){
                        document.getElementById(categoria_para_borrar[x]).innerHTML='';
                    }
                }
            };
            xhttp.open("POST", "ajax2.php?motivo=eliminar_categoria" + "&categorias=" + datos);
            xhttp.send();
        });





        document.getElementById("alert_cancelar").addEventListener("click", function(){
            document.getElementById("miAlerta").style.display = "none";
        });

        document.getElementById("alert_actualizar2").addEventListener("click", function(){

           var categoria = document.getElementById("categoria").value;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    console.log(categoria_para_borrar);
                    document.getElementById("miAlerta").style.display = "none";
                    document.getElementById("tabla").innerHTML='';

                    var xhttp2 = new XMLHttpRequest();
                    xhttp2.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            console.log(this.responseText);

                            document.getElementById("miAlerta").style.display = "none";
                            document.getElementById("tabla").innerHTML=this.responseText;

                        }
                    };
                    xhttp2.open("POST", "ajax2.php?motivo=mostrar_categorias");
                    xhttp2.send();



                }
            };
            xhttp.open("POST", "ajax2.php?motivo=anadir_categoria" + "&categoria=" + categoria);
            xhttp.send();





        });







    </script>

</div>
</body>
