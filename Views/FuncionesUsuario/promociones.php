<?php
session_start();
include '../../config/conexion.php';
$codAeroFiltro = isset($_GET['codAerolinea']) ? (int)$_GET['codAerolinea'] : 0;
$sqlAero = "SELECT codAerolinea, nombreAerolinea FROM AEROLINEAS ORDER BY nombreAerolinea ASC";
$resAero = mysqli_query($link, $sqlAero);
$aerolineas = [];
if ($resAero) {
    while ($a = mysqli_fetch_assoc($resAero)) {
        $aerolineas[] = $a;
    }
}
$whereAero = $codAeroFiltro > 0 ? "AND a.codAerolinea = $codAeroFiltro" : '';
$sqlPromos = "SELECT p.codPromocion, p.descripcionPromocion, p.descuentoPromocion,
                     a.codAerolinea, a.nombreAerolinea
              FROM PROMOCIONES p
              JOIN AEROLINEAS a ON p.codAerolinea = a.codAerolinea
              WHERE p.estadoPromocion = 'aprobada' $whereAero
              ORDER BY p.descuentoPromocion DESC";
$resPromos = mysqli_query($link, $sqlPromos);
$promos = [];
if ($resPromos) {
    while ($p = mysqli_fetch_assoc($resPromos)) {
        $promos[] = $p;
    }
}
$totalPromos   = count($promos);
$porPagina     = 2;
$totalPaginas  = max(1, (int)ceil($totalPromos / $porPagina));
$paginaPromos  = max(1, min($totalPaginas, (int)($_GET['pagina'] ?? 1)));
$promosPag     = array_slice($promos, ($paginaPromos - 1) * $porPagina, $porPagina);
$urlBasePromos = 'promociones.php' . ($codAeroFiltro > 0 ? '?codAerolinea=' . $codAeroFiltro . '&' : '?');
$sinPromo = [];
if ($codAeroFiltro === 0) {
    $sqlSin = "SELECT a.codAerolinea, a.nombreAerolinea
               FROM AEROLINEAS a
               WHERE a.codAerolinea NOT IN (
                   SELECT codAerolinea FROM PROMOCIONES WHERE estadoPromocion = 'aprobada'
               )
               ORDER BY a.nombreAerolinea ASC";
    $resSin = mysqli_query($link, $sqlSin);
    if ($resSin) {
        while ($s = mysqli_fetch_assoc($resSin)) {
            $sinPromo[] = $s;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Consultá las promociones vigentes de todas las aerolíneas en VuelaLibre." />
  <title>Promociones | VuelaLibre – UTN FRR</title>

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
    <section class="py-5" aria-labelledby="promociones-titulo">
      <div class="container">

        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-tag-fill me-1" aria-hidden="true"></i>Descuentos
          </p>
          <h1 id="promociones-titulo" class="h3 fw-bold mb-0">Promociones vigentes</h1>
          <p class="text-secondary">
            Descuentos aprobados por el Administrador. Solo puede haber
            una promoción vigente por aerolínea a la vez.
          </p>
        </div>

        <div class="card border-0 shadow-sm p-3 mb-4">
          <form action="promociones.php" method="get"
                class="d-flex align-items-center gap-3 flex-wrap"
                aria-label="Filtrar promociones por aerolínea">
            <label for="filtroAerolinea" class="form-label fw-semibold mb-0 small">
              <i class="bi bi-building me-1" aria-hidden="true"></i>Filtrar por aerolínea:
            </label>
            <select id="filtroAerolinea" name="codAerolinea"
                    class="form-select form-select-sm w-auto">
              <option value="">Todas las aerolíneas</option>
              <?php foreach ($aerolineas as $aero): ?>
              <option value="<?= (int)$aero['codAerolinea'] ?>"
                      <?= $codAeroFiltro === (int)$aero['codAerolinea'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($aero['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>
              </option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            <a href="promociones.php" class="btn btn-outline-secondary btn-sm">Ver todas las promociones</a>
          </form>
        </div>

        <ul class="list-unstyled" role="list" aria-label="Lista de promociones vigentes">

          <?php if (!empty($promosPag)): ?>
            <?php foreach ($promosPag as $p):
              $codFmt    = str_pad((string)$p['codPromocion'], 3, '0', STR_PAD_LEFT);
              $descuento = (int)$p['descuentoPromocion'];
            ?>
            <li class="mb-4" role="listitem">
              <article class="card border-0 shadow-sm tarjeta-promocion"
                       aria-label="Promoción <?= $codFmt ?>: <?= htmlspecialchars($p['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>, <?= $descuento ?>% de descuento">
                <div class="card-body p-4">
                  <div class="row align-items-center g-4">

                    <div class="col-md-2 text-center">
                      <div class="insignia-descuento text-success"><?= $descuento ?>%</div>
                      <div class="text-success small fw-semibold">DE DESCUENTO</div>
                    </div>

                    <div class="col-md-7">
                      <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                          <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Aprobada
                        </span>
                        <small class="text-secondary">Cód: <?= $codFmt ?></small>
                      </div>
                      <h2 class="h6 fw-bold mb-1">
                        <i class="bi bi-lightning-charge text-primary me-1" aria-hidden="true"></i>
                        <?= htmlspecialchars($p['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>
                      </h2>
                      <?php if (!empty($p['descripcionPromocion'])): ?>
                        <p class="text-secondary small mb-3">
                          <?= htmlspecialchars($p['descripcionPromocion'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                      <?php endif; ?>
                      <dl class="mb-0">
                        <div class="fila-detalle">
                          <dt class="etiqueta-detalle">Aerolínea</dt>
                          <dd class="fw-semibold mb-0">
                            <?= htmlspecialchars($p['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>
                            · Cód: <?= (int)$p['codAerolinea'] ?>
                          </dd>
                        </div>
                        <div class="fila-detalle">
                          <dt class="etiqueta-detalle">Descuento aplicado</dt>
                          <dd class="fw-semibold mb-0 text-success"><?= $descuento ?>%</dd>
                        </div>
                      </dl>
                    </div>

                    <div class="col-md-3 text-md-end">
                      <a href="buscar_vuelos.php?codAerolinea=<?= (int)$p['codAerolinea'] ?>"
                         class="btn btn-primary w-100">
                        <i class="bi bi-search me-1" aria-hidden="true"></i>
                        Ver vuelos con descuento
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
                No hay promociones aprobadas en este momento.
              </div>
            </li>
          <?php endif; ?>

        </ul>

        <?php if ($totalPaginas > 1): ?>
          <nav aria-label="Paginación de promociones" class="my-4">
            <ul class="pagination justify-content-center">
              <li class="page-item <?= $paginaPromos <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $urlBasePromos ?>pagina=<?= $paginaPromos - 1 ?>">Anterior</a>
              </li>
              <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= $i === $paginaPromos ? 'active' : '' ?>">
                  <a class="page-link" href="<?= $urlBasePromos ?>pagina=<?= $i ?>"
                     <?= $i === $paginaPromos ? 'aria-current="page"' : '' ?>><?= $i ?></a>
                </li>
              <?php endfor; ?>
              <li class="page-item <?= $paginaPromos >= $totalPaginas ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= $urlBasePromos ?>pagina=<?= $paginaPromos + 1 ?>">Siguiente</a>
              </li>
            </ul>
          </nav>
        <?php endif; ?>

        <ul class="list-unstyled">

          <?php if (!empty($sinPromo)): ?>
          <li role="listitem">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <h2 class="h6 fw-bold text-secondary mb-3">
                  <i class="bi bi-building me-2" aria-hidden="true"></i>
                  Aerolíneas sin promoción activa
                </h2>
                <ul class="list-unstyled mb-0 d-flex flex-wrap gap-2"
                    aria-label="Aerolíneas sin promoción vigente">
                  <?php foreach ($sinPromo as $s): ?>
                  <li>
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
                      <i class="bi bi-airplane me-1" aria-hidden="true"></i>
                      <?= htmlspecialchars($s['nombreAerolinea'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                  </li>
                  <?php endforeach; ?>
                </ul>
              </div>
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
