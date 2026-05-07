<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Consultá las novedades y anuncios del sistema SkyReserva." />
  <title>Novedades | SkyReserva – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="../LandPage/EstilosLandUsuarioNoRegistrado.css"/>

  <style>
    /* Estilos exclusivos de novedades.php */

    /* Borde lateral de color según vigencia */
    .novedad-vigente  { border-left: 4px solid var(--bs-primary) !important; }
    .novedad-proxima  { border-left: 4px solid var(--bs-warning) !important; }
    .novedad-expirada { border-left: 4px solid var(--bs-secondary) !important; }

    /* Fechas de publicación/expiración */
    .novedad-fechas {
      display: flex;
      gap: 1.5rem;
      flex-wrap: wrap;
      font-size: 0.8rem;
      color: var(--bs-secondary-color);
      margin-top: 0.75rem;
      padding-top: 0.75rem;
      border-top: 1px solid var(--bs-border-color);
    }
  </style>
</head>

<body class="bg-light">

  <?php include '../Header/headerIniciado.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="novedades-titulo">
      <div class="container">

        <!-- Encabezado -->
        <div class="mb-4">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-megaphone me-1" aria-hidden="true"></i>Sistema
          </p>
          <h1 id="novedades-titulo" class="h3 fw-bold mb-0">Novedades</h1>
          <p class="text-secondary">
            Anuncios y comunicados del sistema publicados por el Administrador.
          </p>
        </div>

        <!-- Leyenda de estados -->
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


        <!--
          TODO: Esta lista se generará dinámicamente con PHP
          haciendo un SELECT a la tabla NOVEDADES
          ORDER BY fechaPublicacionNovedad DESC.
          El modelo tiene: codNovedad, textoNovedad,
          fechaPublicacionNovedad, fechaExpiracionNovedad.
        -->
        <ul class="list-unstyled" role="list" aria-label="Lista de novedades del sistema">

          <!-- Novedad 1 — Vigente -->
          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm novedad-vigente"
                     aria-label="Novedad 0001: Mantenimiento programado — vigente">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                      Cód: 0001
                    </span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                      <i class="bi bi-circle-fill me-1" style="font-size:.5rem;" aria-hidden="true"></i>
                      Vigente
                    </span>
                  </div>
                </div>

                <h2 class="h6 fw-bold mb-2">
                  <i class="bi bi-tools text-primary me-2" aria-hidden="true"></i>
                  Mantenimiento programado — Aeropuerto Internacional de Rosario
                </h2>

                <p class="text-secondary mb-0" style="font-size:.92rem;">
                  Debido a tareas de mantenimiento programadas, el Aeropuerto
                  Internacional Islas Malvinas de Rosario permanecerá con operaciones
                  reducidas el próximo domingo 17 de mayo de 2026 entre las 02:00 y
                  las 06:00 hs. Los vuelos afectados serán reprogramados. Si tu vuelo
                  coincide con ese horario, te contactaremos a tu correo electrónico
                  registrado con más información.
                </p>

                <div class="novedad-fechas">
                  <span>
                    <i class="bi bi-calendar-plus me-1" aria-hidden="true"></i>
                    Publicada:
                    <time datetime="2026-05-01">1 de mayo de 2026</time>
                  </span>
                  <span>
                    <i class="bi bi-calendar-x me-1" aria-hidden="true"></i>
                    Expira:
                    <time datetime="2026-05-18">18 de mayo de 2026</time>
                  </span>
                </div>

              </div>
            </article>
          </li>


          <!-- Novedad 2 — Vigente -->
          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm novedad-vigente"
                     aria-label="Novedad 0002: Nueva aerolínea incorporada — vigente">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                      Cód: 0002
                    </span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                      <i class="bi bi-circle-fill me-1" style="font-size:.5rem;" aria-hidden="true"></i>
                      Vigente
                    </span>
                  </div>
                </div>

                <h2 class="h6 fw-bold mb-2">
                  <i class="bi bi-building-fill-add text-primary me-2" aria-hidden="true"></i>
                  Nueva aerolínea incorporada al sistema
                </h2>

                <p class="text-secondary mb-0" style="font-size:.92rem;">
                  Nos complace anunciar que Aerolíneas del Sur se incorpora a
                  SkyReserva a partir del 10 de mayo de 2026. Ya podés buscar
                  y reservar sus vuelos desde la sección
                  <a href="buscar_vuelos.php">Buscar Vuelos</a>.
                  En una primera etapa operarán rutas domésticas en la Patagonia.
                </p>

                <div class="novedad-fechas">
                  <span>
                    <i class="bi bi-calendar-plus me-1" aria-hidden="true"></i>
                    Publicada:
                    <time datetime="2026-05-08">8 de mayo de 2026</time>
                  </span>
                  <span>
                    <i class="bi bi-calendar-x me-1" aria-hidden="true"></i>
                    Expira:
                    <time datetime="2026-06-08">8 de junio de 2026</time>
                  </span>
                </div>

              </div>
            </article>
          </li>


          <!-- Novedad 3 — Próxima (aún no inició) -->
          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm novedad-proxima"
                     aria-label="Novedad 0003: Actualización del sistema — próxima">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                      Cód: 0003
                    </span>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                      <i class="bi bi-circle-fill me-1" style="font-size:.5rem;" aria-hidden="true"></i>
                      Próxima
                    </span>
                  </div>
                </div>

                <h2 class="h6 fw-bold mb-2">
                  <i class="bi bi-arrow-repeat text-warning me-2" aria-hidden="true"></i>
                  Actualización programada del sistema — 20 de mayo
                </h2>

                <p class="text-secondary mb-0" style="font-size:.92rem;">
                  El día 20 de mayo de 2026 entre las 00:00 y las 04:00 hs se
                  realizará una actualización de mantenimiento del sistema.
                  Durante ese período SkyReserva no estará disponible.
                  Te recomendamos realizar tus reservas antes de ese horario.
                </p>

                <div class="novedad-fechas">
                  <span>
                    <i class="bi bi-calendar-plus me-1" aria-hidden="true"></i>
                    Publicada:
                    <time datetime="2026-05-10">10 de mayo de 2026</time>
                  </span>
                  <span>
                    <i class="bi bi-calendar-x me-1" aria-hidden="true"></i>
                    Expira:
                    <time datetime="2026-05-20">20 de mayo de 2026</time>
                  </span>
                </div>

              </div>
            </article>
          </li>


          <!-- Novedad 4 — Expirada -->
          <li class="mb-4" role="listitem">
            <article class="card border-0 shadow-sm novedad-expirada"
                     aria-label="Novedad 0004: Semana de promociones — expirada">
              <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                      Cód: 0004
                    </span>
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                      <i class="bi bi-circle-fill me-1" style="font-size:.5rem;" aria-hidden="true"></i>
                      Expirada
                    </span>
                  </div>
                </div>

                <h2 class="h6 fw-bold mb-2 text-secondary">
                  <i class="bi bi-tag text-secondary me-2" aria-hidden="true"></i>
                  Semana de promociones especiales — Abril 2026
                </h2>

                <p class="text-secondary mb-0" style="font-size:.92rem;">
                  Durante la semana del 21 al 28 de abril de 2026 las aerolíneas
                  asociadas a SkyReserva aplicaron descuentos especiales de hasta
                  el 40% en rutas seleccionadas. Esta promoción ya ha finalizado.
                </p>

                <div class="novedad-fechas">
                  <span>
                    <i class="bi bi-calendar-plus me-1" aria-hidden="true"></i>
                    Publicada:
                    <time datetime="2026-04-20">20 de abril de 2026</time>
                  </span>
                  <span>
                    <i class="bi bi-calendar-x me-1" aria-hidden="true"></i>
                    Expiró:
                    <time datetime="2026-04-28">28 de abril de 2026</time>
                  </span>
                </div>

              </div>
            </article>
          </li>

        </ul><!-- /novedades -->

      </div>
    </section>
  </main>

  <?php include '../Footer/footerIniciado.php'; ?>

</body>
</html>
