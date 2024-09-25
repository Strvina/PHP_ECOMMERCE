<?php 
require_once "../inc/headerUser.php"; 
require_once "../app/classes/Order.php";

if (!$user->is_logged()) {
    header("Location: login.php");
    exit();
}

$order = new Order();
$orders_history = $order->getHistoryOrdersByUser($_SESSION['user_id']);

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
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders_history as $history): ?>
            <?php
            // Fetch associated order items for each order
            $order_items = $order->get_order_by_id($history['order_id']);
            foreach ($order_items as $item): // Loop through each item in the order
            ?>
                <tr>
                    <td><?php echo $history['order_id']; ?></td>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['price']; ?> €</td>
                    <td><?php echo $item['size']; ?></td>
                    <td><img src="../public/product_images/<?php echo $item['image']; ?>" height="50"></td>
                    <td><?php echo $history['delivery_address']; ?></td>
                    <td><?php echo $history['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once "../inc/footer.php"; ?>
