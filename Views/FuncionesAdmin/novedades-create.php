<?php
include '../../config/conexion.php';
require_once '../../config/requiere_admin.php';
$error = '';
$exito = '';
$textoNovedad = '';
$fechaPublicacionNovedad = '';
$fechaExpiracionNovedad = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $textoNovedad = trim($_POST['textoNovedad'] ?? '');
  $fechaPublicacionNovedad = trim($_POST['fechaPublicacionNovedad'] ?? '');
  $fechaExpiracionNovedad = trim($_POST['fechaExpiracionNovedad'] ?? '');
  $hoy = date('Y-m-d');
  if ($textoNovedad === '') {
    $error = 'El texto de la novedad es obligatorio.';
  } elseif (mb_strlen($textoNovedad) > 200) {
    $error = 'El texto de la novedad no puede superar los 200 caracteres.';
  } elseif ($fechaPublicacionNovedad === '') {
    $error = 'La fecha de publicación es obligatoria.';
  } elseif ($fechaExpiracionNovedad === '') {
    $error = 'La fecha de expiración es obligatoria.';
  } elseif ($fechaPublicacionNovedad < $hoy) {
    $error = 'La fecha de publicación no puede ser anterior a la actual.';
  } elseif ($fechaExpiracionNovedad <= $fechaPublicacionNovedad) {
    $error = 'La fecha de expiración debe ser posterior a la fecha de publicación.';
  } else {
    $textoEsc = mysqli_real_escape_string($link, $textoNovedad);
    $pubEsc = mysqli_real_escape_string($link, $fechaPublicacionNovedad);
    $expEsc = mysqli_real_escape_string($link, $fechaExpiracionNovedad);
    $query = "INSERT INTO NOVEDADES (textoNovedad, fechaPublicacionNovedad, fechaExpiracionNovedad)
              VALUES ('$textoEsc', '$pubEsc', '$expEsc')";
    if (mysqli_query($link, $query)) {
      $exito = 'La novedad se registró correctamente.';
      $textoNovedad = $fechaPublicacionNovedad = $fechaExpiracionNovedad = '';
    } else {
      $error = 'Ocurrió un error al registrar la novedad. Intentalo nuevamente.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nueva Novedad | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1" class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h2 mb-1">Alta de Nueva Novedad</h1>
        <p class="text-secondary mb-0">Completá los datos para publicar un nuevo anuncio del sistema.</p>
      </div>
      <a href="novedades-index.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver al listado
      </a>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-7">

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger d-flex align-items-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>
            <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
          </div>
        <?php elseif (!empty($exito)): ?>
          <div class="alert alert-success d-flex align-items-start" role="status">
            <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>
            <div><?= htmlspecialchars($exito, ENT_QUOTES, 'UTF-8') ?></div>
          </div>
        <?php endif; ?>

        <form action="novedades-create.php" method="POST" class="card border-0 shadow-sm p-4 p-md-5"
          aria-label="Formulario de alta de nueva novedad">

          <div class="mb-3">
            <label for="textoNovedad" class="form-label fw-semibold">
              <i class="bi bi-megaphone me-1" aria-hidden="true"></i>
              Texto de la novedad
              <span class="text-danger" aria-hidden="true">*</span>
            </label>
            <textarea id="textoNovedad" name="textoNovedad" class="form-control" rows="4" maxlength="200" required
              aria-required="true"><?= htmlspecialchars($textoNovedad, ENT_QUOTES, 'UTF-8') ?></textarea>
            <div class="form-text">Máximo 200 caracteres.</div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="fechaPublicacionNovedad" class="form-label fw-semibold">
                <i class="bi bi-calendar-plus me-1" aria-hidden="true"></i>
                Fecha de publicación
                <span class="text-danger" aria-hidden="true">*</span>
              </label>
              <input type="date" id="fechaPublicacionNovedad" name="fechaPublicacionNovedad" class="form-control"
                required min="<?= date('Y-m-d') ?>" max="9999-12-31"
                value="<?= htmlspecialchars($fechaPublicacionNovedad, ENT_QUOTES, 'UTF-8') ?>" aria-required="true" />
              <div class="form-text">Debe ser de hoy en adelante.</div>
            </div>
            <div class="col-md-6">
              <label for="fechaExpiracionNovedad" class="form-label fw-semibold">
                <i class="bi bi-calendar-x me-1" aria-hidden="true"></i>
                Fecha de expiración
                <span class="text-danger" aria-hidden="true">*</span>
              </label>
              <input type="date" id="fechaExpiracionNovedad" name="fechaExpiracionNovedad" class="form-control" required max="9999-12-31"
                value="<?= htmlspecialchars($fechaExpiracionNovedad, ENT_QUOTES, 'UTF-8') ?>" aria-required="true" />
              <div class="form-text">Debe ser posterior a la fecha de publicación.</div>
            </div>
          </div>

          <p class="text-secondary small mb-3 mt-3">
            <span class="text-danger" aria-hidden="true">*</span> Todos los campos obligatorios deben estar completos.
          </p>

          <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2">
            <button type="button" id="btnLimpiar" class="btn btn-light me-md-2">
              <i class="bi bi-eraser me-1" aria-hidden="true"></i>Limpiar campos
            </button>
            <button type="submit" class="btn btn-success">
              <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Guardar Novedad
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <?php include '../Footer/footer.php'; ?>
  <script>
    const fechaPublicacion = document.getElementById('fechaPublicacionNovedad');
    const fechaExpiracion = document.getElementById('fechaExpiracionNovedad');

    fechaPublicacion.addEventListener('change', function () {
      const fecha = new Date(this.value);
      fecha.setDate(fecha.getDate() + 1);

      const minExp = fecha.toISOString().split('T')[0];

      fechaExpiracion.min = minExp;

      if (fechaExpiracion.value && fechaExpiracion.value < minExp) {
        fechaExpiracion.value = '';
      }
    });

    document.getElementById('btnLimpiar').addEventListener('click', function () {
      document.getElementById('textoNovedad').value = '';
      document.getElementById('fechaPublicacionNovedad').value = '';
      document.getElementById('fechaExpiracionNovedad').value = '';
    });
  </script>
</body>

</html>