// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add to Cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            addToCart(productId);
        });
    });

    // Search functionality with auto-suggestions
    const searchInput = document.querySelector('input[type="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = this.value.trim();
                if (query.length >= 2) {
                    fetchSearchSuggestions(query);
                }
            }, 300);
        });
    }

    // Wishlist functionality
    const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            toggleWishlist(productId);
        });
    });

    // Quantity increment/decrement
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        const decrementBtn = input.previousElementSibling;
        const incrementBtn = input.nextElementSibling;

        decrementBtn.addEventListener('click', () => {
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
                updateCartItem(input.dataset.cartId, input.value);
            }
        });

        incrementBtn.addEventListener('click', () => {
            input.value = parseInt(input.value) + 1;
            updateCartItem(input.dataset.cartId, input.value);
        });
    });
});

// Add to Cart function
function addToCart(productId) {
    fetch('api/cart/add.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Product added to cart successfully!', 'success');
            updateCartCount(data.cart_count);
            updateWishlistCount(data.wishlist_count);
            console.log('Item is added');
        } else {
            showToast(data.message || 'Error adding product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error adding product to cart', 'error');
    });
}

// Update Cart Item function
function updateCartItem(cartId, quantity) {
    fetch('api/cart/update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cart_id: cartId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartTotal(data.total);
        } else {
            showToast(data.message || 'Error updating cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating cart', 'error');
    });
}

// Toggle Wishlist function
function toggleWishlist(productId) {
    fetch('api/wishlist/toggle.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = document.querySelector(`[data-product-id="${productId}"]`);
            if (data.in_wishlist) {
                button.classList.add('active');
                showToast('Product added to wishlist!', 'success');
            } else {
                button.classList.remove('active');
                showToast('Product removed from wishlist', 'info');
            }
            updateWishlistCount(data.wishlist_count);
        } else {
            showToast(data.message || 'Error updating wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating wishlist', 'error');
    });
}

// Fetch Search Suggestions
function fetchSearchSuggestions(query) {
    fetch(`api/search/suggestions.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            const suggestionsContainer = document.querySelector('.search-suggestions');
            if (suggestionsContainer) {
                suggestionsContainer.innerHTML = '';
                data.forEach(suggestion => {
                    const div = document.createElement('div');
                    div.className = 'suggestion-item';
                    div.textContent = suggestion.name;
                    div.addEventListener('click', () => {
                        window.location.href = `product.php?id=${suggestion.id}`;
                    });
                    suggestionsContainer.appendChild(div);
                });
                suggestionsContainer.style.display = data.length ? 'block' : 'none';
            }
        })
        .catch(error => console.error('Error:', error));
}

// Show Toast Notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast show ${type}`;
    toast.innerHTML = `
        <div class="toast-header">
            <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Update Cart Count
function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        cartCount.style.display = count > 0 ? 'inline' : 'none';
    }
}

// Update Cart Total
function updateCartTotal(total) {
    const cartTotal = document.querySelector('.cart-total');
    if (cartTotal) {
        cartTotal.textContent = `$${total.toFixed(2)}`;
    }
}

// Image Carousel for Product Details
function initProductCarousel() {
    const carousel = document.querySelector('.product-carousel');
    if (carousel) {
        new bootstrap.Carousel(carousel, {
            interval: 5000,
            wrap: true
        });
    }
}

// Initialize product carousel if on product page
if (document.querySelector('.product-carousel')) {
    initProductCarousel();
}

function updateWishlistCount(count) {
    const wishlistCount = document.getElementById('wishlist-count');
    if (wishlistCount) {
        wishlistCount.textContent = count;
        wishlistCount.style.display = count > 0 ? 'inline' : 'none';
    }
} 