function initializeIndexPage() {
  const splineViewer = document.querySelector("#right");
  if (!splineViewer) return;
  const threshold = window.innerHeight;
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
