<?php
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/includes/inventario.php';
$datos = require __DIR__ . '/includes/datos.php';

$h = fn($v) => e((string)$v);

$vistaActiva = (($_GET['vista'] ?? '') === 'pf') ? 'pf' : 'portada';
$error       = $_GET['err'] ?? '';
$mensajeOk   = $_GET['ok'] ?? '';

try {
    $pdo         = db();
    $categorias  = consultarCategorias($pdo);
    $productos   = consultarProductos($pdo);
    $movimientos = consultarMovimientos($pdo);
} catch (Throwable $e) {
    $error       = $e->getMessage();
    $categorias  = [];
    $productos   = [];
    $movimientos = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>Inventario y movimientos</title>
    <link rel="stylesheet" href="assets/style.css?v=<?= time() ?>"/>
</head>
<body>
    <header>
        <div class="container header-flex">
            <div>
                <h1>Inventario y movimientos</h1>
                <p>Proyecto integrador</p>
            </div>
            <nav class="nav" aria-label="Navegación principal">
                <button type="button" class="nav-btn <?= $vistaActiva === 'portada' ? 'active' : '' ?>" data-vista="portada">
                    Portada
                </button>
                <button type="button" class="nav-btn <?= $vistaActiva === 'practicas' ? 'active' : '' ?>" data-vista="practicas">
                    Prácticas
                </button>
                <button type="button" class="nav-btn <?= $vistaActiva === 'pf' ? 'active' : '' ?>" data-vista="pf">
                    PF
                </button>
                <button type="button" class="nav-btn <?= $vistaActiva === 'conclusiones' ? 'active' : '' ?>" data-vista="conclusiones">
                    Conclusiones
                </button>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($mensajeOk)): ?>
            <div class="alert alert-success"><?= e($mensajeOk) ?></div>
        <?php endif; ?>

        <section id="vista-portada" class="vista <?= $vistaActiva === 'portada' ? 'active' : '' ?>">
            <div class="card portada-card portada-hero">
                <p class="eyebrow"><?= $h($datos['institucion']) ?></p>
                <h2><?= $h($datos['proyecto']) ?></h2>
                <p>Aplicación web para la gestión de productos, existencias mínimas y monitoreo de entradas y salidas mediante SQL.</p>
                <hr class="divider"/>
                <div class="grid">
                    <div>
                        <strong>Materia</strong>
                        <p class="small"><?= $h($datos['materia']) ?></p>
                    </div>
                    <div>
                        <strong>Profesor</strong>
                        <p class="small"><?= $h($datos['profesor']) ?></p>
                    </div>
                    <div>
                        <strong>Carrera y grupo</strong>
                        <p class="small"><?= $h($datos['carrera']) ?> · Grupo <?= $h($datos['grupo']) ?></p>
                    </div>
                    <div>
                        <strong>Integrantes</strong>
                        <p class="small">
                            <?php foreach ($datos['participantes'] as $p): ?>
                                <?= $h($p['nombre']) ?> (<?= $h($p['matricula']) ?>)<br/>
                            <?php endforeach; ?>
                        </p>
                    </div>
                </div>
                <div class="actions">
                    <button type="button" class="primary" data-vista="pf">Ir al Proyecto Final (PF)</button>
                    <button type="button" class="secondary" data-vista="practicas">Ver Prácticas</button>
                    <button type="button" class="secondary" data-vista="conclusiones">Conclusiones</button>
                </div>
            </div>

            <div class="grid">
                <div class="card">
                    <h3>Proyecto Final</h3>
                    <p class="small">Alta de productos, registro de entradas y salidas con transacciones y consulta del inventario.</p>
                </div>
                <div class="card">
                    <h3>Prácticas JS y PHP</h3>
                    <p class="small">Enlaces a la página de prácticas de cada integrante, hechas durante el curso.</p>
                </div>
                <div class="card">
                    <h3>Conclusiones</h3>
                    <p class="small">Datos generales de los participantes y reflexiones finales del proyecto.</p>
                </div>
            </div>
        </section>

        <section id="vista-practicas" class="vista <?= $vistaActiva === 'practicas' ? 'active' : '' ?>">
            <div class="card">
                <h2>Prácticas del curso</h2>
                <p class="small">Selecciona el repositorio o portafolio de prácticas correspondiente:</p>
                <div class="actions">
                    <a href="https://aplicaciones-web-gecg.onrender.com/" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                        Prácticas Gael
                    </a>
                    <a href="http://kevin-pagina.lovestoblog.com/practicas/" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">
                        Prácticas Kevin
                    </a>
                </div>
            </div>
        </section>

        <!-- PROYECTO FINAL -->
        <section id="vista-pf" class="vista <?= $vistaActiva === 'pf' ? 'active' : '' ?>">
            <div class="card">
                <h2>Alta categoría</h2>
                <form action="guardar.php" method="post">
                    <input type="hidden" name="accion" value="categoria"/>
                    <div class="grid">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input name="nomCategoria" required/>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="primary">Guardar categoría</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h2>Alta de producto</h2>
                <form action="guardar.php" method="post">
                    <input type="hidden" name="accion" value="producto"/>
                    <div class="grid">
                        <div class="form-group">
                            <label>Código</label>
                            <input name="codigo" required/>
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input name="nombre" required/>
                        </div>
                        <div class="form-group">
                            <label>Categoría</label>
                            <select name="categoria_id" required>
                                <option value="">Selecciona</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= (int)$cat['id'] ?>">
                                        <?= e($cat['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Stock inicial</label>
                            <input type="number" name="stock" min="0" value="0" required/>
                        </div>
                        <div class="form-group">
                            <label>Stock mínimo</label>
                            <input type="number" name="stock_minimo" min="0" value="5" required/>
                        </div>
                        <div class="form-group">
                            <label>Precio</label>
                            <input type="number" name="precio" min="0" step="0.01" required/>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="primary">Guardar producto</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h2>Registrar movimiento</h2>
                <form id="form-movimiento" action="guardar.php" method="post">
                    <input type="hidden" name="accion" value="movimiento"/>
                    <div class="grid">
                        <div class="form-group">
                            <label>Producto</label>
                            <select name="producto_id" required>
                                <option value="">Selecciona</option>
                                <?php foreach ($productos as $prod): ?>
                                    <option value="<?= (int)$prod['id'] ?>">
                                        <?= e($prod['codigo']) ?> - <?= e($prod['nombre']) ?> (Stock: <?= (int)$prod['stock'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="tipo">
                                <option value="ENTRADA">ENTRADA</option>
                                <option value="SALIDA">SALIDA</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad" min="1" required/>
                        </div>
                        <div class="form-group">
                            <label>Observaciones</label>
                            <input name="observaciones"/>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="primary">Registrar movimiento</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h2>Productos</h2>
                <input id="buscar" placeholder="Buscar..."/>
                <div class="table-wrap">
                    <table id="tabla-productos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Mínimo</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($productos)): ?>
                                <tr>
                                    <td colspan="6">No hay productos registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($productos as $prod): ?>
                                    <tr class="<?= ((int)$prod['stock'] <= (int)$prod['stock_minimo']) ? 'stock-bajo' : '' ?>">
                                        <td><?= e($prod['codigo']) ?></td>
                                        <td><?= e($prod['nombre']) ?></td>
                                        <td><?= e($prod['categoria']) ?></td>
                                        <td><strong><?= (int)$prod['stock'] ?></strong></td>
                                        <td><?= (int)$prod['stock_minimo'] ?></td>
                                        <td>$<?= number_format((float)$prod['precio'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h2>Últimos movimientos</h2>
                <div class="table-wrap">
                    <table id="tabla-movimientos">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($movimientos)): ?>
                                <tr>
                                    <td colspan="5">Aún no hay movimientos registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($movimientos as $mov): ?>
                                    <tr>
                                        <td><?= e($mov['fecha']) ?></td>
                                        <td><?= e($mov['codigo']) ?> - <?= e($mov['producto']) ?></td>
                                        <td><strong><?= e($mov['tipo']) ?></strong></td>
                                        <td><?= (int)$mov['cantidad'] ?></td>
                                        <td><?= e($mov['observaciones'] ?? '—') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="vista-conclusiones" class="vista <?= $vistaActiva === 'conclusiones' ? 'active' : '' ?>">
            <div class="card">
                <h2>Datos generales</h2>
                <dl class="datos">
                    <dt>Proyecto</dt>
                    <dd><?= $h($datos['proyecto']) ?></dd>
                    <dt>Materia</dt>
                    <dd><?= $h($datos['materia']) ?></dd>
                    <dt>Profesor</dt>
                    <dd><?= $h($datos['profesor']) ?></dd>
                    <dt>Institución</dt>
                    <dd><?= $h($datos['institucion']) ?></dd>
                    <dt>Repositorio</dt>
                    <dd><a href="<?= $h($datos['repositorio']) ?>" target="_blank" rel="noopener noreferrer"><?= $h($datos['repositorio']) ?></a></dd>
                </dl>
            </div>

            <div class="card">
                <h2>Participantes</h2>
                <div class="grid">
                    <?php foreach ($datos['participantes'] as $p): ?>
                        <div class="participante">
                            <h3><?= $h($p['nombre']) ?></h3>
                            <dl class="datos">
                                <dt>Matrícula</dt>
                                <dd><?= $h($p['matricula']) ?></dd>
                                <dt>Carrera</dt>
                                <dd><?= $h($datos['carrera']) ?></dd>
                                <dt>Grupo</dt>
                                <dd><?= $h($datos['grupo']) ?></dd>
                                <dt>Correo</dt>
                                <dd><a href="mailto:<?= $h($p['correo']) ?>"><?= $h($p['correo']) ?></a></dd>
                            </dl>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card">
                <h2>Conclusión general</h2>
                <p>
                    Con el Sistema de Control de Inventario y Movimientos integramos lo visto en la materia: JavaScript para
                    cambiar de vista, validar y filtrar en el navegador; PHP para procesar formularios y MySQL como fuente
                    principal de la información. Aprendimos a relacionar tablas con llaves foráneas, a consultar con
                    <code>JOIN</code> y a usar transacciones para que un movimiento y la actualización del stock ocurran juntos.
                    También trabajamos con Git y GitLab para colaborar y publicamos nuestras prácticas en un servidor.
                </p>
            </div>

            <div class="card">
                <h2>Conclusiones individuales</h2>
                <div class="grid">
                    <?php foreach ($datos['participantes'] as $p): ?>
                        <div>
                            <h3><?= $h($p['nombre']) ?></h3>
                            <?php if ($p['conclusion'] !== ''): ?>
                                <p><?= nl2br($h($p['conclusion'])) ?></p>
                            <?php else: ?>
                                <p class="pendiente">Pendiente.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <script src="assets/app.js?v=<?= time() ?>"></script>
</body>
</html>