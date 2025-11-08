// login.js – logic for login.html
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

function initializeLoginForm() {
  const loginForm = document.querySelector(".login-container form");
  if (!loginForm) return;
  loginForm.addEventListener("submit", (e) => {
    e.preventDefault();
    alert("Login successful!");
    window.location.href = "index.html";
  });
}

document.addEventListener("DOMContentLoaded", () => {
  updateReservationBadge();
  initializeLoginForm();
});
