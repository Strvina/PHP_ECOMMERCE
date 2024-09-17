<?php
ob_start();

require_once '../app/config/config.php';
require_once '../app/classes/User.php';
require_once '../inc/headerUser.php';

$user = new User();

if (!$user->is_logged()) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_data = $user->get_user_by_id($user_id);

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">My data:</h2>

        <div class="card mb-4">
            <div class="card-body text-center">
                <?php if (!empty($user_data['image'])): ?>
                    <img src="../public/user_images/<?php echo htmlspecialchars($user_data['image']); ?>" alt="Profile Picture" class="profile-img mb-3">
                <?php else: ?>
                    <img src="https://via.placeholder.com/150" alt="Default Profile Picture" class="profile-img mb-3">
                <?php endif; ?>
                <h4 class="card-title"><?php echo htmlspecialchars($user_data['username']); ?></h4>
                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>
                <p class="card-text"><strong>Phone Number:</strong> <?php echo htmlspecialchars($user_data['phone_number']); ?></p>
                <a href="update_profile.php" class="btn btn-primary">Update Details</a>
            </div>
        </div>
    </div>

    <?php require_once '../inc/footer.php'; ?>
</body>

</html>
