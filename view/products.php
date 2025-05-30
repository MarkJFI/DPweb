<?php
// Lista de cursos (puedes reemplazarlo por datos desde una base de datos)
$cursos = [
    ['id' => 101, 'nombre' => 'Introducción a la Programación', 'duracion' => '3 meses', 'precio' => 150.00],
    ['id' => 102, 'nombre' => 'Diseño Gráfico', 'duracion' => '4 meses', 'precio' => 200.00],
    ['id' => 103, 'nombre' => 'Administración de Empresas', 'duracion' => '6 meses', 'precio' => 300.00],
    ['id' => 104, 'nombre' => 'Redes y Seguridad Informática', 'duracion' => '5 meses', 'precio' => 250.00],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cursos del Instituto</title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef2f3; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Oferta Académica del Instituto</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre del Curso</th>
                <th>Duración</th>
                <th>Precio</th>
            </tr>
            <?php foreach ($cursos as $curso): ?>
                <tr>
                    <td><?php echo htmlspecialchars($curso['id']); ?></td>
                    <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($curso['duracion']); ?></td>
                    <td>$<?php echo number_format($curso['precio'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>

