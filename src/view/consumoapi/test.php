<div class="container py-5" style="max-width: 1200px;">
  <!-- TÍTULO -->
  <div class="text-center mb-5">
    <h2 class="fw-bold text-primary">
      <i class="fas fa-futbol me-2"></i> Consulta de Canchas
    </h2>
    <p class="text-muted mb-0">
      Consulta y visualiza información actualizada de las canchas deportivas disponibles.
    </p>
  </div>

  <!-- FORMULARIO DE BÚSQUEDA -->
  <div class="card shadow-lg border-0 mb-5">
    <div class="card-header text-white d-flex align-items-center justify-content-between"
         style="background: linear-gradient(135deg, #0d6efd, #084298);">
      <div>
        <i class="fas fa-search me-2"></i>
        <strong>Buscar Canchas</strong>
      </div>
    </div>

    <div class="card-body px-4 py-4">
      <form id="formTestAPI" class="row g-3 align-items-end" method="POST">

        <!-- Token -->
        <input type="hidden" id="token" name="token"
               value="tok_38a51afa73011df642e1cb75baaa3a92-7">

        <!-- URL base de la API -->
        <input type="hidden" id="ruta_api" value="https://canchasdeportivas.serviciosvirtuales.com.pe/">

        <!-- Tipo de búsqueda -->
        <div class="col-md-5">
          <label for="tipo" class="form-label fw-semibold">Tipo de búsqueda</label>
          <select class="form-select border-primary shadow-sm" id="tipo" name="tipo" required>
            <option value="">Seleccione tipo de búsqueda...</option>
            <option value="listar_canchas">Listar todas las canchas</option>
            <option value="listar_canchas_disponibles">Solo disponibles</option>
            <option value="buscar_cancha_nombre">Por nombre</option>
            <option value="buscar_cancha_ubicacion">Por ubicación</option>
          </select>
        </div>

        <!-- Campo de búsqueda -->
        <div class="col-md-5" id="divData" style="display: none;">
          <label for="data" class="form-label fw-semibold">Dato de búsqueda</label>
          <div class="input-group shadow-sm">
            <span class="input-group-text bg-primary text-white">
              <i class="fas fa-keyboard"></i>
            </span>
            <input type="text" class="form-control border-primary" id="data" name="data"
                   placeholder="Ingrese nombre o ubicación">
          </div>
          <small class="text-muted">Solo necesario para búsquedas específicas.</small>
        </div>

        <!-- Botones -->
        <div class="col-md-2 text-center">
          <button type="submit" class="btn btn-primary w-100 shadow-sm">
            <i class="fas fa-magnifying-glass me-1"></i> Buscar
          </button>
          <button type="button" class="btn btn-outline-secondary w-100 mt-2" id="btnLimpiar">
            <i class="fas fa-rotate-left me-1"></i> Limpiar
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- RESULTADOS -->
  <div class="card shadow border-0">
    <div class="card-header text-white" 
         style="background: linear-gradient(135deg, #0d6efd, #084298);">
      <i class="fas fa-database me-2"></i> Resultados
    </div>
    <div class="card-body bg-light">
      <div id="loading" style="display: none;" class="text-center py-5">
        <div class="spinner-border text-success" role="status"></div>
        <p class="mt-3 text-muted">Procesando petición...</p>
      </div>

      <div id="resultado" class="table-responsive" style="min-height: 250px;">
        <em class="text-muted">Los resultados aparecerán aquí...</em>
      </div>
    </div>
  </div>
</div>

<!-- ESTILOS -->
<style>
  body {
    background: #f8f9fa;
  }
  table {
    border-radius: 8px;
    overflow: hidden;
  }
  td, th {
    vertical-align: middle;
  }
  .estado-disponible {
    color: #198754;
    font-weight: bold;
  }
  .estado-ocupado {
    color: #dc3545;
    font-weight: bold;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 50% !important;
    margin: 0 3px;
  }
  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #0d6efd !important;
    color: white !important;
  }
  .dataTables_filter input {
    border-radius: 8px;
    border: 1px solid #ced4da;
    padding: 5px 10px;
  }
</style>

<!-- SCRIPT PRINCIPAL -->
<script>
document.getElementById('tipo').addEventListener('change', function() {
  const divData = document.getElementById('divData');
  const inputData = document.getElementById('data');
  if (this.value === 'buscar_cancha_nombre' || this.value === 'buscar_cancha_ubicacion') {
    divData.style.display = 'block';
    inputData.required = true;
  } else {
    divData.style.display = 'none';
    inputData.required = false;
    inputData.value = '';
  }
});

document.getElementById('formTestAPI').addEventListener('submit', async function(e) {
  e.preventDefault();
  const loading = document.getElementById('loading');
  const resultado = document.getElementById('resultado');
  const formData = new FormData(this);
  const ruta_api = document.getElementById('ruta_api').value; // 🔹 URL base dinámica

  loading.style.display = 'block';
  resultado.innerHTML = '';

  try {
   // 🔹 Ahora apunta a tu propio controlador del sistema TOKEN
const response = await fetch(`?c=consumoApi&a=consumir`, {
  method: 'POST',
  body: formData
});


    const data = await response.json();

    if (data.status && data.data && data.data.canchas) {
      const canchas = data.data.canchas;
      let html = `
        <div class="alert alert-success mb-4">
          <strong>Consulta exitosa:</strong> ${data.data.total} resultados encontrados.
        </div>
        <table class="table table-hover table-bordered bg-white" id="tablaCanchas">
          <thead class="table-success text-dark">
            <tr>
              <th>Nro</th>
              <th>Nombre</th>
              <th>Ubicación</th>
              <th>Descripción</th>
              <th>Precio/Hora</th>
              <th>Teléfono</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>`;

      canchas.forEach((c) => {
        html += `
          <tr>
            <td></td>
            <td>${c.nombre || '—'}</td>
            <td>${c.ubicacion || '—'}</td>
            <td>${c.descripcion || '—'}</td>
            <td><span class="badge bg-primary bg-opacity-75">S/ ${c.precio_hora || '—'}</span></td>
            <td>${c.telefono || '—'}</td>
            <td class="${c.estado === 'Disponible' ? 'estado-disponible' : 'estado-ocupado'}">${c.estado || '—'}</td>
          </tr>`;
      });

      html += `</tbody></table>`;
      resultado.innerHTML = html;

      // Inicializar DataTable
      setTimeout(() => {
        const table = $('#tablaCanchas').DataTable({
          pageLength: 5,
          lengthChange: false,
          ordering: true,
          columnDefs: [{ orderable: false, targets: 0 }],
          language: {
            search: "Buscar:",
            paginate: {
              first: "Primero",
              last: "Último",
              next: "Siguiente",
              previous: "Anterior"
            },
            info: "Mostrando _START_ a _END_ de _TOTAL_ resultados",
            infoEmpty: "No hay registros disponibles",
            zeroRecords: "No se encontraron coincidencias"
          },
          drawCallback: function () {
            const api = this.api();
            const info = api.page.info();
            api.column(0, { page: 'current' }).nodes().each(function (cell, i) {
              cell.innerHTML = `<span class="badge bg-light text-dark">${info.start + i + 1}</span>`;
            });
          }
        });
      }, 200);
    } else {
      resultado.innerHTML = '<div class="alert alert-warning">No se encontraron resultados.</div>';
    }
  } catch (error) {
    resultado.innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
  } finally {
    loading.style.display = 'none';
  }
});

document.getElementById('btnLimpiar').addEventListener('click', function() {
  document.getElementById('formTestAPI').reset();
  document.getElementById('divData').style.display = 'none';
  document.getElementById('resultado').innerHTML = '<em class="text-muted">Los resultados aparecerán aquí...</em>';
});
</script>

<!-- LIBRERÍAS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>








