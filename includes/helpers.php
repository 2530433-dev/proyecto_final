<?php
function e(?string $v): string { return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8'); }
function redirigir(string $r='index.php'): never { header('Location: '.$r); exit; }
