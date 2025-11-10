<div class="container-fluid mt-4 row">
    <h2>Productos</h2>
    <div class="col-9">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Productos Disponibles</h5>
                <div class="row container-fluid" id="productos_venta">
                    <!-- Los productos se cargarán aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Lista de Compra</h5>
                <div class="row" style="min-height: 500px;">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="lista_compra">
                                <tr>
                                    <td>Producto 1</td>
                                    <td>2</td>
                                    <td>$10.00</td>
                                    <td>$20.00</td>
                                    <td><button class="btn btn-danger btn-sm">Eliminar</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-end">
                        <h4>Subtotal : <label id="">$20.00</label></h4>
                        <h4>Igv : <label id="">$20.00</label></h4>
                        <h4>Total : <label id="">$20.00</label></h4>
                        <button class="btn btn-success">Realizar Venta</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= BASE_URL ?>view/function/producto.js"></script>
<script src="<?= BASE_URL ?>view/function/ventas.js"></script>
