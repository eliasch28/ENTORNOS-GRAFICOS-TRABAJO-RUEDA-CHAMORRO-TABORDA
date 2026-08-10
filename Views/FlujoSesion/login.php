<?php
include '../../config/conexion.php';
include '../../config/validaciones.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_logout'])) {
  $_SESSION = [];
  session_destroy();
  header('Location: login.php');
  exit;
}
if (isset($_SESSION['codUsuario'])) {
  $check = mysqli_query($link, "SELECT codUsuario FROM USUARIOS WHERE codUsuario = " . (int) $_SESSION['codUsuario']);
  if (mysqli_num_rows($check) > 0) {
    header('Location: ../LandPage/LandUsuarioRegistrado.php');
    exit;
  } else {
    session_destroy();
  }
}
$campoError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $emailUsuario = trim($_POST['emailUsuario']);
  $claveUsuario = $_POST['claveUsuario'];
  if (!emailConDominioValido($emailUsuario)) {
    $error = "Ingresá un correo electrónico válido con un dominio como ejemplo@correo.com.";
    $campoError = "emailUsuario";
  } else {
    $query = "SELECT * FROM USUARIOS WHERE BINARY emailUsuario = ?";
    $stmtLogin = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmtLogin, 's', $emailUsuario);
    mysqli_stmt_execute($stmtLogin);
    $resultado = mysqli_stmt_get_result($stmtLogin);
    if ($resultado && mysqli_num_rows($resultado) === 1) {
      $usuario = mysqli_fetch_assoc($resultado);
      if (md5($claveUsuario) === $usuario['claveUsuario']) {
        if ($usuario['verificado'] == 0) {
          if ($usuario['tipoUsuario'] === 'ceo de aerolinea') {
            $error = "Tu cuenta está pendiente de aprobación por el Administrador.";
          } else {
            $error = 'Tu cuenta todavía no fue verificada. Revisá tu correo o <a href="reenviar_verificacion.php">reenviá el enlace de verificación</a>.';
          }
        } else {
          $_SESSION['codUsuario'] = $usuario['codUsuario'];
          $_SESSION['nombreUsuario'] = $usuario['nombreUsuario'];
          $_SESSION['apellidoUsuario'] = $usuario['apellidoUsuario'];
          $_SESSION['nombreCompleto'] = trim($usuario['nombreUsuario'] . ' ' . $usuario['apellidoUsuario']);
          $_SESSION['tipoUsuario'] = $usuario['tipoUsuario'];
          header('Location: ../LandPage/LandUsuarioRegistrado.php');
          exit;
        }
      } else {
        $error = "Contraseña incorrecta.";
        $campoError = "claveUsuario";
      }
    } else {
      $error = "No encontramos ninguna cuenta con ese correo electrónico.";
      $campoError = "emailUsuario";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Iniciá sesión en VuelaLibre para gestionar tus reservas de vuelos." />
  <title>Iniciar Sesión | VuelaLibre – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="login-titulo">
      <div class="container">
        <div class="row justify-content-center g-4">

          <div class="col-md-8 col-lg-5">

            <div class="text-center mb-4">
              <i class="bi bi-shield-lock text-primary icono-seccion" aria-hidden="true"></i>
              <h1 id="login-titulo" class="h3 fw-bold mt-2">Iniciar Sesión</h1>
              <p class="text-secondary">Ingresá con tu cuenta para acceder a VuelaLibre.</p>
            </div>

            <?php if (isset($_GET['registro'])): ?>
              <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
                ¡Cuenta creada con éxito! Te enviamos un correo para verificar tu cuenta antes de poder iniciar sesión.
              </div>
            <?php endif; ?>

            <?php if (isset($_GET['correoFallido'])): ?>
              <div class="alert alert-warning" role="alert">
                <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>
                No pudimos enviar el correo de verificación. Probá
                <a href="reenviar_verificacion.php">reenviarlo</a> en unos minutos.
              </div>
            <?php endif; ?>

            <?php if (isset($_GET['pendiente'])): ?>
              <div class="alert alert-warning" role="alert">
                <i class="bi bi-hourglass-split me-2" aria-hidden="true"></i>Tu solicitud fue enviada. Aguardá la
                aprobación del Administrador para poder iniciar sesión.
              </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i><?= $error ?>
              </div>
            <?php endif; ?>

            <form action="login.php" method="post" class="card border-0 shadow-sm p-4 p-md-5"
              aria-label="Formulario de inicio de sesión">

              <div class="mb-3">
                <label for="emailUsuario" class="form-label fw-semibold">
                  <i class="bi bi-envelope me-1" aria-hidden="true"></i>Correo electrónico
                </label>
                <input type="email" id="emailUsuario" name="emailUsuario"
                  class="form-control<?= $campoError === 'emailUsuario' ? ' is-invalid' : '' ?>" placeholder="Tu email"
                  required autofocus autocomplete="email" aria-required="true" maxlength="100"
                  value="<?= htmlspecialchars($_POST['emailUsuario'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                  pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$"
                  title="Ingresá un correo con formato ejemplo@correo.com"
                  oninvalid="this.setCustomValidity('Introducí un correo electrónico válido.')"
                  oninput="this.setCustomValidity('')" />
                <?php if ($campoError === 'emailUsuario'): ?>
                  <div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
              </div>

              <div class="mb-2">
                <label for="claveUsuario" class="form-label fw-semibold">
                  <i class="bi bi-lock me-1" aria-hidden="true"></i>Contraseña
                </label>
                <div class="input-group">
                  <input type="password" id="claveUsuario" name="claveUsuario"
                    class="form-control<?= $campoError === 'claveUsuario' ? ' is-invalid' : '' ?>"
                    placeholder="Tu contraseña" required autocomplete="current-password" aria-required="true"
                    maxlength="32" />
                  <button type="button" class="btn btn-outline-secondary btn-ver-contrasena"
                    aria-label="Mostrar contraseña" aria-controls="claveUsuario">
                    <i class="bi bi-eye" aria-hidden="true"></i>
                  </button>
                </div>
                <?php if ($campoError === 'claveUsuario'): ?>
                  <div class="invalid-feedback d-block"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
              </div>

              <div class="text-end mb-4">
                <a href="recuperar_contrasena_.php" class="text-secondary small">
                  ¿Olvidaste tu contraseña?
                </a>
              </div>

              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Ingresar
              </button>

            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>

</html>