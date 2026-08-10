<?php
session_start();
include '../../config/conexion.php';
include '../../config/EnviarCorreo.php';
include '../../config/validaciones.php';
$enviado = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $emailUsuario = trim($_POST['emailUsuario']);
  if (!emailConDominioValido($emailUsuario)) {
    $enviado = false;
  } else {
    $emailEscapado = mysqli_real_escape_string($link, $emailUsuario);
    $query = "SELECT codUsuario, nombreUsuario FROM USUARIOS WHERE emailUsuario = '$emailEscapado'";
    $resultado = mysqli_query($link, $query);
    if ($resultado && mysqli_num_rows($resultado) === 1) {
      $usuario = mysqli_fetch_assoc($resultado);
      $codUsuario = (int) $usuario['codUsuario'];
      $token = bin2hex(random_bytes(32));
      mysqli_query($link, "UPDATE USUARIOS
                                  SET tokenRecuperacion = '$token', tokenRecuperacionExp = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                                  WHERE codUsuario = $codUsuario");
      $enlace = BASE_URL . '/Views/FlujoSesion/restablecer_contrasena.php?token=' . $token;
      $cuerpo = "<p>Hola <strong>{$usuario['nombreUsuario']}</strong>,</p>"
        . "<p>Recibimos una solicitud para restablecer tu contraseña de VuelaLibre. El siguiente enlace es válido por 1 hora:</p>"
        . "<p><a href=\"$enlace\">$enlace</a></p>"
        . "<p>Si no solicitaste esto, podés ignorar este correo.</p>";
      enviarCorreo($emailUsuario, 'Recuperación de contraseña - VuelaLibre', $cuerpo);
    }
    $enviado = true;
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
    content="Recuperá tu contraseña de VuelaLibre ingresando tu correo electrónico registrado." />
  <title>Recuperar Contraseña | VuelaLibre – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="recuperar-titulo">
      <div class="container">
        <div class="row justify-content-center g-4">

          <div class="col-md-8 col-lg-5">

            <div class="text-center mb-4">
              <i class="bi bi-key text-primary icono-seccion" aria-hidden="true"></i>
              <h1 id="recuperar-titulo" class="h3 fw-bold mt-2">Recuperar contraseña</h1>
              <p class="text-secondary">
                Ingresá tu correo electrónico registrado y te enviaremos
                un enlace para restablecer tu contraseña.
              </p>
            </div>

            <?php if ($enviado): ?>
              <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
                Si el correo está registrado, te enviamos un enlace para restablecer tu contraseña.
              </div>
            <?php endif; ?>

            <form action="recuperar_contrasena_.php" method="post"
              class="card border-0 shadow-sm p-4 p-md-5"
              aria-label="Formulario de recuperación de contraseña">

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
                  aria-describedby="emailUsuario-ayuda"
                  maxlength="100"
                  pattern="^[^@\s]+@[^@\s]+\.[^@\s]+$"
                  title="Ingresá un correo con formato ejemplo@correo.com" />
                <div id="emailUsuario-ayuda" class="form-text">
                  Debe coincidir con el correo con el que te registraste.
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-send me-2" aria-hidden="true"></i>
                Enviar enlace de recuperación
              </button>

            </form>
          </div>

          <div class="col-lg-4 d-none d-lg-block">
            <div class="card border-0 shadow-sm p-4 h-100">

              <h2 class="h6 fw-bold text-primary mb-1">
                <i class="bi bi-question-circle-fill me-2" aria-hidden="true"></i>¿Cómo funciona?
              </h2>
              <p class="text-secondary small mb-3">
                El proceso de recuperación tiene tres pasos simples.
              </p>

              <ol class="lista-pasos-recuperacion" aria-label="Pasos para recuperar la contraseña">
                <li>
                  <div class="numero-paso" aria-hidden="true">1</div>
                  <span>
                    Ingresá el <strong>correo electrónico</strong> con el que
                    te registraste en VuelaLibre.
                  </span>
                </li>
                <li>
                  <div class="numero-paso" aria-hidden="true">2</div>
                  <span>
                    Revisá tu bandeja de entrada. Te enviaremos un
                    <strong>enlace seguro</strong> que expira en 1 hora.
                  </span>
                </li>
                <li>
                  <div class="numero-paso" aria-hidden="true">3</div>
                  <span>
                    Hacé clic en el enlace e ingresá tu
                    <strong>nueva contraseña</strong> (máximo 8 caracteres).
                  </span>
                </li>
              </ol>

              <hr class="my-3" />

              <div class="alert alert-light border small mb-0 p-3" role="note">
                <i class="bi bi-shield-check text-primary me-1" aria-hidden="true"></i>
                Por seguridad, el sistema <strong>no confirma</strong> si el correo
                está registrado o no.
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