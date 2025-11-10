//prueba 1
let productos_venta = {};
let id = 2;
let id2 = 4;

let producto = {};
    producto.nombre = "Producto A";
    producto.precio = 100;
    producto.cantidad = 2;

let producto2 = {};
    producto2.nombre = "Producto B";
    producto2.precio = 50;
    producto2.cantidad = 1;
    //productos_venta.push(producto);

productos_venta[id] = producto;
productos_venta[id2] = producto2;
console.log(productos_venta);

//splice remueve elementos, inserta nuevo elemento
productos_venta.splice(id,1);
console.log(productos_venta);

// Función para agregar producto al carrito

function agregarAlCarrito(id, nombre, precio) {
    // Aquí puedes implementar la lógica para agregar al carrito
    Swal.fire({
        title: 'Producto agregado',
        text: `${nombre} ha sido agregado al carrito`,
        icon: 'success',
        timer: 1500
    });
}

// Función para ver detalles del producto
function verDetalles(id) {
    // Aquí puedes implementar la lógica para mostrar detalles adicionales
    Swal.fire({
        title: 'Detalles del Producto',
        text: 'Funcionalidad de detalles próximamente',
        icon: 'info'
    });
}

// Cargar productos para ventas si estamos en la página de ventas
if (document.getElementById('productos_venta')) {
    cargarProductosVentas();
}

// Función para cargar productos en la vista de ventas
async function cargarProductosVentas() {
    try {
        let respuesta = await fetch(base_url + 'control/ProductsController.php?tipo=ver_productos', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        let json = await respuesta.json();
        if (json && json.status && Array.isArray(json.data) && json.data.length > 0) {
            let html = '';
            json.data.forEach((producto) => {
                let imagenSrc = producto.imagen ? base_url + 'assets/images/' + producto.imagen : 'https://via.placeholder.com/200x150?text=Sin+Imagen';
                html += `
                    <div class="card m-2" style="width: 18rem;">
                        <img src="${imagenSrc}" class="card-img-top" alt="${producto.nombre}" style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">${producto.nombre}</h5>
                            <p class="card-text">
                                <strong>Precio:</strong> $${producto.precio}<br>
                                <strong>Detalle:</strong> ${producto.detalle}<br>
                                <strong>Categoría:</strong> ${producto.categoria_nombre || 'N/A'}<br>
                                <strong>Proveedor:</strong> ${producto.proveedor_nombre || 'N/A'}
                            </p>
                            <button class="btn btn-info btn-sm me-2" onclick="verDetalles(${producto.id})">Detalles</button>
                            <button class="btn btn-success btn-sm" onclick="agregarAlCarrito(${producto.id}, '${producto.nombre}', ${producto.precio})">Añadir al carrito</button>
                        </div>
                    </div>
                `;
            });
            document.getElementById('productos_venta').innerHTML = html;
        } else {
            document.getElementById('productos_venta').innerHTML = '<p class="text-center">No hay productos disponibles</p>';
        }
    } catch (error) {
        console.log("Error al cargar productos para ventas: " + error);
        document.getElementById('productos_venta').innerHTML = '<p class="text-center">Error al cargar los productos</p>';
    }
}
