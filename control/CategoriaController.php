<?php
require_once("../model/CategoriaModel.php");
$objCategoria = new CategoriaModel();

$tipo = $_REQUEST['tipo'] ?? '';

if ($tipo == "registrar") {
    $nombre = $_POST['nombre'] ?? '';
    $detalle = $_POST['detalle'] ?? '';
    if ($nombre == "" || $detalle == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacios');
    } else {
        $existeCategoria = $objCategoria->existeCategoria($nombre);
        if ($existeCategoria > 0) {
            $arrResponse = array('status' => false, 'msg' => 'Error, categoria ya existe');
        } else {
            $respuesta = $objCategoria->registrar($nombre, $detalle);
            if ($respuesta) {
                $arrResponse = array('status' => true, 'msg' => 'Registrado Correctamente');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error, fallo en registro');
            }
        }
    }
    echo json_encode($arrResponse);
}

if ($tipo == "ver_categorias") {
    $arr = $objCategoria->verCategorias();
    echo json_encode(['status' => true, 'data' => $arr]);
}

if ($tipo == "ver") {
    $id = $_POST['id_categoria'] ?? '';
    if ($id == '') {
        echo json_encode(['status' => false, 'msg' => 'ID requerido']);
        exit;
    }
    $row = $objCategoria->ver($id);
    if ($row) echo json_encode(['status' => true, 'data' => $row]);
    else echo json_encode(['status' => false, 'msg' => 'No encontrado']);
}

if ($tipo == "actualizar") {
    $id = $_POST['id_categoria'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $detalle = $_POST['detalle'] ?? '';
    if ($id == '' || $nombre == '' || $detalle == '') {
        echo json_encode(['status' => false, 'msg' => 'Datos incompletos']);
        exit;
    }
    $ok = $objCategoria->actualizar($id, $nombre, $detalle);
    echo json_encode(['status' => (bool)$ok, 'msg' => $ok ? 'Actualizado correctamente' : 'Error al actualizar']);
}

if ($tipo == "eliminar") {
    $id = $_POST['id_categoria'] ?? '';
    if ($id == '') {
        echo json_encode(['status' => false, 'msg' => 'ID requerido']);
        exit;
    }
    $ok = $objCategoria->eliminar($id);
    echo json_encode(['status' => (bool)$ok, 'msg' => $ok ? 'Eliminado correctamente' : 'Error al eliminar']);
}