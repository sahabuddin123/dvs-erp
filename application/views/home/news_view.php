<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$is_en = ($current_lang === 'en');

$title = function_exists('get_lang_field') ? get_lang_field($event, 'title') : (!empty($event['title']) ? $event['title'] : 'Notice');
$page_title = $is_en ? 'Notice Details' : 'নোটিশ বিস্তারিত';
$date_timestamp = !empty($event['date']) ? strtotime($event['date']) : time();
$published_date_formatted = date('d F, Y', $date_timestamp);

function to_bn_date($en_date) {
    $en_months = array('January','February','March','April','May','June','July','August','September','October','November','December');
    $bn_months = array('জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর');
    $en_nums = array('0','1','2','3','4','5','6','7','8','9');
    $bn_nums = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    $str = str_replace($en_months, $bn_months, $en_date);
    return str_replace($en_nums, $bn_nums, $str);
}
$display_date = $is_en ? $published_date_formatted : to_bn_date($published_date_formatted);

$current_page_url = current_url();
$encoded_url = rawurlencode($current_page_url);
$encoded_title = rawurlencode($title);

$prefix = !empty($cms_setting['url_alias']) ? $cms_setting['url_alias'] . '/' : '';
?>

<style type="text/css">
.notice-detail-page {
    color: #1e293b;
}

.page-header-icon {
    font-size: 5rem;
    color: #273142;
}

/* Notice Card Container */
.notice-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    margin-bottom: 40px;
}

.notice-card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    padding: 24px 30px;
    border-bottom: 1px solid #f1f5f9;
    border-left: 6px solid var(--primary-color, #007F3D);
}

.notice-meta-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 12px;
}

.notice-headline {
    font-size: 1.65rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.4;
    margin-bottom: 0;
}

.notice-card-body {
    padding: 32px 30px;
}

.notice-featured-image-wrap {
    text-align: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px;
    margin-bottom: 28px;
}

.notice-featured-image {
    max-height: 720px;
    width: auto;
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
}

.notice-body-content {
    font-size: 1.05rem;
    line-height: 1.85;
    color: #334155;
}

.notice-body-content p {
    margin-bottom: 1.2rem;
}

/* Bottom Action Bar */
.notice-bottom-actions {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.btn-back-notice {
    background: #ffffff;
    color: #334155;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 9px 22px;
    font-weight: 600;
    font-size: 0.92rem;
    transition: all 0.25s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-back-notice:hover {
    background: #0f172a;
    border-color: #0f172a;
    color: #ffffff;
    transform: translateY(-2px);
}

/* Share Buttons */
.notice-share-wrapper {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.share-title-label {
    font-size: 0.88rem;
    font-weight: 700;
    color: #64748b;
    margin-right: 4px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-share {
    border-radius: 10px;
    padding: 8px 16px;
    font-size: 0.88rem;
    font-weight: 600;
    border: none;
    color: #ffffff !important;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.btn-share:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.16);
    filter: brightness(1.08);
}

/* WhatsApp Button */
.btn-share-whatsapp {
    background-color: #25D366;
}

/* Facebook Button */
.btn-share-facebook {
    background-color: #1877F2;
}

/* Print Button */
.btn-share-print {
    background-color: #0f172a;
}

/* Copy Link Button */
.btn-share-copy {
    background-color: var(--primary-color, #007F3D);
}

/* Official Printable Header (Hidden on Screen, Shown on Print) */
.official-print-header {
    display: none;
}

/* Print Styles */
@media print {
    @page {
        margin: 15mm 15mm 15mm 15mm;
        size: A4 portrait;
    }

    /* Hide unneeded elements */
    .top-bar, 
    .school-header, 
    .navbar, 
    .page-header, 
    .page-header-wave,
    .notice-bottom-actions, 
    footer, 
    .footer, 
    #mobileNavbar, 
    .scroll-to-top,
    .btn-back-notice,
    .notice-share-wrapper {
        display: none !important;
    }

    body, .section, .notice-detail-page {
        background: #ffffff !important;
        color: #000000 !important;
        padding: 0 !important;
        margin: 0 !important;
        font-family: 'Hind Siliguri', 'Bangla', Arial, sans-serif !important;
    }

    .container {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
    }

    .notice-card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .notice-card-header, .notice-card-body {
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
    }

    .official-print-header {
        display: block !important;
        text-align: center;
        border-bottom: 2.5px solid #007F3D;
        padding-bottom: 12px;
        margin-bottom: 24px;
    }

    .official-print-header h2 {
        font-size: 24px !important;
        font-weight: bold !important;
        color: #007F3D !important;
        margin: 0 0 4px 0 !important;
    }

    .official-print-header p {
        font-size: 13px !important;
        color: #333333 !important;
        margin: 0 !important;
    }

    .notice-headline {
        font-size: 20px !important;
        font-weight: bold !important;
        text-align: center !important;
        margin: 15px 0 !important;
        text-decoration: underline !important;
    }

    .notice-meta-bar {
        border-bottom: 1px dashed #cccccc;
        padding-bottom: 8px;
        margin-bottom: 18px;
    }

    .notice-featured-image-wrap {
        border: none !important;
        padding: 0 !important;
        background: transparent !important;
    }

    .notice-featured-image {
        max-height: 480px !important;
        box-shadow: none !important;
    }

    .notice-body-content {
        font-size: 14px !important;
        line-height: 1.8 !important;
        color: #000000 !important;
    }

    .print-signature-section {
        display: block !important;
        margin-top: 60px;
        page-break-inside: avoid;
    }

    .print-sig-col {
        display: inline-block;
        width: 48%;
        text-align: center;
    }

    .print-sig-line {
        border-top: 1px dashed #000000;
        width: 180px;
        margin: 0 auto 6px auto;
        padding-top: 4px;
        font-weight: bold;
        font-size: 13px;
    }
}
</style>

<div class="notice-detail-page">
    <!-- Page Header (DVS Match) -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge mb-2 px-3 py-2 fw-semibold" style="background-color: rgba(var(--primary-rgb, 0, 127, 61), 0.1); color: var(--primary-color); border-radius: 30px; font-size: 0.82rem;">
                        <i class="fas fa-bullhorn me-1"></i> <?php echo $is_en ? 'Notice Board' : 'মাদরাসা নোটিশ বোর্ড'; ?>
                    </span>
                    <h1 class="page-header-title mb-2" style="font-weight: 800; font-size: 2.1rem;">
                        <?php echo html_escape($page_title); ?>
                    </h1>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">
                        <i class="far fa-calendar-alt me-1 text-warning"></i>
                        <?php echo $is_en ? 'Published on ' . $display_date : 'প্রকাশের তারিখ: ' . $display_date; ?>
                    </p>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-end">
                    <div class="page-header-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Shape -->
        <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
            <path fill="var(--primary-color, #007F3D)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
        </svg>
    </div>

    <!-- Main Notice Details Content -->
    <section class="section py-5" style="background-color: #f8fafc; min-height: 550px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <div class="notice-card" id="printableNoticeArea">
                        
                        <!-- Official Header for Print Mode Only -->
                        <div class="official-print-header">
                            <h2>চর হাসান ইসলামিয়া দাখিল মাদ্রাসা</h2>
                            <p>গ্রাম: চর হাসান, ডাকঘর: পশ্চিম চর জব্বর, উপজেলা: সুবর্ণচর, জেলা: নোয়াখালী।</p>
                            <p>মোবাইল: +8801727770321 | ইমেইল: charhasan1996@gmail.com | শিক্ষাবর্ষ: <?php echo date('Y'); ?></p>
                        </div>

                        <!-- Card Header -->
                        <div class="notice-card-header">
                            <div class="notice-meta-bar">
                                <span class="badge text-white px-3 py-2" style="background-color: var(--primary-color, #007F3D); border-radius: 8px; font-weight: 600; font-size: 0.85rem;">
                                    <i class="fas fa-file-lines me-1"></i> <?php echo $is_en ? 'Official Notice' : 'দাপ্তরিক নোটিশ'; ?>
                                </span>
                                <span class="text-muted fw-semibold small">
                                    <i class="far fa-calendar-alt text-success me-1"></i>
                                    <?php echo $is_en ? 'Published on ' . $display_date : 'প্রকাশের তারিখ: ' . $display_date; ?>
                                </span>
                            </div>
                            <h2 class="notice-headline">
                                <?php echo html_escape($title); ?>
                            </h2>
                        </div>

                        <!-- Card Body -->
                        <div class="notice-card-body">
                            <!-- Featured Image (if available) -->
                            <?php if (!empty($event['image'])) { ?>
                                <div class="notice-featured-image-wrap">
                                    <img src="<?php echo base_url('uploads/frontend/news/' . $event['image']); ?>" 
                                         alt="<?php echo html_escape($title); ?>" 
                                         class="img-fluid notice-featured-image">
                                </div>
                            <?php } ?>

                            <!-- Notice Body HTML -->
                            <div class="notice-body-content" id="description">
                                <?php echo function_exists('get_lang_field') ? get_lang_field($event, 'description') : $event['description']; ?>
                            </div>

                            <!-- Print Signatures Section (shown only during print) -->
                            <div class="print-signature-section" style="display: none;">
                                <div style="display: flex; justify-content: space-between; margin-top: 60px;">
                                    <div style="text-align: center; width: 200px;">
                                        <div style="border-top: 1px dashed #333; padding-top: 5px; font-weight: bold; font-size: 13px;">
                                            প্রশাসনিক কর্মকর্তা
                                        </div>
                                    </div>
                                    <div style="text-align: center; width: 200px;">
                                        <div style="border-top: 1px dashed #333; padding-top: 5px; font-weight: bold; font-size: 13px;">
                                            অধ্যক্ষ / সুপারিনটেনডেন্ট
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Action Bar: Back Button (Left) & Share Buttons (Right) -->
                        <div class="notice-bottom-actions">
                            <!-- Back Button (Left) -->
                            <div>
                                <a href="<?php echo base_url($prefix . 'news'); ?>" 
                                   onclick="if(document.referrer && document.referrer.indexOf(window.location.host) !== -1){history.back();return false;}" 
                                   class="btn-back-notice">
                                    <i class="fas fa-arrow-left"></i>
                                    <span><?php echo $is_en ? 'Back' : 'Back / ফিরে যান'; ?></span>
                                </a>
                            </div>

                            <!-- Share Buttons (Right) -->
                            <div class="notice-share-wrapper">
                                <span class="share-title-label">
                                    <i class="fas fa-share-nodes text-success"></i>
                                    <span><?php echo $is_en ? 'Share:' : 'শেয়ার করুন:'; ?></span>
                                </span>

                                <!-- WhatsApp Share Button -->
                                <a href="https://api.whatsapp.com/send?text=<?php echo $encoded_title . '%0A' . $encoded_url; ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="btn-share btn-share-whatsapp" 
                                   title="<?php echo $is_en ? 'Share on WhatsApp' : 'হোয়াটসঅ্যাপে শেয়ার করুন'; ?>">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>WhatsApp</span>
                                </a>

                                <!-- Facebook Share Button -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $encoded_url; ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="btn-share btn-share-facebook js-fb-share" 
                                   title="<?php echo $is_en ? 'Share on Facebook' : 'ফেসবুকে শেয়ার করুন'; ?>">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>

                                <!-- Print Button -->
                                <button type="button" 
                                        onclick="window.print();" 
                                        class="btn-share btn-share-print" 
                                        title="<?php echo $is_en ? 'Print Notice' : 'নোটিশ প্রিন্ট করুন'; ?>">
                                    <i class="fas fa-print"></i>
                                    <span><?php echo $is_en ? 'Print' : 'প্রিন্ট'; ?></span>
                                </button>

                                <!-- Copy Link Button -->
                                <button type="button" 
                                        id="btnCopyNoticeLink" 
                                        class="btn-share btn-share-copy" 
                                        data-url="<?php echo $current_page_url; ?>" 
                                        title="<?php echo $is_en ? 'Copy Notice Link' : 'লিংক কপি করুন'; ?>">
                                    <i class="fas fa-link"></i>
                                    <span id="copyLinkText"><?php echo $is_en ? 'Copy Link' : 'কপি লিংক'; ?></span>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {
    // Facebook Share Popup Window
    var fbBtn = document.querySelector('.js-fb-share');
    if (fbBtn) {
        fbBtn.addEventListener('click', function (e) {
            e.preventDefault();
            var shareUrl = this.getAttribute('href');
            var width = 640;
            var height = 480;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;
            window.open(shareUrl, 'facebook-share-dialog', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left + ',toolbar=0,menubar=0,status=0,scrollbars=yes,resizable=yes');
        });
    }

    // Copy Notice Link to Clipboard
    var copyBtn = document.getElementById('btnCopyNoticeLink');
    if (copyBtn) {
        copyBtn.addEventListener('click', function () {
            var url = this.getAttribute('data-url') || window.location.href;
            var textSpan = document.getElementById('copyLinkText');
            var originalText = textSpan ? textSpan.innerText : 'কপি লিংক';

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(showCopiedState).catch(fallbackCopy);
            } else {
                fallbackCopy();
            }

            function fallbackCopy() {
                var tempInput = document.createElement('input');
                tempInput.value = url;
                document.body.appendChild(tempInput);
                tempInput.select();
                try {
                    document.execCommand('copy');
                    showCopiedState();
                } catch (err) {
                    alert("লিংক: " + url);
                }
                document.body.removeChild(tempInput);
            }

            function showCopiedState() {
                if (textSpan) {
                    textSpan.innerHTML = '<?php echo $is_en ? "Copied!" : "কপি হয়েছে!"; ?>';
                }
                copyBtn.style.backgroundColor = '#10b981';
                copyBtn.querySelector('i').className = 'fas fa-check';

                setTimeout(function () {
                    if (textSpan) {
                        textSpan.innerText = originalText;
                    }
                    copyBtn.style.backgroundColor = '';
                    copyBtn.querySelector('i').className = 'fas fa-link';
                }, 2200);
            }
        });
    }
});
</script>
