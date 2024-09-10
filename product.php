<?php
require_once 'inc/header.php';
require_once 'app/classes/Product.php';
require_once 'app/classes/Cart.php';


if(!$user->is_logged()){
    header("Location: login.php");
    exit();
}

$product=new Product();
$product=$product->citajJedan($_GET['product_id']);

if($_SERVER['REQUEST_METHOD']== "POST"){
    $product_id=$product['product_id'];
    $user_id=$_SESSION['user_id'];
    $quantity=$_POST["quantity"];
    $cart=new Cart();
    $cart->add_to_cart($product_id, $user_id, $quantity);
    header('Location: cart.php');
    exit();
}

?>


<div class="row">
    <div class="col-lg-6">
        <img src="public/product_images/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
    </div>
    <div class="col-lg-6">
        <h2><?php echo $product['name']; ?></h2>
        <p>Size: <?php echo $product['size']; ?></p>
        <p>Price: <?php echo $product['price']; ?>€</p>
        <form action="" method="post">
            <input type="number" name="quantity">
            <button type="submit" class="btn btn-primary">Add to Cart</button>
        </form>
    </div>
</div>


<?php require_once 'inc/footer.php'; ?>