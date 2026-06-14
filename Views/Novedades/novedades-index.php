<?php include '../Navbar/index.html'; ?>

<main class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Gestión de Novedades</h1>
    <a href="novedades-create.php" class="btn btn-primary">+ Nueva Novedad</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive rounded">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Título del Anuncio</th>
              <th scope="col">Fecha Publicación</th>
              <th scope="col">Fecha Caducidad</th>
              <th scope="col">Estado</th>
              <th scope="col" class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">101</th>
              <td class="fw-bold">Nuevas rutas a Brasil para el verano</td>
              <td>15/10/2026</td>
              <td>01/03/2027</td>
              <td><span class="badge bg-success">Activa</span></td>
              <td class="text-end">
                <a href="novedades-mod.php?id=101" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Editar</a>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarNovedad">
                  <i class="bi bi-trash"></i> Eliminar
                </button>
              </td>
            </tr>

            <tr>
              <th scope="row">100</th>
              <td class="text-muted">Mantenimiento programado del servidor</td>
              <td class="text-muted">01/09/2026</td>
              <td class="text-muted">05/09/2026</td>
              <td><span class="badge bg-secondary">Caducada</span></td>
              <td class="text-end">
                <a href="novedades-mod.php?id=100" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Editar</a>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarNovedad">
                  <i class="bi bi-trash"></i> Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <nav aria-label="Navegación de páginas de novedades" class="mt-4">
        <ul class="pagination justify-content-center mb-0">
          <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a></li>
          <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
        </ul>
      </nav>

    </div>
  </div>
</main>

<div class="modal fade" id="modalEliminarNovedad" tabindex="-1" aria-labelledby="modalEliminarNovedadLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarNovedadLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar esta novedad? Los pasajeros ya no podrán verla.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <form action="novedades-delete.php" method="POST">
          <input type="hidden" name="id_novedad" value="101">
          <button type="submit" class="btn btn-danger">Sí, eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../Footer/index.html'; ?>