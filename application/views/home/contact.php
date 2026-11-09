<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 

$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Contact Us' : 'যোগাযোগ')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : ($current_lang == 'en' ? 'Contact Us' : 'যোগাযোগ'));
$page_subtitle = function_exists('get_lang_field') ? get_lang_field($page_data, 'box_title', ($current_lang == 'en' ? 'Get in touch with us' : 'আমাদের সাথে যোগাযোগ করুন')) : (!empty($page_data['box_title']) ? $page_data['box_title'] : ($current_lang == 'en' ? 'Get in touch with us' : 'আমাদের সাথে যোগাযোগ করুন'));

// Parse dynamic Google Map embed URL
$raw_map = !empty($page_data['map_iframe']) ? trim($page_data['map_iframe']) : '';
$default_map_url = 'https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d304.80548393016215!2d91.0281643742625!3d22.70338703988637!2m3!1f0!2f39.425130870358664!3f0!3m2!1i1024!2i768!4f35!2m1!1schar%20hasan%20islamia%20dakhil%20madrasah!5e1!3m2!1sen!2sbd!4v1692559717334!5m2!1sen!2sbd';

if (empty($raw_map)) {
    $map_embed_url = $default_map_url;
} elseif (preg_match('/src=["\']([^"\']+)["\']/i', $raw_map, $map_matches)) {
    $map_embed_url = $map_matches[1];
} else {
    $map_embed_url = $raw_map;
}

// Dynamic contact data from front_cms_contact with fallback
$contact_address = !empty($page_data['address']) ? $page_data['address'] : (!empty($cms_setting['address']) ? $cms_setting['address'] : 'গ্রাম: চর হাসান, পো: পশ্চিম চর জব্বর, সুবর্ণচর, নোয়াখালী।');
$contact_phone = !empty($page_data['phone']) ? $page_data['phone'] : (!empty($cms_setting['mobile_no']) ? $cms_setting['mobile_no'] : '+88018855-295441');
$contact_email = !empty($page_data['email']) ? $page_data['email'] : (!empty($cms_setting['email']) ? $cms_setting['email'] : 'charhasan1996@gmail.com');
$form_title = !empty($page_data['form_title']) ? $page_data['form_title'] : (($current_lang == 'en') ? 'Send us a Message' : 'আমাদের বার্তা পাঠান');
$submit_text = !empty($page_data['submit_text']) ? $page_data['submit_text'] : (($current_lang == 'en') ? 'Send Message' : 'বার্তা পাঠান');
$box_title = !empty($page_data['box_title']) ? $page_data['box_title'] : (($current_lang == 'en') ? 'Contact Information' : 'যোগাযোগের তথ্য');
$box_description = !empty($page_data['box_description']) ? $page_data['box_description'] : (($current_lang == 'en') ? 'Feel free to contact us with any inquiries or feedback.' : 'আপনার যেকোনো অভিযোগ বা পরামর্শ আমাদের সাথে শেয়ার করতে নিচের ফর্মটি পূরণ করুন।');

if (!function_exists('contact_to_bn_digits')) {
    function contact_to_bn_digits($number) {
        $en = ['0','1','2','3','4','5','6','7','8','9'];
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        return str_replace($en, $bn, (string)$number);
    }
}
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
            <!-- Dynamic Location Map -->
            <div class="col-lg-12 mb-5">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <div>
                        <h2 class="h3 fw-bold mb-1" style="color: var(--primary-color);">
                            <i class="fas fa-map-marked-alt me-2"></i> <?php echo ($current_lang == 'en') ? 'Our Location' : 'আমাদের অবস্থান'; ?>
                        </h2>
                        <p class="text-muted mb-0"><?php echo ($current_lang == 'en') ? 'Visit us at our campus: Char Hasan Islamia Dakhil Madrasah' : 'আমাদের ক্যাম্পাসে আসুন: চর হাসান ইসলামিয়া দাখিল মাদরাসা, সুবর্ণচর, নোয়াখালী'; ?></p>
                    </div>
                    <div>
                        <a href="https://www.google.com/maps/search/?api=1&query=char+hasan+islamia+dakhil+madrasah+subarnachar+noakhali" target="_blank" class="btn btn-outline-primary btn-sm px-3 py-2" style="border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-external-link-alt me-1"></i> <?php echo ($current_lang == 'en') ? 'Open in Google Maps' : 'গুগল ম্যাপসে খুলুন'; ?>
                        </a>
                    </div>
                </div>
                <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                    <iframe
                        src="<?php echo html_escape($map_embed_url); ?>"
                        width="100%" height="420" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- Contact Form Card -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm border-0 p-4 p-md-5 h-100" style="border-radius: 14px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                    <div class="mb-4 border-bottom pb-3">
                        <h3 class="mb-1" style="color: var(--primary-color); font-weight: 700;">
                            <?php echo html_escape($form_title); ?>
                        </h3>
                        <?php if (!empty($box_description)): ?>
                            <p class="text-muted mb-0 small"><?php echo html_escape($box_description); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if($this->session->flashdata('msg_success')): ?>
                    <div class="alert alert-success d-flex align-items-center mb-4" style="border-radius: 8px;">
                        <i class="fas fa-check-circle fa-lg me-2"></i> 
                        <div><?php echo $this->session->flashdata('msg_success'); ?></div>
                    </div>
                    <?php endif; ?>

                    <?php if($this->session->flashdata('msg_error')): ?>
                    <div class="alert alert-danger d-flex align-items-center mb-4" style="border-radius: 8px;">
                        <i class="fas fa-exclamation-circle fa-lg me-2"></i> 
                        <div><?php echo $this->session->flashdata('msg_error'); ?></div>
                    </div>
                    <?php endif; ?>

                    <?php echo form_open(current_url(), array('class' => 'contact-form', 'id' => 'contactSubmissionForm')); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    <?php echo ($current_lang == 'en') ? 'Full Name' : 'পূর্ণ নাম'; ?> <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="name" id="name" value="<?php echo set_value('name'); ?>" placeholder="<?php echo ($current_lang == 'en') ? 'e.g. Mohammad Abdullah' : 'যেমন: মোহাম্মদ আব্দুল্লাহ'; ?>" required />
                                <span class="text-danger small"><?php echo form_error('name'); ?></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <?php echo ($current_lang == 'en') ? 'Email Address' : 'ইমেইল ঠিকানা'; ?> <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" placeholder="example@email.com" required />
                                <span class="text-danger small"><?php echo form_error('email'); ?></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phoneno" class="form-label fw-semibold">
                                    <?php echo ($current_lang == 'en') ? 'Phone Number' : 'ফোন নম্বর'; ?> <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control" name="phoneno" id="phoneno" value="<?php echo set_value('phoneno'); ?>" placeholder="018XXXXXXXX" required />
                                <span class="text-danger small"><?php echo form_error('phoneno'); ?></span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label fw-semibold">
                                    <?php echo ($current_lang == 'en') ? 'Subject' : 'বিষয়'; ?> <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="subject" id="subject" value="<?php echo set_value('subject'); ?>" placeholder="<?php echo ($current_lang == 'en') ? 'Message subject' : 'বার্তার বিষয়'; ?>" required />
                                <span class="text-danger small"><?php echo form_error('subject'); ?></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label fw-semibold">
                                <?php echo ($current_lang == 'en') ? 'Message' : 'বার্তা'; ?> <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" rows="4" name="message" id="message" placeholder="<?php echo ($current_lang == 'en') ? 'Write your message details here...' : 'আপনার বার্তা বা প্রশ্ন বিস্তারিত লিখুন...'; ?>" required><?php echo set_value('message'); ?></textarea>
                            <span class="text-danger small"><?php echo form_error('message'); ?></span>
                        </div>

                        <!-- Manual Math Captcha -->
                        <div class="mb-4 p-3 rounded-3" style="background: #f8fafc; border: 1.5px dashed #cbd5e1;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label for="math_captcha" class="form-label fw-bold mb-0 text-dark">
                                    <i class="fas fa-shield-alt text-success me-1"></i> <?php echo ($current_lang == 'en') ? 'Human Verification (Math Captcha)' : 'নিরাপত্তা যাচাই (গণিত ক্যাপচা)'; ?> <span class="text-danger">*</span>
                                </label>
                                <button type="button" id="btnRefreshCaptcha" class="btn btn-sm btn-light border py-1 px-2" style="font-size: 0.8rem; border-radius: 6px;" title="<?php echo ($current_lang == 'en') ? 'Refresh Captcha' : 'নতুন সংখ্যা আনুন'; ?>">
                                    <i class="fas fa-sync-alt me-1"></i> <?php echo ($current_lang == 'en') ? 'Refresh' : 'নতুন সংখ্যা'; ?>
                                </button>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text fw-bold text-primary" id="captchaQuestionBadge" style="background: #e2e8f0; font-size: 1.25rem; min-width: 140px; justify-content: center; letter-spacing: 2px;">
                                    <?php 
                                    if ($current_lang == 'bn') {
                                        echo contact_to_bn_digits($captcha_num1) . ' + ' . contact_to_bn_digits($captcha_num2) . ' = ?';
                                    } else {
                                        echo $captcha_num1 . ' + ' . $captcha_num2 . ' = ?';
                                    }
                                    ?>
                                </span>
                                <input type="text" inputmode="numeric" name="math_captcha" id="math_captcha" class="form-control form-control-lg" placeholder="<?php echo ($current_lang == 'en') ? 'Enter the sum result' : 'যোগফলটি লিখুন'; ?>" required autocomplete="off" />
                            </div>
                            <div class="form-text small text-muted mt-1">
                                <i class="fas fa-info-circle text-info"></i> <?php echo ($current_lang == 'en') ? 'Spam prevention: Calculate the sum of the two numbers above and enter the answer.' : 'স্প্যাম রোধে উপরের সংখ্যা দুটির সঠিক যোগফল লিখুন। বাংলা বা ইংরেজি যেকোনো সংখ্যা সমর্থনযোগ্য।'; ?>
                            </div>
                            <span class="text-danger small"><?php echo form_error('math_captcha'); ?></span>
                        </div>

                        <div>
                            <button type="submit" id="btnSubmitContact" class="btn text-white px-5 py-2" style="background-color: var(--primary-color); border-radius: 8px; font-weight: 600; font-size: 1.05rem;">
                                <i class="fas fa-paper-plane me-2"></i> <?php echo html_escape($submit_text); ?>
                            </button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="col-lg-5 mb-4">
                <div class="contact-info-box shadow-sm h-100" style="border-radius: 14px; background: #fff; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                    <h3 class="contact-info-title mb-4" style="color: var(--primary-color); font-weight: 700; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px;">
                        <?php echo html_escape($box_title); ?>
                    </h3>

                    <ul class="contact-info-list list-unstyled mb-4">
                        <li class="contact-info-item d-flex align-items-start mb-4">
                            <div class="contact-info-icon me-3" style="width: 48px; height: 48px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.25rem; flex-shrink: 0;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-info-text">
                                <h5 class="mb-1 fw-bold text-dark"><?php echo ($current_lang == 'en') ? 'Campus Address' : 'ক্যাম্পাসের ঠিকানা'; ?></h5>
                                <p class="text-muted mb-0" style="line-height: 1.6;">
                                    <?php echo nl2br(html_escape($contact_address)); ?>
                                </p>
                            </div>
                        </li>

                        <li class="contact-info-item d-flex align-items-start mb-4">
                            <div class="contact-info-icon me-3" style="width: 48px; height: 48px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.25rem; flex-shrink: 0;">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-info-text">
                                <h5 class="mb-1 fw-bold text-dark"><?php echo ($current_lang == 'en') ? 'Phone Numbers' : 'মোবাইল / টেলিফোন'; ?></h5>
                                <div class="text-muted mb-0">
                                    <?php 
                                    $phones = preg_split('/[\r\n,]+/', $contact_phone);
                                    foreach ($phones as $p_item):
                                        $clean_p = trim($p_item);
                                        if (empty($clean_p)) continue;
                                    ?>
                                        <div>
                                            <a href="tel:<?php echo html_escape($clean_p); ?>" class="text-decoration-none text-dark fw-semibold">
                                                <i class="fas fa-phone text-success small me-1"></i> <?php echo html_escape($clean_p); ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </li>

                        <li class="contact-info-item d-flex align-items-start mb-4">
                            <div class="contact-info-icon me-3" style="width: 48px; height: 48px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.25rem; flex-shrink: 0;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-info-text">
                                <h5 class="mb-1 fw-bold text-dark"><?php echo ($current_lang == 'en') ? 'Email Address' : 'ইমেইল ঠিকানা'; ?></h5>
                                <div class="text-muted mb-0">
                                    <?php 
                                    $emails = preg_split('/[\r\n,]+/', $contact_email);
                                    foreach ($emails as $e_item):
                                        $clean_e = trim($e_item);
                                        if (empty($clean_e)) continue;
                                    ?>
                                        <div>
                                            <a href="mailto:<?php echo html_escape($clean_e); ?>" class="text-decoration-none text-dark fw-semibold">
                                                <i class="fas fa-envelope text-primary small me-1"></i> <?php echo html_escape($clean_e); ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </li>

                        <!-- Office Hours -->
                        <li class="contact-info-item d-flex align-items-start mb-4">
                            <div class="contact-info-icon me-3" style="width: 48px; height: 48px; border-radius: 10px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.25rem; flex-shrink: 0;">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="contact-info-text">
                                <h5 class="mb-1 fw-bold text-dark"><?php echo ($current_lang == 'en') ? 'Madrasah Hours' : 'কার্যকালীন সময়'; ?></h5>
                                <p class="text-muted mb-0" style="line-height: 1.6;">
                                    <?php echo ($current_lang == 'en') ? 'Saturday - Thursday: 8:00 AM - 4:00 PM<br>(Friday Closed)' : 'শনিবার - বৃহস্পতিবার: সকাল ৮:০০ - বিকাল ৪:০০<br>(শুক্রবার সাপ্তাহিক বন্ধ)'; ?>
                                </p>
                            </div>
                        </li>
                    </ul>

                    <div class="pt-4 border-top">
                        <h6 class="fw-bold mb-3 text-dark"><?php echo ($current_lang == 'en') ? 'Connect With Us' : 'সামাজিক যোগাযোগ মাধ্যম'; ?></h6>
                        <div class="d-flex gap-2">
                            <a href="<?php echo !empty($cms_setting['facebook_url']) ? html_escape($cms_setting['facebook_url']) : 'https://www.facebook.com/'; ?>" target="_blank" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; color: #1877f2;" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="<?php echo !empty($cms_setting['youtube_url']) ? $cms_setting['youtube_url'] : 'https://www.youtube.com/'; ?>" target="_blank" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; color: #ff0000;" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="<?php echo !empty($cms_setting['linkedin_url']) ? $cms_setting['linkedin_url'] : 'https://www.linkedin.com/'; ?>" target="_blank" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; color: #0a66c2;" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var btnRefresh = document.getElementById('btnRefreshCaptcha');
    if (btnRefresh) {
        btnRefresh.addEventListener('click', function(e) {
            e.preventDefault();
            var icon = this.querySelector('i');
            if (icon) icon.classList.add('fa-spin');
            
            fetch('<?php echo base_url("refresh-contact-captcha"); ?>')
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data && data.status === 'success') {
                        var isBn = <?php echo ($current_lang == 'bn') ? 'true' : 'false'; ?>;
                        var badge = document.getElementById('captchaQuestionBadge');
                        if (badge) {
                            badge.textContent = isBn ? data.question_bn : data.question_en;
                        }
                        var input = document.getElementById('math_captcha');
                        if (input) {
                            input.value = '';
                            input.focus();
                        }
                    }
                })
                .catch(function(err) {
                    console.error('Captcha refresh failed:', err);
                })
                .finally(function() {
                    if (icon) icon.classList.remove('fa-spin');
                });
        });
    }

    var form = document.getElementById('contactSubmissionForm');
    if (form) {
        form.addEventListener('submit', function() {
            var submitBtn = document.getElementById('btnSubmitContact');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> <?php echo ($current_lang == 'en') ? 'Sending...' : 'বার্তা পাঠানো হচ্ছে...'; ?>';
            }
        });
    }
});
</script>
