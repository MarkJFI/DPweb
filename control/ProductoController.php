<?php
require_once("../model/ProductoModel.php");
require_once("../model/CategoriaModel.php");
$objProducto = new ProductoModel();
$objCategoria = new CategoriaModel();

$tipo = $_GET['tipo'] ?? '';

header('Content-Type: application/json; charset=utf-8');

// REGISTRAR PRODUCTO
if ($tipo == 'registrar') {
    $codigo           = $_POST['codigo'] ?? '';
    $nombre           = $_POST['nombre'] ?? '';
    $detalle          = $_POST['detalle'] ?? '';
    $precio           = $_POST['precio'] ?? '';
    $stock            = $_POST['stock'] ?? '';
    $id_categoria     = $_POST['id_categoria'] ?? '';
    $fecha_vencimiento= $_POST['fecha_vencimiento'] ?? '';
    $id_proveedor     = isset($_POST['id_proveedor']) && $_POST['id_proveedor'] !== '' ? (int)$_POST['id_proveedor'] : 0;

    // Manejo de imagen
    $imagen = '';
    if (!empty($_FILES['imagen']['name'])) {
        $ruta = "../uploads/";
        $imagen = time() . "_" . basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta . $imagen);
    }

    if ($codigo=="" || $nombre=="" || $detalle=="" || $precio=="" || $stock=="" || $id_categoria=="") {
        $arrResponse = ['status'=>false,'msg'=>'Error, campos vacíos'];
    } else {
        $respuesta = $objProducto->registrar($codigo, $nombre, $detalle, $precio, $stock, $fecha_vencimiento, $imagen, $id_categoria, $id_proveedor);
        $arrResponse = $respuesta ? 
            ['status'=>true,'msg'=>'Producto registrado correctamente'] : 
            ['status'=>false,'msg'=>'Error al registrar producto'];
    }
    echo json_encode($arrResponse);
    exit;
}

// LISTAR PRODUCTOS
if ($tipo == 'ver_products') {
    $productos = $objProducto->verProductos();
    echo json_encode($productos);
    exit;
}

// VER UN PRODUCTO
if ($tipo == 'ver') {
    $id_producto = $_POST['id_producto'] ?? '';
    $producto = $objProducto->obtenerProductoPorId((int)$id_producto);
    echo json_encode([
        'status'=> (bool)$producto,
        'data'  => $producto,
        'msg'   => $producto ? '' : 'Producto no encontrado'
    ]);
    exit;
}

// ACTUALIZAR PRODUCTO
if ($tipo == 'actualizar') {
    $id_producto      = $_POST['id_producto'] ?? '';
    $codigo           = $_POST['codigo'] ?? '';
    $nombre           = $_POST['nombre'] ?? '';
    $detalle          = $_POST['detalle'] ?? '';
    $precio           = $_POST['precio'] ?? '';
    $stock            = $_POST['stock'] ?? '';
    $id_categoria     = $_POST['id_categoria'] ?? '';
    $fecha_vencimiento= $_POST['fecha_vencimiento'] ?? '';
    $id_proveedor     = $_POST['id_proveedor'] ?? '';

    $imagen = $_POST['imagen_actual'] ?? '';
    if (!empty($_FILES['imagen']['name'])) {
        $ruta = "../uploads/";
        $imagen = time() . "_" . basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta . $imagen);
    }

    if ($id_producto=="" || $codigo=="" || $nombre=="" || $detalle=="" || $precio=="" || $stock=="" || $id_categoria=="") {
        $arrResponse = ['status'=>false,'msg'=>'Error, campos vacíos'];
    } else {
        $data = [
            'id_producto' => (int)$id_producto,
            'codigo' => $codigo,
            'nombre' => $nombre,
            'detalle' => $detalle,
            'precio' => $precio,
            'stock' => $stock,
            'fecha_vencimiento' => $fecha_vencimiento,
            'imagen' => $imagen,
            'id_categoria' => $id_categoria
        ];
        $actualizar = $objProducto->actualizarProducto($data);
        $arrResponse = $actualizar ?
            ['status'=>true,'msg'=>'Producto actualizado correctamente'] :
            ['status'=>false,'msg'=>'Error al actualizar producto'];
    }
    echo json_encode($arrResponse);
    exit;
}

// ELIMINAR PRODUCTO
if ($tipo == 'eliminar') {
    $id_producto = $_POST['id'] ?? '';
    if ($id_producto=="") {
        $arrResponse = ['status'=>false,'msg'=>'Error, ID vacío'];
    } else {
        $resp = $objProducto->eliminarProducto((int)$id_producto);
        $arrResponse = $resp;
    }
    echo json_encode($arrResponse);
    exit;
}
