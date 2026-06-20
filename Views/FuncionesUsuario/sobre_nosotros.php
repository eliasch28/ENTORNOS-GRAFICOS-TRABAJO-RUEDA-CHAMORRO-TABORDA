<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
        content="Conocé más sobre VuelaLibre, nuestras políticas de privacidad y cómo contactarnos." />
  <title>Sobre Nosotros | VuelaLibre – UTN FRR</title>

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
    <section class="py-5" aria-labelledby="nosotros-titulo">
      <div class="container">

        <div class="mb-5">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-info-circle me-1" aria-hidden="true"></i>La empresa
          </p>
          <h1 id="nosotros-titulo" class="h3 fw-bold mb-0">Sobre Nosotros</h1>
          <p class="text-secondary">
            Conocé más sobre VuelaLibre, cómo trabajamos y nuestros compromisos.
          </p>
        </div>

        <div class="row g-4">
          <div class="col-lg-8">

            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-4">
                <h2 class="h5 fw-bold text-primary mb-3">
                  <i class="bi bi-airplane-fill me-2" aria-hidden="true"></i>
                  ¿Quiénes somos?
                </h2>
                <p class="text-secondary mb-3">
                  <strong>VuelaLibre</strong> es una plataforma de gestión de reservas
                  de pasajes de avión desarrollada en el marco de la cátedra de
                  Entornos Gráficos de la <strong>Universidad Tecnológica Nacional —
                  Facultad Regional Rosario</strong> (UTN FRR), año 2026.
                </p>
                <p class="text-secondary mb-3">
                  Nuestra misión es conectar a los viajeros con las mejores aerolíneas
                  de la región, ofreciendo una experiencia de reserva simple, accesible
                  y confiable. Trabajamos con aerolíneas asociadas para brindar vuelos
                  directos e interconexiones en toda Latinoamérica.
                </p>
                <p class="text-secondary mb-0">
                  El sistema permite a los usuarios buscar vuelos, gestionar reservas,
                  acceder a promociones exclusivas y mantenerse informados sobre las
                  novedades del servicio.
                </p>
              </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-4">
                <h2 class="h5 fw-bold text-primary mb-3">
                  <i class="bi bi-shield-check me-2" aria-hidden="true"></i>
                  Políticas de privacidad
                </h2>

                <h3 class="h6 fw-bold mb-2">Recopilación de datos</h3>
                <p class="text-secondary small mb-3">
                  VuelaLibre recopila únicamente los datos necesarios para operar el
                  servicio: nombre de usuario, correo electrónico y teléfono de contacto.
                  Estos datos son utilizados exclusivamente para gestionar reservas y
                  comunicaciones relacionadas con el sistema.
                </p>

                <h3 class="h6 fw-bold mb-2">Uso de la información</h3>
                <p class="text-secondary small mb-3">
                  La información personal no es compartida con terceros ni utilizada
                  con fines comerciales ajenos al sistema. Las contraseñas se almacenan
                  de forma encriptada y nunca en texto plano.
                </p>

                <h3 class="h6 fw-bold mb-2">Seguridad</h3>
                <p class="text-secondary small mb-0">
                  Implementamos medidas de seguridad estándar para proteger la
                  información de los usuarios, incluyendo encriptación de contraseñas
                  y validación de datos en servidor. En caso de consultas sobre el
                  tratamiento de tus datos, podés contactarnos a través del formulario
                  de contacto.
                </p>
              </div>
            </div>


          </div>

          <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4">
              <h2 class="h6 fw-bold text-primary mb-3">
                <i class="bi bi-geo-alt-fill me-2" aria-hidden="true"></i>
                Información de contacto
              </h2>
              <ul class="list-unstyled text-secondary small">
                <li class="mb-2">
                  <i class="bi bi-building me-2 text-primary" aria-hidden="true"></i>
                  UTN – Facultad Regional Rosario
                </li>
                <li class="mb-2">
                  <i class="bi bi-geo-alt me-2 text-primary" aria-hidden="true"></i>
                  Zeballos 1341, Rosario, Santa Fe
                </li>
                <li class="mb-2">
                  <i class="bi bi-envelope me-2 text-primary" aria-hidden="true"></i>
                  vuelalibredevs@gmail.com
                </li>
                <li>
                  <i class="bi bi-mortarboard me-2 text-primary" aria-hidden="true"></i>
                  Cátedra Entornos Gráficos 2026
                </li>
              </ul>
            </div>
          </div>

        </div>

      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

</body>
</html>
