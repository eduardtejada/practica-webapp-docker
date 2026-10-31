<?php
// Configuración de la conexión a la base de datos MySQL mediante variables de entorno
$host     = getenv('DB_HOST') ?: 'db';
$user     = getenv('DB_USER') ?: 'practica_user';
$password = getenv('DB_PASSWORD') ?: 'practica_password';
$dbname   = getenv('DB_NAME') ?: 'practica_db';

$connected = false;
$error_msg = '';
$mysql_version = '';
$contador_visitas = 0;

// Reintentar conexión durante unos segundos si MySQL está terminando de inicializar
$intentos = 0;
$max_intentos = 5;

while (!$connected && $intentos < $max_intentos) {
    try {
        $mysqli = @new mysqli($host, $user, $password, $dbname);
        if (!$mysqli->connect_errno) {
            $connected = true;
            $mysql_version = $mysqli->server_info;

            // Crear tabla de ejemplo si no existe
            $mysqli->query("CREATE TABLE IF NOT EXISTS visitas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            // Registrar la visita actual
            $mysqli->query("INSERT INTO visitas () VALUES ()");

            // Contar total de visitas
            $resultado = $mysqli->query("SELECT COUNT(*) AS total FROM visitas");
            if ($resultado) {
                $fila = $resultado->fetch_assoc();
                $contador_visitas = $fila['total'];
            }
            break;
        } else {
            $error_msg = $mysqli->connect_error;
        }
    } catch (Exception $e) {
        $error_msg = $e->getMessage();
    }

    $intentos++;
    if (!$connected) {
        sleep(2);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 7 - Web App con Dockerfile y Docker Compose</title>
    <style>
        :root {
            --primary: #3b82f6;
            --success: #10b981;
            --danger: #ef4444;
            --bg: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        body {
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .card {
            background-color: var(--card-bg);
            width: 100%;
            max-width: 580px;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.25rem;
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.4);
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        p.subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-size: 1rem;
        }
        .status-box {
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
        }
        .status-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #34d399;
        }
        .status-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #f87171;
        }
        .status-icon {
            font-size: 1.8rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
            text-align: left;
        }
        .info-item {
            background: rgba(15, 23, 42, 0.6);
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .info-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-main);
        }
        footer {
            margin-top: 2rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge">Práctica 7: Docker & MySQL</span>
        <h1>¡Hola Mundo!</h1>
        <p class="subtitle">Aplicación web servida con Apache + PHP conectada a MySQL en Docker Compose.</p>

        <?php if ($connected): ?>
            <div class="status-box status-success">
                <span class="status-icon">✓</span>
                <div>
                    <strong>Conexión Exitosa con MySQL</strong>
                    <div style="font-size: 0.9rem; color: #a7f3d0;">La base de datos respondió correctamente.</div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Host de Base de Datos</div>
                    <div class="info-value"><?php echo htmlspecialchars($host); ?>:3306</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Base de Datos</div>
                    <div class="info-value"><?php echo htmlspecialchars($dbname); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Versión de MySQL</div>
                    <div class="info-value"><?php echo htmlspecialchars($mysql_version); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Visitas registradas en BD</div>
                    <div class="info-value"><?php echo htmlspecialchars($contador_visitas); ?></div>
                </div>
            </div>
        <?php else: ?>
            <div class="status-box status-error">
                <span class="status-icon">✗</span>
                <div>
                    <strong>Error al conectar con MySQL</strong>
                    <div style="font-size: 0.9rem; color: #fca5a5;"><?php echo htmlspecialchars($error_msg); ?></div>
                </div>
            </div>
        <?php endif; ?>

        <footer>
            Práctica realizada con Dockerfile y Docker Compose
        </footer>
    </div>
</body>
</html>
