const searchTrigger = document.getElementById("triggerSearchBar");
const searchBar = document.getElementById("slideSearchBar");

searchTrigger.addEventListener("click", function (e) {
  e.preventDefault();
  searchBar.classList.add("show");
  searchTrigger.classList.add("hide");
});

// ❌ Removed outside click detection
// Now you'll need to close it manually (if you want a close button, we can add one)

// Function to close search bar (optional if you add a close button)
function closeSearchBar() {
  searchBar.classList.remove("show");
  searchTrigger.classList.remove("hide");
}



// ===================
// Hamburger menu code
// ===================
const navMenu = document.getElementById("navMenu");

const backdrop = document.createElement("div");
backdrop.classList.add("menu-backdrop");

// Close button inside backdrop
const closeBtn = document.createElement("button");
closeBtn.innerHTML = "&times;";
closeBtn.style.position = "absolute";
closeBtn.style.top = "15px";
closeBtn.style.right = "15px";
closeBtn.style.background = "transparent";
closeBtn.style.border = "none";
closeBtn.style.fontSize = "2rem";
closeBtn.style.cursor = "pointer";
closeBtn.style.color = "#0467BB";
backdrop.appendChild(closeBtn);

document.body.appendChild(backdrop);

function openMenu() {
  navMenu.classList.add("show");
  backdrop.classList.add("show");
  document.body.classList.add("menu-open");
  document.body.style.overflow = "hidden"; // stop scrolling
}

function closeMenu() {
  navMenu.classList.remove("show");
  backdrop.classList.remove("show");
  document.body.classList.remove("menu-open");
  document.body.style.overflow = ""; // restore scrolling
}

document.querySelector(".navbar-toggler").addEventListener("click", () => {
  if (navMenu.classList.contains("show")) {
    closeMenu();
  } else {
    openMenu();
  }
});

closeBtn.addEventListener("click", () => {
  closeMenu();
});

