<?php

// $pdo = new PDO('mysql:localhost;port=3006;dbname=products_crud', 'root', 'mysql');
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
/** @var $pdo \ PDO */
require_once 'database.php';



$id = $_POST['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}


$statement = $pdo->prepare('DELETE FROM products WHERE id = :id');
$statement->bindValue(':id', $id);
$statement->execute();

header('Location: index.php');
