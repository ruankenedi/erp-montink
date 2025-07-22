<?php
function calcularFrete($subtotal) {
    if ($subtotal > 200) return 0;
    if ($subtotal >= 52 && $subtotal <= 166.59) return 15;
    return 20;
}
