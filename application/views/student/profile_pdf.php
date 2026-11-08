<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="utf-8">
<title><?=html_escape($student['first_name'] . ' ' . $student['last_name'])?> - Profile</title>
<style>
    body {
        font-family: 'Hind Siliguri', 'SolaimanLipi', sans-serif;
        color: #1f2937;
        font-size: 11px;
        line-height: 1.4;
    }
    .header-table {
        width: 100%;
        border-bottom: 2px solid #007F3D;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }
    .school-title {
        font-size: 18px;
        font-weight: bold;
        color: #007F3D;
        text-transform: uppercase;
        margin: 0;
    }
    .school-sub {
        font-size: 10px;
        color: #4b5563;
        margin: 2px 0 0 0;
    }
    .doc-badge {
        background-color: #007F3D;
        color: #ffffff;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: bold;
        border-radius: 4px;
        display: inline-block;
    }
    .section-title {
        background-color: #ecfdf5;
        border-left: 4px solid #007F3D;
        padding: 5px 8px;
        font-weight: bold;
        font-size: 12px;
        color: #065f46;
        margin: 10px 0 6px 0;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }
    .data-table td {
        padding: 5px 6px;
        vertical-align: top;
        border-bottom: 1px solid #f3f4f6;
    }
    .data-table td.label-cell {
        width: 25%;
        color: #4b5563;
        font-weight: bold;
        background-color: #f9fafb;
    }
    .data-table td.value-cell {
        width: 25%;
        color: #111827;
    }
    .photo-box {
        width: 100px;
        height: 115px;
        border: 1px solid #d1d5db;
        padding: 3px;
        background: #fff;
        text-align: center;
    }
    .photo-box img {
        max-width: 94px;
        max-height: 109px;
    }
    .footer {
        margin-top: 25px;
        padding-top: 10px;
        border-top: 1px dashed #d1d5db;
        font-size: 9px;
        color: #6b7280;
        text-align: right;
    }
</style>
</head>
<body>

<table class="header-table">
    <tr>
        <td style="width: 15%; text-align: left; vertical-align: middle;">
            <?php
            $logo = $this->application_model->getBranchImage($student['branch_id'], 'printing-logo');
            if (!empty($logo)) {
                echo '<img src="' . $logo . '" style="max-height: 55px; max-width: 120px;" />';
            }
            ?>
        </td>
        <td style="width: 60%; text-align: center; vertical-align: middle;">
            <h1 class="school-title"><?=!empty($branch['school_name']) ? html_escape($branch['school_name']) : 'DREAM VALLEY SCHOOL'?></h1>
            <p class="school-sub"><?=!empty($branch['address']) ? html_escape($branch['address']) : ''?></p>
            <p class="school-sub">মোবাইল: <?=!empty($branch['mobileno']) ? html_escape($branch['mobileno']) : ''?> | ইমেইল: <?=!empty($branch['email']) ? html_escape($branch['email']) : ''?></p>
        </td>
        <td style="width: 25%; text-align: right; vertical-align: middle;">
            <div class="doc-badge">শিক্ষার্থীর তথ্য বিবরণী</div>
            <div style="font-size: 9px; color: #6b7280; margin-top: 4px;">মুদ্রণ তারিখ: <?=date('d-m-Y')?></div>
        </td>
    </tr>
</table>

<!-- Student Basic & Photo Grid -->
<table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
    <tr>
        <td style="vertical-align: top; width: 80%;">
            <div class="section-title">একাডেমিক তথ্য (Academic Information)</div>
            <table class="data-table">
                <tr>
                    <td class="label-cell">শিক্ষাবর্ষ (Session):</td>
                    <td class="value-cell"><?=!empty($student['session_name']) ? $student['session_name'] : get_type_name_by_id('schoolyear', get_session_id(), 'school_year')?></td>
                    <td class="label-cell">ভর্তি নং (Register No):</td>
                    <td class="value-cell" style="font-weight: bold; color: #007F3D;"><?=$student['register_no']?></td>
                </tr>
                <tr>
                    <td class="label-cell">শ্রেণি (Class):</td>
                    <td class="value-cell"><?=$student['class_name']?></td>
                    <td class="label-cell">শাখা (Section):</td>
                    <td class="value-cell"><?=$student['section_name']?></td>
                </tr>
                <tr>
                    <td class="label-cell">রোল নং (Roll No):</td>
                    <td class="value-cell" style="font-weight: bold;"><?=$student['roll']?></td>
                    <td class="label-cell">ভর্তির তারিখ (Admission Date):</td>
                    <td class="value-cell"><?=_d($student['admission_date'])?></td>
                </tr>
                <tr>
                    <td class="label-cell">ক্যাটাগরি (Category):</td>
                    <td class="value-cell"><?=get_type_name_by_id('student_category', $student['category_id'])?></td>
                    <td class="label-cell">বর্তমান অবস্থা (Status):</td>
                    <td class="value-cell"><?=$student['active'] == 1 ? '<span style="color:#059669; font-weight:bold;">সক্রিয় (Active)</span>' : '<span style="color:#dc2626; font-weight:bold;">নিষ্ক্রিয় (Inactive)</span>'?></td>
                </tr>
            </table>
        </td>
        <td style="vertical-align: top; width: 20%; text-align: right; padding-left: 10px;">
            <div class="photo-box" style="margin-left: auto;">
                <img src="<?=get_image_url('student', $student['photo'])?>" alt="Photo" />
            </div>
        </td>
    </tr>
</table>

<div class="section-title">ব্যক্তিগত তথ্য (Personal Information)</div>
<table class="data-table">
    <tr>
        <td class="label-cell">শিক্ষার্থীর নাম (বাংলা):</td>
        <td class="value-cell" style="font-weight: bold;"><?=$student['first_name'] . ' ' . $student['last_name']?></td>
        <td class="label-cell">ইংরেজি নাম (Full Name):</td>
        <td class="value-cell" style="font-weight: bold;"><?=!empty($student['full_name_en']) ? $student['full_name_en'] : (get_custom_field_value($student['id'], 1, 'student') ?? '')?></td>
    </tr>
    <tr>
        <td class="label-cell">জন্ম তারিখ (Date of Birth):</td>
        <td class="value-cell"><?=_d($student['birthday'])?></td>
        <td class="label-cell">বয়স (Age):</td>
        <td class="value-cell"><?=!empty($student['birthday']) ? get_calculated_age_bangla($student['birthday']) : ''?></td>
    </tr>
    <tr>
        <td class="label-cell">লিঙ্গ (Gender):</td>
        <td class="value-cell"><?=translate($student['gender'])?></td>
        <td class="label-cell">রক্তের গ্রুপ (Blood Group):</td>
        <td class="value-cell"><?=!empty($student['blood_group']) ? $student['blood_group'] : '-'?></td>
    </tr>
    <tr>
        <td class="label-cell">ধর্ম (Religion):</td>
        <td class="value-cell"><?=!empty($student['religion']) ? $student['religion'] : '-'?></td>
        <td class="label-cell">জন্ম নিবন্ধন নং (Birth Reg):</td>
        <td class="value-cell"><?=!empty($student['birth_certificate_no']) ? $student['birth_certificate_no'] : (get_custom_field_value($student['id'], 2, 'student') ?? '-')?></td>
    </tr>
    <tr>
        <td class="label-cell">শিক্ষার্থীর মোবাইল:</td>
        <td class="value-cell"><?=!empty($student['mobileno']) ? $student['mobileno'] : '-'?></td>
        <td class="label-cell">ইমেইল (Email):</td>
        <td class="value-cell"><?=!empty($student['email']) ? $student['email'] : '-'?></td>
    </tr>
    <tr>
        <td class="label-cell">বর্তমান ঠিকানা (Present Address):</td>
        <td class="value-cell" colspan="3"><?=!empty($student['current_address']) ? html_escape($student['current_address']) : '-'?></td>
    </tr>
    <tr>
        <td class="label-cell">স্থায়ী ঠিকানা (Permanent Address):</td>
        <td class="value-cell" colspan="3"><?=!empty($student['permanent_address']) ? html_escape($student['permanent_address']) : '-'?></td>
    </tr>
</table>

<div class="section-title">অভিভাবকের তথ্য (Parent & Guardian Information)</div>
<table class="data-table">
    <tr>
        <td class="label-cell">পিতার নাম (Father's Name):</td>
        <td class="value-cell"><?=!empty($student['father_name']) ? html_escape($student['father_name']) : '-'?></td>
        <td class="label-cell">পিতার মোবাইল:</td>
        <td class="value-cell"><?=!empty($student['parent_mobileno']) ? html_escape($student['parent_mobileno']) : '-'?></td>
    </tr>
    <tr>
        <td class="label-cell">মাতার নাম (Mother's Name):</td>
        <td class="value-cell"><?=!empty($student['mother_name']) ? html_escape($student['mother_name']) : '-'?></td>
        <td class="label-cell">পেশা (Occupation):</td>
        <td class="value-cell"><?=!empty($student['parent_occupation']) ? html_escape($student['parent_occupation']) : '-'?></td>
    </tr>
    <tr>
        <td class="label-cell">অভিভাবকের নাম (Guardian):</td>
        <td class="value-cell"><?=!empty($student['parent_name']) ? html_escape($student['parent_name']) : '-'?></td>
        <td class="label-cell">অভিভাবকের সম্পর্ক (Relation):</td>
        <td class="value-cell"><?=!empty($student['parent_relation']) ? html_escape($student['parent_relation']) : '-'?></td>
    </tr>
</table>

<?php
$custom_fields = custom_form_table('student', $student['branch_id'], true);
if (!empty($custom_fields)):
?>
<div class="section-title">অন্যান্য তথ্য (Additional Information)</div>
<table class="data-table">
    <tr>
    <?php 
    $c = 0;
    foreach ($custom_fields as $cf): 
        $val = get_custom_field_value($student['id'], $cf['id'], 'student');
        if ($val === null || $val === '') continue;
        if ($c > 0 && $c % 2 == 0) echo '</tr><tr>';
    ?>
        <td class="label-cell"><?=html_escape($cf['field_label'])?>:</td>
        <td class="value-cell"><?=html_escape($val)?></td>
    <?php 
        $c++;
    endforeach; 
    if ($c % 2 != 0) {
        echo '<td class="label-cell"></td><td class="value-cell"></td>';
    }
    ?>
    </tr>
</table>
<?php endif; ?>

<div class="footer">
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; text-align: left; font-size: 9px; color: #9ca3af;">
                ডকুমেন্ট জেনারেট হয়েছে: <?=date('d/m/Y h:i A')?> | ড্রিম ভ্যালি স্কুল ইআরপি
            </td>
            <td style="width: 50%; text-align: right; padding-top: 30px;">
                <span style="border-top: 1px solid #374151; padding-top: 4px; font-weight: bold; color: #374151;">অধ্যক্ষ / অনুমোদিত স্বাক্ষরকারী</span>
            </td>
        </tr>
    </table>
</div>

</body>
</html>

