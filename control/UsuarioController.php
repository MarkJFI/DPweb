<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../model/UsuarioModel.php";

if (!isset($_GET['tipo'])) {
    echo json_encode(['status' => false, 'msg' => 'Tipo no definido']);
    exit;
}

$objPersona = new UsuarioModel();
$tipo = $_GET['tipo'];

// LOGIN
if ($tipo === "iniciar_sesion") {

    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($usuario === "" || $password === "") {
        echo json_encode(['status' => false, 'msg' => 'Campos vacíos']);
        exit;
    }

    $persona = $objPersona->buscarPersonaPornNroIdentidad($usuario);

    if (!$persona) {
        echo json_encode(['status' => false, 'msg' => 'Usuario no existe']);
        exit;
    }

    if (password_verify($password, $persona->password)) {
        session_start();
        $_SESSION['ventas_id'] = $persona->id;
        $_SESSION['ventas_usuario'] = $persona->razon_social;
        echo json_encode(['status' => true, 'msg' => 'ok']);
    } else {
        echo json_encode(['status' => false, 'msg' => 'Contraseña incorrecta']);
    }
    exit;
}

// REGISTRAR
if ($tipo === "registrar") {

    $campos = [
        'nro_identidad','razon_social','telefono','correo',
        'departamento','provincia','distrito','cod_postal',
        'direccion','rol'
    ];

    foreach ($campos as $c) {
        if (empty($_POST[$c])) {
            echo json_encode(['status' => false, 'msg' => 'Campos incompletos']);
            exit;
        }
    }

    if ($objPersona->existePersona($_POST['nro_identidad'])) {
        echo json_encode(['status' => false, 'msg' => 'Documento ya registrado']);
        exit;
    }

    $password = password_hash($_POST['nro_identidad'], PASSWORD_DEFAULT);

    $id = $objPersona->registrar(
        $_POST['nro_identidad'],
        $_POST['razon_social'],
        $_POST['telefono'],
        $_POST['correo'],
        $_POST['departamento'],
        $_POST['provincia'],
        $_POST['distrito'],
        $_POST['cod_postal'],
        $_POST['direccion'],
        $_POST['rol'],
        $password
    );

    echo json_encode([
        'status' => $id > 0,
        'msg' => $id > 0 ? 'Registrado correctamente' : 'Error al registrar'
    ]);
    exit;
}
// VER USUARIOS
if ($tipo === "ver_usuarios") {
    $data = $objPersona->verUsuarios();
    echo json_encode(['status' => true, 'data' => $data]);
    exit;
}   