
<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">
    
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

        <!-- Meta Data -->
		<meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="Description" content="GRM SHOP - Admin panel &amp; Order received and products add">
        <meta name="Author" content="Grm shop">
        
        <!-- TITLE -->
		<title>GRM SHOP - Admin Page</title>

        <!-- FAVICON -->
        <link rel="icon" href="../assets/images/logo/logo.jpg" type="image/x-icon">

        <!-- BOOTSTRAP CSS -->
	    <link  id="style" href="../assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- STYLES CSS -->
        <link href="../assets/css/styles.css" rel="stylesheet">
        
        <!-- ICONS CSS -->
        <link href="../assets/css/icons.css" rel="stylesheet">

        
        <!-- NODE WAVES CSS -->
        <link href="../assets/libs/node-waves/waves.min.css" rel="stylesheet"> 

        <!-- SIMPLEBAR CSS -->
        <link rel="stylesheet" href="../assets/libs/simplebar/simplebar.min.css">

        <!-- PICKER CSS -->
        <link rel="stylesheet" href="../assets/libs/flatpickr/flatpickr.min.css">
        <link rel="stylesheet" href="../assets/libs/%40simonwep/pickr/themes/nano.min.css">

        <!-- AUTO COMPLETE CSS -->
        <link rel="stylesheet" href="../assets/libs/%40tarekraafat/autocomplete.js/css/autoComplete.css">
        
        <!-- CHOICES CSS -->
        <link rel="stylesheet" href="../assets/libs/choices.js/public/assets/styles/choices.min.css">

        <!-- CHOICES JS -->
        <script src="../assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
        
        <!-- MAIN JS -->
        <script src="../assets/js/main.js"></script>

        <!-- ...Remixcode CSS... -->
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
        <!-- ...Remixcode CSS... -->

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        

	</head>

    <body>
        <!-- LOADER -->
        <div id="loader">
            <img src="../assets/images/logo/logo.jpg" alt="">
        </div>
        <!-- END LOADER -->

        <!-- PAGE -->
        <div class="page">

            <!-- HEADER -->
            
            <header class="app-header">

                <!-- Start::main-header-container -->
                <div class="main-header-container container-fluid">

                    <!-- Start::header-content-left -->
                    <div class="header-content-left">

                        <!-- Start::header-element -->
                        <div class="header-element">
                            <div class="horizontal-logo">
                                <a href="index.php" class="header-logo">
                                    <img src="../assets/images/logo/logo.jpg" alt="logo" class="desktop-logo">
                                    <img src="../assets/images/logo/logo.jpg" alt="logo" class="toggle-logo">
                                    <img src="../assets/images/logo/logo.jpg" alt="logo" class="desktop-white">
                                    <img src="../assets/images/logo/logo.jpg" alt="logo" class="toggle-white">
                                </a>
                            </div>
                        </div>
                        <!-- End::header-element -->

                        <!-- Start::header-element -->
                        <div class="header-element mx-lg-0 mx-2">
                            <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                        </div>
                        <!-- End::header-element -->

                        <!-- Start::header-element -->
                        <div class="header-element header-search d-lg-block d-none my-auto auto-complete-search">
                            <!-- Start::header-link -->
                            <input type="text" class="header-search-bar form-control rounded-pill" id="header-search" placeholder="Search for Results..." spellcheck=false autocomplete="off" autocapitalize="off">
                            <a href="javascript:void(0);" class="header-search-icon border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            </a>
                            <!-- End::header-link -->
                        </div>
                        
                        <!-- End::header-element -->

                    </div>
                    <!-- End::header-content-left -->

                    <!-- Start::header-content-right -->
                    <div class="header-content-right">

                        <!-- Start::header-element -->
                        <div class="header-element d-lg-none d-flex">
                            <a href="javascript:void(0);" class="header-link" data-bs-toggle="modal" data-bs-target="#responsive-searchModal">
                                <!-- Start::header-link-icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                                <!-- End::header-link-icon -->
                            </a>  
                        </div>
                        <!-- End::header-element -->

                        
                        <!-- Start::header-element -->
                        <div class="header-element dropdown">
                            <!-- Start::header-link|dropdown-toggle -->
                            <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <span class="avatar avatar-sm avatar-rounded">
                                    <img src="../assets/images/logo/user.png" alt="img" class="img-fluid">
                                </span>
                            </a>
                            <!-- End::header-link|dropdown-toggle -->
                            <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                                <li class="p-3 bg-light bg-opacity-75 border-bottom">
                                    <div class="d-flex align-items-center justify-content-between gap-4">
                                        <div>
                                            <p class="mb-0 fw-semibold lh-1">Grmelitewear</p>
                                        </div>
                                    </div>
                                </li>
                                <li><a class="dropdown-item d-flex align-items-center" href="login.php"><i class="ti ti-logout fs-18 me-2 text-gray fw-normal"></i>Sign Out</a></li>
                            </ul>
                        </div>  
                        <!-- End::header-element -->

                    </div>
                    <!-- End::header-content-right -->

                </div>
                <!-- End::main-header-container -->

            </header>

            <div class="modal fade" id="responsive-searchModal" tabindex="-1" aria-labelledby="responsive-searchModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="input-group">
                                <input type="text" class="form-control border-end-0" placeholder="Search Anything ..."
                                    aria-label="Search Anything ..." aria-describedby="button-addon2">
                                <button class="btn btn-primary" type="button"
                                    id="button-addon2"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END HEADER -->

            <!-- SIDEBAR -->
            
            <aside class="app-sidebar sticky" id="sidebar">

                <!-- Start::main-sidebar-header -->
                <div class="main-sidebar-header">
                    <a href="index.php" class="header-logo">
                        <img src="../assets/images/logo/grm logo.png" alt="logo" class="desktop-logo">
                        <img src="../assets/images/logo/grm logo.png" alt="logo" class="toggle-logo">
                        <img src="../assets/images/logo/grm logo.png" alt="logo" class="desktop-white">
                        <img src="../assets/images/logo/grm logo.png" alt="logo" class="toggle-white">
                    </a>
                </div>
                <!-- End::main-sidebar-header -->

                <!-- Start::main-sidebar -->
                <div class="main-sidebar" id="sidebar-scroll">

                    <!-- Start::nav -->
                    <nav class="main-menu-container nav nav-pills flex-column sub-open">
                        <div class="slide-left" id="slide-left">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#0d0d0eff" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
                        </div>
                        <ul class="main-menu">
                            <!-- Start::slide__category -->
                            <li class="slide__category"><span class="category-name">Home</span></li>
                            <!-- End::slide__category -->

                            <!-- Start::slide -->
                            <li class="slide">
                                <a href="index.php" class="side-menu__item">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg>
                                    <span class="side-menu__label">Dashboard</span>
                                </a>
                            </li>
                            <!-- End::slide -->

                            <!-- Start::slide__category -->
                            <li class="slide__category"><span class="category-name">Products</span></li>
                            <!-- End::slide__category -->

                            <!-- Start::slide -->
                            <li class="slide has-sub">
                                <a href="javascript:void(0);" class="side-menu__item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M13 4H6v16h12V9h-5V4zm3 14H8v-2h8v2zm0-6v2H8v-2h8z" opacity=".3"/><path d="M8 16h8v2H8zm0-4h8v2H8zm6-10H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
                                    <span class="side-menu__label">Category</span>
                                    <i class="ri-arrow-right-s-line side-menu__angle"></i>
                                </a>
                                <ul class="slide-menu child1">
                                     <li class="slide">
                                        <a href="kids.php" class="side-menu__item">Kids</a>
                                    </li>
                                     <li class="slide">
                                        <a href="women.php" class="side-menu__item">Women's</a>
                                    </li>
                                    <li class="slide">
                                        <a href="toy.php" class="side-menu__item">Toy's</a>
                                    </li>
                                      <li class="slide">
                                        <a href="accessories.php" class="side-menu__item">Accessories</a>
                                    </li>

                                </ul>
                            </li>
                            <!-- End::slide -->

                            <!-- Start::slide -->
                            <li class="slide has-sub">
                                <a href="javascript:void(0);" class="side-menu__item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368"><g fill="none"><path d="M0 0h24v24H0V0z"/><path d="M0 0h24v24H0V0z" opacity=".87"/></g><path d="M6 20h12V10H6v10zm6-7c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" opacity=".3"/><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg>
                                    <span class="side-menu__label">Products</span>
                                    <i class="ri-arrow-right-s-line side-menu__angle"></i>
                                </a>
                                <ul class="slide-menu child1">
                                    <li class="slide side-menu__label1">
                                        <a href="javascript:void(0)">Products</a>
                                    </li>
                                    <li class="slide has-sub">
                                        <a href="javascript:void(0);" class="side-menu__item">Kids
                                            <i class="ri-arrow-right-s-line side-menu__angle"></i></a>
                                        <ul class="slide-menu child2">
                                            <li class="slide">
                                                <a href="add_kids_product.php" class="side-menu__item">Add</a>
                                            </li>
                                            <li class="slide">
                                                <a href="view_kids_products.php" class="side-menu__item">View</a>
                                            </li>
                                        </ul>
                                    </li>      
                                    <li class="slide has-sub">
                                        <a href="javascript:void(0);" class="side-menu__item">Women's
                                            <i class="ri-arrow-right-s-line side-menu__angle"></i></a>
                                        <ul class="slide-menu child2">
                                            <li class="slide">
                                                <a href="add_womens_products.php" class="side-menu__item">Add</a>
                                            </li>
                                            <li class="slide">
                                                <a href="view_womens_products.php" class="side-menu__item">View</a>
                                            </li>
                                        </ul>
                                    </li>     
                                    <li class="slide has-sub">
                                        <a href="javascript:void(0);" class="side-menu__item">Toy's
                                            <i class="ri-arrow-right-s-line side-menu__angle"></i></a>
                                        <ul class="slide-menu child2">
                                            <li class="slide">
                                                <a href="add_toys_products.php" class="side-menu__item">Add</a>
                                            </li>
                                            <li class="slide">
                                                <a href="view_toys_products.php" class="side-menu__item">View</a>
                                            </li>
                                        </ul>
                                    </li>     
                                    <li class="slide has-sub">
                                        <a href="javascript:void(0);" class="side-menu__item">Accessories
                                            <i class="ri-arrow-right-s-line side-menu__angle"></i></a>
                                        <ul class="slide-menu child2">
                                            <li class="slide">
                                                <a href="add_accessories_products.php" class="side-menu__item">Add</a>
                                            </li>
                                            <li class="slide">
                                                <a href="view_accessories_products.php" class="side-menu__item">View</a>
                                            </li>
                                        </ul>
                                    </li>  
                                </ul>
                            </li>
                            <!-- End::slide -->

                            <!-- Start::slide__category -->
                            <li class="slide__category"><span class="category-name">Orders</span></li>
                            <!-- End::slide__category -->

                            <!-- Start::slide -->
                            <li class="slide has-sub">
                                <a href="javascript:void(0);" class="side-menu__item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368"><g><rect fill="none" height="24" width="24"/></g><g><g><rect height="4" opacity=".3" width="4" x="5" y="5"/><rect height="4" opacity=".3" width="4" x="5" y="15"/><rect height="4" opacity=".3" width="4" x="15" y="15"/><rect height="4" opacity=".3" width="4" x="15" y="5"/><path d="M3,21h8v-8H3V21z M5,15h4v4H5V15z"/><path d="M3,11h8V3H3V11z M5,5h4v4H5V5z"/><path d="M13,21h8v-8h-8V21z M15,15h4v4h-4V15z"/><path d="M13,3v8h8V3H13z M19,9h-4V5h4V9z"/></g></g></svg>
                                    <span class="side-menu__label">Orders</span>
                                    <i class="ri-arrow-right-s-line side-menu__angle"></i>
                                </a>
                                <ul class="slide-menu child1">
                                    <li class="slide">
                                        <a href="pending.php" class="side-menu__item">Pending</a>
                                    </li>

                                    <li class="slide">
                                        <a href="readytoship.php" class="side-menu__item">Ready to Ship</a>
                                    </li>
                                    <li class="slide">
                                        <a href="shipped.php" class="side-menu__item">Shipped</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- End::slide -->

                            <!-- Start::slide__category -->
                            <li class="slide__category"><span class="category-name">Analytics</span></li>
                            <!-- End::slide__category -->

                            <!-- Start::slide -->
                            <li class="slide">
                                <a href="todayorderanalysis.php" class="side-menu__item">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5v14h14V5H5zm4 12H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3"/><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 10h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg>
                                   <span class="side-menu__label">Orders</span>
                                </a>
                            </li>
                            <!-- End::slide -->

                            <!-- Start::slide -->
                            <li class="slide">
                                <a href="products.php" class="side-menu__item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h15v3H5zm12 5h3v9h-3zm-7 0h5v9h-5zm-5 0h3v9H5z" opacity=".3"/><path d="M20 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h15c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM8 19H5v-9h3v9zm7 0h-5v-9h5v9zm5 0h-3v-9h3v9zm0-11H5V5h15v3z"/></svg>
                                    <span class="side-menu__label">Products</span>
                                </a>
                            </li>
                            <!-- End::slide -->

                            <!-- Start::slide -->
                            <li class="slide">
                                <a href="newcustomer.php" class="side-menu__item">
                                    <!-- ...existing code... -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368">
                                    <path d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
                                    </svg>
                                    <!-- ...existing code... -->
                                    <span class="side-menu__label">Customers</span>
                                </a>
                            </li>
                            <!-- End::slide -->

                           <li class="slide__category"><span class="category-name">Tracking Updation</span></li>
                            <!-- End::slide__category -->

                            <!-- Start::slide -->
                            <li class="slide has-sub">
                                <a href="javascript:void(0);" class="side-menu__item">
                                    <!-- Main Tracking Icon -->
                                    <i class="ri-truck-line side-menu__icon"></i>
                                    <span class="side-menu__label">Tracking Number</span>
                                    <i class="ri-arrow-right-s-line side-menu__angle"></i>
                                </a>
                                <ul class="slide-menu child1">
                                    <li class="slide">
                                        <a href="stcourier.php" class="side-menu__item">
                                            ST Courier
                                        </a>
                                    </li>

                                    <li class="slide">
                                        <a href="indianpost.php" class="side-menu__item">
                                            Indian Post
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
                    </nav>
                    <!-- End::nav -->

                </div>
                <!-- End::main-sidebar -->

            </aside>
            <!-- END SIDEBAR -->