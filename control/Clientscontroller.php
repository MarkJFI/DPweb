<?php
require_once("../model/ClientsModel.php");

$obj = new ClientsModel();

$tipo = $_GET['tipo'];

if ($tipo == "registrar") {
    $nro_identidad = $_POST['nro_identidad'];
    $razon_social = $_POST['razon_social'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $departamento = $_POST['departamento'];
    $provincia = $_POST['provincia'];
    $distrito = $_POST['distrito'];
    $cod_postal = $_POST['cod_postal'];
    $direccion = $_POST['direccion'];
    $rol = $_POST['rol']; // "Cliente" o "Proveedor"

    $password = password_hash($nro_identidad, PASSWORD_DEFAULT);

    if ($nro_identidad == "" || $razon_social == "" || $telefono == "" || $correo == "" || $departamento == "" || $provincia == "" || $distrito == "" || $cod_postal == "" || $direccion == "" || $rol == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacios');
    } else {
        $existePersona = $obj->existePersona($nro_identidad);
        if ($existePersona > 0) {
            $arrResponse = array('status' => false, 'msg' => 'Error, numero de documento ya existe');
        } else {
            $respuesta = $obj->registrar($nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol, $password);
            if ($respuesta) {
                $arrResponse = array('status' => true, 'msg' => 'Registrado correctamente');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error, fallo en registro');
            }
        }
    }
    echo json_encode($arrResponse);
}

if ($tipo == "ver_clientes") {
    $respuesta = array('status' => false, 'msg' => 'No se encontraron clientes');
    $items = $obj->verClientes();
    if (count($items)) {
        $respuesta = array('status' => true, 'msg' => '', 'data' => $items);
    }
    echo json_encode($respuesta);
}

if ($tipo == "ver_proveedores") {
    $respuesta = array('status' => false, 'msg' => 'No se encontraron proveedores');
    $items = $obj->verProveedores();
    if (count($items)) {
        $respuesta = array('status' => true, 'msg' => '', 'data' => $items);
    }
    echo json_encode($respuesta);
}

if ($tipo == "ver") {
    $id_persona = $_POST['id_persona'];
    $respuesta = array('status' => false, 'msg' => 'No encontrado');
    $persona = $obj->ver($id_persona);
    if ($persona) {
        $respuesta = array('status' => true, 'msg' => '', 'data' => $persona);
    }
    echo json_encode($respuesta);
}

if ($tipo == "actualizar") {
    $id_persona = $_POST['id_persona'];
    $nro_identidad = $_POST['nro_identidad'];
    $razon_social = $_POST['razon_social'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $departamento = $_POST['departamento'];
    $provincia = $_POST['provincia'];
    $distrito = $_POST['distrito'];
    $cod_postal = $_POST['cod_postal'];
    $direccion = $_POST['direccion'];
    $rol = $_POST['rol'];
    if ($id_persona == "" || $nro_identidad == "" || $razon_social == "" || $telefono == "" || $correo == "" || $departamento == "" || $provincia == "" || $distrito == "" || $cod_postal == "" || $direccion == "" || $rol == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacios');
    } else {
        $existeID = $obj->ver($id_persona);
        if (!$existeID) {
            $arrResponse = array('status' => false, 'msg' => 'Error, no existe en BD');
        } else {
            $actualizar = $obj->actualizar($id_persona, $nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol);
            if ($actualizar) {
                $arrResponse = array('status' => true, 'msg' => 'Actualizado correctamente');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al actualizar');
            }
        }
    }
    echo json_encode($arrResponse);
}

if ($tipo == "eliminar") {
    $id_persona = $_POST['id_persona'];
    $respuesta = array('status' => false, 'msg' => '');
    $resultado = $obj->eliminar($id_persona);
    if ($resultado) {
        $respuesta = array('status' => true, 'msg' => 'Eliminado correctamente');
    } else {
        $respuesta = array('status' => false, 'msg' => 'Error al eliminar');
    }
    echo json_encode($respuesta);
}