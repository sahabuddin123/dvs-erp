<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = ($current_lang == 'en') ? 'Notice' : 'নোটিশ';
$date_timestamp = !empty($event['date']) ? strtotime($event['date']) : time();
$published_date_formatted = date('F j, Y', $date_timestamp);
?>

<!-- Page Header (DVS Match) -->
<div class="page-header" style="background: var(--header-bg);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <p class="page-header-subtitle text-muted mb-0 mt-2">
                    Published on <?php echo $published_date_formatted; ?>
                </p>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Notice Details Content -->
<section class="section bg-light py-5" style="min-height: 550px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-md-flex justify-content-between align-items-center">
                            <h3 class="mb-md-0 mb-2 fw-bold" style="color: #1e293b;"><?php echo html_escape($event['title']); ?></h3>
                            <div>
                                <span class="badge p-2 me-2 text-white" style="background-color: var(--dark-color); border-radius: 6px; font-weight: 500;">
                                    <i class="fas fa-newspaper me-1"></i> <?php echo ($current_lang == 'en') ? 'Notice' : 'নোটিশ'; ?>
                                </span>
                                <span class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i> Published on <?php echo $published_date_formatted; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="post-content mb-4" id="description">
                            <?php if (!empty($event['image'])) { ?>
                                <p class="text-center mb-4">
                                    <img src="<?php echo base_url('uploads/frontend/news/' . $event['image']); ?>" alt="<?php echo html_escape($event['title']); ?>" class="img-fluid" style="border: 1px solid #dee2e6; border-radius: 6px; padding: 8px; max-height: 650px; background: #fff;">
                                    <br>
                                </p>
                            <?php } ?>
                            <div class="lh-lg" style="color: #334155; font-size: 1.05rem;">
                                <?php echo $event['description']; ?>
                            </div>
                        </div>
                        <div class="row mt-4 pt-3 border-top">
                            <div class="col-md-6 d-flex align-items-center">
                                <button onclick="history.go(-1)" class="btn text-light" style="background-color: var(--dark-color); border-radius: 6px; font-weight: 500; padding: 7px 22px;">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
