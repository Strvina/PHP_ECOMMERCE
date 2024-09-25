<?php
require_once "inc/header.php";
require_once "app/classes/User.php";


if($user->is_logged()){
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"]== "POST"){
    $name=$_POST['name'];
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $phone_number=$_POST['phone_number'];

    $created=$user->create($name, $username, $email, $password, $phone_number);

    if($created){
        $_SESSION['message']['type']= "success";
        $_SESSION['message']['text']= "Uspesno ste registrovani";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['message']['type']= "danger";
        $_SESSION['message']['text']= "Greska";
        header("Location: register.php");
        exit();
    }
}

?>


    <h1 class="mt-5 mb-3">Registracija</h1>
    <form action="" method="post">
        <div class="form-group mb-3">
            <label for="name">Full name</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <div class="form-group mb-3">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group mb-3">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <div class="form-group mb-3">
            <label for="phone_number">Phone number</label>
            <input type="text" class="form-control" name="phone_number" id="phone_number" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

    <?php require_once 'inc/footer.php';
