<?php
// Roteador simples
$page = $_GET['page'] ?? 'produtos';
require_once "controllers/{$page}.php";