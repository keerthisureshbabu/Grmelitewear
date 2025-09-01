<?php
include "header.php";
?>

<div class="map-responsive">
  <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3916.1445783326617!2d77.1340362!3d11.0277775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba8555df2b556c7%3A0x644e302bae9436c3!2sGRM%20ELITE%20WEAR!5e0!3m2!1sen!2sin!4v1753954765446!5m2!1sen!2sin"
    allowfullscreen=""
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
</div>

<section class="contact py-80">
    <div class="container container-lg">
        <div class="row gy-5">
            <div class="col-lg-8">
                <div class="contact-box border border-gray-100 rounded-16 px-24 py-40">
                    <form action="#">
                        <h6 class="mb-32">Make Custom Request</h6>
                        <div class="row gy-4">
                            <div class="col-sm-6 col-xs-6">
                                <label for="name" class="flex-align gap-4 text-sm font-heading-two text-gray-900 fw-semibold mb-4">Full Name <span class="text-danger text-xl line-height-1">*</span> </label>
                                <input type="text" class="common-input px-16" id="name" placeholder="Full name">
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <label for="email" class="flex-align gap-4 text-sm font-heading-two text-gray-900 fw-semibold mb-4">Email Address <span class="text-danger text-xl line-height-1">*</span> </label>
                                <input type="email" class="common-input px-16" id="email" placeholder="Email address">
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <label for="phone" class="flex-align gap-4 text-sm font-heading-two text-gray-900 fw-semibold mb-4">Phone Number<span class="text-danger text-xl line-height-1">*</span> </label>
                                <input type="number" class="common-input px-16" id="phone" placeholder="Phone Number*">
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <label for="subject" class="flex-align gap-4 text-sm font-heading-two text-gray-900 fw-semibold mb-4">Subject <span class="text-danger text-xl line-height-1">*</span> </label>
                                <input type="text" class="common-input px-16" id="subject" placeholder="Subject">
                            </div>
                            <div class="col-sm-12">
                                <label for="message" class="flex-align gap-4 text-sm font-heading-two text-gray-900 fw-semibold mb-4">Message <span class="text-danger text-xl line-height-1">*</span> </label>
                                <textarea class="common-input px-16" id="message" placeholder="Type your message"></textarea>
                            </div>
                            <div class="col-sm-12 mt-32">
                                <button type="submit" class="btn btn-main py-18 px-32 rounded-8">Get a Quote</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-box border border-gray-100 rounded-16 px-24 py-40">
                    <h6 class="mb-48">Get In Touch</h6>
                    <div class="flex-align gap-16 mb-16">
                        <span class="w-40 h-40 flex-center rounded-circle border border-gray-100 text-main-two-600 text-2xl flex-shrink-0"><i class="ph-fill ph-phone-call"></i></span>
                        <a href="tel:+918754652625" class="text-md text-gray-900 hover-text-main-600">8754652625</a>
                    </div>
                    <div class="flex-align gap-16 mb-16">
                        <span class="w-40 h-40 flex-center rounded-circle border border-gray-100 text-main-two-600 text-2xl flex-shrink-0"><i class="ph-fill ph-envelope"></i></span>
                        <a href="mailto:contact@info.com" class="text-md text-gray-900 hover-text-main-600">contact@info.com</a>
                    </div>
                    <div class="flex-align gap-16 mb-0">
                        <span class="w-40 h-40 flex-center rounded-circle border border-gray-100 text-main-two-600 text-2xl flex-shrink-0"><i class="ph-fill ph-map-pin"></i></span>
                        <span class="text-md text-gray-900 ">Rajalingam Nagar, Kadampadi (PO), Sulur Aero, Coimbatore – 641401</span>
                    </div>
                </div>
                <div class="mt-24 flex-align flex-wrap gap-16">
                    <a href="#" class="bg-neutral-600 hover-bg-main-600 rounded-8 p-10 px-16 flex-between flex-wrap gap-8 flex-grow-1">
                        <span class="text-white fw-medium">Get Support On Call</span>
                        <span class="w-36 h-36 bg-main-600 rounded-8 flex-center text-xl text-white"><i class="ph ph-headset"></i></span>
                    </a>
                    <a href="#" class="bg-neutral-600 hover-bg-main-600 rounded-8 p-10 px-16 flex-between flex-wrap gap-8 flex-grow-1">
                        <span class="text-white fw-medium">Get Direction</span>
                        <span class="w-36 h-36 bg-main-600 rounded-8 flex-center text-xl text-white"><i class="ph ph-map-pin"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
include ("footer.php");
?>