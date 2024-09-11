<?php
require_once 'inc/header.php';
require_once 'app/classes/product.php';

$products = new Product();

if (isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-success">';
    echo 'Ajde, ' . 'kutre (' . $_SESSION['username'] . ') ulegni' . '!';
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('search').addEventListener('keyup', function() {
            var query = this.value;
            console.log('Search query:', query);
            fetch('search.php?search=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => {
                    console.log('Fetch response:', data);
                    document.getElementById('search-results').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('search-results').innerHTML = '<div class="alert alert-danger">An error occurred.</div>';
                    console.error('Fetch error:', error);
                });
        });

        // Initial load - fetch all products if no search query is present
        fetch('search.php')
            .then(response => response.text())
            .then(data => {
                console.log('Initial fetch response:', data);
                document.getElementById('search-results').innerHTML = data;
            })
            .catch(error => {
                document.getElementById('search-results').innerHTML = '<div class="alert alert-danger">An error occurred.</div>';
                console.error('Initial fetch error:', error);
            });
    });
    </script>
</head>
<body>
    <div class="container">
        <input type="text" id="search" class="form-control mt-3" placeholder="Search for products...">
        <div id="search-results" class="row mt-4">
            
        </div>
    </div>
    
    <?php require_once 'inc/footer.php'; ?>
</body>
</html>
