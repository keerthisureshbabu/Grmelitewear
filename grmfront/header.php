<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once('../backend/pages/db.php');

$categories = [];
$error_message = '';

$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cartCount = count($_SESSION['cart']);
}

$is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$current_customer = null;
if ($is_logged_in) {
    $current_customer = [
        'customer_name' => $_SESSION['user_name'] ?? 'User',
        'user_id' => $_SESSION['user_id']
    ];
}

if (isset($conn) && $conn) {
    $categoryQueries = [
        'kids' => 'SELECT id, name FROM kids ORDER BY name',
        'women' => 'SELECT id, name FROM women ORDER BY name',
        'toy' => 'SELECT id, name FROM toy ORDER BY name',
        'accessories' => 'SELECT id, name FROM accessories ORDER BY name'
    ];

    foreach ($categoryQueries as $key => $sql) {
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if ($result) {
                    $categories[$key] = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $categories[$key][] = $row;
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
} else {
    $error_message = "Database connection failed";
}
?>

<!DOCTYPE html>
<html lang="en" class="color-two font-outfit header-border-0 header-style-two">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta name="description" content="Grm Elite Wear - Maternity Wear, Casual Wear, Kids Wear, Kids Products, New Mom Collection, Kids Toys, Born Baby Accessories, New Mom Accessories, Pregent Kit, Hospital kit">
    
    <!-- Title -->
    <title>Grm Elite Wear - Maternity Wear, Casual Wear, Kids Wear, Kids Products, New Mom Collection, Kids Toys, Born Baby Accessories, New Mom Accessories, Pregent Kit, Hospital kit</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/grm logo.png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- select 2 -->
    <link rel="stylesheet" href="assets/css/select2.min.css">
    <!-- Slick -->
    <link rel="stylesheet" href="assets/css/slick.css">
    <!-- Jquery Ui -->
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <!-- animate -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!-- AOS Animation -->
    <link rel="stylesheet" href="assets/css/aos.css">
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/main.css">
</head> 

<body>

    <div class="overlay"></div>
    <!--==================== Overlay End ====================-->

    <!--==================== Sidebar Overlay End ====================-->
    <div class="side-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->

    <!-- ==================== Scroll to Top End Here ==================== -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- ==================== Scroll to Top End Here ==================== -->

    <!-- ==================== Search Box Start Here ==================== -->
    <form action="search_direct.php" class="search-box">
        <button type="button" class="search-box__close position-absolute inset-block-start-0 inset-inline-end-0 m-16 w-48 h-48 border border-gray-100 rounded-circle flex-center text-white hover-text-gray-800 hover-bg-white text-2xl transition-1">
            <i class="ph ph-x"></i>
        </button>
        <div class="container">
            <div class="position-relative">
                <input type="text" class="form-control py-16 px-24 text-xl rounded-pill pe-64" placeholder="Search for a product or brand" id="searchInput">
                <button type="submit" class="w-48 h-48 bg-main-600 rounded-circle flex-center text-xl text-white position-absolute top-50 translate-middle-y inset-inline-end-0 me-8">
                    <i class="ph ph-magnifying-glass"></i>
                </button>
                <!-- Search Results Container -->
                <div id="searchResults" class="search-results-container"></div>
            </div>
        </div>
    </form>
    <!-- ==================== Search Box End Here ==================== -->

    <!-- ==================== Mobile Menu Start Here ==================== -->
    <div class="mobile-menu scroll-sm d-lg-none d-block">
        <button type="button" class="close-button"> <i class="ph ph-x"></i> </button>
        <div class="mobile-menu__inner">
            <a href="index.php" class="mobile-menu__logo">
                <img src="assets/images/logo/grm logo.png" alt="Logo">
            </a>
            <div class="mobile-menu__menu">
                <!-- Nav Menu Start -->
                <ul class="nav-menu flex-align nav-menu--mobile">
                    <li class="nav-menu__item">
                        <a href="index.php" class="nav-menu__link text-heading-two">Home</a>
                    </li>
                    <li class="on-hover-item nav-menu__item has-submenu">
                        <a href="javascript:void(0)" class="nav-menu__link text-heading-two">Kids</a>
                        <ul class="on-hover-dropdown common-dropdown nav-submenu scroll-sm">
                            <?php if (isset($categories['kids']) && !empty($categories['kids'])): ?>
                                <?php foreach ($categories['kids'] as $cat): ?>
                                    <li class="mb-24">
                                        <a href="shop.php?type=kids&cat=<?= htmlspecialchars($cat['id']); ?>" class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100">
                                            <?= htmlspecialchars($cat['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="mb-24"><span class="text-muted">No categories available</span></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <li class="on-hover-item nav-menu__item has-submenu">
                        <a href="javascript:void(0)" class="nav-menu__link text-heading-two">Women's</a>
                        <ul class="on-hover-dropdown common-dropdown nav-submenu scroll-sm">
                            <?php if (isset($categories['women']) && !empty($categories['women'])): ?>
                                <?php foreach ($categories['women'] as $cat): ?>
                                    <li class="mb-24">
                                        <a href="shop.php?type=women&cat=<?= htmlspecialchars($cat['id']); ?>" class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100">
                                            <?= htmlspecialchars($cat['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="mb-24"><span class="text-muted">No categories available</span></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <li class="on-hover-item nav-menu__item has-submenu">
                        <a href="javascript:void(0)" class="nav-menu__link text-heading-two">Toys</a>
                        <ul class="on-hover-dropdown common-dropdown nav-submenu scroll-sm">
                            <?php if (isset($categories['toy']) && !empty($categories['toy'])): ?>
                                <?php foreach ($categories['toy'] as $cat): ?>
                                    <li class="mb-24">
                                        <a href="toys.php?type=toy&cat=<?= htmlspecialchars($cat['id']); ?>" class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100">
                                            <?= htmlspecialchars($cat['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="mb-24"><span class="text-muted">No categories available</span></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <li class="on-hover-item nav-menu__item has-submenu">
                        <a href="javascript:void(0)" class="nav-menu__link text-heading-two">Accessories</a>
                        <ul class="on-hover-dropdown common-dropdown nav-submenu scroll-sm">
                            <?php if (isset($categories['accessories']) && !empty($categories['accessories'])): ?>
                                <?php foreach ($categories['accessories'] as $cat): ?>
                                    <li class="mb-24">
                                        <a href="accessories.php?type=accessories&cat=<?= htmlspecialchars($cat['id']); ?>" class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100">
                                            <?= htmlspecialchars($cat['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="mb-24"><span class="text-muted">No categories available</span></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <li class="nav-menu__item">
                        <a href="contact.php" class="nav-menu__link text-heading-two">Contact Us</a>
                    </li>
                </ul>
                <!-- Nav Menu End -->
            </div>
        </div>
    </div>
    <!-- ==================== Mobile Menu End Here ==================== -->

    <!-- ======================= Middle Header Two Start ========================= -->
    <div class="header-top bg-main-900 flex-between">
        <div class="container container-lg">
            <div class="flex-between flex-wrap gap-8">
                <ul class="header-top__right flex-align flex-wrap gap-16 w-auto">
                    <li class="d-lg-flex d-none">
                        <a href="#" class="text-white text-sm d-flex align-items-center gap-4 hover-text-decoration-underline">
                            <img src="assets/images/icon/track-icon.png" alt="Track Icon">
                            <span class="">3-5 Days Delivery within Tamilnadu</span>
                        </a>
                    </li>
                </ul>

                <ul class="flex-align flex-wrap d-none d-xl-flex">
                    <li class="border-right-item pe-12 me-12">
                        <span class="text-white text-sm">
                            Buy 3 pcs Get 1 free
                            <span class="text-yellow">Rs. 1000/- Only</span>
                        </span>
                    </li>
                    <li class="border-right-item pe-12 me-12">
                        <a href="track.php" class="text-white text-sm d-flex align-items-center gap-4 hover-text-decoration-underline">
                            <img src="assets/images/icon/track-icon.png" alt="Track Icon">
                            <span class="">Track Your Order</span>
                        </a>
                    </li>
                </ul>

                <ul class="header-top__right flex-align flex-wrap gap-16 w-auto">
                    <li class="d-lg-flex d-none">
                        <a href="#" class="text-white text-sm d-flex align-items-center gap-4 hover-text-decoration-underline">
                            <img src="assets/images/icon/track-icon.png" alt="Track Icon">
                            <span class="">7-9 Days Delivery for Other States</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <header class="header-middle border-bottom border-neutral-40 py-4">
        <div class="container container-lg">
            <nav class="header-inner flex-between gap-8">
                <!-- Logo Start -->
                <div class="logo">
                    <a href="index.php" class="link">
                        <img src="assets/images/logo/grm logo.png" alt="Logo" width="150" height="30" class="logo__image">
                    </a>
                </div>
                <!-- Logo End  -->

                <!-- Menu Start  -->
                <div class="header-menu d-lg-block d-none">
                    <!-- Nav Menu Start -->
                    <ul class="nav-menu flex-align">
                        <li class="nav-menu__item">
                            <a href="index.php" class="nav-menu__link text-heading-two">Home</a>
                        </li>
                        <li class="on-hover-item nav-menu__item has-submenu">
                            <a href="javascript:void(0)" class="nav-menu__link text-heading-two">Kid's</a>
                            <ul class="on-hover-dropdown common-dropdown nav-submenu scroll-sm">
                                <?php if (isset($categories['kids']) && !empty($categories['kids'])): ?>
                                    <?php foreach ($categories['kids'] as $cat): ?>
                                        <li class="mb-24">
                                            <a href="shop.php?type=kids&cat=<?= htmlspecialchars($cat['id']); ?>" class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100">
                                                <?= htmlspecialchars($cat['name']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="mb-24"><span class="text-muted">No categories available</span></li>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="on-hover-item nav-menu__item has-submenu">
                            <a href="javascript:void(0)" class="nav-menu__link text-heading-two">Women's</a>
                            <ul class="on-hover-dropdown common-dropdown nav-submenu scroll-sm">
                                <?php if (isset($categories['women']) && !empty($categories['women'])): ?>
                                    <?php foreach ($categories['women'] as $cat): ?>
                                        <li class="mb-24">
                                            <a href="shop.php?type=women&cat=<?= htmlspecialchars($cat['id']); ?>" class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100">
                                                <?= htmlspecialchars($cat['name']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="mb-24"><span class="text-muted">No categories available</span></li>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="on-hover-item nav-menu__item has-submenu">
                            <a href="javascript:void(0)" class="nav-menu__link text-heading-two">Toy's</a>
                            <ul class="on-hover-dropdown common-dropdown nav-submenu scroll-sm">
                                <?php if (isset($categories['toy']) && !empty($categories['toy'])): ?>
                                    <?php foreach ($categories['toy'] as $cat): ?>
                                        <li class="mb-24">
                                            <a href="toys.php?type=toy&cat=<?= htmlspecialchars($cat['id']); ?>" class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100">
                                                <?= htmlspecialchars($cat['name']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="mb-24"><span class="text-muted">No categories available</span></li>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="on-hover-item nav-menu__item has-submenu">
                            <a href="javascript:void(0)" class="nav-menu__link text-heading-two">Accessories</a>
                            <ul class="on-hover-dropdown common-dropdown nav-submenu scroll-sm">
                                <?php if (isset($categories['accessories']) && !empty($categories['accessories'])): ?>
                                    <?php foreach ($categories['accessories'] as $cat): ?>
                                        <li class="mb-24">
                                            <a href="accessories.php?type=accessories&cat=<?= htmlspecialchars($cat['id']); ?>" class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100">
                                                <?= htmlspecialchars($cat['name']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="mb-24"><span class="text-muted">No categories available</span></li>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <li class="nav-menu__item">
                            <a href="contact.php" class="nav-menu__link text-heading-two">Contact Us</a>
                        </li>
                    </ul>
                    <!-- Nav Menu End -->
                </div>
                <!-- Menu End  -->

                <!-- Middle Header Right start -->
                <div class="header-right flex-align">
                    <!-- Dropdown Select Start -->
                    <ul class="header-top__right style-two style-three flex-align flex-wrap">
                        <li class="d-sm-flex d-none">
                            <a href="track.php" class="selected-text selected-text text-neutral-500 fw-semibold text-sm py-8 text-sm py-8 hover-text-heading"> Track Your Order</a>
                        </li>
                    </ul>
                    <!-- Dropdown Select End -->
                    <button type="button" class="toggle-mobileMenu d-lg-none ms-3n text-gray-800 text-4xl d-flex"> <i class="ph ph-list"></i> </button>
                </div>
                <!-- Middle Header Right End  -->
            </nav>
        </div>
    </header>
    <!-- ======================= Middle Header Two End ========================= -->

    <!-- ==================== Header Two Start Here ==================== -->
    <header class="header bg-white pt-24">
        <div class="container container-lg">
            <nav class="header-inner d-flex justify-content-between gap-16">
                <div class="d-flex w-100">
                    <!-- Category Dropdown Start -->
                    <div class="category-two h-100 d-none flex-shrink-0">
                        <button type="button" class="category__button flex-align gap-8 fw-medium bg-main-two-600 py-16 px-20 text-white h-100 md-rounded-top">
                            <span class="icon text-2xl d-md-flex d-none"><i class="ph ph-squares-four"></i></span>
                            <span class="d-lg-flex d-none">All</span> Categories
                            <span class="arrow-icon text-md d-flex ms-auto"><i class="ph ph-caret-down"></i></span>
                        </button>

                        <div class="responsive-dropdown common-dropdown d-lg-none d-block nav-submenu p-0 submenus-submenu-wrapper shadow-none border border-gray-100">
                            <button type="button" class="close-responsive-dropdown rounded-circle text-xl position-absolute inset-inline-end-0 inset-block-start-0 mt-4 me-8 d-lg-none d-flex"> <i class="ph ph-x"></i> </button>
                            <div class="logo px-16 d-lg-none d-block">
                                <a href="index.php" class="link">
                                    <img src="assets/images/logo/grm logo.png" alt="Logo">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="category d-block on-hover-item text-white flex-shrink-0 w-310">
                        <button type="button" class="category__button flex-align gap-8 fw-medium p-16 bg-main-600 text-white rounded-top h-100 w-100">
                            <span class="icon text-2xl d-md-flex d-none"><i class="ph ph-squares-four"></i></span>
                            <span class="d-sm-flex d-none">All</span> Categories
                            <span class="arrow-icon text-xl d-flex ms-auto"><i class="ph ph-caret-down"></i></span>
                        </button>

                        <div class="responsive-dropdown on-hover-dropdown common-dropdown nav-submenu p-0 submenus-submenu-wrapper">
                            <button type="button" class="close-responsive-dropdown rounded-circle text-xl position-absolute inset-inline-end-0 inset-block-start-0 mt-4 me-8 d-lg-none d-flex"> <i class="ph ph-x"></i> </button>
                            <div class="logo px-16 d-lg-none d-block">
                                <a href="index.php" class="link">
                                    <img src="assets/images/logo/grm logo.png" alt="Logo">
                                </a>
                            </div>
                            <ul class="scroll-sm p-0 py-8 w-300 max-h-400 overflow-y-auto">
                                <?php if (isset($categories['women']) && !empty($categories['women'])): ?>
                                    <?php foreach ($categories['women'] as $cat): ?>
                                        <li class="has-submenus-submenu">
                                            <a href="shop.php?type=women&cat=<?= htmlspecialchars($cat['id']); ?>" class="text-gray-500 text-15 py-12 px-16 flex-align gap-8 rounded-0">
                                                <span class="text-xl d-flex"><i class="ph ph-dress"></i></span>
                                                <span><?= htmlspecialchars($cat['name']); ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="has-submenus-submenu">
                                        <span class="text-gray-500 text-15 py-12 px-16">No categories available</span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- Category Dropdown End  -->

                    <!-- Search Start  -->
                    <form action="search_direct.php" method="GET" class="position-relative ms-20 max-w-870 w-100 d-md-block d-none">
                        <input type="text" name="query" class="form-control fw-medium placeholder-italic shadow-none bg-neutral-30 placeholder-fw-medium placeholder-light py-16 ps-30 pe-60" placeholder="Search for products, categories or brands...">
                        <button type="submit" class="position-absolute top-50 translate-middle-y text-main-600 end-0 me-36 text-xl line-height-1">
                            <i class="ph-bold ph-magnifying-glass"></i>
                        </button>
                    </form>
                    <!-- Search End  -->
                </div>

                <!-- Header Middle Right start -->
                <div class="d-flex align-items-center gap-20-px flex-shrink-0">
                    <a href="cart.php" class="flex-align gap-6 item-hover">
                        <span class="text-2xl text-heading d-flex position-relative me-6 mt-6 item-hover__text">
                            <i class="ph-bold ph-shopping-cart"></i>
                            <span class="w-18 h-18 flex-center rounded-circle bg-danger text-white text-xs position-absolute top-n6 end-n4"><?= $cartCount; ?></span>
                        </span>
                        <span class="text-md text-neutral-500 item-hover__text fw-medium d-none d-lg-flex">Cart</span>
                    </a>
                    <?php if ($is_logged_in): ?>
                        <div class="dropdown">
                            <button class="d-flex align-content-around gap-10 fw-medium text-main-600 py-14 px-24 bg-main-50 rounded-pill line-height-1 hover-bg-main-600 hover-text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-sm-flex d-none line-height-1"><i class="ph-bold ph-user"></i></span>
                                <?= htmlspecialchars($current_customer['customer_name']); ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="customer_dashboard.php">Dashboard</a></li>
                                <li><a class="dropdown-item" href="account.php?logout=1">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="account.php" class="d-flex align-content-around gap-10 fw-medium text-main-600 py-14 px-24 bg-main-50 rounded-pill line-height-1 hover-bg-main-600 hover-text-white">
                            <span class="d-sm-flex d-none line-height-1"><i class="ph-bold ph-user"></i></span>
                            Account
                        </a>
                    <?php endif; ?>
                </div>
                <!-- Header Middle Right End  -->
            </nav>
        </div>
    </header>
    <!-- ==================== Header Two End ========================= -->

    <!-- Include Search JavaScript -->
    <script src="assets/js/search.js"></script>