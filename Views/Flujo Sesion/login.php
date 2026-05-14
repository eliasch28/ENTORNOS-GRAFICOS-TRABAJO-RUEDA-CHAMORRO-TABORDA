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
  <link rel="stylesheet" href="../LandPage/EstilosLandUsuarioNoRegistrado.css"/>

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
    .user-type-item {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      padding: 0.75rem 0;
      border-bottom: 1px solid var(--bs-border-color);
    }
    .user-type-item:last-child { border-bottom: none; padding-bottom: 0; }
    .user-type-icon {
      width: 36px; height: 36px;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem; flex-shrink: 0;
    }
  </style>
</head>

<body class="bg-light">

  <?php include '../Header/headerNoIniciado.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="login-titulo">
      <div class="container">
        <div class="row justify-content-center g-4">

          <!-- Formulario -->
          <div class="col-md-8 col-lg-5">

            <div class="text-center mb-4">
              <i class="bi bi-shield-lock text-primary" style="font-size:3rem;" aria-hidden="true"></i>
              <h1 id="login-titulo" class="h3 fw-bold mt-2">Iniciar Sesión</h1>
              <p class="text-secondary">Ingresá con tu cuenta para acceder a VuelaLibre.</p>
            </div>

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

              <div class="my-4 divider-text">¿No tenés cuenta?</div>

              <a href="registrarse_.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-person-plus me-2" aria-hidden="true"></i>Crear una cuenta nueva
              </a>

            </form>
          </div>

          <!-- Panel informativo -->
          <div class="col-lg-4 d-none d-lg-block">
            <div class="card border-0 shadow-sm p-4 h-100">

              <h2 class="h6 fw-bold text-primary mb-1">
                <i class="bi bi-people-fill me-2" aria-hidden="true"></i>Tipos de usuario
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
                <a href="registrarse_.php" class="fw-semibold text-primary">Registrate acá</a>
                para comenzar a reservar vuelos.
              </div>

            </div>
          </div>

        </div>
      </div>
    </section>
  </main>

  <?php include '../Footer/footerNoIniciado.php'; ?>

</body>
</html>
