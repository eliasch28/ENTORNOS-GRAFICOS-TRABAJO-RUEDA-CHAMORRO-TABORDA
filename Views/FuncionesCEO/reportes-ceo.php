<?php
include '../../config/conexion.php';
require_once '../../config/requiere_ceo.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reportes del Sistema | VuelaLibre – UTN FRR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../../styles.css"/>
</head>
<body class="bg-light">

<?php include '../Header/header.php'; ?>

<main id="contenido-principal" tabindex="-1" class="container my-5">
  <div class="mb-4">
    <h1 class="h2">Reportes del Sistema</h1>
    <p class="text-muted">Vista de lectura — generada por el Administrador.</p>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3">
      <div class="card text-bg-primary shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-people-fill fs-2"></i>
          <div>
            <div class="fs-4 fw-bold">128</div>
            <div class="small">Usuarios registrados</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card text-bg-success shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-airplane-fill fs-2"></i>
          <div>
            <div class="fs-4 fw-bold">54</div>
            <div class="small">Vuelos activos</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card text-bg-warning shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-ticket-perforated-fill fs-2"></i>
          <div>
            <div class="fs-4 fw-bold">312</div>
            <div class="small">Reservas realizadas</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card text-bg-danger shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-tag-fill fs-2"></i>
          <div>
            <div class="fs-4 fw-bold">7</div>
            <div class="small">Promociones pendientes</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header fw-semibold">Últimas Reservas</div>
    <div class="card-body">
      <div class="table-responsive rounded">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="table-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Usuario</th>
              <th scope="col">Vuelo</th>
              <th scope="col">Fecha Reserva</th>
              <th scope="col">Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">312</th>
              <td>juan.perez</td>
              <td>BUE → MAD</td>
              <td>14/06/2026</td>
              <td><span class="badge bg-success">Confirmada</span></td>
            </tr>
            <tr>
              <th scope="row">311</th>
              <td>maria.gomez</td>
              <td>ROS → EZE</td>
              <td>13/06/2026</td>
              <td><span class="badge bg-warning text-dark">Pendiente</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<?php include '../Footer/footer.php'; ?>

</body>
</html>
