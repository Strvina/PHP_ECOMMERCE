    <?php

    require_once 'inc/header.php';
    require_once 'app/classes/product.php';

    if (isset($_SESSION['user_id'])) {
        echo '<div class="alert alert-success">';
        echo 'Ajde, ' . 'kutre (' .($_SESSION['username']) . ') ulegni' . '!';
        echo '</div>';
    }

    $products=new Product();
    $products=$products->izlistajSve();
    ?>

    <div class="container">
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="public/product_images/<?php echo $product['image']; ?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                            <p class="card-text">Price: <?php echo $product['price']; ?> €</p>
                            <p class="card-text">Size: <?php echo $product['size']; ?></p>
                            <?php if($user->is_logged()) : ?>
                            <a href="product.php?product_id=<?php echo $product['product_id'] ?>" class="btn btn-primary">View product</a>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
 
    <?php require_once 'inc/footer.php'; ?>