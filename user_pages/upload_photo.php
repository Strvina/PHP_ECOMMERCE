<?php

$photo= $_FILES['photo'];
//vraca samo ime slike ne i njegovu putanju
$photo_name=basename($photo['name']);

$photo_path= '../public/user_images/' . $photo_name;
//provera da li je extenzija dozvoljena
$allowed_ext=['jpg', 'jpeg', 'png', 'gif'];
$ext=pathinfo($photo_name, PATHINFO_EXTENSION);
if(in_array($ext, $allowed_ext) && $photo['size'] < 200000000) {
    move_uploaded_file($photo['tmp_name'], $photo_path);

    echo json_encode(['success' => true, 'photo_path' => $photo_name]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid file']);
}

