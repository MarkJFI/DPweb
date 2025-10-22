<!------------------INICIO DE CUERPO DE PÁGINA------------------>
<div class="container-fluid">
    <div class="card">
        <h5 class="card-header">Registro de Categoría</h5>
        <form id="frm_category" action="<?= BASE_URL ?>controller/CategoriaController.php?tipo=registrar" method="POST">
            <div class="card-body">

                <div class="mb-3 row">
                    <label for="nombre" class="col-sm-4 col-form-label">Nombre de la categoría:</label>
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

                <button type="submit" class="btn btn-primary">Registrar</button>
                <button type="reset" class="btn btn-warning">Limpiar</button>
                <a href="<?= BASE_URL ?>categorias" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<!--------------------FIN DE CUERPO DE PÁGINA------------------->

<script src="<?= BASE_URL ?>view/function/categoria.js"></script>
