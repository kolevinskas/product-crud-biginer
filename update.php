<?php

require_once './functions.php';

/** @var $pdo \ PDO */
require_once 'database.php';




$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$statement->bindValue(':id', $id);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);


$errors = [];
$title = $product['title'];
$newImgPath = '';
$description = $product['description'];
$price = $product['price'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];


    if (!$title) {
        $errors[] = "Product Title is required";
    }
    if (!$price) {
        $errors[] = "Product Price is required";
    }


    if (empty($errors)) {
        $image = $_FILES['image'] ?? null;
        $newImgPath = $product['image'];




        if ($image && $image['tmp_name']) {

            if ($product['image']) {
                unlink($product['image']);
            }


            $randomFolderName = generateRandomString();
            // Create random dir
            mkdir("./img/$randomFolderName");

            $newImgPath = 'img/' . $randomFolderName . '/' . $image['name'];
            move_uploaded_file($image['tmp_name'], $newImgPath);
        }

        $statement = $pdo->prepare(
            "UPDATE products SET title = :title, image = :image, description = :description, price = :price WHERE id = :id"
        );

        $statement->bindValue(':id', $product['id']);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $newImgPath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->execute();

        header('Location: index.php');
    }
}

?>

<?php include_once './views/partials/header.php'; ?>

<p>
    <a href="index.php" class="btn btn-secondary">Go back</a>
</p>

<h1>Update Product <b><?php echo $product['title']; ?></b></h1>

<!-- Form -->
<?php include_once './views/products/form.php'; ?>

<?php include_once './views/partials/footer.php'; ?>