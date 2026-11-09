<?php 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn'; 
$is_en = ($current_lang === 'en');

$default_page_title = $is_en ? 'Examination Admit Card' : 'পরীক্ষার প্রবেশপত্র';
$title = function_exists('get_lang_field') 
    ? get_lang_field($page_data, 'page_title', $default_page_title) 
    : (!empty($page_data['page_title']) ? $page_data['page_title'] : $default_page_title);

$default_desc = $is_en 
    ? 'Search, verify, and print your examination admit card online for Char Hasan Islamia Dakhil Madrasah.' 
    : 'চর হাসান ইসলামিয়া দাখিল মাদরাসার শিক্ষার্থীদের পরীক্ষার প্রবেশপত্র অনলাইন থেকে তাৎক্ষণিক অনুসন্ধান, যাচাই ও প্রিন্ট করুন।';

$description = function_exists('get_lang_field') 
    ? get_lang_field($page_data, 'description', $default_desc) 
    : (!empty($page_data['description']) && strpos($page_data['description'], 'Anim pariatur') === false ? $page_data['description'] : $default_desc);
?>
<script type="text/javascript" src="<?php echo base_url('assets/js/certificate.js?v=' . version_combine()) ?>"></script>

<style type="text/css">
/* Admit Card Modern Theme */
.admit-page {
    color: #1e293b;
}

.page-header-icon {
    font-size: 5rem;
    color: rgba(255, 255, 255, 0.85);
}

/* Search Card Container */
.admit-card-search {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    margin-bottom: 35px;
    max-width: 920px;
    margin-left: auto;
    margin-right: auto;
}

.admit-card-header {
    background: #ffffff;
    padding: 22px 28px;
    border-bottom: 1px solid #f1f5f9;
    border-left: 6px solid var(--primary-color, #007F3D);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.admit-card-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1.25rem;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
}

.admit-card-header h5 i {
    color: var(--primary-color, #007F3D);
}

.admit-form-body {
    padding: 30px 28px;
}

.admit-input-label {
    font-size: 0.95rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.admit-input-field {
    border-radius: 10px;
    padding: 12px 16px;
    border: 1px solid #cbd5e1;
    font-size: 0.95rem;
    background: #f8fafc;
    transition: all 0.25s ease;
}

.admit-input-field:focus {
    background: #ffffff;
    border-color: var(--primary-color, #007F3D);
    box-shadow: 0 0 0 3px rgba(0, 127, 61, 0.15);
    outline: none;
}

.admit-submit-btn {
    background-color: var(--primary-color, #007F3D);
    color: #ffffff !important;
    border: none;
    border-radius: 12px;
    padding: 13px 28px;
    font-size: 1rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 14px rgba(0, 127, 61, 0.25);
}

.admit-submit-btn:hover {
    background-color: #00602e;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 127, 61, 0.35);
}

/* Result Card Container */
#card_holder {
    max-width: 920px;
    margin-left: auto;
    margin-right: auto;
}

.admit-result-wrapper {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    padding: 25px;
    margin-bottom: 40px;
}

.admit-result-actions {
    background: #f8fafc;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    border: 1px solid #e2e8f0;
}

#card {
    background: #ffffff;
    border-radius: 10px;
    overflow-x: auto;
    padding: 10px 0;
}

/* Guide Cards */
.admit-guide-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 24px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    height: 100%;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.admit-guide-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
}

.admit-guide-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #e8f5e9;
    color: var(--primary-color, #007F3D);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    margin-bottom: 16px;
}
</style>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-header-title"><?php echo html_escape($title); ?></h1>
                <?php if (!empty($description)) { ?>
                    <p class="page-header-subtitle text-muted mb-0 mt-2" style="font-size: 1.05rem; line-height: 1.6;">
                        <?php echo strip_tags($description); ?>
                    </p>
                <?php } ?>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-id-card"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Section -->
<section class="section py-5 admit-page" style="background-color: #f8fafc;">
    <div class="container">
        
        <!-- Search Card -->
        <div class="admit-card-search">
            <div class="admit-card-header">
                <h5>
                    <i class="fas fa-search-plus"></i>
                    <?php echo $is_en ? 'Admit Card Search & Verification' : 'পরীক্ষার প্রবেশপত্র অনুসন্ধান'; ?>
                </h5>
                <span class="badge bg-light text-dark border px-3 py-2" style="font-size: 0.85rem; font-weight: 600;">
                    <i class="far fa-calendar-check text-success me-1"></i>
                    <?php echo $is_en ? 'Academic Year: ' . (date('Y')) : 'শিক্ষাবর্ষ: ' . (date('Y')); ?>
                </span>
            </div>

            <div class="admit-form-body">
                <?php echo form_open('home/admitCardprintFn', array('class' => 'printIn', 'id' => 'admitSearchForm')); ?>
                    <div class="row g-3">
                        <!-- Exam Selection -->
                        <div class="col-md-6 mb-3">
                            <label class="admit-input-label">
                                <i class="fas fa-clipboard-list text-primary"></i>
                                <?php echo $is_en ? 'Select Examination' : 'পরীক্ষা নির্বাচন করুন'; ?> <span class="text-danger">*</span>
                            </label>
                            <?php
                                $array = array();
                                $result = $this->home_model->getExamList($branchID);
                                if (!empty($result) && count($result)) {
                                    $array[''] = $is_en ? '-- Select Exam --' : '-- পরীক্ষা নির্বাচন করুন --';
                                    foreach ($result as $row) {
                                        if (!empty($row['term_id']) && $row['term_id'] != 0) {
                                            $termObj = $this->db->select('name')->where('id', $row['term_id'])->get('exam_term')->row();
                                            $termName = !empty($termObj) ? $termObj->name : '';
                                            $name = $row['name'] . (!empty($termName) ? ' (' . $termName . ')' : '');
                                        } else {
                                            $name = $row['name'];
                                        }
                                        $array[$row['id']] = $name;
                                    }
                                } else {
                                    $array[''] = $is_en ? 'No exams available' : 'বর্তমানে কোনো পরীক্ষা নির্ধারিত নেই';
                                }

                                echo form_dropdown("exam_id", $array, set_value('exam_id'), "class='form-select form-control admit-input-field' id='exam_id' required");
                            ?>
                            <span class="error text-danger small mt-1 d-block"></span>
                        </div>

                        <!-- Register No -->
                        <div class="col-md-6 mb-3">
                            <label class="admit-input-label">
                                <i class="fas fa-user-graduate text-primary"></i>
                                <?php echo $is_en ? 'Student Register No' : 'শিক্ষার্থীর রেজিস্টার নং'; ?> <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control admit-input-field" name="register_no" id="register_no" 
                                   value="<?=set_value('register_no')?>" 
                                   placeholder="<?php echo $is_en ? 'e.g. 10245 or Register No' : 'যেমন: 10245 বা রেজি নং'; ?>" 
                                   autocomplete="off" required />
                            <span class="error text-danger small mt-1 d-block"></span>
                        </div>

                        <!-- Hidden template ID -->
                        <input type="hidden" name="templete_id" value="<?php echo !empty($page_data['templete_id']) ? $page_data['templete_id'] : '4'; ?>">
                    </div>

                    <div class="mt-4 pt-2 border-top d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="text-muted small">
                            <i class="fas fa-info-circle text-info me-1"></i>
                            <?php echo $is_en ? 'Make sure you enter the correct student register number.' : 'সঠিক তথ্যের জন্য শিক্ষার্থীর রেজিস্টার নম্বরটি নির্ভুলভাবে লিখুন।'; ?>
                        </div>
                        <button type="submit" class="admit-submit-btn" id="btnSubmitAdmit">
                            <i class="fas fa-search"></i>
                            <span><?php echo $is_en ? 'Search Admit Card' : 'প্রবেশপত্র অনুসন্ধান করুন'; ?></span>
                        </button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        <!-- Result Holder Card -->
        <div id="card_holder" style="display: none;">
            <div class="admit-result-wrapper">
                <div class="admit-result-actions">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success p-2 rounded-circle text-white">
                            <i class="fas fa-check"></i>
                        </span>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">
                                <?php echo $is_en ? 'Admit Card Generated Successfully' : 'প্রবেশপত্র সফলভাবে তৈরি হয়েছে'; ?>
                            </h6>
                            <small class="text-muted">
                                <?php echo $is_en ? 'You can print or download the admit card below.' : 'নিচের বাটন থেকে প্রবেশপত্রটি সরাসরি প্রিন্ট অথবা PDF সংরক্ষণ করতে পারেন।'; ?>
                            </small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-secondary px-3 py-2" id="btnResetSearch" style="border-radius: 8px;">
                            <i class="fas fa-redo me-1"></i> <?php echo $is_en ? 'Search Again' : 'নতুন অনুসন্ধান'; ?>
                        </button>
                        <button type="button" class="btn btn-success px-4 py-2" id="print" style="border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-print me-2"></i> <?php echo $is_en ? 'Print Admit Card' : 'প্রবেশপত্র প্রিন্ট করুন'; ?>
                        </button>
                    </div>
                </div>

                <div id="card"></div>
            </div>
        </div>

        <!-- Helpful Guidelines Section -->
        <div class="mt-5 pt-3">
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1" style="color: var(--primary-color, #007F3D);">
                    <i class="fas fa-book-reader me-2"></i>
                    <?php echo $is_en ? 'Important Guidelines & Instructions' : 'প্রবেশপত্র ও পরীক্ষার প্রয়োজনীয় নির্দেশনাবলী'; ?>
                </h4>
                <p class="text-muted small">
                    <?php echo $is_en ? 'Please read the following instructions carefully before the exam day.' : 'পরীক্ষার পূর্বে অনুগ্রহ করে নিম্নে বর্ণিত নির্দেশনাগুলো মনোযোগ সহকারে পড়ুন।'; ?>
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="admit-guide-card">
                        <div class="admit-guide-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <h5 class="fw-bold mb-2">
                            <?php echo $is_en ? '1. Download & Print' : '১. সংগ্রহ ও প্রিন্ট'; ?>
                        </h5>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">
                            <?php echo $is_en 
                                ? 'Select your examination, enter the student register number, and click Search. Print the admit card clearly on standard A4 paper.' 
                                : 'পরীক্ষার নাম ও রেজিস্টার নম্বর দিয়ে সার্চ করুন এবং প্রবেশপত্রটি স্পষ্ট দেখতে A4 সাইজের অফসেট কাগজে প্রিন্ট করে নিন।'; ?>
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="admit-guide-card">
                        <div class="admit-guide-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h5 class="fw-bold mb-2">
                            <?php echo $is_en ? '2. Exam Hall Rules' : '২. পরীক্ষা হলের নিয়ম'; ?>
                        </h5>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">
                            <?php echo $is_en 
                                ? 'Bring the printed original admit card to the examination hall every day. Any overwriting or tampering makes the card invalid.' 
                                : 'পরীক্ষার দিন অবশ্যই প্রবেশপত্রের মূল কপি সঙ্গে আনতে হবে। প্রবেশপত্রে কোনো প্রকার ঘষামাজা বা কাটাকাটি গ্রহণযোগ্য নয়।'; ?>
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="admit-guide-card">
                        <div class="admit-guide-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h5 class="fw-bold mb-2">
                            <?php echo $is_en ? '3. Correction & Support' : '৩. তথ্য সংশোধন ও সহায়তা'; ?>
                        </h5>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">
                            <?php echo $is_en 
                                ? 'If any subject or student information appears incorrect, contact the madrasah office helpline immediately: +88018855-295441.' 
                                : 'প্রবেশপত্রে কোনো তথ্য বা বিষয়ে অসঙ্গতি পরিলক্ষিত হলে অবিলম্বে মাদরাসা অফিসে যোগাযোগ করুন: +৮৮০১৮৮৫৫-২৯৫৪৪১।'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script type="text/javascript">
$(document).ready(function () {
    $('form.printIn').on('submit', function(e){
        e.preventDefault();
        var btn = $('#btnSubmitAdmit');
        var originalBtnHtml = btn.html();
        var $this = $(this);
        $("#card_holder").slideUp(200);

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> <?php echo $is_en ? 'Searching...' : 'অনুসন্ধান করা হচ্ছে...'; ?>');

        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {
                $('.error').html("");
                if (data.status == "fail") {
                    $.each(data.error, function (index, value) {
                        $this.find("[name='" + index + "']").siblings('.error').html(value);
                    });
                } else if (data.status == 0) {
                    if (typeof swal === 'function') {
                        swal({
                            title: "<?php echo $is_en ? 'Not Found!' : 'তথ্য পাওয়া যায়নি!'; ?>",
                            text: data.error,
                            type: 'error',
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: "<?php echo $is_en ? 'OK' : 'ঠিক আছে'; ?>",
                            buttonsStyling: false
                        });
                    } else {
                        alert(data.error);
                    }
                } else {
                    $('#card').html(data.card_data);
                    $("#card_holder").slideDown(300, function() {
                        $('html, body').animate({
                            scrollTop: $("#card_holder").offset().top - 80
                        }, 500);
                    });
                }
            },
            error: function () {
                alert("<?php echo $is_en ? 'An error occurred, please try again.' : 'একটি ত্রুটি ঘটেছে, অনুগ্রহ করে আবার চেষ্টা করুন।'; ?>");
            },
            complete: function () {
                btn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });

    $('#print').on('click', function(e){
        certificate_printElem('card', false);
    });

    $('#btnResetSearch').on('click', function(e) {
        $("#card_holder").slideUp(250);
        $('#register_no').focus();
    });
});
</script>
