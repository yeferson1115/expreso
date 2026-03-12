<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe los datos del frontend
    $data = json_decode(file_get_contents("php://input"), true);

    $numeroIdentificacion = $data['numeroIdentificacion'] ?? null;
    $fechaInicio = $data['fechaInicio'] ?? null;

    if (!$numeroIdentificacion) {
        echo json_encode(["error" => true, "message" => "Faltan parámetros requeridos"]);
        exit;
    }

    // Construye la URL con los parámetros
    $url = "https://testportalapp.expresobrasilia.com/BrasiliaFacturacionWS/api/v1/productos/getTiquetes";
    $url .= "?numeroIdentificacion=" . urlencode($numeroIdentificacion);
    $url .= "&fechaInicio=2018-01-01" ;

    // Credenciales proporcionadas
    $usuario = "BRASILIA_PRUEBAS";
    $contrasena = "Brasilia.2024*";

    // Inicializa CURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json'
    ]);

    // Autenticación básica
    curl_setopt($ch, CURLOPT_USERPWD, "$usuario:$contrasena");

    // Desactivar SSL (solo pruebas)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    // Ejecuta la solicitud
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        echo json_encode(["error" => true, "message" => $error]);
    } else {
        echo $response;
    }

} else {
    echo json_encode(["error" => true, "message" => "Método no permitido"]);
}
