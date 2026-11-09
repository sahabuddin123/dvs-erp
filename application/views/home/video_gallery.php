<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$is_en = ($current_lang == 'en');
$page_title = $is_en ? 'Video Gallery' : 'ভিডিও গ্যালারি';
$page_subtitle = $is_en ? 'Watch video clips of our academic programs, sports, ceremonies and student achievements.' : 'আমাদের শিক্ষা কার্যক্রম, বার্ষিক ক্রীড়া, ক্বেরাত প্রতিযোগিতা ও বিশেষ আয়োজনের ভিডিও চিত্র।';
?>

<!-- Custom CSS for Video Gallery UI -->
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

.video-card {
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.05);
    transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s ease;
}
.video-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 14px 28px rgba(0,0,0,0.12);
    border-color: rgba(0,0,0,0.08);
}
.video-thumb-wrap {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: #0f172a;
    cursor: pointer;
}
.video-thumb-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.video-card:hover .video-thumb-wrap img {
    transform: scale(1.08);
}
.video-play-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
}
.video-card:hover .video-play-overlay {
    background: rgba(15, 23, 42, 0.5);
}
.video-play-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #dc2626;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
    animation: videoPulse 2s infinite;
    transition: transform 0.2s ease, background 0.2s ease;
    border: none;
}
.video-play-btn i {
    margin-left: 3px;
}
.video-card:hover .video-play-btn {
    transform: scale(1.12);
    background: #b91c1c;
}
@keyframes videoPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
    }
    70% {
        box-shadow: 0 0 0 16px rgba(220, 38, 38, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
    }
}
.video-badge-yt {
    position: absolute;
    top: 14px;
    left: 14px;
    background: rgba(220, 38, 38, 0.9);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    backdrop-filter: blur(4px);
    z-index: 2;
}
.video-badge-cat {
    position: absolute;
    top: 14px;
    right: 14px;
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(4px);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    z-index: 2;
}
.video-card-body {
    padding: 20px 22px;
}
.video-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.45;
    margin-bottom: 8px;
    transition: color 0.2s;
    cursor: pointer;
}
.video-card:hover .video-title {
    color: var(--primary-color);
}
.video-desc {
    color: #64748b;
    font-size: 0.88rem;
    line-height: 1.6;
    margin-bottom: 14px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.video-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #f1f5f9;
    padding-top: 14px;
}
.video-date {
    font-size: 0.82rem;
    color: #94a3b8;
    font-weight: 500;
}
.btn-watch-video {
    font-weight: 600;
    font-size: 0.88rem;
    color: #dc2626;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}
.btn-watch-video:hover {
    color: #b91c1c;
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
                    <i class="fas fa-video"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Video Gallery Section -->
<section class="section bg-light py-5" style="min-height: 600px;">
    <div class="container">
        
        <!-- Gallery Type Switcher (Photo Gallery vs Video Gallery) -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <div class="gallery-nav-pills shadow-sm">
                    <a href="<?php echo base_url('gallery'); ?>" class="gallery-type-pill">
                        <i class="fas fa-camera-retro"></i>
                        <span><?php echo $is_en ? 'Photo Gallery' : 'ফটো গ্যালারি'; ?></span>
                        <span class="badge"><?php echo isset($total_photos_count) ? $total_photos_count : 0; ?></span>
                    </a>
                    <a href="<?php echo base_url('video-gallery'); ?>" class="gallery-type-pill active">
                        <i class="fas fa-video"></i>
                        <span><?php echo $is_en ? 'Video Gallery' : 'ভিডিও গ্যালারি'; ?></span>
                        <span class="badge"><?php echo isset($total_videos_count) ? $total_videos_count : count($videos); ?></span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="row g-3 align-items-center justify-content-between mb-4">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-2" id="videoFilterContainer">
                    <button type="button" class="gallery-filter-btn active" data-filter="all">
                        <i class="fas fa-play-circle me-1"></i> <?php echo $is_en ? 'All Videos' : 'সকল ভিডিও'; ?> (<?php echo count($videos); ?>)
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
                    <input type="text" id="videoSearchInput" class="form-control border-start-0 ps-0" placeholder="<?php echo $is_en ? 'Search videos...' : 'ভিডিও অনুসন্ধান করুন...'; ?>" style="border-radius: 0 30px 30px 0; font-size: 0.9rem;">
                </div>
            </div>
        </div>

        <!-- Video Cards Grid -->
        <div class="row g-4" id="videosGrid">
            <?php 
            if (!empty($videos)) {
                foreach ($videos as $vid) {
                    $vid_title = $is_en && !empty($vid['title_en']) ? $vid['title_en'] : $vid['title'];
                    $vid_desc = $is_en && !empty($vid['description_en']) ? $vid['description_en'] : $vid['description'];
                    $cat_name = $is_en && !empty($vid['category_name_en']) ? $vid['category_name_en'] : (!empty($vid['category_name']) ? $vid['category_name'] : ($is_en ? 'General' : 'সাধারণ'));
                    
                    // Video URL resolution
                    $video_url = !empty($vid['video_url']) ? $vid['video_url'] : '';
                    if (empty($video_url) && !empty($vid['elements'])) {
                        $elem = json_decode($vid['elements'], true);
                        if (is_array($elem)) {
                            foreach ($elem as $el) {
                                if (!empty($el['video_url'])) {
                                    $video_url = $el['video_url'];
                                    break;
                                }
                            }
                        }
                    }

                    // Thumbnail resolution
                    $thumb_url = $this->gallery_model->get_video_thumbnail($video_url, $vid['thumb_image']);
            ?>
            <div class="col-md-6 col-lg-4 video-item" data-category="cat-<?php echo $vid['category_id']; ?>" data-title="<?php echo html_escape(mb_strtolower($vid_title . ' ' . $cat_name)); ?>">
                <div class="video-card h-100 d-flex flex-column">
                    <div class="video-thumb-wrap trigger-video-modal" data-video-url="<?php echo html_escape($video_url); ?>" data-video-title="<?php echo html_escape($vid_title); ?>">
                        <img src="<?php echo $thumb_url; ?>" alt="<?php echo html_escape($vid_title); ?>" loading="lazy" onerror="this.onerror=null;this.src='<?php echo base_url('uploads/app_image/default.png'); ?>';">
                        <span class="video-badge-yt">
                            <i class="fab fa-youtube me-1"></i> YouTube
                        </span>
                        <span class="video-badge-cat">
                            <?php echo html_escape($cat_name); ?>
                        </span>
                        <div class="video-play-overlay">
                            <button type="button" class="video-play-btn" aria-label="Play Video">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                    <div class="video-card-body d-flex flex-column flex-grow-1">
                        <h3 class="video-title trigger-video-modal" data-video-url="<?php echo html_escape($video_url); ?>" data-video-title="<?php echo html_escape($vid_title); ?>">
                            <?php echo html_escape($vid_title); ?>
                        </h3>
                        <?php if (!empty($vid_desc)): ?>
                            <p class="video-desc"><?php echo html_escape($vid_desc); ?></p>
                        <?php endif; ?>
                        
                        <div class="video-footer mt-auto">
                            <span class="video-date">
                                <i class="far fa-calendar-alt me-1"></i> <?php echo date('d M, Y', strtotime($vid['date'])); ?>
                            </span>
                            <button type="button" class="btn-watch-video trigger-video-modal" data-video-url="<?php echo html_escape($video_url); ?>" data-video-title="<?php echo html_escape($vid_title); ?>">
                                <i class="fas fa-play-circle"></i>
                                <span><?php echo $is_en ? 'Watch Video' : 'ভিডিও দেখুন'; ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else { ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-info d-inline-block px-5 py-4 shadow-sm" style="border-radius: 12px; max-width: 500px;">
                    <i class="fas fa-video fa-3x mb-3 text-muted"></i>
                    <h5 class="fw-bold mb-1"><?php echo $is_en ? 'No Videos Found' : 'কোনো ভিডিও পাওয়া যায়নি'; ?></h5>
                    <p class="mb-0 text-muted small"><?php echo $is_en ? 'New video clips will be published soon.' : 'শীঘ্রই নতুন ভিডিও প্রকাশ করা হবে।'; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- No search results state -->
        <div id="noVideoSearchResults" class="text-center py-5 d-none">
            <div class="alert alert-warning d-inline-block px-5 py-4 shadow-sm" style="border-radius: 12px; max-width: 480px;">
                <i class="fas fa-search fa-2x mb-2 text-warning"></i>
                <h5 class="fw-bold mb-1"><?php echo $is_en ? 'No matching videos found' : 'কোনো ভিডিও পাওয়া যায়নি'; ?></h5>
                <p class="mb-0 text-muted small"><?php echo $is_en ? 'Try searching with different keywords or clear the filter.' : 'অন্য কি-ওয়ার্ড দিয়ে অনুসন্ধান করুন অথবা ফিল্টার পরিবর্তন করুন।'; ?></p>
            </div>
        </div>

    </div>
</section>

<!-- Video Play Modal -->
<div class="modal fade" id="videoPlayModal" tabindex="-1" aria-labelledby="videoModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-0 shadow-lg text-white" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header border-0 py-3 px-4 bg-black bg-opacity-50">
                <h5 class="modal-title fw-bold text-truncate me-3" id="videoModalTitle" style="font-size: 1.05rem;">
                    <i class="fab fa-youtube text-danger me-2"></i> <span></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9" id="videoIframeContainer">
                    <!-- Dynamic iframe inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Video Gallery Interactive Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var filterBtns = document.querySelectorAll('.gallery-filter-btn');
    var videoItems = document.querySelectorAll('.video-item');
    var searchInput = document.getElementById('videoSearchInput');
    var noResults = document.getElementById('noVideoSearchResults');
    var currentFilter = 'all';

    function applyFilterAndSearch() {
        var query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var visibleCount = 0;

        videoItems.forEach(function(item) {
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
            if (visibleCount === 0 && videoItems.length > 0) {
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

    // Modal Video Player Handling
    function extractYouTubeId(url) {
        if (!url) return null;
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        var match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    var modalEl = document.getElementById('videoPlayModal');
    var modalTitleSpan = document.querySelector('#videoModalTitle span');
    var iframeContainer = document.getElementById('videoIframeContainer');
    var bsModal = null;
    if (modalEl && typeof bootstrap !== 'undefined') {
        bsModal = new bootstrap.Modal(modalEl);
    }

    document.querySelectorAll('.trigger-video-modal').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var url = this.getAttribute('data-video-url');
            var title = this.getAttribute('data-video-title') || '';
            var videoId = extractYouTubeId(url);

            if (videoId && iframeContainer) {
                iframeContainer.innerHTML = '<iframe src="https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0&modestbranding=1" title="YouTube video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="border:0;"></iframe>';
                if (modalTitleSpan) {
                    modalTitleSpan.textContent = title;
                }
                if (bsModal) {
                    bsModal.show();
                }
            } else if (url) {
                window.open(url, '_blank');
            }
        });
    });

    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function() {
            if (iframeContainer) {
                iframeContainer.innerHTML = ''; // Stops audio/video completely
            }
        });
    }
});
</script>
