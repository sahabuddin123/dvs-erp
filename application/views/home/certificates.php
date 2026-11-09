<?php 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn'; 
$is_en = ($current_lang === 'en');

$default_page_title = $is_en ? 'Certificate & Testimonial Verification' : 'প্রশংসাপত্র ও সনদপত্র যাচাই';
$title = function_exists('get_lang_field') 
    ? get_lang_field($page_data, 'page_title', $default_page_title) 
    : (!empty($page_data['page_title']) ? $page_data['page_title'] : $default_page_title);

$subtitle = function_exists('get_lang_field') 
    ? get_lang_field($page_data, 'description', ($is_en ? 'Search, verify, and print academic certificates and testimonials for students of Char Hasan Islamia Dakhil Madrasah.' : 'চর হাসান ইসলামিয়া দাখিল মাদরাসার শিক্ষার্থীদের বিভিন্ন সনদ ও প্রশংসাপত্র অনলাইন থেকে তাৎক্ষণিক অনুসন্ধান, যাচাই ও প্রিন্ট করুন।')) 
    : (!empty($page_data['description']) ? $page_data['description'] : ($is_en ? 'Search, verify, and print academic certificates and testimonials for students.' : 'চর হাসান ইসলামিয়া দাখিল মাদরাসার শিক্ষার্থীদের বিভিন্ন সনদ ও প্রশংসাপত্র অনলাইন থেকে তাৎক্ষণিক অনুসন্ধান, যাচাই ও প্রিন্ট করুন।'));
?>
<script type="text/javascript" src="<?php echo base_url('assets/js/certificate.js?v=' . version_combine()) ?>"></script>

<style type="text/css">
/* Certificate Verification Modern Theme */
.cert-page {
    color: #1e293b;
}

.page-header-icon {
    font-size: 5rem;
    color: #273142;
}

/* Verification Search Card */
.cert-verify-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    margin-bottom: 35px;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
}

.cert-card-header {
    background: #ffffff;
    padding: 20px 28px;
    border-bottom: 1px solid #f1f5f9;
    border-left: 6px solid var(--primary-color, #007F3D);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.cert-card-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1.25rem;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
}

.cert-card-header h5 i {
    color: var(--primary-color, #007F3D);
}

.cert-form-body {
    padding: 30px 28px;
}

.cert-input-label {
    font-size: 0.95rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.cert-input-field {
    border-radius: 10px;
    padding: 12px 16px;
    border: 1px solid #cbd5e1;
    font-size: 0.95rem;
    background: #f8fafc;
    transition: all 0.25s ease;
}

.cert-input-field:focus {
    background: #ffffff;
    border-color: var(--primary-color, #007F3D);
    box-shadow: 0 0 0 3px rgba(0, 127, 61, 0.15);
    outline: none;
}

.cert-submit-btn {
    background-color: var(--primary-color, #007F3D);
    color: #ffffff !important;
    border: none;
    border-radius: 12px;
    padding: 13px 28px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.25s ease;
    box-shadow: 0 6px 18px rgba(0, 127, 61, 0.25);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
}

.cert-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(0, 127, 61, 0.35);
}

/* Feature Matrix Cards */
.cert-feature-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 24px 20px;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.cert-feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.07);
    border-color: rgba(0, 127, 61, 0.25);
}

.cert-feature-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.45rem;
    flex-shrink: 0;
}

.cert-feature-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
}

.cert-feature-desc {
    font-size: 0.88rem;
    color: #64748b;
    margin: 0;
    line-height: 1.5;
}

/* Certificate Preview Frame */
#card_holder {
    max-width: 980px;
    margin: 35px auto 0 auto;
}

.cert-preview-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.cert-preview-topbar {
    background: #0f172a;
    color: #ffffff;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.cert-preview-topbar h5 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #f8fafc;
    display: flex;
    align-items: center;
    gap: 10px;
}

.cert-preview-topbar h5 i {
    color: #38bdf8;
}

.cert-paper-container {
    padding: 30px 20px;
    background: #525659;
    overflow-x: auto;
    text-align: center;
}

#card {
    display: inline-block;
    background: #ffffff;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.45);
    border-radius: 4px;
    text-align: left;
}

/* Alert Message Box */
.cert-alert-box {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-left: 5px solid #ef4444;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: none;
}

.cert-alert-box.active {
    display: flex;
    align-items: center;
    gap: 14px;
}

.cert-alert-icon {
    font-size: 1.8rem;
    color: #ef4444;
    flex-shrink: 0;
}

@media (max-width: 767.98px) {
    .cert-form-body {
        padding: 22px 18px;
    }
    .cert-card-header {
        padding: 16px 18px;
    }
    .cert-paper-container {
        padding: 15px 10px;
    }
}
</style>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-header-title fw-bold mb-1"><?php echo html_escape($title); ?></h1>
                <p class="text-muted mb-0 d-none d-md-block" style="font-size: 0.95rem;">
                    <?php echo html_escape($subtitle); ?>
                </p>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-award"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Container Starts -->
<div class="container main-container cert-page my-5 py-3">

    <!-- Verification Search Card -->
    <div class="cert-verify-card">
        <div class="cert-card-header">
            <h5>
                <i class="fas fa-search-plus"></i>
                <span><?php echo $is_en ? 'Certificate Search & Verification' : 'সনদপত্র অনুসন্ধান ও যাচাই ফরম'; ?></span>
            </h5>
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.82rem;">
                <i class="fas fa-shield-alt me-1"></i> <?php echo $is_en ? 'Official Verification Portal' : 'যাচাইকৃত পোর্টাল'; ?>
            </span>
        </div>

        <div class="cert-form-body">
            <!-- Inline Alert for Errors -->
            <div id="cert_alert_box" class="cert-alert-box">
                <div class="cert-alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-danger" id="cert_alert_heading">
                        <?php echo $is_en ? 'Certificate Not Found' : 'কোনো সনদপত্র পাওয়া যায়নি'; ?>
                    </h6>
                    <p class="mb-0 text-muted small" id="cert_alert_msg">
                        <?php echo $is_en 
                            ? 'No active student record matches this registration number. Please check and try again.' 
                            : 'প্রদত্ত রেজিস্টার নম্বরটির কোনো সনদপত্র পাওয়া যায়নি। অনুগ্রহ করে সঠিক রেজিস্টার নম্বর প্রদান করুন।'; ?>
                    </p>
                </div>
            </div>

            <?php echo form_open('home/certificatesPrintFn', array('class' => 'printIn')); ?>
                <div class="row g-4 mb-4">
                    <!-- Template Select -->
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="cert-input-label">
                                <i class="fas fa-certificate text-primary"></i>
                                <span><?php echo $is_en ? 'Certificate Type' : 'সনদের ধরন / বিষয়'; ?></span>
                                <span class="text-danger">*</span>
                            </label>
                            <?php
                                $arrayClass = $this->app_lib->getSelectByBranch('certificates_templete', $branchID, false, array('user_type' => 1));
                                echo form_dropdown("templete_id", $arrayClass, set_value('templete_id'), "class='form-select cert-input-field w-100' id='templete_id' required ");
                            ?>
                            <span class="error text-danger small mt-1 d-block"></span>
                        </div>
                    </div>

                    <!-- Register No Input -->
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="cert-input-label">
                                <i class="fas fa-id-card text-success"></i>
                                <span><?php echo $is_en ? 'Registration No' : 'শিক্ষার্থীর রেজিস্টার নং'; ?></span>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control cert-input-field" name="register_no" placeholder="<?php echo $is_en ? 'e.g. CHIDM0001' : 'যেমন: CHIDM0001'; ?>" value="<?=set_value('register_no')?>" autocomplete="off" required />
                            <span class="error text-danger small mt-1 d-block"></span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="cert-submit-btn" id="btnSubmitCert">
                            <i class="fas fa-search me-1"></i>
                            <span><?php echo $is_en ? 'Search & Verify Certificate' : 'সনদ অনুসন্ধান ও যাচাই করুন'; ?></span>
                        </button>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <!-- 3 Feature Matrix Cards -->
    <div class="row g-4 my-2" style="max-width: 900px; margin-left: auto; margin-right: auto;">
        <div class="col-md-4">
            <div class="cert-feature-card">
                <div class="cert-feature-icon" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">
                    <i class="fas fa-bolt"></i>
                </div>
                <div>
                    <h6 class="cert-feature-title"><?php echo $is_en ? 'Instant Verification' : 'তাত্ক্ষণিক যাচাই'; ?></h6>
                    <p class="cert-feature-desc"><?php echo $is_en ? 'Verify student credentials in real-time with registration ID.' : 'শিক্ষার্থীর রেজিস্টার নম্বর দিয়ে মুহূর্তেই কাঙ্ক্ষিত সনদ ও তথ্য খুঁজে নিন।'; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="cert-feature-card">
                <div class="cert-feature-icon" style="background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <h6 class="cert-feature-title"><?php echo $is_en ? 'QR Code Security' : 'কিউআর কোড নিরাপত্তা'; ?></h6>
                    <p class="cert-feature-desc"><?php echo $is_en ? 'Automated QR code on each certificate for authenticity check.' : 'মাদরাসার প্রতিটি সনদে স্বয়ংক্রিয় কিউআর কোড ও অনুমোদন সংযুক্ত থাকে।'; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="cert-feature-card">
                <div class="cert-feature-icon" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                    <i class="fas fa-print"></i>
                </div>
                <div>
                    <h6 class="cert-feature-title"><?php echo $is_en ? 'One-Click Print' : 'এক-ক্লিকে প্রিন্ট'; ?></h6>
                    <p class="cert-feature-desc"><?php echo $is_en ? 'Print high-resolution certificates directly on standard A4 layout.' : 'এক ক্লিকেই এ৪ সাইজে পোর্ট্রেট বা ল্যান্ডস্কেপ মোডে পরিষ্কার প্রিন্ট করুন।'; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificate Result Preview Area -->
    <div id="card_holder" style="display: none;">
        <div class="cert-preview-card">
            <div class="cert-preview-topbar">
                <h5>
                    <i class="fas fa-certificate"></i>
                    <span><?php echo $is_en ? 'Official Student Certificate / Testimonial' : 'শিক্ষার্থীর মূল প্রশংসাপত্র / সনদ অনুলিপি'; ?></span>
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="$('html, body').animate({scrollTop: $('.cert-verify-card').offset().top - 80}, 400);">
                        <i class="fas fa-redo me-1"></i> <?php echo $is_en ? 'New Search' : 'নতুন অনুসন্ধান'; ?>
                    </button>
                    <button type="button" class="btn btn-sm btn-success rounded-pill px-4" id="print">
                        <i class="fas fa-print me-1"></i> <?php echo $is_en ? 'Print Certificate' : 'সনদ প্রিন্ট করুন'; ?>
                    </button>
                </div>
            </div>

            <!-- Paper Frame -->
            <div class="cert-paper-container">
                <div id="card"></div>
            </div>
        </div>
    </div>

</div>
<!-- Main Container Ends -->

<script type="text/javascript">
    $(document).ready(function () {
        var isEn = <?php echo $is_en ? 'true' : 'false'; ?>;

        $('form.printIn').on('submit', function(e){
            e.preventDefault();
            var btn = $(this).find('#btnSubmitCert');
            var $this = $(this);
            
            $("#card_holder").hide();
            $('#cert_alert_box').removeClass('active').hide();
            $('.error').html("");

            var originalBtnHtml = btn.html();

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function () {
                    btn.prop('disabled', true).html("<i class='fas fa-spinner fa-spin me-2'></i> " + (isEn ? "Searching..." : "অনুসন্ধান করা হচ্ছে..."));
                },
                success: function (data) {
                    $('.error').html("");
                    if (data.status == "fail") {
                        $.each(data.error, function (index, value) {
                            $this.find("[name='" + index + "']").parents('.form-group').find('.error').html(value);
                        });
                    } else if (data.status == 0 || data.status == '0') {
                        var errMsg = isEn 
                            ? "No active student record matches this registration number. Please check and try again." 
                            : "প্রদত্ত রেজিস্টার নম্বরটির কোনো সনদপত্র পাওয়া যায়নি। অনুগ্রহ করে সঠিক রেজিস্টার নম্বর দিন।";
                        
                        if (data.error && data.error !== "Register No Not Found.") {
                            errMsg = data.error;
                        }
                        $('#cert_alert_msg').html(errMsg);
                        $('#cert_alert_box').addClass('active').fadeIn(200);
                    } else {
                        $('#card').html(data.card_data);
                        $("#card_holder").fadeIn(300);
                        $('html, body').animate({
                            scrollTop: $("#card_holder").offset().top - 70
                        }, 500);
                    }
                },
                error: function () {
                    var errorAlert = isEn 
                        ? "An error occurred during verification. Please try again." 
                        : "যাচাই করার সময় একটি ত্রুটি ঘটেছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।";
                    $('#cert_alert_msg').html(errorAlert);
                    $('#cert_alert_box').addClass('active').fadeIn(200);
                },
                complete: function () {
                    btn.prop('disabled', false).html(originalBtnHtml);
                }
            });
        });

        $('#print').on('click', function(e){
            certificate_printElem('card', false);
        });
    });
</script>
