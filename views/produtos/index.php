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

<hr>

<h3>Produtos cadastrados</h3>

<!-- Modal de Edição -->
<div class="modal fade" id="modalEdicaoProduto" tabindex="-1" aria-labelledby="tituloModalEdicao" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="?page=produtos&action=editar" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModalEdicao">Editar Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">

        <input type="hidden" name="id" id="editarId">

        <div class="mb-3">
          <label class="form-label">Nome</label>
          <input type="text" name="nome" id="editarNome" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Preço</label>
          <input type="number" step="0.01" name="preco" id="editarPreco" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Variações</label>
          <input type="text" name="variacoes" id="editarVariacoes" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Estoque</label>
          <input type="number" name="estoque" id="editarEstoque" class="form-control" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      </div>
    </form>
  </div>
</div>

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
          <form method="POST" action="?page=CartController&add=<?= $produto['id'] ?>" style="display:inline-flex; align-items:center; gap:5px;">
            <input
              type="number"
              name="quantidade"
              value="1"
              min="1"
              max="<?= isset($produto['estoque']) ? (int)$produto['estoque'] : 1000 ?>"
              class="form-control form-control-sm"
              style="width: 70px;"
              required
            >
            <button type="submit" class="btn btn-sm btn-success">Comprar</button>
          </form>
          <button 
            class="btn btn-sm btn-warning" 
            onclick="abrirModalEdicao(<?= htmlspecialchars(json_encode($produto)) ?>)"
            type="button"
          >Editar</button>
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

function abrirModalEdicao(produto) {
  // Preenche os dados
  document.getElementById('editarId').value = produto.id;
  document.getElementById('editarNome').value = produto.nome;
  document.getElementById('editarPreco').value = produto.preco;
  document.getElementById('editarVariacoes').value = produto.variacoes ?? '';
  document.getElementById('editarEstoque').value = produto.estoque ?? 0;

  // Mostra a modal
  const modal = new bootstrap.Modal(document.getElementById('modalEdicaoProduto'));
  modal.show();
}
</script>
