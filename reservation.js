// reservation.js – logic for reservation.html
const roomData = window.roomData || [];

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
			<img class="big-img" src="${room.images.big}" alt="${room.name}" loading="lazy" />
			${thumbnails}
		</div>
	`.trim();
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

function renderReservationPage() {
  const reservationContainer = document.getElementById("reservation-container");
  if (!reservationContainer) return;
  let reservations = JSON.parse(localStorage.getItem("reservations")) || [];
  reservationContainer.innerHTML = "";
  if (reservations.length === 0) {
    reservationContainer.innerHTML = `<p style="color: white; font-size: 1.2rem;">You have no active reservations.</p>`;
    return;
  }
  const syncBar = document.createElement("div");
  syncBar.className = "reservation-sync-bar";
  const syncBtn = document.createElement("button");
  syncBtn.className = "book-now";
  syncBtn.id = "sync-reservations";
  syncBtn.type = "button";
  syncBtn.textContent = "Sync to Server (Demo)";
  syncBar.appendChild(syncBtn);
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
          updateReservationBadge();
          renderReservationPage();
          alert("Your cancellation request has been submitted.");
        }
      });
    });
  syncBtn.addEventListener("click", async () => {
    const ids = JSON.parse(localStorage.getItem("reservations")) || [];
    const result = await postReservationBatch(ids);
    if (result.ok) alert("Synced with server (demo)");
    else alert("Sync failed (demo)");
  });
}

document.addEventListener("DOMContentLoaded", () => {
  updateReservationBadge();
  renderReservationPage();
});
