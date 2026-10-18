<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// panel.php — Página protegida
session_start();

// Cerrar sesión: panel.php?salir=1
if (isset($_GET["salir"])) {
    $_SESSION = [];      // vaciamos los datos
    session_destroy();   // destruimos la sesión en el servidor
    header("Location: login.php");
    exit;
}

// Si no hay sesión, no puede estar acá
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

// Cada vez que se carga la página, sumamos una visita
$_SESSION["visitas"]++;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
    <style>
 

    .cerrar-sesion:hover  { 
        color: rgb(196, 21, 21) !important;
    } 

    .cerrar-sesion {
        background-color: rgb(255, 255, 255) !important ;
    }

    h6 {
        text-align: center;
    }

</style>

<body>

    <p>
        <a href="panel.php">Recargar</a> |
        <a href="panel.php?salir=1">Cerrar sesión</a>
    </p>

    <div class="container-fluid">
        <header class="row bg-primary text-white p-3">
            <div class="col">
                <h1 class="h3">Hospital de clínicas</h1>
            </div>
        </header>

        <div class="row">
            <aside class="col-md-3 bg-dark text-white p-3 min-vh-100">
             <h2 class="h5 mb-3">Menú</h2>
                <div class="text-white text-center mb-3">
                    <h6>Administrador</h6>
                    <?= htmlspecialchars($_SESSION["usuario"]) ?>
                </div>

                
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action"> Pacientes</a>

                    <a href="#" class="list-group-item list-group-item-action active"> Médicos</a>

                    <a href="#" class="list-group-item list-group-item-action"> Turnos</a>

                    <a href="#" class="list-group-item list-group-item-action cerrar-sesion "> Cerrar sesión</a>
                </div>

            </aside>



            <main class="col-md-9 p-4">
                <h2 class="h4 mb-4">Panel de administración</h2>
                  <section class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="h5">Pacientes registrados</h3>

                                <p class="display-6">7500</p>
                            </div>
                       </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="h5">Turnos del día</h3>

                                <p class="display-6">5560</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">

                                <h3 class="h5">Médicos disponibles</h3>

                                <p class="display-6"> 2800 </p>
                            </div>
                        </div>
                    </div>
                </section>


            <div class="container">
                <section class="card">  
                    <div class="card-header">
                        <h4> Historia clínica de la paciente <b> AHYLEN FERNÁNDEZ </b></h4>
                   </div> 

                    <div class="card-body">
                        <p><b>Número de H.C:</b> <u>12345678.</u></p>
                        <p><b>Cédula:</b> <u>1.234.567-8.</u></p>
                        <p><b>Apellido:</b> <u>Fernández.</u></p>
                        <p><b>Nombre:</b> <u>Ahylen.</u></p>
                        <p><b>Edad:</b> <u>14 años.</u></p>
                        <p><b>Ocupación:</b> <u>Front-End Company.</u></p>
                        <p><b>Fecha de nacimiento:</b> <u>17/02/2012.</u></p>
                        <p><b>Estado civil:</b> <u>Soltera.</u></p>
                        <p><b>Nacionalidad:</b> <u>Uruguaya.</u></p>
                        <p><b>Residencia Actual:</b> <u>Montevideo.</u></p>
                        <p><b>Residencia Anterior:</b> <u>Marindia, Canelones.</u></p>
                        <p><b>Grado de instrucción:</b> <u>Bachillerato completo.</u></p>
                        <p><b>Fecha de internación:</b> <u>23/06/2021</u></p>
                        <p><b>ENFERMEDAD ACTUAL:</b> Paciente, sexo femenino, 14 años de edad, con antecedentes de Hipertensión Arterial, desde 2021, tratada con Enalapril 10mg/día, sin dieta DASH, ingresa al hospital un jueves 23/06/21 a las 19:00 PM por un dolor en el abdomen de inicio súbito, con carácter cólico, intesidad 10/10, se irradia en forma de hemicinturón, que no cede al uso de antiespasmódicos (Buscapina) automedicado en su casa, donde el dolor se agranda por ingesta de alimentos grasos, y alivia en posición decúbito lateral. Presentó síntomas acompañantes como vómitos y naúseas. Junto con familiares, en el hospìtal se realizaron estudios de laboratorio, Rayos-X, ECG y ecografía abdominal.</p>
                  </div>
                </section>
            </main>
        </div>
    </div>

    <footer>
        
        <h6>© 2026 Hospital de Clínicas Todos los derechos registrados. Desarrollado por División Tecnologias de la Información</h6>
    </footer>

    <script>
        src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"

        integrity = "sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"

        crossorigin = "anonymous"

    </script>
</body>
</html>
