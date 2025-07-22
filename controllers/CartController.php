<?php
session_start();
require_once 'models/Produto.php';
require_once 'views/header.php';
require_once 'models/Coupon_model.php';

$produtoModel = new Produto();

// Add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produto_id'])) {
    $produtoId = $_POST['produto_id'];
    $quantidade = intval($_POST['quantidade']);

    $produto = $produtoModel->buscarPorId($produtoId);
    if (!$produto) {
        echo "<div class='alert alert-danger'>Produto não encontrado</div>";
    } else {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$produtoId])) {
            $_SESSION['cart'][$produtoId]['quantidade'] += $quantidade;
        } else {
            $_SESSION['cart'][$produtoId] = [
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'quantidade' => $quantidade
            ];
        }

        echo "<div class='alert alert-success'>Produto adicionado ao carrinho</div>";
    }
}

// Remover item
if (isset($_GET['remover'])) {
    unset($_SESSION['cart'][$_GET['remover']]);
}

// Calcular subtotal
$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}

// Calcular frete
if ($subtotal >= 52 && $subtotal <= 166.59) {
    $frete = 15.00;
} elseif ($subtotal > 200) {
    $frete = 0.00;
} else {
    $frete = 20.00;
}

$total = $subtotal + $frete;

$couponModel = new Coupon();
$coupon_applied = null;
$desconto = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_cupom'])) {
    $cupom = $couponModel->buscarPorCodigo($_POST['codigo_cupom']);
    if ($cupom && $subtotal >= $cupom['minimo_subtotal']) {
        $_SESSION['cupom'] = $cupom;
        $coupon_applied = $cupom;
        $desconto = $cupom['desconto'];
        echo "<div class='alert alert-success'>Cupom aplicado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Cupom inválido ou subtotal abaixo do mínimo.</div>";
    }
}

if (isset($_SESSION['coupon'])) {
    $coupon_applied = $_SESSION['coupon'];
    $desconto = $coupon_applied['desconto'];
}

$total = max(0, $subtotal + $frete - $desconto);

require_once 'views/cart/index.php';
require_once 'views/footer.php';
