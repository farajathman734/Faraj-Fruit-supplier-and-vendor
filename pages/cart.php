<?php
session_start();
require_once '../includes/header.php';
require_once '../config/db.php';

// Add to cart
if (isset($_GET['add'])) {
    $id  = (int)$_GET['add'];
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$id"));
    $emojis = ['Banana'=>'🍌','Mango'=>'🥭','Apple'=>'🍎','Watermelon'=>'🍉','Pineapple'=>'🍍','Pawpaw'=>'🍈','Orange'=>'🍊','Lemon'=>'🍋'];
    if ($row) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = ['name'=>$row['name'],'price'=>$row['price_retail'],'qty'=>1,'emoji'=>$emojis[$row['name']] ?? '🍊'];
        } else {
            $_SESSION['cart'][$id]['qty']++;
        }
    }
    header('Location: cart.php'); exit;
}

// Remove
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][(int)$_GET['remove']]);
    header('Location: cart.php'); exit;
}

$cart     = $_SESSION['cart'] ?? [];
$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));
$discount = $_SESSION['coupon_discount'] ?? 0;
$total    = max(0, $subtotal - $discount);
$_SESSION['cart_subtotal'] = $subtotal;
?>

<div style="background:var(--green-dark);color:white;padding:40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:34px;">Your Cart</h1>
</div>

<div class="section">
  <?php if (empty($cart)): ?>
    <div style="text-align:center;padding:60px 20px;">
      <div style="font-size:60px;margin-bottom:16px;">🛒</div>
      <h3 style="color:#888;margin-bottom:16px;">Your cart is empty</h3>
      <a href="products.php" class="btn btn-primary">Browse Products</a>
    </div>
  <?php else: ?>
    <table class="cart-table">
      <thead>
        <tr><th>Product</th><th>Unit Price</th><th>Qty</th><th>Subtotal</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($cart as $id => $item): ?>
        <tr>
          <td><?= $item['emoji'] ?> <strong><?= htmlspecialchars($item['name']) ?></strong></td>
          <td>KES <?= number_format($item['price'],2) ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:8px;">
              <button onclick="updateQty(<?= $id ?>, -1)" style="background:var(--green-light);border:none;width:28px;height:28px;border-radius:4px;cursor:pointer;font-size:16px;">−</button>
              <input id="qty_<?= $id ?>" type="number" value="<?= $item['qty'] ?>" min="1" style="width:50px;text-align:center;border:1px solid var(--border);border-radius:4px;padding:4px;">
              <button onclick="updateQty(<?= $id ?>, 1)" style="background:var(--green-light);border:none;width:28px;height:28px;border-radius:4px;cursor:pointer;font-size:16px;">+</button>
            </div>
          </td>
          <td>KES <?= number_format($item['price'] * $item['qty'],2) ?></td>
          <td><button onclick="confirmRemove(<?= $id ?>)" style="background:none;border:none;color:red;cursor:pointer;font-size:13px;">🗑 Remove</button></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Coupon Code -->
    <div style="margin-top:24px;background:white;border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow);border:1px solid var(--border);max-width:500px;">
      <h4 style="color:var(--green-dark);margin-bottom:12px;">🏷️ Apply Coupon Code</h4>
      <div style="display:flex;gap:10px;">
        <input type="text" id="coupon_code" placeholder="e.g. FARAJ10" style="flex:1;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius);font-size:14px;">
        <input type="hidden" id="coupon_id" name="coupon_id">
        <button onclick="applyCoupon()" class="btn btn-secondary">Apply</button>
      </div>
      <p id="coupon_message" style="font-size:13px;margin-top:8px;"></p>
      <p style="font-size:12px;color:#888;margin-top:6px;">Try: FARAJ10 (10% off) · BULK20 (20% off orders over KES 500) · WELCOME50 (KES 50 off)</p>
    </div>

    <!-- Order Summary -->
    <div class="cart-summary">
      <h3 style="color:var(--green-dark);margin-bottom:16px;font-size:18px;">Order Summary</h3>
      <div style="display:flex;justify-content:space-between;margin-bottom:10px;font-size:14px;">
        <span>Subtotal</span><span>KES <?= number_format($subtotal,2) ?></span>
      </div>
      <div id="discount_row" style="display:<?= $discount > 0 ? 'flex' : 'none' ?>;justify-content:space-between;margin-bottom:10px;font-size:14px;color:var(--green-mid);">
        <span>Discount</span><span id="discount_amount">− KES <?= number_format($discount,2) ?></span>
      </div>
      <div style="display:flex;justify-content:space-between;margin-bottom:10px;font-size:14px;">
        <span>Delivery</span><span style="color:var(--green-mid);">Free</span>
      </div>
      <hr style="margin:14px 0;border-color:var(--border);">
      <div style="display:flex;justify-content:space-between;font-weight:700;font-size:16px;margin-bottom:20px;">
        <span>Total</span><span id="order_total" style="color:var(--green-dark);">KES <?= number_format($total,2) ?></span>
      </div>
      <a href="checkout.php" class="btn btn-primary" style="width:100%;text-align:center;display:block;">Proceed to Checkout</a>
      <a href="products.php" class="btn" style="width:100%;text-align:center;display:block;margin-top:10px;color:var(--green-dark);border:2px solid var(--green-dark);">Continue Shopping</a>
    </div>
  <?php endif; ?>
</div>

<script src="../assets/js/cart.js"></script>
<?php require_once '../includes/footer.php'; ?>
