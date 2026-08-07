<?php
include '../../config/conexion.php';
require_once '../../config/requiere_ceo.php';
$codUsuario = (int) $_SESSION['codUsuario'];
$resU = mysqli_query($link, "SELECT u.codAerolinea, a.nombreAerolinea, a.codigoIATA
                              FROM USUARIOS u
                              JOIN AEROLINEAS a ON u.codAerolinea = a.codAerolinea
                              WHERE u.codUsuario = $codUsuario");
$ceoData = $resU ? mysqli_fetch_assoc($resU) : null;
if (!$ceoData || !$ceoData['codAerolinea']) {
  header('Location: ../LandPage/LandUsuarioRegistrado.php');
  exit;
}
$codAerolinea = (int) $ceoData['codAerolinea'];
$nombreAerolinea = htmlspecialchars($ceoData['nombreAerolinea'], ENT_QUOTES, 'UTF-8');
$codigoIATA = htmlspecialchars($ceoData['codigoIATA'], ENT_QUOTES, 'UTF-8');
$codPromocion = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($codPromocion <= 0) {
  header('Location: promociones-index.php');
  exit;
}
$resP = mysqli_query(
  $link,
  "SELECT codPromocion, descripcionPromocion, descuentoPromocion, estadoPromocion
     FROM PROMOCIONES
     WHERE codPromocion = $codPromocion AND codAerolinea = $codAerolinea"
);
$promo = ($resP && mysqli_num_rows($resP) > 0) ? mysqli_fetch_assoc($resP) : null;
if (!$promo) {
  header('Location: promociones-index.php');
  exit;
}
$error = '';
$campoError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $descripcion = trim($_POST['descripcionPromocion'] ?? '');
  $descuento = (float) ($_POST['descuentoPromocion'] ?? 0);
  if ($descripcion === '') {
    $error = 'La descripción es obligatoria.';
    $campoError = 'descripcionPromocion';
  } elseif ($descuento <= 0 || $descuento > 100) {
    $error = 'El descuento debe ser un número entre 1 y 100.';
    $campoError = 'descuentoPromocion';
  } elseif (
    $descripcion === $promo['descripcionPromocion'] &&
    (float) $descuento == (float) $promo['descuentoPromocion']
  ) {
    $error = 'No realizaste ningún cambio.';
  } else {
    $stmtUpd = mysqli_prepare(
      $link,
      "UPDATE PROMOCIONES
         SET descripcionPromocion = ?,
             descuentoPromocion   = ?,
             estadoPromocion      = 'pendiente'
         WHERE codPromocion = ?
           AND codAerolinea = ?"
    );
    mysqli_stmt_bind_param($stmtUpd, 'sdii', $descripcion, $descuento, $codPromocion, $codAerolinea);
    $upd = mysqli_stmt_execute($stmtUpd);

    if ($upd) {
      header('Location: promociones-index.php?actualizada=1');
      exit;
    } else {
      $error = 'Error al actualizar la promoción. Intentá nuevamente.';
    }
  }
  $promo['descripcionPromocion'] = $_POST['descripcionPromocion'] ?? $promo['descripcionPromocion'];
  $promo['descuentoPromocion'] = $_POST['descuentoPromocion'] ?? $promo['descuentoPromocion'];
}
$codFmt = str_pad((string) $promo['codPromocion'], 3, '0', STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Promoción | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <p class="text-primary text-uppercase small fw-semibold mb-1">
              <i class="bi bi-tag-fill me-1" aria-hidden="true"></i>CEO · Promociones
            </p>
            <h1 class="h3 fw-bold mb-0">Editar Promoción <small class="text-secondary fs-5">#<?= $codFmt ?></small></h1>
            <p class="text-secondary small mb-0">Al guardar, la promoción volverá a estado <strong>Pendiente</strong>
              para re-aprobación.</p>
          </div>
          <a href="promociones-index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver al listado
          </a>
        </div>

        <div class="mb-4">
          <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
            <i class="bi bi-building me-1" aria-hidden="true"></i>
            Aerolínea: <strong><?= $nombreAerolinea ?></strong> · IATA: <?= $codigoIATA ?>
          </span>
        </div>

        <?php if ($error !== ''): ?>
          <div class="alert alert-danger mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"
              aria-hidden="true"></i><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <?php if ($promo['estadoPromocion'] === 'aprobada'): ?>
          <div class="alert alert-warning mb-4" role="note">
            <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>
            Esta promoción está <strong>aprobada y activa</strong>. Si la editás, volverá a estado
            <strong>Pendiente</strong> y dejará de aplicarse hasta que el Administrador la re-apruebe.
          </div>
        <?php endif; ?>

        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4 p-md-5">

                <form action="promociones-mod.php?id=<?= $codPromocion ?>" method="post"
                  aria-label="Formulario de edición de promoción">

                  <div class="mb-4">
                    <label for="descripcionPromocion" class="form-label fw-semibold">
                      Descripción <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="descripcionPromocion" name="descripcionPromocion" class="form-control<?= $campoError === 'descripcionPromocion' ? ' is-invalid' : '' ?>
<?php if ($campoError === 'descripcionPromocion'): ?>
<div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>"
                      maxlength="200" required
                      value="<?= htmlspecialchars($promo['descripcionPromocion'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                    <div class="form-text">Máximo 200 caracteres.</div>
                  </div>

                  <div class="mb-4">
                    <label for="descuentoPromocion" class="form-label fw-semibold">
                      Descuento (%) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <input type="number" id="descuentoPromocion" name="descuentoPromocion" class="form-control<?= $campoError === 'descuentoPromocion' ? ' is-invalid' : '' ?>
<?php if ($campoError === 'descuentoPromocion'): ?>
<div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>"
                        min="1" max="100" step="0.01" required
                        value="<?= htmlspecialchars((string) $promo['descuentoPromocion'], ENT_QUOTES, 'UTF-8') ?>" />
                      <span class="input-group-text">%</span>
                    </div>
                    <div class="form-text">Entre 1% y 100%.</div>
                  </div>

                  <div class="d-flex gap-2 justify-content-end">
                    <a href="promociones-index.php" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                      <i class="bi bi-floppy me-1" aria-hidden="true"></i>Guardar cambios
                    </button>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>

</html>