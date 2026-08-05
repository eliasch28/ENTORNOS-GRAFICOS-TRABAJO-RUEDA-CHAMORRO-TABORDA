<?php
session_start();
require_once '../../config/EnviarCorreo.php';
$contactoExito = '';
$contactoError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mensajeContacto'])) {
    $nombreContacto  = trim($_POST['nombreContacto'] ?? '');
    $emailContacto   = trim($_POST['emailContacto'] ?? '');
    $mensajeContacto = trim($_POST['mensajeContacto'] ?? '');
    if ($nombreContacto === '' || $emailContacto === '' || $mensajeContacto === '') {
        $contactoError = 'Completá todos los campos para enviar tu consulta.';
    } elseif (!filter_var($emailContacto, FILTER_VALIDATE_EMAIL)) {
        $contactoError = 'Ingresá un correo electrónico válido.';
    } else {
        $nombreSafe  = htmlspecialchars($nombreContacto, ENT_QUOTES, 'UTF-8');
        $emailSafe   = htmlspecialchars($emailContacto, ENT_QUOTES, 'UTF-8');
        $mensajeSafe = nl2br(htmlspecialchars($mensajeContacto, ENT_QUOTES, 'UTF-8'));
        $cuerpo = "<p><strong>Nueva consulta desde el formulario de contacto de VuelaLibre.</strong></p>"
            . "<p><strong>Nombre:</strong> $nombreSafe</p>"
            . "<p><strong>Correo:</strong> $emailSafe</p>"
            . "<p><strong>Consulta:</strong></p>"
            . "<p>$mensajeSafe</p>";
        $enviado = enviarCorreo(
            MAIL_SMTP_USER,
            'Nueva consulta de contacto - VuelaLibre',
            $cuerpo,
            false,
            ['email' => $emailContacto, 'nombre' => $nombreContacto]
        );
        if ($enviado) {
            $contactoExito = '¡Tu consulta fue enviada! Te responderemos a la brevedad.';
        } else {
            $contactoError = 'No pudimos enviar tu consulta en este momento. Intentá más tarde.';
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
        content="Encontrá respuestas a las preguntas frecuentes de VuelaLibre o contactanos directamente." />
  <title>Ayuda | VuelaLibre – UTN FRR</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>

<?php

$categoriaActual = $_GET['categoria'] ?? 'todas';
$preguntas = [
  [
    'id'        => 'faq-1',
    'categoria' => 'registro',
    'pregunta'  => '¿Cómo me registro en VuelaLibre?',
    'respuesta' => 'Para registrarte, hacé clic en el botón <strong>"Registrarse"</strong>
                    del menú superior. Completá el formulario con tu nombre de usuario,
                    contraseña (máximo 8 caracteres), tipo de usuario, correo electrónico
                    y teléfono. Si te registrás como <strong>Cliente</strong>, recibirás
                    un correo de validación para activar tu cuenta. Si te registrás como
                    <strong>CEO de Aerolínea</strong>, tu solicitud deberá ser aprobada
                    por el Administrador.',
  ],
  [
    'id'        => 'faq-2',
    'categoria' => 'reservas',
    'pregunta'  => '¿Cómo busco y reservo un vuelo?',
    'respuesta' => 'Una vez con sesión iniciada, accedé a <strong>"Buscar Vuelos"</strong>
                    desde el menú. Filtrá por origen, destino, fecha y aerolínea. Al
                    encontrar el vuelo que querés, hacé clic en <strong>"Reservar"</strong>.
                    Revisá los detalles, aceptá los términos y confirmá la reserva. Tu
                    reserva quedará en estado <em>pendiente de pago</em> hasta que
                    confirmes el abono.',
  ],
  [
    'id'        => 'faq-3',
    'categoria' => 'reservas',
    'pregunta'  => '¿Puedo cancelar una reserva?',
    'respuesta' => 'Sí. Podés cancelar una reserva hasta <strong>72 horas antes</strong>
                    de la salida del vuelo, tanto si está en estado
                    <em>pendiente de pago</em> como <em>confirmada</em>. Para cancelar,
                    accedé a <strong>"Mis Reservas"</strong> y hacé clic en
                    "Cancelar reserva". Una vez cancelada no puede reactivarse.',
  ],
  [
    'id'        => 'faq-4',
    'categoria' => 'vuelos',
    'pregunta'  => '¿Cómo funcionan las promociones?',
    'respuesta' => 'Las promociones son descuentos creados por los CEOs de cada aerolínea
                    y aprobados por el Administrador. Solo puede haber una promoción
                    vigente por aerolínea a la vez. El descuento se aplica
                    <strong>automáticamente</strong> al seleccionar un vuelo de la
                    aerolínea que tiene promoción activa. Podés ver todas las promociones
                    vigentes en la sección <strong>"Promociones"</strong>.',
  ],
  [
    'id'        => 'faq-5',
    'categoria' => 'registro',
    'pregunta'  => 'Olvidé mi contraseña, ¿qué hago?',
    'respuesta' => 'En la pantalla de inicio de sesión, hacé clic en
                    <strong>"¿Olvidaste tu contraseña?"</strong>. Ingresá el correo
                    electrónico con el que te registraste y recibirás un enlace para
                    restablecer tu contraseña. El enlace expira en <strong>1 hora</strong>.
                    Revisá también la carpeta de spam si no lo encontrás en tu bandeja principal.',
  ],
  [
    'id'        => 'faq-6',
    'categoria' => 'pagos',
    'pregunta'  => '¿Cómo se confirma el pago de una reserva?',
    'respuesta' => 'Una vez que realizaste la reserva, esta queda en estado
                    <em>pendiente de pago</em>. Para confirmarla, ingresá a
                    <strong>"Mis Reservas"</strong> y hacé clic en
                    <strong>"Confirmar / Pagar"</strong>. La reserva pasará
                    al estado <em>confirmada</em> y quedará registrada en tu historial
                    de compras.',
  ],
  [
    'id'        => 'faq-7',
    'categoria' => 'pagos',
    'pregunta'  => '¿Dónde veo mis compras confirmadas?',
    'respuesta' => 'Podés ver todas tus compras confirmadas en la sección
                    <strong>"Historial de Compras"</strong>, disponible desde el menú
                    principal. Allí encontrarás el detalle de cada vuelo, el precio
                    abonado, y el ahorro obtenido si aplicaste alguna promoción.',
  ],
  [
    'id'        => 'faq-8',
    'categoria' => 'vuelos',
    'pregunta'  => '¿Qué significa que un vuelo tenga "pocos asientos"?',
    'respuesta' => 'El indicador de asientos disponibles cambia de color según la
                    disponibilidad: <strong style="color:green">verde</strong> indica
                    más de 20 asientos libres, <strong style="color:orange">naranja</strong>
                    entre 6 y 20, y <strong style="color:red">rojo</strong> 5 o menos.
                    Te recomendamos reservar rápido cuando el indicador esté en rojo.',
  ],
  [
    'id'        => 'faq-9',
    'categoria' => 'registro',
    'pregunta'  => '¿Cómo me registro como CEO de aerolínea?',
    'respuesta' => 'Durante el registro seleccioná el tipo de usuario
                    <strong>"CEO de Aerolínea"</strong>. Tu solicitud quedará pendiente
                    de aprobación por parte del Administrador del sistema. Una vez
                    aprobada recibirás acceso al panel de gestión de vuelos y
                    promociones de tu aerolínea.',
  ],
  [
    'id'        => 'faq-10',
    'categoria' => 'reservas',
    'pregunta'  => '¿Puedo tener varias reservas al mismo tiempo?',
    'respuesta' => 'Sí. Podés tener múltiples reservas activas simultáneamente, ya sea
                    en estado <em>pendiente de pago</em> o <em>confirmada</em>. Cada
                    reserva se gestiona de forma independiente desde la sección
                    <strong>"Mis Reservas"</strong>, donde podés confirmar o cancelar
                    cada una por separado.',
  ],
];
$preguntasFiltradas = ($categoriaActual === 'todas')
  ? $preguntas
  : array_values(array_filter($preguntas, fn($p) => $p['categoria'] === $categoriaActual));
$porPaginaFaq   = 3;
$totalFaq       = count($preguntasFiltradas);
$totalPagFaq    = max(1, (int)ceil($totalFaq / $porPaginaFaq));
$paginaFaq      = max(1, min($totalPagFaq, (int)($_GET['pagina'] ?? 1)));
$pregsPagina    = array_slice($preguntasFiltradas, ($paginaFaq - 1) * $porPaginaFaq, $porPaginaFaq);
$getAyuda = [];
if ($categoriaActual !== 'todas') $getAyuda['categoria'] = $categoriaActual;
$queryAyuda  = http_build_query($getAyuda);
$urlBaseAyuda = 'ayuda.php' . ($queryAyuda ? '?' . $queryAyuda . '&' : '?');
$categorias = [
  'todas'    => 'Todas',
  'registro' => 'Registro y cuenta',
  'reservas' => 'Reservas',
  'pagos'    => 'Pagos',
  'vuelos'   => 'Vuelos',
];
?>

<body class="bg-light">

  <?php include '../Header/header.php'; ?>

  <main id="contenido-principal" tabindex="-1">
    <section class="py-5" aria-labelledby="ayuda-titulo">
      <div class="container">

        <div class="mb-5">
          <p class="text-primary text-uppercase small fw-semibold mb-1">
            <i class="bi bi-question-circle me-1" aria-hidden="true"></i>Soporte
          </p>
          <h1 id="ayuda-titulo" class="h3 fw-bold mb-0">Ayuda</h1>
          <p class="text-secondary">
            Encontrá respuestas a las preguntas más frecuentes o envianos tu consulta.
          </p>
        </div>

        <div class="row g-4">
          <div class="col-lg-8">

            <div class="mb-4 d-flex gap-2 flex-wrap"
                 role="group" aria-label="Filtrar preguntas por categoría">
              <?php foreach ($categorias as $clave => $etiqueta): ?>
                <a href="ayuda.php<?= $clave !== 'todas' ? '?categoria=' . urlencode($clave) : '' ?>"
                   class="btn btn-outline-primary btn-sm boton-categoria <?= $categoriaActual === $clave ? 'active' : '' ?>"
                   <?= $categoriaActual === $clave ? 'aria-current="true"' : '' ?>>
                  <?= htmlspecialchars($etiqueta) ?>
                </a>
              <?php endforeach; ?>
            </div>

            <?php if (empty($preguntasFiltradas)): ?>
              <div class="alert alert-info" role="note">
                <i class="bi bi-info-circle me-2" aria-hidden="true"></i>
                No hay preguntas disponibles para la categoría
                <strong><?= htmlspecialchars($categorias[$categoriaActual] ?? $categoriaActual) ?></strong>.
              </div>
            <?php else: ?>
              <div class="accordion shadow-sm mb-4" id="faq-accordion">
                <?php foreach ($pregsPagina as $pregunta): ?>
                  <div class="accordion-item border-0 mb-2">
                    <h2 class="accordion-header" id="<?= $pregunta['id'] ?>-header">
                      <button class="accordion-button collapsed fw-semibold"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#<?= $pregunta['id'] ?>"
                              aria-expanded="false"
                              aria-controls="<?= $pregunta['id'] ?>">
                        <?= htmlspecialchars($pregunta['pregunta']) ?>
                      </button>
                    </h2>
                    <div id="<?= $pregunta['id'] ?>"
                         class="accordion-collapse collapse"
                         aria-labelledby="<?= $pregunta['id'] ?>-header"
                         data-bs-parent="#faq-accordion">
                      <div class="accordion-body text-secondary small">
                        <?= $pregunta['respuesta'] ?>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <?php if ($totalPagFaq > 1): ?>
            <nav aria-label="Paginación de preguntas frecuentes" class="mb-4">
              <ul class="pagination justify-content-center">
                <li class="page-item <?= $paginaFaq <= 1 ? 'disabled' : '' ?>">
                  <a class="page-link" href="<?= $urlBaseAyuda ?>pagina=<?= $paginaFaq - 1 ?>">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $totalPagFaq; $i++): ?>
                  <li class="page-item <?= $i === $paginaFaq ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $urlBaseAyuda ?>pagina=<?= $i ?>"
                       <?= $i === $paginaFaq ? 'aria-current="page"' : '' ?>><?= $i ?></a>
                  </li>
                <?php endfor; ?>
                <li class="page-item <?= $paginaFaq >= $totalPagFaq ? 'disabled' : '' ?>">
                  <a class="page-link" href="<?= $urlBaseAyuda ?>pagina=<?= $paginaFaq + 1 ?>">Siguiente</a>
                </li>
              </ul>
            </nav>
            <?php endif; ?>

            <div class="text-center mb-3">
              <button class="btn btn-primary btn-lg"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#formularioContactoAyuda"
                      aria-expanded="false"
                      aria-controls="formularioContactoAyuda">
                <i class="bi bi-envelope me-2" aria-hidden="true"></i>Contáctanos
              </button>
            </div>

            <div class="collapse <?= ($contactoExito || $contactoError) ? 'show' : '' ?>" id="formularioContactoAyuda">
              <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                  <h2 class="h6 fw-bold text-primary mb-3">
                    <i class="bi bi-envelope me-2" aria-hidden="true"></i>
                    Envianos tu consulta
                  </h2>

                  <?php if ($contactoExito): ?>
                    <div class="alert alert-success" role="status">
                      <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
                      <?= htmlspecialchars($contactoExito, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                  <?php elseif ($contactoError): ?>
                    <div class="alert alert-danger" role="alert">
                      <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>
                      <?= htmlspecialchars($contactoError, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                  <?php endif; ?>

                  <form action="ayuda.php" method="post"
                        aria-label="Formulario de consulta">
                    <div class="row g-3">

                      <div class="col-md-6">
                        <label for="ayuda-nombre" class="form-label fw-semibold">
                          Nombre completo
                          <span class="text-danger" aria-hidden="true">*</span>
                        </label>
                        <input type="text" id="ayuda-nombre" name="nombreContacto"
                               class="form-control"
                               placeholder="Tu nombre completo"
                               required maxlength="100"/>
                      </div>

                      <div class="col-md-6">
                        <label for="ayuda-email" class="form-label fw-semibold">
                          Correo electrónico
                          <span class="text-danger" aria-hidden="true">*</span>
                        </label>
                        <input type="email" id="ayuda-email" name="emailContacto"
                               class="form-control"
                               placeholder="tu@correo.com"
                               required maxlength="100"/>
                      </div>

                      <div class="col-12">
                        <label for="ayuda-mensaje" class="form-label fw-semibold">
                          Consulta
                          <span class="text-danger" aria-hidden="true">*</span>
                        </label>
                        <textarea id="ayuda-mensaje" name="mensajeContacto"
                                  class="form-control" rows="4"
                                  placeholder="Describí tu consulta en detalle..."
                                  required maxlength="1000"></textarea>
                      </div>

                      <div class="col-12">
                        <p class="text-secondary small mb-3">
                          <span class="text-danger" aria-hidden="true">*</span>
                          Todos los campos son obligatorios.
                        </p>
                        <button type="submit" class="btn btn-primary">
                          <i class="bi bi-send me-2" aria-hidden="true"></i>Enviar consulta
                        </button>
                        <button type="button"
                                class="btn btn-outline-secondary ms-2"
                                data-bs-toggle="collapse"
                                data-bs-target="#formularioContactoAyuda">
                          Cancelar
                        </button>
                      </div>

                    </div>
                  </form>

                </div>
              </div>
            </div>

          </div>

          <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4">
              <h2 class="h6 fw-bold text-primary mb-3">
                <i class="bi bi-lightning-charge me-2" aria-hidden="true"></i>
                Accesos directos
              </h2>
              <ul class="list-unstyled mb-0">
                <li class="mb-2">
                  <a href="../FlujoSesion/recuperar_contrasena_.php"
                     class="text-decoration-none text-secondary small">
                    <i class="bi bi-key me-2 text-primary" aria-hidden="true"></i>
                    Recuperar contraseña
                  </a>
                </li>
                <li>
                  <a href="sobre_nosotros.php"
                     class="text-decoration-none text-secondary small">
                    <i class="bi bi-info-circle me-2 text-primary" aria-hidden="true"></i>
                    Sobre Nosotros
                  </a>
                </li>
              </ul>
            </div>

            <div class="card border-0 shadow-sm p-4">
              <h2 class="h6 fw-bold text-primary mb-2">
                <i class="bi bi-envelope me-2" aria-hidden="true"></i>
                ¿No encontraste tu respuesta?
              </h2>
              <p class="text-secondary small mb-3">
                Usá el formulario de contacto y te respondemos a la brevedad.
              </p>
              <button class="btn btn-outline-primary btn-sm w-100"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#formularioContactoAyuda"
                      aria-controls="formularioContactoAyuda">
                <i class="bi bi-envelope me-1" aria-hidden="true"></i>
                Enviar consulta
              </button>
            </div>
          </div>

        </div>

      </div>
    </section>
  </main>

  <?php include '../Footer/footer.php'; ?>

<script>
  if (window.location.hash === '#formularioContactoAyuda') {
    var el = document.getElementById('formularioContactoAyuda');
    if (el) {
      new bootstrap.Collapse(el, { show: true });
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
</script>

</body>
</html>
