<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
?>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <!-- Col 1: Brand & Social -->
            <div class="col-md-4 mb-4">
                <img src="<?php echo base_url('assets/frontend/dvs/storage/uploads/settings/logo_dark_1781374935.png'); ?>" alt="Logo" class="footer-logo">
                <h1><?php echo ($current_lang == 'en') ? 'Dream Valley School' : 'ড্রিম ভ্যালী স্কুল'; ?></h1>
                <p style="color: #9ca3af; font-size: 0.9rem; font-style: italic;"></p>
                <div class="social-icons">
                    <a href="https://www.facebook.com/dreamvalleyintschool" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-linkedin"></i></a>
                    <a href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-md-3 mb-4">
                <h4><i class="fas fa-link"></i> Quick Links</h4>
                <ul style="list-style: none; padding: 0;">
                    <li class="mb-2"><a href="<?php echo base_url(); ?>"><i class="fas fa-arrow-right"></i> <?php echo ($current_lang == 'en') ? 'Home' : 'হোম'; ?></a></li>
                    <li class="mb-2"><a href="<?php echo base_url('about'); ?>"><i class="fas fa-arrow-right"></i> <?php echo ($current_lang == 'en') ? 'About Us' : 'আমাদের সম্পর্কে'; ?></a></li>
                    <li class="mb-2"><a href="<?php echo base_url('contact'); ?>"><i class="fas fa-arrow-right"></i> <?php echo ($current_lang == 'en') ? 'Contact Us' : 'যোগাযোগ'; ?></a></li>
                    <li class="mb-2"><a href="<?php echo base_url('admission'); ?>"><i class="fas fa-arrow-right"></i> <?php echo ($current_lang == 'en') ? 'Admission' : 'ভর্তি'; ?></a></li>
                    <li class="mb-2"><a href="<?php echo base_url('events'); ?>"><i class="fas fa-arrow-right"></i> <?php echo ($current_lang == 'en') ? 'Notices' : 'নোটিশ'; ?></a></li>
                </ul>
            </div>

            <!-- Col 3: Contact Us -->
            <div class="col-md-3 mb-4">
                <h4><i class="fas fa-envelope"></i> Contact Us</h4>
                <p style="color: #d1d5db; margin: 10px 0;"><i class="fas fa-map-marker-alt"></i> <?php echo ($current_lang == 'en') ? 'Charbata, Subarnachar, Noakhali' : 'চরবাটা, সুবর্ণচর, নোয়াখালী'; ?></p>
                <p style="color: #d1d5db; margin: 10px 0;"><i class="fas fa-phone"></i> +880 1845 746454</p>
                <p style="color: #d1d5db; margin: 10px 0;"><i class="fas fa-envelope"></i> info@dreamvalleyschool.com</p>
            </div>

            <!-- Col 4: Visitors -->
            <div class="col-md-2 mb-4">
                <h4><i class="fas fa-eye"></i> Visitors</h4>
                <div style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 20px; text-align: center;">
                    <small style="color: #d1d5db;"><?php echo ($current_lang == 'en') ? 'Total Visitors' : 'মোট পরিদর্শনকারী'; ?></small>
                    <h5 style="color: #60a5fa; font-weight: 700; margin: 10px 0; font-size: 1.25rem;">672</h5>
                </div>
            </div>
        </div>

        <div class="copyright">
            <p><?php echo ($current_lang == 'en') ? '© 2026 Dream Valley School. All rights reserved. | Developed by <a href="https://innographixbd.com/" target="_blank" style="color: #60a5fa;">Innographix BD</a>' : '© 2026 Dream Valley School. All rights reserved. | কারিগরি সহায়তায় <a href="https://innographixbd.com/" target="_blank" style="color: #60a5fa;">ইনোগ্রাফিক্স বিডি</a>'; ?></p>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Magnific Popup JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<!-- Slick Carousel JS -->
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!-- Plyr JS -->
<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
<!-- DVS Custom JS -->
<script src="<?php echo base_url('assets/frontend/dvs/js/main.js'); ?>"></script>
