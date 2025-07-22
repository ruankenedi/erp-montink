<?php
require_once 'config/config.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['pedido_id']) || !isset($data['status'])) {
    http_response_code(400);
    echo json_encode(["erro" => "Dados inválidos."]);
    exit;
}

$pedidoId = (int) $data['pedido_id'];
$status = strtolower(trim($data['status']));

try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($status === 'cancelado') {
        // 1. Buscar itens do pedido
        $stmtItens = $pdo->prepare("SELECT produto_id, quantidade FROM pedido_itens WHERE pedido_id = ?");
        $stmtItens->execute([$pedidoId]);
        $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

        // 2. Repor o estoque
        foreach ($itens as $item) {
            $stmtEstoque = $pdo->prepare("UPDATE estoque SET quantidade = quantidade + ? WHERE produto_id = ?");
            $stmtEstoque->execute([$item['quantidade'], $item['produto_id']]);
        }

        // 3. Excluir itens e pedido
        $pdo->prepare("DELETE FROM pedido_itens WHERE pedido_id = ?")->execute([$pedidoId]);
        $pdo->prepare("DELETE FROM pedidos WHERE id = ?")->execute([$pedidoId]);

        echo json_encode(["mensagem" => "Pedido cancelado e removido com sucesso."]);
    } else {
        // Atualiza o status
        $stmt = $pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->execute([$status, $pedidoId]);

        echo json_encode(["mensagem" => "Status atualizado com sucesso."]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao processar webhook: " . $e->getMessage()]);
}
