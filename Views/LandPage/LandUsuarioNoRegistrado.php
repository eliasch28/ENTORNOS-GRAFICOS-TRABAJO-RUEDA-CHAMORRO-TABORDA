<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="VuelaLibre – Sistema de gestión de reservas de pasajes de avión. UTN Facultad Regional Rosario, Cátedra Entornos Gráficos 2026." />
  <title>VuelaLibre – Inicio | Reservas de Vuelos</title>

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

    <section class="seccion-portada text-white bg-primary py-5"
             aria-labelledby="hero-titulo">
      <div class="container py-5">
        <div class="row">
          <div class="col-lg-7">
            <p class="text-white-50 text-uppercase small fw-semibold mb-2">
              <i class="bi bi-globe2 me-1" aria-hidden="true"></i>
              UTN FRR · Entornos Gráficos 2026
            </p>
            <h1 id="hero-titulo" class="display-4 fw-bold mb-3">
              Tu próximo vuelo,<br>a un clic de distancia
            </h1>
            <p class="lead text-white-50 mb-4">
              Buscá y reservá vuelos en las mejores aerolíneas, gestioná tus
              reservas y consultá tus compras desde un solo lugar.
            </p>
            <div class="d-flex gap-3 flex-wrap">
              <a href="#seccion-vuelos" class="btn btn-light btn-lg text-primary fw-semibold">
                <i class="bi bi-search me-2" aria-hidden="true"></i>Buscar Vuelos
              </a>
              <a href="../Flujo Sesion/registrarse_.php" class="btn btn-outline-light btn-lg">
                <i class="bi bi-person-plus me-2" aria-hidden="true"></i>Crear Cuenta
              </a>
            </div>
            <ul class="d-flex gap-5 mt-5 pt-4 border-top border-white border-opacity-25 list-unstyled"
                aria-label="Estadísticas del sistema">
              <li class="text-center">
                <span class="d-block fs-3 fw-bold">12+</span>
                <span class="text-white-50 small text-uppercase">Aerolíneas</span>
              </li>
              <li class="text-center">
                <span class="d-block fs-3 fw-bold">340+</span>
                <span class="text-white-50 small text-uppercase">Rutas</span>
              </li>
              <li class="text-center">
                <span class="d-block fs-3 fw-bold">98%</span>
                <span class="text-white-50 small text-uppercase">Satisfacción</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section id="seccion-aerolineas" class="py-5" aria-labelledby="aerolineas-titulo">
      <div class="container">
        <header class="mb-5">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolíneas
          </p>
          <h2 id="aerolineas-titulo" class="h2 fw-bold">Nuestras aerolíneas asociadas</h2>
          <p class="text-secondary">Trabajamos con las principales aerolíneas de la región.</p>
        </header>

        <ul class="row g-4 list-unstyled" role="list" aria-label="Lista de aerolíneas">

          <li class="col-sm-6 col-lg-3" role="listitem">
            <article class="card tarjeta-aerolinea h-100 border-0 shadow-sm" aria-label="Aerolíneas Argentinas">
              <div class="card-body d-flex flex-column p-4">
                <i class="bi bi-airplane text-primary fs-1 mb-3 icono-aerolinea" aria-hidden="true"></i>
                <p class="text-primary small text-uppercase fw-semibold mb-1">IATA: AA · Cód: 001</p>
                <h3 class="h6 fw-bold mb-1">Aerolíneas Argentinas</h3>
                <p class="text-secondary small mb-2">
                  <i class="bi bi-geo-alt-fill me-1" aria-hidden="true"></i>Argentina · AR
                </p>
                <p class="text-secondary small flex-grow-1">
                  La aerolínea de bandera argentina conecta las principales ciudades
                  del país y del mundo con vuelos directos e interconexiones.
                </p>
                <a href="#seccion-vuelos" class="btn btn-outline-primary btn-sm mt-3">
                  Ver vuelos de Aerolíneas Argentinas <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </article>
          </li>

          <li class="col-sm-6 col-lg-3" role="listitem">
            <article class="card tarjeta-aerolinea h-100 border-0 shadow-sm" aria-label="LATAM Airlines">
              <div class="card-body d-flex flex-column p-4">
                <i class="bi bi-globe2 text-primary fs-1 mb-3 icono-aerolinea" aria-hidden="true"></i>
                <p class="text-primary small text-uppercase fw-semibold mb-1">IATA: LA · Cód: 002</p>
                <h3 class="h6 fw-bold mb-1"><span lang="en">LATAM Airlines</span></h3>
                <p class="text-secondary small mb-2">
                  <i class="bi bi-geo-alt-fill me-1" aria-hidden="true"></i>Chile · CL
                </p>
                <p class="text-secondary small flex-grow-1">
                  El grupo aéreo más grande de Latinoamérica, con cobertura regional
                  y conexiones intercontinentales a Europa y Estados Unidos.
                </p>
                <a href="#seccion-vuelos" class="btn btn-outline-primary btn-sm mt-3">
                  Ver vuelos de LATAM <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </article>
          </li>

          <li class="col-sm-6 col-lg-3" role="listitem">
            <article class="card tarjeta-aerolinea h-100 border-0 shadow-sm" aria-label="Flybondi">
              <div class="card-body d-flex flex-column p-4">
                <i class="bi bi-lightning-charge text-primary fs-1 mb-3 icono-aerolinea" aria-hidden="true"></i>
                <p class="text-primary small text-uppercase fw-semibold mb-1">IATA: FO · Cód: 003</p>
                <h3 class="h6 fw-bold mb-1">Flybondi</h3>
                <p class="text-secondary small mb-2">
                  <i class="bi bi-geo-alt-fill me-1" aria-hidden="true"></i>Argentina · AR
                </p>
                <p class="text-secondary small flex-grow-1">
                  Aerolínea de bajo costo pionera en democratizar los vuelos, con
                  tarifas accesibles en rutas domésticas argentinas.
                </p>
                <a href="#seccion-vuelos" class="btn btn-outline-primary btn-sm mt-3">
                  Ver vuelos de Flybondi <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </article>
          </li>

          <li class="col-sm-6 col-lg-3" role="listitem">
            <article class="card tarjeta-aerolinea h-100 border-0 shadow-sm" aria-label="JetSmart">
              <div class="card-body d-flex flex-column p-4">
                <i class="bi bi-stars text-primary fs-1 mb-3 icono-aerolinea" aria-hidden="true"></i>
                <p class="text-primary small text-uppercase fw-semibold mb-1">IATA: JA · Cód: 004</p>
                <h3 class="h6 fw-bold mb-1"><span lang="en">JetSmart</span></h3>
                <p class="text-secondary small mb-2">
                  <i class="bi bi-geo-alt-fill me-1" aria-hidden="true"></i>Chile · CL
                </p>
                <p class="text-secondary small flex-grow-1">
                  <span lang="en">Ultra low cost</span> con presencia en Argentina, Chile, Perú y Colombia.
                  Ideal para viajeros que priorizan el precio.
                </p>
                <a href="#seccion-vuelos" class="btn btn-outline-primary btn-sm mt-3">
                  Ver vuelos de JetSmart <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </article>
          </li>

        </ul>
      </div>
    </section>

    <section id="seccion-vuelos" class="py-5 bg-white" aria-labelledby="vuelos-titulo">
      <div class="container">
        <header class="mb-5">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-airplane-fill me-1" aria-hidden="true"></i>Vuelos
          </p>
          <h2 id="vuelos-titulo" class="h2 fw-bold">Vuelos disponibles</h2>
          <p class="text-secondary">Encontrá el vuelo ideal. Iniciá sesión para reservar.</p>
        </header>

        <ul class="list-unstyled" role="list" aria-label="Lista de vuelos disponibles">

          <li class="mb-3" role="listitem">
            <article class="card tarjeta-vuelo border-0 shadow-sm"
                     aria-label="Vuelo 0001: Rosario a Buenos Aires, 15 de mayo 2026">
              <div class="card-body">
                <div class="row align-items-center g-3">
                  <div class="col-md-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                      <i class="bi bi-airplane me-1" aria-hidden="true"></i>Aer. Argentinas · 0001
                    </span>
                  </div>
                  <div class="col-md-5">
                    <div class="d-flex align-items-center">
                      <div class="text-center">
                        <div class="codigo-iata text-dark fw-bold">ROS</div>
                        <small class="text-secondary text-uppercase">Rosario</small>
                      </div>
                      <div class="linea-ruta" aria-hidden="true"></div>
                      <i class="bi bi-airplane-fill text-primary mx-2" aria-hidden="true"></i>
                      <div class="linea-ruta" aria-hidden="true"></div>
                      <div class="text-center">
                        <div class="codigo-iata text-dark fw-bold">EZE</div>
                        <small class="text-secondary text-uppercase">Buenos Aires</small>
                      </div>
                    </div>
                    <small class="text-secondary d-block mt-1">
                      <i class="bi bi-clock me-1" aria-hidden="true"></i>2h 10min · Directo
                    </small>
                  </div>
                  <div class="col-md-2 text-center">
                    <div class="text-secondary small">
                      <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                      <time datetime="2026-05-15T08:30">15 May 2026 · 08:30</time>
                    </div>
                    <div class="text-success small mt-1">
                      <i class="bi bi-people-fill me-1" aria-hidden="true"></i>32 asientos disp.
                    </div>
                  </div>
                  <div class="col-md-1 text-center">
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                      <i class="bi bi-tag-fill me-1" aria-hidden="true"></i>15% OFF
                    </span>
                  </div>
                  <div class="col-md-2 text-end">
                    <div class="fw-bold fs-5 text-dark">
                      <small class="text-secondary fw-normal fs-6">ARS</small> 48.500
                    </div>
                    <a href="../Flujo Sesion/login.php" class="btn btn-primary btn-sm mt-2">
                      Iniciar sesión para reservar
                    </a>
                  </div>
                </div>
              </div>
            </article>
          </li>

          <li class="mb-3" role="listitem">
            <article class="card tarjeta-vuelo border-0 shadow-sm"
                     aria-label="Vuelo 0002: Buenos Aires a Santiago, 18 de mayo 2026">
              <div class="card-body">
                <div class="row align-items-center g-3">
                  <div class="col-md-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                      <i class="bi bi-airplane me-1" aria-hidden="true"></i>LATAM · 0002
                    </span>
                  </div>
                  <div class="col-md-5">
                    <div class="d-flex align-items-center">
                      <div class="text-center">
                        <div class="codigo-iata text-dark fw-bold">AEP</div>
                        <small class="text-secondary text-uppercase">Bs. Aires</small>
                      </div>
                      <div class="linea-ruta" aria-hidden="true"></div>
                      <i class="bi bi-airplane-fill text-primary mx-2" aria-hidden="true"></i>
                      <div class="linea-ruta" aria-hidden="true"></div>
                      <div class="text-center">
                        <div class="codigo-iata text-dark fw-bold">SCL</div>
                        <small class="text-secondary text-uppercase">Santiago</small>
                      </div>
                    </div>
                    <small class="text-secondary d-block mt-1">
                      <i class="bi bi-clock me-1" aria-hidden="true"></i>3h 50min · Directo
                    </small>
                  </div>
                  <div class="col-md-2 text-center">
                    <div class="text-secondary small">
                      <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                      <time datetime="2026-05-18T14:15">18 May 2026 · 14:15</time>
                    </div>
                    <div class="text-warning small mt-1">
                      <i class="bi bi-people-fill me-1" aria-hidden="true"></i>8 asientos disp.
                    </div>
                  </div>
                  <div class="col-md-1 text-center">
                    <span class="text-secondary small">Sin promo</span>
                  </div>
                  <div class="col-md-2 text-end">
                    <div class="fw-bold fs-5 text-dark">
                      <small class="text-secondary fw-normal fs-6">ARS</small> 124.900
                    </div>
                    <a href="../Flujo Sesion/login.php" class="btn btn-primary btn-sm mt-2">
                      Iniciar sesión para reservar
                    </a>
                  </div>
                </div>
              </div>
            </article>
          </li>

        </ul>

        <div class="text-center mt-3">
          <a href="../Flujo Sesion/login.php" class="btn btn-outline-primary">
            Iniciar sesión para ver todos los vuelos <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </section>

    <section class="py-5 bg-primary text-white text-center" aria-labelledby="cta-titulo">
      <div class="container py-3">
        <h2 id="cta-titulo" class="display-6 fw-bold mb-3">¿Listo para despegar?</h2>
        <p class="lead text-white-50 mb-4">
          Creá tu cuenta gratis y empezá a explorar vuelos y promociones exclusivas.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <a href="../Flujo Sesion/registrarse_.php" class="btn btn-light btn-lg text-primary fw-semibold">
            <i class="bi bi-person-plus-fill me-2" aria-hidden="true"></i>Registrarse gratis
          </a>
          <a href="../Flujo Sesion/login.php" class="btn btn-outline-light btn-lg">
            <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Ya tengo cuenta
          </a>
        </div>
      </div>
    </section>

  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>

