<?php
declare(strict_types=1);

// consultar categorías.
function consultarCategorias(PDO $pdo): array
{
    $sql = 'SELECT id, nombre FROM categorias ORDER BY nombre ASC';
    return $pdo->query($sql)->fetchAll();
}

// consultar productos con JOIN.
function consultarProductos(PDO $pdo): array
{
    $sql = 'SELECT p.id, p.categoria_id, p.codigo, p.nombre, p.stock, p.stock_minimo, p.precio,
                   c.nombre AS categoria
            FROM productos p
            INNER JOIN categorias c ON p.categoria_id = c.id
            ORDER BY p.id DESC';
    return $pdo->query($sql)->fetchAll();
}

// consultar ultimos movimientos.
function consultarMovimientos(PDO $pdo): array
{
    $sql = 'SELECT m.id, m.tipo, m.cantidad, m.observaciones, m.fecha,
                   p.codigo, p.nombre AS producto
            FROM movimientos m
            INNER JOIN productos p ON m.producto_id = p.id
            ORDER BY m.fecha DESC, m.id DESC
            LIMIT 20';
    return $pdo->query($sql)->fetchAll();
}

// Alta de categoria.
function guardarCategoria(PDO $pdo, string $nombre): void
{
    $sql = 'INSERT INTO categorias (nombre) VALUES (:nombre)';
    $sentencia = $pdo->prepare($sql);
    $sentencia->execute(['nombre' => $nombre]);
}

// registrar un producto.
function guardarProducto(PDO $pdo, int $categoriaId, string $codigo, string $nombre, int $stock, int $stockMinimo, float $precio): void
{
    $sql = 'INSERT INTO productos (categoria_id, codigo, nombre, stock, stock_minimo, precio)
            VALUES (:categoria_id, :codigo, :nombre, :stock, :stock_minimo, :precio)';
    $sentencia = $pdo->prepare($sql);
    $sentencia->execute([
        'categoria_id' => $categoriaId,
        'codigo'       => $codigo,
        'nombre'       => $nombre,
        'stock'        => $stock,
        'stock_minimo' => $stockMinimo,
        'precio'       => $precio,
    ]);
}

// registrar entrada y salida.
function guardarMovimiento(PDO $pdo, int $productoId, string $tipo, int $cantidad, ?string $observaciones): void
{
    $pdo->beginTransaction();

    try {
        // Consultar stock actual
        $sqlStock = 'SELECT nombre, stock FROM productos WHERE id = :id FOR UPDATE';
        $sentenciaStock = $pdo->prepare($sqlStock);
        $sentenciaStock->execute(['id' => $productoId]);
        $producto = $sentenciaStock->fetch();

        if (!$producto) {
            throw new RuntimeException('El producto seleccionado no existe.');
        }

        $stockActual = (int) $producto['stock'];

        if ($tipo === 'SALIDA' && $stockActual < $cantidad) {
            throw new RuntimeException("Stock insuficiente para {$producto['nombre']} (disponible: {$stockActual}).");
        }

        $nuevoStock = ($tipo === 'ENTRADA')
            ? $stockActual + $cantidad
            : $stockActual - $cantidad;

        // insertar el movimiento
        $sqlMov = 'INSERT INTO movimientos (producto_id, tipo, cantidad, observaciones)
                   VALUES (:producto_id, :tipo, :cantidad, :observaciones)';
        $sentenciaMov = $pdo->prepare($sqlMov);
        $sentenciaMov->execute([
            'producto_id'   => $productoId,
            'tipo'          => $tipo,
            'cantidad'      => $cantidad,
            'observaciones' => $observaciones,
        ]);

        // actualizar el stock del producto
        $sqlUpd = 'UPDATE productos SET stock = :stock WHERE id = :id';
        $sentenciaUpd = $pdo->prepare($sqlUpd);
        $sentenciaUpd->execute([
            'stock' => $nuevoStock,
            'id'    => $productoId,
        ]);

        $pdo->commit();
    } catch (Throwable $excepcion) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $excepcion;
    }
}