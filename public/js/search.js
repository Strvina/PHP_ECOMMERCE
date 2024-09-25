document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');
    const searchResults = document.getElementById('search-results');

    function fetchResults() {
        const query = searchInput.value;
        const category = categoryFilter.value;
        const price = priceFilter.value;

        const url = new URL('search.php', window.location.href);

        if (query) url.searchParams.append('search', query);
        if (category) url.searchParams.append('category', category);
        if (price) url.searchParams.append('price', price);

        fetch(url)
            .then(response => response.text())
            .then(data => {
                searchResults.innerHTML = data;
            })
            .catch(error => {
                searchResults.innerHTML = '<div class="alert alert-danger">An error occurred.</div>';
                console.error('Fetch error:', error);
            });
    }

    searchInput.addEventListener('keyup', fetchResults);
    categoryFilter.addEventListener('change', fetchResults);
    priceFilter.addEventListener('change', fetchResults);

    fetchResults();
});
