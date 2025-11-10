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
            <i class="bi bi-arrow-left-circle"></i> Regresar a Productos
        </a>
    </div>

    <!-- Barra de búsqueda -->
    <div class="row mb-4 justify-content-center">
        <div class="col-md-6">
            <div class="input-group input-group-lg shadow rounded-pill">
                <span class="input-group-text bg-primary text-white border-0 rounded-start-pill">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="busquedaProducto" class="form-control border-0 rounded-end-pill" placeholder="Buscar producto...">
            </div>
        </div>
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
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>Precio</th>
                                    <th>Total</th>
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
                    <div class="p-2 border rounded bg-white mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">Subtotal:</span>
                            <span id="subtotal" class="fw-semibold text-secondary">S/ 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">IGV (18%):</span>
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

<!-- Filtro de búsqueda -->
<script>
    document.getElementById("busquedaProducto").addEventListener("keyup", function() {
        const valor = this.value.toLowerCase();
        const cards = document.querySelectorAll("#content_products .card");

        cards.forEach(card => {
            const nombre = card.textContent.toLowerCase();
            card.parentElement.style.display = nombre.includes(valor) ? "" : "none";
        });
    });
</script>