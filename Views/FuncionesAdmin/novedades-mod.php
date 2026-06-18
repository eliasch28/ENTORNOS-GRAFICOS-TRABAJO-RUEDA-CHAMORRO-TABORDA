<?php
include '../../config/conexion.php';
require_once '../../config/requiere_admin.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modificar Novedad | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1" class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Modificar Novedad</h1>
    <a href="novedades-index.php" class="btn btn-outline-secondary">Volver al Listado</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="novedades-update.php" method="POST">
        <input type="hidden" name="id_novedad" value="">
        <div class="mb-3">
          <label for="titulo" class="form-label">Título del Anuncio <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
          <label for="descripcion" class="form-label">Descripción</label>
          <textarea class="form-control" id="descripcion" name="descripcion" rows="4"></textarea>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="fecha_publicacion" class="form-label">Fecha de Publicación <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion" required>
          </div>
          <div class="col-md-6">
            <label for="fecha_caducidad" class="form-label">Fecha de Caducidad <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="fecha_caducidad" name="fecha_caducidad" required>
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
