// Módulo unificado de Productos (registrar, listar, editar, eliminar)

// ===================== Utilidades =====================
function getVal(id) {
    const el = document.getElementById(id);
    return el ? el.value.trim() : '';
}

function hasEl(id) {
    return !!document.getElementById(id);
}

// ===================== Cargar categorías =====================
async function cargarCategorias() {
    try {
        const sel = document.getElementById('id_categoria');
        if (!sel) return;

        let resp = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias', {
            method: 'POST'
        });
        let categorias = await resp.json();

        sel.innerHTML = '<option value="" disabled selected>Seleccionar</option>';
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

// ===================== Validar formulario =====================
function validar_producto(tipo) {
    const nombre   = getVal('nombre');
    const detalle  = getVal('detalle');
    const precio   = getVal('precio');
    const stock    = getVal('stock');
    const categoria= getVal('id_categoria');
    const requiereProveedor = hasEl('id_proveedor');
    const proveedor = requiereProveedor ? getVal('id_proveedor') : '';

    if (!nombre || !detalle || !precio || !stock || !categoria || (requiereProveedor && !proveedor)) {
        alert('ERROR: Campos vacíos');
        return;
    }

    if (tipo === 'nuevo') registrarProducto();
    if (tipo === 'actualizar') actualizarProducto();
}

// ===================== Nuevo producto =====================
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
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=registrar', {
            method: 'POST',
            body: datos
        });
        let json = await respuesta.json();
        alert(json.msg);
        if (json.status) {
            frm_producto.reset();
            verProductos();
        }
    } catch (error) {
        console.log('Error al registrar producto:', error);
    }
}

// ===================== Listar productos =====================
async function verProductos() {
    try {
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=ver_productos', { method: 'POST' });
        let json = await respuesta.json();
        let content = document.getElementById('content_productos');
        if (!content) return;

        content.innerHTML = '';

        json.forEach((prod, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${prod.nombre ?? ''}</td>
                <td>${prod.detalle ?? ''}</td>
                <td>${prod.stock ?? ''}</td>
                <td>
                    <a href="${base_url}edit-producto/${prod.id}" class="btn btn-primary btn-sm rounded-pill">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <button data-id="${prod.id}" class="btn btn-eliminar btn-danger btn-sm rounded-pill">Eliminar</button>
                </td>
            `;
            content.appendChild(tr);
        });

        // Eliminar
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', async function () {
                if (confirm('¿Seguro de eliminar este producto?')) {
                    const datos = new FormData();
                    datos.append('id', this.getAttribute('data-id'));
                    let resp = await fetch(base_url + 'control/ProductoController.php?tipo=eliminar', {
                        method: 'POST',
                        body: datos
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

// Cargar lista automáticamente si existe la tabla
if (document.getElementById('content_productos')) verProductos();

// ===================== Editar producto (precargar datos) =====================
async function edit_product() {
    try {
        let id = getVal('id_producto');
        if (!id) return;
        const datos = new FormData();
        datos.append('id_producto', id);
        let resp = await fetch(base_url + 'control/ProductoController.php?tipo=ver', {
            method: 'POST',
            body: datos
        });
        let json = await resp.json();
        if (!json.status) { alert(json.msg || 'Producto no encontrado'); return; }
        let p = json.data;
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
        console.log('Error al cargar producto:', error);
    }
}

// ===================== Actualizar producto =====================
let frm_edit_producto = document.querySelector('#frm_edit_product');
if (frm_edit_producto) {
    frm_edit_producto.onsubmit = function (e) {
        e.preventDefault();
        validar_producto('actualizar');
    }
}

async function actualizarProducto() {
    try {
        const datos = new FormData(frm_edit_producto);
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=actualizar', {
            method: 'POST',
            body: datos
        });
        let json = await respuesta.json();
        alert(json.msg);
        if (json.status) verProductos();
    } catch (error) {
        console.log('Error al actualizar producto:', error);
    }
}

// ===================== Inicialización =====================
document.addEventListener('DOMContentLoaded', () => {
    if (hasEl('id_categoria')) cargarCategorias();
    if (hasEl('id_producto')) edit_product();
});
