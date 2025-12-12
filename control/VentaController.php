<?php
require_once("../model/VentaModel.php");
require_once("../model/ProductoModel.php");

$objProducto = new ProductoModel();
$objVenta = new VentaModel();

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if ($tipo == "registrarTemporal") {
    $respuesta = array('status' => false, 'msg' => 'Fallo el controlador');
    $id_producto = isset($_POST['id_producto']) ? $_POST['id_producto'] : '';
    $precio = isset($_POST['precio']) ? $_POST['precio'] : 0;
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1;

    // Buscar si el producto ya existe en temporal
    $b_producto = $objVenta->buscarTemporalPorId($id_producto);

    if ($b_producto) {
        $r_cantidad = $b_producto->cantidad + 1;
        $objVenta->actualizarCantidadTemporal($id_producto, $r_cantidad);
        $respuesta = array('status' => true, 'msg' => 'Actualizado con éxito');
    } else {
        $objVenta->registrar_temporal($id_producto, $precio, $cantidad);
        $respuesta = array('status' => true, 'msg' => 'registrado');
    }

    echo json_encode($respuesta);
    exit;
}

if ($tipo == "buscarTemporal") {
    $respuesta = array('status' => true, 'msg' => 'Sin productos', 'data' => array());
    $productos = $objVenta->buscarTemporal();
    if ($productos && count($productos) > 0) {
        $respuesta = array('status' => true, 'msg' => 'Productos encontrados', 'data' => $productos);
    }
    echo json_encode($respuesta);
    exit;
}
?>







