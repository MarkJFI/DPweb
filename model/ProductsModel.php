<?php
require_once("../library/conexion.php");

class ProductModel
{
    private $conexion;

    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrarProducto($codigo, $nombre, $detalle, $precio, $stock, $categoria, $fecha_vencimiento, $imagen, $proveedor, $estado)
    {
        // Normalizar proveedor: si viene vacío o no numérico, insertar NULL
        $proveedor_val = (is_numeric($proveedor) && intval($proveedor) > 0) ? intval($proveedor) : null;
        $proveedor_sql = is_null($proveedor_val) ? "NULL" : intval($proveedor_val);

        // Escapar strings básicos para evitar errores (nota: ideal usar prepared statements)
        $codigo = $this->conexion->real_escape_string($codigo);
        $nombre = $this->conexion->real_escape_string($nombre);
        $detalle = $this->conexion->real_escape_string($detalle);
        $fecha_vencimiento = $this->conexion->real_escape_string($fecha_vencimiento);
        $imagen = $this->conexion->real_escape_string($imagen);

        $consulta = "INSERT INTO productos(codigo, nombre, detalle, precio, stock, categoria, fecha_vencimiento, imagen, proveedor, estado, fecha_creacion) VALUES ('{$codigo}', '{$nombre}', '{$detalle}', {$precio}, {$stock}, {$categoria}, '{$fecha_vencimiento}', '{$imagen}', {$proveedor_sql}, {$estado}, NOW())";
        $sql = $this->conexion->query($consulta);
        $arr_respuesta = array('status' => false, 'id' => "");

        if ($sql) {
            $arr_respuesta['status'] = true;
            $arr_respuesta['id'] = $this->conexion->insert_id;

            // Registrar movimiento inicial de stock
            $this->registrarMovimiento($arr_respuesta['id'], 'entrada', $stock, $precio, 'Stock inicial del producto');
        } else {
            $arr_respuesta['status'] = false;
            $arr_respuesta['error'] = $this->conexion->error;
        }
        return $arr_respuesta;
    }

    public function obtenerProductos()
    {
        $sql = $this->conexion->query("SELECT p.*, c.nombre as categoria_nombre
                                      FROM productos p 
                                      LEFT JOIN categoria c ON p.categoria = c.id 
                                      ORDER BY p.id DESC");
        $sql = $sql->fetch_all(MYSQLI_ASSOC);
        return $sql;
    }

    public function verProducto($id)
    {
        $sql = $this->conexion->query("SELECT p.*, c.nombre as categoria_nombre
                                      FROM productos p 
                                      LEFT JOIN categoria c ON p.categoria = c.id 
                                      WHERE p.id='{$id}'");
        $sql = $sql->fetch_assoc();
        return $sql;
    }

    public function obtenerRegistrosProducto($id_producto)
    {
        $sql = $this->conexion->query("SELECT m.*, u.nombres as usuario_nombre 
                                      FROM movimientos_producto m 
                                      LEFT JOIN usuarios u ON m.usuario_id = u.id 
                                      WHERE m.id_producto = '{$id_producto}' 
                                      ORDER BY m.fecha_creacion DESC");
        $sql = $sql->fetch_all(MYSQLI_ASSOC);
        return $sql;
    }

    public function actualizarProducto($id, $codigo, $nombre, $detalle, $precio, $stock, $categoria, $fecha_vencimiento, $imagen, $proveedor, $estado)
    {
        // Obtener stock actual para comparar
        $producto_actual = $this->verProducto($id);
        $stock_anterior = $producto_actual['stock'];

        $sql = $this->conexion->query("UPDATE productos SET codigo='{$codigo}', nombre='{$nombre}', detalle='{$detalle}', precio='{$precio}', stock='{$stock}', categoria='{$categoria}', fecha_vencimiento='{$fecha_vencimiento}', imagen='{$imagen}', proveedor='{$proveedor}', estado='{$estado}', fecha_actualizacion=NOW() WHERE id='{$id}'");

        // Si cambió el stock, registrar el movimiento
        if ($sql && $stock != $stock_anterior) {
            $diferencia = $stock - $stock_anterior;
            $tipo_movimiento = $diferencia > 0 ? 'entrada' : 'salida';
            $cantidad = abs($diferencia);
            $this->registrarMovimiento($id, $tipo_movimiento, $cantidad, $precio, 'Ajuste por actualización de producto');
        }

        return $sql;
    }

    public function eliminarProducto($id)
    {
        // Primero eliminar los movimientos asociados
        $this->conexion->query("DELETE FROM movimientos_producto WHERE id_producto='{$id}'");
        // Luego eliminar el producto
        $sql = $this->conexion->query("DELETE FROM productos WHERE id='{$id}'");
        return $sql;
    }

    public function obtenerRegistro($id_registro)
    {
        $sql = $this->conexion->query("SELECT * FROM movimientos_producto WHERE id='{$id_registro}'");
        $sql = $sql->fetch_assoc();
        return $sql;
    }

    public function editarRegistro($id_registro, $tipo_movimiento, $cantidad, $precio_unitario, $descripcion, $estado)
    {
        $sql = $this->conexion->query("UPDATE movimientos_producto SET tipo_movimiento='{$tipo_movimiento}', cantidad='{$cantidad}', precio_unitario='{$precio_unitario}', descripcion='{$descripcion}', estado='{$estado}', fecha_actualizacion=NOW() WHERE id='{$id_registro}'");
        return $sql;
    }

    public function eliminarRegistro($id_registro)
    {
        $sql = $this->conexion->query("DELETE FROM movimientos_producto WHERE id='{$id_registro}'");
        return $sql;
    }

    public function registrarRegistro($id_producto, $tipo_movimiento, $cantidad, $precio_unitario, $descripcion, $estado)
    {
        $sql = $this->conexion->query("INSERT INTO movimientos_producto(id_producto, tipo_movimiento, cantidad, precio_unitario, descripcion, estado, fecha_creacion) VALUES('{$id_producto}', '{$tipo_movimiento}', '{$cantidad}', '{$precio_unitario}', '{$descripcion}', '{$estado}', NOW())");

        // Actualizar stock del producto
        if ($sql) {
            $this->actualizarStockProducto($id_producto, $tipo_movimiento, $cantidad);
        }

        return $sql;
    }

    private function registrarMovimiento($id_producto, $tipo_movimiento, $cantidad, $precio_unitario, $descripcion)
    {
        $sql = $this->conexion->query("INSERT INTO movimientos_producto(id_producto, tipo_movimiento, cantidad, precio_unitario, descripcion, estado, fecha_creacion) VALUES('{$id_producto}', '{$tipo_movimiento}', '{$cantidad}', '{$precio_unitario}', '{$descripcion}', '1', NOW())");
        return $sql;
    }

    private function actualizarStockProducto($id_producto, $tipo_movimiento, $cantidad)
    {
        if ($tipo_movimiento == 'entrada') {
            $sql = $this->conexion->query("UPDATE productos SET stock = stock + {$cantidad} WHERE id = '{$id_producto}'");
        } else if ($tipo_movimiento == 'salida') {
            $sql = $this->conexion->query("UPDATE productos SET stock = stock - {$cantidad} WHERE id = '{$id_producto}'");
        }
        return $sql ?? false;
    }

    public function buscarProducto($termino)
    {
        $sql = $this->conexion->query("SELECT p.*, c.nombre as categoria_nombre
                                      FROM productos p 
                                      LEFT JOIN categoria c ON p.categoria = c.id 
                                      WHERE p.codigo LIKE '%{$termino}%' OR p.nombre LIKE '%{$termino}%' 
                                      ORDER BY p.nombre ASC");
        $sql = $sql->fetch_all(MYSQLI_ASSOC);
        return $sql;
    }

    public function obtenerProductosPorCategoria($id_categoria)
    {
        $sql = $this->conexion->query("SELECT p.*, c.nombre as categoria_nombre
                                      FROM productos p 
                                      LEFT JOIN categoria c ON p.categoria = c.id 
                                      WHERE p.categoria = '{$id_categoria}' 
                                      ORDER BY p.nombre ASC");
        $sql = $sql->fetch_all(MYSQLI_ASSOC);
        return $sql;
    }

    public function obtenerProductosBajoStock($limite_stock = 10)
    {
        $sql = $this->conexion->query("SELECT p.*, c.nombre as categoria_nombre
                                      FROM productos p 
                                      LEFT JOIN categoria c ON p.categoria = c.id 
                                      WHERE p.stock <= {$limite_stock} AND p.estado = 1 
                                      ORDER BY p.stock ASC");
        $sql = $sql->fetch_all(MYSQLI_ASSOC);
        return $sql;
    }

    public function obtenerProductosVencidos($dias_limite = 30)
    {
        $sql = $this->conexion->query("SELECT p.*, c.nombre as categoria_nombre,
                                      DATEDIFF(p.fecha_vencimiento, CURDATE()) as dias_restantes
                                      FROM productos p 
                                      LEFT JOIN categoria c ON p.categoria = c.id 
                                      WHERE p.fecha_vencimiento IS NOT NULL 
                                      AND DATEDIFF(p.fecha_vencimiento, CURDATE()) <= {$dias_limite}
                                      AND p.estado = 1 
                                      ORDER BY p.fecha_vencimiento ASC");
        $sql = $sql->fetch_all(MYSQLI_ASSOC);
        return $sql;
    }

    public function actualizarStock($id_producto, $nuevo_stock, $tipo_movimiento, $descripcion)
    {
        // Obtener stock actual
        $producto = $this->verProducto($id_producto);
        $stock_actual = $producto['stock'];
        $diferencia = abs($nuevo_stock - $stock_actual);

        // Actualizar stock
        $sql = $this->conexion->query("UPDATE productos SET stock = '{$nuevo_stock}', fecha_actualizacion = NOW() WHERE id = '{$id_producto}'");

        // Registrar movimiento
        if ($sql && $diferencia > 0) {
            $this->registrarMovimiento($id_producto, $tipo_movimiento, $diferencia, $producto['precio'], $descripcion);
        }

        return $sql;
    }

    public function obtenerCategorias()
    {
        $sql = $this->conexion->query("SELECT id, nombre FROM categoria WHERE estado = 1 ORDER BY nombre ASC");
        $sql = $sql->fetch_all(MYSQLI_ASSOC);
        return $sql;
    }

    public function obtenerProveedores()
    {
        $sql = $this->conexion->query("SELECT id, nombre, telefono, email FROM proveedores WHERE estado = 1 ORDER BY nombre ASC");
        $sql = $sql->fetch_all(MYSQLI_ASSOC);
        return $sql;
    }

    public function contarProductos()
    {
        $sql = $this->conexion->query("SELECT COUNT(*) as total FROM productos WHERE estado = 1");
        $result = $sql->fetch_assoc();
        return $result['total'];
    }

    public function contarProductosBajoStock($limite = 10)
    {
        $sql = $this->conexion->query("SELECT COUNT(*) as total FROM productos WHERE stock <= {$limite} AND estado = 1");
        $result = $sql->fetch_assoc();
        return $result['total'];
    }

    public function contarProductosVencidos($dias = 30)
    {
        $sql = $this->conexion->query("SELECT COUNT(*) as total FROM productos WHERE fecha_vencimiento IS NOT NULL AND DATEDIFF(fecha_vencimiento, CURDATE()) <= {$dias} AND estado = 1");
        $result = $sql->fetch_assoc();
        return $result['total'];
    }

    public function obtenerEstadisticasProductos()
    {
        $estadisticas = array();
        $estadisticas['total_productos'] = $this->contarProductos();
        $estadisticas['productos_bajo_stock'] = $this->contarProductosBajoStock();
        $estadisticas['productos_vencidos'] = $this->contarProductosVencidos();

        // Valor total del inventario
        $sql = $this->conexion->query("SELECT SUM(precio * stock) as valor_total FROM productos WHERE estado = 1");
        $result = $sql->fetch_assoc();
        $estadisticas['valor_inventario'] = $result['valor_total'] ?? 0;

        return $estadisticas;
    }

    public function verificarCodigoExiste($codigo, $id_excluir = null)
    {
        $where_clause = "codigo = '{$codigo}'";
        if ($id_excluir) {
            $where_clause .= " AND id != '{$id_excluir}'";
        }

        $sql = $this->conexion->query("SELECT COUNT(*) as total FROM productos WHERE {$where_clause}");
        $result = $sql->fetch_assoc();
        return $result['total'] > 0;
    }

    public function obtenerProductosPorProveedor($id_proveedor)
    {
        $id_proveedor = intval($id_proveedor);
        $sql = $this->conexion->query("SELECT p.*, c.nombre as categoria_nombre 
                                      FROM productos p 
                                      LEFT JOIN categoria c ON p.categoria = c.id 
                                      WHERE p.proveedor = {$id_proveedor} 
                                      ORDER BY p.nombre ASC");
        $sql = $sql->fetch_all(MYSQLI_ASSOC);
        return $sql;
    }
}
