<!-- inicio de cuerpo de pagina -->
    <div class="container" style="margin-top: 100px;">
        <div class="card">
            <div class="card-header" style="text-align:center;">
                Registrar Categoria
            </div>
            <form id="frm_categoria" action="" method="">
                <div class="card-body">

                    <div class="mb-3 row">
                        <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="detalle" class="col-sm-2 col-form-label">Detalle</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="detalle" name="detalle" required>
                        </div>
                    </div>

                    <div style="display: flex; justify-content:center; gap:20px">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                        <button type="reset" class="btn btn-info">Limpiar</button>
                        <button type="button" class="btn btn-danger">Cancelar</button>
                        <a href="<?php echo BASE_URL; ?>categoria-lista">ver</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- fin de cuerpo de pagina -->
    <script src="<?php echo BASE_URL; ?>view/function/categoria.js"></script>