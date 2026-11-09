<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$page_title = !empty($dept_name) ? $dept_name : (($current_lang == 'en') ? 'Student Department' : 'শিক্ষার্থী বিভাগ');
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-graduation-cap"></i>
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
<section class="section my-5 py-5 bg-light" style="min-height: 500px;">
    <div class="container">
        <div class="mb-4">
            <a href="<?php echo base_url('students'); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> <?php echo ($current_lang == 'en') ? 'Go Back' : 'ফিরে যান'; ?>
            </a>
        </div>

        <?php if (!empty($student_list)) { ?>
            <div class="row g-4">
                <?php foreach ($student_list as $student) { ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100" style="border-radius: 8px;">
                            <div class="card-body p-4 text-center">
                                <h5 class="fw-bold mb-1"><?php echo html_escape($student['first_name'] . ' ' . $student['last_name']); ?></h5>
                                <p class="text-muted small mb-0"><?php echo html_escape($student['register_no']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-info shadow-sm" style="background-color: #e0f2fe; border-color: #bae6fd; color: #0369a1; border-radius: 8px; padding: 1.5rem;">
                <h5 class="alert-heading fw-bold mb-1"><?php echo ($current_lang == 'en') ? 'No students found' : 'কোনও শিক্ষার্থী পাওয়া যায়নি'; ?></h5>
                <p class="mb-0"><?php echo ($current_lang == 'en') ? 'No records have been added to this department yet or no data was found.' : 'এই বিভাগে এখনও কোনও রেকর্ড যোগ করা হয়নি অথবা ডেটা পাওয়া যায়নি।'; ?></p>
            </div>
        <?php } ?>
    </div>
</section>

