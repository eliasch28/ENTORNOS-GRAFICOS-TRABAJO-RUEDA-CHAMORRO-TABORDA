<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Confirmá tu reserva de vuelo en SkyReserva." />
  <title>Reservar Vuelo | SkyReserva – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="../LandPage/EstilosLandUsuarioNoRegistrado.css"/>

  <style>
    .paso-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.4rem;
      flex: 1;
      position: relative;
    }
    /* Línea conectora entre pasos */
    .paso-item:not(:last-child)::after {
      content: '';
      position: absolute;
      top: 18px;
      left: calc(50% + 18px);
      width: calc(100% - 36px);
      height: 2px;
      background-color: var(--bs-border-color);
    }
    .paso-item.activo::after  { background-color: var(--bs-primary); }

    .paso-circulo {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      font-weight: 700;
      border: 2px solid var(--bs-border-color);
      background: #fff;
      color: var(--bs-secondary-color);
      z-index: 1;
    }
    .paso-item.activo .paso-circulo {
      border-color: var(--bs-primary);
      background: var(--bs-primary);
      color: #fff;
    }
    .paso-item.completado .paso-circulo {
      border-color: var(--bs-success);
      background: var(--bs-success);
      color: #fff;
    }
    .paso-label {
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--bs-secondary-color);
      text-align: center;
    }
    .paso-item.activo .paso-label { color: var(--bs-primary); font-weight: 600; }

    /* Resumen del vuelo seleccionado */
    .vuelo-resumen-card {
      border-left: 4px solid var(--bs-primary);
    }

    /* Fila de detalle en el resumen */
    .detalle-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 0;
      border-bottom: 1px solid var(--bs-border-color);
      font-size: 0.9rem;
    }
    .detalle-row:last-child { border-bottom: none; }
    .detalle-label { color: var(--bs-secondary-color); }
    .detalle-valor { font-weight: 600; }
  </style>
</head>

<body class="bg-light">

  <?php include '../Header/headerIniciado.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="reservar-titulo">
      <div class="container">

        <!-- Encabezado -->
        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Reservas
          </p>
          <h1 id="reservar-titulo" class="h3 fw-bold mb-0">Reservar Vuelo</h1>
          <p class="text-secondary">
            Revisá los detalles del vuelo y confirmá tu reserva.
          </p>
        </div>


        <!-- ── INDICADOR DE PASOS ──────────────────────────── -->
        <!--
          Muestra visualmente en qué paso del proceso está el usuario.
          Paso 1 activo: el usuario está revisando y confirmando.
          TODO: En etapas futuras, el Paso 2 (pago) activará cuando
          se integre la pasarela de pago.
        -->
        <div class="card border-0 shadow-sm p-4 mb-4">
          <ol class="d-flex list-unstyled mb-0"
              aria-label="Pasos del proceso de reserva">
            <li class="paso-item activo" aria-current="step">
              <div class="paso-circulo">1</div>
              <span class="paso-label">Confirmar reserva</span>
            </li>
            <li class="paso-item">
              <div class="paso-circulo">2</div>
              <span class="paso-label">Pago</span>
            </li>
            <li class="paso-item">
              <div class="paso-circulo">3</div>
              <span class="paso-label">Confirmado</span>
            </li>
          </ol>
        </div>


        <div class="row g-4">

          <!-- ── COLUMNA IZQUIERDA: Detalle del vuelo ───────── -->
          <div class="col-lg-7">

            <!-- Resumen del vuelo seleccionado -->
            <!--
              TODO: Los datos de este card se poblarán dinámicamente
              con PHP haciendo un SELECT a la tabla VUELOS usando el
              codVuelo recibido por GET ($_GET['codVuelo']).
              Por ahora se muestran datos de ejemplo.
            -->
            <div class="card border-0 shadow-sm vuelo-resumen-card mb-4">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start mb-3">
                  <h2 class="h6 fw-bold text-primary mb-0">
                    <i class="bi bi-airplane-fill me-2" aria-hidden="true"></i>
                    Detalle del vuelo
                  </h2>
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    Cód: <?= htmlspecialchars($_GET['codVuelo'] ?? '0001') ?>
                  </span>
                </div>

                <!-- Ruta visual -->
                <div class="d-flex align-items-center justify-content-center py-3 mb-3">
                  <div class="text-center">
                    <div class="iata-code text-dark fw-bold">ROS</div>
                    <small class="text-secondary text-uppercase">Rosario</small>
                  </div>
                  <div class="route-line mx-3" aria-hidden="true"></div>
                  <i class="bi bi-airplane-fill text-primary fs-4 mx-2" aria-hidden="true"></i>
                  <div class="route-line mx-3" aria-hidden="true"></div>
                  <div class="text-center">
                    <div class="iata-code text-dark fw-bold">EZE</div>
                    <small class="text-secondary text-uppercase">Buenos Aires</small>
                  </div>
                </div>

                <!-- Datos del vuelo -->
                <dl>
                  <div class="detalle-row">
                    <dt class="detalle-label">
                      <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolínea
                    </dt>
                    <dd class="detalle-valor mb-0">Aerolíneas Argentinas</dd>
                  </div>
                  <div class="detalle-row">
                    <dt class="detalle-label">
                      <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Fecha de salida
                    </dt>
                    <dd class="detalle-valor mb-0">
                      <time datetime="2026-05-15T08:30">15 de mayo de 2026 · 08:30 hs</time>
                    </dd>
                  </div>
                  <div class="detalle-row">
                    <dt class="detalle-label">
                      <i class="bi bi-clock me-1" aria-hidden="true"></i>Duración
                    </dt>
                    <dd class="detalle-valor mb-0">2h 10min · Vuelo directo</dd>
                  </div>
                  <div class="detalle-row">
                    <dt class="detalle-label">
                      <i class="bi bi-people-fill me-1" aria-hidden="true"></i>Asientos disponibles
                    </dt>
                    <dd class="detalle-valor mb-0 text-success">32 asientos</dd>
                  </div>
                  <div class="detalle-row">
                    <dt class="detalle-label">
                      <i class="bi bi-tag-fill me-1" aria-hidden="true"></i>Promoción aplicada
                    </dt>
                    <dd class="mb-0">
                      <span class="badge bg-success-subtle text-success border border-success-subtle">
                        15% de descuento
                      </span>
                    </dd>
                  </div>
                </dl>

              </div>
            </div>


            <!-- Datos del pasajero (solo lectura, vienen del perfil) -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-4">

                <h2 class="h6 fw-bold text-primary mb-3">
                  <i class="bi bi-person-fill me-2" aria-hidden="true"></i>
                  Datos del pasajero
                </h2>

                <!--
                  TODO: Estos datos se obtendrán de $_SESSION una vez
                  integrada la autenticación con BD.
                  Por ahora se muestran datos de ejemplo.
                -->
                <dl>
                  <div class="detalle-row">
                    <dt class="detalle-label">
                      <i class="bi bi-person me-1" aria-hidden="true"></i>Usuario
                    </dt>
                    <dd class="detalle-valor mb-0">juan_perez</dd>
                  </div>
                  <div class="detalle-row">
                    <dt class="detalle-label">
                      <i class="bi bi-envelope me-1" aria-hidden="true"></i>Email
                    </dt>
                    <dd class="detalle-valor mb-0">juan@correo.com</dd>
                  </div>
                  <div class="detalle-row">
                    <dt class="detalle-label">
                      <i class="bi bi-telephone me-1" aria-hidden="true"></i>Teléfono
                    </dt>
                    <dd class="detalle-valor mb-0">+54 341 555-1234</dd>
                  </div>
                </dl>

                <a href="mi_perfil.php" class="btn btn-outline-secondary btn-sm mt-2">
                  <i class="bi bi-pencil me-1" aria-hidden="true"></i>
                  Actualizar datos del perfil
                </a>

              </div>
            </div>

          </div><!-- /col izquierda -->


          <!-- ── COLUMNA DERECHA: Resumen y confirmación ───── -->
          <div class="col-lg-5">

            <!-- Card de resumen económico -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-4">

                <h2 class="h6 fw-bold text-primary mb-3">
                  <i class="bi bi-receipt me-2" aria-hidden="true"></i>Resumen del pago
                </h2>

                <div class="detalle-row">
                  <span class="detalle-label">Precio base</span>
                  <span>ARS 57.059</span>
                </div>
                <div class="detalle-row">
                  <span class="detalle-label">Descuento (15%)</span>
                  <span class="text-success">- ARS 8.559</span>
                </div>
                <div class="detalle-row">
                  <span class="fw-bold">Total a pagar</span>
                  <span class="fw-bold fs-5 text-dark">ARS 48.500</span>
                </div>

                <div class="alert alert-info small py-2 mt-3 mb-0" role="note">
                  <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                  La reserva quedará en estado
                  <strong>pendiente de pago</strong> hasta que
                  se confirme el abono.
                </div>

              </div>
            </div>


            <!-- Formulario de confirmación -->
            <!--
              El form usa POST para enviar la reserva al servidor.
              El codVuelo viaja como campo oculto para no depender
              del GET (que el usuario podría modificar).
              TODO: En etapa de integración con BD, este form
              insertará un registro en la tabla RESERVAS con:
                - codUsuario (desde $_SESSION)
                - codVuelo   (desde el campo oculto)
                - fechaReserva (fecha actual)
                - estadoReserva = 'pendiente de pago'
              Y decrementará asientosDisponibles en VUELOS.
            -->
            <form action="reservar_vuelo.php" method="post"
                  class="card border-0 shadow-sm p-4"
                  aria-label="Formulario de confirmación de reserva">

              <!-- Campo oculto con el código del vuelo -->
              <input type="hidden" name="codVuelo"
                     value="<?= htmlspecialchars($_GET['codVuelo'] ?? '0001') ?>"/>

              <h2 class="h6 fw-bold mb-3">
                <i class="bi bi-check2-circle me-2 text-primary" aria-hidden="true"></i>
                Confirmar reserva
              </h2>

              <!-- Checkbox de términos -->
              <div class="mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox"
                         id="aceptaTerminos" name="aceptaTerminos"
                         required
                         aria-required="true"
                         aria-describedby="terminos-ayuda"/>
                  <label class="form-check-label small" for="aceptaTerminos">
                    Acepto los términos y condiciones de la reserva. Entiendo que
                    podré cancelar hasta <strong>72 horas antes</strong> de la
                    salida del vuelo.
                  </label>
                </div>
                <div id="terminos-ayuda" class="form-text mt-1">
                  Campo obligatorio para continuar.
                </div>
              </div>

              <!-- Botón de confirmación -->
              <button type="submit" class="btn btn-primary w-100 btn-lg mb-2">
                <i class="bi bi-calendar2-check me-2" aria-hidden="true"></i>
                Confirmar reserva
              </button>

              <!-- Volver a resultados -->
              <a href="buscar_vuelos.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                Volver a los resultados
              </a>

            </form>

          </div><!-- /col derecha -->

        </div><!-- /row -->

      </div><!-- /container -->
    </section>
  </main>

  <?php include '../Footer/footerIniciado.php'; ?>

</body>
</html>
