<?php
include '../../config/conexion.php';
require_once '../../config/requiere_admin.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modificar Aerolínea | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1" class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Modificar Aerolínea</h1>
    <a href="aerolineas-index.php" class="btn btn-outline-secondary">Volver al Listado</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="aerolineas-update.php" method="POST">
        <input type="hidden" name="id_aerolinea" value="">
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="razon_social" class="form-label">Razón Social <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="razon_social" name="razon_social" required>
          </div>
          <div class="col-md-6">
            <label for="pais" class="form-label">País de Origen <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="pais" name="pais" required>
          </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
          <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include '../Footer/footer.php'; ?>

</body>
</html>
