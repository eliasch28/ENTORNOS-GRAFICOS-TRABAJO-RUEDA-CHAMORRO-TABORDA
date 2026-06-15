<?php
include '../../config/conexion.php';
session_start();
if (!isset($_SESSION['codUsuario'])) {
    header('Location: ../Flujo Sesion/login.php');
    exit;
}
$tipo = $_SESSION['tipoUsuario'];
$nombre = htmlspecialchars($_SESSION['nombreUsuario']);
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

  <!-- ═══════════════════ PANEL ADMINISTRADOR ═══════════════════ -->
  <section class="portada-registrado text-white bg-dark" aria-labelledby="hero-titulo">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <p class="text-white-50 text-uppercase small fw-semibold mb-2">
            <i class="bi bi-shield-fill-check me-1"></i>Panel de Administración
          </p>
          <h1 id="hero-titulo" class="display-5 fw-bold mb-2">¡Bienvenido, <?= $nombre ?>!</h1>
          <p class="lead text-white-50 mb-4">Gestioná aerolíneas, novedades, aprobá promociones y consultá reportes del sistema.</p>
          <div class="d-flex gap-3 flex-wrap">
            <a href="../Funciones Admin/aerolineas-index.php" class="btn btn-light btn-lg fw-semibold">
              <i class="bi bi-building me-2"></i>Aerolíneas
            </a>
            <a href="../Funciones Admin/global-reports.php" class="btn btn-outline-light btn-lg">
              <i class="bi bi-bar-chart-line me-2"></i>Reportes
            </a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card border-0 shadow">
            <div class="card-body p-4">
              <h2 class="h6 fw-bold text-dark mb-3"><i class="bi bi-speedometer2 me-2"></i>Resumen del sistema</h2>
              <div class="fila-detalle"><span class="etiqueta-detalle">Usuarios registrados</span><span class="fw-bold text-primary">128</span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Aerolíneas activas</span><span class="fw-bold text-success">5</span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Promociones pendientes</span><span class="fw-bold text-warning">7</span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Novedades vigentes</span><span class="fw-bold text-info">3</span></div>
              <a href="../Funciones Admin/auditoria-promociones.php" class="btn btn-outline-dark btn-sm w-100 mt-3">
                <i class="bi bi-check2-circle me-1"></i>Auditoría de Promociones
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
          <a href="../Funciones Admin/aerolineas-index.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-building" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Aerolíneas</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones Admin/novedades-index.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-megaphone" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Novedades</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones Admin/auditoria-promociones.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-check2-circle" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Auditoría</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones Admin/global-reports.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Reportes</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones Usuario/mi_perfil.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-person-circle" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Mi Perfil</div>
          </a>
        </li>
      </ul>
    </div>
  </section>

<?php elseif ($tipo === 'ceo de aerolinea'): ?>

  <!-- ═══════════════════ PANEL CEO ═══════════════════ -->
  <section class="portada-registrado text-white bg-secondary" aria-labelledby="hero-titulo">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <p class="text-white-50 text-uppercase small fw-semibold mb-2">
            <i class="bi bi-briefcase-fill me-1"></i>Panel CEO de Aerolínea
          </p>
          <h1 id="hero-titulo" class="display-5 fw-bold mb-2">¡Bienvenido, <?= $nombre ?>!</h1>
          <p class="lead text-white-50 mb-4">Administrá los vuelos de tu aerolínea y gestioná las promociones que enviás al Administrador.</p>
          <div class="d-flex gap-3 flex-wrap">
            <a href="../Funciones CEO/vuelos-index.php" class="btn btn-light btn-lg fw-semibold">
              <i class="bi bi-airplane me-2"></i>Mis Vuelos
            </a>
            <a href="../Funciones CEO/promociones-index.php" class="btn btn-outline-light btn-lg">
              <i class="bi bi-tag me-2"></i>Mis Promociones
            </a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card border-0 shadow">
            <div class="card-body p-4">
              <h2 class="h6 fw-bold text-dark mb-3"><i class="bi bi-speedometer2 me-2"></i>Resumen de mi aerolínea</h2>
              <div class="fila-detalle"><span class="etiqueta-detalle">Vuelos activos</span><span class="fw-bold text-success">12</span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Promociones activas</span><span class="fw-bold text-primary">1</span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Promociones pendientes</span><span class="fw-bold text-warning">2</span></div>
              <a href="../Funciones CEO/reportes-ceo.php" class="btn btn-outline-secondary btn-sm w-100 mt-3">
                <i class="bi bi-bar-chart-line me-1"></i>Ver Reportes del Sistema
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
          <a href="../Funciones CEO/vuelos-index.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-airplane" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Vuelos</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones CEO/vuelos-create.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-plus-circle" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Nuevo Vuelo</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones CEO/promociones-index.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-tag" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Promociones</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones CEO/reportes-ceo.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Reportes</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones Usuario/mi_perfil.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-person-circle" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Mi Perfil</div>
          </a>
        </li>
      </ul>
    </div>
  </section>

<?php else: ?>

  <!-- ═══════════════════ PANEL USUARIO/PASAJERO ═══════════════════ -->
  <section class="portada-registrado text-white bg-primary" aria-labelledby="hero-titulo">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <p class="text-white-50 text-uppercase small fw-semibold mb-2">
            <i class="bi bi-person-check-fill me-1"></i>Sesión iniciada
          </p>
          <h1 id="hero-titulo" class="display-5 fw-bold mb-2">¡Bienvenido, <?= $nombre ?>!</h1>
          <p class="lead text-white-50 mb-4">¿A dónde volamos hoy? Buscá tu próximo vuelo o gestioná tus reservas.</p>
          <div class="d-flex gap-3 flex-wrap">
            <a href="../Funciones Usuario/buscar_vuelos.php" class="btn btn-light btn-lg text-primary fw-semibold">
              <i class="bi bi-search me-2"></i>Buscar Vuelos
            </a>
            <a href="../Funciones Usuario/mis_reservas.php" class="btn btn-outline-light btn-lg">
              <i class="bi bi-calendar2-check me-2"></i>Mis Reservas
            </a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card border-0 shadow">
            <div class="card-body p-4">
              <h2 class="h6 fw-bold text-primary mb-3"><i class="bi bi-speedometer2 me-2"></i>Mi resumen</h2>
              <div class="fila-detalle"><span class="etiqueta-detalle">Reservas pendientes</span><span class="fw-bold text-warning">1</span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Reservas confirmadas</span><span class="fw-bold text-success">1</span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Compras totales</span><span class="fw-bold">2</span></div>
              <div class="fila-detalle"><span class="etiqueta-detalle">Novedades vigentes</span><span class="fw-bold text-primary">2</span></div>
              <a href="../Funciones Usuario/mi_perfil.php" class="btn btn-outline-primary btn-sm w-100 mt-3">
                <i class="bi bi-person-circle me-1"></i>Ver mi perfil completo
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
          <a href="../Funciones Usuario/buscar_vuelos.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-search" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Buscar Vuelos</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones Usuario/mis_reservas.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-calendar2-check" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Mis Reservas</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones Usuario/historial_compras.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-clock-history" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Historial</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones Usuario/novedades.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-megaphone" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Novedades</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones Usuario/promociones.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-tag-fill" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Promociones</div>
          </a>
        </li>
        <li class="col-6 col-md-4 col-lg-2">
          <a href="../Funciones Usuario/mi_perfil.php" class="tarjeta-acceso card border-0 shadow-sm p-3 text-center">
            <div class="icono-acceso text-primary"><i class="bi bi-person-circle" aria-hidden="true"></i></div>
            <div class="fw-semibold small">Mi Perfil</div>
          </a>
        </li>
      </ul>
    </div>
  </section>

  <section class="pb-4" aria-labelledby="pendiente-titulo">
    <div class="container">
      <div class="alert alert-warning d-flex align-items-start gap-3 shadow-sm" role="note">
        <i class="bi bi-hourglass-split fs-4 flex-shrink-0 mt-1" aria-hidden="true"></i>
        <div>
          <h2 id="pendiente-titulo" class="alert-heading h6 fw-bold mb-1">Tenés una reserva pendiente de pago</h2>
          <p class="mb-2 small">La <strong>Reserva N° 0001</strong> (ROS → EZE, 15 May 2026) está esperando confirmación de pago.</p>
          <a href="../Funciones Usuario/mis_reservas.php" class="btn btn-warning btn-sm">
            <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Ir a Mis Reservas para pagar
          </a>
        </div>
      </div>
    </div>
  </section>

<?php endif; ?>

</main>

<?php include '../Footer/footer.php'; ?>

</body>
</html>
