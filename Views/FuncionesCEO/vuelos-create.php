<?php
include '../../config/conexion.php';
require_once '../../config/requiere_ceo.php';

$codUsuario = (int)$_SESSION['codUsuario'];

$resU = mysqli_query($link, "SELECT u.codAerolinea, a.nombreAerolinea, a.codigoIATA
                              FROM USUARIOS u
                              JOIN AEROLINEAS a ON u.codAerolinea = a.codAerolinea
                              WHERE u.codUsuario = $codUsuario");
$ceoData = $resU ? mysqli_fetch_assoc($resU) : null;
if (!$ceoData || !$ceoData['codAerolinea']) {
    header('Location: ../LandPage/LandUsuarioRegistrado.php');
    exit;
}
$codAerolinea   = (int)$ceoData['codAerolinea'];
$nombreAerolinea = htmlspecialchars($ceoData['nombreAerolinea'], ENT_QUOTES, 'UTF-8');
$codigoIATA      = htmlspecialchars($ceoData['codigoIATA'], ENT_QUOTES, 'UTF-8');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $origen   = trim($_POST['origenVuelo']      ?? '');
    $destino  = trim($_POST['destinoVuelo']     ?? '');
    $fecha    = trim($_POST['fechaSalidaVuelo'] ?? '');
    $hora     = trim($_POST['horaSalidaVuelo']  ?? '');
    $precio   = (float)($_POST['precioVuelo']         ?? 0);
    $asientos = (int)($_POST['asientosDisponibles']   ?? 0);

    if ($origen === '' || $destino === '') {
        $error = 'Origen y destino son obligatorios.';
    } elseif ($fecha === '' || $hora === '') {
        $error = 'Fecha y hora de salida son obligatorias.';
    } elseif ($fecha < date('Y-m-d')) {
        $error = 'La fecha de salida no puede ser en el pasado.';
    } elseif ($precio <= 0) {
        $error = 'El precio debe ser mayor a cero.';
    } elseif ($asientos <= 0) {
        $error = 'Los asientos disponibles deben ser al menos 1.';
    } else {
        $orig_esc = mysqli_real_escape_string($link, $origen);
        $dest_esc = mysqli_real_escape_string($link, $destino);
        $fech_esc = mysqli_real_escape_string($link, $fecha);
        $hora_esc = mysqli_real_escape_string($link, $hora);
        $ins = mysqli_query($link,
            "INSERT INTO VUELOS (codAerolinea, origenVuelo, destinoVuelo, fechaSalidaVuelo, horaSalidaVuelo, precioVuelo, asientosDisponibles)
             VALUES ($codAerolinea, '$orig_esc', '$dest_esc', '$fech_esc', '$hora_esc', $precio, $asientos)");
        if ($ins) {
            header('Location: vuelos-index.php?creado=1');
            exit;
        } else {
            $error = 'Error al guardar el vuelo. Intentá nuevamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nuevo Vuelo | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1">
  <section class="py-5">
    <div class="container">

      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-airplane me-1"></i>CEO · Vuelos
          </p>
          <h1 class="h3 fw-bold mb-0">Nuevo Vuelo</h1>
        </div>
        <a href="vuelos-index.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i>Volver al listado
        </a>
      </div>

      <div class="mb-4">
        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
          <i class="bi bi-building me-1"></i>
          Aerolínea: <strong><?= $nombreAerolinea ?></strong> · IATA: <?= $codigoIATA ?>
        </span>
      </div>

      <?php if ($error !== ''): ?>
        <div class="alert alert-danger mb-4" role="alert">
          <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">

              <form action="vuelos-create.php" method="post" aria-label="Formulario de nuevo vuelo">

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <label for="origenVuelo" class="form-label fw-semibold">
                      Origen <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="origenVuelo" name="origenVuelo"
                           class="form-control" placeholder="Ej: Buenos Aires"
                           maxlength="50" required
                           value="<?= htmlspecialchars($_POST['origenVuelo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
                  </div>
                  <div class="col-md-6">
                    <label for="destinoVuelo" class="form-label fw-semibold">
                      Destino <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="destinoVuelo" name="destinoVuelo"
                           class="form-control" placeholder="Ej: Madrid"
                           maxlength="50" required
                           value="<?= htmlspecialchars($_POST['destinoVuelo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
                  </div>
                </div>

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <label for="fechaSalidaVuelo" class="form-label fw-semibold">
                      Fecha de salida <span class="text-danger">*</span>
                    </label>
                    <input type="date" id="fechaSalidaVuelo" name="fechaSalidaVuelo"
                           class="form-control"
                           min="<?= date('Y-m-d') ?>" required
                           value="<?= htmlspecialchars($_POST['fechaSalidaVuelo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
                  </div>
                  <div class="col-md-6">
                    <label for="horaSalidaVuelo" class="form-label fw-semibold">
                      Hora de salida <span class="text-danger">*</span>
                    </label>
                    <input type="time" id="horaSalidaVuelo" name="horaSalidaVuelo"
                           class="form-control" required
                           value="<?= htmlspecialchars($_POST['horaSalidaVuelo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
                  </div>
                </div>

                <div class="row g-3 mb-4">
                  <div class="col-md-6">
                    <label for="precioVuelo" class="form-label fw-semibold">
                      Precio (ARS) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text">ARS</span>
                      <input type="number" id="precioVuelo" name="precioVuelo"
                             class="form-control" min="1" step="0.01" required
                             placeholder="Ej: 85000"
                             value="<?= htmlspecialchars($_POST['precioVuelo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="asientosDisponibles" class="form-label fw-semibold">
                      Asientos disponibles <span class="text-danger">*</span>
                    </label>
                    <input type="number" id="asientosDisponibles" name="asientosDisponibles"
                           class="form-control" min="1" required
                           placeholder="Ej: 120"
                           value="<?= htmlspecialchars($_POST['asientosDisponibles'] ?? '', ENT_QUOTES, 'UTF-8') ?>"/>
                  </div>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                  <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                  <button type="submit" class="btn btn-success">
                    <i class="bi bi-floppy me-1"></i>Guardar vuelo
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
