// bookingdetails.js – logic for bookingdetails.html (render room + payment form)
const roomData =
  window.roomData ||
  [
    // Fallback minimal structure if not already defined by another script on page
  ];

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
  if (smallImg3 && room.images.small3) smallImg3.src = room.images.small3;
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

function isValidCVV(cvv) {
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

function initializePaymentForm() {
  const paymentForm = document.querySelector(".payment-modal form");
  if (!paymentForm) return;
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
      showPaymentError(expiryInput, expiryCheck.message || "Invalid expiry");
      valid = false;
    }
    if (!isValidCVV(cvvRaw)) {
      showPaymentError(cvvInput, "Enter a valid CVV");
      valid = false;
    }
    if (!valid) return;
    const roomId = localStorage.getItem("selectedRoomId");
    let reservations = JSON.parse(localStorage.getItem("reservations")) || [];
    if (!reservations.includes(roomId)) reservations.push(roomId);
    localStorage.setItem("reservations", JSON.stringify(reservations));
    alert("Payment successful! Your reservation is confirmed.");
    window.location.href = "reservation.html";
  });
}

document.addEventListener("DOMContentLoaded", () => {
  renderBookingDetailsPage();
  initializePaymentForm();
});
