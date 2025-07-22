<?php
require_once 'models/Produto.php';
require_once 'views/header.php';

$produtoModel = new Produto();

// Exclusão de produtos
if (isset($_GET['action']) && $_GET['action'] === 'excluir' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['produtos_excluir'])) {
        $ids = $_POST['produtos_excluir'];
        
        // Criar placeholders para os produtos
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        // Deletar os itens dos pedidos que usam esses produtos
        $sqlItens = "DELETE FROM pedido_itens WHERE produto_id IN ($placeholders)";
        $stmtItens = $pdo->prepare($sqlItens);
        $stmtItens->execute($ids);

        // Deletar os produtos
        $sql = "DELETE FROM produtos WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute($ids);

        $msg = $resultado ? count($ids) . " produto(s) excluído(s) com sucesso." : "Erro ao excluir produtos.";
    } else {
        $msg = "Nenhum produto selecionado para exclusão.";
    }
}


// Cadastro ou Edição de produtos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    $id = isset($_POST['id']) && $_POST['id'] !== '' ? intval($_POST['id']) : null;
    $nome = $_POST['nome'];
    $preco = str_replace(',', '.', $_POST['preco']);
    $variacoes = $_POST['variacoes'] ?? '';
    $estoque = intval($_POST['estoque']);

    if ($id) {
        // Atualizar produto
        $produtoModel->atualizarProdutoEdit($id, $nome, $preco, $variacoes, $estoque);
    } else {
        // Novo produto
        $produtoModel->salvarProdutoEdit($nome, $preco, $variacoes, $estoque);
    }

    header('Location: ?page=produtos&sucesso=1');
    exit;
}

// Listagem de produtos
$produtos = $produtoModel->listar();

require_once 'views/produtos/index.php';
require_once 'views/footer.php';
