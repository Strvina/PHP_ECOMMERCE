<?php
ob_start();

require_once '../app/config/config.php';
require_once '../app/classes/User.php';
require_once '../inc/header.php';

$user = new User();

if (!$user->is_logged()) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_data = $user->get_user_by_id($user_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = !empty($_POST['new_password']) ? $_POST['new_password'] : null;
    $photo_path = $_POST['photo_path'];
    $phone = $_POST['phone_number'];

    $user->update_profile($user_id, $username, $email, $new_password, $photo_path, $phone);

    $_SESSION['message'] = [
        'type' => 'success',
        'text' => 'Profile updated successfully!'
    ];

    header("Location: profile.php");
    exit();
}


ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Update Profile</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message']['type']); ?> alert-dismissible fade show" role="alert">
                <?php
                echo htmlspecialchars($_SESSION['message']['text']);
                unset($_SESSION['message']);
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Add Phone Number/Change:</label>
                <input type="tel" id="phone_number" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($user_data['phone_number']); ?>" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" class="form-control">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
            </div>

            <div class="form-group">
                <label for="dropzone-upload">Upload Profile Picture</label>
                <div id="dropzone-upload" class="dropzone border rounded"></div>
            </div>

            <input type="hidden" name="photo_path" id="photoPathInput">

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK3G6W9d0DkC6XyU7s3O61f0sIkjJp3jzJ9h8E2u8TxO4L8" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UOaTOaTCOZbKkS7H1MC6A3K/c6mBvoC6s0M8ltNQZ9X2k5U5bT0mZf86qR8t2i9J" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-d8s93E4F33eb1X8CftO+kdF+I4I1a1O4+yZ4W8FMWC5T5z9L3R9Ff83y7jB+GSiI" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

    <script>
        Dropzone.options.dropzoneUpload = {
            url: "upload_photo.php",
            paramName: "photo",
            maxFilesize: 20,
            acceptedFiles: "image/*",
            init: function() {
                this.on("success", function(file, response) {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        document.getElementById('photoPathInput').value = jsonResponse.photo_path;
                    } else {
                        console.error(jsonResponse.error);
                    }
                });
            }
        };
    </script>
</body>

</html>