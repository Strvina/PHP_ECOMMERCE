<?php
class User
{
    protected $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function get_user_by_id($user_id)
    {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function get_all_users()
    {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update_profile($user_id, $username, $email, $new_password = null, $photo_path = null)
    {
        if ($new_password) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $query = "UPDATE users SET username = ?, email = ?, password = ?, image = ? WHERE user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssi", $username, $email, $hashed_password, $photo_path, $user_id);
        } else {
            $query = "UPDATE users SET username = ?, email = ?, image = ? WHERE user_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssi", $username, $email, $photo_path, $user_id);
        }

        $stmt->execute();
        $stmt->close();
    }

    public function create($name, $username, $email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("ssss", $name, $username, $email, $hashed_password);

        $result = $stmt->execute();

        if ($result) {
            $_SESSION['user_id'] = $result->insert_id;
            return true;
        } else {
            return false;
        }
    }

    public function login($username, $password)
    {
        $sql = "SELECT user_id, username, password FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $results = $stmt->get_result();
        if ($results->num_rows == 1) {
            $user = $results->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                return true;
            }
        }
        return false;
    }

    public function promote($user_id)
    {
        $stmt = $this->conn->prepare("UPDATE users SET is_admin = '1' WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        return $stmt->execute();
    }

    public function demote($user_id)
    {
        // Proveri dal je tatko
        $stmt = $this->conn->prepare("SELECT is_admin FROM users WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result['is_admin'] !== 'tatko') {
            // Proceed to demote
            $stmt = $this->conn->prepare("UPDATE users SET is_admin = '0' WHERE user_id = ?");
            $stmt->bind_param('i', $user_id);
            return $stmt->execute();
        } else {
            return false; // Deny demotion
        }
    }

    public function delete($user_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        return $stmt->execute();
    }

    public function is_logged()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        return false;
    }

    public function is_admin()
    {
        $sql = "SELECT * FROM users where user_id=? AND is_admin='1'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function is_tatko()
    {
        $sql = "SELECT * FROM users where user_id=? AND is_admin='tatko'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
    }
}
