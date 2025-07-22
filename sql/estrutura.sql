CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    preco DECIMAL(10,2)
);

CREATE TABLE estoques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    variacao VARCHAR(255),
    quantidade INT,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

CREATE TABLE cupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE,
    desconto DECIMAL(10,2),
    minimo DECIMAL(10,2),
    validade DATE
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data DATETIME,
    subtotal DECIMAL(10,2),
    frete DECIMAL(10,2),
    total DECIMAL(10,2),
    cupom_aplicado VARCHAR(50),
    status VARCHAR(50),
    cep VARCHAR(10),
    endereco TEXT,
    email_cliente VARCHAR(255)
);