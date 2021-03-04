<?php

require_once './functions.php';

$pdo = new PDO('mysql:localhost;port=3006;dbname=products_crud', 'root', 'mysql');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="./css/app.css" rel="stylesheet">
    <title>Products crud</title>
</head>

<body>
    <p>
        <a href="index.php" class="btn btn-secondary">Go back</a>
    </p>

    <h1>Update Product <b><?php echo $product['title']; ?></b></h1>


    <?php if (!empty($errors)) : ?>

        <div class="alert alert-danger">
            <?php foreach ($errors as $error) : ?>
                <div><?php echo $error; ?></div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">

        <?php if ($product['image']) : ?>

            <img src="<?php echo $product['image']; ?>" alt="" class="productPageImg">

        <?php endif; ?>

        <div class="form-group">
            <label>Product Image</label><br>
            <input class="mt-3 mb-3" type="file" name="image">
        </div>
        <div class="form-group">
            <label>Product Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" name="description"><?php echo $description; ?></textarea>
        </div>
        <div class=" form-group">
            <label>Product Price</label>
            <input type="number" step="0.1" class="form-control" name="price" value="<?php echo $price; ?>">
        </div>


        <button type=" submit" class="btn mt-3 btn-primary">Submit</button>
    </form>

</body>

</html>