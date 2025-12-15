<?php
require_once("../library/conexion.php");
class VentaModel
{
    private $conexion;
    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrar_temporal($id_producto, $precio, $cantidad){
        $sql = "INSERT INTO temporal_venta(id_producto, precio, cantidad) VALUES
        ('$id_producto','$precio','$cantidad')";
        $resultado = $this->conexion->query($sql);
        if ($resultado){
            return $this->conexion->insert_id;
        }
        return 0;
    }

    public function actualizarCantidadTemporal($id_producto, $cantidad){
        $consulta = "UPDATE temporal_venta SET cantidad='$cantidad' WHERE id_producto='$id_producto'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }

    public function buscarTemporalPorId($id_producto){
        $consulta = "SELECT * FROM temporal_venta WHERE id_producto='$id_producto' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        if ($sql && $sql->num_rows > 0) {
            return $sql->fetch_object();
        }
        return null;
    }

    public function buscarTemporal(){
        $arr_temporal = array();
        $consulta = "SELECT * FROM temporal_venta";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_temporal, $objeto);
        }
        return $arr_temporal;
    }

    public function eliminarTemporal($id_producto){
        $consulta = "DELETE FROM temporal_venta WHERE id_producto='$id_producto'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }

    public function eliminarTemporales(){
        $consulta = "DELETE FROM temporal_venta";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }

    // VENTAS REGISTRADAS (OFICIALES) - métodos a implementar aquí si es necesario


    public function buscar_ultima_venta(){
        $consulta = "SELECT codigo FROM ventas ORDER BY id DESC LIMIT 1";
        $sql = $this->conexion->query($consulta);
            return $sql->fetch_object();
    }
    public function registrar_venta($correlativo, $id_cliente, $id_vendedor, $fecha_hora){
        $sql = "INSERT INTO ventas(codigo, fecha_hora, id_cliente, id_vendedor) VALUES
        ('$correlativo','$fecha_hora','$id_cliente','$id_vendedor')";
        $resultado = $this->conexion->query($sql);
        if ($resultado){
            return $this->conexion->insert_id;
        }
        return 0;
    }

    public function registrar_detalle_venta($correlativo, $id_producto, $precio, $cantidad){
        $consulta = "INSERT INTO detalle_ventas (id_venta, id_producto, precio, cantidad) VALUES
        ('$correlativo','$id_producto','$precio','$cantidad')";
        $resultado = $this->conexion->query($consulta);
        return $resultado;
    }
}
