<!-- Header -->
<header class="medi-o-header-container">
    <nav class="medi-o-navbar navbar navbar-expand-lg">
        <div class="container-fluid medi-o-container-fluid">

            <!-- Logo (left) -->
            <a class="medi-o-navbar-brand navbar-brand" href="index.php">
                <img src="images/medi-o-logo.svg" alt="Medi-O Logo" class="medi-o-logo logo-h" />
            </a>

            <!-- Hamburger button (right) -->
            <button class="medi-o-navbar-toggler navbar-toggler" style="width: fit-content;" type="button" data-bs-toggle="collapse"
                data-bs-target="#medi-o-navbarMenu" aria-controls="medi-o-navbarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="medi-o-navbar-toggler-icon navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="medi-o-navbar-collapse collapse navbar-collapse" id="medi-o-navbarMenu">
                <div class="w-100 d-lg-flex flex-lg-column align-items-lg-end medi-o-navbar-collapse-inner">

                    <!-- Nav Links -->
                    <ul class="medi-o-navbar-nav navbar-nav flex-lg-row mb-2 gap-3 mb-lg-0">
                        <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="index.php">Home</a></li>
                        <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="shop.php">Shop</a></li>
                        <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="about.php">About Us</a></li>
                        <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link" href="contact.php">Contact Us</a></li>
                    </ul>

                    <!-- Desktop Icons -->
                    <div class="medi-o-nav-icons nav-icons d-none d-lg-flex gap-5">
                        <a href="#" class="medi-o-nav-icon-link" id="openSearchDesktop"><i class="bi bi-search"></i></a>
                        <a href="cart.php" class="medi-o-nav-icon-link"><i class="bi bi-cart"></i></a>

                        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                            <div class="dropdown">
                                <a class="medi-o-nav-icon-link dropdown-toggle text-decoration-none" href="#"
                                    id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person me-1"></i><?php echo htmlspecialchars(mb_strimwidth($_SESSION['user_name'], 0, 10, '..')); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="login.php" class="medi-o-nav-icon-link"><i class="bi bi-person"></i></a>
                        <?php endif; ?>
                    </div>

                    <!-- Mobile Icons -->
                    <div class="medi-o-nav-icons-mobile nav-icons d-lg-none mt-3">
                        <a href="#" class="medi-o-nav-icon-link nav-m" id="openSearchMobile"><i class="bi bi-search"></i></a>
                        <a href="cart.php" class="medi-o-nav-icon-link nav-m"><i class="bi bi-cart"></i></a>

                        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                            <div class="dropdown">
                                <a class="medi-o-nav-icon-link dropdown-toggle text-decoration-none nav-m" href="#"
                                    id="userDropdownMobile" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-person me-1"></i><?php echo htmlspecialchars(mb_strimwidth($_SESSION['user_name'], 0, 10, '..')); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdownMobile">
                                    <li><a class="dropdown-item nav-m" href="profile.php">Profile</a></li>
                                    <li><a class="dropdown-item text-danger nav-m" href="logout.php">Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="login.php" class="medi-o-nav-icon-link nav-m"><i class="bi bi-person"></i></a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <!-- Search Popup Overlay -->
            <div class="medi-o-search-overlay d-none" id="searchOverlay" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="searchLabel">
                <div class="medi-o-search-popup" role="document" id="searchPopup">

                    <form
                        class="medi-o-search-form-popup position-relative"
                        role="search"
                        aria-label="Popup site search"
                        method="GET"
                        action="shop.php"
                    >
                        <input
                            type="search"
                            id="medi-o-search-input-popup"
                            name="search"
                            class="form-control medi-o-search-input-popup"
                            placeholder="Search Here....."
                            aria-labelledby="searchLabel"
                            autocomplete="off"
                        />
                        <label id="searchLabel" class="visually-hidden" for="medi-o-search-input-popup">Search</label>

                        <button
                            type="submit"
                            style="width: fit-content;"
                            class="btn medi-o-search-btn-popup"
                            aria-label="Submit popup search"
                        >
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </nav>
</header>

<!-- Add this JS at the bottom of your page or in your js/script.js -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const searchOverlay = document.getElementById('searchOverlay');
        const searchPopup = document.getElementById('searchPopup');
        const openSearchDesktop = document.getElementById('openSearchDesktop');
        const openSearchMobile = document.getElementById('openSearchMobile');

        function openSearch() {
            searchOverlay.classList.remove('d-none');
            document.getElementById('medi-o-search-input-popup').focus();
        }

        function closeSearch() {
            searchOverlay.classList.add('d-none');
            document.getElementById('medi-o-search-input-popup').value = '';
        }

        openSearchDesktop?.addEventListener('click', e => {
            e.preventDefault();
            openSearch();
        });

        openSearchMobile?.addEventListener('click', e => {
            e.preventDefault();
            openSearch();
        });

        searchOverlay.addEventListener('click', e => {
            if (!searchPopup.contains(e.target)) {
                closeSearch();
            }
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && !searchOverlay.classList.contains('d-none')) {
                closeSearch();
            }
        });

        // Optional: close popup on form submit (before page redirects)
        const searchForm = document.querySelector('.medi-o-search-form-popup');
        searchForm.addEventListener('submit', () => {
            closeSearch();
        });
    });
</script>

<!-- Add minimal CSS if needed -->
<style>
    .medi-o-search-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 1050;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .medi-o-search-popup {
        background: #fff;
        padding: 1.5rem;
        border-radius: 6px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
</style>
