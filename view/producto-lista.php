<div class="container py-10">
    <!-- Título -->
    <div class="d-flex flex-column align-items-center">
        <h3 class="mt-4 mb-3 text-center text-primary fw-bold py-3 px-4 rounded-pill shadow titulo-usuarios">
            <i class="bi bi-box-seam"></i>
            <i class="bi bi-cart4"></i>
            LISTA DE PRODUCTOS CON IMAGEN
        </h3>
    </div>

    <!-- Botón para regresar a productos -->
    <div class="text-end mt-3">
        <a href="<?= BASE_URL ?>producto" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4">
            <i class="bi bi-arrow-left-circle"></i> Regresar
        </a>
    </div>


    <!-- Barra de búsqueda -->
    <div class="col-md-4">
        <div class="input-group input-group-lg shadow rounded-pill">

            <span class="input-group-text bg-primary text-white border-0 rounded-start-pill">
                <i class="bi bi-search"></i>
            </span>

            <input
                type="text"
                class="form-control border-0 rounded-end-pill"
                placeholder="Buscar productos..."
                id="busquedaProducto"
                onkeyup="view_products_cards()">

                <input type="hidden" id="id_producto_venta">
                <input type="hidden" id="producto_precio_venta">
                <input type="hidden" id="producto_cantidad_venta">
            

        </div>
    </div>


    <!-- Carrusel -->
    <div id="productosCarrusel" class="carousel slide mt-5" data-bs-ride="carousel">
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
        <div class="col-lg-9 mb-3">
            <div id="content_products" class="row gy-5"></div>
        </div>

        <!-- Carrito lateral -->
        <div class="col-lg-3">
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

                   <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Realizar venta
</button>
<!-- Modal -->
<div class="modal fade modal-lg" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Registro de Venta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_venta">
            <div class="row">
                <div class="col-md-4">
                    <label for="cliente_dni" class="form-label">DNI del Cliente</label>
                    <input type="text" class="form-control" id="cliente_dni" name="cliente_dni" required>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#modalCliente">
                        Buscar Cliente
                    </button>
                </div>
                <div class="col-md-6">
                    <label for="cliente_nombre" class="form-label">Nombre del cliente</label>
                    <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" required>
                </div>
                <div class="col-md-6">
                    <label for="fecha_venta">Fecha de Venta</label>
                    <input type="date" class="form-control" id="fecha_venta" name="fecha_venta" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary">Registrar venta</button>
      </div>
    </div>
  </div>
</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Buscar Cliente -->
    <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalClienteLabel">Buscar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="mensajeCliente">Buscando cliente...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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

    // Agregar funcionalidad para buscar cliente por DNI
    document.querySelector('button[data-bs-target="#modalCliente"]').addEventListener('click', function() {
        const dni = document.getElementById('cliente_dni').value.trim();
        const nombreInput = document.getElementById('cliente_nombre');
        const mensaje = document.getElementById('mensajeCliente');
        
        if (!dni) {
            mensaje.textContent = 'Por favor, ingrese un DNI válido.';
            return;
        }
        
        mensaje.textContent = 'Buscando cliente...';
        
        fetch('/DPweb/control/VentaController.php?tipo=buscarCliente', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'dni=' + encodeURIComponent(dni)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                nombreInput.value = data.nombre;
                mensaje.textContent = 'Cliente encontrado: ' + data.nombre;
            } else {
                nombreInput.value = '';
                mensaje.textContent = data.msg;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mensaje.textContent = 'Error al buscar el cliente.';
        });
    });
</script>

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


</body>
</html>

