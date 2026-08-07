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
            <li><a href="../FuncionesAdmin/aerolineas-index.php">Aerolíneas</a></li>
            <li><a href="../FuncionesAdmin/auditoria-promociones.php">Promociones</a></li>
            <li><a href="../FuncionesAdmin/novedades-index.php">Novedades</a></li>
            <li><a href="../FuncionesAdmin/solicitudes-ceo.php">Solicitudes CEO</a></li>
            <li><a href="../FuncionesAdmin/global-reports.php">Reportes</a></li>
            <li><a href="../FuncionesUsuario/sobre_nosotros.php">Sobre Nosotros</a></li>
            <li><a href="../FuncionesUsuario/ayuda.php">Centro de Ayuda</a></li>
          </ul>

        <?php elseif ($tipoUsuario === 'ceo de aerolinea'): ?>
          <ul class="lista-mapa-sitio" role="list">
            <li><a href="../LandPage/LandUsuarioRegistrado.php">Inicio</a></li>
            <li><a href="../FuncionesCEO/vuelos-index.php">Vuelos</a></li>
            <li><a href="../FuncionesCEO/promociones-index.php">Promociones</a></li>
            <li><a href="../FuncionesCEO/reportes-ceo.php">Reportes</a></li>
            <li><a href="../FuncionesUsuario/sobre_nosotros.php">Sobre Nosotros</a></li>
            <li><a href="../FuncionesUsuario/ayuda.php">Centro de Ayuda</a></li>
          </ul>

        <?php elseif ($tipoUsuario === 'usuario'): ?>
          <ul class="lista-mapa-sitio" role="list">
            <li><a href="../LandPage/LandUsuarioRegistrado.php">Inicio</a></li>
            <li><a href="../FuncionesUsuario/buscar_vuelos.php">Buscar Vuelos</a></li>
            <li><a href="../FuncionesUsuario/promociones.php">Promociones</a></li>
            <li><a href="../FuncionesUsuario/novedades.php">Novedades</a></li>
            <li><a href="../FuncionesUsuario/sobre_nosotros.php">Sobre Nosotros</a></li>
            <li><a href="../FuncionesUsuario/ayuda.php">Centro de Ayuda</a></li>
            <li><a href="../FuncionesUsuario/ayuda.php#formularioContactoAyuda">Contáctanos</a></li>
          </ul>

        <?php else: ?>
          <ul class="lista-mapa-sitio" role="list">
            <li><a href="../LandPage/LandUsuarioNoRegistrado.php">Inicio</a></li>
            <li><a href="../LandPage/LandUsuarioNoRegistrado.php#seccion-aerolineas">Aerolíneas</a></li>
            <li><a href="../LandPage/LandUsuarioNoRegistrado.php#seccion-vuelos">Vuelos disponibles</a></li>
            <li><a href="../FuncionesUsuario/sobre_nosotros.php">Sobre Nosotros</a></li>
            <li><a href="../FuncionesUsuario/ayuda.php">Centro de Ayuda</a></li>
            <li><a href="../FuncionesUsuario/ayuda.php#formularioContactoAyuda">Contáctanos</a></li>
          </ul>
        <?php endif; ?>

      </nav>

      <?php if ($tipoUsuario): ?>
        <nav class="col-lg-3 col-md-6" aria-label="Mi cuenta">
          <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">Mi Cuenta</h2>
          <ul class="lista-mapa-sitio" role="list">
            <li><a href="../FuncionesUsuario/mi_perfil.php">Mi Perfil</a></li>
              <?php if ($tipoUsuario === 'usuario'): ?>
              <li><a href="../FuncionesUsuario/mis_reservas.php">Mis Reservas</a></li>
              <li><a href="../FuncionesUsuario/historial_compras.php">Historial de Compras</a></li>
              <?php endif; ?>
            <li><a href="../FlujoSesion/recuperar_contrasena_.php">Recuperar Contraseña</a></li>
            <li>
              <form action="../FlujoSesion/login.php" method="post" class="d-inline">
                <input type="hidden" name="_logout" value="1">
                <button type="submit" class="btn btn-link p-0 text-secondary">
                  Cerrar Sesión
                </button>
              </form>
            </li>
          </ul>
        </nav>
      <?php endif; ?>

      <div class="col-lg-3 col-md-6">
        <h2 class="h6 text-uppercase text-secondary small fw-semibold mb-3">
          <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>Contacto
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
            <a href="mailto:vuelalibredevs@gmail.com" class="text-secondary text-decoration-none">
              vuelalibredevs@gmail.com
            </a>
          </li>
          <li>
            <i class="bi bi-mortarboard me-2 text-primary" aria-hidden="true"></i>
            Cátedra Entornos Gráficos 2026
          </li>
        </ul>
      </div>

    </div>

    <div class="border-top border-secondary mt-5 pt-4 d-flex justify-content-between flex-wrap gap-2">
      <small class="text-secondary">&copy; 2026 VuelaLibre — Todos los derechos reservados.</small>
      <small class="text-secondary">UTN FRR · Entornos Gráficos</small>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.btn-ver-contrasena').forEach(function (btn) {
  btn.addEventListener('click', function () {
    var input = btn.closest('.input-group').querySelector('input');
    var icon = btn.querySelector('i');
    var visible = input.type === 'text';
    input.type = visible ? 'password' : 'text';
    icon.classList.toggle('bi-eye', visible);
    icon.classList.toggle('bi-eye-slash', !visible);
    btn.setAttribute('aria-label', visible ? 'Mostrar contraseña' : 'Ocultar contraseña');
  });
});

function habilitarFormulario(form) {
  form.dataset.enviando = '';
  form.querySelectorAll('button[type="submit"]').forEach(function (boton) {
    boton.disabled = false;
    if (boton.dataset.htmlOriginal) {
      boton.innerHTML = boton.dataset.htmlOriginal;
    }
  });
}

document.querySelectorAll('form').forEach(function (form) {
  form.addEventListener('submit', function (evento) {
    if (evento.defaultPrevented) {
      return;
    }
    if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
      return;
    }
    if (form.dataset.enviando === '1') {
      evento.preventDefault();
      return;
    }
    form.dataset.enviando = '1';
    form.querySelectorAll('button[type="submit"]').forEach(function (boton) {
      boton.dataset.htmlOriginal = boton.innerHTML;
      boton.disabled = true;
      boton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Procesando...';
    });
  });
});

window.addEventListener('pageshow', function (evento) {
  if (evento.persisted) {
    document.querySelectorAll('form[data-enviando="1"]').forEach(habilitarFormulario);
  }
});
</script>