<?php require __DIR__ . '/../layout/header.php'; ?>
<style>
  .cap-wrap{padding:16px}
  .cap-card{background:#fff;border-radius:14px;box-shadow:0 8px 24px rgba(15,23,42,.08);padding:16px;margin-bottom:16px}
  .cap-title{font-size:20px;font-weight:700;margin-bottom:12px}
  .cap-tablewrap{overflow:auto;border-radius:14px;background:#fff;box-shadow:0 8px 24px rgba(15,23,42,.08)}
  table.cap{width:100%;min-width:600px;border-collapse:collapse}
  table.cap th, table.cap td{padding:12px 14px;border-bottom:1px solid #e5e7eb;text-align:left;font-size:14px}
  table.cap thead tr{background:#f3f4f6}
  .cap-btn{display:inline-block;padding:8px 10px;border-radius:8px;border:1px solid #e5e7eb;background:#2563eb;color:#fff;text-decoration:none;font-size:13px}
  .cap-empty{padding:18px;text-align:center;color:#6b7280}
</style>

<div class="cap-wrap">
  <div class="cap-card">
    <div class="cap-title"><i class="fa-solid fa-key"></i> Lista de Tokens</div>

    <div class="cap-tablewrap">
      <table class="cap">
        <thead>
          <tr>
            <th>ID</th>
            <th>Token</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($tokens)): ?>
            <?php foreach ($tokens as $t): ?>
              <tr>
                <td><?= (int)$t['id'] ?></td>
              <td><code><?= htmlspecialchars($t['tokens'] ?? '') ?></code></td>
                <td>
                  <a class="cap-btn" href="<?= BASE_URL ?>?c=token&a=edit&id=<?= (int)$t['id'] ?>">Editar</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="3" class="cap-empty">No hay tokens registrados</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>


