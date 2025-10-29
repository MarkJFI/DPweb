function validar_form(tipo) {
    let codigo = document.getElementById("codigo").value;
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;
    let precio = document.getElementById("precio").value;
    let stock = document.getElementById("stock").value;
    let id_categoria = document.getElementById("id_categoria").value;
    let fecha_vencimiento = document.getElementById("fecha_vencimiento").value;


    if (codigo == "" || nombre == "" || detalle == "" || precio == "" || stock == "" || id_categoria == "" || fecha_vencimiento == "") {

        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Por favor, complete todos los campos requeridos',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    if (tipo == "nuevo") {
        registrarProducto();
    }
    if (tipo == "actualizar") {
        actualizarProducto();
    }
}

if (document.querySelector('#frm_product')) {
    //evita que se envie el formulario
    let frm_product = document.querySelector('#frm_product');
    frm_product.onsubmit = function (e) {
        e.preventDefault();
        validar_form("nuevo");
    }
}

//Registra el producto
async function registrarProducto() {
    try {
        const frm_product = document.querySelector("#frm_product");
        const datos = new FormData(frm_product);
        // Map id_categoria -> categoria
        if (datos.has('id_categoria') && !datos.has('categoria')) {
            datos.append('categoria', datos.get('id_categoria'));
        }
        // Map id_proveedor -> proveedor (opcional)
        if (datos.has('id_proveedor') && !datos.has('proveedor')) {
            datos.append('proveedor', datos.get('id_proveedor'));
        }
        if (!datos.has('imagen')) datos.append('imagen', '');
        let respuesta = await fetch(base_url + 'control/ProductsController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        if (json.status) {
            Swal.fire({
                icon: "success",
                title: "Éxito",
                text: json.msg
            }).then(() => {
                window.location.href = base_url + 'products-list';
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: json.msg
            });
        }
    } catch (error) {
        console.log("Error al registrar producto: " + error);
    }
}

function cancelar() {
    Swal.fire({
        icon: "warning",
        title: "¿Estás seguro?",
        text: "Se cancelará el registro",
        showCancelButton: true,
        confirmButtonText: "Sí, cancelar",
        cancelButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + "new-products";
        }
    });
}

//Ver producto
async function view_producto() {
    try {
        let respuesta = await fetch(base_url + 'control/ProductsController.php?tipo=ver_productos', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        let json = await respuesta.json();
        if (json && json.status && Array.isArray(json.data) && json.data.length > 0) {
            let html = '';
            json.data.forEach((producto, index) => {
                html += `<tr>
                    <td>${index + 1}</td>
                    <td>${producto.codigo || ''}</td>
                    <td>${producto.nombre || ''}</td>
                    <td>${producto.precio || ''}</td>
                    <td>${producto.stock || ''}</td>
                    <td>${producto.categoria_nombre || ''}</td>
                    <td>${producto.proveedor_nombre || ''}</td>
                    <td>${producto.fecha_vencimiento || ''}</td>
                    <td>
                        <a href="${base_url}edit-products/${producto.id}" class="btn btn-primary">Editar</a>
                        <button onclick="eliminar(${producto.id})" class="btn btn-danger">Eliminar</button>
                    </td>
                </tr>`;
            });
            document.getElementById('content_productos').innerHTML = html;
        } else {
            document.getElementById('content_productos').innerHTML = '<tr><td colspan="8">No hay productos disponibles</td></tr>';
        }
    } catch (error) {
        console.log(error);
        document.getElementById('content_productos').innerHTML = '<tr><td colspan="8">Error al cargar los productos</td></tr>';
    }
}

if (document.getElementById('content_productos')) {
    view_producto();
}

//Edita productos
async function edit_product() {
    try {
        let id_producto = document.getElementById('id_producto').value;
        const datos = new FormData();
        datos.append('id_producto', id_producto);

        let respuesta = await fetch(base_url + 'control/ProductsController.php?tipo=ver', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        json = await respuesta.json();
        if (!json.status) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: json.msg
            });
            return;
        }
        document.getElementById('codigo').value = json.data.codigo;
        document.getElementById('nombre').value = json.data.nombre;
        document.getElementById('detalle').value = json.data.detalle;
        document.getElementById('precio').value = json.data.precio;
        document.getElementById('stock').value = json.data.stock;
        if ('categoria' in json.data && json.data.categoria !== null) {
            document.getElementById('id_categoria').value = json.data.categoria;
        }
        if ('proveedor' in json.data && json.data.proveedor !== null) {
            document.getElementById('id_proveedor').value = json.data.proveedor;
        }
        document.getElementById('fecha_vencimiento').value = json.data.fecha_vencimiento;

    } catch (error) {
        console.log('oops, ocurrio un error' + error);
    }
}

if (document.querySelector("#frm_edit_producto")) {
    let frm_edit_producto = document.querySelector("#frm_edit_producto");
    frm_edit_producto.onsubmit = function (e) {
        e.preventDefault();
        validar_form("actualizar");
    }
}
//actualiza el producto
async function actualizarProducto() {
    const frm_edit_producto = document.querySelector("#frm_edit_producto")
    const datos = new FormData(frm_edit_producto);
    // Map id_categoria -> categoria
    if (datos.has('id_categoria') && !datos.has('categoria')) {
        datos.append('categoria', datos.get('id_categoria'));
    }
    // Map id_proveedor -> proveedor (opcional)
    if (datos.has('id_proveedor') && !datos.has('proveedor')) {
        datos.append('proveedor', datos.get('id_proveedor'));
    }
    if (!datos.has('imagen')) datos.append('imagen', '');
    let respuesta = await fetch(base_url + 'control/ProductsController.php?tipo=actualizar', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        body: datos
    });
    json = await respuesta.json();
    if (!json.status) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Ops, ocurrio un error al actualizar, contacte con el administrador",
        });
        console.log(json.msg);
        return;
    } else {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: json.msg
        });
    }
}
//Elimina el producto
async function eliminar(id) {
    Swal.fire({
        icon: "warning",
        title: "¿Estás seguro?",
        text: "Esta acción no se puede revertir",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "No, cancelar",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6"
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const datos = new FormData();
                datos.append('id_producto', id)
                let respuesta = await fetch(base_url + 'control/ProductsController.php?tipo=eliminar', {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    body: datos
                });
                json = await respuesta.json();
                if (json.status) {
                    Swal.fire({
                        icon: "success",
                        title: "Eliminado",
                        text: json.msg
                    }).then(() => {
                        view_producto();
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: json.msg
                    });
                }

            } catch (error) {
                console.log('oops, ocurrio un error' + error);
            }
        }
    });
}
function nuevoProducto() {
    // Redirige al formulario de registro de productos
    window.location.href = base_url + "new-products";
}
//carga las categorias con opciones
async function cargar_categorias() {
    try {
        const sel = document.getElementById('id_categoria');
        if (!sel) return;
        const respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        const json = await respuesta.json();
        let opciones = '<option value="" selected disabled>Seleccionar</option>';
        if (json && json.status && Array.isArray(json.data)) {
            json.data.forEach(categoria => {
                opciones += `<option value="${categoria.id}">${categoria.nombre}</option>`;
            });
        }
        sel.innerHTML = opciones;
    } catch (e) {
        console.log('Error cargando categorías', e);
    }
}

async function cargar_proveedores() {
    try {
        const sel = document.getElementById('id_proveedor');
        if (!sel) return;
        const respuesta = await fetch(base_url + 'control/ProductsController.php?tipo=obtener_proveedores', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        const json = await respuesta.json();
        let opciones = '<option value="" selected disabled>Seleccionar</option>';
        if (json && json.status && Array.isArray(json.data)) {
            json.data.forEach(proveedor => {
                opciones += `<option value="${proveedor.id}">${proveedor.nombre}</option>`;
            });
        }
        sel.innerHTML = opciones;
    } catch (e) {
        console.log('Error cargando proveedores', e);
    }
}