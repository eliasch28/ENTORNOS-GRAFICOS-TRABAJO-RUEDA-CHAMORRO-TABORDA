<?php include '../Navbar/index.html'; ?>

<link rel="stylesheet" href="../../styles.css">
<main class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Modificar vuelo</h1>
    <a href="vuelos-index.php" class="btn btn-outline-secondary">Volver al Listado</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="vuelos-update.php" method="POST" class="needs-validation" novalidate>

        <input type="hidden" name="id_vuelo" value="1">

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="origen" class="form-label">Origen <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="origen" name="origen" value="Buenos Aires" required>
            <div class="invalid-feedback">
              Por favor, ingrese el origen del vuelo.
            </div>
          </div>
          <div class="col-md-6">
            <label for="destino" class="form-label">Destino<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="destino" name="destino" value="Argentina" required>
            <div class="invalid-feedback">
              Por favor, ingrese el destino del vuelo.
            </div>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="precio" class="form-label mb-0">Precio <span class="text-danger">*</span></label>
            <div class="input-group has-validation">
              <span class="input-group-text" id="basic-addon1">$</span>
              <input type="number" class="form-control" id="precio" name="precio" value="500" step="0.01" required>

              <div class="invalid-feedback">
                Por favor, ingrese el precio del vuelo.
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <label for="asientos" clsass="form-label">Asientos disponibles<span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="asientos" name="asientos" value="20" required>
            <div class="invalid-feedback">
              Por favor, ingrese el número de asientos disponibles del vuelo.
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
  (function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
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