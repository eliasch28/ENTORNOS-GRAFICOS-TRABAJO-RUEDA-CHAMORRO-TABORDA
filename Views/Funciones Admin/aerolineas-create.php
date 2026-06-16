<?php
include '../../config/conexion.php';
require_once '../../config/requiere_admin.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombreAerolinea      = trim($_POST['nombreAerolinea'] ?? '');
  $codigoIATA           = strtoupper(trim($_POST['codigoIATA'] ?? ''));
  $descripcionAerolinea = trim($_POST['descripcionAerolinea'] ?? '');
  $codigoPais           = strtoupper(trim($_POST['codigoPais'] ?? ''));

  // Validaciones del lado del servidor
  if ($nombreAerolinea === '') {
    $error = 'El nombre de la aerolínea es obligatorio.';
  } elseif ($codigoIATA === '') {
    $error = 'El código IATA es obligatorio.';
  } elseif (!preg_match('/^[A-Z]{2,3}$/', $codigoIATA)) {
    $error = 'El código IATA debe tener 2 o 3 letras.';
  } elseif ($descripcionAerolinea === '') {
    $error = 'La descripción de la aerolínea es obligatoria.';
  } elseif ($codigoPais === '') {
    $error = 'El código de país es obligatorio.';
  } elseif (!preg_match('/^[A-Z]{2,3}$/', $codigoPais)) {
    $error = 'El código de país debe tener 2 o 3 letras.';
  } elseif (mb_strlen($nombreAerolinea) > 100) {
    $error = 'El nombre de la aerolínea no puede superar los 100 caracteres.';
  } elseif (mb_strlen($descripcionAerolinea) > 200) {
    $error = 'La descripción no puede superar los 200 caracteres.';
  } else {
    // Verificar que no exista otra aerolínea con el mismo código IATA
    $nombreEsc   = mysqli_real_escape_string($link, $nombreAerolinea);
    $iataEsc     = mysqli_real_escape_string($link, $codigoIATA);
    $descEsc     = mysqli_real_escape_string($link, $descripcionAerolinea);
    $paisEsc     = mysqli_real_escape_string($link, $codigoPais);

    $checkIata = mysqli_query($link, "SELECT codAerolinea FROM AEROLINEAS WHERE codigoIATA = '$iataEsc'");
    if ($checkIata && mysqli_num_rows($checkIata) > 0) {
      $error = 'Ya existe una aerolínea registrada con ese código IATA.';
    } else {
      $query = "INSERT INTO AEROLINEAS (nombreAerolinea, codigoIATA, descripcionAerolinea, codPais)
                      VALUES ('$nombreEsc', '$iataEsc', '$descEsc', '$paisEsc')";

      if (mysqli_query($link, $query)) {
        $exito = 'La aerolínea se registró correctamente.';
        // Limpiar campos del formulario después del alta exitosa
        $nombreAerolinea = $codigoIATA = $descripcionAerolinea = $codigoPais = '';
      } else {
        $error = 'Ocurrió un error al registrar la aerolínea. Intentalo nuevamente.';
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
  <title>Nueva Aerolínea | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1" class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h2 mb-1">Alta de Nueva Aerolínea</h1>
        <p class="text-secondary mb-0">Completá los datos para registrar una nueva aerolínea asociada.</p>
      </div>
      <a href="aerolineas-index.php" class="btn btn-outline-secondary">
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

        <form action="aerolineas-create.php" method="POST" class="card border-0 shadow-sm p-4 p-md-5"
          aria-label="Formulario de alta de nueva aerolínea">

          <div class="mb-3">
            <label for="nombreAerolinea" class="form-label fw-semibold">
              <i class="bi bi-building me-1" aria-hidden="true"></i>
              Nombre de la aerolínea
              <span class="text-danger" aria-hidden="true">*</span>
            </label>
            <input
              type="text"
              id="nombreAerolinea"
              name="nombreAerolinea"
              class="form-control"
              required
              maxlength="100"
              value="<?= htmlspecialchars($nombreAerolinea ?? '', ENT_QUOTES, 'UTF-8') ?>"
              aria-required="true" />
            <div class="form-text">Máximo 100 caracteres.</div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="codigoIATA" class="form-label fw-semibold">
                <i class="bi bi-airplane me-1" aria-hidden="true"></i>
                Código IATA
                <span class="text-danger" aria-hidden="true">*</span>
              </label>
              <input
                type="text"
                id="codigoIATA"
                name="codigoIATA"
                class="form-control text-uppercase"
                required
                maxlength="3"
                pattern="[A-Za-z]{2,3}"
                title="Ingresá un código IATA de 2 o 3 letras"
                value="<?= htmlspecialchars($codigoIATA ?? '', ENT_QUOTES, 'UTF-8') ?>"
                aria-required="true" />
              <div class="form-text">
                Código de 2 o 3 letras asignado por la IATA a la aerolínea (por ejemplo, <strong>AR</strong>).
              </div>
            </div>

            <div class="col-md-6">
              <label for="codigoPais" class="form-label fw-semibold">
                <i class="bi bi-flag me-1" aria-hidden="true"></i>
                Código de país
                <span class="text-danger" aria-hidden="true">*</span>
              </label>
              <input
                type="text"
                id="codigoPais"
                name="codigoPais"
                class="form-control text-uppercase"
                required
                maxlength="3"
                pattern="[A-Za-z]{2,3}"
                title="Ingresá un código de país de 2 o 3 letras (ej. AR, CL)"
                value="<?= htmlspecialchars($codigoPais ?? '', ENT_QUOTES, 'UTF-8') ?>"
                aria-required="true" />
              <div class="form-text">
                Código ISO del país (2 o 3 letras), por ejemplo <strong>AR</strong> para Argentina.
              </div>
            </div>
          </div>

          <div class="mb-3 mt-3">
            <label for="descripcionAerolinea" class="form-label fw-semibold">
              <i class="bi bi-card-text me-1" aria-hidden="true"></i>
              Descripción de la aerolínea
              <span class="text-danger" aria-hidden="true">*</span>
            </label>
            <textarea
              id="descripcionAerolinea"
              name="descripcionAerolinea"
              class="form-control"
              rows="3"
              maxlength="200"
              required
              aria-required="true"><?= htmlspecialchars($descripcionAerolinea ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            <div class="form-text">Máximo 200 caracteres.</div>
          </div>

          <p class="text-secondary small mb-3">
            <span class="text-danger" aria-hidden="true">*</span> Todos los campos obligatorios deben estar completos.
          </p>

          <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2">
            <button type="reset" class="btn btn-light me-md-2">
              <i class="bi bi-eraser me-1" aria-hidden="true"></i>Limpiar campos
            </button>
            <button type="submit" class="btn btn-success">
              <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Guardar Aerolínea
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>

</html>