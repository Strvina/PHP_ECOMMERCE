<?php require_once "inc/header.php"; 
require_once "app/classes/Cart.php";
require_once "app/classes/Order.php";


if(!$user->is_logged()){
    header("Location: login.php");
    exit();
}

$order=new Order();
$orders=$order->get_orders();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
    $order_id = $_POST['order_id'];
    $order->cancel_order($order_id);
    
    $_SESSION['message']['type'] = "success";
    $_SESSION['message']['text'] = "Order has been successfully canceled.";
    
    header("Location: orders.php");
    exit();
}


?>

<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Order ID</th>
            <th scope="col">Product Name</th>
            <th scope="col">Quantity</th>
            <th scope="col">Price</th>
            <th scope="col">Size</th>
            <th scope="col">Image</th>
            <th scope="col">Delivery Address</th>
            <th scope="col">Order Date</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['order_id']; ?></td>
                <td><?php echo $order['name']; ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo $order['price']; ?> €</td>
                <td><?php echo $order['size']; ?></td>
                <td><img src="public/product_images/<?php echo $order['image']; ?>" height="50"></td>
                <td><?php echo $order['delivery_address']; ?></td>
                <td><?php echo $order['created_at']; ?></td>
                <td>
                    <form action="orders.php" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <input type="hidden" name="action" value="cancel">
                        <button type="submit" class="btn btn-danger btn-sm">Cancel order</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>










<?php require_once "inc/footer.php" ?>