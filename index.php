<?php

$pdo = new PDO('mysql:localhost;port=3006;dbname=products_crud', 'root', 'mysql');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$search = $_GET['search'] ?? '';

if ($search) {
    $statement =  $pdo->prepare('SELECT * FROM products WHERE title LIKE :title ORDER BY create_date DESC ');
    $statement->bindValue(':title', "%$search%");
} else {
    $statement =  $pdo->prepare('SELECT * FROM products ORDER BY create_date DESC ');
}


$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);


//echo '<pre>';
//var_dump($products);
//echo '</pre>';

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

    <h1>Products Crud</h1>

    <p>
        <a href="create.php" type="button" class="btn btn-outline-success">Create product</a>

    </p>

    <form action="" method="GET">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search for products" name="search" value="<?php echo $search; ?>">
            <button class="btn btn-outline-secondary" type="button" id="button-addon2" type="submit">Search</button>
        </div>
    </form>


    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Image</th>
                <th scope="col">Title</th>
                <th scope="col">Price</th>
                <th scope="col">Created Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            foreach ($products as $i => $product) : ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><img src="<?php echo $product['image']; ?>" class="productImg" alt=""></td>
                    <td><?php echo  $product['title']; ?></td>
                    <td><?php echo  $product['price']; ?></td>
                    <td><?php echo  $product['create_date']; ?></td>
                    <td>
                        <a href="update.php?id=<?php echo $product['id']; ?>" class=" btn btn-sm btn-outline-primary">Edit</a>

                        <form action="delete.php" method="POST" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>

                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

</body>

</html>