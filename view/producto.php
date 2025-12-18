<script>
</script>
<div class="d-flex flex-column align-items-center">
    <h3 class="mt-3 mb-4 text-center text-primary fw-bold py-3 px-4 rounded-pill shadow titulo-usuarios">
        <i class="bi bi-box-seam"></i>
        <i class="bi bi-cart4"></i>
        PRODUCTOS
    </h3>

    <!-- Botón para agregar producto -->
    <div class="container">
        <div class="text-end mt-3">
            <a href="<?= BASE_URL ?>new-producto" class="btn btn-primary btn-lg rounded-pill">

                <i class="bi bi-plus-circle"></i>+ Nuevo producto</a>
        </div>

        <div class="text-end mt-3">
            <a href="<?= BASE_URL ?>producto-lista" class="btn btn-success btn-lg rounded-pill">
                <i class="bi bi-plus-circle"></i>Lista de productos</a>
        </div>

        <div class="text-end mt-3"></div>



    

        <div class="container">
            <table class="table table-bordered table-hover">
                <thead class="table-success">
                    <tr>
                        <th>Nro</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Detalle</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Fecha</th>
                        <th>Codigo de Barra</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="content_products_table">
                    <!-- JS carga aquí los productos -->
                </tbody>
            </table>

        </div>

        <!-- Contenedor donde se mostrarán las tarjetas de productos (corregido) -->
        <div id="content_products_cards" class="mt-5"></div>
    </div>

    <script src="<?= BASE_URL ?>view/function/JsBarcode.all.min.js"></script>
    <script src="<?= BASE_URL ?>view/function/venta.js"></script>