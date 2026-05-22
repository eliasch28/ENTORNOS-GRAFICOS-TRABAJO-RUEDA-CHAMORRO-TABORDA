<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Bienvenido a SkyReserva. Buscá vuelos, gestioná tus reservas y consultá novedades." />
  <title>Inicio | SkyReserva – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="EstilosLandUsuarioNoRegistrado.css"/>

  <style>
    /* Estilos exclusivos de LandUsuarioRegistrado.php */

    /* Hero más compacto que el de usuario no registrado */
    .hero-registrado {
      background: linear-gradient(135deg,
        rgba(13, 110, 253, 0.92) 0%,
        rgba(10, 88, 202, 0.85) 100%);
      padding: 3rem 0;
    }

    /* Cards de acceso rápido */
    .acceso-card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      text-decoration: none;
      display: block;
      height: 100%;
    }
    .acceso-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(0,0,0,.12) !important;
    }

    /* Ícono grande en cada acceso rápido */
    .acceso-icono {
      font-size: 2rem;
      line-height: 1;
      margin-bottom: 0.75rem;
    }

    /* Fila de dato en el resumen de reserva pendiente */
    .detalle-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.4rem 0;
      border-bottom: 1px solid var(--bs-border-color);
      font-size: 0.85rem;
    }
    .detalle-row:last-child { border-bottom: none; }
    .detalle-label { color: var(--bs-secondary-color); }
  </style>
</head>

<body class="bg-light">

  <?php include '../Header/headerIniciado.php'; ?>

  <main id="contenido-principal" tabindex="-1">

    <!-- ── HERO BIENVENIDA ───────────────────────────────── -->
    <section class="hero-registrado text-white bg-primary"
             aria-labelledby="hero-titulo">
      <div class="container">
        <div class="row align-items-center g-4">

          <div class="col-lg-7">
            <p class="text-white-50 text-uppercase small fw-semibold mb-2">
              <i class="bi bi-person-check-fill me-1" aria-hidden="true"></i>
              Sesión iniciada
            </p>
            <!--
              TODO: Reemplazar "juan_perez" por
              <?= htmlspecialchars($_SESSION['nombreUsuario']) ?>
              cuando se integre la autenticación con BD.
            -->
            <h1 id="hero-titulo" class="display-5 fw-bold mb-2">
              ¡Bienvenido, juan_perez!
            </h1>
            <p class="lead text-white-50 mb-4">
              ¿A dónde volamos hoy? Buscá tu próximo vuelo o gestioná tus reservas.
            </p>
            <div class="d-flex gap-3 flex-wrap">
              <a href="../Funciones Usuario/buscar_vuelos.php"
                 class="btn btn-light btn-lg text-primary fw-semibold">
                <i class="bi bi-search me-2" aria-hidden="true"></i>Buscar Vuelos
              </a>
              <a href="../Funciones Usuario/mis_reservas.php"
                 class="btn btn-outline-light btn-lg">
                <i class="bi bi-calendar2-check me-2" aria-hidden="true"></i>Mis Reservas
              </a>
            </div>
          </div>

          <!-- Resumen rápido de estado de cuenta -->
          <div class="col-lg-5">
            <div class="card border-0 shadow">
              <div class="card-body p-4">
                <h2 class="h6 fw-bold text-primary mb-3">
                  <i class="bi bi-speedometer2 me-2" aria-hidden="true"></i>
                  Mi resumen
                </h2>
                <!--
                  TODO: Poblar dinámicamente con PHP consultando
                  la tabla RESERVAS para el usuario de sesión.
                -->
                <div class="detalle-row">
                  <span class="detalle-label">Reservas pendientes</span>
                  <span class="fw-bold text-warning">1</span>
                </div>
                <div class="detalle-row">
                  <span class="detalle-label">Reservas confirmadas</span>
                  <span class="fw-bold text-success">1</span>
                </div>
                <div class="detalle-row">
                  <span class="detalle-label">Compras totales</span>
                  <span class="fw-bold">2</span>
                </div>
                <div class="detalle-row">
                  <span class="detalle-label">Novedades vigentes</span>
                  <span class="fw-bold text-primary">2</span>
                </div>
                <a href="../Funciones Usuario/mi_perfil.php"
                   class="btn btn-outline-primary btn-sm w-100 mt-3">
                  <i class="bi bi-person-circle me-1" aria-hidden="true"></i>
                  Ver mi perfil completo
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>


    <!-- ── BUSCADOR ──────────────────────────────────────── -->
    <section class="py-4 bg-white shadow-sm" role="search"
             aria-labelledby="buscador-titulo">
      <div class="container">
        <h2 id="buscador-titulo" class="h6 fw-semibold text-primary mb-3">
          <i class="bi bi-search me-2" aria-hidden="true"></i>Buscar vuelos disponibles
        </h2>
        <form action="../Funciones Usuario/buscar_vuelos.php" method="get"
              aria-label="Formulario de búsqueda de vuelos">
          <div class="row g-3 align-items-end">
            <div class="col-md-3">
              <label for="origenVuelo" class="form-label fw-semibold small">
                <i class="bi bi-geo me-1" aria-hidden="true"></i>Origen
              </label>
              <input type="text" id="origenVuelo" name="origenVuelo"
                     class="form-control" placeholder="Ciudad o aeropuerto"
                     autocomplete="off"/>
            </div>
            <div class="col-md-3">
              <label for="destinoVuelo" class="form-label fw-semibold small">
                <i class="bi bi-geo-fill me-1" aria-hidden="true"></i>Destino
              </label>
              <input type="text" id="destinoVuelo" name="destinoVuelo"
                     class="form-control" placeholder="Ciudad o aeropuerto"
                     autocomplete="off"/>
            </div>
            <div class="col-md-2">
              <label for="fechaSalidaVuelo" class="form-label fw-semibold small">
                <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Fecha
              </label>
              <input type="date" id="fechaSalidaVuelo" name="fechaSalidaVuelo"
                     class="form-control"/>
            </div>
            <div class="col-md-2">
              <label for="codAerolinea" class="form-label fw-semibold small">
                <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolínea
              </label>
              <select id="codAerolinea" name="codAerolinea" class="form-select">
                <option value="">Todas</option>
                <option value="1">Aerolíneas Argentinas</option>
                <option value="2">LATAM Airlines</option>
                <option value="3">Flybondi</option>
                <option value="4">JetSmart</option>
              </select>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search me-1" aria-hidden="true"></i>Buscar
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>


    <!-- ── ACCESOS RÁPIDOS ───────────────────────────────── -->
    <section class="py-5" aria-labelledby="accesos-titulo">
      <div class="container">
        <header class="mb-4">
          <h2 id="accesos-titulo" class="h5 fw-bold mb-0">Accesos rápidos</h2>
          <p class="text-secondary small">Todo lo que necesitás desde un solo lugar.</p>
        </header>

        <ul class="row g-3 list-unstyled" role="list">

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/buscar_vuelos.php"
               class="acceso-card card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Buscar Vuelos">
              <div class="acceso-icono text-primary">
                <i class="bi bi-search" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Buscar Vuelos</div>
            </a>
          </li>

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/mis_reservas.php"
               class="acceso-card card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Mis Reservas">
              <div class="acceso-icono text-primary">
                <i class="bi bi-calendar2-check" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Mis Reservas</div>
            </a>
          </li>

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/historial_compras.php"
               class="acceso-card card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Historial de Compras">
              <div class="acceso-icono text-primary">
                <i class="bi bi-clock-history" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Historial</div>
            </a>
          </li>

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/novedades.php"
               class="acceso-card card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Novedades">
              <div class="acceso-icono text-primary">
                <i class="bi bi-megaphone" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Novedades</div>
            </a>
          </li>

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/promociones.php"
               class="acceso-card card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Promociones">
              <div class="acceso-icono text-primary">
                <i class="bi bi-tag-fill" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Promociones</div>
            </a>
          </li>

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/mi_perfil.php"
               class="acceso-card card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Mi Perfil">
              <div class="acceso-icono text-primary">
                <i class="bi bi-person-circle" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Mi Perfil</div>
            </a>
          </li>

        </ul>
      </div>
    </section>


    <!-- ── RESERVA PENDIENTE (alerta contextual) ─────────── -->
    <!--
      TODO: Mostrar condicionalmente con PHP si el usuario
      tiene al menos una reserva en estado 'pendiente de pago'.
      if ($reservasPendientes > 0) { mostrar este bloque }
    -->
    <section class="pb-4" aria-labelledby="pendiente-titulo">
      <div class="container">
        <div class="alert alert-warning d-flex align-items-start gap-3 shadow-sm"
             role="note">
          <i class="bi bi-hourglass-split fs-4 flex-shrink-0 mt-1"
             aria-hidden="true"></i>
          <div>
            <h2 id="pendiente-titulo" class="alert-heading h6 fw-bold mb-1">
              Tenés una reserva pendiente de pago
            </h2>
            <p class="mb-2 small">
              La <strong>Reserva N° 0001</strong> (ROS → EZE, 15 May 2026)
              está esperando confirmación de pago.
            </p>
            <a href="../Funciones Usuario/mis_reservas.php"
               class="btn btn-warning btn-sm">
              <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>
              Ver mis reservas
            </a>
          </div>
        </div>
      </div>
    </section>


    <!-- ── PROMOCIONES DESTACADAS ────────────────────────── -->
    <section class="py-5 bg-white" aria-labelledby="promos-titulo">
      <div class="container">
        <header class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-tag-fill me-1" aria-hidden="true"></i>Promociones
          </p>
          <h2 id="promos-titulo" class="h5 fw-bold mb-0">Promociones vigentes</h2>
          <p class="text-secondary small">Descuentos aprobados por el Administrador.</p>
        </header>

        <ul class="row g-3 list-unstyled" role="list"
            aria-label="Promociones destacadas">

          <li class="col-md-6 col-lg-4" role="listitem">
            <article class="card border-0 shadow-sm h-100"
                     aria-label="Promoción Flybondi: 30% de descuento">
              <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <span class="badge bg-success-subtle text-success border border-success-subtle fs-6 px-3">
                    30% OFF
                  </span>
                  <small class="text-secondary">Cód: 001</small>
                </div>
                <h3 class="h6 fw-bold mb-1">Flybondi</h3>
                <p class="text-secondary small mb-3">
                  30% de descuento en todos los pasajes durante el mes de mayo.
                  Válido para vuelos domésticos.
                </p>
                <a href="../Funciones Usuario/buscar_vuelos.php?codAerolinea=3"
                   class="btn btn-outline-primary btn-sm">
                  Ver vuelos <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </article>
          </li>

          <li class="col-md-6 col-lg-4" role="listitem">
            <article class="card border-0 shadow-sm h-100"
                     aria-label="Promoción Aerolíneas Argentinas: 15% de descuento">
              <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <span class="badge bg-success-subtle text-success border border-success-subtle fs-6 px-3">
                    15% OFF
                  </span>
                  <small class="text-secondary">Cód: 002</small>
                </div>
                <h3 class="h6 fw-bold mb-1">Aerolíneas Argentinas</h3>
                <p class="text-secondary small mb-3">
                  15% de descuento adicional para pasajeros que realizan
                  su primera compra en el sistema.
                </p>
                <a href="../Funciones Usuario/buscar_vuelos.php?codAerolinea=1"
                   class="btn btn-outline-primary btn-sm">
                  Ver vuelos <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </article>
          </li>

          <li class="col-md-6 col-lg-4" role="listitem">
            <article class="card border-0 shadow-sm h-100 d-flex flex-column justify-content-center align-items-center p-4 text-center"
                     aria-label="Ver todas las promociones">
              <i class="bi bi-tag text-primary mb-3"
                 style="font-size:2rem;" aria-hidden="true"></i>
              <h3 class="h6 fw-bold mb-2">¿Querés ver todas?</h3>
              <p class="text-secondary small mb-3">
                Consultá el listado completo de promociones vigentes.
              </p>
              <a href="../Funciones Usuario/promociones.php"
                 class="btn btn-primary btn-sm">
                Ver todas las promociones
              </a>
            </article>
          </li>

        </ul>
      </div>
    </section>


    <!-- ── NOVEDADES RECIENTES ───────────────────────────── -->
    <section class="py-5" aria-labelledby="novedades-recientes-titulo">
      <div class="container">
        <header class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <p class="text-primary text-uppercase small fw-semibold mb-1">
              <i class="bi bi-megaphone me-1" aria-hidden="true"></i>Sistema
            </p>
            <h2 id="novedades-recientes-titulo" class="h5 fw-bold mb-0">
              Novedades recientes
            </h2>
          </div>
          <a href="../Funciones Usuario/novedades.php"
             class="btn btn-outline-primary btn-sm">
            Ver todas <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
          </a>
        </header>

        <ul class="list-unstyled" role="list" aria-label="Novedades recientes">

          <li class="mb-3" role="listitem">
            <article class="card border-0 shadow-sm border-start border-4 border-primary">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start gap-2">
                  <div>
                    <h3 class="h6 fw-bold mb-1">
                      <i class="bi bi-tools text-primary me-1" aria-hidden="true"></i>
                      Mantenimiento programado — Aeropuerto de Rosario
                    </h3>
                    <p class="text-secondary small mb-0">
                      Operaciones reducidas el domingo 17 de mayo entre las 02:00
                      y las 06:00 hs. Vuelos afectados serán reprogramados.
                    </p>
                  </div>
                  <small class="text-secondary text-nowrap">
                    <time datetime="2026-05-01">1 May</time>
                  </small>
                </div>
              </div>
            </article>
          </li>

          <li class="mb-3" role="listitem">
            <article class="card border-0 shadow-sm border-start border-4 border-primary">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start gap-2">
                  <div>
                    <h3 class="h6 fw-bold mb-1">
                      <i class="bi bi-building-fill-add text-primary me-1" aria-hidden="true"></i>
                      Nueva aerolínea incorporada al sistema
                    </h3>
                    <p class="text-secondary small mb-0">
                      Aerolíneas del Sur se incorpora a SkyReserva con rutas
                      domésticas en la Patagonia a partir del 10 de mayo.
                    </p>
                  </div>
                  <small class="text-secondary text-nowrap">
                    <time datetime="2026-05-08">8 May</time>
                  </small>
                </div>
              </div>
            </article>
          </li>

        </ul>
      </div>
    </section>

  </main>

  <?php include '../Footer/footerIniciado.php'; ?>

</body>
</html>
