<?php
require_once 'inc/header.php';
require_once 'app/classes/product.php';

$products = new Product();
$categories = $products->getCategories();
$exclusiveProduct = $products->getExclusiveProduct();

if (isset($_SESSION['user_id'])) {
   
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
        <?php if ($exclusiveProduct): ?>
            <div id="exclusive-notification" class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                <img src="public/product_images/<?php echo htmlspecialchars($exclusiveProduct['image']); ?>" alt="<?php echo htmlspecialchars($exclusiveProduct['name']); ?>" style="width: 50px; height: 50px; margin-right: 10px;">
                <strong>Exclusive Offer! -50%!!!</strong> Check out our exclusive item: 
                <a href="product.php?product_id=<?php echo htmlspecialchars($exclusiveProduct['product_id']); ?>" class="alert-link">View Product</a>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

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
