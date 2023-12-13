<?php
// Verificar si se reciben datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Validar las credenciales (esto es un ejemplo muy básico, se debe implementar una lógica más segura)
    $usuario_valido = 'admin'; // Nombre de usuario válido
    $contrasena_valida = 'root'; // Contraseña válida

    if ($usuario === $usuario_valido && $contrasena === $contrasena_valida) {
        // Si las credenciales son válidas, se puede redirigir a otra página o enviar una respuesta JSON, por ejemplo
        // Aquí un ejemplo de respuesta JSON
        echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso"]);
    } else {
        // Si las credenciales son inválidas, enviar una respuesta de error
        echo json_encode(["success" => false, "message" => "Credenciales incorrectas"]);
    }
} else {
    // Si no se enviaron datos, mostrar un mensaje de error
    echo json_encode(["success" => false, "message" => "Datos no recibidos"]);
}
?>
