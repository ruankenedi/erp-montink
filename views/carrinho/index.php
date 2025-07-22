<h2>Carrinho</h2>

<?php if (empty($carrinho)): ?>
  <p>Seu carrinho está vazio.</p>
<?php else: ?>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Produto</th>
      <th>Quantidade</th>
      <th>Preço unitário</th>
      <th>Subtotal</th>
      <th>Ação</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($carrinho as $produtoId => $item): ?>
    
    <tr>
      <td><?= htmlspecialchars($item['nome']) ?></td>
      <td><?= $item['quantidade'] ?></td>
      <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
      <td>R$ <?= number_format($item['quantidade'] * $item['preco'], 2, ',', '.') ?></td>
      <td>
        <a href="?page=CartController&remover=<?= $produtoId ?>" class="btn btn-danger btn-sm">Remover</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<div class="mb-3">
  <strong>Subtotal:</strong> R$ <?= number_format($subtotal, 2, ',', '.') ?><br>
  <strong>Frete:</strong> R$ <?= number_format($frete, 2, ',', '.') ?><br>
  <?php if ($cupom_aplicado): ?>
    <strong>Desconto (Cupom <?= htmlspecialchars($cupom_aplicado['codigo']) ?>):</strong> -R$ <?= number_format($desconto, 2, ',', '.') ?><br>
  <?php endif; ?>
  <strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?>
</div>

<?php if (isset($_SESSION['mensagem_cupom'])): ?>
  <div class="alert <?= strpos($_SESSION['mensagem_cupom'], 'sucesso') !== false ? 'alert-success' : 'alert-danger' ?>">
    <?= $_SESSION['mensagem_cupom'] ?>
  </div>
  <?php unset($_SESSION['mensagem_cupom']); ?>
<?php endif; ?>


<!-- <form method="POST" class="mb-4">
  <div class="mb-3">
    <label class="form-label">Aplicar cupom</label>
    <input type="text" name="codigo_cupom" class="form-control" placeholder="Digite o código do cupom">
  </div>
  <button class="btn btn-primary" type="submit">Aplicar Cupom</button>
</form> -->

<form method="POST" action="?page=CartController" class="mb-3">
  <label>Cupom de Desconto:</label>
  <div class="input-group">
    <input type="text" name="codigo_cupom" class="form-control" placeholder="Digite o cupom" required>
    <button type="submit" name="aplicar_cupom" class="btn btn-outline-primary">Aplicar</button>
  </div>
</form>

<?php if ($cupom_aplicado): ?>
    <p><strong>Desconto aplicado:</strong> R$ <?= number_format($desconto, 2, ',', '.') ?> (<?= $cupom_aplicado['codigo'] ?>)</p>
<?php endif; ?>

<a href="?page=finalizar" class="btn btn-success">Finalizar Pedido</a>

<?php endif; ?>
