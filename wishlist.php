<?php
require_once 'app/classes/product.php';
require_once 'app/config/config.php';
include_once "inc/header.php";

$products = new Product();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-warning">Please log in to view your wishlist.</div>';
    exit();
}

// Get the user's wishlist
$userId = $_SESSION['user_id'];
$wishlistItems = $products->getWishlist($userId);

// Handle wishlist actions
if (isset($_POST['product_id']) && isset($_POST['wishlist_action'])) {
    $productId = intval($_POST['product_id']);

    if ($_POST['wishlist_action'] === 'add') {
        // Add to wishlist
        $products->addToWishlist($userId, $productId);
    } elseif ($_POST['wishlist_action'] === 'remove') {
        // Remove from wishlist
        $products->removeFromWishlist($userId, $productId);
    }

    // Refresh the wishlist items
    $wishlistItems = $products->getWishlist($userId);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Your Wishlist</h2>
        
        <?php if (empty($wishlistItems)): ?>
            <div class="alert alert-warning">Your wishlist is empty.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($wishlistItems as $item): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="public/product_images/<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p class="card-text">Price: <?php echo htmlspecialchars($item['price']); ?> €</p>
                                
                                <!-- Form to remove item from wishlist -->
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['product_id']); ?>">
                                    <input type="hidden" name="wishlist_action" value="remove">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Remove from Wishlist
                                    </button>
                                </form>
                                <a href="product.php?product_id=<?php echo htmlspecialchars($item['product_id']); ?>" class="btn btn-primary mt-2">View Product</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>
