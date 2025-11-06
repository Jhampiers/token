<?php
require_once __DIR__ . '/../library/conexion.php';

class Token {

  // Obtener todos los tokens
  public static function all(): array {
    $sql = "SELECT id, tokens FROM tokens_api ORDER BY id DESC";
    return Conexion::getConexion()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  // Buscar un token por su ID
  public static function find(int $id): ?array {
    $st = Conexion::getConexion()->prepare("SELECT * FROM tokens_api WHERE id = ? LIMIT 1");
    $st->execute([$id]);
    $r = $st->fetch(PDO::FETCH_ASSOC);
    return $r ?: null;
  }

  // Crear un nuevo token
  public static function create(array $d): int {
    $st = Conexion::getConexion()->prepare("
      INSERT INTO tokens_api (tokens, fecha_creacion)
      VALUES (?, NOW())
    ");
    $st->execute([
      trim($d['tokens']) // ahora usa 'tokens' correctamente
    ]);
    return (int) Conexion::getConexion()->lastInsertId();
  }

  // Actualizar token existente
  public static function update(int $id, array $d): bool {
    $st = Conexion::getConexion()->prepare("
      UPDATE tokens_api SET tokens=? WHERE id=?
    ");
    return $st->execute([
      trim($d['tokens']),
      $id
    ]);
  }

  // Buscar token por su valor (útil para autenticaciones)
  public static function findByToken(string $tokenValue): ?array {
    $st = Conexion::getConexion()->prepare("SELECT * FROM tokens_api WHERE tokens = ? LIMIT 1");
    $st->execute([trim($tokenValue)]);
    $r = $st->fetch(PDO::FETCH_ASSOC);
    return $r ?: null;
  }

  // Eliminar token (opcional)
  public static function delete(int $id): bool {
    $st = Conexion::getConexion()->prepare("DELETE FROM tokens_api WHERE id = ?");
    return $st->execute([$id]);
  }
}


