<?php
session_start();
require_once 'models/Produto.php';
require_once 'views/header.php';
require_once 'helpers/email.php';

$produtoModel = new Produto();
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "<div class='alert alert-warning'>Carrinho vazio. Adicione produtos antes de finalizar o pedido.</div>";
    require_once 'views/footer.php';
    exit;
}

// Calcular subtotal
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}

// Regras de frete
if ($subtotal >= 52 && $subtotal <= 166.59) {
    $frete = 15.00;
} elseif ($subtotal > 200) {
    $frete = 0.00;
} else {
    $frete = 20.00;
}
$total = $subtotal + $frete;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $email = $_POST['email'];

    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO pedidos (subtotal, frete, total, cep, endereco, email) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$subtotal, $frete, $total, $cep, $endereco, $email]);
    $pedidoId = $pdo->lastInsertId();

    foreach ($cart as $produtoId => $item) {
        $stmt = $pdo->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        $stmt->execute([$pedidoId, $produtoId, $item['quantidade'], $item['preco']]);

        // Atualiza o estoque
        $stmtEstoque = $pdo->prepare("UPDATE estoque SET quantidade = quantidade - ? WHERE produto_id = ?");
        $stmtEstoque->execute([$item['quantidade'], $produtoId]);
    }

    $pdo->commit();
    enviarEmail($email, $pedidoId, $total);

    unset($_SESSION['cart']);

    echo "<div class='alert alert-success'>Pedido finalizado! Verifique seu e-mail.</div>";
    require_once 'views/footer.php';
    exit;
}

require_once 'views/finalizar/index.php';
require_once 'views/footer.php';
