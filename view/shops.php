<!--async function viewMisProducts() {
    try {
        let respuesta = await fetch(base_url + 'control/productosController.php?tipo=mostrarMisProductos', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });
        if (!respuesta.ok) throw new Error(`HTTP error! status: ${respuesta.status}`);
        let json = await respuesta.json();

        let html = '';
        if (json.status && json.data && json.data.length > 0) {
            json.data.forEach(producto => {
                // Ajusta campo 'imagen' al nombre real que devuelve tu API
                let imgSrc = producto.imagen ? (base_url + producto.imagen) : (base_url + 'uploads/productos/no-image.png');

                html += `
                    <div class="col-6 col-sm-4 col-md-3">
                         <div class="card mb-3 product-card">
                             <img src="${imgSrc}" class="card-img-top" alt="${producto.nombre || ''}" style="height:140px;object-fit:cover;">
                                <div class="card-body p-2">
                                    <p class="mb-1 small text-truncate">${producto.nombre || ''}</p>
                                    <p class="mb-1"><strong>Precio:</strong> ${producto.precio || '0'}</p>
                                    <p class="mb-0 small text-muted">Categoría: ${producto.categoria || 'Sin categoría'}</p>
                                        <div class="d-flex justify-content-between mt-2">
                                            <button class="btn btn-success btn-sm" onclick="agregarAlCarrito(${producto.id})">
                                                <i class="fas fa-shopping-cart"></i> Agregar al carrito
                                            </button>
                                                <button class="btn btn-primary btn-sm" onclick="verDetalles(${producto.id})">
                                                  <i class="fas fa-eye"></i> Ver detalles
                                                </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            html = '<div class="col-12"><div class="alert alert-info mb-0">No hay productos disponibles</div></div>';
        }
        const container = document.getElementById('productos_grid');
        if (container) container.innerHTML = html;
    } catch (error) {
        console.error("Error al cargar productos :", error);
        const container = document.getElementById('productos_grid');
        if (container) container.innerHTML = '<div class="col-12"><div class="alert alert-danger mb-0">Error al cargar los productos</div></div>';
    }
}

if (document.getElementById('productos_grid')) {
    viewMisProducts();
}-->
