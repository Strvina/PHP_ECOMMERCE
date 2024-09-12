document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');

    function fetchResults() {
        const query = searchInput.value;
        const category = categoryFilter.value;
        const price = priceFilter.value;

        fetch(`search.php?search=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}&price=${encodeURIComponent(price)}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('search-results').innerHTML = data;
            })
            .catch(error => {
                document.getElementById('search-results').innerHTML = '<div class="alert alert-danger">An error occurred.</div>';
                console.error('Fetch error:', error);
            });
    }

    searchInput.addEventListener('keyup', fetchResults);
    categoryFilter.addEventListener('change', fetchResults);
    priceFilter.addEventListener('change', fetchResults);

    // Initial load
    fetchResults();
});
