<?php
require '../app/config/config.php';
require '../app/classes/User.php';

$user = new User();

if (!$user->is_logged() || (!$user->is_admin() && !$user->is_tatko())) {
    header("Location: ../login.php");
    exit();
}

$users = $user->get_all_users();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'promote') {
        $user->promote($user_id);
    } elseif ($action === 'demote') {
        $user->demote($user_id);
    }  elseif ($action === 'delete') {
        $user->delete($user_id);
    }
    
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
    <a href="index.php" class="btn btn-secondary mb-4">Back to Admin Panel</a>
        <h2 class="mb-4">Manage Users</h2>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['is_admin'] == '1' ? 'Admin' : ($user['is_admin'] == 'tatko' ? 'Tatko' : 'User'); ?></td>
                        <td>
                            <?php if ($user['is_admin'] === 'tatko'): ?>
                                <form action="" method="POST" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-important">TATKO</button>
                                </form>
                            <?php else: ?>
                                <?php if ($user['is_admin'] !== '1'): ?>
                                    <form action="" method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <input type="hidden" name="action" value="promote">
                                        <button type="submit" class="btn btn-primary">Promote to Admin</button>
                                    </form>
                                <?php else: ?>
                                    <form action="" method="POST" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <input type="hidden" name="action" value="demote">
                                        <button type="submit" class="btn btn-warning">Demote to User</button>
                                    </form>
                                <?php endif; ?>
                                <form action="" method="POST" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            <?php endif; ?>
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
