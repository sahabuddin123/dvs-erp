<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = !empty($event['title']) ? $event['title'] : (($current_lang == 'en') ? 'Event Details' : 'ইভেন্ট বিস্তারিত');
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <p class="text-white-50 mb-0 mt-2">
                    <a href="<?php echo base_url('events'); ?>" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i> <?php echo ($current_lang == 'en') ? 'Back to Events' : 'সকল ইভেন্টে ফিরে যান'; ?>
                    </a>
                </p>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Event View Container -->
<section class="section py-5" style="background-color: #f8fafc; min-height: 500px;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8 col-md-7 col-sm-12">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 10px; overflow: hidden;">
                    <?php if (!empty($event['image'])) { ?>
                        <div class="text-center bg-dark" style="max-height: 450px; overflow: hidden;">
                            <img src="<?=base_url('uploads/frontend/events/' . $event['image'] )?>" alt="<?php echo html_escape($event['title']); ?>" class="img-fluid w-100" style="object-fit: contain; max-height: 450px;">
                        </div>
                    <?php } ?>
                    <div class="card-body p-4 p-md-5">
                        <h2 class="fw-bold mb-3" style="color: #1e293b;"><?php echo html_escape($event['title']); ?></h2>
                        
                        <div class="d-flex flex-wrap gap-3 py-2 mb-4 border-bottom text-muted small">
                            <span><i class="far fa-calendar-alt text-success me-1"></i> <?php echo _d($event['start_date']); ?> To <?php echo _d($event['end_date']); ?></span>
                            <?php if (!empty($event['created_by'])) { ?>
                                <span><i class="far fa-user text-primary me-1"></i> By <?=get_type_name_by_id('staff', $event['created_by'], 'name')?></span>
                            <?php } ?>
                            <span>
                                <i class="fas fa-tag text-warning me-1"></i>
                                <?php
                                    if ($event['type'] != 'holiday'){
                                        echo get_type_name_by_id('event_types', $event['type']);
                                    } else {
                                        echo translate('holiday'); 
                                    }
                                ?>
                            </span>
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
                            echo "<div class='alert alert-light border py-2 px-3 mb-4'><strong>Event For:</strong> " . translate($audition) . $subtxt . "</div>";
                        }
                        ?>

                        <div class="event-details-content lh-lg" style="color: #334155; font-size: 1.05rem;">
                            <?php echo $event['remark']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 col-md-5 col-sm-12">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 10px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0" style="color: var(--primary-color);">
                            <i class="fas fa-calendar-alt me-2"></i> <?php echo ($current_lang == 'en') ? 'Recent Events' : 'সাম্প্রতিক ইভেন্টস'; ?>
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0">
                            <?php
                            $start_date = date('Y-m-d', strtotime("+2 day"));
                            $end_date = date('Y-m-d', strtotime("-4 day"));
                            $this->db->limit(6);
                            $this->db->where('start_date >=', $end_date);
                            $this->db->where('start_date <=', $start_date);
                            $this->db->where('branch_id', $branchID);
                            $this->db->where('status', 1);
                            $this->db->where('show_web', 1);
                            $this->db->order_by("id", "desc");
                            $q = $this->db->get('event');
                            if ($q->num_rows() > 0) {
                                $result = $q->result_array();
                                foreach ($result as $key => $value) {
                            ?>
                            <li class="py-2 border-bottom">
                                <a href="<?=base_url('home/event_view/' . $value['id'] )?>" class="text-decoration-none fw-semibold" style="color: #1e293b;">
                                    <?php echo html_escape($value['title']); ?>
                                </a>
                                <div class="text-muted small mt-1">
                                    <i class="far fa-clock me-1"></i> <?php echo get_nicetime($value['created_at']); ?>
                                </div>
                            </li>
                            <?php } } else { ?>
                                <li class="text-muted text-center py-3">No recent events</li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
