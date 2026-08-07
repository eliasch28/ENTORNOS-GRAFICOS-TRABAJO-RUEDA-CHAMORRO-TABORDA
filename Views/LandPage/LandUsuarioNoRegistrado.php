<?php
include '../../config/conexion.php';
include '../../config/fechas.php';

$iconosAero = ['bi-airplane', 'bi-globe2', 'bi-lightning-charge', 'bi-stars', 'bi-building'];

/* ── TOTALES PARA LA PORTADA ───────────────────────────────────────── */
$resTotalAerolineas = mysqli_query($link, "SELECT COUNT(*) AS total FROM AEROLINEAS");
$totalAerolineas = (int) (mysqli_fetch_assoc($resTotalAerolineas)['total'] ?? 0);
$resTotalVuelos = mysqli_query($link, "SELECT COUNT(*) AS total FROM VUELOS");
$totalVuelos = (int) (mysqli_fetch_assoc($resTotalVuelos)['total'] ?? 0);

/* ════ SECCIÓN AEROLÍNEAS (paginadas de a 3) ════ */
$porPagAero = 3;
$resCantAero = mysqli_query($link, "SELECT COUNT(*) AS total FROM AEROLINEAS");
$cantAero = (int) (mysqli_fetch_assoc($resCantAero)['total'] ?? 0);
$totPaginasAero = max(1, (int) ceil($cantAero / $porPagAero));
$paginaAero = max(1, min($totPaginasAero, (int) ($_GET['paginaAero'] ?? 1)));
$offsetAero = ($paginaAero - 1) * $porPagAero;
$resAero = mysqli_query(
  $link,
  "SELECT codAerolinea, nombreAerolinea, codigoIATA, descripcionAerolinea, codPais
     FROM AEROLINEAS ORDER BY codAerolinea ASC LIMIT $offsetAero, $porPagAero"
);
$aerolineas = [];
if ($resAero) {
  while ($a = mysqli_fetch_assoc($resAero))
    $aerolineas[] = $a;
}
$getAero = $_GET;
unset($getAero['paginaAero']);
$urlBaseAero = 'LandUsuarioNoRegistrado.php' . (http_build_query($getAero) ? '?' . http_build_query($getAero) . '&' : '?');

/* ── TODAS LAS AEROLÍNEAS (para el select de filtros de vuelos) ──── */
$resAeroTodas = mysqli_query($link, "SELECT codAerolinea, nombreAerolinea FROM AEROLINEAS ORDER BY nombreAerolinea ASC");
$aerolineasTodas = [];
if ($resAeroTodas) {
  while ($a = mysqli_fetch_assoc($resAeroTodas))
    $aerolineasTodas[] = $a;
}

/* ════ SECCIÓN VUELOS (paginados de a 2 + filtros) ════ */
$origenInput  = trim($_GET['origenVuelo']      ?? '');
$destinoInput = trim($_GET['destinoVuelo']     ?? '');
$fechaInput   = trim($_GET['fechaSalidaVuelo'] ?? '');
$codAeroInput = isset($_GET['codAerolinea']) ? (int) $_GET['codAerolinea'] : 0;
$conds = ["v.asientosDisponibles > 0", "v.fechaSalidaVuelo >= CURDATE()"];
$tiposVuelos = '';
$valoresVuelos = [];
if ($origenInput !== '') {
  $conds[] = "v.origenVuelo LIKE ?";
  $tiposVuelos .= 's';
  $valoresVuelos[] = '%' . $origenInput . '%';
}
if ($destinoInput !== '') {
  $conds[] = "v.destinoVuelo LIKE ?";
  $tiposVuelos .= 's';
  $valoresVuelos[] = '%' . $destinoInput . '%';
}
if ($fechaInput !== '') {
  $conds[] = "v.fechaSalidaVuelo = ?";
  $tiposVuelos .= 's';
  $valoresVuelos[] = $fechaInput;
}
if ($codAeroInput > 0) {
  $conds[] = "v.codAerolinea = ?";
  $tiposVuelos .= 'i';
  $valoresVuelos[] = $codAeroInput;
}
$whereVuelos = implode(' AND ', $conds);
$stmtVuelos = mysqli_prepare(
  $link,
  "SELECT v.codVuelo, v.origenVuelo, v.destinoVuelo,
            v.fechaSalidaVuelo, v.horaSalidaVuelo,
            v.precioVuelo, v.asientosDisponibles,
            a.nombreAerolinea,
            p.descuentoPromocion
     FROM VUELOS v
     JOIN AEROLINEAS a ON v.codAerolinea = a.codAerolinea
     LEFT JOIN PROMOCIONES p
       ON p.codAerolinea = v.codAerolinea AND p.estadoPromocion = 'aprobada'
     WHERE $whereVuelos
     ORDER BY v.fechaSalidaVuelo ASC, v.horaSalidaVuelo ASC"
);
if ($tiposVuelos !== '') {
  mysqli_stmt_bind_param($stmtVuelos, $tiposVuelos, ...$valoresVuelos);
}
mysqli_stmt_execute($stmtVuelos);
$resVuelos = mysqli_stmt_get_result($stmtVuelos);
$vuelosFiltrados = [];
if ($resVuelos) {
  while ($v = mysqli_fetch_assoc($resVuelos))
    $vuelosFiltrados[] = $v;
}
$totalVuelosFiltrados = count($vuelosFiltrados);
$porPagVuelo = 5;
$totPaginasVuelo = max(1, (int) ceil($totalVuelosFiltrados / $porPagVuelo));
$paginaVuelos = max(1, min($totPaginasVuelo, (int) ($_GET['paginaVuelo'] ?? 1)));
$vuelos = array_slice($vuelosFiltrados, ($paginaVuelos - 1) * $porPagVuelo, $porPagVuelo);
$getVuelos = $_GET;
unset($getVuelos['paginaVuelo']);
$urlBaseVuelos = 'LandUsuarioNoRegistrado.php' . (http_build_query($getVuelos) ? '?' . http_build_query($getVuelos) . '&' : '?');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
    content="VuelaLibre – Sistema de gestión de reservas de pasajes de avión. UTN Facultad Regional Rosario, Cátedra Entornos Gráficos 2026." />
  <title>VuelaLibre – Inicio | Reservas de Vuelos</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../styles.css" />
</head>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1">

    <section class="seccion-portada text-white bg-primary py-5" aria-labelledby="hero-titulo">
      <div class="container py-5">
        <div class="row">
          <div class="col-lg-7">
            <p class="text-white-50 text-uppercase small fw-semibold mb-2">
              <i class="bi bi-globe2 me-1" aria-hidden="true"></i>
              UTN FRR · Entornos Gráficos 2026
            </p>
            <h1 id="hero-titulo" class="display-4 fw-bold mb-3">
              Tu próximo vuelo,<br>a un clic de distancia
            </h1>
            <p class="lead text-white-50 mb-4">
              Buscá y reservá vuelos en las mejores aerolíneas, gestioná tus
              reservas y consultá tus compras desde un solo lugar.
            </p>
            <div class="d-flex gap-3 flex-wrap">
              <a href="#seccion-vuelos" class="btn btn-light btn-lg text-primary fw-semibold">
                <i class="bi bi-search me-2" aria-hidden="true"></i>Buscar Vuelos
              </a>
            </div>
            <ul class="d-flex gap-5 mt-5 pt-4 border-top border-white border-opacity-25 list-unstyled"
              aria-label="Estadísticas del sistema">
              <li class="text-center">
                <span class="d-block fs-3 fw-bold">
                  <?= $totalAerolineas ?>
                </span>
                <span class="text-white-50 small text-uppercase">Aerolíneas</span>
              </li>
              <li class="text-center">
                <span class="d-block fs-3 fw-bold">
                  <?= $totalVuelos ?>
                </span>
                <span class="text-white-50 small text-uppercase">Vuelos</span>
              </li>
              <li class="text-center">
                <span class="d-block fs-3 fw-bold">100%</span>
                <span class="text-white-50 small text-uppercase">Satisfacción</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section id="seccion-aerolineas" class="py-5" aria-labelledby="aerolineas-titulo">
      <div class="container">
        <header class="mb-5">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolíneas
          </p>
          <h2 id="aerolineas-titulo" class="h2 fw-bold">Nuestras aerolíneas asociadas</h2>
          <p class="text-secondary">Trabajamos con las principales aerolíneas de la región.</p>
        </header>

        <?php if (!empty($aerolineas)): ?>
          <ul class="row g-4 list-unstyled" role="list" aria-label="Lista de aerolíneas">
            <?php foreach ($aerolineas as $i => $aero):
              $icono = $iconosAero[$i % count($iconosAero)];
              $codFmt = str_pad((string) $aero['codAerolinea'], 3, '0', STR_PAD_LEFT);
              $nombre = htmlspecialchars($aero['nombreAerolinea'], ENT_QUOTES, 'UTF-8');
              $desc = htmlspecialchars($aero['descripcionAerolinea'] ?? '', ENT_QUOTES, 'UTF-8');
              $pais = htmlspecialchars($aero['codPais'] ?? '', ENT_QUOTES, 'UTF-8');
              $iata = htmlspecialchars($aero['codigoIATA'], ENT_QUOTES, 'UTF-8');
            ?>
              <li class="col-sm-6 col-lg-4">
                <article class="card tarjeta-aerolinea h-100 border-0 shadow-sm" aria-label="<?= $nombre ?>">
                  <div class="card-body d-flex flex-column p-4">
                    <i class="bi <?= $icono ?> text-primary fs-1 mb-3 icono-aerolinea" aria-hidden="true"></i>
                    <p class="text-primary small text-uppercase fw-semibold mb-1">
                      IATA: <?= $iata ?> · Cód: <?= $codFmt ?>
                    </p>
                    <h3 class="h6 fw-bold mb-1"><?= $nombre ?></h3>
                    <?php if ($pais !== ''): ?>
                      <p class="text-secondary small mb-2">
                        <i class="bi bi-geo-alt-fill me-1" aria-hidden="true"></i><?= $pais ?>
                      </p>
                    <?php endif; ?>
                    <?php if ($desc !== ''): ?>
                      <p class="text-secondary small flex-grow-1"><?= $desc ?></p>
                    <?php else: ?>
                      <p class="flex-grow-1"></p>
                    <?php endif; ?>
                  </div>
                </article>
              </li>
            <?php endforeach; ?>
            <?php for ($h = count($aerolineas); $h < $porPagAero; $h++): ?>
              <li class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm bg-light bg-opacity-50 d-flex align-items-center justify-content-center text-center p-4" style="min-height: 250px;">
                  <div>
                    <i class="bi bi-cloud-sun text-secondary fs-2 d-block mb-2" aria-hidden="true"></i>
                    <p class="mb-0 text-secondary fw-semibold">Nada que ver por ahora</p>
                  </div>
                </div>
              </li>
            <?php endfor; ?>
          </ul>

          <?php if ($totPaginasAero > 1): ?>
            <nav aria-label="Paginación de aerolíneas" class="mt-4">
              <ul class="pagination justify-content-center">
                <li class="page-item <?= $paginaAero <= 1 ? 'disabled' : '' ?>">
                  <a class="page-link" href="<?= $urlBaseAero ?>paginaAero=<?= $paginaAero - 1 ?>#seccion-aerolineas">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $totPaginasAero; $i++): ?>
                  <li class="page-item <?= $i === $paginaAero ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $urlBaseAero ?>paginaAero=<?= $i ?>#seccion-aerolineas"
                      <?= $i === $paginaAero ? 'aria-current="page"' : '' ?>><?= $i ?></a>
                  </li>
                <?php endfor; ?>
                <li class="page-item <?= $paginaAero >= $totPaginasAero ? 'disabled' : '' ?>">
                  <a class="page-link" href="<?= $urlBaseAero ?>paginaAero=<?= $paginaAero + 1 ?>#seccion-aerolineas">Siguiente</a>
                </li>
              </ul>
            </nav>
          <?php endif; ?>
        <?php else: ?>
          <div class="alert alert-light border text-center py-4" role="status">
            <i class="bi bi-building text-secondary fs-2 d-block mb-2" aria-hidden="true"></i>
            <p class="mb-0 text-secondary">Aún no hay aerolíneas registradas en el sistema.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <section id="seccion-vuelos" class="py-5 bg-white" aria-labelledby="vuelos-titulo">
      <div class="container">
        <header class="mb-5">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-airplane-fill me-1" aria-hidden="true"></i>Vuelos
          </p>
          <h2 id="vuelos-titulo" class="h2 fw-bold">Vuelos disponibles</h2>
          <p class="text-secondary">Encontrá el vuelo ideal. Iniciá sesión para reservar.</p>
        </header>

        <div class="card border-0 shadow-sm p-4 mb-4 tarjeta-filtros">
          <form action="LandUsuarioNoRegistrado.php#seccion-vuelos" method="get"
            class="row g-3 align-items-end"
            aria-label="Filtros de búsqueda de vuelos">
            <div class="col-md-3">
              <label for="origenVuelo" class="form-label fw-semibold small">
                <i class="bi bi-geo me-1" aria-hidden="true"></i>Origen
              </label>
              <input type="text" id="origenVuelo" name="origenVuelo" class="form-control form-control-sm"
                placeholder="Ciudad o aeropuerto" value="<?= htmlspecialchars($origenInput, ENT_QUOTES, 'UTF-8') ?>" />
            </div>
            <div class="col-md-3">
              <label for="destinoVuelo" class="form-label fw-semibold small">
                <i class="bi bi-geo-fill me-1" aria-hidden="true"></i>Destino
              </label>
              <input type="text" id="destinoVuelo" name="destinoVuelo" class="form-control form-control-sm"
                placeholder="Ciudad o aeropuerto"
                value="<?= htmlspecialchars($destinoInput, ENT_QUOTES, 'UTF-8') ?>" />
            </div>
            <div class="col-md-2">
              <label for="fechaSalidaVuelo" class="form-label fw-semibold small">
                <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Fecha de salida
              </label>
              <input type="date" id="fechaSalidaVuelo" name="fechaSalidaVuelo"
                class="form-control form-control-sm"
                min="<?= date('Y-m-d') ?>"
                value="<?= htmlspecialchars($fechaInput, ENT_QUOTES, 'UTF-8') ?>" />
            </div>
            <div class="col-md-2">
              <label for="codAerolineaVuelo" class="form-label fw-semibold small">
                <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolínea
              </label>
              <select id="codAerolineaVuelo" name="codAerolinea" class="form-select form-select-sm">
                <option value="">Todas</option>
                <?php foreach ($aerolineasTodas as $aero): ?>
                  <option value="<?= (int) $aero['codAerolinea'] ?>"
                    <?= $codAeroInput === (int) $aero['codAerolinea'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($aero['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-sm flex-fill">
                <i class="bi bi-search me-1" aria-hidden="true"></i>Filtrar
              </button>
              <a href="LandUsuarioNoRegistrado.php#seccion-vuelos"
                class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-x-circle" aria-hidden="true"></i>
              </a>
            </div>
          </form>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <p class="text-secondary small mb-0">
            Mostrando <strong><?= $totalVuelosFiltrados ?></strong>
            vuelo<?= $totalVuelosFiltrados !== 1 ? 's' : '' ?> disponible<?= $totalVuelosFiltrados !== 1 ? 's' : '' ?>
          </p>
        </div>

        <?php if (!empty($vuelos)): ?>
          <ul class="list-unstyled" role="list" aria-label="Lista de vuelos disponibles">
            <?php foreach ($vuelos as $v):
              $codFmt = str_pad((string) $v['codVuelo'], 4, '0', STR_PAD_LEFT);
              $origenCod = mb_strtoupper(mb_substr($v['origenVuelo'], 0, 3));
              $destinoCod = mb_strtoupper(mb_substr($v['destinoVuelo'], 0, 3));
              $descuento = (float) ($v['descuentoPromocion'] ?? 0);
              $precioBase = (float) $v['precioVuelo'];
              $precioFinal = $descuento > 0 ? $precioBase * (1 - $descuento / 100) : $precioBase;
              $asientos = (int) $v['asientosDisponibles'];
              $claseAsientos = $asientos > 20 ? 'text-success' : ($asientos > 5 ? 'text-warning' : 'text-danger');
              $fechaFmt = formatearFechaCorta($v['fechaSalidaVuelo']);
              $horaFmt = substr($v['horaSalidaVuelo'], 0, 5);
            ?>
              <li class="mb-3">
                <article class="card tarjeta-vuelo border-0 shadow-sm"
                  aria-label="Vuelo <?= $codFmt ?>: <?= htmlspecialchars($v['origenVuelo'], ENT_QUOTES, 'UTF-8') ?> a <?= htmlspecialchars($v['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?>, <?= $fechaFmt ?>">
                  <div class="card-body">
                    <div class="row align-items-center g-3">

                      <div class="col-md-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                          <i class="bi bi-airplane me-1" aria-hidden="true"></i>
                          <?= htmlspecialchars($v['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?> · <?= $codFmt ?>
                        </span>
                      </div>

                      <div class="col-md-4">
                        <div class="d-flex align-items-center">
                          <div class="text-center">
                            <div class="codigo-iata text-dark fw-bold"><?= $origenCod ?></div>
                            <small class="text-secondary text-uppercase">
                              <?= htmlspecialchars($v['origenVuelo'], ENT_QUOTES, 'UTF-8') ?>
                            </small>
                          </div>
                          <div class="linea-ruta" aria-hidden="true"></div>
                          <i class="bi bi-airplane-fill text-primary mx-2" aria-hidden="true"></i>
                          <div class="linea-ruta" aria-hidden="true"></div>
                          <div class="text-center">
                            <div class="codigo-iata text-dark fw-bold"><?= $destinoCod ?></div>
                            <small class="text-secondary text-uppercase">
                              <?= htmlspecialchars($v['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?>
                            </small>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-2 text-center">
                        <div class="text-secondary small">
                          <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                          <time datetime="<?= $v['fechaSalidaVuelo'] ?>T<?= $horaFmt ?>">
                            <?= $fechaFmt ?> · <?= $horaFmt ?>
                          </time>
                        </div>
                        <div class="<?= $claseAsientos ?> small mt-1">
                          <i class="bi bi-people-fill me-1" aria-hidden="true"></i><?= $asientos ?> asientos disp.
                        </div>
                      </div>

                      <div class="col-md-1 text-center">
                        <?php if ($descuento > 0): ?>
                          <span class="badge bg-success-subtle text-success border border-success-subtle">
                            <i class="bi bi-tag-fill me-1" aria-hidden="true"></i><?= (int) $descuento ?>% OFF
                          </span>
                        <?php else: ?>
                          <span class="text-secondary small">Sin promo</span>
                        <?php endif; ?>
                      </div>

                      <div class="col-md-3 text-end">
                        <div class="fw-bold fs-5 text-dark">
                          <small class="text-secondary fw-normal fs-6">ARS</small>
                          <?= number_format($precioFinal, 0, ',', '.') ?>
                        </div>
                        <p class="text-secondary small mt-2 mb-0">
                          Iniciá sesión para reservar
                        </p>
                      </div>

                    </div>
                  </div>
                </article>
              </li>
            <?php endforeach; ?>
          </ul>

          <?php if ($totPaginasVuelo > 1): ?>
            <nav aria-label="Paginación de vuelos" class="mt-4">
              <ul class="pagination justify-content-center">
                <li class="page-item <?= $paginaVuelos <= 1 ? 'disabled' : '' ?>">
                  <a class="page-link" href="<?= $urlBaseVuelos ?>paginaVuelo=<?= $paginaVuelos - 1 ?>#seccion-vuelos">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $totPaginasVuelo; $i++): ?>
                  <li class="page-item <?= $i === $paginaVuelos ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $urlBaseVuelos ?>paginaVuelo=<?= $i ?>#seccion-vuelos"
                      <?= $i === $paginaVuelos ? 'aria-current="page"' : '' ?>><?= $i ?></a>
                  </li>
                <?php endfor; ?>
                <li class="page-item <?= $paginaVuelos >= $totPaginasVuelo ? 'disabled' : '' ?>">
                  <a class="page-link" href="<?= $urlBaseVuelos ?>paginaVuelo=<?= $paginaVuelos + 1 ?>#seccion-vuelos">Siguiente</a>
                </li>
              </ul>
            </nav>
          <?php endif; ?>
        <?php else: ?>
          <div class="alert alert-light border text-center py-4" role="status">
            <i class="bi bi-airplane text-secondary fs-2 d-block mb-2" aria-hidden="true"></i>
            <p class="mb-0 text-secondary">No hay vuelos disponibles en este momento.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>

  </main>

  <?php include '../Footer/footer.php'; ?>

</body>

</html>