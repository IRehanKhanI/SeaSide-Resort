<?php
  // bookingdetails.php - server-rendered room details
  $conn = require __DIR__ . '/db_connect.php';
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) {
    http_response_code(400);
    die('<!DOCTYPE html><html><body><p style="color:white;">Invalid room id. <a href="booking.php">Back to listings</a></p></body></html>');
  }

  $sql = "SELECT id, name, deal, price, maxAdults, maxChildren, image_big, image_small1, image_small2, image_small3";
  // include description if present
  $descRes = mysqli_query($conn, "SHOW COLUMNS FROM hotels LIKE 'description'");
  if ($descRes && mysqli_num_rows($descRes) > 0) {
    $sql .= ", description";
  }
  if ($descRes) mysqli_free_result($descRes);

  $sql .= " FROM hotels WHERE id = ? LIMIT 1";
  $stmt = mysqli_prepare($conn, $sql);
  if (!$stmt) {
    http_response_code(500);
    die('DB prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
  }
  mysqli_stmt_bind_param($stmt, 'i', $id);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $room = mysqli_fetch_assoc($res);
  mysqli_free_result($res);
  mysqli_stmt_close($stmt);
  mysqli_close($conn);

  if (!$room) {
    http_response_code(404);
    die('<!DOCTYPE html><html><body><p style="color:white;">Room not found. <a href="booking.php">Back to listings</a></p></body></html>');
  }

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Seaside Resort - Booking Details</title>
    <link rel="stylesheet" href="style.css" />
  <style>
    <style>
  .details-card {
    width: 80%;
    max-width: 1100px;
    margin: 20px;
    padding: 30px;
    background: rgba(56, 53, 53, 0.8);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.45);
    display: flex;
    gap: 24px;
    align-items: flex-start;
  }

  /* --- Image Gallery Grid --- */
  .images {
    width: 350px;
    display: grid;
    gap: 10px;
    grid-template-areas:
      "big big big"
      "small1 small2 small3";
  }
  #bigimg {
    grid-area: big;
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 10px;
  }
  #smallimg1 {
    grid-area: small1;
    width: 110px;
    height: 75px;
    object-fit: cover;
    border-radius: 6px;
  }
  #smallimg2 {
    grid-area: small2;
    width: 110px;
    height: 75px;
    object-fit: cover;
    border-radius: 6px;
  }
  #smallimg3 {
    grid-area: small3;
    width: 110px;
    height: 75px;
    object-fit: cover;
    border-radius: 6px;
  }
  img {
    transition: transform 0.25s ease;
  }
  img:hover {
    transform: scale(1.05);
  }

  /* --- Room Info --- */
  .info {
    margin-left: 8px;
  }
  .info h2 {
    font-size: 28px;
    margin: 0 0 10px 0;
  }
  .deal {
    display: inline-block;
    background: rgba(217, 217, 217, 0.18);
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
  }
  .btn-container {
    margin-top: 16px;
  }

  /* --- Payment Modal --- */
  .overlay {
    position: fixed;
    display: none;
    justify-content: center;
    align-items: center;
    width: 80%;
  }
  .overlay:target {
    display: flex;
  }
  .payment-modal {
    width: 90%;
    background: rgba(63, 62, 62, 0.795);
    border-radius: 12px;
    padding: 18px;
    display: flex;
    gap: 18px;
    color: #111;
    position: relative;
  }
  .pay-left {
    padding: 12px;
    color: #fff;
    width: 50%;
  }
  /* Payment-specific form group */
  .form-group {
    margin-bottom: 12px;
  }
  .form-group label {
    color: #ddd;
    font-size: 0.95rem;
    display: block;
    margin-bottom: 6px;
  }
  .form-group input {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: none;
  }
  .btn-next {
    background: #0b74de;
    color: #ffffff;
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 700;
  }
  .pay-right {
    width: 320px;
    background: #fff;
    padding: 14px;
    border-radius: 10px;
    text-align: center;
  }
  .qr-box img {
    width: 200px;
    height: 200px;
    display: block;
    margin: 0 auto 10px;
    border-radius: 6px;
    object-fit: cover;
  }
  .upi-logos {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 10px;
  }
  .upi-logos span {
    font-size: 0.85rem;
    color: #555;
    background: #f3f3f3;
    padding: 6px 8px;
    border-radius: 6px;
  }
  .close-x {
    position: absolute;
    right: 12px;
    top: 8px;
    font-size: 26px;
    color: #fff;
    text-decoration: none;
    line-height: 1;
  }
  
  /* --- Image Gallery Overlay --- */
  .img-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
  }
  .img-overlay.open {
    display: flex;
  }
  .gallery-frame {
    max-width: 90%;
    max-height: 80%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .gallery-frame img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 10px;
  }
  .gallery-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.4);
    color: #fff;
    border: none;
    font-size: 40px;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 6px;
  }
  .gallery-nav.prev {
    left: 18px;
  }
  .gallery-nav.next {
    right: 18px;
  }
  .gallery-close {
    position: absolute;
    right: 18px;
    top: 18px;
    background: rgba(255, 255, 255, 0.06);
    border: none;
    color: #fff;
    font-size: 28px;
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
  }
  .gallery-thumbs {
    position: absolute;
    bottom: 36px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    max-width: 90%;
    overflow: auto;
    padding: 6px 8px;
  }
  .gallery-thumbs img {
    height: 64px;
    width: auto;
    border-radius: 6px;
    cursor: pointer;
    opacity: 0.7;
    border: 2px solid transparent;
  }
  .gallery-thumbs img.active {
    opacity: 1;
    border-color: #0b74de;
  }
</style>
  </style>
  </head>
  <body>
    <nav>
      <ul>
        <li class="logo">
          <img src="image/logo2.png" alt="Seaside Resort logo" class="logo-wordmark" />
        </li>
        <li class="navBtn"><a href="index.php">Home</a></li>
        <li class="navBtn"><a href="booking.php">Booking</a></li>
        <li class="navBtn"><a href="reservation.php">Reservation</a></li>
        <li class="navBtn"><a href="login.php">Login</a></li>
      </ul>
    </nav>

    <div class="details-card">
      <div class="images">
        <img id="bigimg" src="<?php echo htmlspecialchars($room['image_big']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" />
        <?php if (!empty($room['image_small1'])): ?>
          <img id="smallimg1" src="<?php echo htmlspecialchars($room['image_small1']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?> thumb 1" />
        <?php else: ?>
          <img id="smallimg1" src="<?php echo htmlspecialchars($room['image_big']); ?>" alt="thumb" />
        <?php endif; ?>
        <?php if (!empty($room['image_small2'])): ?>
          <img id="smallimg2" src="<?php echo htmlspecialchars($room['image_small2']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?> thumb 2" />
        <?php else: ?>
          <img id="smallimg2" src="<?php echo htmlspecialchars($room['image_big']); ?>" alt="thumb" />
        <?php endif; ?>
        <?php if (!empty($room['image_small3'])): ?>
          <img id="smallimg3" src="<?php echo htmlspecialchars($room['image_small3']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?> thumb 3" />
        <?php else: ?>
          <img id="smallimg3" src="<?php echo htmlspecialchars($room['image_big']); ?>" alt="thumb" />
        <?php endif; ?>
      </div>

      <div class="info">
        <h2 id="room-name"><?php echo htmlspecialchars($room['name']); ?></h2>
        <p><span class="deal"><?php echo htmlspecialchars($room['deal']); ?></span></p>
        <?php if (isset($room['description'])): ?>
          <p id="room-description"><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
        <?php endif; ?>
        <p><b>Price:</b> <span id="room-price"><?php echo htmlspecialchars($room['price']); ?></span></p>
        <div class="btn-container">
          <a href="#paymentOverlay" class="book-now" style="text-decoration: none; display: inline-block; padding: 10px 16px; border-radius: 8px; background: #0b74de; color: #fff;">Confirm Booking</a>
        </div>
      </div>
        <!-- fullscreen image gallery overlay -->
        <div id="imgOverlay" class="img-overlay" aria-hidden="true" role="dialog" aria-label="Image viewer">
          <button class="gallery-close" aria-label="Close viewer">✕</button>
          <button class="gallery-nav prev" aria-label="Previous image">‹</button>
          <div class="gallery-frame">
            <img id="galleryImage" src="" alt="" />
          </div>
          <button class="gallery-nav next" aria-label="Next image">›</button>
          <div class="gallery-thumbs" id="galleryThumbs" aria-hidden="false"></div>
        </div>
    </div>

    <div id="paymentOverlay" class="overlay">
      <div class="payment-modal" role="document">
  <a href="bookingdetails.php?id=<?php echo (int)$room['id']; ?>" class="close-x">❌</a>

        <div class="pay-left">
          <h3 style="margin-top: 0; color: #fff">Debit / Credit Card</h3>
          <form action="reservation.php" method="post">
            <div class="form-group">
              <label for="card-number">Card Number</label>
              <input
                id="card-number"
                name="card"
                type="text"
                placeholder="Enter Card Number"
                required
              />
            </div>
            <div class="form-group">
              <label for="expiry">Expiry Date</label>
              <input
                id="expiry"
                name="expiry"
                type="text"
                placeholder="MM/YY"
                required
              />
            </div>
            <div class="form-group">
              <label for="cvv">CVV</label>
              <input
                id="cvv"
                name="cvv"
                type="password"
                placeholder="Enter CVV number"
                required
              />
            </div>

            <div style="display: flex; gap: 12px; align-items: center">
              <input type="hidden" name="room_id" value="<?php echo (int)$room['id']; ?>" />
              <label for="quantity" style="color:#ddd; margin-right:8px;">Rooms</label>
              <input id="quantity" name="quantity" type="number" min="1" value="1" style="width:70px; padding:6px; border-radius:6px;" />
              <button type="submit" class="btn-next">NEXT</button>
              <div style="color: #ddd; font-weight: 700; margin-left: 8px">
                OR
              </div>
            </div>
          </form>
        </div>

        <div class="pay-right">
          <div class="qr-box">
            <img
              src="image/WhatsApp Image 2025-09-09 at 23.15.45_ce85702d.jpg"
              alt="QR code"
              width="200"
              height="200"
            />
          </div>

          <div style="font-size: 0.9rem; color: #666">
            Scan and pay with any BHIM UPI app
          </div>
          <div style="margin-top: 8px; font-weight: 700; color: #222">
            seasideresort@upi
          </div>

          <h4 style="margin-top: 18px">Scan to pay</h4>
          <div class="upi-logos">
            <span>GPay</span><span>PhonePe</span><span>Paytm</span
            ><span>Amazon Pay</span>
          </div>
        </div>
      </div>
    </div>
    <div class="contact-section">
      <h2>Contact Us</h2>
      <p>Seaside Road, Bardez, Goa - 403507</p>
      <p>📞 +91 832 555 0123</p>
      <p>✉️ www.seasideresort.com</p>
    </div>
    <!-- Image gallery script (inline, no external deps) -->
    <script>
      // Build gallery images list from PHP-provided URLs
      (function(){
        const images = <?php
          $g = [];
          if (!empty($room['image_big'])) $g[] = $room['image_big'];
          foreach (['image_small1','image_small2','image_small3'] as $k) {
            if (!empty($room[$k]) && !in_array($room[$k], $g)) $g[] = $room[$k];
          }
          echo json_encode($g);
        ?>;

        if (!images || images.length === 0) return;

        const overlay = document.getElementById('imgOverlay');
        const mainImg = document.getElementById('galleryImage');
        const thumbs = document.getElementById('galleryThumbs');
        const btnPrev = overlay.querySelector('.gallery-nav.prev');
        const btnNext = overlay.querySelector('.gallery-nav.next');
        const btnClose = overlay.querySelector('.gallery-close');

        let current = 0;

        function setImage(idx) {
          current = (idx + images.length) % images.length;
          mainImg.src = images[current];
          mainImg.alt = 'Image ' + (current + 1) + ' of ' + images.length;
          Array.from(thumbs.children).forEach((t,i)=> t.classList.toggle('active', i===current));
        }

        // populate thumbnails
        images.forEach((src, i) => {
          const t = document.createElement('img');
          t.src = src;
          t.alt = 'Thumbnail ' + (i+1);
          t.addEventListener('click', (e) => setImage(i));
          thumbs.appendChild(t);
        });

        // open overlay at index
        function openAt(idx) {
          setImage(idx);
          overlay.classList.add('open');
          overlay.setAttribute('aria-hidden','false');
          // focus for keyboard nav
          btnClose.focus();
        }

        function closeOverlay() {
          overlay.classList.remove('open');
          overlay.setAttribute('aria-hidden','true');
        }

        btnPrev.addEventListener('click', ()=> setImage(current-1));
        btnNext.addEventListener('click', ()=> setImage(current+1));
        btnClose.addEventListener('click', closeOverlay);

        // close on background click
        overlay.addEventListener('click', (e)=>{
          if (e.target === overlay) closeOverlay();
        });

        // keyboard navigation
        document.addEventListener('keydown', (e)=>{
          if (!overlay.classList.contains('open')) return;
          if (e.key === 'ArrowLeft') setImage(current-1);
          if (e.key === 'ArrowRight') setImage(current+1);
          if (e.key === 'Escape') closeOverlay();
        });

        // open when clicking the main big image on the details page
        const bigImage = document.getElementById('bigimg');
        if (bigImage) {
          bigImage.style.cursor = 'zoom-in';
          bigImage.addEventListener('click', ()=> openAt(0));
        }

        // also open when clicking any of the small images
        ['smallimg1','smallimg2','smallimg3'].forEach((id, idx)=>{
          const el = document.getElementById(id);
          if (el) {
            el.style.cursor = 'zoom-in';
            el.addEventListener('click', ()=> openAt(Math.min(idx+1, images.length-1)));
          }
        });

        // initial active thumbnail
        setImage(0);
      })();
    </script>
    <!-- bookingdetails.js removed: page is server-rendered -->
  </body>
</html>
