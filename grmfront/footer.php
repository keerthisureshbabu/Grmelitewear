<?php

$kids = mysqli_query($conn, "SELECT id, name FROM kids ORDER BY name");
$women = mysqli_query($conn, "SELECT id, name FROM women ORDER BY name");
$toys = mysqli_query($conn, "SELECT id, name FROM toy ORDER BY name");
$accessories = mysqli_query($conn, "SELECT id, name FROM accessories ORDER BY name");

?>


<!-- ==================== Footer Two Start Here ==================== -->
<footer class="footer py-80 overflow-hidden">
    <div class="container container-lg">
        <div class="footer-item-two-wrapper d-flex align-items-start flex-wrap">
            <div class="footer-item max-w-275" data-aos="fade-up" data-aos-duration="200">
                <div class="footer-item__logo">
                    <a href="index.php"> <img src="assets\images\logo\grm logo.png" alt=""></a>
                </div>
                <p class="mb-24">Grm Elite Wear - Maternity Wear,Casual Wear, Kids Wear, Kids Products,New mom Collection,kids Toys,Born Baby Accessories,New Mom Accessories,Pregent Kit,Hospital kit</p>
                <div class="flex-align gap-16 mb-16">
                    <span class="w-32 h-32 flex-center rounded-circle border border-gray-100 text-main-two-600 text-md flex-shrink-0"><i class="ph-fill ph-phone-call"></i></span>
                    <a href="tel:+918754652625" class="text-md text-gray-900 hover-text-main-600"> +91 87546 52625</a>
                </div>
                <div class="flex-align gap-16 mb-16">
                    <span class="w-32 h-32 flex-center rounded-circle border border-gray-100 text-main-two-600 text-md flex-shrink-0"><i class="ph-fill ph-envelope"></i></span>
                    <a href="mailto:contact@info.com" class="text-md text-gray-900 hover-text-main-600">contact@info.com</a>
                </div>
                <div class="flex-align gap-16 mb-16">
                    <span class="w-32 h-32 flex-center rounded-circle border border-gray-100 text-main-two-600 text-md flex-shrink-0"><i class="ph-fill ph-map-pin"></i></span>
                    <span class="text-md text-gray-900 ">Rajalingam Nagar, Kadampadi (PO), Sulur Aero, Coimbatore – 641401</span>
                </div>
            </div>

                <div class="footer-item" data-aos="fade-up" data-aos-duration="300">
                    <h6 class="footer-item__title">Kid's</h6>
                    <ul class="footer-menu">
                        <?php while($row = mysqli_fetch_assoc($kids)) { ?>
                             <li class="common-dropdown__item nav-submenu__item"><a class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100" href="kids.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a></li>
                        <?php } ?>
                            
                    </ul>
                </div>

                <div class="footer-item" data-aos="fade-up" data-aos-duration="300">
                    <h6 class="footer-item__title">Women's</h6>
                    <ul class="footer-menu">
                        <?php while($row = mysqli_fetch_assoc($women)) { ?>
                             <li class="common-dropdown__item nav-submenu__item"><a class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100" href="women.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a></li>
                        <?php } ?>
                            
                    </ul>
                </div>

                 <div class="footer-item" data-aos="fade-up" data-aos-duration="300">
                    <h6 class="footer-item__title">Toy's</h6>
                    <ul class="footer-menu">
                        <?php while($row = mysqli_fetch_assoc($toys)) { ?>
                             <li class="common-dropdown__item nav-submenu__item"><a class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100" href="toys.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a></li>
                        <?php } ?>
                            
                    </ul>
                </div>

                 <div class="footer-item" data-aos="fade-up" data-aos-duration="300">
                    <h6 class="footer-item__title">Accessories</h6>
                    <ul class="footer-menu">
                        <?php while($row = mysqli_fetch_assoc($accessories)) { ?>
                             <li class="common-dropdown__item nav-submenu__item"><a class="common-dropdown__link nav-submenu__link text-heading-two hover-bg-neutral-100" href="accesscories.php?id=<?= $row['id'] ?>"><?= $row['name'] ?></a></li>
                        <?php } ?>
                            
                    </ul>
                </div>
                            
            <div class="footer-item" data-aos="fade-up" data-aos-duration="1200">
                <h6 class="">Location</h6>
                <p class="mb-16">Grm Elite Wear Location</p>
                <div class="flex-align gap-8 my-32">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3916.1445783326617!2d77.1340362!3d11.0277775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba8555df2b556c7%3A0x644e302bae9436c3!2sGRM%20ELITE%20WEAR!5e0!3m2!1sen!2sin!4v1753954765446!5m2!1sen!2sin" width="250" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                <ul class="flex-align gap-16">
                   
                    <li>
                        <a href="#" class="w-44 h-44 flex-center bg-main-two-50 text-main-two-600 text-xl rounded-8 hover-bg-main-two-600 hover-text-white">
                            <i class="ph-fill ph-whatsapp-logo"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="w-44 h-44 flex-center bg-main-two-50 text-main-two-600 text-xl rounded-8 hover-bg-main-two-600 hover-text-white">
                            <i class="ph-fill ph-instagram-logo"></i>
                        </a>
                    </li>
                     <li>
                        <a href="#" class="w-44 h-44 flex-center bg-main-two-50 text-main-two-600 text-xl rounded-8 hover-bg-main-two-600 hover-text-white">
                            <i class="ph-fill ph-facebook-logo"></i>
                        </a>
                    </li>
                     <li>
                        <a href="#" class="w-44 h-44 flex-center bg-main-two-50 text-main-two-600 text-xl rounded-8 hover-bg-main-two-600 hover-text-white">
                            <i class="ph-fill ph-youtube-logo"></i>
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
</footer>

<!-- bottom Footer -->
<div class="bottom-footer bg-color-three py-8">
    <div class="container container-lg">
        <div class="bottom-footer__inner flex-between flex-wrap gap-16 py-16">
            <p class="bottom-footer__text wow fadeInLeftBig">Designed by Keerthana As A freelancer </p>
            <div class="flex-align gap-8 flex-wrap wow fadeInRightBig">
                <span class="text-heading text-sm">We Are Accepting</span>
                <img src="assets/images/thumbs/payment-method.png" alt="">
            </div>
        </div>
    </div>
</div>
<!-- ==================== Footer Two End Here ==================== -->
    <!-- Jquery js -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="assets/js/boostrap.bundle.min.js"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="assets/js/phosphor-icon.js"></script>
    <!-- Select 2 -->
    <script src="assets/js/select2.min.js"></script>
    <!-- Slick js -->
    <script src="assets/js/slick.min.js"></script>
    <!-- count down js -->
    <script src="assets/js/count-down.js"></script>
    <!-- jquery UI js -->
    <script src="assets/js/jquery-ui.js"></script>
    <!-- wow js -->
    <script src="assets/js/wow.min.js"></script>
    <!-- AOS Animation -->
    <script src="assets/js/aos.js"></script>
    <!-- marque -->
    <script src="assets/js/marque.min.js"></script>
    <!-- marque -->
    <script src="assets/js/vanilla-tilt.min.js"></script>
    <!-- Counter -->
    <script src="assets/js/counter.min.js"></script>
    <!-- main js -->
    <script src="assets/js/main.js"></script>


</body>

</html>
