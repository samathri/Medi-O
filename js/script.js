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
