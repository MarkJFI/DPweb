<?php
require_once __DIR__ . "/../library/conexion.php";

class UsuarioModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    // REGISTRAR
    public function registrar($nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol, $password)
    {
        $consulta = "INSERT INTO persona 
        (nro_identidad,razon_social,telefono,correo,departamento,provincia,distrito,cod_postal,direccion,rol,password)
        VALUES ('$nro_identidad','$razon_social','$telefono','$correo','$departamento','$provincia','$distrito','$cod_postal','$direccion','$rol','$password')";

        $sql = $this->conexion->query($consulta);
        return $sql ? $this->conexion->insert_id : 0;
    }

    // EXISTE PERSONA
    public function existePersona($nro_identidad)
    {
        $consulta = "SELECT id FROM persona WHERE nro_identidad='$nro_identidad' LIMIT 1";
        $sql = $this->conexion->query($consulta);

        if (!$sql) return 0;
        return $sql->num_rows;
    }

    // BUSCAR PERSONA POR DNI
    public function buscarPersonaPornNroIdentidad($nro_identidad)
    {
        $consulta = "SELECT id, razon_social, password FROM persona 
                     WHERE nro_identidad='$nro_identidad' LIMIT 1";
        $sql = $this->conexion->query($consulta);

        if (!$sql || $sql->num_rows == 0) {
            return null;
        }
        return $sql->fetch_object();
    }

    // VER USUARIOS
    public function verUsuarios()
    {
        $data = [];
        $sql = $this->conexion->query("SELECT * FROM persona WHERE rol<>'Cliente' AND rol<>'Proveedor'");
        while ($row = $sql->fetch_object()) {
            $data[] = $row;
        }
        return $data;
    }

    // VER UNO
    public function ver($id)
    {
        $sql = $this->conexion->query("SELECT * FROM persona WHERE id='$id' LIMIT 1");
        return $sql ? $sql->fetch_object() : null;
    }

    // ACTUALIZAR
    public function actualizar($id, $nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol)
    {
        return $this->conexion->query(
            "UPDATE persona SET 
            nro_identidad='$nro_identidad',
            razon_social='$razon_social',
            telefono='$telefono',
            correo='$correo',
            departamento='$departamento',
            provincia='$provincia',
            distrito='$distrito',
            cod_postal='$cod_postal',
            direccion='$direccion',
            rol='$rol'
            WHERE id='$id'"
        );
    }

    // ELIMINAR
    public function eliminar($id)
    {
        return $this->conexion->query("DELETE FROM persona WHERE id='$id'");
    }

    // CLIENTES
    public function verClientes()
    {
        $data = [];
        $sql = $this->conexion->query("SELECT * FROM persona WHERE rol='Cliente'");
        while ($row = $sql->fetch_object()) {
            $data[] = $row;
        }
        return $data;
    }

    // PROVEEDORES
    public function verProveedores()
    {
        $data = [];
        $sql = $this->conexion->query("SELECT * FROM persona WHERE rol='Proveedor'");
        while ($row = $sql->fetch_object()) {
            $data[] = $row;
        }
        return $data;
    }
}   
