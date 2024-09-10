<?php
require '../app/config/config.php';
require '../app/classes/User.php';
require '../app/classes/Product.php';

$user = new User();

if ($user->is_logged() && ($user->is_admin() || $user->is_tatko())) : 

    $product_obj = new Product();
    $product = $product_obj->citajJedan($_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_id = $_GET['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $size = $_POST['size'];
        $image = $_POST['image'];

        $result=$product_obj->update($product_id, $name, $price, $size, $image);
        if ($result) {
            $_SESSION['message']['type'] = "success";
            $_SESSION['message']['text'] = "Uspesno ste updejtovali proizvod!";
        } else {
            $_SESSION['message']['type'] = "danger";
            $_SESSION['message']['text'] = "Došlo je do greške prilikom updejtovanja proizvoda.";
        }
        header("Location: edit_product.php?id=" . $product_id);
        exit();
    }

endif;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
    <?php
        // Display the session message
        if (isset($_SESSION['message'])):
        ?>
            <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']['text']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php
        // Unset the message after displaying it
        unset($_SESSION['message']);
        endif;
        ?>
    <a href="index.php" class="btn btn-secondary mb-4">Back to Admin Panel</a>
        <h2 class="mb-4">Edit Product</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="size">Size</label>
                <input type="text" class="form-control" id="size" name="size" value="<?php echo $product['size']; ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="text" class="form-control" id="image" name="image" value="<?php echo $product['image']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
