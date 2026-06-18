<?php
include '../../config/conexion.php';
require_once '../../config/requiere_ceo.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modificar Promoción | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1" class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Modificar Promoción</h1>
    <a href="promociones-index.php" class="btn btn-outline-secondary">Volver al Listado</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="promociones-update.php" method="POST">
        <input type="hidden" name="id_promocion" value="">
        <div class="mb-3">
          <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>
        <div class="mb-3">
          <label for="descuento" class="form-label">Descuento (%) <span class="text-danger">*</span></label>
          <div class="input-group">
            <input type="number" class="form-control" id="descuento" name="descuento" min="1" max="100" required>
            <span class="input-group-text">%</span>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
          </div>
          <div class="col-md-6">
            <label for="fecha_fin" class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
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
