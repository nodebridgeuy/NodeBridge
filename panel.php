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
                    <h6>Funcionario/a</h6>
                    <?= htmlspecialchars($_SESSION["usuario"]) ?>
                </div>

                
                <div class="list-group">

                    <a href="/proyecto/login/panel.php" class="list-group-item list-group-item-action active">Dashboard</a>

                    <a href="/proyecto/app/agregar_doc.php" class="list-group-item list-group-item-action">Documentos</a>
                
                    <a href="/proyecto/app/registrar_encuesta.php" class="list-group-item list-group-item-action">Encuestas</a>

                    <a href="/proyecto/app/traslados.html" class="list-group-item list-group-item-action">Traslados</a>

                    <a href="/proyecto/app/eliminar_funcionario.php" class="list-group-item list-group-item-action">Funcionarios</a>

                    <a href="logout.php" class="list-group-item list-group-item-action text-danger">Cerrar sesión</a>
                </div>

            </aside>



            <main class="col-md-9 p-4">
                <h2 class="h4 mb-4">Panel de pacientes</h2>
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

                  <section class="card">

                <div class="card-header">

                    <h3 class="h5 mb-0">Próximos Turnos</h3>

                </div>

                <div class="card-body">

                    <table class="table table-striped table-hover">

                        <thead>

                            <tr>

                                <th>Paciente</th>

                                <th>Médico</th>

                                <th>Hora</th>

                                <th>Estado</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>Juan Loussich</td>

                                <td>Dra. Martina Díaz</td>

                                <td>7:00</td>

                                <td> <span class="badge bg-success">Confirmado</span></td>

                            </tr>

                             <tr>

                                <td>Alfredo Moreno</td>

                                <td>Dra. Lorena Rodríguez</td>

                                <td>12:30</td>

                                <td> <span class="badge bg-danger">Cancelado</span></td>

                            </tr>

                            <tr>

                                <td>Roberto Musso</td>

                                <td>Dr. Brian Fernández</td>

                                <td>17:00</td>

                                <td> <span class="badge bg-warning">Llega tarde </span></td>

                            </tr>
                        </tbody>
                    </table>
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
