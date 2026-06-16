<?php
include '../../config/conexion.php';
require_once '../../config/requiere_admin.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Solicitudes de CEO | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1" class="container my-5">
  <div class="mb-4">
    <h1 class="h2">Solicitudes de Registro — CEO</h1>
    <p class="text-muted">Usuarios que se registraron como CEO de Aerolínea y están pendientes de aprobación.</p>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive rounded">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Usuario</th>
              <th scope="col">Email</th>
              <th scope="col">Teléfono</th>
              <th scope="col">Estado</th>
              <th scope="col" class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">5</th>
              <td>ceo_ejemplo</td>
              <td>ceo@ejemplo.com</td>
              <td>3410000000</td>
              <td><span class="badge bg-warning text-dark">Pendiente</span></td>
              <td class="text-end">
                <button type="button" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Aprobar</button>
                <button type="button" class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i> Rechazar</button>
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
