async function view_products_cards() {
    try {
        let dato = document.getElementById('busquedaProducto').value;
        const datos = new FormData();
        datos.append('dato', dato);
        
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=buscar_producto_venta', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        console.log("Datos recibidos:", json);

        let contenido = document.getElementById('content_products');
        if (!contenido) {
            console.error("No se encontró el contenedor #content_products");
            return;
        }

        contenido.innerHTML = '';

        if (json.status && json.data.length > 0) {
            // Cambiar a 3 columnas en lg para tarjetas más anchas
            let fila = document.createElement('div');
            fila.className = 'row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 g-4';

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
                        style="height: 300px; width: 100%; object-fit: contain; transition: transform 0.3s ease;">
        
                        <div class="card-body text-center bg-light rounded-4 shadow-sm py-4">
                            <h5 class="card-title fw-bold mb-3 text-dark fs-4">
                                ${producto.nombre}
                            </h5>
                            <p class="card-text small text-secondary mb-3">
                                ${producto.detalle}
                            </p>
                            <p class="fw-semibold fs-5 text-dark mb-3">
                                S/ ${parseFloat(producto.precio).toFixed(2)}
                            </p>
                            <span class="badge bg-dark text-white mb-3 px-4 py-2 rounded-pill">
                                Stock: ${producto.stock}
                            </span>
                            <div class="border-top pt-3">
                                <p class="text-dark small mb-2">
                                    <i class="bi bi-tags me-1 text-secondary"></i>
                                    <strong>Categoría:</strong> ${producto.categoria ?? '—'}
                                </p>
                                <p class="text-dark small mb-2">
                                    <i class="bi bi-truck me-1 text-secondary"></i>
                                    <strong>Proveedor:</strong> ${producto.proveedor ?? '—'}
                                </p>
                                <p class="text-dark small mb-0">
                                    <i class="bi bi-calendar-event me-1 text-secondary"></i>
                                    <strong>Fecha:</strong> ${producto.fecha_vencimiento ?? '—'}
                                </p>
                            </div>
                        </div>

                        <div class="card-footer bg-light border-0 d-flex justify-content-center gap-2 pb-3">
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3 btn-ver-detalles"
                                data-producto-id="${producto.id}"
                                data-producto-nombre="${producto.nombre}">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </button>
            
                            <button class="btn btn-outline-success btn-sm rounded-pill px-3 btn-agregar-carrito"
                                data-producto-id="${producto.id}"
                                data-producto-nombre="${producto.nombre}"
                                data-producto-precio="${producto.precio}"
                                data-producto-stock="${producto.stock}">
                                <i class="bi bi-cart-plus"></i> Agregar al Carrito
                            </button>
                        </div>
                    </div>
                `;

                fila.appendChild(col);
            });

            contenido.appendChild(fila);

            // Agregar event listeners a los botones después de insertarlos en el DOM
            _inicializar_botones_tarjetas();
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

// Función para inicializar botones en las tarjetas
function _inicializar_botones_tarjetas() {
    // Botones "Agregar al Carrito"
    const botonesCarrito = document.querySelectorAll('.btn-agregar-carrito');
    botonesCarrito.forEach(btn => {
        btn.addEventListener('click', function() {
            const id_producto = this.getAttribute('data-producto-id');
            const nombre = this.getAttribute('data-producto-nombre');
            const precio = this.getAttribute('data-producto-precio');
            const cantidad = 1;

            console.log('Agregar carrito desde tarjeta:', { id_producto, nombre, precio, cantidad });
            
            // Llamar la función del carrito (desde venta.js)
            if (typeof agregar_producto_temporal === 'function') {
                agregar_producto_temporal(id_producto, precio, cantidad);
            } else {
                console.error('La función agregar_producto_temporal no está disponible');
                alert('Error: Módulo de carrito no disponible');
            }
        });
    });

    // Botones "Ver Detalles" (opcional)
    const botonesDetalles = document.querySelectorAll('.btn-ver-detalles');
    botonesDetalles.forEach(btn => {
        btn.addEventListener('click', function() {
            const id_producto = this.getAttribute('data-producto-id');
            const nombre = this.getAttribute('data-producto-nombre');
            console.log('Ver detalles del producto:', { id_producto, nombre });
            // Aquí puedes implementar la lógica para mostrar detalles
        });
    });
}

if (document.getElementById('content_products')) {
    view_products_cards();
}
