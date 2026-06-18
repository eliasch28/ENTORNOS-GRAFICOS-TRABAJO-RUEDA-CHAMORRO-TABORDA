<?php
include '../../config/conexion.php';

$resAero = mysqli_query($link,
    "SELECT codAerolinea, nombreAerolinea, codigoIATA, descripcionAerolinea, codPais
     FROM AEROLINEAS ORDER BY codAerolinea ASC LIMIT 3");
$aerolineas = [];
if ($resAero) {
    while ($a = mysqli_fetch_assoc($resAero)) $aerolineas[] = $a;
}

$resVuelos = mysqli_query($link,
    "SELECT v.codVuelo, v.origenVuelo, v.destinoVuelo,
            v.fechaSalidaVuelo, v.horaSalidaVuelo,
            v.precioVuelo, v.asientosDisponibles,
            a.nombreAerolinea,
            p.descuentoPromocion
     FROM VUELOS v
     JOIN AEROLINEAS a ON v.codAerolinea = a.codAerolinea
     LEFT JOIN PROMOCIONES p
       ON p.codAerolinea = v.codAerolinea AND p.estadoPromocion = 'aprobada'
     WHERE v.asientosDisponibles > 0 AND v.fechaSalidaVuelo >= CURDATE()
     ORDER BY v.fechaSalidaVuelo ASC, v.horaSalidaVuelo ASC
     LIMIT 2");
$vuelos = [];
if ($resVuelos) {
    while ($v = mysqli_fetch_assoc($resVuelos)) $vuelos[] = $v;
}

$iconosAero = ['bi-airplane', 'bi-globe2', 'bi-lightning-charge', 'bi-stars', 'bi-building'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="VuelaLibre – Sistema de gestión de reservas de pasajes de avión. UTN Facultad Regional Rosario, Cátedra Entornos Gráficos 2026." />
  <title>VuelaLibre – Inicio | Reservas de Vuelos</title>

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

    <section class="seccion-portada text-white bg-primary py-5"
             aria-labelledby="hero-titulo">
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
              <a href="../FlujoSesion/registrarse_.php" class="btn btn-outline-light btn-lg">
                <i class="bi bi-person-plus me-2" aria-hidden="true"></i>Crear Cuenta
              </a>
            </div>
            <ul class="d-flex gap-5 mt-5 pt-4 border-top border-white border-opacity-25 list-unstyled"
                aria-label="Estadísticas del sistema">
              <li class="text-center">
                <span class="d-block fs-3 fw-bold">12+</span>
                <span class="text-white-50 small text-uppercase">Aerolíneas</span>
              </li>
              <li class="text-center">
                <span class="d-block fs-3 fw-bold">340+</span>
                <span class="text-white-50 small text-uppercase">Rutas</span>
              </li>
              <li class="text-center">
                <span class="d-block fs-3 fw-bold">98%</span>
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
            $icono   = $iconosAero[$i % count($iconosAero)];
            $codFmt  = str_pad((string)$aero['codAerolinea'], 3, '0', STR_PAD_LEFT);
            $nombre  = htmlspecialchars($aero['nombreAerolinea'], ENT_QUOTES, 'UTF-8');
            $desc    = htmlspecialchars($aero['descripcionAerolinea'] ?? '', ENT_QUOTES, 'UTF-8');
            $pais    = htmlspecialchars($aero['codPais'] ?? '', ENT_QUOTES, 'UTF-8');
            $iata    = htmlspecialchars($aero['codigoIATA'], ENT_QUOTES, 'UTF-8');
          ?>
          <li class="col-sm-6 col-lg-4" role="listitem">
            <article class="card tarjeta-aerolinea h-100 border-0 shadow-sm"
                     aria-label="<?= $nombre ?>">
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
                <a href="#seccion-vuelos" class="btn btn-outline-primary btn-sm mt-3">
                  Ver vuelos de <?= $nombre ?>
                  <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                </a>
              </div>
            </article>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <div class="alert alert-light border text-center py-4" role="status">
          <i class="bi bi-building text-secondary fs-2 d-block mb-2" aria-hidden="true"></i>
          <p class="mb-2 text-secondary">Aún no hay aerolíneas registradas en el sistema.</p>
          <a href="../FlujoSesion/login.php" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
            Iniciá sesión para conocer más
          </a>
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

        <?php if (!empty($vuelos)): ?>
        <ul class="list-unstyled" role="list" aria-label="Lista de vuelos disponibles">
          <?php foreach ($vuelos as $v):
            $codFmt     = str_pad((string)$v['codVuelo'], 4, '0', STR_PAD_LEFT);
            $origenCod  = mb_strtoupper(mb_substr($v['origenVuelo'],  0, 3));
            $destinoCod = mb_strtoupper(mb_substr($v['destinoVuelo'], 0, 3));
            $descuento  = (float)($v['descuentoPromocion'] ?? 0);
            $precioBase = (float)$v['precioVuelo'];
            $precioFinal = $descuento > 0 ? $precioBase * (1 - $descuento / 100) : $precioBase;
            $asientos   = (int)$v['asientosDisponibles'];
            $claseAsientos = $asientos > 20 ? 'text-success' : ($asientos > 5 ? 'text-warning' : 'text-danger');
            $fechaFmt   = date('j M Y', strtotime($v['fechaSalidaVuelo']));
            $horaFmt    = substr($v['horaSalidaVuelo'], 0, 5);
          ?>
          <li class="mb-3" role="listitem">
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
                        <i class="bi bi-tag-fill me-1" aria-hidden="true"></i><?= (int)$descuento ?>% OFF
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
                    <a href="../FlujoSesion/login.php" class="btn btn-primary btn-sm mt-2">
                      <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
                      Iniciar sesión para reservar
                    </a>
                  </div>

                </div>
              </div>
            </article>
          </li>
          <?php endforeach; ?>
        </ul>
        <div class="text-center mt-3">
          <a href="../FlujoSesion/login.php" class="btn btn-outline-primary">
            Iniciar sesión para ver todos los vuelos
            <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
          </a>
        </div>
        <?php else: ?>
        <div class="alert alert-light border text-center py-4" role="status">
          <i class="bi bi-airplane text-secondary fs-2 d-block mb-2" aria-hidden="true"></i>
          <p class="mb-2 text-secondary">No hay vuelos disponibles en este momento.</p>
          <a href="../FlujoSesion/login.php" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
            Iniciá sesión para conocer más
          </a>
        </div>
        <?php endif; ?>
      </div>
    </section>

    <section class="py-5 bg-primary text-white text-center" aria-labelledby="cta-titulo">
      <div class="container py-3">
        <h2 id="cta-titulo" class="display-6 fw-bold mb-3">¿Listo para despegar?</h2>
        <p class="lead text-white-50 mb-4">
          Creá tu cuenta gratis y empezá a explorar vuelos y promociones exclusivas.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <a href="../FlujoSesion/registrarse_.php" class="btn btn-light btn-lg text-primary fw-semibold">
            <i class="bi bi-person-plus-fill me-2" aria-hidden="true"></i>Registrarse gratis
          </a>
          <a href="../FlujoSesion/login.php" class="btn btn-outline-light btn-lg">
            <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Ya tengo cuenta
          </a>
        </div>
      </div>
    </section>

  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>

