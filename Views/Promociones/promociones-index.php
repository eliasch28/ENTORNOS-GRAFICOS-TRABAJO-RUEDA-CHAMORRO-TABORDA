<?php include '../Navbar/index.html'; ?>

<main class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Gestión de vuelos</h1>
    <a href="vuelos-create.php" class="btn btn-primary">+ Nuevo vuelo</a>
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
              <th scope="col">Asientos disponibles</th>
              <th scope="col" class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>Buenos Aires</td>
              <td>Madrid</td>
              <td>$500</td>
              <td>20</td>
              <td class="text-end">
                <a href="vuelos-mod.php?id=1" class="btn btn-sm btn-outline-secondary">Editar</a>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                  data-bs-target="#modalEliminar">
                  Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <nav aria-label="Navegación de páginas de vuelos" class="mt-4">
        <ul class="pagination justify-content-center mb-0">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
          </li>

          <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>

          <li class="page-item">
            <a class="page-link" href="#">Siguiente</a>
          </li>
        </ul>
      </nav>

    </div>
  </div>
</main>

<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este vuelo? Esta acción no se puede deshacer.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <form action="vuelos-delete.php" method="POST">
          <input type="hidden" name="id_vuelo" value="1">
          <button type="submit" class="btn btn-danger">Sí, eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include '../Footer/index.html'; ?>