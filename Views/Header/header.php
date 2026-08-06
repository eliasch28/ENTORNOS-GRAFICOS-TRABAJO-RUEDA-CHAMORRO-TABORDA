<?php
$tipoUsuario = $_SESSION['tipoUsuario'] ?? '';
$nombreMostrado = trim($_SESSION['nombreCompleto'] ?? ($_SESSION['nombreUsuario'] ?? ''));
$paginaActual = basename($_SERVER['PHP_SELF']);
$seccionActiva = function (array $paginas) use ($paginaActual) {
    return in_array($paginaActual, $paginas, true);
};
$etiquetaRol = [
    'administrador'    => 'Administrador',
    'ceo de aerolinea' => 'CEO de aerolínea',
    'usuario'          => 'Usuario',
][$tipoUsuario] ?? '';
?>

<a class="visually-hidden-focusable position-absolute top-0 start-0 m-2 btn btn-light text-primary fw-semibold"
   href="#contenido-principal">
  Saltar al contenido principal
</a>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary"
       aria-label="Navegación principal">
    <div class="container">

      <a class="navbar-brand fw-bold d-flex align-items-center gap-2"
         href="<?= $tipoUsuario ? '../LandPage/LandUsuarioRegistrado.php' : '../LandPage/LandUsuarioNoRegistrado.php' ?>"
         aria-label="VuelaLibre — Ir a la página de inicio">
        <i class="bi bi-airplane-fill" aria-hidden="true"></i>
        VuelaLibre
      </a>

      <button class="navbar-toggler" type="button"
              data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
              aria-controls="menuPrincipal" aria-expanded="false"
              aria-label="Abrir o cerrar menú de navegación">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuPrincipal">

        <?php if ($tipoUsuario === 'administrador'): ?>
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['LandUsuarioRegistrado.php']) ? ' active' : '' ?>"
                 href="../LandPage/LandUsuarioRegistrado.php"
                 <?= $seccionActiva(['LandUsuarioRegistrado.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-house-fill me-1" aria-hidden="true"></i>Inicio
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['aerolineas-index.php', 'aerolineas-create.php', 'aerolineas-mod.php']) ? ' active' : '' ?>"
                 href="../FuncionesAdmin/aerolineas-index.php"
                 <?= $seccionActiva(['aerolineas-index.php', 'aerolineas-create.php', 'aerolineas-mod.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolíneas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['auditoria-promociones.php']) ? ' active' : '' ?>"
                 href="../FuncionesAdmin/auditoria-promociones.php"
                 <?= $seccionActiva(['auditoria-promociones.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-tag me-1" aria-hidden="true"></i>Promociones
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['novedades-index.php', 'novedades-create.php', 'novedades-mod.php']) ? ' active' : '' ?>"
                 href="../FuncionesAdmin/novedades-index.php"
                 <?= $seccionActiva(['novedades-index.php', 'novedades-create.php', 'novedades-mod.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-megaphone me-1" aria-hidden="true"></i>Novedades
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['solicitudes-ceo.php']) ? ' active' : '' ?>"
                 href="../FuncionesAdmin/solicitudes-ceo.php"
                 <?= $seccionActiva(['solicitudes-ceo.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-person-check me-1" aria-hidden="true"></i>Solicitudes CEO
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['global-reports.php']) ? ' active' : '' ?>"
                 href="../FuncionesAdmin/global-reports.php"
                 <?= $seccionActiva(['global-reports.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-bar-chart-fill me-1" aria-hidden="true"></i>Reportes
              </a>
            </li>
          </ul>

        <?php elseif ($tipoUsuario === 'ceo de aerolinea'): ?>
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['LandUsuarioRegistrado.php']) ? ' active' : '' ?>"
                 href="../LandPage/LandUsuarioRegistrado.php"
                 <?= $seccionActiva(['LandUsuarioRegistrado.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-house-fill me-1" aria-hidden="true"></i>Inicio
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['vuelos-index.php', 'vuelos-create.php', 'vuelos-mod.php']) ? ' active' : '' ?>"
                 href="../FuncionesCEO/vuelos-index.php"
                 <?= $seccionActiva(['vuelos-index.php', 'vuelos-create.php', 'vuelos-mod.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-airplane me-1" aria-hidden="true"></i>Vuelos
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['promociones-index.php', 'promociones-create.php', 'promociones-mod.php']) ? ' active' : '' ?>"
                 href="../FuncionesCEO/promociones-index.php"
                 <?= $seccionActiva(['promociones-index.php', 'promociones-create.php', 'promociones-mod.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-tag me-1" aria-hidden="true"></i>Promociones
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['reportes-ceo.php']) ? ' active' : '' ?>"
                 href="../FuncionesCEO/reportes-ceo.php"
                 <?= $seccionActiva(['reportes-ceo.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-bar-chart-fill me-1" aria-hidden="true"></i>Reportes
              </a>
            </li>
          </ul>

        <?php elseif ($tipoUsuario === 'usuario'): ?>
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['LandUsuarioRegistrado.php']) ? ' active' : '' ?>"
                 href="../LandPage/LandUsuarioRegistrado.php"
                 <?= $seccionActiva(['LandUsuarioRegistrado.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-house-fill me-1" aria-hidden="true"></i>Inicio
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['buscar_vuelos.php', 'reservar_vuelo.php']) ? ' active' : '' ?>"
                 href="../FuncionesUsuario/buscar_vuelos.php"
                 <?= $seccionActiva(['buscar_vuelos.php', 'reservar_vuelo.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-search me-1" aria-hidden="true"></i>Buscar Vuelos
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['mis_reservas.php']) ? ' active' : '' ?>"
                 href="../FuncionesUsuario/mis_reservas.php"
                 <?= $seccionActiva(['mis_reservas.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Mis Reservas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['historial_compras.php']) ? ' active' : '' ?>"
                 href="../FuncionesUsuario/historial_compras.php"
                 <?= $seccionActiva(['historial_compras.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-clock-history me-1" aria-hidden="true"></i>Historial
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['novedades.php']) ? ' active' : '' ?>"
                 href="../FuncionesUsuario/novedades.php"
                 <?= $seccionActiva(['novedades.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-megaphone me-1" aria-hidden="true"></i>Novedades
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['promociones.php']) ? ' active' : '' ?>"
                 href="../FuncionesUsuario/promociones.php"
                 <?= $seccionActiva(['promociones.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-tag me-1" aria-hidden="true"></i>Promociones
              </a>
            </li>
          </ul>

        <?php else: ?>
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link<?= $seccionActiva(['LandUsuarioNoRegistrado.php']) ? ' active' : '' ?>"
                 href="../LandPage/LandUsuarioNoRegistrado.php"
                 <?= $seccionActiva(['LandUsuarioNoRegistrado.php']) ? 'aria-current="page"' : '' ?>>
                <i class="bi bi-house-fill me-1" aria-hidden="true"></i>Inicio
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../LandPage/LandUsuarioNoRegistrado.php#seccion-aerolineas">
                <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolíneas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../LandPage/LandUsuarioNoRegistrado.php#seccion-vuelos">
                <i class="bi bi-airplane me-1" aria-hidden="true"></i>Vuelos
              </a>
            </li>
          </ul>
        <?php endif; ?>

        <div class="d-flex align-items-center gap-2">
          <?php if ($tipoUsuario): ?>
            <?php if ($nombreMostrado !== ''): ?>
              <span class="navbar-text text-white text-end lh-sm d-none d-lg-block me-1">
                <span class="d-block small fw-semibold"><?= htmlspecialchars($nombreMostrado, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="d-block text-white-50" style="font-size:.75rem;"><?= htmlspecialchars($etiquetaRol, ENT_QUOTES, 'UTF-8') ?></span>
              </span>
            <?php endif; ?>
            <a href="../FuncionesUsuario/mi_perfil.php"
               class="btn btn-light btn-sm text-primary fw-semibold<?= $seccionActiva(['mi_perfil.php']) ? ' active' : '' ?>"
               <?= $seccionActiva(['mi_perfil.php']) ? 'aria-current="page"' : '' ?>>
              <i class="bi bi-person-circle me-1" aria-hidden="true"></i>
              Mi Perfil
            </a>
            <form action="../FlujoSesion/login.php" method="post" class="d-inline">
              <input type="hidden" name="_logout" value="1"/>
              <button type="submit" class="btn btn-outline-light btn-sm"
                      aria-label="Cerrar sesión">
                <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>
                Salir
              </button>
            </form>
          <?php else: ?>
            <a href="../FlujoSesion/login.php" class="btn btn-outline-light btn-sm">
              <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
              Iniciar Sesión
            </a>
            <a href="../FlujoSesion/registrarse_.php" class="btn btn-light btn-sm text-primary fw-semibold">
              <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>
              Registrarse
            </a>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </nav>
</header>
