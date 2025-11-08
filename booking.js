// booking.js – simplified and readable version
const RoomData = [
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
      big: "https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
      small1:
        "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
      small2:
        "https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
      small3:
        "https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
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
      big: "https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3",
      small1:
        "https://images.unsplash.com/photo-1564501049412-61c2a3083791?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3",
      small2:
        "https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
      small3:
        "https://images.unsplash.com/photo-1505691723518-36a5ac3be353?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
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
      big: "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
      small1:
        "https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
      small2:
        "https://images.unsplash.com/photo-1590490359854-dfba196da72c?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
      small3:
        "https://images.unsplash.com/photo-1559599101-f09722fb4948?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3",
    },
  },
];

function updateReservationBadge() {
  const badge = document.getElementById("reservation-count");
  if (!badge) return;
  const reservations = JSON.parse(localStorage.getItem("reservations") || "[]");
  if (reservations.length > 0) {
    badge.textContent = reservations.length;
    badge.style.display = "inline-block";
  } else {
    badge.style.display = "none";
  }
}

function renderRoomCards(list) {
  const container = document.getElementById("room-card-container");
  if (!container) return;
  container.innerHTML = "";
  if (!list || list.length === 0) {
    const p = document.createElement("p");
    p.style.color = "white";
    p.style.fontSize = "1.1rem";
    p.textContent = "No rooms match your criteria.";
    container.appendChild(p);
    return;
  }

  list.forEach((room) => {
    const card = document.createElement("div");
    card.className = "roomCard";

    const images = document.createElement("div");
    images.className = "images";
    const big = document.createElement("img");
    big.className = "big-img";
    big.src = room.images.big;
    big.alt = room.name;
    images.appendChild(big);
    ["small1", "small2", "small3"].forEach((key) => {
      const src = room.images[key];
      if (!src) return;
      const thumb = document.createElement("img");
      thumb.className = "thumb";
      thumb.src = src;
      thumb.alt = room.name + " thumb";
      thumb.addEventListener("mouseover", () => {
        big.src = thumb.src;
      });
      images.appendChild(thumb);
    });

    const details = document.createElement("div");
    details.className = "roomDetails";
    const deal = document.createElement("span");
    deal.className = "deal";
    deal.textContent = room.deal;
    const h2 = document.createElement("h2");
    h2.textContent = room.name;
    const desc = document.createElement("p");
    desc.textContent = room.description;
    const cap = document.createElement("p");
    cap.style.fontWeight = "bold";
    cap.style.marginTop = "10px";
    cap.textContent = `Sleeps: ${room.maxAdults} Adults & ${room.maxChildren} Children`;
    details.appendChild(deal);
    details.appendChild(h2);
    details.appendChild(desc);
    details.appendChild(cap);

    const side = document.createElement("div");
    side.className = "roomSide";
    const price = document.createElement("div");
    price.className = "price";
    price.textContent = room.price;
    const btn = document.createElement("button");
    btn.className = "book-now";
    btn.textContent = "BOOK NOW";
    btn.addEventListener("click", () => {
      localStorage.setItem("selectedRoomId", room.id);
      window.location.href = "bookingdetails.html";
    });
    side.appendChild(price);
    side.appendChild(btn);

    card.appendChild(images);
    card.appendChild(details);
    card.appendChild(side);
    container.appendChild(card);
  });
}

function handleSearchAndFilter() {
  const search = (
    document.getElementById("search-room") || { value: "" }
  ).value.toLowerCase();
  const adults =
    parseInt(
      (document.getElementById("adults-select") || { value: 0 }).value
    ) || 0;
  const children =
    parseInt(
      (document.getElementById("children-select") || { value: 0 }).value
    ) || 0;

  const filtered = roomData.filter(
    (r) =>
      r.name.toLowerCase().includes(search) &&
      r.maxAdults >= adults &&
      r.maxChildren >= children
  );
  renderRoomCards(filtered);
}

function initializeBookingPage() {
  renderRoomCards(roomData);
  updateReservationBadge();
  const today = new Date().toISOString().split("T")[0];
  const inEl = document.getElementById("checkin-date");
  const outEl = document.getElementById("checkout-date");
  if (inEl) inEl.min = today;
  if (outEl) outEl.min = today;

  const searchBar = document.querySelector(".searchBar");
  if (searchBar) {
    searchBar.addEventListener("input", handleSearchAndFilter);
    searchBar.addEventListener("change", handleSearchAndFilter);
    const searchInput = document.getElementById("search-room");
    if (searchInput)
      searchInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
          e.preventDefault();
          handleSearchAndFilter();
        }
      });
  }

  const searchBtn = document.getElementById("search-button");
  if (searchBtn) searchBtn.addEventListener("click", handleSearchAndFilter);
}

document.addEventListener("DOMContentLoaded", initializeBookingPage);
