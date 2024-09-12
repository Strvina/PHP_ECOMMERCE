<?php 
require_once 'inc/header.php';
require_once 'app/classes/Cart.php'; 

if (!$user->is_logged()) {
    header("Location: login.php");
    exit();
}

$cart = new Cart();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $cart->remove_item($product_id);
        $_SESSION['message']['type'] = "success";
        $_SESSION['message']['text'] = "Item successfully removed from the cart!";
    } else {
        $quantity = (int) $_POST['quantity'];

        if ($quantity < 1) {
            $_SESSION['message']['type'] = "danger";
            $_SESSION['message']['text'] = "Quantity must be at least 1.";
        } else {
            $cart->update_item_quantity($product_id, $quantity);
            $_SESSION['message']['type'] = "success";
            $_SESSION['message']['text'] = "Successfully updated the quantity!";
        }
    }
    header("Location: cart.php");
    exit();
}


$cart_items = $cart->get_cart_items();
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Product Name</th>
            <th scope="col">Size</th>
            <th scope="col">Price</th>
            <th scope="col">Quantity</th>
            <th scope="col">Image</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['size']; ?></td>
                <td>$<?php echo $item['price']; ?></td>
                <td>
                    <form action="cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                        <button type="submit" class="btn btn-primary btn-sm">Update quantity</button>
                    </form>
                </td>
                <td><img src="public/product_images/<?php echo $item['image']; ?>" height="50"></td>
                <td>
                    <form action="cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-danger btn-sm">Delete item</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="checkout.php" class="btn btn-success">Checkout</a>

<?php require_once 'inc/footer.php'; ?>
