<?php
$conn = require __DIR__ . '/db_connect.php';

$createSql = "CREATE TABLE IF NOT EXISTS reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (room_id) REFERENCES hotels(id) ON DELETE CASCADE
);";
mysqli_query($conn, $createSql);

$check = mysqli_query($conn, "SHOW COLUMNS FROM reservations LIKE 'quantity'");
if ($check && mysqli_num_rows($check) === 0) {
  mysqli_query($conn, "ALTER TABLE reservations ADD COLUMN quantity INT NOT NULL DEFAULT 1");
}

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
  
  .price {
    font-size: 1.8rem;
    font-weight: bold;
    color: #ffffffff;
    text-align: right;
    margin-bottom: 10px;
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
        <li class="logo"><img src="image/logo3.png" alt="Seaside Resort logo" class="logo-wordmark" /></li>
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
              <div class="price">₹<?php echo (int)$r['quantity'] * htmlspecialchars($r['price']); ?>/-</div>
              <form method="post" onsubmit="return confirm('Cancel this reservation?');">
                <input type="hidden" name="cancel_id" value="<?php echo (int)$r['res_id']; ?>" />
                <button type="submit" class="btn-cancel">Cancel</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- fullscreen image gallery overlay (shared for all reservation cards) -->
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

        // Wire up each reservation room card
        document.querySelectorAll('.reservation-list .roomCard').forEach(card => {
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
