<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Gallery' : 'গ্যালারি')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : ($current_lang == 'en' ? 'Gallery' : 'গ্যালারি'));
$date_display = date('M d, Y');
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

<!-- Main Gallery Content -->
<section class="gallery-section py-5" style="background-color: #f8fafc; min-height: 500px;">
    <div class="container">
        <!-- Category Filter Buttons -->
        <?php if (!empty($category)) { ?>
        <div class="gallery-filter text-center mb-4">
            <button class="filter-btn active" data-filter="all">
                <i class="fas fa-th-large me-1"></i> <?php echo ($current_lang == 'en') ? 'All' : 'সবগুলো'; ?>
            </button>
            <?php foreach ($category as $row) { ?>
                <button class="filter-btn" data-filter="<?php echo $row['category_id']; ?>">
                    <?php echo html_escape($row['category_name']); ?>
                </button>
            <?php } ?>
        </div>
        <?php } ?>

        <!-- Gallery Grid -->
        <?php if (empty($galleryList)) { ?>
            <div class="text-center py-5">
                <div class="alert alert-info d-inline-block px-4 py-3" style="background-color: #e0f2fe; border-color: #bae6fd; color: #0369a1; border-radius: 8px;">
                    <i class="fas fa-info-circle me-2"></i> <?php echo ($current_lang == 'en') ? 'No photos available in gallery.' : 'গ্যালারিতে বর্তমানে কোনো ছবি পাওয়া যায়নি।'; ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="row g-4">
                <?php 
                $school = $this->uri->segment(1);
                foreach ($galleryList as $row) { 
                    $img_url = $this->gallery_model->get_image_url($row['thumb_image']);
                    $cat_id = !empty($row['category_id']) ? $row['category_id'] : 'all';
                    $album_title = !empty($row['title']) ? $row['title'] : 'Gallery Image';
                    $album_desc = !empty($row['description']) ? $row['description'] : '';
                    $has_sub_elements = !empty($row['elements']) && count(json_decode($row['elements'], true) ?: []) > 0;
                ?>
                <div class="col-sm-6 col-md-4 col-lg-3 gallery-item" data-category="<?php echo $cat_id; ?>">
                    <a href="<?php echo $img_url; ?>" class="gallery-popup d-block" title="<?php echo html_escape($album_title); ?>">
                        <div class="gallery-card shadow-sm">
                            <img src="<?php echo $img_url; ?>" alt="<?php echo html_escape($album_title); ?>" class="img-fluid">
                            <div class="gallery-overlay">
                                <div class="gallery-info p-3">
                                    <h5><?php echo html_escape($album_title); ?></h5>
                                    <?php if (!empty($album_desc)) { ?>
                                        <p><?php echo html_escape(mb_strlen($album_desc) > 60 ? mb_substr($album_desc, 0, 60) . '...' : $album_desc); ?></p>
                                    <?php } ?>
                                    <span class="badge bg-light text-dark mt-2 shadow-sm">
                                        <i class="fas fa-search-plus me-1"></i> <?php echo ($current_lang == 'en') ? 'View Image' : 'বড় করে দেখুন'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="mt-2 text-center">
                        <h6 class="fw-bold mb-1 text-truncate" style="color: #1e293b; font-size: 0.95rem;" title="<?php echo html_escape($album_title); ?>">
                            <?php echo html_escape($album_title); ?>
                        </h6>
                    </div>
                </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</section>
