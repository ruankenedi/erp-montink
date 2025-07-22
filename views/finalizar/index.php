<h2>Finalizar Pedido</h2>

<?php if (!empty($erro)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="POST">
  <div class="mb-3">
    <label class="form-label">CEP</label>
    <input type="text" name="cep" id="cep" class="form-control" required value="<?= isset($cep) ? htmlspecialchars($cep) : '' ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Endereço</label>
    <textarea name="endereco" id="endereco" class="form-control" required><?= isset($endereco) ? htmlspecialchars($endereco) : '' ?></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">E-mail para contato</label>
    <input type="email" name="email" class="form-control" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
  </div>
  <button class="btn btn-primary" type="submit">Confirmar Pedido</button>
</form>

<script>
// Preencher endereço automaticamente pelo ViaCEP
document.getElementById('cep').addEventListener('blur', function() {
  var cep = this.value.replace(/\D/g, '');
  if (cep.length === 8) {
    fetch('https://viacep.com.br/ws/' + cep + '/json/')
      .then(response => response.json())
      .then(data => {
        if (!data.erro) {
          document.getElementById('endereco').value = data.logradouro + ', ' + data.bairro + ', ' + data.localidade + ' - ' + data.uf;
        }
      });
  }
});
</script>
