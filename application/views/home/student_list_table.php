<?php 
defined('BASEPATH') or exit('No direct script access allowed');
$current_lang = !empty($current_lang) ? $current_lang : (function_exists('get_current_lang') ? get_current_lang() : 'bn');
$count = !empty($students) ? count($students) : 0;
?>

<div class="card shadow-sm border-0 mb-4" style="border-radius: 6px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);">
    <div class="card-header text-white" style="background: linear-gradient(135deg, #605DEC 0%, #4E46E5 100%); padding: 14px 20px; font-weight: 600; font-size: 1.05rem;">
        <?php echo ($current_lang == 'en') ? 'Student List (Total: ' . $count . ')' : 'শিক্ষার্থীর তালিকা (মোট: ' . $count . ')'; ?>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($students)) { ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th style="width: 70px; text-align: center; font-weight: 600; color: #334155;">
                            <?php echo ($current_lang == 'en') ? 'Sl' : 'ক্রঃ'; ?>
                        </th>
                        <th style="width: 90px; text-align: center; font-weight: 600; color: #334155;">
                            <?php echo ($current_lang == 'en') ? 'Photo' : 'ছবি'; ?>
                        </th>
                        <th style="font-weight: 600; color: #334155;">
                            <?php echo ($current_lang == 'en') ? 'Student Name' : 'শিক্ষার্থীর নাম'; ?>
                        </th>
                        <th style="width: 100px; text-align: center; font-weight: 600; color: #334155;">
                            <?php echo ($current_lang == 'en') ? 'Roll' : 'রোল'; ?>
                        </th>
                        <th style="width: 180px; font-weight: 600; color: #334155;">
                            <?php echo ($current_lang == 'en') ? 'Class' : 'শ্রেণি'; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sl = 1;
                    foreach ($students as $s) { 
                        $photo_url = get_image_url('student', $s['photo']);
                        $roll = !empty($s['roll']) ? $s['roll'] : '-';
                    ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="text-center text-muted fw-semibold" style="width: 70px;">
                            <?php echo $sl++; ?>
                        </td>
                        <td class="text-center py-2" style="width: 90px;">
                            <img src="<?php echo $photo_url; ?>" 
                                 alt="<?php echo html_escape($s['fullname']); ?>" 
                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; background: #f8fafc;"
                                 onerror="this.src='<?php echo base_url('uploads/app_image/defualt.png'); ?>';">
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                <?php echo html_escape($s['fullname']); ?>
                            </div>
                            <?php if (!empty($s['register_no'])) { ?>
                            <small class="text-muted" style="font-size: 0.8rem;">
                                <?php echo ($current_lang == 'en') ? 'ID / Reg: ' : 'আইডি / রেজিস্ট্রেশন: '; ?><?php echo html_escape($s['register_no']); ?>
                            </small>
                            <?php } ?>
                        </td>
                        <td class="text-center" style="width: 100px;">
                            <span class="badge rounded-circle text-white shadow-sm" style="background-color: #5650de; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600;">
                                <?php echo html_escape($roll); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.85rem; font-weight: 500;">
                                <?php echo html_escape($s['class_name']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else { ?>
        <div class="p-5 text-center text-muted">
            <i class="fas fa-user-slash fa-3x mb-3 text-secondary opacity-50"></i>
            <h5 class="fw-semibold text-secondary">
                <?php echo ($current_lang == 'en') ? 'No students found' : 'কোনো শিক্ষার্থী পাওয়া যায়নি'; ?>
            </h5>
            <p class="mb-0 small">
                <?php echo ($current_lang == 'en') ? 'No students are currently enrolled in this class.' : 'এই শ্রেণি ও শাখায় এখনও কোনো শিক্ষার্থী ভর্তি হয়নি বা সক্রিয় নেই।'; ?>
            </p>
        </div>
        <?php } ?>
    </div>
</div>
