<?php
include '../../config/conexion.php';
require_once '../../config/requiere_admin.php';

$mensajeExito = '';
$mensajeError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $codUsuario = isset($_POST['codUsuario']) ? (int) $_POST['codUsuario'] : 0;
  $accion     = $_POST['accion'] ?? '';

  if ($codUsuario > 0 && in_array($accion, ['aprobar', 'rechazar'], true)) {
    if ($accion === 'aprobar') {
      $query = "UPDATE USUARIOS 
                SET verificado = 1 
                WHERE codUsuario = $codUsuario 
                  AND tipoUsuario = 'ceo de aerolinea' 
                  AND verificado = 0";
    } else {
      $query = "DELETE FROM USUARIOS 
                WHERE codUsuario = $codUsuario 
                  AND tipoUsuario = 'ceo de aerolinea' 
                  AND verificado = 0";
    }

    if (mysqli_query($link, $query) && mysqli_affected_rows($link) > 0) {
      if ($accion === 'aprobar') {
        $mensajeExito = 'El CEO fue aprobado exitosamente.';
      } else {
        $mensajeExito = 'La solicitud de CEO fue rechazada y eliminada correctamente.';
      }
    } else {
      $mensajeError = 'No se pudo procesar la acción seleccionada. Verificá el estado del usuario.';
    }
  } else {
    $mensajeError = 'Solicitud inválida.';
  }
}

$sqlSolicitudes = "SELECT 
                      u.codUsuario,
                      u.nombreUsuario,
                      u.emailUsuario,
                      u.telefonoUsuario,
                      a.nombreAerolinea,
                      a.codigoIATA
                   FROM USUARIOS u
                   LEFT JOIN AEROLINEAS a ON u.codAerolinea = a.codAerolinea
                   WHERE u.tipoUsuario = 'ceo de aerolinea'
                     AND u.verificado = 0
                   ORDER BY u.codUsuario ASC";
$solicitudesResult = mysqli_query($link, $sqlSolicitudes);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Solicitudes de CEO | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1" class="container my-5">
    <div class="mb-4">
      <h1 class="h2">Solicitudes de Registro — CEO</h1>
      <p class="text-muted">Usuarios que se registraron como CEO de Aerolínea y están pendientes de aprobación.</p>
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
                <th scope="col">Usuario</th>
                <th scope="col">Email</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Aerolínea</th>
                <th scope="col">Código IATA</th>
                <th scope="col" class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($solicitudesResult && mysqli_num_rows($solicitudesResult) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($solicitudesResult)): ?>
                  <tr>
                    <th scope="row"><?= (int) $row['codUsuario'] ?></th>
                    <td><?= htmlspecialchars($row['nombreUsuario'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['emailUsuario'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['telefonoUsuario'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['nombreAerolinea'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['codigoIATA'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-end">
                      <form action="solicitudes-ceo.php" method="post" class="d-inline" id="form-aprobar-<?= (int) $row['codUsuario'] ?>">
                        <input type="hidden" name="codUsuario" value="<?= (int) $row['codUsuario'] ?>">
                        <input type="hidden" name="accion" value="aprobar">
                        <button type="button" class="btn btn-sm btn-success"
                          data-confirm-form-id="form-aprobar-<?= (int) $row['codUsuario'] ?>"
                          data-confirm-text="¿Aprobar este CEO?">
                          <i class="bi bi-check-lg" aria-hidden="true"></i> Aprobar
                        </button>
                      </form>
                      <form action="solicitudes-ceo.php" method="post" class="d-inline ms-1" id="form-rechazar-<?= (int) $row['codUsuario'] ?>">
                        <input type="hidden" name="codUsuario" value="<?= (int) $row['codUsuario'] ?>">
                        <input type="hidden" name="accion" value="rechazar">
                        <button type="button" class="btn btn-sm btn-danger"
                          data-confirm-form-id="form-rechazar-<?= (int) $row['codUsuario'] ?>"
                          data-confirm-text="¿Rechazar y eliminar esta solicitud de CEO?">
                          <i class="bi bi-x-lg" aria-hidden="true"></i> Rechazar
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">
                    No hay solicitudes pendientes de CEO en este momento.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal reutilizable para confirmar acciones -->
  <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const modalEl = document.getElementById('modalConfirmacion');
      const modalConfirmacion = new bootstrap.Modal(modalEl);
      const textoEl = document.getElementById('modalConfirmacionTexto');
      const aceptarBtn = document.getElementById('modalConfirmacionAceptar');
      let formIdPendiente = null;

      document.querySelectorAll('[data-confirm-form-id]').forEach(function(btn) {
        btn.addEventListener('click', function() {
          formIdPendiente = btn.getAttribute('data-confirm-form-id');
          textoEl.textContent = btn.getAttribute('data-confirm-text') || '¿Confirmás?';
          modalConfirmacion.show();
        });
      });

      aceptarBtn.addEventListener('click', function() {
        if (!formIdPendiente) return;
        const form = document.getElementById(formIdPendiente);
        if (form) form.submit();
      });
    });
  </script>

  <?php include '../Footer/footer.php'; ?>

</body>

</html>