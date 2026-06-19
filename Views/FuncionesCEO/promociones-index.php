<?php
include '../../config/conexion.php';
require_once '../../config/requiere_ceo.php';

$codUsuario = (int)$_SESSION['codUsuario'];

$resU = mysqli_query($link, "SELECT codAerolinea FROM USUARIOS WHERE codUsuario = $codUsuario");
$ceoData = $resU ? mysqli_fetch_assoc($resU) : null;
if (!$ceoData || !$ceoData['codAerolinea']) {
    header('Location: ../LandPage/LandUsuarioRegistrado.php');
    exit;
}
$codAerolinea = (int)$ceoData['codAerolinea'];

$mensajeExito = '';
$mensajeError = '';

// Eliminar promoción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $codPromocion = (int)($_POST['codPromocion'] ?? 0);
    if ($codPromocion > 0) {
        // Verificar que la promo pertenece a la aerolínea del CEO
        $resV = mysqli_query($link,
            "SELECT codPromocion FROM PROMOCIONES
             WHERE codPromocion = $codPromocion AND codAerolinea = $codAerolinea");
        if ($resV && mysqli_num_rows($resV) > 0) {
            $del = mysqli_query($link, "DELETE FROM PROMOCIONES WHERE codPromocion = $codPromocion");
            $mensajeExito = $del ? 'Promoción eliminada correctamente.' : 'Error al eliminar la promoción.';
            if (!$del) $mensajeError = 'Error al eliminar la promoción.';
        } else {
            $mensajeError = 'Promoción no encontrada.';
        }
    }
}

// Consultar promos de esta aerolínea
$resPromos = mysqli_query($link,
    "SELECT codPromocion, descripcionPromocion, descuentoPromocion, estadoPromocion
     FROM PROMOCIONES
     WHERE codAerolinea = $codAerolinea
     ORDER BY FIELD(estadoPromocion, 'aprobada', 'pendiente', 'denegada'), codPromocion DESC");
$promos = [];
if ($resPromos) {
    while ($p = mysqli_fetch_assoc($resPromos)) $promos[] = $p;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mis Promociones | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1">
  <section class="py-5">
    <div class="container">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-tag-fill me-1"></i>CEO · Promociones
          </p>
          <h1 class="h3 fw-bold mb-0">Mis Promociones</h1>
          <p class="text-secondary small mb-0">Gestioná las promociones de tu aerolínea.</p>
        </div>
        <a href="promociones-create.php" class="btn btn-primary">
          <i class="bi bi-plus-lg me-1"></i>Nueva Promoción
        </a>
      </div>

      <?php if (isset($_GET['creada'])): ?>
        <div class="alert alert-success mb-4" role="status">
          <i class="bi bi-check-circle me-2"></i>
          Promoción enviada correctamente. Quedó en estado <strong>Pendiente</strong> a la espera de aprobación.
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['actualizada'])): ?>
        <div class="alert alert-success mb-4" role="status">
          <i class="bi bi-check-circle me-2"></i>
          Promoción actualizada. Volvió a estado <strong>Pendiente</strong> para re-aprobación.
        </div>
      <?php endif; ?>

      <?php if ($mensajeExito !== ''): ?>
        <div class="alert alert-success mb-4" role="status">
          <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <?php if ($mensajeError !== ''): ?>
        <div class="alert alert-danger mb-4" role="alert">
          <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

          <?php if (empty($promos)): ?>
            <div class="p-5 text-center">
              <i class="bi bi-tag text-secondary fs-1 d-block mb-3"></i>
              <p class="text-secondary mb-3">Tu aerolínea no tiene promociones cargadas todavía.</p>
              <a href="promociones-create.php" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Crear primera promoción
              </a>
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">Cód.</th>
                    <th scope="col">Descripción</th>
                    <th scope="col" class="text-center">Descuento</th>
                    <th scope="col" class="text-center">Estado</th>
                    <th scope="col" class="text-end">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($promos as $p):
                    $codFmt = str_pad((string)$p['codPromocion'], 3, '0', STR_PAD_LEFT);
                    switch ($p['estadoPromocion']) {
                      case 'aprobada':
                        $badgeClass = 'bg-success-subtle text-success border border-success-subtle';
                        $badgeIcon  = 'bi-check-circle-fill';
                        $badgeLabel = 'Aprobada';
                        break;
                      case 'denegada':
                        $badgeClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                        $badgeIcon  = 'bi-x-circle-fill';
                        $badgeLabel = 'Denegada';
                        break;
                      default:
                        $badgeClass = 'bg-warning-subtle text-warning border border-warning-subtle';
                        $badgeIcon  = 'bi-hourglass-split';
                        $badgeLabel = 'Pendiente';
                    }
                  ?>
                  <tr>
                    <td><small class="text-secondary"><?= $codFmt ?></small></td>
                    <td><?= htmlspecialchars($p['descripcionPromocion'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-center">
                      <span class="fw-bold text-success"><?= (int)$p['descuentoPromocion'] ?>%</span>
                    </td>
                    <td class="text-center">
                      <span class="badge <?= $badgeClass ?>">
                        <i class="bi <?= $badgeIcon ?> me-1"></i><?= $badgeLabel ?>
                      </span>
                    </td>
                    <td class="text-end">
                      <a href="promociones-mod.php?id=<?= (int)$p['codPromocion'] ?>"
                         class="btn btn-sm btn-outline-secondary me-1">
                        <i class="bi bi-pencil me-1"></i>Editar
                      </a>
                      <button type="button"
                              class="btn btn-sm btn-outline-danger"
                              data-bs-toggle="modal"
                              data-bs-target="#modalEliminar"
                              data-cod="<?= (int)$p['codPromocion'] ?>"
                              data-desc="<?= htmlspecialchars($p['descripcionPromocion'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <i class="bi bi-trash me-1"></i>Eliminar
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

    </div>
  </section>
</main>

<!-- Modal confirmación de eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1"
     aria-labelledby="modalEliminarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0">
        <h2 class="modal-title h5 fw-bold" id="modalEliminarLabel">
          <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Confirmar eliminación
        </h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p class="mb-1">¿Estás seguro de que querés eliminar la promoción:</p>
        <p class="fw-bold" id="modalDescripcion"></p>
        <p class="text-danger small mb-0">Esta acción no se puede deshacer.</p>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <form method="post" action="promociones-index.php" id="formEliminar">
          <input type="hidden" name="accion" value="eliminar"/>
          <input type="hidden" name="codPromocion" id="inputCodPromocion" value=""/>
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash me-1"></i>Sí, eliminar
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
    document.getElementById('inputCodPromocion').value = btn.dataset.cod;
    document.getElementById('modalDescripcion').textContent = btn.dataset.desc || '(sin descripción)';
  });
</script>

</body>
</html>
