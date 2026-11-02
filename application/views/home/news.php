<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'News & Updates' : 'সংবাদ ও আপডেট')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : 'সংবাদ ও আপডেট');
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <?php if (!empty($page_data['title'])) { ?>
                    <p class="text-white-50 mb-0 mt-2"><?php echo html_escape($page_data['title']); ?></p>
                <?php } ?>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main News Container -->
<section class="section py-5" style="background-color: #f8fafc; min-height: 500px;">
    <div class="container">
        <?php if (!empty($page_data['description'])) { ?>
            <div class="mb-4 text-muted">
                <?php echo $page_data['description']; ?>
            </div>
        <?php } ?>

        <div class="row g-4">
            <?php
            $url_alias = !empty($cms_setting['url_alias']) ? $cms_setting['url_alias'] : 'home';
            if (!empty($results)) {
                foreach ($results as $key => $value) {
                    $news_img = !empty($value['image']) ? base_url('uploads/frontend/news/' . $value['image']) : base_url('uploads/frontend/news/defualt.png');
            ?>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 10px; overflow: hidden; transition: all 0.3s ease;">
                    <div class="position-relative" style="height: 200px; overflow: hidden; background: #e2e8f0;">
                        <img src="<?php echo $news_img; ?>" alt="<?php echo html_escape($value['title']); ?>" class="w-100 h-100" style="object-fit: cover;">
                        <span class="badge position-absolute top-0 end-0 m-3 shadow-sm" style="background-color: var(--primary-color) !important;">
                            <i class="far fa-calendar-alt me-1"></i> <?php echo _d($value['date']); ?>
                        </span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold mb-2">
                                <a href="<?php echo base_url("home/news_view/" . $value['alias']); ?>" class="text-decoration-none" style="color: #1e293b;">
                                    <?php echo html_escape($value['title']); ?>
                                </a>
                            </h5>
                            <div class="small text-muted mb-3">
                                <span><i class="fas fa-clock me-1"></i> <?php echo _d($value['created_at']); ?></span>
                            </div>
                            <p class="card-text text-muted" style="font-size: 0.95rem;">
                                <?php echo mb_strimwidth(strip_tags($value['description']), 0, 100, "..."); ?>
                            </p>
                        </div>
                        <div class="mt-3 pt-3 border-top">
                            <a href="<?php echo base_url("home/news_view/" . $value['alias']); ?>" class="btn btn-sm text-white" style="background-color: var(--primary-color); border-radius: 6px;">
                                <?php echo ($current_lang == 'en') ? 'Read More' : 'বিস্তারিত পড়ুন'; ?> <i class="fas fa-arrow-right ms-1"></i>
                            </a>
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
            <div class="col-12 text-center py-5">
                <div class="alert alert-info d-inline-block px-5 py-4 shadow-sm" style="background-color: #e0f2fe; border-color: #bae6fd; color: #0369a1; border-radius: 10px;">
                    <i class="fas fa-newspaper fa-2x mb-2 d-block"></i>
                    <h5 class="mb-1 fw-bold"><?php echo ($current_lang == 'en') ? 'No News Found' : 'কোনো সংবাদ পাওয়া যায়নি'; ?></h5>
                    <p class="mb-0 small"><?php echo ($current_lang == 'en') ? 'Currently there are no news or announcements published.' : 'বর্তমানে কোনো সংবাদ বা বিজ্ঞপ্তি নেই।'; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
