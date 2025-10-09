<!-- INICIO DE CUERPO DE PÁGINA -->
<div class="container-fluid mt-4">
    <div class="card shadow-lg border-0">
        <h5 class="card-header bg-primary text-white text-center">
            <i class="bi bi-pencil-square"></i> Editar Producto
        </h5>
        <form id="frm_edit_producto" enctype="multipart/form-data">
            <input type="hidden" id="id_producto" name="id_producto">
            <input type="hidden" id="imagen_actual" name="imagen_actual">
            <div class="card-body">

                <div class="mb-3 row">
                    <label for="codigo" class="col-sm-4 col-form-label">Código:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="nombre" class="col-sm-4 col-form-label">Nombre:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="detalle" class="col-sm-4 col-form-label">Detalle:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="detalle" name="detalle" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="precio" class="col-sm-4 col-form-label">Precio:</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="stock" class="col-sm-4 col-form-label">Stock:</label>
                    <div class="col-sm-8">
                        <input type="number" min="0" class="form-control" id="stock" name="stock" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="fecha_vencimiento" class="col-sm-4 col-form-label">Fecha Vencimiento:</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="id_proveedor" class="col-sm-4 col-form-label">Proveedor:</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="id_proveedor" name="id_proveedor" required>
                            <option value="">Seleccione un proveedor</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="imagen" class="col-sm-4 col-form-label">Imagen:</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="id_categoria" class="col-sm-4 col-form-label">Categoría:</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="id_categoria" name="id_categoria" required>
                            <option value="">Seleccione una categoría</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="submit" class="btn btn-success rounded-pill">
                        <i class="bi bi-check2-circle"></i> Actualizar
                    </button>
                    <a href="<?= BASE_URL ?>producto" class="btn btn-danger rounded-pill">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- FIN DE CUERPO DE PÁGINA -->

<script src="<?= BASE_URL ?>view/function/products.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let partes = window.location.pathname.split('/');
        let id = partes[partes.length - 1];

        cargarCategorias();
        cargarProveedores();

        if (!isNaN(id)) {
            edit_product(id); // precarga del producto en JS
        }
    });
</script>
