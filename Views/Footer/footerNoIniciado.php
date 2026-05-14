<?php
/*
 * footerNoIniciado.php
 * Ubicación física: Views/Footer/footerNoIniciado.php
 * Usado por archivos en:
 *   Views/LandPage/     → include '../Footer/footerNoIniciado.php'
 *   Views/Flujo Sesion/ → include '../Footer/footerNoIniciado.php'
 */
?>
<footer class="bg-dark text-light py-5">
  <div class="container">
    <div class="row g-5">

      <!-- Identidad -->
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

      <!-- Mapa del Sitio -->
      <nav class="col-lg-3 col-md-6" aria-label="Mapa del sitio">
        <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">
          <i class="bi bi-map me-1" aria-hidden="true"></i>Mapa del Sitio
        </h2>
        <ul class="sitemap-list" role="list">
          <li><a href="../LandPage/LandUsuarioNoRegistrado.php">Inicio</a></li>
          <li><a href="../LandPage/LandUsuarioNoRegistrado.php#seccion-aerolineas">Aerolíneas</a></li>
          <li><a href="../LandPage/LandUsuarioNoRegistrado.php#seccion-vuelos">Vuelos</a></li>
          <li><a href="../Flujo Sesion/registrarse_.php">Registrarse</a></li>
          <li><a href="../Flujo Sesion/login.php">Iniciar Sesión</a></li>
          <li><a href="../Flujo Sesion/recuperar_contrasena_.php">Recuperar Contraseña</a></li>
          <li><a href="../Flujo Sesion/sobreNosotros.php">Sobre Nosotros</a></li>
        </ul>
      </nav>

      <!-- Mi Cuenta -->
      <nav class="col-lg-3 col-md-6" aria-label="Secciones del pasajero">
        <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">Mi Cuenta</h2>
        <ul class="sitemap-list" role="list">
          <li><a href="../Flujo Sesion/login.php">Mi Perfil</a></li>
          <li><a href="../Flujo Sesion/login.php">Buscar Vuelos</a></li>
          <li><a href="../Flujo Sesion/login.php">Mis Reservas</a></li>
          <li><a href="../Flujo Sesion/login.php">Historial de Compras</a></li>
          <li><a href="../Flujo Sesion/login.php">Ver Novedades</a></li>
          <li><a href="../Flujo Sesion/login.php">Ver Promociones</a></li>
      </nav>

      <!-- Administración -->
      <nav class="col-lg-3 col-md-6" aria-label="Secciones de administración">
        <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">Administración</h2>
        <ul class="sitemap-list" role="list">
          <li><a href="../Flujo Sesion/login.php">Panel Administrador</a></li>
          <li><a href="../Flujo Sesion/login.php">Panel CEO Aerolínea</a></li>
          <li><a href="../Flujo Sesion/login.php">Gestión de Vuelos</a></li>
          <li><a href="../Flujo Sesion/login.php">Gestión de Promociones</a></li>
          <li><a href="../Flujo Sesion/login.php">Reportes</a></li>
        </ul>
      </nav>

    </div>

    <div class="border-top border-secondary mt-5 pt-4 d-flex justify-content-between flex-wrap gap-2">
      <small class="text-secondary">&copy; 2026 VuelaLibre — Todos los derechos reservados.</small>
      <small class="text-secondary">UTN FRR · Entornos Gráficos</small>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmkYJfmIlCv0SSFCE5nEatGkWxlg"
        crossorigin="anonymous"></script>
