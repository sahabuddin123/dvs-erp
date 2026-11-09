<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 

$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$is_en = ($current_lang === 'en');

// Page Title (ensuring it never displays 'নোটিশ' for events page)
$default_page_title = $is_en ? 'Our Events' : 'আমাদের ইভেন্টসমূহ';
$page_title = function_exists('get_lang_field') 
    ? get_lang_field($page_data, 'page_title', $default_page_title) 
    : (!empty($page_data['page_title']) ? $page_data['page_title'] : $default_page_title);

if ($page_title == 'নোটিশ' || $page_title == 'Notice' || empty($page_title)) {
    $page_title = $default_page_title;
}

// Subtitle
$section_title = function_exists('get_lang_field')
    ? get_lang_field($page_data, 'title', ($is_en ? 'Upcoming & Recent Events' : 'আসন্ন ও সাম্প্রতিক অনুষ্ঠানমালা'))
    : (!empty($page_data['title']) ? $page_data['title'] : ($is_en ? 'Upcoming & Recent Events' : 'আসন্ন ও সাম্প্রতিক অনুষ্ঠানমালা'));

$section_subtitle = function_exists('get_lang_field')
    ? get_lang_field($page_data, 'description', ($is_en ? 'Explore our upcoming, ongoing, and past academic, cultural, sports, and religious events.' : 'চর হাসান ইসলামিয়া দাখিল মাদরাসার চলমান, আসন্ন ও সাম্প্রতিক শিক্ষা, সংস্কৃতি, ক্রীড়া ও ধর্মীয় অনুষ্ঠানমালার তালিকা ও সময়সূচি।'))
    : (!empty($page_data['description']) ? $page_data['description'] : '');

// Bengali Date Formatter Helper
function format_bn_event_date($date_str, $is_en = false) {
    if (empty($date_str) || $date_str == '0000-00-00') return '';
    $timestamp = strtotime($date_str);
    if ($is_en) {
        return date('d M, Y', $timestamp);
    }
    $bn_months = [
        'Jan' => 'জানুয়ারি', 'Feb' => 'ফেব্রুয়ারি', 'Mar' => 'মার্চ',
        'Apr' => 'এপ্রিল', 'May' => 'মে', 'Jun' => 'জুন',
        'Jul' => 'জুলাই', 'Aug' => 'আগস্ট', 'Sep' => 'সেপ্টেম্বর',
        'Oct' => 'অক্টোবর', 'Nov' => 'নভেম্বর', 'Dec' => 'ডিসেম্বর'
    ];
    $bn_digits = ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯'];
    $day = strtr(date('d', $timestamp), $bn_digits);
    $month = $bn_months[date('M', $timestamp)] ?? date('M', $timestamp);
    $year = strtr(date('Y', $timestamp), $bn_digits);
    return "$day $month, $year";
}

// Format Date Range
function format_event_range($start_date, $end_date, $is_en = false) {
    if ($start_date == $end_date || empty($end_date)) {
        return format_bn_event_date($start_date, $is_en);
    }
    if ($is_en) {
        $s_ts = strtotime($start_date);
        $e_ts = strtotime($end_date);
        if (date('M Y', $s_ts) == date('M Y', $e_ts)) {
            return date('d', $s_ts) . ' — ' . date('d M, Y', $e_ts);
        }
        return date('d M', $s_ts) . ' — ' . date('d M, Y', $e_ts);
    }
    $bn_months = [
        'Jan' => 'জানুয়ারি', 'Feb' => 'ফেব্রুয়ারি', 'Mar' => 'মার্চ',
        'Apr' => 'এপ্রিল', 'May' => 'মে', 'Jun' => 'জুন',
        'Jul' => 'জুলাই', 'Aug' => 'আগস্ট', 'Sep' => 'সেপ্টেম্বর',
        'Oct' => 'অক্টোবর', 'Nov' => 'নভেম্বর', 'Dec' => 'ডিসেম্বর'
    ];
    $bn_digits = ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯'];
    $s_ts = strtotime($start_date);
    $e_ts = strtotime($end_date);
    $s_day = strtr(date('d', $s_ts), $bn_digits);
    $e_day = strtr(date('d', $e_ts), $bn_digits);
    $month = $bn_months[date('M', $e_ts)] ?? date('M', $e_ts);
    $year = strtr(date('Y', $e_ts), $bn_digits);
    return "$s_day — $e_day $month, $year";
}
?>

<style>
/* Events Page Modern Styling */
.events-section {
    background-color: #f8fafc;
    min-height: 550px;
}

/* Page Header Icon */
.page-header-icon {
    font-size: 5rem;
    color: rgba(255, 255, 255, 0.85);
}

/* Control Bar (Filters & Search) */
.event-control-bar {
    background: #ffffff;
    border-radius: 16px;
    padding: 16px 22px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
    margin-bottom: 35px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.event-filter-pills {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
}

.event-filter-btn {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid transparent;
    padding: 7px 18px;
    border-radius: 50px;
    font-size: 0.88rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.event-filter-btn:hover {
    background: #e2e8f0;
    color: #1e293b;
}

.event-filter-btn.active {
    background: var(--primary-color, #007F3D) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(0, 127, 61, 0.28);
}

/* Search Box */
.event-search-wrapper {
    position: relative;
    min-width: 260px;
}

.event-search-input {
    border-radius: 50px;
    padding: 8px 18px 8px 42px;
    font-size: 0.88rem;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    transition: all 0.25s ease;
    width: 100%;
}

.event-search-input:focus {
    background: #ffffff;
    border-color: var(--primary-color, #007F3D);
    box-shadow: 0 0 0 3px rgba(0, 127, 61, 0.15);
    outline: none;
}

.event-search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.9rem;
}

/* Event Card Architecture */
.event-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.event-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.09);
    border-color: rgba(0, 127, 61, 0.3);
}

/* Image Container */
.event-img-wrap {
    height: 210px;
    position: relative;
    overflow: hidden;
    background: #0f172a;
}

.event-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.event-card:hover .event-card-img {
    transform: scale(1.07);
}

.event-img-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(15, 23, 42, 0.45) 0%, transparent 60%);
}

/* Floating Calendar Date Stamp (Top-Left) */
.event-calendar-stamp {
    position: absolute;
    top: 14px;
    left: 14px;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
    text-align: center;
    width: 62px;
    z-index: 3;
    border: 1px solid rgba(255, 255, 255, 0.7);
}

.event-stamp-month {
    background: var(--primary-color, #007F3D);
    color: #ffffff;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    padding: 3px 2px;
    letter-spacing: 0.5px;
}

.event-stamp-day {
    color: #0f172a;
    font-size: 1.35rem;
    font-weight: 800;
    line-height: 1.1;
    padding: 4px 0 1px 0;
}

.event-stamp-year {
    color: #64748b;
    font-size: 0.68rem;
    font-weight: 600;
    padding-bottom: 4px;
}

/* Floating Status Pill (Top-Right) */
.event-status-pill {
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 3;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.event-status-upcoming {
    background: rgba(16, 185, 129, 0.95);
    color: #ffffff;
    backdrop-filter: blur(4px);
}

.event-status-past {
    background: rgba(30, 41, 59, 0.85);
    color: #f1f5f9;
    backdrop-filter: blur(4px);
}

/* Card Body */
.event-card-body {
    padding: 22px 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.event-category-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(0, 127, 61, 0.08);
    color: var(--primary-color, #007F3D);
    font-size: 0.78rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 50px;
    margin-bottom: 12px;
    width: fit-content;
}

.event-card-title {
    font-size: 1.1rem;
    font-weight: 700;
    line-height: 1.45;
    margin-bottom: 12px;
    color: #1e293b;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 3.1rem;
}

.event-card-title a {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.25s ease;
}

.event-card-title a:hover {
    color: var(--primary-color, #007F3D);
}

/* Meta Row */
.event-meta-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 14px;
    font-size: 0.84rem;
    color: #64748b;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px dashed #e2e8f0;
}

.event-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.event-card-text {
    font-size: 0.92rem;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex-grow: 1;
}

/* Card Footer */
.event-card-footer {
    margin-top: auto;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.event-read-btn {
    background-color: var(--primary-color, #007F3D);
    color: #ffffff !important;
    border-radius: 50px;
    padding: 7px 18px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 10px rgba(0, 127, 61, 0.2);
}

.event-read-btn:hover {
    transform: translateX(3px);
    box-shadow: 0 6px 14px rgba(0, 127, 61, 0.3);
    color: #ffffff;
}

/* Empty State */
.event-empty-box {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 50px 30px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
}

.event-empty-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-bottom: 18px;
}

/* Pagination Custom */
.pagination-bx .pagination {
    gap: 6px;
}

.pagination-bx .pagination li a,
.pagination-bx .pagination li span {
    border-radius: 50px !important;
    padding: 8px 16px;
    font-size: 0.9rem;
    border: 1px solid #e2e8f0;
    color: #475569;
}

.pagination-bx .pagination li.active span {
    background-color: var(--primary-color, #007F3D) !important;
    border-color: var(--primary-color, #007F3D) !important;
    color: #ffffff !important;
}

@media (max-width: 767.98px) {
    .event-control-bar {
        padding: 14px 16px;
    }
    .event-search-wrapper {
        min-width: 100%;
    }
    .event-img-wrap {
        height: 180px;
    }
}
</style>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>" class="text-white-50 text-decoration-none"><i class="fas fa-home"></i> <?php echo $is_en ? 'Home' : 'হোম'; ?></a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page"><?php echo html_escape($page_title); ?></li>
                    </ol>
                </nav>
                <h1 class="page-header-title text-white fw-bold mb-1"><?php echo html_escape($page_title); ?></h1>
                <?php if (!empty($section_title)) { ?>
                    <p class="text-white-50 mb-0 d-none d-md-block" style="font-size: 0.95rem;">
                        <?php echo html_escape($section_title); ?>
                    </p>
                <?php } ?>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Events Container -->
<section class="section py-5 events-section">
    <div class="container">
        
        <!-- Intro text if configured -->
        <?php if (!empty($section_subtitle)) { ?>
            <div class="mb-4 text-center">
                <p class="lead text-muted mx-auto" style="max-width: 800px; font-size: 1.05rem;">
                    <?php echo $section_subtitle; ?>
                </p>
            </div>
        <?php } ?>

        <!-- Control Bar: Category Filters & Live Search -->
        <div class="event-control-bar">
            <!-- Filter Pills -->
            <div class="event-filter-pills" id="eventFilters">
                <button type="button" class="event-filter-btn active" data-filter="all">
                    <i class="fas fa-th-large"></i> <?php echo $is_en ? 'All Events' : 'সকল ইভেন্ট'; ?>
                </button>
                <button type="button" class="event-filter-btn" data-filter="upcoming">
                    <i class="fas fa-clock text-success"></i> <?php echo $is_en ? 'Upcoming' : 'আসন্ন ইভেন্ট'; ?>
                </button>
                <button type="button" class="event-filter-btn" data-filter="past">
                    <i class="fas fa-history text-secondary"></i> <?php echo $is_en ? 'Past Events' : 'সম্পন্ন ইভেন্ট'; ?>
                </button>
            </div>

            <!-- Instant Search Input & Count Badge -->
            <div class="d-flex align-items-center gap-3">
                <div class="event-search-wrapper">
                    <i class="fas fa-search event-search-icon"></i>
                    <input type="text" id="eventSearchInput" class="event-search-input" placeholder="<?php echo $is_en ? 'Search events...' : 'ইভেন্ট খুঁজুন...'; ?>" autocomplete="off">
                </div>
                <span id="eventCountBadge" class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-semibold" style="font-size: 0.85rem;">
                    <?php echo (!empty($results) ? count($results) : 0) . ' ' . ($is_en ? 'events' : 'টি ইভেন্ট'); ?>
                </span>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="row g-4" id="eventsGrid">
            <?php
            $today = date('Y-m-d');
            $bn_months_short = [
                'Jan' => 'জানু', 'Feb' => 'ফেব্রু', 'Mar' => 'মার্চ',
                'Apr' => 'এপ্রিল', 'May' => 'মে', 'Jun' => 'জুন',
                'Jul' => 'জুলাই', 'Aug' => 'আগস্ট', 'Sep' => 'সেপ্টে',
                'Oct' => 'অক্টো', 'Nov' => 'নভে', 'Dec' => 'ডিসে'
            ];
            $bn_digits = ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯'];

            if (!empty($results)) {
                foreach ($results as $key => $value) {
                    $ev_title = function_exists('get_lang_field') ? get_lang_field($value, 'title') : $value['title'];
                    $ev_remark = function_exists('get_lang_field') ? get_lang_field($value, 'remark') : $value['remark'];
                    $event_img = !empty($value['image']) ? base_url('uploads/frontend/events/' . $value['image']) : base_url('uploads/frontend/events/defualt.png');

                    // Date calculations
                    $start_date = $value['start_date'];
                    $end_date = !empty($value['end_date']) ? $value['end_date'] : $start_date;
                    $is_upcoming = ($end_date >= $today);
                    $status_filter = $is_upcoming ? 'upcoming' : 'past';

                    // Stamp parts
                    $ts = strtotime($start_date);
                    $raw_day = date('d', $ts);
                    $raw_month = date('M', $ts);
                    $raw_year = date('Y', $ts);

                    $stamp_day = $is_en ? $raw_day : strtr($raw_day, $bn_digits);
                    $stamp_month = $is_en ? strtoupper($raw_month) : ($bn_months_short[$raw_month] ?? $raw_month);
                    $stamp_year = $is_en ? $raw_year : strtr($raw_year, $bn_digits);

                    // Category / Type
                    $card_type = '';
                    $card_type_slug = 'general';
                    if (!empty($value['type'])) {
                        if (is_numeric($value['type'])) {
                            $type_row = $this->db->get_where('event_types', array('id' => $value['type']))->row_array();
                            if (!empty($type_row)) {
                                $card_type = function_exists('get_lang_field') ? get_lang_field($type_row, 'name') : $type_row['name'];
                                $card_type_slug = 'cat-' . $value['type'];
                            }
                        } else {
                            $card_type = function_exists('get_lang_field') ? get_lang_field($value, 'type') : $value['type'];
                            $card_type_slug = 'cat-' . url_title($value['type']);
                        }
                    }
                    if (empty($card_type)) {
                        $card_type = $is_en ? 'Campus Event' : 'ক্যাম্পাস ইভেন্ট';
                    }

                    // Formatted Range
                    $date_range_text = format_event_range($start_date, $end_date, $is_en);
            ?>
            <div class="col-sm-12 col-md-6 col-lg-4 event-item-col" 
                 data-status="<?php echo $status_filter; ?>" 
                 data-category="<?php echo $card_type_slug; ?>" 
                 data-title="<?php echo html_escape(strtolower($ev_title)); ?>"
                 data-desc="<?php echo html_escape(strtolower(strip_tags($ev_remark))); ?>">
                <div class="event-card">
                    <!-- Image Wrap -->
                    <div class="event-img-wrap">
                        <img src="<?php echo $event_img; ?>" alt="<?php echo html_escape($ev_title); ?>" class="event-card-img" loading="lazy">
                        <div class="event-img-overlay"></div>

                        <!-- Calendar Stamp (Top-Left) -->
                        <div class="event-calendar-stamp">
                            <div class="event-stamp-month"><?php echo $stamp_month; ?></div>
                            <div class="event-stamp-day"><?php echo $stamp_day; ?></div>
                            <div class="event-stamp-year"><?php echo $stamp_year; ?></div>
                        </div>

                        <!-- Status Pill (Top-Right) -->
                        <?php if ($is_upcoming): ?>
                            <span class="event-status-pill event-status-upcoming">
                                <i class="fas fa-circle-check"></i> <?php echo $is_en ? 'Upcoming' : 'আসন্ন'; ?>
                            </span>
                        <?php else: ?>
                            <span class="event-status-pill event-status-past">
                                <i class="fas fa-check-circle"></i> <?php echo $is_en ? 'Completed' : 'সম্পন্ন'; ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Card Body -->
                    <div class="event-card-body">
                        <!-- Category Tag -->
                        <div class="event-category-tag">
                            <i class="fas fa-tag"></i>
                            <span><?php echo html_escape($card_type); ?></span>
                        </div>

                        <!-- Title -->
                        <h3 class="event-card-title">
                            <a href="<?php echo base_url("home/event_view/" . $value['id']); ?>">
                                <?php echo html_escape($ev_title); ?>
                            </a>
                        </h3>

                        <!-- Meta Row -->
                        <div class="event-meta-row">
                            <span class="event-meta-item">
                                <i class="far fa-calendar-alt text-success"></i>
                                <span><?php echo $date_range_text; ?></span>
                            </span>
                            <span class="event-meta-item">
                                <i class="far fa-user text-muted"></i>
                                <span><?php echo $is_en ? 'Open to All' : 'উন্মুক্ত অনুষ্ঠান'; ?></span>
                            </span>
                        </div>

                        <!-- Text Excerpt -->
                        <p class="event-card-text">
                            <?php 
                                $clean_desc = strip_tags($ev_remark);
                                echo mb_strimwidth($clean_desc, 0, 110, "..."); 
                            ?>
                        </p>

                        <!-- Card Footer -->
                        <div class="event-card-footer">
                            <a href="<?php echo base_url("home/event_view/" . $value['id']); ?>" class="event-read-btn">
                                <span><?php echo $is_en ? 'View Details' : 'বিস্তারিত দেখুন'; ?></span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            <span class="text-muted small">
                                <i class="far fa-clock me-1"></i> <?php echo $is_en ? 'Full Coverage' : 'সম্পূর্ণ বিবরণ'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if (isset($links) && !empty($links)) { ?>
                <div class="col-12 mt-4 text-center">
                    <div class="pagination-bx">
                        <?php echo $links; ?>
                    </div>
                </div>
            <?php } ?>

            <?php } else { ?>
            <div class="col-12">
                <div class="event-empty-box">
                    <div class="event-empty-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h4 class="fw-bold mb-2 text-dark"><?php echo $is_en ? 'No Events Found' : 'কোনো ইভেন্ট পাওয়া যায়নি'; ?></h4>
                    <p class="text-muted mb-0"><?php echo $is_en ? 'Currently there are no events scheduled.' : 'বর্তমানে কোনো ইভেন্ট বা অনুষ্ঠান তালিকাভুক্ত নেই।'; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- No Results Fallback for JS Search -->
        <div id="noSearchMatch" class="d-none col-12 my-4">
            <div class="event-empty-box">
                <div class="event-empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h4 class="fw-bold mb-2 text-dark"><?php echo $is_en ? 'No matching events found' : 'অনুসন্ধানের সাথে কোনো ইভেন্ট মিলেনি'; ?></h4>
                <p class="text-muted mb-3"><?php echo $is_en ? 'Try searching with different keywords or reset your filters.' : 'ভিন্ন কোনো শব্দ দিয়ে খুঁজুন অথবা ফিল্টার রিসেট করুন।'; ?></p>
                <button type="button" class="btn btn-outline-success rounded-pill px-4" id="resetFilterBtn">
                    <i class="fas fa-undo me-1"></i> <?php echo $is_en ? 'Reset Filters' : 'ফিল্টার রিসেট করুন'; ?>
                </button>
            </div>
        </div>

    </div>
</section>

<!-- Client-side Interactive Filter & Live Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.event-filter-btn');
    const searchInput = document.getElementById('eventSearchInput');
    const eventCards = document.querySelectorAll('.event-item-col');
    const noMatchBox = document.getElementById('noSearchMatch');
    const countBadge = document.getElementById('eventCountBadge');
    const resetBtn = document.getElementById('resetFilterBtn');

    let currentFilter = 'all';
    let searchQuery = '';

    function applyFilters() {
        let visibleCount = 0;

        eventCards.forEach(card => {
            const status = card.getAttribute('data-status') || '';
            const category = card.getAttribute('data-category') || '';
            const title = card.getAttribute('data-title') || '';
            const desc = card.getAttribute('data-desc') || '';

            // Filter match
            let matchesFilter = false;
            if (currentFilter === 'all') {
                matchesFilter = true;
            } else if (currentFilter === 'upcoming') {
                matchesFilter = (status === 'upcoming');
            } else if (currentFilter === 'past') {
                matchesFilter = (status === 'past');
            } else {
                matchesFilter = (category === currentFilter);
            }

            // Search match
            let matchesSearch = true;
            if (searchQuery.trim() !== '') {
                matchesSearch = title.includes(searchQuery) || desc.includes(searchQuery);
            }

            if (matchesFilter && matchesSearch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update count badge
        if (countBadge) {
            const isEn = <?php echo $is_en ? 'true' : 'false'; ?>;
            countBadge.textContent = visibleCount + (isEn ? ' events' : ' টি ইভেন্ট');
        }

        // Show/hide no match box
        if (noMatchBox) {
            if (visibleCount === 0 && eventCards.length > 0) {
                noMatchBox.classList.remove('d-none');
            } else {
                noMatchBox.classList.add('d-none');
            }
        }
    }

    // Filter Buttons
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter') || 'all';
            applyFilters();
        });
    });

    // Search Input
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchQuery = this.value.toLowerCase().trim();
            applyFilters();
        });
    }

    // Reset Button
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            currentFilter = 'all';
            searchQuery = '';
            if (searchInput) searchInput.value = '';
            filterBtns.forEach(b => {
                b.classList.remove('active');
                if (b.getAttribute('data-filter') === 'all') b.classList.add('active');
            });
            applyFilters();
        });
    }
});
</script>
