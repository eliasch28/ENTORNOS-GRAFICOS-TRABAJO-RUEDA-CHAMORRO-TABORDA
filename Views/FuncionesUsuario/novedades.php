<?php
session_start();
include '../../config/conexion.php';
include '../../config/fechas.php';
$hoy = date('Y-m-d');
function estadoNovedadUsuario(string $fechaPub, string $fechaExp, string $hoy): array {
  if ($hoy < $fechaPub) {
    return [
      'proxima',
      'Próxima',
      'bg-warning-subtle text-warning border border-warning-subtle',
      'novedad-estado-proxima',
      'bi-arrow-repeat text-warning',
    ];
  }
  if ($hoy > $fechaExp) {
    return [
      'expirada',
      'Expirada',
      'bg-secondary-subtle text-secondary border border-secondary-subtle',
      'novedad-estado-expirada',
      'bi-tag text-secondary',
    ];
  }
  return [
    'vigente',
    'Vigente',
    'bg-primary-subtle text-primary border border-primary-subtle',
    'novedad-estado-vigente',
    'bi-megaphone text-primary',
  ];
}
$sqlNovedades = "SELECT codNovedad, textoNovedad, fechaPublicacionNovedad, fechaExpiracionNovedad
                 FROM NOVEDADES
                 ORDER BY fechaPublicacionNovedad DESC";
$resNovedades = mysqli_query($link, $sqlNovedades);
$novedades = [];
if ($resNovedades) {
    while ($row = mysqli_fetch_assoc($resNovedades)) {
        $novedades[] = $row;
    }
}
$totalNovedades = count($novedades);
$porPagina      = 2;
$totalPaginas   = max(1, (int)ceil($totalNovedades / $porPagina));
$pagina         = max(1, min($totalPaginas, (int)($_GET['pagina'] ?? 1)));
$novedadesPag   = array_slice($novedades, ($pagina - 1) * $porPagina, $porPagina);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Consultá las novedades y anuncios del sistema VuelaLibre." />
  <title>Novedades | VuelaLibre – UTN FRR</title>

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
    <section class="py-5" aria-labelledby="novedades-titulo">
      <div class="container">

        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-megaphone me-1" aria-hidden="true"></i>Sistema
          </p>
          <h1 id="novedades-titulo" class="h3 fw-bold mb-0">Novedades</h1>
          <p class="text-secondary">
            Anuncios y comunicados del sistema publicados por el Administrador.
          </p>
        </div>

        <div class="d-flex gap-3 flex-wrap mb-4 small">
          <span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-1">
              ● Vigente
            </span>
            Activa hoy
          </span>
          <span>
            <span class="badge bg-warning-subtle text-warning border border-warning-subtle me-1">
              ● Próxima
            </span>
            Aún no inició
          </span>
          <span>
            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle me-1">
              ● Expirada
            </span>
            Ya finalizó
          </span>
        </div>

        <ul class="list-unstyled" role="list" aria-label="Lista de novedades del sistema">

          <?php if (!empty($novedadesPag)): ?>
            <?php foreach ($novedadesPag as $row):
              [$estadoKey, $estadoLabel, $badgeClass, $cardClass, $iconClass] = estadoNovedadUsuario(
                $row['fechaPublicacionNovedad'],
                $row['fechaExpiracionNovedad'],
                $hoy
              );
              $codFmt = str_pad((string) $row['codNovedad'], 4, '0', STR_PAD_LEFT);
              $texto = $row['textoNovedad'];
              $fechaPubFmt = formatearFechaLarga($row['fechaPublicacionNovedad']);
              $fechaExpFmt = formatearFechaLarga($row['fechaExpiracionNovedad']);
              $expirada = $estadoKey === 'expirada';
            ?>
              <li class="mb-4">
                <article class="card border-0 shadow-sm <?= $cardClass ?>"
                         aria-label="Novedad <?= $codFmt ?>: <?= htmlspecialchars(mb_substr($texto, 0, 40), ENT_QUOTES, 'UTF-8') ?> — <?= strtolower($estadoLabel) ?>">
                  <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                      <div class="d-flex align-items-center gap-2">
                        <span class="badge <?= $badgeClass ?>">
                          Cód: <?= htmlspecialchars($codFmt, ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <span class="badge <?= $badgeClass ?>">
                          <i class="bi bi-circle-fill me-1 punto-estado-novedad" aria-hidden="true"></i>
                          <?= htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8') ?>
                        </span>
                      </div>
                    </div>

                    <h2 class="h6 fw-bold mb-2<?= $expirada ? ' text-secondary' : '' ?>">
                      <i class="bi <?= $iconClass ?> me-2" aria-hidden="true"></i>
                      <?= htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') ?>
                    </h2>

                    <div class="fechas-novedad">
                      <span>
                        <i class="bi bi-calendar-plus me-1" aria-hidden="true"></i>
                        Publicada:
                        <time datetime="<?= htmlspecialchars($row['fechaPublicacionNovedad'], ENT_QUOTES, 'UTF-8') ?>">
                          <?= htmlspecialchars($fechaPubFmt, ENT_QUOTES, 'UTF-8') ?>
                        </time>
                      </span>
                      <span>
                        <i class="bi bi-calendar-x me-1" aria-hidden="true"></i>
                        <?= $expirada ? 'Expiró' : 'Expira' ?>:
                        <time datetime="<?= htmlspecialchars($row['fechaExpiracionNovedad'], ENT_QUOTES, 'UTF-8') ?>">
                          <?= htmlspecialchars($fechaExpFmt, ENT_QUOTES, 'UTF-8') ?>
                        </time>
                      </span>
                    </div>

                  </div>
                </article>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li>
              <div class="alert alert-info mb-0" role="status">
                <i class="bi bi-info-circle me-2" aria-hidden="true"></i>
                No hay novedades publicadas en este momento.
              </div>
            </li>
          <?php endif; ?>

        </ul>

        <?php if ($totalPaginas > 1): ?>
          <nav aria-label="Paginación de novedades" class="mt-4">
            <ul class="pagination justify-content-center">
              <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="novedades.php?pagina=<?= $pagina - 1 ?>">Anterior</a>
              </li>
              <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                  <a class="page-link" href="novedades.php?pagina=<?= $i ?>"
                     <?= $i === $pagina ? 'aria-current="page"' : '' ?>><?= $i ?></a>
                </li>
              <?php endfor; ?>
              <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
                <a class="page-link" href="novedades.php?pagina=<?= $pagina + 1 ?>">Siguiente</a>
              </li>
            </ul>
          </nav>
        <?php endif; ?>

      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>
