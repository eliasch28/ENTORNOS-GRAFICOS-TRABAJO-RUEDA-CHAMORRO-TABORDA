<?php include '../Navbar/index.html'; ?>

<main class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Publicar Nueva Novedad</h1>
        <a href="novedades-index.php" class="btn btn-outline-secondary">Volver al listado</a>
      </div>

      <div class="card shadow-sm">
        <div class="card-body p-4">
          
          <form action="novedades-store.php" method="POST" class="needs-validation" novalidate>
            
            <div class="mb-3">
              <label for="tituloNovedad" class="form-label fw-bold">Título del Anuncio <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="tituloNovedad" name="titulo" placeholder="Ej: Nuevas políticas de equipaje" required>
              <div class="invalid-feedback">
                Por favor, ingresá un título para la novedad.
              </div>
            </div>

            <div class="mb-3">
              <label for="contenidoNovedad" class="form-label fw-bold">Contenido del Anuncio <span class="text-danger">*</span></label>
              <textarea class="form-control" id="contenidoNovedad" name="contenido" rows="5" placeholder="Escribí el cuerpo de la noticia acá..." required></textarea>
              <div class="invalid-feedback">
                El contenido de la novedad no puede estar vacío.
              </div>
            </div>

            <div class="row mb-4">
              <div class="col-md-6">
                <label for="fechaCaducidad" class="form-label fw-bold">Fecha de Caducidad <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fechaCaducidad" name="fecha_caducidad" required>
                <div class="form-text">A partir de esta fecha, la novedad dejará de ser visible para los pasajeros.</div>
                <div class="invalid-feedback">
                  Es obligatorio seleccionar una fecha de caducidad.
                </div>
              </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <button type="reset" class="btn btn-light me-md-2">Limpiar Campos</button>
              <button type="submit" class="btn btn-primary">Publicar Novedad</button>
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