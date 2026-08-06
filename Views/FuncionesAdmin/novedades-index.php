<?php
include '../../config/conexion.php';
require_once '../../config/requiere_admin.php';
$mensajeExito = '';
$mensajeError = '';
$hoy = date('Y-m-d');
function estadoNovedadAdmin(string $fechaPub, string $fechaExp, string $hoy): array
{
  if ($hoy < $fechaPub) {
    return ['Próxima', 'bg-warning text-dark'];
  }
  if ($hoy > $fechaExp) {
    return ['Caducada', 'bg-secondary'];
  }
  return ['Activa', 'bg-success'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $codNovedad = isset($_POST['codNovedad']) ? (int) $_POST['codNovedad'] : 0;
  if ($codNovedad > 0) {
    $query = "DELETE FROM NOVEDADES WHERE codNovedad = $codNovedad";
    if (mysqli_query($link, $query) && mysqli_affected_rows($link) > 0) {
      $mensajeExito = 'La novedad fue eliminada correctamente.';
    } else {
      $mensajeError = 'No se pudo eliminar la novedad.';
    }
  } else {
    $mensajeError = 'Solicitud inválida.';
  }
}
$porPagina = 10;
$paginaActual = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
$offset = ($paginaActual - 1) * $porPagina;
$sqlTotal = "SELECT COUNT(*) AS total FROM NOVEDADES";
$resTotal = mysqli_query($link, $sqlTotal);
$total = mysqli_fetch_assoc($resTotal)['total'] ?? 0;
$totalPaginas = max(1, (int) ceil($total / $porPagina));
$sqlNovedades = "SELECT codNovedad, textoNovedad, fechaPublicacionNovedad, fechaExpiracionNovedad
                 FROM NOVEDADES
                 ORDER BY fechaPublicacionNovedad DESC
                 LIMIT $porPagina OFFSET $offset";
$resNovedades = mysqli_query($link, $sqlNovedades);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Novedades | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1" class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h2">Gestión de Novedades</h1>
      <a href="novedades-create.php" class="btn btn-primary">+ Nueva Novedad</a>
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
                <th scope="col">Texto del Anuncio</th>
                <th scope="col">Fecha Publicación</th>
                <th scope="col">Fecha Expiración</th>
                <th scope="col">Estado</th>
                <th scope="col" class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($resNovedades && mysqli_num_rows($resNovedades) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resNovedades)):
                  [$estadoLabel, $estadoClass] = estadoNovedadAdmin(
                    $row['fechaPublicacionNovedad'],
                    $row['fechaExpiracionNovedad'],
                    $hoy
                  );
                  $textoCorto = mb_strlen($row['textoNovedad']) > 60
                    ? mb_substr($row['textoNovedad'], 0, 60) . '…'
                    : $row['textoNovedad'];
                  $fechaPubFmt = date('d/m/Y', strtotime($row['fechaPublicacionNovedad']));
                  $fechaExpFmt = date('d/m/Y', strtotime($row['fechaExpiracionNovedad']));
                  ?>
                  <tr>
                    <th scope="row"><?= (int) $row['codNovedad'] ?></th>
                    <td title="<?= htmlspecialchars($row['textoNovedad'], ENT_QUOTES, 'UTF-8') ?>">
                      <?= htmlspecialchars($textoCorto, ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td><?= htmlspecialchars($fechaPubFmt, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($fechaExpFmt, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><span
                        class="badge <?= $estadoClass ?>"><?= htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8') ?></span>
                    </td>
                    <td class="text-end">
                      <a href="novedades-mod.php?id=<?= (int) $row['codNovedad'] ?>"
                        class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil" aria-hidden="true"></i> Editar
                      </a>
                      <form action="novedades-index.php?pagina=<?= $paginaActual ?>" method="POST" class="d-inline"
                        id="form-eliminar-<?= (int) $row['codNovedad'] ?>">
                        <input type="hidden" name="codNovedad" value="<?= (int) $row['codNovedad'] ?>">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                          data-confirm-form-id="form-eliminar-<?= (int) $row['codNovedad'] ?>"
                          data-confirm-text="¿Eliminar la novedad «<?= htmlspecialchars($textoCorto, ENT_QUOTES, 'UTF-8') ?>»? Esta acción no se puede deshacer.">
                          <i class="bi bi-trash" aria-hidden="true"></i> Eliminar
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">
                    No hay novedades registradas.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

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

  <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalConfirmacionLabel">Confirmar eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div id="modalConfirmacionTexto" class="text-muted">¿Confirmás?</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" id="modalConfirmacionAceptar">
            <i class="bi bi-trash me-1" aria-hidden="true"></i>Eliminar
          </button>
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
        if (!form) return;
        aceptarBtn.disabled = true;
        form.submit();
      });
      modalEl.addEventListener('hidden.bs.modal', function () {
        formIdPendiente = null;
      });
    });
  </script>

  <?php include '../Footer/footer.php'; ?>

</body>

</html>