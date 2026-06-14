<?php include '../Navbar/index.html'; ?>

<main class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Editar Novedad</h1>
        <a href="novedades-index.php" class="btn btn-outline-secondary">Volver al listado</a>
      </div>

      <div class="card shadow-sm">
        <div class="card-body p-4">
          
          <form action="novedades-update.php" method="POST" class="needs-validation" novalidate>
            
            <input type="hidden" name="id_novedad" value="101">

            <div class="mb-3">
              <label for="tituloNovedad" class="form-label fw-bold">Título del Anuncio <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="tituloNovedad" name="titulo" value="Nuevas rutas a Brasil para el verano" required>
              <div class="invalid-feedback">
                Por favor, ingresá un título para la novedad.
              </div>
            </div>

            <div class="mb-3">
              <label for="contenidoNovedad" class="form-label fw-bold">Contenido del Anuncio <span class="text-danger">*</span></label>
              <textarea class="form-control" id="contenidoNovedad" name="contenido" rows="5" required>Aprovechá nuestros nuevos vuelos directos a Río de Janeiro y San Pablo a partir de enero con un 10% de descuento en la primera compra.</textarea>
              <div class="invalid-feedback">
                El contenido de la novedad no puede estar vacío.
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-md-6">
                <label for="fechaCaducidad" class="form-label fw-bold">Fecha de Caducidad <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fechaCaducidad" name="fecha_caducidad" value="2027-03-01" required>
                <div class="form-text">A partir de esta fecha, la novedad dejará de ser visible para los pasajeros.</div>
                <div class="invalid-feedback">
                  Es obligatorio seleccionar una fecha de caducidad.
                </div>
              </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <a href="novedades-index.php" class="btn btn-light me-md-2">Cancelar</a>
              <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</main>

<script>
  (function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
</script>

<?php include '../Footer/index.html'; ?>