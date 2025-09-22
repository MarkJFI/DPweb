<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <h5 class="mb-0">Lista de Productos</h5>
        <a href="<?php echo BASE_URL; ?>new-products" class="btn btn-primary">Nuevo Producto</a>
    </div>

    <table class="table table-striped-columns">
        <thead>
            <tr>
                <th>Nro</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Fecha de vencimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="content_productos"></tbody>
    </table>
</div>

<script src="<?php echo BASE_URL; ?>view/function/producto.js"></script>
