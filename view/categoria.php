<!-- categories.php -->
<div class="d-flex flex-column align-items-center">
    <!-- Título -->
    <h3 class="mt-3 mb-4 text-center text-primary fw-bold py-3 px-4 rounded-pill shadow titulo-categorias">
        <i class="bi bi-tags"></i> LISTA DE CATEGORÍAS
    </h3>

    <div class="container">

        <!-- Formulario agregar categoría -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="bi bi-plus-circle"></i> Agregar Categoría
            </div>
            <div class="card-body">
                <form id="frm_category">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de categoría">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Detalle</label>
                            <input type="text" class="form-control" id="detalle" name="detalle" placeholder="Detalle de categoría">
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary rounded-pill">
                            <i class="bi bi-save"></i> Guardar Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla categorías -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="bi bi-list"></i> Categorías Registradas
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Detalle</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="content_categories">
                        <!-- JS carga aquí las categorías -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- JS -->
<script src="<?= BASE_URL ?>view/function/categoria.js"></script>
