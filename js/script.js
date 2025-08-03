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


    // // Sidebar toggle script
    //     document.getElementById('sidebarCollapse').addEventListener('click', function () {
    //         document.getElementById('sidebar').classList.toggle('active');
    //     });

    //     // Section navigation
    //     const sidebarLinks = document.querySelectorAll('#sidebarMenu a.nav-link');
    //     const sections = document.querySelectorAll('main section');

    //     function showSection(id) {
    //         sections.forEach(section => {
    //             if (section.id === id) {
    //                 section.classList.remove('d-none');
    //             } else {
    //                 section.classList.add('d-none');
    //             }
    //         });
    //     }

    //     sidebarLinks.forEach(link => {
    //         link.addEventListener('click', e => {
    //             e.preventDefault();
    //             const targetId = link.getAttribute('href').substring(1);
    //             showSection(targetId);
    //             // Update active class
    //             sidebarLinks.forEach(l => l.classList.remove('active'));
    //             link.classList.add('active');
    //         });
    //     });

    //     // Show dashboard by default
    //     showSection('dashboardSection');

    //     // Data for dashboard cards and chart (simulate fetching from API)
    //     const dashboardData = {
    //         totalUsers: 1234,
    //         totalPrescriptions: 567,
    //         pendingApprovals: 89,
    //         totalProducts: 350,
    //         prescriptionStatusCount: {
    //             Pending: 89,
    //             Approved: 400,
    //             Rejected: 78
    //         },
    //         bestSellingItems: ["Paracetamol", "Ibuprofen", "Vitamin C"]
    //     };

    //     // Update dashboard cards dynamically
    //     document.getElementById('totalUsers').textContent = dashboardData.totalUsers;
    //     document.getElementById('totalPrescriptions').textContent = dashboardData.totalPrescriptions;
    //     document.getElementById('pendingApprovals').textContent = dashboardData.pendingApprovals;
    //     document.getElementById('totalProducts').textContent = dashboardData.totalProducts;

    //     // Update Best Selling Items list
    //     const bestSellingList = document.getElementById('bestSellingList');
    //     bestSellingList.innerHTML = '';
    //     dashboardData.bestSellingItems.forEach(item => {
    //         const li = document.createElement('li');
    //         li.className = 'list-group-item';
    //         li.textContent = item;
    //         bestSellingList.appendChild(li);
    //     });

    //     // Initialize Chart.js for prescription statuses
    //     const ctx = document.getElementById('statusChart').getContext('2d');
    //     const statusChart = new Chart(ctx, {
    //         type: 'doughnut',
    //         data: {
    //             labels: Object.keys(dashboardData.prescriptionStatusCount),
    //             datasets: [{
    //                 data: Object.values(dashboardData.prescriptionStatusCount),
    //                 backgroundColor: ['#ffc107', '#198754', '#dc3545'],
    //             }]
    //         },
    //         options: {
    //             responsive: true,
    //             plugins: {
    //                 legend: {
    //                     position: 'bottom',
    //                     labels: {
    //                         font: { family: 'Poppins' }
    //                     }
    //                 },
    //                 title: {
    //                     display: false,
    //                 }
    //             }
    //         }
    //     });

    //     // Update Admin Profile Pending Approval count
    //     const pendingApprovalCount = document.getElementById('pendingApprovalCount');
    //     pendingApprovalCount.textContent = dashboardData.pendingApprovals + " prescriptions pending approval";

    //     // Change password form (example basic validation)
    //     document.getElementById('changePasswordForm').addEventListener('submit', e => {
    //         e.preventDefault();
    //         const currentPass = document.getElementById('currentPassword').value.trim();
    //         const newPass = document.getElementById('newPassword').value.trim();
    //         const confirmPass = document.getElementById('confirmNewPassword').value.trim();

    //         if (newPass !== confirmPass) {
    //             alert("New passwords do not match!");
    //             return;
    //         }

    //         if (newPass.length < 6) {
    //             alert("New password should be at least 6 characters.");
    //             return;
    //         }

    //         alert("Password changed successfully!");
    //         e.target.reset();
    //     });

    //     // Logout button
    //     document.getElementById('logoutBtn').addEventListener('click', () => {
    //         alert('Logging out...');
    //         // Implement actual logout here
    //     });



    // // User Search filter example
    // const userSearch = document.getElementById('userSearch');
    // const userTableBody = document.getElementById('userTableBody');

    // userSearch.addEventListener('input', () => {
    //   const filter = userSearch.value.toLowerCase();
    //   const rows = userTableBody.querySelectorAll('tr');

    //   rows.forEach(row => {
    //     const text = row.textContent.toLowerCase();
    //     if (text.includes(filter)) {
    //       row.style.display = '';
    //     } else {
    //       row.style.display = 'none';
    //     }
    //   });
    // });

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


 // Sidebar toggle for mobile
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');

    sidebarCollapse.addEventListener('click', () => {
      sidebar.classList.toggle('active');
    });

    // Navigation section toggle
    const navLinks = document.querySelectorAll('#sidebarMenu .nav-link');

    navLinks.forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();

        // Close sidebar on mobile after clicking
        if (window.innerWidth <= 768) {
          sidebar.classList.remove('active');
        }

        // Remove active class from all links
        navLinks.forEach(nav => nav.classList.remove('active'));
        link.classList.add('active');

        // Hide all sections
        sections.forEach(section => section.classList.add('d-none'));

        // Show clicked section
        const targetId = link.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);
        if (targetSection) {
          targetSection.classList.remove('d-none');
          targetSection.focus();
        }
      });
    });

    

    // Frontend integration example snippet (fetch users):
    async function loadUsers() {
  const tbody = document.querySelector('#usersTable tbody');
  tbody.innerHTML = '';

  try {
    const res = await fetch('get_users.php');
    const users = await res.json();

    users.forEach(user => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${user.id}</td>
        <td>${user.name}</td>
        <td>${user.email}</td>
        <td>${user.phone || '-'}</td>
        <td>${user.role}</td>
        <td>${new Date(user.created_at).toLocaleDateString()}</td>
      `;
      tbody.appendChild(tr);
    });
  } catch (error) {
    console.error('Error loading users:', error);
  }
}

loadUsers();


async function loadUsers() {
  const tbody = document.querySelector('#userManagementSection tbody');
  try {
    const res = await fetch('api/get_users.php');
    const users = await res.json();

    tbody.innerHTML = '';
    users.forEach(user => {
      tbody.innerHTML += `
        <tr>
          <td>${user.id}</td>
          <td>${user.name}</td>
          <td>${user.email}</td>
          <td><span class="badge bg-${user.status === 'Active' ? 'success' : 'secondary'}">${user.status}</span></td>
          <td>${user.registered_date}</td>
          <td>
            <button class="btn btn-sm btn-info me-1">View</button>
            <button class="btn btn-sm btn-danger">Suspend</button>
          </td>
        </tr>`;
    });
  } catch (error) {
    console.error('Failed to load users:', error);
  }
}

async function loadPrescriptions() {
  const tbody = document.querySelector('#prescriptionManagementSection tbody');
  try {
    const res = await fetch('api/get_prescriptions.php');
    const prescriptions = await res.json();

    tbody.innerHTML = '';
    prescriptions.forEach(p => {
      tbody.innerHTML += `
        <tr>
          <td>${p.id}</td>
          <td>${p.user_name}</td>
          <td>${p.pharmacist_name || 'Unassigned'}</td>
          <td><span class="badge bg-${getBadgeClass(p.status)}">${p.status}</span></td>
          <td>${p.created_at}</td>
          <td>
            ${getActions(p.status, p.id)}
          </td>
        </tr>`;
    });
  } catch (error) {
    console.error('Failed to load prescriptions:', error);
  }
}

function getBadgeClass(status) {
  return {
    'Pending': 'warning',
    'Approved': 'success',
    'Rejected': 'danger',
    'Cancelled': 'secondary'
  }[status] || 'secondary';
}

function getActions(status, id) {
  if (status === 'Pending') {
    return `
      <button class="btn btn-sm btn-success me-1" onclick="updateStatus(${id}, 'Approved')">Approve</button>
      <button class="btn btn-sm btn-danger" onclick="updateStatus(${id}, 'Rejected')">Reject</button>
    `;
  }
  return `<button class="btn btn-sm btn-secondary me-1" disabled>Approve</button>
          <button class="btn btn-sm btn-danger" onclick="updateStatus(${id}, 'Cancelled')">Cancel</button>`;
}

async function updateStatus(id, status) {
  try {
    const res = await fetch('api/update_prescription_status.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({id, status})
    });
    const result = await res.json();
    if (res.ok) {
      alert(result.message);
      loadPrescriptions(); // refresh after update
    } else {
      alert('Error: ' + result.error);
    }
  } catch (e) {
    alert('Failed to update status');
  }
}

// Load initial data on page load or when sections are shown
document.addEventListener('DOMContentLoaded', () => {
  loadUsers();
  loadPrescriptions();
});



async function loadProducts() {
  const tbody = document.querySelector('#productManagementSection tbody');

  try {
    const response = await fetch('api/get_products.php');
    if (!response.ok) throw new Error('Network response was not ok');

    const products = await response.json();

    tbody.innerHTML = ''; // clear existing rows

    products.forEach(product => {
      tbody.innerHTML += `
        <tr>
          <td>${product.id}</td>
          <td>${product.name}</td>
          <td>${product.description}</td>
          <td>${parseFloat(product.price).toFixed(2)}</td>
          <td>${product.stock_quantity}</td>
          <td>${new Date(product.created_at).toLocaleString()}</td>
          <td>
            <button class="btn btn-sm btn-primary" onclick="editProduct(${product.id})">Edit</button>
            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">Delete</button>
          </td>
        </tr>
      `;
    });
  } catch (error) {
    console.error('Failed to load products:', error);
    tbody.innerHTML = `<tr><td colspan="7">Failed to load products.</td></tr>`;
  }
}

// Call loadProducts on page load
document.addEventListener('DOMContentLoaded', loadProducts);


async function deleteProduct(id) {
  if (!confirm('Are you sure you want to delete this product?')) return;

  try {
    const response = await fetch('api/delete_product.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({id})
    });

    const result = await response.json();
    if (response.ok) {
      alert(result.message);
      loadProducts(); // refresh table
    } else {
      alert('Error: ' + result.error);
    }
  } catch (error) {
    alert('Failed to delete product');
  }
}


// Example product data (replace with backend fetch later)
let products = [
  { id: 1, name: "Vitamin D3", description: "Supports bone health", price: 10.99, stock_quantity: 150, created_at: "2025-07-01" },
  { id: 2, name: "Paracetamol", description: "Pain reliever", price: 5.99, stock_quantity: 200, created_at: "2025-07-05" }
];

// Function to render products in the table
function renderProducts() {
  const tbody = document.getElementById("productTableBody");
  tbody.innerHTML = ""; // Clear existing rows

  products.forEach(product => {
    const tr = document.createElement("tr");

    tr.innerHTML = `
      <td>${product.id}</td>
      <td>${product.name}</td>
      <td>${product.description}</td>
      <td>${product.price.toFixed(2)}</td>
      <td>${product.stock_quantity}</td>
      <td>${product.created_at}</td>
      <td>
        <button class="btn btn-sm btn-warning" onclick="editProduct(${product.id})">Edit</button>
        <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">Delete</button>
      </td>
    `;

    tbody.appendChild(tr);
  });
}

// Handle form submission to add product
document.getElementById("addProductForm").addEventListener("submit", function(e) {
  e.preventDefault();

  // Get form values
  const name = document.getElementById("productName").value.trim();
  const description = document.getElementById("productDescription").value.trim();
  const price = parseFloat(document.getElementById("productPrice").value);
  const stock = parseInt(document.getElementById("productStock").value);

  if (!name || !description || isNaN(price) || isNaN(stock)) {
    displayMessage("Please fill in all fields correctly.", "danger");
    return;
  }

  // Create new product object (mock id and created_at)
  const newProduct = {
    id: products.length ? products[products.length - 1].id + 1 : 1,
    name,
    description,
    price,
    stock_quantity: stock,
    created_at: new Date().toISOString().split("T")[0]
  };

  // Add to products array (replace with backend API call)
  products.push(newProduct);

  // Reset form
  this.reset();

  // Refresh table
  renderProducts();

  displayMessage("Product added successfully!", "success");
});

// Simple message display function
function displayMessage(msg, type) {
  const messageDiv = document.getElementById("addProductMessage");
  messageDiv.textContent = msg;
  messageDiv.className = type === "success" ? "text-success" : "text-danger";
  setTimeout(() => {
    messageDiv.textContent = "";
    messageDiv.className = "";
  }, 3000);
}

// Placeholder functions for Edit/Delete (implement later)
function editProduct(id) {
  alert("Edit product with ID: " + id);
}

function deleteProduct(id) {
  if (confirm("Are you sure you want to delete this product?")) {
    products = products.filter(p => p.id !== id);
    renderProducts();
  }
}

// Initial rendering on page load
renderProducts();


