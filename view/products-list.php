
<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div class="d-flex align-items-center">
            <a href="<?php echo BASE_URL; ?>new-products" class="btn btn-success me-3"><i class="bi bi-plus-circle"></i>+ Nuevo Producto</a>
            <h5 class="mb-0"><center><i>Lista de Productos</i></center></h5>
        </div>
    </div>

    <div class="row" id="content_productos"></div>
<?php include_once dirname(__DIR__) . '/config/config.php'; ?>
<div class="container mt-4">
  <h3 class="mb-3">Lista de Productos</h3>
  <a href="<?= BASE_URL; ?>new-products" class="btn btn-success mb-4">Nuevo Producto</a>
  <div id="products_grid" class="row g-3"></div>
</div>
<script>
  const base_url = '<?= BASE_URL; ?>';
</script>
<script src="<?= BASE_URL; ?>view/function/producto.js"></script>
