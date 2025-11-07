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
                const defaultImg = base_url + 'view/img/imagen.avif';
                const imgSrc = producto.imagen ? (base_url + producto.imagen) : defaultImg;
                html += `<div class="col-12 col-md-4 mb-4">
                    <div class="card h-100 d-flex flex-column text-center shadow-sm">
                        <div class="card-img-container" style="height:300px;padding:1rem;display:flex;align-items:center;justify-content:center;background:#fff;">
                            <img src="${imgSrc}" class="card-img-top" alt="${producto.nombre || ''}" 
                                style="max-width:100%;max-height:100%;width:auto;height:auto;object-fit:contain;" 
                                onerror="this.onerror=null;this.src='${defaultImg}'">
                        </div>
                        <div class="card-body d-flex flex-column align-items-center">
                            <h5 class="card-title text-primary fw-bold">${producto.nombre || ''}</h5>
                            <div class="text-muted mb-2">${producto.detalle || ''}</div>
                            <div class="fw-bold text-success mb-2">S/ ${producto.precio || ''}</div>
                            <div class="small text-muted">Categoría: ${producto.categoria_nombre || ''}</div>
                            <div class="small text-muted">Proveedor: ${producto.proveedor_nombre || ''}</div>
                            <div class="small text-muted">Vence: ${producto.fecha_vencimiento || ''}</div>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="${base_url}edit-products/${producto.id}" class="btn btn-primary btn-sm">Editar</a>
                            <button onclick="eliminar(${producto.id})" class="btn btn-danger btn-sm">Eliminar</button>
                        </div>
                    </div>
                </div>`;
            });
            document.getElementById('content_productos').innerHTML = html;
        } else {
            document.getElementById('content_productos').innerHTML = '<div class="col-12"><p>No hay productos disponibles</p></div>';
        }
    } catch (error) {
        console.log(error);
        document.getElementById('content_productos').innerHTML = '<div class="col-12"><p>Error al cargar los productos</p></div>';
    }
}

if (document.getElementById('content_productos')) {
    view_producto();
}

// Render en cards para products-list
async function render_cards_productos() {
    const grid = document.getElementById('products_grid');
    if (!grid) return;

    // Guard global para evitar re-ejecuciones si el script se incluye 2 veces
    if (window.__PRODUCTS_LIST_RENDERED) return;
    window.__PRODUCTS_LIST_RENDERED = true;

    // Evitar ejecuciones simultáneas o repetidas por eventos
    if (grid.dataset.loading === '1') return;
    grid.dataset.loading = '1';

    try {
        const resp = await fetch(base_url + 'control/ProductsController.php?tipo=ver_productos', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        const raw = await resp.text();
        if (!resp.ok) {
            console.error('HTTP', resp.status, raw);
            grid.innerHTML = '<div class="col-12"><div class="alert alert-danger">Error al cargar productos (' + resp.status + ')</div></div>';
            return;
        }
        let json;
        try { json = JSON.parse(raw); } catch (e) {
            console.error('Respuesta no-JSON', raw);
            grid.innerHTML = '<div class="col-12"><div class="alert alert-warning">Respuesta inválida del servidor</div></div>';
            return;
        }
        let html = '';
        if (!json.status || !Array.isArray(json.data) || json.data.length === 0) {
            html = '<div class="col-12"><div class="alert alert-info">No hay productos disponibles</div></div>';
        } else {
            html = json.data.map(p => {
                const defaultImg = base_url + 'view/img/imagen.avif';
                const img = p.imagen ? (base_url + p.imagen) : defaultImg;
                const precio = (p.precio !== undefined && p.precio !== null) ? Number(p.precio).toFixed(2) : '0.00';
                const detalle = p.detalle || p.estado || '';
                return `
                                            <div class="col-12 col-md-4 mb-4">
                                                <div class="card h-100 d-flex flex-column text-center shadow-sm">
                                                    <div class="card-img-container" style="height:380px;padding:1.5rem;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border-bottom:1px solid rgba(0,0,0,0.1);">
                                                        <img src="${img}" class="card-img-top" alt="${p.nombre || ''}" 
                                                            style="max-width:100%;max-height:100%;width:auto;height:auto;object-fit:cover;" 
                                                            onerror="this.onerror=null;this.src='${defaultImg}'">
                                                    </div>
                                                    <div class="card-body d-flex flex-column" style="min-height:220px;">
                                                        <h5 class="card-title text-primary fw-bold mb-3">${p.nombre || ''}</h5>
                                                        <div class="text-muted mb-2">${detalle}</div>
                                                        <div class="fw-bold text-success mb-3 fs-4">S/ ${precio}</div>
                                                        <div class="small text-muted mb-1">Categoría: ${p.categoria_nombre || ''}</div>
                                                        <div class="small text-muted mb-1">Proveedor: ${p.proveedor_nombre || ''}</div>
                                                        <div class="small text-muted mb-3">Vence: ${p.fecha_vencimiento || ''}</div>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top-0 d-flex justify-content-center gap-3 py-3">
                                                        <a href="${base_url}edit-products/${p.id}" class="btn btn-outline-primary">
                                                            <i class="fas fa-edit me-1"></i> Editar
                                                        </a>
                                                        <button onclick="eliminar(${p.id})" class="btn btn-outline-danger">
                                                            <i class="fas fa-trash me-1"></i> Eliminar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>`;
            }).join('');
        }
        // Asignar de una sola vez para minimizar repintados
        grid.innerHTML = html;
    } catch (err) {
        console.error('Error cargando productos:', err);
        grid.innerHTML = '<div class="col-12"><div class="alert alert-danger">No se pudo cargar la lista de productos</div></div>';
    } finally {
        grid.dataset.loading = '0';
    }
}

if (document.getElementById('products_grid')) {
    render_cards_productos();
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

// Configurar el evento submit del formulario de edición
function setupEditForm() {
    const frm_edit_producto = document.querySelector("#frm_edit_producto");
    if (!frm_edit_producto) return;

    console.log('Configurando formulario de edición');
    
    frm_edit_producto.onsubmit = function(e) {
        e.preventDefault();
        console.log('Formulario enviado - validando...');
        validar_form("actualizar");
    };
}

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupEditForm);
} else {
    setupEditForm();
}
//actualiza el producto
async function actualizarProducto() {
    try {
        // Verificar que tenemos el ID del producto
        const id_producto = document.getElementById('id_producto').value;
        if (!id_producto) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo identificar el producto a actualizar"
            });
            return;
        }

        const frm_edit_producto = document.querySelector("#frm_edit_producto");
        if (!frm_edit_producto) {
            console.error("Formulario no encontrado");
            return;
        }

        const datos = new FormData(frm_edit_producto);
        
        // Asegurar que tenemos el ID en el FormData
        if (!datos.has('id_producto')) {
            datos.append('id_producto', id_producto);
        }

        // Map id_categoria -> categoria
        if (datos.has('id_categoria') && !datos.has('categoria')) {
            datos.append('categoria', datos.get('id_categoria'));
        }
        
        // Map id_proveedor -> proveedor (opcional)
        if (datos.has('id_proveedor') && !datos.has('proveedor')) {
            datos.append('proveedor', datos.get('id_proveedor'));
        }

        // Manejar imagen
        if (!datos.has('imagen')) {
            datos.append('imagen', '');
        }

        console.log('Enviando actualización para producto ID:', id_producto);
        
        const respuesta = await fetch(base_url + 'control/ProductsController.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        if (!respuesta.ok) {
            throw new Error(`Error HTTP: ${respuesta.status}`);
        }

        const json = await respuesta.json();
        console.log('Respuesta del servidor:', json);

        if (!json.status) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: json.msg || "Ocurrió un error al actualizar el producto"
            });
            return;
        }

        await Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: json.msg || 'Producto actualizado correctamente'
        });

        // Redireccionar solo después de que el usuario vea el mensaje de éxito
        window.location.href = base_url + 'products-list';
    } catch (error) {
        console.error('Error al actualizar:', error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un problema al actualizar el producto. Por favor, intente nuevamente."
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