<!--inicio de cuerpo de pagina-->
<div class="container-fluid">
    <div class="card">
        <h5 class="card-header">Editar Cliente/Proveedor</h5>
        <?php
        if (isset($_GET["views"])) {
            $ruta = explode("/", $_GET["views"]);
        }
        ?>
        <form id="frm_edit_client" action="">
            <input type="hidden" id="id_persona" name="id_persona" value="<?= isset($ruta[1]) ? $ruta[1] : '';?>">
            <div class="card-body">
                <div class="mb-3 row">
                    <label for="nro_identidad" class="col-sm-4 col-form-label">Nro de Documento :</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="nro_identidad" name="nro_identidad" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="razon_social" class="col-sm-4 col-form-label">Apellidos y Nombres :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="telefono" class="col-sm-4 col-form-label">Teléfono :</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="telefono" name="telefono" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="correo" class="col-sm-4 col-form-label">Correo Electrónico:</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="departamento" class="col-sm-4 col-form-label">Departamento:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="departamento" name="departamento" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="provincia" class="col-sm-4 col-form-label">Provincia:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="provincia" name="provincia" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="distrito" class="col-sm-4 col-form-label">Distrito:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="distrito" name="distrito" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cod_postal" class="col-sm-4 col-form-label">Código Postal:</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="cod_postal" name="cod_postal" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="direccion" class="col-sm-4 col-form-label">Dirección:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password" class="col-sm-4 col-form-label">Contraseña:</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Dejar vacío para no cambiar">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="rol" class="col-sm-4 col-form-label">Rol:</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="rol" id="rol" required>
                            <option value="" disabled>Seleccione un rol</option>
                            <option value="Cliente">Cliente</option>
                            <option value="Proveedor">Proveedor</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="<?php echo BASE_URL; ?>clientes" class="btn btn-secondary">Volver</a>

            </div>
        </form>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>view/function/clients.js"></script>
<script>
    // cuando cargue, solicitar los datos (edit_client se define en clients.js)
    document.addEventListener('DOMContentLoaded', function(){
        if (typeof edit_client === 'function') {
            edit_client();
        }
    });
</script>
<!--fin de pie de pagina-->
