<?php
include '../../config/conexion.php';
include '../../config/EnviarCorreo.php';
session_start();
if (isset($_SESSION['codUsuario'])) {
  $check = mysqli_query($link, "SELECT codUsuario FROM USUARIOS WHERE codUsuario = " . (int) $_SESSION['codUsuario']);
  if (mysqli_num_rows($check) > 0) {
    header('Location: ../LandPage/LandUsuarioRegistrado.php');
    exit;
  } else {
    session_destroy();
  }
}
$error = '';
$campoError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombreUsuario = trim($_POST['nombreUsuario']);
  $apellidoUsuario = trim($_POST['apellidoUsuario']);
  $clavePlano = $_POST['claveUsuario'];
  $claveConfirmacion = $_POST['confirmarClave'] ?? '';
  $tipoUsuario = $_POST['tipoUsuario'];
  $emailUsuario = trim($_POST['emailUsuario']);
  $telefonoUsuario = trim($_POST['telefonoUsuario']);
  $codigoIATA = isset($_POST['codigoIATA']) ? strtoupper(trim($_POST['codigoIATA'])) : '';
  $codAerolinea = null;
  $stmtEmail = mysqli_prepare($link, "SELECT codUsuario FROM USUARIOS WHERE emailUsuario = ?");
  mysqli_stmt_bind_param($stmtEmail, 's', $emailUsuario);
  mysqli_stmt_execute($stmtEmail);
  $checkEmail = mysqli_stmt_get_result($stmtEmail);
  if (mysqli_num_rows($checkEmail) > 0) {
    $error = "El correo electrónico ya está registrado.";
    $campoError = "emailUsuario";
  } elseif ($tipoUsuario === 'ceo de aerolinea') {
    if ($codigoIATA === '') {
      $error = "Debés ingresar el código IATA de la aerolínea.";
      $campoError = "codigoIATA";
    } else {
      $stmtAerolinea = mysqli_prepare($link, "SELECT codAerolinea FROM AEROLINEAS WHERE codigoIATA = ?");
      mysqli_stmt_bind_param($stmtAerolinea, 's', $codigoIATA);
      mysqli_stmt_execute($stmtAerolinea);
      $checkAerolinea = mysqli_stmt_get_result($stmtAerolinea);
      if (mysqli_num_rows($checkAerolinea) === 0) {
        $error = "No existe una aerolínea registrada con el código IATA ingresado.";
        $campoError = "codigoIATA";
      } else {
        $rowAerolinea = mysqli_fetch_assoc($checkAerolinea);
        $codAerolinea = (int) $rowAerolinea['codAerolinea'];
      }
    }
  }
  if (empty($error) && !preg_match('/^\+54\d{11}$|^\+598\d{8}$|^\+56\d{9}$|^\+595\d{9}$|^\+591\d{8}$/', $telefonoUsuario)) {
    $error = "El número de teléfono no es válido para el país seleccionado.";
    $campoError = "telefonoUsuario";
  }
  if (empty($error) && !preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,32}$/', $clavePlano)) {
    $error = "La contraseña debe tener entre 8 y 32 caracteres e incluir al menos 1 mayúscula, 1 dígito y 1 carácter especial.";
    $campoError = "claveUsuario";
  }
  if (empty($error) && $claveConfirmacion !== $clavePlano) {
    $error = "Las contraseñas no coinciden.";
    $campoError = "confirmarClave";
  }
  if (empty($error)) {
    $claveUsuario = md5($clavePlano);
    $esCliente = ($tipoUsuario !== 'ceo de aerolinea');
    $tokenVerificacion = $esCliente ? bin2hex(random_bytes(32)) : null;
    $query = "INSERT INTO USUARIOS (nombreUsuario, apellidoUsuario, claveUsuario, tipoUsuario, emailUsuario, telefonoUsuario, verificado, tokenVerificacion, tokenVerificacionExp, codAerolinea)
          VALUES (?, ?, ?, ?, ?, ?, 0, ?, IF(? IS NULL, NULL, DATE_ADD(NOW(), INTERVAL 24 HOUR)), ?)";
    $stmtInsert = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param(
      $stmtInsert,
      'ssssssssi',
      $nombreUsuario,
      $apellidoUsuario,
      $claveUsuario,
      $tipoUsuario,
      $emailUsuario,
      $telefonoUsuario,
      $tokenVerificacion,
      $tokenVerificacion,
      $codAerolinea
    );
    if (mysqli_stmt_execute($stmtInsert)) {
      if (!$esCliente) {
        header('Location: login.php?pendiente=1');
        exit;
      }
      $enlace = BASE_URL . '/Views/FlujoSesion/verificar_email.php?token=' . $tokenVerificacion;
      $nombreCompletoSafe = htmlspecialchars(trim("$nombreUsuario $apellidoUsuario"), ENT_QUOTES, 'UTF-8');
      $cuerpo = "<p>Hola <strong>$nombreCompletoSafe</strong>,</p>"
        . "<p>Gracias por registrarte en VuelaLibre. Confirmá tu cuenta haciendo clic en el siguiente enlace (válido por 24 horas):</p>"
        . "<p><a href=\"$enlace\">$enlace</a></p>";
      $enviado = enviarCorreo($emailUsuario, 'Verificá tu cuenta en VuelaLibre', $cuerpo);
      if ($enviado) {
        header('Location: login.php?registro=1');
      } else {
        header('Location: login.php?registro=1&correoFallido=1');
      }
      exit;
    } else {
      $error = "Error al registrar el usuario en la base de datos.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Creá tu cuenta en VuelaLibre para buscar y reservar vuelos." />
  <title>Registrarse | VuelaLibre – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="registro-titulo">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">

            <div class="text-center mb-4">
              <i class="bi bi-person-circle text-primary icono-seccion" aria-hidden="true"></i>
              <h1 id="registro-titulo" class="h3 fw-bold mt-2">Registrarse</h1>
              <p class="text-secondary">Completá el formulario para registrarte en VuelaLibre.</p>
            </div>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i><?= $error ?>
              </div>
            <?php endif; ?>

            <form action="registrarse_.php" method="post" class="card border-0 shadow-sm p-4 p-md-5"
              aria-label="Formulario de registro de nuevo usuario">

              <div class="mb-3">
                <label for="nombreUsuario" class="form-label fw-semibold">
                  <i class="bi bi-person me-1" aria-hidden="true"></i>
                  Nombre
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="text" id="nombreUsuario" name="nombreUsuario" class="form-control" placeholder="Ej: Juan"
                  required autofocus autocomplete="given-name" aria-required="true"
                  value="<?= htmlspecialchars($_POST['nombreUsuario'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  aria-describedby="nombreUsuario-ayuda" maxlength="100" />
                <div id="nombreUsuario-ayuda" class="form-text">Máximo 100 caracteres.</div>
              </div>

              <div class="mb-3">
                <label for="apellidoUsuario" class="form-label fw-semibold">
                  <i class="bi bi-person me-1" aria-hidden="true"></i>
                  Apellido
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="text" id="apellidoUsuario" name="apellidoUsuario" class="form-control"
                  placeholder="Ej: Pérez" required autocomplete="family-name" aria-required="true"
                  value="<?= htmlspecialchars($_POST['apellidoUsuario'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  aria-describedby="apellidoUsuario-ayuda" maxlength="100" />
                <div id="apellidoUsuario-ayuda" class="form-text">Máximo 100 caracteres.</div>
              </div>

              <div class="mb-3">
                <label for="claveUsuario" class="form-label fw-semibold">
                  <i class="bi bi-lock me-1" aria-hidden="true"></i>
                  Contraseña
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <div class="input-group">
                  <input type="password" id="claveUsuario" name="claveUsuario" class="form-control<?= $campoError === 'claveUsuario' ? ' is-invalid' : '' ?>"
                    placeholder="Máximo 32 caracteres" required autocomplete="new-password" aria-required="true"
                    aria-describedby="claveUsuario-ayuda" minlength="8" maxlength="32"
                    title="La contraseña debe tener entre 8 y 32 caracteres e incluir al menos 1 mayúscula, 1 dígito y 1 carácter especial." />
                  <button type="button" class="btn btn-outline-secondary btn-ver-contrasena"
                    aria-label="Mostrar contraseña" aria-controls="claveUsuario">
                    <i class="bi bi-eye" aria-hidden="true"></i>
                  </button>
                </div>
                <?php if ($campoError === 'claveUsuario'): ?>
                  <div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <div id="claveUsuario-ayuda" class="form-text">
                  La contraseña debe tener entre 8 y 32 caracteres e incluir al menos una mayúscula, un dígito y un
                  carácter especial.
                </div>
              </div>

              <div class="mb-3">
                <label for="confirmarClave" class="form-label fw-semibold">
                  <i class="bi bi-lock me-1" aria-hidden="true"></i>
                  Confirmar contraseña
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <div class="input-group">
                  <input type="password" id="confirmarClave" name="confirmarClave" class="form-control<?= $campoError === 'confirmarClave' ? ' is-invalid' : '' ?>"
                    placeholder="Repetí tu contraseña" required autocomplete="new-password" aria-required="true"
                    aria-describedby="confirmarClave-ayuda" minlength="8" maxlength="32"
                    title="Debe coincidir con la contraseña ingresada." />
                  <button type="button" class="btn btn-outline-secondary btn-ver-contrasena"
                    aria-label="Mostrar contraseña" aria-controls="confirmarClave">
                    <i class="bi bi-eye" aria-hidden="true"></i>
                  </button>
                </div>
                <?php if ($campoError === 'confirmarClave'): ?>
                  <div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <div id="confirmarClave-ayuda" class="form-text">
                  Repetí la misma contraseña para confirmarla.
                </div>
              </div>

              <fieldset class="mb-3">
                <legend class="form-label fw-semibold mb-2">
                  <i class="bi bi-person-badge me-1" aria-hidden="true"></i>
                  Tipo de usuario
                  <span class="text-danger" aria-hidden="true">*</span>
                </legend>
                <div class="row g-3">
                  <div class="col-6">
                    <label class="tarjeta-tipo-usuario d-flex flex-column align-items-center p-3 text-center w-100"
                      for="tipo-usuario">
                      <i class="bi bi-person-fill text-primary fs-2 mb-2" aria-hidden="true"></i>
                      <span class="fw-semibold">Cliente / Pasajero</span>
                      <small class="text-secondary mt-1">Buscá y reservá vuelos</small>
                      <input type="radio" id="tipo-usuario" name="tipoUsuario" value="usuario" class="visually-hidden"
                        required <?= (($_POST['tipoUsuario'] ?? 'usuario') === 'usuario') ? 'checked' : '' ?> />
                    </label>
                  </div>
                  <div class="col-6">
                    <label class="tarjeta-tipo-usuario d-flex flex-column align-items-center p-3 text-center w-100"
                      for="tipo-aerolinea">
                      <i class="bi bi-building-fill text-primary fs-2 mb-2" aria-hidden="true"></i>
                      <span class="fw-semibold"><abbr title="Director Ejecutivo">CEO</abbr> de Aerolínea</span>
                      <small class="text-secondary mt-1">Gestión de vuelos y promociones</small>
                      <input type="radio" id="tipo-aerolinea" name="tipoUsuario" value="ceo de aerolinea"
                        class="visually-hidden" <?= (($_POST['tipoUsuario'] ?? '') === 'ceo de aerolinea') ? 'checked' : '' ?> />
                    </label>
                  </div>
                </div>

                <div id="campo-codigo-iata" class="mb-3" hidden>
                  <label for="codigoIATA" class="form-label fw-semibold">
                    <i class="bi bi-airplane me-1" aria-hidden="true"></i>
                    Código IATA de la aerolínea
                    <span class="text-danger" aria-hidden="true">*</span>
                  </label>
                  <input type="text" id="codigoIATA" name="codigoIATA" class="form-control text-uppercase<?= $campoError === 'codigoIATA' ? ' is-invalid' : '' ?>"
                    placeholder="Ej: AR" maxlength="3" pattern="[A-Za-z]{2,3}"
                    title="Ingresá un código IATA de 2 o 3 letras" autocomplete="off"
                    aria-describedby="codigoIATA-ayuda"
                    value="<?= htmlspecialchars($_POST['codigoIATA'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                  <?php if ($campoError === 'codigoIATA'): ?>
                    <div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                  <?php endif; ?>
                  <div id="codigoIATA-ayuda" class="form-text">
                    Código de 2 o 3 letras asignado por la IATA a la aerolínea.
                  </div>
                </div>
                <div class="alert alert-warning py-2 mt-3 mb-0 small" role="note">
                  <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                  Los registros de <strong><abbr title="Director Ejecutivo">CEO</abbr> de Aerolínea</strong> requieren
                  aprobación del Administrador.
                </div>
              </fieldset>

              <div class="mb-3">
                <label for="emailUsuario" class="form-label fw-semibold">
                  <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                  Correo electrónico
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="email" id="emailUsuario" name="emailUsuario" class="form-control<?= $campoError === 'emailUsuario' ? ' is-invalid' : '' ?>"
                  placeholder="ejemplo@correo.com" required autocomplete="email" aria-required="true"
                  value="<?= htmlspecialchars($_POST['emailUsuario'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  aria-describedby="emailUsuario-ayuda" maxlength="100" />
                <?php if ($campoError === 'emailUsuario'): ?>
                  <div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <div id="emailUsuario-ayuda" class="form-text">
                  Los Clientes recibirán un enlace de validación en esta dirección.
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label fw-semibold">
                  <i class="bi bi-telephone me-1" aria-hidden="true"></i>
                  Teléfono
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="hidden" id="telefonoUsuario" name="telefonoUsuario"
                  value="<?= htmlspecialchars($_POST['telefonoUsuario'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
                <div class="input-group">
                  <select id="paisTelefono" class="form-select flex-grow-0" style="width:auto;"
                    aria-label="País del teléfono">
                    <option value="">— País —</option>
                    <option value="AR">Argentina (+54)</option>
                    <option value="UY">Uruguay (+598)</option>
                    <option value="CL">Chile (+56)</option>
                    <option value="PY">Paraguay (+595)</option>
                    <option value="BO">Bolivia (+591)</option>
                  </select>
                  <input type="text" id="numeroTelefono" class="form-control<?= $campoError === 'telefonoUsuario' ? ' is-invalid' : '' ?>" inputmode="numeric"
                    placeholder="Seleccioná un país" disabled aria-label="Número de teléfono" />
                </div>
                <?php if ($campoError === 'telefonoUsuario'): ?>
                  <div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <div id="ayudaTelefono" class="form-text">Seleccioná un país para ingresar tu número.</div>
              </div>
              <script>
                (function () {
                  var PAISES = { AR: { code: '54', digits: 11 }, UY: { code: '598', digits: 8 }, CL: { code: '56', digits: 9 }, PY: { code: '595', digits: 9 }, BO: { code: '591', digits: 8 } };
                  var sel = document.getElementById('paisTelefono');
                  var num = document.getElementById('numeroTelefono');
                  var hidden = document.getElementById('telefonoUsuario');
                  var ayuda = document.getElementById('ayudaTelefono');
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
                  function restaurar() {
                    if (!hidden.value) return;
                    for (var clave in PAISES) {
                      var prefijo = '+' + PAISES[clave].code;
                      if (hidden.value.indexOf(prefijo) === 0) {
                        sel.value = clave;
                        update();
                        num.value = hidden.value.slice(prefijo.length);
                        sync();
                        return;
                      }
                    }
                  }
                  restaurar();
                  sel.addEventListener('change', update);
                  num.addEventListener('input', function () {
                    limpiarValidez();
                    this.value = this.value.replace(/\D/g, '').slice(0, PAISES[sel.value] ? PAISES[sel.value].digits : 0);
                    sync();
                  });
                  sel.closest('form').addEventListener('submit', function (e) {
                    limpiarValidez();
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
                })();
              </script>

              <p class="text-secondary small mb-3">
                <span class="text-danger" aria-hidden="true">*</span> Todos los campos son obligatorios.
              </p>

              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-person-check-fill me-2" aria-hidden="true"></i>Registrarse
              </button>

            </form>

          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const tipoUsuario = document.getElementById('tipo-usuario');
      const tipoAerolinea = document.getElementById('tipo-aerolinea');
      const campoIata = document.getElementById('campo-codigo-iata');
      const inputIata = document.getElementById('codigoIATA');
      function actualizarCampoIata() {
        const esCeo = tipoAerolinea.checked;
        campoIata.hidden = !esCeo;
        inputIata.required = esCeo;
        if (!esCeo) {
          inputIata.value = '';
        }
      }
      tipoUsuario.addEventListener('change', actualizarCampoIata);
      tipoAerolinea.addEventListener('change', actualizarCampoIata);
      actualizarCampoIata();
    });
  </script>

</body>

</html>