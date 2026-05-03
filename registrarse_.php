<?php
$registroExitoso = false;
$tipoRegistrado  = '';
$errores         = [];
$formData        = [
    'nombreUsuario'   => '',
    'tipoUsuario'     => '',
    'emailUsuario'    => '',
    'telefonoUsuario' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombreUsuario   = trim(htmlspecialchars($_POST['nombreUsuario']   ?? ''));
    $claveUsuario    = $_POST['claveUsuario'] ?? '';
    $tipoUsuario     = trim(htmlspecialchars($_POST['tipoUsuario']     ?? ''));
    $emailUsuario    = trim(htmlspecialchars($_POST['emailUsuario']    ?? ''));
    $telefonoUsuario = trim(htmlspecialchars($_POST['telefonoUsuario'] ?? ''));
    $formData = [
        'nombreUsuario'   => $nombreUsuario,
        'tipoUsuario'     => $tipoUsuario,
        'emailUsuario'    => $emailUsuario,
        'telefonoUsuario' => $telefonoUsuario,
    ];

    if (empty($nombreUsuario)) {
        $errores[] = 'El nombre de usuario es obligatorio.';
    }
    if (strlen($claveUsuario) < 1 || strlen($claveUsuario) > 8) {
        $errores[] = 'La clave debe tener entre 1 y 8 caracteres.';
    }
    if (!in_array($tipoUsuario, ['usuario', 'aerolinea'])) {
        $errores[] = 'Debe seleccionar un tipo de usuario válido.';
    }
    if (!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo electrónico ingresado no es válido.';
    }
    if (empty($telefonoUsuario)) {
        $errores[] = 'El teléfono es obligatorio.';
    }

    if (empty($errores)) {
        $claveHash = password_hash($claveUsuario, PASSWORD_DEFAULT);
        $registroExitoso = true;
        $tipoRegistrado  = $tipoUsuario;
        $formData        = ['nombreUsuario'=>'','tipoUsuario'=>'','emailUsuario'=>'','telefonoUsuario'=>''];

    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Creá tu cuenta en SkyReserva para buscar y reservar vuelos. UTN FRR, Cátedra Entornos Gráficos 2026." />
  <title>Registrarse | SkyReserva – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="EstilosLandUsuarioNoRegistrado.css"/>

  <style>
    .tipo-card {
      cursor: pointer;
      border: 2px solid var(--bs-border-color);
      border-radius: var(--bs-border-radius);
      transition: border-color 0.2s ease, background-color 0.2s ease;
    }
    .tipo-card:hover {
      border-color: var(--bs-primary);
      background-color: var(--bs-primary-bg-subtle);
    }
    .tipo-card:has(input[type="radio"]:checked) {
      border-color: var(--bs-primary);
      background-color: var(--bs-primary-bg-subtle);
    }
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
            <a href="registrarse_.php"
               class="btn btn-light btn-sm text-primary fw-semibold"
               aria-current="page">
              <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>Registrarse
            </a>
          </div>
        </div>
      </div>
    </nav>
  </header>


  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="registro-titulo">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">

            <div class="text-center mb-4">
              <i class="bi bi-person-circle text-primary"
                 style="font-size:3rem;" aria-hidden="true"></i>
              <h1 id="registro-titulo" class="h3 fw-bold mt-2">
                Crear una cuenta
              </h1>
              <p class="text-secondary">
                Completá el formulario para registrarte en SkyReserva.
              </p>
            </div>

            <?php if ($registroExitoso && $tipoRegistrado === 'usuario'): ?>
              <div class="alert alert-success d-flex align-items-start gap-3" role="alert">
                <i class="bi bi-envelope-check-fill fs-4 flex-shrink-0 mt-1" aria-hidden="true"></i>
                <div>
                  <h2 class="alert-heading h6 fw-bold mb-1">¡Registro exitoso!</h2>
                  <p class="mb-3">
                    Tu cuenta fue creada correctamente. Te enviamos un
                    <strong>correo de validación</strong> a tu dirección de email.
                    Revisá tu bandeja de entrada y hacé clic en el enlace para activar tu cuenta.
                  </p>
                  <a href="login.php" class="btn btn-success btn-sm">
                    <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>Ir a Iniciar Sesión
                  </a>
                </div>
              </div>

            <?php elseif ($registroExitoso && $tipoRegistrado === 'aerolinea'): ?>
              <div class="alert alert-info d-flex align-items-start gap-3" role="alert">
                <i class="bi bi-hourglass-split fs-4 flex-shrink-0 mt-1" aria-hidden="true"></i>
                <div>
                  <h2 class="alert-heading h6 fw-bold mb-1">
                    Solicitud enviada — Pendiente de aprobación
                  </h2>
                  <p class="mb-0">
                    Tu solicitud de registro como <strong>CEO de Aerolínea</strong>
                    fue recibida. El Administrador la revisará y recibirás una
                    notificación cuando tu cuenta esté activa.
                  </p>
                </div>
              </div>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
              <div class="alert alert-danger" role="alert">
                <h2 class="alert-heading h6 fw-bold mb-2">
                  <i class="bi bi-exclamation-triangle-fill me-1" aria-hidden="true"></i>
                  Se encontraron los siguientes errores:
                </h2>
                <ul class="mb-0 ps-3">
                  <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <?php if (!$registroExitoso): ?>
            <form action="registrarse_.php" method="post"
                  class="card border-0 shadow-sm p-4 p-md-5"
                  aria-label="Formulario de registro de nuevo usuario">

              <!-- Nombre de usuario -->
              <div class="mb-3">
                <label for="nombreUsuario" class="form-label fw-semibold">
                  <i class="bi bi-person me-1" aria-hidden="true"></i>
                  Nombre de usuario
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="text"
                       id="nombreUsuario" name="nombreUsuario"
                       class="form-control"
                       placeholder="Ej: juan_perez"
                       value="<?= htmlspecialchars($formData['nombreUsuario']) ?>"
                       required autofocus
                       autocomplete="username"
                       aria-required="true"
                       aria-describedby="nombreUsuario-ayuda"
                       maxlength="100"/>
                <div id="nombreUsuario-ayuda" class="form-text">
                  Máximo 100 caracteres.
                </div>
              </div>

              <div class="mb-3">
                <label for="claveUsuario" class="form-label fw-semibold">
                  <i class="bi bi-lock me-1" aria-hidden="true"></i>
                  Contraseña
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="password"
                       id="claveUsuario" name="claveUsuario"
                       class="form-control"
                       placeholder="Máximo 8 caracteres"
                       required
                       autocomplete="new-password"
                       aria-required="true"
                       aria-describedby="claveUsuario-ayuda"
                       pattern=".{1,8}"
                       title="La contraseña debe tener entre 1 y 8 caracteres (modelo de datos de la cátedra)"
                       maxlength="8"/>
                <div id="claveUsuario-ayuda" class="form-text">
                  Máximo 8 caracteres (modelo de datos de la cátedra).
                </div>
              </div>

              <fieldset class="mb-3">
                <legend class="form-label fw-semibold mb-2">
                  <i class="bi bi-person-badge me-1" aria-hidden="true"></i>Tipo de usuario
                  <span class="text-danger" aria-hidden="true">*</span>
                </legend>
                <div class="row g-3">
                  <div class="col-6">
                    <label class="tipo-card d-flex flex-column align-items-center p-3 text-center w-100"
                           for="tipo-usuario">
                      <i class="bi bi-person-fill text-primary fs-2 mb-2" aria-hidden="true"></i>
                      <span class="fw-semibold">Cliente / Pasajero</span>
                      <small class="text-secondary mt-1">Buscá y reservá vuelos</small>
                      <input type="radio" id="tipo-usuario" name="tipoUsuario"
                             value="usuario" class="visually-hidden" required
                             <?= ($formData['tipoUsuario'] === 'usuario') ? 'checked' : '' ?>/>
                    </label>
                  </div>
                  <div class="col-6">
                    <label class="tipo-card d-flex flex-column align-items-center p-3 text-center w-100"
                           for="tipo-aerolinea">
                      <i class="bi bi-building-fill text-primary fs-2 mb-2" aria-hidden="true"></i>
                      <span class="fw-semibold">CEO de Aerolínea</span>
                      <small class="text-secondary mt-1">Gestión de vuelos y promociones</small>
                      <input type="radio" id="tipo-aerolinea" name="tipoUsuario"
                             value="aerolinea" class="visually-hidden"
                             <?= ($formData['tipoUsuario'] === 'aerolinea') ? 'checked' : '' ?>/>
                    </label>
                  </div>
                </div>
                
                <div class="alert alert-warning py-2 mt-3 mb-0 small" role="note">
                  <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                  Los registros de <strong>CEO de Aerolínea</strong> requieren
                  aprobación del Administrador antes de poder acceder al sistema.
                </div>
              </fieldset>

              <div class="mb-3">
                <label for="emailUsuario" class="form-label fw-semibold">
                  <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                  Correo electrónico
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="email"
                       id="emailUsuario" name="emailUsuario"
                       class="form-control"
                       placeholder="ejemplo@correo.com"
                       value="<?= htmlspecialchars($formData['emailUsuario']) ?>"
                       required
                       autocomplete="email"
                       aria-required="true"
                       aria-describedby="emailUsuario-ayuda"
                       maxlength="100"/>
                <div id="emailUsuario-ayuda" class="form-text">
                  Los Clientes recibirán un enlace de validación en esta dirección.
                </div>
              </div>

              <div class="mb-4">
                <label for="telefonoUsuario" class="form-label fw-semibold">
                  <i class="bi bi-telephone me-1" aria-hidden="true"></i>
                  Teléfono
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input type="tel"
                       id="telefonoUsuario" name="telefonoUsuario"
                       class="form-control"
                       placeholder="Ej: +54 341 555-1234"
                       value="<?= htmlspecialchars($formData['telefonoUsuario']) ?>"
                       required
                       autocomplete="tel"
                       aria-required="true"
                       maxlength="20"/>
              </div>

              <p class="text-secondary small mb-3">
                <span class="text-danger" aria-hidden="true">*</span>
                Todos los campos son obligatorios.
              </p>

              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-person-check-fill me-2" aria-hidden="true"></i>Crear cuenta
              </button>

              <div class="my-4 divider-text">¿Ya tenés cuenta?</div>

              <a href="login.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Iniciar Sesión
              </a>
            </form>
            <?php endif; ?>

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
            <li><a href="registrarse_.php" aria-current="page">Registrarse</a></li>
            <li><a href="login.php">Iniciar Sesión</a></li>
            <li><a href="recuperar_contrasena_.php">Recuperar Contraseña</a></li>
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
