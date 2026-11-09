<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$is_en = ($current_lang == 'en');
$page_title = $is_en ? 'Photo Gallery' : 'ফটো গ্যালারি';
$page_subtitle = $is_en ? 'Explore memorable moments, events and activities through our photo albums.' : 'মাদরাসার বিভিন্ন অনুষ্ঠান, উৎসব ও স্মরণীয় মুহূর্তের আলোকচিত্র অ্যালবাম।';
?>

<!-- Custom CSS for Modern Gallery UI -->
<style>
.gallery-nav-pills {
    display: inline-flex;
    background: #f1f5f9;
    padding: 6px;
    border-radius: 50px;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
}
.gallery-type-pill {
    padding: 10px 24px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.95rem;
    color: #475569;
    text-decoration: none;
    transition: all 0.25s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.gallery-type-pill:hover {
    color: var(--primary-color);
}
.gallery-type-pill.active {
    background: var(--primary-color);
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.gallery-type-pill .badge {
    background: rgba(255,255,255,0.25);
    color: #fff;
    font-size: 0.75rem;
    padding: 4px 8px;
    border-radius: 20px;
}
.gallery-type-pill:not(.active) .badge {
    background: #e2e8f0;
    color: #475569;
}

.gallery-filter-btn {
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #475569;
    padding: 7px 18px;
    border-radius: 30px;
    font-size: 0.88rem;
    font-weight: 600;
    transition: all 0.2s ease;
    cursor: pointer;
}
.gallery-filter-btn:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
    background: #f8fafc;
}
.gallery-filter-btn.active {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
}

.album-card {
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.05);
    transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s ease;
}
.album-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 14px 28px rgba(0,0,0,0.12);
    border-color: rgba(0,0,0,0.08);
}
.album-thumb-wrap {
    position: relative;
    height: 240px;
    overflow: hidden;
    background: #0f172a;
}
.album-thumb-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.album-card:hover .album-thumb-wrap img {
    transform: scale(1.08);
}
.album-category-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(4px);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    letter-spacing: 0.3px;
    z-index: 2;
}
.album-count-badge {
    position: absolute;
    top: 14px;
    right: 14px;
    background: var(--primary-color);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    z-index: 2;
}
.album-card-body {
    padding: 20px 22px;
}
.album-title {
    font-size: 1.12rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.45;
    margin-bottom: 8px;
    transition: color 0.2s;
}
.album-card:hover .album-title {
    color: var(--primary-color);
}
.album-desc {
    color: #64748b;
    font-size: 0.88rem;
    line-height: 1.6;
    margin-bottom: 14px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.album-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #f1f5f9;
    padding-top: 14px;
}
.album-date {
    font-size: 0.82rem;
    color: #94a3b8;
    font-weight: 500;
}
.btn-view-album {
    font-weight: 600;
    font-size: 0.88rem;
    color: var(--primary-color);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: transform 0.2s;
}
.album-card:hover .btn-view-album {
    transform: translateX(4px);
}
</style>

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
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Gallery Section -->
<section class="section bg-light py-5" style="min-height: 600px;">
    <div class="container">
        
        <!-- Gallery Type Switcher (Photo Gallery vs Video Gallery) -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <div class="gallery-nav-pills shadow-sm">
                    <a href="<?php echo base_url('gallery'); ?>" class="gallery-type-pill active">
                        <i class="fas fa-camera-retro"></i>
                        <span><?php echo $is_en ? 'Photo Gallery' : 'ফটো গ্যালারি'; ?></span>
                        <span class="badge"><?php echo isset($total_photos_count) ? $total_photos_count : count($albums); ?></span>
                    </a>
                    <a href="<?php echo base_url('video-gallery'); ?>" class="gallery-type-pill">
                        <i class="fas fa-video"></i>
                        <span><?php echo $is_en ? 'Video Gallery' : 'ভিডিও গ্যালারি'; ?></span>
                        <span class="badge"><?php echo isset($total_videos_count) ? $total_videos_count : 0; ?></span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="row g-3 align-items-center justify-content-between mb-4">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-2" id="galleryFilterContainer">
                    <button type="button" class="gallery-filter-btn active" data-filter="all">
                        <i class="fas fa-border-all me-1"></i> <?php echo $is_en ? 'All Albums' : 'সকল অ্যালবাম'; ?> (<?php echo count($albums); ?>)
                    </button>
                    <?php if (!empty($category)): 
                        foreach ($category as $cat):
                            $cat_name = $is_en && !empty($cat['name_en']) ? $cat['name_en'] : $cat['name'];
                    ?>
                        <button type="button" class="gallery-filter-btn" data-filter="cat-<?php echo $cat['id']; ?>">
                            <?php echo html_escape($cat_name); ?>
                        </button>
                    <?php endforeach; endif; ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 30px 0 0 30px;"><i class="fas fa-search"></i></span>
                    <input type="text" id="albumSearchInput" class="form-control border-start-0 ps-0" placeholder="<?php echo $is_en ? 'Search albums...' : 'অ্যালবাম অনুসন্ধান করুন...'; ?>" style="border-radius: 0 30px 30px 0; font-size: 0.9rem;">
                </div>
            </div>
        </div>

        <!-- Albums Grid -->
        <div class="row g-4" id="albumsGrid">
            <?php 
            if (!empty($albums)) {
                foreach ($albums as $album) {
                    $alb_title = $is_en && !empty($album['title_en']) ? $album['title_en'] : $album['title'];
                    $alb_desc = $is_en && !empty($album['description_en']) ? $album['description_en'] : $album['description'];
                    $cat_name = $is_en && !empty($album['category_name_en']) ? $album['category_name_en'] : (!empty($album['category_name']) ? $album['category_name'] : ($is_en ? 'General' : 'সাধারণ'));
                    
                    // Album elements / photo count
                    $elements = !empty($album['elements']) ? json_decode($album['elements'], true) : [];
                    $photo_count = is_array($elements) ? count($elements) : 0;
                    if ($photo_count == 0 && !empty($album['thumb_image'])) {
                        $photo_count = 1;
                    }

                    // Cover image
                    $thumb_url = $this->gallery_model->get_image_url($album['thumb_image']);
                    $view_url = base_url('gallery-details/' . (!empty($album['alias']) ? $album['alias'] : $album['id']));
            ?>
            <div class="col-md-6 col-lg-4 album-item" data-category="cat-<?php echo $album['category_id']; ?>" data-title="<?php echo html_escape(mb_strtolower($alb_title . ' ' . $cat_name)); ?>">
                <div class="album-card h-100 d-flex flex-column">
                    <div class="album-thumb-wrap">
                        <a href="<?php echo $view_url; ?>">
                            <img src="<?php echo $thumb_url; ?>" alt="<?php echo html_escape($alb_title); ?>" loading="lazy" onerror="this.onerror=null;this.src='<?php echo base_url('uploads/app_image/default.png'); ?>';">
                        </a>
                        <span class="album-category-badge">
                            <i class="fas fa-tag me-1"></i> <?php echo html_escape($cat_name); ?>
                        </span>
                        <span class="album-count-badge">
                            <i class="fas fa-images me-1"></i> <?php echo $photo_count; ?> <?php echo $is_en ? 'Photos' : 'টি ছবি'; ?>
                        </span>
                    </div>
                    <div class="album-card-body d-flex flex-column flex-grow-1">
                        <a href="<?php echo $view_url; ?>" class="text-decoration-none">
                            <h3 class="album-title"><?php echo html_escape($alb_title); ?></h3>
                        </a>
                        <?php if (!empty($alb_desc)): ?>
                            <p class="album-desc"><?php echo html_escape($alb_desc); ?></p>
                        <?php endif; ?>
                        
                        <div class="album-footer mt-auto">
                            <span class="album-date">
                                <i class="far fa-calendar-alt me-1"></i> <?php echo date('d M, Y', strtotime($album['date'])); ?>
                            </span>
                            <a href="<?php echo $view_url; ?>" class="btn-view-album">
                                <span><?php echo $is_en ? 'View Album' : 'অ্যালবাম দেখুন'; ?></span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else { ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-info d-inline-block px-5 py-4 shadow-sm" style="border-radius: 12px; max-width: 500px;">
                    <i class="fas fa-images fa-3x mb-3 text-muted"></i>
                    <h5 class="fw-bold mb-1"><?php echo $is_en ? 'No Photo Albums Found' : 'কোনো ফটো অ্যালবাম পাওয়া যায়নি'; ?></h5>
                    <p class="mb-0 text-muted small"><?php echo $is_en ? 'New photo albums will be published soon.' : 'শীঘ্রই নতুন ছবির অ্যালবাম যুক্ত করা হবে।'; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- No search results state -->
        <div id="noSearchResults" class="text-center py-5 d-none">
            <div class="alert alert-warning d-inline-block px-5 py-4 shadow-sm" style="border-radius: 12px; max-width: 480px;">
                <i class="fas fa-search fa-2x mb-2 text-warning"></i>
                <h5 class="fw-bold mb-1"><?php echo $is_en ? 'No matching albums found' : 'কোনো অ্যালবাম পাওয়া যায়নি'; ?></h5>
                <p class="mb-0 text-muted small"><?php echo $is_en ? 'Try searching with different keywords or clear the filter.' : 'অন্য কি-ওয়ার্ড দিয়ে অনুসন্ধান করুন অথবা ফিল্টার পরিবর্তন করুন।'; ?></p>
            </div>
        </div>

    </div>
</section>

<!-- Filter & Search JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var filterBtns = document.querySelectorAll('.gallery-filter-btn');
    var albumItems = document.querySelectorAll('.album-item');
    var searchInput = document.getElementById('albumSearchInput');
    var noResults = document.getElementById('noSearchResults');
    var currentFilter = 'all';

    function applyFilterAndSearch() {
        var query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var visibleCount = 0;

        albumItems.forEach(function(item) {
            var itemCategory = item.getAttribute('data-category');
            var itemTitle = item.getAttribute('data-title');
            var matchesCategory = (currentFilter === 'all' || itemCategory === currentFilter);
            var matchesSearch = (query === '' || itemTitle.indexOf(query) !== -1);

            if (matchesCategory && matchesSearch) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (noResults) {
            if (visibleCount === 0 && albumItems.length > 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }
    }

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            applyFilterAndSearch();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyFilterAndSearch);
    }
});
</script>
