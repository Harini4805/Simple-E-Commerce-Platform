<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .payment-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,.125);
        }
        .payment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .payment-section {
            display: none;
        }
        .payment-section.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>' . $_SESSION['error'] . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>' . $_SESSION['success'] . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                    unset($_SESSION['success']);
                }
                ?>
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Select Payment Method</h2>
                        
                        <!-- Payment Method Selector -->
                        <div class="mb-4">
                            <select class="form-select" id="paymentMethod">
                                <option value="">Choose Payment Method</option>
                                <option value="upi">UPI Payment</option>
                                <option value="card">Debit/Credit Card</option>
                                <option value="netbanking">Net Banking</option>
                                <option value="cod">Cash on Delivery</option>
                            </select>
                        </div>

                        <!-- UPI Payment Section -->
                        <div id="upiSection" class="payment-section">
                            <div class="card payment-card">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-qrcode me-2"></i>UPI Payment</h5>
                                    <form action="process_upi.php" method="POST">
                                        <div class="text-center mb-4">
                                            <div class="mb-2">
                                                <i class="fas fa-barcode fa-2x text-primary mb-2"></i>
                                                <div class="fw-bold">Scan the QR code below with your UPI app to pay</div>
                                            </div>
                                            <img src="../images/upi_qr_code.png" alt="UPI QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                                            <p class="text-muted mb-1">Scan this QR code to pay</p>
                                            <div class="alert alert-info py-2 mb-2" style="display:inline-block;">
                                                <strong>Amount: ₹<?php echo $order_data['amount_due']; ?></strong>
                                            </div>
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

                        <!-- Card Payment Section -->
                        <div id="cardSection" class="payment-section">
                            <div class="card payment-card">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-credit-card me-2"></i>Card Payment</h5>
                                    <form action="process_card.php" method="POST">
                                        <div class="mb-3">
                                            <label for="cardName" class="form-label">Cardholder Name</label>
                                            <input type="text" class="form-control" id="cardName" name="card_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cardNumber" class="form-label">Card Number</label>
                                            <input type="text" class="form-control" id="cardNumber" name="card_number" 
                                                   pattern="\d{16}" maxlength="16" required>
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
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-lock me-2"></i>Pay Securely
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Net Banking Section -->
                        <div id="netbankingSection" class="payment-section">
                            <div class="card payment-card">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-university me-2"></i>Net Banking</h5>
                                    <form action="process_netbanking.php" method="POST">
                                        <div class="mb-3">
                                            <label for="bankSelect" class="form-label">Select Bank</label>
                                            <select class="form-select" id="bankSelect" name="bank" required>
                                                <option value="">Choose your bank</option>
                                                <option value="sbi">State Bank of India</option>
                                                <option value="hdfc">HDFC Bank</option>
                                                <option value="icici">ICICI Bank</option>
                                                <option value="axis">Axis Bank</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="accountName" class="form-label">Account Holder Name</label>
                                            <input type="text" class="form-control" id="accountName" name="account_name" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-sign-in-alt me-2"></i>Proceed to Bank
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Cash on Delivery Section -->
                        <div id="codSection" class="payment-section">
                            <div class="card payment-card">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery</h5>
                                    <p class="mb-4">Pay when your order is delivered</p>
                                    <form action="process_cod.php" method="POST">
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

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('paymentMethod').addEventListener('change', function() {
            // Hide all sections
            document.querySelectorAll('.payment-section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Show selected section
            const selectedMethod = this.value;
            if (selectedMethod) {
                document.getElementById(selectedMethod + 'Section').classList.add('active');
            }
        });

        // Format card number with spaces
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            e.target.value = value;
        });

        // Format expiry date
        document.getElementById('expiryDate').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            if (value.length >= 2) {
                value = value.slice(0,2) + '/' + value.slice(2);
            }
            e.target.value = value;
        });
    </script>
</body>
</html> 