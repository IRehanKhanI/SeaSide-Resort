<?php
$conn = require __DIR__ . '/db_connect.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$adults = isset($_GET['adults']) ? (int)$_GET['adults'] : 0;
$children = isset($_GET['children']) ? (int)$_GET['children'] : 0;
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : '';
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : '';

$hasDescription = false;
if ($res = mysqli_query($conn, "SHOW COLUMNS FROM hotels LIKE 'description'")) {
  $hasDescription = mysqli_num_rows($res) > 0;
  mysqli_free_result($res);
}

$selectCols = "id, name, deal, price, maxAdults, maxChildren, image_big, image_small1, image_small2, image_small3" . ($hasDescription ? ", description" : "");
$sql = "SELECT $selectCols FROM hotels";
$where = [];
$params = [];
$types = '';

if ($q !== '') {
  $where[] = "name LIKE ?";
  $params[] = "%$q%";
  $types .= 's';
}
if ($adults > 0) {
  $where[] = "maxAdults >= ?";
  $params[] = $adults;
  $types .= 'i';
}
if ($children > 0) {
  $where[] = "maxChildren >= ?";
  $params[] = $children;
  $types .= 'i';
}

if (!empty($where)) {
  $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY id ASC';

$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
  http_response_code(500);
  die('Query prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
}
if (!empty($params)) {
  mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$hotels = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $hotels[] = $row;
  }
  mysqli_free_result($result);
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Seaside Resort - Booking</title>
    <link rel="stylesheet" href="style.css" />
<style>
  <style>
  /* --- Search Bar --- */
  .searchBar {
    background-color: rgba(245, 240, 240, 0.26);
    padding: 1rem;
    width: 90%;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    border-radius: 10px;
    gap: 1rem;
  }
  .searchBar input,
  .searchBar select,
  .searchBar button {
    background-color: white;
    border: 2px transparent;
    border-radius: 5px;
    height: 41px;
    padding: 0 12px;
  }
  .searchBar input[type="search"] {
    width: 300px;
  }
  .searchBar input[type="date"] {
    width: 150px;
  }
  .searchBar select {
    width: 120px;
  }
  
  /* --- Misc --- */
  .no-results {
    color: white;
    font-size: 1.1rem;
    margin: 1rem 0;
    text-align: center;
  }
  .capacity {
    font-weight: bold;
    margin-top: 10px;
  }
  
  /* --- Price Display --- */
  .price {
    font-size: 1.8rem;
    font-weight: bold;
    color: #ffffffff;
    text-align: right;
    margin-bottom: 10px;
  }

  /* --- Booking Page Room Card --- */
  .roomCard {
    display: flex;
    width: 1200px;
    background-color: rgb(26 26 37 / 59%);
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
    color: white;
    gap: 16px;
    align-items: center;
  }
  .roomCard .images {
    flex: 0 0 auto;
    width: 250px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: 150px 50px;
    gap: 12px;
    justify-content: center;
  }
  .roomCard .images .big-img,
  .roomCard .images .thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
    display: block;
    min-width: 0;
    min-height: 0;
  }
  .roomCard .images .big-img {
    grid-column: 1 / span 3;
    grid-row: 1 / 2;
    border-radius: 10px;
  }
  .roomCard .images .thumb {
    grid-row: 2 / 3;
  }
  .roomCard .roomDetails {
    flex: 1 1 auto;
  }
  .roomCard .roomSide {
    flex: 0 0 160px;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: center;
    gap: 8px;
  }
  
  /* This was in your booking.php, but conflicts with .roomCard .images grid. */
  /* You may need to rename this or integrate it. */
  .thumb-row {
    display: flex;
    width: 250px;
  }
  .thumb-row img {
    margin: 5px;
  }
</style>
</style>
  </head>
  <body>
    <nav>
      <ul>
        <li class="logo">
          <img
            src="image/logo2.png"
            alt="Seaside Resort logo"
            class="logo-wordmark"
          />
        </li>
        <li class="navBtn"><a href="index.php">home</a></li>
        <li class="navBtn"><a href="booking.php">Booking</a></li>
        <li class="navBtn"><a href="reservation.php">Reservation <span id="reservation-count" class="badge"></span></a></li>
        <li class="navBtn"><a href="login.php">Login</a></li>
        <li class="navBtn"><a href="signup.php">Sign up</a></li>
      </ul>
    </nav>

    <form class="searchBar" method="get" action="booking.php">
      <input name="q" type="search" placeholder="Search for Room" value="<?php echo htmlspecialchars($q, ENT_QUOTES); ?>" />
      <input name="checkin" type="date" placeholder="Check-in" value="<?php echo htmlspecialchars($checkin, ENT_QUOTES); ?>" />
      <input name="checkout" type="date" placeholder="Check-out" value="<?php echo htmlspecialchars($checkout, ENT_QUOTES); ?>" />
      <select name="adults">
        <?php
          $adultOpts = [0=>'Adults',1=>'1 Adult',2=>'2 Adults',3=>'3 Adults',4=>'4 Adults'];
          foreach ($adultOpts as $val=>$label) {
            $sel = ($adults === $val) ? 'selected' : '';
            echo "<option value=\"$val\" $sel>" . htmlspecialchars($label) . "</option>";
          }
        ?>
      </select>
      <select name="children">
        <?php
          $childOpts = [0=>'Children',0=>'0 Children',1=>'1 Child',2=>'2 Children',3=>'3 Children'];
          $childOptions = [ ['0','Children'], ['0','0 Children'], ['1','1 Child'], ['2','2 Children'], ['3','3 Children'] ];
          foreach ($childOptions as [$val,$label]) {
            $sel = ($children === (int)$val) ? 'selected' : '';
            echo "<option value=\"$val\" $sel>" . htmlspecialchars($label) . "</option>";
          }
        ?>
      </select>
      <button type="submit" class="book-now">Search</button>
    </form>

    <div id="room-card-container">
      <?php if (empty($hotels)): ?>
        <p class="no-results">No rooms match your criteria.</p>
      <?php else: ?>
        <?php foreach ($hotels as $room): ?>
          <div class="roomCard">
            <div class="images">
              <img class="big-img" src="<?php echo htmlspecialchars($room['image_big']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" />
              <div class="thumb-row">
                <?php if (!empty($room['image_small1'])): ?>
                  <img class="thumb" src="<?php echo htmlspecialchars($room['image_small1']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?> thumb 1" />
                <?php endif; ?>
                <?php if (!empty($room['image_small2'])): ?>
                  <img class="thumb" src="<?php echo htmlspecialchars($room['image_small2']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?> thumb 2" />
                <?php endif; ?>
                <?php if (!empty($room['image_small3'])): ?>
                  <img class="thumb" src="<?php echo htmlspecialchars($room['image_small3']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?> thumb 3" />
                <?php endif; ?>
              </div>
            </div>
            <div class="roomDetails">
              <span class="deal"><?php echo htmlspecialchars($room['deal']); ?></span>
              <h2><?php echo htmlspecialchars($room['name']); ?></h2>
              <?php if (isset($room['description'])): ?>
                <p><?php echo htmlspecialchars($room['description']); ?></p>
              <?php endif; ?>
              <p class="capacity">Sleeps: <?php echo (int)$room['maxAdults']; ?> Adults &amp; <?php echo (int)$room['maxChildren']; ?> Children</p>
            </div>
            <div class="roomSide">
              <div class="price">₹<?php echo htmlspecialchars($room['price']); ?>/-</div>
              <a class="book-now" href="bookingdetails.php?id=<?php echo (int)$room['id']; ?>">BOOK NOW</a>
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