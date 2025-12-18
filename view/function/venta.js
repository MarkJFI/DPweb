let productos_venta = {}; // carrito temporal en memoria

// Función para cargar productos temporales desde BD
async function cargar_productos_temporales() {
    try {
        console.log('Cargando productos temporales desde BD...');
        const respuesta = await fetch(base_url + 'control/VentaController.php?tipo=buscarTemporal', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });

        const textoRespuesta = await respuesta.text();
        console.log('Respuesta raw de buscarTemporal:', textoRespuesta);

        let json;
        try {
            json = JSON.parse(textoRespuesta);
        } catch (parseError) {
            console.error('Error al parsear JSON de temporales:', parseError);
            console.error('Texto recibido:', textoRespuesta);
            return;
        }

        console.log('JSON parseado:', json);

        if (json && json.status && Array.isArray(json.data)) {
            productos_venta = {}; // limpiar carrito local
            console.log('Cargando', json.data.length, 'productos...');

            json.data.forEach((producto, index) => {
                const key = `prod_${producto.id_producto}`; // Usar la misma clave que al agregar
                productos_venta[key] = {
                    id_producto: producto.id_producto,
                    precio: producto.precio,
                    cantidad: parseInt(producto.cantidad),
                    nombre: producto.nombre || 'Producto'
                };
                console.log('Producto cargado:', key, productos_venta[key]);
            });

            console.log('Productos totales en carrito:', Object.keys(productos_venta).length);
            actualizar_tabla_ventas();
        } else {
            console.warn('Sin productos temporales o respuesta inválida');
            productos_venta = {};
            actualizar_tabla_ventas();
        }
    } catch (error) {
        console.error('Error al cargar productos temporales: ' + error);
    }
}

// Función para agregar producto al carrito temporal
function addToCart(producto, cantidad) {
    const id = producto.id;
    if (!id) return;

    const qty = parseInt(cantidad) || 1;
    const key = `prod_${id}`; // Usar una clave predecible

    if (productos_venta[key]) {
        productos_venta[key].cantidad += qty;
    } else {
        productos_venta[key] = {
            id_producto: id,
            nombre: producto.nombre,
            precio: parseFloat(producto.precio) || 0,
            cantidad: qty
        };
    }

    // Actualiza la UI y sincroniza con el servidor
    actualizar_tabla_ventas();
    agregar_producto_temporal(id, productos_venta[key].precio, productos_venta[key].cantidad, key);
}

async function agregar_producto_temporal(id_producto, precio, cantidad) {
    const datos = new FormData();
    datos.append('id_producto', id_producto);
    datos.append('precio', precio);
    datos.append('cantidad', cantidad);

    try {
        const respuesta = await fetch(base_url + 'control/VentaController.php?tipo=registrar_temporal', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        // No necesitamos procesar la respuesta si todo va bien,
        // pero sí manejar el fallo.
        if (!respuesta.ok) {
            // Intentar leer el cuerpo de la respuesta para obtener más detalles del error
            const errorText = await respuesta.text();
            throw new Error(`El servidor respondió con estado ${respuesta.status}. Respuesta: ${errorText}`); // Mostrar la respuesta del servidor
        }

        // Opcional: recargar desde el servidor para asegurar consistencia total.
        // await cargar_productos_temporales();

    } catch (error) {
        console.error('Error al agregar producto temporal: ' + error);
        // Revertir el cambio local si el servidor falla
        const key = `prod_${id_producto}`;
        if (productos_venta[key]) {
            productos_venta[key].cantidad -= cantidad; // Asumimos que la cantidad es el incremento
            if (productos_venta[key].cantidad <= 0) {
                delete productos_venta[key];
            }
        }
        actualizar_tabla_ventas(); // Refrescar la UI al estado anterior
        Swal.fire({
            title: 'Error de Comunicación',
            html: `<p>No se pudo sincronizar el producto con el servidor.</p>
                   <p class="small text-muted">Revise la consola del navegador (F12) para ver los detalles del error del servidor.</p><p>${error.message}</p>`, // Incluir el mensaje de error en el diálogo
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    }
}

// Función para actualizar la tabla visual del carrito
function actualizar_tabla_ventas() {
    const tabla = document.getElementById('tablaCarrito')?.querySelector('tbody') || document.getElementById('tablaCarrito');
    console.log('Buscando tabla con id "tablaCarrito":', tabla);

    if (!tabla) {
        console.error('No se encontró elemento #tablaCarrito en el DOM');
        return;
    }

    tabla.innerHTML = ''; // limpiar tabla
    let subtotal = 0;

    const keys = Object.keys(productos_venta);
    console.log('Renderizando', keys.length, 'filas en tabla');

    if (keys.length === 0) {
        const fila = document.createElement('tr');
        fila.innerHTML = '<td colspan="5" class="text-center text-muted py-3">No hay productos en la lista.</td>';
        tabla.appendChild(fila);
        actualizar_totales(0);
        return;
    }

    for (const key in productos_venta) {
        const p = productos_venta[key];
        const subtotalProducto = parseFloat(p.precio) * parseInt(p.cantidad);
        subtotal += subtotalProducto;

        const fila = document.createElement('tr');
        fila.id = 'fila_' + key;
        fila.className = 'text-center';
        const nombreConId = `${p.id_producto}`;
        const nombreProducto = p.nombre || `Producto ${p.id_producto}`;
        fila.innerHTML = `
            <td>${nombreProducto}</td>
            <td>
                <input type="number" class="form-control form-control-sm mx-auto" style="width: 70px;" value="${p.cantidad}" 
                    onchange="editar_cantidad_carrito('${key}', this.value)">
            </td>
            <td>S/ ${parseFloat(p.precio).toFixed(2)}</td>
            <td>S/ ${subtotalProducto.toFixed(2)}</td>
            <td>
                <button class="btn btn-sm btn-outline-danger" onclick="eliminar_item_carrito('${key}')" title="Eliminar">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tabla.appendChild(fila);
    }

    actualizar_totales(subtotal);
    console.log('Tabla actualizada. Subtotal:', subtotal.toFixed(2));
}

// Función para actualizar totales (subtotal, IGV, total)
function actualizar_totales(subtotal) {
    const igv = subtotal * 0.18;
    const totalGeneral = subtotal + igv;

    const subtotalElement = document.getElementById('subtotal');
    const igvElement = document.getElementById('igv');
    const totalElement = document.getElementById('totalGeneral');

    if (subtotalElement) subtotalElement.textContent = 'S/. ' + subtotal.toFixed(2);
    if (igvElement) igvElement.textContent = 'S/. ' + igv.toFixed(2);
    if (totalElement) totalElement.textContent = 'S/. ' + totalGeneral.toFixed(2);
}

// Funci��n para editar cantidad de un item (solo visual)
function editar_cantidad_carrito(key, nuevaCantidad) {
    const p = productos_venta[key];
    if (!p) return;

    nuevaCantidad = parseInt(nuevaCantidad);
    if (nuevaCantidad && nuevaCantidad > 0) {
        p.cantidad = nuevaCantidad;
        actualizar_tabla_ventas();
    }
}

// Función para eliminar un item del carrito (solo visual)
async function eliminar_item_carrito(key) {
    const producto = productos_venta[key];
    if (!producto) return;

    // Eliminar localmente primero para una UI rápida
    delete productos_venta[key];
    actualizar_tabla_ventas();

    try {
        const datos = new FormData();
        datos.append('id_producto', producto.id_producto);
        await fetch(base_url + 'control/VentaController.php?tipo=eliminar_temporal', {
            method: 'POST',
            body: datos
        });
        // Opcional: mostrar toast de éxito
        // showToast('Producto eliminado', 'error');
    } catch (error) {
        console.error('Error al eliminar en servidor:', error);
        // Si falla, revertir (volver a agregar el producto al carrito local)
        productos_venta[key] = producto;
        actualizar_tabla_ventas();
        Swal.fire('Error', 'No se pudo eliminar el producto del servidor.', 'error');
    }
}

// Inicializa los botones de "Agregar al carrito"
function inicializar_botones_carrito() {
    const botones = document.querySelectorAll('.btn-add-cart-table');

    botones.forEach(btn => {
        if (btn.dataset.carritoListenerAdded) return;

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const precio = this.getAttribute('data-precio');
            const stock = 999; // Asumimos stock disponible desde la tabla principal

            if (!id || !nombre || !precio) {
                console.error('Datos de producto incompletos en el botón.');
                return;
            }

            // Llama a la función global addToCart (de este mismo archivo)
            addToCart({ id, nombre, precio, stock }, 1);
            showToast(`${nombre} agregado al carrito.`, 'success');
        });

        btn.dataset.carritoListenerAdded = 'true';
    });
}

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('tablaCarrito')) { // Solo inicializar el carrito si la tabla existe
            console.log('DOM listo, inicializando carrito...');
            inicializar_botones_carrito();
            cargar_productos_temporales();
        }
    });
} else {
    if (document.getElementById('tablaCarrito')) { // Solo inicializar el carrito si la tabla existe
        console.log('DOM ya cargado, inicializando carrito...');
        inicializar_botones_carrito();
        cargar_productos_temporales();
    }
}

// Re-inicializar después de que se carguen nuevos productos dinámicamente
const originalViewProducts = window.view_products;
if (typeof originalViewProducts === 'function') {
    window.view_products = async function () {
        console.log('view_products llamado, cargando productos...');
        await originalViewProducts.call(this);
        console.log('Productos cargados, inicializando botones de carrito...');
        setTimeout(inicializar_botones_carrito, 100);
    };
}

// Registrar venta
async function registrarVenta() {
    const id_cliente = document.getElementById('id_cliente_venta')?.value || '';
    const fecha_hora = document.getElementById('fecha_venta')?.value || '';

    if (id_cliente === '' || fecha_hora === '') {
        Swal.fire({
            title: 'Datos incompletos',
            text: 'Seleccione un cliente y una fecha de venta',
            icon: 'warning'
        });
        return;
    }

    try {
        const datos = new FormData();
        datos.append('id_cliente', id_cliente);
        datos.append('fecha_hora', fecha_hora);

        const respuesta = await fetch(base_url + 'control/VentaController.php?tipo=registrar_venta', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        const json = await respuesta.json();
        if (json.status) {
            Swal.fire({
                title: 'Éxito',
                text: 'Venta registrada con éxito',
                icon: 'success'
            }).then(() => window.location.reload());
        } else {
            Swal.fire({
                title: 'Error',
                text: json.msg || 'Error al registrar la venta',
                icon: 'error'
            });
        }
    } catch (error) {
        console.log('error al registrar venta ' + error);
        Swal.fire({
            title: 'Error',
            text: 'No se pudo registrar la venta',
            icon: 'error'
        });
    }
}
    
