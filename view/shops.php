<div class="d-flex flex-column align-items-center">
    <h3 class="mt-3 mb-4 text-center text-primary fw-bold py-3 px-4 rounded-pill shadow titulo-usuarios">
        <i class="bi bi-people-fill"></i>
        <i class="bi bi-person-check-fill"></i>
        LISTA DE PRODUCTOS
    </h3>

    <div class="text-end mt-4">
        <a href="<?= BASE_URL ?>new-producto" class="btn btn-primary btn-lg rounded-pill shadow-sm">
            <i class="bi bi-plus-circle"></i> Agregar producto
        </a>
    </div>
    
    <script>
        var base_url = '<?php echo $base; ?>';
    </script>
    <script src="<?php echo $base; ?>view/function/producto.js"></script>

</div>