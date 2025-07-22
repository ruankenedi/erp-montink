<?php

class Produto
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    }

    public function salvarProdutoEdit($nome, $preco, $variacoes, $estoque)
    {
        // Inserir produto
        $stmt = $this->pdo->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
        $stmt->execute([$nome, $preco]);
        $produtoId = $this->pdo->lastInsertId();

        if (!empty($variacoes) && is_array($variacoes)) {
            foreach ($variacoes as $i => $nomeVar) {
                $stmtVar = $this->pdo->prepare("INSERT INTO variacoes (produto_id, nome) VALUES (?, ?)");
                $stmtVar->execute([$produtoId, $nomeVar]);

                $variacaoId = $this->pdo->lastInsertId();
                $qtd = intval($estoque[$i] ?? 0);

                $stmtEstoque = $this->pdo->prepare("INSERT INTO estoque (produto_id, variacao_id, quantidade) VALUES (?, ?, ?)");
                $stmtEstoque->execute([$produtoId, $variacaoId, $qtd]);
            }
        } else {
            $stmtEstoque = $this->pdo->prepare("INSERT INTO estoque (produto_id, quantidade) VALUES (?, ?)");
            $stmtEstoque->execute([$produtoId, $estoque ?? 0]);
        }
    }

    public function atualizarProdutoEdit($id, $nome, $preco, $variacoes, $estoque)
    {
        // Atualizar produto
        $stmt = $this->pdo->prepare("UPDATE produtos SET nome = ?, preco = ? WHERE id = ?");
        $stmt->execute([$nome, $preco, $id]);

        // Apagar variações e estoque antigos
        $this->pdo->prepare("DELETE FROM estoque WHERE produto_id = ?")->execute([$id]);
        $this->pdo->prepare("DELETE FROM variacoes WHERE produto_id = ?")->execute([$id]);

        // Recriar estoque e variações (simples ou com variações)
        if (!empty($variacoes) && is_array($variacoes)) {
            foreach ($variacoes as $i => $nomeVar) {
                $stmtVar = $this->pdo->prepare("INSERT INTO variacoes (produto_id, nome) VALUES (?, ?)");
                $stmtVar->execute([$id, $nomeVar]);
                $variacaoId = $this->pdo->lastInsertId();

                $qtd = intval($estoque[$i] ?? 0);
                $stmtEstoque = $this->pdo->prepare("INSERT INTO estoque (produto_id, variacao_id, quantidade) VALUES (?, ?, ?)");
                $stmtEstoque->execute([$id, $variacaoId, $qtd]);
            }
        } else {
            $stmtEstoque = $this->pdo->prepare("INSERT INTO estoque (produto_id, quantidade) VALUES (?, ?)");
            $stmtEstoque->execute([$id, $estoque ?? 0]);
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
