<?php

function enviarEmail($para, $pedidoId, $total)
{
    $assunto = "Confirmação do Pedido #{$pedidoId}";
    $mensagem = "Seu pedido foi realizado com sucesso! Valor total: R$ ".number_format($total, 2, ',', '.');
    $headers = "From: loja@meuerp.com.br\r\nContent-Type: text/plain; charset=UTF-8";

    @mail($para, $assunto, $mensagem, $headers);
}
