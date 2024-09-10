<?php

class Product{
    protected $conn;

    public function __construct(){
        global $conn;
        $this->conn=$conn;
    }

    public function izlistajSve(){
        $sql="SELECT * FROM products";
        $result=$this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function citajJedan($product_id){
        $stmt=$this->conn->prepare("SELECT * FROM products WHERE product_id=?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result=$stmt->get_result();
        return $result->fetch_assoc();
    }

    public function add($name, $price, $size, $image) {
        $sql = "INSERT INTO products (name, price, size, image) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $price, $size, $image);
        $stmt->execute();
    }
    

    public function update($product_id, $name, $price, $size, $image) {
        $sql = "UPDATE products SET name = ?, price = ?, size = ?, image = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $price, $size, $image, $product_id);
        return $stmt->execute();
    }
    

    public function delete($product_id) {
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
         
    }
}