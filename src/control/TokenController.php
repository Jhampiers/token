<?php
require_once __DIR__ . '/../model/Token.php';

class TokenController
{
  private function requireAuth(){
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['user'])) {
      header('Location: ' . BASE_URL . '?c=auth&a=loginForm');
      exit;
    }
  }

  private function requireAdmin(){
    $this->requireAuth();
    if (($_SESSION['user']['rol'] ?? '') !== 'admin') {
      http_response_code(403);
      echo 'Acceso restringido';
      exit;
    }
  }

  /** 📋 Listado de tokens (solo admin) */
  public function index(){
    $this->requireAdmin();
    $tokens = Token::all();
    require __DIR__ . '/../view/tokens/index.php';
  }

  /** ✏️ Formulario de edición (solo admin) */
  public function edit(){
    $this->requireAdmin();
    $id = (int)($_GET['id'] ?? 0);
    $token = Token::find($id);
    if (!$token) {
      http_response_code(404);
      echo 'Token no encontrado';
      exit;
    }
    $isEdit = true;
    require __DIR__ . '/../view/tokens/form.php';
  }

  /** 💾 Actualizar token (solo admin) */
  public function update(){
    $this->requireAdmin();
    $id = (int)($_POST['id'] ?? 0);

    // 🔧 CAMBIO AQUÍ — usamos "tokens" porque así se llama en el formulario y en la BD
    if (empty($_POST['tokens'])) {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'El campo token es obligatorio';
      header('Location: ' . BASE_URL . '?c=token&a=edit&id=' . $id);
      exit;
    }

    Token::update($id, $_POST);
    header('Location: ' . BASE_URL . '?c=token&a=index');
  }
}

