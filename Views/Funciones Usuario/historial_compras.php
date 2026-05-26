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
  <link rel="stylesheet" href="../../styles.css"/>
</head>

<body class="bg-light">

  <?php include '../Header/headerIniciado.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="historial-titulo">
      <div class="container">

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

        <!--
          TODO: Estos valores se calcularán dinámicamente con PHP
          haciendo consultas agregadas (COUNT, SUM) a la tabla RESERVAS
          donde estadoReserva = 'confirmada' y codUsuario = $_SESSION['codUsuario'].
        -->
        <div class="row g-3 mb-4">
          <div class="col-6 col-md-3">
            <div class="caja-estadistica bg-primary-subtle text-center">
              <div class="fw-bold fs-4 text-primary">2</div>
              <small class="text-secondary text-uppercase etiqueta-estadistica">
                Compras totales
              </small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="caja-estadistica bg-success-subtle text-center">
              <div class="fw-bold fs-4 text-success">ARS 78.490</div>
              <small class="text-secondary text-uppercase etiqueta-estadistica">
                Total gastado
              </small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="caja-estadistica bg-warning-subtle text-center">
              <div class="fw-bold fs-4 text-warning">ARS 17.059</div>
              <small class="text-secondary text-uppercase etiqueta-estadistica">
                Ahorrado en promos
              </small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="caja-estadistica bg-info-subtle text-center">
              <div class="fw-bold fs-4 text-info">2</div>
              <small class="text-secondary text-uppercase etiqueta-estadistica">
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
        -->
        <ul class="list-unstyled" role="list" aria-label="Historial de compras">

          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm tarjeta-compra"
                     aria-label="Compra N°0002: Córdoba a Mendoza, confirmada el 22 de abril de 2026">
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

                  <div class="col-md-5">
                    <div class="d-flex align-items-center mb-2">
                      <div class="text-center">
                        <div class="codigo-iata text-dark fw-bold">COR</div>
                        <small class="text-secondary text-uppercase">Córdoba</small>
                      </div>
                      <div class="linea-ruta mx-3" aria-hidden="true"></div>
                      <i class="bi bi-airplane-fill text-primary mx-1" aria-hidden="true"></i>
                      <div class="linea-ruta mx-3" aria-hidden="true"></div>
                      <div class="text-center">
                        <div class="codigo-iata text-dark fw-bold">MDZ</div>
                        <small class="text-secondary text-uppercase">Mendoza</small>
                      </div>
                    </div>
                    <small class="text-secondary">
                      <i class="bi bi-clock me-1" aria-hidden="true"></i>
                      <time datetime="2026-05-20T11:00">20 May 2026 · 11:00 hs</time>
                      · 1h 45min · Directo
                    </small>
                  </div>

                  <div class="col-md-4">
                    <dl class="mb-0">
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Aerolínea</dt>
                        <dd class="valor-detalle mb-0">Flybondi</dd>
                      </div>
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Promoción</dt>
                        <dd class="mb-0">
                          <span class="badge bg-success-subtle text-success border border-success-subtle">
                            30% OFF
                          </span>
                        </dd>
                      </div>
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Total pagado</dt>
                        <dd class="valor-detalle mb-0 text-dark">ARS 29.990</dd>
                      </div>
                    </dl>
                  </div>

                  <div class="col-md-3">
                    <div class="bg-success-subtle rounded p-3 text-center h-100 d-flex flex-column justify-content-center">
                      <div class="text-success small fw-semibold mb-1">
                        <i class="bi bi-piggy-bank me-1" aria-hidden="true"></i>
                        Ahorraste
                      </div>
                      <div class="fw-bold text-success">ARS 12.853</div>
                      <div class="text-secondary texto-descuento-ahorro">con el 30% de descuento</div>
                    </div>
                  </div>

                </div>
              </div>
            </article>
          </li>

          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm tarjeta-compra"
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
                        <div class="codigo-iata text-dark fw-bold">ROS</div>
                        <small class="text-secondary text-uppercase">Rosario</small>
                      </div>
                      <div class="linea-ruta mx-3" aria-hidden="true"></div>
                      <i class="bi bi-airplane-fill text-primary mx-1" aria-hidden="true"></i>
                      <div class="linea-ruta mx-3" aria-hidden="true"></div>
                      <div class="text-center">
                        <div class="codigo-iata text-dark fw-bold">EZE</div>
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
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Aerolínea</dt>
                        <dd class="valor-detalle mb-0">Aer. Argentinas</dd>
                      </div>
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Promoción</dt>
                        <dd class="mb-0">
                          <span class="badge bg-success-subtle text-success border border-success-subtle">
                            15% OFF
                          </span>
                        </dd>
                      </div>
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Total pagado</dt>
                        <dd class="valor-detalle mb-0 text-dark">ARS 48.500</dd>
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
                      <div class="text-secondary texto-descuento-ahorro">con el 15% de descuento</div>
                    </div>
                  </div>

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
