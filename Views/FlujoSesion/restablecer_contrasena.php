<?php
include '../../config/conexion.php';
$token = isset($_GET['token']) ? $_GET['token'] : (isset($_POST['token']) ? $_POST['token'] : '');
$tokenValido = false;
$error = '';
$exito = false;
if ($token === '') {
    $error = "Enlace de recuperación inválido.";
} else {
    $tokenEscapado = mysqli_real_escape_string($link, $token);
    $query = "SELECT codUsuario FROM USUARIOS
              WHERE tokenRecuperacion = '$tokenEscapado' AND tokenRecuperacionExp > NOW()";
    $resultado = mysqli_query($link, $query);
    if ($resultado && mysqli_num_rows($resultado) === 1) {
        $tokenValido = true;
        $usuario = mysqli_fetch_assoc($resultado);
        $codUsuario = (int) $usuario['codUsuario'];
    } else {
        $error = "El enlace de recuperación venció o no es válido.";
    }
}
if ($tokenValido && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $claveUsuario = $_POST['claveUsuario'];
    if ($claveUsuario === '' || strlen($claveUsuario) > 8) {
        $error = "La contraseña debe tener entre 1 y 8 caracteres.";
    } else {
        $claveHash = md5($claveUsuario);
        mysqli_query($link, "UPDATE USUARIOS
                              SET claveUsuario = '$claveHash', tokenRecuperacion = NULL, tokenRecuperacionExp = NULL
                              WHERE codUsuario = $codUsuario");
        $exito = true;
        $tokenValido = false;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restablecer Contraseña | VuelaLibre – UTN FRR</title>

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
    <section class="py-5" aria-labelledby="restablecer-titulo">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-5">

            <div class="text-center mb-4">
              <i class="bi bi-key text-primary icono-seccion" aria-hidden="true"></i>
              <h1 id="restablecer-titulo" class="h3 fw-bold mt-2">Restablecer contraseña</h1>
            </div>

            <?php if ($exito): ?>
                <div class="card border-0 shadow-sm p-4 p-md-5 text-center">
                  <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
                    Tu contraseña fue actualizada con éxito.
                  </div>
                  <a href="login.php" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Iniciar Sesión
                  </a>
                </div>
            <?php elseif (!$tokenValido): ?>
                <div class="card border-0 shadow-sm p-4 p-md-5 text-center">
                  <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i><?= $error ?>
                  </div>
                  <a href="recuperar_contrasena_.php" class="btn btn-primary w-100">
                    <i class="bi bi-arrow-repeat me-2" aria-hidden="true"></i>Solicitar un nuevo enlace
                  </a>
                </div>
            <?php else: ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i><?= $error ?>
                    </div>
                <?php endif; ?>

                <form action="restablecer_contrasena.php" method="post"
                      class="card border-0 shadow-sm p-4 p-md-5"
                      aria-label="Formulario de restablecimiento de contraseña">

                  <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>"/>

                  <div class="mb-4">
                    <label for="claveUsuario" class="form-label fw-semibold">
                      <i class="bi bi-lock me-1" aria-hidden="true"></i>
                      Nueva contraseña
                      <span class="text-danger" aria-hidden="true">*</span>
                    </label>
                    <input type="password"
                           id="claveUsuario" name="claveUsuario"
                           class="form-control"
                           placeholder="Máximo 8 caracteres"
                           required autofocus
                           autocomplete="new-password"
                           aria-required="true"
                           aria-describedby="claveUsuario-ayuda"
                           pattern=".{1,8}"
                           title="La contraseña debe tener entre 1 y 8 caracteres"
                           maxlength="8"/>
                    <div id="claveUsuario-ayuda" class="form-text">
                      Máximo 8 caracteres (modelo de datos de la cátedra).
                    </div>
                  </div>

                  <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
                    Actualizar contraseña
                  </button>

                </form>

            <?php endif; ?>

          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>
