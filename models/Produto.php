<?php

class Produto
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    }

    public function salvar($dados)
    {
        if (!empty($dados['nome']) && !empty($dados['preco'])) {
            // 1. Inserir produto
            $stmt = $this->pdo->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
            $stmt->execute([$dados['nome'], $dados['preco']]);
            $produtoId = $this->pdo->lastInsertId();

            // 2. Inserir variações (se houver)
            if (!empty($dados['variacoes']) && is_array($dados['variacoes'])) {
                foreach ($dados['variacoes'] as $i => $nomeVar) {
                    $stmtVar = $this->pdo->prepare("INSERT INTO variacoes (produto_id, nome) VALUES (?, ?)");
                    $stmtVar->execute([$produtoId, $nomeVar]);
                    $variacaoId = $this->pdo->lastInsertId();

                    // Estoque da variação
                    $qtd = intval($dados['estoque'][$i] ?? 0);
                    $stmtEstoque = $this->pdo->prepare("INSERT INTO estoque (produto_id, variacao_id, quantidade) VALUES (?, ?, ?)");
                    $stmtEstoque->execute([$produtoId, $variacaoId, $qtd]);
                }
            } else {
                // 3. Estoque simples (sem variação)
                $stmtEstoque = $this->pdo->prepare("INSERT INTO estoque (produto_id, quantidade) VALUES (?, ?)");
                $stmtEstoque->execute([$produtoId, $dados['estoque_unico'] ?? 0]);
            }
        }
    }

    public function listar()
    {
        $stmt = $this->pdo->query("SELECT * FROM produtos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
