<?php
// Datos de ejemplo de cursos
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
    <title>Oferta Académica del Instituto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 32px 24px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(25, 118, 210, 0.12);
        }
        h1 {
            text-align: center;
            color: #1976d2;
            font-weight: bold;
            margin-bottom: 32px;
            letter-spacing: 1px;
        }
        .table th {
            background-color: #bbdefb;
            color: #1976d2;
            font-weight: 600;
        }
        .table td {
            vertical-align: middle;
        }
        .bi-book {
            color: #1976d2;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="bi bi-book"></i> Oferta Académica del Instituto</h1>
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Curso</th>
                    <th>Duración</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cursos as $curso): ?>
                <tr>
                    <td><?php echo htmlspecialchars($curso['id']); ?></td>
                    <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($curso['duracion']); ?></td>
                    <td><span class="badge bg-success">$<?php echo number_format($curso['precio'], 2); ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

