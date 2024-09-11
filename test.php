<?php

require_once "app/config/config.php";
require_once "app/classes/User.php";

$user = new User();

if ($user->is_logged()) {
    echo "User is logged in.";
} else {
    echo "User is not logged in.";
}

echo "GASGASGAS";
?>
