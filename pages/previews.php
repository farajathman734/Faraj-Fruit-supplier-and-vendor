<?php
session_start();
require_once '../includes/header.php';
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}

$user_id = $_SESSION['user_id'];
$success = $error = '';

// Submit review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)$_POST['product_id'];
    $rating     = (int)$_POST['rating'];
    $comment    = mysqli_real_escape_string($conn, $_POST['comment']);

    // Check if already reviewed
    $exists = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM reviews WHERE user_id=$user_id AND product_id=$product_id"));
    if ($exists > 0) {
        mysqli_query($conn, "UPDATE reviews SET rating=$rating, comment='$comment' WHERE user_id=$user_id AND product_id=$product_id");
        $success = 'Review updated successfully!';
    } else {
        mysqli_query($conn, "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES ($product_id, $user_id, $rating, '$comment')");
        $success = 'Review submitted successfully!';
    }
}

// Get products for review (from delivered orders)
$products = mysqli_query($conn, "SELECT DISTINCT p.id, p.name, p.category FROM order_items oi 
    JOIN orders o ON oi.order_id=o.id 
    JOIN products p ON oi.product_id=p.id 
    WHERE o.user_id=$user_id AND o.status='delivered'");

$emojis = ['Banana'=>'🍌','Mango'=>'🥭','Apple'=>'🍎','Watermelon'=>'🍉','Pineapple'=>'🍍','Pawpaw'=>'🍈','Orange'=>'🍊','Lemon'=>'🍋'];

// Get all reviews with product info
$all_reviews = mysqli_query($conn, "SELECT r.*, p.name as product_name, u.name as reviewer FROM reviews r 
    JOIN products p ON r.product_id=p.id 
    JOIN users u ON r.user_id=u.id 
    WHERE r.is_approved=1 ORDER BY r.created_at DESC LIMIT 20");
?>

<div style="background:var(--green-dark);color:white;padding:50px 40px;text-align:center;">
  <h1 style="font-family:'Playfair Display',serif;font-size:34px;margin-bottom:8px;">Product Reviews</h1>
  <p style="opacity:0.85;">Share your experience with Faraj products</p>
</div>

<div class="section" style="max-width:900px;">

  <?php if ($success): ?>
    <div style="background:#e8f5ec;border-left:5px solid var(--green-mid);padding:14px 18px;border-radius:var(--radius);margin-bottom:20px;color:var(--green-dark);font-weight:600;">⭐ <?= $success ?></div>
  <?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:28px;align-items:start;">

    <!-- Submit Review Form -->
    <div style="background:white;border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);border:1px solid var(--border);">
      <h3 style="color:var(--green-dark);margin-bottom:18px;font-size:16px;">⭐ Write a Review</h3>
      <?php if ($products && mysqli_num_rows($products) > 0): ?>
      <form method="POST">
        <div class="form-group">
          <label>Select Product</label>
          <select name="product_id" id="product_select">
            <?php while ($p = mysqli_fetch_assoc($products)): ?>
            <option value="<?= $p['id'] ?>"><?= $emojis[$p['name']] ?? '🍊' ?> <?= htmlspecialchars($p['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Your Rating</label>
          <div style="display:flex;gap:8px;margin-top:6px;" id="star_rating">
            <?php for ($i=1; $i<=5; $i++): ?>
            <span onclick="setRating(<?= $i ?>)" style="font-size:28px;cursor:pointer;opacity:0.3;transition:opacity 0.1s;" id="star_<?= $i ?>">⭐</span>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="rating" id="rating_value" value="0">
          <span id="rating_label" style="font-size:13px;color:#888;margin-top:6px;display:block;"></span>
        </div>
        <div class="form-group">
          <label>Your Comment</label>
          <textarea name="comment" rows="4" placeholder="Tell us about the product quality, freshness, delivery..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Submit Review</button>
      </form>
      <?php else: ?>
      <div style="text-align:center;padding:20px;color:#888;">
        <p style="margin-bottom:12px;">You need to complete a delivery to leave a review.</p>
        <a href="products.php" class="btn btn-primary btn-sm">Shop Now</a>
      </div>
      <?php endif; ?>
    </div>

    <!-- All Reviews -->
    <div>
      <h3 style="color:var(--green-dark);margin-bottom:16px;font-size:16px;">Recent Reviews</h3>
      <?php if ($all_reviews && mysqli_num_rows($all_reviews) > 0):
        while ($rev = mysqli_fetch_assoc($all_reviews)): ?>
      <div style="background:white;border-radius:var(--radius-lg);padding:18px;box-shadow:var(--shadow);border:1px solid var(--border);margin-bottom:14px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
          <div>
            <span style="font-weight:600;font-size:14px;"><?= $emojis[$rev['product_name']] ?? '🍊' ?> <?= htmlspecialchars($rev['product_name']) ?></span>
          </div>
          <span style="font-size:18px;"><?= str_repeat('⭐', $rev['rating']) ?><?= str_repeat('☆', 5 - $rev['rating']) ?></span>
        </div>
        <p style="font-size:13px;color:#555;line-height:1.6;margin-bottom:8px;"><?= htmlspecialchars($rev['comment']) ?></p>
        <div style="font-size:12px;color:#aaa;">— <?= htmlspecialchars($rev['reviewer']) ?> · <?= date('d M Y', strtotime($rev['created_at'])) ?></div>
      </div>
      <?php endwhile; else: ?>
      <p style="color:#888;text-align:center;padding:20px;">No reviews yet. Be the first!</p>
      <?php endif; ?>
    </div>

  </div>
</div>

<script>
const labels = ['','Poor','Fair','Good','Very Good','Excellent'];
function setRating(val) {
    document.getElementById('rating_value').value = val;
    document.getElementById('rating_label').textContent = labels[val];
    for (let i = 1; i <= 5; i++) {
        document.getElementById('star_' + i).style.opacity = i <= val ? '1' : '0.3';
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>