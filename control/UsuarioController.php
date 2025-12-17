<?php
require_once("../model/UsuarioModel.php");
require_once("../config/config.php");
$tipo = $_GET['tipo'];


if ($tipo == 'registrar') {
    $objPersona = new UsuarioModel();

    //print_r($_POST);
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
    //Encriptando nro_documento para utilizarlo como contraseña
    $password = password_hash($nro_identidad, PASSWORD_DEFAULT);

    if ($nro_identidad == "" || $razon_social == "" || $telefono == "" || $correo == "" || $departamento == "" || $provincia == "" || $distrito == "" || $cod_postal == "" || $direccion == "" || $rol == "") {

        $arrResponse = array('status' => false, 'msg' => 'Error,campos vacios');
    } else {
        //Validacion si existe Persona con el mismo dni
        $existePersona = $objPersona->existePersona($nro_identidad);
        if ($existePersona > 0) {
            $arrResponse = array('status' => false, 'msg' => 'Error,nro de documento ya existe');
        } else {
            $respuesta = $objPersona->registrar($nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol, $password);
            if ($respuesta) {
                $arrResponse = array('status' => true, 'msg' => 'Registrado Correctamente');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error,falló en registro');
            }
        }
    }
    echo json_encode($arrResponse);
}
if ($tipo == "iniciar_sesion") {
    try {
        $objPersona = new UsuarioModel();
        $nro_identidad = isset($_POST['usuario']) ? $_POST['usuario'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($nro_identidad == "" || $password == "") {
            echo json_encode(['status' => false, 'msg' => 'Error, campos vacios']);
            exit;
        }

        $existePersona = $objPersona->existePersona($nro_identidad);
        if (!$existePersona) {
            echo json_encode(['status' => false, 'msg' => 'Error, usuario no existe']);
            exit;
        }

        $persona = $objPersona->buscarPersonaPornNroIdentidad($nro_identidad);
        if (!$persona) {
            echo json_encode(['status' => false, 'msg' => 'Error, usuario no encontrado']);
            exit;
        }

        // Verificación con hash o en texto plano (fallback)
        $verificado = false;
        if (!empty($persona->password)) {
            $verificado = password_verify($password, $persona->password) || $password === $persona->password;
        }
        if ($verificado) {
            session_start();
            $_SESSION['ventas_id'] = $persona->id;
            $_SESSION['ventas_usuario'] = $persona->razon_social;
            echo json_encode(['status' => true, 'msg' => 'ok']);
            exit;
        } else {
            echo json_encode(['status' => false, 'msg' => 'Error, contraseña incorrecta']);
            exit;
        }
    } catch (Throwable $e) {
        if (defined('DEBUG') && DEBUG) {
            echo json_encode(['status' => false, 'msg' => 'Error interno en el servidor', 'error' => $e->getMessage()]);
        } else {
            echo json_encode(['status' => false, 'msg' => 'Error interno en el servidor']);
        }
        exit;
    }
}


//ver usuarios
if ($tipo == "ver_usuarios") {
    try {
        $objPersona = new UsuarioModel();
        $usuarios = $objPersona->verUsuarios();
        echo json_encode($usuarios);
    } catch (Throwable $e) {
        echo json_encode(['status' => false, 'msg' => 'Error interno en el servidor', 'error' => (defined('DEBUG') && DEBUG) ? $e->getMessage() : null]);
    }
}


// Ver usuario
if ($tipo == "ver") {
    //print_r($_POST);
    $respuesta = array('status' => false, 'msg' => 'Error');
    $id_persona = $_POST['id_persona'];
    $objPersona = new UsuarioModel();
    $usuario = $objPersona->ver($id_persona);
    if ($usuario) {
        $respuesta['status'] = true;
        $respuesta['data'] = $usuario;
    } else {
        $respuesta['msg'] = 'Error, usuario no existe';
    }
    echo json_encode($respuesta);
}


// Actualizar
if ($tipo == "actualizar") {
    //print_r($_POST);
    $id_persona = $_POST['id_persona'];
    $objPersona = new UsuarioModel();
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
        $arrResponse = array('status' => false, 'msg' => 'Error,campos vacios');
    } else {
        $existeID = $objPersona->ver($id_persona);
        if (!$existeID) {
            //devolver msm
            $arrResponse = array('status' => false, 'msg' => 'Error, usuario, no existe en BD');
            echo json_encode($arrResponse);
            //cerrar funcion
            exit;
        } else {
            //actualizar
            $actualizar = $objPersona->actualizar($id_persona, $nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol);
            if ($actualizar) {
                $arrResponse = array('status' => true, 'msg' => "actualizado correctamente");
            } else {
                $arrResponse = array('status' => false, 'msg' => $actualizar);
            }
            echo json_encode($arrResponse);
            exit;
        }
    }
}


// ELIMINAR
if ($tipo == "eliminar") {

    $id_persona = isset($_POST['id']) ? $_POST['id'] : '';
    $objPersona = new UsuarioModel();

    if ($id_persona == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, ID vacío');
    } else {
        $existeId = $objPersona->ver($id_persona);
        if (!$existeId) {
            $arrResponse = array('status' => false, 'msg' => 'Error, usuario no existe en Base de Datos!!');
        } else {
            $eliminar = $objPersona->eliminar($id_persona);
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

// Ver clientes
if ($tipo == "ver_clients") {
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $objPersona = new UsuarioModel();
    $usuarios = $objPersona->verClientes();
    if (count($usuarios)) {
        $respuesta = array('status' => true, 'msg' => '', 'data' => $usuarios);
    }
    echo json_encode($respuesta);
}

// Ver proveedores
if ($tipo == "ver_proveedores") {
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $objPersona = new UsuarioModel();
    $usuarios = $objPersona->verProveedores();
    if (count($usuarios)) {
        $respuesta = array('status' => true, 'msg' => '', 'data' => $usuarios);
    }
    echo json_encode($respuesta);
}
