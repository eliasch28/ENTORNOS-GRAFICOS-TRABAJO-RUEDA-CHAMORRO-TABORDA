<?php
include '../../config/conexion.php';
session_start();
if (!isset($_SESSION['codUsuario'])) {
    header('Location: ../Flujo Sesion/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Confirmá tu reserva de vuelo en VuelaLibre." />
  <title>Reservar Vuelo | VuelaLibre – UTN FRR</title>

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
    <section class="py-5" aria-labelledby="reservar-titulo">
      <div class="container">

        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Reservas
          </p>
          <h1 id="reservar-titulo" class="h3 fw-bold mb-0">Reservar Vuelo</h1>
          <p class="text-secondary">
            Revisá los detalles del vuelo y confirmá tu reserva.
          </p>
        </div>

        <div class="card border-0 shadow-sm p-4 mb-4">
          <ol class="d-flex list-unstyled mb-0"
              aria-label="Pasos del proceso de reserva">
            <li class="elemento-paso activo" aria-current="step">
              <div class="circulo-paso">1</div>
              <span class="etiqueta-paso">Confirmar reserva</span>
            </li>
            <li class="elemento-paso">
              <div class="circulo-paso">2</div>
              <span class="etiqueta-paso">Pago</span>
            </li>
            <li class="elemento-paso">
              <div class="circulo-paso">3</div>
              <span class="etiqueta-paso">Confirmado</span>
            </li>
          </ol>
        </div>

        <div class="row g-4">

          <div class="col-lg-7">

            <!--
              TODO: Los datos de este card se poblarán dinámicamente
              con PHP haciendo un SELECT a la tabla VUELOS usando el
              codVuelo recibido por GET ($_GET['codVuelo']).
            -->
            <div class="card border-0 shadow-sm tarjeta-resumen-vuelo mb-4">
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

                <div class="d-flex align-items-center justify-content-center py-3 mb-3">
                  <div class="text-center">
                    <div class="codigo-iata text-dark fw-bold">ROS</div>
                    <small class="text-secondary text-uppercase">Rosario</small>
                  </div>
                  <div class="linea-ruta mx-3" aria-hidden="true"></div>
                  <i class="bi bi-airplane-fill text-primary fs-4 mx-2" aria-hidden="true"></i>
                  <div class="linea-ruta mx-3" aria-hidden="true"></div>
                  <div class="text-center">
                    <div class="codigo-iata text-dark fw-bold">EZE</div>
                    <small class="text-secondary text-uppercase">Buenos Aires</small>
                  </div>
                </div>

                <dl>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolínea
                    </dt>
                    <dd class="valor-detalle mb-0">Aerolíneas Argentinas</dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Fecha de salida
                    </dt>
                    <dd class="valor-detalle mb-0">
                      <time datetime="2026-05-15T08:30">15 de mayo de 2026 · 08:30 hs</time>
                    </dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-clock me-1" aria-hidden="true"></i>Duración
                    </dt>
                    <dd class="valor-detalle mb-0">2h 10min · Vuelo directo</dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-people-fill me-1" aria-hidden="true"></i>Asientos disponibles
                    </dt>
                    <dd class="valor-detalle mb-0 text-success">32 asientos</dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
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

            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-4">

                <h2 class="h6 fw-bold text-primary mb-3">
                  <i class="bi bi-person-fill me-2" aria-hidden="true"></i>
                  Datos del pasajero
                </h2>

                <!--
                  TODO: Estos datos se obtendrán de $_SESSION una vez
                  integrada la autenticación con BD.
                -->
                <dl>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-person me-1" aria-hidden="true"></i>Usuario
                    </dt>
                    <dd class="valor-detalle mb-0">juan_perez</dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-envelope me-1" aria-hidden="true"></i>Email
                    </dt>
                    <dd class="valor-detalle mb-0">juan@correo.com</dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-telephone me-1" aria-hidden="true"></i>Teléfono
                    </dt>
                    <dd class="valor-detalle mb-0">+54 341 555-1234</dd>
                  </div>
                </dl>

                <a href="mi_perfil.php" class="btn btn-outline-secondary btn-sm mt-2">
                  <i class="bi bi-pencil me-1" aria-hidden="true"></i>
                  Actualizar datos del perfil
                </a>

              </div>
            </div>

          </div>

          <div class="col-lg-5">

            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-4">

                <h2 class="h6 fw-bold text-primary mb-3">
                  <i class="bi bi-receipt me-2" aria-hidden="true"></i>Resumen del pago
                </h2>

                <div class="fila-detalle">
                  <span class="etiqueta-detalle">Precio base</span>
                  <span>ARS 57.059</span>
                </div>
                <div class="fila-detalle">
                  <span class="etiqueta-detalle">Descuento (15%)</span>
                  <span class="text-success">- ARS 8.559</span>
                </div>
                <div class="fila-detalle">
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

            <!--
              El codVuelo viaja como campo oculto para no depender
              del GET (que el usuario podría modificar).
              TODO: En etapa de integración con BD, este form insertará
              un registro en RESERVAS y decrementará asientosDisponibles.
            -->
            <form action="reservar_vuelo.php" method="post"
                  class="card border-0 shadow-sm p-4"
                  aria-label="Formulario de confirmación de reserva">

              <input type="hidden" name="codVuelo"
                     value="<?= htmlspecialchars($_GET['codVuelo'] ?? '0001') ?>"/>

              <h2 class="h6 fw-bold mb-3">
                <i class="bi bi-check2-circle me-2 text-primary" aria-hidden="true"></i>
                Confirmar reserva
              </h2>

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

              <button type="submit" class="btn btn-primary w-100 btn-lg mb-2">
                <i class="bi bi-calendar2-check me-2" aria-hidden="true"></i>
                Confirmar reserva
              </button>

              <a href="buscar_vuelos.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                Volver a los resultados de búsqueda
              </a>

            </form>

          </div>

        </div>

      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>

