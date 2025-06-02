<?php
include('../includes/connect.php');
include('../functions/common_function.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='user_login.php';</script>";
    exit();
}

if(isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $username = $_SESSION['username'];
    
    // Get user details
    $get_user = "SELECT * FROM user_table WHERE username='$username'";
    $result = mysqli_query($con, $get_user);
    $user_data = mysqli_fetch_assoc($result);
    
    // Get order details
    $get_order = "SELECT uo.*, up.payment_mode, up.payment_date, p.product_title, p.product_image1, p.product_price
                 FROM user_orders uo 
                 JOIN orders_pending op ON uo.order_id = op.order_id 
                 JOIN products p ON op.product_id = p.product_id 
                 LEFT JOIN user_payments up ON uo.order_id = up.order_id
                 WHERE uo.order_id = $order_id AND uo.user_id = {$user_data['user_id']}";
    $result_order = mysqli_query($con, $get_order);
    
    if(mysqli_num_rows($result_order) > 0) {
        $order_data = mysqli_fetch_assoc($result_order);
    } else {
        echo "<script>alert('Invalid order!')</script>";
        echo "<script>window.location.href='profile.php?my_orders';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request!')</script>";
    echo "<script>window.location.href='profile.php?my_orders';</script>";
    exit();
}

// Calculate delivery date (3-5 business days)
$delivery_date = date('Y-m-d', strtotime('+3 weekdays'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Payment - Ecommerce Website</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .payment-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 1200px;
        }
        .payment-method-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-method-card:hover {
            border-color: #0d6efd;
            transform: translateY(-2px);
        }
        .payment-method-card.selected {
            border-color: #0d6efd;
            background-color: #f8f9ff;
        }
        .payment-section {
            display: none;
        }
        .payment-section.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .secure-badge {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .delivery-date {
            background-color: #fff3e0;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }
        .payment-status {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Payment Status Badge -->
    <div class="payment-status">
        <span class="badge bg-warning text-dark">
            <i class="fas fa-clock me-1"></i> Payment Pending
        </span>
    </div>

    <div class="container py-5">
        <div class="payment-container p-4">
            <div class="row">
                <!-- Order Summary Column -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="../admin_area/product_images/<?php echo $order_data['product_image1']; ?>" 
                                     class="product-image me-3" alt="Product Image">
                                <div>
                                    <h6 class="mb-0"><?php echo $order_data['product_title']; ?></h6>
                                    <p class="text-muted mb-0">Quantity: <?php echo $order_data['total_products']; ?></p>
            </div>
        </div>
                            
                            <hr>

                            <div class="mb-2 d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span>₹<?php echo $order_data['amount_due']; ?></span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between">
                                <span>Delivery Charges:</span>
                                <span>₹0.00</span>
                            </div>
                            <hr>
                            <div class="mb-2 d-flex justify-content-between">
                                <strong>Total Amount:</strong>
                                <strong>₹<?php echo $order_data['amount_due']; ?></strong>
                            </div>

                            <div class="delivery-date mt-3">
                                <i class="fas fa-truck me-2"></i>
                                Estimated Delivery: <?php echo date('F j, Y', strtotime($delivery_date)); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address Card -->
                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong><?php echo $user_data['username']; ?></strong></p>
                            <p class="mb-1"><?php echo $user_data['user_address']; ?></p>
                            <p class="mb-1">Phone: <?php echo $user_data['user_mobile']; ?></p>
                            <button class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addressModal">
                                <i class="fas fa-edit me-1"></i> Edit Address
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Column -->
                <div class="col-lg-8">
                    <h4 class="mb-4">Select Payment Method</h4>
                    
                    <!-- Payment Methods Selection -->
                    <div class="payment-methods mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="payment-method-card" data-method="upi">
                                    <i class="fas fa-qrcode fa-2x mb-2 text-primary"></i>
                                    <h6>UPI Payment</h6>
                                    <small class="text-muted">Pay using UPI apps</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-method-card" data-method="card">
                                    <i class="fas fa-credit-card fa-2x mb-2 text-primary"></i>
                                    <h6>Credit/Debit Card</h6>
                                    <small class="text-muted">Pay using card</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-method-card" data-method="netbanking">
                                    <i class="fas fa-university fa-2x mb-2 text-primary"></i>
                                    <h6>Net Banking</h6>
                                    <small class="text-muted">Pay using net banking</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-method-card" data-method="cod">
                                    <i class="fas fa-money-bill-wave fa-2x mb-2 text-primary"></i>
                                    <h6>Cash on Delivery</h6>
                                    <small class="text-muted">Pay when delivered</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Forms -->
                    <div class="payment-forms">
                        <!-- UPI Payment Form -->
                        <div id="upiSection" class="payment-section">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">UPI Payment</h5>
                                    <form action="process_payment.php" method="POST">
                                        <input type="hidden" name="payment_method" value="upi">
                                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                        
                                        <div class="text-center mb-4">
                                            <img src="../images/upi_qr_code.png" alt="UPI QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                                            <p class="text-muted">Scan QR code to pay</p>
                                            <p class="text-primary"><strong>Total Amount: ₹<?php echo $order_data['amount_due']; ?></strong></p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="utrNumber" class="form-label">UTR/Transaction ID</label>
                                            <input type="text" class="form-control" id="utrNumber" name="utr_number" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-check-circle me-2"></i>Verify Payment
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Card Payment Form -->
                        <div id="cardSection" class="payment-section">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Card Payment</h5>
                                    <form action="process_payment.php" method="POST">
                                        <input type="hidden" name="payment_method" value="card">
                                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                        
                                        <div class="mb-3">
                                            <label for="cardName" class="form-label">Cardholder Name</label>
                                            <input type="text" class="form-control" id="cardName" name="card_name" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="cardNumber" class="form-label">Card Number</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="cardNumber" name="card_number" 
                                                       pattern="\d{16}" maxlength="16" required>
                                                <span class="input-group-text">
                                                    <i class="fas fa-credit-card"></i>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="expiryDate" class="form-label">Expiry Date</label>
                                                <input type="text" class="form-control" id="expiryDate" name="expiry_date" 
                                                       placeholder="MM/YY" pattern="(0[1-9]|1[0-2])\/([0-9]{2})" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="cvv" class="form-label">CVV</label>
                                                <input type="password" class="form-control" id="cvv" name="cvv" 
                                                       pattern="\d{3,4}" maxlength="4" required>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="saveCard">
                                            <label class="form-check-label" for="saveCard">Save card for future payments</label>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-lock me-2"></i>Pay Securely
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Net Banking Form -->
                        <div id="netbankingSection" class="payment-section">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Net Banking</h5>
                                    <form action="process_payment.php" method="POST">
                                        <input type="hidden" name="payment_method" value="netbanking">
                                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                        
                                        <div class="mb-3">
                                            <label for="bankSelect" class="form-label">Select Bank</label>
                                            <select class="form-select" id="bankSelect" name="bank" required>
                                                <option value="">Choose your bank</option>
                                                <option value="sbi">State Bank of India</option>
                                                <option value="hdfc">HDFC Bank</option>
                                                <option value="icici">ICICI Bank</option>
                                                <option value="axis">Axis Bank</option>
                                                <option value="kotak">Kotak Mahindra Bank</option>
                                                <option value="pnb">Punjab National Bank</option>
                                                <option value="canara">Canara Bank</option>
                                                <option value="bankofbaroda">Bank of Baroda</option>
                                                <option value="idfc">IDFC FIRST Bank</option>
                                                <option value="yesbank">Yes Bank</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="accountNumber" class="form-label">Account Number</label>
                                            <input type="text" class="form-control" id="accountNumber" name="account_number" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-sign-in-alt me-2"></i>Proceed to Bank
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Cash on Delivery Form -->
                        <div id="codSection" class="payment-section">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title mb-4">Cash on Delivery</h5>
                                    <p class="mb-4">Pay when your order is delivered</p>
                                    <form action="process_payment.php" method="POST">
                                        <input type="hidden" name="payment_method" value="cod">
                                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                        
                                        <div class="mb-3">
                                            <label for="codNote" class="form-label">Delivery Instructions (Optional)</label>
                                            <textarea class="form-control" id="codNote" name="delivery_note" rows="3" 
                                                      placeholder="Add any specific delivery instructions..."></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-check me-2"></i>Confirm Order
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Edit Modal -->
    <div class="modal fade" id="addressModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Shipping Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addressForm">
                        <div class="mb-3">
                            <label for="editAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="editAddress" rows="3" required><?php echo $user_data['user_address']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="editPhone" value="<?php echo $user_data['user_mobile']; ?>" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveAddress">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Payment method selection
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                document.querySelectorAll('.payment-method-card').forEach(c => {
                    c.classList.remove('selected');
                });
                
                // Add selected class to clicked card
                this.classList.add('selected');
                
                // Hide all payment sections
                document.querySelectorAll('.payment-section').forEach(section => {
                    section.classList.remove('active');
                });
                
                // Show selected payment section
                const method = this.dataset.method;
                document.getElementById(method + 'Section').classList.add('active');
            });
        });

        // Card number formatting
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            e.target.value = value;
        });

        // Expiry date formatting
        document.getElementById('expiryDate').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            if (value.length >= 2) {
                value = value.slice(0,2) + '/' + value.slice(2);
            }
            e.target.value = value;
        });

        // Address form submission
        document.getElementById('saveAddress').addEventListener('click', function() {
            const address = document.getElementById('editAddress').value;
            const phone = document.getElementById('editPhone').value;
            
            // Here you would typically make an AJAX call to update the address
            // For now, we'll just close the modal
            bootstrap.Modal.getInstance(document.getElementById('addressModal')).hide();
        });
    </script>
</body>
</html>
