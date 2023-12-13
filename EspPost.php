<?php

$user = "esp8266";
$pass = "esp8266";
$server = "localhost";
$db ="esp8266";
$con = mysqli_connect($server, $user, $pass, $db);

if ($con) {


    echo "Conexi贸n con base de datos exitosa!";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['temperatura']) && isset($_POST['humedad'])) {
            $temperatura = $_POST['temperatura'];
            $humedad = $_POST['humedad'];
            
            date_default_timezone_set('America/Mexico');
            $fecha_actual = date("Y-m-d H:i:s");
            
            $consulta = "INSERT INTO dht11(temperatura, humedad, fecha) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $consulta);
            
            mysqli_stmt_bind_param($stmt, 'sss', $temperatura, $humedad, $fecha_actual);
            $resultado = mysqli_stmt_execute($stmt);
            
            if ($resultado){
                echo "Registro en base de datos OK!";
            } else {
                echo "Falla! Registro BD";
            }
        }
        
        if(isset($_POST['latitud']) && isset($_POST['longitud'])) {
            
            $latitud = $_POST['latitud'];
            $longitud = $_POST['longitud'];
            
            date_default_timezone_set('America/Mexico');
            $fecha_actual = date("Y-m-d H:i:s");
            
            $consulta = "INSERT INTO gps(latitud, longitud, fecha) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $consulta);
            
            mysqli_stmt_bind_param($stmt, 'sss', $latitud, $longitud, $fecha_actual);
            $resultado = mysqli_stmt_execute($stmt);
            
            if ($resultado){
                echo "Registro en base de datos OK!";
            } else {
                echo "Falla! Registro BD";
            }
            
        }else {
            echo "Datos de latitud o longitud no recibidos.";
        }
    }
}
?>
