const roomData = [
  {
    id: 1,
    name: "Standard Double Room",
    deal: "Limited-time Deal",
    description:
      "Beds: 1 double or 2 singles. Breakfast included · Free cancellation.",
    price: "₹3,000",
    maxAdults: 2,
    maxChildren: 1,
    images: {
      big: "https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small1:
        "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small2:
        "https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small3:
        "https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
    },
  },
  {
    id: 2,
    name: "Deluxe Sea View Room",
    deal: "Premium Choice",
    description:
      "Larger room with a king-size bed. Balcony with sea view · Breakfast & dinner included.",
    price: "₹8,000",
    maxAdults: 2,
    maxChildren: 2,
    images: {
      big: "https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small1:
        "https://images.unsplash.com/photo-1564501049412-61c2a3083791?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small2:
        "https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small3:
        "https://images.unsplash.com/photo-1505691723518-36a5ac3be353?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
    },
  },
  {
    id: 3,
    name: "Executive Suite",
    deal: "Luxury Pick",
    description:
      "Luxury suite with a living area and Jacuzzi. King-size bed · Complimentary drinks.",
    price: "₹12,000",
    maxAdults: 3,
    maxChildren: 2,
    images: {
      big: "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small1:
        "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small2:
        "https://images.unsplash.com/photo-1590490359853-380a2b828795?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small3:
        "https://images.unsplash.com/photo-1505692794403-35d0d2489ef7?q=80&w=2074&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
    },
  },
  {
    id: 4,
    name: "Family Room",
    deal: "Family Favorite",
    description:
      "Spacious room for families with 2 double beds + sofa bed · Free kids' meals.",
    price: "₹15,000",
    maxAdults: 4,
    maxChildren: 3,
    images: {
      big: "https://images.unsplash.com/photo-1616594039964-ae9021a400a0?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small1:
        "https://images.unsplash.com/photo-1556020685-ae41abfc9365?q=80&w=1974&auto=format&fit=crop&ixlib-rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small2:
        "https://images.unsplash.com/photo-1584132967334-10e028bd69f7?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      small3:
        "https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
    },
  },
];

document.addEventListener("DOMContentLoaded", () => {
  const path = window.location.pathname;

  updateReservationBadge();

  if (path.includes("booking.html")) {
    initializeBookingPage();
  } else if (path.includes("bookingdetails.html")) {
    renderBookingDetailsPage();
    initializeForms();
  } else if (path.includes("reservation.html")) {
    renderReservationPage();
  } else if (path.includes("login.html") || path.includes("signup.html")) {
    initializeForms();
  }
});

function initializeBookingPage() {
  renderRoomCards(roomData);
  loadResortHighlights();
  updateReservationBadge();
  const today = new Date().toISOString().split("T")[0];
  const checkinDateEl = document.getElementById("checkin-date");
  const checkoutDateEl = document.getElementById("checkout-date");
  if (checkinDateEl) checkinDateEl.setAttribute("min", today);
  if (checkoutDateEl) checkoutDateEl.setAttribute("min", today);

  const searchBar = document.querySelector(".searchBar");
  if (searchBar) {
    searchBar.addEventListener("input", handleSearchAndFilter);
    searchBar.addEventListener("change", handleSearchAndFilter);
    const searchInput = document.getElementById("search-room");
    searchInput?.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        handleSearchAndFilter();
      }
    });
  }
}

function updateReservationBadge() {
  const badge = document.getElementById("reservation-count");
  if (!badge) return;
  const reservations = JSON.parse(localStorage.getItem("reservations")) || [];
  if (reservations.length > 0) {
    badge.textContent = reservations.length;
    badge.style.display = "inline-block";
  } else {
    badge.style.display = "none";
  }
}

function clearChildren(node) {
  if (!node) return;
  while (node.firstChild) {
    node.removeChild(node.firstChild);
  }
}

function el(tag, attrs = {}, children = []) {
  const e = document.createElement(tag);
  for (const [k, v] of Object.entries(attrs)) {
    switch (k) {
      case "class":
        e.className = v;
        break;
      case "text":
        e.textContent = v;
        break;
      default:
        e.setAttribute(k, v);
    }
  }
  for (const child of [].concat(children)) {
    if (typeof child === "string")
      e.appendChild(document.createTextNode(child));
    else if (child) e.appendChild(child);
  }
  return e;
}

async function loadResortHighlights() {
  try {
    const container = document.getElementById("resort-highlights");
    const mount =
      container ||
      el("section", { id: "resort-highlights", class: "highlights" });
    if (!container) {
      const anchor =
        document.getElementById("room-card-container")?.closest(".roomCard")
          ?.parentNode || document.body;
      const contact = document.querySelector(".contact-section");
      (contact?.parentNode || document.body).insertBefore(
        mount,
        contact || null
      );
    }
    clearChildren(mount);

    const res = await fetch("data/highlights.json");
    if (!res.ok) throw new Error(`Failed to load highlights (${res.status})`);
    const items = await res.json();

    const list = el("div", { class: "highlight-list" });
    for (const item of items) {
      const card = el("div", { class: "highlight-card" }, [
        el("img", { src: item.image, alt: item.title, loading: "lazy" }),
        el("h3", { text: item.title }),
        el("p", { text: item.text }),
      ]);
      list.appendChild(card);
    }
    mount.appendChild(el("h2", { text: "Resort Highlights" }));
    mount.appendChild(list);
  } catch (err) {
    console.error("Highlights error:", err);
  }
}

async function postReservationBatch(reservationIds) {
  const payload = reservationIds.map((id) => ({ roomId: id, ts: Date.now() }));
  try {
    const res = await fetch("https://example.com/api/reservations/batch", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });
    if (!res.ok) throw new Error(`Server responded ${res.status}`);
    let data = null;
    try {
      data = await res.json();
    } catch (_) {}
    return { ok: true, data };
  } catch (error) {
    console.warn("Batch POST failed (simulated or offline)", error);
    return { ok: false, error };
  }
}

function handleSearchAndFilter() {
  const searchInput = document
    .getElementById("search-room")
    .value.toLowerCase();
  const checkinDate = document.getElementById("checkin-date").value;
  const checkoutDate = document.getElementById("checkout-date").value;
  const adults = parseInt(document.getElementById("adults-select").value) || 0;
  const children =
    parseInt(document.getElementById("children-select").value) || 0;

  if (checkinDate && checkoutDate && checkoutDate <= checkinDate) {
    alert("Check-out date must be after the check-in date.");
    return;
  }

  let filteredRooms = roomData.filter((room) => {
    const nameMatch = room.name.toLowerCase().includes(searchInput);
    const capacityMatch =
      room.maxAdults >= adults && room.maxChildren >= children;
    return nameMatch && capacityMatch;
  });

  renderRoomCards(filteredRooms);
}

function buildRoomImageMarkup(room) {
  const thumbnailKeys = ["small1", "small2", "small3"];
  const thumbnails = thumbnailKeys
    .map((key, idx) => {
      const src = room.images?.[key];
      if (!src) return "";
      return `
        <img
          class="thumb thumb-${idx + 1}"
          src="${src}"
          alt="${room.name} view ${idx + 1}"
          loading="lazy"
        />
      `;
    })
    .join("");

  return `
    <div class="images">
      <img
        class="big-img"
        src="${room.images.big}"
        alt="${room.name}"
        loading="lazy"
      />
      ${thumbnails}
    </div>
  `.trim();
}

function renderRoomCards(roomsToRender) {
  const roomContainer = document.getElementById("room-card-container");
  if (!roomContainer) return;
  roomContainer.innerHTML = "";

  if (roomsToRender.length === 0) {
    roomContainer.innerHTML = `<p style="color: white; font-size: 1.2rem; text-align: center; width: 100%;">No rooms match your criteria.</p>`;
    return;
  }

  roomsToRender.forEach((room) => {
    const imageMarkup = buildRoomImageMarkup(room);
    const roomCard = `
      <div class="roomCard">
        ${imageMarkup}
        <div class="roomDetails">
          <span class="deal">${room.deal}</span>
          <h2>${room.name}</h2>
          <p>${room.description}</p>
          <p style="font-weight: bold; margin-top: 10px;">
            Sleeps: ${room.maxAdults} Adults & ${room.maxChildren} Children
          </p>
        </div>
        <div class="roomSide">
          <div class="price">${room.price}</div>
          <a href="bookingdetails.html" style="text-decoration: none;">
            <button class="book-now" onclick="selectRoom(${room.id})">BOOK NOW</button>
          </a>
        </div>
      </div>
    `;
    roomContainer.innerHTML += roomCard;
  });

  roomContainer.querySelectorAll(".roomCard").forEach((card) => {
    const big = card.querySelector(".big-img");
    card.querySelectorAll(".thumb").forEach((thumb) => {
      thumb.addEventListener("mouseover", () => {
        if (big && thumb instanceof HTMLImageElement) {
          big.src = thumb.src;
        }
      });
      thumb.addEventListener("mouseout", () => {});
    });
  });
}

function selectRoom(roomId) {
  localStorage.setItem("selectedRoomId", roomId);
}

function renderBookingDetailsPage() {
  const roomId = localStorage.getItem("selectedRoomId");
  const room = roomData.find((r) => r.id == roomId);
  if (!room) {
    window.location.href = "booking.html";
    return;
  }

  document.getElementById("room-name").textContent = room.name;
  document.getElementById("room-description").textContent = room.description;
  document.getElementById("room-price").textContent = room.price;
  document.getElementById("bigimg").src = room.images.big;
  document.getElementById("smallimg1").src = room.images.small1;
  document.getElementById("smallimg2").src = room.images.small2;
  const smallImg3 = document.getElementById("smallimg3");
  if (smallImg3 && room.images.small3) {
    smallImg3.src = room.images.small3;
  }
}

function renderReservationPage() {
  const reservationContainer = document.getElementById("reservation-container");
  if (!reservationContainer) return;
  let reservations = JSON.parse(localStorage.getItem("reservations")) || [];

  reservationContainer.innerHTML = "";

  if (reservations.length === 0) {
    reservationContainer.innerHTML = `<p style="color: white; font-size: 1.2rem;">You have no active reservations.</p>`;
    return;
  }

  const syncBar = el("div", { class: "reservation-sync-bar" }, [
    el(
      "button",
      { class: "book-now", id: "sync-reservations", type: "button" },
      ["Sync to Server (Demo)"]
    ),
  ]);
  reservationContainer.appendChild(syncBar);

  reservations.forEach((roomId, index) => {
    const room = roomData.find((r) => r.id == roomId);
    if (!room) return;

    const card = document.createElement("div");
    card.className = "roomCard";
    const imageMarkup = buildRoomImageMarkup(room);
    card.innerHTML = `
      ${imageMarkup}
      <div class="roomDetails">
        <span class="deal">Your Booking</span>
        <h2>${room.name}</h2>
        <p>${room.description}</p>
      </div>
      <div class="roomSide">
        <div class="price">${room.price}</div>
        <button class="book-now" data-index="${index}" style="background:#d9534f;color:#fff;">Cancel Request</button>
      </div>
    `;

    reservationContainer.appendChild(card);
  });

  reservationContainer
    .querySelectorAll("button.book-now[data-index]")
    .forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const idx = parseInt(e.currentTarget.getAttribute("data-index"));
        let res = JSON.parse(localStorage.getItem("reservations")) || [];
        if (idx >= 0 && idx < res.length) {
          res.splice(idx, 1);
          localStorage.setItem("reservations", JSON.stringify(res));
          updateReservationBadge?.();
          renderReservationPage();
          alert("Your cancellation request has been submitted.");
        }
      });
    });

  const syncBtn = document.getElementById("sync-reservations");
  syncBtn?.addEventListener("click", async () => {
    const ids = JSON.parse(localStorage.getItem("reservations")) || [];
    const result = await postReservationBatch(ids);
    if (result.ok) alert("Synced with server (demo)");
    else alert("Sync failed (demo)");
  });
}

function initializeForms() {
  const signupForm = document.querySelector(".signup-container form");
  if (signupForm) {
    signupForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const password = document.getElementById("password").value;
      const confirm = document.getElementById("confirm").value;
      if (password !== confirm) {
        alert("Passwords do not match!");
        return;
      }
      alert("Sign up successful!");
      window.location.href = "index.html";
    });
  }

  const loginForm = document.querySelector(".login-container form");
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      alert("Login successful!");
      window.location.href = "index.html";
    });
  }

  const paymentForm = document.querySelector(".payment-modal form");
  if (paymentForm) {
    attachPaymentFieldMasks(paymentForm);
    paymentForm.querySelectorAll("input").forEach((inp) => {
      inp.addEventListener(
        "focus",
        () => (inp.style.outline = "2px solid #6b73ff")
      );
      inp.addEventListener("blur", () => (inp.style.outline = ""));
    });

    const nextAnchor = paymentForm.querySelector(".btn-next a");
    if (nextAnchor) {
      nextAnchor.addEventListener("click", (ev) => {
        ev.preventDefault();
        paymentForm.requestSubmit();
      });
    }

    paymentForm.addEventListener("submit", (e) => {
      e.preventDefault();

      const cardInput = document.getElementById("card-number");
      const expiryInput = document.getElementById("expiry");
      const cvvInput = document.getElementById("cvv");

      clearPaymentErrors(paymentForm);

      const cardNumberRaw = (cardInput?.value || "").replace(/\D/g, "");
      const expiryRaw = (expiryInput?.value || "").trim();
      const cvvRaw = (cvvInput?.value || "").replace(/\D/g, "");

      let valid = true;

      if (!isValidCardNumber(cardNumberRaw)) {
        showPaymentError(cardInput, "Enter a valid card number");
        valid = false;
      }

      const expiryCheck = parseExpiry(expiryRaw);
      if (!expiryCheck.valid) {
        showPaymentError(
          expiryInput,
          expiryCheck.message || "Enter expiry as MM/YY and not expired"
        );
        valid = false;
      }

      if (!isValidCVV(cvvRaw, cardNumberRaw)) {
        showPaymentError(cvvInput, "Enter a valid CVV");
        valid = false;
      }

      if (!valid) return;

      const roomId = localStorage.getItem("selectedRoomId");
      let reservations = JSON.parse(localStorage.getItem("reservations")) || [];

      if (!reservations.includes(roomId)) {
        reservations.push(roomId);
      }

      localStorage.setItem("reservations", JSON.stringify(reservations));

      postReservationBatch(reservations)
        .then(() => {})
        .catch(() => {});

      alert("Payment successful! Your reservation is confirmed.");
      window.location.href = "reservation.html";
    });
  }
}

function describeOccupancy(adults, children) {
  let label = "";
  switch (true) {
    case adults === 0 && children === 0:
      label = "Sleeps: N/A";
      break;
    case adults <= 2 && children <= 1:
      label = "Sleeps: up to a small family";
      break;
    case adults <= 4 && children <= 3:
      label = "Sleeps: family size";
      break;
    default:
      label = "Sleeps: group";
  }
  return label;
}

function attachPaymentFieldMasks(form) {
  const card = form.querySelector("#card-number");
  const expiry = form.querySelector("#expiry");
  const cvv = form.querySelector("#cvv");

  if (card) {
    card.setAttribute("inputmode", "numeric");
    card.setAttribute("autocomplete", "cc-number");
    card.maxLength = 19;
    card.addEventListener("input", () => {
      const digits = card.value.replace(/\D/g, "").slice(0, 16);
      const groups = digits.match(/.{1,4}/g) || [];
      card.value = groups.join(" ");
    });
  }

  if (expiry) {
    expiry.setAttribute("placeholder", "MM/YY");
    expiry.setAttribute("inputmode", "numeric");
    expiry.setAttribute("autocomplete", "cc-exp");
    expiry.maxLength = 5;
    expiry.addEventListener("input", () => {
      let v = expiry.value.replace(/[^\d]/g, "");
      if (v.length > 2) v = v.slice(0, 2) + "/" + v.slice(2, 4);
      expiry.value = v.slice(0, 5);
    });
  }

  if (cvv) {
    cvv.setAttribute("inputmode", "numeric");
    cvv.setAttribute("autocomplete", "cc-csc");
    cvv.maxLength = 3;
    cvv.addEventListener("input", () => {
      cvv.value = cvv.value.replace(/\D/g, "").slice(0, 3);
    });
  }
}

function luhnCheck(num) {
  let sum = 0;
  let shouldDouble = false;
  for (let i = num.length - 1; i >= 0; i--) {
    let digit = parseInt(num[i], 10);
    if (shouldDouble) {
      digit *= 2;
      if (digit > 9) digit -= 9;
    }
    sum += digit;
    shouldDouble = !shouldDouble;
  }
  return sum % 10 === 0;
}

function isValidCardNumber(num) {
  if (!/^\d{16}$/.test(num)) return false;
  return luhnCheck(num);
}

function parseExpiry(mmYY) {
  const out = { valid: false, month: null, year: null, message: "" };
  const m = mmYY.match(/^(0[1-9]|1[0-2])\/(\d{2})$/);
  if (!m) {
    out.message = "Use MM/YY";
    return out;
  }
  const month = parseInt(m[1], 10);
  const year = 2000 + parseInt(m[2], 10);
  const expiryDate = new Date(year, month, 0, 23, 59, 59, 999);
  const now = new Date();
  if (expiryDate < now) {
    out.message = "Card expired";
    return out;
  }
  out.valid = true;
  out.month = month;
  out.year = year;
  return out;
}

function isValidCVV(cvv, cardNum) {
  return /^\d{3}$/.test(cvv);
}

function showPaymentError(inputEl, message) {
  if (!inputEl) return;
  const err = document.createElement("div");
  err.className = "input-error";
  err.textContent = message;
  err.style.color = "#ffb4b4";
  err.style.fontSize = "0.85rem";
  err.style.marginTop = "6px";
  inputEl.style.border = "2px solid #ff6b6b";
  inputEl.insertAdjacentElement("afterend", err);
}

function clearPaymentErrors(form) {
  form.querySelectorAll(".input-error").forEach((el) => el.remove());
  form.querySelectorAll("input").forEach((inp) => (inp.style.border = ""));
}
