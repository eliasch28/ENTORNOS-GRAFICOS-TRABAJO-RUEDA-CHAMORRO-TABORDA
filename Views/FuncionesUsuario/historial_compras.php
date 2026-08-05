?php
include '../../config/conexion.php';
session_start();
if (!isset($_SESSION['codUsuario'])) {
    header('Location: ../FlujoSesion/login.php');
    exit;
}
$codUsuario = (int)$_SESSION['codUsuario'];
$sqlStats = "SELECT COUNT(*) AS totalCompras,
                    SUM(v.precioVuelo * (1 - COALESCE(p.descuentoPromocion, 0) / 100)) AS totalGastado,
                    SUM(v.precioVuelo * COALESCE(p.descuentoPromocion, 0) / 100)        AS totalAhorrado
             FROM RESERVAS r
             JOIN VUELOS v ON r.codVuelo = v.codVuelo
             LEFT JOIN PROMOCIONES p
               ON p.codAerolinea = v.codAerolinea
              AND p.estadoPromocion = 'aprobada'
             WHERE r.codUsuario = $codUsuario
               AND r.estadoReserva = 'confirmada'";
$resStats = mysqli_query($link, $sqlStats);
$stats    = $resStats ? mysqli_fetch_assoc($resStats) : [];
$totalCompras  = (int)($stats['totalCompras']  ?? 0);
$totalGastado  = (float)($stats['totalGastado']  ?? 0);
$totalAhorrado = (float)($stats['totalAhorrado'] ?? 0);
$sqlHistorial = "SELECT r.codReserva, r.fechaReserva,
                        v.origenVuelo, v.destinoVuelo,
                        v.fechaSalidaVuelo, v.horaSalidaVuelo,
                        v.precioVuelo,
                        a.nombreAerolinea,
                        p.descuentoPromocion
                 FROM RESERVAS r
                 JOIN VUELOS v ON r.codVuelo = v.codVuelo
                 JOIN AEROLINEAS a ON v.codAerolinea = a.codAerolinea
                 LEFT JOIN PROMOCIONES p
                   ON p.codAerolinea = v.codAerolinea
                  AND p.estadoPromocion = 'aprobada'
                 WHERE r.codUsuario = $codUsuario
                   AND r.estadoReserva = 'confirmada'
                 ORDER BY r.fechaReserva DESC, r.codReserva DESC";
$resHistorial = mysqli_query($link, $sqlHistorial);
?>

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

  <?php include '../Header/header.php'; ?>

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

        <div class="row g-3 mb-4">
          <div class="col-6 col-md-3">
            <div class="caja-estadistica bg-primary-subtle text-center">
              <div class="fw-bold fs-4 text-primary"><?= $totalCompras ?></div>
              <small class="text-secondary text-uppercase etiqueta-estadistica">
                Compras totales
              </small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="caja-estadistica bg-success-subtle text-center">
              <div class="fw-bold fs-4 text-success">ARS <?= number_format($totalGastado, 0, ',', '.') ?></div>
              <small class="text-secondary text-uppercase etiqueta-estadistica">
                Total gastado
              </small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="caja-estadistica bg-warning-subtle text-center">
              <div class="fw-bold fs-4 text-warning">ARS <?= number_format($totalAhorrado, 0, ',', '.') ?></div>
              <small class="text-secondary text-uppercase etiqueta-estadistica">
                Ahorrado en promos
              </small>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="caja-estadistica bg-info-subtle text-center">
              <div class="fw-bold fs-4 text-info"><?= $totalCompras ?></div>
              <small class="text-secondary text-uppercase etiqueta-estadistica">
                Vuelos realizados
              </small>
            </div>
          </div>
        </div>

        <ul class="list-unstyled" aria-label="Historial de compras">

          <?php if ($resHistorial && mysqli_num_rows($resHistorial) > 0): ?>
            <?php while ($c = mysqli_fetch_assoc($resHistorial)):
              $codFmt       = str_pad((string)$c['codReserva'], 4, '0', STR_PAD_LEFT);
              $origenCod    = mb_strtoupper(mb_substr($c['origenVuelo'],  0, 3));
              $destinoCod   = mb_strtoupper(mb_substr($c['destinoVuelo'], 0, 3));
              $descuento    = (float)($c['descuentoPromocion'] ?? 0);
              $precioBase   = (float)$c['precioVuelo'];
              $precioFinal  = $descuento > 0 ? $precioBase * (1 - $descuento / 100) : $precioBase;
              $ahorro       = $precioBase - $precioFinal;
              $fechaVFmt    = date('j M Y', strtotime($c['fechaSalidaVuelo']));
              $horaFmt      = substr($c['horaSalidaVuelo'], 0, 5);
              $fechaConfFmt = date('j \d\e F \d\e Y', strtotime($c['fechaReserva']));
            ?>
            <li class="mb-4">
              <article class="card border-0 shadow-sm tarjeta-compra"
                       aria-label="Compra N°<?= $codFmt ?>: <?= htmlspecialchars($c['origenVuelo'], ENT_QUOTES, 'UTF-8') ?> a <?= htmlspecialchars($c['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="card-body p-4">

                  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                      <h2 class="h6 fw-bold mb-1">
                        <i class="bi bi-receipt text-success me-2" aria-hidden="true"></i>
                        Compra N° <?= $codFmt ?>
                      </h2>
                      <small class="text-secondary">
                        <i class="bi bi-calendar-check me-1" aria-hidden="true"></i>
                        Confirmada el
                        <time datetime="<?= htmlspecialchars($c['fechaReserva'], ENT_QUOTES, 'UTF-8') ?>">
                          <?= $fechaConfFmt ?>
                        </time>
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
                          <div class="codigo-iata text-dark fw-bold"><?= $origenCod ?></div>
                          <small class="text-secondary text-uppercase">
                            <?= htmlspecialchars($c['origenVuelo'], ENT_QUOTES, 'UTF-8') ?>
                          </small>
                        </div>
                        <div class="linea-ruta mx-3" aria-hidden="true"></div>
                        <i class="bi bi-airplane-fill text-primary mx-1" aria-hidden="true"></i>
                        <div class="linea-ruta mx-3" aria-hidden="true"></div>
                        <div class="text-center">
                          <div class="codigo-iata text-dark fw-bold"><?= $destinoCod ?></div>
                          <small class="text-secondary text-uppercase">
                            <?= htmlspecialchars($c['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?>
                          </small>
                        </div>
                      </div>
                      <small class="text-secondary">
                        <i class="bi bi-clock me-1" aria-hidden="true"></i>
                        <time datetime="<?= htmlspecialchars($c['fechaSalidaVuelo'], ENT_QUOTES, 'UTF-8') ?>T<?= $horaFmt ?>">
                          <?= $fechaVFmt ?> · <?= $horaFmt ?> hs
                        </time>
                      </small>
                    </div>

                    <div class="col-md-4">
                      <dl class="mb-0">
                        <div class="fila-detalle">
                          <dt class="etiqueta-detalle">Aerolínea</dt>
                          <dd class="valor-detalle mb-0">
                            <?= htmlspecialchars($c['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>
                          </dd>
                        </div>
                        <div class="fila-detalle">
                          <dt class="etiqueta-detalle">Promoción</dt>
                          <dd class="mb-0">
                            <?php if ($descuento > 0): ?>
                              <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <?= (int)$descuento ?>% OFF
                              </span>
                            <?php else: ?>
                              <span class="text-secondary small">Sin promo activa</span>
                            <?php endif; ?>
                          </dd>
                        </div>
                        <div class="fila-detalle">
                          <dt class="etiqueta-detalle">Total pagado</dt>
                          <dd class="valor-detalle mb-0 text-dark">
                            ARS <?= number_format($precioFinal, 0, ',', '.') ?>
                          </dd>
                        </div>
                      </dl>
                    </div>

                    <div class="col-md-3">
                      <?php if ($ahorro > 0): ?>
                        <div class="bg-success-subtle rounded p-3 text-center h-100 d-flex flex-column justify-content-center">
                          <div class="text-success small fw-semibold mb-1">
                            <i class="bi bi-piggy-bank me-1" aria-hidden="true"></i>
                            Ahorraste
                          </div>
                          <div class="fw-bold text-success">ARS <?= number_format($ahorro, 0, ',', '.') ?></div>
                          <div class="text-secondary texto-descuento-ahorro">con el <?= (int)$descuento ?>% de descuento</div>
                        </div>
                      <?php else: ?>
                        <div class="rounded p-3 text-center h-100 d-flex flex-column justify-content-center">
                          <div class="text-secondary small">Sin descuento aplicado</div>
                        </div>
                      <?php endif; ?>
                    </div>

                  </div>
                </div>
              </article>
            </li>
            <?php endwhile; ?>
          <?php else: ?>
            <li>
              <div class="alert alert-info" role="status">
                <i class="bi bi-info-circle me-2" aria-hidden="true"></i>
                Todavía no tenés compras confirmadas.
              </div>
            </li>
          <?php endif; ?>

        </ul>

      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>
