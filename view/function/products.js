

//VALIDAR FORMULARIO
function validar_form(tipo) {
    let codigo = document.getElementById("codigo").value;
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;
    let precio = document.getElementById("precio").value;
    let stock = document.getElementById("stock").value;
    let id_categoria = document.getElementById("id_categoria").value;
    let fecha_vencimiento = document.getElementById("fecha_vencimiento").value;
    //let imagen = document.getElementById("imagen").value;
    if (codigo == "" || nombre == "" || detalle == "" || precio == "" || stock == "" || id_categoria == "" || fecha_vencimiento == "") {
        Swal.fire({
            title: "Error campos vacios!",
            icon: "Error",
            draggable: true
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

//EVITA QUE SE ENVIE EL FORMULARIO
if (document.querySelector('#frm_product')) {
    // evita que se envie el formulario
    let frm_product = document.querySelector('#frm_product');
    frm_product.onsubmit = function (e) {
        e.preventDefault();
        validar_form("nuevo");
    }
}

//REGISTRAR PRODUCTO
async function registrarProducto() {
    try {
        //capturar campos de formulario (HTML)
        const datos = new FormData(frm_product);
        //enviar datos a controlador
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        // validamos que json.status sea = True
        if (json.status) { //true
            alert(json.msg);
            document.getElementById('frm_product').reset();
        } else {
            alert(json.msg);
        }
    } catch (e) {
        console.log("Error al registrar Producto:" + e);
    }
}

//VER PRODUCTOS
async function view_products() {
    try {
        console.log('Iniciando carga de productos...');
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=ver_productos', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        console.log('Respuesta recibida:', respuesta);
        json = await respuesta.json();
        console.log('Datos recibidos:', json);
        contenidot = document.getElementById('content_products');
        if (json.status) {
            let cont = 1;
            json.data.forEach(producto => {
                let nueva_fila = document.createElement("tr");
                nueva_fila.id = "fila" + producto.id;
                nueva_fila.className = "filas_tabla";
                nueva_fila.innerHTML = `
                            <td>${cont}</td>
                            <td>${producto.codigo}</td>
                            <td>${producto.nombre}</td>
                            <td>${producto.detalle}</td>
                            <td>${producto.precio}</td>
                            <td>${producto.stock}</td>
                            <td>${producto.categoria}</td>
                            <td>${producto.fecha_vencimiento}</td>
                            <td>
                                <a href="`+ base_url + `edit-producto/` + producto.id + `" 
                                class="btn btn-primary btn-sm rounded-pill">
                                <i class="bi bi-pencil-square"></i> Editar
                                </a>
                                <button class="btn btn-danger btn-sm rounded-pill ms-1"
                                 onclick="fn_eliminar(` + producto.id + `);" 
                                style="background:#dc3545;">
                                 <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </td>
                            `;
                cont++;
                contenidot.appendChild(nueva_fila);
            });
        }
    } catch (e) {
        console.log('error en mostrar producto ' + e);
    }
}
//llama a la funcion para ver productos si existe el contenedor
if (document.getElementById('content_products')) {
    view_products();
}



//EDITAR PRODUCTO
// Ahora acepta un id opcional: edit_product(id)
async function edit_product(id = null) {
    try {
        // Determinar id del producto: parámetro o campo oculto
        let id_producto = id;
        if (!id_producto) {
            const el = document.getElementById('id_producto');
            id_producto = el ? el.value : null;
        }

        if (!id_producto || isNaN(id_producto)) {
            console.warn('edit_product: id_producto no válido', id_producto);
            return;
        }

        const datos = new FormData();
        datos.append('id_producto', id_producto);

        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=ver', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        const json = await respuesta.json();
        if (!json.status) {
            alert(json.msg);
            return;
        }

        // Rellenar campos del formulario
        const data = json.data || {};
        const setIfExists = (idField, value) => {
            const field = document.getElementById(idField);
            if (field) field.value = value ?? '';
        };

        setIfExists('codigo', data.codigo);
        setIfExists('nombre', data.nombre);
        setIfExists('detalle', data.detalle);
        setIfExists('precio', data.precio);
        setIfExists('stock', data.stock);
        setIfExists('fecha_vencimiento', data.fecha_vencimiento);
        setIfExists('imagen_actual', data.imagen);

        // Los selects pueden no estar poblados todavía (cargar_categorias / cargar_proveedores
        // son llamadas desde la vista sin await). Reintentamos asignar el valor varias veces.
        const trySetSelect = (selectId, value, attempts = 6, delay = 150) => {
            if (!selectId) return;
            let i = 0;
            const iv = setInterval(() => {
                const sel = document.getElementById(selectId);
                if (sel) {
                    // si la opción existe, asignar y detener
                    const optionExists = Array.from(sel.options).some(o => o.value == value);
                    if (optionExists || sel.options.length > 0) {
                        sel.value = value ?? '';
                        clearInterval(iv);
                    }
                }
                i++;
                if (i >= attempts) clearInterval(iv);
            }, delay);
        };

        trySetSelect('id_categoria', data.id_categoria);
        trySetSelect('id_proveedor', data.id_proveedor);

    } catch (error) {
        console.log('oops, ocurrió un error ' + error);
    }
}

//ACTUALIZAR PRODUCTO
async function actualizarProducto() {
    const datos = new FormData(frm_edit_producto);
    let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=actualizar', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        body: datos
    });
    json = await respuesta.json();
    if (!json.status) {
        alert("Oooooops, ocurrio un error al actualizar, intentelo nuevamente");
        console.log(json.msg);
        return;
    } else {
        alert(json.msg);
    }
}

//funcion para eliminar producto
async function fn_eliminar(id) {
    if (window.confirm("¿Seguro que quiere eliminar?")) {
        eliminar(id);
    }
}

//ELIMINAR PRODUCTO
async function eliminar(id) {
    try {
        const datos = new FormData();
        datos.append('id_producto', id);

        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=eliminar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        const json = await respuesta.json();
        if (json.status) {
            // Si se eliminó correctamente, remover la fila de la tabla
            const fila = document.getElementById('fila' + id);
            if (fila) {
                fila.remove();
            }
            alert(json.msg);
        } else {
            alert(json.msg || 'Error al eliminar el producto');
        }
    } catch (error) {
        console.error('Error al eliminar producto:', error);
        alert('Error al intentar eliminar el producto');
    }
}

if (document.querySelector('#frm_edit_producto')) {
    // evita que se envie el formulario
    let frm_producto = document.querySelector('#frm_edit_producto');
    frm_producto.onsubmit = function (e) {
        e.preventDefault();
        validar_form("actualizar");
    }
}



async function cargar_categorias() {
    let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache'
    });
    let json = await respuesta.json();
    let contenido = '<option>Seleccione Categoria</option>';
    json.data.forEach(categoria => {
        contenido += '<option value="' + categoria.id + '">' + categoria.nombre + '</option>';
    });
    //console.log(contenido);
    document.getElementById("id_categoria").innerHTML = contenido;
}


//cargar proveedores en el select
async function cargar_proveedores() {
    let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_proveedores', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache'
    });
    let json = await respuesta.json();
    let contenido = '<option>Seleccione Proveedor</option>';
    json.data.forEach(proveedor => {
        contenido += '<option value="' + proveedor.id + '">' + proveedor.razon_social + '</option>';
    });
    //console.log(contenido);
    document.getElementById("id_proveedor").innerHTML = contenido;
}