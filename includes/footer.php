<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>About Shelluxe</h5>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="about.php" class="text-light">About Us</a></li>
                    <li><a href="contact.php" class="text-light">Contact</a></li>
                    <li><a href="shipping.php" class="text-light">Shipping Policy</a></li>
                    <li><a href="returns.php" class="text-light">Returns & Refunds</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h5>Shop</h5>
                <ul class="list-unstyled">
                    <li><a href="category.php?type=new" class="text-light">New Arrivals</a></li>
                    <li><a href="category.php?type=best" class="text-light">Best Sellers</a></li>
                    <li><a href="category.php?type=sale" class="text-light">On Sale</a></li>
                    <li><a href="gift-cards.php" class="text-light">Gift Cards</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h5>Customer Service</h5>
                <ul class="list-unstyled">
                    <li><a href="contact.php" class="text-light">Contact Us</a></li>
                    <li><a href="shipping.php" class="text-light">Shipping Info</a></li>
                    <li><a href="returns.php" class="text-light">Returns</a></li>
                    <li><a href="faq.php" class="text-light">FAQ</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Newsletter</h5>
                <p>Subscribe to receive updates, access to exclusive deals, and more.</p>
                <form class="newsletter-form" action="subscribe.php" method="POST">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Enter your email" required>
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                    </div>
                </form>
                <div class="payment-methods mt-3">
                    <img src="assets/images/payment-methods.png" alt="Payment Methods" class="img-fluid" style="max-height: 30px;">
                </div>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> Shelluxe. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="privacy.php" class="text-light">Privacy Policy</a></li>
                    <li class="list-inline-item"><a href="terms.php" class="text-light">Terms of Service</a></li>
                    <li class="list-inline-item"><a href="sitemap.php" class="text-light">Sitemap</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4" style="display: none;">
    <i class="fas fa-arrow-up"></i>
</button>

<script>
    // Back to Top Button
    const backToTopButton = document.getElementById('backToTop');
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 100) {
            backToTopButton.style.display = 'block';
        } else {
            backToTopButton.style.display = 'none';
        }
    });
    
    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Newsletter Form Submission
    document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        
        fetch('api/newsletter/subscribe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Thank you for subscribing!', 'success');
                this.reset();
            } else {
                showToast(data.message || 'Error subscribing to newsletter', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error subscribing to newsletter', 'error');
        });
    });
</script> 