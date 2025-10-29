<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../model/ProductsModel.php";

$tipo = $_REQUEST['tipo'];

// Instanciar el modelo
$objProduct = new ProductsModel();

if ($tipo == "registrar") {
    // Registrar nuevo producto
    if ($_POST) {
        $codigo = trim($_POST['codigo'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $detalle = trim($_POST['detalle'] ?? '');
        $precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
        $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
        // Aceptar tanto 'categoria' como 'id_categoria' desde el formulario
        $categoria = isset($_POST['categoria']) ? intval($_POST['categoria']) : (isset($_POST['id_categoria']) ? intval($_POST['id_categoria']) : 0);
        $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;
        // Imagen: si viene por FILES, no se procesa aquí (quedará vacío)
        $imagen = $_POST['imagen'] ?? '';
        $proveedor = $_POST['proveedor'] ?? '';

        // Validación básica
        if ($codigo === '' || $nombre === '' || $detalle === '' || $precio <= 0 || $stock < 0 || $categoria <= 0 || empty($fecha_vencimiento)) {
            echo json_encode(['status' => false, 'msg' => 'Datos inválidos o incompletos']);
            exit;
        }

        $arr_respuesta = $objProduct->registrarProducto($codigo, $nombre, $detalle, $precio, $stock, $categoria, $fecha_vencimiento, $imagen, $proveedor);

        if (empty($arr_respuesta['id'])) {
            $msg = !empty($arr_respuesta['error'] ?? '') ? $arr_respuesta['error'] : "Error al registrar producto";
            $arr_response = array('status' => false, 'msg' => $msg);
        } else {
            $arr_response = array('status' => true, 'msg' => "Producto registrado correctamente");
        }
        echo json_encode($arr_response);
    }
}

if ($tipo == "ver_productos") {
    // Listar todos los productos
    $arr_respuesta = $objProduct->obtenerProductos();
    
    if (empty($arr_respuesta)) {
        $response = array('status' => false, 'msg' => "Error al obtener productos");
    } else {
        $response = array('status' => true, 'data' => $arr_respuesta);
    }
    echo json_encode($response);
}

if ($tipo == "ver") {
    // Ver un producto específico
    if ($_POST) {
        $id_producto = $_POST['id_producto'];
        $arr_respuesta = $objProduct->verProducto($id_producto);
        
        if (empty($arr_respuesta)) {
            $response = array('status' => false, 'msg' => "Error al obtener producto");
        } else {
            $response = array('status' => true, 'data' => $arr_respuesta);
        }
        echo json_encode($response);
    }
}

if ($tipo == "ver_registros") {
    // Ver registros de un producto específico (movimientos de stock, ventas, etc.)
    if ($_POST) {
        $id_producto = $_POST['id_producto'];
        $arr_respuesta = $objProduct->obtenerRegistrosProducto($id_producto);
        
        $response = array('status' => true, 'data' => $arr_respuesta);
        echo json_encode($response);
    }
}

if ($tipo == "actualizar") {
    // Actualizar producto
    if ($_POST) {
        $id_producto = $_POST['id_producto'];
        $codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
        $detalle = $_POST['detalle'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $categoria = $_POST['categoria'];
        $fecha_vencimiento = $_POST['fecha_vencimiento'];
        $imagen = $_POST['imagen'] ?? '';
        $proveedor = $_POST['proveedor'];
        
        $arr_respuesta = $objProduct->actualizarProducto($id_producto, $codigo, $nombre, $detalle, $precio, $stock, $categoria, $fecha_vencimiento, $imagen, $proveedor);
        
        if ($arr_respuesta) {
            $response = array('status' => true, 'msg' => "Producto actualizado correctamente");
        } else {
            $response = array('status' => false, 'msg' => "Error al actualizar producto");
        }
        echo json_encode($response);
    }
}

if ($tipo == "eliminar") {
    // Eliminar producto
    if ($_POST) {
        $id_producto = $_POST['id_producto'];
        $arr_respuesta = $objProduct->eliminarProducto($id_producto);
        
        if ($arr_respuesta) {
            $response = array('status' => true, 'msg' => "Producto eliminado correctamente");
        } else {
            $response = array('status' => false, 'msg' => "Error al eliminar producto");
        }
        echo json_encode($response);
    }
}

if ($tipo == "obtener_registro") {
    // Obtener un registro específico para editar
    if ($_POST) {
        $id_registro = $_POST['id_registro'];
        $arr_respuesta = $objProduct->obtenerRegistro($id_registro);
        
        if (empty($arr_respuesta)) {
            $response = array('status' => false, 'msg' => "Error al obtener registro");
        } else {
            $response = array('status' => true, 'data' => $arr_respuesta);
        }
        echo json_encode($response);
    }
}

if ($tipo == "editar_registro") {
    // Editar un registro específico del producto
    if ($_POST) {
        $id_registro = $_POST['id_registro'];
        $tipo_movimiento = $_POST['tipo_movimiento'];
        $cantidad = $_POST['cantidad'];
        $precio_unitario = $_POST['precio_unitario'];
        $descripcion = $_POST['descripcion'];
        $estado = $_POST['estado'];
        
        $arr_respuesta = $objProduct->editarRegistro($id_registro, $tipo_movimiento, $cantidad, $precio_unitario, $descripcion, $estado);
        
        if ($arr_respuesta) {
            $response = array('status' => true, 'msg' => "Registro actualizado correctamente");
        } else {
            $response = array('status' => false, 'msg' => "Error al actualizar registro");
        }
        echo json_encode($response);
    }
}

if ($tipo == "eliminar_registro") {
    // Eliminar un registro específico del producto
    if ($_POST) {
        $id_registro = $_POST['id_registro'];
        $arr_respuesta = $objProduct->eliminarRegistro($id_registro);
        
        if ($arr_respuesta) {
            $response = array('status' => true, 'msg' => "Registro eliminado correctamente");
        } else {
            $response = array('status' => false, 'msg' => "Error al eliminar registro");
        }
        echo json_encode($response);
    }
}

if ($tipo == "registrar_registro") {
    // Registrar nuevo registro para un producto (movimiento de stock)
    if ($_POST) {
        $id_producto = $_POST['id_producto'];
        $tipo_movimiento = $_POST['tipo_movimiento']; // entrada, salida, venta, devolucion
        $cantidad = $_POST['cantidad'];
        $precio_unitario = $_POST['precio_unitario'];
        $descripcion = $_POST['descripcion'];
        $estado = $_POST['estado'] ?? 1;
        
        $arr_respuesta = $objProduct->registrarRegistro($id_producto, $tipo_movimiento, $cantidad, $precio_unitario, $descripcion, $estado);
        
        if ($arr_respuesta) {
            $response = array('status' => true, 'msg' => "Registro agregado correctamente");
        } else {
            $response = array('status' => false, 'msg' => "Error al agregar registro");
        }
        echo json_encode($response);
    }
}

if ($tipo == "buscar_producto") {
    // Buscar productos por código o nombre
    if ($_POST) {
        $termino = $_POST['termino'];
        $arr_respuesta = $objProduct->buscarProducto($termino);
        
        if (empty($arr_respuesta)) {
            $response = array('status' => false, 'msg' => "No se encontraron productos");
        } else {
            $response = array('status' => true, 'data' => $arr_respuesta);
        }
        echo json_encode($response);
    }
}

if ($tipo == "productos_por_categoria") {
    // Obtener productos por categoría
    if ($_POST) {
        $id_categoria = $_POST['id_categoria'];
        $arr_respuesta = $objProduct->obtenerProductosPorCategoria($id_categoria);
        
        if (empty($arr_respuesta)) {
            $response = array('status' => false, 'msg' => "No hay productos en esta categoría");
        } else {
            $response = array('status' => true, 'data' => $arr_respuesta);
        }
        echo json_encode($response);
    }
}

if ($tipo == "productos_bajo_stock") {
    // Obtener productos con stock bajo
    $limite_stock = $_POST['limite_stock'] ?? 10;
    $arr_respuesta = $objProduct->obtenerProductosBajoStock($limite_stock);
    
    if (empty($arr_respuesta)) {
        $response = array('status' => false, 'msg' => "No hay productos con stock bajo");
    } else {
        $response = array('status' => true, 'data' => $arr_respuesta);
    }
    echo json_encode($response);
}

if ($tipo == "productos_vencidos") {
    // Obtener productos próximos a vencer o vencidos
    $dias_limite = $_POST['dias_limite'] ?? 30;
    $arr_respuesta = $objProduct->obtenerProductosVencidos($dias_limite);
    
    if (empty($arr_respuesta)) {
        $response = array('status' => false, 'msg' => "No hay productos próximos a vencer");
    } else {
        $response = array('status' => true, 'data' => $arr_respuesta);
    }
    echo json_encode($response);
}

if ($tipo == "actualizar_stock") {
    // Actualizar stock de un producto
    if ($_POST) {
        $id_producto = $_POST['id_producto'];
        $nuevo_stock = $_POST['nuevo_stock'];
        $tipo_movimiento = $_POST['tipo_movimiento']; // entrada o salida
        $descripcion = $_POST['descripcion'] ?? 'Ajuste de stock';
        
        $arr_respuesta = $objProduct->actualizarStock($id_producto, $nuevo_stock, $tipo_movimiento, $descripcion);
        
        if ($arr_respuesta) {
            $response = array('status' => true, 'msg' => "Stock actualizado correctamente");
        } else {
            $response = array('status' => false, 'msg' => "Error al actualizar stock");
        }
        echo json_encode($response);
    }
}

if ($tipo == "obtener_categorias") {
    // Obtener todas las categorías para el formulario
    $arr_respuesta = $objProduct->obtenerCategorias();
    
    if (empty($arr_respuesta)) {
        $response = array('status' => false, 'msg' => "Error al obtener categorías");
    } else {
        $response = array('status' => true, 'data' => $arr_respuesta);
    }
    echo json_encode($response);
}

if ($tipo == "obtener_proveedores") {
    // Obtener todos los proveedores para el formulario
    $arr_respuesta = $objProduct->obtenerProveedores();
    
    if (empty($arr_respuesta)) {
        $response = array('status' => false, 'msg' => "Error al obtener proveedores");
    } else {
        $response = array('status' => true, 'data' => $arr_respuesta);
    }
    echo json_encode($response);
}
?>