<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Governing Body' : 'পরিচালনা পর্ষদ')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : 'পরিচালনা পর্ষদ');
$description = function_exists('get_lang_field') ? get_lang_field($page_data, 'content', '') : (!empty($page_data['content']) ? $page_data['content'] : '');
$inst_title = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'application_title', (!empty($global_config['institute_name']) ? $global_config['institute_name'] : 'School')) : (!empty($global_config['institute_name']) ? $global_config['institute_name'] : 'School');
if (empty($description)) {
    $description = ($current_lang == 'en') ? 'Meet the dedicated governing body and council that guides and governs ' . $inst_title . '.' : $inst_title . '-এর নীতি নির্ধারণ, সার্বিক পরিচালনা ও মানসম্মত শিক্ষা নিশ্চিতকরণে আমাদের সম্মানিত পরিচালনা পর্ষদ ও অভিভাবক পরিষদ নিরলসভাবে দায়িত্ব পালন করে আসছেন।';
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
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Governing Body Content -->
<section class="section bg-light my-5 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm mb-4" style="border: none; border-radius: 8px;">
                    <!-- Card Header -->
                    <div class="card-header bg-white py-3" style="border-bottom: 1px solid #edf2f7;">
                        <div class="d-md-flex justify-content-between align-items-center">
                            <h3 class="mb-md-0 mb-2 fw-bold" style="color: #1e293b; font-size: 1.5rem;"><?php echo html_escape($page_title); ?></h3>
                            <div>
                                <span class="badge bg-primary p-2 me-2" style="font-size: 0.85rem;">
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

                        <!-- 3-Column Members Grid -->
                        <div class="row g-4 mt-4">
                            <?php 
                            if (!empty($members)) {
                                $url_map = [
                                    15 => 1,
                                    18 => 2,
                                    17 => 3,
                                    20 => 4,
                                    19 => 5,
                                    16 => 6,
                                    21 => 7,
                                ];
                                foreach ($members as $m) {
                                    $m_name = function_exists('get_lang_field') ? get_lang_field($m, 'name') : $m['name'];
                                    $m_surname = function_exists('get_lang_field') ? get_lang_field($m, 'surname') : $m['surname'];
                                    $m_desc = function_exists('get_lang_field') ? get_lang_field($m, 'description') : $m['description'];
                                    $founder_url_id = isset($url_map[$m['id']]) ? $url_map[$m['id']] : $m['id'];

                                    // Image path resolution
                                    $img_src = base_url('uploads/app_image/default.png');
                                    if (!empty($m['image']) && file_exists(FCPATH . 'uploads/frontend/testimonial/' . $m['image'])) {
                                        $img_src = base_url('uploads/frontend/testimonial/' . $m['image']);
                                    } elseif (!empty($m['image']) && file_exists(FCPATH . 'uploads/frontend/founder/' . $m['image'])) {
                                        $img_src = base_url('uploads/frontend/founder/' . $m['image']);
                                    }
                            ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-body text-center p-4">
                                            <div class="mb-3">
                                                <img src="<?php echo $img_src; ?>" onerror="this.onerror=null;this.src='<?php echo base_url('uploads/app_image/defualt.png'); ?>';" alt="<?php echo html_escape($m_name); ?>" class="img-fluid rounded" style="height: 200px; width: 100%; object-fit: cover;">
                                            </div>
                                            <h5 class="mb-1 fw-bold"><?php echo html_escape($m_name); ?></h5>
                                            <p class="text-muted small mb-1"><?php echo html_escape($m_surname); ?></p>
                                            <p class="small mb-3" style="color: var(--primary-color) !important; font-weight: 500;"><?php echo html_escape($m_surname); ?></p>
                                            <a href="<?php echo base_url('message-founder/' . $founder_url_id); ?>" class="btn btn-sm btn-outline-primary mt-3">
                                                <i class="fas fa-envelope-open-text me-1"></i> Message
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Message Modal for this leader -->
                                <div class="modal fade" id="founderModal<?php echo $m['id']; ?>" tabindex="-1" aria-labelledby="founderModalLabel<?php echo $m['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header" style="background-color: var(--primary-color); color: #ffffff;">
                                                <h5 class="modal-title fw-bold" id="founderModalLabel<?php echo $m['id']; ?>">
                                                    <i class="fas fa-user-tie me-2"></i> <?php echo html_escape($m_name); ?>
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4 text-center">
                                                <img src="<?php echo $img_src; ?>" alt="<?php echo html_escape($m_name); ?>" class="rounded mb-3 shadow-sm" style="width: 140px; height: 160px; object-fit: cover;">
                                                <h5 class="fw-bold mb-1" style="color: var(--primary-color);"><?php echo html_escape($m_name); ?></h5>
                                                <span class="badge rounded-pill text-white mb-3" style="background-color: var(--primary-color); padding: 5px 15px; font-weight: 500;">
                                                    <?php echo html_escape($m_surname); ?>
                                                </span>
                                                <div class="text-start p-3 rounded" style="background-color: #f8f9fa; border-left: 3px solid var(--primary-color); line-height: 1.8; color: #334155;">
                                                    <i class="fas fa-quote-left text-muted me-2"></i>
                                                    <?php echo !empty($m_desc) ? nl2br(html_escape($m_desc)) : html_escape($inst_title) . ' শিক্ষার্থীদের সুনাগরিক ও আদর্শ মানুষ হিসেবে গড়ে তুলতে অঙ্গীকারবদ্ধ।'; ?>
                                                    <i class="fas fa-quote-right text-muted ms-2"></i>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><?php echo translate('close'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                }
                            } 
                            ?>
                        </div>

                        <!-- Back Button -->
                        <div class="row mt-5">
                            <div class="col-md-6 d-flex align-items-center">
                                <button onclick="history.go(-1)" class="btn btn-outline-primary" style="border-color: var(--primary-color); color: var(--primary-color);">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </button>
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
.member-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}
.member-card .btn-outline-primary:hover {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: #fff !important;
}
</style>
