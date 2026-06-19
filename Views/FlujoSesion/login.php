<?php
include '../../config/conexion.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}
if (isset($_SESSION['codUsuario'])) {
    $check = mysqli_query($link, "SELECT codUsuario FROM USUARIOS WHERE codUsuario = " . $_SESSION['codUsuario']);
    if (mysqli_num_rows($check) > 0) {
        header('Location: ../LandPage/LandUsuarioRegistrado.php');
        exit;
    } else {
        session_destroy();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = trim($_POST['nombreUsuario']);
    $claveUsuario  = $_POST['claveUsuario'];
    $query = "SELECT * FROM USUARIOS WHERE BINARY nombreUsuario = '$nombreUsuario'";
    $resultado = mysqli_query($link, $query);
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
                $_SESSION['codUsuario']    = $usuario['codUsuario'];
                $_SESSION['nombreUsuario'] = $usuario['nombreUsuario'];
                $_SESSION['tipoUsuario']   = $usuario['tipoUsuario'];
                header('Location: ../LandPage/LandUsuarioRegistrado.php');
                exit;
            }
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
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
                    <i class="bi bi-hourglass-split me-2" aria-hidden="true"></i>Tu solicitud fue enviada. Aguardá la aprobación del Administrador para poder iniciar sesión.
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i><?= $error ?>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="post"
                  class="card border-0 shadow-sm p-4 p-md-5"
                  aria-label="Formulario de inicio de sesión">

              <div class="mb-3">
                <label for="nombreUsuario" class="form-label fw-semibold">
                  <i class="bi bi-person me-1" aria-hidden="true"></i>Nombre de usuario
                </label>
                <input type="text"
                       id="nombreUsuario" name="nombreUsuario"
                       class="form-control"
                       placeholder="Tu nombre de usuario"
                       required autofocus
                       autocomplete="username"
                       aria-required="true"
                       maxlength="100"/>
              </div>

              <div class="mb-2">
                <label for="claveUsuario" class="form-label fw-semibold">
                  <i class="bi bi-lock me-1" aria-hidden="true"></i>Contraseña
                </label>
                <input type="password"
                       id="claveUsuario" name="claveUsuario"
                       class="form-control"
                       placeholder="Tu contraseña"
                       required
                       autocomplete="current-password"
                       aria-required="true"
                       maxlength="8"/>
              </div>

              <div class="text-end mb-4">
                <a href="recuperar_contrasena_.php" class="text-secondary small">
                  ¿Olvidaste tu contraseña?
                </a>
              </div>

              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Ingresar
              </button>

              <div class="my-4 separador-texto">¿No tenés cuenta?</div>

              <a href="registrarse_.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-person-plus me-2" aria-hidden="true"></i>Crear una cuenta nueva
              </a>

            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>
