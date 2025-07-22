<h2>Cadastrar Produto</h2>

<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
  <div id="alertaSucesso" class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 200px;">
    Produto salvo com sucesso!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
  </div>
  <script>
    setTimeout(function () {
      const alerta = document.getElementById('alertaSucesso');
      if (alerta) {
        alerta.classList.remove('show');
        alerta.classList.add('fade');
        alerta.style.opacity = 0;
        setTimeout(() => alerta.remove(), 500);
      }
    }, 3000);
  </script>
<?php endif; ?>


<form method="POST" class="mb-4">
    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Preço</label>
        <input type="number" step="0.01" name="preco" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Variações (separadas por vírgula)</label>
        <input type="text" name="variacoes" class="form-control" placeholder="Ex: P, M, G">
    </div>
    <div class="mb-3">
        <label class="form-label">Estoque</label>
        <input type="number" name="estoque" class="form-control" required>
    </div>
    <button class="btn btn-primary" type="submit">Salvar Produto</button>
</form>

<script>
function addVariacao() {
    const container = document.getElementById('variacoes');
    const idx = container.children.length;
    container.innerHTML += `
        <div class="row mb-2">
            <div class="col">
                <input type="text" name="variacoes[]" class="form-control" placeholder="Nome da variação">
            </div>
            <div class="col">
                <input type="number" name="estoque[]" class="form-control" placeholder="Qtd em estoque">
            </div>
        </div>
    `;
}
</script>

<hr>

<h3>Produtos cadastrados</h3>

<form method="POST" action="?page=produtos&action=excluir">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th><input type="checkbox" id="checkTodos"></th>
        <th>Nome</th>
        <th>Preço (R$)</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($produtos as $produto): ?>
      <tr>
        <td><input type="checkbox" class="checkbox-produto" name="produtos_excluir[]" value="<?= $produto['id'] ?>"></td>
        <td><?= htmlspecialchars($produto['nome']) ?></td>
        <td><?= number_format($produto['preco'], 2, ',', '.') ?></td>
        <td>
          <a href="?page=CartController&add=<?= $produto['id'] ?>" class="btn btn-sm btn-success">Comprar</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <button type="submit" id="btnExcluir" class="btn btn-danger" disabled onclick="return confirm('Deseja mesmo excluir os produtos selecionados?')">
    Excluir Selecionados
  </button>
</form>

<script>
// Selecionar todos
document.getElementById('checkTodos').addEventListener('change', function () {
  const checkboxes = document.querySelectorAll('.checkbox-produto');
  checkboxes.forEach(cb => cb.checked = this.checked);
  atualizarBotaoExcluir();
});

// Habilitar botão quando marcar algum checkbox
document.querySelectorAll('.checkbox-produto').forEach(checkbox => {
  checkbox.addEventListener('change', atualizarBotaoExcluir);
});

function atualizarBotaoExcluir() {
  const checkboxes = document.querySelectorAll('.checkbox-produto:checked');
  const botao = document.getElementById('btnExcluir');
  botao.disabled = checkboxes.length === 0;
}
</script>

