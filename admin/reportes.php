<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Verificar rol de administrador
if (!is_logged_in() || $_SESSION['id_rol'] != 2) {
    header("Location: ../login.php");
    exit();
}

$elecciones = [];
$reporte = [];
$error = '';

// Obtener todas las elecciones
try {
    $sql = "SELECT * FROM elecciones ORDER BY fecha_inicio DESC";
    $stmt = $pdo->query($sql);
    $elecciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error al obtener elecciones: " . $e->getMessage();
}

// Procesar solicitud de reporte
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eleccion_id = clean_input($_POST['eleccion']);
    
    try {
        // Obtener resultados por ciudad
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
        
        // Obtener resultados por candidato
        $sql = "SELECT ca.nombre, ca.apellido, p.nombre as partido, p.sigla, COUNT(v.id_voto) as votos
                FROM votos v
                JOIN candidatos ca ON v.id_candidato = ca.id_candidato
                JOIN partidos_politicos p ON ca.id_partido = p.id_partido
                WHERE v.id_eleccion = :eleccion
                GROUP BY ca.id_candidato
                ORDER BY votos DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':eleccion' => $eleccion_id]);
        $reporte['por_candidato'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener total de votos
        $sql = "SELECT COUNT(*) as total FROM votos WHERE id_eleccion = :eleccion";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':eleccion' => $eleccion_id]);
        $reporte['total_votos'] = $stmt->fetchColumn();
        
        // Obtener información de la elección
        $sql = "SELECT * FROM elecciones WHERE id_eleccion = :eleccion";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':eleccion' => $eleccion_id]);
        $reporte['eleccion'] = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Error al generar el reporte: " . $e->getMessage();
    }
}

// Registrar actividad
log_activity("Accedió a la sección de reportes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Votación</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Reportes de Votación</h1>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="report-form">
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
            
            <button type="submit" class="btn btn-primary">Generar Reporte</button>
        </form>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif (!empty($reporte)): ?>
            <section class="report-section">
                <h2>Reporte de <?php echo htmlspecialchars($reporte['eleccion']['nombre']); ?></h2>
                <p>Total de votos: <?php echo $reporte['total_votos']; ?></p>
                
                <div class="report-charts">
                    <div class="chart-container">
                        <h3>Distribución por Ciudad</h3>
                        <canvas id="ciudadChart"></canvas>
                    </div>
                    
                    <div class="chart-container">
                        <h3>Distribución por Candidato</h3>
                        <canvas id="candidatoChart"></canvas>
                    </div>
                </div>
                
                <div class="report-tables">
                    <h3>Detalle por Ciudad</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Ciudad</th>
                                <th>Votos</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reporte['por_ciudad'] as $ciudad): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ciudad['ciudad']); ?></td>
                                    <td><?php echo $ciudad['votos']; ?></td>
                                    <td><?php echo round(($ciudad['votos'] / $reporte['total_votos']) * 100, 2); ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <h3>Detalle por Candidato</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Candidato</th>
                                <th>Partido</th>
                                <th>Votos</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reporte['por_candidato'] as $candidato): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($candidato['nombre'] . " " . $candidato['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($candidato['partido'] . " (" . $candidato['sigla'] . ")"); ?></td>
                                    <td><?php echo $candidato['votos']; ?></td>
                                    <td><?php echo round(($candidato['votos'] / $reporte['total_votos']) * 100, 2); ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <script>
                // Gráfico por ciudad
                const ciudadCtx = document.getElementById('ciudadChart').getContext('2d');
                const ciudadChart = new Chart(ciudadCtx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode(array_column($reporte['por_ciudad'], 'ciudad')); ?>,
                        datasets: [{
                            label: 'Votos por Ciudad',
                            data: <?php echo json_encode(array_column($reporte['por_ciudad'], 'votos')); ?>,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                
                // Gráfico por candidato
                const candidatoCtx = document.getElementById('candidatoChart').getContext('2d');
                const candidatoChart = new Chart(candidatoCtx, {
                    type: 'pie',
                    data: {
                        labels: <?php echo json_encode(array_map(function($c) { 
                            return $c['nombre'] . ' ' . $c['apellido'] . ' (' . $c['sigla'] . ')'; 
                        }, $reporte['por_candidato'])); ?>,
                        datasets: [{
                            data: <?php echo json_encode(array_column($reporte['por_candidato'], 'votos')); ?>,
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
                        responsive: true
                    }
                });
            </script>
        <?php endif; ?>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>