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

<!-- Main Gallery View Section -->
<section class="gallery-section py-5 bg-light" style="min-height: 500px;">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-images me-2"></i> <?php echo html_escape($page_title); ?></h2>
            <p>Explore moments captured at our school</p>
        </div>

        <div class="row g-4 gallery-container">
            <?php 
            if (!empty($galleryList)) {
                foreach ($galleryList as $row) { 
                    $img_url = $this->gallery_model->get_image_url($row['thumb_image']);
                    $album_title = !empty($row['title']) ? $row['title'] : 'Science Fair';
            ?>
            <div class="col-sm-6 col-md-4 col-lg-3 gallery-item">
                <a href="<?php echo $img_url; ?>" class="gallery-popup d-block text-decoration-none" title="<?php echo html_escape($album_title); ?>">
                    <div class="gallery-card shadow-sm border-0 position-relative overflow-hidden" style="border-radius: 8px; background: #fff; height: 260px;">
                        <img src="<?php echo $img_url; ?>" alt="<?php echo html_escape($album_title); ?>" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        <div class="gallery-overlay d-flex align-items-end p-3 position-absolute w-100 h-100 top-0 start-0" style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.2) 60%, transparent 100%); transition: all 0.3s ease;">
                            <div class="gallery-info text-white">
                                <h5 class="mb-1 fw-bold fs-6"><?php echo html_escape($album_title); ?></h5>
                                <?php 
                                $photo_sub = !empty($row['description']) ? get_lang_field($row, 'description') : (!empty($row['category_name']) ? $row['category_name'] : '');
                                if (!empty($photo_sub)) { ?>
                                    <p class="mb-0 text-white-50 small"><?php echo html_escape($photo_sub); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } 
            } else { ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-info d-inline-block px-5 py-3 shadow-sm" style="background-color: #e0f2fe; border-color: #bae6fd; color: #0369a1; border-radius: 8px;">
                    <i class="fas fa-images fa-2x mb-2 d-block"></i>
                    <h5 class="mb-1 fw-bold"><?php echo ($current_lang == 'en') ? 'No Photos Found' : 'কোনো ছবি পাওয়া যায়নি'; ?></h5>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Magnific Popup Lightbox Initialization -->
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.magnificPopup !== 'undefined') {
        jQuery('.gallery-popup').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1]
            },
            zoom: {
                enabled: true,
                duration: 300,
                easing: 'ease-in-out'
            }
        });
    }
});
</script>
