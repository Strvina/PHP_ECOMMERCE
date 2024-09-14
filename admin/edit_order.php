<?php
require_once '../app/config/config.php';
require_once '../app/classes/Order.php';
require_once '../app/classes/User.php';

$user = new User();

if (!$user->is_logged() || (!$user->is_admin() && !$user->is_tatko())) {
    header("Location: ../login.php");
    exit();
}

$order = new Order();

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $orderDetails = $order->get_order_by_id($order_id);

    if (!$orderDetails) {
        header("Location: orders_status.php"); // Preusmeravanje na orders_status.php ako narudžbina ne postoji
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $status = $_POST['status'];
        $delivery_address = $_POST['delivery_address'];
        $quantities = $_POST['quantity']; // Assuming quantity is an associative array

        $result = $order->update_order($order_id, $status, $delivery_address, $quantities);
        if ($result) {
            $_SESSION['message']['type'] = "success";
            $_SESSION['message']['text'] = "Order updated successfully!";
            header("Location: orders_status.php"); // Preusmeravanje na orders_status.php nakon uspešnog ažuriranja
            exit();
        } else {
            $_SESSION['message']['type'] = "danger";
            $_SESSION['message']['text'] = "Error updating order.";
            header("Location: edit_order.php?id=" . $order_id); // Preusmeravanje nazad na edit_order.php u slučaju greške
            exit();
        }
    }
} else {
    header("Location: orders_status.php"); // Preusmeravanje na orders_status.php ako id nije postavljen
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
    <a href="orders_status.php" class="btn btn-secondary mb-4">Back to Orders Status</a>

        <h2>Edit Order</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']['text']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="delivery_address">Delivery Address</label>
                <input type="text" class="form-control" id="delivery_address" name="delivery_address" value="<?php echo htmlspecialchars($orderDetails[0]['delivery_address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="primljeno" <?php echo $orderDetails[0]['status'] === 'primljeno' ? 'selected' : ''; ?>>Primljeno</option>
                    <option value="poslato" <?php echo $orderDetails[0]['status'] === 'poslato' ? 'selected' : ''; ?>>Poslato</option>
                    <option value="stiglo" <?php echo $orderDetails[0]['status'] === 'stiglo' ? 'selected' : ''; ?>>Stiglo</option>
                    <option value="otkazano" <?php echo $orderDetails[0]['status'] === 'otkazano' ? 'selected' : ''; ?>>Otkazano</option>
                </select>
            </div>

            <?php foreach ($orderDetails as $item): ?>
                <div class="form-group">
                    <label for="quantity_<?php echo htmlspecialchars($item['product_id']); ?>">Quantity for Product ID <?php echo htmlspecialchars($item['product_id']); ?></label>
                    <input type="number" class="form-control" id="quantity_<?php echo htmlspecialchars($item['product_id']); ?>" name="quantity[<?php echo htmlspecialchars($item['product_id']); ?>]" value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
                </div>
            <?php endforeach; ?>
            
            <button type="submit" class="btn btn-primary">Update Order</button>
        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>
