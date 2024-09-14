<?php
require_once '../app/config/config.php';
require_once '../app/classes/Order.php';
require_once '../app/classes/User.php';
require_once '../app/classes/Cart.php';

$user = new User();

if (!$user->is_logged() || (!$user->is_admin() && !$user->is_tatko())) {
    header("Location: ../login.php");
    exit();
}

$order = new Order();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];

        if (in_array($status, ['primljeno', 'poslato', 'stiglo', 'otkazano'])) {
            $order->update_order_status($order_id, $status);
            $_SESSION['message']['type'] = "success";
            $_SESSION['message']['text'] = "Order status updated successfully.";
        } else {
            $_SESSION['message']['type'] = "danger";
            $_SESSION['message']['text'] = "Invalid status.";
        }
    } elseif ($_POST['action'] === 'delete_order') {
        $order_id = $_POST['order_id'];
        $order->delete_order($order_id);
        $_SESSION['message']['type'] = "success";
        $_SESSION['message']['text'] = "Order deleted successfully.";
    }

    header("Location: orders_status.php");
    exit();
}

$orders = $order->get_orders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php" class="btn btn-secondary mb-4">Back to Admin Panel</a>
        <h2 class="mb-4">Manage Orders</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message']['type']; ?>">
                <?php echo $_SESSION['message']['text']; ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Size</th>
                    <th>Image</th>
                    <th>Delivery Address</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['name']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['price']); ?> €</td>
                        <td><?php echo htmlspecialchars($order['size']); ?></td>
                        <td><img src="../public/product_images/<?php echo htmlspecialchars($order['image']); ?>" height="50"></td>
                        <td><?php echo htmlspecialchars($order['delivery_address']); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td class="status-<?php echo htmlspecialchars($order['status']); ?>">
                            <?php echo htmlspecialchars($order['status']); ?>
                        </td>
                        <td>
                            <!-- Update Status Form -->
                            <div class="mb-2">
                                <form action="orders_status.php" method="post">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                    <select name="status" class="form-control">
                                        <option value="primljeno" <?php echo $order['status'] === 'primljeno' ? 'selected' : ''; ?>>Primljeno</option>
                                        <option value="poslato" <?php echo $order['status'] === 'poslato' ? 'selected' : ''; ?>>Poslato</option>
                                        <option value="stiglo" <?php echo $order['status'] === 'stiglo' ? 'selected' : ''; ?>>Stiglo</option>
                                        <option value="otkazano" <?php echo $order['status'] === 'otkazano' ? 'selected' : ''; ?>>Otkazano</option>
                                    </select>
                                    <input type="hidden" name="action" value="update_status">
                                    <button type="submit" class="btn btn-primary btn-block mt-2">Update Status</button>
                                </form>
                            </div>

                            <!-- Delete Order Form -->
                            <div class="mb-2">
                                <form action="orders_status.php" method="post">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                    <input type="hidden" name="action" value="delete_order">
                                    <button type="submit" class="btn btn-danger btn-block mt-2">Delete Order</button>
                                </form>
                            </div>

                            <!-- Edit Order Link -->
                            <div class="mb-2">
                                <a href="edit_order.php?id=<?php echo htmlspecialchars($order['order_id']); ?>" class="btn btn-warning btn-block mt-2">Edit Order</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

