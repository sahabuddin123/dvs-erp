<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Gallery' : 'গ্যালারি')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : ($current_lang == 'en' ? 'Gallery' : 'গ্যালারি'));
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-images"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Gallery Section -->
<section class="section bg-light my-5 py-5" style="min-height: 500px;">
    <div class="container">
        <div class="row g-4">
            <!-- Science Fair Album Card (Matching Live Site) -->
            <div class="col-md-6 col-lg-4">
                <div class="card gallery-category-card h-100 shadow-sm border-0" style="border-radius: 8px; overflow: hidden; background: #fff;">
                    <img src="<?php echo base_url('assets/frontend/dvs/storage/galleries/dOE0oYuYQRh3bDbaMw6J9JQyEMO884GAv5lQ5YGq.jpg'); ?>" class="card-img-top" alt="Science Fair" style="height: 220px; object-fit: cover;">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-dark mb-2">Science Fair</h4>
                        <p class="card-text text-muted mb-3">
                            <i class="fas fa-images me-2 text-success"></i> <?php echo !empty($galleryList) ? count($galleryList) : '8'; ?> <?php echo ($current_lang == 'en') ? 'Photos' : 'টি ছবি'; ?>
                        </p>
                        <a href="<?php echo base_url('gallery-details/1'); ?>" class="btn btn-outline-primary" style="border-radius: 6px; font-weight: 500;">
                            <?php echo ($current_lang == 'en') ? 'View Album' : 'View Album'; ?>
                        </a>
                    </div>
                </div>
            </div>

            <?php 
            // If there are other albums/categories in the database with multiple elements, render them as well
            if (!empty($category) && count($category) > 1) {
                foreach ($category as $cat) {
                    if ($cat['category_id'] == 1) continue; // already shown above
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card gallery-category-card h-100 shadow-sm border-0" style="border-radius: 8px; overflow: hidden; background: #fff;">
                    <div class="d-flex align-items-center justify-content-center bg-secondary text-white" style="height: 220px;">
                        <i class="fas fa-images fa-3x opacity-50"></i>
                    </div>
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-dark mb-2"><?php echo html_escape($cat['category_name']); ?></h4>
                        <p class="card-text text-muted mb-3"><i class="fas fa-images me-2 text-success"></i> <?php echo ($current_lang == 'en') ? 'Album' : 'অ্যালবাম'; ?></p>
                        <a href="<?php echo base_url('gallery-details/' . $cat['category_id']); ?>" class="btn btn-outline-primary" style="border-radius: 6px; font-weight: 500;">
                            <?php echo ($current_lang == 'en') ? 'View Album' : 'View Album'; ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>
</section>
