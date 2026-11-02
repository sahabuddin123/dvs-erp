<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = function_exists('get_lang_field') ? get_lang_field($page_data, 'page_title', ($current_lang == 'en' ? 'Notice Board' : 'নোটিশ')) : (!empty($page_data['page_title']) ? $page_data['page_title'] : ($current_lang == 'en' ? 'Notice Board' : 'নোটিশ'));
?>

<!-- Page Header (DVS Match) -->
<div class="page-header" style="background: var(--header-bg);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <?php if (!empty($page_data['title'])) { ?>
                    <p class="page-header-subtitle text-muted mb-0 mt-2"><?php echo html_escape($page_data['title']); ?></p>
                <?php } ?>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Notice Container -->
<section class="section py-5 bg-light" style="min-height: 550px;">
    <div class="container">
        <!-- Search and Filter -->
        <div class="card shadow-sm mb-5 border-0" style="border-radius: 12px; background: #fff;">
            <div class="card-body p-4">
                <h5 class="mb-3 fw-bold" style="color: #1e293b; font-size: 1.15rem;">
                    Search & Filter Notices
                </h5>
                <form action="<?php echo base_url('notice'); ?>" method="get">
                    <div class="row g-3">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0" id="notice-search" name="search"
                                    placeholder="Search notices.." 
                                    value="<?php echo html_escape($this->input->get('search') ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn w-100 text-white"
                                style="background-color: var(--dark-color); font-weight: 600;">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notices List -->
        <div class="notice-boards">
            <?php 
            $search_kw = $this->input->get('search');
            $filtered_results = $results;
            if (!empty($search_kw) && !empty($results)) {
                $filtered_results = array_filter($results, function($item) use ($search_kw) {
                    return (stripos($item['title'], $search_kw) !== false || stripos($item['description'], $search_kw) !== false);
                });
            }
            ?>
            <?php if (!empty($filtered_results)) { ?>
                <ul class="notice-list list-unstyled">
                    <?php foreach ($filtered_results as $row) { 
                        $notice_url = !empty($row['alias']) ? base_url('notice-details/' . $row['alias']) : base_url('notice-details/' . $row['id']);
                    ?>
                    <li class="notice-items mb-4">
                        <div class="d-md-flex justify-content-between align-items-center mb-2">
                            <h4 class="notice-title mb-1 fw-bold">
                                <a href="<?php echo $notice_url; ?>" class="text-decoration-none">
                                    <?php echo html_escape($row['title']); ?>
                                </a>
                            </h4>
                            <span class="notice-dates text-muted" style="font-size: 0.9rem;">
                                <i class="far fa-calendar-alt me-1 text-warning"></i> <?php echo date('M d, Y', strtotime($row['date'])); ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge py-2 px-3 text-dark" style="background-color: #fef08a; border-radius: 6px; font-weight: 500; font-size: 0.85rem;">
                                <i class="fas fa-info-circle me-1 text-muted"></i> <?php echo ($current_lang == 'en') ? 'Noticeboard' : 'নোটিসবার্ড'; ?>
                            </span>
                            <a href="<?php echo $notice_url; ?>" class="btn btn-sm btn-outline-success" style="border-radius: 6px; color: #007F3D; border-color: #007F3D; font-weight: 500; padding: 5px 16px;">
                                <?php echo ($current_lang == 'en') ? 'Read More' : 'বিস্তারিত পড়ুন'; ?>
                            </a>
                        </div>
                    </li>
                    <?php } ?>
                </ul>

                <?php if (isset($links) && !empty($links)) { ?>
                    <div class="pagination-wrapper mt-4 text-center">
                        <div class="pagination-bx">
                            <?php echo $links; ?>
                        </div>
                    </div>
                <?php } ?>

            <?php } else { ?>
                <div class="text-center py-5">
                    <div class="alert alert-info d-inline-block px-5 py-4 shadow-sm" style="background-color: #e0f2fe; border-color: #bae6fd; color: #0369a1; border-radius: 10px;">
                        <i class="fas fa-bullhorn fa-2x mb-2 d-block"></i>
                        <h5 class="mb-1 fw-bold"><?php echo ($current_lang == 'en') ? 'No Notices Found' : 'কোনো নোটিশ পাওয়া যায়নি'; ?></h5>
                        <p class="mb-0 small"><?php echo ($current_lang == 'en') ? 'Currently there are no notices published.' : 'বর্তমানে কোনো নোটিশ বা বিজ্ঞপ্তি প্রকাশিত হয়নি।'; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
