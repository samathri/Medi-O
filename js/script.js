// hamburger menu code

let lastScrollTop = 0;
  const header = document.querySelector('.medi-o-header-container');

  window.addEventListener('scroll', function () {
    const scrollTop = window.scrollY || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop) {
      // Scrolling down
      header.classList.add('medi-o-header-hide');
    } else {
      // Scrolling up
      header.classList.remove('medi-o-header-hide');
    }

    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // For Mobile or negative scrolling
  });



  // Search bar code
  
  // Toggle popup visibility
function toggleSearchPopup(show) {
  const overlay = document.getElementById('searchOverlay');
  overlay.classList.toggle('d-none', !show);
}

// Open popup on any .bi-search icon click
document.querySelectorAll('.bi-search').forEach(icon => {
  icon.addEventListener('click', (e) => {
    e.preventDefault();
    toggleSearchPopup(true);
  });
});


// swiper initialization

  const swiper = new Swiper(".mySwiper", {
  slidesPerView: 4,
  spaceBetween: 20,
  loop: true,
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
  breakpoints: {
    0: {
      slidesPerView: 2,
    },
    768: {
      slidesPerView: 3,
    },
    992: {
      slidesPerView: 4,
    }
  }
});


// price range slider code
const priceSlider = document.getElementById('priceSlider');
const priceLabel = document.getElementById('priceLabel');
const filterBtn = document.getElementById('filterBtn');

// Set initial value for the label
priceLabel.textContent = `Rs. ${priceSlider.value}.00`;

// Function to update the background with a gradient based on the slider value
function updateSliderBackground() {
    const value = priceSlider.value;
    const min = priceSlider.min;
    const max = priceSlider.max;

    // Calculate percentage for the gradient
    const percentage = ((value - min) / (max - min)) * 100;

    // Set the background with a gradient from left (min value) to right (max value)
    priceSlider.style.background = `linear-gradient(to right, #0467BB ${percentage}%, #ddd ${percentage}%)`;

    // Update the label with the current value
    priceLabel.textContent = `Rs. ${value}.00`;
}

// Event listener to update background as the user drags the slider
priceSlider.addEventListener('input', updateSliderBackground);

// Initialize the slider background on load
updateSliderBackground();

// Event listener for the Filter button
filterBtn.addEventListener('click', () => {
    alert(`Filter applied! Showing products in the price range Rs. 0.00 to Rs. ${priceSlider.value}.00`);
});



// details swiper code
    // Function to change the main image when a thumbnail is clicked
    function changeImage(thumbnail) {
        // Get the source of the clicked thumbnail
        var newSrc = thumbnail.src;

        // Get the main image element
        var mainImage = document.getElementById('mainImage');

        // Update the main image's source to the clicked thumbnail
        mainImage.src = newSrc;
    }


// Add event listeners for the buttons
    document.querySelector('.btn-primary').addEventListener('click', function() {
    alert("Product added to cart!");
    // Here, you can add code to add the product to the cart in your cart system
});

document.querySelector('.btn-success').addEventListener('click', function() {
    alert("Proceeding to checkout!");
    // Here, you can redirect to a checkout page or trigger any checkout functionality
});
