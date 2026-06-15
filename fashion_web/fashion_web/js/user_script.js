document.addEventListener("DOMContentLoaded", function () {
    // User profile dropdown toggle
    const userBtn = document.querySelector('#user-btn');
    const profile = document.querySelector('.profile');
    const searchForm = document.querySelector('.header .flex .search-form');

    if (userBtn) {
        userBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (profile) profile.classList.toggle('active');
            if (searchForm) searchForm.classList.remove('active');
        });
    }

    // Search toggle
    const searchBtn = document.querySelector('#search-btn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (searchForm) searchForm.classList.toggle('active');
            if (profile) profile.classList.remove('active');
        });
    }

    // Mobile menu toggle
    const menuBtn = document.querySelector('#menu-btn');
    if (menuBtn) {
        menuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const navbar = document.querySelector('.navbar');
            if (navbar) navbar.classList.toggle('active');
        });
    }

    // Close all dropdowns when clicking outside header
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.header')) {
            if (profile) profile.classList.remove('active');
            if (searchForm) searchForm.classList.remove('active');
        }
    });

    // Handle Add to Cart and Add to Wishlist via AJAX
    document.querySelectorAll('form.box').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitter = e.submitter;
            if (submitter && (submitter.name === 'add_to_cart' || submitter.name === 'add_to_wishlist')) {
                e.preventDefault();
                const formData = new FormData(form);
                
                if (submitter.name === 'add_to_cart') {
                    formData.append('ajax_add_cart', '1');
                } else {
                    formData.append('ajax_add_wishlist', '1');
                }
                
                // Ensure product_id is sent if it wasn't picked up automatically
                if (!formData.has('product_id')) {
                    formData.append('product_id', submitter.value);
                }

                // If submitting to the same page, fetch from the current URL
                // We'll post to the current URL where the PHP logic includes add_cart.php/add_wishlist.php
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(res => {
                    // It's possible the PHP file outputs HTML or warnings before JSON
                    // We must ensure the PHP script returns pure JSON and exits.
                    return res.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.status === 'success') {
                            // Update header counts
                            if (submitter.name === 'add_to_cart') {
                                const cartSup = document.querySelector('.cart-btn i.bx-cart');
                                if (cartSup && cartSup.nextElementSibling) {
                                    cartSup.nextElementSibling.textContent = data.total;
                                }
                            } else {
                                const wishSup = document.querySelector('.cart-btn i.bx-heart');
                                if (wishSup && wishSup.nextElementSibling) {
                                    wishSup.nextElementSibling.textContent = data.total;
                                }
                            }
                            swal(data.message, "", "success");
                        } else if (data.status === 'warning') {
                            swal(data.message, "", "warning");
                        } else {
                            swal(data.message, "", "error");
                        }
                    } catch (err) {
                        console.error('Failed to parse JSON:', text);
                        // Fallback to normal form submit if JSON fails
                        form.submit();
                    }
                })
                .catch(err => {
                    console.error(err);
                    swal("Something went wrong", "", "error");
                });
            }
        });
    });
});