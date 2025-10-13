<div class="container">
    <div class="row align-items-center mt-3 mb-3">
        <div class="col-4 text-start">
            <a href="<?= BASE_URL ?>new-clients" class="btn btn-primary btn-sm">+ Nuevo Cliente</a>
        </div>
        <div class="col-4 text-center">
            <h4 class="mb-0">Lista de Clientes</h4>
        </div>
        <div class="col-4 text-end">
            <a href="<?= BASE_URL ?>proveedor-lista" class="btn btn-success btn-sm">Ver lista de Proveedores</a>
        </div>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nro</th>
                <th>DNI</th>
                <th>Nombres y apellidos</th>
                <th>Correo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="content_clients"></tbody>
    </table>
</div>
<script src="<?= BASE_URL ?>view/function/clients.js"></script>