<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 

$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$is_en = ($current_lang === 'en');

// Default titles
$default_page_title = $is_en ? 'Academic Recognition & Accreditation' : 'স্বীকৃতি ও সরকারি অনুমোদন';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', $default_page_title) : (!empty($page_data['page_title']) ? $page_data['page_title'] : $default_page_title);

// Subtitle & headings
$header_subtitle = $is_en 
    ? 'Official Academic Recognition & Accreditation under Bangladesh Madrasah Education Board, Dhaka' 
    : 'বাংলাদেশ মাদরাসা শিক্ষা বোর্ড, ঢাকা কর্তৃক দাখিল স্তরের একাডেমিক স্বীকৃতি ও সরকারি অনুমোদনপত্র';

// Institution details
$default_inst_name = !empty($global_config['institute_name']) ? $global_config['institute_name'] : (!empty($branch['school_name']) ? $branch['school_name'] : 'চর হাসান ইসলামিয়া দাখিল মাদরাসা');
$inst_name = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'application_title', $default_inst_name) : $default_inst_name;

$default_address = !empty($global_config['address']) ? $global_config['address'] : (!empty($branch['address']) ? $branch['address'] : 'গ্রাম: চর হাসান, ০১ নং চর জব্বর, সুবর্ণচর, নোয়াখালী।');
$inst_address = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'address', $default_address) : $default_address;

// Document Image
$doc_img_url = base_url('uploads/frontend/banners/page_16.jpg');
if (!file_exists(FCPATH . 'uploads/frontend/banners/page_16.jpg')) {
    if (file_exists(FCPATH . 'uploads/frontend/images/recognition_doc.jpg')) {
        $doc_img_url = base_url('uploads/frontend/images/recognition_doc.jpg');
    } else {
        $doc_img_url = 'https://cjmdm.edu.bd/uploads/frontend/banners/page_16.jpg';
    }
}

// Custom content from Admin CMS (if any)
$custom_content = function_exists('get_lang_field') ? get_lang_field($page_data, 'content', '') : (!empty($page_data['content']) ? $page_data['content'] : '');
?>

<style>
/* Recognition Page Modern Theme */
.recognition-page {
    color: #2d3748;
}

.recognition-page .page-header-icon {
    font-size: 5rem;
    color: #273142;
}

/* Status Hero Banner */
.rec-status-banner {
    background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
    border: 1px solid rgba(16, 185, 129, 0.25);
    border-left: 6px solid #10b981;
    border-radius: 16px;
    box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    padding: 24px 28px;
    margin-bottom: 30px;
    transition: all 0.3s ease;
}

.rec-status-banner:hover {
    box-shadow: 0 15px 30px -5px rgba(16, 185, 129, 0.14);
}

.rec-status-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.85rem;
    box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
    flex-shrink: 0;
}

.rec-status-badge {
    background: rgba(16, 185, 129, 0.12);
    color: #047857;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 5px 14px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Key Metadata Cards */
.rec-meta-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 22px 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    height: 100%;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.rec-meta-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--primary-color, #10b981);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.rec-meta-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
    border-color: rgba(16, 185, 129, 0.3);
}

.rec-meta-card:hover::after {
    transform: scaleX(1);
}

.rec-meta-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: rgba(0, 127, 61, 0.08);
    color: var(--primary-color, #007F3D);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}

.rec-meta-title {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6b7280;
    margin-bottom: 4px;
    font-weight: 600;
}

.rec-meta-val {
    font-size: 1.12rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
    line-height: 1.35;
}

.rec-meta-sub {
    font-size: 0.82rem;
    color: #9ca3af;
    margin-bottom: 0;
}

/* Content & Compliance Cards */
.rec-content-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    padding: 28px;
    height: 100%;
}

.rec-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 2px solid #f3f4f6;
}

.rec-card-header i {
    font-size: 1.35rem;
    color: var(--primary-color, #007F3D);
}

.rec-card-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    color: #1f2937;
}

.rec-info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.rec-info-list li {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 10px 0;
    border-bottom: 1px dashed #f0f0f0;
    font-size: 0.92rem;
}

.rec-info-list li:last-child {
    border-bottom: none;
}

.rec-info-list .info-label {
    color: #6b7280;
    font-weight: 500;
    min-width: 130px;
}

.rec-info-list .info-value {
    color: #111827;
    font-weight: 600;
    text-align: right;
}

.rec-check-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.rec-check-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 0;
    font-size: 0.93rem;
    color: #374151;
    line-height: 1.5;
}

.rec-check-list li i {
    color: #10b981;
    font-size: 1.1rem;
    margin-top: 3px;
    flex-shrink: 0;
}

/* Document Showcase Viewer */
.rec-doc-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    margin-top: 35px;
}

.rec-doc-topbar {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: #ffffff;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.rec-doc-title {
    font-size: 1.05rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #f8fafc;
}

.rec-doc-title i {
    color: #38bdf8;
}

.rec-doc-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.rec-doc-frame {
    background: #525659;
    padding: 30px 20px;
    position: relative;
    text-align: center;
    overflow: hidden;
}

.rec-doc-sheet {
    display: inline-block;
    background: #ffffff;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35);
    border-radius: 6px;
    position: relative;
    max-width: 900px;
    width: 100%;
    cursor: pointer;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.rec-doc-sheet:hover {
    transform: scale(1.008);
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.45);
}

.rec-doc-img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 6px;
}

.rec-doc-ribbon {
    position: absolute;
    top: 15px;
    right: -30px;
    background: #dc2626;
    color: #ffffff;
    padding: 4px 35px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    transform: rotate(45deg);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    letter-spacing: 0.5px;
    z-index: 2;
}

.rec-hover-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.4);
    opacity: 0;
    transition: opacity 0.25s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    border-radius: 6px;
    gap: 8px;
}

.rec-doc-sheet:hover .rec-hover-overlay {
    opacity: 1;
}

.rec-hover-overlay i {
    font-size: 2.2rem;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
}

.rec-hover-overlay span {
    font-size: 0.95rem;
    font-weight: 600;
    background: rgba(0, 0, 0, 0.6);
    padding: 6px 16px;
    border-radius: 50px;
}

.rec-doc-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 0.88rem;
    color: #64748b;
}

.rec-doc-footer-meta {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

/* Modal Styling */
#docModal .modal-content {
    border-radius: 16px;
    overflow: hidden;
    border: none;
}

#docModal .modal-header {
    background: #0f172a;
    color: #ffffff;
    padding: 14px 22px;
    border-bottom: 1px solid #334155;
}

#docModal .modal-title {
    font-size: 1.05rem;
    color: #f8fafc;
    display: flex;
    align-items: center;
    gap: 8px;
}

#docModal .btn-close {
    filter: invert(1);
    opacity: 0.8;
}

#docModal .modal-body {
    background: #334155;
    padding: 20px;
    text-align: center;
    max-height: 82vh;
    overflow-y: auto;
}

#docModal .modal-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    transition: transform 0.2s ease;
}

/* Print Rules */
@media print {
    .top-bar, .header, .page-header, .page-header-wave, footer, .rec-doc-topbar, .rec-doc-actions, .rec-hover-overlay, .btn {
        display: none !important;
    }
    .main-container, .rec-doc-card, .rec-doc-frame {
        padding: 0 !important;
        margin: 0 !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    .rec-doc-img {
        max-width: 100% !important;
        page-break-inside: avoid;
    }
}

@media (max-width: 767.98px) {
    .rec-status-banner {
        padding: 18px 20px;
    }
    .rec-status-icon {
        width: 52px;
        height: 52px;
        font-size: 1.5rem;
    }
    .rec-doc-frame {
        padding: 15px 10px;
    }
}
</style>

<!-- Page Header (DVS Theme Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>" class="text-muted text-decoration-none"><i class="fas fa-home"></i> <?php echo $is_en ? 'Home' : 'হোম'; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('about'); ?>" class="text-muted text-decoration-none"><?php echo $is_en ? 'About Us' : 'আমাদের সম্পর্কে'; ?></a></li>
                        <li class="breadcrumb-item active" style="color: var(--dark-color, #c79f22); font-weight: 500;" aria-current="page"><?php echo html_escape($page_title); ?></li>
                    </ol>
                </nav>
                <h1 class="page-header-title fw-bold mb-1"><?php echo html_escape($page_title); ?></h1>
                <p class="text-muted mb-0 d-none d-md-block" style="font-size: 0.95rem;">
                    <?php echo $header_subtitle; ?>
                </p>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-stamp"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape matching primary branding -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Container Starts -->
<div class="container main-container recognition-page my-5 py-3">

    <!-- 1. Hero Status Banner -->
    <div class="rec-status-banner">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rec-status-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div>
                    <span class="rec-status-badge mb-1">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $is_en ? 'Official Recognition: Active & Verified' : 'স্বীকৃতি স্ট্যাটাস: সরকারি অনুমোদিত ও সক্রিয়'; ?>
                    </span>
                    <h3 class="fw-bold mb-1 text-dark" style="font-size: 1.25rem;">
                        <?php echo $is_en ? 'Current Academic Accreditation Validity' : 'সর্বশেষ একাডেমিক স্বীকৃতির কার্যকাল ও মেয়াদ'; ?>
                    </h3>
                    <p class="mb-0 text-muted" style="font-size: 0.95rem;">
                        <i class="fas fa-calendar-alt text-success me-1"></i>
                        <strong class="text-dark">
                            <?php echo $is_en ? '01 January 2024 to 31 December 2028' : '০১ জানুয়ারি ২০২৪ খ্রিঃ হইতে ৩১ ডিসেম্বর ২০২৮ খ্রিঃ পর্যন্ত'; ?>
                        </strong>
                        <span class="badge bg-success-subtle text-success ms-2 px-2 py-1" style="font-weight: 600;">
                            <?php echo $is_en ? '5 Years Renewable' : '৫ বছর মেয়াদী নবায়নকৃত'; ?>
                        </span>
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="<?php echo $doc_img_url; ?>" download="BMEB_Recognition_Char_Hasan_Madrasah.jpg" class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm py-2">
                    <i class="fas fa-download me-1"></i> <?php echo $is_en ? 'Download Order' : 'আদেশপত্র ডাউনলোড'; ?>
                </a>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm py-2" data-bs-toggle="modal" data-bs-target="#docModal" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                    <i class="fas fa-search-plus me-1"></i> <?php echo $is_en ? 'View Document' : 'অনুলিপি জুম করুন'; ?>
                </button>
            </div>
        </div>
    </div>

    <!-- 2. Four Quick Facts Matrix -->
    <div class="row g-3 mb-4">
        <!-- Level -->
        <div class="col-sm-6 col-lg-3">
            <div class="rec-meta-card">
                <div class="rec-meta-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <div class="rec-meta-title"><?php echo $is_en ? 'Approval Level' : 'অনুমোদনের স্তর'; ?></div>
                    <div class="rec-meta-val"><?php echo $is_en ? 'Dakhil Level' : 'দাখিল স্তর'; ?></div>
                    <p class="rec-meta-sub"><?php echo $is_en ? 'Grade 1 to Grade 10' : '১ম হইতে ১০ম শ্রেণি পর্যন্ত'; ?></p>
                </div>
            </div>
        </div>
        <!-- Authority -->
        <div class="col-sm-6 col-lg-3">
            <div class="rec-meta-card">
                <div class="rec-meta-icon">
                    <i class="fas fa-landmark"></i>
                </div>
                <div>
                    <div class="rec-meta-title"><?php echo $is_en ? 'Issuing Board' : 'অনুমোদনকারী বোর্ড'; ?></div>
                    <div class="rec-meta-val"><?php echo $is_en ? 'BMEB, Dhaka' : 'মাদরাসা শিক্ষা বোর্ড, ঢাকা'; ?></div>
                    <p class="rec-meta-sub"><?php echo $is_en ? 'Ministry of Education' : 'শিক্ষা মন্ত্রণালয়, বাংলাদেশ'; ?></p>
                </div>
            </div>
        </div>
        <!-- Memo Reference -->
        <div class="col-sm-6 col-lg-3">
            <div class="rec-meta-card">
                <div class="rec-meta-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <div class="rec-meta-title"><?php echo $is_en ? 'Memo Reference' : 'স্মারক ও নথি নম্বর'; ?></div>
                    <div class="rec-meta-val" style="font-size: 0.98rem;"><?php echo $is_en ? 'Memo: 225181076341' : 'স্মারক: 225181076341'; ?></div>
                    <p class="rec-meta-sub"><?php echo $is_en ? 'File No: 2648' : 'নথি নং: ২৬৪৮ | তারিখ: ২৫-১১-১৮'; ?></p>
                </div>
            </div>
        </div>
        <!-- Validity -->
        <div class="col-sm-6 col-lg-3">
            <div class="rec-meta-card">
                <div class="rec-meta-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <div class="rec-meta-title"><?php echo $is_en ? 'Current Term' : 'স্বীকৃতির মেয়াদ'; ?></div>
                    <div class="rec-meta-val" style="font-size: 1.05rem;"><?php echo $is_en ? '2024 — 2028' : '২০২৪ — ২০২৮ খ্রিঃ'; ?></div>
                    <p class="rec-meta-sub"><?php echo $is_en ? 'Valid till Dec 31, 2028' : '৩১ ডিসেম্বর ২০২৮ পর্যন্ত কার্যকর'; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Two Columns: Institution Profile & Compliance Guidelines -->
    <div class="row g-4 mb-4">
        <!-- Col 1: Institutional Profile -->
        <div class="col-lg-6">
            <div class="rec-content-card">
                <div class="rec-card-header">
                    <i class="fas fa-school"></i>
                    <h3><?php echo $is_en ? 'Institution Credentials & Board Info' : 'প্রাতিষ্ঠানিক তথ্যাবলী ও সনদ পরিচিতি'; ?></h3>
                </div>
                <ul class="rec-info-list">
                    <li>
                        <span class="info-label"><?php echo $is_en ? 'Institution Name' : 'প্রতিষ্ঠানের নাম'; ?></span>
                        <span class="info-value"><?php echo html_escape($inst_name); ?></span>
                    </li>
                    <li>
                        <span class="info-label"><?php echo $is_en ? 'Location / Address' : 'প্রতিষ্ঠানের অবস্থান'; ?></span>
                        <span class="info-value"><?php echo html_escape($inst_address); ?></span>
                    </li>
                    <li>
                        <span class="info-label"><?php echo $is_en ? 'Accreditation Level' : 'পাঠদানের স্তর'; ?></span>
                        <span class="info-value"><?php echo $is_en ? 'Dakhil (Secondary Level)' : 'দাখিল স্তর (১ম থেকে ১০ম শ্রেণি)'; ?></span>
                    </li>
                    <li>
                        <span class="info-label"><?php echo $is_en ? 'Authority' : 'অনুমোদন প্রদানকারী সংস্থা'; ?></span>
                        <span class="info-value"><?php echo $is_en ? 'Bangladesh Madrasah Education Board' : 'বাংলাদেশ মাদ্রাসা শিক্ষা বোর্ড, ঢাকা'; ?></span>
                    </li>
                    <li>
                        <span class="info-label"><?php echo $is_en ? 'Board Location' : 'বোর্ড কার্যালয়'; ?></span>
                        <span class="info-value"><?php echo $is_en ? '2, Orphanage Road, Bakshibazar, Dhaka-1211' : '২নং অরফানেজ রোড, বকশিবাজার, ঢাকা-১২১১'; ?></span>
                    </li>
                    <li>
                        <span class="info-label"><?php echo $is_en ? 'Official Board Web' : 'বোর্ড ওয়েবসাইট'; ?></span>
                        <span class="info-value"><a href="http://www.bmeb.gov.bd" target="_blank" class="text-decoration-none text-primary">www.bmeb.gov.bd</a></span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Col 2: Compliance Guidelines -->
        <div class="col-lg-6">
            <div class="rec-content-card">
                <div class="rec-card-header">
                    <i class="fas fa-clipboard-check"></i>
                    <h3><?php echo $is_en ? 'Statutory Standards & Board Compliance' : 'বোর্ড নির্ধারিত শর্তাবলী ও মানদণ্ড প্রতিপালন'; ?></h3>
                </div>
                <ul class="rec-check-list">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>
                            <strong><?php echo $is_en ? 'National Curriculum Compliance' : 'সরকারি শিক্ষাক্রম যথাযথ অনুসরণ:'; ?></strong>
                            <?php echo $is_en ? 'Full adherence to the national madrasah curriculum prescribed by the NCTB and Madrasah Education Board.' : 'জাতীয় শিক্ষাক্রম ও পাঠ্যপুস্তক বোর্ড এবং বাংলাদেশ মাদরাসা শিক্ষা বোর্ড কর্তৃক অনুমোদিত পাঠ্যক্রম অনুযায়ী শ্রেণি কার্যক্রম পরিচালনা।'; ?>
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>
                            <strong><?php echo $is_en ? 'Qualified Teaching Faculty' : 'যোগ্য ও অভিজ্ঞ শিক্ষক মণ্ডলী:'; ?></strong>
                            <?php echo $is_en ? 'Regular teaching conducted by government certified, experienced subject teachers ensuring high academic quality.' : 'সরকারি বিধি ও বোর্ড নীতিমালা অনুযায়ী নিয়োগপ্রাপ্ত অভিজ্ঞ ও বিষয়ভিত্তিক শিক্ষক মণ্ডলী দ্বারা মানসম্মত পাঠদান।'; ?>
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>
                            <strong><?php echo $is_en ? 'Modern Learning Environment' : 'শিক্ষাবান্ধব ক্যাম্পাস ও সুযোগ-সুবিধা:'; ?></strong>
                            <?php echo $is_en ? 'Adequate classrooms, science laboratory equipment, computer facilities, and an enriched library.' : 'পর্যাপ্ত শ্রেণিকক্ষ, বিজ্ঞানাগার, সমৃদ্ধ লাইব্রেরি ও শিক্ষার্থীদের নৈতিক ও মানবিক মূল্যবোধ বিকাশে সুশৃঙ্খল পরিবেশ।'; ?>
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>
                            <strong><?php echo $is_en ? 'Excellence in Board Examinations' : 'পাবলিক পরীক্ষায় সন্তোষজনক ফলাফল:'; ?></strong>
                            <?php echo $is_en ? 'Consistent academic excellence and competitive pass rates in Dakhil board examinations.' : 'প্রতি বছর দাখিল পাবলিক পরীক্ষায় শিক্ষার্থীদের অংশগ্রহণ ও কৃতিত্বপূর্ণ ফলাফল অর্জনের গৌরবময় ধারাবাহিকতা।'; ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 4. Official Document Showcase Card (Frame + Lightbox + Download) -->
    <div class="rec-doc-card">
        <div class="rec-doc-topbar">
            <div class="rec-doc-title">
                <i class="fas fa-file-contract"></i>
                <span><?php echo $is_en ? 'Original Office Order Copy — Bangladesh Madrasah Education Board' : 'বাংলাদেশ মাদরাসা শিক্ষা বোর্ড কর্তৃক জারীকৃত মূল অফিস আদেশপত্র'; ?></span>
            </div>
            <div class="rec-doc-actions">
                <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#docModal">
                    <i class="fas fa-search-plus me-1"></i> <?php echo $is_en ? 'Zoom' : 'জুম করুন'; ?>
                </button>
                <a href="<?php echo $doc_img_url; ?>" download="BMEB_Recognition_Char_Hasan_Madrasah.jpg" class="btn btn-sm btn-success rounded-pill px-3">
                    <i class="fas fa-download me-1"></i> <?php echo $is_en ? 'Download' : 'ডাউনলোড'; ?>
                </a>
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" onclick="window.print();">
                    <i class="fas fa-print me-1"></i> <?php echo $is_en ? 'Print' : 'প্রিন্ট'; ?>
                </button>
            </div>
        </div>

        <!-- Frame containing the certificate document -->
        <div class="rec-doc-frame">
            <div class="rec-doc-sheet" data-bs-toggle="modal" data-bs-target="#docModal" title="<?php echo $is_en ? 'Click to inspect in fullscreen' : 'বড় আকারে দেখতে ক্লিক করুন'; ?>">
                <span class="rec-doc-ribbon"><?php echo $is_en ? 'Official Copy' : 'অফিস আদেশ'; ?></span>
                <img src="<?php echo $doc_img_url; ?>" alt="<?php echo $is_en ? 'Madrasah Recognition Document' : 'মাদ্রাসার স্বীকৃতি সনদপত্র'; ?>" class="rec-doc-img">
                <div class="rec-hover-overlay">
                    <i class="fas fa-search-plus"></i>
                    <span><?php echo $is_en ? 'Click to View Fullscreen' : 'ক্লিক করে বড় আকারে দেখুন'; ?></span>
                </div>
            </div>
        </div>

        <div class="rec-doc-footer">
            <div class="rec-doc-footer-meta">
                <span><i class="fas fa-shield-alt text-success me-1"></i> <?php echo $is_en ? 'Verified Official Record' : 'যাচাইকৃত অফিসিয়াল নথি'; ?></span>
                <span><i class="fas fa-fingerprint text-primary me-1"></i> <?php echo $is_en ? 'Ref: Rec/Renewal/225181076341' : 'স্মারক নং: রিক/নবায়ন/225181076341'; ?></span>
                <span><i class="fas fa-folder-open text-warning me-1"></i> <?php echo $is_en ? 'File No: 2648' : 'নথি নং: ২৬৪৮'; ?></span>
            </div>
            <div>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    <?php echo $is_en ? 'Issued by: Bangladesh Madrasah Education Board, Dhaka' : 'প্রদানকারী কর্তৃপক্ষ: বাংলাদেশ মাদ্রাসা শিক্ষা বোর্ড, ঢাকা'; ?>
                </small>
            </div>
        </div>
    </div>

    <!-- 5. Dynamic CMS Content (if admin wrote custom text) -->
    <?php if (!empty($custom_content) && strpos($custom_content, 'page_16.jpg') === false && strlen(strip_tags($custom_content)) > 20) { ?>
        <div class="rec-content-card mt-4">
            <div class="rec-card-header">
                <i class="fas fa-align-left"></i>
                <h3><?php echo $is_en ? 'Additional Information' : 'অতিরিক্ত তথ্যাবলী ও প্রাসঙ্গিক বিবরণ'; ?></h3>
            </div>
            <div class="cms-rendered-content">
                <?php echo $custom_content; ?>
            </div>
        </div>
    <?php } ?>

</div>

<!-- Fullscreen Modal / Lightbox -->
<div class="modal fade" id="docModal" tabindex="-1" aria-labelledby="docModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="docModalLabel">
                    <i class="fas fa-certificate text-warning"></i>
                    <?php echo $is_en ? 'Academic Recognition Office Order — BMEB Dhaka' : 'বাংলাদেশ মাদরাসা শিক্ষা বোর্ড কর্তৃক দাখিল একাডেমিক স্বীকৃতি ও অফিস আদেশ'; ?>
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <a href="<?php echo $doc_img_url; ?>" download="BMEB_Recognition_Char_Hasan_Madrasah.jpg" class="btn btn-sm btn-success rounded-pill px-3">
                        <i class="fas fa-download me-1"></i> <?php echo $is_en ? 'Download' : 'ডাউনলোড'; ?>
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <img id="zoomDocImg" src="<?php echo $doc_img_url; ?>" alt="<?php echo $is_en ? 'Recognition Document High Resolution' : 'স্বীকৃতি সনদপত্র উচ্চ রেজোলিউশন'; ?>" class="img-fluid">
            </div>
        </div>
    </div>
</div>
