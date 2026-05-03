<?php
/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  login.php — SkyReserva                                     ║
 * ║  UTN · Facultad Regional Rosario · Entornos Gráficos 2026   ║
 * ╠══════════════════════════════════════════════════════════════╣
 * ║  Descripción: Formulario de inicio de sesión.               ║
 * ║  Usuarios: Administrador, CEO de Aerolínea, Cliente.        ║
 * ║  Redireccionamiento: Usuarios no autenticados que intenten  ║
 * ║  acceder a páginas protegidas son enviados aquí.            ║
 * ║  Sin JavaScript — solo PHP, HTML5 y Bootstrap estándar.     ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

/* ──────────────────────────────────────────────────────────────
   BLOQUE 1: INICIALIZACIÓN DE VARIABLES DE ESTADO
────────────────────────────────────────────────────────────── */
$loginExitoso  = false;
$errorLogin    = '';
$formData      = ['nombreUsuario' => ''];

/*
 * Mensaje contextual de redirección.
 * Cuando otra página redirige al usuario no autenticado a login.php,
 * puede pasar el parámetro GET 'msg' para mostrar una explicación.
 * Ejemplo: login.php?msg=reserva
 * Solo se aceptan valores predefinidos para evitar XSS.
 */
$mensajesRedireccion = [
    'reserva'  => 'Debés iniciar sesión para poder reservar un vuelo.',
    'perfil'   => 'Debés iniciar sesión para acceder a tu perfil.',
    'historial'=> 'Debés iniciar sesión para ver tu historial de compras.',
    'reservas' => 'Debés iniciar sesión para gestionar tus reservas.',
];
$msgKey         = $_GET['msg'] ?? '';
$msgRedireccion = $mensajesRedireccion[$msgKey] ?? '';


/* ──────────────────────────────────────────────────────────────
   BLOQUE 2: PROCESAMIENTO DEL FORMULARIO (POST)
────────────────────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* 2.1 – Sanitización de entradas
       La clave NO se imprime nunca; se compara con password_verify().
       nombreUsuario se sanitiza para mostrar en el form si hay error. */
    $nombreUsuario = trim(htmlspecialchars($_POST['nombreUsuario'] ?? ''));
    $claveUsuario  = $_POST['claveUsuario'] ?? '';

    $formData = ['nombreUsuario' => $nombreUsuario];

    /* 2.2 – Validaciones básicas del servidor */
    if (empty($nombreUsuario) || empty($claveUsuario)) {
        $errorLogin = 'Completá el nombre de usuario y la contraseña para continuar.';
    }

    /* 2.3 – Autenticación contra la base de datos */
    if (empty($errorLogin)) {
        $errorLogin = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Iniciá sesión en SkyReserva para gestionar tus reservas de vuelos. UTN FRR, Cátedra Entornos Gráficos 2026." />
  <title>Iniciar Sesión | SkyReserva – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="EstilosLandUsuarioNoRegistrado.css"/>

  <style>
    /* Estilos exclusivos de login.php — sin dependencias JS */

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

    /* Íconos de tipo de usuario en el panel informativo */
    .user-type-item {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      padding: 0.75rem 0;
      border-bottom: 1px solid var(--bs-border-color);
    }
    .user-type-item:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }
    .user-type-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }
  </style>
</head>

<body class="bg-light">

  <a class="skip-link btn btn-primary btn-sm" href="#contenido-principal">
    Saltar al contenido principal
  </a>

  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary"
         aria-label="Navegación principal">
      <div class="container">

        <a class="navbar-brand fw-bold d-flex align-items-center gap-2"
           href="LandUsuarioNoRegistrado.html"
           aria-label="SkyReserva — Ir a la página de inicio">
          <i class="bi bi-airplane-fill" aria-hidden="true"></i>
          SkyReserva
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
            <a href="login.php"
               class="btn btn-light btn-sm text-primary fw-semibold"
               aria-current="page">
              <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
              Iniciar Sesión
            </a>
            <a href="registrarse_.php" class="btn btn-outline-light btn-sm">
              <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>
              Registrarse
            </a>
          </div>
        </div>

      </div>
    </nav>
  </header>


  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="login-titulo">
      <div class="container">
        <div class="row justify-content-center g-4">

          <!-- Columna principal: formulario -->
          <div class="col-md-8 col-lg-5">

            <div class="text-center mb-4">
              <i class="bi bi-shield-lock text-primary"
                 style="font-size:3rem;" aria-hidden="true"></i>
              <h1 id="login-titulo" class="h3 fw-bold mt-2">
                Iniciar Sesión
              </h1>
              <p class="text-secondary">
                Ingresá con tu cuenta para acceder a SkyReserva.
              </p>
            </div>


            <!-- Mensaje de redirección (ej: "Debés loguearte para reservar") -->
            <?php if (!empty($msgRedireccion)): ?>
              <div class="alert alert-info d-flex align-items-center gap-2" role="status">
                <i class="bi bi-info-circle-fill flex-shrink-0" aria-hidden="true"></i>
                <?= htmlspecialchars($msgRedireccion) ?>
              </div>
            <?php endif; ?>

            <!-- Error de autenticación -->
            <?php if (!empty($errorLogin)): ?>
              <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0" aria-hidden="true"></i>
                <?= htmlspecialchars($errorLogin) ?>
              </div>
            <?php endif; ?>


            <!-- Formulario de login -->
            <form action="login.php" method="post"
                  class="card border-0 shadow-sm p-4 p-md-5"
                  aria-label="Formulario de inicio de sesión">

              <!-- Nombre de usuario -->
              <div class="mb-3">
                <label for="nombreUsuario" class="form-label fw-semibold">
                  <i class="bi bi-person me-1" aria-hidden="true"></i>
                  Nombre de usuario
                </label>
                <input type="text"
                       id="nombreUsuario" name="nombreUsuario"
                       class="form-control"
                       placeholder="Tu nombre de usuario"
                       value="<?= htmlspecialchars($formData['nombreUsuario']) ?>"
                       required
                       autofocus
                       autocomplete="username"
                       aria-required="true"
                       maxlength="100"/>
              </div>

              <!-- Contraseña -->
              <div class="mb-2">
                <label for="claveUsuario" class="form-label fw-semibold">
                  <i class="bi bi-lock me-1" aria-hidden="true"></i>
                  Contraseña
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

              <!-- Recuperar contraseña -->
              <div class="text-end mb-4">
                <a href="#"
                   class="text-secondary small"
                   aria-label="Ir a la página de recuperación de contraseña">
                  ¿Olvidaste tu contraseña?
                </a>
              </div>

              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>
                Ingresar
              </button>

              <div class="my-4 divider-text">¿No tenés cuenta?</div>

              <a href="registrarse_.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-person-plus me-2" aria-hidden="true"></i>
                Crear una cuenta nueva
              </a>

            </form>

          </div><!-- /.col formulario -->


          <!-- Columna secundaria: panel informativo (visible en pantallas medianas+) -->
          <div class="col-lg-4 d-none d-lg-block">

            <div class="card border-0 shadow-sm p-4 h-100">

              <h2 class="h6 fw-bold text-primary mb-1">
                <i class="bi bi-people-fill me-2" aria-hidden="true"></i>
                Tipos de usuario
              </h2>
              <p class="text-secondary small mb-3">
                Cada perfil tiene acceso a distintas funciones del sistema.
              </p>

              <div class="user-type-item">
                <div class="user-type-icon bg-danger-subtle text-danger">
                  <i class="bi bi-gear-fill" aria-hidden="true"></i>
                </div>
                <div>
                  <div class="fw-semibold small">Administrador</div>
                  <div class="text-secondary" style="font-size:.8rem;">
                    Gestiona aerolíneas, aprueba promociones y genera reportes.
                  </div>
                </div>
              </div>

              <div class="user-type-item">
                <div class="user-type-icon bg-warning-subtle text-warning">
                  <i class="bi bi-building-fill" aria-hidden="true"></i>
                </div>
                <div>
                  <div class="fw-semibold small">CEO de Aerolínea</div>
                  <div class="text-secondary" style="font-size:.8rem;">
                    Crea y gestiona vuelos y promociones de su aerolínea.
                  </div>
                </div>
              </div>

              <div class="user-type-item">
                <div class="user-type-icon bg-primary-subtle text-primary">
                  <i class="bi bi-person-fill" aria-hidden="true"></i>
                </div>
                <div>
                  <div class="fw-semibold small">Cliente / Pasajero</div>
                  <div class="text-secondary" style="font-size:.8rem;">
                    Busca vuelos, realiza reservas y consulta su historial.
                  </div>
                </div>
              </div>

              <hr class="my-3"/>

              <div class="alert alert-light border small mb-0 p-3" role="note">
                <i class="bi bi-info-circle text-primary me-1" aria-hidden="true"></i>
                ¿Aún no tenés cuenta?
                <a href="registrarse_.php" class="fw-semibold text-primary">
                  Registrate acá
                </a>
                para comenzar a reservar vuelos.
              </div>

            </div>

          </div><!-- /.col informativo -->

        </div><!-- /.row -->
      </div><!-- /.container -->
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
            <li><a href="login.php" aria-current="page">Iniciar Sesión</a></li>
            <li><a href="#">Recuperar Contraseña</a></li>
            <li><a href="#">Mapa de Sitio completo</a></li>
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

  <!-- Bootstrap JS — solo para el navbar toggler en mobile -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmkYJfmIlCv0SSFCE5nEatGkWxlg"
          crossorigin="anonymous"></script>
</body>
</html>
