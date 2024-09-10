<?php
require '../app/config/config.php';
require '../app/classes/User.php';
require '../app/classes/Product.php';

$user = new User();

if ($user->is_logged() && ($user->is_admin() || $user->is_tatko())) :

    $product=new Product();
    $product_id=$_GET['id'];
    $product->delete($product_id);
    header('Location: index.php');
    exit();
endif;