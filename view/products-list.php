<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div class="d-flex align-items-center">
            <a href="<?php echo BASE_URL; ?>new-products" class="btn btn-success me-3"><i class="bi bi-plus-circle"></i>+ Nuevo Producto</a>
            <h5 class="mb-0"><i>Lista de Productos</i></h5>
        </div>
    </div>

    <table class="table table-striped-columns">
        <thead>
            <tr>
                <th><i>Nro</i></th>
                <th><i>Código</i></th>
                <th><i>Nombre</i></th>
                <th><i>Precio</i></th>
                <th><i>Stock</i></th>
                <th><i>Categoria</i></th>
                <th><i>Fecha de vencimiento</i></th>
                <th><i>Acciones</i></th>
            </tr>
        </thead>
        <tbody id="content_productos"></tbody>
    </table>
</div>

<script src="<?php echo BASE_URL; ?>view/function/producto.js"></script>
