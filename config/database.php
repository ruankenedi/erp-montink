<?php
$pdo = new PDO('mysql:host=localhost;dbname=erp-montink', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
