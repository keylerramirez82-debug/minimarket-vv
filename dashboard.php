<?php
session_start();

// Verificar si el usuario está logueado (opcional, comenta si quieres acceso público)
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.html');
//     exit;
// }

require_once 'Conexion.php';

// Obtener estadísticas
try {
    // Total de usuarios
    $stmtUsers = $pdo->query('SELECT COUNT(*) as total FROM usuarios');
    $totalUsers = $stmtUsers->fetch()['total'];

    // Total de opiniones
    $stmtOpiniones = $pdo->query('SELECT COUNT(*) as total FROM opiniones');
    $totalOpiniones = $stmtOpiniones->fetch()['total'];

    // Opiniones por motivo
    $stmtMotivoOpiniones = $pdo->query('SELECT motivo, COUNT(*) as cantidad FROM opiniones GROUP BY motivo ORDER BY cantidad DESC');
    $opinionesPorMotivo = $stmtMotivoOpiniones->fetchAll();

    // Últimos usuarios registrados
    $stmtUltimosUsuarios = $pdo->query('SELECT nombre, email, creado FROM usuarios ORDER BY creado DESC LIMIT 5');
    $ultimosUsuarios = $stmtUltimosUsuarios->fetchAll();

    // Últimas opiniones
    $stmtUltimasOpiniones = $pdo->query('SELECT nombre, motivo, opinion, creado FROM opiniones ORDER BY creado DESC LIMIT 5');
    $ultimasOpiniones = $stmtUltimasOpiniones->fetchAll();

} catch (PDOException $e) {
    die('Error al obtener estadísticas: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Minimarket V&V</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-dashboard {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 10px;
        }

        .header-dashboard h1 {
            color: white;
            font-size: 2.5rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: #f0f0f0;
            transform: translateX(-5px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .stat-card h3 {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #999;
            font-size: 0.85rem;
        }

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .chart-container h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .chart-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-label {
            min-width: 120px;
            color: #666;
            font-weight: 500;
        }

        .chart-bar {
            flex-grow: 1;
            height: 30px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 5px;
            margin: 0 15px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-weight: bold;
        }

        .chart-value {
            min-width: 40px;
            text-align: right;
            color: #666;
            font-weight: 600;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
        }

        .table-container h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 15px;
            text-align: left;
            color: #666;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            color: #555;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            background: #667eea;
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .header-dashboard {
                flex-direction: column;
                gap: 15px;
            }

            .header-dashboard h1 {
                font-size: 1.8rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .chart-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .chart-bar {
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-dashboard">
            <h1>📊 Dashboard</h1>
            <a href="index.php" class="back-link">← Volver al inicio</a>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>👥 Total de Usuarios</h3>
                <div class="stat-number"><?= $totalUsers ?></div>
                <div class="stat-label">Usuarios registrados en el sistema</div>
            </div>
            <div class="stat-card">
                <h3>💬 Total de Opiniones</h3>
                <div class="stat-number"><?= $totalOpiniones ?></div>
                <div class="stat-label">Opiniones recibidas de clientes</div>
            </div>
        </div>

        <!-- Gráfico de opiniones por motivo -->
        <?php if (!empty($opinionesPorMotivo)): ?>
        <div class="chart-container">
            <h2>📈 Opiniones por Categoría</h2>
            <?php 
            $maxOpiniones = max(array_column($opinionesPorMotivo, 'cantidad')) ?: 1;
            foreach ($opinionesPorMotivo as $item): 
            ?>
                <div class="chart-item">
                    <div class="chart-label"><?= htmlspecialchars($item['motivo'] ?: 'Sin categoría') ?></div>
                    <div class="chart-bar" style="width: <?= ($item['cantidad'] / $maxOpiniones * 100) ?>%">
                        <?= $item['cantidad'] ?>
                    </div>
                    <div class="chart-value"><?= $item['cantidad'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Tabla de últimos usuarios -->
        <div class="table-container">
            <h2>👤 Últimos Usuarios Registrados</h2>
            <?php if (!empty($ultimosUsuarios)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimosUsuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($usuario['creado'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <p>No hay usuarios registrados aún</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tabla de últimas opiniones -->
        <div class="table-container">
            <h2>💬 Últimas Opiniones</h2>
            <?php if (!empty($ultimasOpiniones)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Categoría</th>
                        <th>Opinión</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimasOpiniones as $opinion): ?>
                    <tr>
                        <td><?= htmlspecialchars($opinion['nombre']) ?></td>
                        <td><span class="badge"><?= htmlspecialchars($opinion['motivo'] ?: 'otro') ?></span></td>
                        <td><?= htmlspecialchars(substr($opinion['opinion'], 0, 100)) ?><?= strlen($opinion['opinion']) > 100 ? '...' : '' ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($opinion['creado'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <p>No hay opiniones aún</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
