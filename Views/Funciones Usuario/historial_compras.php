<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Consultá tu historial de compras y vuelos confirmados en VuelaLibre." />
  <title>Historial de Compras | VuelaLibre – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="../LandPage/EstilosLandUsuarioNoRegistrado.css"/>

  <style>
    /* Estilos exclusivos de historial_compras.php */

    .compra-card {
      border-left: 4px solid var(--bs-success) !important;
    }

    .detalle-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.45rem 0;
      border-bottom: 1px solid var(--bs-border-color);
      font-size: 0.88rem;
    }
    .detalle-row:last-child { border-bottom: none; }
    .detalle-label { color: var(--bs-secondary-color); }
    .detalle-valor { font-weight: 600; }

    /* Resumen estadístico superior */
    .stat-box {
      border-radius: var(--bs-border-radius);
      padding: 1rem 1.25rem;
    }
  </style>
</head>

<body class="bg-light">

  <?php include '../Header/headerIniciado.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="historial-titulo">
      <div class="container">

        <!-- Encabezado -->
        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-clock-history me-1" aria-hidden="true"></i>Mi cuenta
          </p>
          <h1 id="historial-titulo" class="h3 fw-bold mb-0">Historial de Compras</h1>
          <p class="text-secondary">
            Tus reservas confirmadas y pagadas. Una compra es toda reserva
            que pasó de "pendiente de pago" a "confirmada".
          </p>
        </div>


        <!-- Resumen estadístico -->
        <!--
          TODO: Estos valores se calcularán dinámicamente con PHP
          haciendo consultas agregadas (COUNT, SUM) a la tabla RESERVAS
          donde estadoReserva = 'confirmada' y codUsuario = $_SESSION['codUsuario'].
        -->
        <div class="row g-3 mb-4">
          <div class="col-6 col-md-3">
            <div class="stat-box bg-primary-subtle text-center">
              <div class="fw-bold fs-4 text-primary">2</div>
              <small class="text-secondary text-uppercase" style="font-size:.72rem;letter-spacing:.08em;">
                Compras totales
              </small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-box bg-success-subtle text-center">
              <div class="fw-bold fs-4 text-success">ARS 78.490</div>
              <small class="text-secondary text-uppercase" style="font-size:.72rem;letter-spacing:.08em;">
                Total gastado
              </small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-box bg-warning-subtle text-center">
              <div class="fw-bold fs-4 text-warning">ARS 17.059</div>
              <small class="text-secondary text-uppercase" style="font-size:.72rem;letter-spacing:.08em;">
                Ahorrado en promos
              </small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="stat-box bg-info-subtle text-center">
              <div class="fw-bold fs-4 text-info">2</div>
              <small class="text-secondary text-uppercase" style="font-size:.72rem;letter-spacing:.08em;">
                Vuelos realizados
              </small>
            </div>
          </div>
        </div>


        <!--
          TODO: Esta lista se generará dinámicamente con PHP
          haciendo un SELECT a RESERVAS JOIN VUELOS donde
          estadoReserva = 'confirmada'
          y codUsuario = $_SESSION['codUsuario']
          ORDER BY fechaReserva DESC.
          Solo se muestran compras (reservas confirmadas).
        -->
        <ul class="list-unstyled" role="list" aria-label="Historial de compras">

          <!-- Compra 1 -->
          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm compra-card"
                     aria-label="Compra N°0002: Córdoba a Mendoza, confirmada el 20 de abril de 2026">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                  <div>
                    <h2 class="h6 fw-bold mb-1">
                      <i class="bi bi-receipt text-success me-2" aria-hidden="true"></i>
                      Compra N° 0002
                    </h2>
                    <small class="text-secondary">
                      <i class="bi bi-calendar-check me-1" aria-hidden="true"></i>
                      Confirmada el <time datetime="2026-04-22">22 de abril de 2026</time>
                    </small>
                  </div>
                  <span class="badge bg-success-subtle text-success border border-success-subtle fs-6 px-3 py-2">
                    <i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>
                    Confirmada
                  </span>
                </div>

                <div class="row g-3">

                  <!-- Ruta -->
                  <div class="col-md-5">
                    <div class="d-flex align-items-center mb-2">
                      <div class="text-center">
                        <div class="iata-code text-dark fw-bold">COR</div>
                        <small class="text-secondary text-uppercase">Córdoba</small>
                      </div>
                      <div class="route-line mx-3" aria-hidden="true"></div>
                      <i class="bi bi-airplane-fill text-primary mx-1" aria-hidden="true"></i>
                      <div class="route-line mx-3" aria-hidden="true"></div>
                      <div class="text-center">
                        <div class="iata-code text-dark fw-bold">MDZ</div>
                        <small class="text-secondary text-uppercase">Mendoza</small>
                      </div>
                    </div>
                    <small class="text-secondary">
                      <i class="bi bi-clock me-1" aria-hidden="true"></i>
                      <time datetime="2026-05-20T11:00">20 May 2026 · 11:00 hs</time>
                      · 1h 45min · Directo
                    </small>
                  </div>

                  <!-- Detalles -->
                  <div class="col-md-4">
                    <dl class="mb-0">
                      <div class="detalle-row">
                        <dt class="detalle-label">Aerolínea</dt>
                        <dd class="detalle-valor mb-0">Flybondi</dd>
                      </div>
                      <div class="detalle-row">
                        <dt class="detalle-label">Promoción</dt>
                        <dd class="mb-0">
                          <span class="badge bg-success-subtle text-success border border-success-subtle">
                            30% OFF
                          </span>
                        </dd>
                      </div>
                      <div class="detalle-row">
                        <dt class="detalle-label">Total pagado</dt>
                        <dd class="detalle-valor mb-0 text-dark">ARS 29.990</dd>
                      </div>
                    </dl>
                  </div>

                  <!-- Resumen de la compra -->
                  <div class="col-md-3">
                    <div class="bg-success-subtle rounded p-3 text-center h-100 d-flex flex-column justify-content-center">
                      <div class="text-success small fw-semibold mb-1">
                        <i class="bi bi-piggy-bank me-1" aria-hidden="true"></i>
                        Ahorraste
                      </div>
                      <div class="fw-bold text-success">ARS 12.853</div>
                      <div class="text-secondary" style="font-size:.75rem;">con el 30% de descuento</div>
                    </div>
                  </div>

                </div>
              </div>
            </article>
          </li>


          <!-- Compra 2 -->
          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm compra-card"
                     aria-label="Compra N°0001: Rosario a Buenos Aires, confirmada el 5 de mayo de 2026">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                  <div>
                    <h2 class="h6 fw-bold mb-1">
                      <i class="bi bi-receipt text-success me-2" aria-hidden="true"></i>
                      Compra N° 0001
                    </h2>
                    <small class="text-secondary">
                      <i class="bi bi-calendar-check me-1" aria-hidden="true"></i>
                      Confirmada el <time datetime="2026-05-05">5 de mayo de 2026</time>
                    </small>
                  </div>
                  <span class="badge bg-success-subtle text-success border border-success-subtle fs-6 px-3 py-2">
                    <i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>
                    Confirmada
                  </span>
                </div>

                <div class="row g-3">

                  <div class="col-md-5">
                    <div class="d-flex align-items-center mb-2">
                      <div class="text-center">
                        <div class="iata-code text-dark fw-bold">ROS</div>
                        <small class="text-secondary text-uppercase">Rosario</small>
                      </div>
                      <div class="route-line mx-3" aria-hidden="true"></div>
                      <i class="bi bi-airplane-fill text-primary mx-1" aria-hidden="true"></i>
                      <div class="route-line mx-3" aria-hidden="true"></div>
                      <div class="text-center">
                        <div class="iata-code text-dark fw-bold">EZE</div>
                        <small class="text-secondary text-uppercase">Buenos Aires</small>
                      </div>
                    </div>
                    <small class="text-secondary">
                      <i class="bi bi-clock me-1" aria-hidden="true"></i>
                      <time datetime="2026-05-15T08:30">15 May 2026 · 08:30 hs</time>
                      · 2h 10min · Directo
                    </small>
                  </div>

                  <div class="col-md-4">
                    <dl class="mb-0">
                      <div class="detalle-row">
                        <dt class="detalle-label">Aerolínea</dt>
                        <dd class="detalle-valor mb-0">Aer. Argentinas</dd>
                      </div>
                      <div class="detalle-row">
                        <dt class="detalle-label">Promoción</dt>
                        <dd class="mb-0">
                          <span class="badge bg-success-subtle text-success border border-success-subtle">
                            15% OFF
                          </span>
                        </dd>
                      </div>
                      <div class="detalle-row">
                        <dt class="detalle-label">Total pagado</dt>
                        <dd class="detalle-valor mb-0 text-dark">ARS 48.500</dd>
                      </div>
                    </dl>
                  </div>

                  <div class="col-md-3">
                    <div class="bg-success-subtle rounded p-3 text-center h-100 d-flex flex-column justify-content-center">
                      <div class="text-success small fw-semibold mb-1">
                        <i class="bi bi-piggy-bank me-1" aria-hidden="true"></i>
                        Ahorraste
                      </div>
                      <div class="fw-bold text-success">ARS 8.559</div>
                      <div class="text-secondary" style="font-size:.75rem;">con el 15% de descuento</div>
                    </div>
                  </div>

                </div>
              </div>
            </article>
          </li>

        </ul><!-- /historial -->

        <!-- Estado vacío (cuando no hay compras) -->
        <!--
          TODO: Mostrar este bloque condicionalmente con PHP
          cuando el SELECT no devuelva resultados.
          if ($totalCompras === 0) { mostrar bloque vacío }
        -->
        <!--
        <div class="text-center py-5">
          <i class="bi bi-bag-x text-secondary" style="font-size:3rem;" aria-hidden="true"></i>
          <h2 class="h5 fw-bold mt-3 mb-1">Todavía no tenés compras</h2>
          <p class="text-secondary">
            Cuando confirmes una reserva aparecerá aquí tu historial.
          </p>
          <a href="buscar_vuelos.php" class="btn btn-primary mt-2">
            <i class="bi bi-search me-2" aria-hidden="true"></i>Buscar vuelos
          </a>
        </div>
        -->

      </div>
    </section>
  </main>

  <?php include '../Footer/footerIniciado.php'; ?>

</body>
</html>
