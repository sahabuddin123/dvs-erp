<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = ($current_lang == 'en') ? 'Gallery Details' : 'গ্যালারি বিস্তারিত';
$page_subtitle = 'Explore our memorable moments through photos.';
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <p class="page-header-subtitle text-muted mb-0 mt-2"><?php echo html_escape($page_subtitle); ?></p>
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

<!-- Main Gallery View Content -->
<section class="gallery-section py-5" style="background-color: #f8fafc; min-height: 500px;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h4 class="fw-bold mb-0" style="color: #1e293b;"><?php echo html_escape($gallery['title'] ?? ''); ?></h4>
            <a href="<?php echo base_url('gallery'); ?>" class="btn btn-outline-success btn-sm">
                <i class="fas fa-arrow-left me-1"></i> <?php echo ($current_lang == 'en') ? 'All Albums' : 'সব অ্যালবাম'; ?>
            </a>
        </div>

        <div class="row g-4">
            <?php 
            $elem = !empty($gallery['elements']) ? json_decode($gallery['elements'], TRUE) : [];
            if (!empty($elem)) {
                foreach ($elem as $key => $row) {
                    $url = "";
                    $is_video = false;
                    if ($row['type'] == 2) {
                        $is_video = true;
                        $url = $row['video_url'];
                    } else {
                        $url = $this->gallery_model->get_image_url($row['image']);
                    }
            ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="gallery-card shadow-sm">
                        <img src="<?php echo $this->gallery_model->get_image_url($row['image']); ?>" alt="Gallery Image" class="img-fluid">
                        <div class="gallery-overlay">
                            <div class="gallery-info p-3">
                                <a href="<?php echo $url; ?>" class="<?php echo $is_video ? 'popup-video' : 'gallery-popup'; ?> btn btn-light btn-sm text-dark shadow-sm">
                                    <i class="<?php echo $is_video ? 'fab fa-youtube text-danger' : 'fas fa-search-plus text-success'; ?> me-1"></i>
                                    <?php echo $is_video ? 'Play Video' : 'বড় করে দেখুন'; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                } 
            } else if (!empty($gallery['thumb_image'])) { 
                $img_url = $this->gallery_model->get_image_url($gallery['thumb_image']);
            ?>
                <div class="col-sm-6 col-md-4 col-lg-4">
                    <a href="<?php echo $img_url; ?>" class="gallery-popup d-block" title="<?php echo html_escape($gallery['title']); ?>">
                        <div class="gallery-card shadow-sm">
                            <img src="<?php echo $img_url; ?>" alt="<?php echo html_escape($gallery['title']); ?>" class="img-fluid">
                            <div class="gallery-overlay">
                                <div class="gallery-info p-3">
                                    <h5><?php echo html_escape($gallery['title']); ?></h5>
                                    <span class="badge bg-light text-dark mt-2 shadow-sm">
                                        <i class="fas fa-search-plus me-1"></i> বড় করে দেখুন
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } else { ?>
                <div class="col-12 text-center py-5">
                    <div class="alert alert-info d-inline-block px-4 py-3" style="background-color: #e0f2fe; border-color: #bae6fd; color: #0369a1; border-radius: 8px;">
                        <i class="fas fa-info-circle me-2"></i> <?php echo ($current_lang == 'en') ? 'No items in this album.' : 'এই অ্যালবামে কোনো ছবি পাওয়া যায়নি।'; ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
