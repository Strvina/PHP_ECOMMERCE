
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const currentAction = this.getAttribute('data-action');
            const icon = this.querySelector('i');

            // Send AJAX request to update the wishlist
            fetch('wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    action: currentAction
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Change icon state based on the action
                    if (currentAction === 'add') {
                        icon.classList.remove('fa-star-o');
                        icon.classList.add('fa-star');
                        button.setAttribute('data-action', 'remove');
                    } else {
                        icon.classList.remove('fa-star');
                        icon.classList.add('fa-star-o');
                        button.setAttribute('data-action', 'add');
                    }
                } else {
                    alert('Failed to update wishlist.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });

