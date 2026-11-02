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
            <?php 
            if (!empty($category)) {
                foreach ($category as $cat) {
                    $cat_name = function_exists('get_lang_field') ? get_lang_field($cat, 'category_name') : $cat['category_name'];
                    if (empty($cat_name)) {
                        $cat_name = $cat['category_name'];
                    }
                    $photo_cnt = !empty($cat['photo_count']) ? $cat['photo_count'] : (!empty($galleryList) ? count($galleryList) : 8);
                    
                    // Cover image
                    $cover_img = base_url('assets/frontend/dvs/storage/galleries/dOE0oYuYQRh3bDbaMw6J9JQyEMO884GAv5lQ5YGq.jpg');
                    if (!empty($cat['cover_image']) && file_exists(FCPATH . 'uploads/frontend/gallery/' . $cat['cover_image'])) {
                        $cover_img = base_url('uploads/frontend/gallery/' . $cat['cover_image']);
                    } elseif (!empty($cat['cover_image']) && file_exists(FCPATH . 'assets/frontend/dvs/storage/galleries/' . $cat['cover_image'])) {
                        $cover_img = base_url('assets/frontend/dvs/storage/galleries/' . $cat['cover_image']);
                    }
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card gallery-category-card h-100 shadow-sm border-0" style="border-radius: 8px; overflow: hidden; background: #fff;">
                    <img src="<?php echo $cover_img; ?>" class="card-img-top" alt="<?php echo html_escape($cat_name); ?>" style="height: 220px; object-fit: cover;">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-dark mb-2"><?php echo html_escape($cat_name); ?></h4>
                        <p class="card-text text-muted mb-3">
                            <i class="fas fa-images me-2 text-success"></i> <?php echo $photo_cnt; ?> <?php echo ($current_lang == 'en') ? 'Photos' : 'টি ছবি'; ?>
                        </p>
                        <a href="<?php echo base_url('gallery-details/' . $cat['category_id']); ?>" class="btn btn-outline-primary" style="border-radius: 6px; font-weight: 500;">
                            <?php echo ($current_lang == 'en') ? 'View Album' : 'View Album'; ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else { ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-info d-inline-block px-5 py-3 shadow-sm" style="background-color: #e0f2fe; border-color: #bae6fd; color: #0369a1; border-radius: 8px;">
                    <i class="fas fa-images fa-2x mb-2 d-block"></i>
                    <h5 class="mb-1 fw-bold"><?php echo ($current_lang == 'en') ? 'No Albums Found' : 'কোনো অ্যালবাম পাওয়া যায়নি'; ?></h5>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
