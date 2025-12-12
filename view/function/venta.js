let productos_venta = {}; // carrito temporal en memoria
let id_contador = 1; // contador para IDs únicos de elementos en el carrito


// Función para cargar productos temporales desde BD
async function cargar_productos_temporales() {
    try {
        console.log('Cargando productos temporales desde BD...');
        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=buscarTemporal', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });

        let textoRespuesta = await respuesta.text();
        console.log('Respuesta raw de buscarTemporal:', textoRespuesta);

let producto = {};
producto.nombre = "Producto A";
producto.precio = 100;
producto.cantidad = 2;

let producto2 = {};
producto2.nombre = "Producto B";
producto2.precio = 200;
producto2.cantidad = 1;
//productos_venta.push(producto);


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
                const key = producto.id_producto + '_' + index;
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
        console.error("Error al cargar productos temporales: " + error);
    }
}

// Función para agregar producto al carrito temporal
async function agregar_producto_temporal(id_producto, precio, cantidad) {
    const datos = new FormData();
    datos.append('id_producto', id_producto);
    datos.append('precio', precio);
    datos.append('cantidad', cantidad);

    try {
        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=registrarTemporal', {

//splice remueve elementos, inserta nuevo elemento
/*productos_venta.splice(id,1);
console.log(productos_venta);*/


//agregar producto
async function agregar_producto_temporal() {
    let id = document.getElementById('id_producto_venta').value;
    let precio = document.getElementById('producto_precio_venta').value;
    let cantidad = document.getElementById('producto_cantidad_venta').value;
    const datos = new FormData();
    datos.append('id_producto', id);
    datos.append('precio', precio);
    datos.append('cantidad', cantidad);
    try {
        let respuesta = await fetch(base_url + 'control/ventaController.php?tipo=registrar_temporal', {

            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });


        // Obtener texto de respuesta para validar si es JSON
        let textoRespuesta = await respuesta.text();
        console.log('Respuesta del servidor (raw):', textoRespuesta);

        // Intentar parsear como JSON
        let json;
        try {
            json = JSON.parse(textoRespuesta);
        } catch (parseError) {
            console.error('Error al parsear JSON:', parseError);
            console.error('Respuesta recibida:', textoRespuesta);
            
            // Detectar si es un error PHP
            if (textoRespuesta.includes('Parse error') || textoRespuesta.includes('Fatal error')) {
                Swal.fire({
                    title: "Error en el servidor",
                    html: `<p>Hay un error PHP en el servidor:</p><pre style="text-align:left; background:#f5f5f5; padding:10px; border-radius:5px; font-size:12px; max-height:300px; overflow-y:auto;">${textoRespuesta}</pre>`,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            } else {
                Swal.fire({
                    title: "Error del servidor",
                    text: "El servidor retornó una respuesta inválida. Verifique los logs del servidor.",
                    icon: "error"
                });
            }
            return;
        }

        if (json && json.status) {
            // Cargar productos temporales desde BD para sincronizar
            await cargar_productos_temporales();

            Swal.fire({
                title: "Éxito",
                text: json.msg === "registrado" ? "Producto agregado al carrito" : "Cantidad actualizada",
                icon: "success",
                timer: 1500
            });
        } else {
            Swal.fire({
                title: "Error",
                text: json ? json.msg : "Error desconocido",
                icon: "error"
            });
        }
    } catch (error) {
        console.error("Error al agregar producto temporal: " + error);
        Swal.fire({
            title: "Error",
            text: "No se pudo agregar el producto",
            icon: "error"
        });
    }
}

// Función para actualizar la tabla visual del carrito
function actualizar_tabla_ventas() {
    const tabla = document.getElementById('tablaCarrito');
    console.log('Buscando tabla con id "tablaCarrito":', tabla);
    
    if (!tabla) {
        console.error('No se encontró elemento #tablaCarrito en el DOM');
        return;
    }

    tabla.innerHTML = ''; // limpiar tabla
    let subtotal = 0;
    let fila_count = 1;

    const keys = Object.keys(productos_venta);
    console.log('Renderizando', keys.length, 'filas en tabla');

    if (keys.length === 0) {
        // Si no hay productos, mostrar mensaje
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
        // Mostrar SOLO el ID del producto
        const nombreConId = `${p.id_producto}`;
        fila.innerHTML = `
            <td>${nombreConId}</td>
            <td>
                <input type="number" class="form-control form-control-sm" value="${p.cantidad}" 
                    onchange="editar_cantidad_carrito('${key}', this.value)">
            </td>
            <td>S/ ${parseFloat(p.precio).toFixed(2)}</td>
            <td>S/ ${subtotalProducto.toFixed(2)}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="eliminar_item_carrito('${key}')">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tabla.appendChild(fila);
        fila_count++;
    }

    // actualizar totales
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

    if (subtotalElement) subtotalElement.textContent = 'S/ ' + subtotal.toFixed(2);
    if (igvElement) igvElement.textContent = 'S/ ' + igv.toFixed(2);
    if (totalElement) totalElement.textContent = 'S/ ' + totalGeneral.toFixed(2);
}

// Función para editar cantidad de un item
function editar_cantidad_carrito(key, nuevaCantidad) {
    const p = productos_venta[key];
    if (!p) return;

    nuevaCantidad = parseInt(nuevaCantidad);
    if (nuevaCantidad && nuevaCantidad > 0) {
        p.cantidad = nuevaCantidad;
        actualizar_tabla_ventas();
    }
}

// Función para eliminar un item del carrito
function eliminar_item_carrito(key) {
    if (window.confirm('¿Eliminar este producto del carrito?')) {
        delete productos_venta[key];
        const fila = document.getElementById('fila_' + key);
        if (fila) fila.remove();
        actualizar_tabla_ventas();
    }
}

// Función mejorada para inicializar botones "Agregar al carrito"
function inicializar_botones_carrito() {
    // Selector directo: buscar todos los botones "Agregar" en filas de tabla de productos
    const botones = document.querySelectorAll('table tbody tr td:last-child a[href*="edit-producto"], table tbody tr td:last-child button');
    
    botones.forEach(btn => {
        // Evitar agregar múltiples listeners al mismo botón
        if (btn.dataset.carritoListenerAdded) return;

        // Detectar si es el botón "Eliminar" o un enlace "Editar"
        if (btn.textContent.toLowerCase().includes('eliminar') || 
            btn.href || 
            btn.classList.contains('btn-primary')) {
            return;
        }

        // Agregar listener solo a botones "Agregar" o "Carrito"
        if (btn.textContent.toLowerCase().includes('agregar') || 
            btn.textContent.toLowerCase().includes('carrito') ||
            btn.classList.contains('btn-success') ||
            btn.classList.contains('btn-info')) {
            
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Obtener la fila más cercana
                const fila = this.closest('tr');
                if (!fila) {
                    console.warn('No se encontró fila para el botón');
                    return;
                }

                // Estructura esperada de la tabla de productos:
                // [0]=nro, [1]=código, [2]=nombre, [3]=detalle, [4]=precio, [5]=stock, [6]=categoría, [7]=fecha, [8]=barcode, [9]=acciones
                const celdas = fila.querySelectorAll('td');
                
                const id_producto = fila.id ? fila.id.replace('fila', '') : celdas[1]?.textContent;
                const nombre = celdas[2]?.textContent?.trim() || 'Producto';
                const precio = celdas[4]?.textContent?.replace(/[^\d.]/g, '') || '0';
                const cantidad = 1;

                // Validar datos
                if (!id_producto || id_producto === '' || precio === '0') {
                    console.warn('Datos incompletos:', { id_producto, nombre, precio });
                    Swal.fire({
                        title: "Error",
                        text: "No se pueden obtener los datos del producto",
                        icon: "error"
                    });
                    return;
                }

                console.log('Agregando al carrito:', { id_producto, nombre, precio, cantidad });
                agregar_producto_temporal(id_producto, precio, cantidad);
            });

            btn.dataset.carritoListenerAdded = 'true';
        }
    });
}

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM listo, inicializando carrito...');
        inicializar_botones_carrito();
        cargar_productos_temporales(); // cargar productos al abrir la página
    });
} else {
    console.log('DOM ya cargado, inicializando carrito...');
    inicializar_botones_carrito();
    cargar_productos_temporales();
}

// Re-inicializar después de que se carguen nuevos productos dinámicamente
// Interceptar la función view_products para re-inicializar listeners después de cargar
const originalViewProducts = window.view_products;
if (typeof originalViewProducts === 'function') {
    window.view_products = async function () {
        console.log('view_products llamado, cargando productos...');
        await originalViewProducts.call(this);
        console.log('Productos cargados, inicializando botones de carrito...');
        setTimeout(inicializar_botones_carrito, 100); // delay para asegurar que el DOM esté actualizado
    };
}
async function listar_temporales() {
    try {
        
    } catch (error) {
        console.log("error al cargar productos temporales" + error);
        
    }

    

        json = await respuesta.json();
        if (json.status) {
            // No mostrar alert nativo. Registrar en consola para depuración.
            console.log('agregar_producto_temporal:', json.msg);
        }

    } catch (error) {
        console.log("error" + error)
    }




}