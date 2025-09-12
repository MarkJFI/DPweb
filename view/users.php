<div class="d-flex flex-column align-items-center">
    <h3 class="mt-3 mb-4 text-center text-primary fw-bold py-3 px-4 rounded-pill shadow titulo-usuarios">
        <i class="bi bi-people-fill"></i>
        <i class="bi bi-person-check-fill"></i>
        LISTA DE USUARIO
    </h3>
    <div class="container">
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>N°</th>
                    <th>DNI</th>
                    <th>Nombres y Apellidos</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="content_users">

            </tbody>
        </table>
        <!-- Botón para agregar usuario -->
        <div class="text-end mt-3">
            <a href="<?= BASE_URL ?>new-user" class="btn btn-success btn-lg rounded-pill">
                <i class="bi bi-person-plus"></i> Agregar usuario
            </a>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>view/function/user.js"></script>