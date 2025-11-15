<?php
  $conn = require __DIR__ . '/db_connect.php';
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) {
    http_response_code(400);
    die('<!DOCTYPE html><html><body><p style="color:white;">Invalid room id. <a href="booking.php">Back to listings</a></p></body></html>');
  }

  $sql = "SELECT id, name, deal, price, maxAdults, maxChildren, image_big, image_small1, image_small2, image_small3";
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
      /* Align with booking.php card layout */
      .roomCard {
        display: flex;
        width: 1200px;
        background-color: rgb(26 26 37 / 59%);
        padding: 20px;
        border-radius: 10px;
        margin: 20px auto;
        color: white;
        gap: 16px;
        align-items: center;
      }
      .roomCard .images {
        flex: 0 0 auto;
        width: 350px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: 250px auto;
        gap: 12px;
      }
      .roomCard .images .big-img { grid-column: 1 / span 3; grid-row: 1 / 2; width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }
      .thumb-row{ display:flex; width:100%; gap:8px; grid-column: 1 / span 3; }
      .thumb-row .thumb{ flex:1 1 0; height: 80px; object-fit: cover; border-radius: 8px; display:block; }
      .roomDetails{ flex:1 1 auto; }
      .roomDetails h2{ margin:0 0 10px; font-size:28px; }
      .capacity{ font-weight:bold; margin-top:10px; }
      .deal{ display:inline-block; background: rgba(217,217,217,0.18); padding:4px 8px; border-radius:6px; font-weight:600; }
      .roomSide{ flex:0 0 180px; display:flex; flex-direction:column; align-items:flex-end; justify-content:center; gap:8px; }
      .price{ font-size:1.8rem; font-weight:bold; color:#fff; text-align:right; }

      /* Payment overlay */
      .overlay{ position: fixed; display:none; justify-content:center; align-items:center; inset:0; }
      .overlay:target{ display:flex; }
      .payment-modal{ width: 90%; max-width: 900px; background: rgba(20,20,20,0.95); border-radius:12px; padding:18px; display:flex; gap:18px; color:#eee; position:relative; }
      .pay-left{ padding:12px; color:#fff; width:55%; }
      .form-group{ margin-bottom:12px; }
      .form-group label{ color:#ddd; font-size:0.95rem; display:block; margin-bottom:6px; }
      .form-group input{ width:100%; padding:8px 10px; border-radius:6px; border:none; }
      .btn-next{ background:#0b74de; color:#fff; padding:10px 16px; border-radius:8px; border:none; cursor:pointer; font-weight:700; }
      .pay-right{ width: 320px; background:#1f1f1f; padding:14px; border-radius:10px; text-align:center; color:#f0f0f0; }
      .qr-box img{ width:200px; height:200px; display:block; margin:0 auto 10px; border-radius:6px; object-fit:cover; }
      .upi-logos{ display:flex; gap:8px; justify-content:center; flex-wrap:wrap; margin-top:10px; }
      .upi-logos span{ font-size:0.85rem; color:#555; background:#f3f3f3; padding:6px 8px; border-radius:6px; }
      .close-x{ position:absolute; right:12px; top:8px; font-size:26px; color:#fff; text-decoration:none; line-height:1; }

      /* Image viewer overlay */
      .img-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85); display: none; align-items: center; justify-content: center; z-index: 9999; }
      .img-overlay.open { display: flex; }
      .gallery-frame { max-width: 90%; max-height: 80%; display: flex; align-items: center; justify-content: center; }
      .gallery-frame img { max-width: 100%; max-height: 80vh; object-fit: contain; border-radius: 10px; }
      .gallery-nav { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0, 0, 0, 0.4); color: #fff; border: none; font-size: 40px; padding: 8px 12px; cursor: pointer; border-radius: 6px; }
      .gallery-nav.prev { left: 18px; }
      .gallery-nav.next { right: 18px; }
      .gallery-close { position: absolute; right: 18px; top: 18px; background: rgba(255, 255, 255, 0.06); border: none; color: #fff; font-size: 28px; padding: 6px 10px; border-radius: 6px; cursor: pointer; }
      .gallery-thumbs { position: absolute; bottom: 36px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; max-width: 90%; overflow: auto; padding: 6px 8px; }
      .gallery-thumbs img { height: 64px; width: auto; border-radius: 6px; cursor: pointer; opacity: 0.7; border: 2px solid transparent; }
      .gallery-thumbs img.active { opacity: 1; border-color: #0b74de; }

    </style>
  </head>
  <body>
    <nav>
      <ul>
        <li class="logo">
          <img src="image/logo3.png" alt="Seaside Resort logo" class="logo-wordmark" />
        </li>
        <li class="navBtn"><a href="index.php">Home</a></li>
        <li class="navBtn"><a href="booking.php">Booking</a></li>
        <li class="navBtn"><a href="reservation.php">Reservation</a></li>
        <li class="navBtn"><a href="login.php">Login</a></li>
      </ul>
    </nav>

    <div class="roomCard">
      <div class="images">
        <img id="bigimg" class="big-img" src="<?php echo htmlspecialchars($room['image_big']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>" />
        <div class="thumb-row">
          <?php if (!empty($room['image_small1'])): ?>
            <img id="smallimg1" class="thumb" src="<?php echo htmlspecialchars($room['image_small1']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?> thumb 1" />
          <?php endif; ?>
          <?php if (!empty($room['image_small2'])): ?>
            <img id="smallimg2" class="thumb" src="<?php echo htmlspecialchars($room['image_small2']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?> thumb 2" />
          <?php endif; ?>
          <?php if (!empty($room['image_small3'])): ?>
            <img id="smallimg3" class="thumb" src="<?php echo htmlspecialchars($room['image_small3']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?> thumb 3" />
          <?php endif; ?>
        </div>
      </div>

      <div class="roomDetails">
        <span class="deal"><?php echo htmlspecialchars($room['deal']); ?></span>
        <h2 id="room-name"><?php echo htmlspecialchars($room['name']); ?></h2>
        <?php if (isset($room['description'])): ?>
          <p id="room-description"><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
        <?php endif; ?>
        <p class="capacity">Sleeps: <?php echo (int)$room['maxAdults']; ?> Adults &amp; <?php echo (int)$room['maxChildren']; ?> Children</p>
      </div>
      <div class="roomSide">
        <div class="price">₹<?php echo htmlspecialchars($room['price']); ?>/-</div>
        <div style="display:flex; align-items:center; gap:8px;">
          <label for="cardQuantity" style="color:#fff;">Rooms</label>
          <input id="cardQuantity" type="number" min="1" value="1" style="width:70px; padding:6px; border-radius:6px;" />
        </div>
        <div style="color:#fff; font-weight:700;">Total: ₹<span id="cardTotalPrice"><?php echo (int)$room['price']; ?></span></div>
        <a href="#paymentOverlay" id="confirmBookingBtn" class="book-now" style="text-decoration: none;">Confirm Booking</a>
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
              <input type="hidden" id="quantityHidden" name="quantity" value="1" />
              <button type="submit" class="btn-next">NEXT</button>
              <div style="color: #ddd; font-weight: 700; margin-left: 8px">
                OR
              </div>
            </div>
          </form>
        </div>

        <div class="pay-right">
          <div style="margin:0 0 12px; font-weight:800; font-size:1.1rem;">Total: ₹<span id="modalTotalPrice"><?php echo (int)$room['price']; ?></span></div>
          <div class="qr-box">
            <img
              src="image/WhatsApp Image 2025-09-09 at 23.15.45_ce85702d.jpg"
              alt="QR code"
              width="200"
              height="200"
            />
          </div>

          <div style="font-size: 0.9rem; color: #ddd">
            Scan and pay with any BHIM UPI app
          </div>
          <div style="margin-top: 8px; font-weight: 700; color: #f0f0f0">
            seasideresort@upi
          </div>

          <h4 style="margin-top: 18px">Scan to pay</h4>
          <div class="upi-logos" style="color:#ddd">
            <span>GPay</span><span>PhonePe</span><span>Paytm</span
            ><span>Amazon Pay</span>
          </div>
        </div>
      </div>
    </div>
    <div class="contact-section">
     <div class="contacts"> <h2>Contact Us</h2>
      <p>Seaside Road, Bardez, Goa - 403507</p>
      <p>📞 +91 832 555 0123</p>
      <p>✉️ www.seasideresort.com</p></div><div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3167.421090365323!2d73.79243147512595!3d15.594991185017815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbfeb0074e556bd%3A0x8df5557f01b8a85!2sAgnel%20Institute%20of%20Technology%20and%20Design!5e1!3m2!1sen!2sin!4v1762616821915!5m2!1sen!2sin" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

      </div>
    </div>
    <script>
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

        // Overlay elements
        const overlay = document.getElementById('imgOverlay');
        const overlayImg = document.getElementById('galleryImage');
        const overlayThumbs = document.getElementById('galleryThumbs');
        const btnPrev = overlay.querySelector('.gallery-nav.prev');
        const btnNext = overlay.querySelector('.gallery-nav.next');
        const btnClose = overlay.querySelector('.gallery-close');

        // Page images
        const bigImage = document.getElementById('bigimg');
        const pageThumbIds = ['smallimg1','smallimg2','smallimg3'];

        let current = 0;

        function setOverlay(idx){
          current = (idx + images.length) % images.length;
          overlayImg.src = images[current];
          overlayImg.alt = 'Image ' + (current + 1) + ' of ' + images.length;
          Array.from(overlayThumbs.children).forEach((t,i)=> t.classList.toggle('active', i===current));
        }

        // Build overlay thumbnails once
        images.forEach((src, i) => {
          const t = document.createElement('img');
          t.src = src;
          t.alt = 'Thumbnail ' + (i+1);
          t.addEventListener('click', () => setOverlay(i));
          overlayThumbs.appendChild(t);
        });

        function openOverlayFromMain(){
          // Try to open at the index matching current big image
          const currentSrc = bigImage ? bigImage.src : '';
          let idx = 0;
          if (currentSrc) {
            const found = images.findIndex(s => currentSrc.endsWith(s));
            if (found >= 0) idx = found;
          }
          setOverlay(idx);
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

        // Big image opens overlay
        if (bigImage) {
          bigImage.style.cursor = 'zoom-in';
          bigImage.addEventListener('click', openOverlayFromMain);
        }

        // Clicking page thumbnails changes the big image (no overlay)
        pageThumbIds.forEach(id => {
          const el = document.getElementById(id);
          if (el) {
            el.style.cursor = 'pointer';
            el.addEventListener('click', () => {
              if (bigImage) {
                bigImage.src = el.src;
                bigImage.alt = el.alt || 'Room image';
              }
            });
          }
        });

        // Card price updater and syncing to hidden field in form
        const unitPrice = <?php echo (int)$room['price']; ?>;
        const cardQty = document.getElementById('cardQuantity');
        const cardTotal = document.getElementById('cardTotalPrice');
        const hiddenQty = document.getElementById('quantityHidden');
        const confirmBtn = document.getElementById('confirmBookingBtn');
        const modalTotal = document.getElementById('modalTotalPrice');

        function updateCardTotal(){
          const q = Math.max(1, parseInt(cardQty?.value || '1', 10) || 1);
          if (cardTotal) cardTotal.textContent = String(unitPrice * q);
          if (hiddenQty) hiddenQty.value = String(q);
          if (modalTotal) modalTotal.textContent = String(unitPrice * q);
        }
        if (cardQty) {
          cardQty.addEventListener('input', updateCardTotal);
          cardQty.addEventListener('change', updateCardTotal);
          updateCardTotal();
        }
        if (confirmBtn) {
          confirmBtn.addEventListener('click', updateCardTotal);
        }
      })();
    </script>
  </body>
</html>
