<?php
require_once("../model/CategoriaModel.php");
$objCategoria = new CategoriaModel();
$tipo = $_GET['tipo'];

if ($tipo == 'registrar') {
    $nombre = $_POST['nombre'];
    $detalle = $_POST['detalle'];

    if ($nombre == "" || $detalle == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacios');
    } else {
        // Validación si existe categoría con el mismo nombre
        $existeCategoria = $objCategoria->existeCategoria($nombre);
        if ($existeCategoria > 0) {
            $arrResponse = array('status' => false, 'msg' => 'Error, nombre de categoría ya existe');
        } else {
            $respuesta = $objCategoria->registrar($nombre, $detalle);
            if ($respuesta) {
                $arrResponse = array('status' => true, 'msg' => 'Registrado Correctamente');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error, falló en registro');
            }
        }
    }
    echo json_encode($arrResponse);
}

// Ver categorías
if ($tipo == "ver_categorias") {
    $categorias = $objCategoria->verCategorias();
    echo json_encode($categorias);
}

// Ver una categoría
if ($tipo == "ver") {
    $respuesta = array('status' => false, 'msg' => 'Error');
    $id_categoria = $_POST['id_categoria'];
    $categoria = $objCategoria->ver($id_categoria);
    if ($categoria) {
        $respuesta['status'] = true;
        $respuesta['data'] = $categoria;
    } else {
        $respuesta['msg'] = 'Error, categoría no existe';
    }
    echo json_encode($respuesta);
}

// Actualizar
if ($tipo == "actualizar") {
    $id_categoria = $_POST['id_categoria'];
    $nombre = $_POST['nombre'];
    $detalle = $_POST['detalle'];
    if ($id_categoria == "" || $nombre == "" || $detalle == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacios');
    } else {
        $existeID = $objCategoria->ver($id_categoria);
        if (!$existeID) {
            $arrResponse = array('status' => false, 'msg' => 'Error, categoría no existe en BD');
            echo json_encode($arrResponse);
            exit;
        } else {
            $actualizar = $objCategoria->actualizar($id_categoria, $nombre, $detalle);
            if ($actualizar) {
                $arrResponse = array('status' => true, 'msg' => "Actualizado correctamente");
            } else {
                $arrResponse = array('status' => false, 'msg' => $actualizar);
            }
            echo json_encode($arrResponse);
            exit;
        }
    }
}

// Eliminar
if ($tipo == "eliminar") {
    $id_categoria = isset($_POST['id']) ? $_POST['id'] : '';
    if ($id_categoria == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, ID vacío');
    } else {
        $existeId = $objCategoria->ver($id_categoria);
        if (!$existeId) {
            $arrResponse = array('status' => false, 'msg' => 'Error, categoría no existe en Base de Datos!!');
        } else {
            $eliminar = $objCategoria->eliminar($id_categoria);
            if ($eliminar) {
                $arrResponse = array('status' => true, 'msg' => "Eliminado correctamente");
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
            }
        }
    }
    echo json_encode($arrResponse);
    exit;
}