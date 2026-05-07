<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Buscá vuelos disponibles en SkyReserva por origen, destino, fecha y aerolínea." />
  <title>Buscar Vuelos | SkyReserva – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="../LandPage/EstilosLandUsuarioNoRegistrado.css"/>

  <style>
    .filtros-card {
      position: sticky;
      top: 1rem;
    }

    /* Badge de asientos con colores semánticos según disponibilidad */
    .asientos-alto  { color: var(--bs-success); }
    .asientos-medio { color: var(--bs-warning); }
    .asientos-bajo  { color: var(--bs-danger);  }

    /* Highlight del vuelo al hacer hover */
    .flight-card:hover {
      border-color: var(--bs-primary) !important;
    }
  </style>
</head>

<body class="bg-light">

  <?php include '../Header/headerIniciado.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="buscar-titulo">
      <div class="container">

        <!-- Encabezado de página -->
        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-search me-1" aria-hidden="true"></i>Vuelos
          </p>
          <h1 id="buscar-titulo" class="h3 fw-bold mb-0">Buscar Vuelos</h1>
          <p class="text-secondary">
            Filtrá por origen, destino, fecha y aerolínea para encontrar tu vuelo ideal.
          </p>
        </div>


        <div class="row g-4">

          <!-- ── COLUMNA IZQUIERDA: Filtros ──────────────── -->
          <div class="col-lg-3">
            <div class="card border-0 shadow-sm p-4 filtros-card">

              <h2 class="h6 fw-bold text-primary mb-3">
                <i class="bi bi-funnel-fill me-2" aria-hidden="true"></i>Filtros de búsqueda
              </h2>

              <!--
                El form usa GET para que los filtros queden
                visibles en la URL y el usuario pueda compartir
                o volver a la búsqueda con el botón Atrás.
              -->
              <form action="buscar_vuelos.php" method="get"
                    aria-label="Filtros de búsqueda de vuelos">

                <!-- Origen -->
                <div class="mb-3">
                  <label for="origenVuelo" class="form-label fw-semibold small">
                    <i class="bi bi-geo me-1" aria-hidden="true"></i>Origen
                  </label>
                  <input type="text"
                         id="origenVuelo" name="origenVuelo"
                         class="form-control form-control-sm"
                         placeholder="Ciudad o aeropuerto"
                         value="<?= htmlspecialchars($_GET['origenVuelo'] ?? '') ?>"
                         autocomplete="off"/>
                </div>

                <!-- Destino -->
                <div class="mb-3">
                  <label for="destinoVuelo" class="form-label fw-semibold small">
                    <i class="bi bi-geo-fill me-1" aria-hidden="true"></i>Destino
                  </label>
                  <input type="text"
                         id="destinoVuelo" name="destinoVuelo"
                         class="form-control form-control-sm"
                         placeholder="Ciudad o aeropuerto"
                         value="<?= htmlspecialchars($_GET['destinoVuelo'] ?? '') ?>"
                         autocomplete="off"/>
                </div>

                <!-- Fecha -->
                <div class="mb-3">
                  <label for="fechaSalidaVuelo" class="form-label fw-semibold small">
                    <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Fecha de salida
                  </label>
                  <input type="date"
                         id="fechaSalidaVuelo" name="fechaSalidaVuelo"
                         class="form-control form-control-sm"
                         value="<?= htmlspecialchars($_GET['fechaSalidaVuelo'] ?? '') ?>"/>
                </div>

                <!-- Aerolínea -->
                <div class="mb-4">
                  <label for="codAerolinea" class="form-label fw-semibold small">
                    <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolínea
                  </label>
                  <select id="codAerolinea" name="codAerolinea"
                          class="form-select form-select-sm">
                    <option value="">Todas las aerolíneas</option>
                    <option value="1" <?= (($_GET['codAerolinea'] ?? '') == '1') ? 'selected' : '' ?>>
                      Aerolíneas Argentinas
                    </option>
                    <option value="2" <?= (($_GET['codAerolinea'] ?? '') == '2') ? 'selected' : '' ?>>
                      LATAM Airlines
                    </option>
                    <option value="3" <?= (($_GET['codAerolinea'] ?? '') == '3') ? 'selected' : '' ?>>
                      Flybondi
                    </option>
                    <option value="4" <?= (($_GET['codAerolinea'] ?? '') == '4') ? 'selected' : '' ?>>
                      JetSmart
                    </option>
                  </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-sm">
                  <i class="bi bi-search me-1" aria-hidden="true"></i>Buscar
                </button>

                <a href="buscar_vuelos.php" class="btn btn-outline-secondary w-100 btn-sm mt-2">
                  <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Limpiar filtros
                </a>

              </form>
            </div>
          </div><!-- /col filtros -->


          <!-- ── COLUMNA DERECHA: Resultados ─────────────── -->
          <div class="col-lg-9">

            <!-- Contador de resultados -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <p class="text-secondary small mb-0">
                <!-- TODO: reemplazar con conteo real desde la BD -->
                Mostrando <strong>4</strong> vuelos disponibles
              </p>
            </div>

            <!-- Lista de vuelos -->
            <!--
              TODO: Esta sección se generará dinámicamente con PHP
              haciendo un SELECT a la tabla VUELOS con los filtros
              ingresados por el usuario. Por ahora se muestran
              datos de ejemplo que representan el modelo de datos.
            -->
            <ul class="list-unstyled" role="list" aria-label="Vuelos encontrados">

              <!-- Vuelo 1 -->
              <li class="mb-3" role="listitem">
                <article class="card flight-card border border-2 border-transparent shadow-sm"
                         aria-label="Vuelo 0001: Rosario a Buenos Aires, 15 de mayo 2026, ARS 48.500">
                  <div class="card-body p-4">
                    <div class="row align-items-center g-3">

                      <!-- Aerolínea + código -->
                      <div class="col-12 col-md-2">
                        <div class="d-flex flex-column gap-1">
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            <i class="bi bi-airplane me-1" aria-hidden="true"></i>
                            Cód: 0001
                          </span>
                          <small class="text-secondary fw-semibold" style="font-size:.75rem;">
                            Aer. Argentinas
                          </small>
                        </div>
                      </div>

                      <!-- Ruta -->
                      <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center">
                          <div class="text-center">
                            <div class="iata-code text-dark fw-bold">ROS</div>
                            <small class="text-secondary text-uppercase" style="font-size:.7rem;">
                              Rosario
                            </small>
                          </div>
                          <div class="route-line" aria-hidden="true"></div>
                          <i class="bi bi-airplane-fill text-primary mx-2" aria-hidden="true"></i>
                          <div class="route-line" aria-hidden="true"></div>
                          <div class="text-center">
                            <div class="iata-code text-dark fw-bold">EZE</div>
                            <small class="text-secondary text-uppercase" style="font-size:.7rem;">
                              Buenos Aires
                            </small>
                          </div>
                        </div>
                        <small class="text-secondary d-block mt-1">
                          <i class="bi bi-clock me-1" aria-hidden="true"></i>2h 10min · Directo
                        </small>
                      </div>

                      <!-- Fecha y hora -->
                      <div class="col-6 col-md-2 text-center">
                        <div class="text-secondary small">
                          <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                          <time datetime="2026-05-15T08:30">15 May 2026</time>
                        </div>
                        <div class="fw-semibold small">08:30 hs</div>
                        <div class="asientos-alto small mt-1">
                          <i class="bi bi-people-fill me-1" aria-hidden="true"></i>32 asientos
                        </div>
                      </div>

                      <!-- Precio y promo -->
                      <div class="col-6 col-md-2 text-center">
                        <div class="fw-bold fs-5 text-dark">
                          <small class="text-secondary fw-normal" style="font-size:.7rem;">ARS</small>
                          48.500
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">
                          <i class="bi bi-tag-fill me-1" aria-hidden="true"></i>15% OFF
                        </span>
                      </div>

                      <!-- CTA -->
                      <div class="col-12 col-md-2 text-md-end">
                        <a href="reservar_vuelo.php?codVuelo=0001"
                           class="btn btn-primary w-100"
                           aria-label="Reservar vuelo ROS a EZE el 15 de mayo">
                          <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>
                          Reservar
                        </a>
                      </div>

                    </div>
                  </div>
                </article>
              </li>

              <!-- Vuelo 2 -->
              <li class="mb-3" role="listitem">
                <article class="card flight-card border border-2 border-transparent shadow-sm"
                         aria-label="Vuelo 0002: Buenos Aires a Santiago, 18 de mayo 2026, ARS 124.900">
                  <div class="card-body p-4">
                    <div class="row align-items-center g-3">
                      <div class="col-12 col-md-2">
                        <div class="d-flex flex-column gap-1">
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            <i class="bi bi-airplane me-1" aria-hidden="true"></i>Cód: 0002
                          </span>
                          <small class="text-secondary fw-semibold" style="font-size:.75rem;">
                            LATAM Airlines
                          </small>
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center">
                          <div class="text-center">
                            <div class="iata-code text-dark fw-bold">AEP</div>
                            <small class="text-secondary text-uppercase" style="font-size:.7rem;">Bs. Aires</small>
                          </div>
                          <div class="route-line" aria-hidden="true"></div>
                          <i class="bi bi-airplane-fill text-primary mx-2" aria-hidden="true"></i>
                          <div class="route-line" aria-hidden="true"></div>
                          <div class="text-center">
                            <div class="iata-code text-dark fw-bold">SCL</div>
                            <small class="text-secondary text-uppercase" style="font-size:.7rem;">Santiago</small>
                          </div>
                        </div>
                        <small class="text-secondary d-block mt-1">
                          <i class="bi bi-clock me-1" aria-hidden="true"></i>3h 50min · Directo
                        </small>
                      </div>
                      <div class="col-6 col-md-2 text-center">
                        <div class="text-secondary small">
                          <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                          <time datetime="2026-05-18T14:15">18 May 2026</time>
                        </div>
                        <div class="fw-semibold small">14:15 hs</div>
                        <div class="asientos-medio small mt-1">
                          <i class="bi bi-people-fill me-1" aria-hidden="true"></i>8 asientos
                        </div>
                      </div>
                      <div class="col-6 col-md-2 text-center">
                        <div class="fw-bold fs-5 text-dark">
                          <small class="text-secondary fw-normal" style="font-size:.7rem;">ARS</small>
                          124.900
                        </div>
                        <small class="text-secondary" style="font-size:.75rem;">Sin promo activa</small>
                      </div>
                      <div class="col-12 col-md-2 text-md-end">
                        <a href="reservar_vuelo.php?codVuelo=0002"
                           class="btn btn-primary w-100"
                           aria-label="Reservar vuelo AEP a SCL el 18 de mayo">
                          <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Reservar
                        </a>
                      </div>
                    </div>
                  </div>
                </article>
              </li>

              <!-- Vuelo 3 -->
              <li class="mb-3" role="listitem">
                <article class="card flight-card border border-2 border-transparent shadow-sm"
                         aria-label="Vuelo 0003: Córdoba a Mendoza, 20 de mayo 2026, ARS 29.990">
                  <div class="card-body p-4">
                    <div class="row align-items-center g-3">
                      <div class="col-12 col-md-2">
                        <div class="d-flex flex-column gap-1">
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            <i class="bi bi-airplane me-1" aria-hidden="true"></i>Cód: 0003
                          </span>
                          <small class="text-secondary fw-semibold" style="font-size:.75rem;">Flybondi</small>
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center">
                          <div class="text-center">
                            <div class="iata-code text-dark fw-bold">COR</div>
                            <small class="text-secondary text-uppercase" style="font-size:.7rem;">Córdoba</small>
                          </div>
                          <div class="route-line" aria-hidden="true"></div>
                          <i class="bi bi-airplane-fill text-primary mx-2" aria-hidden="true"></i>
                          <div class="route-line" aria-hidden="true"></div>
                          <div class="text-center">
                            <div class="iata-code text-dark fw-bold">MDZ</div>
                            <small class="text-secondary text-uppercase" style="font-size:.7rem;">Mendoza</small>
                          </div>
                        </div>
                        <small class="text-secondary d-block mt-1">
                          <i class="bi bi-clock me-1" aria-hidden="true"></i>1h 45min · Directo
                        </small>
                      </div>
                      <div class="col-6 col-md-2 text-center">
                        <div class="text-secondary small">
                          <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                          <time datetime="2026-05-20T11:00">20 May 2026</time>
                        </div>
                        <div class="fw-semibold small">11:00 hs</div>
                        <div class="asientos-alto small mt-1">
                          <i class="bi bi-people-fill me-1" aria-hidden="true"></i>50 asientos
                        </div>
                      </div>
                      <div class="col-6 col-md-2 text-center">
                        <div class="fw-bold fs-5 text-dark">
                          <small class="text-secondary fw-normal" style="font-size:.7rem;">ARS</small>
                          29.990
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">
                          <i class="bi bi-tag-fill me-1" aria-hidden="true"></i>30% OFF
                        </span>
                      </div>
                      <div class="col-12 col-md-2 text-md-end">
                        <a href="reservar_vuelo.php?codVuelo=0003"
                           class="btn btn-primary w-100"
                           aria-label="Reservar vuelo COR a MDZ el 20 de mayo">
                          <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Reservar
                        </a>
                      </div>
                    </div>
                  </div>
                </article>
              </li>

              <!-- Vuelo 4 -->
              <li class="mb-3" role="listitem">
                <article class="card flight-card border border-2 border-transparent shadow-sm"
                         aria-label="Vuelo 0004: Iguazú a Bariloche, 22 de mayo 2026, ARS 55.750">
                  <div class="card-body p-4">
                    <div class="row align-items-center g-3">
                      <div class="col-12 col-md-2">
                        <div class="d-flex flex-column gap-1">
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            <i class="bi bi-airplane me-1" aria-hidden="true"></i>Cód: 0004
                          </span>
                          <small class="text-secondary fw-semibold" style="font-size:.75rem;">JetSmart</small>
                        </div>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center">
                          <div class="text-center">
                            <div class="iata-code text-dark fw-bold">IGR</div>
                            <small class="text-secondary text-uppercase" style="font-size:.7rem;">Iguazú</small>
                          </div>
                          <div class="route-line" aria-hidden="true"></div>
                          <i class="bi bi-airplane-fill text-primary mx-2" aria-hidden="true"></i>
                          <div class="route-line" aria-hidden="true"></div>
                          <div class="text-center">
                            <div class="iata-code text-dark fw-bold">BRC</div>
                            <small class="text-secondary text-uppercase" style="font-size:.7rem;">Bariloche</small>
                          </div>
                        </div>
                        <small class="text-secondary d-block mt-1">
                          <i class="bi bi-clock me-1" aria-hidden="true"></i>2h 30min · Directo
                        </small>
                      </div>
                      <div class="col-6 col-md-2 text-center">
                        <div class="text-secondary small">
                          <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                          <time datetime="2026-05-22T16:45">22 May 2026</time>
                        </div>
                        <div class="fw-semibold small">16:45 hs</div>
                        <div class="asientos-alto small mt-1">
                          <i class="bi bi-people-fill me-1" aria-hidden="true"></i>21 asientos
                        </div>
                      </div>
                      <div class="col-6 col-md-2 text-center">
                        <div class="fw-bold fs-5 text-dark">
                          <small class="text-secondary fw-normal" style="font-size:.7rem;">ARS</small>
                          55.750
                        </div>
                        <small class="text-secondary" style="font-size:.75rem;">Sin promo activa</small>
                      </div>
                      <div class="col-12 col-md-2 text-md-end">
                        <a href="reservar_vuelo.php?codVuelo=0004"
                           class="btn btn-primary w-100"
                           aria-label="Reservar vuelo IGR a BRC el 22 de mayo">
                          <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Reservar
                        </a>
                      </div>
                    </div>
                  </div>
                </article>
              </li>

            </ul><!-- /lista vuelos -->

          </div><!-- /col resultados -->

        </div><!-- /row -->

      </div><!-- /container -->
    </section>
  </main>

  <?php include '../Footer/footerIniciado.php'; ?>

</body>
</html>
