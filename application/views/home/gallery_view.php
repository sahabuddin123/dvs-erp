<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$is_en = ($current_lang == 'en');

$album_title = $is_en && !empty($album['title_en']) ? $album['title_en'] : $album['title'];
$album_desc = $is_en && !empty($album['description_en']) ? $album['description_en'] : $album['description'];
$cat_name = $is_en && !empty($album['category_name_en']) ? $album['category_name_en'] : (!empty($album['category_name']) ? $album['category_name'] : ($is_en ? 'General' : 'সাধারণ'));

// Parse elements
$raw_elements = !empty($album['elements']) ? json_decode($album['elements'], true) : [];
$photos = [];
$videos = [];

if (is_array($raw_elements) && !empty($raw_elements)) {
    foreach ($raw_elements as $item) {
        if (isset($item['type']) && $item['type'] == 2) {
            $videos[] = $item;
        } else {
            $photos[] = $item;
        }
    }
}

// Ensure at least the cover photo is shown if no photos in elements
if (empty($photos) && !empty($album['thumb_image'])) {
    $photos[] = [
        'image' => $album['thumb_image'],
        'type' => '1',
        'date' => $album['date']
    ];
}

$page_title = $album_title;
$page_subtitle = $is_en ? 'Photo Album & Media Collection' : 'আলোকচিত্র অ্যালবাম ও মিডিয়া সংগ্রহ';
?>

<!-- Custom CSS for Album Detail Page -->
<style>
.album-meta-box {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    padding: 24px 28px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.04);
}
.photo-card {
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    background: #0f172a;
    height: 240px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s ease;
}
.photo-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 24px rgba(0,0,0,0.18);
}
.photo-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.photo-card:hover img {
    transform: scale(1.1);
}
.photo-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.1) 60%, transparent 100%);
    opacity: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
}
.photo-card:hover .photo-overlay {
    opacity: 1;
}
.photo-zoom-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    transform: scale(0.8);
    transition: transform 0.2s ease;
}
.photo-card:hover .photo-zoom-icon {
    transform: scale(1);
}
.photo-caption-pill {
    position: absolute;
    bottom: 12px;
    left: 12px;
    right: 12px;
    color: #fff;
    font-size: 0.8rem;
    font-weight: 500;
    text-shadow: 0 1px 3px rgba(0,0,0,0.8);
    z-index: 2;
}

/* Lightbox styles */
.mfp-bg {
    background: #0b0f19;
    opacity: 0.92;
}
.mfp-image-holder .mfp-close,
.mfp-iframe-holder .mfp-close {
    color: #fff;
    right: 0;
    top: -40px;
}
</style>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-header-title"><?php echo html_escape($album_title); ?></h1>
                <p class="page-header-subtitle text-muted mb-0 mt-2"><?php echo html_escape($page_subtitle); ?></p>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <a href="<?php echo base_url('gallery'); ?>" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 30px; font-weight: 600;">
                    <i class="fas fa-arrow-left me-2"></i> <?php echo $is_en ? 'All Albums' : 'সকল অ্যালবাম'; ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Section -->
<section class="section bg-light py-5" style="min-height: 600px;">
    <div class="container">

        <!-- Album Metadata Header Box -->
        <div class="album-meta-box mb-5">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge px-3 py-2 text-white" style="background: var(--primary-color); border-radius: 20px; font-size: 0.82rem; font-weight: 600;">
                            <i class="fas fa-tag me-1"></i> <?php echo html_escape($cat_name); ?>
                        </span>
                        <span class="badge bg-secondary px-3 py-2" style="border-radius: 20px; font-size: 0.82rem;">
                            <i class="fas fa-camera me-1"></i> <?php echo count($photos); ?> <?php echo $is_en ? 'Photos' : 'টি ছবি'; ?>
                        </span>
                        <?php if (!empty($videos)): ?>
                        <span class="badge bg-danger px-3 py-2" style="border-radius: 20px; font-size: 0.82rem;">
                            <i class="fas fa-video me-1"></i> <?php echo count($videos); ?> <?php echo $is_en ? 'Videos' : 'টি ভিডিও'; ?>
                        </span>
                        <?php endif; ?>
                        <span class="text-muted ms-2" style="font-size: 0.88rem;">
                            <i class="far fa-calendar-alt me-1"></i> <?php echo date('d M, Y', strtotime($album['date'])); ?>
                        </span>
                    </div>
                    <h2 class="fw-bold mb-2" style="font-size: 1.45rem; color: #0f172a;"><?php echo html_escape($album_title); ?></h2>
                    <?php if (!empty($album_desc)): ?>
                        <p class="text-muted mb-0" style="line-height: 1.7; font-size: 0.95rem;"><?php echo html_escape($album_desc); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="<?php echo base_url('gallery'); ?>" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 30px; font-weight: 600; font-size: 0.9rem;">
                        <i class="fas fa-arrow-left me-1"></i> <?php echo $is_en ? 'Back to Gallery' : 'গ্যালারিতে ফিরে যান'; ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Photos Grid Section -->
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <h4 class="fw-bold mb-0" style="color: #1e293b; font-size: 1.2rem;">
                    <i class="fas fa-images me-2" style="color: var(--primary-color);"></i> <?php echo $is_en ? 'Photos in this Album' : 'অ্যালবামের আলোকচিত্রসমূহ'; ?>
                </h4>
                <small class="text-muted"><?php echo $is_en ? 'Click any photo to zoom & browse' : 'যেকোনো ছবিতে ক্লিক করে বড় করে দেখুন'; ?></small>
            </div>

            <div class="row g-3 g-md-4 gallery-popup-container">
                <?php 
                $p_index = 1;
                foreach ($photos as $photo):
                    $img_filename = is_array($photo) ? ($photo['image'] ?? '') : $photo;
                    $img_url = $this->gallery_model->get_image_url($img_filename);
                ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?php echo $img_url; ?>" class="gallery-popup-item text-decoration-none d-block" title="<?php echo html_escape($album_title . ' - ' . ($is_en ? 'Photo ' : 'ছবি ') . $p_index); ?>">
                        <div class="photo-card">
                            <img src="<?php echo $img_url; ?>" alt="<?php echo html_escape($album_title); ?>" loading="lazy" onerror="this.onerror=null;this.src='<?php echo base_url('uploads/app_image/default.png'); ?>';">
                            <div class="photo-overlay">
                                <div class="photo-zoom-icon">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                                <div class="photo-caption-pill text-truncate">
                                    <i class="fas fa-camera me-1"></i> <?php echo $is_en ? 'Photo #' . $p_index : 'ছবি নং ' . $p_index; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php 
                    $p_index++;
                endforeach; 
                ?>
            </div>
        </div>

        <!-- Videos in this Album (if any) -->
        <?php if (!empty($videos)): ?>
        <div class="mt-5">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <h4 class="fw-bold mb-0" style="color: #1e293b; font-size: 1.2rem;">
                    <i class="fas fa-video me-2 text-danger"></i> <?php echo $is_en ? 'Videos in this Album' : 'অ্যালবামের ভিডিও চিত্রসমূহ'; ?>
                </h4>
            </div>

            <div class="row g-4">
                <?php foreach ($videos as $vid): 
                    $v_url = !empty($vid['video_url']) ? $vid['video_url'] : '';
                    $v_thumb = $this->gallery_model->get_video_thumbnail($v_url, $vid['image'] ?? '');
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        <div class="position-relative" style="height: 200px; background: #000;">
                            <img src="<?php echo $v_thumb; ?>" class="w-100 h-100" style="object-fit: cover;" alt="Video">
                            <?php if (!empty($v_url)): ?>
                            <a href="<?php echo html_escape($v_url); ?>" target="_blank" rel="noopener noreferrer" class="position-absolute inset-0 d-flex align-items-center justify-content-center text-decoration-none" style="background: rgba(0,0,0,0.4);">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background: #dc2626; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                    <i class="fas fa-play ms-1"></i>
                                </div>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-1" style="font-size: 0.95rem;"><?php echo html_escape($album_title); ?></h6>
                            <?php if (!empty($v_url)): ?>
                                <a href="<?php echo html_escape($v_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger mt-2" style="border-radius: 20px;">
                                    <i class="fab fa-youtube me-1"></i> <?php echo $is_en ? 'Watch on YouTube' : 'ইউটিউবে দেখুন'; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- Lightbox Initialization (Magnific Popup) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.magnificPopup !== 'undefined') {
        jQuery('.gallery-popup-container').magnificPopup({
            delegate: 'a.gallery-popup-item',
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 2],
                tPrev: '<?php echo $is_en ? "Previous" : "পূর্ববর্তী"; ?>',
                tNext: '<?php echo $is_en ? "Next" : "পরবর্তী"; ?>',
                tCounter: '%curr% / %total%'
            },
            image: {
                titleSrc: function(item) {
                    return item.el.attr('title') || '';
                }
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
