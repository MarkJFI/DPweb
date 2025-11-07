<?php
require_once __DIR__ . '/../library/conexion.php';

class ProductsModel
{
    private $conexion;

    function __construct()
    {
        // Usar el método estático de conexión
        $this->conexion = Conexion::connect();
    }

    public function registrarProducto($codigo, $nombre, $detalle, $precio, $stock, $categoria, $fecha_vencimiento, $imagen, $proveedor)
    {
        $arr_respuesta = ['status' => false, 'id' => ""];

        // Normalización de tipos/valores
        $precio = floatval($precio);
        $stock = intval($stock);
        $categoria = intval($categoria);
        $proveedor_val = (is_numeric($proveedor) && intval($proveedor) > 0) ? intval($proveedor) : null;
        $fecha_venc_val = (!empty($fecha_vencimiento)) ? $fecha_vencimiento : null; // Permitir NULL
        $imagen_val = ($imagen !== null && $imagen !== '') ? $imagen : null; // Permitir NULL

        $sql = "INSERT INTO producto(codigo, nombre, detalle, precio, stock, id_categoria, id_proveedor, fecha_vencimiento, imagen)
                VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            $arr_respuesta['error'] = $this->conexion->error;
            return $arr_respuesta;
        }

        // sss d i i s s s -> 9 params
        $stmt->bind_param(
            "sssdiiiss",
            $codigo,
            $nombre,
            $detalle,
            $precio,
            $stock,
            $categoria,
            $proveedor_val,
            $fecha_venc_val,
            $imagen_val
        );

        if ($stmt->execute()) {
            $arr_respuesta['status'] = true;
            $arr_respuesta['id'] = $this->conexion->insert_id;
            // Registrar movimiento inicial de stock (comentado porque la tabla no existe)
            // $this->registrarMovimiento($arr_respuesta['id'], 'entrada', $stock, $precio, 'Stock inicial del producto');
        } else {
            $arr_respuesta['error'] = $stmt->error;
        }
        $stmt->close();
        return $arr_respuesta;
    }

    public function obtenerProductos()
    {
        $res = $this->conexion->query("
        SELECT p.*, c.nombre AS categoria_nombre, pr.razon_social AS proveedor_nombre
        FROM producto p
        LEFT JOIN categoria c ON p.id_categoria = c.id
        LEFT JOIN persona pr ON p.id_proveedor = pr.id
        ORDER BY p.nombre ASC
    ");

        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }


    public function verProducto($id)
    {
        $sql = "SELECT p.*,
                       p.id_categoria,
                       p.id_proveedor,
                       c.nombre as categoria_nombre,
                       pr.razon_social as proveedor_nombre
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id
                LEFT JOIN persona pr ON p.id_proveedor = pr.id
                WHERE p.id = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return [];
        $id = intval($id);
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : [];
        $stmt->close();
        return $row ?: [];
    }

    public function obtenerRegistrosProducto($id_producto)
    {
        $sql = "SELECT m.*, u.nombres as usuario_nombre 
                FROM movimientos_producto m 
                LEFT JOIN usuarios u ON m.usuario_id = u.id 
                WHERE m.id_producto = ? 
                ORDER BY m.fecha_creacion DESC";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return [];
        $id_producto = intval($id_producto);
        $stmt->bind_param("i", $id_producto);
        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function actualizarProducto($id, $codigo, $nombre, $detalle, $precio, $stock, $categoria, $fecha_vencimiento, $imagen, $proveedor)
    {
        $id = intval($id);
        $precio = floatval($precio);
        $stock = intval($stock);
        $categoria = intval($categoria);
        $proveedor_val = (is_numeric($proveedor) && intval($proveedor) > 0) ? intval($proveedor) : null;
        $fecha_venc_val = (!empty($fecha_vencimiento)) ? $fecha_vencimiento : null;
        $imagen_val = ($imagen !== null && $imagen !== '') ? $imagen : null;

        // Obtener stock anterior
        $stock_anterior = 0;
        $stmtPrev = $this->conexion->prepare("SELECT stock FROM producto WHERE id = ?");
        if ($stmtPrev) {
            $stmtPrev->bind_param("i", $id);
            if ($stmtPrev->execute()) {
                $resPrev = $stmtPrev->get_result();
                if ($resPrev && $row = $resPrev->fetch_assoc()) {
                    $stock_anterior = intval($row['stock']);
                }
            }
            $stmtPrev->close();
        }

        $sql = "UPDATE producto
                SET codigo = ?, nombre = ?, detalle = ?, precio = ?, stock = ?, id_categoria = ?, id_proveedor = ?,
                    fecha_vencimiento = ?, imagen = ?
                WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param(
            "sssdiiissi",
            $codigo,
            $nombre,
            $detalle,
            $precio,
            $stock,
            $categoria,
            $proveedor_val,
            $fecha_venc_val,
            $imagen_val,
            $id
        );

        $ok = $stmt->execute();
        $stmt->close();

        if ($ok && $stock != $stock_anterior) {
            $diferencia = $stock - $stock_anterior;
            $tipo_movimiento = $diferencia > 0 ? 'entrada' : 'salida';
            $cantidad = abs($diferencia);
            // Registrar movimiento (comentado porque la tabla no existe)
            // $this->registrarMovimiento($id, $tipo_movimiento, $cantidad, $precio, 'Ajuste por actualización de producto');
        }

        return $ok;
    }

    public function eliminarProducto($id)
    {
        $id = intval($id);
        // Eliminar movimientos
        $stmtMov = $this->conexion->prepare("DELETE FROM movimientos_producto WHERE id_producto = ?");
        if ($stmtMov) {
            $stmtMov->bind_param("i", $id);
            $stmtMov->execute();
            $stmtMov->close();
        }
        // Eliminar producto
        $stmt = $this->conexion->prepare("DELETE FROM producto WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function obtenerRegistro($id_registro)
    {
        $id_registro = intval($id_registro);
        $stmt = $this->conexion->prepare("SELECT * FROM movimientos_producto WHERE id = ?");
        if (!$stmt) return [];
        $stmt->bind_param("i", $id_registro);
        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : [];
        $stmt->close();
        return $row ?: [];
    }

    public function editarRegistro($id_registro, $tipo_movimiento, $cantidad, $precio_unitario, $descripcion, $estado)
    {
        $id_registro = intval($id_registro);
        $cantidad = intval($cantidad);
        $precio_unitario = floatval($precio_unitario);
        $estado = intval($estado);

        $sql = "UPDATE movimientos_producto 
                SET tipo_movimiento = ?, cantidad = ?, precio_unitario = ?, descripcion = ?, estado = ?, fecha_actualizacion = NOW() 
                WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("sidsii", $tipo_movimiento, $cantidad, $precio_unitario, $descripcion, $estado, $id_registro);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function eliminarRegistro($id_registro)
    {
        $id_registro = intval($id_registro);
        $stmt = $this->conexion->prepare("DELETE FROM movimientos_producto WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id_registro);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function registrarRegistro($id_producto, $tipo_movimiento, $cantidad, $precio_unitario, $descripcion, $estado)
    {
        $id_producto = intval($id_producto);
        $cantidad = intval($cantidad);
        $precio_unitario = floatval($precio_unitario);
        $estado = intval($estado);

        $sql = "INSERT INTO movimientos_producto(id_producto, tipo_movimiento, cantidad, precio_unitario, descripcion, estado, fecha_creacion) 
                VALUES(?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("isidsi", $id_producto, $tipo_movimiento, $cantidad, $precio_unitario, $descripcion, $estado);
        $ok = $stmt->execute();
        $stmt->close();

        // Actualizar stock del producto si insertó correctamente
        if ($ok) {
            $this->actualizarStockProducto($id_producto, $tipo_movimiento, $cantidad);
        }

        return $ok;
    }

    private function registrarMovimiento($id_producto, $tipo_movimiento, $cantidad, $precio_unitario, $descripcion)
    {
        $id_producto = intval($id_producto);
        $cantidad = intval($cantidad);
        $precio_unitario = floatval($precio_unitario);
        $sql = "INSERT INTO movimientos_producto(id_producto, tipo_movimiento, cantidad, precio_unitario, descripcion, estado, fecha_creacion) 
                VALUES(?, ?, ?, ?, ?, 1, NOW())";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("isids", $id_producto, $tipo_movimiento, $cantidad, $precio_unitario, $descripcion);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    private function actualizarStockProducto($id_producto, $tipo_movimiento, $cantidad)
    {
        $id_producto = intval($id_producto);
        $cantidad = intval($cantidad);

        // Obtener stock actual
        $stock_actual = 0;
        $stmtPrev = $this->conexion->prepare("SELECT stock FROM producto WHERE id = ?");
        if ($stmtPrev) {
            $stmtPrev->bind_param("i", $id_producto);
            if ($stmtPrev->execute()) {
                $resPrev = $stmtPrev->get_result();
                if ($resPrev && $row = $resPrev->fetch_assoc()) {
                    $stock_actual = intval($row['stock']);
                }
            }
            $stmtPrev->close();
        }

        $nuevo_stock = ($tipo_movimiento === 'entrada') ? ($stock_actual + $cantidad) : max(0, $stock_actual - $cantidad);

        $stmt = $this->conexion->prepare("UPDATE producto SET stock = ?, fecha_actualizacion = NOW() WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("ii", $nuevo_stock, $id_producto);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function buscarProducto($termino)
    {
        $pattern = "%" . $termino . "%";
        $sql = "SELECT p.*, c.nombre as categoria_nombre
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id
                WHERE p.codigo LIKE ? OR p.nombre LIKE ?
                ORDER BY p.nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("ss", $pattern, $pattern);
        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function obtenerProductosPorCategoria($id_categoria)
    {
        $id_categoria = intval($id_categoria);
        $sql = "SELECT p.*, c.nombre as categoria_nombre
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id
                WHERE p.id_categoria = ?
                ORDER BY p.nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $id_categoria);
        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function obtenerProductosBajoStock($limite_stock = 10)
    {
        $limite_stock = intval($limite_stock);
        $sql = "SELECT p.*, c.nombre as categoria_nombre
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id
                WHERE p.stock <= ? AND p.estado = 1
                ORDER BY p.stock ASC";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $limite_stock);
        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function obtenerProductosVencidos($dias_limite = 30)
    {
        $dias_limite = intval($dias_limite);
        $sql = "SELECT p.*, c.nombre as categoria_nombre,
                       DATEDIFF(p.fecha_vencimiento, CURDATE()) as dias_restantes
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id
                WHERE p.fecha_vencimiento IS NOT NULL
                  AND DATEDIFF(p.fecha_vencimiento, CURDATE()) <= ?
                  AND p.estado = 1
                ORDER BY p.fecha_vencimiento ASC";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $dias_limite);
        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function actualizarStock($id_producto, $nuevo_stock, $tipo_movimiento, $descripcion)
    {
        $id_producto = intval($id_producto);
        $nuevo_stock = intval($nuevo_stock);

        // Obtener producto actual
        $producto = $this->verProducto($id_producto);
        if (empty($producto)) return false;
        $stock_actual = intval($producto['stock']);
        $precio_actual = isset($producto['precio']) ? floatval($producto['precio']) : 0.0;

        // Actualizar stock
        $stmt = $this->conexion->prepare("UPDATE producto SET stock = ?, fecha_actualizacion = NOW() WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("ii", $nuevo_stock, $id_producto);
        $ok = $stmt->execute();
        $stmt->close();

        // Registrar movimiento
        $diferencia = abs($nuevo_stock - $stock_actual);
        if ($ok && $diferencia > 0) {
            $this->registrarMovimiento($id_producto, $tipo_movimiento, $diferencia, $precio_actual, $descripcion);
        }

        return $ok;
    }

    public function obtenerCategorias()
    {
        $res = $this->conexion->query("SELECT id, nombre FROM categoria WHERE estado = 1 ORDER BY nombre ASC");
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerProveedores()
    {
        $res = $this->conexion->query("SELECT id, razon_social as nombre FROM persona WHERE rol = 'Proveedor' ORDER BY razon_social ASC");
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function contarProductos()
    {
        $res = $this->conexion->query("SELECT COUNT(*) as total FROM producto WHERE estado = 1");
        if (!$res) return 0;
        $row = $res->fetch_assoc();
        return intval($row['total'] ?? 0);
    }

    public function contarProductosBajoStock($limite = 10)
    {
        $limite = intval($limite);
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM producto WHERE stock <= ? AND estado = 1");
        if (!$stmt) return 0;
        $stmt->bind_param("i", $limite);
        if (!$stmt->execute()) {
            $stmt->close();
            return 0;
        }
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : ['total' => 0];
        $stmt->close();
        return intval($row['total'] ?? 0);
    }

    public function contarProductosVencidos($dias = 30)
    {
        $dias = intval($dias);
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as total FROM producto WHERE fecha_vencimiento IS NOT NULL AND DATEDIFF(fecha_vencimiento, CURDATE()) <= ? AND estado = 1");
        if (!$stmt) return 0;
        $stmt->bind_param("i", $dias);
        if (!$stmt->execute()) {
            $stmt->close();
            return 0;
        }
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : ['total' => 0];
        $stmt->close();
        return intval($row['total'] ?? 0);
    }

    public function obtenerEstadisticasProductos()
    {
        $estadisticas = array();
        $estadisticas['total_productos'] = $this->contarProductos();
        $estadisticas['productos_bajo_stock'] = $this->contarProductosBajoStock();
        $estadisticas['productos_vencidos'] = $this->contarProductosVencidos();

        // Valor total del inventario
        $res = $this->conexion->query("SELECT SUM(precio * stock) as valor_total FROM producto WHERE estado = 1");
        $row = $res ? $res->fetch_assoc() : ['valor_total' => 0];
        $estadisticas['valor_inventario'] = floatval($row['valor_total'] ?? 0);

        return $estadisticas;
    }

    public function verificarCodigoExiste($codigo, $id_excluir = null)
    {
        if ($id_excluir) {
            $sql = "SELECT COUNT(*) as total FROM producto WHERE codigo = ? AND id != ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) return false;
            $id_excluir = intval($id_excluir);
            $stmt->bind_param("si", $codigo, $id_excluir);
        } else {
            $sql = "SELECT COUNT(*) as total FROM producto WHERE codigo = ?";
            $stmt = $this->conexion->prepare($sql);
            if (!$stmt) return false;
            $stmt->bind_param("s", $codigo);
        }

        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : ['total' => 0];
        $stmt->close();
        return intval($row['total'] ?? 0) > 0;
    }

    public function obtenerProductosPorProveedor($id_proveedor)
    {
        // No existe columna 'proveedor' en la tabla producto. Funcionalidad no soportada.
        return [];
    }
}
