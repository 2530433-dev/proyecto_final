<?php
function db(): PDO {
    $ruta = __DIR__ . '/../config/config.php';

    if (file_exists($ruta)) {
        $c = require $ruta;
    } else {
        $c = [
            'host'     => getenv('DB_HOST'),
            'port'     => getenv('DB_PORT') ?: '3306',
            'database' => getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASS'),
        ];
    }

    if (empty($c['host'])) {
        throw new RuntimeException('Falta configurar la base de datos (config.php o variables de entorno).');
    }

    $dsn = "mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset=utf8mb4";
    return new PDO($dsn, $c['username'], $c['password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}