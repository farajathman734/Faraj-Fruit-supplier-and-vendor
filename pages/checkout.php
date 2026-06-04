<?php
session_start();
require_once '../includes/header.php';
require_once '../config/db.php';

$cart     = $_SESSION['cart'] ?? [];
$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));
$discount = $_SESSION['coupon_discount'] ?? 0;
$total    = max(0, $subtotal - $discount);
$success  = '';
$error    = '';

if (empty($cart)) {
    header('Location: products.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['recipient_name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city    = mysqli_real_escape_string($conn, $_POST['city']);
    $method  = mysqli_real_escape_string($conn, $_POST['delivery_method']);
    $user_id = $_SESSION['user_id'] ?? null;
    $coupon_id = $_SESSION['coupon_id'] ?? null;

    // Insert order
    mysqli_query($conn, "INSERT INTO orders (user_id, coupon_id, subtotal, discount, total, order_type, status)
        VALUES ('$user_id', " . ($coupon_id ? "'$coupon_id'" : "NULL") . ", '$subtotal', '$discount', '$total', 'retail', 'pending')");
    $order_id = mysqli_insert_id($conn);

    // Insert order items
    foreach ($cart as $product_id => $item) {
        $qty       = (int)$item['qty'];
        $unit_price = (float)$item['price'];
        $sub       = $qty * $unit_price;
        mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal)
            VALUES ('$order_id', '$product_id', '$qty', '$unit_price', '$sub')");
    }

    // Insert delivery
    $fee = $method === 'express' ? 150 : 0;
    mysqli_query($conn, "INSERT INTO delivery (order_id, recipient_name, phone, address, city, delivery_method, delivery_fee, status)
        VALUES ('$order_id', '$name', '$phone', '$address', '$city', '$method', '$fee', 'pending')");

    // Clear cart & coupon
    unset($_SESSION['cart'], $_SESSION['coupon_id'], $_SESSION['coupon_discount'], $_SESSION['cart_subtotal']);

    $success = "Order #$order_id placed successfully! We will contact you shortly.";
}
?>

<div style="background:var(--green-dark);color:white;padding:40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:34px;">Checkout</h1>
  <p style="opacity:0.85;">Complete your order below</p>
</div>

<div class="section" style="max-width:800px;">
  <?php if ($success): ?>
    <div style="background:#e8f5ec;border-left:5px solid var(--green-mid);padding:20px 24px;border-radius:var(--radius);text-align:center;">
      <div style="font-size:48px;margin-bottom:10px;">✅</div>
      <h3 style="color:var(--green-dark);margin-bottom:8px;"><?= $success ?></h3>
      <a href="../index.php" class="btn btn-primary" style="margin-top:16px;">Back to Home</a>
    </div>
  <?php else: ?>

  <div style="display:grid;grid-template-columns:1fr 320px;gap:30px;align-items:start;">

    <!-- Delivery Form -->
    <div style="background:white;border-radius:var(--radius-lg);padding:28px;box-shadow:var(--shadow);border:1px solid var(--border);">
      <h3 style="color:var(--green-dark);margin-bottom:20px;font-size:18px;">📍 Delivery Details</h3>
      <form method="POST" onsubmit="validateCheckout(event)">
        <div class="form-group">
          <label>Recipient Name</label>
          <input type="text" id="recipient_name" name="recipient_name" placeholder="Full name">
        </div>
        <div class="form-group">
          <label>Phone Number</label>
          <input type="text" id="phone" name="phone" placeholder="0712345678">
        </div>
        <div class="form-group">
          <label>Delivery Address</label>
          <textarea id="address" name="address" rows="3" placeholder="Street, building, estate..."></textarea>
        </div>
        <div class="form-group">
          <label>City</label>
          <input type="text" id="city" name="city" placeholder="Nairobi" value="Nairobi">
        </div>
        <div class="form-group">
          <label>Delivery Method</label>
          <select name="delivery_method" id="delivery_method" onchange="updateDeliveryFee(this.value)">
            <option value="standard">Standard Delivery – Free</option>
            <option value="express">Express Delivery – KES 150</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Place Order</button>
      </form>
    </div>

    <!-- Order Summary -->
    <div style="background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);">
      <h3 style="color:var(--green-dark);margin-bottom:16px;font-size:16px;">🧾 Order Summary</h3>
      <?php foreach ($cart as $item): ?>
      <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;">
        <span><?= $item['emoji'] ?> <?= htmlspecialchars($item['name']) ?> × <?= $item['qty'] ?></span>
        <span>KES <?= number_format($item['price'] * $item['qty'], 2) ?></span>
      </div>
      <?php endforeach; ?>
      <hr style="margin:14px 0;border-color:var(--border);">
      <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;">
        <span>Subtotal</span><span>KES <?= number_format($subtotal,2) ?></span>
      </div>
      <?php if ($discount > 0): ?>
      <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;color:var(--green-mid);">
        <span>Discount</span><span>− KES <?= number_format($discount,2) ?></span>
      </div>
      <?php endif; ?>
      <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;">
        <span>Delivery</span><span id="delivery_fee_display">Free</span>
      </div>
      <hr style="margin:14px 0;border-color:var(--border);">
      <div style="display:flex;justify-content:space-between;font-weight:700;font-size:15px;">
        <span>Total</span>
        <span id="checkout_total" style="color:var(--green-dark);">KES <?= number_format($total,2) ?></span>
      </div>
    </div>

  </div>
  <?php endif; ?>
</div>

<script src="../assets/js/validation.js"></script>
<script>
function updateDeliveryFee(method) {
    const fee     = method === 'express' ? 150 : 0;
    const base    = <?= $total ?>;
    const newTotal = base + fee;
    document.getElementById('delivery_fee_display').textContent = fee > 0 ? 'KES ' + fee : 'Free';
    document.getElementById('checkout_total').textContent = 'KES ' + newTotal.toFixed(2);
}
</script>
<?php require_once '../includes/footer.php'; ?>
