<?php

class Coupon_model
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    }

    public function salvar($dados)
    {
        $stmt = $this->pdo->prepare("INSERT INTO cupons (codigo, desconto, minimo_subtotal, validade) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            strtoupper($dados['codigo']),
            $dados['desconto'],
            $dados['minimo_subtotal'],
            $dados['validade']
        ]);
    }

    public function listar()
    {
        return $this->pdo->query("SELECT * FROM cupons")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorCodigo($codigo)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cupons WHERE codigo = ? AND validade >= CURDATE()");
        $stmt->execute([strtoupper($codigo)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
