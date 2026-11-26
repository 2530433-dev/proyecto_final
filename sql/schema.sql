CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    codigo VARCHAR(30) NOT NULL UNIQUE,
    nombre VARCHAR(120) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 5,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY(categoria_id) REFERENCES categorias(id)
);

CREATE TABLE movimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    tipo ENUM('ENTRADA','SALIDA') NOT NULL,
    cantidad INT NOT NULL,
    observaciones VARCHAR(255),
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(producto_id) REFERENCES productos(id)
);

INSERT INTO categorias(nombre) VALUES
    ('Cómputo'),
    ('Accesorios'),
    ('Papelería');

INSERT INTO productos(categoria_id,codigo,nombre,stock,stock_minimo,precio) VALUES
    (1,'P001','Laptop de práctica',8,3,15000),
    (2,'P002','Mouse USB',15,5,250),
    (3,'P003','Cuaderno profesional',20,6,65);
