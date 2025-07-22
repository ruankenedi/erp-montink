<h2>Gerenciar Cupons</h2>

<form method="POST" class="mb-4">
  <div class="mb-3">
    <label class="form-label">Código</label>
    <input type="text" name="codigo" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Desconto (R$)</label>
    <input type="number" step="0.01" name="desconto" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Subtotal mínimo</label>
    <input type="number" step="0.01" name="minimo_subtotal" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Validade</label>
    <input type="date" name="validade" class="form-control" required>
  </div>
  <button class="btn btn-primary" type="submit">Salvar Cupom</button>
</form>

<hr>

<h4>Cupons Atuais</h4>
<ul class="list-group">
  <?php foreach ($cupons as $cupom): ?>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <div>
      <strong><?= htmlspecialchars($cupom['codigo']) ?></strong> - R$ <?= number_format($cupom['desconto'], 2, ',', '.') ?><br>
      <small>Subtotal mínimo: R$ <?= number_format($cupom['minimo_subtotal'], 2, ',', '.') ?> | Validade: <?= date('d/m/Y', strtotime($cupom['validade'])) ?></small>
    </div>
  </li>
  <?php endforeach; ?>
</ul>
