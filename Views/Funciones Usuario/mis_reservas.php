<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Consultá, confirmá o cancelá tus reservas de vuelos en VuelaLibre." />
  <title>Mis Reservas | VuelaLibre – UTN FRR</title>

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
    <section class="py-5" aria-labelledby="reservas-titulo">
      <div class="container">

        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Mi cuenta
          </p>
          <h1 id="reservas-titulo" class="h3 fw-bold mb-0">Mis Reservas</h1>
          <p class="text-secondary">
            Consultá el estado de tus reservas, confirmalas o cancelalas.
          </p>
        </div>

        <div class="card border-0 shadow-sm p-3 mb-4">
          <form action="mis_reservas.php" method="get"
                class="d-flex align-items-center gap-3 flex-wrap"
                aria-label="Filtrar reservas por estado">
            <label for="filtroEstado" class="form-label fw-semibold mb-0 small">
              <i class="bi bi-funnel me-1" aria-hidden="true"></i>Filtrar por estado:
            </label>
            <select id="filtroEstado" name="estado" class="form-select form-select-sm w-auto">
              <option value="">Todas</option>
              <option value="pendiente"  <?= (($_GET['estado'] ?? '') === 'pendiente')  ? 'selected' : '' ?>>Pendiente de pago</option>
              <option value="confirmada" <?= (($_GET['estado'] ?? '') === 'confirmada') ? 'selected' : '' ?>>Confirmada</option>
              <option value="cancelada"  <?= (($_GET['estado'] ?? '') === 'cancelada')  ? 'selected' : '' ?>>Cancelada</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            <a href="mis_reservas.php" class="btn btn-outline-secondary btn-sm">Ver todas las reservas</a>
          </form>
        </div>

        <!--
          TODO: Esta lista se generará dinámicamente con PHP
          haciendo un SELECT a la tabla RESERVAS donde
          codUsuario = $_SESSION['codUsuario'].
        -->
        <ul class="list-unstyled" role="list" aria-label="Lista de mis reservas">

          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm reserva-estado-pendiente"
                     aria-label="Reserva 0001: Rosario a Buenos Aires, pendiente de pago">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                  <div>
                    <h2 class="h6 fw-bold mb-1">
                      <i class="bi bi-airplane-fill text-primary me-2" aria-hidden="true"></i>
                      Reserva N° 0001
                    </h2>
                    <small class="text-secondary">
                      <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                      Reservada el <time datetime="2026-05-01">1 de mayo de 2026</time>
                    </small>
                  </div>
                  <span class="badge bg-warning-subtle text-warning border border-warning-subtle fs-6 px-3 py-2">
                    <i class="bi bi-hourglass-split me-1" aria-hidden="true"></i>
                    Pendiente de pago
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
                        <dt class="etiqueta-detalle">Total</dt>
                        <dd class="valor-detalle mb-0 text-dark">ARS 48.500</dd>
                      </div>
                    </dl>
                  </div>

                  <div class="col-md-3 d-flex flex-column gap-2 justify-content-center">
                    <form action="mis_reservas.php" method="post">
                      <input type="hidden" name="accion" value="confirmar"/>
                      <input type="hidden" name="codReserva" value="0001"/>
                      <button type="submit" class="btn btn-success w-100 btn-sm">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                        Confirmar / Pagar
                      </button>
                    </form>
                    <form action="mis_reservas.php" method="post">
                      <input type="hidden" name="accion" value="cancelar"/>
                      <input type="hidden" name="codReserva" value="0001"/>
                      <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                        Cancelar reserva
                      </button>
                    </form>
                  </div>

                </div>

                <div class="alert alert-warning py-2 mt-3 mb-0 small" role="note">
                  <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                  Podés cancelar esta reserva hasta <strong>72 horas antes</strong>
                  de la salida del vuelo (hasta el 12 de mayo de 2026 a las 08:30 hs).
                </div>

              </div>
            </article>
          </li>

          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm reserva-estado-confirmada"
                     aria-label="Reserva 0002: Córdoba a Mendoza, confirmada">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                  <div>
                    <h2 class="h6 fw-bold mb-1">
                      <i class="bi bi-airplane-fill text-primary me-2" aria-hidden="true"></i>
                      Reserva N° 0002
                    </h2>
                    <small class="text-secondary">
                      <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                      Reservada el <time datetime="2026-04-20">20 de abril de 2026</time>
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

                  <div class="col-md-3 d-flex flex-column gap-2 justify-content-center">
                    <form action="mis_reservas.php" method="post">
                      <input type="hidden" name="accion" value="cancelar"/>
                      <input type="hidden" name="codReserva" value="0002"/>
                      <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                        Cancelar reserva
                      </button>
                    </form>
                  </div>

                </div>

                <div class="alert alert-success py-2 mt-3 mb-0 small" role="note">
                  <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                  Reserva confirmada. Podés cancelar hasta el
                  <strong>17 de mayo de 2026 a las 11:00 hs</strong>.
                </div>

              </div>
            </article>
          </li>

          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm reserva-estado-cancelada"
                     aria-label="Reserva 0003: Buenos Aires a Santiago, cancelada">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                  <div>
                    <h2 class="h6 fw-bold mb-1">
                      <i class="bi bi-airplane-fill text-secondary me-2" aria-hidden="true"></i>
                      Reserva N° 0003
                    </h2>
                    <small class="text-secondary">
                      <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                      Reservada el <time datetime="2026-04-10">10 de abril de 2026</time>
                    </small>
                  </div>
                  <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-6 px-3 py-2">
                    <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                    Cancelada
                  </span>
                </div>

                <div class="row g-3">

                  <div class="col-md-5">
                    <div class="d-flex align-items-center mb-2">
                      <div class="text-center">
                        <div class="codigo-iata text-secondary fw-bold">AEP</div>
                        <small class="text-secondary text-uppercase">Bs. Aires</small>
                      </div>
                      <div class="linea-ruta mx-3" aria-hidden="true"></div>
                      <i class="bi bi-airplane-fill text-secondary mx-1" aria-hidden="true"></i>
                      <div class="linea-ruta mx-3" aria-hidden="true"></div>
                      <div class="text-center">
                        <div class="codigo-iata text-secondary fw-bold">SCL</div>
                        <small class="text-secondary text-uppercase">Santiago</small>
                      </div>
                    </div>
                    <small class="text-secondary">
                      <i class="bi bi-clock me-1" aria-hidden="true"></i>
                      <time datetime="2026-05-18T14:15">18 May 2026 · 14:15 hs</time>
                      · 3h 50min · Directo
                    </small>
                  </div>

                  <div class="col-md-4">
                    <dl class="mb-0">
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Aerolínea</dt>
                        <dd class="valor-detalle mb-0 text-secondary">LATAM Airlines</dd>
                      </div>
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Promoción</dt>
                        <dd class="mb-0 text-secondary">Sin promo activa</dd>
                      </div>
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Total</dt>
                        <dd class="valor-detalle mb-0 text-secondary">ARS 124.900</dd>
                      </div>
                    </dl>
                  </div>

                  <div class="col-md-3 d-flex align-items-center justify-content-center">
                    <p class="text-secondary small text-center mb-0">
                      <i class="bi bi-slash-circle me-1" aria-hidden="true"></i>
                      Sin acciones disponibles
                    </p>
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
