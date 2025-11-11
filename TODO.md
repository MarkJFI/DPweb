
- [x] Agregar botón "Mostrar Productos" en view/products-list.php que redirige directamente a view/ventas.php (sin modal)
- [x] Modificar view/ventas.php para mostrar productos en cards con imagen, nombre, precio, detalle, categoría, proveedor y botones "Detalles" y "Añadir al carrito"
- [x] Agregar función JavaScript en view/function/producto.js para cargar y renderizar productos en ventas.php
- [x] Crear view/function/ventas.js con funciones para cargar productos dinámicamente y manejar botones
- [x] Agregar "ventas" a la whitelist en model/views_model.php para evitar error 404
- [x] Restaurar la sección de "Lista de Compra" en view/ventas.php según solicitud del usuario
- [x] Agregar columna de imagen en la tabla de productos-list.php y mostrar imágenes en la lista
- [x] Crear directorio assets/images para almacenar imágenes de productos
- [x] Implementar subida de imágenes en el controlador para registrar y actualizar productos
- [x] Agregar enctype="multipart/form-data" a los formularios de registro y edición
- [x] Mostrar imagen actual en el formulario de edición
- [ ] Probar la funcionalidad del botón y la visualización de productos

# TODO: Change Product List to Card Display

- [x] Modify view/products-list.php to replace table with Bootstrap card grid container
- [x] Update view/function/producto.js view_producto() function to generate card HTML with image, name, price, detail, category, supplier, expiration date, and action buttons
- [x] Test the card display functionality

