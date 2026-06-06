<?php
// ============================================
// Faraj Fruit Supplier and Vendor
// Week 4 – Task 2: Contact Form
// HTML Forms + PHP POST Integration
// ============================================

require_once 'config/db.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive POST data
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Server-side validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $query = "INSERT INTO messages (name, email, subject, message)
                  VALUES ('$name', '$email', '$subject', '$message')";
        if (mysqli_query($conn, $query)) {
            $success = 'Your message has been sent successfully! We will contact you shortly.';
        } else {
            $error = 'Failed to send message. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Contact – Faraj</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/faraj/assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div style="background:#1a5c2e;color:white;padding:50px 40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:36px;margin-bottom:10px;">Contact Us</h1>
  <p style="opacity:0.85;">Send us a message – Week 4 Task 2: Contact Form</p>
</div>

<div class="section" style="max-width:600px;">

  <?php if ($success): ?>
    <div style="background:#e8f5ec;border-left:5px solid #2e8b4a;padding:16px 20px;border-radius:8px;margin-bottom:20px;color:#1a5c2e;font-weight:600;">
      ✅ <?= $success ?>
    </div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div style="background:#fdecea;border-left:5px solid #cc0000;padding:16px 20px;border-radius:8px;margin-bottom:20px;color:#cc0000;">
      ❌ <?= $error ?>
    </div>
  <?php endif; ?>

  <div style="background:white;border-radius:14px;padding:32px;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid #e0e0e0;">
    <h3 style="color:#1a5c2e;margin-bottom:20px;">Send a Message</h3>

    <!-- form method POST -->
    <form method="POST">
      <div class="form-group">
        <label>Full Name <span style="color:red;">*</span></label>
        <input type="text" name="name" placeholder="John Doe" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Email Address <span style="color:red;">*</span></label>
        <input type="email" name="email" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Subject</label>
        <select name="subject">
          <option value="General Inquiry">General Inquiry</option>
          <option value="Wholesale Order">Wholesale Order</option>
          <option value="Delivery Issue">Delivery Issue</option>
          <option value="Product Feedback">Product Feedback</option>
        </select>
      </div>
      <div class="form-group">
        <label>Message <span style="color:red;">*</span></label>
        <textarea name="message" rows="5" placeholder="Write your message here..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;">Send Message</button>
    </form>
  </div>

</div>

<?php include 'includes/footer.php'; ?>
