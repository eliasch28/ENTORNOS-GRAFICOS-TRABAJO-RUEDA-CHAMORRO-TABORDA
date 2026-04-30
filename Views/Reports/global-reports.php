<?php include '../Navbar/index.html'; ?>

<main class="container my-5" id="contenido-principal" tabindex="-1">
  
  <!-- Encabezado -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Reportes Globales y Estadísticas</h1>
    <button class="btn btn-outline-secondary">
      <i class="bi bi-printer"></i> Exportar a PDF
    </button>
  </div>

  <!-- Tarjetas de Resumen (KPIs) -->
  <div class="row g-4 mb-5">
    <!-- KPI Ventas -->
    <div class="col-md-4">
      <div class="card bg-success text-white h-100 shadow border-0 fade-up">
        <div class="card-body d-flex flex-column justify-content-center align-items-center">
          <h5 class="card-title text-uppercase fw-bold mb-1"><i class="bi bi-cash-stack"></i> Ingresos Totales</h5>
          <h2 class="display-5 fw-bold mb-0">$14.5M</h2>
          <small class="text-white-50">Reservas confirmadas globales</small>
        </div>
      </div>
    </div>
    <!-- KPI Vuelos -->
    <div class="col-md-4">
      <div class="card bg-primary text-white h-100 shadow border-0 fade-up" style="transition-delay: 0.1s;">
        <div class="card-body d-flex flex-column justify-content-center align-items-center">
          <h5 class="card-title text-uppercase fw-bold mb-1"><i class="bi bi-airplane"></i> Vuelos Operativos</h5>
          <h2 class="display-5 fw-bold mb-0">342</h2>
          <small class="text-white-50">En el sistema actualmente</small>
        </div>
      </div>
    </div>
    <!-- KPI Usuarios -->
    <div class="col-md-4">
      <div class="card bg-dark text-white h-100 shadow border-0 fade-up" style="transition-delay: 0.2s;">
        <div class="card-body d-flex flex-column justify-content-center align-items-center">
          <h5 class="card-title text-uppercase fw-bold mb-1"><i class="bi bi-people"></i> Usuarios Registrados</h5>
          <h2 class="display-5 fw-bold mb-0">1,204</h2>
          <small class="text-white-50">Pasajeros y CEOs</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Contenedor de Pestañas (Tabs) -->
  <div class="card shadow border fade-up" style="transition-delay: 0.3s;">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-0 ">
      <ul class="nav nav-tabs" id="reportesTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active fw-bold" id="ventas-tab" data-bs-toggle="tab" data-bs-target="#ventas" type="button" role="tab" aria-controls="ventas" aria-selected="true">
            Ventas
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-bold" id="vuelos-activos-tab" data-bs-toggle="tab" data-bs-target="#vuelos-activos" type="button" role="tab" aria-controls="vuelos-activos" aria-selected="false">
            Vuelos Activos
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-bold" id="vuelos-log-tab" data-bs-toggle="tab" data-bs-target="#vuelos-log" type="button" role="tab" aria-controls="vuelos-log" aria-selected="false">
            Historial de Vuelos
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-bold" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab" aria-controls="usuarios" aria-selected="false">
            Usuarios
          </button>
        </li>
      </ul>
    </div>
    
    <div class="card-body">
      <div class="tab-content" id="reportesTabsContent">
        
        <!-- PESTAÑA 1: VENTAS -->
        <div class=" rounded tab-pane fade show active" id="ventas" role="tabpanel" aria-labelledby="ventas-tab">
          <div class="table-responsive rounded">
            <table class="table table-striped table-hover table-bordered border-secondary-subtle align-middle shadow-sm">
              <thead class="table-dark">
                <tr>
                  <th scope="col">ID Reserva</th>
                  <th scope="col">Fecha</th>
                  <th scope="col">Vuelo (Aerolínea)</th>
                  <th scope="col">Pasajero</th>
                  <th scope="col">Monto</th>
                  <th scope="col">Estado</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr>
                  <th scope="row" class="text-primary fw-bold">#RV-8821</th>
                  <td>30/04/2026</td>
                  <td class="fw-semibold">FO-0003 (Flybondi)</td>
                  <td>Juan Pérez</td>
                  <td class="fw-bold">$29.990</td>
                  <td><span class="badge bg-success">Confirmada</span></td>
                </tr>
                <tr>
                  <th scope="row" class="text-primary fw-bold">#RV-8822</th>
                  <td>29/04/2026</td>
                  <td class="fw-semibold">AA-0001 (Aerolíneas)</td>
                  <td>María Gómez</td>
                  <td class="fw-bold">$48.500</td>
                  <td><span class="badge bg-warning text-dark">Pendiente Pago</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PESTAÑA 2: VUELOS ACTIVOS -->
        <div class="tab-pane fade" id="vuelos-activos" role="tabpanel" aria-labelledby="vuelos-activos-tab ">
          <div class="table-responsive rounded">
            <table class="table table-striped table-hover table-bordered border-secondary-subtle align-middle shadow-sm">
              <thead class="table-dark">
                <tr>
                  <th scope="col">Cód. Vuelo</th>
                  <th scope="col">Aerolínea</th>
                  <th scope="col">Ruta</th>
                  <th scope="col">Fecha Salida</th>
                  <th scope="col">Ocupación Actual</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr>
                  <th scope="row" class="text-primary fw-bold">LA-0002</th>
                  <td class="fw-semibold">LATAM</td>
                  <td>AEP ➔ SCL</td>
                  <td>18/05/2026 14:15</td>
                  <td>
                    <div class="progress" style="height: 20px;">
                      <div class="progress-bar bg-success" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%</div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th scope="row" class="text-primary fw-bold">JA-0004</th>
                  <td class="fw-semibold">JetSmart</td>
                  <td>IGR ➔ BRC</td>
                  <td>22/05/2026 16:45</td>
                  <td>
                    <div class="progress" style="height: 20px;">
                      <div class="progress-bar bg-warning text-dark" role="progressbar" style="width: 45%;" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">45%</div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PESTAÑA 3: HISTORIAL DE VUELOS (LOG) -->
        <div class="tab-pane fade" id="vuelos-log" role="tabpanel" aria-labelledby="vuelos-log-tab">
          <div class="table-responsive rounded">
            <table class="table table-striped table-hover table-bordered border-secondary-subtle align-middle shadow-sm">
              <thead class="table-dark">
                <tr>
                  <th scope="col">Cód. Vuelo</th>
                  <th scope="col">Aerolínea</th>
                  <th scope="col">Ruta</th>
                  <th scope="col">Fecha Original</th>
                  <th scope="col">Estado Final</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr>
                  <th scope="row" class="text-secondary fw-bold">AA-0099</th>
                  <td class="fw-semibold">Aerolíneas Argentinas</td>
                  <td>ROS ➔ EZE</td>
                  <td>15/04/2026 08:30</td>
                  <td><span class="badge bg-secondary">Aterrizado</span></td>
                </tr>
                <tr>
                  <th scope="row" class="text-secondary fw-bold">FO-0105</th>
                  <td class="fw-semibold">Flybondi</td>
                  <td>COR ➔ MDZ</td>
                  <td>14/04/2026 11:00</td>
                  <td><span class="badge bg-danger">Cancelado</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PESTAÑA 4: USUARIOS -->
        <div class="tab-pane fade" id="usuarios" role="tabpanel" aria-labelledby="usuarios-tab">
          <div class="table-responsive rounded">
            <table class="table table-striped table-hover table-bordered border-secondary-subtle align-middle shadow-sm">
              <thead class="table-dark">
                <tr>
                  <th scope="col">ID User</th>
                  <th scope="col">Nombre Completo</th>
                  <th scope="col">Email</th>
                  <th scope="col">Rol</th>
                  <th scope="col">Fecha Registro</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr>
                  <th scope="row" class="text-primary fw-bold">U-105</th>
                  <td class="fw-semibold">Elias Chamorro</td>
                  <td>elias@utn.edu.ar</td>
                  <td><span class="badge bg-secondary">Pasajero</span></td>
                  <td>15/03/2026</td>
                </tr>
                                <tr>
                  <th scope="row" class="text-primary fw-bold">U-106</th>
                  <td class="fw-semibold">Ezequiel Wheel</td>
                  <td>eze@utn.edu.ar</td>
                  <td><span class="badge bg-secondary">Pasajero</span></td>
                  <td>15/03/2026</td>
                </tr>
                <tr>
                  <th scope="row" class="text-primary fw-bold">U-002</th>
                  <td class="fw-semibold">CEO Flybondi</td>
                  <td>ceo@flybondi.com</td>
                  <td><span class="badge bg-primary">CEO Aerolínea</span></td>
                  <td>10/01/2026</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>

</main>

<?php include '../Footer/index.html'; ?>