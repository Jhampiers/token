<?php
class ConsumoApiController
{
    /**
     * Muestra la vista del test
     */
    public function vistaTest()
    {
        require __DIR__ . '/../view/consumoapi/header_test.php';
        require __DIR__ . '/../view/consumoapi/test.php';
        require __DIR__ . '/../view/consumoapi/footer_test.php';
    }

    /**
     * Método que envía los datos al API principal y devuelve la respuesta
     */
    public function consumir()
    {
        header('Content-Type: application/json; charset=utf-8');

        // Obtener datos enviados por el formulario
        $tipo = $_POST['tipo'] ?? '';
        $token = $_POST['token'] ?? '';
        $data = $_POST['data'] ?? '';
        $ruta_api = $_POST['ruta_api'] ?? '';

        // Validar que haya una ruta
        if (empty($ruta_api)) {
            echo json_encode(['status' => false, 'mensaje' => 'No se especificó la ruta del API']);
            return;
        }

        // Preparar datos para enviar al API principal
        $postData = [
            'tipo' => $tipo,
            'token' => $token,
            'data' => $data
        ];

        // Enviar al API principal usando cURL
        $ch = curl_init($ruta_api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // por si usa HTTPS sin certificado válido

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        // Si hay error de conexión
        if ($error) {
            echo json_encode(['status' => false, 'mensaje' => 'Error al conectar con el API: ' . $error]);
            return;
        }

        // Intentar decodificar la respuesta del API principal
        $jsonData = json_decode($response, true);

        // Si no se puede decodificar, entonces el API devolvió HTML o texto
        if ($jsonData === null) {
            echo json_encode([
                'status' => false,
                'mensaje' => 'La respuesta del API no es un JSON válido',
                'respuesta_raw' => $response
            ]);
            return;
        }

        // Devolver tal cual el JSON que vino del API principal
        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}

