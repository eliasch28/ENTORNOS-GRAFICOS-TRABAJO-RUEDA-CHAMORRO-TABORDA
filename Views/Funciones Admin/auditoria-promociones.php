<?php
include '../../config/conexion.php';
require_once '../../config/requiere_admin.php';

$mensajeExito = '';
$mensajeError = '';

// Gestión de aprobación / rechazo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $codPromocion = isset($_POST['codPromocion']) ? (int) $_POST['codPromocion'] : 0;
  $accion = $_POST['accion'] ?? '';

  if ($codPromocion > 0 && in_array($accion, ['aprobar', 'rechazar'], true)) {
    $nuevoEstado = $accion === 'aprobar' ? 'aprobada' : 'denegada';
    $nuevoEstadoEsc = mysqli_real_escape_string($link, $nuevoEstado);

    $query = "UPDATE PROMOCIONES
              SET estadoPromocion = '$nuevoEstadoEsc'
              WHERE codPromocion = $codPromocion";

    if (mysqli_query($link, $query) && mysqli_affected_rows($link) > 0) {
      $mensajeExito = $accion === 'aprobar'
        ? 'La promoción fue aprobada correctamente.'
        : 'La promoción fue denegada correctamente.';
    } else {
      $mensajeError = 'No se pudo procesar la acción. Verificá el estado de la promoción.';
    }
  } else {
    $mensajeError = 'Solicitud inválida.';
  }
}

// Paginación
$porPagina = 10;
$paginaActual = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
$offset = ($paginaActual - 1) * $porPagina;

$sqlTotal = "SELECT COUNT(*) AS total FROM PROMOCIONES";
$resTotal = mysqli_query($link, $sqlTotal);
$total = mysqli_fetch_assoc($resTotal)['total'] ?? 0;
$totalPaginas = max(1, (int) ceil($total / $porPagina));

$sqlPromociones = "SELECT p.codPromocion,
                          p.descripcionPromocion,
                          p.descuentoPromocion,
                          p.estadoPromocion,
                          a.nombreAerolinea
                   FROM PROMOCIONES p
                   LEFT JOIN AEROLINEAS a ON p.codAerolinea = a.codAerolinea
                   ORDER BY p.codPromocion ASC
                   LIMIT $porPagina OFFSET $offset";
$resPromociones = mysqli_query($link, $sqlPromociones);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Auditoría de Promociones | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1" class="container my-5">
    <div class="mb-4">
      <h1 class="h2">Auditoría de Promociones</h1>
      <p class="text-muted">Revisá y aprobá las promociones enviadas por los CEO de aerolíneas.</p>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">

        <?php if (!empty($mensajeError)): ?>
          <div class="alert alert-danger d-flex align-items-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
            <div><?= htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8') ?></div>
          </div>
        <?php elseif (!empty($mensajeExito)): ?>
          <div class="alert alert-success d-flex align-items-start" role="status">
            <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>
            <div><?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?></div>
          </div>
        <?php endif; ?>

        <div class="table-responsive rounded">
          <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Aerolínea</th>
                <th scope="col">Descripción</th>
                <th scope="col">Descuento</th>
                <th scope="col">Estado</th>
                <th scope="col" class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($resPromociones && mysqli_num_rows($resPromociones) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resPromociones)): ?>
                  <?php
                  $estado = $row['estadoPromocion'] ?? 'pendiente';
                  $badgeClass = match ($estado) {
                    'aprobada' => 'bg-success',
                    'denegada' => 'bg-danger',
                    default => 'bg-warning text-dark',
                  };
                  $badgeLabel = match ($estado) {
                    'aprobada' => 'Aprobada',
                    'denegada' => 'Denegada',
                    default => 'Pendiente',
                  };
                  $esPendiente = $estado === 'pendiente';
                  ?>
                  <tr>
                    <th scope="row"><?= (int) $row['codPromocion'] ?></th>
                    <td><?= htmlspecialchars($row['nombreAerolinea'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-truncate" style="max-width: 260px;"
                      title="<?= htmlspecialchars($row['descripcionPromocion'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                      <?= htmlspecialchars($row['descripcionPromocion'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td><?= htmlspecialchars($row['descuentoPromocion'] ?? '—', ENT_QUOTES, 'UTF-8') ?>%</td>
                    <td><span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span></td>
                    <td class="text-end">
                      <?php if ($esPendiente): ?>
                        <form action="auditoria-promociones.php?pagina=<?= $paginaActual ?>" method="POST" class="d-inline"
                          id="form-aprobar-<?= (int) $row['codPromocion'] ?>">
                          <input type="hidden" name="codPromocion" value="<?= (int) $row['codPromocion'] ?>">
                          <input type="hidden" name="accion" value="aprobar">
                          <button type="button" class="btn btn-sm btn-success"
                            data-confirm-form-id="form-aprobar-<?= (int) $row['codPromocion'] ?>"
                            data-confirm-text="¿Aprobar la promoción «<?= htmlspecialchars($row['descripcionPromocion'], ENT_QUOTES, 'UTF-8') ?>»?">
                            <i class="bi bi-check-lg" aria-hidden="true"></i> Aprobar
                          </button>
                        </form>
                        <form action="auditoria-promociones.php?pagina=<?= $paginaActual ?>" method="POST"
                          class="d-inline ms-1" id="form-rechazar-<?= (int) $row['codPromocion'] ?>">
                          <input type="hidden" name="codPromocion" value="<?= (int) $row['codPromocion'] ?>">
                          <input type="hidden" name="accion" value="rechazar">
                          <button type="button" class="btn btn-sm btn-danger"
                            data-confirm-form-id="form-rechazar-<?= (int) $row['codPromocion'] ?>"
                            data-confirm-text="¿Denegar la promoción «<?= htmlspecialchars($row['descripcionPromocion'], ENT_QUOTES, 'UTF-8') ?>»?">
                            <i class="bi bi-x-lg" aria-hidden="true"></i> Rechazar
                          </button>
                        </form>
                      <?php else: ?>
                        <span class="text-muted small">Sin acciones disponibles</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">
                    No hay promociones registradas.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Paginación -->
        <?php if ($totalPaginas > 1): ?>
          <nav aria-label="Paginación" class="mt-4">
            <ul class="pagination justify-content-center mb-0">
              <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>" <?= $paginaActual <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Anterior</a>
              </li>
              <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= $i === $paginaActual ? 'active' : '' ?>" <?= $i === $paginaActual ? 'aria-current="page"' : '' ?>>
                  <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
              <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
                <a class="page-link" href="?pagina=<?= $paginaActual + 1 ?>" <?= $paginaActual >= $totalPaginas ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Siguiente</a>
              </li>
            </ul>
          </nav>
        <?php endif; ?>

      </div>
    </div>
  </main>

  <!-- Modal reutilizable para confirmar acciones -->
  <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalConfirmacionLabel">Confirmar acción</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div id="modalConfirmacionTexto" class="text-muted">¿Confirmás?</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="modalConfirmacionAceptar">Confirmar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmFJ3d7CMK4xBqwJoKD21vBbVxy" crossorigin="anonymous"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const modalEl = document.getElementById('modalConfirmacion');
      const modalConfirmacion = new bootstrap.Modal(modalEl);
      const textoEl = document.getElementById('modalConfirmacionTexto');
      const aceptarBtn = document.getElementById('modalConfirmacionAceptar');
      let formIdPendiente = null;

      document.querySelectorAll('[data-confirm-form-id]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          formIdPendiente = btn.getAttribute('data-confirm-form-id');
          textoEl.textContent = btn.getAttribute('data-confirm-text') || '¿Confirmás?';
          modalConfirmacion.show();
        });
      });

      aceptarBtn.addEventListener('click', function () {
        if (!formIdPendiente) return;
        const form = document.getElementById(formIdPendiente);
        if (form) form.submit();
      });

      modalEl.addEventListener('hidden.bs.modal', function () {
        formIdPendiente = null;
      });
    });
  </script>

  <?php include '../Footer/footer.php'; ?>

</body>

</html>