<?php
require __DIR__.'/includes/db.php';
require __DIR__.'/includes/helpers.php';

// Si se envía un formulario de inventario, mantiene activa la vista PF
$vistaActiva = ($_SERVER['REQUEST_METHOD'] === 'POST') ? 'pf' : 'portada';

try {
    $pdo = db();
    // TODO 1: consultar categorías.
    $categorias = [];
    // TODO 2: consultar productos con JOIN.
    $productos = [];
    // TODO 3: registrar un producto.
    // TODO 4: registrar ENTRADA/SALIDA usando transacción.
    // Regla: una SALIDA no puede dejar stock negativo.
    // TODO 5: consultar últimos movimientos.
    $movimientos = [];
} catch (Throwable $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width,initial-scale=1" name="viewport"/>
  <title>Inventario y movimientos</title>
  <link href="assets/style.css?v=<?= time() ?>" rel="stylesheet"/>
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
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
   <?php endif; ?>

   <section id="vista-portada" class="vista <?= $vistaActiva === 'portada' ? 'active' : '' ?>">
    <div class="card portada-card">
     <h2>Sistema de Control de Inventario y Movimientos</h2>
     <p class="small">Aplicación web para la gestión de productos, existencias mínimas y monitoreo de entradas y salidas mediante SQL.</p>
     <hr class="divider"/>
     <div class="grid">
      <div>
       <strong>Integrantes</strong>
       <p class="small">Gael<br/>Kevin</p>
      </div>
     </div>
     <div class="actions">
      <button type="button" class="primary" data-vista="pf">Ir al Proyecto Final (PF)</button>
      <button type="button" class="secondary" data-vista="practicas">Ver Prácticas</button>
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
      <a href="URL_PRACTICAS_KEVIN" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">
       Prácticas Kevin
      </a>
     </div>
    </div>
   </section>

   <section id="vista-pf" class="vista <?= $vistaActiva === 'pf' ? 'active' : '' ?>">
    <div class="card">
     <h2>Alta de producto</h2>
     <form method="post">
      <input name="accion" type="hidden" value="producto"/>
      <div class="grid">
       <div class="form-group">
        <label>Código</label>
        <input name="codigo" required=""/>
       </div>
       <div class="form-group">
        <label>Nombre</label>
        <input name="nombre" required=""/>
       </div>
       <div class="form-group">
        <label>Categoría</label>
        <select name="categoria_id" required="">
         <option value="">Selecciona</option>
         <!-- TODO -->
        </select>
       </div>
       <div class="form-group">
        <label>Stock inicial</label>
        <input min="0" name="stock" required="" type="number" value="0"/>
       </div>
       <div class="form-group">
        <label>Stock mínimo</label>
        <input min="0" name="stock_minimo" required="" type="number" value="5"/>
       </div>
       <div class="form-group">
        <label>Precio</label>
        <input min="0" name="precio" required="" step="0.01" type="number"/>
       </div>
      </div>
      <div class="actions">
       <button class="primary">Guardar producto</button>
      </div>
     </form>
    </div>

    <div class="card">
     <h2>Registrar movimiento</h2>
     <form id="form-movimiento" method="post">
      <input name="accion" type="hidden" value="movimiento"/>
      <div class="grid">
       <div class="form-group">
        <label>Producto</label>
        <select name="producto_id" required="">
         <option value="">Selecciona</option>
         <!-- TODO -->
        </select>
       </div>
       <div class="form-group">
        <label>Tipo</label>
        <select name="tipo">
         <option>ENTRADA</option>
         <option>SALIDA</option>
        </select>
       </div>
       <div class="form-group">
        <label>Cantidad</label>
        <input min="1" name="cantidad" required="" type="number"/>
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
       </tbody>
      </table>
     </div>
    </div>
   </section>

   <section id="vista-conclusiones" class="vista <?= $vistaActiva === 'conclusiones' ? 'active' : '' ?>">
    <div class="card">
     <h2>Conclusiones</h2>
     <p class="small">Espacio reservado para las reflexiones finales del proyecto.</p>
     <hr class="divider"/>
     <div class="grid">
      <div>
       <h3>Gael</h3>
      </div>
      <div>
       <h3>Kevin</h3>
      </div>
     </div>
    </div>
   </section>
  </main>

  <script src="assets/app.js?v=<?= time() ?>"></script>
 </body>
</html>