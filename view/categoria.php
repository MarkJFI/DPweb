<!-- categories.php -->
<div class="d-flex flex-column align-items-center">
    <!-- Título -->
    <h3 class="mt-3 mb-4 text-center text-primary fw-bold py-3 px-4 rounded-pill shadow titulo-usuarios">
        LISTA DE CATEGORÍAS
    </h3>

    <div class="container">



        <!-- Tabla categorías -->

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
                    <!-- Aquí se cargarán las categorías dinámicamente -->
                </tbody>
            </table>
        </div>
        
        <!-- Formulario agregar categoría -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fw-bold">
                Agregar Categoría
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







    </div>
</div>

<!-- Incluir el archivo JavaScript -->
<script src="<?= BASE_URL ?>view/function/categoria.js"></script>
```
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