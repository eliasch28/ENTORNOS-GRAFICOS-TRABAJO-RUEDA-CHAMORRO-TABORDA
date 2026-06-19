<?php
include '../../config/conexion.php';
session_start();
if (!isset($_SESSION['codUsuario'])) {
    header('Location: ../FlujoSesion/login.php');
    exit;
}
$tipo = $_SESSION['tipoUsuario'];
$nombre = htmlspecialchars($_SESSION['nombreUsuario']);
$resNovedadesVigentes = mysqli_query($link, "SELECT COUNT(*) AS total FROM NOVEDADES
                                             WHERE fechaPublicacionNovedad <= CURDATE()
                                               AND fechaExpiracionNovedad >= CURDATE()");
$novedadesVigentes = (int) (mysqli_fetch_assoc($resNovedadesVigentes)['total'] ?? 0);
$reservasPendientes  = 0;
$reservasConfirmadas = 0;
$comprasTotales      = 0;
$primerPendiente     = null;
$usuariosRegistrados   = 0;
$aerolineasActivas     = 0;
$promocionesPendientes = 0;
$vuelosActivos         = 0;
$promosAprobadas       = 0;
$promosPendientesCeo   = 0;
$nombreAerolineaCeo    = '';
if ($tipo === 'administrador') {
    $r = mysqli_query($link, "SELECT COUNT(*) AS total FROM USUARIOS");
    $usuariosRegistrados = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
    $r = mysqli_query($link, "SELECT COUNT(*) AS total FROM AEROLINEAS");
    $aerolineasActivas = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
    $r = mysqli_query($link, "SELECT COUNT(*) AS total FROM PROMOCIONES WHERE estadoPromocion = 'pendiente'");
    $promocionesPendientes = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
}
if ($tipo === 'ceo de aerolinea') {
    $codUsuarioCeo = (int)$_SESSION['codUsuario'];
    $resAeroCeo = mysqli_query($link,
        "SELECT u.codAerolinea, a.nombreAerolinea FROM USUARIOS u
         JOIN AEROLINEAS a ON u.codAerolinea = a.codAerolinea
         WHERE u.codUsuario = $codUsuarioCeo");
    $aeroCeo = $resAeroCeo ? mysqli_fetch_assoc($resAeroCeo) : null;
    if ($aeroCeo) {
        $codAerolineaCeo    = (int)$aeroCeo['codAerolinea'];
        $nombreAerolineaCeo = htmlspecialchars($aeroCeo['nombreAerolinea'], ENT_QUOTES, 'UTF-8');
        $r = mysqli_query($link,
            "SELECT COUNT(*) AS total FROM VUELOS
             WHERE codAerolinea = $codAerolineaCeo AND fechaSalidaVuelo >= CURDATE()");
        $vuelosActivos = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
        $r = mysqli_query($link,
            "SELECT COUNT(*) AS total FROM PROMOCIONES
             WHERE codAerolinea = $codAerolineaCeo AND estadoPromocion = 'aprobada'");
        $promosAprobadas = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
        $r = mysqli_query($link,
            "SELECT COUNT(*) AS total FROM PROMOCIONES
             WHERE codAerolinea = $codAerolineaCeo AND estadoPromocion = 'pendiente'");
        $promosPendientesCeo = (int)(mysqli_fetch_assoc($r)['total'] ?? 0);
    }
}
if ($tipo === 'usuario') {
    $codUsuario = (int)$_SESSION['codUsuario'];
    $resStats = mysqli_query($link,
        "SELECT SUM(estadoReserva = 'pendiente de pago') AS pendientes,
                SUM(estadoReserva = 'confirmada')        AS confirmadas
         FROM RESERVAS WHERE codUsuario = $codUsuario");
    $stats = mysqli_fetch_assoc($resStats);
    $reservasPendientes  = (int)($stats['pendientes']  ?? 0);
    $reservasConfirmadas = (int)($stats['confirmadas'] ?? 0);
    $comprasTotales      = $reservasConfirmadas;
    $resPend = mysqli_query($link,
        "SELECT r.codReserva, v.origenVuelo, v.destinoVuelo, v.fechaSalidaVuelo
         FROM RESERVAS r
         JOIN VUELOS v ON r.codVuelo = v.codVuelo
         WHERE r.codUsuario = $codUsuario AND r.estadoReserva = 'pendiente de pago'
         ORDER BY r.fechaReserva ASC LIMIT 1");
    $primerPendiente = ($resPend && mysqli_num_rows($resPend) > 0)
                       ? mysqli_fetch_assoc($resPend) : null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Bienvenido a VuelaLibre. Buscá vuelos, gestioná tus reservas y consultá novedades." />
  <title>Inicio | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1">

<?php if ($tipo === 'administrador'): ?>

  <section class="portada-registrado text-white bg-dark" aria-labelledby="hero-titulo">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <p class="text-white-50 text-uppercase small fw-semibold mb-2">
            <i class="bi bi-shield-fill-check me-1" aria-hidden="true"></i>Panel de Administración
          </p>
          <h1 id="hero-titulo" class="display-5 fw-bold mb-2">¡Bienvenido, <?= $nombre ?>!</h1>
          <p class="lead text-white-50 mb-4">Gestioná aerolíneas, novedades, aprobá promociones y consultá reportes del sistema.</p>
          <div class="d-flex gap-3 flex-wrap">
            <a href="../FuncionesAdmin/aerolineas-index.php" class="btn btn-light btn-lg fw-semibold">
              <i class="bi bi-building me-2" aria-hidden="true"></i>Aerolíneas
            </a>
            <a href="../FuncionesAdmin/global-reports.php" class="btn btn-outline-light btn-lg">
              <i class="bi bi-bar-chart-line me-2" aria-hidden="true"></i>Reportes
            </a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card border-0 shadow">
            <div class="card-body p-4">
              <h2 class="h6 fw-bold text-dark mb-3"><i class="bi bi-speedometer2 me-2" aria-hidden="true"></i>Resumen del sistema</h2>
              <div class="fila-detalle"><span class="etiqueta-detalle">Usuarios registrados</span><span class="fw-bold text-primary"><?= $usuariosRegistrados ?></span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Aerolíneas activas</span><span class="fw-bold text-success"><?= $aerolineasActivas ?></span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Promociones pendientes</span><span class="fw-bold text-warning"><?= $promocionesPendientes ?></span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Novedades vigentes</span><span class="fw-bold text-info"><?= $novedadesVigentes ?></span></div>
              <a href="../FuncionesAdmin/auditoria-promociones.php" class="btn btn-outline-dark btn-sm w-100 mt-3">
                <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>Auditoría de Promociones
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5" aria-labelledby="accesos-admin">
    <div class="container">
      <h2 id="accesos-admin" class="h5 fw-bold mb-1">Accesos rápidos</h2>
      <p class="text-secondary small mb-4">Todas las funciones administrativas desde un solo lugar.</p>
      <ul class="row g-3 list-unstyled">
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesAdmin/aerolineas-index.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-building" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Aerolíneas</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesAdmin/novedades-index.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-megaphone" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Novedades</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesAdmin/auditoria-promociones.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-check2-circle" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Auditoría</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesAdmin/global-reports.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Reportes</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesUsuario/mi_perfil.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-person-circle" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Mi Perfil</div>
          </a>
        </li>
      </ul>
    </div>
  </section>

<?php elseif ($tipo === 'ceo de aerolinea'): ?>

  <section class="portada-registrado text-white bg-secondary" aria-labelledby="hero-titulo">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <p class="text-white-50 text-uppercase small fw-semibold mb-2">
            <i class="bi bi-briefcase-fill me-1" aria-hidden="true"></i>Panel CEO de Aerolínea
          </p>
          <h1 id="hero-titulo" class="display-5 fw-bold mb-2">¡Bienvenido, <?= $nombre ?>!</h1>
          <p class="lead text-white-50 mb-4">Administrá los vuelos de tu aerolínea y gestioná las promociones que enviás al Administrador.</p>
          <div class="d-flex gap-3 flex-wrap">
            <a href="../FuncionesCEO/vuelos-index.php" class="btn btn-light btn-lg fw-semibold">
              <i class="bi bi-airplane me-2" aria-hidden="true"></i>Mis Vuelos
            </a>
            <a href="../FuncionesCEO/promociones-index.php" class="btn btn-outline-light btn-lg">
              <i class="bi bi-tag me-2" aria-hidden="true"></i>Mis Promociones
            </a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card border-0 shadow">
            <div class="card-body p-4">
              <h2 class="h6 fw-bold text-dark mb-3"><i class="bi bi-speedometer2 me-2" aria-hidden="true"></i>Resumen de mi aerolínea</h2>
              <?php if ($nombreAerolineaCeo !== ''): ?>
              <div class="fila-detalle"><span class="etiqueta-detalle">Aerolínea</span><span class="fw-bold text-secondary small"><?= $nombreAerolineaCeo ?></span></div>
              <?php endif; ?>
              <div class="fila-detalle"><span class="etiqueta-detalle">Vuelos futuros</span><span class="fw-bold text-success"><?= $vuelosActivos ?></span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Promociones aprobadas</span><span class="fw-bold text-primary"><?= $promosAprobadas ?></span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Promociones pendientes</span><span class="fw-bold text-warning"><?= $promosPendientesCeo ?></span></div>
              <a href="../FuncionesCEO/reportes-ceo.php" class="btn btn-outline-secondary btn-sm w-100 mt-3">
                <i class="bi bi-bar-chart-line me-1" aria-hidden="true"></i>Ver Reportes del Sistema
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5" aria-labelledby="accesos-ceo">
    <div class="container">
      <h2 id="accesos-ceo" class="h5 fw-bold mb-1">Accesos rápidos</h2>
      <p class="text-secondary small mb-4">Todo lo que necesitás para gestionar tu aerolínea.</p>
      <ul class="row g-3 list-unstyled">
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesCEO/vuelos-index.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-airplane" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Vuelos</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesCEO/vuelos-create.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-plus-circle" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Nuevo Vuelo</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesCEO/promociones-index.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-tag" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Promociones</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesCEO/reportes-ceo.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Reportes</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesUsuario/mi_perfil.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-person-circle" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Mi Perfil</div>
          </a>
        </li>
      </ul>
    </div>
  </section>

<?php else: ?>

  <section class="portada-registrado text-white bg-primary" aria-labelledby="hero-titulo">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <p class="text-white-50 text-uppercase small fw-semibold mb-2">
            <i class="bi bi-person-check-fill me-1" aria-hidden="true"></i>Sesión iniciada
          </p>
          <h1 id="hero-titulo" class="display-5 fw-bold mb-2">¡Bienvenido, <?= $nombre ?>!</h1>
          <p class="lead text-white-50 mb-4">¿A dónde volamos hoy? Buscá tu próximo vuelo o gestioná tus reservas.</p>
          <div class="d-flex gap-3 flex-wrap">
            <a href="../FuncionesUsuario/buscar_vuelos.php" class="btn btn-light btn-lg text-primary fw-semibold">
              <i class="bi bi-search me-2" aria-hidden="true"></i>Buscar Vuelos
            </a>
            <a href="../FuncionesUsuario/mis_reservas.php" class="btn btn-outline-light btn-lg">
              <i class="bi bi-calendar2-check me-2" aria-hidden="true"></i>Mis Reservas
            </a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card border-0 shadow">
            <div class="card-body p-4">
              <h2 class="h6 fw-bold text-primary mb-3"><i class="bi bi-speedometer2 me-2" aria-hidden="true"></i>Mi resumen</h2>
              <div class="fila-detalle"><span class="etiqueta-detalle">Reservas pendientes</span><span class="fw-bold text-warning"><?= $reservasPendientes ?></span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Reservas confirmadas</span><span class="fw-bold text-success"><?= $reservasConfirmadas ?></span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Compras totales</span><span class="fw-bold"><?= $comprasTotales ?></span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Novedades vigentes</span><span class="fw-bold text-primary"><?= $novedadesVigentes ?></span></div>
              <a href="../FuncionesUsuario/mi_perfil.php" class="btn btn-outline-primary btn-sm w-100 mt-3">
                <i class="bi bi-person-circle me-1" aria-hidden="true"></i>Ver mi perfil completo
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5" aria-labelledby="accesos-titulo">
    <div class="container">
      <h2 id="accesos-titulo" class="h5 fw-bold mb-1">Accesos rápidos</h2>
      <p class="text-secondary small mb-4">Todo lo que necesitás desde un solo lugar.</p>
      <ul class="row g-3 list-unstyled">
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesUsuario/buscar_vuelos.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-search" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Buscar Vuelos</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesUsuario/mis_reservas.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-calendar2-check" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Mis Reservas</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesUsuario/historial_compras.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-clock-history" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Historial</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesUsuario/novedades.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-megaphone" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Novedades</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesUsuario/promociones.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-tag-fill" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Promociones</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../FuncionesUsuario/mi_perfil.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-person-circle" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Mi Perfil</div>
          </a>
        </li>
      </ul>
    </div>
  </section>

  <?php if ($primerPendiente):
      $codFmt     = str_pad((string)$primerPendiente['codReserva'], 4, '0', STR_PAD_LEFT);
      $origenCod  = mb_strtoupper(mb_substr($primerPendiente['origenVuelo'],  0, 3));
      $destinoCod = mb_strtoupper(mb_substr($primerPendiente['destinoVuelo'], 0, 3));
      $fechaFmt   = date('j M Y', strtotime($primerPendiente['fechaSalidaVuelo']));
      $extra      = $reservasPendientes > 1 ? " (y otras " . ($reservasPendientes - 1) . " más)" : '';
  ?>
  <section class="pb-4" aria-labelledby="pendiente-titulo">
    <div class="container">
      <div class="alert alert-warning d-flex align-items-start gap-3 shadow-sm" role="note">
        <i class="bi bi-hourglass-split fs-4 flex-shrink-0 mt-1" aria-hidden="true"></i>
        <div>
          <h2 id="pendiente-titulo" class="alert-heading h6 fw-bold mb-1">Tenés una reserva pendiente de pago</h2>
          <p class="mb-2 small">
            La <strong>Reserva N° <?= $codFmt ?></strong>
            (<?= htmlspecialchars($origenCod, ENT_QUOTES, 'UTF-8') ?> → <?= htmlspecialchars($destinoCod, ENT_QUOTES, 'UTF-8') ?>,
            <?= $fechaFmt ?>) está esperando confirmación de pago<?= $extra ?>.
          </p>
          <a href="../Funciones Usuario/mis_reservas.php" class="btn btn-warning btn-sm">
            <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Ir a Mis Reservas para pagar
          </a>
        </div>
      </div>
    </div>
  </section>
  <?php endif; ?>

<?php endif; ?>

</main>

<?php include '../Footer/footer.php'; ?>

</body>
</html>