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
        $query = "SELECT * FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function add($name, $price, $size, $image, $category)
    {
        $sql = "INSERT INTO products (name, price, size, category, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $price, $size, $category, $image);
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

    public function trazi($trazi, $category = '')
    {
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

    public function getCategories()
    {
        $query = "SELECT DISTINCT category FROM products";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function sortByPriceAsc($category = '')
    {
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

    public function sortByPriceDesc($category = '')
    {
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

    public function searchAndSortByPriceAsc($search, $category = '')
    {
        $search = "%" . $search . "%";
        $categoryFilter = !empty($category) ? "AND category = ?" : "";
        $sql = "SELECT * FROM products WHERE name LIKE ? $categoryFilter ORDER BY price ASC";
        $stmt = $this->conn->prepare($sql);

        if (!empty($category)) {
            $stmt->bind_param("ss", $search, $category);
        } else {
            $stmt->bind_param("s", $search);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function searchAndSortByPriceDesc($search, $category = '')
    {
        $search = "%" . $search . "%";
        $categoryFilter = !empty($category) ? "AND category = ?" : "";
        $sql = "SELECT * FROM products WHERE name LIKE ? $categoryFilter ORDER BY price DESC";
        $stmt = $this->conn->prepare($sql);

        if (!empty($category)) {
            $stmt->bind_param("ss", $search, $category);
        } else {
            $stmt->bind_param("s", $search);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getExclusiveProduct()
    {
        $sql = "SELECT * FROM products WHERE type = 1 ORDER BY RAND() LIMIT 1";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    public function addToWishlist($userId, $productId) {
        $stmt = $this->conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $stmt->close();
    }
    
    public function removeFromWishlist($userId, $productId) {
        $stmt = $this->conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $stmt->close();
    }
    

    // Proveri da li je proizvod u wishlist-u
    public function isInWishlist($userId, $productId) {
        $stmt = $this->conn->prepare("SELECT 1 FROM wishlist WHERE user_id = ? AND product_id = ? LIMIT 1");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $stmt->store_result();
        
        // Proveri broj redova pre zatvaranja
        $inWishlist = $stmt->num_rows > 0; // Vraća true ako je proizvod u wishlist-u
        
        $stmt->close(); // Zatvori statement nakon što proveriš broj redova
        
        return $inWishlist;
    }
    
    public function getWishlist($userId) {
        $query = "SELECT p.product_id, p.name, p.price, p.image
                  FROM wishlist w
                  JOIN products p ON w.product_id = p.product_id
                  WHERE w.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $wishlistItems = [];
        while ($row = $result->fetch_assoc()) {
            $wishlistItems[] = $row;
        }

        return $wishlistItems; // Return the wishlist items
    }
    
    
    
    
}
