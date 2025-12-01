<div class="container mt-5">

    <h2 class="text-center text-primary fw-bold mb-4 py-3 px-4 rounded-pill shadow">
        <i class="bi bi-house-door-fill"></i> PANEL PRINCIPAL
    </h2>
    <!-- Imagen centrada -->
    <div class="text-center mb-4">
        <img src="https://cdn-icons-png.flaticon.com/512/1828/1828884.png"
            alt="Imagen del panel"
            class="img-fluid rounded-4 shadow"
            style="max-width: 260px;">
    </div>


    <div class="row g-4 justify-content-center">

        <!-- Usuarios -->
        <div class="col-md-4">
            <div class="card card-equal shadow-lg border-0 rounded-4 text-center p-3">
                <i class="bi bi-person-fill-gear display-4 text-primary"></i>
                <h4 class="fw-bold mt-2">Usuarios</h4>
                <p>Gestiona los usuarios del sistema y sus permisos.</p>
                <a href="<?= BASE_URL ?>users" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-people-fill"></i> Usuarios
                </a>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-md-4">
            <div class="card card-equal shadow-lg border-0 rounded-4 text-center p-3">
                <i class="bi bi-person-badge-fill display-4 text-info"></i>
                <h4 class="fw-bold mt-2">Clientes</h4>
                <p>Administra la información de todos los clientes registrados.</p>
                <a href="<?= BASE_URL ?>clients" class="btn btn-info rounded-pill mt-auto text-white">
                    <i class="bi bi-people-fill"></i> Clientes
                </a>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-md-4">
            <div class="card card-equal shadow-lg border-0 rounded-4 text-center p-3">
                <i class="bi bi-box-seam-fill display-4 text-success"></i>
                <h4 class="fw-bold mt-2">Productos</h4>
                <p>Visualiza y administra todos los productos disponibles.</p>
                <a href="<?= BASE_URL ?>producto" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-box-seam-fill"></i> Productos
                </a>
            </div>
        </div>

        <!-- Proveedor -->
        <div class="col-md-4">
            <div class="card card-equal shadow-lg border-0 rounded-4 text-center p-3">
                <i class="bi bi-truck display-4 text-warning"></i>
                <h4 class="fw-bold mt-2">Proveedor</h4>
                <p>Registra y gestiona los proveedores del sistema.</p>
                <a href="<?= BASE_URL ?>proveedor" class="btn btn-warning rounded-pill mt-auto">
                    <i class="bi bi-search"></i> Ver proveedor
                </a>
            </div>
        </div>

        <!-- Categoría -->
        <div class="col-md-4">
            <div class="card card-equal shadow-lg border-0 rounded-4 text-center p-3">
                <i class="bi bi-tags-fill display-4 text-danger"></i>
                <h4 class="fw-bold mt-2">Categoría</h4>
                <p>Gestiona las categorías de los productos del sistema.</p>
                <a href="<?= BASE_URL ?>categoria" class="btn btn-danger rounded-pill mt-auto">
                    <i class="bi bi-tag"></i> Ver categorías
                </a>
            </div>
        </div>


    </div>
</div>