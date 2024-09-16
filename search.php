<?php
require_once 'app/classes/product.php';
require_once 'app/config/config.php';

$products = new Product();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$price_order = isset($_GET['price']) ? $_GET['price'] : '';


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



if (empty($results)) {
    echo '<div class="alert alert-warning">No items found.</div>';
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
?>
