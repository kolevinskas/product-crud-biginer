<?php

require_once './functions.php';

/** @var $pdo \ PDO */
require_once 'database.php';



$errors = [];
$title = '';
$newImgPath = '';
$description = '';
$price = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d H:i:s');

    if (!$title) {
        $errors[] = "Product Title is required";
    }
    if (!$price) {
        $errors[] = "Product Price is required";
    }


    if (empty($errors)) {
        $image = $_FILES['image'] ?? null;
        if ($image && $image['tmp_name']) {
            $randomFolderName = generateRandomString();
            // Create random dir
            mkdir("./img/$randomFolderName");

            $newImgPath = 'img/' . $randomFolderName . '/' . $image['name'];
            move_uploaded_file($image['tmp_name'], $newImgPath);
        }

        $statement = $pdo->prepare(
            "INSERT INTO products(title, image, description, price, create_date)
        VALUES (:title, :image, :description, :price, :date)"
        );

        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $newImgPath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':date', $date);
        $statement->execute();

        header('Location: index.php');
    }
}

?>

<?php include_once './views/partials/header.php'; ?>

<p>
    <a href="index.php" class="btn btn-secondary">Go back</a>
</p>
<h1>Create New Product</h1>

<!-- Form -->
<?php include_once './views/products/form.php'; ?>



<?php include_once './views/partials/footer.php'; ?>