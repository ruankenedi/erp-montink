<?php
session_start();
require_once 'models/Produto.php';
require_once 'views/header.php';

$produtoModel = new Produto();

if (isset($_GET['add'])) {
    $produtoId = intval($_GET['add']);
    $quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 1;
    if ($quantidade < 1) $quantidade = 1; // mínimo 1

    $produto = $produtoModel->buscarPorId($produtoId);
    if ($produto) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$produtoId])) {
            $_SESSION['cart'][$produtoId]['quantidade'] += $quantidade;
        } else {
            $_SESSION['cart'][$produtoId] = [
                'produto_id' => $produto['id'],
                'nome' => $produto['nome'],
                'preco' => $produto['preco'],
                'quantidade' => $quantidade
            ];
        }

        header('Location: ?page=CartController&msg=addsuccess');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Produto não encontrado.</div>";
    }
}

// Remover item do carrinho
if (isset($_GET['remover'])) {
    unset($_SESSION['cart'][$_GET['remover']]);
}

// Aplicar cupom
if (isset($_POST['aplicar_cupom']) && !empty($_POST['codigo_cupom'])) {
    require_once 'models/Coupon_model.php';
    $cupomModel = new Coupon_model();

    $codigo = strtoupper(trim($_POST['codigo_cupom']));
    $cupom = $cupomModel->buscarPorCodigo($codigo);

    if ($cupom) {
        $_SESSION['cupom_aplicado'] = $cupom;
        $_SESSION['mensagem_cupom'] = 'Cupom aplicado com sucesso!';
    } else {
        unset($_SESSION['cupom_aplicado']);
        $_SESSION['mensagem_cupom'] = 'Cupom inválido ou expirado.';
    }

    header('Location: ?page=CartController');
    exit;
}


// Cálculo subtotal, frete, desconto etc, mantém igual ao seu código original
$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}

if ($subtotal >= 52 && $subtotal <= 166.59) {
    $frete = 15.00;
} elseif ($subtotal > 200) {
    $frete = 0.00;
} else {
    $frete = 20.00;
}

// Verificar se tem cupom aplicado
$cupom_aplicado = $_SESSION['cupom_aplicado'] ?? null;
$desconto = $_SESSION['desconto'] ?? 0;

$total = $subtotal + $frete;

$desconto = 0;

if ($cupom_aplicado && $subtotal >= $cupom_aplicado['minimo_subtotal']) {
    $desconto = floatval($cupom_aplicado['desconto']);
}

$total = $subtotal + $frete - $desconto;
if ($total < 0) $total = 0;


// Mensagem de sucesso ao adicionar produto
if (isset($_GET['msg']) && $_GET['msg'] === 'addsuccess') {
    echo '<div class="alert alert-success">Produto adicionado ao carrinho com sucesso!</div>';
}

$carrinho = $_SESSION['cart'] ?? [];

require_once 'views/carrinho/index.php';
require_once 'views/footer.php';
