// ===================== UTILIDADES =====================
function getVal(id) {
    const el = document.getElementById(id);
    return el ? el.value.trim() : '';
}
function hasEl(id) {
    return !!document.getElementById(id);
}

// ===================== CARGAR CATEGORÍAS =====================
async function cargarCategorias() {
    try {
        const sel = document.getElementById('id_categoria');
        if (!sel) return;

        let resp = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias', {
            method: 'POST'
        });
        let categorias = await resp.json();

        sel.innerHTML = '<option value="" disabled selected>Seleccione una categoría</option>';
        if (Array.isArray(categorias)) {
            categorias.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.nombre;
                sel.appendChild(opt);
            });
        }
    } catch (e) {
        console.log('Error al cargar categorías:', e);
    }
}

// ===================== CARGAR PROVEEDORES =====================
// Proveedores: se usa el select `id_persona` en el formulario de producto (proveedores vienen del controlador de Usuario)
async function cargarProveedores() {
    try {
        const sel = document.getElementById('id_persona');
        if (!sel) return;

        let resp = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_proveedores', {
            method: 'POST'
        });
        let proveedores = await resp.json();

        sel.innerHTML = '<option value="" disabled selected>Seleccione un proveedor</option>';
        if (proveedores && proveedores.status && Array.isArray(proveedores.data)) {
            proveedores.data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.razon_social || p.nombre || p.email || ('Proveedor ' + p.id);
                sel.appendChild(opt);
            });
        } else if (Array.isArray(proveedores)) {
            // fallback si controlador devuelve array directamente
            proveedores.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.razon_social || p.nombre || ('Proveedor ' + p.id);
                sel.appendChild(opt);
            });
        }
    } catch (e) {
        console.log('Error al cargar proveedores:', e);
    }
}

// ===================== VALIDAR FORMULARIO =====================
function validar_producto(tipo) {
    const codigo   = getVal('codigo');
    const nombre   = getVal('nombre');
    const detalle  = getVal('detalle');
    const precio   = getVal('precio');
    const stock    = getVal('stock');
    const categoria= getVal('id_categoria');
    // El formulario usa `id_persona` para el proveedor
    const proveedor= hasEl('id_persona') ? getVal('id_persona') : '';

    if (!codigo || !nombre || !detalle || !precio || !stock || !categoria) {
        alert('ERROR: Complete todos los campos obligatorios');
        return;
    }

    if (tipo === 'nuevo') registrarProducto();
    if (tipo === 'actualizar') actualizarProducto();
}

// ===================== REGISTRAR PRODUCTO =====================
let frm_producto = document.querySelector('#frm_producto');
if (frm_producto) {
    frm_producto.onsubmit = function (e) {
        e.preventDefault();
        validar_producto('nuevo');
    }
}

async function registrarProducto() {
    try {
        const datos = new FormData(frm_producto);
        let resp = await fetch(base_url + 'control/ProductoController.php?tipo=registrar', {
            method: 'POST',
            body: datos
        });
        let text = await resp.text();
        let json;
        try {
            json = JSON.parse(text);
        } catch (e) {
            alert('Respuesta inválida del servidor al registrar producto. Revisa la consola y el log de PHP.');
            console.error('Respuesta cruda registrarProducto:', text);
            return;
        }
        alert(json.msg);
        if (json.status) {
            frm_producto.reset();
            verProductos();
        }
    } catch (error) {
        console.log('Error al registrar producto:', error);
    }
}

// ===================== LISTAR PRODUCTOS =====================
async function verProductos() {
    try {
        let resp = await fetch(base_url + 'control/ProductoController.php?tipo=ver_products', { method: 'POST' });
        let productos = await resp.json();
        const content = document.getElementById('content_productos');
        if (!content) return;

        content.innerHTML = '';
        productos.forEach((prod, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${prod.codigo ?? ''}</td>
                <td>${prod.nombre ?? ''}</td>
                <td>${prod.detalle ?? ''}</td>
                <td>${prod.precio ?? ''}</td>
                <td>${prod.stock ?? ''}</td>
                <td>${prod.categoria ?? ''}</td>
                <td>${prod.fecha_vencimiento ?? ''}</td>
                <td>
                    <a href="${base_url}edit-producto/${prod.id}" class="btn btn-primary btn-sm rounded-pill">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <button data-id="${prod.id}" class="btn btn-eliminar btn-danger btn-sm rounded-pill">Eliminar</button>
                </td>
            `;
            content.appendChild(tr);
        });

        // Botones de eliminar
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', async function () {
                if (confirm('\u00bfSeguro de eliminar este producto?')) {
                    let id = this.getAttribute('data-id');
                    let resp = await fetch(base_url + 'control/ProductoController.php?tipo=eliminar', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'id=' + encodeURIComponent(id)
                    });
                    let json = await resp.json();
                    alert(json.msg);
                    if (json.status) verProductos();
                }
            });
        });

    } catch (error) {
        console.log('Error al listar productos:', error);
    }
}

// ===================== OBTENER PRODUCTO POR ID =====================
async function obtenerProductoPorId(id) {
    try {
        let resp = await fetch(base_url + 'control/ProductoController.php?tipo=ver', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_producto=' + encodeURIComponent(id)
        });
        let json = await resp.json();
        if (!json || !json.data) {
            alert('Producto no encontrado');
            return;
        }
        let p = json.data;
        if (hasEl('id_producto')) document.getElementById('id_producto').value = p.id;
        if (hasEl('codigo')) document.getElementById('codigo').value = p.codigo || '';
        if (hasEl('nombre')) document.getElementById('nombre').value = p.nombre || '';
        if (hasEl('detalle')) document.getElementById('detalle').value = p.detalle || '';
        if (hasEl('precio')) document.getElementById('precio').value = p.precio || '';
        if (hasEl('stock')) document.getElementById('stock').value = p.stock || '';
        if (hasEl('id_categoria')) document.getElementById('id_categoria').value = p.id_categoria || '';
        if (hasEl('id_proveedor')) document.getElementById('id_proveedor').value = p.id_proveedor || '';
        if (hasEl('fecha_vencimiento')) document.getElementById('fecha_vencimiento').value = p.fecha_vencimiento || '';
        if (hasEl('imagen_actual')) document.getElementById('imagen_actual').value = p.imagen || '';
    } catch (error) {
        console.log('Error al obtener producto:', error);
    }
}

// Alias para compatibilidad con la vista de editar
function edit_product(id) {
    if (!id) return;
    obtenerProductoPorId(id);
}

// ===================== ACTUALIZAR PRODUCTO =====================
let frm_edit_producto = document.querySelector('#frm_edit_producto');
if (frm_edit_producto) {
    frm_edit_producto.onsubmit = function (e) {
        e.preventDefault();
        validar_producto('actualizar');
    }
}

async function actualizarProducto() {
    try {
        const datos = new FormData(frm_edit_producto);
        let resp = await fetch(base_url + 'control/ProductoController.php?tipo=actualizar', {
            method: 'POST',
            body: datos
        });
        let text = await resp.text();
        let json;
        try {
            json = JSON.parse(text);
        } catch (e) {
            alert('Respuesta inválida del servidor al actualizar producto. Revisa la consola y el log de PHP.');
            console.error('Respuesta cruda actualizarProducto:', text);
            return;
        }
        alert(json.msg);
        if (json.status) {
            window.location.href = base_url + "producto"; // redirige a la lista
        }
    } catch (error) {
        console.log('Error al actualizar producto:', error);
    }
}

// ===================== AUTOLOAD =====================
document.addEventListener('DOMContentLoaded', () => {
    if (hasEl('content_productos')) verProductos();
    if (hasEl('id_categoria')) cargarCategorias();
    if (hasEl('id_proveedor')) cargarProveedores();

    // Precargar en editar
    if (hasEl('id_producto')) {
        let partes = window.location.pathname.split('/');
        let id = partes[partes.length - 1];
        if (!isNaN(id)) obtenerProductoPorId(id);
    }
});

// cargar proveedor 
async function cargarProveedores() {
    try {
        const sel = document.getElementById('id_persona'); // Usar id_persona en lugar de id_proveedor
        if (!sel) {
            console.log('Elemento con id="id_persona" no encontrado');
            return;
        }

        let resp = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_proveedores', {
            method: 'POST'
        });
        let proveedores = await resp.json();

        sel.innerHTML = '<option value="" disabled selected>Seleccione un proveedor</option>';
        if (proveedores.status && Array.isArray(proveedores.data)) {
            proveedores.data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.razon_social;
                sel.appendChild(opt);
            });
        } else {
            console.log('No se encontraron proveedores o la respuesta no es válida:', proveedores);
        }
    } catch (e) {
        console.log('Error al cargar proveedores:', e);
    }
}
