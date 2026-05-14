<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Gestioná tu perfil en VuelaLibre. Actualizá tu información personal." />
  <title>Mi Perfil | VuelaLibre – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="../LandPage/EstilosLandUsuarioNoRegistrado.css"/>

  <style>
    .avatar-circle {
      width: 80px; height: 80px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 2rem; font-weight: 700; flex-shrink: 0;
    }
    .profile-data-row {
      display: flex; align-items: flex-start; gap: 0.75rem;
      padding: 0.75rem 0;
      border-bottom: 1px solid var(--bs-border-color);
    }
    .profile-data-row:last-child { border-bottom: none; padding-bottom: 0; }
    .profile-data-label {
      font-size: 0.78rem; text-transform: uppercase;
      letter-spacing: 0.06em; color: var(--bs-secondary-color);
      font-weight: 600; min-width: 110px; flex-shrink: 0;
    }
    .profile-data-value { font-size: 0.92rem; word-break: break-word; }
    .form-section-title {
      font-size: 0.78rem; text-transform: uppercase;
      letter-spacing: 0.1em; font-weight: 600;
      color: var(--bs-secondary-color);
      border-bottom: 1px solid var(--bs-border-color);
      padding-bottom: 0.4rem; margin-bottom: 1rem;
    }
  </style>
</head>

<body class="bg-light">

  <?php include '../Header/headerIniciado.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="perfil-titulo">
      <div class="container">

        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-person-circle me-1" aria-hidden="true"></i>Mi cuenta
          </p>
          <h1 id="perfil-titulo" class="h3 fw-bold mb-0">Mi Perfil</h1>
          <p class="text-secondary">Visualizá y actualizá tu información personal.</p>
        </div>

        <div class="row g-4">

          <!-- Columna izquierda: resumen -->
          <div class="col-lg-4">

            <div class="card border-0 shadow-sm p-4 mb-4">
              <div class="d-flex align-items-center gap-3 mb-4">
                <div class="avatar-circle bg-primary text-white" aria-hidden="true">J</div>
                <div>
                  <div class="fw-bold fs-5">juan_perez</div>
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    Cliente / Pasajero
                  </span>
                </div>
              </div>

              <dl aria-label="Datos del perfil">
                <div class="profile-data-row">
                  <dt class="profile-data-label">
                    <i class="bi bi-hash me-1" aria-hidden="true"></i>Código
                  </dt>
                  <dd class="profile-data-value mb-0">1</dd>
                </div>
                <div class="profile-data-row">
                  <dt class="profile-data-label">
                    <i class="bi bi-person me-1" aria-hidden="true"></i>Usuario
                  </dt>
                  <dd class="profile-data-value mb-0">juan_perez</dd>
                </div>
                <div class="profile-data-row">
                  <dt class="profile-data-label">
                    <i class="bi bi-envelope me-1" aria-hidden="true"></i>Email
                  </dt>
                  <dd class="profile-data-value mb-0">juan@correo.com</dd>
                </div>
                <div class="profile-data-row">
                  <dt class="profile-data-label">
                    <i class="bi bi-telephone me-1" aria-hidden="true"></i>Teléfono
                  </dt>
                  <dd class="profile-data-value mb-0">+54 341 555-1234</dd>
                </div>
              </dl>
            </div>

            <!-- Accesos rápidos -->
            <div class="card border-0 shadow-sm p-4">
              <h2 class="h6 fw-bold text-primary mb-3">
                <i class="bi bi-grid-fill me-2" aria-hidden="true"></i>Accesos rápidos
              </h2>
              <nav aria-label="Accesos rápidos del usuario">
                <ul class="sitemap-list" role="list">
                  <li>
                    <a href="buscar_vuelos.php">
                      <i class="bi bi-search me-2 text-primary" aria-hidden="true"></i>Buscar Vuelos
                    </a>
                  </li>
                  <li>
                    <a href="mis_reservas.php">
                      <i class="bi bi-calendar2-check me-2 text-primary" aria-hidden="true"></i>Mis Reservas
                    </a>
                  </li>
                  <li>
                    <a href="historial_compras.php">
                      <i class="bi bi-clock-history me-2 text-primary" aria-hidden="true"></i>Historial de Compras
                    </a>
                  </li>
                  <li>
                    <a href="novedades.php">
                      <i class="bi bi-megaphone me-2 text-primary" aria-hidden="true"></i>Ver Novedades
                    </a>
                  </li>
                </ul>
              </nav>
            </div>

          </div><!-- /col izquierda -->


          <!-- Columna derecha: formulario -->
          <div class="col-lg-8">
            <form action="mi_perfil.php" method="post"
                  class="card border-0 shadow-sm p-4"
                  aria-label="Formulario de edición de perfil">

              <!-- Datos personales -->
              <p class="form-section-title">
                <i class="bi bi-person me-1" aria-hidden="true"></i>Datos personales
              </p>

              <div class="row g-3 mb-4">

                <div class="col-md-6">
                  <label for="nombreUsuario" class="form-label fw-semibold">
                    Nombre de usuario
                  </label>
                  <input type="text" id="nombreUsuario" class="form-control"
                         value="juan_perez" readonly aria-readonly="true"
                         aria-describedby="nombreUsuario-nota"/>
                  <div id="nombreUsuario-nota" class="form-text">
                    <i class="bi bi-lock me-1" aria-hidden="true"></i>
                    El nombre de usuario no puede modificarse.
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="tipoUsuario" class="form-label fw-semibold">
                    Tipo de usuario
                  </label>
                  <input type="text" id="tipoUsuario" class="form-control"
                         value="Cliente / Pasajero" readonly aria-readonly="true"/>
                  <div class="form-text">
                    <i class="bi bi-lock me-1" aria-hidden="true"></i>
                    El tipo es asignado por el sistema.
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="emailUsuario" class="form-label fw-semibold">
                    <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                    Correo electrónico
                    <span class="text-danger" aria-hidden="true">*</span>
                  </label>
                  <input type="email" id="emailUsuario" name="emailUsuario"
                         class="form-control" value="juan@correo.com"
                         required autocomplete="email"
                         aria-required="true" maxlength="100"/>
                </div>

                <div class="col-md-6">
                  <label for="telefonoUsuario" class="form-label fw-semibold">
                    <i class="bi bi-telephone me-1" aria-hidden="true"></i>
                    Teléfono
                    <span class="text-danger" aria-hidden="true">*</span>
                  </label>
                  <input type="tel" id="telefonoUsuario" name="telefonoUsuario"
                         class="form-control" value="+54 341 555-1234"
                         required autocomplete="tel"
                         aria-required="true" maxlength="20"/>
                </div>

              </div>

              <!-- Cambio de contraseña -->
              <p class="form-section-title">
                <i class="bi bi-lock me-1" aria-hidden="true"></i>
                Cambiar contraseña
                <span class="fw-normal text-secondary" style="font-size:.85em;">
                  — Opcional. Dejá los campos vacíos si no querés cambiarla.
                </span>
              </p>

              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label for="claveActual" class="form-label fw-semibold">Contraseña actual</label>
                  <input type="password" id="claveActual" name="claveActual"
                         class="form-control"
                         placeholder="Ingresá tu contraseña actual"
                         autocomplete="current-password"
                         aria-describedby="claveActual-ayuda"
                         maxlength="8"/>
                  <div id="claveActual-ayuda" class="form-text">
                    Requerida solo si querés cambiar tu contraseña.
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="claveNueva" class="form-label fw-semibold">Nueva contraseña</label>
                  <input type="password" id="claveNueva" name="claveNueva"
                         class="form-control"
                         placeholder="Máximo 8 caracteres"
                         autocomplete="new-password"
                         aria-describedby="claveNueva-ayuda"
                         pattern=".{1,8}"
                         title="La nueva contraseña debe tener entre 1 y 8 caracteres"
                         maxlength="8"/>
                  <div id="claveNueva-ayuda" class="form-text">
                    Máximo 8 caracteres (modelo de datos de la cátedra).
                  </div>
                </div>
              </div>

              <p class="text-secondary small mb-3">
                <span class="text-danger" aria-hidden="true">*</span>
                Los campos marcados son obligatorios.
              </p>

              <div class="d-flex gap-3 flex-wrap">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-floppy-fill me-2" aria-hidden="true"></i>Guardar cambios
                </button>
                <a href="../LandPage/LandUsuarioNoRegistrado.php" class="btn btn-outline-secondary">
                  <i class="bi bi-x-circle me-2" aria-hidden="true"></i>Cancelar
                </a>
              </div>

            </form>
          </div><!-- /col derecha -->

        </div><!-- /row -->
      </div>
    </section>
  </main>

  <?php include '../Footer/footerIniciado.php'; ?>

</body>
</html>
