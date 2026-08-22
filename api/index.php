<?php

// Vercel Serverless Entrypoint for CodeIgniter 4
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Ensure writable directory exists in /tmp for serverless environment
$writablePath = '/tmp/ci4_writable';
if (!is_dir($writablePath)) {
    @mkdir($writablePath, 0777, true);
    @mkdir($writablePath . '/cache', 0777, true);
    @mkdir($writablePath . '/session', 0777, true);
    @mkdir($writablePath . '/logs', 0777, true);
    @mkdir($writablePath . '/debugbar', 0777, true);
}

// Support DB Connection Test endpoint
if (isset($_SERVER['REQUEST_URI']) && str_starts_with($_SERVER['REQUEST_URI'], '/test-db')) {
    header('Content-Type: application/json');
    $host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'not set');
    $user = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'not set');
    $pass = getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? 'not set');
    $name = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'test');
    $port = (int)(getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? 4000));
    
    $mysqli = mysqli_init();
    mysqli_ssl_set($mysqli, NULL, NULL, NULL, NULL, NULL);
    $conn = @mysqli_real_connect($mysqli, $host, $user, $pass, $name, $port, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
    
    if (!$conn) {
        echo json_encode([
            'status' => 'error',
            'error_message' => mysqli_connect_error(),
            'host' => $host,
            'user' => $user,
            'database' => $name,
            'port' => $port,
        ], JSON_PRETTY_PRINT);
    } else {
        $tables = [];
        $res = mysqli_query($mysqli, "SHOW TABLES");
        if ($res) {
            while ($row = mysqli_fetch_array($res)) {
                $tables[] = $row[0];
            }
        }
        echo json_encode([
            'status' => 'success',
            'message' => 'Connected to TiDB Cloud successfully!',
            'host' => $host,
            'database' => $name,
            'tables_found' => $tables,
        ], JSON_PRETTY_PRINT);
    }
    exit;
}

// Forward to public/index.php
require __DIR__ . '/../public/index.php';
