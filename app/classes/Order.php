<?php

require_once __DIR__ . '/Cart.php';

class Order extends Cart
{
    protected $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function create($delivery_address)
    {
        $stmt = $this->conn->prepare("INSERT INTO orders (user_id, delivery_address) VALUES (?, ?)");
        $stmt->bind_param("is", $_SESSION["user_id"], $delivery_address);
        $stmt->execute();

        $order_id = $this->conn->insert_id;
        $cart_items = $this->get_cart_items();

        $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt->bind_param("iis", $order_id, $item['product_id'], $item['quantity']);
            $stmt->execute();
        }

        // Add to orders_history
        $this->addToHistory($order_id, $_SESSION["user_id"], $delivery_address, 'pending');

        $this->destroy_cart();
    }

    private function addToHistory($order_id, $user_id, $delivery_address, $status)
    {
        $stmt = $this->conn->prepare("INSERT INTO orders_history (order_id, user_id, delivery_address, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $order_id, $user_id, $delivery_address, $status);
        $stmt->execute();
    }

    public function get_orders()
    {
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT orders.order_id, orders.delivery_address, orders.created_at, order_items.quantity, products.name, products.image, products.price, products.size
                FROM orders
                INNER JOIN order_items ON orders.order_id = order_items.order_id
                INNER JOIN products ON order_items.product_id=products.product_id
                WHERE orders.user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function cancel_order($order_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $stmt = $this->conn->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
    }

    public function update_order_status($order_id, $status)
    {
        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
    }

    public function delete_order($order_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();

        // Delete the order
        $stmt = $this->conn->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
    }


    public function get_order_by_id($order_id)
    {
        $query = "
            SELECT orders.order_id, orders.delivery_address, orders.created_at, orders.status,
                   order_items.product_id, order_items.quantity
            FROM orders
            INNER JOIN order_items ON orders.order_id = order_items.order_id
            WHERE orders.order_id = ?
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch all rows and group them by order_id
        $order_details = array();
        while ($row = $result->fetch_assoc()) {
            $order_details[] = $row;
        }

        return $order_details;
    }

    public function update_order($order_id, $status, $delivery_address, $quantities)
    {
        $stmt = $this->conn->prepare("UPDATE orders SET status = ?, delivery_address = ? WHERE order_id = ?");
        $stmt->bind_param("ssi", $status, $delivery_address, $order_id);
        $stmt->execute();

        foreach ($quantities as $product_id => $quantity) {
            $stmt = $this->conn->prepare("UPDATE order_items SET quantity = ? WHERE order_id = ? AND product_id = ?");
            $stmt->bind_param("iis", $quantity, $order_id, $product_id);
            $stmt->execute();
        }

        return true;
    }

    public function getOrdersByUser($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getHistoryOrdersByUser($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM orders_history WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
