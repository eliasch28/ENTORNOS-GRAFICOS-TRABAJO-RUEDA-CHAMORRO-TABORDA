<?php $tipoUsuario = $_SESSION['tipoUsuario'] ?? ''; ?>

<footer class="bg-dark text-light py-5">
  <div class="container">
    <div class="row g-5">

      <div class="col-lg-3 col-md-6">
        <div class="d-flex align-items-center gap-2 mb-2">
          <i class="bi bi-airplane-fill text-primary" aria-hidden="true"></i>
          <span class="fw-bold fs-5">VuelaLibre</span>
        </div>
        <p class="text-secondary small">
          Sistema de gestión de reservas de pasajes de avión.<br>
          UTN – Facultad Regional Rosario<br>
          Cátedra Entornos Gráficos 2026
        </p>
      </div>

      <nav class="col-lg-3 col-md-6" aria-label="Mapa del sitio">
        <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">
          <i class="bi bi-map me-1" aria-hidden="true"></i>Mapa del Sitio
        </h2>

        <?php if ($tipoUsuario === 'administrador'): ?>
          <ul class="lista-mapa-sitio" role="list">
            <li><a href="../LandPage/LandUsuarioRegistrado.php">Inicio</a></li>
            <li><a href="../Funciones Admin/aerolineas-index.php">Aerolíneas</a></li>
            <li><a href="../Funciones Admin/auditoria-promociones.php">Promociones</a></li>
            <li><a href="../Funciones Admin/novedades-index.php">Novedades</a></li>
            <li><a href="../Funciones Admin/solicitudes-ceo.php">Solicitudes CEO</a></li>
            <li><a href="../Funciones Admin/global-reports.php">Reportes</a></li>
            <li><a href="../Funciones Usuario/sobre_nosotros.php">Sobre Nosotros</a></li>
            <li><a href="../Funciones Usuario/ayuda.php">Centro de Ayuda</a></li>
          </ul>

        <?php elseif ($tipoUsuario === 'ceo de aerolinea'): ?>
          <ul class="lista-mapa-sitio" role="list">
            <li><a href="../LandPage/LandUsuarioRegistrado.php">Inicio</a></li>
            <li><a href="../Funciones CEO/vuelos-index.php">Vuelos</a></li>
            <li><a href="../Funciones CEO/promociones-index.php">Promociones</a></li>
            <li><a href="../Funciones CEO/reportes-ceo.php">Reportes</a></li>
            <li><a href="../Funciones Usuario/sobre_nosotros.php">Sobre Nosotros</a></li>
            <li><a href="../Funciones Usuario/ayuda.php">Centro de Ayuda</a></li>
          </ul>

        <?php elseif ($tipoUsuario === 'usuario'): ?>
          <ul class="lista-mapa-sitio" role="list">
            <li><a href="../LandPage/LandUsuarioRegistrado.php">Inicio</a></li>
            <li><a href="../Funciones Usuario/buscar_vuelos.php">Buscar Vuelos</a></li>
            <li><a href="../Funciones Usuario/promociones.php">Promociones</a></li>
            <li><a href="../Funciones Usuario/novedades.php">Novedades</a></li>
            <li><a href="../Funciones Usuario/sobre_nosotros.php">Sobre Nosotros</a></li>
            <li><a href="../Funciones Usuario/ayuda.php">Centro de Ayuda</a></li>
          </ul>

        <?php else: ?>
          <ul class="lista-mapa-sitio" role="list">
            <li><a href="../LandPage/LandUsuarioNoRegistrado.php">Inicio</a></li>
            <li><a href="../LandPage/LandUsuarioNoRegistrado.php#seccion-aerolineas">Aerolíneas</a></li>
            <li><a href="../LandPage/LandUsuarioNoRegistrado.php#seccion-vuelos">Vuelos disponibles</a></li>
            <li><a href="../Flujo Sesion/registrarse_.php">Registrarse</a></li>
            <li><a href="../Flujo Sesion/login.php">Iniciar Sesión</a></li>
            <li><a href="../Funciones Usuario/sobre_nosotros.php">Sobre Nosotros</a></li>
            <li><a href="../Funciones Usuario/ayuda.php">Centro de Ayuda</a></li>
          </ul>
        <?php endif; ?>

      </nav>

      <?php if ($tipoUsuario): ?>
      <nav class="col-lg-3 col-md-6" aria-label="Mi cuenta">
        <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">Mi Cuenta</h2>
        <ul class="lista-mapa-sitio" role="list">
          <li><a href="../Funciones Usuario/mi_perfil.php">Mi Perfil</a></li>
          <?php if ($tipoUsuario === 'usuario'): ?>
            <li><a href="../Funciones Usuario/mis_reservas.php">Mis Reservas</a></li>
            <li><a href="../Funciones Usuario/historial_compras.php">Historial de Compras</a></li>
          <?php endif; ?>
          <li><a href="../Flujo Sesion/recuperar_contrasena_.php">Recuperar Contraseña</a></li>
          <li><a href="../Flujo Sesion/logout.php">Cerrar Sesión</a></li>
        </ul>
      </nav>
      <?php endif; ?>

    </div>

    <div class="border-top border-secondary mt-5 pt-4 d-flex justify-content-between flex-wrap gap-2">
      <small class="text-secondary">&copy; 2026 VuelaLibre — Todos los derechos reservados.</small>
      <small class="text-secondary">UTN FRR · Entornos Gráficos</small>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
