<?php
// Evitar que cualquier output previo rompa las respuestas JSON
ob_start();
ini_set('display_errors', '0'); // no mostrar errores en la salida JSON
error_reporting(E_ALL);

// Helper para enviar JSON de forma segura (limpia buffer y registra salida inesperada)
function send_json($data) {
    $buf = ob_get_clean();
    if (!empty($buf)) {
        error_log("Unexpected output before JSON response in ProductsController: " . $buf);
    }
    // Intentar codificar a JSON manejando errores de codificación (UTF-8)
    $json = @json_encode($data, JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        $err = json_last_error_msg();
        error_log("JSON encode error in ProductsController: " . $err . " - attempting utf8 normalization");

        // Función recursiva para normalizar strings a UTF-8
        $utf8ize = function ($mixed) use (&$utf8ize) {
            if (is_array($mixed)) {
                $res = [];
                foreach ($mixed as $k => $v) {
                    $res[$k] = $utf8ize($v);
                }
                return $res;
            } elseif (is_string($mixed)) {
                // Convertir a UTF-8 si no lo está
                if (!mb_check_encoding($mixed, 'UTF-8')) {
                    return mb_convert_encoding($mixed, 'UTF-8', 'auto');
                }
                return $mixed;
            }
            return $mixed;
        };

        $safe = $utf8ize($data);
        $json = @json_encode($safe, JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            $err2 = json_last_error_msg();
            error_log("JSON encode still failing after utf8 normalization: " . $err2);
            // Fallback: enviar un JSON mínimo y registrar los datos completos en el log
            error_log("ProductsController response data (for debugging): " . print_r($data, true));
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => false,
                'msg' => 'Error serializando respuesta JSON en el servidor',
                'json_error' => $err2
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $json;
    exit;
}

// Capturar todos los errores y convertirlos en excepciones
function exception_error_handler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

try {
    require_once __DIR__ . '/../model/ProductsModel.php';

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

        send_json([
            'status' => true,
            'msg' => 'Producto actualizado correctamente'
        ]);
    }

    // Resto de operaciones aquí...
    if ($tipo == "ver_productos") {
        $arr_respuesta = $objProduct->obtenerProductos();
        send_json([
            'status' => !empty($arr_respuesta),
            'data' => $arr_respuesta ?: []
        ]);
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
        send_json([
            'status' => $result,
            'msg' => $result ? 'Producto eliminado correctamente' : 'Error al eliminar el producto'
        ]);
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
        
        send_json([
            'status' => true,
            'msg' => 'Producto encontrado',
            'data' => $producto
        ]);
    }

    if ($tipo == "obtener_proveedores") {
        $proveedores = $objProduct->obtenerProveedores();
        send_json([
            'status' => true,
            'data' => $proveedores
        ]);
    }

    throw new Exception('Tipo de operación no válido: ' . $tipo);

} catch (Exception $e) {
    error_log("Error en ProductsController: " . $e->getMessage());
    send_json([
        'status' => false,
        'msg' => $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
?>