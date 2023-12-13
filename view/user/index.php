<?php
  session_start();

  // Validamos que exista una session y ademas que el cargo que exista sea igual a 1 (Administrador)
  if(!isset($_SESSION['cargo']) || $_SESSION['cargo'] != 2){
    header('location: ../../index.php');
  }
  include './../../model/conexion.php'; // Asegúrate de incluir el archivo de la clase Conexion

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (isset($_POST['fechaElegida'])) {
        $fechaElegida = $_POST['fechaElegida'];

  
  $con = new Conexion();
  $con->conectar();

  // $fechaElegida = '2023-12-07'; // Aquí define la fecha para la que deseas calcular el promedio


  $tempActual = "SELECT temperatura FROM dht11 ORDER BY id DESC LIMIT 1"; // tempActual SQL para obtener la última temperatura registrada

  $promedioTemp = "SELECT AVG(temperatura) AS promedio_temperatura 
             FROM dht11 
             WHERE DATE(fecha) = '$fechaElegida'"; // tempActual SQL para obtener el promedio de temperatura para una fecha específica

  $promedioHumedad = "SELECT AVG(humedad) AS promedio_humedad 
      FROM dht11 
      WHERE DATE(fecha) = '$fechaElegida'"; // tempActual SQL para obtener el promedio de temperatura para una fecha específica

  $consultagps = "SELECT latitud, longitud, fecha 
    FROM gps 
    ORDER BY id DESC 
    LIMIT 1"; // Consulta SQL para obtener la última ubicación

  $tabla_querydht11 = "SELECT temperatura, humedad, fecha FROM dht11 WHERE fecha = '$fechaElegida'";

  $tabla_query_gps = "SELECT latitud, longitud, fecha FROM gps WHERE fecha = '$fechaElegida'";


  $result_table_dht11 = $con->query($tabla_querydht11);

  $result_table_gps = $con->query($tabla_query_gps);


  $resultadogps = $con->query($consultagps);

  $promedio = $con->query($promedioTemp);

  $promedioHu = $con->query($promedioHumedad);

  
  $resultado = $con->query($tempActual);
  
  if ($resultado->num_rows > 0) {
      $fila = $resultado->fetch_assoc();
      $ultimaTemperatura = $fila['temperatura'];
  } else {
      $ultimaTemperatura = "N/A"; // Si no se encuentra ningún registro
  }

  if ($promedio->num_rows > 0) {
    $fila = $promedio->fetch_assoc();
    $promedioTemperaturaDia = $fila['promedio_temperatura'];
  } else {
      $promedioTemperaturaDia = "N/A"; // Si no se encuentra ningún registro para esa fecha
  }

  if ($promedioHu->num_rows > 0) {
    $fila = $promedioHu->fetch_assoc();
    $promedioHumedadDia = $fila['promedio_humedad'];
  } else {
      $promedioHumedadDia = "N/A"; // Si no se encuentra ningún registro para esa fecha
  }

  if ($resultadogps->num_rows > 0) {
      $fila = $resultadogps->fetch_assoc();
      $latitud = $fila['latitud'];
      $longitud = $fila['longitud'];
      $fecha = $fila['fecha'];
  } else {
      $latitud = "N/A";
      $longitud = "N/A";
      $fecha = "N/A"; // Si no se encuentra ningún registro en la tabla
  }

  $con->cerrar();

}
}
  
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png">
  <link rel="icon" type="image/png" href="img/favicon.png">
  <title>
    System
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="./../../css/nucleo-icons.css" rel="stylesheet" />
  <link href="./../../css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="./../../css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="./../../css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
  <link id="pagestyle" href="" rel="stylesheet" />

</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>

  <main class="main-content position-relative border-radius-lg ">

    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        
        <div class="col-xl-7">
          <div class="card">
            <div class="card-header d-flex pb-0 p-3">
              <h6 class="my-auto">
                Hola <?php echo ucfirst($_SESSION['nombre']); ?> <br>
                <a href="../../controller/cerrarSesion.php">
                  <button class="btn btn-danger btn-sm" type="button" name="button">Cerrar sesion</button>
                </a>
            </h6>
              <div class="nav-wrapper position-relative ms-auto w-50">
                <ul class="nav nav-pills nav-fill p-1" role="tablist">

                  <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#cam1" role="tab" aria-controls="cam1" aria-selected="true">
                      Lounge
                    </a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="card-body p-3 mt-2">
              <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show position-relative active height-400 border-radius-lg" id="cam1" role="tabpanel" aria-labelledby="cam1" style="">

                 <div id="map" style="width: 100%; height: 400px;border-radius:10px;"></div>
                  <div class="position-absolute d-flex top-0 w-100">
                    
                    <p class="text-white p-3 mb-0">Última ubicación: Latitud <?php echo $latitud; ?>, Longitud <?php echo $longitud; ?> (<?php echo $fecha; ?>)</p>

                  </div>
                  <div class="row">

                    <div class="col-6 mt-3">
                      <p>Registros de Temperatura y humedad</p>
                      <?php
                          // Verificar si hay resultados
                          if ($result_table_dht11->num_rows > 0) {
                              echo "<table class='table border='1'>
                              <tr>
                              <th>Temperatura</th>
                              <th>Humedad</th>
                              <th>Fecha</th>
                              </tr>";

                              // Mostrar los datos en la tabla
                              while ($row = $result_table_dht11->fetch_assoc()) {
                                  echo "<tr>";
                                  echo "<td>" . $row['temperatura'] . "</td>";
                                  echo "<td>" . $row['humedad'] . "</td>";
                                  echo "<td>" . $row['fecha'] . "</td>";
                                  echo "</tr>";
                              }
                              echo "</table>";
                          } else {
                              echo "No se encontraron registros para la fecha especificada";
                          }
                          ?>
                    </div>

                    <div class="col-6 mt-3">
                    <p>Registros de Latitud y longitud</p>
                    <?php
                    // Verifica si hay resultados
                    if ($result_table_gps->num_rows > 0) {
                      echo "<table class='table border='1'>
                        <tr>
                        <th>Latitud</th>
                        <th>Longitud</th>
                        <th>Fecha</th>
                        <th>Ver Mapa</th>
                        </tr>";

                        // Muestra los datos en la tabla
                        while ($row_gps = $result_table_gps->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td>" . $row_gps['latitud'] . "</td>";
                          echo "<td>" . $row_gps['longitud'] . "</td>";
                          echo "<td>" . $row_gps['fecha'] . "</td>";
                  
                          // Agrega un enlace para abrir el mapa en Google Maps
                          echo "<td><a href='https://www.google.com/maps/search/?api=1&query=" . $row_gps['latitud'] . "," . $row_gps['longitud'] . "' target='_blank'>Ver en Google Maps</a></td>";
                  
                          echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No se encontraron registros para la fecha especificada en la tabla GPS";
                    }
                    ?>

                    </div>
                  </div>
                  
                </div>

              </div>
            </div>

          </div>
        </div>

        <div class="col-xl-5 ms-auto mt-xl-0 mt-4">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body p-3">
                  <div class="row">
                    <div class="col-8 my-auto">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-capitalize font-weight-bold opacity-7">El clima hoy</p>
                        <h5 class="font-weight-bolder mb-0">
                        CDMX - <?php echo $ultimaTemperatura; ?>°C
                        </h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-4">

            <div class="col-md-6 mt-md-0 mt-4 mb-3">
              <div class="card">
                <div class="card-body text-center">
                  <h1 class="text-gradient text-primary"><span id="status1" countto=" <?php echo $promedioTemperaturaDia; ?>"> </span> <span class="text-lg ms-n2">°C</span></h1>
                  <h6 class="mb-0 font-weight-bolder">
                    Promedio temperatura <br> <?php echo $fechaElegida; ?> 
                  </h6>
                </div>
              </div>
            </div>

            <div class="col-md-6 mt-md-0 mt-4 mb-3">
              <div class="card">
                <div class="card-body text-center">
                  <h1 class="text-gradient text-primary"> <span id="status2" countto="<?php echo $promedioHumedadDia; ?>"><?php echo $promedioHumedadDia; ?></span> <span class="text-lg ms-n1">%</span></h1>
                  <h6 class="mb-0 font-weight-bolder">
                    Promedio Humedad <br> <?php echo $fechaElegida; ?> 
                  </h6>
                </div>
              </div>
            </div>

            <div class="col-md-12 mt-md-0 mt-4 mb-3">
              <div class="card">
                <div class="card-body text-center">
                <form method="POST" action="">
  <label for="fechaElegida">Selecciona una fecha:</label>
  <input type="date" id="fechaElegida" name="fechaElegida">
  <button type="submit" name="submit">Buscar</button>
</form>

                </div>
              </div>
            </div>

          </div>

        </div>
      </div>

        <hr class="horizontal dark my-5">

    </div>
  </main>

  <!--   Core JS Files   -->
  <script src="./../../js/core/popper.min.js"></script>
  <script src="./../../js/core/bootstrap.min.js"></script>
  <script src="./../../js/plugins/countup.min.js"></script>

<script>

    function iniciarMap(){
        var coord = {lat:-34.5956145 ,lng: -58.4431949};
        var map = new google.maps.Map(document.getElementById('map'),{
          zoom: 10,
          center: coord
        });
        var marker = new google.maps.Marker({
          position: coord,
          map: map
        });
    }

    // Count To
    if (document.getElementById('status1')) {
      const countUp = new CountUp('status1', document.getElementById("status1").getAttribute("countTo"));
      if (!countUp.error) {
        countUp.start();
      } else {
        console.error(countUp.error);
      }
    }
    if (document.getElementById('status2')) {
      const countUp = new CountUp('status2', document.getElementById("status2").getAttribute("countTo"));
      if (!countUp.error) {
        countUp.start();
      } else {
        console.error(countUp.error);
      }
    }

    function iniciarMap(){
      var coord = {lat:<?php echo $latitud; ?> ,lng: <?php echo $longitud; ?>};
      var map = new google.maps.Map(document.getElementById('map'),{
        zoom: 13,
        center: coord
      });

      var marker = new google.maps.Marker({
        position: coord,
        map: map
      });
    }

  </script>

  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="./../../js/argon-dashboard.min.js?v=2.0.4"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik&callback=iniciarMap"></script>

</body>

</html>