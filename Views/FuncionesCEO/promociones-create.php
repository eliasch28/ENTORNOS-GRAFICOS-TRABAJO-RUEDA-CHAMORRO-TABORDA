<?php
include '../../config/conexion.php';
require_once '../../config/requiere_ceo.php';
$codUsuario = (int) $_SESSION['codUsuario'];
$resU = mysqli_query($link, "SELECT codAerolinea FROM USUARIOS WHERE codUsuario = $codUsuario");
$ceoData = $resU ? mysqli_fetch_assoc($resU) : null;
if (!$ceoData || !$ceoData['codAerolinea']) {
  header('Location: ../LandPage/LandUsuarioRegistrado.php');
  exit;
}
$codAerolinea = (int) $ceoData['codAerolinea'];
$error = '';
$campoError = '';
$exito = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $descripcion = trim($_POST['descripcionPromocion'] ?? '');
  $descuento = (float) ($_POST['descuentoPromocion'] ?? 0);
  if ($descripcion === '') {
    $error = 'La descripción es obligatoria.';
    $campoError = 'descripcionPromocion';
  } elseif ($descuento <= 0 || $descuento > 100) {
    $error = 'El descuento debe ser un número entre 1 y 100.';
    $campoError = 'descuentoPromocion';
  } else {
    $resCheck = mysqli_query(
      $link,
      "SELECT codPromocion FROM PROMOCIONES
             WHERE codAerolinea = $codAerolinea
               AND estadoPromocion IN ('pendiente', 'aprobada')"
    );
    if ($resCheck && mysqli_num_rows($resCheck) > 0) {
      $error = 'Ya existe una promoción pendiente o aprobada para tu aerolínea. Solo puede haber una activa a la vez.';
    } else {
      $stmtIns = mysqli_prepare(
        $link,
        "INSERT INTO PROMOCIONES (descripcionPromocion, descuentoPromocion, estadoPromocion, codAerolinea)
                 VALUES (?, ?, 'pendiente', ?)"
      );
      mysqli_stmt_bind_param($stmtIns, 'sdi', $descripcion, $descuento, $codAerolinea);
      $ins = mysqli_stmt_execute($stmtIns);
      if ($ins) {
        header('Location: promociones-index.php?creada=1');
        exit;
      } else {
        $error = 'Error al guardar la promoción. Intentá nuevamente.';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nueva Promoción | VuelaLibre – UTN FRR</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <p class="text-primary text-uppercase small fw-semibold mb-1">
              <i class="bi bi-tag-fill me-1" aria-hidden="true"></i>CEO · Promociones
            </p>
            <h1 class="h3 fw-bold mb-0">Nueva Promoción</h1>
            <p class="text-secondary small mb-0">La promoción quedará pendiente hasta que el Administrador la apruebe.
            </p>
          </div>
          <a href="promociones-index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver al listado
          </a>
        </div>

        <?php if ($error !== ''): ?>
          <div class="alert alert-danger mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"
              aria-hidden="true"></i><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4 p-md-5">

                <form action="promociones-create.php" method="post" aria-label="Formulario de nueva promoción">

                  <div class="mb-4">
                    <label for="descripcionPromocion" class="form-label fw-semibold">
                      Descripción <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="descripcionPromocion" name="descripcionPromocion" class="form-control<?= $campoError === 'descripcionPromocion' ? ' is-invalid' : '' ?>
<?php if ($campoError === 'descripcionPromocion'): ?>
<div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>"
                      placeholder="Ej: 20% de descuento en vuelos nacionales" maxlength="200" required
                      value="<?= htmlspecialchars($_POST['descripcionPromocion'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
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
                        min="1" max="100" step="0.01" required placeholder="Ej: 15"
                        value="<?= htmlspecialchars($_POST['descuentoPromocion'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                      <span class="input-group-text">%</span>
                    </div>
                    <div class="form-text">Entre 1% y 100%.</div>
                  </div>

                  <div class="alert alert-info py-2 small mb-4" role="note">
                    <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                    Solo puede existir <strong>una promoción pendiente o aprobada</strong> por aerolínea a la vez.
                    Esta promoción quedará en estado <strong>Pendiente</strong> hasta su aprobación.
                  </div>

                  <div class="d-flex gap-2 justify-content-end">
                    <button type="button" id="btnLimpiar" class="btn btn-outline-secondary">Limpiar</button>
                    <button type="submit" class="btn btn-success">
                      <i class="bi bi-send me-1" aria-hidden="true"></i>Enviar para aprobación
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
  <script>
    document.getElementById('btnLimpiar').addEventListener('click', function () {
      document.getElementById('descripcionPromocion').value = '';
      document.getElementById('descuentoPromocion').value = '';
    });
  </script>
</body>

</html>