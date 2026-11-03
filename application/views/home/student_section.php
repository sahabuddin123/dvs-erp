<?php 
defined('BASEPATH') or exit('No direct script access allowed'); 
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$section_name = !empty($section['name']) ? $section['name'] : (($current_lang == 'en') ? 'Section Students' : 'শাখার শিক্ষার্থী');
$page_title = $section_name;
?>

<!-- Page Header (DVS Match) -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="page-header-title"><?php echo html_escape($page_title); ?></h1>
                <p class="text-white opacity-80 mb-0 mt-2">
                    <?php echo ($current_lang == 'en') ? 'Enrolled students in this section' : 'এই শাখার সকল শ্রেণির শিক্ষার্থীবৃন্দ'; ?>
                </p>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-end">
                <div class="display-1 opacity-80 page-header-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Shape -->
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="#007F3D" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Section -->
<section class="section my-5 py-4 bg-light" style="min-height: 550px;">
    <div class="container">
        
        <!-- Back Button & Breadcrumb -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a href="<?php echo base_url('students'); ?>" class="btn btn-outline-secondary btn-sm px-3 shadow-sm" style="border-radius: 6px;">
                <i class="fas fa-arrow-left me-1"></i> <?php echo ($current_lang == 'en') ? 'All Sections' : 'সকল শাখা'; ?>
            </a>
            <span class="badge text-white px-3 py-2 shadow-sm" style="background-color: #5650de; font-size: 0.9rem; border-radius: 6px;">
                <i class="fas fa-layer-group me-1"></i> <?php echo html_escape($section_name); ?>
            </span>
        </div>

        <!-- Filter Card (Matches user reference design) -->
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 6px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #605DEC 0%, #4E46E5 100%); padding: 14px 20px; font-weight: 600; font-size: 1.05rem;">
                <?php echo ($current_lang == 'en') ? 'Student List Filter' : 'শিক্ষার্থীর তালিকা'; ?>
            </div>
            <div class="card-body p-4 bg-white">
                <form method="get" id="studentFilterForm" action="<?php echo base_url('students/section/' . $section['id']); ?>">
                    <input type="hidden" name="section_id" id="hidden_section_id" value="<?php echo $section['id']; ?>">
                    <div class="row g-3 align-items-end">
                        
                        <!-- Academic Year -->
                        <div class="col-md-5">
                            <label class="form-label fw-bold mb-1" style="color: #4338ca; font-size: 0.9rem;">
                                <?php echo ($current_lang == 'en') ? 'Academic Year *' : 'একাডেমিক বছর *'; ?>
                            </label>
                            <input type="text" class="form-control" name="academic_year" value="<?php echo html_escape($academic_year); ?>" readonly style="background-color: #f8fafc; border: 1px solid #cbd5e1; height: 42px; font-weight: 500; border-radius: 6px;">
                        </div>

                        <!-- Class Dropdown -->
                        <div class="col-md-5">
                            <label class="form-label fw-bold mb-1" style="color: #4338ca; font-size: 0.9rem;">
                                <?php echo ($current_lang == 'en') ? 'Class *' : 'শ্রেণি *'; ?>
                            </label>
                            <select name="class_id" id="classSelect" class="form-select" required style="border: 1px solid #cbd5e1; height: 42px; font-weight: 500; border-radius: 6px;">
                                <option value=""><?php echo ($current_lang == 'en') ? '-- Select Class --' : '-- শ্রেণি নির্বাচন করুন --'; ?></option>
                                <?php if (!empty($classes)) { 
                                    foreach ($classes as $c) { 
                                        $selected = ($selected_class_id == $c['id']) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo html_escape($c['name']); ?>
                                    </option>
                                <?php } } ?>
                            </select>
                        </div>

                        <!-- Submit / Search Button -->
                        <div class="col-md-2">
                            <button type="submit" id="searchBtn" class="btn text-white w-100 shadow-sm" style="background-color: #5650de; border-color: #5650de; height: 42px; font-weight: 600; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="fas fa-search me-2"></i> <?php echo ($current_lang == 'en') ? 'Search' : 'অনুসন্ধান'; ?>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <!-- Student List Result Container -->
        <div id="studentsResultContainer">
            <?php 
            if (!empty($selected_class_id)) {
                $this->load->view('home/student_list_table', array(
                    'students' => $students,
                    'current_lang' => $current_lang
                ));
            } else {
            ?>
                <!-- Empty State Prompt -->
                <div class="card shadow-sm border-0 text-center py-5" style="border-radius: 6px; border: 2px dashed #cbd5e1 !important; background-color: #ffffff;">
                    <div class="py-4">
                        <div class="mb-3">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: #eef2ff; color: #4f46e5; font-size: 28px;">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">
                            <?php echo ($current_lang == 'en') ? 'Select a Class to View Student List' : 'শিক্ষার্থী তালিকা দেখতে উপরে শ্রেণি নির্বাচন করুন'; ?>
                        </h5>
                        <p class="text-muted small mb-0 px-3" style="max-width: 480px; margin: 0 auto;">
                            <?php echo ($current_lang == 'en') 
                                ? 'Choose your desired class from the dropdown above and click the "Search" button to display enrolled students.' 
                                : 'উপরের ড্রপডাউন থেকে আপনার কাঙ্ক্ষিত শ্রেণি নির্বাচন করে "অনুসন্ধান" বাটনে ক্লিক করুন।'; ?>
                        </p>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</section>

<!-- AJAX Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('studentFilterForm');
    const classSelect = document.getElementById('classSelect');
    const searchBtn = document.getElementById('searchBtn');
    const resultContainer = document.getElementById('studentsResultContainer');
    const sectionId = document.getElementById('hidden_section_id').value;

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const classId = classSelect.value;
            if (!classId) {
                alert('<?php echo ($current_lang == "en") ? "Please select a class." : "অনুগ্রহ করে একটি শ্রেণি নির্বাচন করুন।"; ?>');
                return;
            }

            // Show loading spinner
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> <?php echo ($current_lang == "en") ? "Searching..." : "অনুসন্ধান হচ্ছে..."; ?>';
            
            resultContainer.innerHTML = `
                <div class="card shadow-sm border-0 text-center py-5" style="border-radius: 6px; background: #fff; border: 1px solid #e2e8f0;">
                    <div class="py-4">
                        <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem; color: #5650de !important;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted fw-semibold mb-0"><?php echo ($current_lang == "en") ? "Loading student data..." : "শিক্ষার্থীর তথ্য লোড হচ্ছে..."; ?></p>
                    </div>
                </div>
            `;

            // Make AJAX GET Request (safe against CSRF tokens)
            const ajaxUrl = '<?php echo base_url("home/get_section_students_ajax"); ?>?section_id=' + encodeURIComponent(sectionId) + '&class_id=' + encodeURIComponent(classId);

            fetch(ajaxUrl)
                .then(response => response.json())
                .then(data => {
                    searchBtn.disabled = false;
                    searchBtn.innerHTML = '<i class="fas fa-search me-2"></i> <?php echo ($current_lang == "en") ? "Search" : "অনুসন্ধান"; ?>';

                    if (data.status === 'success') {
                        resultContainer.innerHTML = data.html;
                        // Update URL query string without reloading page
                        if (window.history.pushState) {
                            const newUrl = window.location.pathname + '?class_id=' + encodeURIComponent(classId);
                            window.history.pushState({ path: newUrl }, '', newUrl);
                        }
                    } else {
                        resultContainer.innerHTML = `
                            <div class="alert alert-danger shadow-sm border-0" style="border-radius: 6px;">
                                <i class="fas fa-exclamation-triangle me-2"></i> ${data.message || 'Error loading data.'}
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    searchBtn.disabled = false;
                    searchBtn.innerHTML = '<i class="fas fa-search me-2"></i> <?php echo ($current_lang == "en") ? "Search" : "অনুসন্ধান"; ?>';
                    // Fallback to normal form submit on network error
                    form.submit();
                });
        });
    }
});
</script>
