<?php
require_once("../model/VentaModel.php");
require_once("../model/ProductoModel.php");
require_once("../model/ClienteModel.php");

$objProducto = new ProductoModel();
$objVenta = new VentaModel();
$objCliente = new ClienteModel();

$tipo = $_GET['tipo'];

if ($tipo == "registrar_temporal"){
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $id_producto = $_POST['id_producto'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    $b_producto = $objVenta->buscarTemporal($id_producto);
    if ($b_producto) {
        // Se actualiza a la cantidad total enviada desde el frontend, no solo se suma 1
        $objVenta->actualizarCantidadTemporal($id_producto, $cantidad);
        $respuesta = array('status' => true, 'msg' => 'actualizado');

    }else{
        $registro = $objVenta->registrar_temporal($id_producto, $precio, $cantidad);
        $respuesta = array('status' => true, 'msg' => 'registrado');
    }
    echo json_encode($respuesta);
}


if ($tipo == "eliminarTemporal") {
    $respuesta = array('status' => false, 'msg' => 'Fallo el controlador');
    $id_producto = isset($_POST['id_producto']) ? $_POST['id_producto'] : '';
    if ($id_producto) {
        $objVenta->eliminarTemporal($id_producto);
        $respuesta = array('status' => true, 'msg' => 'Producto eliminado');
    }
    echo json_encode($respuesta);
    exit;
}

if ($tipo == "buscarTemporal") {
    // Lógica corregida para devolver siempre una respuesta JSON válida
    $productos = $objVenta->buscarTemporales(); // Usando el método que devuelve todos los temporales
    $respuesta = array('status' => true, 'data' => $productos);
    echo json_encode($respuesta);
    exit;
}

if ($tipo == "buscarCliente") {
    $respuesta = array('status' => false, 'msg' => 'Fallo el controlador');
    $dni = isset($_POST['dni']) ? $_POST['dni'] : '';
    if ($dni) {
        $cliente = $objCliente->buscarPorDNI($dni);
        if ($cliente) {
            $respuesta = array('status' => true, 'msg' => 'Cliente encontrado', 'nombre' => $cliente->nombre);
        } else {
            $respuesta = array('status' => false, 'msg' => 'Cliente no encontrado');
        }
    }
    echo json_encode($respuesta);
    exit;
}
if ($tipo == "registrar_venta") {
    session_start();
    $id_cliente = $_POST['id_cliente'];
    $fecha_hora = $_POST['fecha_hora'];
    $id_vendedor = $_SESSION['ventas_id'];
    $ultima_venta = $objVenta->buscar_ultima_venta();
    $respuesta = array('status' => false, 'msg' => 'Fallo el controlador');
    if ($ultima_venta) {
        $correlativo = $ultima_venta->codigo + 2;
    } else {
        $correlativo = 1;
    }
    $venta = $objVenta->registrar_venta($correlativo, $id_cliente, $id_vendedor, $fecha_hora);
    if ($venta) {
        $temporales = $objVenta->buscarTemporal();
        foreach ($temporales as $temporal) {
            $objVenta->registrar_detalle_venta($correlativo, $temporal->id_producto, $temporal->precio, $temporal->cantidad);
        }
        //eliminar temporales
        $objVenta->eliminarTemporales();
        $respuesta = array('status' => true, 'msg' => 'Venta registrada con éxito');
    }else {
        $respuesta = array('status' => false, 'msg' => 'Error al registrar la venta');
    }
    echo json_encode($respuesta);
    exit;
}




?>
