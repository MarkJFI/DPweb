

//VALIDAR FORMULARIO
function validar_form(tipo) {
    // helper para obtener valores de forma segura (evita errores si el elemento no existe)
    const getVal = (id) => {
        const el = document.getElementById(id);
        return el ? el.value : '';
    };

    let codigo = getVal("codigo");
    let nombre = getVal("nombre");
    let detalle = getVal("detalle");
    let precio = getVal("precio");
    let stock = getVal("stock");
    let id_categoria = getVal("id_categoria");
    let codigo_barra = getVal("codigo_barra"); // puede no existir en la vista
    let fecha_vencimiento = getVal("fecha_vencimiento");
    //let imagen = document.getElementById("imagen").value;
    if (codigo == "" || nombre == "" || detalle == "" || precio == "" || stock == "" || id_categoria == "" || fecha_vencimiento == "") {
        showMessage("Error, campos vacíos", 'error');
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
// Adjuntar el listener de forma robusta
const _form_product = document.getElementById('frm_product');
if (_form_product) {
    _form_product.addEventListener('submit', function (e) {
        e.preventDefault();
        try {
            validar_form("nuevo");
        } catch (err) {
            // Mostrar información útil en consola y en la UI para depuración local
            console.error('Error en handler submit:', err);
            const msg = err && err.message ? 'Error interno: ' + err.message : 'Error interno al procesar el formulario';
            showMessage(msg, 'error');
        }
    });
}

// Helper: mostrar mensajes con fallback a alert si Swal no está disponible
function showMessage(message, type = 'info') {
    try {
        if (typeof Swal !== 'undefined') {
            const icon = (type === 'error') ? 'error' : (type === 'success' ? 'success' : 'info');
            Swal.fire({ title: message, icon: icon });
            return;
        }
    } catch (e) {
        console.warn('Swal showMessage fallo:', e);
    }
    // fallback
    if (type === 'error') alert(message);
    else alert(message);
}

//REGISTRAR PRODUCTO
async function registrarProducto() {
    try {
        //capturar campos de formulario (HTML)
        const formElem = document.getElementById('frm_product');
        if (!formElem) {
            console.error('Formulario #frm_product no encontrado');
            return;
        }
        const datos = new FormData(formElem);
        // DEBUG: mostrar contenido de FormData (no muestra contenido de archivos en algunos navegadores, pero permite verificar claves)
        try {
            console.groupCollapsed('FormData (registrarProducto)');
            for (const pair of datos.entries()) {
                if (pair[1] instanceof File) {
                    console.log(pair[0] + ': File ->', pair[1].name, pair[1].type, pair[1].size);
                } else {
                    console.log(pair[0] + ':', pair[1]);
                }
            }
            console.groupEnd();
        } catch (logErr) {
            console.warn('No se pudo listar FormData:', logErr);
        }
        //enviar datos a controlador
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        // Mejor manejo de respuesta: si no es OK, mostrar texto de respuesta para depuración
        if (!respuesta.ok) {
            const txt = await respuesta.text();
            console.error('Respuesta no OK:', respuesta.status, txt);
            alert('Error en la petición al servidor (ver consola)');
            return;
        }
        let text = await respuesta.text();
        // intentar parsear JSON con control de error
        let json;
        try {
            json = JSON.parse(text);
        } catch (parseErr) {
            console.error('No se pudo parsear JSON. Texto recibido:', text);
            alert('Respuesta del servidor no es JSON (revisar consola)');
            return;
        }

        console.log('Respuesta JSON registrarProducto:', json);
        // validamos que json.status sea = True
        if (json.status) { //true
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: json.msg, icon: 'success' }).then(() => {
                    document.getElementById('frm_product').reset();
                });
            } else {
                alert(json.msg);
                document.getElementById('frm_product').reset();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: json.msg || 'Error', icon: 'error' });
            } else {
                alert(json.msg || 'Error al registrar');
            }
        }
    } catch (e) {
        console.log("Error al registrar Producto:" + e);
    }
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
        setIfExists('codigo_barra', data.codigo_barra);
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

//ELIMINAR PRODUCTO
async function fn_eliminar(id) {
    if (window.confirm("¿Seguro que quiere eliminar?")) {
        eliminar(id);
    }
}
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


//CARGAR CATEGORIAS EN EL SELECT
async function cargar_categorias() {
    let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache'
    });

    let json = await respuesta.json();
    let contenido = '<option value="">Seleccione Categoria</option>';
    json.data.forEach(categoria => {
        contenido += '<option value="' + categoria.id + '">' + categoria.nombre + '</option>';
    });
    //console.log(contenido);
    document.getElementById("id_categoria").innerHTML = contenido;
}




//CARGAR PROVEEDORES EN EL SELECT
async function cargar_proveedores() {
    let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_proveedores', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache'
    });
    let json = await respuesta.json();
    let contenido = '<option value="">Seleccione Proveedor</option>';
    json.data.forEach(proveedor => {
        contenido += '<option value="' + proveedor.id + '">' + proveedor.razon_social + '</option>';
    });
    //console.log(contenido);
    document.getElementById("id_proveedor").innerHTML = contenido;
}




// FUNCION PARA VER PRODUCTOS EN LA VISTA DE TABLA
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
                            <td><svg id="barcode${producto.id}"></svg></td>
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
                JsBarcode("#barcode" + producto.id, "" + producto.codigo, {
                    width: 2,
                    height: 40,
                });
                //JsBarcode("#barcode" + producto.id, producto.codigo, {format: "CODE128", width: 2, height: 40});
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

