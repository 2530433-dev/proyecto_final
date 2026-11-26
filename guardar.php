<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/inventario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('index.php');
}

$accion  = trim((string) ($_POST['accion'] ?? ''));
$destino = 'index.php?vista=pf';

try {
    $pdo = db();

    if ($accion === 'categoria') {
        $nombre = trim((string) ($_POST['nomCategoria'] ?? ''));

        if ($nombre === '' || strlen($nombre) > 80) {
            redirigir($destino . '&err=' . urlencode('Verifica el nombre de la categoría (máx. 80 caracteres).'));
        }

        guardarCategoria($pdo, $nombre);
        $destino .= '&ok=' . urlencode('Categoría registrada correctamente.');

    } elseif ($accion === 'producto') {
        $codigo      = trim((string) ($_POST['codigo'] ?? ''));
        $nombre      = trim((string) ($_POST['nombre'] ?? ''));
        $categoriaId = (int) ($_POST['categoria_id'] ?? 0);
        $stock       = (int) ($_POST['stock'] ?? 0);
        $stockMinimo = (int) ($_POST['stock_minimo'] ?? 5);
        $precio      = (float) ($_POST['precio'] ?? 0);

        if (
            $codigo === '' || strlen($codigo) > 30 ||
            $nombre === '' || strlen($nombre) > 120 ||
            $categoriaId <= 0 ||
            $stock < 0 || $stockMinimo < 0 || $precio < 0
        ) {
            redirigir($destino . '&err=' . urlencode('Verifica todos los campos del producto.'));
        }

        guardarProducto($pdo, $categoriaId, $codigo, $nombre, $stock, $stockMinimo, $precio);
        $destino .= '&ok=' . urlencode('Producto registrado correctamente.');

    } elseif ($accion === 'movimiento') {
        $productoId    = (int) ($_POST['producto_id'] ?? 0);
        $tipo          = trim((string) ($_POST['tipo'] ?? ''));
        $cantidad      = (int) ($_POST['cantidad'] ?? 0);
        $observaciones = trim((string) ($_POST['observaciones'] ?? ''));

        if (
            $productoId <= 0 ||
            !in_array($tipo, ['ENTRADA', 'SALIDA'], true) ||
            $cantidad <= 0 ||
            strlen($observaciones) > 255
        ) {
            redirigir($destino . '&err=' . urlencode('Verifica el producto, tipo y una cantidad mayor a cero.'));
        }

        guardarMovimiento($pdo, $productoId, $tipo, $cantidad, $observaciones !== '' ? $observaciones : null);
        $destino .= '&ok=' . urlencode('Movimiento registrado y stock actualizado.');

    } else {
        $destino .= '&err=' . urlencode('Acción no válida.');
    }

} catch (PDOException $excepcion) {
    // 23000 representa una violación de restricción, como UNIQUE.
    error_log($excepcion->getMessage());
    $mensajeError = ((string) $excepcion->getCode() === '23000')
        ? 'La categoría o el código de producto ya existen.'
        : 'No fue posible guardar el registro.';
    $destino .= '&err=' . urlencode($mensajeError);
} catch (RuntimeException $excepcion) {
    $destino .= '&err=' . urlencode($excepcion->getMessage());
} catch (Throwable $excepcion) {
    error_log($excepcion->getMessage());
    $destino .= '&err=' . urlencode('Error de conexión con la base de datos.');
}

// Evita el reenvío del formulario al actualizar el navegador.
redirigir($destino);