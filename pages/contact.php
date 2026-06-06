<?php
session_start();
require_once '../includes/header.php';
require_once '../config/db.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if (!$name || !$email || !$message) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Save to DB (messages table - created below)
        $result = mysqli_query($conn, "INSERT INTO messages (name, email, subject, message) VALUES ('$name','$email','$subject','$message')");
        if ($result) {
            $success = 'Thank you! Your message has been sent. We will get back to you shortly.';
        } else {
            $error = 'Failed to send message. Please try again.';
        }
    }
}
?>

<div style="background:var(--green-dark);color:white;padding:60px 40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:40px;margin-bottom:12px;">Contact Us</h1>
  <p style="opacity:0.85;font-size:17px;">Get in touch for wholesale inquiries, support, or feedback.</p>
</div>

<div class="section" style="max-width:900px;">

  <?php if ($success): ?>
    <div style="background:#e8f5ec;border-left:5px solid var(--green-mid);padding:16px 20px;border-radius:var(--radius);margin-bottom:24px;color:var(--green-dark);font-weight:600;">
      ✅ <?= $success ?>
    </div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div style="background:#fdecea;border-left:5px solid #cc0000;padding:16px 20px;border-radius:var(--radius);margin-bottom:24px;color:#cc0000;">
      ❌ <?= $error ?>
    </div>
  <?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1.5fr;gap:40px;align-items:start;">

    <!-- Contact Info -->
    <div>
      <h3 style="color:var(--green-dark);font-size:20px;margin-bottom:20px;">Get In Touch</h3>
      <?php foreach([
        ['📍','Address','Nairobi, Kenya'],
        ['📞','Phone','+254 700 000 000'],
        ['✉️','Email','info@farajfruits.co.ke'],
        ['🕐','Hours','Mon–Sat: 7am – 6pm'],
        ['🚚','Delivery','Nairobi & surroundings'],
      ] as $info): ?>
      <div style="display:flex;gap:14px;margin-bottom:18px;align-items:flex-start;">
        <span style="font-size:22px;"><?= $info[0] ?></span>
        <div>
          <div style="font-weight:600;font-size:14px;color:var(--green-dark);"><?= $info[1] ?></div>
          <div style="font-size:13px;color:#666;"><?= $info[2] ?></div>
        </div>
      </div>
      <?php endforeach; ?>

      <div style="background:var(--green-light);border-radius:var(--radius-lg);padding:20px;margin-top:20px;">
        <h4 style="color:var(--green-dark);margin-bottom:8px;">💼 Wholesale Inquiries?</h4>
        <p style="font-size:13px;color:#555;line-height:1.6;">For bulk orders above KES 5,000, contact us directly for special pricing and dedicated account management.</p>
      </div>
    </div>

    <!-- Contact Form -->
    <div style="background:white;border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);border:1px solid var(--border);">
      <h3 style="color:var(--green-dark);font-size:18px;margin-bottom:20px;">Send a Message</h3>
      <form method="POST">
        <div class="form-group">
          <label>Full Name <span style="color:red;">*</span></label>
          <input type="text" name="name" placeholder="John Doe" required>
        </div>
        <div class="form-group">
          <label>Email Address <span style="color:red;">*</span></label>
          <input type="email" name="email" placeholder="you@example.com" required>
        </div>
        <div class="form-group">
          <label>Subject</label>
          <select name="subject">
            <option value="General Inquiry">General Inquiry</option>
            <option value="Wholesale Order">Wholesale Order</option>
            <option value="Delivery Issue">Delivery Issue</option>
            <option value="Product Feedback">Product Feedback</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label>Message <span style="color:red;">*</span></label>
          <textarea name="message" rows="5" placeholder="Write your message here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Send Message</button>
      </form>
    </div>

  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
