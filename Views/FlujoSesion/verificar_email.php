<?php
include '../../config/conexion.php';
$token = isset($_GET['token']) ? $_GET['token'] : '';
$exito = false;
$mensaje = '';
if ($token === '') {
    $mensaje = "Enlace de verificación inválido.";
} else {
    $query = "SELECT codUsuario FROM USUARIOS
              WHERE tokenVerificacion = ? AND tokenVerificacionExp > NOW()";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    if ($resultado && mysqli_num_rows($resultado) === 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $codUsuario = (int) $usuario['codUsuario'];
        mysqli_query($link, "UPDATE USUARIOS
                              SET verificado = 1, tokenVerificacion = NULL, tokenVerificacionExp = NULL
                              WHERE codUsuario = $codUsuario");
        $exito = true;
        $mensaje = "¡Tu cuenta fue verificada con éxito! Ya podés iniciar sesión.";
    } else {
        $mensaje = "El enlace de verificación venció o no es válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verificar cuenta | VuelaLibre – UTN FRR</title>

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
    <section class="py-5" aria-labelledby="verificar-titulo">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">

            <div class="text-center mb-4">
              <i class="bi bi-<?= $exito ? 'patch-check' : 'x-octagon' ?> text-primary icono-seccion" aria-hidden="true"></i>
              <h1 id="verificar-titulo" class="h3 fw-bold mt-2">Verificación de cuenta</h1>
            </div>

            <div class="card border-0 shadow-sm p-4 p-md-5 text-center">
              <div class="alert <?= $exito ? 'alert-success' : 'alert-danger' ?>" role="alert">
                <i class="bi bi-<?= $exito ? 'check-circle' : 'exclamation-triangle' ?> me-2" aria-hidden="true"></i><?= $mensaje ?>
              </div>

              <?php if (!$exito): ?>
                <a href="reenviar_verificacion.php" class="btn btn-primary w-100">
                  <i class="bi bi-arrow-repeat me-2" aria-hidden="true"></i>Reenviar enlace de verificación
                </a>
              <?php endif; ?>
            </div>

          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>
