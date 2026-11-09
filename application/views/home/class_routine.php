<?php
$current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';
$is_en = ($current_lang === 'en');

$days_en = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
$days_label = $is_en
    ? ['sunday'=>'Sunday','monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday','thursday'=>'Thursday','friday'=>'Friday','saturday'=>'Saturday']
    : ['sunday'=>'রবিবার','monday'=>'সোমবার','tuesday'=>'মঙ্গলবার','wednesday'=>'বুধবার','thursday'=>'বৃহস্পতিবার','friday'=>'শুক্রবার','saturday'=>'শনিবার'];
?>

<style>
.cr-page { color: #1e293b; }
.cr-filter-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.04);
    overflow: hidden;
    margin-bottom: 28px;
}
.cr-filter-header {
    padding: 14px 22px;
    border-bottom: 1px solid #f1f5f9;
    border-left: 5px solid var(--primary-color, #c0392b);
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
}
.cr-filter-header h5 { margin:0; font-weight:700; font-size:1.05rem; color:var(--primary-color,#c0392b); }
.cr-filter-body { padding: 22px 22px 18px; }
.cr-filter-body .form-group label { font-weight:600; font-size:.88rem; color:#374151; margin-bottom:6px; }
.cr-filter-body select.form-control { border-radius:8px; border:1px solid #d1d5db; height:44px; font-size:.92rem; }
.cr-filter-body .btn-search {
    background: var(--primary-color, #c0392b);
    color: #fff;
    border-radius: 8px;
    height: 44px;
    padding: 0 24px;
    font-weight: 600;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size:.92rem;
}
.cr-filter-body .btn-search:hover { opacity:.88; }

/* Result card */
.cr-result-card {
    background:#fff;
    border-radius:16px;
    border:1px solid #e2e8f0;
    box-shadow:0 6px 24px rgba(0,0,0,.05);
    overflow:hidden;
}
.cr-result-header {
    background:#f8fafc;
    padding:16px 22px;
    border-bottom:1px solid #e2e8f0;
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:10px;
}
.cr-result-header h5 { margin:0; font-weight:700; color:#0f172a; font-size:1rem; }
.cr-result-body { padding:0; }

/* Timetable table */
.cr-table { width:100%; border-collapse:collapse; }
.cr-table th {
    background: var(--primary-color, #c0392b);
    color:#fff;
    padding:10px 14px;
    font-size:.82rem;
    font-weight:600;
    text-align:center;
    white-space:nowrap;
}
.cr-table td {
    padding:10px 14px;
    border-bottom:1px solid #f1f5f9;
    font-size:.88rem;
    text-align:center;
    vertical-align:middle;
    color:#1e293b;
}
.cr-table tr:last-child td { border-bottom:none; }
.cr-table tbody tr:hover { background:#f8fafc; }
.day-label {
    font-weight:700;
    font-size:.85rem;
    color: var(--primary-color,#c0392b);
    min-width:90px;
    text-align:left;
}
.subject-name { font-weight:700; font-size:.9rem; display:block; }
.teacher-name { font-size:.78rem; color:#64748b; display:block; margin-top:2px; }
.time-badge {
    display:inline-block;
    background:#f1f5f9;
    color:#374151;
    border-radius:20px;
    padding:2px 10px;
    font-size:.75rem;
    font-weight:600;
    white-space:nowrap;
}
.break-badge {
    display:inline-block;
    background:#fef3c7;
    color:#92400e;
    border-radius:20px;
    padding:2px 10px;
    font-size:.78rem;
    font-weight:600;
}

/* Guide / empty */
.cr-guide {
    background:#f8fafc;
    border:1px dashed #cbd5e1;
    border-radius:14px;
    padding:48px 24px;
    text-align:center;
    color:#64748b;
}
.cr-guide-icon {
    width:66px; height:66px;
    border-radius:50%;
    background:#e2e8f0;
    color:#94a3b8;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:1.7rem;
    margin-bottom:16px;
}
.cr-empty-icon {
    width:68px; height:68px;
    border-radius:50%;
    background:#fef2f2;
    color:#ef4444;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:1.8rem;
    margin-bottom:16px;
}

@media print {
    .top-bar,.school-header,.navbar,.notice-bar,footer,.page-header,.page-header-wave,.cr-filter-card,.btn-print-cr { display:none !important; }
    .cr-result-card { border:none !important; box-shadow:none !important; }
}
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-header-title fw-bold mb-1">
                    <?php echo $is_en ? 'Class Routine' : 'ক্লাস রুটিন'; ?>
                </h1>
                <p class="text-muted mb-0 d-none d-md-block" style="font-size:.92rem;">
                    <?php echo $is_en
                        ? 'Filter by session, class and section to view the class timetable'
                        : 'সেশন, শ্রেণি ও সেকশন অনুযায়ী ক্লাস রুটিন দেখুন'; ?>
                </p>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="display-1 opacity-80" style="font-size:5rem; color:#273142;">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
        </div>
    </div>
    <svg class="page-header-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
        <path fill="var(--primary-color)" fill-opacity="1" d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,21.3C840,11,960,21,1080,26.7C1200,32,1320,32,1380,32L1440,32L1440,70L1380,70C1320,70,1200,70,1080,70C960,70,840,70,720,70C600,70,480,70,360,70C240,70,120,70,60,70L0,70Z"></path>
    </svg>
</div>

<!-- Main Container -->
<div class="container cr-page my-5 py-2">

    <!-- Filter Card -->
    <div class="cr-filter-card">
        <div class="cr-filter-header">
            <i class="fas fa-filter"></i>
            <h5><?php echo $is_en ? 'Filter Class Routine' : 'ক্লাস রুটিন অনুসন্ধান'; ?></h5>
        </div>
        <div class="cr-filter-body">
            <form method="GET" action="">
                <div class="row align-items-end" style="gap-y:12px;">

                    <!-- Class -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="form-group mb-0">
                            <label><i class="fas fa-graduation-cap me-1"></i> <?php echo $is_en ? 'Class' : 'শ্রেণি'; ?> <span style="color:red;">*</span></label>
                            <select name="class_id" id="cr_class_id" class="form-control" required onchange="crLoadSections(this.value)">
                                <option value=""><?php echo $is_en ? '— Select Class —' : '— শ্রেণি নির্বাচন করুন —'; ?></option>
                                <?php if (!empty($classes)): foreach ($classes as $cls): ?>
                                    <option value="<?php echo $cls['id']; ?>"
                                        <?php echo ($selected_class == $cls['id']) ? 'selected' : ''; ?>>
                                        <?php
                                            $cname = html_escape($cls['name']);
                                            if (function_exists('dvs_clean_bilingual')) {
                                                $cname = dvs_clean_bilingual($cls['name'], $is_en ? 'english' : 'bengali');
                                            }
                                            echo $cname;
                                        ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Section (dynamic) -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="form-group mb-0">
                            <label><i class="fas fa-layer-group me-1"></i> <?php echo $is_en ? 'Section' : 'সেকশন'; ?></label>
                            <select name="section_id" id="cr_section_id" class="form-control">
                                <option value=""><?php echo $is_en ? '— All Sections —' : '— সকল সেকশন —'; ?></option>
                                <?php
                                // Pre-fill sections if class is already selected
                                if (!empty($selected_class)):
                                    $pre_sections = $this->db->select('sections_allocation.section_id, section.name')
                                        ->from('sections_allocation')
                                        ->join('section','section.id = sections_allocation.section_id','left')
                                        ->where('sections_allocation.class_id', $selected_class)
                                        ->get()->result_array();
                                    foreach ($pre_sections as $sec):
                                        $sname = html_escape($sec['name']);
                                        if (function_exists('dvs_clean_bilingual')) {
                                            $sname = dvs_clean_bilingual($sec['name'], $is_en ? 'english' : 'bengali');
                                        }
                                ?>
                                    <option value="<?php echo $sec['section_id']; ?>"
                                        <?php echo ($selected_section == $sec['section_id']) ? 'selected' : ''; ?>>
                                        <?php echo $sname; ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <button type="submit" class="btn-search w-100">
                            <i class="fas fa-search"></i>
                            <?php echo $is_en ? 'View Routine' : 'রুটিন দেখুন'; ?>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Results -->
    <?php if ($is_searched): ?>

        <?php if (!empty($selected_class)): ?>

            <?php if (!empty($timetables)): ?>

                <?php
                    // Group timetables by day
                    $by_day = [];
                    foreach ($timetables as $row) {
                        $by_day[$row['day']][] = $row;
                    }
                    // Find max periods across all days (for consistent columns)
                    $max_periods = 0;
                    foreach ($by_day as $d => $rows) {
                        if (count($rows) > $max_periods) $max_periods = count($rows);
                    }
                ?>

                <div class="cr-result-card">
                    <div class="cr-result-header">
                        <h5><i class="fas fa-table me-2"></i><?php echo $is_en ? 'Class Routine' : 'ক্লাস রুটিন'; ?></h5>
                        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary btn-print-cr" style="border-radius:8px;">
                            <i class="fas fa-print me-1"></i> <?php echo $is_en ? 'Print' : 'প্রিন্ট'; ?>
                        </button>
                    </div>
                    <div class="cr-result-body">
                        <div class="table-responsive">
                            <table class="cr-table">
                                <thead>
                                    <tr>
                                        <th style="text-align:left;"><?php echo $is_en ? 'Day' : 'বার'; ?></th>
                                        <?php for ($p = 1; $p <= $max_periods; $p++): ?>
                                            <th><?php echo $is_en ? 'Period '.$p : $p.' য় পিরিয়ড'; ?></th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($days_en as $day): ?>
                                        <?php if (!isset($by_day[$day])) continue; ?>
                                        <tr>
                                            <td class="day-label"><?php echo $days_label[$day]; ?></td>
                                            <?php foreach ($by_day[$day] as $slot): ?>
                                                <td>
                                                    <?php if (!empty($slot['break'])): ?>
                                                        <span class="break-badge"><i class="fas fa-coffee"></i> <?php echo $is_en ? 'Break' : 'বিরতি'; ?></span>
                                                    <?php else: ?>
                                                        <span class="subject-name">
                                                            <?php
                                                                if (function_exists('get_type_name_by_id')) {
                                                                    echo html_escape(get_type_name_by_id('subject', $slot['subject_id']));
                                                                } else {
                                                                    echo html_escape($slot['subject_name'] ?? '—');
                                                                }
                                                            ?>
                                                        </span>
                                                        <?php if (!empty($slot['teacher_name'])): ?>
                                                            <span class="teacher-name"><i class="fas fa-user-tie" style="font-size:.7rem;"></i> <?php echo html_escape($slot['teacher_name']); ?></span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <span class="time-badge mt-1">
                                                        <?php echo date('g:i A', strtotime($slot['time_start'])) . ' – ' . date('g:i A', strtotime($slot['time_end'])); ?>
                                                    </span>
                                                    <?php if (!empty($slot['class_room'])): ?>
                                                        <span class="teacher-name"><i class="fas fa-door-open" style="font-size:.7rem;"></i> <?php echo html_escape($slot['class_room']); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; ?>
                                            <?php
                                                // Pad empty cells
                                                $filled = count($by_day[$day]);
                                                while ($filled < $max_periods) {
                                                    echo '<td><span style="color:#cbd5e1;">—</span></td>';
                                                    $filled++;
                                                }
                                            ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- No data found -->
                <div class="cr-guide text-center">
                    <div class="cr-empty-icon mx-auto">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="color:#0f172a;">
                        <?php echo $is_en ? 'No class routine found.' : 'ক্লাস রুটিন পাওয়া যায়নি।'; ?>
                    </h5>
                    <p class="mb-0 small text-muted" style="max-width:480px; margin:0 auto;">
                        <?php echo $is_en
                            ? 'No timetable has been created yet for the selected class and section. Please contact the administration.'
                            : 'নির্বাচিত শ্রেণি ও সেকশনের জন্য এখনো কোনো রুটিন তৈরি করা হয়নি। অনুগ্রহ করে প্রশাসনের সাথে যোগাযোগ করুন।'; ?>
                    </p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Class not selected -->
            <div class="alert alert-warning border-0 rounded-4 p-4 shadow-sm text-center" style="border-radius:14px!important;">
                <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block text-warning"></i>
                <h5 class="fw-bold mb-1"><?php echo $is_en ? 'Please select a Class' : 'অনুগ্রহ করে শ্রেণি নির্বাচন করুন'; ?></h5>
                <p class="mb-0 small text-muted"><?php echo $is_en ? 'Class is required to view the routine.' : 'রুটিন দেখতে শ্রেণি নির্বাচন করা আবশ্যক।'; ?></p>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Initial state guide -->
        <div class="cr-guide">
            <div class="cr-guide-icon mx-auto">
                <i class="fas fa-search"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">
                <?php echo $is_en ? 'Find Your Class Routine' : 'আপনার ক্লাস রুটিন খুঁজুন'; ?>
            </h5>
            <p class="mb-0 small" style="max-width:500px; margin:0 auto;">
                <?php echo $is_en
                    ? 'Select a session, class and optionally a section from the filters above, then click "View Routine" to display the timetable.'
                    : 'উপরের ফিল্টার থেকে সেশন, শ্রেণি এবং সেকশন নির্বাচন করুন, তারপর "রুটিন দেখুন" বাটনে ক্লিক করুন।'; ?>
            </p>
        </div>
    <?php endif; ?>

</div>

<script>
function crLoadSections(classId) {
    var $sec = document.getElementById('cr_section_id');
    $sec.innerHTML = '<option value=""><?php echo $is_en ? "— Loading... —" : "— লোড হচ্ছে... —"; ?></option>';
    if (!classId) {
        $sec.innerHTML = '<option value=""><?php echo $is_en ? "— All Sections —" : "— সকল সেকশন —"; ?></option>';
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo base_url("home/getSectionByClass"); ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            $sec.innerHTML = '<option value=""><?php echo $is_en ? "— All Sections —" : "— সকল সেকশন —"; ?></option>' + xhr.responseText;
        }
    };
    xhr.send('class_id=' + encodeURIComponent(classId));
}
</script>
