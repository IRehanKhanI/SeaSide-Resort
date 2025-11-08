// signup.js – logic for signup.html
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

function initializeSignupForm() {
  const signupForm = document.querySelector(".signup-container form");
  if (!signupForm) return;
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

document.addEventListener("DOMContentLoaded", () => {
  updateReservationBadge();
  initializeSignupForm();
});
