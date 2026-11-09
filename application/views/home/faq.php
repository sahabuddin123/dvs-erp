<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 

$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';

if ($current_lang == 'en') {
    $page_header_title = !empty($page_data['page_title_en']) ? $page_data['page_title_en'] : 'Frequently Asked Questions';
    $intro_title = !empty($page_data['title_en']) ? $page_data['title_en'] : 'Frequently Asked Questions';
    $intro_desc = !empty($page_data['description_en']) ? $page_data['description_en'] : '<p>Find answers to common questions regarding admissions, academics, and campus facilities.</p>';
} else {
    $page_header_title = !empty($page_data['page_title']) ? $page_data['page_title'] : 'সচরাচর জিজ্ঞাসা';
    $intro_title = !empty($page_data['title']) ? $page_data['title'] : 'সচরাচর জিজ্ঞাসা';
    $intro_desc = !empty($page_data['description']) ? $page_data['description'] : '<p>সচরাচর সাধারণ জিজ্ঞাসা বা আপনাদের কমন প্রশ্ন গুলো এবং তার উত্তর তুলে ধরা হলো।</p>';
}

$app_phone = !empty($cms_setting['mobile_no']) ? $cms_setting['mobile_no'] : (!empty($global_config['mobileno']) ? $global_config['mobileno'] : '');
$app_email = !empty($cms_setting['email']) ? $cms_setting['email'] : (!empty($global_config['institute_email']) ? $global_config['institute_email'] : '');

// Fetch FAQ items
$branch_id_val = !empty($branchID) ? $branchID : 1;
$faq_list = $this->db->where('branch_id', $branch_id_val)->get('front_cms_faq_list')->result_array();
if (empty($faq_list)) {
    $faq_list = $this->db->get('front_cms_faq_list')->result_array();
}

$bn_digits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
$en_digits = ['0','1','2','3','4','5','6','7','8','9'];
?>

<!-- Scoped FAQ Styles -->
<style>
    .faq-page-wrapper {
        background-color: #f8fafc;
        min-height: 60vh;
    }
    
    .faq-container {
        max-width: 980px;
    }

    /* Search Card */
    .faq-search-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        position: relative;
        overflow: hidden;
    }
    .faq-search-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color) 0%, #f59e0b 100%);
    }

    .faq-search-input-wrapper {
        position: relative;
        max-width: 620px;
        margin: 0 auto;
    }
    .faq-search-input {
        height: 52px;
        padding-left: 50px;
        padding-right: 48px;
        border-radius: 50px;
        border: 2px solid #e2e8f0;
        font-size: 1rem;
        transition: all 0.25s ease;
        background-color: #f8fafc;
    }
    .faq-search-input:focus {
        border-color: var(--primary-color);
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb, 220, 33, 19), 0.15);
        outline: none;
    }
    .faq-search-icon-left {
        position: absolute;
        top: 50%;
        left: 20px;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.15rem;
        pointer-events: none;
    }
    .faq-search-clear {
        position: absolute;
        top: 50%;
        right: 14px;
        transform: translateY(-50%);
        border: none;
        background: #e2e8f0;
        color: #64748b;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.75rem;
        transition: all 0.2s ease;
    }
    .faq-search-clear:hover {
        background: #cbd5e1;
        color: #0f172a;
    }

    /* Filter Chips */
    .faq-chip {
        display: inline-flex;
        align-items: center;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.86rem;
        font-weight: 500;
        background: #f1f5f9;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        border: 1px solid transparent;
    }
    .faq-chip:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .faq-chip.active {
        background: var(--primary-color);
        color: #ffffff;
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(var(--primary-rgb, 220, 33, 19), 0.25);
    }

    /* Modern Accordion */
    .modern-faq-accordion .accordion-item {
        background: #ffffff;
        border-radius: 14px !important;
        border: 1px solid #e2e8f0;
        margin-bottom: 16px;
        overflow: hidden;
        transition: all 0.25s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }
    .modern-faq-accordion .accordion-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    .modern-faq-accordion .accordion-item.is-open {
        border-color: rgba(var(--primary-rgb, 220, 33, 19), 0.35);
        box-shadow: 0 8px 24px rgba(var(--primary-rgb, 220, 33, 19), 0.07);
    }

    /* Accordion Button */
    .modern-faq-accordion .accordion-button {
        padding: 1.15rem 1.35rem;
        background: #ffffff;
        color: #1e293b;
        font-weight: 600;
        font-size: 1.05rem;
        border-radius: 14px !important;
        box-shadow: none !important;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 14px;
        border-left: 4px solid transparent;
    }
    .modern-faq-accordion .accordion-button::after {
        display: none !important; /* Hide default bootstrap SVG arrow */
    }
    .modern-faq-accordion .accordion-button:not(.collapsed) {
        background-color: rgba(var(--primary-rgb, 220, 33, 19), 0.03);
        color: var(--primary-color);
        border-left-color: var(--primary-color);
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    /* FAQ Number Pill */
    .faq-num-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 0.92rem;
        font-weight: 700;
        transition: all 0.25s ease;
        flex-shrink: 0;
    }
    .modern-faq-accordion .accordion-button:not(.collapsed) .faq-num-pill {
        background: var(--primary-color);
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(var(--primary-rgb, 220, 33, 19), 0.3);
    }

    /* FAQ Question Text */
    .faq-question-title {
        flex-grow: 1;
        margin: 0;
        text-align: left;
        line-height: 1.5;
        font-family: 'Hind Siliguri', 'Poppins', sans-serif;
    }

    /* Custom Arrow */
    .faq-arrow-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
    }
    .modern-faq-accordion .accordion-button:not(.collapsed) .faq-arrow-icon {
        transform: rotate(180deg);
        background: rgba(var(--primary-rgb, 220, 33, 19), 0.12);
        color: var(--primary-color);
    }

    /* Accordion Body & Answers */
    .modern-faq-accordion .accordion-body {
        padding: 1.35rem 1.5rem 1.6rem 4.4rem;
        background: #ffffff;
        border-top: 1px dashed #e2e8f0;
        font-size: 1.02rem;
        line-height: 1.85;
        color: #334155;
    }
    @media (max-width: 767.98px) {
        .modern-faq-accordion .accordion-body {
            padding-left: 1.25rem;
        }
    }

    .faq-answer-lead {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .modern-faq-accordion .accordion-body ol,
    .modern-faq-accordion .accordion-body ul {
        padding-left: 1.35rem;
        margin-bottom: 0.75rem;
    }
    .modern-faq-accordion .accordion-body li {
        margin-bottom: 8px;
        position: relative;
    }
    .modern-faq-accordion .accordion-body p {
        margin-bottom: 0.75rem;
    }
    .modern-faq-accordion .accordion-body p:last-child {
        margin-bottom: 0;
    }

    /* Contact Help Card */
    .faq-contact-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }
    .faq-contact-card::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 6px;
        background: linear-gradient(180deg, var(--primary-color) 0%, #f59e0b 100%);
    }
    .faq-contact-avatar {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: rgba(var(--primary-rgb, 220, 33, 19), 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    /* Highlight Match in Search */
    .faq-highlight {
        background-color: #fef08a;
        color: #854d0e;
        padding: 0 4px;
        border-radius: 4px;
        font-weight: 700;
    }
</style>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-8">
                <h1 class="page-header-title"><?php echo html_escape($page_header_title); ?></h1>
            </div>
            <div class="col-lg-5 col-md-4 d-none d-md-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main FAQ Section -->
<section class="section faq-page-wrapper py-5">
    <div class="container faq-container">

        <!-- Search & Filter Card -->
        <div class="card faq-search-card mb-5">
            <div class="card-body p-4 p-md-5 text-center">
                <h2 class="fw-bold mb-2" style="color: #0f172a; font-size: 1.75rem;">
                    <?php echo html_escape($intro_title); ?>
                </h2>
                <?php if (!empty($intro_desc)): ?>
                    <div class="text-muted mb-4 mx-auto" style="max-width: 650px; font-size: 1.02rem;">
                        <?php echo $intro_desc; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 650px; font-size: 1.02rem;">
                        <?php echo ($current_lang == 'en') 
                            ? 'Search your query below or click on any question to view the detailed answer.' 
                            : 'নিচের সার্চ বক্সে বিষয় লিখে খুঁজুন অথবা সরাসরি প্রশ্নে ক্লিক করে বিস্তারিত উত্তর জেনে নিন।'; ?>
                    </p>
                <?php endif; ?>

                <!-- Live Search Box -->
                <div class="faq-search-input-wrapper">
                    <i class="fas fa-search faq-search-icon-left"></i>
                    <input type="text" id="faqSearchInput" class="form-control faq-search-input" 
                        placeholder="<?php echo ($current_lang == 'en') ? 'Search questions (e.g. admission, sports, facilities)...' : 'প্রশ্ন বা কিওয়ার্ড লিখে খুঁজুন (যেমন: ভর্তি, খেলাধুলা, শৌচাগার, সুবিধা)...'; ?>" 
                        autocomplete="off">
                    <button type="button" id="clearFaqSearch" class="faq-search-clear d-none" title="Clear">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Category Chips -->
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                    <span class="faq-chip active" data-filter="all">
                        <i class="fas fa-layer-group me-1"></i> 
                        <?php echo ($current_lang == 'en') ? 'All Questions' : 'সবগুলো প্রশ্ন'; ?> 
                        <span class="badge bg-white text-dark ms-1 rounded-pill" style="font-size: 0.75rem;"><?php echo count($faq_list); ?></span>
                    </span>
                    <span class="faq-chip" data-filter="<?php echo ($current_lang == 'en') ? 'admission' : 'ভর্তি'; ?>">
                        <i class="fas fa-user-graduate me-1"></i> 
                        <?php echo ($current_lang == 'en') ? 'Admission' : 'ভর্তি সংক্রান্ত'; ?>
                    </span>
                    <span class="faq-chip" data-filter="<?php echo ($current_lang == 'en') ? 'facilit' : 'সুযোগ'; ?>">
                        <i class="fas fa-gift me-1"></i> 
                        <?php echo ($current_lang == 'en') ? 'Facilities' : 'সুযোগ-সুবিধা'; ?>
                    </span>
                    <span class="faq-chip" data-filter="<?php echo ($current_lang == 'en') ? 'sport' : 'খেলাধুলা'; ?>">
                        <i class="fas fa-futbol me-1"></i> 
                        <?php echo ($current_lang == 'en') ? 'Sports & Playground' : 'খেলাধুলা ও মাঠ'; ?>
                    </span>
                    <span class="faq-chip" data-filter="<?php echo ($current_lang == 'en') ? 'mpo' : 'সরকারী'; ?>">
                        <i class="fas fa-landmark me-1"></i> 
                        <?php echo ($current_lang == 'en') ? 'Recognition' : 'স্বীকৃতি ও এমপিও'; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- FAQs Modern Accordion -->
        <div class="accordion modern-faq-accordion" id="accordionFaqs">
            <?php 
            if (!empty($faq_list)):
                foreach ($faq_list as $key => $value):
                    $is_first = ($key === 0);
                    $num_bn = str_replace($en_digits, $bn_digits, sprintf('%02d', $key + 1));
                    $num_display = ($current_lang == 'en') ? sprintf('%02d', $key + 1) : $num_bn;
                    $item_id = 'faq_' . $key;
                    $item_title = ($current_lang == 'en' && !empty($value['title_en'])) ? $value['title_en'] : $value['title'];
                    $item_desc = ($current_lang == 'en' && !empty($value['description_en'])) ? $value['description_en'] : $value['description'];
            ?>
            <div class="accordion-item faq-item" data-faq-index="<?php echo $key; ?>">
                <h2 class="accordion-header" id="heading<?php echo $key; ?>">
                    <button class="accordion-button <?php echo $is_first ? '' : 'collapsed'; ?>" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapse<?php echo $key; ?>" 
                            aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>" 
                            aria-controls="collapse<?php echo $key; ?>">
                        <span class="faq-num-pill"><?php echo $num_display; ?></span>
                        <span class="faq-question-title"><?php echo html_escape($item_title); ?></span>
                        <span class="faq-arrow-icon"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="collapse<?php echo $key; ?>" 
                     class="accordion-collapse collapse <?php echo $is_first ? 'show' : ''; ?>" 
                     aria-labelledby="heading<?php echo $key; ?>" 
                     data-bs-parent="#accordionFaqs">
                    <div class="accordion-body">
                        <div class="faq-answer-lead">
                            <i class="fas fa-check-circle text-success"></i> 
                            <span><?php echo ($current_lang == 'en') ? 'Verified Answer' : 'মাদ্রাসার উত্তর'; ?>:</span>
                        </div>
                        <div class="faq-answer-content">
                            <?php echo $item_desc; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                endforeach;
            else:
            ?>
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="text-muted mb-3" style="font-size: 2.5rem;"><i class="fas fa-info-circle"></i></div>
                <h5 class="text-dark"><?php echo ($current_lang == 'en') ? 'No FAQ entries found.' : 'বর্তমানে কোনো সাধারণ জিজ্ঞাসা যুক্ত করা নেই।'; ?></h5>
            </div>
            <?php endif; ?>
        </div>

        <!-- No Results Fallback for Live Search -->
        <div id="noFaqResults" class="card border-0 shadow-sm text-center py-5 px-3 my-4 d-none" style="border-radius: 18px;">
            <div class="text-muted mb-3" style="font-size: 3rem; color: #94a3b8;">
                <i class="fas fa-search"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">
                <?php echo ($current_lang == 'en') ? 'No matching questions found' : 'আপনার কাঙ্ক্ষিত প্রশ্নটি খুঁজে পাওয়া যায়নি'; ?>
            </h4>
            <p class="text-muted mb-4 mx-auto" style="max-width: 520px;">
                <?php echo ($current_lang == 'en') 
                    ? 'Try searching with simpler keywords or check our contact channels below.' 
                    : 'দয়া করে অন্য কোনো শব্দ দিয়ে চেষ্টা করুন অথবা নিচে দেওয়া নম্বরে সরাসরি যোগাযোগ করে জেনে নিন।'; ?>
            </p>
            <div>
                <button type="button" class="btn btn-outline-primary rounded-pill px-4 py-2" id="resetFaqBtn">
                    <i class="fas fa-redo me-1"></i> <?php echo ($current_lang == 'en') ? 'Show All Questions' : 'সকল প্রশ্ন দেখুন'; ?>
                </button>
            </div>
        </div>

        <!-- Contact & Direct Help Card -->
        <div class="card faq-contact-card mt-5">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center">
                    <div class="col-lg-7 mb-4 mb-lg-0">
                        <div class="d-flex align-items-center gap-3">
                            <div class="faq-contact-avatar">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1" style="color: #0f172a;">
                                    <?php echo ($current_lang == 'en') ? 'Still have more questions?' : 'আরো কিছু জানতে চান?'; ?>
                                </h4>
                                <p class="text-muted mb-0" style="font-size: 0.98rem;">
                                    <?php echo ($current_lang == 'en') 
                                        ? 'Our office and support team are always ready to assist you with any information.' 
                                        : 'আমাদের অফিস ও হেল্পলাইন প্রতিনিধি যেকোনো তথ্যে আপনাকে সার্বক্ষণিক সহযোগিতা করতে প্রস্তুত।'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 text-lg-end">
                        <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                            <?php if (!empty($app_phone)): ?>
                                <a href="tel:<?php echo html_escape($app_phone); ?>" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                                    <i class="fas fa-phone-alt me-1"></i> <?php echo html_escape($app_phone); ?>
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo base_url('contact'); ?>" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold">
                                <i class="fas fa-envelope me-1"></i> <?php echo ($current_lang == 'en') ? 'Contact Us' : 'যোগাযোগ পাতা'; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Client-side Interactive Search & Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('faqSearchInput');
    const clearBtn = document.getElementById('clearFaqSearch');
    const resetBtn = document.getElementById('resetFaqBtn');
    const faqItems = document.querySelectorAll('.faq-item');
    const noResults = document.getElementById('noFaqResults');
    const chips = document.querySelectorAll('.faq-chip');

    // Manage isOpen class for active styling
    const accordionEl = document.getElementById('accordionFaqs');
    if (accordionEl) {
        accordionEl.addEventListener('show.bs.collapse', function (e) {
            const item = e.target.closest('.accordion-item');
            if (item) item.classList.add('is-open');
        });
        accordionEl.addEventListener('hide.bs.collapse', function (e) {
            const item = e.target.closest('.accordion-item');
            if (item) item.classList.remove('is-open');
        });
    }

    function filterFaqs(query) {
        query = (query || '').trim().toLowerCase();
        let visibleCount = 0;

        if (query.length > 0) {
            clearBtn.classList.remove('d-none');
        } else {
            clearBtn.classList.add('d-none');
        }

        faqItems.forEach(function(item) {
            const title = item.querySelector('.faq-question-title').textContent.toLowerCase();
            const body = item.querySelector('.faq-answer-content').textContent.toLowerCase();
            const match = title.includes(query) || body.includes(query);

            if (match) {
                item.style.display = '';
                visibleCount++;
                // If searching actively, automatically expand the matching item
                if (query.length >= 2) {
                    const collapseEl = item.querySelector('.accordion-collapse');
                    if (collapseEl && !collapseEl.classList.contains('show')) {
                        const bsCollapse = bootstrap.Collapse.getInstance(collapseEl) || new bootstrap.Collapse(collapseEl, { toggle: false });
                        bsCollapse.show();
                    }
                }
            } else {
                item.style.display = 'none';
            }
        });

        if (visibleCount === 0 && faqItems.length > 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            chips.forEach(c => c.classList.remove('active'));
            filterFaqs(this.value);
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            chips.forEach(c => c.classList.remove('active'));
            document.querySelector('.faq-chip[data-filter="all"]')?.classList.add('active');
            filterFaqs('');
            searchInput.focus();
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            chips.forEach(c => c.classList.remove('active'));
            document.querySelector('.faq-chip[data-filter="all"]')?.classList.add('active');
            filterFaqs('');
        });
    }

    // Chips filter click
    chips.forEach(function(chip) {
        chip.addEventListener('click', function() {
            chips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const filter = this.getAttribute('data-filter');
            if (filter === 'all') {
                if (searchInput) searchInput.value = '';
                filterFaqs('');
            } else {
                if (searchInput) searchInput.value = filter;
                filterFaqs(filter);
            }
        });
    });
});
</script>
