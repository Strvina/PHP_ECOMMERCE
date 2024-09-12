<?php

class Product
{
    protected $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function izlistajSve()
    {
        $sql = "SELECT * FROM products";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function citajJedan($product_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE product_id=?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function add($name, $price, $size, $image)
    {
        $sql = "INSERT INTO products (name, price, size, image) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $price, $size, $image);
        $stmt->execute();
    }


    public function update($product_id, $name, $price, $size, $image)
    {
        $sql = "UPDATE products SET name = ?, price = ?, size = ?, image = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $price, $size, $image, $product_id);
        return $stmt->execute();
    }


    public function delete($product_id)
    {
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
    }

    public function trazi($trazi, $category = '') {
        $trazi = "%" . $trazi . "%";
        $categoryFilter = !empty($category) ? "AND category = ?" : "";
        $sql = "SELECT * FROM products WHERE name LIKE ? $categoryFilter";
        $stmt = $this->conn->prepare($sql);
        if (!empty($category)) {
            $stmt->bind_param("ss", $trazi, $category);
        } else {
            $stmt->bind_param("s", $trazi);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    

    // Update this method if you need to check category more specifically
    public function sortByCategory($category)
    {
        $category = "%" . $category . "%";
        $sql = "SELECT * FROM products WHERE category LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function sortByPriceAsc($category = '') {
        $categoryFilter = !empty($category) ? "WHERE category = ?" : "";
        $sql = "SELECT * FROM products $categoryFilter ORDER BY price ASC";
        $stmt = $this->conn->prepare($sql);
        if (!empty($category)) {
            $stmt->bind_param("s", $category);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function sortByPriceDesc($category = '') {
        $categoryFilter = !empty($category) ? "WHERE category = ?" : "";
        $sql = "SELECT * FROM products $categoryFilter ORDER BY price DESC";
        $stmt = $this->conn->prepare($sql);
        if (!empty($category)) {
            $stmt->bind_param("s", $category);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }



    public function getCategories()
    {
        $query = "SELECT DISTINCT category FROM products";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
