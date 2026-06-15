<?php
include '../../config/conexion.php';
session_start();
if (!isset($_SESSION['codUsuario'])) {
    header('Location: ../Flujo Sesion/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nuevo Vuelo | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1" class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Alta de Nuevo Vuelo</h1>
    <a href="vuelos-index.php" class="btn btn-outline-secondary">Volver al Listado</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="vuelos-store.php" method="POST">
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="origen" class="form-label">Origen <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="origen" name="origen" required>
          </div>
          <div class="col-md-6">
            <label for="destino" class="form-label">Destino <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="destino" name="destino" required>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="precio" class="form-label">Precio <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
            </div>
          </div>
          <div class="col-md-6">
            <label for="asientos" class="form-label">Asientos Disponibles <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="asientos" name="asientos" min="1" required>
          </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
          <button type="reset" class="btn btn-light me-md-2">Limpiar Campos</button>
          <button type="submit" class="btn btn-success">Guardar Vuelo</button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include '../Footer/footer.php'; ?>

</body>
</html>
