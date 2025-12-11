<div class="container py-4">
    <!-- Título -->
    <div class="d-flex flex-column align-items-center">
        <h3 class="mt-3 mb-4 text-center text-primary fw-bold py-3 px-4 rounded-pill shadow titulo-usuarios">
            <i class="bi bi-box-seam"></i>
            <i class="bi bi-cart4"></i>
            LISTA DE PRODUCTOS CON IMAGEN
        </h3>
    </div>

    <!-- Botón para regresar a productos -->
    <div class="text-end mt-3">
        <a href="<?= BASE_URL ?>producto" class="btn btn-secondary btn-lg rounded-pill shadow-sm px-4">
            <i class="bi bi-arrow-left-circle"></i> Regresar a Productos
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
                class="form-control border-0 rounded-end-pill"
                placeholder="BUSCAR PRODUCTO POR NOMBRE O CÓDIGO"
                id="busquedaProducto"
                onkeyup="view_products_cards()">

                <input type="hidden" id="id_producto_venta">
                <input type="hidden" id="producto_precio_venta">
                <input type="hidden" id="producto_cantidad_venta">
            

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
<script>
    let input = document.getElementById("busquedaProducto");
    input.addEventListener('keydown',(event)=>{
        if (event.key =='Enter'){
            agregar_producto_temporal();
        }
    })
</script>
<!-- Modal para ver detalles del producto -->
<style>
    /* Estilos para modal de producto */
    .product-modal-img { max-height: 320px; object-fit: contain; border-radius: .5rem; box-shadow: 0 6px 18px rgba(0,0,0,0.12); }
    .product-price { font-size: 1.6rem; color: #0d6efd; font-weight: 700; }
    .product-badge { font-size: .85rem; margin-right: .4rem; }
    .product-attr { color: #6c757d; }
</style>
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4">
                        <img id="modalProductoImagen" src="" alt="Imagen" class="img-fluid product-modal-img">
                    </div>
                    <div class="col-md-7 p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 id="modalProductoNombre" class="mb-1"></h3>
                                <div class="mb-2">
                                    <span id="modalProductoCategoria" class="badge bg-secondary product-badge">Categoría</span>
                                    <span id="modalProductoProveedor" class="badge bg-info text-dark product-badge">Proveedor</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="product-price mb-1" id="modalProductoPrecio">S/ 0.00</div>
                                <div class="product-attr">Stock: <span id="modalProductoStock">0</span></div>
                            </div>
                        </div>

                        <p id="modalProductoDetalle" class="text-muted small mt-3"></p>

                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <label class="small text-muted">Fecha de vencimiento</label>
                                <div class="fw-medium" id="modalProductoFecha">—</div>
                            </div>
                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                <div class="d-inline-flex align-items-center gap-2">
                                    <button id="modalQtyMinus" class="btn btn-outline-secondary btn-sm">-</button>
                                    <input id="modalCantidad" type="number" min="1" value="1" class="form-control form-control-sm text-center" style="width:80px">
                                    <button id="modalQtyPlus" class="btn btn-outline-secondary btn-sm">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button id="modalAgregarBtn" class="btn btn-primary btn-lg px-4"> <i class="bi bi-cart-plus me-2"></i> Agregar al Carrito</button>
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal"><i class="bi bi-x-lg me-2"></i>Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
