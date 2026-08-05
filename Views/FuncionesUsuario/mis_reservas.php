?php
include '../../config/conexion.php';
include '../../config/EnviarCorreo.php';
session_start();
if (!isset($_SESSION['codUsuario'])) {
    header('Location: ../FlujoSesion/login.php');
    exit;
}
$codUsuario   = (int)$_SESSION['codUsuario'];
$mensajeExito = '';
$mensajeError = '';
function enviarCorreoConfirmacion(array $reserva): void
{
    if (empty($reserva['emailUsuario'])) {
        return;
    }
    $codFmt      = str_pad((string)$reserva['codReserva'], 4, '0', STR_PAD_LEFT);
    $descuento   = (float)($reserva['descuentoPromocion'] ?? 0);
    $precioBase  = (float)$reserva['precioVuelo'];
    $precioFinal = $descuento > 0 ? $precioBase * (1 - $descuento / 100) : $precioBase;
    $fechaFmt    = date('d/m/Y', strtotime($reserva['fechaSalidaVuelo']));
    $horaFmt     = substr($reserva['horaSalidaVuelo'], 0, 5);
    $totalFmt    = number_format($precioFinal, 0, ',', '.');
    $nombre   = htmlspecialchars($reserva['nombreUsuario'], ENT_QUOTES, 'UTF-8');
    $origen   = htmlspecialchars($reserva['origenVuelo'], ENT_QUOTES, 'UTF-8');
    $destino  = htmlspecialchars($reserva['destinoVuelo'], ENT_QUOTES, 'UTF-8');
    $aero     = htmlspecialchars($reserva['nombreAerolinea'], ENT_QUOTES, 'UTF-8');
    $cuerpo  = "<p>Hola <strong>$nombre</strong>,</p>"
        . "<p>¡Tu reserva fue confirmada y pagada con éxito! Estos son los datos de tu vuelo:</p>"
        . "<table cellpadding='8' cellspacing='0' style='border-collapse:collapse;border:1px solid #ddd;'>"
        . "<tr><td style='border:1px solid #ddd;'><strong>N° de reserva</strong></td><td style='border:1px solid #ddd;'>$codFmt</td></tr>"
        . "<tr><td style='border:1px solid #ddd;'><strong>Aerolínea</strong></td><td style='border:1px solid #ddd;'>$aero</td></tr>"
        . "<tr><td style='border:1px solid #ddd;'><strong>Origen</strong></td><td style='border:1px solid #ddd;'>$origen</td></tr>"
        . "<tr><td style='border:1px solid #ddd;'><strong>Destino</strong></td><td style='border:1px solid #ddd;'>$destino</td></tr>"
        . "<tr><td style='border:1px solid #ddd;'><strong>Fecha de salida</strong></td><td style='border:1px solid #ddd;'>$fechaFmt · $horaFmt hs</td></tr>";
    if ($descuento > 0) {
        $cuerpo .= "<tr><td style='border:1px solid #ddd;'><strong>Promoción aplicada</strong></td><td style='border:1px solid #ddd;'>" . (int)$descuento . "% de descuento</td></tr>";
    }
    $cuerpo .= "<tr><td style='border:1px solid #ddd;'><strong>Total pagado</strong></td><td style='border:1px solid #ddd;'>ARS $totalFmt</td></tr>"
        . "</table>"
        . "<p>¡Buen viaje! Gracias por elegir VuelaLibre.</p>";
    enviarCorreo($reserva['emailUsuario'], "Confirmación de tu reserva N° $codFmt - VuelaLibre", $cuerpo);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion     = $_POST['accion']     ?? '';
    $codReserva = isset($_POST['codReserva']) ? (int)$_POST['codReserva'] : 0;
    if ($codReserva > 0) {
        $sqlRes = "SELECT r.codReserva, r.estadoReserva,
                          v.codVuelo, v.origenVuelo, v.destinoVuelo,
                          v.fechaSalidaVuelo, v.horaSalidaVuelo,
                          v.precioVuelo,
                          a.nombreAerolinea,
                          p.descuentoPromocion,
                          u.nombreUsuario, u.emailUsuario
                   FROM RESERVAS r
                   JOIN VUELOS v ON r.codVuelo = v.codVuelo
                   JOIN AEROLINEAS a ON v.codAerolinea = a.codAerolinea
                   JOIN USUARIOS u ON r.codUsuario = u.codUsuario
                   LEFT JOIN PROMOCIONES p
                     ON p.codAerolinea = v.codAerolinea
                    AND p.estadoPromocion = 'aprobada'
                   WHERE r.codReserva = $codReserva AND r.codUsuario = $codUsuario";
        $resRes = mysqli_query($link, $sqlRes);
        $reserva = $resRes ? mysqli_fetch_assoc($resRes) : null;
        if ($reserva) {
            if ($accion === 'confirmar' && $reserva['estadoReserva'] === 'pendiente de pago') {
                mysqli_query($link, "UPDATE RESERVAS SET estadoReserva = 'confirmada'
                                     WHERE codReserva = $codReserva");
                $mensajeExito = 'Reserva confirmada correctamente.';
                enviarCorreoConfirmacion($reserva);
            } elseif ($accion === 'cancelar' && $reserva['estadoReserva'] !== 'cancelada') {
                $salidaTs   = strtotime($reserva['fechaSalidaVuelo'] . ' ' . $reserva['horaSalidaVuelo']);
                $limiteTs   = $salidaTs - (72 * 3600);
                if (time() <= $limiteTs) {
                    mysqli_query($link, "UPDATE RESERVAS SET estadoReserva = 'cancelada'
                                         WHERE codReserva = $codReserva");
                    mysqli_query($link, "UPDATE VUELOS SET asientosDisponibles = asientosDisponibles + 1
                                         WHERE codVuelo = {$reserva['codVuelo']}");
                    $mensajeExito = 'Reserva cancelada. El asiento fue liberado.';
                } else {
                    $mensajeError = 'No es posible cancelar: ya pasaron las 72 horas previas al vuelo.';
                }
            }
        } else {
            $mensajeError = 'Reserva no encontrada.';
        }
    }
}
$estadoFiltro = $_GET['estado'] ?? '';
$estadosValidos = ['pendiente de pago', 'confirmada', 'cancelada'];
$whereEstado = '';
if ($estadoFiltro !== '' && in_array($estadoFiltro, $estadosValidos, true)) {
    $e = mysqli_real_escape_string($link, $estadoFiltro);
    $whereEstado = "AND r.estadoReserva = '$e'";
}
$sqlReservas = "SELECT r.codReserva, r.estadoReserva, r.fechaReserva,
                       v.codVuelo, v.origenVuelo, v.destinoVuelo,
                       v.fechaSalidaVuelo, v.horaSalidaVuelo,
                       a.nombreAerolinea,
                       p.descuentoPromocion, v.precioVuelo
                FROM RESERVAS r
                JOIN VUELOS v ON r.codVuelo = v.codVuelo
                JOIN AEROLINEAS a ON v.codAerolinea = a.codAerolinea
                LEFT JOIN PROMOCIONES p
                  ON p.codAerolinea = v.codAerolinea
                 AND p.estadoPromocion = 'aprobada'
                WHERE r.codUsuario = $codUsuario $whereEstado
                ORDER BY r.fechaReserva DESC, r.codReserva DESC";
$resReservas = mysqli_query($link, $sqlReservas);
?>

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

  <?php include '../Header/header.php'; ?>

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
              <option value="pendiente de pago" <?= (($_GET['estado'] ?? '') === 'pendiente de pago') ? 'selected' : '' ?>>Pendiente de pago</option>
              <option value="confirmada" <?= (($_GET['estado'] ?? '') === 'confirmada') ? 'selected' : '' ?>>Confirmada</option>
              <option value="cancelada"  <?= (($_GET['estado'] ?? '') === 'cancelada')  ? 'selected' : '' ?>>Cancelada</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            <a href="mis_reservas.php" class="btn btn-outline-secondary btn-sm">Ver todas las reservas</a>
          </form>
        </div>

        <?php if (!empty($mensajeExito)): ?>
          <div class="alert alert-success mb-4" role="status">
            <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
            <?= htmlspecialchars($mensajeExito, ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php elseif (!empty($mensajeError)): ?>
          <div class="alert alert-danger mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>
            <?= htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <?php if (isset($_GET['reservaCreada'])): ?>
          <div class="alert alert-success mb-4" role="status">
            <i class="bi bi-calendar2-check me-2" aria-hidden="true"></i>
            Reserva creada correctamente. Quedó en estado <strong>pendiente de pago</strong>.
          </div>
        <?php endif; ?>

        <ul class="list-unstyled" aria-label="Lista de mis reservas">

          <?php if ($resReservas && mysqli_num_rows($resReservas) > 0): ?>
            <?php while ($r = mysqli_fetch_assoc($resReservas)):
              $estado     = $r['estadoReserva'];
              $codFmt     = str_pad((string)$r['codReserva'], 4, '0', STR_PAD_LEFT);
              $origenCod  = mb_strtoupper(mb_substr($r['origenVuelo'],  0, 3));
              $destinoCod = mb_strtoupper(mb_substr($r['destinoVuelo'], 0, 3));
              $descuento  = (float)($r['descuentoPromocion'] ?? 0);
              $precioBase = (float)$r['precioVuelo'];
              $precioFinal = $descuento > 0 ? $precioBase * (1 - $descuento / 100) : $precioBase;
              $fechaVueloFmt  = date('j M Y', strtotime($r['fechaSalidaVuelo']));
              $horaVueloFmt   = substr($r['horaSalidaVuelo'], 0, 5);
              $fechaReservaFmt = date('j \d\e F \d\e Y', strtotime($r['fechaReserva']));
              $salidaTs   = strtotime($r['fechaSalidaVuelo'] . ' ' . $r['horaSalidaVuelo']);
              $limiteTs   = $salidaTs - (72 * 3600);
              $puedeCancelar = ($estado !== 'cancelada' && time() <= $limiteTs);
              $limiteStr  = date('j \d\e F \d\e Y \a \l\a\s H:i \h\s', $limiteTs);
              switch ($estado) {
                case 'pendiente de pago':
                  $cardClass  = 'reserva-estado-pendiente';
                  $badgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                  $badgeIcon  = 'bi-hourglass-split';
                  $badgeLabel = 'Pendiente de pago';
                  $iconColor  = 'text-primary';
                  break;
                case 'confirmada':
                  $cardClass  = 'reserva-estado-confirmada';
                  $badgeClass = 'bg-success-subtle text-success border-success-subtle';
                  $badgeIcon  = 'bi-check-circle-fill';
                  $badgeLabel = 'Confirmada';
                  $iconColor  = 'text-primary';
                  break;
                default:
                  $cardClass  = 'reserva-estado-cancelada';
                  $badgeClass = 'bg-secondary-subtle text-secondary border-secondary-subtle';
                  $badgeIcon  = 'bi-x-circle';
                  $badgeLabel = 'Cancelada';
                  $iconColor  = 'text-secondary';
              }
            ?>
            <li class="mb-4">
              <article class="card border-0 shadow-sm <?= $cardClass ?>"
                       aria-label="Reserva <?= $codFmt ?>: <?= htmlspecialchars($r['origenVuelo'], ENT_QUOTES, 'UTF-8') ?> a <?= htmlspecialchars($r['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($badgeLabel, ENT_QUOTES, 'UTF-8') ?>">
                <div class="card-body p-4">

                  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                      <h2 class="h6 fw-bold mb-1">
                        <i class="bi bi-airplane-fill <?= $iconColor ?> me-2" aria-hidden="true"></i>
                        Reserva N° <?= $codFmt ?>
                      </h2>
                      <small class="text-secondary">
                        <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                        Reservada el <time datetime="<?= htmlspecialchars($r['fechaReserva'], ENT_QUOTES, 'UTF-8') ?>">
                          <?= $fechaReservaFmt ?>
                        </time>
                      </small>
                    </div>
                    <span class="badge <?= $badgeClass ?> border fs-6 px-3 py-2">
                      <i class="bi <?= $badgeIcon ?> me-1" aria-hidden="true"></i>
                      <?= htmlspecialchars($badgeLabel, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                  </div>

                  <div class="row g-3">

                    <div class="col-md-5">
                      <div class="d-flex align-items-center mb-2">
                        <div class="text-center">
                          <div class="codigo-iata <?= $iconColor ?> fw-bold"><?= $origenCod ?></div>
                          <small class="text-secondary text-uppercase">
                            <?= htmlspecialchars($r['origenVuelo'], ENT_QUOTES, 'UTF-8') ?>
                          </small>
                        </div>
                        <div class="linea-ruta mx-3" aria-hidden="true"></div>
                        <i class="bi bi-airplane-fill <?= $iconColor ?> mx-1" aria-hidden="true"></i>
                        <div class="linea-ruta mx-3" aria-hidden="true"></div>
                        <div class="text-center">
                          <div class="codigo-iata <?= $iconColor ?> fw-bold"><?= $destinoCod ?></div>
                          <small class="text-secondary text-uppercase">
                            <?= htmlspecialchars($r['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?>
                          </small>
                        </div>
                      </div>
                      <small class="text-secondary">
                        <i class="bi bi-clock me-1" aria-hidden="true"></i>
                        <time datetime="<?= htmlspecialchars($r['fechaSalidaVuelo'], ENT_QUOTES, 'UTF-8') ?>T<?= $horaVueloFmt ?>">
                          <?= $fechaVueloFmt ?> · <?= $horaVueloFmt ?> hs
                        </time>
                      </small>
                    </div>

                    <div class="col-md-4">
                      <dl class="mb-0">
                        <div class="fila-detalle">
                          <dt class="etiqueta-detalle">Aerolínea</dt>
                          <dd class="valor-detalle mb-0">
                            <?= htmlspecialchars($r['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>
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
                          <dt class="etiqueta-detalle"><?= $estado === 'confirmada' ? 'Total pagado' : 'Total' ?></dt>
                          <dd class="valor-detalle mb-0 <?= $estado === 'cancelada' ? 'text-secondary' : 'text-dark' ?>">
                            ARS <?= number_format($precioFinal, 0, ',', '.') ?>
                          </dd>
                        </div>
                      </dl>
                    </div>

                    <div class="col-md-3 d-flex flex-column gap-2 justify-content-center">
                      <?php if ($estado === 'pendiente de pago'): ?>
                        <form action="mis_reservas.php" method="post">
                          <input type="hidden" name="accion" value="confirmar"/>
                          <input type="hidden" name="codReserva" value="<?= (int)$r['codReserva'] ?>"/>
                          <button type="submit" class="btn btn-success w-100 btn-sm">
                            <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                            Confirmar / Pagar
                          </button>
                        </form>
                      <?php endif; ?>
                      <?php if ($puedeCancelar): ?>
                        <form action="mis_reservas.php" method="post">
                          <input type="hidden" name="accion" value="cancelar"/>
                          <input type="hidden" name="codReserva" value="<?= (int)$r['codReserva'] ?>"/>
                          <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                            <i class="bi bi-x-circle me-1" aria-hidden="true"></i>
                            Cancelar reserva
                          </button>
                        </form>
                      <?php elseif ($estado === 'cancelada'): ?>
                        <p class="text-secondary small text-center mb-0">
                          <i class="bi bi-slash-circle me-1" aria-hidden="true"></i>
                          Sin acciones disponibles
                        </p>
                      <?php endif; ?>
                    </div>

                  </div>

                  <?php if ($estado !== 'cancelada'): ?>
                    <?php if ($puedeCancelar): ?>
                      <div class="alert <?= $estado === 'confirmada' ? 'alert-success' : 'alert-warning' ?> py-2 mt-3 mb-0 small" role="note">
                        <i class="bi bi-info-circle me-1" aria-hidden="true"></i>
                        Podés cancelar hasta el <strong><?= $limiteStr ?></strong>.
                      </div>
                    <?php else: ?>
                      <div class="alert alert-secondary py-2 mt-3 mb-0 small" role="note">
                        <i class="bi bi-lock me-1" aria-hidden="true"></i>
                        Ya no es posible cancelar: pasaron las 72 horas previas al vuelo.
                      </div>
                    <?php endif; ?>
                  <?php endif; ?>

                </div>
              </article>
            </li>
            <?php endwhile; ?>
          <?php else: ?>
            <li>
              <div class="alert alert-info" role="status">
                <i class="bi bi-info-circle me-2" aria-hidden="true"></i>
                No tenés reservas<?= $estadoFiltro !== '' ? ' con ese estado' : '' ?>.
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
