<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (!is_logged_in() || $_SESSION['id_rol'] != 1) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$reporte = [];
$elecciones = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eleccion_id = clean_input($_POST['eleccion']);
    $tipo_reporte = clean_input($_POST['tipo_reporte']);
    
    try {
        if ($tipo_reporte == 'general') {
            // Reporte general por departamento
            $sql = "SELECT d.nombre as departamento, COUNT(v.id_voto) as votos
                    FROM votos v
                    JOIN usuarios u ON v.id_usuario = u.id_usuario
                    JOIN ciudades c ON u.id_ciudad = c.id_ciudad
                    JOIN provincias p ON c.id_provincia = p.id_provincia
                    JOIN departamentos d ON p.id_departamento = d.id_departamento
                    WHERE v.id_eleccion = :eleccion
                    GROUP BY d.id_departamento
                    ORDER BY votos DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':eleccion' => $eleccion_id]);
            $reporte['por_departamento'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $sql = "SELECT COUNT(*) as total FROM votos WHERE id_eleccion = :eleccion";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':eleccion' => $eleccion_id]);
            $reporte['total_votos'] = $stmt->fetchColumn();
            
        } elseif ($tipo_reporte == 'detallado') {
            // Reporte detallado por ciudad (votos por candidato)
            $sql = "SELECT c.nombre as ciudad, ca.nombre, ca.apellido, p.nombre as partido, p.sigla, COUNT(v.id_voto) as votos
                    FROM votos v
                    JOIN candidatos ca ON v.id_candidato = ca.id_candidato
                    JOIN partidos_politicos p ON ca.id_partido = p.id_partido
                    JOIN usuarios u ON v.id_usuario = u.id_usuario
                    JOIN ciudades c ON u.id_ciudad = c.id_ciudad
                    WHERE v.id_eleccion = :eleccion
                    GROUP BY c.id_ciudad, ca.id_candidato
                    ORDER BY c.nombre, votos DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':eleccion' => $eleccion_id]);
            $reporte['detallado'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Nuevo: Votos por ciudad con gráfica
            $sql = "SELECT c.nombre as ciudad, COUNT(v.id_voto) as votos
                    FROM votos v
                    JOIN usuarios u ON v.id_usuario = u.id_usuario
                    JOIN ciudades c ON u.id_ciudad = c.id_ciudad
                    WHERE v.id_eleccion = :eleccion
                    GROUP BY c.id_ciudad
                    ORDER BY votos DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':eleccion' => $eleccion_id]);
            $reporte['por_ciudad'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Nuevo: Votos por departamento y candidato
            $sql = "SELECT d.nombre as departamento, ca.nombre, ca.apellido, p.nombre as partido, p.sigla, COUNT(v.id_voto) as votos
                    FROM votos v
                    JOIN candidatos ca ON v.id_candidato = ca.id_candidato
                    JOIN partidos_politicos p ON ca.id_partido = p.id_partido
                    JOIN usuarios u ON v.id_usuario = u.id_usuario
                    JOIN ciudades c ON u.id_ciudad = c.id_ciudad
                    JOIN provincias pr ON c.id_provincia = pr.id_provincia
                    JOIN departamentos d ON pr.id_departamento = d.id_departamento
                    WHERE v.id_eleccion = :eleccion
                    GROUP BY d.id_departamento, ca.id_candidato
                    ORDER BY d.nombre, votos DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':eleccion' => $eleccion_id]);
            $reporte['por_departamento_candidato'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        $sql = "SELECT * FROM elecciones WHERE id_eleccion = :eleccion";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':eleccion' => $eleccion_id]);
        $reporte['eleccion'] = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Error al generar el reporte: " . $e->getMessage();
    }
}

try {
    $sql = "SELECT * FROM elecciones ORDER BY fecha_inicio DESC";
    $stmt = $pdo->query($sql);
    $elecciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener elecciones: " . $e->getMessage();
}

log_activity("Accedió a reportes globales");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Globales</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Estilos idénticos a votaciones_alcaldia.php */
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --sidebar-width: 250px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--dark-color);
            color: white;
            position: fixed;
            height: 100%;
            padding: 20px 0;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            color: white;
            margin-bottom: 10px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu ul {
            list-style: none;
        }

        .sidebar-menu li {
            position: relative;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: #bbb;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 15px;
        }

        .sidebar-menu a:hover, 
        .sidebar-menu a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .sidebar-menu a:hover::before,
        .sidebar-menu a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--primary-color);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 20px;
            transition: all 0.3s;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 18px;
            color: var(--dark-color);
            margin: 0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="datetime-local"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .data-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .data-table tr:hover {
            background-color: #f5f5f5;
        }

        .estado-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .estado-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }

        .estado-activa {
            background-color: #d4edda;
            color: #155724;
        }

        .estado-finalizada {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .inline-form {
            display: inline-block;
            margin-right: 5px;
        }

        .btn-small {
            padding: 4px 8px;
            font-size: 12px;
        }

        .chart-container {
            margin: 20px 0;
            max-width: 800px;
            height: 400px;
        }

        .chart-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-box {
            flex: 1;
            min-width: 300px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .chart-box {
                min-width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Sistema de Votaciones</h3>
            <p>SuperAdministrador</p>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="gestion_administradores.php"><i class="fas fa-users-cog"></i> Administradores</a></li>
                <li><a href="votaciones_electorales.php"><i class="fas fa-vote-yea"></i> Votaciones Nacionales</a></li>
                <li><a href="votaciones_alcaldia.php"><i class="fas fa-landmark"></i> Votaciones Alcaldía</a></li>
                <li><a href="reportes_globales.php" class="active"><i class="fas fa-chart-bar"></i> Reportes</a></li>
                <li><a href="reclamos.php"><i class="fas fa-exclamation-circle"></i> Reclamos</a></li>
                <li><a href="../includes/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Reportes Globales</h1>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nombre']); ?>&background=3498db&color=fff" alt="User">
                <span><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
            </div>
        </div>
        
        <div class="card">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <h2>Generar Reporte</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="report-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="eleccion">Seleccionar Elección:</label>
                        <select name="eleccion" id="eleccion" required>
                            <option value="">-- Seleccione una elección --</option>
                            <?php foreach ($elecciones as $eleccion): ?>
                                <option value="<?php echo $eleccion['id_eleccion']; ?>" <?php echo (isset($_POST['eleccion']) && $_POST['eleccion'] == $eleccion['id_eleccion']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($eleccion['nombre'] . " (" . date('d/m/Y', strtotime($eleccion['fecha_inicio'])) . ")"); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo_reporte">Tipo de Reporte:</label>
                        <select name="tipo_reporte" id="tipo_reporte" required>
                            <option value="general" <?php echo (isset($_POST['tipo_reporte']) && $_POST['tipo_reporte'] == 'general') ? 'selected' : ''; ?>>General por Departamento</option>
                            <option value="detallado" <?php echo (isset($_POST['tipo_reporte']) && $_POST['tipo_reporte'] == 'detallado') ? 'selected' : ''; ?>>Detallado por Ciudad</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </form>
        </div>
        
        <?php if (!empty($reporte)): ?>
            <div class="card">
                <h2>Reporte de <?php echo htmlspecialchars($reporte['eleccion']['nombre']); ?></h2>
                
                <?php if (isset($reporte['por_departamento'])): ?>
                    <div class="chart-row">
                        <div class="chart-box">
                            <h3>Distribución por Departamento</h3>
                            <div class="chart-container">
                                <canvas id="departamentoChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Departamento</th>
                                    <th>Votos</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reporte['por_departamento'] as $departamento): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($departamento['departamento']); ?></td>
                                        <td><?php echo $departamento['votos']; ?></td>
                                        <td><?php echo round(($departamento['votos'] / $reporte['total_votos']) * 100, 2); ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <script>
                        const departamentoCtx = document.getElementById('departamentoChart').getContext('2d');
                        const departamentoChart = new Chart(departamentoCtx, {
                            type: 'bar',
                            data: {
                                labels: <?php echo json_encode(array_column($reporte['por_departamento'], 'departamento')); ?>,
                                datasets: [{
                                    label: 'Votos por Departamento',
                                    data: <?php echo json_encode(array_column($reporte['por_departamento'], 'votos')); ?>,
                                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>
                    
                <?php elseif (isset($reporte['detallado'])): ?>
                    <div class="chart-row">
                        <?php if (!empty($reporte['por_ciudad'])): ?>
                            <div class="chart-box">
                                <h3>Votos por Ciudad</h3>
                                <div class="chart-container">
                                    <canvas id="ciudadChart"></canvas>
                                </div>
                            </div>
                            
                            <script>
                                const ciudadCtx = document.getElementById('ciudadChart').getContext('2d');
                                const ciudadChart = new Chart(ciudadCtx, {
                                    type: 'pie',
                                    data: {
                                        labels: <?php echo json_encode(array_column($reporte['por_ciudad'], 'ciudad')); ?>,
                                        datasets: [{
                                            data: <?php echo json_encode(array_column($reporte['por_ciudad'], 'votos')); ?>,
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.5)',
                                                'rgba(54, 162, 235, 0.5)',
                                                'rgba(255, 206, 86, 0.5)',
                                                'rgba(75, 192, 192, 0.5)',
                                                'rgba(153, 102, 255, 0.5)',
                                                'rgba(255, 159, 64, 0.5)'
                                            ],
                                            borderColor: [
                                                'rgba(255, 99, 132, 1)',
                                                'rgba(54, 162, 235, 1)',
                                                'rgba(255, 206, 86, 1)',
                                                'rgba(75, 192, 192, 1)',
                                                'rgba(153, 102, 255, 1)',
                                                'rgba(255, 159, 64, 1)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false
                                    }
                                });
                            </script>
                        <?php endif; ?>
                        
                        <?php if (!empty($reporte['por_departamento_candidato'])): 
                            // Preparar datos para la gráfica por departamento y candidato
                            $departamentos = [];
                            $candidatos = [];
                            $datosGrafica = [];
                            
                            foreach ($reporte['por_departamento_candidato'] as $item) {
                                if (!in_array($item['departamento'], $departamentos)) {
                                    $departamentos[] = $item['departamento'];
                                }
                                
                                $nombreCandidato = $item['nombre'] . ' ' . $item['apellido'] . ' (' . $item['sigla'] . ')';
                                if (!in_array($nombreCandidato, $candidatos)) {
                                    $candidatos[] = $nombreCandidato;
                                }
                                
                                $datosGrafica[$item['departamento']][$nombreCandidato] = $item['votos'];
                            }
                            
                            // Preparar datasets para Chart.js
                            $datasets = [];
                            $colors = [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(153, 102, 255, 0.5)',
                                'rgba(255, 159, 64, 0.5)'
                            ];
                            
                            $colorIndex = 0;
                            foreach ($candidatos as $candidato) {
                                $data = [];
                                foreach ($departamentos as $departamento) {
                                    $data[] = $datosGrafica[$departamento][$candidato] ?? 0;
                                }
                                
                                $datasets[] = [
                                    'label' => $candidato,
                                    'data' => $data,
                                    'backgroundColor' => $colors[$colorIndex % count($colors)],
                                    'borderColor' => str_replace('0.5', '1', $colors[$colorIndex % count($colors)]),
                                    'borderWidth' => 1
                                ];
                                $colorIndex++;
                            }
                            ?>
                            
                            <div class="chart-box">
                                <h3>Votos por Departamento y Candidato</h3>
                                <div class="chart-container">
                                    <canvas id="departamentoCandidatoChart"></canvas>
                                </div>
                            </div>
                            
                            <script>
                                const departamentoCandidatoCtx = document.getElementById('departamentoCandidatoChart').getContext('2d');
                                const departamentoCandidatoChart = new Chart(departamentoCandidatoCtx, {
                                    type: 'bar',
                                    data: {
                                        labels: <?php echo json_encode($departamentos); ?>,
                                        datasets: <?php echo json_encode($datasets); ?>
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: {
                                            x: {
                                                stacked: true,
                                            },
                                            y: {
                                                stacked: true,
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            </script>
                        <?php endif; ?>
                    </div>
                    
                    <div class="table-responsive">
                        <?php
                        $ciudad_actual = '';
                        foreach ($reporte['detallado'] as $fila):
                            if ($ciudad_actual != $fila['ciudad']):
                                if ($ciudad_actual != ''):
                                    echo '</tbody></table>';
                                endif;
                                $ciudad_actual = $fila['ciudad'];
                        ?>
                            <h3><?php echo htmlspecialchars($ciudad_actual); ?></h3>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Candidato</th>
                                        <th>Partido</th>
                                        <th>Votos</th>
                                    </tr>
                                </thead>
                                <tbody>
                        <?php endif; ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($fila['nombre'] . " " . $fila['apellido']); ?></td>
                                        <td><?php echo htmlspecialchars($fila['partido'] . " (" . $fila['sigla'] . ")"); ?></td>
                                        <td><?php echo $fila['votos']; ?></td>
                                    </tr>
                        <?php endforeach; ?>
                                </tbody>
                            </table>
                    </div>
                    
                    <?php if (!empty($reporte['por_departamento_candidato'])): ?>
                        <h3>Votos por Departamento y Candidato</h3>
                        <div class="table-responsive">
                            <?php
                            $departamento_actual = '';
                            foreach ($reporte['por_departamento_candidato'] as $fila):
                                if ($departamento_actual != $fila['departamento']):
                                    if ($departamento_actual != ''):
                                        echo '</tbody></table>';
                                    endif;
                                    $departamento_actual = $fila['departamento'];
                            ?>
                                <h4><?php echo htmlspecialchars($departamento_actual); ?></h4>
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Candidato</th>
                                            <th>Partido</th>
                                            <th>Votos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            <?php endif; ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($fila['nombre'] . " " . $fila['apellido']); ?></td>
                                            <td><?php echo htmlspecialchars($fila['partido'] . " (" . $fila['sigla'] . ")"); ?></td>
                                            <td><?php echo $fila['votos']; ?></td>
                                        </tr>
                            <?php endforeach; ?>
                                    </tbody>
                                </table>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>