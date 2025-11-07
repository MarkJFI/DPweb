

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




// Función para mostrar productos en tarjetas
async function view_products_cards() {
    try {
        console.log("Cargando productos en vista de cards...");
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=ver_productos', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });

        let json = await respuesta.json();
        console.log("Datos recibidos:", json);

        let contenido = document.getElementById('content_products');
        if (!contenido) {
            console.error("❌ No se encontró el contenedor #content_products");
            return;
        }

        contenido.innerHTML = '';

        if (json.status && json.data.length > 0) {
            let fila = document.createElement('div');
            fila.className = 'row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4';

            json.data.forEach(producto => {

                let rutaImagen;
                if (producto.imagen && producto.imagen.startsWith('data:image')) {
                    rutaImagen = producto.imagen;
                } else if (producto.imagen && producto.imagen.trim() !== "") {
                    rutaImagen = base_url + producto.imagen;
                } else {
                    rutaImagen = base_url + 'assets/img/no-image.png';
                }


                let col = document.createElement('div');
                col.className = 'col';
                col.setAttribute('data-producto-id', producto.id);

                col.innerHTML = `
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                       <img src="${rutaImagen}" 
             class="card-img-top img-fluid" 
             alt="${producto.nombre}" 
             style="height: 200px; object-fit: cover; transition: transform 0.3s ease;">
        
        <div class="card-body text-center">
            <h5 class="card-title text-primary fw-bold mb-2">${producto.nombre}</h5>
            <p class="card-text text-muted small mb-2">${producto.detalle}</p>
            <p class="fw-semibold text-success fs-6 mb-2">S/ ${parseFloat(producto.precio).toFixed(2)}</p>
            <span class="badge bg-secondary mb-2 px-3 py-2">Stock: ${producto.stock}</span>
            <p class="text-muted small mb-1"><i class="bi bi-tags"></i> Categoría: ${producto.categoria ?? '—'}</p>
            <p class="text-muted small mb-1"><i class="bi bi-truck"></i> Proveedor: ${producto.proveedor ?? '—'}</p>
            <p class="text-muted small mb-0"><i class="bi bi-calendar-event"></i> Fecha: ${producto.fecha_vencimiento ?? '—'}</p>
        </div>

        <div class="card-footer bg-light border-0 d-flex justify-content-center gap-2 pb-3">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="bi bi-eye"></i> Ver Detalles
            </button>
            <a href="${base_url}edit-producto/${producto.id}" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                <i class="bi bi-pencil-square"></i> Editar
            </a>
            <button class="btn btn-outline-success btn-sm rounded-pill px-3">
                <i class="bi bi-cart-plus"></i> Agregar al Carrito
            </button>
            <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="fn_eliminar(${producto.id})">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>
    </div>
`;

                fila.appendChild(col);
            });

            contenido.appendChild(fila);
        } else {
            contenido.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-box-seam display-4 text-muted"></i>
                    <h5 class="mt-3 text-muted">No hay productos disponibles</h5>
                </div>
            `;
        }
    } catch (error) {
        console.error("Error al mostrar productos en tarjetas:", error);
        let contenido = document.getElementById('content_products');
        if (contenido) {
            contenido.innerHTML = `
                <div class="alert alert-danger text-center" role="alert">
                    Error al cargar los productos. Intente nuevamente más tarde.
                </div>
            `;
        }
    }
}

if (document.getElementById('content_products')) {
    view_products_cards();
}


