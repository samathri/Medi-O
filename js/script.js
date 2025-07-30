const searchTrigger = document.getElementById("triggerSearchBar");
const searchBar = document.getElementById("slideSearchBar");

searchTrigger.addEventListener("click", function (e) {
  e.preventDefault();
  searchBar.classList.add("show");
  searchTrigger.classList.add("hide");
});

// Click outside to hide
document.addEventListener("click", function (e) {
  const clickedOutside = !searchBar.contains(e.target) && !searchTrigger.contains(e.target);
  if (clickedOutside) {
    searchBar.classList.remove("show");
    searchTrigger.classList.remove("hide");
  }
});



// Hamburger menu slide-in/out with backdrop handling
const navMenu = document.getElementById("navMenu");

// Create a backdrop div dynamically for overlay effect
const backdrop = document.createElement("div");
backdrop.classList.add("menu-backdrop");
document.body.appendChild(backdrop);

// Function to open menu
function openMenu() {
  navMenu.classList.add("show");
  backdrop.classList.add("show");
  document.body.classList.add("menu-open");
}

// Function to close menu
function closeMenu() {
  navMenu.classList.remove("show");
  backdrop.classList.remove("show");
  document.body.classList.remove("menu-open");
}

// Toggle menu when hamburger button is clicked
document.querySelector(".navbar-toggler").addEventListener("click", () => {
  if (navMenu.classList.contains("show")) {
    closeMenu();
  } else {
    openMenu();
  }
});

// Close menu when clicking on backdrop
backdrop.addEventListener("click", () => {
  closeMenu();
});
