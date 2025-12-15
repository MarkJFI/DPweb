<?php
require_once("../model/VentaModel.php");
require_once("../model/ProductoModel.php");
require_once("../model/ClienteModel.php");

$objProducto = new ProductoModel();
$objVenta = new VentaModel();
$objCliente = new ClienteModel();

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
    $respuesta = array('status' => true, 'msg' => 'Sin productos', 'data' => array());
    $productos = $objVenta->buscarTemporal();
    if ($productos && count($productos) > 0) {
        $total = 0;
        foreach ($productos as $producto) {
            $total += $producto->precio * $producto->cantidad;
        }
        $total = round($total, 2);
        $igv = round($total * 0.18, 2);
        $total_con_igv = $total + $igv;
        $respuesta = array('status' => true, 'msg' => 'Productos encontrados', 'data' => $productos, 'subtotal' => $total, 'igv' => $igv, 'total_con_igv' => $total_con_igv);
    }
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







