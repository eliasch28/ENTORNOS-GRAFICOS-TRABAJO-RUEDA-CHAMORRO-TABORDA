<?php include '../Navbar/index.html'; ?>

<main class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Alta de Nueva Aerolínea</h1>
    <a href="aerolineas-index.php" class="btn btn-outline-secondary">Volver al Listado</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="aerolineas-store.php" method="POST" class="needs-validation" novalidate>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="razon_social" class="form-label">Razón Social <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="razon_social" name="razon_social" required minlength="3" maxlength="100">
            <div class="invalid-feedback">
              Por favor, ingrese una razón social válida (mínimo 3 caracteres).
            </div>
          </div>
          <div class="col-md-6">
            <label for="pais" class="form-label">País de Origen <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="pais" name="pais" required>
            <div class="invalid-feedback">
              Por favor, ingrese el país de origen.
            </div>
          </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
          <button type="reset" class="btn btn-light me-md-2">Limpiar Campos</button>
          <button type="submit" class="btn btn-success">Guardar Aerolínea</button>
        </div>
      </form>
    </div>
  </div>
</main>

<script>
  (function() {
    'use strict'
    // Obtener todos los formularios a los que queremos aplicar estilos de validación de Bootstrap
    var forms = document.querySelectorAll('.needs-validation')

    // Bucle sobre ellos y evitar el envío si son inválidos
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