<div class="container">
    <div class="row align-items-center mt-3 mb-3">
        <div class="col-4 text-start">
            <a href="<?= BASE_URL ?>new-proveedor" class="btn btn-primary btn-sm">+ Nuevo Proveedor</a>
        </div>
        <div class="col-4 text-center">
            <h4 class="mb-0"><i>Lista de Proveedores</i></h4>
        </div>
        <div class="col-4 text-end">
        </div>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th><i>Nro</i></th>
                <th><i>DNI</i></th>
                <th><i>Nombre y Apellidos</i></th>
                <th><i>Correo</i></th>
                <th><i>Estado</i></th>
                <th><i>Acciones</i></th>
            </tr>
        </thead>
        <tbody id="content_proveedores">

        </tbody>
    </table>
</div>
<script src="<?= BASE_URL ?>view/function/clients.js"></script>
