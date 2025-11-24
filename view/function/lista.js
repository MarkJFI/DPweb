async function view_products_cards() {
    try {
        let dato = document.getElementById('busquedaProducto').value;
        const datos = new FormData();
        datos.append('dato', dato);
        //console.log("Cargando productos en vista de cards...");
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
            console.error(" No se encontró el contenedor #content_products");
            return;
        }
        let cont =1;
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
                        style="height: 300px; width: 900px; object-fit: cover; transition: transform 0.3s ease;">
        
                    <div class="card-body text-center bg-light rounded-4 shadow-sm py-4">
                        <h5 class="card-title fw-bold mb-3 text-dark">
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
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-eye"></i> Ver Detalles
                            </button>
            
                            <button class="btn btn-outline-success btn-sm rounded-pill px-3">
                            <i class="bi bi-cart-plus"></i> Agregar al Carrito
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
