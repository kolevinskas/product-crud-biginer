<?php

$pdo = new PDO('mysql:localhost;port=3006;dbname=products_crud', 'root', 'mysql');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
