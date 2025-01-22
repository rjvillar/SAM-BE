<?php
session_start();
require_once '../assets/php/connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$query = "SELECT * FROM products";
$result = executeQuery($query);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

$cart_query = "SELECT c.*, p.name, p.price 
               FROM cart c 
               JOIN products p ON c.product_id = p.product_id 
               WHERE c.user_id = " . $_SESSION['user_id'];
$cart_result = executeQuery($cart_query);
$cart_items = [];
while ($row = mysqli_fetch_assoc($cart_result)) {
    $cart_items[] = $row;
}

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

if (isset($_POST['submit_feedback'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $query = "INSERT INTO feedback (user_name, email, message) VALUES ('$name', '$email', '$message')";
    if (executeQuery($query)) {
        $feedback_success = "Thank you for your feedback!";
    } else {
        $feedback_error = "Error submitting feedback.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>kopikolympics</title>
    <link rel="icon" href="../assets/images/favicon.webp" type="img/icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Tourney:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/css-doodle@0.14.0/css-doodle.min.js"></script>
    <link href="../assets/styles/user.css" rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../assets/images/logo.webp" width="200">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link poppins-regular" href="#menu">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link poppins-regular" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link poppins-regular" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-link" onclick="openCart()">
                            <i class="bi bi-cart df"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="btn poppins-regular logout-btn" onclick="logout()">Logout</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section vh-100 d-flex align-items-center">
        <css-doodle seed="2020">
            :doodle {
            @grid: 20x20;
            position: absolute;
            width: 100px;
            height: 100px;
            top: @rand(0, 100)%;
            left: @rand(0, 100)%;
            }
            background: @pick(
            url('../assets/images/doodle1.webp'),
            url('../assets/images/doodle2.webp'),
            url('../assets/images/doodle3.webp'),
            url('../assets/images/doodle4.webp'),
            url('../assets/images/doodle5.webp'),
            url('../assets/images/doodle6.webp'),
            url('../assets/images/doodle7.webp'),
            url('../assets/images/doodle8.webp')
            );
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.5;
            width: @rand(40px, 150px);
            height: @rand(40px, 150px);
            transform: rotate(@rand(0, 360)deg);
        </css-doodle>
        <div class="container text-center text-white hero-content">
            <h1 class="display-1 mb-2 animate__animated animate__fadeIn tourney-bold df">
                WHERE COFFEE MEETS SPORTS
            </h1>
            <p class="fs-4 lead mb-4 animate__animated animate__fadeIn poppins-medium df">
                Crafted for champions, brewed for greatness.
            </p>
            <button class="btn btn-primary poppins-medium" onclick="scrollToMenu()">
                View Menu
            </button>
        </div>
    </section>

    <section id="menu" class="py-5 min-vh-100" style="background-color: #ffffff;">
        <div class="container">
            <h2 class="text-center mb-5 df poppins-bold">Our Menu</h2>
            <div class="text-center mb-4">
                <button onclick="filterProducts('all')" class="btn me-2 df poppins-regular <?php echo (!isset($_GET['category']) || $_GET['category'] === 'all') ? 'btn-primary' : 'btn-outline-primary'; ?>">All</button>
                <button onclick="filterProducts('beverages')" class="btn me-2 df poppins-regular <?php echo (isset($_GET['category']) && $_GET['category'] === 'beverages') ? 'btn-primary' : 'btn-outline-primary'; ?>">Beverages</button>
                <button onclick="filterProducts('food')" class="btn df poppins-regular <?php echo (isset($_GET['category']) && $_GET['category'] === 'food') ? 'btn-primary' : 'btn-outline-primary'; ?>">Food</button>
            </div>
            <div class="row g-4" id="menuItems">
                <?php
                $category = $_GET['category'] ?? 'all';
                foreach ($products as $product):
                    if ($category === 'all' || $category === $product['category']):
                ?>
                        <div class="col-md-4 col-lg-3" data-category="<?php echo $product['category']; ?>">
                            <div class="card menu-item h-100 border-0" style="background: linear-gradient(135deg, rgba(0, 133, 199, 0.1) 0%, rgba(0, 159, 61, 0.1) 100%); border-radius:15px;">
                                <?php if ($product['image']): ?>
                                    <img src="../assets/images/products/<?php echo $product['image']; ?>"
                                        class="card-img-top"
                                        alt="<?php echo $product['name']; ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title df poppins-medium"><?php echo $product['name']; ?></h5>
                                    <p class="card-text text-muted df poppins-regular"><?php echo $product['description']; ?></p>
                                    <p class="card-text df poppins-bold">₱<?php echo number_format($product['price'], 2); ?></p>
                                    <form class="add-to-cart-form" onsubmit="addToCart(event, <?php echo $product['product_id']; ?>)">
                                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                        <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                <?php
                    endif;
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <section class="about-section min-vh-100 d-flex align-items-center py-5" id="about">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="text-center mb-5">
                        <h1 class="display-4 fw-bold mb-1 df poppins-bold">About</h1>
                        <img src="../assets/images/logo.webp" width="153">
                        <div class="divider-custom"></div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card menu-item h-100 border-0" style="background: linear-gradient(135deg, rgba(0, 133, 199, 0.1) 0%, rgba(0, 159, 61, 0.1) 100%); border-radius:15px;">
                                <div class="card-body">
                                    <h2 class="mb-4 df poppins-medium">Our Story</h2>
                                    <p class="lead df poppins-light">
                                        Founded in 2025, KOPIKOlympics brings the spirit of
                                        athletic excellence to your dining experience. Our cafe
                                        celebrates the values of determination, health, and
                                        community that the Olympic Games represent.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card menu-item h-100 border-0" style="background: linear-gradient(135deg, rgba(0, 133, 199, 0.1) 0%, rgba(0, 159, 61, 0.1) 100%); border-radius:15px;">
                                <div class="card-body">
                                    <h2 class="mb-4 df poppins-medium">Our Mission</h2>
                                    <p class="lead poppins-light">
                                        We strive to provide healthy, delicious food and beverages
                                        that fuel your active lifestyle. Every dish is crafted
                                        with the same dedication that athletes bring to their
                                        training.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-4">
                        <div class="col-md-3 col-6">
                            <div class="card h-100 bg-light border-0" style="background: linear-gradient(135deg, rgba(0, 133, 199, 0.1) 0%, rgba(0, 159, 61, 0.1) 100%); border-radius:15px;">
                                <div class="card-body text-center">
                                    <h3 class="h4 mb-3 df poppins-medium">Quality</h3>
                                    <p class="mb-0 df poppins-regular">Premium ingredients in every dish</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card h-100 bg-light border-0" style="background: linear-gradient(135deg, rgba(0, 133, 199, 0.1) 0%, rgba(0, 159, 61, 0.1) 100%); border-radius:15px;">
                                <div class="card-body text-center">
                                    <h3 class="h4 mb-3 df poppins-medium">Health</h3>
                                    <p class="mb-0 df poppins-regular">Nutritious and balanced meals</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card h-100 bg-light border-0" style="background: linear-gradient(135deg, rgba(0, 133, 199, 0.1) 0%, rgba(0, 159, 61, 0.1) 100%); border-radius:15px;">
                                <div class="card-body text-center">
                                    <h3 class="h4 mb-3 df poppins-medium">Community</h3>
                                    <p class="mb-0 df poppins-regular">A gathering place for all</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card h-100 bg-light border-0" style="background: linear-gradient(135deg, rgba(0, 133, 199, 0.1) 0%, rgba(0, 159, 61, 0.1) 100%); border-radius:15px;">
                                <div class="card-body text-center">
                                    <h3 class="h4 mb-3 df poppins-medium">Spirit</h3>
                                    <p class="mb-0 df poppins-regular">Olympic values in every serve</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-section min-vh-100 d-flex align-items-center py-5" id="contact">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="text-center mb-5">
                        <h1 class="display-4 fw-bold mb-4 df poppins-bold">Contact Us</h1>
                        <div class="divider-custom"></div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, rgba(0, 133, 199, 0.05) 0%, rgba(0, 159, 61, 0.05) 100%);">
                                <div class="card-body">
                                    <h2 class="mb-4 df poppins-medium">Send us a Message</h2>
                                    <form method="POST" onsubmit="submitFeedback(event)" class="needs-validation">
                                        <div class="mb-3">
                                            <label for="name" class="form-label df poppins-light">Name</label>
                                            <input type="text" class="form-control poppins-regular" name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label df poppins-light">Email</label>
                                            <input type="email" class="form-control poppins-regular" name="email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="message" class="form-label df poppins-light">Message</label>
                                            <textarea class="form-control poppins-regular" name="message" rows="5" required></textarea>
                                        </div>
                                        <button type="submit" name="submit_feedback" class="btn btn-primary w-100">Send Message</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, rgba(0, 133, 199, 0.05) 0%, rgba(0, 159, 61, 0.05) 100%);">
                                <div class="card-body">
                                    <h2 class="mb-4 df poppins-medium">Visit Us</h2>
                                    <div class="mb-4">
                                        <h3 class="h5 mb-3 df poppins-regular">Address</h3>
                                        <p class="poppins-light df">PUP Sto. Tomas Campus
                                            <br>A. Bonifacio St. Poblacion II,
                                            <br>Sto. Tomas Batangas, Philippines
                                        </p>
                                    </div>
                                    <div class="mb-4">
                                        <h3 class="h5 mb-3 df poppins-regular">Hours</h3>
                                        <p class="poppins-light df">
                                            Monday - Friday: 7:00 AM - 8:00 PM<br />
                                            Saturday - Sunday: 8:00 AM - 6:00 PM
                                        </p>
                                    </div>
                                    <div>
                                        <h3 class="h5 mb-3 df poppins-regular">Contact</h3>
                                        <p class="poppins-light df">
                                            Phone: +63 918 564 ****<br />
                                            Email: info@kopikolympics.com
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border: 3px solid #0085C7;">
                <div class="modal-header">
                    <h5 class="modal-title poppins-bold">Your Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="cartItems">
                        <?php if (empty($cart_items)): ?>
                            <p class="text-center poppins-regular">Your cart is empty</p>
                        <?php else: ?>
                            <?php foreach ($cart_items as $item): ?>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0 poppins-medium"><?php echo $item['name']; ?></h6>
                                        <div class="quantity-control mt-1">
                                            <button type="button" onclick="return updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity']; ?> - 1)" class="btn btn-sm btn-outline-secondary">-</button>
                                            <span class="mx-2 poppins-regular"><?php echo $item['quantity']; ?></span>
                                            <button type="button" onclick="return updateQuantity(<?php echo $item['product_id']; ?>, <?php echo $item['quantity']; ?> + 1)" class="btn btn-sm btn-outline-secondary">+</button>
                                        </div>
                                    </div>
                                    <div class="text-end d-flex align-items-center">
                                        <div class="poppins-medium me-3">₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                                        <button onclick="removeFromCart(<?php echo $item['product_id']; ?>)" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="border-top pt-3 mt-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="poppins-medium df">Total:</span>
                                    <span class="poppins-medium df" id="cartTotal">
                                        ₱<?php echo number_format($total, 2); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (!empty($cart_items)): ?>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary border-0" data-bs-dismiss="modal" style="background-color: grey;">Continue Shopping</button>
                        <button type="button" class="btn btn-primary" style="background-color: #009F3D; color: white; border: none;">Checkout</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addToCart(event, productId) {
            event.preventDefault();

            fetch('../assets/php/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}`
                })
                .then(response => response.text())
                .then(data => {
                    showToast('Item added to cart');
                    updateCartItems();
                })
                .catch(error => {
                    showToast('Error adding item to cart');
                });
        }

        function updateCartItems() {
            fetch('../assets/php/get_cart.php')
                .then(response => response.json())
                .then(data => {
                    const cartItems = document.getElementById('cartItems');
                    if (data.items.length === 0) {
                        cartItems.innerHTML = '<p class="text-center poppins-regular">Your cart is empty</p>';
                        document.querySelector('.modal-footer').style.display = 'none';
                    } else {
                        let html = '';
                        data.items.forEach(item => {
                            html += `
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h6 class="mb-0 poppins-medium">${item.name}</h6>
        <div class="quantity-control mt-1">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="event.preventDefault(); updateQuantity(${item.product_id}, parseInt(${item.quantity}) - 1);">-</button>
            <span class="mx-2 poppins-regular">${item.quantity}</span>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="event.preventDefault(); updateQuantity(${item.product_id}, parseInt(${item.quantity}) + 1);">+</button>
        </div>
    </div>
    <div class="text-end d-flex align-items-center">
        <div class="poppins-medium me-3">₱${(item.price * item.quantity).toFixed(2)}</div>
        <button onclick="removeFromCart(${item.product_id})" class="btn btn-sm btn-danger">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>`;
                        });
                        html += `
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="poppins-medium df">Total:</span>
                            <span class="poppins-medium df" id="cartTotal">₱${data.total.toFixed(2)}</span>
                        </div>
                    </div>
                `;
                        cartItems.innerHTML = html;
                        document.querySelector('.modal-footer').style.display = 'flex';
                    }
                });
        }

        function removeFromCart(productId) {
            event.preventDefault();
            event.stopPropagation();

            fetch('../assets/php/remove_from_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        updateCartItems();
                        showToast('Item removed from cart');
                    } else {
                        showToast('Error removing item');
                    }
                })
                .catch(error => {
                    showToast('Error removing item');
                });
        }

        function openCart() {
            const cartModal = new bootstrap.Modal(document.getElementById("cartModal"));
            cartModal.show();
        }

        function updateQuantity(productId, newQuantity) {
            event.preventDefault();
            event.stopPropagation();

            newQuantity = Number(newQuantity);

            if (newQuantity < 1) {
                removeFromCart(productId);
                return false;
            }

            fetch('../assets/php/update_quantity.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=${newQuantity}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        updateCartItems();
                    } else {
                        showToast('Error updating quantity');
                    }
                })
                .catch(error => {
                    showToast('Error updating quantity');
                });

            return false;
        }

        function filterProducts(category) {
            event.preventDefault();

            const url = new URL(window.location);
            url.searchParams.set('category', category);
            window.history.pushState({}, '', url);

            document.querySelectorAll('.text-center.mb-4 .btn').forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            event.target.classList.remove('btn-outline-primary');
            event.target.classList.add('btn-primary');

            const menuItems = document.querySelectorAll('#menuItems .col-md-6');
            menuItems.forEach(item => {
                const productCategory = item.getAttribute('data-category');
                if (category === 'all' || productCategory === category) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function submitFeedback(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            fetch('../assets/php/submit_feedback.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast('Thank you for your feedback!');
                        form.reset();
                    } else {
                        showToast('Error submitting feedback');
                    }
                })
                .catch(error => {
                    showToast('Error submitting feedback');
                });
        }

        function scrollToMenu() {
            document.getElementById("menu").scrollIntoView({
                behavior: "smooth"
            });
        }

        function logout() {
            fetch('../assets/php/logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = '../index.php';
                    }
                });
        }

        function showToast(message) {
            const existingContainer = document.querySelector('.toast-container');
            if (existingContainer) {
                existingContainer.remove();
            }

            const toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container';

            const toast = document.createElement('div');
            toast.className = 'custom-toast';
            toast.textContent = message;

            toastContainer.appendChild(toast);
            document.body.appendChild(toastContainer);

            setTimeout(() => {
                toastContainer.remove();
            }, 3000);
        }
    </script>
</body>

</html>