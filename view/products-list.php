
<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div class="d-flex align-items-center">
            <a href="<?php echo BASE_URL; ?>new-products" class="btn btn-success me-3"><i class="bi bi-plus-circle"></i>+ Nuevo Producto</a>
            <a href="<?php echo BASE_URL; ?>ventas" class="btn btn-primary me-3">
                <i class="bi bi-eye"></i> Mostrar Productos
            </a>
            <h5 class="mb-0"><center><i>Lista de Productos</i></center></h5>
        </div>
    </div>



    <table class="table table-striped-columns">
        <thead>
            <tr>
                <th><i>Nro</i></th>
                <th><i>Imagen</i></th>
                <th><i>Código</i></th>
                <th><i>Nombre</i></th>
                <th><i>Precio</i></th>
                <th><i>Stock</i></th>
                <th><i>Categoria</i></th>
                <th><i>Proveedor</i></th>
                <th><i>Fecha de vencimiento</i></th>
                <th><i>Acciones</i></th>
            </tr>
        </thead>
        <tbody id="content_productos"></tbody>
    </table>

    <!-- Grid moderno de tarjetas -->
    <div class="row g-3" id="products_grid" data-loading="0" aria-live="polite">
        <!-- Placeholder visible mientras el JS carga los productos -->
        <div class="col-12 text-center py-5" id="products_grid_placeholder">
            <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
            <div class="mt-2">Cargando productos...</div>
        </div>
    </div>

</div>

<script src="<?php echo BASE_URL; ?>view/function/producto.js"></script>
<!-- end -->

    <script>
        // Debug helper: si después de 2s no hay tarjetas reemplaza el placeholder con la respuesta cruda
        (function () {
            const placeholder = document.getElementById('products_grid_placeholder');
            const grid = document.getElementById('products_grid');
            function showDebug(msg) {
                if (!placeholder) return;
                placeholder.innerHTML = '<pre style="text-align:left; white-space:pre-wrap; word-break:break-word;">' + msg + '</pre>';
            }

            setTimeout(async () => {
                // Si grid tiene más de un hijo (tarjetas) entonces ok
                if (!grid) return;
                const cards = grid.querySelectorAll('.card');
                if (cards.length > 0) {
                    // productos renderizados correctamente
                    const ph = document.getElementById('products_grid_placeholder');
                    if (ph) ph.style.display = 'none';
                    return;
                }

                // Comprueba si base_url existe
                if (typeof base_url === 'undefined') {
                    showDebug('Error: la variable JavaScript base_url no está definida. Asegúrate de cargar la página a través de la plantilla que incluye header.php.');
                    console.error('base_url no definido');
                    return;
                }

                // Intenta obtener la respuesta del controlador para mostrar errores
                try {
                    const resp = await fetch(base_url + 'control/ProductsController.php?tipo=ver_productos', { method: 'POST' });
                    const text = await resp.text();
                    if (!resp.ok) {
                        showDebug('HTTP ' + resp.status + '\n\n' + text);
                        console.error('HTTP', resp.status, text);
                        return;
                    }
                    // Si la respuesta parece JSON, la parseamos y mostramos un resumen
                    try {
                        const json = JSON.parse(text);
                        if (!json || !json.status) {
                            showDebug('Respuesta del servidor (no status true):\n' + JSON.stringify(json, null, 2));
                        } else if (Array.isArray(json.data) && json.data.length === 0) {
                            showDebug('No hay productos en la base de datos. JSON válido recibido: ' + JSON.stringify(json, null, 2));
                        } else {
                            // Si el renderer falla por alguna razón, renderizamos una vista mínima de fallback
                            try {
                                const items = json.data;
                                                                const html = items.map(p => {
                                                                        const nombre = p.nombre || '';
                                                                        const precio = (p.precio !== undefined && p.precio !== null) ? Number(p.precio).toFixed(2) : '0.00';
                                                                        const img = p.imagen ? (base_url + p.imagen) : (base_url + 'view/img/imagen.avif');
                                                                        const detalle = p.detalle || p.estado || '';
                                                                        return `<div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                                                <div class="card h-100 d-flex flex-column text-center">
                                                                                    <img src="${img}" class="card-img-top" alt="${nombre}" style="height:160px; object-fit:cover;" onerror="this.onerror=null;this.src='${base_url}view/img/imagen.avif'">
                                                                                    <div class="card-body d-flex flex-column align-items-center">
                                                                                        <h5 class="card-title text-primary fw-bold mb-1">${nombre}</h5>
                                                                                        <div class="text-muted mb-2">${detalle}</div>
                                                                                        <div class="fw-bold text-success mb-2">S/ ${precio}</div>
                                                                                        <div class="small text-muted">Categoría: ${p.categoria_nombre || ''}</div>
                                                                                        <div class="small text-muted">Proveedor: ${p.proveedor_nombre || ''}</div>
                                                                                        <div class="small text-muted">Vence: ${p.fecha_vencimiento || ''}</div>
                                                                                    </div>
                                                                                    <div class="card-footer d-flex justify-content-between">
                                                                                        <a href="${base_url}edit-products/${p.id}" class="btn btn-primary btn-sm">Editar</a>
                                                                                        <button onclick="eliminar(${p.id})" class="btn btn-danger btn-sm">Eliminar</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>`;
                                                                }).join('');
                                grid.innerHTML = html;
                                const ph = document.getElementById('products_grid_placeholder');
                                if (ph) ph.style.display = 'none';
                            } catch (e) {
                                showDebug('JSON válido recibido. Productos: ' + (json.data ? json.data.length : 0) + '\n\n' + JSON.stringify(json.data ? json.data.slice(0,5) : json, null, 2));
                            }
                        }
                    } catch (e) {
                        // respuesta no JSON
                        showDebug('Respuesta no-JSON recibida del servidor:\n' + text);
                    }
                } catch (err) {
                    showDebug('Error al hacer fetch: ' + err);
                    console.error(err);
                }
            }, 2000);
        })();
    </script>
