<?php
require_once("../library/conexion.php");
class ClientsModel
{
    private $conexion;
    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    // Registrar cliente o proveedor (reutiliza estructura de UsuarioModel)
    public function registrar($nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol, $password)
    {
        // Usar prepared statement para evitar inyecciones y manejar errores
        $stmt = $this->conexion->prepare("INSERT INTO persona (nro_identidad, razon_social, telefono, correo, departamento, provincia, distrito, cod_postal, direccion, rol, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            // error en la preparación
            error_log('ClientsModel::registrar prepare error: ' . $this->conexion->error);
            return 0;
        }
        $stmt->bind_param('sssssssssss', $nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol, $password);
        $ok = $stmt->execute();
        if ($ok) {
            $insert_id = $this->conexion->insert_id;
        } else {
            error_log('ClientsModel::registrar execute error: ' . $stmt->error);
            $insert_id = 0;
        }
        $stmt->close();
        return $insert_id;
    }

    public function existePersona($nro_identidad)
    {
        $consulta = "SELECT * FROM persona WHERE nro_identidad= '$nro_identidad'";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }

    public function verClientes()
    {
        $arr_items = array();
        $consulta = "SELECT * FROM persona WHERE rol='Cliente'";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_items, $objeto);
        }
        return $arr_items;
    }

    public function verProveedores()
    {
        $arr_items = array();
        $consulta = "SELECT * FROM persona WHERE rol='Proveedor'";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_items, $objeto);
        }
        return $arr_items;
    }

    public function ver($id)
    {
        $consulta = "SELECT * FROM persona WHERE id='$id'";
        $sql = $this->conexion->query($consulta);
        return $sql->fetch_object();
    }

    public function actualizar($id_persona, $nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol)
    {
        $consulta = "UPDATE persona SET nro_identidad ='$nro_identidad', razon_social= '$razon_social', telefono='$telefono', correo='$correo', departamento='$departamento', provincia='$provincia', distrito='$distrito', cod_postal='$cod_postal', direccion='$direccion', rol='$rol' WHERE id= '$id_persona'";
        $sql = $this->conexion->query(($consulta));
        return $sql;
    }

    public function eliminar($id_persona)
    {
        $consulta = "UPDATE persona SET estado=0 WHERE id='$id_persona'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }
}