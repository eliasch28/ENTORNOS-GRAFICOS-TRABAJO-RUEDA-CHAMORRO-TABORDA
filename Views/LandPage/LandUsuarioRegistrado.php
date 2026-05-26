<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Bienvenido a VuelaLibre. Buscá vuelos, gestioná tus reservas y consultá novedades." />
  <title>Inicio | VuelaLibre – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>

<body class="bg-light">

  <?php include '../Header/headerIniciado.php'; ?>

  <main id="contenido-principal" tabindex="-1">

    <section class="portada-registrado text-white bg-primary"
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
                <div class="fila-detalle">
                  <span class="etiqueta-detalle">Reservas pendientes</span>
                  <span class="fw-bold text-warning">1</span>
                </div>
                <div class="fila-detalle">
                  <span class="etiqueta-detalle">Reservas confirmadas</span>
                  <span class="fw-bold text-success">1</span>
                </div>
                <div class="fila-detalle">
                  <span class="etiqueta-detalle">Compras totales</span>
                  <span class="fw-bold">2</span>
                </div>
                <div class="fila-detalle">
                  <span class="etiqueta-detalle">Novedades vigentes</span>
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

    <section class="py-5" aria-labelledby="accesos-titulo">
      <div class="container">
        <header class="mb-4">
          <h2 id="accesos-titulo" class="h5 fw-bold mb-0">Accesos rápidos</h2>
          <p class="text-secondary small">Todo lo que necesitás desde un solo lugar.</p>
        </header>

        <ul class="row g-3 list-unstyled" role="list">

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/buscar_vuelos.php"
               class="tarjeta-acceso card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Buscar Vuelos">
              <div class="icono-acceso text-primary">
                <i class="bi bi-search" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Buscar Vuelos</div>
            </a>
          </li>

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/mis_reservas.php"
               class="tarjeta-acceso card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Mis Reservas">
              <div class="icono-acceso text-primary">
                <i class="bi bi-calendar2-check" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Mis Reservas</div>
            </a>
          </li>

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/historial_compras.php"
               class="tarjeta-acceso card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Historial de Compras">
              <div class="icono-acceso text-primary">
                <i class="bi bi-clock-history" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Historial</div>
            </a>
          </li>

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/novedades.php"
               class="tarjeta-acceso card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Novedades">
              <div class="icono-acceso text-primary">
                <i class="bi bi-megaphone" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Novedades</div>
            </a>
          </li>

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/promociones.php"
               class="tarjeta-acceso card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Promociones">
              <div class="icono-acceso text-primary">
                <i class="bi bi-tag-fill" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Promociones</div>
            </a>
          </li>

          <li class="col-6 col-md-4 col-lg-2" role="listitem">
            <a href="../Funciones Usuario/mi_perfil.php"
               class="tarjeta-acceso card border-0 shadow-sm p-3 text-center"
               aria-label="Ir a Mi Perfil">
              <div class="icono-acceso text-primary">
                <i class="bi bi-person-circle" aria-hidden="true"></i>
              </div>
              <div class="fw-semibold small">Mi Perfil</div>
            </a>
          </li>

        </ul>
      </div>
    </section>

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
              Ir a Mis Reservas para pagar
            </a>
          </div>
        </div>
      </div>
    </section>

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
                  Ver vuelos de Flybondi con descuento <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
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
                  Ver vuelos de Aer. Argentinas con descuento <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </article>
          </li>

          <li class="col-md-6 col-lg-4" role="listitem">
            <article class="card border-0 shadow-sm h-100 d-flex flex-column justify-content-center align-items-center p-4 text-center"
                     aria-label="Ver todas las promociones vigentes">
              <i class="bi bi-tag text-primary mb-3 icono-grande-tarjeta" aria-hidden="true"></i>
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
            Ver todas las novedades <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
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
                      Aerolíneas del Sur se incorpora a VuelaLibre con rutas
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
