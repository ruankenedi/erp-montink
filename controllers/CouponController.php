<?php
require_once 'models/Coupon_model.php';
require_once 'views/header.php';

$model = new Coupon_model();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->salvar($_POST);
    echo "<div class='alert alert-success'>Cupom salvo com sucesso!</div>";
}

$cupons = $model->listar();
require_once 'views/cupons/index.php';
require_once 'views/footer.php';
