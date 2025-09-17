<!------------------INICIO DE CUERPO DE PÁGINA------------------>
<div class="container-fluid">
    <div class="card">
        <h5 class="card-header">Editar Registro de la Categoría</h5>

        <?php
        if (isset($_GET["views"])) {
            $ruta = explode("/", $_GET["views"]);
            // $ruta[1] contiene el ID de la categoría
        }
        ?>
        <form id="frm_edit_categoria" action="" method="post">
            <input type="hidden" id="id_categoria" name="id_categoria" value="<?= $ruta[1]; ?>">

            <div class="card-body">
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

                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="<?= BASE_URL ?>categoria" class="btn btn-primary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<!--------------------FIN DE CUERPO DE PÁGINA------------------->

<script src="<?= BASE_URL ?>view/function/categoria.js"></script>
<script>
    edit_categoria(); // función que traerá datos y rellenará el formulario
</script>
