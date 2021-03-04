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