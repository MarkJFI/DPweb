<?php
require_once("../model/UsuarioModel.php");
$objPersona = new UsuarioModel();

$tipo = $_GET['tipo'];

if ($tipo == "registrar"){
    //print_r($_POST);
    $nro_documento = $_POST['nro_documento'];
    $razon_social = $_POST['razon_social'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $departamento = $_POST['departamento'];
    $provincia = $_POST['provincia'];
    $distrito = $_POST['distrito'];
    $cod_postal = $_POST['cod_postal'];
    $direccion = $_POST['direccion'];
    $rol = $_POST['rol'];
    // ENCRIPTANDO DNI PARA UTILIZARLO COMO CONTRASEÑA
    $password = password_hash($nro_documento,PASSWORD_DEFAULT);

    if ($nro_documento =="" || $razon_social =="" ||  $telefono =="" || $correo =="" || $departamento ==""||
    $provincia=="" || $distrito=="" || $distrito=="" || $cod_postal =="" || $direccion == "" ||  $rol == "") {
        $arrResponse = array('status' =>false, 'msg'=>'Error, campos vacios');
    }else{
        $respuesta = $objPersona->registrar($nro_documento,$razon_social,$telefono,$correo,$departamento,$provincia,$distrito,$cod_postal,$direccion,$rol,$password);
        $arrResponse = array('status' =>true, 'msg'=>'Procedemos a registrar');
    }
    echo json_encode($arrResponse); 

}