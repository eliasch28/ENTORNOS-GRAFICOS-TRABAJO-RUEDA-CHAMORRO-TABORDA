<?php
/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  registrarse.php — SkyReserva                               ║
 * ║  UTN · Facultad Regional Rosario                            ║
 * ║  Cátedra: Entornos Gráficos 2026                            ║
 * ╠══════════════════════════════════════════════════════════════╣
 * ║  Descripción: Formulario de registro de nuevos usuarios.    ║
 * ║  Tipos soportados: Cliente y CEO de Aerolínea.              ║
 * ║  Los registros de tipo CEO requieren aprobación del Admin.  ║
 * ║  Los registros de tipo Cliente reciben un email de          ║
 * ║  validación para activar la cuenta (Regla de Negocio #7).   ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

/* ──────────────────────────────────────────────────────────────
   BLOQUE 1: INICIALIZACIÓN DE VARIABLES DE ESTADO
   Se usan para controlar qué mensaje mostrar en la vista.
────────────────────────────────────────────────────────────── */
$registroExitoso  = false;   // true cuando la inserción fue OK
$tipoRegistrado   = '';      // guarda el tipoUsuario registrado
$errores          = [];      // array de mensajes de error del servidor
$formData         = [        // repopula el form si hay errores
    'nombreUsuario'   => '',
    'tipoUsuario'     => '',
    'emailUsuario'    => '',
    'telefonoUsuario' => '',
];


/* ──────────────────────────────────────────────────────────────
   BLOQUE 2: PROCESAMIENTO DEL FORMULARIO (POST)
────────────────────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* 2.1 – Sanitización básica de entradas
       En producción usar prepared statements; NUNCA insertar
       datos sin sanitizar directamente en una consulta SQL.     */
    $nombreUsuario   = trim(htmlspecialchars($_POST['nombreUsuario']   ?? ''));
    $claveUsuario    = $_POST['claveUsuario']   ?? '';          // NO se imprime; solo se hashea
    $tipoUsuario     = trim(htmlspecialchars($_POST['tipoUsuario']     ?? ''));
    $emailUsuario    = trim(htmlspecialchars($_POST['emailUsuario']    ?? ''));
    $telefonoUsuario = trim(htmlspecialchars($_POST['telefonoUsuario'] ?? ''));

    // Repopular el form para no perder datos en caso de error
    $formData = [
        'nombreUsuario'   => $nombreUsuario,
        'tipoUsuario'     => $tipoUsuario,
        'emailUsuario'    => $emailUsuario,
        'telefonoUsuario' => $telefonoUsuario,
    ];

    /* 2.2 – Validaciones del lado servidor
       (complementan las validaciones HTML5 del cliente)         */
    if (empty($nombreUsuario)) {
        $errores[] = 'El nombre de usuario es obligatorio.';
    }
    if (strlen($claveUsuario) < 1 || strlen($claveUsuario) > 8) {
        $errores[] = 'La clave debe tener entre 1 y 8 caracteres.';
    }
    if (!in_array($tipoUsuario, ['usuario', 'aerolinea'])) {
        $errores[] = 'El tipo de usuario seleccionado no es válido.';
    }
    if (!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo electrónico ingresado no es válido.';
    }
    if (empty($telefonoUsuario)) {
        $errores[] = 'El teléfono es obligatorio.';
    }

    /* 2.3 – Si no hay errores de validación, continuar con la BD */
    if (empty($errores)) {

        /* ── CONEXIÓN A LA BASE DE DATOS ──────────────────────
           TODO (implementar en integración backend):

           Opción A — MySQLi procedural:
           ┌─────────────────────────────────────────────────┐
           │ $conn = mysqli_connect(                         │
           │     'localhost',   // host                      │
           │     'db_user',     // usuario de BD             │
           │     'db_pass',     // contraseña de BD          │
           │     'skyreserva'   // nombre de la base         │
           │ );                                              │
           │ if (!$conn) {                                   │
           │     die('Error de conexión: '                   │
           │         . mysqli_connect_error());              │
           │ }                                               │
           └─────────────────────────────────────────────────┘

           Opción B — PDO (recomendada para producción):
           ┌─────────────────────────────────────────────────┐
           │ $dsn = 'mysql:host=localhost;dbname=skyreserva' │
           │       . ';charset=utf8mb4';                     │
           │ $pdo = new PDO($dsn, 'db_user', 'db_pass', [   │
           │     PDO::ATTR_ERRMODE =>                        │
           │         PDO::ERRMODE_EXCEPTION,                 │
           │     PDO::ATTR_DEFAULT_FETCH_MODE =>             │
           │         PDO::FETCH_ASSOC,                       │
           │ ]);                                             │
           └─────────────────────────────────────────────────┘
        ── FIN CONEXIÓN ─────────────────────────────────── */


        /* ── HASH DE CONTRASEÑA ────────────────────────────
           NUNCA guardar contraseñas en texto plano.
           password_hash() usa bcrypt por defecto.           */
        $claveHash = password_hash($claveUsuario, PASSWORD_DEFAULT);


        /* ── INSERCIÓN EN LA TABLA USUARIOS ───────────────
           TODO (implementar en integración backend):

           La tabla USUARIOS del modelo de datos tiene:
             codUsuario       INT  AUTO_INCREMENT PK
             nombreUsuario    VARCHAR(100)
             claveUsuario     VARCHAR(255)   ← almacenar el hash
             tipoUsuario      VARCHAR(20)    ← 'usuario' | 'aerolinea' | 'administrador'
             emailUsuario     VARCHAR(100)
             telefonoUsuario  VARCHAR(20)

           Con MySQLi (prepared statement):
           ┌─────────────────────────────────────────────────┐
           │ $sql = "INSERT INTO USUARIOS                    │
           │         (nombreUsuario, claveUsuario,           │
           │          tipoUsuario, emailUsuario,             │
           │          telefonoUsuario)                       │
           │         VALUES (?, ?, ?, ?, ?)";                │
           │                                                 │
           │ $stmt = mysqli_prepare($conn, $sql);            │
           │ mysqli_stmt_bind_param(                         │
           │     $stmt, 'sssss',                             │
           │     $nombreUsuario, $claveHash,                 │
           │     $tipoUsuario, $emailUsuario,                │
           │     $telefonoUsuario                            │
           │ );                                              │
           │ mysqli_stmt_execute($stmt);                     │
           │ $nuevoId = mysqli_insert_id($conn);             │
           └─────────────────────────────────────────────────┘

           Con PDO (prepared statement):
           ┌─────────────────────────────────────────────────┐
           │ $stmt = $pdo->prepare(                          │
           │     "INSERT INTO USUARIOS                       │
           │      (nombreUsuario, claveUsuario,              │
           │       tipoUsuario, emailUsuario,                │
           │       telefonoUsuario)                          │
           │      VALUES (:nombre, :clave,                   │
           │              :tipo, :email, :tel)"              │
           │ );                                              │
           │ $stmt->execute([                                │
           │     ':nombre' => $nombreUsuario,                │
           │     ':clave'  => $claveHash,                    │
           │     ':tipo'   => $tipoUsuario,                  │
           │     ':email'  => $emailUsuario,                 │
           │     ':tel'    => $telefonoUsuario,              │
           │ ]);                                             │
           │ $nuevoId = $pdo->lastInsertId();                │
           └─────────────────────────────────────────────────┘
        ── FIN INSERCIÓN ────────────────────────────────── */


        /* ── ENVÍO DE CORREO DE VALIDACIÓN (solo Cliente) ─
           TODO (implementar en integración backend):

           Si el tipo es 'usuario' (Cliente/Pasajero), se debe
           enviar un email con un enlace de validación único.
           Se recomienda usar PHPMailer o la función mail() de PHP.

           Lógica sugerida:
           ┌─────────────────────────────────────────────────┐
           │ if ($tipoUsuario === 'usuario') {               │
           │     $token = bin2hex(random_bytes(32));         │
           │     // Guardar $token en tabla TOKENS_VALIDACION│
           │     // con $nuevoId y fecha de expiración       │
           │     $enlace = "https://tudominio.com/           │
           │                validar.php?token={$token}";     │
           │     mail(                                       │
           │         $emailUsuario,                          │
           │         'Validá tu cuenta en SkyReserva',       │
           │         "Hacé clic aquí para activar tu cuenta: │
           │          {$enlace}",                            │
           │         'From: noreply@skyreserva.com'          │
           │     );                                          │
           │ }                                               │
           └─────────────────────────────────────────────────┘

           Si el tipo es 'aerolinea' (CEO), NO se envía email;
           en cambio el Administrador recibe una notificación
           para aprobar o rechazar el registro.
        ── FIN ENVÍO EMAIL ──────────────────────────────── */


        /* Si todo fue bien, marcar éxito y guardar el tipo */
        $registroExitoso = true;
        $tipoRegistrado  = $tipoUsuario;

        // Limpiar el formData para no repoblar el form
        $formData = ['nombreUsuario'=>'','tipoUsuario'=>'','emailUsuario'=>'','telefonoUsuario'=>''];

    } // fin if empty($errores)

} // fin if POST
?>
<!DOCTYPE html>
<html lang="es">
<!--
  ╔══════════════════════════════════════════════════════════════╗
  ║  registrarse.php — Vista HTML                               ║
  ║  SkyReserva · UTN FRR · Entornos Gráficos 2026             ║
  ╠══════════════════════════════════════════════════════════════╣
  ║  Estándares:                                                ║
  ║  · HTML5 semántico (header/nav, main/section, footer)       ║
  ║  · WCAG 2.1 AA: skip-link, aria-*, label/for, autofocus     ║
  ║  · Bootstrap 5.3 — paleta estándar, sin hex personalizados  ║
  ║  · styles.css para heredar estilos generales del sitio      ║
  ╚══════════════════════════════════════════════════════════════╝
-->
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Creá tu cuenta en SkyReserva para buscar y reservar vuelos. UTN FRR, Cátedra Entornos Gráficos 2026." />

  <title>Registrarse | SkyReserva – UTN FRR</title>

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>

  <!--
    Hoja de estilos compartida del sitio.
    Hereda: .skip-link, .navbar border-bottom, .airline-card,
            .flight-card, .step-number, .sitemap-list, .fade-up
  -->
  <link rel="stylesheet" href="EstilosLandUsuarioNoRegistrado.css" type="text/css"/>

  <style>
    /*
     * Estilos EXCLUSIVOS de esta página.
     * Solo se agregan aquí los que Bootstrap no cubre y que
     * NO son reutilizables en otras páginas del sitio.
     */

    /* Indicador visual del tipo de usuario seleccionado */
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
    /* Estado activo cuando el radio interno está seleccionado */
    .tipo-card:has(input[type="radio"]:checked) {
      border-color: var(--bs-primary);
      background-color: var(--bs-primary-bg-subtle);
    }

    /* Separador "¿Ya tenés cuenta?" */
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

    /* Indicador de fortaleza de contraseña */
    .password-strength-bar {
      height: 4px;
      border-radius: 2px;
      transition: width 0.3s ease, background-color 0.3s ease;
    }
  </style>
</head>

<body class="bg-light">

  <!-- ════════════════════════════════════════════════════════
       HEADER / NAVBAR
       Idéntico al de index.html para coherencia visual.
       aria-current="page" NO está en ningún link porque
       esta es la página de registro, no la de inicio.
  ═════════════════════════════════════════════════════════ -->
  <header>
    <nav
      class="navbar navbar-expand-lg navbar-dark bg-primary"
      aria-label="Navegación principal"
    >
      <div class="container">

        <a
          class="navbar-brand fw-bold d-flex align-items-center gap-2"
          href="LandUsuarioNoRegistrado.html"
          aria-label="SkyReserva — Ir a la página de inicio"
        >
          <i class="bi bi-airplane-fill" aria-hidden="true"></i>
          SkyReserva
        </a>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#menuPrincipal"
          aria-controls="menuPrincipal"
          aria-expanded="false"
          aria-label="Abrir o cerrar menú de navegación"
        >
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
            <a href="login.php" class="btn btn-outline-light btn-sm"
               aria-label="Iniciar sesión en SkyReserva">
              <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
              Iniciar Sesión
            </a>
            <!--
              aria-current="page" aquí porque el usuario ya está
              en la página de registro.
            -->
            <a href="registrarse.php"
               class="btn btn-light btn-sm text-primary fw-semibold"
               aria-current="page"
               aria-label="Página actual: Registrarse">
              <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>
              Registrarse
            </a>
          </div>
        </div>
      </div>
    </nav>
  </header>


  <!-- ════════════════════════════════════════════════════════
       MAIN
  ═════════════════════════════════════════════════════════ -->
  <main id="contenido-principal" tabindex="-1">
    <section
      class="py-5"
      aria-labelledby="registro-titulo"
    >
      <div class="container">
        <div class="row justify-content-center">

          <!--
            col-md-8 col-lg-6: centrado y responsivo.
            En mobile ocupa el 100%; en tablet/desktop se estrecha.
          -->
          <div class="col-md-8 col-lg-6">

            <!-- ── Encabezado de sección ─────────────────── -->
            <div class="text-center mb-4">
              <div class="mb-3">
                <i class="bi bi-person-circle text-primary"
                   style="font-size:3rem;"
                   aria-hidden="true"></i>
              </div>
              <!--
                h1: único en la página.
                id referenciado por aria-labelledby del <section>.
              -->
              <h1 id="registro-titulo" class="h3 fw-bold">
                Crear una cuenta
              </h1>
              <p class="text-secondary">
                Completá el formulario para registrarte en SkyReserva.
              </p>
            </div>


            <!-- ── MENSAJES DE FEEDBACK ──────────────────── -->

            <?php if ($registroExitoso): ?>
              <!--
                ALERT ÉXITO — visible solo cuando el registro fue procesado.
                role="alert" hace que los lectores de pantalla lo anuncien
                automáticamente al aparecer en el DOM (WCAG 4.1.3).
              -->
              <?php if ($tipoRegistrado === 'usuario'): ?>
                <div
                  class="alert alert-success d-flex align-items-start gap-3"
                  role="alert"
                  aria-live="polite"
                >
                  <i class="bi bi-envelope-check-fill fs-4 flex-shrink-0 mt-1"
                     aria-hidden="true"></i>
                  <div>
                    <h2 class="alert-heading h6 fw-bold mb-1">
                      ¡Registro exitoso!
                    </h2>
                    <p class="mb-0">
                      Tu cuenta fue creada correctamente. Te enviamos un
                      <strong>correo de validación</strong> a tu dirección de email.
                      Por favor revisá tu bandeja de entrada (y la carpeta de spam)
                      y hacé clic en el enlace para activar tu cuenta.
                    </p>
                    <hr />
                    <a href="login.php" class="btn btn-success btn-sm">
                      <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
                      Ir a Iniciar Sesión
                    </a>
                  </div>
                </div>

              <?php elseif ($tipoRegistrado === 'aerolinea'): ?>
                <div
                  class="alert alert-info d-flex align-items-start gap-3"
                  role="alert"
                  aria-live="polite"
                >
                  <i class="bi bi-hourglass-split fs-4 flex-shrink-0 mt-1"
                     aria-hidden="true"></i>
                  <div>
                    <h2 class="alert-heading h6 fw-bold mb-1">
                      Solicitud enviada — Pendiente de aprobación
                    </h2>
                    <p class="mb-0">
                      Tu solicitud de registro como <strong>CEO de Aerolínea</strong>
                      fue recibida. El Administrador del sistema revisará y aprobará
                      tu cuenta. Recibirás una notificación cuando esté activa.
                    </p>
                  </div>
                </div>
              <?php endif; ?>

            <?php endif; ?>


            <?php if (!empty($errores)): ?>
              <!--
                ALERT ERRORES — lista de validaciones del servidor.
                aria-live="assertive" interrumpe al lector de pantalla
                para anunciar errores críticos (WCAG 4.1.3).
              -->
              <div
                class="alert alert-danger"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
              >
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


            <!-- ── FORMULARIO DE REGISTRO ────────────────── -->
            <!--
              action="" envía al mismo archivo (registrarse.php).
              method="post" para no exponer datos en la URL.
              novalidate NO se incluye: queremos que las validaciones
              HTML5 del navegador actúen como primera línea de defensa.
            -->
            <?php if (!$registroExitoso): ?>
            <form
              action="registrarse.php"
              method="post"
              aria-label="Formulario de registro de nuevo usuario"
              class="card border-0 shadow-sm p-4 p-md-5"
            >

              <!-- Campo: Nombre de usuario ──────────────── -->
              <div class="mb-3">
                <label for="nombreUsuario" class="form-label fw-semibold">
                  <i class="bi bi-person me-1" aria-hidden="true"></i>
                  Nombre de usuario
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input
                  type="text"
                  id="nombreUsuario"
                  name="nombreUsuario"
                  class="form-control"
                  placeholder="Ej: juan_perez"
                  value="<?= htmlspecialchars($formData['nombreUsuario']) ?>"
                  required
                  autofocus
                  autocomplete="username"
                  aria-required="true"
                  aria-describedby="nombreUsuario-ayuda"
                  maxlength="100"
                />
                <!--
                  aria-describedby enlaza el campo con su texto de ayuda.
                  Los lectores de pantalla leen el hint después del label.
                -->
                <div id="nombreUsuario-ayuda" class="form-text">
                  Solo letras, números y guión bajo. Máximo 100 caracteres.
                </div>
              </div>


              <!-- Campo: Contraseña ─────────────────────── -->
              <div class="mb-3">
                <label for="claveUsuario" class="form-label fw-semibold">
                  <i class="bi bi-lock me-1" aria-hidden="true"></i>
                  Contraseña
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <div class="input-group">
                  <input
                    type="password"
                    id="claveUsuario"
                    name="claveUsuario"
                    class="form-control"
                    placeholder="Máximo 8 caracteres"
                    required
                    autocomplete="new-password"
                    aria-required="true"
                    aria-describedby="claveUsuario-ayuda"
                    pattern=".{1,8}"
                    title="La contraseña debe tener entre 1 y 8 caracteres (requerimiento del modelo de datos UTN)"
                    maxlength="8"
                  />
                  <!--
                    Botón mostrar/ocultar contraseña.
                    type="button" evita que envíe el form.
                    aria-pressed cambia por JS para indicar estado.
                    aria-controls indica qué elemento controla.
                  -->
                  <button
                    type="button"
                    class="btn btn-outline-secondary"
                    id="toggle-clave"
                    aria-label="Mostrar u ocultar contraseña"
                    aria-pressed="false"
                    aria-controls="claveUsuario"
                  >
                    <i class="bi bi-eye" aria-hidden="true" id="toggle-clave-icon"></i>
                  </button>
                </div>
                <div id="claveUsuario-ayuda" class="form-text">
                  Máximo 8 caracteres (modelo de datos de la cátedra).
                </div>
                <!-- Barra de fortaleza (accesible: también hay texto) -->
                <div class="mt-2" aria-live="polite" aria-atomic="true">
                  <div
                    class="bg-secondary bg-opacity-25 rounded"
                    style="height:4px;"
                    role="progressbar"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    aria-valuenow="0"
                    aria-label="Fortaleza de contraseña"
                    id="strength-bar-wrap"
                  >
                    <div
                      id="strength-bar"
                      class="password-strength-bar"
                      style="width:0%;"
                    ></div>
                  </div>
                  <small id="strength-label" class="text-secondary"></small>
                </div>
              </div>


              <!-- Campo: Tipo de usuario ───────────────── -->
              <!--
                Se usa fieldset + legend para agrupar los radio buttons,
                lo que es la práctica correcta de accesibilidad (WCAG 1.3.1).
                Los lectores de pantalla anuncian la leyenda al entrar al grupo.
              -->
              <fieldset class="mb-3">
                <legend class="form-label fw-semibold mb-2">
                  <i class="bi bi-person-badge me-1" aria-hidden="true"></i>
                  Tipo de usuario
                  <span class="text-danger" aria-hidden="true">*</span>
                </legend>
                <div class="row g-3" role="radiogroup" aria-required="true">

                  <!-- Opción: Cliente/Pasajero -->
                  <div class="col-6">
                    <label
                      class="tipo-card d-flex flex-column align-items-center p-3 text-center w-100"
                      for="tipo-usuario"
                    >
                      <i class="bi bi-person-fill text-primary fs-2 mb-2" aria-hidden="true"></i>
                      <span class="fw-semibold">Cliente / Pasajero</span>
                      <small class="text-secondary mt-1">
                        Buscá y reservá vuelos
                      </small>
                      <input
                        type="radio"
                        id="tipo-usuario"
                        name="tipoUsuario"
                        value="usuario"
                        class="visually-hidden"
                        required
                        <?= ($formData['tipoUsuario'] === 'usuario') ? 'checked' : '' ?>
                      />
                    </label>
                  </div>

                  <!-- Opción: CEO de Aerolínea -->
                  <div class="col-6">
                    <label
                      class="tipo-card d-flex flex-column align-items-center p-3 text-center w-100"
                      for="tipo-aerolinea"
                    >
                      <i class="bi bi-building-fill text-primary fs-2 mb-2" aria-hidden="true"></i>
                      <span class="fw-semibold">CEO de Aerolínea</span>
                      <small class="text-secondary mt-1">
                        Gestión de vuelos y promociones
                      </small>
                      <input
                        type="radio"
                        id="tipo-aerolinea"
                        name="tipoUsuario"
                        value="aerolinea"
                        class="visually-hidden"
                        <?= ($formData['tipoUsuario'] === 'aerolinea') ? 'checked' : '' ?>
                      />
                    </label>
                  </div>

                </div>
                <!--
                  Alerta contextual que aparece si se selecciona CEO.
                  aria-live="polite" permite que el lector de pantalla
                  lo anuncie sin interrumpir lo que está leyendo.
                -->
                <div
                  id="aviso-ceo"
                  class="alert alert-warning alert-sm py-2 mt-2 d-none"
                  role="note"
                  aria-live="polite"
                >
                  <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                  Los registros de CEO requieren <strong>aprobación del Administrador</strong>
                  antes de poder acceder al sistema.
                </div>
              </fieldset>


              <!-- Campo: Email ──────────────────────────── -->
              <div class="mb-3">
                <label for="emailUsuario" class="form-label fw-semibold">
                  <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                  Correo electrónico
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input
                  type="email"
                  id="emailUsuario"
                  name="emailUsuario"
                  class="form-control"
                  placeholder="ejemplo@correo.com"
                  value="<?= htmlspecialchars($formData['emailUsuario']) ?>"
                  required
                  autocomplete="email"
                  aria-required="true"
                  aria-describedby="emailUsuario-ayuda"
                  maxlength="100"
                />
                <div id="emailUsuario-ayuda" class="form-text">
                  Los Clientes recibirán un enlace de validación en esta dirección.
                </div>
              </div>


              <!-- Campo: Teléfono ───────────────────────── -->
              <div class="mb-4">
                <label for="telefonoUsuario" class="form-label fw-semibold">
                  <i class="bi bi-telephone me-1" aria-hidden="true"></i>
                  Teléfono
                  <span class="text-danger" aria-hidden="true">*</span>
                </label>
                <input
                  type="tel"
                  id="telefonoUsuario"
                  name="telefonoUsuario"
                  class="form-control"
                  placeholder="Ej: +54 341 555-1234"
                  value="<?= htmlspecialchars($formData['telefonoUsuario']) ?>"
                  required
                  autocomplete="tel"
                  aria-required="true"
                  maxlength="20"
                />
              </div>


              <!-- Nota campos obligatorios -->
              <p class="text-secondary small mb-3">
                <span class="text-danger" aria-hidden="true">*</span>
                Todos los campos son obligatorios.
              </p>


              <!-- Botón de envío ────────────────────────── -->
              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-person-check-fill me-2" aria-hidden="true"></i>
                Crear cuenta
              </button>


              <!-- Separador ────────────────────────────── -->
              <div class="my-4 divider-text">¿Ya tenés cuenta?</div>

              <a
                href="login.php"
                class="btn btn-outline-secondary w-100"
                aria-label="Ir a la página de inicio de sesión"
              >
                <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>
                Iniciar Sesión
              </a>

            </form>
            <?php endif; ?>


          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container -->
    </section>
  </main>


  <!-- ════════════════════════════════════════════════════════
       FOOTER con MAPA DE SITIO
       Estructura idéntica a la de index.html para coherencia.
  ═════════════════════════════════════════════════════════ -->
  <footer class="bg-dark text-light py-5">
    <div class="container">
      <div class="row g-5">

        <!-- Identidad -->
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

        <!-- MAPA DE SITIO (requerido por la consigna) -->
        <nav class="col-lg-3 col-md-6" aria-label="Mapa del sitio">
          <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">
            <i class="bi bi-map me-1" aria-hidden="true"></i>
            Mapa del Sitio
          </h2>
          <ul class="sitemap-list" role="list">
            <li><a href="LandUsuarioNoRegistrado.html">Inicio</a></li>
            <li><a href="LandUsuarioNoRegistrado.html#seccion-aerolineas">Aerolíneas</a></li>
            <li><a href="LandUsuarioNoRegistrado.html#seccion-vuelos">Vuelos</a></li>
            <li>
              <a href="registrarse.php" aria-current="page">
                Registrarse
              </a>
            </li>
            <li><a href="login.php">Iniciar Sesión</a></li>
            <li><a href="forgot-password.php">Recuperar Contraseña</a></li>
            <li><a href="sitemap.html">Mapa de Sitio completo</a></li>
          </ul>
        </nav>

        <!-- Mi Cuenta -->
        <nav class="col-lg-3 col-md-6" aria-label="Secciones del pasajero">
          <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">
            Mi Cuenta
          </h2>
          <ul class="sitemap-list" role="list">
            <li><a href="login.php">Mi Perfil</a></li>
            <li><a href="login.php">Buscar Vuelos</a></li>
            <li><a href="login.php">Mis Reservas</a></li>
            <li><a href="login.php">Historial de Compras</a></li>
            <li><a href="login.php">Ver Novedades</a></li>
          </ul>
        </nav>

        <!-- Administración -->
        <nav class="col-lg-3 col-md-6" aria-label="Secciones de administración">
          <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">
            Administración
          </h2>
          <ul class="sitemap-list" role="list">
            <li><a href="login.php">Panel Administrador</a></li>
            <li><a href="login.php">Panel CEO Aerolínea</a></li>
            <li><a href="login.php">Gestión de Vuelos</a></li>
            <li><a href="login.php">Gestión de Promociones</a></li>
            <li><a href="login.php">Reportes</a></li>
          </ul>
        </nav>

      </div><!-- /.row -->

      <div class="border-top border-secondary mt-5 pt-4 d-flex justify-content-between flex-wrap gap-2">
        <small class="text-secondary">
          &copy; 2026 SkyReserva — Todos los derechos reservados.
        </small>
        <small class="text-secondary">UTN FRR · Entornos Gráficos</small>
      </div>
    </div>
  </footer>


  <!-- Bootstrap JS Bundle -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmkYJfmIlCv0SSFCE5nEatGkWxlg"
    crossorigin="anonymous"
  ></script>

  <script>
    /* ──────────────────────────────────────────────────────
       1. MOSTRAR/OCULTAR CONTRASEÑA
       Cambia type entre 'password' y 'text'.
       Actualiza aria-pressed y el ícono visualmente.
    ─────────────────────────────────────────────────────── */
    const toggleBtn  = document.getElementById('toggle-clave');
    const claveInput = document.getElementById('claveUsuario');
    const toggleIcon = document.getElementById('toggle-clave-icon');

    if (toggleBtn) {
      toggleBtn.addEventListener('click', () => {
        const isPassword = claveInput.type === 'password';
        claveInput.type  = isPassword ? 'text' : 'password';
        toggleBtn.setAttribute('aria-pressed', String(isPassword));
        toggleIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
      });
    }


    /* ──────────────────────────────────────────────────────
       2. INDICADOR DE FORTALEZA DE CONTRASEÑA
       Calcula una puntuación simple 0-3 y actualiza
       la barra de progreso y el texto descriptivo.
       aria-valuenow y aria-label se actualizan para a11y.
    ─────────────────────────────────────────────────────── */
    const strengthBar   = document.getElementById('strength-bar');
    const strengthWrap  = document.getElementById('strength-bar-wrap');
    const strengthLabel = document.getElementById('strength-label');

    const strengthLevels = [
      { label: '',           color: '',             pct: 0   },
      { label: 'Débil',      color: 'bg-danger',    pct: 33  },
      { label: 'Moderada',   color: 'bg-warning',   pct: 66  },
      { label: 'Fuerte',     color: 'bg-success',   pct: 100 },
    ];

    function calcStrength(pwd) {
      if (!pwd) return 0;
      let score = 1;
      if (/[A-Z]/.test(pwd) || /[0-9]/.test(pwd)) score++;
      if (/[^A-Za-z0-9]/.test(pwd)) score++;
      return Math.min(score, 3);
    }

    if (claveInput) {
      claveInput.addEventListener('input', () => {
        const lvl = calcStrength(claveInput.value);
        const s   = strengthLevels[lvl];

        // Limpiar clases de color previas
        strengthBar.className = 'password-strength-bar ' + s.color;
        strengthBar.style.width = s.pct + '%';

        strengthWrap.setAttribute('aria-valuenow', s.pct);
        strengthWrap.setAttribute('aria-label', 'Fortaleza de contraseña: ' + (s.label || 'sin evaluar'));
        strengthLabel.textContent = s.label;
        strengthLabel.className   = lvl === 1 ? 'text-danger small'
                                  : lvl === 2 ? 'text-warning small'
                                  : lvl === 3 ? 'text-success small'
                                  : 'text-secondary small';
      });
    }


    /* ──────────────────────────────────────────────────────
       3. AVISO CONTEXTUAL PARA CEO
       Muestra/oculta el alert informativo cuando el usuario
       selecciona la opción "CEO de Aerolínea".
       aria-live="polite" en el elemento ya maneja el anuncio.
    ─────────────────────────────────────────────────────── */
    const radiosCEO  = document.querySelectorAll('input[name="tipoUsuario"]');
    const avisoCEO   = document.getElementById('aviso-ceo');

    radiosCEO.forEach(radio => {
      radio.addEventListener('change', () => {
        if (avisoCEO) {
          avisoCEO.classList.toggle('d-none', radio.value !== 'aerolinea');
        }
      });
    });


    /* ──────────────────────────────────────────────────────
       4. ANIMACIONES FADE-UP (respeta prefers-reduced-motion)
    ─────────────────────────────────────────────────────── */
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!prefersReduced) {
      const obs = new IntersectionObserver(entries => {
        entries.forEach(e => {
          if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
      }, { threshold: 0.12 });
      document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
    } else {
      document.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));
    }
  </script>

</body>
</html>
