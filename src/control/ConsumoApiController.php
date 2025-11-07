<?php
require_once __DIR__ . '/../model/Token.php';
require_once __DIR__ . '/../model/ClienteApi.php';
require_once __DIR__ . '/../model/Cancha.php';

class ConsumoApiController
{
    /**
     * Punto de entrada principal para consumo de API
     * Valida token, procesa petición y devuelve JSON
     */
    public function procesar()
    {
        // Configurar headers para JSON
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");

        // Permitir solicitudes OPTIONS (preflight)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        // 1️⃣ VALIDAR MÉTODO HTTP
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responderError('Método no permitido. Use POST', 405);
            return;
        }

        // 2️⃣ LEER DATOS DEL CUERPO (POST)
        $input = file_get_contents("php://input");
        $dataInput = json_decode($input, true);

        // Si viene vacío, intentar con $_POST (por formularios normales)
        if (!$dataInput) {
            $dataInput = $_POST;
        }

        $tipo = $dataInput['tipo'] ?? '';
        $token = $dataInput['token'] ?? '';
        $data = $dataInput['data'] ?? '';

        // 3️⃣ VALIDAR TOKEN
        if (empty($token)) {
            $this->responderError('Token no proporcionado', 401);
            return;
        }

        $tokenData = Token::findByToken($token);
        if (!$tokenData) {
            $this->responderError('Token inválido', 401);
            return;
        }

        if ($tokenData['estado'] !== 'Activo') {
            $this->responderError('Token inactivo', 401);
            return;
        }

        if ($tokenData['cliente_estado'] !== 'Activo') {
            $this->responderError('Cliente API no está activo', 403);
            return;
        }

        // 4️⃣ PROCESAR PETICIÓN SEGÚN EL TIPO
        switch ($tipo) {
            case 'buscar_cancha_nombre':
                $this->buscarCanchaPorNombre($data, $tokenData);
                break;

            case 'buscar_cancha_ubicacion':
                $this->buscarCanchaPorUbicacion($data, $tokenData);
                break;

            case 'listar_canchas':
                $this->listarCanchas($tokenData);
                break;

            case 'listar_canchas_disponibles':
                $this->listarCanchasDisponibles($tokenData);
                break;

            default:
                $this->responderError('Tipo de operación no válida', 400);
        }
    }

    /**
     * Buscar canchas por nombre
     */
    private function buscarCanchaPorNombre($nombre, $tokenData)
    {
        if (empty($nombre)) {
            $this->responderError('Debe proporcionar un nombre para buscar', 400);
            return;
        }

        $canchas = ClienteApi::buscarCanchasPorNombre($nombre);
        $this->responderExito([
            'cliente' => $tokenData['razon_social'],
            'total' => count($canchas),
            'canchas' => $canchas
        ]);
    }

    /**
     * Buscar canchas por ubicación
     */
    private function buscarCanchaPorUbicacion($ubicacion, $tokenData)
    {
        if (empty($ubicacion)) {
            $this->responderError('Debe proporcionar una ubicación para buscar', 400);
            return;
        }

        $canchas = ClienteApi::buscarCanchasPorUbicacion($ubicacion);
        $this->responderExito([
            'cliente' => $tokenData['razon_social'],
            'total' => count($canchas),
            'canchas' => $canchas
        ]);
    }

    /**
     * Listar todas las canchas
     */
    private function listarCanchas($tokenData)
    {
        $canchas = Cancha::all();
        $this->responderExito([
            'cliente' => $tokenData['razon_social'],
            'total' => count($canchas),
            'canchas' => $canchas
        ]);
    }

    /**
     * Listar canchas disponibles
     */
    private function listarCanchasDisponibles($tokenData)
    {
        $todasCanchas = Cancha::all();
        $disponibles = array_filter($todasCanchas, fn($c) => $c['estado'] === 'Disponible');

        $this->responderExito([
            'cliente' => $tokenData['razon_social'],
            'total' => count($disponibles),
            'canchas' => array_values($disponibles)
        ]);
    }

    /**
     * Responder con éxito
     */
    private function responderExito($contenido)
    {
        echo json_encode([
            'status' => true,
            'mensaje' => 'Consulta exitosa',
            'data' => $contenido
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Responder con error
     */
    private function responderError($mensaje, $codigo = 400)
    {
        http_response_code($codigo);
        echo json_encode([
            'status' => false,
            'mensaje' => $mensaje,
            'data' => null
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Vista pública para probar la API
     */
    public function vistaTest()
    {
        // Sin sesión ni login
        $tokens = Token::all();

        require __DIR__ . '/../view/consumoapi/header_test.php';
        require __DIR__ . '/../view/consumoapi/test.php';
        require __DIR__ . '/../view/consumoapi/footer_test.php';
    }
}
