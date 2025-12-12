<div class="container py-4">
    <!-- Título -->
    <div class="d-flex flex-column align-items-center">
        <h3 class="mt-3 mb-4 text-center text-primary fw-bold py-3 px-4 rounded-pill shadow titulo-usuarios">
            <i class="bi bi-box-seam"></i>
            <i class="bi bi-cart4"></i>
            LISTA DE PRODUCTOS
        </h3>
    </div>

    <!-- Botón para regresar a productos -->
    <div class="text-end mt-3">
        <a href="<?= BASE_URL ?>producto" class="btn btn-secondary btn-lg rounded-pill shadow-sm px-4">
            <i class="bi bi-arrow-left-circle"></i> Regresar
        </a>
    </div>


    <!-- Barra de búsqueda -->
    <div class="col-md-6">
        <div class="input-group input-group-lg shadow rounded-pill">

            <span class="input-group-text bg-primary text-white border-0 rounded-start-pill">
                <i class="bi bi-search"></i>
            </span>

            <input
                type="text"
                class="form-control border-4 rounded-end-pill"
                placeholder="BUSCAR..."
                id="busquedaProducto"
                onkeyup="view_products_cards()">

        </div>
    </div>


    <!-- Carrusel -->
    <div id="productosCarrusel" class="carousel slide mt-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="<?= BASE_URL ?>view/img/img4.jpg" class="d-block w-100" alt="Producto Destacado 1" style="height: 300px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div>
            <div class="carousel-item">
                <img src="<?= BASE_URL ?>view/img/img3.webp" class="d-block w-100" alt="Producto Destacado 2" style="height: 300px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div>
            <div class="carousel-item">
                <img src="<?= BASE_URL ?>view/img/img5.webp" class="d-block w-100" alt="Producto Destacado 3" style="height: 300px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">

                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#productosCarrusel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productosCarrusel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <!-- Contenido principal -->
    <div class="row">
        <!-- Lista de productos -->
        <div class="col-lg-8 mb-3">
            <div id="content_products" class="row gy-4"></div>
        </div>

        <!-- Carrito lateral -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white text-center fw-bold">
                    <i class="bi bi-bag-check-fill"></i> LISTA DE VENTAS
                </div>

                <div class="card-body p-2" id="carritoProductos" style="max-height: 400px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>nombre</th>
                                    <th>cantidad</th>
                                    <th>precio</th>
                                    <th>SubTotal</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tablaCarrito">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        No hay productos en la lista.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="p-2 border rounded bg-white mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">Subtotal:</span>
                            <span id="subtotal" class="fw-semibold text-secondary">S/ 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">IGV:</span>
                            <span id="igv" class="fw-semibold text-secondary">S/ 0.00</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total General:</span>
                            <span id="totalGeneral" class="fw-bold text-success fs-6">S/ 0.00</span>
                        </div>
                    </div>

                    <button class="btn btn-success w-100 fw-bold py-2">
                        <i class="bi bi-cash-stack"></i> Realizar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script que genera las cards -->
<script src="<?= BASE_URL ?>view/function/lista.js"></script>
<script src="<?= BASE_URL ?>view/function/venta.js"></script>
<script>
    let input = document.getElementById('busquedaProducto');
    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            agregar_producto_temporal(1, 12, 2)
           
        }
    })
</script>
