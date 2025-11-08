// index.js – logic for index.html (spline viewer scroll behavior + badge)
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

function initializeIndexPage() {
  const splineViewer = document.querySelector("#right");
  if (!splineViewer) return;
  const threshold = window.innerHeight + window.scrollY;
  window.addEventListener("scroll", () => {
    const scrollY = window.scrollY;
    if (scrollY > threshold) {
      splineViewer.style.position = "fixed";
      splineViewer.style.top = "10px";
      splineViewer.style.right = "10px";
      splineViewer.style.transform = "scale(0.3)";
      splineViewer.style.transformOrigin = "top right";
      splineViewer.style.zIndex = "1";
    } else {
      splineViewer.style.position = "";
      splineViewer.style.top = "";
      splineViewer.style.right = "";
      splineViewer.style.transform = "";
      splineViewer.style.transformOrigin = "";
      splineViewer.style.zIndex = "";
    }
  });
}

document.addEventListener("DOMContentLoaded", () => {
  updateReservationBadge();
  initializeIndexPage();
});
