<?php

  /*
    En ocasiones el usuario puede volver al login
    aun si ya existe una sesion iniciada, lo correcto
    es no mostrar otra ves el login sino redireccionarlo
    a su pagina principal mientras exista una sesion entonces
    creamos un archivo que controle el redireccionamiento
  */

  session_start();

  // isset verifica si existe una variable o eso creo xd
  if(isset($_SESSION['id'])){
    header('location: controller/redirec.php');
  }

?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Login en PHP</title>

    <!-- Importamos los estilos de Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Font Awesome: para los iconos -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Sweet Alert: alertas JavaScript presentables para el usuario (más bonitas que el alert) -->
    <link rel="stylesheet" href="css/sweetalert.css">
    <!-- Estilos personalizados: archivo personalizado 100% real no feik -->
    <link rel="stylesheet" href="css/style.css">

    <link id="pagestyle" href="css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />


  </head>
  <body>

    <!-- Formulario Login -->

    <!-- / Final Formulario login -->

    <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
            <h1 class="text-white mb-2 mt-5">Bienvenido!</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">

            <div class="card-header text-center pt-4">
              <h5>Registrate ahora</h5>
            </div>

            <div class="card-body">

            <form id="formulario_registro">

                <div class="mb-3">
                  <input type="text" class="form-control" name="name" placeholder="Ingresa tu nombre">
                </div>

                <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Ingresa tu email">
                </div>

                <div class="mb-3">
                <input type="password" autocomplete="off" class="form-control" name="clave" placeholder="Ingresa tu clave">
                </div>

                <div class="mb-3">
                <input type="password" autocomplete="off" class="form-control" name="clave2" placeholder="Verificar contraseña">
                </div>

                <div class="text-center">
                  <button type="button" class="btn btn-primary btn-block" name="button" id="registro">Registrate</button>

                </div>

          </form>

                <p class="text-sm mt-3 mb-0">Ya tienes una cuenta?
                  <a href="index.php" class="text-dark font-weight-bolder">Iniciar Sesion</a>
                  
                </p>

            </div>

          </div>
        </div>
      </div>
    </div>
  </main>

    <!-- Jquery -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- SweetAlert js -->
    <script src="js/sweetalert.min.js"></script>
    <!-- Js personalizado -->
    <script src="js/operaciones.js"></script>
  </body>
</html>
