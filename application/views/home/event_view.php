<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$is_en = ($current_lang === 'en');
$ev_title = function_exists('get_lang_field') ? get_lang_field($event, 'title') : (!empty($event['title']) ? $event['title'] : '');
$page_title = !empty($ev_title) ? $ev_title : (($is_en) ? 'Event Details' : 'ইভেন্ট বিস্তারিত');

// Bengali Date Formatter
function format_bn_event_date_single($date_str, $is_en = false) {
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
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>" class="text-white-50 text-decoration-none"><i class="fas fa-home"></i> <?php echo $is_en ? 'Home' : 'হোম'; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('events'); ?>" class="text-white-50 text-decoration-none"><?php echo $is_en ? 'Events' : 'ইভেন্টসমূহ'; ?></a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page"><?php echo html_escape(mb_strimwidth($page_title, 0, 35, "...")); ?></li>
                    </ol>
                </nav>
                <h1 class="page-header-title text-white fw-bold mb-1"><?php echo html_escape($page_title); ?></h1>
                <p class="text-white-50 mb-0 mt-2">
                    <a href="<?php echo base_url('events'); ?>" class="text-white text-decoration-none btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                        <i class="fas fa-arrow-left me-1"></i> <?php echo ($is_en) ? 'Back to All Events' : 'সকল ইভেন্টে ফিরে যান'; ?>
                    </a>
                </p>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon" style="font-size: 4.5rem; color: rgba(255,255,255,0.85);">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Event View Container -->
<section class="section py-5" style="background-color: #f8fafc; min-height: 500px;">
    <div class="container">
        <div class="row g-4">
            <!-- Main Content Area -->
            <div class="col-lg-8 col-md-7 col-sm-12">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">
                    <?php if (!empty($event['image'])) { ?>
                        <div class="text-center" style="max-height: 480px; overflow: hidden; background: #0f172a;">
                            <img src="<?=base_url('uploads/frontend/events/' . $event['image'] )?>" alt="<?php echo html_escape($ev_title); ?>" class="img-fluid w-100" style="object-fit: cover; max-height: 480px;">
                        </div>
                    <?php } ?>
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge text-white px-3 py-2 rounded-pill" style="background-color: var(--primary-color); font-size: 0.82rem;">
                                <i class="fas fa-tag me-1"></i>
                                <?php
                                    if ($event['type'] == 'holiday') {
                                        echo translate('holiday');
                                    } elseif (is_numeric($event['type'])) {
                                        $type_row = $this->db->get_where('event_types', array('id' => $event['type']))->row_array();
                                        echo !empty($type_row) ? html_escape(function_exists('get_lang_field') ? get_lang_field($type_row, 'name') : $type_row['name']) : translate('event');
                                    } else {
                                        echo html_escape(function_exists('get_lang_field') ? get_lang_field($event, 'type') : $event['type']);
                                    }
                                ?>
                            </span>
                            <?php if ($event['end_date'] >= date('Y-m-d')): ?>
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.82rem;">
                                    <i class="fas fa-circle-check me-1"></i> <?php echo $is_en ? 'Upcoming' : 'আসন্ন অনুষ্ঠান'; ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.82rem;">
                                    <i class="fas fa-history me-1"></i> <?php echo $is_en ? 'Completed' : 'সম্পন্ন অনুষ্ঠান'; ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h2 class="fw-bold mb-3" style="color: #1e293b; font-size: 1.65rem; line-height: 1.35;"><?php echo html_escape($ev_title); ?></h2>
                        
                        <div class="d-flex flex-wrap gap-3 py-3 mb-4 border-top border-bottom text-muted small" style="background: #f8fafc; border-radius: 8px; padding-left: 12px; padding-right: 12px;">
                            <span>
                                <i class="far fa-calendar-alt text-success me-1"></i> 
                                <strong><?php echo $is_en ? 'Date: ' : 'তারিখ: '; ?></strong>
                                <?php 
                                    if ($event['start_date'] == $event['end_date']) {
                                        echo format_bn_event_date_single($event['start_date'], $is_en);
                                    } else {
                                        echo format_bn_event_date_single($event['start_date'], $is_en) . ' — ' . format_bn_event_date_single($event['end_date'], $is_en);
                                    }
                                ?>
                            </span>
                            <?php if (!empty($event['created_by'])) { ?>
                                <span><i class="far fa-user text-primary me-1"></i> <?=($is_en ? 'Published by' : 'কর্তৃক')?>: <?=get_type_name_by_id('staff', $event['created_by'], 'name')?></span>
                            <?php } ?>
                        </div>

                        <?php
                        if (!empty($event['audition'])) {
                            $auditions = array(
                                "1" => "everybody",
                                "2" => "class",
                                "3" => "section",
                            ); 
                            $aud_key = !empty($event['audition']) ? (string)$event['audition'] : '1';
                            $audition = isset($auditions[$aud_key]) ? $auditions[$aud_key] : 'everybody';
                            $subtxt = "";
                            if($aud_key != '1') {
                                $array = array();
                                if ($event['audition'] == 2) {
                                    $selecteds = json_decode($event['selected_list']); 
                                    foreach ($selecteds as $selected) {
                                       $array[] = get_type_name_by_id('class', $selected);
                                    }
                                } 
                                if ($event['audition'] == 3) {
                                    $selecteds = json_decode($event['selected_list']); 
                                    foreach ($selecteds as $selected) {
                                        $selected = explode('-', $selected);
                                        $array[] =  get_type_name_by_id('class', $selected[0]) . " (" . get_type_name_by_id('section', $selected[1])  . ')' ;
                                    }
                                }
                                $subtxt = " (" . implode(', ', $array) . ")";
                            }
                            $for_label = ($is_en) ? 'Event Audience:' : 'উদ্দিষ্ট অংশগ্রহণকারী:';
                            echo "<div class='alert alert-light border py-2 px-3 mb-4 rounded-3' style='background:#f0fdf4; border-color:#bbf7d0 !important;'><i class='fas fa-users text-success me-2'></i><strong>$for_label</strong> " . translate($audition) . $subtxt . "</div>";
                        }
                        ?>

                        <div class="event-details-content lh-lg" style="color: #334155; font-size: 1.05rem;">
                            <?php echo function_exists('get_lang_field') ? get_lang_field($event, 'remark') : $event['remark']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 col-md-5 col-sm-12">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0" style="color: var(--primary-color);">
                            <i class="fas fa-calendar-alt me-2"></i> <?php echo ($is_en) ? 'Recent Events' : 'সাম্প্রতিক ইভেন্টস'; ?>
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0">
                            <?php
                            $this->db->limit(6);
                            $this->db->where('branch_id', $branchID);
                            $this->db->where('status', 1);
                            $this->db->where('show_web', 1);
                            $this->db->order_by("start_date", "desc");
                            $q = $this->db->get('event');
                            if ($q->num_rows() > 0) {
                                $result = $q->result_array();
                                foreach ($result as $key => $value) {
                                    $rec_title = function_exists('get_lang_field') ? get_lang_field($value, 'title') : $value['title'];
                            ?>
                            <li class="py-3 border-bottom">
                                <a href="<?=base_url('home/event_view/' . $value['id'] )?>" class="text-decoration-none fw-semibold" style="color: #1e293b; transition: color 0.2s;" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color='#1e293b'">
                                    <?php echo html_escape($rec_title); ?>
                                </a>
                                <div class="text-muted small mt-1">
                                    <i class="far fa-calendar-alt text-success me-1"></i> <?php echo format_bn_event_date_single($value['start_date'], $is_en); ?>
                                </div>
                            </li>
                            <?php } } else { ?>
                                <li class="text-muted text-center py-3"><?php echo ($is_en ? 'No recent events' : 'কোনো সাম্প্রতিক ইভেন্ট নেই'); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
