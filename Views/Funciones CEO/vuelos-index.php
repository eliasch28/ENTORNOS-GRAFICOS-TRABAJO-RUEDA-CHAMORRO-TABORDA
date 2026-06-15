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
  <title>Gestión de Vuelos | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1" class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Gestión de Vuelos</h1>
    <a href="vuelos-create.php" class="btn btn-primary">+ Nuevo Vuelo</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive rounded">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Origen</th>
              <th scope="col">Destino</th>
              <th scope="col">Precio</th>
              <th scope="col">Asientos</th>
              <th scope="col" class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>Buenos Aires</td>
              <td>Madrid</td>
              <td>$850.00</td>
              <td>120</td>
              <td class="text-end">
                <a href="vuelos-mod.php?id=1" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Editar</a>
                <button type="button" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Eliminar</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <nav aria-label="Paginación" class="mt-4">
        <ul class="pagination justify-content-center mb-0">
          <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a></li>
          <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
        </ul>
      </nav>
    </div>
  </div>
</main>

<?php include '../Footer/footer.php'; ?>

</body>
</html>
