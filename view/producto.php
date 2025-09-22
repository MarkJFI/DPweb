<!-- products.php -->
<div class="d-flex flex-column align-items-center">
    <h3 class="mt-3 mb-4 text-center text-success fw-bold py-3 px-4 rounded-pill shadow titulo-productos">
        <i class="bi bi-box-seam"></i>
        <i class="bi bi-cart4"></i>
        LISTA DE PRODUCTOS
    </h3>
    <div class="container">
        <table class="table table-bordered table-hover">
            <thead class="table-success">
                <tr>
                    <th>codigo</th>
                    <th>Nombre</th>
                    <th>Detalle</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>id_categoria</th>
                    <th>fecha_vencimiento</th>
                    <th>imagen</th>
                    <th>id_proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="content_productos">
                <!-- JS carga aquí los productos -->
            </tbody>
        </table>
        <!-- Botón para agregar producto -->
        <div class="text-end mt-3">
            <a href="<?= BASE_URL ?>new-producto" class="btn btn-primary btn-lg rounded-pill">
                <i class="bi bi-plus-circle"></i> Agregar producto
            </a>
        </div>
    </div>
</div>

<!-- Enlaza tu JS -->
<script src="<?= BASE_URL ?>view/function/products.js"></script>