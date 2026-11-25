<?php
function db(): PDO {
    $ruta=__DIR__.'/../config/config.php';
    if(!file_exists($ruta)) throw new RuntimeException('Falta config/config.php');
    $c=require $ruta;
    $dsn="mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset=utf8mb4";
    return new PDO($dsn,$c['username'],$c['password'],[
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
    ]);
}
