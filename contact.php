<?php
// Include the necessary files for database connection
include('includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Us</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      font-family: 'Roboto', sans-serif;
      padding: 40px 0;
    }

    .container {
      max-width: 800px;
    }

    .contact-form-container {
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
      from {
        transform: translateY(20px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .contact-form-container h3 {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 30px;
      color: #333;
      text-align: center;
    }

    .form-label {
      font-weight: 600;
      color: #444;
    }

    .form-control {
      border-radius: 10px;
      border: 1px solid #ccc;
      padding: 14px 16px;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #4facfe;
      box-shadow: 0 0 8px rgba(79, 172, 254, 0.5);
    }

    .btn-primary {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      border: none;
      padding: 14px;
      font-size: 18px;
      border-radius: 30px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(to right, #00f2fe, #4facfe);
      transform: scale(1.03);
    }

    .alert {
      font-size: 16px;
      margin-top: 20px;
      text-align: center;
    }

    .alert-success {
      color: green;
    }

    .alert-danger {
      color: red;
    }

    @media (max-width: 768px) {
      .contact-form-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="contact-form-container mt-5">
    <h3>Contact Us</h3>
    <form action="contact.php" method="POST">
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" id="name" placeholder="Your name" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" id="email" placeholder="you@example.com" required>
      </div>
      <div class="mb-3">
        <label for="subject" class="form-label">Subject</label>
        <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject..." required>
      </div>
      <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea name="message" class="form-control" id="message" rows="5" placeholder="Type your message here..." required></textarea>
      </div>
      <button type="submit" name="submit_contact" class="btn btn-primary w-100">Submit</button>
    </form>

    <!-- Feedback Message -->
    <?php
    if (isset($_POST['submit_contact'])) {
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $subject = mysqli_real_escape_string($con, $_POST['subject']);
        $message = mysqli_real_escape_string($con, $_POST['message']);

        $insert_contact_query = "INSERT INTO contact_messages (name, email, subject, message)
                                 VALUES ('$name', '$email', '$subject', '$message')";
        $result = mysqli_query($con, $insert_contact_query);

        if ($result) {
            echo "<div class='alert alert-success'>Your message has been sent successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>There was an error sending your message. Please try again.</div>";
        }
    }
    ?>
    <a href="index.php" class="btn btn-secondary w-100 mt-3">Back to Home Page</a>
  </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
