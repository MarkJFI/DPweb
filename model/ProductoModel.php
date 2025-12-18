<?php
require_once("../library/conexion.php");
class ProductoModel
{
    private $conexion;
    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function verProductos()
    {
        $arr_productos = array();
        // Consulta optimizada para incluir el nombre de la categoría
        $consulta = "SELECT 
                        p.*,
                        c.nombre as categoria 
                     FROM producto p
                     LEFT JOIN categoria c ON p.id_categoria = c.id
                     ORDER BY p.nombre ASC";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_productos, $objeto);
        }
        return $arr_productos;
    }


    public function existeCodigo($codigo)
    {
        $codigo = $this->conexion->real_escape_string($codigo);
        $consulta = "SELECT id FROM producto WHERE codigo='$codigo' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }
    public function existeCategoria($nombre)
    {
        $consulta = "SELECT * FROM producto WHERE nombre='$nombre'";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }
    public function registrar($codigo, $nombre, $detalle, $precio, $stock, $id_categoria, $fecha_vencimiento, $imagen, $id_proveedor)
    {
        $codigo            = $this->conexion->real_escape_string($codigo);
        $nombre            = $this->conexion->real_escape_string($nombre);
        $detalle           = $this->conexion->real_escape_string($detalle);
        $precio            = floatval($precio);
        $stock             = intval($stock);
        $id_categoria      = intval($id_categoria);
        $fecha_vencimiento = $this->conexion->real_escape_string($fecha_vencimiento);
        $id_proveedor      = intval($id_proveedor);
        $imagen            = $this->conexion->real_escape_string($imagen);
        $consulta = "INSERT INTO producto (codigo, nombre, detalle, precio, stock, id_categoria, fecha_vencimiento, imagen, id_proveedor) VALUES ('$codigo', '$nombre', '$detalle', $precio, $stock, $id_categoria, '$fecha_vencimiento', '$imagen', '$id_proveedor')";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            return $this->conexion->insert_id;
        }
        return 0;
    }
    public function ver($id)
    {
        $consulta = "SELECT * FROM producto WHERE id='$id'";
        $sql = $this->conexion->query($consulta);
        return $sql->fetch_object();
    }

    //ACTUALIZAR PRODUCTO
    public function actualizar($id_producto, $codigo, $nombre, $detalle, $precio, $stock, $id_categoria, $fecha_vencimiento, $imagen, $id_proveedor)
    {
        // Sanitizar y preparar valores
        $id_producto      = intval($id_producto);
        $codigo           = $this->conexion->real_escape_string($codigo);
        $nombre           = $this->conexion->real_escape_string($nombre);
        $detalle          = $this->conexion->real_escape_string($detalle);
        $precio           = floatval($precio);
        $stock            = intval($stock);
        $id_categoria     = intval($id_categoria);
        $fecha_vencimiento = $this->conexion->real_escape_string($fecha_vencimiento);
        $id_proveedor     = intval($id_proveedor);
        $imagen           = $this->conexion->real_escape_string($imagen);

        $consulta = "UPDATE producto SET codigo='$codigo', nombre='$nombre', detalle='$detalle', precio=$precio, stock=$stock, id_categoria=$id_categoria, fecha_vencimiento='$fecha_vencimiento', imagen='$imagen', id_proveedor=$id_proveedor WHERE id='$id_producto'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }
    public function eliminar($id)
    {
        $consulta = "DELETE FROM producto WHERE id='$id'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }


    // BUSCAR PRODUCTO POR NOMBRE O CÓDIGO
    public function buscarProductoByNombreOrCodigo($dato)
    {
        $arr_productos = array();
        // Se sanitiza el dato para prevenir inyección SQL simple
        $dato_saneado = $this->conexion->real_escape_string($dato);
 
        // Consulta mejorada con LEFT JOIN para obtener el nombre del proveedor y la categoría
        $consulta = "SELECT 
                        p.*, 
                        prov.razon_social as proveedor,
                        cat.nombre as categoria
                     FROM producto p
                     LEFT JOIN persona prov ON p.id_proveedor = prov.id
                     LEFT JOIN categoria cat ON p.id_categoria = cat.id
                     WHERE p.codigo LIKE '$dato_saneado%' 
                        OR p.nombre LIKE '%$dato_saneado%' 
                        OR p.detalle LIKE '%$dato_saneado%'
                     ORDER BY p.nombre ASC";

        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_productos, $objeto);
        }
        return $arr_productos;
    }
}
