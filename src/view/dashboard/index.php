<?php require __DIR__ . '/../layout/header.php'; ?>

<h2 style="margin:0 0 14px 0">Panel de Control</h2>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem">

  <!-- Total Tokens -->
  <div class="card kpi" style="display:flex;align-items:center;gap:1rem;padding:1.2rem;border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 2px 6px rgba(0,0,0,0.05)">
    <div style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:rgba(37,99,235,.1);color:#2563eb">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m4 0V9a2 2 0 00-2-2h-1m-6 10v-4a2 2 0 00-2-2H7a2 2 0 00-2 2v4" />
      </svg>
    </div>
    <div>
      <h3 style="margin:0;font-size:14px;color:#6b7280;font-weight:500">Total de Tokens</h3>
      <strong style="font-size:20px;color:#111827"><?= $totalTokens ?></strong>
    </div>
  </div>

  <!-- Total Usuarios -->
  <div class="card kpi" style="display:flex;align-items:center;gap:1rem;padding:1.2rem;border:1px solid #e5e7eb;border-radius:12px;background:#fff;box-shadow:0 2px 6px rgba(0,0,0,0.05)">
    <div style="width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:rgba(16,185,129,.1);color:#10b981">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
    </div>
    <div>
      <h3 style="margin:0;font-size:14px;color:#6b7280;font-weight:500">Usuarios Registrados</h3>
      <strong style="font-size:20px;color:#111827"><?= $totalUsuarios ?></strong>
    </div>
  </div>

</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

