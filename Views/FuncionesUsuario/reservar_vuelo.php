<?php
include '../../config/conexion.php';
session_start();
if (!isset($_SESSION['codUsuario'])) {
    header('Location: ../FlujoSesion/login.php');
    exit;
}

$mensajeError = '';
$codUsuario   = (int)$_SESSION['codUsuario'];

// Datos completos del usuario (email y teléfono no están en sesión)
$sqlUser = "SELECT nombreUsuario, emailUsuario, telefonoUsuario
            FROM USUARIOS WHERE codUsuario = $codUsuario";
$resUser = mysqli_query($link, $sqlUser);
$usuario = mysqli_fetch_assoc($resUser);

// Manejo del POST: confirmar reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codVuelo = isset($_POST['codVuelo']) ? (int)$_POST['codVuelo'] : 0;
    if ($codVuelo > 0) {
        // Verificar que el vuelo sigue con asientos disponibles
        $sqlCheck = "SELECT codVuelo, asientosDisponibles FROM VUELOS
                     WHERE codVuelo = $codVuelo AND asientosDisponibles > 0
                     AND fechaSalidaVuelo >= CURDATE()";
        $resCheck = mysqli_query($link, $sqlCheck);
        if ($resCheck && mysqli_num_rows($resCheck) > 0) {
            $fechaHoy = date('Y-m-d');
            $sqlIns = "INSERT INTO RESERVAS (codUsuario, codVuelo, fechaReserva, estadoReserva)
                       VALUES ($codUsuario, $codVuelo, '$fechaHoy', 'pendiente de pago')";
            if (mysqli_query($link, $sqlIns)) {
                mysqli_query($link, "UPDATE VUELOS SET asientosDisponibles = asientosDisponibles - 1
                                     WHERE codVuelo = $codVuelo AND asientosDisponibles > 0");
                header('Location: mis_reservas.php?reservaCreada=1');
                exit;
            } else {
                $mensajeError = 'No se pudo crear la reserva. Intentá de nuevo.';
            }
        } else {
            $mensajeError = 'El vuelo ya no tiene asientos disponibles o ha salido.';
        }
    } else {
        $mensajeError = 'Vuelo inválido.';
    }
}

// Cargar datos del vuelo desde GET
$codVueloGet = isset($_GET['codVuelo']) ? (int)$_GET['codVuelo'] : 0;
$vuelo = null;
if ($codVueloGet > 0) {
    $sqlVuelo = "SELECT v.codVuelo, v.origenVuelo, v.destinoVuelo,
                        v.fechaSalidaVuelo, v.horaSalidaVuelo,
                        v.precioVuelo, v.asientosDisponibles,
                        a.nombreAerolinea,
                        p.descuentoPromocion
                 FROM VUELOS v
                 JOIN AEROLINEAS a ON v.codAerolinea = a.codAerolinea
                 LEFT JOIN PROMOCIONES p
                   ON p.codAerolinea = v.codAerolinea
                  AND p.estadoPromocion = 'aprobada'
                 WHERE v.codVuelo = $codVueloGet
                   AND v.asientosDisponibles > 0
                   AND v.fechaSalidaVuelo >= CURDATE()";
    $resVuelo = mysqli_query($link, $sqlVuelo);
    $vuelo = $resVuelo ? mysqli_fetch_assoc($resVuelo) : null;
}

if (!$vuelo) {
    header('Location: buscar_vuelos.php?error=vuelo_no_disponible');
    exit;
}

$codFmt      = str_pad((string)$vuelo['codVuelo'], 4, '0', STR_PAD_LEFT);
$origenCod   = mb_strtoupper(mb_substr($vuelo['origenVuelo'],  0, 3));
$destinoCod  = mb_strtoupper(mb_substr($vuelo['destinoVuelo'], 0, 3));
$descuento   = (float)($vuelo['descuentoPromocion'] ?? 0);
$precioBase  = (float)$vuelo['precioVuelo'];
$precioFinal = $descuento > 0 ? $precioBase * (1 - $descuento / 100) : $precioBase;
$ahorro      = $precioBase - $precioFinal;
$fechaFmt    = date('j \d\e F \d\e Y', strtotime($vuelo['fechaSalidaVuelo']));
$horaFmt     = substr($vuelo['horaSalidaVuelo'], 0, 5);
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

                <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger mb-4" role="alert">
              <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>
              <?= htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm tarjeta-resumen-vuelo mb-4">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start mb-3">
                  <h2 class="h6 fw-bold text-primary mb-0">
                    <i class="bi bi-airplane-fill me-2" aria-hidden="true"></i>
                    Detalle del vuelo
                  </h2>
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    Cód: <?= $codFmt ?>
                  </span>
                </div>

                <div class="d-flex align-items-center justify-content-center py-3 mb-3">
                  <div class="text-center">
                    <div class="codigo-iata text-dark fw-bold"><?= $origenCod ?></div>
                    <small class="text-secondary text-uppercase">
                      <?= htmlspecialchars($vuelo['origenVuelo'], ENT_QUOTES, 'UTF-8') ?>
                    </small>
                  </div>
                  <div class="linea-ruta mx-3" aria-hidden="true"></div>
                  <i class="bi bi-airplane-fill text-primary fs-4 mx-2" aria-hidden="true"></i>
                  <div class="linea-ruta mx-3" aria-hidden="true"></div>
                  <div class="text-center">
                    <div class="codigo-iata text-dark fw-bold"><?= $destinoCod ?></div>
                    <small class="text-secondary text-uppercase">
                      <?= htmlspecialchars($vuelo['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?>
                    </small>
                  </div>
                </div>

                <dl>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolínea
                    </dt>
                    <dd class="valor-detalle mb-0">
                      <?= htmlspecialchars($vuelo['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>
                    </dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Fecha de salida
                    </dt>
                    <dd class="valor-detalle mb-0">
                      <time datetime="<?= htmlspecialchars($vuelo['fechaSalidaVuelo'], ENT_QUOTES, 'UTF-8') ?>T<?= $horaFmt ?>">
                        <?= $fechaFmt ?> · <?= $horaFmt ?> hs
                      </time>
                    </dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-people-fill me-1" aria-hidden="true"></i>Asientos disponibles
                    </dt>
                    <dd class="valor-detalle mb-0 text-success">
                      <?= (int)$vuelo['asientosDisponibles'] ?> asientos
                    </dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-tag-fill me-1" aria-hidden="true"></i>Promoción aplicada
                    </dt>
                    <dd class="mb-0">
                      <?php if ($descuento > 0): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                          <?= (int)$descuento ?>% de descuento
                        </span>
                      <?php else: ?>
                        <span class="text-secondary small">Sin promo activa</span>
                      <?php endif; ?>
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

                <dl>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-person me-1" aria-hidden="true"></i>Usuario
                    </dt>
                    <dd class="valor-detalle mb-0">
                      <?= htmlspecialchars($usuario['nombreUsuario'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-envelope me-1" aria-hidden="true"></i>Email
                    </dt>
                    <dd class="valor-detalle mb-0">
                      <?= htmlspecialchars($usuario['emailUsuario'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
                    </dd>
                  </div>
                  <div class="fila-detalle">
                    <dt class="etiqueta-detalle">
                      <i class="bi bi-telephone me-1" aria-hidden="true"></i>Teléfono
                    </dt>
                    <dd class="valor-detalle mb-0">
                      <?= htmlspecialchars($usuario['telefonoUsuario'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
                    </dd>
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
                  <span>ARS <?= number_format($precioBase, 0, ',', '.') ?></span>
                </div>
                <?php if ($descuento > 0): ?>
                <div class="fila-detalle">
                  <span class="etiqueta-detalle">Descuento (<?= (int)$descuento ?>%)</span>
                  <span class="text-success">- ARS <?= number_format($ahorro, 0, ',', '.') ?></span>
                </div>
                <?php endif; ?>
                <div class="fila-detalle">
                  <span class="fw-bold">Total a pagar</span>
                  <span class="fw-bold fs-5 text-dark">ARS <?= number_format($precioFinal, 0, ',', '.') ?></span>
                </div>

                <div class="alert alert-info small py-2 mt-3 mb-0" role="note">
                  <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                  La reserva quedará en estado
                  <strong>pendiente de pago</strong> hasta que
                  se confirme el abono.
                </div>

              </div>
            </div>

            <form action="reservar_vuelo.php" method="post"
                  class="card border-0 shadow-sm p-4"
                  aria-label="Formulario de confirmación de reserva">

              <input type="hidden" name="codVuelo"
                     value="<?= (int)$vuelo['codVuelo'] ?>"/>

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

