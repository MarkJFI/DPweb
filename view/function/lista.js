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

        let contenido = document.getElementById('content_products_cards');
        if (!contenido) {

            console.error("No se encontró el contenedor #content_products_cards");

            console.error(" No se encontró el contenedor #content_products_cards");

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
                
                // Añadido para depuración: verifica la ruta de la imagen
                console.log(`Producto: ${producto.nombre}, Imagen recibida: ${producto.imagen}, Ruta final de imagen: ${rutaImagen}`);

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
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3 btn-ver-detalle" 
                                data-id="${producto.id}" 
                                data-nombre="${escapeHtml(producto.nombre)}" 
                                data-detalle="${escapeHtml(producto.detalle)}"
                                data-precio="${producto.precio}"
                                data-stock="${producto.stock}"
                                data-imagen="${rutaImagen}"
                                data-categoria="${escapeHtml(producto.categoria ?? '')}"
                                data-proveedor="${escapeHtml(producto.proveedor ?? '')}">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </button>

                            <button class="btn btn-outline-success btn-sm rounded-pill px-3 btn-add-cart" 
                                data-id="${producto.id}" 
                                data-nombre="${escapeHtml(producto.nombre)}" 
                                data-precio="${producto.precio}"
                                data-stock="${producto.stock}">
                                <i class="bi bi-cart-plus"></i> Agregar al Carrito
                            </button>

                        </div>
                    </div >
                    `;


                fila.appendChild(col);

                // Adjuntar handlers en los botones recién creados
                const verBtn = col.querySelector('.btn-ver-detalle');
                const addBtn = col.querySelector('.btn-add-cart');
                if (verBtn) {
                    verBtn.addEventListener('click', () => showProductDetailsFromButton(verBtn));
                }
                if (addBtn) {
                    addBtn.addEventListener('click', () => addToCartFromButton(addBtn));
                }
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
                        Error al cargar los productos.Intente nuevamente más tarde.
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

if (document.getElementById('content_products_cards')) {
    view_products_cards();
}

function formatCurrency(value){
    return 'S/ ' + parseFloat(value).toFixed(2);
}

function escapeHtml(str){
    if (!str && str !== 0) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function addToCartFromButton(btn){
    const id = btn.getAttribute('data-id');
    const nombre = btn.getAttribute('data-nombre');
    const precio = parseFloat(btn.getAttribute('data-precio')) || 0;
    const stock = parseInt(btn.getAttribute('data-stock')) || 0;

    if (typeof addToCart === 'function') {
        addToCart({id, nombre, precio, stock}, 1);
        showToast(`${nombre} agregado al carrito.`, 'success');
    } else {
        console.error('La función global addToCart (de venta.js) no está disponible.');
    }
}

function showProductDetailsFromButton(btn){
    const id = btn.getAttribute('data-id');
    const nombre = btn.getAttribute('data-nombre');
    const detalle = btn.getAttribute('data-detalle');
    const precio = btn.getAttribute('data-precio');
    const stock = btn.getAttribute('data-stock');
    const imagen = btn.getAttribute('data-imagen');
    const categoria = btn.getAttribute('data-categoria');
    const proveedor = btn.getAttribute('data-proveedor');

    // rellenar modal
    const img = document.getElementById('modalProductoImagen');
    const nameEl = document.getElementById('modalProductoNombre');
    const detEl = document.getElementById('modalProductoDetalle');
    const precioEl = document.getElementById('modalProductoPrecio');
    const stockEl = document.getElementById('modalProductoStock');
    const categoriaEl = document.getElementById('modalProductoCategoria');
    const proveedorEl = document.getElementById('modalProductoProveedor');
    const fechaEl = document.getElementById('modalProductoFecha');
    const cantidadInput = document.getElementById('modalCantidad');
    const agregarBtn = document.getElementById('modalAgregarBtn');

    if (img) img.src = imagen || base_url + 'assets/img/no-image.png';
    if (nameEl) nameEl.textContent = nombre || '';
    if (detEl) detEl.textContent = detalle || '';
    if (precioEl) precioEl.textContent = formatCurrency(precio || 0);
    if (stockEl) stockEl.textContent = stock || '0';
    if (categoriaEl) categoriaEl.textContent = categoria || '—';
    if (proveedorEl) proveedorEl.textContent = proveedor || '—';
    if (fechaEl) fechaEl.textContent = '—';
    if (cantidadInput) cantidadInput.value = 1;

    // attach handler to modal add button
    if (agregarBtn) {
        // remove previous listeners by cloning
        const newBtn = agregarBtn.cloneNode(true);
        agregarBtn.parentNode.replaceChild(newBtn, agregarBtn);
        newBtn.addEventListener('click', () => {
            const qty = parseInt(document.getElementById('modalCantidad').value) || 1;
            if (typeof addToCart === 'function') {
                addToCart({id, nombre, precio: parseFloat(precio)||0, stock: parseInt(stock)||0}, qty);
                showToast(`${nombre} x${qty} agregado(s) al carrito.`, 'success');
            } else {
                console.error('La función global addToCart (de venta.js) no está disponible.');
            }
            // hide modal
            const modalEl = document.getElementById('productModal');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();
        });
    }

    // show modal
    const modalEl = document.getElementById('productModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

// Toast helper (Bootstrap 5)
function showToast(message, type = 'success', delay = 2000) {
    // container
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.position = 'fixed';
        container.style.top = '1rem';
        container.style.right = '1rem';
        container.style.zIndex = 1080;
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-bg-' + (type === 'error' ? 'danger' : 'success') + ' border-0';
    toast.role = 'alert';
    toast.ariaLive = 'assertive';
    toast.ariaAtomic = 'true';
    toast.style.minWidth = '220px';

    toast.innerHTML = `
                    <div class="d-flex">
            <div class="toast-body small">
                ${escapeHtml(message)}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
                    `;

    container.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: delay });
    bsToast.show();
    // remove after hidden
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}
