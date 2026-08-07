<?php
include '../../config/conexion.php';
require_once '../../config/requiere_ceo.php';
$codUsuario = (int)$_SESSION['codUsuario'];
$resU = mysqli_query($link, "SELECT u.codAerolinea, a.nombreAerolinea, a.codigoIATA
                              FROM USUARIOS u
                              JOIN AEROLINEAS a ON u.codAerolinea = a.codAerolinea
                              WHERE u.codUsuario = $codUsuario");
$ceoData = $resU ? mysqli_fetch_assoc($resU) : null;
$sinAerolinea = (!$ceoData || !$ceoData['codAerolinea']);
$mensajeExito = '';
$mensajeError = '';
$vuelos = [];
if (!$sinAerolinea) {
  $codAerolinea    = (int)$ceoData['codAerolinea'];
  $nombreAerolinea = htmlspecialchars($ceoData['nombreAerolinea'], ENT_QUOTES, 'UTF-8');
  $codigoIATA      = htmlspecialchars($ceoData['codigoIATA'], ENT_QUOTES, 'UTF-8');
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'eliminar') {
    $codVuelo = (int)($_POST['codVuelo'] ?? 0);
    if ($codVuelo > 0) {
      $resV = mysqli_query(
        $link,
        "SELECT codVuelo FROM VUELOS WHERE codVuelo = $codVuelo AND codAerolinea = $codAerolinea"
      );
      if ($resV && mysqli_num_rows($resV) > 0) {
        $resRes = mysqli_query(
          $link,
          "SELECT COUNT(*) AS total FROM RESERVAS
                     WHERE codVuelo = $codVuelo AND estadoReserva IN ('pendiente de pago','confirmada')"
        );
        $cntRes = (int)(mysqli_fetch_assoc($resRes)['total'] ?? 0);
        if ($cntRes > 0) {
          $mensajeError = 'No se puede eliminar: el vuelo tiene reservas activas.';
        } else {
          $del = mysqli_query($link, "DELETE FROM VUELOS WHERE codVuelo = $codVuelo AND codAerolinea = $codAerolinea");
          $mensajeExito = $del ? 'Vuelo eliminado correctamente.' : '';
          if (!$del) $mensajeError = 'Error al eliminar el vuelo.';
        }
      } else {
        $mensajeError = 'Vuelo no encontrado.';
      }
    }
  }
  $resVuelos = mysqli_query(
    $link,
    "SELECT codVuelo, origenVuelo, destinoVuelo, fechaSalidaVuelo, horaSalidaVuelo,
                precioVuelo, asientosDisponibles
         FROM VUELOS
         WHERE codAerolinea = $codAerolinea
         ORDER BY fechaSalidaVuelo ASC, horaSalidaVuelo ASC"
  );
  if ($resVuelos) {
    while ($v = mysqli_fetch_assoc($resVuelos)) $vuelos[] = $v;
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mis Vuelos | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5">
      <div class="container">

        <?php if ($sinAerolinea): ?>
          <div class="py-5 text-center">
            <i class="bi bi-building-x fs-1 text-secondary d-block mb-3" aria-hidden="true"></i>
            <h2 class="h5 fw-bold mb-2">Sin aerolínea asociada</h2>
            <p class="text-secondary mb-4">Tu cuenta de CEO no está asociada a ninguna aerolínea.<br>Contactá al administrador para que te asigne una.</p>
            <a href="../LandPage/LandUsuarioRegistrado.php" class="btn btn-primary">
              <i class="bi bi-house-fill me-1" aria-hidden="true"></i>Ir al Inicio
            </a>
          </div>
        <?php else: ?>

          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              <p class="text-primary text-uppercase small fw-semibold mb-1">
                <i class="bi bi-airplane me-1" aria-hidden="true"></i>CEO · Vuelos
              </p>
              <h1 class="h3 fw-bold mb-0">Mis Vuelos</h1>
            </div>
            <a href="vuelos-create.php" class="btn btn-primary">
              <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Nuevo Vuelo
            </a>
          </div>

          <div class="mb-4">
            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
              <i class="bi bi-building me-1" aria-hidden="true"></i>
              Aerolínea: <strong><?= $nombreAerolinea ?></strong> · IATA: <?= $codigoIATA ?>
            </span>
          </div>

          <?php if (isset($_GET['creado'])): ?>
            <div class="alert alert-success mb-4" role="status">
              <i class="bi bi-check-circle me-2" aria-hidden="true"></i>Vuelo creado correctamente.
            </div>
          <?php endif; ?>
          <?php if (isset($_GET['actualizado'])): ?>
            <div class="alert alert-success mb-4" role="status">
              <i class="bi bi-check-circle me-2" aria-hidden="true"></i>Vuelo actualizado correctamente.
            </div>
          <?php endif; ?>
          <?php if ($mensajeExito !== ''): ?>
            <div class="alert alert-success mb-4" role="status">
              <i class="bi bi-check-circle me-2" aria-hidden="true"></i><?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>
          <?php if ($mensajeError !== ''): ?>
            <div class="alert alert-danger mb-4" role="alert">
              <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i><?= htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8') ?>
            </div>
          <?php endif; ?>

          <div class="card border-0 shadow-sm">
            <div class="card-body p-0">

              <?php if (empty($vuelos)): ?>
                <div class="p-5 text-center">
                  <i class="bi bi-airplane text-secondary fs-1 d-block mb-3" aria-hidden="true"></i>
                  <p class="text-secondary mb-3">Tu aerolínea no tiene vuelos cargados todavía.</p>
                  <a href="vuelos-create.php" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Crear primer vuelo
                  </a>
                </div>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                      <tr>
                        <th scope="col">Cód.</th>
                        <th scope="col">Origen</th>
                        <th scope="col">Destino</th>
                        <th scope="col">Fecha salida</th>
                        <th scope="col">Hora</th>
                        <th scope="col" class="text-end">Precio</th>
                        <th scope="col" class="text-center">Asientos</th>
                        <th scope="col" class="text-end">Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($vuelos as $v):
                        $codFmt   = str_pad((string)$v['codVuelo'], 4, '0', STR_PAD_LEFT);
                        $fechaFmt = date('d/m/Y', strtotime($v['fechaSalidaVuelo']));
                        $horaFmt  = substr($v['horaSalidaVuelo'], 0, 5);
                        $esPasado = $v['fechaSalidaVuelo'] < date('Y-m-d');
                      ?>
                        <tr class="<?= $esPasado ? 'text-secondary' : '' ?>">
                          <td><small class="text-secondary"><?= $codFmt ?></small></td>
                          <td><?= htmlspecialchars($v['origenVuelo'], ENT_QUOTES, 'UTF-8') ?></td>
                          <td><?= htmlspecialchars($v['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?></td>
                          <td>
                            <?= $fechaFmt ?>
                            <?php if ($esPasado): ?>
                              <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle ms-1">Pasado</span>
                            <?php endif; ?>
                          </td>
                          <td><?= $horaFmt ?> hs</td>
                          <td class="text-end">ARS <?= number_format((float)$v['precioVuelo'], 0, ',', '.') ?></td>
                          <td class="text-center">
                            <?php
                            $asientos = (int)$v['asientosDisponibles'];
                            $clase = $asientos > 20 ? 'text-success' : ($asientos > 5 ? 'text-warning' : 'text-danger');
                            ?>
                            <span class="<?= $clase ?> fw-semibold"><?= $asientos ?></span>
                          </td>
                          <td class="text-end">
                            <a href="vuelos-mod.php?id=<?= (int)$v['codVuelo'] ?>"
                              class="btn btn-sm btn-outline-secondary me-1">
                              <i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                              data-bs-toggle="modal" data-bs-target="#modalEliminar"
                              data-cod="<?= (int)$v['codVuelo'] ?>"
                              data-desc="<?= htmlspecialchars($v['origenVuelo'] . ' → ' . $v['destinoVuelo'] . ' · ' . $fechaFmt, ENT_QUOTES, 'UTF-8') ?>">
                              <i class="bi bi-trash me-1" aria-hidden="true"></i>Eliminar
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>

            </div>
          </div>

        <?php endif; ?>
      </div>
    </section>
  </main>

  <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog"
    aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header border-0">
          <h2 class="modal-title h5 fw-bold" id="modalEliminarLabel">
            <i class="bi bi-exclamation-triangle-fill text-danger me-2" aria-hidden="true"></i>Confirmar eliminación
          </h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p class="mb-1">¿Estás seguro de que querés eliminar el vuelo:</p>
          <p class="fw-bold" id="modalDescVuelo"></p>
          <p class="text-danger small mb-0">Esta acción no se puede deshacer. No podrás eliminar vuelos con reservas activas.</p>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <form method="post" action="vuelos-index.php">
            <input type="hidden" name="accion" value="eliminar" />
            <input type="hidden" name="codVuelo" id="inputCodVuelo" value="" />
            <button type="submit" class="btn btn-danger">
              <i class="bi bi-trash me-1" aria-hidden="true"></i>Sí, eliminar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include '../Footer/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmEtu5PinQ2N9b+tQNBqAKFGj7EV" crossorigin="anonymous"></script>
  <script>
    document.getElementById('modalEliminar').addEventListener('show.bs.modal', function(e) {
      const btn = e.relatedTarget;
      document.getElementById('inputCodVuelo').value = btn.dataset.cod;
      document.getElementById('modalDescVuelo').textContent = btn.dataset.desc;
    });
  </script>

</body>

</html>