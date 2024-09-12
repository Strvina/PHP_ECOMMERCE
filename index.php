<?php
require_once 'inc/header.php';
require_once 'app/classes/product.php';

$products = new Product();
$categories = $products->getCategories();

if (isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-success">';
    echo 'Ajde, ' . 'kutre (' . $_SESSION['username'] . ') ulegni' . '!';
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/style.css" rel="stylesheet">

</head>
<body>
    <div class="container">
        <input type="text" id="search" class="form-control mt-3" placeholder="Search for products...">
        
        <div class="row mt-4">
            <div class="col-md-4">
                <select id="category-filter" class="form-select mt-3">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['category']); ?>">
                            <?php echo htmlspecialchars($category['category']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select id="price-filter" class="form-select mt-3">
                    <option value="">Sort by Price</option>
                    <option value="asc">Price Low to High</option>
                    <option value="desc">Price High to Low</option>
                </select>
            </div>
        </div>
        
        <div id="search-results" class="row mt-4"></div>
    </div>
    
    <?php require_once 'inc/footer.php'; ?>
    <script src="public/js/search.js"></script>
</body>
</html>
