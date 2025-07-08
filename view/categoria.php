<!-- -->
    <div class="container-fluid">
        <div class="card">
            <h5 class="card-header">Categoria</h5>
            <form id="frm_categoria" action="" method="">
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
                    <button type="submit" class="btn btn-primary">Registrar</button>
                    <button type="reset" class="btn btn-warning">Limpiar</button>
                    <button type="button" class="btn btn-danger">Cancelar</button>

                </div>
            </form>
        </div>
    </div>
 <!-- -->

  <script src="<?php echo BASE_URL; ?>view/function/categoria.js"></script>