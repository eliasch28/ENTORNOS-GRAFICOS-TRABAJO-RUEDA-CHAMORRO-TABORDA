<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Creá tu cuenta en VuelaLibre para buscar y reservar vuelos." />
  <title>Registrarse | VuelaLibre – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="../LandPage/EstilosLandUsuarioNoRegistrado.css"/>

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

  <?php include '../Header/headerNoIniciado.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="registro-titulo">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">

            <div class="text-center mb-4">
              <i class="bi bi-person-circle text-primary"
                 style="font-size:3rem;" aria-hidden="true"></i>
              <h1 id="registro-titulo" class="h3 fw-bold mt-2">Crear una cuenta</h1>
              <p class="text-secondary">Completá el formulario para registrarte en VuelaLibre.</p>
            </div>

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
                       required autofocus
                       autocomplete="username"
                       aria-required="true"
                       aria-describedby="nombreUsuario-ayuda"
                       maxlength="100"/>
                <div id="nombreUsuario-ayuda" class="form-text">Máximo 100 caracteres.</div>
              </div>

              <!-- Contraseña -->
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
                       title="La contraseña debe tener entre 1 y 8 caracteres"
                       maxlength="8"/>
                <div id="claveUsuario-ayuda" class="form-text">
                  Máximo 8 caracteres (modelo de datos de la cátedra).
                </div>
              </div>

              <!-- Tipo de usuario -->
              <fieldset class="mb-3">
                <legend class="form-label fw-semibold mb-2">
                  <i class="bi bi-person-badge me-1" aria-hidden="true"></i>
                  Tipo de usuario
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
                             value="usuario" class="visually-hidden" required/>
                    </label>
                  </div>
                  <div class="col-6">
                    <label class="tipo-card d-flex flex-column align-items-center p-3 text-center w-100"
                           for="tipo-aerolinea">
                      <i class="bi bi-building-fill text-primary fs-2 mb-2" aria-hidden="true"></i>
                      <span class="fw-semibold">CEO de Aerolínea</span>
                      <small class="text-secondary mt-1">Gestión de vuelos y promociones</small>
                      <input type="radio" id="tipo-aerolinea" name="tipoUsuario"
                             value="aerolinea" class="visually-hidden"/>
                    </label>
                  </div>
                </div>
                <div class="alert alert-warning py-2 mt-3 mb-0 small" role="note">
                  <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                  Los registros de <strong>CEO de Aerolínea</strong> requieren
                  aprobación del Administrador.
                </div>
              </fieldset>

              <!-- Email -->
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
                       required
                       autocomplete="email"
                       aria-required="true"
                       aria-describedby="emailUsuario-ayuda"
                       maxlength="100"/>
                <div id="emailUsuario-ayuda" class="form-text">
                  Los Clientes recibirán un enlace de validación en esta dirección.
                </div>
              </div>

              <!-- Teléfono -->
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
                       required
                       autocomplete="tel"
                       aria-required="true"
                       maxlength="20"/>
              </div>

              <p class="text-secondary small mb-3">
                <span class="text-danger" aria-hidden="true">*</span> Todos los campos son obligatorios.
              </p>

              <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="bi bi-person-check-fill me-2" aria-hidden="true"></i>Crear cuenta
              </button>

              <div class="my-4 divider-text">¿Ya tenés cuenta?</div>

              <a href="login.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Iniciar Sesión
              </a>

            </form>

          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include '../Footer/footerNoIniciado.php'; ?>

</body>
</html>
