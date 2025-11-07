<?php
header('Content-Type: application/json; charset=utf-8');

// Capturar todos los errores
function exception_error_handler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

try {
    require_once "../model/ProductsModel.php";

    if (!isset($_REQUEST['tipo'])) {
        throw new Exception('Tipo de operación no especificado');
    }

    $tipo = $_REQUEST['tipo'];
    
    // Instanciar el modelo
    $objProduct = new ProductsModel();

    // Manejar la actualización de productos
    if ($tipo == "actualizar") {
        if (!$_POST) {
            throw new Exception('No se recibieron datos POST');
        }

        // Debug: registrar datos recibidos
        error_log("Datos POST recibidos: " . print_r($_POST, true));
        
        // Validación de campos requeridos
        $campos_requeridos = ['id_producto', 'codigo', 'nombre', 'detalle', 'precio', 'stock', 'id_categoria', 'fecha_vencimiento'];
        $faltan_campos = [];
        
        foreach ($campos_requeridos as $campo) {
            if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
                $faltan_campos[] = $campo;
            }
        }
        
        if (!empty($faltan_campos)) {
            throw new Exception('Faltan campos requeridos: ' . implode(', ', $faltan_campos));
        }

        // Procesar los datos
        $id_producto = filter_var($_POST['id_producto'], FILTER_VALIDATE_INT);
        if (!$id_producto) {
            throw new Exception('ID de producto inválido');
        }

        $codigo = trim($_POST['codigo']);
        $nombre = trim($_POST['nombre']);
        $detalle = trim($_POST['detalle']);
        $precio = filter_var($_POST['precio'], FILTER_VALIDATE_FLOAT);
        $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
        $categoria = filter_var($_POST['id_categoria'], FILTER_VALIDATE_INT);
        $fecha_vencimiento = $_POST['fecha_vencimiento'];
        $proveedor = isset($_POST['id_proveedor']) ? filter_var($_POST['id_proveedor'], FILTER_VALIDATE_INT) : null;

        // Validar valores
        if ($precio === false || $precio <= 0) {
            throw new Exception('Precio inválido');
        }
        if ($stock === false || $stock < 0) {
            throw new Exception('Stock inválido');
        }
        if (!$categoria) {
            throw new Exception('Categoría inválida');
        }

        // Obtener imagen actual
        $current = $objProduct->verProducto($id_producto);
        if (!$current) {
            throw new Exception('No se encontró el producto');
        }
        $imagen = $current['imagen'];

        // Procesar nueva imagen si se subió
        if (isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name']) {
            $file = $_FILES['imagen'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error al subir la imagen: ' . $file['error']);
            }

            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowed)) {
                throw new Exception('Tipo de imagen no permitido');
            }

            $uploadDir = __DIR__ . '/../uploads/productos/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new Exception('No se pudo crear el directorio de uploads');
                }
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('prod_') . '.' . $ext;
            $target = $uploadDir . $filename;

            if (!move_uploaded_file($file['tmp_name'], $target)) {
                throw new Exception('No se pudo mover el archivo subido');
            }

            // Eliminar imagen anterior
            if ($imagen && file_exists(__DIR__ . '/../' . $imagen)) {
                unlink(__DIR__ . '/../' . $imagen);
            }

            $imagen = 'uploads/productos/' . $filename;
        }

        // Actualizar producto
        $result = $objProduct->actualizarProducto(
            $id_producto,
            $codigo,
            $nombre,
            $detalle,
            $precio,
            $stock,
            $categoria,
            $fecha_vencimiento,
            $imagen,
            $proveedor
        );

        if (!$result) {
            throw new Exception('Error al actualizar el producto en la base de datos');
        }

        echo json_encode([
            'status' => true,
            'msg' => 'Producto actualizado correctamente'
        ]);
        exit;
    }

    // Resto de operaciones aquí...
    if ($tipo == "ver_productos") {
        $arr_respuesta = $objProduct->obtenerProductos();
        echo json_encode([
            'status' => !empty($arr_respuesta),
            'data' => $arr_respuesta ?: []
        ]);
        exit;
    }

    if ($tipo == "eliminar") {
        if (!isset($_POST['id_producto'])) {
            throw new Exception('ID de producto no especificado');
        }
        $id_producto = filter_var($_POST['id_producto'], FILTER_VALIDATE_INT);
        if (!$id_producto) {
            throw new Exception('ID de producto inválido');
        }
        
        $result = $objProduct->eliminarProducto($id_producto);
        echo json_encode([
            'status' => $result,
            'msg' => $result ? 'Producto eliminado correctamente' : 'Error al eliminar el producto'
        ]);
        exit;
    }

    if ($tipo == "ver") {
        if (!isset($_POST['id_producto'])) {
            throw new Exception('ID de producto no especificado');
        }
        $id_producto = filter_var($_POST['id_producto'], FILTER_VALIDATE_INT);
        if (!$id_producto) {
            throw new Exception('ID de producto inválido');
        }
        
        $producto = $objProduct->verProducto($id_producto);
        if (!$producto) {
            throw new Exception('Producto no encontrado');
        }
        
        // Asegurar que los campos de categoria y proveedor estén correctamente mapeados
        if (isset($producto['id_categoria'])) {
            $producto['categoria'] = $producto['id_categoria'];
        }
        if (isset($producto['id_proveedor'])) {
            $producto['proveedor'] = $producto['id_proveedor'];
        }
        
        echo json_encode([
            'status' => true,
            'msg' => 'Producto encontrado',
            'data' => $producto
        ]);
        exit;
    }

    if ($tipo == "obtener_proveedores") {
        $proveedores = $objProduct->obtenerProveedores();
        echo json_encode([
            'status' => true,
            'data' => $proveedores
        ]);
        exit;
    }

    throw new Exception('Tipo de operación no válido: ' . $tipo);

} catch (Exception $e) {
    error_log("Error en ProductsController: " . $e->getMessage());
    echo json_encode([
        'status' => false,
        'msg' => $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
?>