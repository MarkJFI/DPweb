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
