<?php
include '../../config/conexion.php';
require_once '../../config/requiere_admin.php';
function badgeEstadoReserva(string $estado): array
{
  switch ($estado) {
    case 'confirmada':
      return ['Confirmada', 'bg-success'];
    case 'pendiente de pago':
      return ['Pendiente', 'bg-warning text-dark'];
    case 'cancelada':
      return ['Cancelada', 'bg-secondary'];
    default:
      return [ucfirst($estado), 'bg-secondary'];
  }
}
function formatearPrecio($precio): string
{
  return '$' . number_format((float) $precio, 2, ',', '.');
}
$resClientes = mysqli_query($link, "SELECT COUNT(*) AS total FROM USUARIOS WHERE tipoUsuario = 'usuario'");
$totalClientes = (int) (mysqli_fetch_assoc($resClientes)['total'] ?? 0);
$resCeos = mysqli_query($link, "SELECT COUNT(*) AS total FROM USUARIOS WHERE tipoUsuario = 'ceo de aerolinea'");
$totalCeos = (int) (mysqli_fetch_assoc($resCeos)['total'] ?? 0);
$resVuelosActivos = mysqli_query($link, "SELECT COUNT(*) AS total FROM VUELOS
                                          WHERE fechaSalidaVuelo >= CURDATE()
                                            AND asientosDisponibles > 0");
$totalVuelosActivos = (int) (mysqli_fetch_assoc($resVuelosActivos)['total'] ?? 0);
$resReservas = mysqli_query($link, "SELECT COUNT(*) AS total FROM RESERVAS");
$totalReservas = (int) (mysqli_fetch_assoc($resReservas)['total'] ?? 0);
$reporteActivo = $_GET['reporte'] ?? '';
$reporteValido = in_array($reporteActivo, ['ventas', 'vuelos', 'usuarios'], true);
$resReporte = null;
$totalVentas = 0;
$filasReporte = 0;
if ($reporteValido) {
  switch ($reporteActivo) {
    case 'ventas':
      $resReporte = mysqli_query($link, "SELECT r.codReserva, u.nombreUsuario, u.apellidoUsuario, v.origenVuelo, v.destinoVuelo,
                                                v.precioVuelo, r.fechaReserva, r.estadoReserva
                                         FROM RESERVAS r
                                         INNER JOIN USUARIOS u ON r.codUsuario = u.codUsuario
                                         INNER JOIN VUELOS v ON r.codVuelo = v.codVuelo
                                         WHERE r.estadoReserva = 'confirmada'
                                         ORDER BY r.fechaReserva DESC, r.codReserva DESC");
      $resTotalVentas = mysqli_query($link, "SELECT COALESCE(SUM(v.precioVuelo), 0) AS total
                                             FROM RESERVAS r
                                             INNER JOIN VUELOS v ON r.codVuelo = v.codVuelo
                                             WHERE r.estadoReserva = 'confirmada'");
      $totalVentas = (float) (mysqli_fetch_assoc($resTotalVentas)['total'] ?? 0);
      break;
    case 'vuelos':
      $resReporte = mysqli_query($link, "SELECT v.codVuelo, a.nombreAerolinea, v.origenVuelo, v.destinoVuelo,
                                                v.fechaSalidaVuelo, v.horaSalidaVuelo, v.precioVuelo,
                                                v.asientosDisponibles
                                         FROM VUELOS v
                                         INNER JOIN AEROLINEAS a ON v.codAerolinea = a.codAerolinea
                                         ORDER BY v.fechaSalidaVuelo DESC, v.codVuelo DESC");
      break;
    case 'usuarios':
      $resReporte = mysqli_query($link, "SELECT codUsuario, nombreUsuario, apellidoUsuario, tipoUsuario, emailUsuario,
                                                telefonoUsuario, verificado
                                         FROM USUARIOS
                                         ORDER BY FIELD(tipoUsuario, 'administrador', 'ceo de aerolinea', 'usuario'),
                                                  nombreUsuario ASC");
      break;
  }
  if ($resReporte) {
    $filasReporte = mysqli_num_rows($resReporte);
  }
}
$porPagina = 10;
$paginaActual = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
$offset = ($paginaActual - 1) * $porPagina;
$resTotalReservas = mysqli_query($link, "SELECT COUNT(*) AS total FROM RESERVAS");
$totalReservasListado = (int) (mysqli_fetch_assoc($resTotalReservas)['total'] ?? 0);
$totalPaginas = max(1, (int) ceil($totalReservasListado / $porPagina));
if ($paginaActual > $totalPaginas) {
  $paginaActual = $totalPaginas;
  $offset = ($paginaActual - 1) * $porPagina;
}
$sqlUltimasReservas = "SELECT r.codReserva, u.nombreUsuario, u.apellidoUsuario, v.origenVuelo, v.destinoVuelo,
                              r.fechaReserva, r.estadoReserva
                       FROM RESERVAS r
                       INNER JOIN USUARIOS u ON r.codUsuario = u.codUsuario
                       INNER JOIN VUELOS v ON r.codVuelo = v.codVuelo
                       ORDER BY r.codReserva DESC
                       LIMIT $porPagina OFFSET $offset";
$resUltimasReservas = mysqli_query($link, $sqlUltimasReservas);
$queryReporte = $reporteValido ? '&reporte=' . urlencode($reporteActivo) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reportes Globales | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
  <style>
    .print-encabezado {
      display: none;
    }
    @media print {
      header,
      footer {
        display: none !important;
      }
      .no-print {
        display: none !important;
      }
      .print-encabezado {
        display: block !important;
      }
      #contenido-principal {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
      }
      .card {
        border: 0 !important;
        box-shadow: none !important;
      }
      .card-body {
        padding: 0 !important;
      }
      body {
        background: #fff !important;
      }
      .table {
        font-size: 11px;
      }
    }
  </style>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1" class="container my-5">
  <div class="mb-4">
    <h1 class="h2">Reportes Globales</h1>
    <p class="text-muted">Resumen del sistema y generación de reportes desde la base de datos.</p>
  </div>

  <div class="row g-4 mb-5 no-print">
    <div class="col-sm-6 col-xl-3">
      <div class="card text-bg-primary shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-person-fill fs-2" aria-hidden="true"></i>
          <div>
            <div class="fs-4 fw-bold"><?= $totalClientes ?></div>
            <div class="small">Clientes registrados</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card text-bg-info shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-briefcase-fill fs-2" aria-hidden="true"></i>
          <div>
            <div class="fs-4 fw-bold"><?= $totalCeos ?></div>
            <div class="small">CEOs registrados</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card text-bg-success shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-airplane-fill fs-2" aria-hidden="true"></i>
          <div>
            <div class="fs-4 fw-bold"><?= $totalVuelosActivos ?></div>
            <div class="small">Vuelos activos</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card text-bg-warning shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-ticket-perforated-fill fs-2" aria-hidden="true"></i>
          <div>
            <div class="fs-4 fw-bold"><?= $totalReservas ?></div>
            <div class="small">Reservas realizadas</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm mb-5">
    <div class="card-header fw-semibold no-print">
      <i class="bi bi-file-earmark-bar-graph me-2" aria-hidden="true"></i>Generar reportes
    </div>
    <div class="card-body">
      <p class="text-secondary small mb-3 no-print">Seleccioná el tipo de reporte para visualizar el detalle completo.</p>
      <div class="d-flex flex-wrap gap-2 no-print">
        <a href="?reporte=ventas" class="btn btn-outline-success<?= $reporteActivo === 'ventas' ? ' active' : '' ?>">
          <i class="bi bi-cash-stack me-1" aria-hidden="true"></i>Reporte de Ventas
        </a>
        <a href="?reporte=vuelos" class="btn btn-outline-primary<?= $reporteActivo === 'vuelos' ? ' active' : '' ?>">
          <i class="bi bi-airplane me-1" aria-hidden="true"></i>Reporte de Vuelos
        </a>
        <a href="?reporte=usuarios" class="btn btn-outline-info<?= $reporteActivo === 'usuarios' ? ' active' : '' ?>">
          <i class="bi bi-people me-1" aria-hidden="true"></i>Reporte de Usuarios
        </a>
      </div>

      <?php if ($reporteValido): ?>
        <hr class="my-4 no-print">

        <?php if ($reporteActivo === 'ventas'): ?>
          <div id="area-imprimir">
            <div class="print-encabezado mb-4">
              <h1 class="h5 mb-1">VuelaLibre – Reporte Global</h1>
              <p class="text-secondary mb-0">Reporte de Ventas (reservas confirmadas) · Generado el <?= date('d/m/Y H:i') ?></p>
            </div>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
              <h2 class="h6 fw-bold mb-0">Reporte de Ventas — reservas confirmadas</h2>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle">
                  Total facturado: <?= htmlspecialchars(formatearPrecio($totalVentas), ENT_QUOTES, 'UTF-8') ?>
                </span>
                <button type="button" class="btn btn-outline-success btn-sm no-print" onclick="window.print()">
                  <i class="bi bi-printer me-1" aria-hidden="true"></i>Imprimir
                </button>
              </div>
            </div>
          <div class="table-responsive rounded">
            <table class="table table-striped table-hover align-middle mb-0">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Cliente</th>
                  <th scope="col">Vuelo</th>
                  <th scope="col">Precio</th>
                  <th scope="col">Fecha Reserva</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($filasReporte > 0): ?>
                  <?php while ($row = mysqli_fetch_assoc($resReporte)):
                    [$estadoLabel, $estadoClass] = badgeEstadoReserva($row['estadoReserva']);
                  ?>
                    <tr>
                      <th scope="row"><?= (int) $row['codReserva'] ?></th>
                      <td><?= htmlspecialchars($row['nombreUsuario'] . ' ' . $row['apellidoUsuario'], ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($row['origenVuelo'] . ' → ' . $row['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars(formatearPrecio($row['precioVuelo']), ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= $row['fechaReserva'] ? htmlspecialchars(date('d/m/Y', strtotime($row['fechaReserva'])), ENT_QUOTES, 'UTF-8') : '—' ?></td>
                      <td><span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8') ?></span></td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay ventas confirmadas registradas.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          </div>

        <?php elseif ($reporteActivo === 'vuelos'): ?>
          <div id="area-imprimir">
            <div class="print-encabezado mb-4">
              <h1 class="h5 mb-1">VuelaLibre – Reporte Global</h1>
              <p class="text-secondary mb-0">Reporte de Vuelos · Generado el <?= date('d/m/Y H:i') ?></p>
            </div>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
              <h2 class="h6 fw-bold mb-0">Reporte de Vuelos</h2>
              <button type="button" class="btn btn-outline-primary btn-sm no-print" onclick="window.print()">
                <i class="bi bi-printer me-1" aria-hidden="true"></i>Imprimir
              </button>
            </div>
          <div class="table-responsive rounded">
            <table class="table table-striped table-hover align-middle mb-0">
              <thead class="table-dark">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Aerolínea</th>
                  <th scope="col">Origen</th>
                  <th scope="col">Destino</th>
                  <th scope="col">Fecha Salida</th>
                  <th scope="col">Hora</th>
                  <th scope="col">Precio</th>
                  <th scope="col">Asientos</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($filasReporte > 0): ?>
                  <?php while ($row = mysqli_fetch_assoc($resReporte)): ?>
                    <tr>
                      <th scope="row"><?= (int) $row['codVuelo'] ?></th>
                      <td><?= htmlspecialchars($row['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($row['origenVuelo'], ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($row['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['fechaSalidaVuelo'])), ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars(substr($row['horaSalidaVuelo'], 0, 5), ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars(formatearPrecio($row['precioVuelo']), ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= (int) $row['asientosDisponibles'] ?></td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay vuelos registrados.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          </div>

        <?php elseif ($reporteActivo === 'usuarios'): ?>
          <div id="area-imprimir">
            <div class="print-encabezado mb-4">
              <h1 class="h5 mb-1">VuelaLibre – Reporte Global</h1>
              <p class="text-secondary mb-0">Reporte de Usuarios · Generado el <?= date('d/m/Y H:i') ?></p>
            </div>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
              <h2 class="h6 fw-bold mb-0">Reporte de Usuarios</h2>
              <button type="button" class="btn btn-outline-info btn-sm no-print" onclick="window.print()">
                <i class="bi bi-printer me-1" aria-hidden="true"></i>Imprimir
              </button>
            </div>
          <div class="table-responsive rounded">
            <table class="table table-striped table-hover align-middle mb-0">
              <thead class="table-dark">
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Usuario</th>
                  <th scope="col">Tipo</th>
                  <th scope="col">Email</th>
                  <th scope="col">Teléfono</th>
                  <th scope="col">Verificado</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($filasReporte > 0): ?>
                  <?php while ($row = mysqli_fetch_assoc($resReporte)): ?>
                    <tr>
                      <th scope="row"><?= (int) $row['codUsuario'] ?></th>
                      <td><?= htmlspecialchars($row['nombreUsuario'] . ' ' . $row['apellidoUsuario'], ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars(ucfirst($row['tipoUsuario']), ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($row['emailUsuario'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($row['telefonoUsuario'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                      <td>
                        <?php if ((int) ($row['verificado'] ?? 0) === 1): ?>
                          <span class="badge bg-success">Sí</span>
                        <?php else: ?>
                          <span class="badge bg-secondary">No</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">No hay usuarios registrados.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="card shadow-sm no-print">
    <div class="card-header fw-semibold">Últimas Reservas</div>
    <div class="card-body">
      <div class="table-responsive rounded">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="table-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Usuario</th>
              <th scope="col">Vuelo</th>
              <th scope="col">Fecha Reserva</th>
              <th scope="col">Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($resUltimasReservas && mysqli_num_rows($resUltimasReservas) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($resUltimasReservas)):
                [$estadoLabel, $estadoClass] = badgeEstadoReserva($row['estadoReserva']);
              ?>
                <tr>
                  <th scope="row"><?= (int) $row['codReserva'] ?></th>
                  <td><?= htmlspecialchars($row['nombreUsuario'] . ' ' . $row['apellidoUsuario'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars($row['origenVuelo'] . ' → ' . $row['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= $row['fechaReserva'] ? htmlspecialchars(date('d/m/Y', strtotime($row['fechaReserva'])), ENT_QUOTES, 'UTF-8') : '—' ?></td>
                  <td><span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8') ?></span></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted py-4">No hay reservas registradas.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($totalPaginas > 1): ?>
        <nav aria-label="Paginación de reservas" class="mt-4">
          <ul class="pagination justify-content-center mb-0">
            <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="?pagina=<?= $paginaActual - 1 ?><?= $queryReporte ?>" <?= $paginaActual <= 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Anterior</a>
            </li>
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
              <li class="page-item <?= $i === $paginaActual ? 'active' : '' ?>" <?= $i === $paginaActual ? 'aria-current="page"' : '' ?>>
                <a class="page-link" href="?pagina=<?= $i ?><?= $queryReporte ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
              <a class="page-link" href="?pagina=<?= $paginaActual + 1 ?><?= $queryReporte ?>" <?= $paginaActual >= $totalPaginas ? 'tabindex="-1" aria-disabled="true"' : '' ?>>Siguiente</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php include '../Footer/footer.php'; ?>

</body>
</html>
