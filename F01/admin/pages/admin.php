<?php
session_start();
require_once '../assets/php/connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../index.php');
    exit();
}

$query = "SELECT * FROM products";
$result = executeQuery($query);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

$feedback_query = "SELECT *, DATE_FORMAT(created_at, '%Y-%m-%d') as date FROM feedback ORDER BY created_at DESC";
$feedback_result = executeQuery($feedback_query);
$feedbacks = [];
while ($row = mysqli_fetch_assoc($feedback_result)) {
    $feedbacks[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kopikolympics - Admin Dashboard</title>
    <link rel="icon" href="../assets/images/favicon.webp" type="img/icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Tourney:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../assets/styles/admin.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <div class="d-flex">
                <img src="../assets/images/logo-admin.webp" width="200">
                <h3 class="poppins-bold fs-5 mx-1" style="color: #ede9e6;">admin</h3>
            </div>
            <li class="nav-item">
                <button class="btn poppins-regular logout-btn" onclick="logout()">Logout</button>
            </li>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 df poppins-medium">Products Management</h5>
                        <button class="btn btn-primary poppins-light" data-bs-toggle="modal"
                            data-bs-target="#addProductModal">
                            Add New Product
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="productsTable">
                                <thead>
                                    <tr>
                                        <th class="df poppins-bold">Name</th>
                                        <th class="df poppins-bold">Description</th>
                                        <th class="df poppins-bold">Price</th>
                                        <th class="df poppins-bold">Category</th>
                                        <th class="df poppins-bold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 df poppins-medium">Customer Feedback</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="feedbackTable">
                                <thead>
                                    <tr>
                                        <th class="df poppins-bold">Date</th>
                                        <th class="df poppins-bold">Name</th>
                                        <th class="df poppins-bold">Email</th>
                                        <th class="df poppins-bold">Message</th>
                                    </tr>
                                </thead>
                                <tbody id="feedbackTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title df poppins-bold" id="modalTitle">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" id="productId">
                        <div class="mb-3">
                            <label for="productName" class="form-label df poppins-medium">Product Name</label>
                            <input type="text" class="form-control poppins-regular" id="productName" required>
                        </div>
                        <div class="mb-3">
                            <label for="productDescription" class="form-label df poppins-medium">Description</label>
                            <textarea class="form-control poppins-regular" id="productDescription" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label df poppins-medium">Price</label>
                            <input type="number" class="form-control poppins-regular" id="productPrice" step="0.01"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label df poppins-medium">Category</label>
                            <select class="form-control poppins-regular" id="productCategory" required>
                                <option value="beverages">Beverages</option>
                                <option value="food">Food</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary poppins-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary poppins-light" id="saveProduct">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let products = [{
                id: '1',
                name: 'Champion\'s Latte',
                description: 'Premium espresso with silky steamed milk',
                price: 249,
                category: 'beverages'
            },
            {
                id: '2',
                name: 'Olympic Breakfast Bowl',
                description: 'Acai, granola, fresh fruits, and honey',
                price: 449,
                category: 'food'
            }
        ];

        let feedbacks = [{
            date: '2024-02-20',
            name: 'John Doe',
            email: 'john@example.com',
            message: 'Great service and amazing food!'
        }];

        const productsTableBody = document.getElementById('productsTableBody');
        const feedbackTableBody = document.getElementById('feedbackTableBody');
        const productForm = document.getElementById('productForm');
        const productModal = new bootstrap.Modal(document.getElementById('addProductModal'));

        function loadProducts() {
            fetch('../assets/php/get_products.php')
                .then(response => response.json())
                .then(data => {
                    const productsTableBody = document.getElementById('productsTableBody');
                    productsTableBody.innerHTML = '';
                    data.forEach(product => {
                        productsTableBody.innerHTML += `
                    <tr>
                        <td class="poppins-regular">${product.name}</td>
                        <td class="poppins-regular">${product.description}</td>
                        <td class="poppins-regular">₱${parseFloat(product.price).toFixed(2)}</td>
                        <td class="poppins-regular">${product.category}</td>
                        <td>
                            <button class="btn btn-sm btn-warning me-1 poppins-light" onclick="editProduct(${product.product_id})">Edit</button>
                            <button class="btn btn-sm btn-danger poppins-light" onclick="deleteProduct(${product.product_id})">Delete</button>
                        </td>
                    </tr>
                `;
                    });
                });
        }

        function addProduct() {
            const formData = new FormData();
            formData.append('name', document.getElementById('productName').value);
            formData.append('description', document.getElementById('productDescription').value);
            formData.append('price', document.getElementById('productPrice').value);
            formData.append('category', document.getElementById('productCategory').value);

            fetch('../assets/php/add_product.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        loadProducts();
                        productModal.hide();
                        productForm.reset();
                        showToast('Product added successfully!');
                    }
                });
        }

        function editProduct(id) {
            fetch(`../assets/php/get_product.php?id=${id}`)
                .then(response => response.json())
                .then(product => {
                    if (product.status === 'error') {
                        showToast(product.message);
                        return;
                    }

                    document.getElementById('productId').value = product.product_id;
                    document.getElementById('productName').value = product.name;
                    document.getElementById('productDescription').value = product.description;
                    document.getElementById('productPrice').value = product.price;
                    document.getElementById('productCategory').value = product.category;

                    const modal = new bootstrap.Modal(document.getElementById('addProductModal'));
                    document.getElementById('modalTitle').textContent = 'Edit Product';
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error loading product details');
                });
        }

        function updateProduct() {
            const formData = new FormData();
            formData.append('product_id', document.getElementById('productId').value);
            formData.append('name', document.getElementById('productName').value);
            formData.append('description', document.getElementById('productDescription').value);
            formData.append('price', document.getElementById('productPrice').value);
            formData.append('category', document.getElementById('productCategory').value);

            fetch('../assets/php/update_product.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        loadProducts();
                        productModal.hide();
                        productForm.reset();
                        showToast('Product updated successfully!');
                    }
                });
        }

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                fetch('../assets/php/delete_product.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `product_id=${id}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            loadProducts();
                            showToast('Product deleted successfully!');
                        }
                    });
            }
        }

        document.getElementById('saveProduct').addEventListener('click', () => {
            const productId = document.getElementById('productId').value;
            if (productId) {
                updateProduct();
            } else {
                addProduct();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            loadProducts();
        });

        function loadFeedback() {
            fetch('../assets/php/get_feedback.php')
                .then(response => response.json())
                .then(data => {
                    const feedbackTableBody = document.getElementById('feedbackTableBody');
                    feedbackTableBody.innerHTML = '';
                    data.forEach(feedback => {
                        feedbackTableBody.innerHTML += `
                    <tr>
                        <td class="df poppins-regular">${feedback.date || new Date(feedback.created_at).toLocaleDateString()}</td>
                        <td class="df poppins-regular">${feedback.user_name}</td>
                        <td class="df poppins-regular">${feedback.email}</td>
                        <td class="df poppins-regular">${feedback.message}</td>
                    </tr>
                `;
                    });
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
            const toastContainer = document.createElement('div');
            toastContainer.style.position = 'fixed';
            toastContainer.style.top = '20px';
            toastContainer.style.right = '20px';
            toastContainer.style.zIndex = '1050';

            const toastElement = document.createElement('div');
            toastElement.className = 'toast show';
            toastElement.setAttribute('role', 'alert');
            toastElement.innerHTML = `
        <div class="toast-header">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;

            toastContainer.appendChild(toastElement);
            document.body.appendChild(toastContainer);

            setTimeout(() => {
                toastContainer.remove();
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadProducts();
            loadFeedback();

            document.getElementById('addProductModal').addEventListener('hidden.bs.modal', () => {
                productForm.reset();
                document.getElementById('productId').value = '';
                document.getElementById('modalTitle').textContent = 'Add New Product';
            });
        });
    </script>
</body>

</html>