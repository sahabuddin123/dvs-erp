<?php defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$default_name = !empty($global_config['institute_name']) ? $global_config['institute_name'] : (!empty($branch['school_name']) ? $branch['school_name'] : 'School');
$app_title = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'application_title', $default_name) : $default_name;
$default_address = !empty($global_config['address']) ? $global_config['address'] : (!empty($branch['address']) ? $branch['address'] : '');
$app_address = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'address', $default_address) : $default_address;
$app_tagline = function_exists('get_lang_field') ? get_lang_field($cms_setting, 'site_tagline') : '';
$v_stats = !empty($visitor_stats) ? $visitor_stats : (function_exists('get_frontend_visitor_stats') ? get_frontend_visitor_stats() : ['total' => 0, 'month' => 0, 'today' => 0]);
$branchID_active = !empty($branchID) ? $branchID : 1;
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay">
        <h1>
            <?php echo html_escape($app_title); ?>
        </h1>
        <?php if (!empty($app_address)): ?>
        <p class="subtitle" style="font-size: 1.25rem; font-weight: 500; margin-bottom: 12px;">
            <?php echo html_escape($app_address); ?>
        </p>
        <?php endif; ?>
        <?php if (!empty($app_tagline)): ?>
        <p class="subtitle" style="font-size: 1.15rem; font-style: italic; line-height: 1.6; margin-bottom: 25px;">
            <?php echo $app_tagline; ?>
        </p>
        <?php endif; ?>
        <div class="btn-group">
            <a href="<?php echo base_url('admission'); ?>" class="btn btn-primary-hero">
                <i class="fas fa-edit me-1"></i> <?php echo translate('apply_for_admission'); ?>
            </a>
            <a href="<?php echo base_url('about'); ?>" class="btn btn-outline-light-custom">
                <i class="fas fa-book-open me-1"></i> <?php echo translate('our_history'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Highlighted Action Buttons -->
<section class="highlighted-buttons">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="<?php echo base_url('notice'); ?>" class="btn-highlight btn-blue">
                    <i class="fas fa-bullhorn me-1"></i> <span><?php echo translate('latest_notice'); ?></span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?php echo base_url('exam_results'); ?>" class="btn-highlight btn-green">
                    <i class="fas fa-check-circle me-1"></i> <span><?php echo translate('exam_results'); ?></span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?php echo base_url('admission'); ?>" class="btn-highlight btn-orange">
                    <i class="fas fa-edit me-1"></i> <span><?php echo translate('online_application'); ?></span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?php echo base_url('career'); ?>" class="btn-highlight btn-red">
                    <i class="fas fa-briefcase me-1"></i> <span><?php echo translate('career_portal'); ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Slider and Notice Board Section -->
<section class="slider-notice-section py-4">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <!-- Left: Dynamic Admin Sliders -->
            <div class="col-lg-8">
                <div class="slider-container rounded overflow-hidden shadow-sm h-100" style="border: 1px solid #e2e8f0; height: 460px;">
                    <div class="owl-carousel owl-theme h-100" id="homeSlider">
                        <?php 
                        if (!empty($sliders)) {
                            foreach ($sliders as $slide) {
                                $elements = !empty($slide['elements']) ? json_decode($slide['elements'], true) : [];
                                $slide_img = !empty($elements['image']) ? $elements['image'] : '';
                                $img_url = '';
                                if (!empty($slide_img)) {
                                    if (file_exists(FCPATH . 'uploads/frontend/slider/' . $slide_img)) {
                                        $img_url = base_url('uploads/frontend/slider/' . $slide_img);
                                    } elseif (file_exists(FCPATH . 'uploads/frontend/home_page/' . $slide_img)) {
                                        $img_url = base_url('uploads/frontend/home_page/' . $slide_img);
                                    } elseif (file_exists(FCPATH . 'uploads/frontend/images/' . $slide_img)) {
                                        $img_url = base_url('uploads/frontend/images/' . $slide_img);
                                    }
                                }
                                $slide_title = html_entity_decode(get_lang_field($slide, 'title'), ENT_QUOTES, 'UTF-8');
                        ?>
                            <div class="item position-relative" style="background: #1e293b; height: 460px;">
                                <?php if (!empty($img_url)) { ?>
                                    <img src="<?php echo $img_url; ?>" alt="<?php echo strip_tags($slide_title); ?>" style="width: 100%; height: 460px; object-fit: cover;">
                                <?php } else { ?>
                                    <div class="d-flex align-items-center justify-content-center h-100 text-white text-center p-4" style="background: linear-gradient(135deg, var(--primary-color) 0%, #1e293b 100%);">
                                        <h2 class="fw-bold"><?php echo strip_tags($slide_title, '<span><strong><b>'); ?></h2>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($slide_title)) { ?>
                                <div class="slider-caption" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0, 0, 0, 0.65); color: #fff; padding: 12px 15px 28px 15px; text-align: center; font-weight: 600; font-size: 1.05rem;">
                                    <?php echo strip_tags($slide_title, '<span><strong><b><small><em>'); ?>
                                </div>
                                <?php } ?>
                            </div>
                        <?php 
                            }
                        } else { ?>
                            <div class="item position-relative" style="background: linear-gradient(135deg, var(--primary-color) 0%, #1e293b 100%); height: 460px;">
                                <div class="d-flex align-items-center justify-content-center h-100 text-white text-center p-4">
                                    <div>
                                        <h2 class="fw-bold mb-2"><?php echo html_escape($app_title); ?></h2>
                                        <p class="text-white-50"><?php echo html_escape($app_address); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Right: Notice Board -->
            <div class="col-lg-4">
                <div class="notice-board bg-white rounded shadow-sm d-flex flex-column justify-content-between h-100" style="padding: 24px 28px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); height: 460px;">
                    <div>
                        <div class="border-bottom pb-2 mb-2" style="border-bottom: 3px solid var(--primary-color) !important;">
                            <h4 class="mb-0" style="color: var(--primary-color); font-weight: 700; font-size: 1.25rem;">
                                <i class="fas fa-clipboard-list me-2"></i> <?php echo translate('notice_board'); ?>
                            </h4>
                        </div>
                        <div class="notice-list">
                            <?php 
                            $board_notices = $this->db->where('branch_id', $branchID_active)->where('show_web', 1)->order_by('id', 'desc')->limit(5)->get('front_cms_news_list')->result_array();
                            if (empty($board_notices)) {
                                $board_notices = $this->db->where('show_web', 1)->order_by('id', 'desc')->limit(5)->get('front_cms_news_list')->result_array();
                            }
                            if (!empty($board_notices)) {
                                foreach ($board_notices as $bn) { 
                                    $notice_date = !empty($bn['date']) ? $bn['date'] : $bn['created_at'];
                                    $date_str = date('M, d', strtotime($notice_date));
                                    $notice_item_url = !empty($bn['alias']) ? base_url('notice-details/' . $bn['alias']) : base_url('notice-details/' . $bn['id']);
                            ?>
                                <div class="notice-item d-flex align-items-center gap-3 py-2 border-bottom" style="border-bottom: 1px solid #e5e7eb;">
                                    <div class="notice-date" style="background-color: var(--primary-color); color: white; padding: 8px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; min-width: 65px; text-align: center;">
                                        <?php echo $date_str; ?>
                                    </div>
                                    <a href="<?php echo $notice_item_url; ?>" class="notice-title" style="color: #374151; font-weight: 600; font-size: 0.92rem; text-decoration: none; flex: 1; line-height: 1.4;">
                                        <?php echo html_escape(get_lang_field($bn, 'title')); ?>
                                    </a>
                                </div>
                            <?php }
                            } else { ?>
                                <p class="text-muted small py-3 mb-0"><?php echo ($current_lang == 'en') ? 'No notices published yet.' : 'বর্তমানে কোনো নোটিশ নেই।'; ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo base_url('notice'); ?>" class="btn w-100 text-white" style="background-color: var(--primary-color); font-weight: 600; border-radius: 4px; padding: 10px; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none;">
                            ➔ <?php echo translate('view_all_notices'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Messages (নেতৃত্বের বার্তা - Only if real founders are added by admin) -->
<?php
$db_leaders = [];
if ($this->db->table_exists('founder')) {
    $db_leaders = $this->db->where('branch_id', $branchID_active)->where('active', 1)->order_by('position', 'asc')->get('founder')->result_array();
}
if (!empty($db_leaders)) {
?>
<section class="leadership-section py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="section-title mb-4">
            <h2><i class="fas fa-comments"></i> <?php echo translate('messages_from_leadership'); ?></h2>
        </div>

        <div class="row g-4 justify-content-center">
            <?php 
                $count = count($db_leaders);
                foreach ($db_leaders as $index => $leader) { 
                    $img_file = !empty($leader['image']) ? $leader['image'] : '';
                    $img_src = base_url('uploads/app_image/defualt.png');
                    if (!empty($img_file) && file_exists(FCPATH . 'uploads/frontend/founder/' . $img_file)) {
                        $img_src = base_url('uploads/frontend/founder/' . $img_file);
                    }

                    $col_class = ($count % 2 != 0 && $index == ($count - 1)) ? "col-lg-6 col-md-8 mx-auto" : "col-lg-6 col-md-6";
                    
                    $leader_name = function_exists('get_lang_field') ? get_lang_field($leader, 'name') : (!empty($leader['name_bn']) ? $leader['name_bn'] : $leader['name_en']);
                    $leader_desig = function_exists('get_lang_field') ? get_lang_field($leader, 'designation') : (!empty($leader['designation_bn']) ? $leader['designation_bn'] : $leader['designation_en']);
                    if (empty($leader_desig)) {
                        $leader_desig = function_exists('get_lang_field') ? get_lang_field($leader, 'title') : (!empty($leader['title_bn']) ? $leader['title_bn'] : $leader['title_en']);
                    }
            ?>
                <div class="<?php echo $col_class; ?>">
                    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 8px; border-left: 4px solid var(--primary-color) !important;">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="flex-shrink-0">
                                <img src="<?php echo $img_src; ?>" alt="<?php echo html_escape($leader_name); ?>" 
                                    style="width: 100px; height: 110px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-1" style="color: #b91c1c; font-weight: 700; font-size: 1.15rem;">
                                    <?php echo html_escape($leader_name); ?>
                                </h4>
                                <div class="mb-3">
                                    <span class="badge" style="background-color: #ef4444; color: #fff; font-size: 0.78rem; font-weight: 500; border-radius: 4px; padding: 4px 8px;">
                                        <?php echo html_escape($leader_desig); ?>
                                    </span>
                                </div>
                                <div>
                                    <a href="<?php echo base_url('message-founder/' . $leader['id']); ?>" class="btn btn-sm text-white" style="background-color: #d97706; font-weight: 600; padding: 5px 16px; border-radius: 4px; font-size: 13px; text-decoration: none;">
                                        <?php echo translate('read_full_message'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                } 
            ?>
        </div>
    </div>
</section>
<?php } ?>

<!-- Our Teachers Section (Controlled by Home Page Settings > Teachers tab active flag) -->
<?php
$teachers_is_active = !empty($teachers) && isset($teachers['active']) && $teachers['active'] == 1;
if ($teachers_is_active && !empty($teachers_list)) {
    $teachers_title = !empty($teachers['title']) ? $teachers['title'] : (($current_lang == 'en') ? 'Our Teachers' : 'আমাদের শিক্ষকবৃন্দ');
?>
<section class="teachers-section py-5" id="teachers-section" style="background-color: #f8fafc;">
    <div class="container">
        <div class="teachers-section-title mb-4">
            <h2 style="color: #007F3D; font-weight: 700; font-size: 2.2rem; display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
                <i class="fas fa-comments" style="color: #007F3D; font-size: 2rem;"></i>
                <span><?php echo html_escape($teachers_title); ?></span>
            </h2>
            <div style="margin-left: 50px;">
                <div style="width: 50px; height: 3px; background-color: #007F3D; border-radius: 2px;"></div>
                <div style="width: 50px; height: 3px; background-color: #f59e0b; border-radius: 2px; margin-top: 3px;"></div>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <?php 
            foreach ($teachers_list as $t_item) {
                $t_name = ($current_lang == 'en') ? (!empty($t_item['name_en']) ? $t_item['name_en'] : $t_item['name']) : (!empty($t_item['name_bn']) ? $t_item['name_bn'] : $t_item['name']);
                $t_designation = ($current_lang == 'en') ? (!empty($t_item['designation_en']) ? $t_item['designation_en'] : $t_item['designation']) : (!empty($t_item['designation_bn']) ? $t_item['designation_bn'] : $t_item['designation']);
                $t_title = ($current_lang == 'en') ? (!empty($t_item['title_en']) ? $t_item['title_en'] : $t_item['title']) : (!empty($t_item['title_bn']) ? $t_item['title_bn'] : $t_item['title']);
                $t_subject = ($current_lang == 'en') ? (!empty($t_item['subject_en']) ? $t_item['subject_en'] : $t_item['subject']) : (!empty($t_item['subject_bn']) ? $t_item['subject_bn'] : $t_item['subject']);
                $t_message = ($current_lang == 'en') ? (!empty($t_item['message_en']) ? $t_item['message_en'] : (!empty($t_item['message']) ? $t_item['message'] : '')) : (!empty($t_item['message_bn']) ? $t_item['message_bn'] : (!empty($t_item['message']) ? $t_item['message'] : ''));

                $t_img = !empty($t_item['image']) ? $t_item['image'] : '';
                $t_img_url = base_url('uploads/app_image/default.png');
                if (!empty($t_item['is_staff'])) {
                    $t_img_url = !empty($t_img) ? get_image_url('staff', $t_img) : base_url('uploads/app_image/default.png');
                } else {
                    if (!empty($t_img) && file_exists(FCPATH . 'uploads/frontend/teacher/' . $t_img)) {
                        $t_img_url = base_url('uploads/frontend/teacher/' . $t_img);
                    }
                }
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="teacher-card text-center h-100 p-4" style="background: #ffffff; border: 1px solid #e9ecef; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); display: flex; flex-direction: column; align-items: center; justify-content: space-between; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                        <div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 130px; height: 130px; border-radius: 8px; overflow: hidden; background: #f1f5f9; margin-bottom: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                                <img src="<?php echo $t_img_url; ?>" alt="<?php echo html_escape($t_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <h4 style="color: #007F3D; font-weight: 700; font-size: 1.15rem; margin-bottom: 4px;"><?php echo html_escape($t_name); ?></h4>
                            <p style="color: #64748b; font-size: 0.92rem; margin-bottom: 8px; font-weight: 500;"><?php echo html_escape(!empty($t_designation) ? $t_designation : $t_title); ?></p>
                            <?php if (!empty($t_subject)): ?>
                                <span class="badge" style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-size: 0.8rem; font-weight: 600; padding: 4px 10px; margin-bottom: 12px;"><?php echo html_escape($t_subject); ?></span>
                            <?php endif; ?>
                        </div>

                        <div style="width: 100%; margin-top: 15px;">
                            <button type="button" class="btn btn-teacher-msg w-100 text-white" 
                                style="background-color: #007F3D; font-weight: 600; font-size: 0.9rem; padding: 8px 16px; border-radius: 4px; border: none; transition: background 0.2s;"
                                data-bs-toggle="modal" data-bs-target="#teacherModal"
                                data-name="<?php echo html_escape($t_name); ?>"
                                data-designation="<?php echo html_escape($t_designation); ?>"
                                data-title="<?php echo html_escape($t_title); ?>"
                                data-subject="<?php echo html_escape($t_subject); ?>"
                                data-phone="<?php echo html_escape(!empty($t_item['phone']) ? $t_item['phone'] : ''); ?>"
                                data-email="<?php echo html_escape(!empty($t_item['email']) ? $t_item['email'] : ''); ?>"
                                data-image="<?php echo $t_img_url; ?>"
                                data-message="<?php echo html_escape($t_message); ?>">
                                <?php echo ($current_lang == 'en') ? 'Read Full Message' : 'Read Full Message'; ?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Teacher Modal -->
<div class="modal fade" id="teacherModal" tabindex="-1" aria-labelledby="teacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 10px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header text-white" style="background-color: #007F3D; padding: 16px 24px;">
                <h5 class="modal-title fw-bold" id="teacherModalLabel" style="font-size: 1.15rem; color: #fff;">
                    <i class="fas fa-chalkboard-teacher me-2"></i> <span id="modalTeacherName">Teacher Profile</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row align-items-center mb-3">
                    <div class="col-sm-4 text-center mb-3 mb-sm-0">
                        <img id="modalTeacherImg" src="" alt="Teacher Photo" class="img-fluid rounded shadow-sm" style="max-height: 180px; width: 160px; object-fit: cover; border: 2px solid #e2e8f0;">
                    </div>
                    <div class="col-sm-8">
                        <h4 id="modalTeacherFullName" class="fw-bold mb-1" style="color: #007F3D;"></h4>
                        <p id="modalTeacherDesig" class="text-muted fw-semibold mb-2" style="font-size: 1rem;"></p>
                        <p class="mb-2"><span class="badge" style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; font-size: 0.85rem;" id="modalTeacherSubject"></span></p>
                        <div id="modalTeacherContact" class="small text-muted mt-2">
                            <span id="modalTeacherPhone" class="me-3" style="display:none;"><i class="fas fa-phone-alt me-1 text-success"></i> <span></span></span>
                            <span id="modalTeacherEmail" style="display:none;"><i class="fas fa-envelope me-1 text-success"></i> <span></span></span>
                        </div>
                    </div>
                </div>
                <hr style="border-color: #e2e8f0;">
                <div class="teacher-bio-content mt-3">
                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-comment-dots text-success me-1"></i> <?php echo translate('message'); ?> / Bio:</h6>
                    <div id="modalTeacherMessage" class="text-muted lh-base" style="font-size: 0.98rem; max-height: 300px; overflow-y: auto; padding: 12px; background: #f8fafc; border-radius: 6px;"></div>
                </div>
            </div>
            <div class="modal-footer bg-light" style="padding: 10px 24px;">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal"><?php echo translate('close'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var teacherBtns = document.querySelectorAll('.btn-teacher-msg');
    teacherBtns.forEach(function(btn){
        btn.addEventListener('click', function(){
            var name = this.getAttribute('data-name') || '';
            var desig = this.getAttribute('data-designation') || '';
            var title = this.getAttribute('data-title') || '';
            var subj = this.getAttribute('data-subject') || '';
            var phone = this.getAttribute('data-phone') || '';
            var email = this.getAttribute('data-email') || '';
            var img = this.getAttribute('data-image') || '';
            var msg = this.getAttribute('data-message') || '';

            var displayDesig = desig ? desig : title;
            if (title && desig && title !== desig) {
                displayDesig = title + ' (' + desig + ')';
            }

            var modalTitle = document.getElementById('modalTeacherName');
            if (modalTitle) modalTitle.textContent = name;
            var modalFullName = document.getElementById('modalTeacherFullName');
            if (modalFullName) modalFullName.textContent = name;
            var modalDesig = document.getElementById('modalTeacherDesig');
            if (modalDesig) modalDesig.textContent = displayDesig;
            var modalImg = document.getElementById('modalTeacherImg');
            if (modalImg) modalImg.src = img;

            var subjEl = document.getElementById('modalTeacherSubject');
            if (subjEl) {
                if (subj) {
                    subjEl.textContent = 'Subject: ' + subj;
                    subjEl.style.display = 'inline-block';
                } else {
                    subjEl.style.display = 'none';
                }
            }

            var phoneEl = document.getElementById('modalTeacherPhone');
            if (phoneEl) {
                if (phone) {
                    phoneEl.querySelector('span').textContent = phone;
                    phoneEl.style.display = 'inline-block';
                } else {
                    phoneEl.style.display = 'none';
                }
            }

            var emailEl = document.getElementById('modalTeacherEmail');
            if (emailEl) {
                if (email) {
                    emailEl.querySelector('span').textContent = email;
                    emailEl.style.display = 'inline-block';
                } else {
                    emailEl.style.display = 'none';
                }
            }

            var msgContainer = document.getElementById('modalTeacherMessage');
            if (msgContainer) {
                if (msg && msg.trim() !== '') {
                    if (/<[a-z][\s\S]*>/i.test(msg)) {
                        msgContainer.innerHTML = msg;
                    } else {
                        msgContainer.innerHTML = msg.replace(/\n/g, '<br>');
                    }
                } else {
                    msgContainer.innerHTML = '<em>No message or biography has been added yet.</em>';
                }
            }
        });
    });
});
</script>
<?php } ?>

<!-- Welcome Section (From Admin Panel Home Page Settings > Welcome Message) -->
<?php if (!empty($wellcome) && (!isset($wellcome['active']) || $wellcome['active'] == 1)) { ?>
<section class="welcome-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <?php 
            $wellcome_elements = !empty($wellcome['elements']) ? json_decode($wellcome['elements'], true) : [];
            $wellcome_img = !empty($wellcome_elements['image']) ? $wellcome_elements['image'] : '';
            $wellcome_img_url = '';

            if (!empty($wellcome_img)) {
                if (file_exists(FCPATH . 'uploads/frontend/home_page/' . $wellcome_img)) {
                    $wellcome_img_url = base_url('uploads/frontend/home_page/' . $wellcome_img);
                } elseif (file_exists(FCPATH . 'uploads/frontend/images/' . $wellcome_img)) {
                    $wellcome_img_url = base_url('uploads/frontend/images/' . $wellcome_img);
                }
            }
            if (empty($wellcome_img_url)) {
                foreach (['jpg', 'png', 'jpeg', 'webp'] as $test_ext) {
                    if (file_exists(FCPATH . 'uploads/frontend/home_page/wellcome' . $branchID_active . '.' . $test_ext)) {
                        $wellcome_img_url = base_url('uploads/frontend/home_page/wellcome' . $branchID_active . '.' . $test_ext);
                        break;
                    }
                }
            }
            $col_width = !empty($wellcome_img_url) ? 'col-lg-6' : 'col-lg-12';
            ?>
            <div class="<?php echo $col_width; ?> mb-4">
                <div class="section-title">
                    <h2><i class="fas fa-hands-praying"></i> <?php echo html_escape(get_lang_field($wellcome, 'title', ($current_lang == 'en' ? 'Welcome' : 'স্বাগতম'))); ?></h2>
                    <?php 
                    $welcome_sub = get_lang_field($wellcome, 'subtitle');
                    if (!empty($welcome_sub)): ?>
                        <p class="text-muted fw-semibold" style="font-size: 1.1rem;"><?php echo html_escape($welcome_sub); ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-justify pr-4 lh-lg text-muted" style="font-size: 1.02rem;">
                    <?php 
                    $welcome_text = get_lang_field($wellcome, 'description');
                    echo nl2br(strip_tags($welcome_text));
                    ?>
                </div>
                <div class="mt-4">
                    <a href="<?php echo base_url('about'); ?>" class="btn"
                        style="background-color: var(--primary-color); color: white; border: none; padding: 10px 24px; font-weight: 600; border-radius: 6px;">
                        <i class="fas fa-arrow-right me-1"></i> <?php echo translate('learn_more'); ?>
                    </a>
                </div>
            </div>
            <?php if (!empty($wellcome_img_url)): ?>
            <div class="col-lg-6 mb-4 text-center">
                <img src="<?php echo $wellcome_img_url; ?>" alt="Welcome" class="img-fluid shadow-sm" style="border-radius: 8px; max-height: 480px; width: 100%; object-fit: cover;">
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php } ?>

<!-- Latest Events Section (Only if real events exist for this branch) -->
<?php if (!empty($events)) { ?>
<section class="news-section">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-newspaper"></i> <?php echo ($current_lang == 'en') ? 'Latest Events' : 'সর্বশেষ ইভেন্ট'; ?></h2>
            <p><?php echo translate('stay_informed_events'); ?></p>
        </div>

        <div class="news-slider row" id="newsCardsContainer">
            <?php 
            foreach ($events as $event) {
                $ev_title = get_lang_field($event, 'title');
                $ev_type = get_lang_field($event, 'type', ($current_lang == 'en' ? 'Event' : 'ইভেন্ট'));
                $ev_date = !empty($event['start_date']) ? date('F d, Y', strtotime($event['start_date'])) : '';
                $ev_img = !empty($event['image']) ? $event['image'] : '';
                $ev_img_url = base_url('uploads/app_image/default.png');
                if (!empty($ev_img) && file_exists(FCPATH . 'uploads/frontend/events/' . $ev_img)) {
                    $ev_img_url = base_url('uploads/frontend/events/' . $ev_img);
                }
            ?>
                <div class="col-md-6 col-lg-4 mb-4 news-item">
                    <div class="news-card h-100 shadow-sm border-0" style="border-radius: 8px; overflow: hidden; background: #fff;">
                        <img src="<?php echo $ev_img_url; ?>" alt="<?php echo html_escape($ev_title); ?>" style="height: 200px; width: 100%; object-fit: cover;">
                        <div class="news-card-body p-3">
                            <span class="news-category badge text-white mb-2" style="background-color: var(--primary-color);">
                                <i class="fas fa-calendar-check me-1"></i> <?php echo html_escape($ev_type); ?>
                            </span>
                            <h4 class="fw-bold mb-2 fs-5" style="color: #1e293b;"><?php echo html_escape($ev_title); ?></h4>
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i> <?php echo $ev_date; ?></small>
                            <br><br>
                            <a href="<?php echo base_url('events'); ?>" class="read-more text-decoration-none fw-semibold" style="color: var(--primary-color);">
                                <i class="fas fa-arrow-right"></i> <?php echo translate('event_details'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="text-center mt-4">
            <a href="<?php echo base_url('events'); ?>" class="btn"
                style="background-color: var(--primary-color); color: white; border: none; padding: 12px 40px; font-weight: 600; border-radius: 6px;">
                <i class="fas fa-arrow-right"></i> <?php echo translate('view_more_events'); ?>
            </a>
        </div>
    </div>
</section>
<?php } ?>

<!-- Video Gallery Section (Only if real videos exist for this branch) -->
<?php if (!empty($video_list)) { ?>
<section class="video-gallery-section py-5 bg-white" id="video-gallery-section">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-video"></i> <?php echo translate('video_gallery'); ?></h2>
            <p><?php echo translate('browse_video_gallery'); ?></p>
        </div>

        <div class="row g-4">
            <?php 
            foreach ($video_list as $vid) {
                $vid_title = get_lang_field($vid, 'title');
                $vid_elem = !empty($vid['elements']) ? json_decode($vid['elements'], true) : [];
                $vid_url = !empty($vid_elem['video_url']) ? $vid_elem['video_url'] : '';
                $vid_thumb = !empty($vid['thumb_image']) ? $vid['thumb_image'] : '';
                $thumb_url = base_url('uploads/app_image/default.png');
                if (!empty($vid_thumb) && file_exists(FCPATH . 'uploads/frontend/gallery/' . $vid_thumb)) {
                    $thumb_url = base_url('uploads/frontend/gallery/' . $vid_thumb);
                }
            ?>
                <div class="col-sm-6 col-md-4 col-lg-4">
                    <div class="card video-card shadow-sm h-100 border-0" style="border-radius: 8px; overflow: hidden;">
                        <div class="position-relative">
                            <img src="<?php echo $thumb_url; ?>" class="card-img-top" alt="<?php echo html_escape($vid_title); ?>" style="height: 220px; object-fit: cover;">
                            <?php if (!empty($vid_url)) { ?>
                                <button class="btn btn-play" data-youtube-url="<?php echo html_escape($vid_url); ?>"><i class="fas fa-play"></i></button>
                            <?php } ?>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="mb-0 fw-semibold" style="font-size: 0.95rem; color: #1e293b;"><?php echo html_escape($vid_title); ?></h6>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Video Modal (custom player popup using Plyr) -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" style="right:10px;top:10px;color:white;z-index:1051;background-color:rgba(255,255,255,0.8);"></button>
                <div id="playerContainer" class="p-0"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var playerInstance = null;
    var modalEl = document.getElementById('videoModal');
    var bsModal = null;
    if (modalEl && typeof bootstrap !== 'undefined') {
        bsModal = new bootstrap.Modal(modalEl);
    }

    function getYouTubeId(url){
        if(!url) return null;
        var m = url.match(/(?:v=|youtu\.be\/|embed\/)([A-Za-z0-9_-]+)/);
        return m && m[1] ? m[1] : null;
    }

    function buildSrc(id){
        var origin = window.location.origin;
        return 'https://www.youtube.com/embed/' + id + '?rel=0&modestbranding=1&iv_load_policy=3&enablejsapi=1&origin=' + encodeURIComponent(origin);
    }

    document.querySelectorAll('.btn-play').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            var url = btn.getAttribute('data-youtube-url');
            var id = getYouTubeId(url);
            if(!id) return;

            var container = document.getElementById('playerContainer');
            container.innerHTML = '<div class="plyr__video-embed" id="playerEmbed">'
                + '<iframe src="' + buildSrc(id) + '" allow="autoplay; encrypted-media; fullscreen" allowfullscreen></iframe>'
                + '</div>';

            try {
                if (typeof Plyr !== 'undefined') {
                    playerInstance = new Plyr('#playerEmbed', {
                        controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'fullscreen'],
                        autoplay: true
                    });
                }
            } catch(err) {
                console.error(err);
            }

            if (bsModal) bsModal.show();
        });
    });

    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function(){
            var container = document.getElementById('playerContainer');
            if (container) container.innerHTML = '';
            if (playerInstance) {
                try { playerInstance.destroy(); } catch(e){}
                playerInstance = null;
            }
        });
    }
});
</script>
<?php } ?>

<!-- Photo Gallery Section (Only if real photos exist for this branch) -->
<?php if (!empty($gallery_list)) { ?>
<section class="gallery-section py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-image"></i> <?php echo translate('photo_gallery'); ?></h2>
            <p><?php echo translate('see_moments_captured'); ?></p>
        </div>

        <div class="row g-4 gallery-container">
            <?php 
            foreach ($gallery_list as $photo) {
                $photo_title = get_lang_field($photo, 'title');
                $photo_desc = get_lang_field($photo, 'description');
                $img_name = !empty($photo['thumb_image']) ? $photo['thumb_image'] : '';
                $img_url = base_url('uploads/app_image/default.png');
                if (!empty($img_name) && file_exists(FCPATH . 'uploads/frontend/gallery/' . $img_name)) {
                    $img_url = base_url('uploads/frontend/gallery/' . $img_name);
                }
            ?>
                <div class="col-sm-6 col-md-4 col-lg-3 gallery-item">
                    <a href="<?php echo $img_url; ?>" class="gallery-popup d-block text-decoration-none" title="<?php echo html_escape($photo_title); ?>">
                        <div class="gallery-card shadow-sm border-0 position-relative overflow-hidden" style="border-radius: 8px; background: #fff; height: 220px;">
                            <img src="<?php echo $img_url; ?>" alt="<?php echo html_escape($photo_title); ?>" class="img-fluid w-100 h-100" style="object-fit: cover;">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <h5 class="mb-1 fw-bold fs-6"><?php echo html_escape($photo_title); ?></h5>
                                    <?php if (!empty($photo_desc)) { ?>
                                        <p class="mb-0 small text-white-50"><?php echo html_escape($photo_desc); ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?php echo base_url('gallery'); ?>" class="btn"
                style="background-color: var(--primary-color); color: white; border: none; padding: 12px 40px; font-weight: 600; border-radius: 6px;">
                <i class="fas fa-images"></i> <?php echo translate('view_more_gallery'); ?>
            </a>
        </div>
    </div>
</section>
<?php } ?>

<!-- Achievements and Statistics (Only if active in Home Page Settings > Statistics) -->
<?php if (!empty($statistics) && (!isset($statistics['active']) || $statistics['active'] == 1)) { ?>
<section class="achievements-section py-5 bg-white">
    <div class="container">
        <div class="section-title">
            <h2><i class="fas fa-star"></i> <?php echo html_escape(get_lang_field($statistics, 'title', translate('our_achievements_statistics'))); ?></h2>
        </div>
        
        <?php 
        $stat_students = $this->home_model->getStatisticsCounter('student', $branchID_active);
        $stat_teachers = $this->home_model->getStatisticsCounter('teacher', $branchID_active);
        $stat_classes  = $this->home_model->getStatisticsCounter('class', $branchID_active);
        $stat_sections = $this->home_model->getStatisticsCounter('section', $branchID_active);
        ?>
        <div class="row mb-5">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="stat-number"><?php echo $stat_students; ?></div>
                    <div class="stat-label"><?php echo translate('total_students'); ?></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="stat-card green">
                    <i class="fas fa-medal"></i>
                    <div class="stat-number"><?php echo $stat_classes; ?></div>
                    <div class="stat-label"><?php echo ($current_lang == 'en') ? 'Classes' : 'শ্রেণীকক্ষ'; ?></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="stat-card purple">
                    <i class="fas fa-trophy"></i>
                    <div class="stat-number"><?php echo $stat_sections; ?></div>
                    <div class="stat-label"><?php echo ($current_lang == 'en') ? 'Sections' : 'শাখা / সেকশন'; ?></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="stat-card orange">
                    <i class="fas fa-book"></i>
                    <div class="stat-number"><?php echo $stat_teachers; ?></div>
                    <div class="stat-label"><?php echo translate('teaching_staff'); ?></div>
                </div>
            </div>
        </div>

        <div class="visitor-stats">
            <div class="row align-items-center">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <h5 class="fw-bold"><i class="fas fa-eye"></i> <?php echo translate('visitor_statistics'); ?></h5>
                    <div class="mt-3">
                        <p class="text-muted mb-1" style="font-size: 0.9rem;"><?php echo translate('total_visitors'); ?></p>
                        <h3 style="color: var(--primary-color); font-weight: 700;"><?php echo $v_stats['total']; ?></h3>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted mb-1" style="font-size: 0.9rem;"><?php echo translate('this_month'); ?></p>
                        <h4 style="color: #10b981; font-weight: 700;"><?php echo $v_stats['month']; ?></h4>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted mb-1" style="font-size: 0.9rem;"><?php echo translate('today'); ?></p>
                        <h4 style="color: #f59e0b; font-weight: 700;"><?php echo $v_stats['today']; ?></h4>
                    </div>
                </div>
                <div class="col-md-8">
                    <h5 class="fw-bold"><i class="fas fa-chart-bar"></i> <?php echo translate('academic_performance'); ?></h5>
                    <div class="row mt-4">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div style="background-color: #dbeafe; border-radius: 8px; padding: 20px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.85rem; font-weight: 500;"><?php echo translate('total_students'); ?></small>
                                <h4 style="color: var(--primary-color); font-weight: 700; margin-bottom: 0;"><?php echo $stat_students; ?></h4>
                            </div>
                        </div>
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div style="background-color: #dcfce7; border-radius: 8px; padding: 20px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.85rem; font-weight: 500;"><?php echo translate('passed'); ?></small>
                                <h4 style="color: #10b981; font-weight: 700; margin-bottom: 0;">0</h4>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div style="background-color: #ede9fe; border-radius: 8px; padding: 20px;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.85rem; font-weight: 500;">GPA- 5</small>
                                <h4 style="color: #8b5cf6; font-weight: 700; margin-bottom: 0;">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>
