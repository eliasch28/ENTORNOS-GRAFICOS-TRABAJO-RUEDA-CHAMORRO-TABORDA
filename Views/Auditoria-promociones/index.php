<?php include '../Navbar/index.html'; ?>

<main class="container my-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Auditoría de Promociones</h1>
    <span class="badge bg-warning text-dark fs-6 shadow-sm">
      ⏳ 2 Pendientes de revisión
    </span>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive rounded">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Aerolínea</th>
              <th scope="col">Vuelo Aplicado</th>
              <th scope="col">Descuento</th>
              <th scope="col">Estado</th>
              <th scope="col" >Acción de Auditoría</th>
            </tr>
          </thead>
          <tbody>

<tr>
              <th scope="row">742</th>
              <td class="fw-bold">Aerolíneas Argentinas</td>
              <td>ROS (Rosario) ➔ BRC (Bariloche)</td>
              <td class="text-success fw-bold">20% OFF</td>
              <td><span class="badge bg-warning text-dark">Pendiente</span></td>
              <td>
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-sm btn-success">
                        <i class="bi bi-check-circle"></i> Aprobar
                    </button>
                    <button type="button" class="btn btn-sm btn-danger">
                        <i class="bi bi-x-circle"></i> Denegar
                    </button>
                </div>
              </td>
            </tr>

            <tr>
              <th scope="row">741</th>
              <td class="fw-bold">LATAM</td>
              <td>COR (Córdoba) ➔ MDZ (Mendoza)</td>
              <td class="text-success fw-bold">15% OFF</td>
              <td><span class="badge bg-success">Aprobada</span></td>
              <td>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>Auditada</button>
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <nav aria-label="Navegación de páginas de auditoría" class="mt-4">
        <ul class="pagination justify-content-center mb-0">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
          </li>
          <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item">
            <a class="page-link" href="#">Siguiente</a>
          </li>
        </ul>
      </nav>

    </div>
  </div>
</main>

<?php include '../Footer/index.html'; ?>