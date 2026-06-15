<?php $tipoUsuario = $_SESSION['tipoUsuario'] ?? ''; ?>

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
              <a class="nav-link" href="../LandPage/LandUsuarioRegistrado.php">
                <i class="bi bi-house-fill me-1" aria-hidden="true"></i>Inicio
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones Admin/aerolineas-index.php">
                <i class="bi bi-building me-1" aria-hidden="true"></i>Aerolíneas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones Admin/auditoria-promociones.php">
                <i class="bi bi-tag me-1" aria-hidden="true"></i>Promociones
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones Admin/novedades-index.php">
                <i class="bi bi-megaphone me-1" aria-hidden="true"></i>Novedades
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones Admin/solicitudes-ceo.php">
                <i class="bi bi-person-check me-1" aria-hidden="true"></i>Solicitudes CEO
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones Admin/global-reports.php">
                <i class="bi bi-bar-chart-fill me-1" aria-hidden="true"></i>Reportes
              </a>
            </li>
          </ul>

        <?php elseif ($tipoUsuario === 'ceo de aerolinea'): ?>
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="../LandPage/LandUsuarioRegistrado.php">
                <i class="bi bi-house-fill me-1" aria-hidden="true"></i>Inicio
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones CEO/vuelos-index.php">
                <i class="bi bi-airplane me-1" aria-hidden="true"></i>Vuelos
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones CEO/promociones-index.php">
                <i class="bi bi-tag me-1" aria-hidden="true"></i>Promociones
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones CEO/reportes-ceo.php">
                <i class="bi bi-bar-chart-fill me-1" aria-hidden="true"></i>Reportes
              </a>
            </li>
          </ul>

        <?php elseif ($tipoUsuario === 'usuario'): ?>
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="../LandPage/LandUsuarioRegistrado.php">
                <i class="bi bi-house-fill me-1" aria-hidden="true"></i>Inicio
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones Usuario/buscar_vuelos.php">
                <i class="bi bi-search me-1" aria-hidden="true"></i>Buscar Vuelos
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones Usuario/mis_reservas.php">
                <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Mis Reservas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones Usuario/historial_compras.php">
                <i class="bi bi-clock-history me-1" aria-hidden="true"></i>Historial
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones Usuario/novedades.php">
                <i class="bi bi-megaphone me-1" aria-hidden="true"></i>Novedades
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Funciones Usuario/promociones.php">
                <i class="bi bi-tag me-1" aria-hidden="true"></i>Promociones
              </a>
            </li>
          </ul>

        <?php else: ?>
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="../LandPage/LandUsuarioNoRegistrado.php">
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
            <a href="../Funciones Usuario/mi_perfil.php"
               class="btn btn-light btn-sm text-primary fw-semibold">
              <i class="bi bi-person-circle me-1" aria-hidden="true"></i>
              Mi Perfil
            </a>
            <form action="../Flujo Sesion/logout.php" method="post" class="d-inline">
              <button type="submit" class="btn btn-outline-light btn-sm"
                      aria-label="Cerrar sesión">
                <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>
                Salir
              </button>
            </form>
          <?php else: ?>
            <a href="../Flujo Sesion/login.php" class="btn btn-outline-light btn-sm">
              <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
              Iniciar Sesión
            </a>
            <a href="../Flujo Sesion/registrarse_.php" class="btn btn-light btn-sm text-primary fw-semibold">
              <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>
              Registrarse
            </a>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </nav>
</header>
