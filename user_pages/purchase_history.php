<?php
require_once(__DIR__ . '/../app/config/config.php');
require_once(__DIR__ . '/../app/classes/User.php');
require_once(__DIR__ . '/../app/classes/Order.php');
require_once '../inc/headerUser.php';

$user = new User();
$order = new Order();

// Ensure user is logged in
if (!$user->is_logged()) {
    header("Location: login.php");
    exit();
}


$orders = $order->get_orders();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1 class="mt-4 mb-4">Purchase History</h1>
        
        <?php if ($orders): ?>
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
                <td><img src="../public/product_images/<?php echo $order['image']; ?>" height="50"></td>
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
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

    <?php require_once '../inc/footer.php'; ?>
</body>
</html>
