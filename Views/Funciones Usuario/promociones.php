<?php session_start(); ?>
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
              <option value="1" <?= (($_GET['codAerolinea'] ?? '') == '1') ? 'selected' : '' ?>>
                Aerolíneas Argentinas
              </option>
              <option value="2" <?= (($_GET['codAerolinea'] ?? '') == '2') ? 'selected' : '' ?>>
                LATAM Airlines
              </option>
              <option value="3" <?= (($_GET['codAerolinea'] ?? '') == '3') ? 'selected' : '' ?>>
                Flybondi
              </option>
              <option value="4" <?= (($_GET['codAerolinea'] ?? '') == '4') ? 'selected' : '' ?>>
                JetSmart
              </option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            <a href="promociones.php" class="btn btn-outline-secondary btn-sm">Ver todas las promociones</a>
          </form>
        </div>

        <!--
          TODO: Esta lista se generará dinámicamente con PHP
          haciendo un SELECT a la tabla PROMOCIONES donde
          estadoPromocion = 'aprobada'
          ORDER BY codPromocion DESC.
        -->
        <ul class="list-unstyled" role="list" aria-label="Lista de promociones vigentes">

          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm tarjeta-promocion"
                     aria-label="Promoción 001: Flybondi, 30% de descuento">
              <div class="card-body p-4">
                <div class="row align-items-center g-4">

                  <div class="col-md-2 text-center">
                    <div class="insignia-descuento text-success">30%</div>
                    <div class="text-success small fw-semibold">DE DESCUENTO</div>
                  </div>

                  <div class="col-md-7">
                    <div class="d-flex align-items-center gap-2 mb-2">
                      <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Aprobada
                      </span>
                      <small class="text-secondary">Cód: 001</small>
                    </div>
                    <h2 class="h6 fw-bold mb-1">
                      <i class="bi bi-lightning-charge text-primary me-1" aria-hidden="true"></i>
                      Flybondi — Descuento mayo
                    </h2>
                    <p class="text-secondary small mb-3">
                      30% de descuento en todos los pasajes durante el mes de mayo.
                      Válido para vuelos domésticos. Se aplica automáticamente
                      al seleccionar un vuelo de Flybondi.
                    </p>
                    <dl class="mb-0">
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Aerolínea</dt>
                        <dd class="fw-semibold mb-0">Flybondi · Cód: 003</dd>
                      </div>
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Descuento aplicado</dt>
                        <dd class="fw-semibold mb-0 text-success">30%</dd>
                      </div>
                    </dl>
                  </div>

                  <div class="col-md-3 text-md-end">
                    <a href="buscar_vuelos.php?codAerolinea=3"
                       class="btn btn-primary w-100">
                      <i class="bi bi-search me-1" aria-hidden="true"></i>
                      Ver vuelos de Flybondi con descuento
                    </a>
                  </div>

                </div>
              </div>
            </article>
          </li>

          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm tarjeta-promocion"
                     aria-label="Promoción 002: Aerolíneas Argentinas, 15% de descuento">
              <div class="card-body p-4">
                <div class="row align-items-center g-4">

                  <div class="col-md-2 text-center">
                    <div class="insignia-descuento text-success">15%</div>
                    <div class="text-success small fw-semibold">DE DESCUENTO</div>
                  </div>

                  <div class="col-md-7">
                    <div class="d-flex align-items-center gap-2 mb-2">
                      <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>Aprobada
                      </span>
                      <small class="text-secondary">Cód: 002</small>
                    </div>
                    <h2 class="h6 fw-bold mb-1">
                      <i class="bi bi-airplane text-primary me-1" aria-hidden="true"></i>
                      Aerolíneas Argentinas — Primera compra
                    </h2>
                    <p class="text-secondary small mb-3">
                      15% de descuento adicional para pasajeros que realizan
                      su primera compra en el sistema. Se aplica automáticamente
                      al seleccionar un vuelo de Aerolíneas Argentinas.
                    </p>
                    <dl class="mb-0">
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Aerolínea</dt>
                        <dd class="fw-semibold mb-0">Aerolíneas Argentinas · Cód: 001</dd>
                      </div>
                      <div class="fila-detalle">
                        <dt class="etiqueta-detalle">Descuento aplicado</dt>
                        <dd class="fw-semibold mb-0 text-success">15%</dd>
                      </div>
                    </dl>
                  </div>

                  <div class="col-md-3 text-md-end">
                    <a href="buscar_vuelos.php?codAerolinea=1"
                       class="btn btn-primary w-100">
                      <i class="bi bi-search me-1" aria-hidden="true"></i>
                      Ver vuelos de Aer. Argentinas con descuento
                    </a>
                  </div>

                </div>
              </div>
            </article>
          </li>

          <li role="listitem">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <h2 class="h6 fw-bold text-secondary mb-3">
                  <i class="bi bi-building me-2" aria-hidden="true"></i>
                  Aerolíneas sin promoción activa
                </h2>
                <ul class="list-unstyled mb-0 d-flex flex-wrap gap-2"
                    aria-label="Aerolíneas sin promoción vigente">
                  <li>
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
                      <i class="bi bi-globe2 me-1" aria-hidden="true"></i>
                      LATAM Airlines
                    </span>
                  </li>
                  <li>
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
                      <i class="bi bi-stars me-1" aria-hidden="true"></i>
                      JetSmart
                    </span>
                  </li>
                </ul>
              </div>
            </div>
          </li>

        </ul>

      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>

