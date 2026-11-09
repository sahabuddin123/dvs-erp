<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Contact' : 'যোগাযোগ')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : ($current_lang == 'en' ? 'Contact' : 'যোগাযোগ'));
$page_subtitle = function_exists('get_lang_field') ? get_lang_field($page_data, 'box_title', ($current_lang == 'en' ? 'Get in touch with us' : 'আমাদের সাথে যোগাযোগ করুন')) : (!empty($page_data['box_title']) ? $page_data['box_title'] : ($current_lang == 'en' ? 'Get in touch with us' : 'আমাদের সাথে যোগাযোগ করুন'));
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <?php if (!empty($page_subtitle)) { ?>
                    <p class="page-header-subtitle text-muted mb-0 mt-2"><?php echo html_escape($page_subtitle); ?></p>
                <?php } ?>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Contact Section -->
<section class="section py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="row">
            <!-- Location Map -->
            <div class="col-lg-12 mb-5">
                <div class="section-title">
                    <h2><?php echo ($current_lang == 'en') ? 'Our Location' : 'আমাদের অবস্থান'; ?></h2>
                    <p><?php echo ($current_lang == 'en') ? 'Visit us at our campus' : 'আমাদের ক্যাম্পাসে আসুন'; ?></p>
                </div>
                <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3682.4847314391927!2d91.12218209999999!3d22.635708899999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80f4d58065734865%3A0x98218f55de0a6065!2sDream%20Valley%20International%20School!5e0!3m2!1sen!2sbd!4v1789839398614!5m2!1sen!2sbd"
                        width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- Contact Form Card -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 p-4 p-md-5 h-100" style="border-radius: 12px; background: #fff;">
                    <h3 class="mb-4" style="color: var(--primary-color); font-weight: 700;">
                        <?php echo ($current_lang == 'en') ? 'Send us a Message' : 'আমাদের বার্তা পাঠান'; ?>
                    </h3>

                    <?php if($this->session->flashdata('msg_success')): ?>
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i> <?php echo $this->session->flashdata('msg_success'); ?>
                    </div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('msg_error')): ?>
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $this->session->flashdata('msg_error'); ?>
                    </div>
                    <?php endif; ?>

                    <?php echo form_open($this->uri->uri_string(), array('class' => 'contact-form')); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                <?php echo ($current_lang == 'en') ? 'Full Name' : 'পূর্ণ নাম'; ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php echo set_value('name'); ?>" required />
                            <span class="text-danger small"><?php echo form_error('name'); ?></span>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <?php echo ($current_lang == 'en') ? 'Email Address' : 'ইমেইল ঠিকানা'; ?> <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" required />
                            <span class="text-danger small"><?php echo form_error('email'); ?></span>
                        </div>

                        <div class="mb-3">
                            <label for="phoneno" class="form-label fw-semibold">
                                <?php echo ($current_lang == 'en') ? 'Phone Number' : 'ফোন নম্বর'; ?> <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control" name="phoneno" id="phoneno" value="<?php echo set_value('phoneno'); ?>" required />
                            <span class="text-danger small"><?php echo form_error('phoneno'); ?></span>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label fw-semibold">
                                <?php echo ($current_lang == 'en') ? 'Subject' : 'বিষয়'; ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="subject" id="subject" value="<?php echo set_value('subject'); ?>" required />
                            <span class="text-danger small"><?php echo form_error('subject'); ?></span>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label fw-semibold">
                                <?php echo ($current_lang == 'en') ? 'Message' : 'বার্তা'; ?> <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" rows="5" name="message" id="message" required><?php echo set_value('message'); ?></textarea>
                            <span class="text-danger small"><?php echo form_error('message'); ?></span>
                        </div>

                        <?php if (isset($cms_setting['captcha_status']) && $cms_setting['captcha_status'] == 'enable' && isset($recaptcha)): ?>
                        <div class="mb-3">
                            <?php echo $recaptcha['widget']; echo $recaptcha['script']; ?>
                            <span class="text-danger small"><?php echo form_error('g-recaptcha-response'); ?></span>
                        </div>
                        <?php endif; ?>

                        <div>
                            <button type="submit" name="submit" value="1" class="btn text-white px-4 py-2" style="background-color: var(--primary-color); border-radius: 8px; font-weight: 600;">
                                <i class="fas fa-paper-plane me-2"></i> <?php echo ($current_lang == 'en') ? 'Send Message' : 'বার্তা পাঠান'; ?>
                            </button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="col-lg-6 mb-4">
                <div class="contact-info-box shadow-sm h-100" style="border-radius: 12px; background: #fff; padding: 2.5rem;">
                    <h3 class="contact-info-title mb-4" style="color: var(--primary-color); font-weight: 700;">
                        <?php echo ($current_lang == 'en') ? 'Contact Information' : 'যোগাযোগের তথ্য'; ?>
                    </h3>

                    <ul class="contact-info-list list-unstyled mb-4">
                        <li class="contact-info-item d-flex align-items-start mb-4">
                            <div class="contact-info-icon me-3" style="width: 48px; height: 48px; border-radius: 50%; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.25rem; flex-shrink: 0;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-info-text">
                                <h5 class="mb-1 fw-bold text-dark"><?php echo ($current_lang == 'en') ? 'Address' : 'ঠিকানা'; ?></h5>
                                <p class="text-muted mb-0">
                                    <?php echo nl2br(html_escape(get_lang_field($cms_setting, 'address', 'চরবাটা, সুবর্ণচর, নোয়াখালী'))); ?>
                                </p>
                            </div>
                        </li>

                        <li class="contact-info-item d-flex align-items-start mb-4">
                            <div class="contact-info-icon me-3" style="width: 48px; height: 48px; border-radius: 50%; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.25rem; flex-shrink: 0;">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-info-text">
                                <h5 class="mb-1 fw-bold text-dark"><?php echo ($current_lang == 'en') ? 'Phone' : 'ফোন'; ?></h5>
                                <p class="text-muted mb-0">
                                    <?php echo !empty($cms_setting['mobile_no']) ? html_escape($cms_setting['mobile_no']) : '+880 1845 746454'; ?>
                                </p>
                            </div>
                        </li>

                        <li class="contact-info-item d-flex align-items-start mb-4">
                            <div class="contact-info-icon me-3" style="width: 48px; height: 48px; border-radius: 50%; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.25rem; flex-shrink: 0;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-info-text">
                                <h5 class="mb-1 fw-bold text-dark"><?php echo ($current_lang == 'en') ? 'Email' : 'ইমেইল'; ?></h5>
                                <p class="text-muted mb-0">
                                    <?php echo !empty($cms_setting['email']) ? html_escape($cms_setting['email']) : (!empty($global_config['email']) ? html_escape($global_config['email']) : ''); ?>
                                </p>
                            </div>
                        </li>
                    </ul>

                    <div class="pt-4 border-top">
                        <h6 class="fw-bold mb-3 text-dark"><?php echo ($current_lang == 'en') ? 'Follow Us' : 'আমাদের সাথে যুক্ত থাকুন'; ?></h6>
                        <div class="d-flex gap-2">
                            <a href="<?php echo !empty($cms_setting['facebook_url']) ? html_escape($cms_setting['facebook_url']) : 'https://www.facebook.com/'; ?>" target="_blank" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; color: #1877f2;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="<?php echo !empty($cms_setting['linkedin_url']) ? $cms_setting['linkedin_url'] : 'https://www.linkedin.com/'; ?>" target="_blank" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; color: #0a66c2;">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="<?php echo !empty($cms_setting['youtube_url']) ? $cms_setting['youtube_url'] : 'https://www.youtube.com/'; ?>" target="_blank" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; color: #ff0000;">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
