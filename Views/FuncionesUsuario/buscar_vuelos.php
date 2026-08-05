<?php
include '../../config/conexion.php';
session_start();
$sqlAero = "SELECT codAerolinea, nombreAerolinea FROM AEROLINEAS ORDER BY nombreAerolinea ASC";
$resAero = mysqli_query($link, $sqlAero);
$aerolineas = [];
if ($resAero) {
    while ($a = mysqli_fetch_assoc($resAero)) {
        $aerolineas[] = $a;
    }
}
$origenInput  = trim($_GET['origenVuelo']      ?? '');
$destinoInput = trim($_GET['destinoVuelo']     ?? '');
$fechaInput   = trim($_GET['fechaSalidaVuelo'] ?? '');
$codAeroInput = isset($_GET['codAerolinea']) ? (int)$_GET['codAerolinea'] : 0;
$conds = ["v.asientosDisponibles > 0", "v.fechaSalidaVuelo >= CURDATE()"];
if ($origenInput !== '') {
    $e = mysqli_real_escape_string($link, $origenInput);
    $conds[] = "v.origenVuelo LIKE '%$e%'";
}
if ($destinoInput !== '') {
    $e = mysqli_real_escape_string($link, $destinoInput);
    $conds[] = "v.destinoVuelo LIKE '%$e%'";
}
if ($fechaInput !== '') {
    $e = mysqli_real_escape_string($link, $fechaInput);
    $conds[] = "v.fechaSalidaVuelo = '$e'";
}
if ($codAeroInput > 0) {
    $conds[] = "v.codAerolinea = $codAeroInput";
}
$whereStr = implode(' AND ', $conds);
$sqlVuelos = "SELECT v.codVuelo, v.origenVuelo, v.destinoVuelo,
                     v.fechaSalidaVuelo, v.horaSalidaVuelo,
                     v.precioVuelo, v.asientosDisponibles,
                     a.codAerolinea, a.nombreAerolinea,
                     p.descuentoPromocion
              FROM VUELOS v
              JOIN AEROLINEAS a ON v.codAerolinea = a.codAerolinea
              LEFT JOIN PROMOCIONES p
                ON p.codAerolinea = v.codAerolinea
               AND p.estadoPromocion = 'aprobada'
              WHERE $whereStr
              ORDER BY v.fechaSalidaVuelo ASC, v.horaSalidaVuelo ASC";
$resVuelos = mysqli_query($link, $sqlVuelos);
$vuelos = [];
if ($resVuelos) {
    while ($v = mysqli_fetch_assoc($resVuelos)) {
        $vuelos[] = $v;
    }
}
$totalVuelos  = count($vuelos);
$porPagina    = 3;
$totalPaginas = max(1, (int)ceil($totalVuelos / $porPagina));
$paginaVuelos = max(1, min($totalPaginas, (int)($_GET['pagina'] ?? 1)));
$vuelosPag    = array_slice($vuelos, ($paginaVuelos - 1) * $porPagina, $porPagina);
$getFiltros = $_GET;
unset($getFiltros['pagina']);
$queryFiltros  = http_build_query($getFiltros);
$urlBaseVuelos = 'buscar_vuelos.php' . ($queryFiltros ? '?' . $queryFiltros . '&' : '?');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Buscá vuelos disponibles en VuelaLibre por origen, destino, fecha y aerolínea." />
  <title>Buscar Vuelos | VuelaLibre – UTN FRR</title>

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
    <section class="py-5" aria-labelledby="buscar-titulo">
      <div class="container">

        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-search me-1" aria-hidden="true"></i>Vuelos
          </p>
          <h1 id="buscar-titulo" class="h3 fw-bold mb-0">Buscar Vuelos</h1>
          <p class="text-secondary">
            Filtrá por origen, destino, fecha y aerolínea para encontrar tu vuelo ideal.
          </p>
        </div>

        <div class="row g-4">

          <div class="col-lg-3">
            <div class="card border-0 shadow-sm p-4 tarjeta-filtros">

              <h2 class="h6 fw-bold text-primary mb-3">
                <i class="bi bi-funnel-fill me-2" aria-hidden="true"></i>Filtros de búsqueda
              </h2>

              <form action="buscar_vuelos.php" method="get"
                    aria-label="Filtros de búsqueda de vuelos">

                <div class="mb-3">
                  <label for="origenVuelo" class="form-label fw-semibold small">
                    <i class="bi bi-geo me-1" aria-hidden="true"></i>Origen
                  </label>
                  <input type="text"
                         id="origenVuelo" name="origenVuelo"
                         class="form-control form-control-sm"
                         placeholder="Ciudad o aeropuerto"
                         value="<?= htmlspecialchars($_GET['origenVuelo'] ?? '') ?>"
                         autocomplete="off"/>
                </div>

                <div class="mb-3">
                  <label for="destinoVuelo" class="form-label fw-semibold small">
                    <i class="bi bi-geo-fill me-1" aria-hidden="true"></i>Destino
                  </label>
                  <input type="text"
                         id="destinoVuelo" name="destinoVuelo"
                         class="form-control form-control-sm"
                         placeholder="Ciudad o aeropuerto"
                         value="<?= htmlspecialchars($_GET['destinoVuelo'] ?? '') ?>"
                         autocomplete="off"/>
                </div>

                <div class="mb-3">
                  <label for="fechaSalidaVuelo" class="form-label fw-semibold small">
                    <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Fecha de salida
                  </label>
                  <input type="date"
                         id="fechaSalidaVuelo" name="fechaSalidaVuelo"
                         class="form-control form-control-sm"
                         min="<?= date('Y-m-d') ?>"
                         value="<?= htmlspecialchars($_GET['fechaSalidaVuelo'] ?? '') ?>"/>
                </div>

                <div class="mb-4">
                  <label for="codAerolinea" class="form-label fw-semibold small">
                    <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolínea
                  </label>
                  <select id="codAerolinea" name="codAerolinea"
                          class="form-select form-select-sm">
                    <option value="">Todas las aerolíneas</option>
                    <?php foreach ($aerolineas as $aero): ?>
                    <option value="<?= (int)$aero['codAerolinea'] ?>"
                            <?= $codAeroInput === (int)$aero['codAerolinea'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($aero['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-sm">
                  <i class="bi bi-search me-1" aria-hidden="true"></i>Buscar
                </button>

                <a href="buscar_vuelos.php" class="btn btn-outline-secondary w-100 btn-sm mt-2">
                  <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Limpiar filtros
                </a>

              </form>
            </div>
          </div>

          <div class="col-lg-9">

            <div class="d-flex justify-content-between align-items-center mb-3">
              <p class="text-secondary small mb-0">
                Mostrando <strong><?= $totalVuelos ?></strong>
                  vuelo<?= $totalVuelos !== 1 ? 's' : '' ?> disponible<?= $totalVuelos !== 1 ? 's' : '' ?>
              </p>
            </div>

            <ul class="list-unstyled" role="list" aria-label="Vuelos encontrados">

              <?php if ($totalVuelos > 0): ?>
                <?php foreach ($vuelosPag as $v):
                  $codFmt     = str_pad((string)$v['codVuelo'], 4, '0', STR_PAD_LEFT);
                  $origenCod  = mb_strtoupper(mb_substr($v['origenVuelo'],  0, 3));
                  $destinoCod = mb_strtoupper(mb_substr($v['destinoVuelo'], 0, 3));
                  $descuento  = (float)($v['descuentoPromocion'] ?? 0);
                  $precioBase = (float)$v['precioVuelo'];
                  $precioFinal = $descuento > 0 ? $precioBase * (1 - $descuento / 100) : $precioBase;
                  $asientos   = (int)$v['asientosDisponibles'];
                  $claseAsientos = $asientos > 20 ? 'asientos-disponibles-alto'
                                 : ($asientos >  5 ? 'asientos-disponibles-medio'
                                                   : 'asientos-disponibles-bajo');
                  $fechaFmt   = date('j M Y', strtotime($v['fechaSalidaVuelo']));
                  $horaFmt    = substr($v['horaSalidaVuelo'], 0, 5);
                ?>
                <li class="mb-3" role="listitem">
                  <article class="card tarjeta-vuelo border border-2 border-transparent shadow-sm"
                           aria-label="Vuelo <?= $codFmt ?>: <?= htmlspecialchars($v['origenVuelo'], ENT_QUOTES, 'UTF-8') ?> a <?= htmlspecialchars($v['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?>, <?= $fechaFmt ?>">
                    <div class="card-body p-4">
                      <div class="row align-items-center g-3">

                        <div class="col-12 col-md-2">
                          <div class="d-flex flex-column gap-1">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                              <i class="bi bi-airplane me-1" aria-hidden="true"></i>
                              Cód: <?= $codFmt ?>
                            </span>
                            <small class="text-secondary texto-nombre-aerolinea">
                              <?= htmlspecialchars($v['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>
                            </small>
                          </div>
                        </div>

                        <div class="col-12 col-md-4">
                          <div class="d-flex align-items-center">
                            <div class="text-center">
                              <div class="codigo-iata text-dark fw-bold"><?= $origenCod ?></div>
                              <small class="text-secondary text-uppercase texto-ciudad-ruta">
                                <?= htmlspecialchars($v['origenVuelo'], ENT_QUOTES, 'UTF-8') ?>
                              </small>
                            </div>
                            <div class="linea-ruta" aria-hidden="true"></div>
                            <i class="bi bi-airplane-fill text-primary mx-2" aria-hidden="true"></i>
                            <div class="linea-ruta" aria-hidden="true"></div>
                            <div class="text-center">
                              <div class="codigo-iata text-dark fw-bold"><?= $destinoCod ?></div>
                              <small class="text-secondary text-uppercase texto-ciudad-ruta">
                                <?= htmlspecialchars($v['destinoVuelo'], ENT_QUOTES, 'UTF-8') ?>
                              </small>
                            </div>
                          </div>
                        </div>

                        <div class="col-6 col-md-2 text-center">
                          <div class="text-secondary small">
                            <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
                            <time datetime="<?= htmlspecialchars($v['fechaSalidaVuelo'], ENT_QUOTES, 'UTF-8') ?>T<?= $horaFmt ?>">
                              <?= $fechaFmt ?>
                            </time>
                          </div>
                          <div class="fw-semibold small"><?= $horaFmt ?> hs</div>
                          <div class="<?= $claseAsientos ?> small mt-1">
                            <i class="bi bi-people-fill me-1" aria-hidden="true"></i><?= $asientos ?> asientos
                          </div>
                        </div>

                        <div class="col-6 col-md-2 text-center">
                          <div class="fw-bold fs-5 text-dark">
                            <small class="text-secondary fw-normal texto-moneda">ARS</small>
                            <?= number_format($precioFinal, 0, ',', '.') ?>
                          </div>
                          <?php if ($descuento > 0): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">
                              <i class="bi bi-tag-fill me-1" aria-hidden="true"></i><?= (int)$descuento ?>% OFF
                            </span>
                          <?php else: ?>
                            <small class="text-secondary texto-sin-promo">Sin promo activa</small>
                          <?php endif; ?>
                        </div>

                        <div class="col-12 col-md-2 text-md-end">
                          <a href="reservar_vuelo.php?codVuelo=<?= (int)$v['codVuelo'] ?>"
                             class="btn btn-primary w-100"
                             aria-label="Reservar vuelo <?= $origenCod ?> a <?= $destinoCod ?> el <?= $fechaFmt ?>">
                            <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>
                            Reservar
                          </a>
                        </div>

                      </div>
                    </div>
                  </article>
                </li>
                <?php endforeach; ?>
              <?php else: ?>
                <li role="listitem">
                  <div class="alert alert-info" role="status">
                    <i class="bi bi-info-circle me-2" aria-hidden="true"></i>
                    No se encontraron vuelos disponibles con los filtros seleccionados.
                  </div>
                </li>
              <?php endif; ?>

            </ul>

            <?php if ($totalPaginas > 1): ?>
              <nav aria-label="Paginación de vuelos" class="mt-4">
                <ul class="pagination justify-content-center">
                  <li class="page-item <?= $paginaVuelos <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $urlBaseVuelos ?>pagina=<?= $paginaVuelos - 1 ?>">Anterior</a>
                  </li>
                  <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $i === $paginaVuelos ? 'active' : '' ?>">
                      <a class="page-link" href="<?= $urlBaseVuelos ?>pagina=<?= $i ?>"
                         <?= $i === $paginaVuelos ? 'aria-current="page"' : '' ?>><?= $i ?></a>
                    </li>
                  <?php endfor; ?>
                  <li class="page-item <?= $paginaVuelos >= $totalPaginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= $urlBaseVuelos ?>pagina=<?= $paginaVuelos + 1 ?>">Siguiente</a>
                  </li>
                </ul>
              </nav>
            <?php endif; ?>

          </div>

        </div>

      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>
