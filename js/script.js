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




// Payment method selection and details display
    function showPaymentDetails() {
        const paymentMethod = document.getElementById('paymentMethod').value;
        const creditCardDetails = document.getElementById('creditCardDetails');
        const paypalDetails = document.getElementById('paypalDetails');

        // Hide all payment details sections
        creditCardDetails.classList.add('d-none');
        paypalDetails.classList.add('d-none');

        // Show relevant payment details section
        if (paymentMethod === 'creditCard') {
            creditCardDetails.classList.remove('d-none');
        } else if (paymentMethod === 'paypal') {
            paypalDetails.classList.remove('d-none');
        }
    }

    function placeOrder() {
        const paymentMethod = document.getElementById('paymentMethod').value;
        const billingForm = document.getElementById('billingForm');

        if (billingForm.checkValidity()) {
            if (paymentMethod === 'paypal') {
                alert('Redirecting to PayPal...');
                // Simulate PayPal redirection
                window.location.href = 'success.html';
            } else {
                alert('Order placed successfully!');
            }
        } else {
            alert('Please fill in all required fields.');
        }
    }

    function cancelOrder() {
        if (confirm('Are you sure you want to cancel the order?')) {
            window.location.href = '/';
        }
    }


    // Replace with dynamic order number and user data
document.getElementById('orderNumber').textContent = "#ORD1234567";
document.getElementById('userName').textContent = "John Doe";
document.getElementById('userEmail').textContent = "john.doe@example.com";
// etc.


// Function to place the order
function placeOrder() {
    // Get form elements to validate if they are filled in
    const fullName = document.getElementById('fullName').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const address = document.getElementById('address').value;
    const city = document.getElementById('city').value;
    const postalCode = document.getElementById('postalCode').value;
    const country = document.getElementById('country').value;

    // Check if all required fields are filled
    if (!fullName || !email || !phone || !address || !city || !postalCode || !country) {
        alert("Please fill in all required fields.");
        return;
    }

    // Optionally, you can validate payment method here as well (Credit/Debit or PayPal)

    // Redirect to successful page
    window.location.href = "successful.html";  // Replace with the actual path to your successful page
}



// Login, Signup, and Forgot Password form handling
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add validation logic here (e.g., check if email/password are entered)
    alert("Login form submitted!");
});

document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert("Signup form submitted!");
});

document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add validation logic here (e.g., check if email is entered)
    alert("Password reset request submitted!");
});



// Simple form validation placeholders
    document.getElementById('profileForm').addEventListener('submit', (e) => {
      e.preventDefault();
      alert('Profile details saved!');
    });

    document.getElementById('passwordForm').addEventListener('submit', (e) => {
      e.preventDefault();
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      if (newPassword !== confirmPassword) {
        alert('New passwords do not match!');
      } else {
        alert('Password updated!');
      }
    });


    // Sidebar toggle script
        document.getElementById('sidebarCollapse').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Section navigation
        const sidebarLinks = document.querySelectorAll('#sidebarMenu a.nav-link');
        const sections = document.querySelectorAll('main section');

        function showSection(id) {
            sections.forEach(section => {
                if (section.id === id) {
                    section.classList.remove('d-none');
                } else {
                    section.classList.add('d-none');
                }
            });
        }

        sidebarLinks.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                showSection(targetId);
                // Update active class
                sidebarLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            });
        });

        // Show dashboard by default
        showSection('dashboardSection');

        // Data for dashboard cards and chart (simulate fetching from API)
        const dashboardData = {
            totalUsers: 1234,
            totalPrescriptions: 567,
            pendingApprovals: 89,
            totalProducts: 350,
            prescriptionStatusCount: {
                Pending: 89,
                Approved: 400,
                Rejected: 78
            },
            bestSellingItems: ["Paracetamol", "Ibuprofen", "Vitamin C"]
        };

        // Update dashboard cards dynamically
        document.getElementById('totalUsers').textContent = dashboardData.totalUsers;
        document.getElementById('totalPrescriptions').textContent = dashboardData.totalPrescriptions;
        document.getElementById('pendingApprovals').textContent = dashboardData.pendingApprovals;
        document.getElementById('totalProducts').textContent = dashboardData.totalProducts;

        // Update Best Selling Items list
        const bestSellingList = document.getElementById('bestSellingList');
        bestSellingList.innerHTML = '';
        dashboardData.bestSellingItems.forEach(item => {
            const li = document.createElement('li');
            li.className = 'list-group-item';
            li.textContent = item;
            bestSellingList.appendChild(li);
        });

        // Initialize Chart.js for prescription statuses
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(dashboardData.prescriptionStatusCount),
                datasets: [{
                    data: Object.values(dashboardData.prescriptionStatusCount),
                    backgroundColor: ['#ffc107', '#198754', '#dc3545'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Poppins' }
                        }
                    },
                    title: {
                        display: false,
                    }
                }
            }
        });

        // Update Admin Profile Pending Approval count
        const pendingApprovalCount = document.getElementById('pendingApprovalCount');
        pendingApprovalCount.textContent = dashboardData.pendingApprovals + " prescriptions pending approval";

        // Change password form (example basic validation)
        document.getElementById('changePasswordForm').addEventListener('submit', e => {
            e.preventDefault();
            const currentPass = document.getElementById('currentPassword').value.trim();
            const newPass = document.getElementById('newPassword').value.trim();
            const confirmPass = document.getElementById('confirmNewPassword').value.trim();

            if (newPass !== confirmPass) {
                alert("New passwords do not match!");
                return;
            }

            if (newPass.length < 6) {
                alert("New password should be at least 6 characters.");
                return;
            }

            alert("Password changed successfully!");
            e.target.reset();
        });

        // Logout button
        document.getElementById('logoutBtn').addEventListener('click', () => {
            alert('Logging out...');
            // Implement actual logout here
        });



    // User Search filter example
    const userSearch = document.getElementById('userSearch');
    const userTableBody = document.getElementById('userTableBody');

    userSearch.addEventListener('input', () => {
      const filter = userSearch.value.toLowerCase();
      const rows = userTableBody.querySelectorAll('tr');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(filter)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });

    // Change password form submit (dummy)
    document.getElementById('changePasswordForm').addEventListener('submit', e => {
      e.preventDefault();
      const current = e.target.currentPassword.value;
      const newPwd = e.target.newPassword.value;
      const confirmPwd = e.target.confirmPassword.value;

      if (newPwd !== confirmPwd) {
        alert('New passwords do not match!');
        return;
      }

      // Here add your backend password change logic
      alert('Password changed successfully!');
      e.target.reset();
    });

    // Logout button (dummy)
    document.getElementById('logoutBtn').addEventListener('click', () => {
      alert('Logged out successfully!');
      // Redirect or call logout endpoint
    });





// Pharmacist Dashboard functionality
    function showQRModal(prescriptionId) {
      const qrImg = document.getElementById('qrModalImg');
      qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${prescriptionId}`;
      const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
      qrModal.show();
    }

    function updateStatus(button, newStatus) {
      const row = button.closest('tr');
      const statusBadge = row.querySelector('td:nth-child(4) > span');
      const prescriptionFileLink = row.querySelector('td:nth-child(3) a').textContent.trim();
      const prescriptionId = prescriptionFileLink.split('_')[1].split('.')[0].toUpperCase();

      const oldStatus = statusBadge.textContent;

      // Update badge text and color
      if (newStatus === 'Packing') {
        statusBadge.textContent = 'Packing';
        statusBadge.className = 'pharmacist-dashboard__badge pharmacist-dashboard__badge--pending';
        statusBadge.style.color = '#212529';
      } else if (newStatus === 'Ready') {
        statusBadge.textContent = 'Ready';
        statusBadge.className = 'pharmacist-dashboard__badge pharmacist-dashboard__badge--ready';
        statusBadge.style.color = '';
        alert(`Notification sent: Prescription ${prescriptionId} is Ready to Pickup.`);
      }

      // Disable/Enable buttons accordingly
      const buttons = row.querySelectorAll('button');
      buttons.forEach(btn => {
        if (btn.textContent.includes('Start Packing')) {
          btn.disabled = newStatus !== 'Assigned';
        } else if (btn.textContent.includes('Complete & Ready')) {
          btn.disabled = newStatus === 'Ready';
        }
      });

      // Append activity log (in a real app, this would be server-side)
      const activityBody = document.getElementById('activityHistoryBody');
      const now = new Date();
      const formattedDate = now.getFullYear() + '-' + 
        String(now.getMonth()+1).padStart(2,'0') + '-' +
        String(now.getDate()).padStart(2,'0') + ' ' +
        String(now.getHours()).padStart(2,'0') + ':' +
        String(now.getMinutes()).padStart(2,'0');

      const newRow = document.createElement('tr');
      newRow.innerHTML = `
        <td>${formattedDate}</td>
        <td>${prescriptionId}</td>
        <td>${oldStatus}</td>
        <td>${newStatus}</td>
        <td>${newStatus === 'Packing' ? 'Started packing' : 'Marked as ready for pickup'}</td>
      `;
      activityBody.prepend(newRow);
    }




// user profile script
      // Prescription Status Dashboard Script
        document.addEventListener('DOMContentLoaded', () => {
            const prescriptionRows = document.querySelectorAll('#prescriptionList tr');
            const statusCounts = {
                Pending: 0,
                "Order Processing": 0,
                Collected: 0,
                "Ready to Collect": 0,
            };

            prescriptionRows.forEach(row => {
                const statusSpan = row.querySelector('td:nth-child(3) span');
                if (!statusSpan) return;
                let status = statusSpan.textContent.trim();

                // Increment count if status matches expected statuses
                if (statusCounts.hasOwnProperty(status)) {
                    statusCounts[status]++;
                }
            });

            // Update dashboard counts
            document.getElementById('countPending').textContent = statusCounts.Pending;
            document.getElementById('countStartToPack').textContent = statusCounts["Order Processing"];
            document.getElementById('countReadyToCollect').textContent = statusCounts["Ready to Collect"];
            document.getElementById('countCollected').textContent = statusCounts.Collected;

            // Initialize Chart.js Doughnut chart
            const ctx = document.getElementById('prescriptionStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Order Processing', 'Collected', 'Ready to Collect'],
                    datasets: [{
                        label: 'Prescription Status',
                        data: [
                            statusCounts.Pending,
                            statusCounts["Order Processing"],
                            statusCounts["Ready to Collect"],
                            statusCounts.Collected,
                        ],
                        backgroundColor: [
                            '#ffc107', // warning yellow (Pending)
                            '#0dcaf0', // info cyan (Order Processing)
                            '#0d6efd', // primary blue (Collected)
                            '#198754', // success green (Ready to Collect)
                        ],
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });



