<?php require __DIR__ . '/../layout/header.php'; ?>
<style>
  .cap-wrap{padding:16px}
  .cap-card{background:#fff;border-radius:14px;box-shadow:0 8px 24px rgba(15,23,42,.08);padding:16px;margin-bottom:16px}
  .cap-title{font-size:20px;font-weight:700;margin-bottom:10px}
  label{display:block;margin:6px 0 6px 2px;font-weight:600}
  input{width:100%;padding:11px 12px;border-radius:12px;border:1px solid #e5e7eb;background:#fff}
  .actions{display:flex;gap:10px;margin-top:12px;flex-wrap:wrap}
  .btn{display:inline-block;padding:10px 12px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;cursor:pointer;text-decoration:none;color:#111827}
  .btn.primary{background:#2563eb;color:#fff;border-color:#1d4ed8}
  .btn.gray{background:#f3f4f6}
  .alert{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:10px;border-radius:10px;margin-bottom:12px}
</style>

<div class="cap-wrap">
  <div class="cap-card">
    <div class="cap-title">Editar Token</div>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>?c=token&a=update">
      <input type="hidden" name="id" value="<?= (int)$token['id'] ?>">

      <div>
        <label for="tokVal">Token *</label>
        <input id="tokVal" name="tokens" type="text" required 
               value="<?= htmlspecialchars($token['tokens'] ?? '') ?>">
      </div>

      <div class="actions">
        <button class="btn primary" type="submit">Guardar cambios</button>
        <a class="btn" href="<?= BASE_URL ?>?c=token&a=index">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>



