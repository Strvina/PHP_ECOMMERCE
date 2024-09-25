<?php


require_once 'app/classes/product.php';
require_once 'app/config/config.php';

$products = new Product();

// Get the search parameters safely
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
$price_order = isset($_GET['price']) ? htmlspecialchars($_GET['price']) : '';

// Fetch product results based on search, category, and price order filters
if (!empty($search)) {
    if (!empty($category)) {
        if ($price_order === 'asc') {
            $results = $products->searchAndSortByPriceAsc($search, $category);
        } elseif ($price_order === 'desc') {
            $results = $products->searchAndSortByPriceDesc($search, $category);
        } else {
            $results = $products->trazi($search, $category);
        }
    } else {
        if ($price_order === 'asc') {
            $results = $products->searchAndSortByPriceAsc($search);
        } elseif ($price_order === 'desc') {
            $results = $products->searchAndSortByPriceDesc($search);
        } else {
            $results = $products->trazi($search);
        }
    }
} else {
    if (!empty($category)) {
        if ($price_order === 'asc') {
            $results = $products->sortByPriceAsc($category);
        } elseif ($price_order === 'desc') {
            $results = $products->sortByPriceDesc($category);
        } else {
            $results = $products->sortByCategory($category);
        }
    } elseif ($price_order === 'asc') {
        $results = $products->sortByPriceAsc();
    } elseif ($price_order === 'desc') {
        $results = $products->sortByPriceDesc();
    } else {
        $results = $products->izlistajSve();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <?php if (empty($results)) : ?>
                <div class="alert alert-warning">No items found.</div>
            <?php else : ?>
                <?php foreach ($results as $product): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <img src="public/product_images/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text">Price: <?= htmlspecialchars($product['price']) ?> €</p>
                                <p class="card-text">Size: <?= htmlspecialchars($product['size']) ?></p>

                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <form action="wishlist.php" method="post" class="float-right">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
                                        
                                        <?php
                                        // Check if the product is in the user's wishlist
                                        $isInWishlist = $products->isInWishlist($_SESSION['user_id'], $product['product_id']);
                                        $starClass = $isInWishlist ? 'fa-star' : 'fa-star-o';
                                        $action = $isInWishlist ? 'remove' : 'add';
                                        ?>
                                        
                                        <button type="submit" name="wishlist_action" value="<?= $action ?>" class="btn btn-outline-warning">
                                            <i class="fa <?= $starClass ?>" aria-hidden="true"></i>
                                        </button>
                                    </form>

                                    <a href="product.php?product_id=<?= htmlspecialchars($product['product_id']) ?>" class="btn btn-primary">View Product</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Wishlist JS -->
    <script src="public/js/wishlist.js"></script>
</body>
</html>
