<?php
include '../../config/conexion.php';
include '../../config/EnviarCorreo.php';

$enviado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailUsuario = trim($_POST['emailUsuario']);
    $emailEscapado = mysqli_real_escape_string($link, $emailUsuario);

    $query = "SELECT codUsuario, nombreUsuario FROM USUARIOS
              WHERE emailUsuario = '$emailEscapado' AND verificado = 0 AND tipoUsuario != 'ceo de aerolinea'";
    $resultado = mysqli_query($link, $query);

    if ($resultado && mysqli_num_rows($resultado) === 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $codUsuario = (int) $usuario['codUsuario'];
        $token = bin2hex(random_bytes(32));

        mysqli_query($link, "UPDATE USUARIOS
                              SET tokenVerificacion = '$token', tokenVerificacionExp = DATE_ADD(NOW(), INTERVAL 24 HOUR)
                              WHERE codUsuario = $codUsuario");

        $enlace = BASE_URL . '/Views/FlujoSesion/verificar_email.php?token=' . $token;
        $cuerpo = "<p>Hola <strong>{$usuario['nombreUsuario']}</strong>,</p>"
            . "<p>Confirmá tu cuenta de VuelaLibre haciendo clic en el siguiente enlace (válido por 24 horas):</p>"
            . "<p><a href=\"$enlace\">$enlace</a></p>";

        enviarCorreo($emailUsuario, 'Verificá tu cuenta en VuelaLibre', $cuerpo);
    }

    // Mensaje genérico siempre, exista o no el correo, para no filtrar qué emails están registrados.
    $enviado = true;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reenviar verificación | VuelaLibre – UTN FRR</title>

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
    <section class="py-5" aria-labelledby="reenviar-titulo">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-5">

            <div class="text-center mb-4">
              <i class="bi bi-envelope-paper text-primary icono-seccion" aria-hidden="true"></i>
              <h1 id="reenviar-titulo" class="h3 fw-bold mt-2">Reenviar verificación</h1>
              <p class="text-secondary">
                Ingresá el correo con el que te registraste y te reenviamos el enlace de verificación.
              </p>
            </div>

            <?php if ($enviado): ?>
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    Si el correo está registrado y pendiente de verificación, te reenviamos el enlace.
                </div>
            <?php endif; ?>

            <form action="reenviar_verificacion.php" method="post"
                  class="card border-0 shadow-sm p-4 p-md-5"
                  aria-label="Formulario de reenvío de verificación">

              <div class="mb-4">
                <label for="emailUsuario" class="form-label fw-semibold">
                  <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                  Correo electrónico registrado
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="email"
                       id="emailUsuario" name="emailUsuario"
                       class="form-control"
                       placeholder="ejemplo@correo.com"
                       required autofocus
                       autocomplete="email"
                       aria-required="true"
                       maxlength="100"/>
              </div>

              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-send me-2" aria-hidden="true"></i>
                Reenviar enlace
              </button>

              <div class="my-4 separador-texto">¿Ya verificaste tu cuenta?</div>

              <a href="login.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>
                Iniciar Sesión
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
