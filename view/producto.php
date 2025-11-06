<div class="d-flex flex-column align-items-center">
    <h3 class="mt-3 mb-4 text-center text-primary fw-bold py-3 px-4 rounded-pill shadow titulo-usuarios">
        <i class="bi bi-box-seam"></i>
        <i class="bi bi-cart4"></i>
        LISTA DE PRODUCTOS
    </h3>
    
    <!-- Botón para agregar nuevo producto -->
    <div class="text-end mt-4">
        <a href="<?= BASE_URL ?>new-producto" class="btn btn-primary btn-lg rounded-pill shadow-sm">
            <i class="bi bi-plus-circle"></i> Agregar producto
        </a>
    </div>

    <!-- Contenedor donde se mostrarán las tarjetas de productos -->
    <div id="content_products" class="mt-5"></div>
</div>

<!-- Script que genera las cards -->
<script src="<?= BASE_URL ?>view/function/products.js"></script>
