<?php
/*
 * headerNoIniciado.php
 * Ubicación física: Views/Header/headerNoIniciado.php
 * Usado por archivos en:
 *   Views/LandPage/     → include '../Header/headerNoIniciado.php'
 *   Views/Flujo Sesion/ → include '../Header/headerNoIniciado.php'
 *
 * Las rutas de los href apuntan relativas a Views/
 * usando ../ para salir de la subcarpeta del archivo que incluye.
 */
?>
<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary"
       aria-label="Navegación principal">
    <div class="container">

      <a class="navbar-brand fw-bold d-flex align-items-center gap-2"
         href="../LandPage/LandUsuarioNoRegistrado.php"
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
        <div class="d-flex gap-2" role="group" aria-label="Acciones de sesión">
          <a href="../Flujo Sesion/login.php" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
            Iniciar Sesión
          </a>
          <a href="../Flujo Sesion/registrarse_.php" class="btn btn-light btn-sm text-primary fw-semibold">
            <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>
            Registrarse
          </a>
        </div>
      </div>

    </div>
  </nav>
</header>
