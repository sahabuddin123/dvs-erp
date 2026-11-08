<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$v_stats = function_exists('get_frontend_visitor_stats') ? get_frontend_visitor_stats() : ['total' => 672];
$default_inst = !empty($global_config['institute_name']) ? $global_config['institute_name'] : (!empty($branch['school_name']) ? $branch['school_name'] : 'School');
$app_title = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'application_title', $default_inst) : $default_inst;
$default_addr = !empty($global_config['address']) ? $global_config['address'] : (!empty($branch['address']) ? $branch['address'] : '');
$app_address = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'address', $default_addr) : $default_addr;
$app_tagline = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'site_tagline', '') : '';
$app_phone = !empty($cms_setting['mobile_no']) ? $cms_setting['mobile_no'] : (!empty($global_config['mobileno']) ? $global_config['mobileno'] : (!empty($branch['mobileno']) ? $branch['mobileno'] : ''));
$app_email = !empty($cms_setting['email']) ? $cms_setting['email'] : (!empty($global_config['institute_email']) ? $global_config['institute_email'] : (!empty($branch['email']) ? $branch['email'] : ''));
$copyright = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'copyright_text', '') : '';
if (empty($copyright)) {
    $copyright = ($current_lang == 'en') ? '© ' . date('Y') . ' ' . html_escape($app_title) . '. All rights reserved.' : 'স্বত্ব © ' . date('Y') . ' ' . html_escape($app_title) . '। সর্বস্বত্ব সংরক্ষিত।';
}
?>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <!-- Col 1: Brand & Social -->
            <div class="col-md-4 mb-4">
                <img src="<?php echo get_frontend_logo_url('dark'); ?>" onerror="this.onerror=null;this.src='<?php echo base_url('uploads/app_image/logo.png'); ?>';" alt="Logo" class="footer-logo">
                <h1><?php echo html_escape($app_title); ?></h1>
                <?php if (!empty($app_tagline)) { ?>
                    <p style="color: #9ca3af; font-size: 0.9rem; font-style: italic;"><?php echo html_escape($app_tagline); ?></p>
                <?php } ?>
                <div class="social-icons">
                    <?php if (!empty($cms_setting['facebook_url'])) { ?><a href="<?php echo html_escape($cms_setting['facebook_url']); ?>" target="_blank"><i class="fab fa-facebook"></i></a><?php } ?>
                    <?php if (!empty($cms_setting['linkedin_url'])) { ?><a href="<?php echo html_escape($cms_setting['linkedin_url']); ?>" target="_blank"><i class="fab fa-linkedin"></i></a><?php } ?>
                    <?php if (!empty($cms_setting['youtube_url'])) { ?><a href="<?php echo html_escape($cms_setting['youtube_url']); ?>" target="_blank"><i class="fab fa-youtube"></i></a><?php } ?>
                    <?php if (!empty($cms_setting['twitter_url'])) { ?><a href="<?php echo html_escape($cms_setting['twitter_url']); ?>" target="_blank"><i class="fab fa-twitter"></i></a><?php } ?>
                    <?php if (!empty($cms_setting['instagram_url'])) { ?><a href="<?php echo html_escape($cms_setting['instagram_url']); ?>" target="_blank"><i class="fab fa-instagram"></i></a><?php } ?>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-md-3 mb-4">
                <h4><i class="fas fa-link"></i> <?php echo translate('quick_links'); ?></h4>
                <ul style="list-style: none; padding: 0;">
                    <li class="mb-2"><a href="<?php echo base_url(); ?>"><i class="fas fa-arrow-right"></i> <?php echo translate('home'); ?></a></li>
                    <li class="mb-2"><a href="<?php echo base_url('about'); ?>"><i class="fas fa-arrow-right"></i> <?php echo translate('about_us'); ?></a></li>
                    <li class="mb-2"><a href="<?php echo base_url('contact'); ?>"><i class="fas fa-arrow-right"></i> <?php echo translate('contact_us'); ?></a></li>
                    <li class="mb-2"><a href="<?php echo base_url('admission'); ?>"><i class="fas fa-arrow-right"></i> <?php echo translate('online_admission'); ?></a></li>
                    <li class="mb-2"><a href="<?php echo base_url('notice'); ?>"><i class="fas fa-arrow-right"></i> <?php echo translate('notice'); ?></a></li>
                </ul>
            </div>

            <!-- Col 3: Contact Us -->
            <div class="col-md-3 mb-4">
                <h4><i class="fas fa-envelope"></i> <?php echo translate('contact_us'); ?></h4>
                <p style="color: #d1d5db; margin: 10px 0;"><i class="fas fa-map-marker-alt"></i> <?php echo html_escape($app_address); ?></p>
                <p style="color: #d1d5db; margin: 10px 0;"><i class="fas fa-phone"></i> <?php echo html_escape($app_phone); ?></p>
                <p style="color: #d1d5db; margin: 10px 0;"><i class="fas fa-envelope"></i> <?php echo html_escape($app_email); ?></p>
            </div>

            <!-- Col 4: Visitors -->
            <div class="col-md-2 mb-4">
                <h4><i class="fas fa-eye"></i> <?php echo translate('visitor_statistics'); ?></h4>
                <div style="background-color: rgba(255,255,255,0.1); border-radius: 8px; padding: 20px; text-align: center;">
                    <small style="color: #d1d5db;"><?php echo translate('total_visitors'); ?></small>
                    <h5 style="color: #60a5fa; font-weight: 700; margin: 10px 0; font-size: 1.25rem;"><?php echo $v_stats['total']; ?></h5>
                </div>
            </div>
        </div>

        <div class="copyright">
            <p><?php echo $copyright; ?></p>
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
