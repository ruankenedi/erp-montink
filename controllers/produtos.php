<?php
require_once 'models/Produto.php';
require_once 'views/header.php';

$produtoModel = new Produto();

if (isset($_GET['action']) && $_GET['action'] === 'excluir' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['produtos_excluir'])) {
        $ids = $_POST['produtos_excluir'];
        
        // Preparar a query para deletar múltiplos IDs com placeholders
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM produtos WHERE id IN ($placeholders)";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($ids)) {
            $msg = count($ids) . " produto(s) excluído(s) com sucesso.";
        } else {
            $msg = "Erro ao excluir produtos.";
        }
    } else {
        $msg = "Nenhum produto selecionado para exclusão.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produtoModel->salvar($_POST);
    // echo "<div class='alert alert-success'>Produto salvo com sucesso!</div>";
     header('Location: ?page=produtos&sucesso=1');
}

$produtos = $produtoModel->listar();
require_once 'views/produtos/index.php';
require_once 'views/footer.php';
