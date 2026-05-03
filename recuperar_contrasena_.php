<?php
$envioExitoso = false;
$errores      = [];
$formData     = ['emailUsuario' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $emailUsuario = trim(htmlspecialchars($_POST['emailUsuario'] ?? ''));
    $formData = ['emailUsuario' => $emailUsuario];

    if (empty($emailUsuario)) {
        $errores[] = 'El correo electrónico es obligatorio.';
    } elseif (!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo electrónico ingresado no es válido.';
    }

    if (empty($errores)) {
        $envioExitoso = true;
        $formData     = ['emailUsuario' => ''];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Recuperá tu contraseña de SkyReserva ingresando tu correo electrónico registrado. UTN FRR, Cátedra Entornos Gráficos 2026." />
  <title>Recuperar Contraseña | SkyReserva – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="EstilosLandUsuarioNoRegistrado.css"/>

  <style>
    .divider-text {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      color: var(--bs-secondary-color);
      font-size: 0.85rem;
    }
    .divider-text::before,
    .divider-text::after {
      content: '';
      flex: 1;
      border-top: 1px solid var(--bs-border-color);
    }
    .steps-list {
      list-style: none;
      padding-left: 0;
      margin-bottom: 0;
    }
    .steps-list li {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      padding: 0.65rem 0;
      border-bottom: 1px solid var(--bs-border-color);
      font-size: 0.88rem;
      color: var(--bs-secondary-color);
    }
    .steps-list li:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }
    .step-num {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background-color: var(--bs-primary);
      color: #fff;
      font-size: 0.75rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }
  </style>
</head>

<body class="bg-light">
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary"
         aria-label="Navegación principal">
      <div class="container">

        <a class="navbar-brand fw-bold d-flex align-items-center gap-2"
           href="LandUsuarioNoRegistrado.html"
           aria-label="SkyReserva — Ir a la página de inicio">
          <i class="bi bi-airplane-fill" aria-hidden="true"></i>SkyReserva
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal" aria-expanded="false"
                aria-label="Abrir o cerrar menú de navegación">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="LandUsuarioNoRegistrado.html">
                <i class="bi bi-house-fill me-1" aria-hidden="true"></i>Inicio
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="LandUsuarioNoRegistrado.html#seccion-aerolineas">
                <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolíneas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="LandUsuarioNoRegistrado.html#seccion-vuelos">
                <i class="bi bi-airplane me-1" aria-hidden="true"></i>Vuelos
              </a>
            </li>
          </ul>
          <div class="d-flex gap-2" role="group" aria-label="Acciones de sesión">
            <a href="login.php" class="btn btn-outline-light btn-sm">
              <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>Iniciar Sesión
            </a>
            <a href="registrarse_.php" class="btn btn-light btn-sm text-primary fw-semibold">
              <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>Registrarse
            </a>
          </div>
        </div>
      </div>
    </nav>
  </header>


  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="recuperar-titulo">
      <div class="container">
        <div class="row justify-content-center g-4">

          <div class="col-md-8 col-lg-5">

            <div class="text-center mb-4">
              <i class="bi bi-key text-primary"
                 style="font-size:3rem;" aria-hidden="true"></i>
              <h1 id="recuperar-titulo" class="h3 fw-bold mt-2">
                Recuperar contraseña
              </h1>
              <p class="text-secondary">
                Ingresá tu correo electrónico registrado y te enviaremos
                un enlace para restablecer tu contraseña.
              </p>
            </div>

            <?php if ($envioExitoso): ?>
              <div class="alert alert-success d-flex align-items-start gap-3"
                   role="alert">
                <i class="bi bi-envelope-check-fill fs-4 flex-shrink-0 mt-1"
                   aria-hidden="true"></i>
                <div>
                  <h2 class="alert-heading h6 fw-bold mb-1">
                    ¡Correo enviado!
                  </h2>
                  <p class="mb-3">
                    Si el correo ingresado está registrado en el sistema,
                    recibirás un enlace para restablecer tu contraseña.
                    Revisá tu bandeja de entrada y también la carpeta de spam.
                  </p>
                  <p class="mb-3 small text-secondary">
                    <i class="bi bi-clock me-1" aria-hidden="true"></i>El enlace expirará en <strong>1 hora</strong>.
                  </p>
                  <a href="login.php" class="btn btn-success btn-sm">
                    <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>Volver a Iniciar Sesión
                  </a>
                </div>
              </div>

            <?php endif; ?>

            <?php if (!empty($errores)): ?>
              <div class="alert alert-danger d-flex align-items-center gap-2"
                   role="alert">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"
                   aria-hidden="true"></i>
                <ul class="mb-0 ps-2">
                  <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <?php if (!$envioExitoso): ?>
            <form action="recuperar_contrasena_.php" method="post"
                  class="card border-0 shadow-sm p-4 p-md-5"
                  aria-label="Formulario de recuperación de contraseña">

              <div class="mb-4">
                <label for="emailUsuario" class="form-label fw-semibold">
                  <i class="bi bi-envelope me-1" aria-hidden="true"></i>Correo electrónico registrado
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="email"
                       id="emailUsuario" name="emailUsuario"
                       class="form-control"
                       placeholder="ejemplo@correo.com"
                       value="<?= htmlspecialchars($formData['emailUsuario']) ?>"
                       required
                       autofocus
                       autocomplete="email"
                       aria-required="true"
                       aria-describedby="emailUsuario-ayuda"
                       maxlength="100"/>
                <div id="emailUsuario-ayuda" class="form-text">
                  Debe coincidir con el correo con el que te registraste.
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-send me-2" aria-hidden="true"></i>Enviar enlace de recuperación
              </button>

              <div class="my-4 divider-text">¿Recordaste tu contraseña?</div>

              <a href="login.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Volver a Iniciar Sesión
              </a>
            </form>
            <?php endif; ?>

          </div>

          <div class="col-lg-4 d-none d-lg-block">
            <div class="card border-0 shadow-sm p-4 h-100">

              <h2 class="h6 fw-bold text-primary mb-1">
                <i class="bi bi-question-circle-fill me-2" aria-hidden="true"></i>¿Cómo funciona?
              </h2>
              <p class="text-secondary small mb-3">
                El proceso de recuperación tiene tres pasos simples.
              </p>

              <ol class="steps-list" aria-label="Pasos para recuperar la contraseña">
                <li>
                  <div class="step-num" aria-hidden="true">1</div>
                  <span>
                    Ingresá el <strong>correo electrónico</strong> con el que
                    te registraste en SkyReserva.
                  </span>
                </li>
                <li>
                  <div class="step-num" aria-hidden="true">2</div>
                  <span>
                    Revisá tu bandeja de entrada. Te enviaremos un
                    <strong>enlace seguro</strong> que expira en 1 hora.
                  </span>
                </li>
                <li>
                  <div class="step-num" aria-hidden="true">3</div>
                  <span>
                    Hacé clic en el enlace e ingresá tu
                    <strong>nueva contraseña</strong> (máximo 8 caracteres).
                  </span>
                </li>
              </ol>

              <hr class="my-3"/>

              <div class="alert alert-light border small mb-0 p-3" role="note">
                <i class="bi bi-shield-check text-primary me-1" aria-hidden="true"></i>
                Por seguridad, el sistema <strong>no confirma</strong> si el correo
                está registrado o no, para proteger la privacidad de los usuarios.
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="bg-dark text-light py-5">
    <div class="container">
      <div class="row g-5">

        <div class="col-lg-3 col-md-6">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-airplane-fill text-primary" aria-hidden="true"></i>
            <span class="fw-bold fs-5">SkyReserva</span>
          </div>
          <p class="text-secondary small">
            Sistema de gestión de reservas de pasajes de avión.<br>
            UTN – Facultad Regional Rosario<br>
            Cátedra Entornos Gráficos 2026
          </p>
        </div>

        <nav class="col-lg-3 col-md-6" aria-label="Mapa del sitio">
          <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">
            <i class="bi bi-map me-1" aria-hidden="true"></i>Mapa del Sitio
          </h2>
          <ul class="sitemap-list" role="list">
            <li><a href="LandUsuarioNoRegistrado.html">Inicio</a></li>
            <li><a href="LandUsuarioNoRegistrado.html#seccion-aerolineas">Aerolíneas</a></li>
            <li><a href="LandUsuarioNoRegistrado.html#seccion-vuelos">Vuelos</a></li>
            <li><a href="registrarse_.php">Registrarse</a></li>
            <li><a href="login.php">Iniciar Sesión</a></li>
            <li><a href="recuperar_contrasena_.php" aria-current="page">Recuperar Contraseña</a></li>
          </ul>
        </nav>

        <nav class="col-lg-3 col-md-6" aria-label="Secciones del pasajero">
          <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">Mi Cuenta</h2>
          <ul class="sitemap-list" role="list">
            <li><a href="login.php">Mi Perfil</a></li>
            <li><a href="login.php">Buscar Vuelos</a></li>
            <li><a href="login.php">Mis Reservas</a></li>
            <li><a href="login.php">Historial de Compras</a></li>
            <li><a href="login.php">Ver Novedades</a></li>
          </ul>
        </nav>

        <nav class="col-lg-3 col-md-6" aria-label="Secciones de administración">
          <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">Administración</h2>
          <ul class="sitemap-list" role="list">
            <li><a href="login.php">Panel Administrador</a></li>
            <li><a href="login.php">Panel CEO Aerolínea</a></li>
            <li><a href="login.php">Gestión de Vuelos</a></li>
            <li><a href="login.php">Gestión de Promociones</a></li>
            <li><a href="login.php">Reportes</a></li>
          </ul>
        </nav>

      </div>
      <div class="border-top border-secondary mt-5 pt-4 d-flex justify-content-between flex-wrap gap-2">
        <small class="text-secondary">&copy; 2026 SkyReserva — Todos los derechos reservados.</small>
        <small class="text-secondary">UTN FRR · Entornos Gráficos</small>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmkYJfmIlCv0SSFCE5nEatGkWxlg"
          crossorigin="anonymous"></script>
</body>
</html>
