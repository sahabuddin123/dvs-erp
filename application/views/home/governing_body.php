<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 

$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Governing Body' : 'পরিচালক মণ্ডলী')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : 'পরিচালক মণ্ডলী');
$description = function_exists('get_lang_field') ? get_lang_field($page_data, 'content', '') : (!empty($page_data['content']) ? $page_data['content'] : '');
$inst_title = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'application_title', (!empty($global_config['institute_name']) ? $global_config['institute_name'] : 'School')) : (!empty($global_config['institute_name']) ? $global_config['institute_name'] : 'School');

if (empty($description) || strpos($description, '<table') !== false || strpos($description, 'কমিটি লিষ্ট') !== false) {
    $description = ($current_lang == 'en') ? 'Meet the dedicated directors and governing body who guide and govern our institution.' : 'পরিচালক মণ্ডলী পরিচিতি দল যারা আমাদের প্রতিষ্ঠানকে পরিচালনা করে।';
}

$date_display = !empty($page_data['created_at']) ? date('M d, Y', strtotime($page_data['created_at'])) : 'Jun 24, 2026';
?>

<!-- Page Header (Live Site Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
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
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Governing Body Content (Matching 2nd Screenshot) -->
<section class="section bg-light my-5 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm mb-4 border-0" style="border-radius: 8px; background: #fff;">
                    <!-- Card Header -->
                    <div class="card-header bg-white py-3" style="border-bottom: 1px solid #edf2f7;">
                        <div class="d-md-flex justify-content-between align-items-center">
                            <h3 class="mb-md-0 mb-2 fw-bold" style="color: #1e293b; font-size: 1.5rem;"><?php echo html_escape($page_title); ?></h3>
                            <div>
                                <span class="badge bg-primary p-2 me-2" style="font-size: 0.85rem; background-color: var(--primary-color) !important;">
                                    <i class="fas fa-newspaper me-1"></i>
                                </span>
                                <span class="text-muted small">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    <?php echo $date_display; ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4 p-md-5">
                        <div class="post-content mb-4" id="description">
                            <p style="color: #4a4e57; font-size: 1.05rem; line-height: 1.7;"><?php echo $description; ?></p>
                        </div>

                        <!-- 3-Column Members Grid (Founders List) -->
                        <div class="row g-4 mt-3">
                            <?php 
                            if (!empty($members)):
                                foreach ($members as $m):
                                    $m_name = !empty($m['name_bn']) ? $m['name_bn'] : (!empty($m['name']) ? $m['name'] : '');
                                    if ($current_lang == 'en' && !empty($m['name_en'])) {
                                        $m_name = $m['name_en'];
                                    }
                                    
                                    $m_desig = !empty($m['designation_bn']) ? $m['designation_bn'] : (!empty($m['designation']) ? $m['designation'] : (!empty($m['title_bn']) ? $m['title_bn'] : ''));
                                    if ($current_lang == 'en' && (!empty($m['designation_en']) || !empty($m['title_en']))) {
                                        $m_desig = !empty($m['designation_en']) ? $m['designation_en'] : $m['title_en'];
                                    }

                                    // Image path resolution
                                    $img_src = base_url('uploads/app_image/default.png');
                                    if (!empty($m['image']) && file_exists(FCPATH . 'uploads/frontend/founder/' . $m['image'])) {
                                        $img_src = base_url('uploads/frontend/founder/' . $m['image']);
                                    } elseif (!empty($m['image']) && file_exists(FCPATH . 'uploads/frontend/testimonial/' . $m['image'])) {
                                        $img_src = base_url('uploads/frontend/testimonial/' . $m['image']);
                                    }
                            ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0 member-card" style="border-radius: 8px; border: 1px solid #edf2f7 !important;">
                                        <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                                            <div>
                                                <div class="mb-3 overflow-hidden rounded" style="background-color: #f8fafc;">
                                                    <img src="<?php echo $img_src; ?>" 
                                                         onerror="this.onerror=null;this.src='<?php echo base_url('uploads/app_image/default.png'); ?>';" 
                                                         alt="<?php echo html_escape($m_name); ?>" 
                                                         class="img-fluid rounded" 
                                                         style="height: 220px; width: 100%; object-fit: cover;">
                                                </div>
                                                <h5 class="mb-1 fw-bold text-dark mt-3" style="font-size: 1.15rem;"><?php echo html_escape($m_name); ?></h5>
                                                <p class="text-muted small mb-1"><?php echo html_escape($m_desig); ?></p>
                                                <p class="small mb-3" style="color: var(--primary-color) !important; font-weight: 500;"><?php echo html_escape($m_desig); ?></p>
                                            </div>
                                            <div>
                                                <a href="<?php echo base_url('message-founder/' . $m['id']); ?>" class="btn btn-sm btn-outline-success mt-2 px-3 py-1" style="border-radius: 6px; font-weight: 500;">
                                                    <i class="far fa-comment-dots me-1"></i> Message
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            else:
                            ?>
                                <div class="col-12 text-center py-5">
                                    <p class="text-muted"><?php echo ($current_lang == 'en') ? 'No governing body members listed yet.' : 'বর্তমানে কোনো পরিচালকের তথ্য যুক্ত করা নেই।'; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Back Button -->
                        <div class="row mt-5">
                            <div class="col-md-6 d-flex align-items-center">
                                <a href="<?php echo base_url(); ?>" class="btn btn-outline-primary" style="border-color: var(--primary-color); color: var(--primary-color); border-radius: 6px; font-weight: 500; padding: 6px 18px;">
                                    <i class="fas fa-arrow-left me-2"></i> <?php echo ($current_lang == 'en') ? 'Back' : 'ফিরে যান'; ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer (Share) -->
                    <div class="card-footer bg-white p-4" style="border-top: 1px solid #edf2f7;">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-md-end justify-content-center align-items-center">
                                <span class="me-3 fw-medium text-muted">Share:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo current_url(); ?>" target="_blank" class="btn btn-outline-primary btn-sm me-2" title="Share on Facebook" style="border-radius: 4px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo current_url(); ?>&amp;text=<?php echo urlencode($page_title); ?>" target="_blank" class="btn btn-outline-info btn-sm me-2" title="Share on Twitter" style="border-radius: 4px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($page_title . ' ' . current_url()); ?>" target="_blank" class="btn btn-outline-success btn-sm" title="Share on WhatsApp" style="border-radius: 4px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.member-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.member-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
}
.member-card .btn-outline-success {
    border-color: #10b981;
    color: #059669;
}
.member-card .btn-outline-success:hover {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: #fff !important;
}
</style>
