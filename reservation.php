<?php
// reservation.php - accept reservation POSTs and render server-side reservations
$conn = require __DIR__ . '/db_connect.php';

// Ensure reservations table exists
// reservations table: stores quantity to support booking multiple rooms in one reservation
$createSql = "CREATE TABLE IF NOT EXISTS reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (room_id) REFERENCES hotels(id) ON DELETE CASCADE
);";
mysqli_query($conn, $createSql);

// Ensure compatibility: if an older reservations table exists without the
// `quantity` column, add it so inserts that expect quantity won't fail.
$check = mysqli_query($conn, "SHOW COLUMNS FROM reservations LIKE 'quantity'");
if ($check && mysqli_num_rows($check) === 0) {
  // Add quantity column with default 1 for backwards compatibility
  mysqli_query($conn, "ALTER TABLE reservations ADD COLUMN quantity INT NOT NULL DEFAULT 1");
}

// Handle POST actions: create or cancel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['room_id'])) {
    $roomId = (int)$_POST['room_id'];
    $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
    $ins = mysqli_prepare($conn, "INSERT INTO reservations (room_id, quantity) VALUES (?, ?)");
    if ($ins) {
      mysqli_stmt_bind_param($ins, 'ii', $roomId, $quantity);
      mysqli_stmt_execute($ins);
      mysqli_stmt_close($ins);
    }
    // Redirect (POST-Redirect-GET)
    header('Location: reservation.php');
    exit;
  }
  if (isset($_POST['cancel_id'])) {
    $cancelId = (int)$_POST['cancel_id'];
    $del = mysqli_prepare($conn, "DELETE FROM reservations WHERE id = ?");
    if ($del) {
      mysqli_stmt_bind_param($del, 'i', $cancelId);
      mysqli_stmt_execute($del);
      mysqli_stmt_close($del);
    }
    header('Location: reservation.php');
    exit;
  }
}

// Fetch reservations joined with hotels
$sql = "SELECT r.id as res_id, r.room_id, r.quantity, r.created_at, h.* FROM reservations r JOIN hotels h ON h.id = r.room_id ORDER BY r.created_at DESC";
$res = mysqli_query($conn, $sql);
$rows = [];
if ($res) {
  while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
  mysqli_free_result($res);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Seaside Resort - Reservation</title>
    <link rel="stylesheet" href="style.css" />
 <style>
  .reservation-list {
    width: 95%;
    max-width: 1100px;
    margin: 20px auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
  }
  
  /* Reservation Page Room Card */
  .roomCard {
    display: flex;
    gap: 12px;
    padding: 16px;
    background: rgba(0, 0, 0, 0.45);
    border-radius: 10px;
    color: white;
    align-items: center;
  }
  .roomCard .images {
    width: 260px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: 140px 40px;
    gap: 8px;
  }
  .roomCard .images .big-img {
    grid-column: 1 / span 3;
    height: 140px;
    width: 100%;
    object-fit: cover;
    border-radius: 8px;
  }
  .thumb {
    width: 100%;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
  }
  .roomDetails {
    flex: 1;
  }
  .roomSide {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-end;
  }
  
  /* Price specific to reservation card */
  .price {
    font-weight: 700;
    font-size: 1.1rem;
  }
  .btn-cancel {
    background: #d9534f;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
  }
</style>
  </head>
  <body>
    <nav>
      <ul>
        <li class="logo"><img src="image/logo2.png" alt="Seaside Resort logo" class="logo-wordmark" /></li>
        <li class="navBtn"><a href="index.php">Home</a></li>
        <li class="navBtn"><a href="booking.php">Booking</a></li>
        <li class="navBtn"><a href="reservation.php">Reservation</a></li>
        <li class="navBtn"><a href="login.php">Login</a></li>
        <li class="navBtn"><a href="signup.php">Sign up</a></li>

      </ul>
    </nav>

    <h1 class="welcome">Your Reservations</h1>

    <div class="reservation-list">
      <?php if (empty($rows)): ?>
        <p style="color:white; font-size:1.2rem;">You have no active reservations.</p>
      <?php else: ?>
        <?php foreach ($rows as $r): ?>
            <div class="roomCard">
            <div class="images">
              <img class="big-img" src="<?php echo htmlspecialchars($r['image_big']); ?>" alt="<?php echo htmlspecialchars($r['name']); ?>" />
              <?php if (!empty($r['image_small1'])): ?><img class="thumb" src="<?php echo htmlspecialchars($r['image_small1']); ?>" alt="thumb" /><?php else: ?><img class="thumb" src="<?php echo htmlspecialchars($r['image_big']); ?>" alt="thumb" /><?php endif; ?>
              <?php if (!empty($r['image_small2'])): ?><img class="thumb" src="<?php echo htmlspecialchars($r['image_small2']); ?>" alt="thumb" /><?php else: ?><img class="thumb" src="<?php echo htmlspecialchars($r['image_big']); ?>" alt="thumb" /><?php endif; ?>
              <?php if (!empty($r['image_small3'])): ?><img class="thumb" src="<?php echo htmlspecialchars($r['image_small3']); ?>" alt="thumb" /><?php else: ?><img class="thumb" src="<?php echo htmlspecialchars($r['image_big']); ?>" alt="thumb" /><?php endif; ?>
            </div>
            <div class="roomDetails">
              <span class="deal">Your Booking</span>
              <h2><?php echo htmlspecialchars($r['name']); ?></h2>
              <?php if (!empty($r['description'])): ?><p><?php echo htmlspecialchars($r['description']); ?></p><?php endif; ?>
              <p class="capacity">Booked on: <?php echo htmlspecialchars($r['created_at']); ?></p>
              <p class="capacity">Rooms booked: <?php echo (int)$r['quantity']; ?></p>
            </div>
            <div class="roomSide">
              <div class="price"><?php echo htmlspecialchars($r['price']); ?></div>
              <form method="post" onsubmit="return confirm('Cancel this reservation?');">
                <input type="hidden" name="cancel_id" value="<?php echo (int)$r['res_id']; ?>" />
                <button type="submit" class="btn-cancel">Cancel</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="contact-section">
      <h2>Contact Us</h2>
      <p>Seaside Road, Bardez, Goa - 403507</p>
      <p>📞 +91 832 555 0123</p>
      <p>✉️ www.seasideresort.com</p>
    </div>
  </body>
</html>
