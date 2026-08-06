<?php
include '../../config/conexion.php';
require_once '../../config/requiere_ceo.php';
$codUsuario = (int)$_SESSION['codUsuario'];
$resU = mysqli_query($link, "SELECT u.codAerolinea, a.nombreAerolinea, a.codigoIATA
                              FROM USUARIOS u
                              JOIN AEROLINEAS a ON u.codAerolinea = a.codAerolinea
                              WHERE u.codUsuario = $codUsuario");
$ceoData = $resU ? mysqli_fetch_assoc($resU) : null;
$sinAerolinea = (!$ceoData || !$ceoData['codAerolinea']);
$nombreAerolinea = '';
$codigoIATA = '';
if (!$sinAerolinea) {
    $codAerolinea    = (int)$ceoData['codAerolinea'];
    $nombreAerolinea = htmlspecialchars($ceoData['nombreAerolinea'], ENT_QUOTES, 'UTF-8');
    $codigoIATA      = htmlspecialchars($ceoData['codigoIATA'], ENT_QUOTES, 'UTF-8');
    $r = mysqli_query($link,
        "SELECT COUNT(*) AS total FROM VUELOS WHERE codAerolinea = $codAerolinea");
    $totalVuelos = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
    $r = mysqli_query($link,
        "SELECT COUNT(*) AS total FROM VUELOS
         WHERE codAerolinea = $codAerolinea AND fechaSalidaVuelo >= CURDATE()");
    $vuelosFuturos = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
    $r = mysqli_query($link,
        "SELECT COUNT(*) AS total
         FROM RESERVAS r JOIN VUELOS v ON r.codVuelo = v.codVuelo
         WHERE v.codAerolinea = $codAerolinea");
    $totalReservas = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
    $r = mysqli_query($link,
        "SELECT COUNT(*) AS total
         FROM RESERVAS r JOIN VUELOS v ON r.codVuelo = v.codVuelo
         WHERE v.codAerolinea = $codAerolinea AND r.estadoReserva = 'confirmada'");
    $reservasConfirmadas = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
    $r = mysqli_query($link,
        "SELECT COUNT(*) AS total
         FROM RESERVAS r JOIN VUELOS v ON r.codVuelo = v.codVuelo
         WHERE v.codAerolinea = $codAerolinea AND r.estadoReserva = 'pendiente de pago'");
    $reservasPendientes = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
    $r = mysqli_query($link,
        "SELECT COALESCE(SUM(v.precioVuelo * (1 - COALESCE(p.descuentoPromocion,0)/100)), 0) AS total
         FROM RESERVAS r
         JOIN VUELOS v ON r.codVuelo = v.codVuelo
         LEFT JOIN PROMOCIONES p
           ON p.codAerolinea = v.codAerolinea AND p.estadoPromocion = 'aprobada'
         WHERE v.codAerolinea = $codAerolinea AND r.estadoReserva = 'confirmada'");
    $ingresosTotales = (float)(mysqli_fetch_assoc($r)['total'] ?? 0);
    $r = mysqli_query($link,
        "SELECT codPromocion, descripcionPromocion, descuentoPromocion FROM PROMOCIONES
         WHERE codAerolinea = $codAerolinea AND estadoPromocion = 'aprobada' LIMIT 1");
    $promoActiva = ($r && mysqli_num_rows($r) > 0) ? mysqli_fetch_assoc($r) : null;
    $resReservas = mysqli_query($link,
        "SELECT r.codReserva, r.estadoReserva, r.fechaReserva,
                u.nombreUsuario, u.apellidoUsuario,
                v.origenVuelo, v.destinoVuelo, v.fechaSalidaVuelo
         FROM RESERVAS r
         JOIN VUELOS v ON r.codVuelo = v.codVuelo
         JOIN USUARIOS u ON r.codUsuario = u.codUsuario
         WHERE v.codAerolinea = $codAerolinea
         ORDER BY r.fechaReserva DESC, r.codReserva DESC
         LIMIT 10");
    $ultimasReservas = [];
    if ($resReservas) {
        while ($row = mysqli_fetch_assoc($resReservas)) $ultimasReservas[] = $row;
    }
    $resTop = mysqli_query($link,
        "SELECT v.codVuelo, v.origenVuelo, v.destinoVuelo, v.fechaSalidaVuelo,
                v.asientosDisponibles,
                COUNT(r.codReserva) AS totalReservas
         FROM VUELOS v
         LEFT JOIN RESERVAS r ON v.codVuelo = r.codVuelo
         WHERE v.codAerolinea = $codAerolinea
         GROUP BY v.codVuelo
         ORDER BY totalReservas DESC
         LIMIT 5");
    $topVuelos = [];
    if ($resTop) {
        while ($row = mysqli_fetch_assoc($resTop)) $topVuelos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reportes | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1">
  <section class="py-5">
    <div class="container">

      <?php if ($sinAerolinea): ?>
      <div class="py-5 text-center">
        <i class="bi bi-building-x fs-1 text-secondary d-block mb-3" aria-hidden="true"></i>
        <h2 class="h5 fw-bold mb-2">Sin aerolínea asociada</h2>
        <p class="text-secondary mb-4">Tu cuenta de CEO no está asociada a ninguna aerolínea.<br>Contactá al administrador para que te asigne una.</p>
        <a href="../LandPage/LandUsuarioRegistrado.php" class="btn btn-primary">
          <i class="bi bi-house-fill me-1" aria-hidden="true"></i>Ir al Inicio
        </a>
      </div>
      <?php else: ?>

      <div class="mb-2">
        <p class="text-primary text-uppercase small fw-semibold mb-1">
          <i class="bi bi-bar-chart-line me-1" aria-hidden="true"></i>CEO · Reportes
        </p>
        <h1 class="h3 fw-bold mb-0">Reportes de mi aerolínea</h1>
      </div>
      <div class="mb-5">
        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
          <i class="bi bi-building me-1" aria-hidden="true"></i>
          Aerolínea: <strong><?= $nombreAerolinea ?></strong> · IATA: <?= $codigoIATA ?>
        </span>
      </div>

      <div class="row g-3 mb-5">
        <div class="col-sm-6 col-xl-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="rounded-3 bg-primary-subtle p-3">
                <i class="bi bi-airplane-fill text-primary fs-3" aria-hidden="true"></i>
              </div>
              <div>
                <div class="fs-3 fw-bold"><?= $totalVuelos ?></div>
                <div class="text-secondary small">Vuelos totales</div>
                <div class="text-success small"><?= $vuelosFuturos ?> futuros</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="rounded-3 bg-success-subtle p-3">
                <i class="bi bi-ticket-perforated-fill text-success fs-3" aria-hidden="true"></i>
              </div>
              <div>
                <div class="fs-3 fw-bold"><?= $totalReservas ?></div>
                <div class="text-secondary small">Reservas totales</div>
                <div class="text-warning small"><?= $reservasPendientes ?> pendientes de pago</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="rounded-3 bg-warning-subtle p-3">
                <i class="bi bi-check-circle-fill text-warning fs-3" aria-hidden="true"></i>
              </div>
              <div>
                <div class="fs-3 fw-bold"><?= $reservasConfirmadas ?></div>
                <div class="text-secondary small">Reservas confirmadas</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xl-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="rounded-3 bg-info-subtle p-3">
                <i class="bi bi-currency-dollar text-info fs-3" aria-hidden="true"></i>
              </div>
              <div>
                <div class="fs-4 fw-bold">ARS <?= number_format($ingresosTotales, 0, ',', '.') ?></div>
                <div class="text-secondary small">Ingresos confirmados</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php if ($promoActiva): ?>
      <div class="alert alert-success d-flex align-items-center gap-3 mb-5" role="note">
        <i class="bi bi-tag-fill fs-4 flex-shrink-0" aria-hidden="true"></i>
        <div>
          <strong>Promoción activa:</strong>
          <?= htmlspecialchars($promoActiva['descripcionPromocion'], ENT_QUOTES, 'UTF-8') ?> —
          <strong><?= (int)$promoActiva['descuentoPromocion'] ?>% de descuento</strong>
        </div>
      </div>
      <?php else: ?>
      <div class="alert alert-secondary d-flex align-items-center gap-3 mb-5" role="note">
        <i class="bi bi-tag fs-4 flex-shrink-0" aria-hidden="true"></i>
        <div>
          Tu aerolínea no tiene ninguna promoción aprobada en este momento.
          <a href="promociones-create.php" class="alert-link ms-1">Crear una promoción →</a>
        </div>
      </div>
      <?php endif; ?>

      <div class="row g-4">

        <div class="col-lg-7">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold border-bottom">
              <i class="bi bi-clock-history me-2 text-primary" aria-hidden="true"></i>Últimas 10 reservas
            </div>
            <div class="card-body p-0">
              <?php if (empty($ultimasReservas)): ?>
                <div class="p-4 text-center text-secondary">
                  <i class="bi bi-inbox fs-2 d-block mb-2" aria-hidden="true"></i>
                  No hay reservas registradas para tus vuelos todavía.
                </div>
              <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                  <thead class="table-light">
                    <tr>
                      <th scope="col">Cód.</th>
                      <th scope="col">Usuario</th>
                      <th scope="col">Vuelo</th>
                      <th scope="col">Fecha reserva</th>
                      <th scope="col" class="text-center">Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($ultimasReservas as $res):
                      $codFmt = str_pad((string)$res['codReserva'], 4, '0', STR_PAD_LEFT);
                      $orig   = mb_strtoupper(mb_substr($res['origenVuelo'],  0, 3));
                      $dest   = mb_strtoupper(mb_substr($res['destinoVuelo'], 0, 3));
                      $fechaResFmt = date('d/m/Y', strtotime($res['fechaReserva']));
                      switch ($res['estadoReserva']) {
                        case 'confirmada':
                          $badge = 'bg-success-subtle text-success border-success-subtle';
                          $label = 'Confirmada'; break;
                        case 'cancelada':
                          $badge = 'bg-secondary-subtle text-secondary border-secondary-subtle';
                          $label = 'Cancelada'; break;
                        default:
                          $badge = 'bg-warning-subtle text-warning border-warning-subtle';
                          $label = 'Pendiente';
                      }
                    ?>
                    <tr>
                      <td class="text-secondary"><?= $codFmt ?></td>
                      <td><?= htmlspecialchars($res['nombreUsuario'] . ' ' . $res['apellidoUsuario'], ENT_QUOTES, 'UTF-8') ?></td>
                      <td class="fw-semibold"><?= $orig ?> → <?= $dest ?></td>
                      <td><?= $fechaResFmt ?></td>
                      <td class="text-center">
                        <span class="badge <?= $badge ?> border"><?= $label ?></span>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold border-bottom">
              <i class="bi bi-trophy me-2 text-warning" aria-hidden="true"></i>Top 5 vuelos con más reservas
            </div>
            <div class="card-body p-0">
              <?php if (empty($topVuelos)): ?>
                <div class="p-4 text-center text-secondary">
                  <i class="bi bi-airplane fs-2 d-block mb-2" aria-hidden="true"></i>
                  No hay vuelos cargados todavía.
                </div>
              <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                  <thead class="table-light">
                    <tr>
                      <th scope="col">Vuelo</th>
                      <th scope="col">Fecha</th>
                      <th scope="col" class="text-center">Reservas</th>
                      <th scope="col" class="text-center">Asientos disp.</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($topVuelos as $i => $v):
                      $orig     = mb_strtoupper(mb_substr($v['origenVuelo'],  0, 3));
                      $dest     = mb_strtoupper(mb_substr($v['destinoVuelo'], 0, 3));
                      $fechaFmt = date('d/m/Y', strtotime($v['fechaSalidaVuelo']));
                      $asientos = (int)$v['asientosDisponibles'];
                      $claseA   = $asientos > 20 ? 'text-success' : ($asientos > 5 ? 'text-warning' : 'text-danger');
                      $medallas = ['bi-trophy-fill text-warning', 'bi-trophy-fill text-secondary', 'bi-trophy-fill text-danger'];
                    ?>
                    <tr>
                      <td>
                        <?php if ($i < 3): ?>
                          <i class="bi <?= $medallas[$i] ?> me-1" aria-hidden="true"></i>
                        <?php endif; ?>
                        <span class="fw-semibold"><?= $orig ?> → <?= $dest ?></span>
                      </td>
                      <td><?= $fechaFmt ?></td>
                      <td class="text-center fw-bold"><?= (int)$v['totalReservas'] ?></td>
                      <td class="text-center <?= $claseA ?> fw-semibold"><?= $asientos ?></td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

      </div>

      <?php endif; ?>
    </div>
  </section>
</main>

<?php include '../Footer/footer.php'; ?>

</body>
</html>
