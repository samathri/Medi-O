    <!-- Header -->
    <header class="medi-o-header-container">
        <nav class="medi-o-navbar navbar navbar-expand-lg">
            <div class="container-fluid medi-o-container-fluid">

                <!-- Logo (left) -->
                <a class="medi-o-navbar-brand navbar-brand" href="index.php">
                    <img src="images/medi-o-logo.svg" alt="Medi-O Logo" class="medi-o-logo logo-h" />
                </a>

                <!-- Hamburger button (right) -->
                <button class="medi-o-navbar-toggler navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#medi-o-navbarMenu" aria-controls="medi-o-navbarMenu" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="medi-o-navbar-toggler-icon navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Menu -->
                <div class="medi-o-navbar-collapse collapse navbar-collapse" id="medi-o-navbarMenu">
                    <div class="w-100 d-lg-flex flex-lg-column align-items-lg-end medi-o-navbar-collapse-inner">

                        <!-- Nav Links -->
                        <ul class="medi-o-navbar-nav navbar-nav flex-lg-row mb-2 gap-3 mb-lg-0">
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="index.php">Home</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="shop.php">Shop</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="about.php">About
                                    Us</a></li>
                            <li class="medi-o-nav-item nav-item"><a class="medi-o-nav-link nav-link"
                                    href="contact.php">Contact
                                    Us</a></li>
                        </ul>

                        <!-- Desktop Icons -->
                        <div class="medi-o-nav-icons nav-icons d-none d-lg-flex gap-5">
                            <a href="#" class="medi-o-nav-icon-link"><i class="bi bi-search"></i></a>
                            <a href="cart.php" class="medi-o-nav-icon-link"><i class="bi bi-cart"></i></a>

                            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                                <div class="dropdown">
                                    <a class="medi-o-nav-icon-link dropdown-toggle text-decoration-none" href="#"
                                        id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i
                                            class="bi bi-person me-1"></i><?php echo htmlspecialchars(mb_strimwidth($_SESSION['user_name'], 0, 10, '..')); ?>

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


                        <div class="medi-o-nav-icons-mobile nav-icons d-lg-none mt-3">
                            <a href="#" class="medi-o-nav-icon-link nav-m"><i class="bi bi-search"></i></a>
                            <a href="cart.php" class="medi-o-nav-icon-link nav-m"><i class="bi bi-cart"></i></a>

                            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])): ?>
                                <div class="dropdown">
                                    <a class="medi-o-nav-icon-link dropdown-toggle text-decoration-none nav-m" href="#"
                                        id="userDropdownMobile" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i
                                            class="bi bi-person me-1"></i><?php echo htmlspecialchars(mb_strimwidth($_SESSION['user_name'], 0, 10, '..')); ?>

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
                <div class="medi-o-search-overlay d-none" id="searchOverlay">
                    <div class="medi-o-search-popup" role="dialog" aria-label="Search popup">
                        <!-- Close Button (aligned to the top-right corner) -->
                        <button type="button" class="btn medi-o-search-close" aria-label="Close search popup"
                            onclick="toggleSearchPopup(false)">
                            &times;
                        </button>

                        <form class="medi-o-search-form-popup position-relative" role="search"
                            aria-label="Popup site search">
                            <input type="search" id="medi-o-search-input-popup"
                                class="form-control medi-o-search-input-popup" placeholder="Search Here....."
                                aria-labelledby="medi-o-search-label-popup" autocomplete="off" />
                            <label id="medi-o-search-label-popup" for="medi-o-search-input-popup"
                                class="visually-hidden">Search</label>

                            <button type="submit" class="btn medi-o-search-btn-popup" aria-label="Submit popup search">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>



            </div>
        </nav>
    </header>