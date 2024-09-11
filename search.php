<?php
require_once 'app/classes/product.php';
require_once 'app/config/config.php'; // Adjust this path if necessary

$products = new Product();

// Check if 'search' parameter is set
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    if (!empty($search)) {
        // Search for products
        $results = $products->trazi($search);

        if (empty($results)) {
            // No items found for search query
            echo '<div class="alert alert-warning">No items found.</div>';
        } else {
            // Display the results
            foreach ($results as $product) {
                echo '<div class="col-md-4">';
                echo '<div class="card mb-4">';
                echo '<img src="public/product_images/' . htmlspecialchars($product['image']) . '" class="card-img-top">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>';
                echo '<p class="card-text">Price: ' . htmlspecialchars($product['price']) . ' €</p>';
                echo '<p class="card-text">Size: ' . htmlspecialchars($product['size']) . '</p>';
                if (isset($_SESSION['user_id'])) {
                    echo '<a href="product.php?product_id=' . htmlspecialchars($product['product_id']) . '" class="btn btn-primary">View product</a>';
                }
                echo '</div></div></div>';
            }
        }
    } else {
        // If search query is empty, list all products
        $results = $products->izlistajSve();
        if (empty($results)) {
            echo '<div class="alert alert-warning">No items available.</div>';
        } else {
            foreach ($results as $product) {
                echo '<div class="col-md-4">';
                echo '<div class="card mb-4">';
                echo '<img src="public/product_images/' . htmlspecialchars($product['image']) . '" class="card-img-top">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>';
                echo '<p class="card-text">Price: ' . htmlspecialchars($product['price']) . ' €</p>';
                echo '<p class="card-text">Size: ' . htmlspecialchars($product['size']) . '</p>';
                if (isset($_SESSION['user_id'])) {
                    echo '<a href="product.php?product_id=' . htmlspecialchars($product['product_id']) . '" class="btn btn-primary">View product</a>';
                }
                echo '</div></div></div>';
            }
        }
    }
} else {
    // Display all products if 'search' parameter is not set
    $results = $products->izlistajSve();
    if (empty($results)) {
        echo '<div class="alert alert-warning">No items available.</div>';
    } else {
        foreach ($results as $product) {
            echo '<div class="col-md-4">';
            echo '<div class="card mb-4">';
            echo '<img src="public/product_images/' . htmlspecialchars($product['image']) . '" class="card-img-top">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>';
            echo '<p class="card-text">Price: ' . htmlspecialchars($product['price']) . ' €</p>';
            echo '<p class="card-text">Size: ' . htmlspecialchars($product['size']) . '</p>';
            if (isset($_SESSION['user_id'])) {
                echo '<a href="product.php?product_id=' . htmlspecialchars($product['product_id']) . '" class="btn btn-primary">View product</a>';
            }
            echo '</div></div></div>';
        }
    }
}
?>
