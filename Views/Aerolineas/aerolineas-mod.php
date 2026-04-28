<?php include '../Navbar/index.html'; ?>

<main class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Modificar Aerolínea</h1>
    <a href="aerolineas-index.php" class="btn btn-outline-secondary">Volver al Listado</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="aerolineas-update.php" method="POST" class="needs-validation" novalidate>

        <input type="hidden" name="id_aerolinea" value="1">

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="razon_social" class="form-label">Razón Social <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="razon_social" name="razon_social" value="Aerolíneas Argentinas" required minlength="3">
            <div class="invalid-feedback">
              Por favor, ingrese una razón social válida.
            </div>
          </div>
          <div class="col-md-6">
            <label for="pais" class="form-label">País de Origen <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="pais" name="pais" value="Argentina" required>
            <div class="invalid-feedback">
              Por favor, ingrese el país de origen.
            </div>
          </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
          <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</main>

<script>
  (function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
      .forEach(function(form) {
        form.addEventListener('submit', function(event) {
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