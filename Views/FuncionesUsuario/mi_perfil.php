<?php
include '../../config/conexion.php';
session_start();
if (!isset($_SESSION['codUsuario'])) {
  header('Location: ../FlujoSesion/login.php');
  exit;
}
$cod = (int) $_SESSION['codUsuario'];
$resultado = mysqli_query($link, "SELECT * FROM USUARIOS WHERE codUsuario = $cod");
$usuario = mysqli_fetch_assoc($resultado);
$error = '';
$campoError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombreUsuario = trim($_POST['nombreUsuario']);
  $apellidoUsuario = trim($_POST['apellidoUsuario']);
  $emailUsuario = trim($_POST['emailUsuario']);
  $telefonoUsuario = trim($_POST['telefonoUsuario']);
  $claveActual = $_POST['claveActual'];
  $claveNueva = $_POST['claveNueva'];
  $checkEmail = mysqli_query($link, "SELECT codUsuario FROM USUARIOS WHERE emailUsuario = '$emailUsuario' AND codUsuario != $cod");
  if (mysqli_num_rows($checkEmail) > 0) {
    $error = "El correo electrónico ya está en uso por otro usuario.";
    $campoError = "emailUsuario";
  } else {
    $telefonoOriginal = $usuario['telefonoUsuario'] ?? '';
    if ($telefonoUsuario !== $telefonoOriginal) {
      if (!preg_match('/^\+54\d{11}$|^\+598\d{8}$|^\+56\d{9}$|^\+595\d{9}$|^\+591\d{8}$/', $telefonoUsuario)) {
        $error = "El número de teléfono no es válido para el país seleccionado.";
        $campoError = "telefonoUsuario";
      }
    } else {
      $telefonoUsuario = $telefonoOriginal;
    }
    if ($error === '') {
      $query = "UPDATE USUARIOS SET nombreUsuario = '$nombreUsuario', apellidoUsuario = '$apellidoUsuario', emailUsuario = '$emailUsuario', telefonoUsuario = '$telefonoUsuario' WHERE codUsuario = $cod";
      if (!empty($claveActual) || !empty($claveNueva)) {
        if (empty($claveActual) || empty($claveNueva)) {
          $error = "Para cambiar la contraseña, completá ambos campos.";
          $campoError = "claveActual";
        } elseif (md5($claveActual) !== $usuario['claveUsuario']) {
          $error = "La contraseña actual es incorrecta.";
          $campoError = "claveActual";
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,32}$/', $claveNueva)) {
          $error = "La contraseña debe tener entre 8 y 32 caracteres e incluir al menos 1 mayúscula, 1 dígito y 1 carácter especial.";
          $campoError = "claveNueva";
        } elseif ($nombreUsuario === '' || $apellidoUsuario === '') {
          $error = "El nombre y el apellido son obligatorios.";
          $campoError = "nombreUsuario";
        } else {
          $query = "UPDATE USUARIOS SET nombreUsuario = '$nombreUsuario', apellidoUsuario = '$apellidoUsuario', emailUsuario = '$emailUsuario', telefonoUsuario = '$telefonoUsuario', claveUsuario = '" . md5($claveNueva) . "' WHERE codUsuario = $cod";
        }
      }
    }
    if ($error === '') {
      mysqli_query($link, $query);
      $resultado = mysqli_query($link, "SELECT * FROM USUARIOS WHERE codUsuario = $cod");
      $usuario = mysqli_fetch_assoc($resultado);
      $exito = "Perfil actualizado correctamente.";
    }
  }
}
// Parsear teléfono existente para pre-poblar el widget
$telExistente = $usuario['telefonoUsuario'] ?? '';
$paisParsed = '';
$numeroParsed = '';
foreach ([
  'UY' => ['code' => '598', 'digits' => 8],
  'PY' => ['code' => '595', 'digits' => 9],
  'BO' => ['code' => '591', 'digits' => 8],
  'AR' => ['code' => '54', 'digits' => 11],
  'CL' => ['code' => '56', 'digits' => 9],
] as $pk => $cfg) {
  if (preg_match('/^\+' . $cfg['code'] . '(\d{' . $cfg['digits'] . '})$/', $telExistente, $m)) {
    $paisParsed = $pk;
    $numeroParsed = $m[1];
    break;
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Gestioná tu perfil en VuelaLibre. Actualizá tu información personal." />
  <title>Mi Perfil | VuelaLibre – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="perfil-titulo">
      <div class="container">

        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-person-circle me-1" aria-hidden="true"></i>Mi cuenta
          </p>
          <h1 id="perfil-titulo" class="h3 fw-bold mb-0">Mi Perfil</h1>
          <p class="text-secondary">Visualizá y actualizá tu información personal.</p>
        </div>

        <div class="row g-4">

          <div class="col-lg-4">

            <div class="card border-0 shadow-sm p-4 mb-4">
              <div class="d-flex align-items-center gap-3 mb-4">
                <div class="avatar-circular bg-primary text-white" aria-hidden="true">
                  <?= htmlspecialchars(mb_strtoupper(mb_substr($usuario['nombreUsuario'], 0, 1)), ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div>
                  <div class="fw-bold fs-5">
                    <?= htmlspecialchars($usuario['nombreUsuario']) . ' ' . htmlspecialchars($usuario['apellidoUsuario']) ?>
                  </div>
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    <?= ucfirst($usuario['tipoUsuario']) ?>
                  </span>
                </div>
              </div>

              <div class="fila-dato-perfil">
                <span class="etiqueta-dato-perfil">
                  <i class="bi bi-hash me-1" aria-hidden="true"></i>Código
                </span>
                <span class="valor-dato-perfil"><?= $usuario['codUsuario'] ?></span>
              </div>
              <div class="fila-dato-perfil">
                <span class="etiqueta-dato-perfil">
                  <i class="bi bi-person me-1" aria-hidden="true"></i>Nombre
                </span>
                <span
                  class="valor-dato-perfil"><?= htmlspecialchars($usuario['nombreUsuario']) . ' ' . htmlspecialchars($usuario['apellidoUsuario']) ?></span>
              </div>
              <div class="fila-dato-perfil">
                <span class="etiqueta-dato-perfil">
                  <i class="bi bi-envelope me-1" aria-hidden="true"></i>Email
                </span>
                <span class="valor-dato-perfil"><?= htmlspecialchars($usuario['emailUsuario']) ?></span>
              </div>
              <div class="fila-dato-perfil">
                <span class="etiqueta-dato-perfil">
                  <i class="bi bi-telephone me-1" aria-hidden="true"></i>Teléfono
                </span>
                <span
                  class="valor-dato-perfil"><?= htmlspecialchars($usuario['telefonoUsuario'] ?? 'No especificado') ?></span>
              </div>
            </div>

            <div class="card border-0 shadow-sm p-4">
              <h2 class="h6 fw-bold text-primary mb-3">
                <i class="bi bi-grid-fill me-2" aria-hidden="true"></i>Accesos rápidos
              </h2>
              <nav aria-label="Accesos rápidos del usuario">
                <ul class="lista-mapa-sitio" role="list">
                  <?php if ($usuario['tipoUsuario'] === 'administrador'): ?>
                    <li>
                      <a href="../FuncionesAdmin/aerolineas-index.php">
                        <i class="bi bi-building me-2 text-primary" aria-hidden="true"></i>Aerolíneas
                      </a>
                    </li>
                    <li>
                      <a href="../FuncionesAdmin/auditoria-promociones.php">
                        <i class="bi bi-tag me-2 text-primary" aria-hidden="true"></i>Promociones
                      </a>
                    </li>
                    <li>
                      <a href="../FuncionesAdmin/novedades-index.php">
                        <i class="bi bi-megaphone me-2 text-primary" aria-hidden="true"></i>Novedades
                      </a>
                    </li>
                    <li>
                      <a href="../FuncionesAdmin/solicitudes-ceo.php">
                        <i class="bi bi-person-check me-2 text-primary" aria-hidden="true"></i>Solicitudes CEO
                      </a>
                    </li>
                    <li>
                      <a href="../FuncionesAdmin/global-reports.php">
                        <i class="bi bi-bar-chart-fill me-2 text-primary" aria-hidden="true"></i>Reportes
                      </a>
                    </li>
                  <?php elseif ($usuario['tipoUsuario'] === 'ceo de aerolinea'): ?>
                    <li>
                      <a href="../FuncionesCEO/vuelos-index.php">
                        <i class="bi bi-airplane me-2 text-primary" aria-hidden="true"></i>Mis Vuelos
                      </a>
                    </li>
                    <li>
                      <a href="../FuncionesCEO/promociones-index.php">
                        <i class="bi bi-tag me-2 text-primary" aria-hidden="true"></i>Mis Promociones
                      </a>
                    </li>
                    <li>
                      <a href="../FuncionesCEO/reportes-ceo.php">
                        <i class="bi bi-bar-chart-fill me-2 text-primary" aria-hidden="true"></i>Reportes
                      </a>
                    </li>
                  <?php else: ?>
                    <li>
                      <a href="buscar_vuelos.php">
                        <i class="bi bi-search me-2 text-primary" aria-hidden="true"></i>Buscar Vuelos
                      </a>
                    </li>
                    <li>
                      <a href="mis_reservas.php">
                        <i class="bi bi-calendar2-check me-2 text-primary" aria-hidden="true"></i>Mis Reservas
                      </a>
                    </li>
                    <li>
                      <a href="historial_compras.php">
                        <i class="bi bi-clock-history me-2 text-primary" aria-hidden="true"></i>Historial de Compras
                      </a>
                    </li>
                    <li>
                      <a href="novedades.php">
                        <i class="bi bi-megaphone me-2 text-primary" aria-hidden="true"></i>Ver Novedades
                      </a>
                    </li>
                  <?php endif; ?>
                </ul>
              </nav>
            </div>

          </div>

          <div class="col-lg-8">

            <?php if (!empty($exito)): ?>
              <div class="alert alert-success"><i class="bi bi-check-circle me-2" aria-hidden="true"></i><?= $exito ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"
                  aria-hidden="true"></i><?= $error ?></div>
            <?php endif; ?>

            <form action="mi_perfil.php" method="post" class="card border-0 shadow-sm p-4"
              aria-label="Formulario de edición de perfil">

              <p class="titulo-seccion-formulario">
                <i class="bi bi-person me-1" aria-hidden="true"></i>Datos personales
              </p>

              <div class="row g-3 mb-4">

                <div class="col-md-6">
                  <label for="nombreUsuario" class="form-label fw-semibold">
                    <i class="bi bi-person me-1" aria-hidden="true"></i>
                    Nombre
                    <span class="text-danger" aria-hidden="true">*</span>
                  </label>
                  <input type="text" id="nombreUsuario" name="nombreUsuario" class="form-control<?= $campoError === 'nombreUsuario' ? ' is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($usuario['nombreUsuario']) ?>" required autocomplete="given-name"
                    aria-required="true" maxlength="100" />
                </div>

                <div class="col-md-6">
                  <label for="apellidoUsuario" class="form-label fw-semibold">
                    <i class="bi bi-person me-1" aria-hidden="true"></i>
                    Apellido
                    <span class="text-danger" aria-hidden="true">*</span>
                  </label>
                  <input type="text" id="apellidoUsuario" name="apellidoUsuario" class="form-control<?= $campoError === 'apellidoUsuario' ? ' is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($usuario['apellidoUsuario']) ?>" required autocomplete="family-name"
                    aria-required="true" maxlength="100" />
                </div>

                <div class="col-md-6">
                  <label for="emailUsuario" class="form-label fw-semibold">
                    <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                    Correo electrónico
                    <span class="text-danger" aria-hidden="true">*</span>
                  </label>
                  <input type="email" id="emailUsuario" name="emailUsuario" class="form-control<?= $campoError === 'emailUsuario' ? ' is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($usuario['emailUsuario']) ?>" required autocomplete="email"
                    aria-required="true" maxlength="100" />
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold">
                    <i class="bi bi-telephone me-1" aria-hidden="true"></i>
                    Teléfono
                    <span class="text-danger" aria-hidden="true">*</span>
                  </label>
                  <input type="hidden" id="telefonoUsuario" name="telefonoUsuario"
                    value="<?= htmlspecialchars($telExistente, ENT_QUOTES, 'UTF-8') ?>"
                    data-original="<?= htmlspecialchars($telExistente, ENT_QUOTES, 'UTF-8') ?>" />
                  <div class="input-group">
                    <select id="paisTelefono" class="form-select flex-grow-0" style="width:auto;"
                      aria-label="País del teléfono">
                      <option value="">— País —</option>
                      <option value="AR" <?= $paisParsed === 'AR' ? ' selected' : '' ?>>Argentina (+54)</option>
                      <option value="UY" <?= $paisParsed === 'UY' ? ' selected' : '' ?>>Uruguay (+598)</option>
                      <option value="CL" <?= $paisParsed === 'CL' ? ' selected' : '' ?>>Chile (+56)</option>
                      <option value="PY" <?= $paisParsed === 'PY' ? ' selected' : '' ?>>Paraguay (+595)</option>
                      <option value="BO" <?= $paisParsed === 'BO' ? ' selected' : '' ?>>Bolivia (+591)</option>
                    </select>
<?php if ($campoError === 'emailUsuario'): ?>
<div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<?php if ($campoError === 'apellidoUsuario'): ?>
<div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<?php if ($campoError === 'nombreUsuario'): ?>
<div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
                    <input type="text" id="numeroTelefono" class="form-control" inputmode="numeric"
                      value="<?= htmlspecialchars($numeroParsed, ENT_QUOTES, 'UTF-8') ?>"
                      placeholder="<?= $paisParsed ? 'Número registrado' : 'Seleccioná un país' ?>"
                      <?= $paisParsed ? '' : 'disabled' ?>
                      aria-label="Número de teléfono" />
                  </div>
                  <div id="ayudaTelefono" class="form-text">
                    <?= $paisParsed ? 'Podés modificar el número o cambiar el país.' : 'Seleccioná un país para ingresar tu número.' ?>
                  </div>
                  <script>
                    (function () {
                      var PAISES = { AR: { code: '54', digits: 11 }, UY: { code: '598', digits: 8 }, CL: { code: '56', digits: 9 }, PY: { code: '595', digits: 9 }, BO: { code: '591', digits: 8 } };
                      var sel = document.getElementById('paisTelefono');
                      var num = document.getElementById('numeroTelefono');
                      var hidden = document.getElementById('telefonoUsuario');
                      var ayuda = document.getElementById('ayudaTelefono');
                      var telefonoOriginal = hidden.dataset.original || hidden.value || '';
                      var paisOriginal = sel.value;
                      var numeroOriginal = num.value;
                      function sync() {
                        var p = PAISES[sel.value];
                        hidden.value = (p && num.value.length === p.digits) ? '+' + p.code + num.value : '';
                      }
                      function limpiarValidez() {
                        sel.setCustomValidity('');
                        num.setCustomValidity('');
                      }
                      function update() {
                        var p = PAISES[sel.value];
                        limpiarValidez();
                        if (!p) {
                          num.disabled = true;
                          num.value = '';
                          num.placeholder = 'Seleccioná un país';
                          ayuda.textContent = 'Seleccioná un país para ingresar tu número.';
                          hidden.value = '';
                          return;
                        }
                        num.disabled = false;
                        num.maxLength = p.digits;
                        num.placeholder = 'Ingresá ' + p.digits + ' dígitos';
                        ayuda.textContent = 'Ingresá exactamente ' + p.digits + ' dígitos (sin el código de país).';
                        sync();
                      }
                      sel.addEventListener('change', update);
                      num.addEventListener('input', function () {
                        limpiarValidez();
                        this.value = this.value.replace(/\D/g, '').slice(0, PAISES[sel.value] ? PAISES[sel.value].digits : 0);
                        sync();
                      });
                      sel.closest('form').addEventListener('submit', function (e) {
                        limpiarValidez();
                        if (telefonoOriginal && sel.value === paisOriginal && num.value === numeroOriginal) {
                          hidden.value = telefonoOriginal;
                          return;
                        }
                        sync();
                        var p = PAISES[sel.value];
                        if (!p) {
                          e.preventDefault();
                          sel.setCustomValidity('Seleccioná un país.');
                          sel.reportValidity();
                          return;
                        }
                        if (!hidden.value) {
                          e.preventDefault();
                          num.setCustomValidity('Ingresá exactamente ' + p.digits + ' dígitos.');
                          num.reportValidity();
                        }
                      });
                      if (PAISES[sel.value]) {
                        update();
                      }
                    })();
                  </script>
                </div>

                <div class="col-md-6">
                  <label for="tipoUsuario" class="form-label fw-semibold">
                    Tipo de usuario
                  </label>
                  <input type="text" id="tipoUsuario" class="form-control"
                    value="<?= ucfirst($usuario['tipoUsuario']) ?>" readonly aria-readonly="true" />
                  <div class="form-text">
                    <i class="bi bi-lock me-1" aria-hidden="true"></i>
                    El tipo es asignado por el sistema.
                  </div>
                </div>

              </div>

              <p class="titulo-seccion-formulario">
                <i class="bi bi-lock me-1" aria-hidden="true"></i>
                Cambiar contraseña
                <span class="fw-normal text-secondary aclaracion-opcional">
                  — Opcional. Dejá los campos vacíos si no querés cambiarla.
                </span>
              </p>

              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label for="claveActual" class="form-label fw-semibold">Contraseña actual</label>
                  <div class="input-group">
                    <input type="password" id="claveActual" name="claveActual" class="form-control<?= $campoError === 'claveActual' ? ' is-invalid' : '' ?>
<?php if ($campoError === 'claveActual'): ?>
<div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>"
                      placeholder="Ingresá tu contraseña actual" autocomplete="current-password"
                      aria-describedby="claveActual-ayuda" minlength="8" maxlength="32" />
                    <button type="button" class="btn btn-outline-secondary btn-ver-contrasena"
                      aria-label="Mostrar contraseña" aria-controls="claveActual">
                      <i class="bi bi-eye" aria-hidden="true"></i>
                    </button>
                  </div>
                  <div id="claveActual-ayuda" class="form-text">
                    Requerida solo si querés cambiar tu contraseña.
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="claveNueva" class="form-label fw-semibold">Nueva contraseña</label>
                  <div class="input-group">
                    <input type="password" id="claveNueva" name="claveNueva" class="form-control<?= $campoError === 'claveNueva' ? ' is-invalid' : '' ?>
<?php if ($campoError === 'claveNueva'): ?>
<div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>"
                      placeholder="Entre 8 y 32 caracteres" autocomplete="new-password"
                      aria-describedby="claveNueva-ayuda"
                      title="La contraseña debe tener entre 8 y 32 caracteres e incluir al menos 1 mayúscula, 1 dígito y 1 carácter especial."
                      minlength="8" maxlength="32" />
                    <button type="button" class="btn btn-outline-secondary btn-ver-contrasena"
                      aria-label="Mostrar contraseña" aria-controls="claveNueva">
                      <i class="bi bi-eye" aria-hidden="true"></i>
                    </button>
                  </div>
                  <div id="claveNueva-ayuda" class="form-text">
                    Entre 8 y 32 caracteres. Debe incluir al menos 1 mayúscula, 1 dígito y 1 carácter especial.
                  </div>
                </div>
              </div>

              <p class="text-secondary small mb-3">
                <span class="text-danger" aria-hidden="true">*</span>
                Los campos marcados son obligatorios.
              </p>

              <div class="d-flex gap-3 flex-wrap">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-floppy-fill me-2" aria-hidden="true"></i>Guardar cambios
                </button>
                <a href="../LandPage/LandUsuarioRegistrado.php" class="btn btn-outline-secondary">
                  <i class="bi bi-x-circle me-2" aria-hidden="true"></i>Cancelar
                </a>
              </div>

            </form>
          </div>

        </div>
      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>

</html>