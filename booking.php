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
  
  .price {
    font-size: 1.8rem;
    font-weight: bold;
    color: #ffffffff;
    text-align: right;
    margin-bottom: 10px;
  }

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
    min-width: 60px;
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
  
      .thumb-row{
        display:flex;
        width:100%;
        gap:8px;
      }
  .thumb-row img {
        margin:5px 0;
        flex:1 1 0;
  @media (max-width: 800px){
    .searchBar{ width:95%; flex-direction:column; gap:8px; }
    .searchBar input, .searchBar select, .searchBar button{ width:100%; }
  }
</style>
</style>
  </head>
  <body>
    <nav>
      <ul>
        <li class="logo">
          <img
            src="image/logo3.png"
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
              <a class="book-now" style="text-decoration: none;" href="bookingdetails.php?id=<?php echo (int)$room['id']; ?>">BOOK NOW</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- fullscreen image gallery overlay (shared for all cards on the page) -->
    <div id="imgOverlay" class="img-overlay" aria-hidden="true" role="dialog" aria-label="Image viewer">
      <button class="gallery-close" aria-label="Close viewer">✕</button>
      <button class="gallery-nav prev" aria-label="Previous image">‹</button>
      <div class="gallery-frame">
        <img id="galleryImage" src="" alt="" />
      </div>
      <button class="gallery-nav next" aria-label="Next image">›</button>
      <div class="gallery-thumbs" id="galleryThumbs" aria-hidden="false"></div>
    </div>

    <style>
      .img-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.85); display: none; align-items: center; justify-content: center; z-index: 9999; }
      .img-overlay.open { display: flex; }
      .gallery-frame { max-width: 90%; max-height: 80%; display: flex; align-items: center; justify-content: center; }
      .gallery-frame img { max-width: 100%; max-height: 80vh; object-fit: contain; border-radius: 10px; }
      .gallery-nav { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.4); color: #fff; border: none; font-size: 40px; padding: 8px 12px; cursor: pointer; border-radius: 6px; }
      .gallery-nav.prev { left: 18px; }
      .gallery-nav.next { right: 18px; }
      .gallery-close { position: absolute; right: 18px; top: 18px; background: rgba(255,255,255,0.06); border: none; color: #fff; font-size: 28px; padding: 6px 10px; border-radius: 6px; cursor: pointer; }
      .gallery-thumbs { position: absolute; bottom: 36px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; max-width: 90%; overflow: auto; padding: 6px 8px; }
      .gallery-thumbs img { height: 64px; width: auto; border-radius: 6px; cursor: pointer; opacity: 0.7; border: 2px solid transparent; }
      .gallery-thumbs img.active { opacity: 1; border-color: #0b74de; }
    </style>

    <script>
      (function(){
        const overlay = document.getElementById('imgOverlay');
        const overlayImg = document.getElementById('galleryImage');
        const overlayThumbs = document.getElementById('galleryThumbs');
        const btnPrev = overlay.querySelector('.gallery-nav.prev');
        const btnNext = overlay.querySelector('.gallery-nav.next');
        const btnClose = overlay.querySelector('.gallery-close');

        let currentImages = [];
        let current = 0;

        function setOverlay(idx){
          if (!currentImages.length) return;
          current = (idx + currentImages.length) % currentImages.length;
          overlayImg.src = currentImages[current];
          overlayImg.alt = 'Image ' + (current + 1) + ' of ' + currentImages.length;
          Array.from(overlayThumbs.children).forEach((t,i)=> t.classList.toggle('active', i===current));
        }

        function openOverlay(images, startIndex){
          currentImages = images.slice();
          overlayThumbs.innerHTML = '';
          currentImages.forEach((src, i) => {
            const t = document.createElement('img');
            t.src = src; t.alt = 'Thumbnail ' + (i+1);
            t.addEventListener('click', () => setOverlay(i));
            overlayThumbs.appendChild(t);
          });
          setOverlay(startIndex || 0);
          overlay.classList.add('open');
          overlay.setAttribute('aria-hidden','false');
          btnClose.focus();
        }

        function closeOverlay(){
          overlay.classList.remove('open');
          overlay.setAttribute('aria-hidden','true');
        }

        btnPrev.addEventListener('click', ()=> setOverlay(current-1));
        btnNext.addEventListener('click', ()=> setOverlay(current+1));
        btnClose.addEventListener('click', closeOverlay);
        overlay.addEventListener('click', (e)=>{ if (e.target === overlay) closeOverlay(); });
        document.addEventListener('keydown', (e)=>{
          if (!overlay.classList.contains('open')) return;
          if (e.key === 'ArrowLeft') setOverlay(current-1);
          if (e.key === 'ArrowRight') setOverlay(current+1);
          if (e.key === 'Escape') closeOverlay();
        });

        // Wire up each room card
        document.querySelectorAll('.roomCard').forEach(card => {
          const big = card.querySelector('.big-img');
          const thumbs = Array.from(card.querySelectorAll('.thumb'));
          if (!big) return;
          const imgs = [big.src];
          thumbs.forEach(t => { if (t.src && !imgs.includes(t.src)) imgs.push(t.src); });

          // Big image opens overlay
          big.style.cursor = 'zoom-in';
          big.addEventListener('click', ()=> openOverlay(imgs, 0));

          // Clicking thumbs swaps big image (no overlay)
          thumbs.forEach(t => {
            t.style.cursor = 'pointer';
            t.addEventListener('click', () => { big.src = t.src; big.alt = t.alt || 'Room image'; });
          });
        });
      })();
    </script>

    <div class="contact-section">
     <div class="contacts"> <h2>Contact Us</h2>
      <p>Seaside Road, Bardez, Goa - 403507</p>
      <p>📞 +91 832 555 0123</p>
      <p>✉️ www.seasideresort.com</p></div><div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3167.421090365323!2d73.79243147512595!3d15.594991185017815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbfeb0074e556bd%3A0x8df5557f01b8a85!2sAgnel%20Institute%20of%20Technology%20and%20Design!5e1!3m2!1sen!2sin!4v1762616821915!5m2!1sen!2sin" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

      </div>
    </div>

  </body>
</html>