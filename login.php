<?php 
require_once 'inc/header.php';
require_once "app/classes/User.php";

if($user->is_logged()){
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"]== "POST"){
    
    $username=$_POST['username'];
    $password=$_POST['password'];

    $result=$user->login($username,$password);

    if(!$result){
        $_SESSION['message']['type']= "danger";
        $_SESSION['message']['text']= "Username ili password nije tacan!!!";

        header("Location: login.php");
        exit();
    } 
    if($user->is_admin() || $user->is_tatko()){
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }

    
}

?>


<div class="row justify-content-center">
    <div class="col-lg-5">
        <h3 class="text-center py-5">Login</h3>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
                <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>

<?php require_once 'inc/footer.php' ?>