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

    //registral temporal
    public function registrar_temporal($id_producto,$precio, $cantidad){
        $consulta = "INSERT INTO temporal_venta (id_producto, precio, cantidad) 
        VALUES ('$id_producto', '$precio', '$cantidad')";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            return $this->conexion->insert_id;
        }
        return 0;
    }

    //actualizar

    public function actualizarCantidadTemporal($id_producto, $cantidad){
        $consulta = "UPDATE temporal_venta SET cantidad='$cantidad' WHERE id_producto='$id_producto'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }

    //buscar temporal
    public function buscarTemporal($id_producto)
    {
        $consulta = "SELECT * FROM temporal_venta WHERE id_producto='$id_producto'";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            return $sql->fetch_object();
        }
        return null;
    }

    //eliminar temporal
    public function eliminarTemporal($id)
    {
        $consulta = "DELETE FROM temporal_venta WHERE id='$id'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }

    //eliminar temporales
     public function eliminarTemporales()
    {
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
?>
