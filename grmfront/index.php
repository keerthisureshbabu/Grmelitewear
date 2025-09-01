<?php
include("header.php");
include('../backend/pages/db.php');

// Define category labels, product table, and variation type
$categories = [
    "Feeding Normal Sleeve" => ['table' => 'womens_products', 'variation_type' => 'women'],
    "Feeding Puff Sleeve" => ['table' => 'womens_products', 'variation_type' => 'women'],
    "Non-Feeding Sleeve" => ['table' => 'womens_products', 'variation_type' => 'women'],
    "Kids Co-ord Sets" => ['table' => 'kids_products', 'variation_type' => 'kids'],
    "Kids Frock" => ['table' => 'kids_products', 'variation_type' => 'kids'],
    "Hospital Bag" => ['table' => 'accessories_products', 'variation_type' => 'accessories'],
    "Women's Co-ord Sets" => ['table' => 'womens_products', 'variation_type' => 'women']
];

// Loop and fetch counts using JOIN
foreach ($categories as $label => &$info) {
    $table = $info['table'];
    $variation_type = $info['variation_type'];
    $variation_table = $variation_type . ''; 

    $sql = "
        SELECT COUNT(*) AS count 
        FROM `$table` 
        INNER JOIN `$variation_table` ON `$table`.variation_id = `$variation_table`.id 
        WHERE `$variation_table`.name = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $label);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $info['count'] = $row ? $row['count'] : 0;
    $stmt->close();
}
?>




        <!-- ==================== Header End Here ==================== --><!-- ==================================== Banner Three Start =================================== -->
            <section class="banner-three bg-img position-relative" data-background-image="assets/images/shape/line-pattern.png">
                <img src="assets/images/shape/star-shape.png" alt="Shape" class="animation star-shape animation-rotate">
                <img src="assets/images/shape/star-shape.png" alt="Shape" class="animation star-shape style-two animation-rotate">
                <img src="assets/images/shape/line-shape.png" alt="Shape" class="animation line-shape opacity-75 animation-rotate">

                <div class="flex-align">
                    <button type="button" id="banner-three-prev" class="slick-prev slick-arrow flex-center rounded-circle border border-white hover-border-main-600 text-white text-2xl hover-bg-main-600 hover-text-white transition-1 position-absolute top-50 translate-middle-y inset-inline-start-0 ms-lg-5 ms-32">
                        <i class="ph ph-caret-left"></i>
                    </button>
                    <button type="button" id="banner-three-next" class="slick-next slick-arrow flex-center rounded-circle border border-white hover-border-main-600 text-white text-2xl hover-bg-main-600 hover-text-white transition-1 position-absolute top-50 translate-middle-y inset-inline-end-0 me-lg-5 me-32">
                        <i class="ph ph-caret-right"></i>
                    </button>
                </div>

                <div class="container container-lg">
                     <div class="banner-three-slider">
                        <div class="">
                            <div class="row align-items-center gy-4">
                                <div class="col-lg-6">
                                    <div class="span3">
                                        <span class="text-white mb-8 h6 animate-left-right animation-delay-08">UP TO 20% OFF</span>
                                        <h1 class="text-white display-one animate-left-right animation-delay-1">New <span class="fw-normal text-main-two-600 font-heading-four">Style</span> Just For You.</h1>
                                        <p class="text-white max-w-472 text-2xl mb-24Up animate-left-right animation-delay-12">Feeding Made Effortless, Fashion Made Easy.</p>
                                        <a href="shop.html" class="btn btn-outline-white d-inline-flex align-items-center rounded-pill gap-8 mt-lg-4 mt-sm-1 animate-left-right animation-delay-15" tabindex="0">
                                            Shop Now<span class="icon text-xl d-flex"><i class="ph ph-shopping-cart-simple"></i>   </span> 
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex justify-content-center" data-tilt data-tilt-max="16" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-scale="1.06">
                                        <img src="assets/images/thumbs/banner-three-img1.png" alt="Thumb" class="animate-scale animation-delay-12">
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="">
                            <div class="row align-items-center gy-4">
                                <div class="col-lg-6">
                                    <div class="span3">
                                        <span class="text-white mb-8 h6 animate-left-right animation-delay-08">UP TO 20% OFF</span>
                                        <h1 class="text-white display-one animate-left-right animation-delay-1">New <span class="fw-normal text-main-two-600 font-heading-four">Style</span> Just For You.</h1>
                                        <p class="text-white max-w-472 text-2xl mb-24Up animate-left-right animation-delay-12">Designed to Do Nothing. And Look Great Doing It.</p>
                                        <a href="shop.html" class="btn btn-outline-white d-inline-flex align-items-center rounded-pill gap-8 mt-lg-4 mt-sm-1 animate-left-right animation-delay-15" tabindex="0">
                                            Shop Now<span class="icon text-xl d-flex"><i class="ph ph-shopping-cart-simple"></i>   </span> 
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex justify-content-center" data-tilt data-tilt-max="16" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-scale="1.06">
                                        <img src="assets/images/thumbs/banner-three-img2.png" alt="Thumb" class="animate-scale animation-delay-12">
                                    </div>
                                </div>  
                            </div>
                        </div><div class="">
                            <div class="row align-items-center gy-4">
                                <div class="col-lg-6">
                                    <div class="span3">
                                        <span class="text-white mb-8 h6 animate-left-right animation-delay-08">UP TO 20% OFF</span>
                                        <h1 class="text-white display-one animate-left-right animation-delay-1">New <span class="fw-normal text-main-two-600 font-heading-four">Style</span> Just For You.</h1>
                                        <p class="text-white max-w-472 text-2xl mb-24Up animate-left-right animation-delay-12">Perfectly Paired for Little Trendsetters.</p>
                                        <a href="shop.html" class="btn btn-outline-white d-inline-flex align-items-center rounded-pill gap-8 mt-lg-4 mt-sm-1 animate-left-right animation-delay-15" tabindex="0">
                                            Shop Now<span class="icon text-xl d-flex"><i class="ph ph-shopping-cart-simple"></i>   </span> 
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="d-flex justify-content-center" data-tilt data-tilt-max="16" data-tilt-speed="500" data-tilt-perspective="5000" data-tilt-scale="1.06">
                                        <img src="assets/images/thumbs/banner-three-img3.png" alt="Thumb" class="animate-scale animation-delay-12">
                                    </div>
                                </div>  
                            </div>
                        </div>        
                    </div>
                 </div>
            </section>
        <!-- ==================================== Banner Three End =================================== -->
      
        



        <!-- ============================== Promotional Banner Three End ========================== --><!-- ============================ Feature Three Section start =============================== -->
        <div class="feature feature-three mt-0 py-120 overflow-hidden" id="featureSection">
            <div class="container container-lg">
                <div class="section-heading text-center">
                    <h5 class="mb-0 wow bounceIn text-uppercase">Popular Categories</h5>
                </div>
                <div class="position-relative arrow-center">
                    <div class="flex-align">
                        <button type="button" id="feature-item-wrapper-prev" class="slick-prev slick-arrow flex-center rounded-circle bg-white text-xl hover-bg-main-600 hover-text-white transition-1">
                            <i class="ph ph-caret-left"></i>
                        </button>
                        <button type="button" id="feature-item-wrapper-next" class="slick-next slick-arrow flex-center rounded-circle bg-white text-xl hover-bg-main-600 hover-text-white transition-1">
                            <i class="ph ph-caret-right"></i>
                        </button>
                    </div>
                    <div class="feature-three-item-wrapper">
                            <div class="feature-item text-center" data-aos="zoom-in" data-aos-duration="800">
                                <div class="feature-item__thumb bg-yellow-light max-w-260 max-h-260 rounded-circle w-100 h-100">
                                    <a href="shop.html" class="w-100 h-100 flex-center">
                                        <img src="assets/images/thumbs/features-three-img1.png" alt="">
                                    </a>
                                </div>
                                <div class="feature-item__content mt-20">
                                    <h6 class="text-lg mb-8">
                                        <a href="javascript:void(0)" class="text-inherit">Feeding Normal Sleeve</a>
                                    </h6>
                                    <span class="text-sm text-gray-900">  <?= $categories["Feeding Normal Sleeve"]['count'] ?> Items</span>
                                </div>
                            </div>

                            <div class="feature-item text-center" data-aos="zoom-in" data-aos-duration="800">
                                <div class="feature-item__thumb bg-danger-light max-w-260 max-h-260 rounded-circle w-100 h-100">
                                    <a href="shop.html" class="w-100 h-100 flex-center">
                                        <img src="assets/images/thumbs/features-three-img2.png" alt="">
                                    </a>
                                </div>
                                <div class="feature-item__content mt-20">
                                    <h6 class="text-lg mb-8">
                                        <a href="javascript:void(0)" class="text-inherit">Feeding Puff Sleeve</a>
                                    </h6>
                                    <span class="text-sm text-gray-900"><?= $categories["Feeding Puff Sleeve"]['count'] ?> Items </span>
                                </div>
                            </div>

                            <div class="feature-item text-center" data-aos="zoom-in" data-aos-duration="800">
                                <div class="feature-item__thumb bg-purple-light max-w-260 max-h-260 rounded-circle w-100 h-100">
                                    <a href="shop.html" class="w-100 h-100 flex-center">
                                        <img src="assets/images/thumbs/features-three-img3.png" alt="">
                                    </a>
                                </div>
                                <div class="feature-item__content mt-20">
                                    <h6 class="text-lg mb-8">
                                        <a href="javascript:void(0)" class="text-inherit">Non-Feeding Sleeve </a>
                                    </h6>
                                    <span class="text-sm text-gray-900"><?= $categories["Non-Feeding Sleeve"]['count'] ?> Items </span>
                                </div>
                            </div>

                            <div class="feature-item text-center" data-aos="zoom-in" data-aos-duration="800">
                                <div class="feature-item__thumb bg-danger-light max-w-260 max-h-260 rounded-circle w-100 h-100">
                                    <a href="shop.html" class="w-100 h-100 flex-center">
                                        <img src="assets/images/thumbs/features-three-img4.png" alt="">
                                    </a>
                                </div>
                                <div class="feature-item__content mt-20">
                                    <h6 class="text-lg mb-8">
                                        <a href="javascript:void(0)" class="text-inherit">Kids Co-ord Sets</a>
                                    </h6>
                                    <span class="text-sm text-gray-900"><?= $categories["Kids Co-ord Sets"]['count'] ?> Items  </span>
                                </div>
                            </div>

                            <div class="feature-item text-center" data-aos="zoom-in" data-aos-duration="800">
                                <div class="feature-item__thumb bg-warning-light max-w-260 max-h-260 rounded-circle w-100 h-100">
                                    <a href="shop.html" class="w-100 h-100 flex-center">
                                        <img src="assets/images/thumbs/features-three-img5.png" alt="">
                                    </a>
                                </div>
                                <div class="feature-item__content mt-20">
                                    <h6 class="text-lg mb-8">
                                        <a href="javascript:void(0)" class="text-inherit">Kids Frock</a>
                                    </h6>
                                    <span class="text-sm text-gray-900"><?= $categories["Kids Frock"]['count'] ?> Items  </span>
                                </div>
                            </div>

                            <div class="feature-item text-center" data-aos="zoom-in" data-aos-duration="800">
                                <div class="feature-item__thumb bg-success-light max-w-260 max-h-260 rounded-circle w-100 h-100">
                                    <a href="shop.html" class="w-100 h-100 flex-center">
                                        <img src="assets/images/thumbs/features-three-img6.png" alt="">
                                    </a>
                                </div>
                                <div class="feature-item__content mt-20">
                                    <h6 class="text-lg mb-8">
                                        <a href="javascript:void(0)" class="text-inherit">Hospital Bag</a>
                                    </h6>
                                    <span class="text-sm text-gray-900"><?= $categories["Hospital Bag"]['count'] ?> Items </span>
                                </div>
                            </div>

                            <div class="feature-item text-center" data-aos="zoom-in" data-aos-duration="800">
                                <div class="feature-item__thumb  max-w-260 max-h-260 rounded-circle w-100 h-100">
                                    <a href="shop.html" class="w-100 h-100 flex-center">
                                        <img src="assets/images/thumbs/features-three-img3.png" alt="">
                                    </a>
                                </div>
                                <div class="feature-item__content mt-20">
                                    <h6 class="text-lg mb-8">
                                        <a href="javascript:void(0)" class="text-inherit">Women's co-ord Sets</a>
                                    </h6>
                                    <span class="text-sm text-gray-900"><?= $categories["Women's Co-ord Sets"]['count'] ?> Items  </span>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>


        <!-- ============================ Feature Three Section End =============================== -->  <!-- text slider -->
        <div class="text-slider-section overflow-hidden bg-neutral-600 py-28" data-aos="fade-up">
            <div class="text-slider d-flex align-items-center gap-4">
                <div class="d-flex flex-nowrap flex-shrink-0 flx-align gap-32">
                    <span class="flex-shrink-0">
                        <img src="assets/images/icon/star-color.png" alt="">
                    </span>
                    <h4 class="text-white flex-grow-1 mb-0 fw-medium">Feeding Normal Sleeve</h4>
                </div>
                <div class="d-flex flex-nowrap flex-shrink-0 flx-align gap-32">
                    <span class="flex-shrink-0">
                        <img src="assets/images/icon/star-color.png" alt="">
                    </span>
                    <h4 class="text-white flex-grow-1 mb-0 fw-medium">Feeding Puff Sleeve</h4>
                </div>
                <div class="d-flex flex-nowrap flex-shrink-0 flx-align gap-32">
                    <span class="flex-shrink-0">
                        <img src="assets/images/icon/star-color.png" alt="">
                    </span>
                    <h4 class="text-white flex-grow-1 mb-0 fw-medium">Non - Feeding Lounguwear</h4>
                </div>
                <div class="d-flex flex-nowrap flex-shrink-0 flx-align gap-32">
                    <span class="flex-shrink-0">
                        <img src="assets/images/icon/star-color.png" alt="">
                    </span>
                    <h4 class="text-white flex-grow-1 mb-0 fw-medium">Kids Collection</h4>
                </div>
                <div class="d-flex flex-nowrap flex-shrink-0 flx-align gap-32">
                    <span class="flex-shrink-0">
                        <img src="assets/images/icon/star-color.png" alt="">
                    </span>
                    <h4 class="text-white flex-grow-1 mb-0 fw-medium">Born Baby Kits</h4>
                </div>
                <div class="d-flex flex-nowrap flex-shrink-0 flx-align gap-32">
                    <span class="flex-shrink-0">
                        <img src="assets/images/icon/star-color.png" alt="">
                    </span>
                    <h4 class="text-white flex-grow-1 mb-0 fw-medium">Hospital Kits</h4>
                </div>
                <div class="d-flex flex-nowrap flex-shrink-0 flx-align gap-32">
                    <span class="flex-shrink-0">
                        <img src="assets/images/icon/star-color.png" alt="">
                    </span>
                    <h4 class="text-white flex-grow-1 mb-0 fw-medium">Toys</h4>
                </div>
                <div class="d-flex flex-nowrap flex-shrink-0 flx-align gap-32">
                    <span class="flex-shrink-0">
                        <img src="assets/images/icon/star-color.png" alt="">
                    </span>
                    <h4 class="text-white flex-grow-1 mb-0 fw-medium">Mom's Accessories</h4>
                </div>
            </div>
        </div>
        <!-- text slider End -->


        <!-- ========================= Trending Products Start ================================ -->
        <section class="trending-products-three py-120 overflow-hidden">
            <div class="container container-lg">
                <div class="section-heading mb-24">
                    <div class="flex-between flex-wrap gap-8">
                        <h5 class="mb-0 text-uppercase">Trending Products</h5>
                        <ul class="nav common-tab style-two nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="pills-sale-tab" data-bs-toggle="pill" data-bs-target="#pills-sale" type="button" role="tab" aria-controls="pills-sale" aria-selected="true">Feeding Normal Sleeve</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-sale" role="tabpanel" aria-labelledby="pills-sale-tab" tabindex="0">
                        <div class="row g-12">
                            
                            <?php
                               $query = "SELECT * FROM womens_products WHERE variation_id = 1 GROUP BY product_code ORDER BY id DESC LIMIT 4";
                                $result = mysqli_query($conn, $query);

                                while ($product = mysqli_fetch_assoc($result)):
                                    $product_id = $product['id'];
                                    $product_name = htmlspecialchars($product['product_name']);
                                    $image = htmlspecialchars($product['image1']);
                                    $price = $product['actual_price'];
                                    $old_price = $product['mrp_price'];
                                    $product_code= $product['product_code'];
                                    $product_link = "product_detail.php?id=" . $product_id . "&type=women";
                                ?>
                                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up">
                                        <div class="product-card h-100 p-16 border border-gray-100 hover-border-main-600 rounded-16 position-relative transition-2">
                                            <div class="product-card__thumb rounded-8 bg-gray-50 position-relative">
                                                <a href="<?= $product_link ?>" class="w-100 h-100 flex-center">
                                                    <img src="../backend/pages/<?= $image ?>" alt="<?= $product_name ?>" class="w-auto max-w-unset">
                                                </a>
                                            </div>

                                            <div class="product-card__content mt-16 w-100">
                                                <h6 class="title text-lg fw-semibold my-16">
                                                    <a href="<?= $product_link ?>" class="link text-line-2" tabindex="0"><?= $product_name ?></a>
                                                </h6>

                                                <div class="product-card__price mt-16 mb-30">
                                                    <?php if (!empty($old_price) && $old_price > $price): ?>
                                                        <span class="text-gray-400 text-md fw-semibold text-decoration-line-through">₹<?= $old_price ?></span>
                                                    <?php endif; ?>
                                                    <span class="text-heading text-md fw-semibold">₹<?= $price ?> <span class="text-gray-500 fw-normal">/Qty</span></span>
                                                </div>

                                                  <form method="GET" action="product_detail.php">
                                                <input type="hidden" name="product_code" value="<?= $product_code ?>">
                                                <input type="hidden" name="type" value="women">
                                                <button type="submit" class="btn bg-gray-50 text-heading hover-bg-main-600 hover-text-white w-100 py-10 rounded-8 fw-medium">
                                                    Quick View <i class="ph ph-eye ms-1"></i>
                                                </button>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>



                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ========================= Trending Products End ================================ -->

         <!-- ========================= Discount Three Start ================================ -->
        <section class="discount-three overflow-hidden">
            <div class="container container-lg">
                <div class="row gy-4">
                    <div class="col-xl-4 col-sm-6" data-aos="zoom-in" data-aos-duration="800">
                        <div class="discount-three-item bg-img rounded-16 overflow-hidden" data-background-image="assets/images/thumbs/discount-three-img1.png">
                            <div class="text-start">
                                <span class="fw-medium text-neutral-600 mb-4 text-uppercase">15% off the all order</span>
                                <h6 class="fw-semibold mb-0 max-w-375">Summer Outfits</h6>
                                <a href="shop.html" class="btn btn-black rounded-pill gap-8 mt-32 flex-align d-inline-flex" tabindex="0">
                                    Shop Now 
                                    <span class="text-xl d-flex"><i class="ph ph-shopping-cart-simple"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6" data-aos="zoom-in" data-aos-duration="800">
                        <div class="discount-three-item bg-img rounded-16 overflow-hidden" data-background-image="assets/images/thumbs/discount-three-img2.png">
                            <div class="text-start">
                                <span class="fw-medium text-neutral-600 mb-4 text-uppercase">Buy Above ₹1000</span>
                                <h6 class="fw-semibold mb-0 max-w-375">Get Free Shipping </h6>
                                <a href="shop.html" class="btn btn-black rounded-pill gap-8 mt-32 flex-align d-inline-flex" tabindex="0">
                                    Shop Now 
                                    <span class="text-xl d-flex"><i class="ph ph-shopping-cart-simple"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6" data-aos="zoom-in" data-aos-duration="800">
                        <div class="discount-three-item bg-img rounded-16 overflow-hidden" data-background-image="assets/images/thumbs/discount-three-img3.png">
                            <div class="text-start">
                                <span class="fw-medium text-neutral-600 mb-4 text-uppercase">Aadi Sale 35% OFF</span>
                                <h6 class="fw-semibold mb-0 max-w-375">Aadi sales</h6>
                                <a href="shop.html" class="btn btn-black rounded-pill gap-8 mt-32 flex-align d-inline-flex" tabindex="0">
                                    Shop Now 
                                    <span class="text-xl d-flex"><i class="ph ph-shopping-cart-simple"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ========================= Discount Three End ================================ -->

         <!-- ========================== New Arrival Three Section Start ===================================== -->
        <section class="trending-products-three py-120 overflow-hidden">
            <div class="container container-lg">
                <div class="section-heading mb-24">
                    <div class="flex-between flex-wrap gap-8">
                        <h5 class="mb-0 text-uppercase">Trending Products</h5>
                        <ul class="nav common-tab style-two nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="pills-sale-tab" data-bs-toggle="pill" data-bs-target="#pills-sale" type="button" role="tab" aria-controls="pills-sale" aria-selected="true">Feeding Puff Sleeve</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-sale" role="tabpanel" aria-labelledby="pills-sale-tab" tabindex="0">
                        <div class="row g-12">

                            <?php
                               $query = "SELECT * FROM womens_products WHERE variation_id = 2 GROUP BY product_code ORDER BY id DESC LIMIT 4";
                                $result = mysqli_query($conn, $query);

                                while ($product = mysqli_fetch_assoc($result)):
                                    $product_id = $product['id'];
                                    $product_name = htmlspecialchars($product['product_name']);
                                    $image = htmlspecialchars($product['image1']);
                                    $price = $product['actual_price'];
                                    $old_price = $product['mrp_price'];
                                    $product_code= $product['product_code'];
                                    $product_link = "product_detail.php?id=" . $product_id . "&type=women";
                                ?>
                                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up">
                                        <div class="product-card h-100 p-16 border border-gray-100 hover-border-main-600 rounded-16 position-relative transition-2">
                                            <div class="product-card__thumb rounded-8 bg-gray-50 position-relative">
                                                <a href="<?= $product_link ?>" class="w-100 h-100 flex-center">
                                                    <img src="../backend/pages/<?= $image ?>" alt="<?= $product_name ?>" class="w-auto max-w-unset">
                                                </a>
                                            </div>

                                            <div class="product-card__content mt-16 w-100">
                                                <h6 class="title text-lg fw-semibold my-16">
                                                    <a href="<?= $product_link ?>" class="link text-line-2" tabindex="0"><?= $product_name ?></a>
                                                </h6>

                                                <div class="product-card__price mt-16 mb-30">
                                                    <?php if (!empty($old_price) && $old_price > $price): ?>
                                                        <span class="text-gray-400 text-md fw-semibold text-decoration-line-through">₹<?= $old_price ?></span>
                                                    <?php endif; ?>
                                                    <span class="text-heading text-md fw-semibold">₹<?= $price ?> <span class="text-gray-500 fw-normal">/Qty</span></span>
                                                </div>

                                                  <form method="GET" action="product_detail.php">
                                                <input type="hidden" name="product_code" value="<?= $product_code ?>">
                                                <input type="hidden" name="type" value="women">
                                                <button type="submit" class="btn bg-gray-50 text-heading hover-bg-main-600 hover-text-white w-100 py-10 rounded-8 fw-medium">
                                                    Quick View <i class="ph ph-eye ms-1"></i>
                                                </button>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ========================== New Arrival Three Section End ===================================== -->


        <!-- ================================ Deals Section Start =================================== -->
        <section class="deals pb-120">
            <div class="container container-lg">
                <div class="bg-neutral-600 rounded-48 bg-img" data-background-image="assets/images/bg/pattern-two.png">
                    <div class="row gy-4 align-items-center">
                        <div class="col-xl-6 d-md-block d-none">
                            <div class="position-relative px-24">
                                <ul class="products-group">
                                    <li class="products-group__list pt-12">
                                        <span class="w-32 h-32 border border-white rounded-circle flex-center position-relative overflow-hidden border-2 bg-blur cursor-pointer">
                                            <span class="w-12 h-12 rounded-circle bg-white"></span>
                                        </span>
                                        <div class="products-group__card product-card w-100 p-16 border border-gray-100 hover-border-main-600 max-w-340 rounded-16 transition-2 bg-white position-absolute bottom-100 start-50 min-width-max-content rotate-10 transition-2">
                                            <div class="product-card__thumb rounded-8 bg-gray-50 position-relative">
                                                <a href="product-details-two.html" class="w-100 h-100 flex-center">
                                                    <img src="assets/images/thumbs/trending-three-img3.png" alt="" class="w-auto max-w-unset">
                                                </a>
                                                <button type="button" class="z-1 position-absolute inset-inline-end-0 inset-block-start-0 me-16 mt-16  text-neutral-600 text-xl flex-center hover-text-main-two-600 wishlist-btn">
                                                    <i class="ph-fill ph-heart text-main-two-600"></i>
                                                </button>
                                            </div>
                                            <div class="product-card__content mt-16 w-100">
                                                <h6 class="title text-2xl fw-semibold my-8">
                                                    <a href="product-details-two.html" class="link text-line-2" tabindex="0">Feeding Normal Sleeve</a>
                                                </h6>
                                                <div class="product-card__price mt-8 mb-8">
                                                    <span class="text-neutral-600 text-lg fw-semibold">₹333 INR</span>
                                                    <span class="text-gray-400 text-lg fw-semibold text-decoration-line-through"> ₹399 INR</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="products-group__list pt-12">
                                        <span class="w-32 h-32 border border-white rounded-circle flex-center position-relative overflow-hidden border-2 bg-blur cursor-pointer">
                                            <span class="w-12 h-12 rounded-circle bg-white"></span>
                                        </span>
                                        <div class="products-group__card product-card w-100 p-16 border border-gray-100 hover-border-main-600 max-w-340 rounded-16 transition-2 bg-white position-absolute bottom-100 start-50 min-width-max-content rotate-10 transition-2">
                                            <div class="product-card__thumb rounded-8 bg-gray-50 position-relative">
                                                <a href="product-details-two.html" class="w-100 h-100 flex-center">
                                                    <img src="assets/images/thumbs/trending-three-img4.png" alt="" class="w-auto max-w-unset">
                                                </a>
                                                <button type="button" class="z-1 position-absolute inset-inline-end-0 inset-block-start-0 me-16 mt-16  text-neutral-600 text-xl flex-center hover-text-main-two-600 wishlist-btn">
                                                    <i class="ph-fill ph-heart text-main-two-600"></i>
                                                </button>
                                            </div>
                                            <div class="product-card__content mt-16 w-100">
                                                <h6 class="title text-2xl fw-semibold my-8">
                                                    <a href="product-details-two.html" class="link text-line-2" tabindex="0">Baby Carrier
                                                    </a>
                                                </h6>
                                                <div class="product-card__price mt-8 mb-8">
                                                    <span class="text-neutral-600 text-lg fw-semibold">₹250 INR</span>
                                                    <span class="text-gray-400 text-lg fw-semibold text-decoration-line-through">₹350 INR</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="products-group__list pt-12">
                                        <span class="w-32 h-32 border border-white rounded-circle flex-center position-relative overflow-hidden border-2 bg-blur cursor-pointer">
                                            <span class="w-12 h-12 rounded-circle bg-white"></span>
                                        </span>
                                        <div class="products-group__card product-card w-100 p-16 border border-gray-100 hover-border-main-600 max-w-340 rounded-16 transition-2 bg-white position-absolute bottom-100 start-50 min-width-max-content rotate-10 transition-2">
                                            <div class="product-card__thumb rounded-8 bg-gray-50 position-relative">
                                                <a href="product-details-two.html" class="w-100 h-100 flex-center">
                                                    <img src="assets/images/thumbs/trending-three-img2.png" alt="" class="w-auto max-w-unset">
                                                </a>
                                                <button type="button" class="z-1 position-absolute inset-inline-end-0 inset-block-start-0 me-16 mt-16  text-neutral-600 text-xl flex-center hover-text-main-two-600 wishlist-btn">
                                                    <i class="ph-fill ph-heart text-main-two-600"></i>
                                                </button>
                                            </div>
                                            <div class="product-card__content mt-16 w-100">
                                                <h6 class="title text-2xl fw-semibold my-8">
                                                    <a href="product-details-two.html" class="link text-line-2" tabindex="0">Hospital Basket</a>
                                                </h6>
                                                <div class="product-card__price mt-8 mb-8">
                                                    <span class="text-neutral-600 text-lg fw-semibold">₹350 INR</span>
                                                    <span class="text-gray-400 text-lg fw-semibold text-decoration-line-through"> ₹350 INR</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                                <div class="text-end" data-aos="fade-up" data-aos-duration="600">
                                    <img src="assets/images/thumbs/deals-img.png" alt="" class="pe-xxl-5 pe-lg-4 deals__img">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6" data-aos="zoom-in">
                            <div class="p-56-px">
                                <div class="text-center border border-white rounded-80 rounded-top-right-0 p-56-px">
                                    <div class="p-56-px bg-white rounded-80 rounded-top-right-0">
                                        <div class="max-w-472 mx-auto">
                                            <span class="text-white bg-neutral-600 py-8 px-16 rounded-pill fw-medium text-md mb-32 text-uppercase">Offer's from Special Women's</span>
                                            <h3 class="mb-32 fw-medium text-uppercase">Our Store Great Offer's</h3>
                                            <p class="text-neutral-600">Offer's from New Mom's and Pregent Ladies's</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ================================ Deals Section End =================================== -->


        <!-- ========================= Trending Products Start ================================ -->
        <section class="trending-products-three py-120 overflow-hidden">
            <div class="container container-lg">
                <div class="section-heading mb-24">
                    <div class="flex-between flex-wrap gap-8">
                        <h5 class="mb-0 text-uppercase">Trending Products</h5>
                        <ul class="nav common-tab style-two nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="pills-sale-tab" data-bs-toggle="pill" data-bs-target="#pills-sale" type="button" role="tab" aria-controls="pills-sale" aria-selected="true">Kids co-ord Sets </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-sale" role="tabpanel" aria-labelledby="pills-sale-tab" tabindex="0">
                        <div class="row g-12">

                            <?php
                               $query = "SELECT * FROM kids_products WHERE variation_id = 2 GROUP BY product_code ORDER BY id DESC LIMIT 4";
                                $result = mysqli_query($conn, $query);

                                while ($product = mysqli_fetch_assoc($result)):
                                    $product_id = $product['id'];
                                    $product_name = htmlspecialchars($product['product_name']);
                                    $image = htmlspecialchars($product['image1']);
                                    $price = $product['actual_price'];
                                    $old_price = $product['mrp_price'];
                                    $product_code= $product['product_code'];
                                    $product_link = "product_detail.php?id=" . $product_id . "&type=women";
                                ?>
                                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up">
                                        <div class="product-card h-100 p-16 border border-gray-100 hover-border-main-600 rounded-16 position-relative transition-2">
                                            <div class="product-card__thumb rounded-8 bg-gray-50 position-relative">
                                                <a href="<?= $product_link ?>" class="w-100 h-100 flex-center">
                                                    <img src="../backend/pages/<?= $image ?>" alt="<?= $product_name ?>" class="w-auto max-w-unset">
                                                </a>
                                            </div>

                                            <div class="product-card__content mt-16 w-100">
                                                <h6 class="title text-lg fw-semibold my-16">
                                                    <a href="<?= $product_link ?>" class="link text-line-2" tabindex="0"><?= $product_name ?></a>
                                                </h6>

                                                <div class="product-card__price mt-16 mb-30">
                                                    <?php if (!empty($old_price) && $old_price > $price): ?>
                                                        <span class="text-gray-400 text-md fw-semibold text-decoration-line-through">₹<?= $old_price ?></span>
                                                    <?php endif; ?>
                                                    <span class="text-heading text-md fw-semibold">₹<?= $price ?> <span class="text-gray-500 fw-normal">/Qty</span></span>
                                                </div>

                                                  <form method="GET" action="product_detail.php">
                                                <input type="hidden" name="product_code" value="<?= $product_code ?>">
                                                <input type="hidden" name="type" value="women">
                                                <button type="submit" class="btn bg-gray-50 text-heading hover-bg-main-600 hover-text-white w-100 py-10 rounded-8 fw-medium">
                                                    Quick View <i class="ph ph-eye ms-1"></i>
                                                </button>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ========================= Trending Products End ================================ -->
 

        <!-- ========================== Shipping Section Start ============================ -->
        <section class="shipping mt-70 pb-120" id="shipping">
            <div class="container container-lg">
                <div class="row gy-4">
                    <div class="col-xxl-3 col-sm-6" data-aos="zoom-in" data-aos-duration="400">
                        <div class="shipping-item flex-align gap-16 rounded-16 bg-main-two-50 hover-bg-main-100 transition-2">
                            <span class="w-56 h-56 flex-center rounded-circle bg-main-two-600 text-white text-32 flex-shrink-0"><i class="ph-fill ph-car-profile"></i></span>
                            <div class="">
                                <h6 class="mb-0">Buy Above ₹1000</h6>
                                <span class="text-sm text-heading">Get Free Shipping</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6" data-aos="zoom-in" data-aos-duration="600">
                        <div class="shipping-item flex-align gap-16 rounded-16 bg-main-two-50 hover-bg-main-100 transition-2">
                            <span class="w-56 h-56 flex-center rounded-circle bg-main-two-600 text-white text-32 flex-shrink-0"><i class="ph-fill ph-hand-heart"></i></span>
                            <div class="">
                                <h6 class="mb-0"> 100% Satisfaction</h6>
                                <span class="text-sm text-heading">8500+ Happy Customer's</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6" data-aos="zoom-in" data-aos-duration="800">
                        <div class="shipping-item flex-align gap-16 rounded-16 bg-main-two-50 hover-bg-main-100 transition-2">
                            <span class="w-56 h-56 flex-center rounded-circle bg-main-two-600 text-white text-32 flex-shrink-0"><i class="ph-fill ph-credit-card"></i></span>
                            <div class="">
                                <h6 class="mb-0"> Secure Payments</h6>
                                <span class="text-sm text-heading">Easy Checkout Process</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6" data-aos="zoom-in" data-aos-duration="1000">
                        <div class="shipping-item flex-align gap-16 rounded-16 bg-main-two-50 hover-bg-main-100 transition-2">
                            <span class="w-56 h-56 flex-center rounded-circle bg-main-two-600 text-white text-32 flex-shrink-0"><i class="ph-fill ph-chats"></i></span>
                            <div class="">
                                <h6 class="mb-0">Customer Support</h6>
                                <span class="text-sm text-heading">9 To 6 Customer Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ========================== Shipping Section End ============================ -->

        <!-- ============================== Testimonial section start ======================= -->
        <section class="testimonials py-120 bg-neutral-600 bg-img overflow-hidden" data-background-image="assets/images/bg/pattern-two.png">
            <div class="container container-lg">
                <div class="row gy-4 align-items-center">
                    <div class="col-xl-1">
                        <div class="section-heading mb-0 d-flex flex-column align-items-center writing-mode wow fadeInLeft">
                            <p class="text-white">Share information about your brand with your customers.</p>
                            <h5 class="text-white mb-0 text-uppercase">Customers Feedback</h5>
                        </div>
                    </div>
                    <div class="col-xl-11">
                        <div class="position-relative">
                            <div class="testimonials-slider mb-60">
                                <div class="testimonials-item">
                                    <h6 class="text-white text-uppercase mb-8 fw-medium">Reshma</h6>
                                    <div class="flex-align gap-8 mt-24">
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                    </div>
                                    <p class="testimonials-item__desc text-white text-2xl fw-normal mt-40 max-w-990">Customers expressed that shopping at Asiana Fashion was a "delightful experience," highlighting the vibrant colors and unique designs that made them feel special at events</p>
                                </div>

                                <div class="testimonials-item">
                                    <h6 class="text-white text-uppercase mb-8 fw-medium">Kanimozhi</h6>
                                    <div class="flex-align gap-8 mt-24">
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                    </div>
                                    <p class="testimonials-item__desc text-white text-2xl fw-normal mt-40 max-w-990">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia</p>
                                </div>

                                <div class="testimonials-item">
                                    <h6 class="text-white text-uppercase mb-8 fw-medium">Thilagavathi</h6>
                                    <div class="flex-align gap-8 mt-24">
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                    </div>
                                    <p class="testimonials-item__desc text-white text-2xl fw-normal mt-40 max-w-990">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here'</p>
                                </div>

                                <div class="testimonials-item">
                                    <h6 class="text-white text-uppercase mb-8 fw-medium">Murugeawari</h6>
                                    <div class="flex-align gap-8 mt-24">
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                    </div>
                                    <p class="testimonials-item__desc text-white text-2xl fw-normal mt-40 max-w-990">Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.</p>
                                </div>

                                <div class="testimonials-item">
                                    <h6 class="text-white text-uppercase mb-8 fw-medium">Broccoll</h6>
                                    <div class="flex-align gap-8 mt-24">
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                        <span class="text-xs fw-medium text-warning-600 d-flex"><i class="ph-fill ph-star"></i></span>
                                    </div>
                                    <p class="testimonials-item__desc text-white text-2xl fw-normal mt-40 max-w-990">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                                </div>
                            </div>

                            <div class="testimonials-thumbs-slider">
                                <div class="testimonials-thumbs d-flex position-relative align-items-end justify-content-end">
                                    <div class="testimonials-thumbs__img">
                                        <img src="assets/images/thumbs/testimonials-img1.png" alt="" class="cover-img">
                                    </div>
                                    <div class="testimonials-thumbs__content position-absolute transition-2 bottom-0 start-50 translate-middle-x mb-16 text-center hidden opacity-0">  
                                        <h6 class="text-white text-uppercase mb-8 fw-medium">Reshma</h6>
                                    </div>
                                </div>

                                <div class="testimonials-thumbs d-flex position-relative align-items-end justify-content-end">
                                    <div class="testimonials-thumbs__img">
                                        <img src="assets/images/thumbs/testimonials-img2.png" alt="" class="cover-img">
                                    </div>
                                    <div class="testimonials-thumbs__content position-absolute transition-2 bottom-0 start-50 translate-middle-x mb-16 text-center hidden opacity-0">  
                                        <h6 class="text-white text-uppercase mb-8 fw-medium">Kanimozhi</h6>
                                    </div>
                                </div>

                                <div class="testimonials-thumbs d-flex position-relative align-items-end justify-content-end">
                                    <div class="testimonials-thumbs__img">
                                        <img src="assets/images/thumbs/testimonials-img3.png" alt="" class="cover-img">
                                    </div>
                                    <div class="testimonials-thumbs__content position-absolute transition-2 bottom-0 start-50 translate-middle-x mb-16 text-center hidden opacity-0">  
                                        <h6 class="text-white text-uppercase mb-8 fw-medium">Thilagavathi</h6>
                                    </div>
                                </div>

                                <div class="testimonials-thumbs d-flex position-relative align-items-end justify-content-end">
                                    <div class="testimonials-thumbs__img">
                                        <img src="assets/images/thumbs/testimonials-img4.png" alt="" class="cover-img">
                                    </div>
                                    <div class="testimonials-thumbs__content position-absolute transition-2 bottom-0 start-50 translate-middle-x mb-16 text-center hidden opacity-0">  
                                        <h6 class="text-white text-uppercase mb-8 fw-medium">Murugeawari</h6>
                                    </div>
                                </div>

                                <div class="testimonials-thumbs d-flex position-relative align-items-end justify-content-end">
                                    <div class="testimonials-thumbs__img">
                                        <img src="assets/images/thumbs/testimonials-img2.png" alt="" class="cover-img">
                                    </div>
                                    <div class="testimonials-thumbs__content position-absolute transition-2 bottom-0 start-50 translate-middle-x mb-16 text-center hidden opacity-0">  
                                        <h6 class="text-white text-uppercase mb-8 fw-medium">Broccoll</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-center gap-8 mt-48">
                    <button type="button" id="testi-prev" class="slick-prev slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 text-white transition-1">
                        <i class="ph ph-caret-left"></i>
                    </button>
                    <button type="button" id="testi-next" class="slick-next slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 text-white transition-1">
                        <i class="ph ph-caret-right"></i>
                    </button>
                </div>
            </div>
        </section>
        <!-- ============================== Testimonial section start ======================= -->

        <!-- ================================ Instagram section start ===================================== -->
        <section class="instagram py-120 overflow-hidden">
            <div class="container container-lg">
                <div class="section-heading">
                    <div class="flex-between flex-wrap gap-8">
                        <div class="">
                            <h5 class="mb-0 text-uppercase">Instagram</h5>
                            <p class="text-gray-500">Get Trending collection with Instagram</p>
                        </div>
                        <div class="flex-align gap-16">
                            <a href="#" class="text-sm fw-semibold text-gray-700 hover-text-main-600 hover-text-decoration-underline">View All</a>
                            <div class="flex-align gap-8">
                                <button type="button" id="instagram-prev" class="slick-prev slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1">
                                    <i class="ph ph-caret-left"></i>
                                </button>
                                <button type="button" id="instagram-next" class="slick-next slick-arrow flex-center rounded-circle border border-gray-100 hover-border-main-600 text-xl hover-bg-main-600 hover-text-white transition-1">
                                    <i class="ph ph-caret-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="instagram-slider">
                                <div data-aos="fade-up" data-aos-duration="400">
                        <div class="instagram-item rounded-24 overflow-hidden position-relative">
                            <img src="assets/images/thumbs/instagram-img1.png" alt="">
                            <a href="https://www.instagram.com/" class="w-72 h-72 bg-black bg-opacity-50 text-white text-32 position-absolute top-50 start-50 translate-middle flex-center rounded-circle hover-bg-main-two-600 hover-text-white">
                                <i class="ph ph-instagram-logo"></i>
                            </a>
                        </div>
                    </div>
                                <div data-aos="fade-up" data-aos-duration="600">
                        <div class="instagram-item rounded-24 overflow-hidden position-relative">
                            <img src="assets/images/thumbs/instagram-img2.png" alt="">
                            <a href="https://www.instagram.com/" class="w-72 h-72 bg-black bg-opacity-50 text-white text-32 position-absolute top-50 start-50 translate-middle flex-center rounded-circle hover-bg-main-two-600 hover-text-white">
                                <i class="ph ph-instagram-logo"></i>
                            </a>
                        </div>
                    </div>
                                <div data-aos="fade-up" data-aos-duration="800">
                        <div class="instagram-item rounded-24 overflow-hidden position-relative">
                            <img src="assets/images/thumbs/instagram-img3.png" alt="">
                            <a href="https://www.instagram.com/" class="w-72 h-72 bg-black bg-opacity-50 text-white text-32 position-absolute top-50 start-50 translate-middle flex-center rounded-circle hover-bg-main-two-600 hover-text-white">
                                <i class="ph ph-instagram-logo"></i>
                            </a>
                        </div>
                    </div>
                                <div data-aos="fade-up" data-aos-duration="1000">
                        <div class="instagram-item rounded-24 overflow-hidden position-relative">
                            <img src="assets/images/thumbs/instagram-img4.png" alt="">
                            <a href="https://www.instagram.com/" class="w-72 h-72 bg-black bg-opacity-50 text-white text-32 position-absolute top-50 start-50 translate-middle flex-center rounded-circle hover-bg-main-two-600 hover-text-white">
                                <i class="ph ph-instagram-logo"></i>
                            </a>
                        </div>
                    </div>
                                <div data-aos="fade-up" data-aos-duration="1200">
                        <div class="instagram-item rounded-24 overflow-hidden position-relative">
                            <img src="assets/images/thumbs/instagram-img2.png" alt="">
                            <a href="https://www.instagram.com/" class="w-72 h-72 bg-black bg-opacity-50 text-white text-32 position-absolute top-50 start-50 translate-middle flex-center rounded-circle hover-bg-main-two-600 hover-text-white">
                                <i class="ph ph-instagram-logo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ================================ Instagram section end ===================================== -->
         
        <!-- =============================== Newsletter-two Section Start ============================ -->
        <div class="newsletter-two bg-neutral-600 py-32 overflow-hidden" data-aos="fade-up" data-aos-duration="600">
            <div class="container container-lg">
                <div class="flex-between gap-20 flex-wrap">
                    <div class="flex-align gap-22">
                        <span class="d-flex"><img src="assets/images/icon/envelop.png" alt=""></span>
                        <div>
                            <h5 class="text-white mb-12 fw-medium">Join Our Newsletter, Get 10% Off</h5>
                            <p class="text-white fw-light">Get all latest information on events, sales and offer</p>
                        </div>
                    </div>
                    <form action="#" class="newsletter-two__form w-50">
                        <div class="flex-align gap-16">
                            <input type="text" class="common-input style-two rounded-8 flex-grow-1 py-14" placeholder="Enter your email address">
                            <button type="submit" class="btn btn-main-two flex-shrink-0 rounded-8 py-16"> Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
include 'footer.php';
?>
<!-- Add this script after including jQuery and slick.js -->
<script>
$(document).ready(function(){
    $('.feature-three-item-wrapper').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: $('#feature-item-wrapper-prev'),
        nextArrow: $('#feature-item-wrapper-next'),
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
});
</script>
