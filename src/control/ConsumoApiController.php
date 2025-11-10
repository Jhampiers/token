<?php
require_once __DIR__ . '/../model/Token.php';

class ConsumoApiController
{
    /**
     * Muestra la vista de prueba del consumo de la API
     */
    public function vistaTest()
    {
        // No requiere login
        $tokens = Token::all();

        require __DIR__ . '/../view/consumoapi/header_test.php';
        require __DIR__ . '/../view/consumoapi/test.php';
        require __DIR__ . '/../view/consumoapi/footer_test.php';
    }

    /**
     * Envía una solicitud al sistema principal (API real)
     */
    public function consumir()
    {
        header('Content-Type: application/json; charset=utf-8');

        // 1️⃣ Validar que se envió por POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => false, 'mensaje' => 'Método no permitido. Use POST']);
            return;
        }

        // 2️⃣ Obtener datos del formulario
        $tipo  = $_POST['tipo'] ?? '';
        $token = $_POST['token'] ?? '';
        $data  = $_POST['data'] ?? '';

        if (empty($tipo) || empty($token)) {
            echo json_encode(['status' => false, 'mensaje' => 'Debe seleccionar tipo y token']);
            return;
        }

        // 3️⃣ Preparar la URL del sistema principal (API real)
        $url_api = "https://canchasdeportivas.serviciosvirtuales.com.pe/?c=consumoapi&a=procesar";

        // 4️⃣ Preparar datos a enviar
        $payload = [
            'tipo'  => $tipo,
            'token' => $token,
            'data'  => $data
        ];

        // 5️⃣ Enviar solicitud usando cURL
        $ch = curl_init($url_api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        // 6️⃣ Validar respuesta
        if ($error) {
            echo json_encode(['status' => false, 'mensaje' => 'Error al conectar con la API: ' . $error]);
            return;
        }

        // 7️⃣ Mostrar respuesta JSON de la API real
        echo $response;
    }
}

