<?php
require_once __DIR__ . '/../library/conexion.php';

class DashboardController {
  private function requireAuth() {
    session_start();
    if (empty($_SESSION['user'])) {
      header('Location: ' . BASE_URL . '?c=auth&a=loginForm');
      exit;
    }
  }

  public function index() {
    $this->requireAuth();

    $db = Conexion::getConexion();

    // ✅ Contar tokens
    $totalTokens = (int)$db->query("SELECT COUNT(*) AS total FROM tokens_api")->fetch()['total'];

    // ✅ Contar usuarios
    $totalUsuarios = (int)$db->query("SELECT COUNT(*) AS total FROM usuarios")->fetch()['total'];

    require __DIR__ . '/../view/dashboard/index.php';
  }
}

