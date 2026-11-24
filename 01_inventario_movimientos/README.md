# Proyecto 1 — Sistema de Inventario y Movimientos

## Duración
Máximo 10 días.

## Modalidad
Trabajo en parejas.

## Objetivo
Desarrollar un sistema web para administrar productos y controlar entradas y salidas de inventario.

El CSS ya está terminado. Concentrarse en PHP, JavaScript, MySQL, JSON, Git y despliegue.

---

# ¿Qué se guardará en MySQL?

MySQL será la fuente principal de información.

## Tabla `categorias`
Ejemplos:

```text
Cómputo
Accesorios
Papelería
```

## Tabla `productos`
Campos mínimos:

```text
Código
Nombre
Categoría
Stock
Stock mínimo
Precio
```

## Tabla `movimientos`
Cada entrada o salida deberá registrar:

```text
Producto
Tipo
Cantidad
Fecha
Observaciones
```

Relaciones:

```text
productos.categoria_id → categorias.id
movimientos.producto_id → productos.id
```

---

# ¿Qué se hará con JSON?

Completar:

```text
api/exportar.php
```

Este archivo deberá consultar MySQL y devolver el inventario como JSON.

Ejemplo:

```json
[
    {
        "codigo": "P001",
        "nombre": "Laptop",
        "categoria": "Cómputo",
        "stock": 8,
        "stock_minimo": 3,
        "precio": 15000
    }
]
```

El JSON debe generarse desde registros reales de MySQL.

La carpeta `data/` solamente contiene un ejemplo de estructura.

---

# Requisitos funcionales

1. Alta de productos.
2. Código único.
3. Listado con categoría mediante `JOIN`.
4. Registrar ENTRADA.
5. Registrar SALIDA.
6. Impedir stock negativo.
7. Actualizar stock.
8. Guardar historial de movimientos.
9. Utilizar transacción para movimiento + stock.
10. Mostrar alerta cuando:

```text
stock <= stock_minimo
```

11. Búsqueda con JavaScript.
12. Validación de cantidad.
13. Confirmación antes de una salida.
14. Exportación JSON.

---

# Flujo de datos

```text
Formulario
   ↓
PHP
   ↓
MySQL
   ↓
Listado
```

Para JSON:

```text
MySQL
   ↓
api/exportar.php
   ↓
json_encode()
   ↓
JSON
```

---

# Plan sugerido

1. Análisis y Git.
2. Modelo de datos.
3. PDO.
4. Alta/listado.
5. Entradas.
6. Salidas/transacción.
7. JavaScript.
8. JSON/pruebas.
9. Producción.
10. Documentación/presentación.

---

# Evidencias

- producto registrado;
- entrada;
- salida;
- stock actualizado;
- stock bajo;
- `JOIN`;
- transacción;
- JSON;
- 15 videos de desarrollo en algun canal
- Documentanción en scripts
- Manual de usuario 
- Manual técncio
- repositorio;
- producción.

