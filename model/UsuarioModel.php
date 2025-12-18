<?php
require_once(__DIR__ . "/../library/conexion.php"); // Ajusta la ruta si es necesario

class UsuarioModel
{
    private $conexion;

    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function buscarPersonaPorNroIdentidad($nro_identidad)
    {
        $nro_identidad = $this->conexion->real_escape_string($nro_identidad);
        $consulta = "SELECT * FROM persona WHERE nro_identidad = '$nro_identidad' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            return $sql->fetch_object();
        }
        return null;
    }

    public function registrar($nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol, $password)
    {
        $nro_identidad = $this->conexion->real_escape_string($nro_identidad);
        $razon_social = $this->conexion->real_escape_string($razon_social);
        $telefono = $this->conexion->real_escape_string($telefono);
        $correo = $this->conexion->real_escape_string($correo);
        $departamento = $this->conexion->real_escape_string($departamento);
        $provincia = $this->conexion->real_escape_string($provincia);
        $distrito = $this->conexion->real_escape_string($distrito);
        $cod_postal = $this->conexion->real_escape_string($cod_postal);
        $direccion = $this->conexion->real_escape_string($direccion);
        $rol = $this->conexion->real_escape_string($rol);
        $password = $this->conexion->real_escape_string($password); // Hashed password

        $consulta = "INSERT INTO persona (nro_identidad, razon_social, telefono, correo, departamento, provincia, distrito, cod_postal, direccion, rol, password) VALUES ('$nro_identidad', '$razon_social', '$telefono', '$correo', '$departamento', '$provincia', '$distrito', '$cod_postal', '$direccion', '$rol', '$password')";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            return $this->conexion->insert_id;
        }
        return 0;
    }

    public function existePersona($nro_identidad)
    {
        $nro_identidad = $this->conexion->real_escape_string($nro_identidad);
        $consulta = "SELECT id FROM persona WHERE nro_identidad = '$nro_identidad' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows > 0;
    }

    public function eliminar($id_persona)
    {
        $id_persona = intval($id_persona);
        $consulta = "DELETE FROM persona WHERE id = $id_persona";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }

    /**
     * Obtiene todos los usuarios (personas con rol 'usuario').
     * @return array Un array de objetos de usuario.
     */
    public function verUsuarios()
    {
        $arr_usuarios = array();
        $consulta = "SELECT * FROM persona WHERE rol='usuario' ORDER BY razon_social ASC"; // Asumiendo 'usuario' es el rol para usuarios
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_usuarios, $objeto);
        }
        return $arr_usuarios;
    }

    /**
     * Obtiene todos los clientes (personas con rol 'cliente').
     * @return array Un array de objetos de cliente.
     */
    public function verClientes()
    {
        $arr_clientes = array();
        $consulta = "SELECT * FROM persona WHERE rol='cliente' ORDER BY razon_social ASC"; // Asumiendo 'cliente' es el rol para clientes
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_clientes, $objeto);
        }
        return $arr_clientes;
    }

    /**
     * Obtiene todos los proveedores (personas con rol 'proveedor').
     * @return array Un array de objetos de proveedor.
     */
    public function verProveedores()
    {
        $arr_proveedores = array();
        $consulta = "SELECT * FROM persona WHERE rol='proveedor' ORDER BY razon_social ASC"; // Asumiendo 'proveedor' es el rol para proveedores
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_proveedores, $objeto);
        }
        return $arr_proveedores;
    }
}
?>