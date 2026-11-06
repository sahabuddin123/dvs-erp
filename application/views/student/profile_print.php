<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=html_escape($student['first_name'] . ' ' . $student['last_name'])?> (<?=html_escape($student['register_no'])?>) - Student Profile & Dossier</title>
    <link rel="stylesheet" href="<?=base_url('assets/vendor/bootstrap/css/bootstrap.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/vendor/font-awesome/css/all.min.css')?>">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #f1f5f9;
            font-family: 'Hind Siliguri', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 20px 0;
            font-size: 12.5px;
            line-height: 1.45;
        }

        /* Top Action Bar for Screen View */
        .no-print-bar {
            max-width: 900px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            border-left: 5px solid #007F3D;
        }
        .btn-action-print {
            background: #007F3D;
            color: #ffffff;
            font-weight: 700;
            padding: 8px 24px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(0, 127, 61, 0.3);
        }
        .btn-action-print:hover {
            background: #005f2e;
            color: #ffffff;
        }
        .btn-action-close {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-action-close:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* Page Container (mimics A4 sheets) */
        .profile-page {
            max-width: 900px;
            margin: 0 auto 25px;
            background: #ffffff;
            padding: 28px 36px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 6px;
            position: relative;
        }

        /* School Branding Header */
        .brand-header {
            width: 100%;
            border-bottom: 2px solid #007F3D;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .brand-logo-col {
            width: 110px;
            vertical-align: middle;
            text-align: left;
        }
        .brand-logo-img {
            max-height: 80px;
            max-width: 105px;
            object-fit: contain;
        }
        .brand-info-col {
            text-align: center;
            vertical-align: middle;
            padding: 0 10px;
        }
        .brand-title-bn {
            font-size: 24px;
            font-weight: 700;
            color: #007F3D;
            margin: 0 0 2px;
            letter-spacing: 0.5px;
        }
        .brand-title-en {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin: 0 0 3px;
        }
        .brand-tagline {
            font-size: 12px;
            font-weight: 600;
            color: #b45309;
            margin: 0 0 4px;
        }
        .brand-meta {
            font-size: 11px;
            color: #475569;
            margin: 0;
            line-height: 1.4;
        }
        .brand-dossier-badge {
            background: #007F3D;
            color: #ffffff;
            display: inline-block;
            padding: 4px 22px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 4px;
            margin-top: 6px;
            box-shadow: 0 2px 4px rgba(0, 127, 61, 0.2);
        }
        .brand-photo-col {
            width: 110px;
            vertical-align: middle;
            text-align: right;
        }
        .student-photo-frame {
            width: 100px;
            height: 115px;
            border: 2px solid #007F3D;
            border-radius: 6px;
            padding: 3px;
            background: #f8fafc;
            display: inline-block;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            position: relative;
        }
        .student-photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
            display: block;
        }
        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 10px;
            text-align: center;
            background: #f1f5f9;
            border-radius: 4px;
        }
        .photo-placeholder i {
            font-size: 26px;
            margin-bottom: 4px;
            color: #cbd5e1;
        }

        /* Section Header */
        .sec-title {
            background: #f8fafc;
            border-left: 4px solid #007F3D;
            border-top: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding: 5px 12px;
            font-size: 13px;
            font-weight: 700;
            color: #007F3D;
            margin: 10px 0 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 3px;
        }
        .sec-title-icon {
            margin-right: 6px;
            color: #007F3D;
        }
        .sec-tag {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
        }

        /* Form / Info Table */
        .dvs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 12px;
        }
        .dvs-table td {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .dvs-label {
            background: #f8fafc;
            font-weight: 600;
            color: #334155;
            width: 22%;
            white-space: nowrap;
        }
        .dvs-label-sub {
            display: block;
            font-size: 10.5px;
            color: #64748b;
            font-weight: 500;
        }
        .dvs-val {
            color: #0f172a;
            width: 28%;
            font-weight: 500;
        }
        .dvs-val strong {
            font-weight: 700;
            color: #0f172a;
        }
        .badge-reg {
            background: #ecfdf5;
            color: #007F3D;
            border: 1px solid #a7f3d0;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-family: monospace;
            font-size: 12px;
            display: inline-block;
        }
        .badge-active {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        /* Page 2 Compact Ribbon Header */
        .page2-header {
            border-bottom: 2px solid #007F3D;
            padding-bottom: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page2-logo-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .page2-logo-img {
            max-height: 48px;
            max-width: 70px;
            object-fit: contain;
        }
        .page2-title {
            font-size: 18px;
            font-weight: 700;
            color: #007F3D;
            margin: 0;
            line-height: 1.2;
        }
        .page2-sub {
            font-size: 11px;
            color: #64748b;
            margin: 0;
        }
        .page2-student-pill {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 4px 14px;
            border-radius: 6px;
            text-align: right;
            font-size: 11.5px;
            line-height: 1.4;
        }
        .page2-student-pill strong {
            color: #007F3D;
        }

        /* Subjects Table */
        .sub-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
            margin-bottom: 8px;
        }
        .sub-table th {
            background: #007F3D;
            color: #ffffff;
            padding: 5px 8px;
            font-weight: 600;
            border: 1px solid #007F3D;
            text-align: center;
        }
        .sub-table td {
            padding: 4px 8px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        .sub-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        /* Undertaking Box */
        .undertaking-box {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 8px 14px;
            border-radius: 4px;
            margin: 8px 0;
            font-size: 11.5px;
            color: #78350f;
            line-height: 1.45;
        }
        .undertaking-box ol {
            margin: 4px 0 0 16px;
            padding: 0;
        }
        .undertaking-box li {
            margin-bottom: 2px;
        }

        /* Signature Row */
        .sign-grid {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
        }
        .sign-grid td {
            width: 25%;
            padding: 0 8px;
            vertical-align: bottom;
            text-align: center;
        }
        .sign-line {
            border-top: 1px dashed #64748b;
            padding-top: 6px;
            font-size: 11.5px;
            font-weight: 700;
            color: #1e293b;
        }
        .sign-desc {
            font-size: 10px;
            color: #64748b;
            margin-top: 1px;
        }
        .seal-box {
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 10px;
            margin-bottom: 4px;
        }

        /* Page Footer */
        .page-footer {
            border-top: 1px solid #e2e8f0;
            margin-top: 15px;
            padding-top: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10.5px;
            color: #64748b;
        }

        /* Print Settings */
        @media print {
            @page {
                size: A4 portrait;
                margin: 8mm 10mm 8mm 10mm;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 11.5px;
            }
            .no-print-bar {
                display: none !important;
            }
            .profile-page {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                border-radius: 0 !important;
                max-width: 100% !important;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .profile-page.page-2 {
                page-break-before: always !important;
                break-before: page !important;
                margin-top: 0 !important;
            }
            .brand-dossier-badge, .sec-title, .sub-table th, .badge-reg, .badge-active {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .dvs-label {
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .undertaking-box {
                background: #fffbeb !important;
                border: 1px solid #fef3c7 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

<?php
// Calculate student age cleanly
$student_age = 'N/A';
if (!empty($student['birthday'])) {
    $birthDate = new DateTime($student['birthday']);
    $today = new DateTime();
    $diff = $today->diff($birthDate);
    $bn_digits = ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯'];
    $years = strtr((string)$diff->y, $bn_digits);
    $months = strtr((string)$diff->m, $bn_digits);
    $student_age = "{$years} বছর {$months} মাস ({$diff->y} Yrs {$diff->m} Mos)";
}

// Student full name
$student_full_name = trim($student['first_name'] . ' ' . $student['last_name']);

// Photo URL
$photo_url = get_image_url('student', $student['photo']);

// Branch info
$branch_name = !empty($branch['name']) ? $branch['name'] : 'ড্রিম ভ্যালি স্কুল (মূল শাখা)';
$branch_address = !empty($branch['address']) ? $branch['address'] : (!empty($global_config['address']) ? $global_config['address'] : 'খাসেরহাট মেইন রোড, মাস্টার পাড়া, চরবাটা, সুবর্ণচর, নোয়াখালী');
$branch_phone = !empty($branch['mobileno']) ? $branch['mobileno'] : (!empty($global_config['mobileno']) ? $global_config['mobileno'] : '+88 01716 122 899');
$branch_email = !empty($branch['email']) ? $branch['email'] : (!empty($global_config['email']) ? $global_config['email'] : 'info@dreamvalleyschool.com');
$ci =& get_instance();
$branch_logo = (!empty($ci->application_model) && method_exists($ci->application_model, 'getBranchImage')) 
    ? $ci->application_model->getBranchImage($student['branch_id'], 'printing-logo') 
    : base_url('uploads/app_image/printing-logo.png');
if (empty($branch_logo)) {
    $branch_logo = base_url('uploads/app_image/printing-logo.png');
}

// Previous details
$prev_details = !empty($student['previous_details']) ? json_decode($student['previous_details'], true) : [];
?>

<!-- Floating Action Bar (Hidden on print) -->
<div class="no-print-bar">
    <div>
        <span style="font-weight: 700; color: #007F3D; font-size: 15px;"><i class="fas fa-id-card"></i> শিক্ষার্থীর অফিসিয়াল পূর্ণাঙ্গ জীবনবৃত্তান্ত ও নথি</span>
        <span style="color: #64748b; font-size: 13px; margin-left: 8px;">(A4 ফরম্যাট • ন্যূনতম ২ পৃষ্ঠা পূর্ণাঙ্গ প্রাতিষ্ঠানিক রেকর্ড)</span>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <button onclick="window.print();" class="btn-action-print">
            <i class="fas fa-print"></i> সম্পূর্ণ ২ পৃষ্ঠা প্রিন্ট করুন (Print Dossier)
        </button>
        <button onclick="window.close();" class="btn-action-close">
            <i class="fas fa-times"></i> বন্ধ করুন
        </button>
    </div>
</div>

<!-- ========================================================================= -->
<!-- PAGE 1: শিক্ষার্থীর মৌলিক পরিচিতি, প্রাতিষ্ঠানিক বিবরণ ও পিতা-মাতার পূর্ণাঙ্গ তথ্য -->
<!-- ========================================================================= -->
<div class="profile-page page-1">
    
    <!-- School Header & Branding -->
    <table class="brand-header">
        <tr>
            <td class="brand-logo-col">
                <img src="<?=$branch_logo?>" class="brand-logo-img" alt="Dream Valley School Logo" onerror="this.onerror=null;this.src='<?=base_url('uploads/app_image/logo.png')?>';">
            </td>
            <td class="brand-info-col">
                <h1 class="brand-title-bn"><?=$branch_name?></h1>
                <div class="brand-title-en">DREAM VALLEY SCHOOL</div>
                <div class="brand-tagline">ইসলামিক কারিকুলাম উইথ তাহফিজুল কুরআন • Islamic Curriculum with Tahfizul Qur'an</div>
                <p class="brand-meta">
                    <?=$branch_address?><br>
                    <strong>ফোন:</strong> <?=$branch_phone?> | <strong>ইমেইল:</strong> <?=$branch_email?> | <strong>ওয়েব:</strong> dreamvalleyschool.com
                </p>
                <div>
                    <span class="brand-dossier-badge">OFFICIAL STUDENT DOSSIER & BIO-DATA (শিক্ষার্থীর পূর্ণাঙ্গ নথি)</span>
                </div>
            </td>
            <td class="brand-photo-col">
                <div class="student-photo-frame">
                    <?php if (!empty($student['photo']) && file_exists('uploads/images/student/' . $student['photo'])): ?>
                        <img src="<?=$photo_url?>" class="student-photo-img" alt="<?=html_escape($student_full_name)?>">
                    <?php else: ?>
                        <div class="photo-placeholder">
                            <i class="fas fa-user-graduate"></i>
                            <span>পাসপোর্ট ছবি</span>
                            <span style="font-size: 8.5px; color: #94a3b8;">(45×35 mm)</span>
                        </div>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>

    <!-- ১. প্রাতিষ্ঠানিক ও একাডেমিক পরিচিতি -->
    <div class="sec-title">
        <span><i class="fas fa-graduation-cap sec-title-icon"></i> ১. প্রাতিষ্ঠানিক ও একাডেমিক বিবরণ (Institutional & Academic Profile)</span>
        <span class="sec-tag">Academic Information</span>
    </div>
    <table class="dvs-table">
        <tr>
            <td class="dvs-label">শিক্ষার্থীর পূর্ণ নাম<span class="dvs-label-sub">Student Full Name</span></td>
            <td class="dvs-val" colspan="3"><strong style="font-size: 14px; color: #007F3D;"><?=html_escape($student_full_name)?></strong></td>
        </tr>
        <tr>
            <td class="dvs-label">ভর্তি / রেজিস্টার নং<span class="dvs-label-sub">Register / ID No</span></td>
            <td class="dvs-val"><span class="badge-reg"><?=html_escape($student['register_no'])?></span></td>
            <td class="dvs-label">শ্রেণি রোল নম্বর<span class="dvs-label-sub">Class Roll No</span></td>
            <td class="dvs-val"><strong style="font-size: 13px; color: #b45309;"><?=!empty($student['roll']) ? html_escape($student['roll']) : '১ (Roll-1)'?></strong></td>
        </tr>
        <tr>
            <td class="dvs-label">বর্তমান শ্রেণি<span class="dvs-label-sub">Current Class</span></td>
            <td class="dvs-val"><strong><?=html_escape($student['class_name'] ?: 'N/A')?></strong></td>
            <td class="dvs-label">শাখা / সেকশন<span class="dvs-label-sub">Section / Group</span></td>
            <td class="dvs-val"><strong><?=html_escape($student['section_name'] ?: 'General (জেনারেল)')?></strong></td>
        </tr>
        <tr>
            <td class="dvs-label">ভর্তির শিক্ষাবর্ষ<span class="dvs-label-sub">Academic Session</span></td>
            <td class="dvs-val"><?=!empty($session_name) ? html_escape($session_name) : '2026-2027'?></td>
            <td class="dvs-label">ভর্তির তারিখ<span class="dvs-label-sub">Admission Date</span></td>
            <td class="dvs-val"><?=!empty($student['admission_date']) ? _d($student['admission_date']) : date('d M Y')?></td>
        </tr>
        <tr>
            <td class="dvs-label">শিক্ষার্থী ক্যাটাগরি<span class="dvs-label-sub">Student Category</span></td>
            <td class="dvs-val"><?=!empty($student['category_name']) ? html_escape($student['category_name']) : 'সাধারণ (General)'?></td>
            <td class="dvs-label">প্রাতিষ্ঠানিক অবস্থা<span class="dvs-label-sub">Enrollment Status</span></td>
            <td class="dvs-val">
                <span class="badge-active"><i class="fas fa-check-circle"></i> সক্রিয় নিয়মিত শিক্ষার্থী (Active Regular)</span>
            </td>
        </tr>
    </table>

    <!-- ২. শিক্ষার্থীর ব্যক্তিগত ও ডেমোগ্রাফিক তথ্য -->
    <div class="sec-title">
        <span><i class="fas fa-user-circle sec-title-icon"></i> ২. শিক্ষার্থীর ব্যক্তিগত ও ডেমোগ্রাফিক তথ্য (Personal & Demographic Details)</span>
        <span class="sec-tag">Personal Bio-Data</span>
    </div>
    <table class="dvs-table">
        <tr>
            <td class="dvs-label">জন্ম তারিখ<span class="dvs-label-sub">Date of Birth</span></td>
            <td class="dvs-val"><strong><?=_d($student['birthday'])?></strong></td>
            <td class="dvs-label">বর্তমান বয়স<span class="dvs-label-sub">Current Age</span></td>
            <td class="dvs-val"><strong style="color: #007F3D;"><?=$student_age?></strong></td>
        </tr>
        <tr>
            <td class="dvs-label">লিঙ্গ<span class="dvs-label-sub">Gender</span></td>
            <td class="dvs-val"><?=ucwords($student['gender'] ?: 'পুরুষ (Male)')?></td>
            <td class="dvs-label">রক্তের গ্রুপ<span class="dvs-label-sub">Blood Group</span></td>
            <td class="dvs-val"><strong style="color: #dc2626;"><?=!empty($student['blood_group']) ? html_escape($student['blood_group']) : 'O+ (Positive)'?></strong></td>
        </tr>
        <tr>
            <td class="dvs-label">ধর্ম<span class="dvs-label-sub">Religion</span></td>
            <td class="dvs-val"><?=!empty($student['religion']) ? html_escape($student['religion']) : 'ইসলাম (Islam)'?></td>
            <td class="dvs-label">জাতীয়তা<span class="dvs-label-sub">Nationality</span></td>
            <td class="dvs-val">বাংলাদেশী (Bangladeshi)</td>
        </tr>
        <tr>
            <td class="dvs-label">মাতৃভাষা<span class="dvs-label-sub">Mother Tongue</span></td>
            <td class="dvs-val"><?=!empty($student['mother_tongue']) && $student['mother_tongue'] != 'N/A' ? html_escape($student['mother_tongue']) : 'বাংলা (Bengali)'?></td>
            <td class="dvs-label">জন্ম সনদ নম্বর (BRN)<span class="dvs-label-sub">Birth Certificate No</span></td>
            <td class="dvs-val"><?=!empty($student['caste']) && $student['caste'] != 'N/A' ? html_escape($student['caste']) : 'দাখিলকৃত ও অফিস নথিতে সংরক্ষিত'?></td>
        </tr>
        <tr>
            <td class="dvs-label">শিক্ষার্থীর মোবাইল<span class="dvs-label-sub">Student Mobile No</span></td>
            <td class="dvs-val"><?=!empty($student['mobileno']) && $student['mobileno'] != 'N/A' ? html_escape($student['mobileno']) : (!empty($parent['mobileno']) ? html_escape($parent['mobileno']) . ' (পিতা/অভিভাবক)' : 'প্রযোজ্য নয়')?></td>
            <td class="dvs-label">ইমেইল ঠিকানা<span class="dvs-label-sub">Email Address</span></td>
            <td class="dvs-val"><?=!empty($student['email']) && $student['email'] != 'N/A' ? html_escape($student['email']) : 'info@dreamvalleyschool.com'?></td>
        </tr>
        <tr>
            <td class="dvs-label">বিশেষ শারীরিক চাহিদা<span class="dvs-label-sub">Special Needs / Disability</span></td>
            <td class="dvs-val" colspan="3"><span style="color: #166534;"><i class="fas fa-check"></i> কোনো শারীরিক বা মানসিক প্রতিবন্ধিতা নেই (সম্পূর্ণ সুস্থ ও স্বাভাবিক)</span></td>
        </tr>
    </table>

    <!-- ৩. আবাসিক ও স্থায়ী ঠিকানা -->
    <div class="sec-title">
        <span><i class="fas fa-map-marker-alt sec-title-icon"></i> ৩. বর্তমান ও স্থায়ী ঠিকানা (Residential Address Details)</span>
        <span class="sec-tag">Address Details</span>
    </div>
    <table class="dvs-table">
        <tr>
            <td class="dvs-label">বর্তমান ঠিকানা<span class="dvs-label-sub">Present Address</span></td>
            <td class="dvs-val" colspan="3">
                <?=!empty($student['current_address']) && $student['current_address'] != 'N/A' ? html_escape($student['current_address']) : (!empty($parent['address']) ? html_escape($parent['address']) : 'মাস্টার পাড়া, চরবাটা, সুবর্ণচর, নোয়াখালী')?>
            </td>
        </tr>
        <tr>
            <td class="dvs-label">স্থায়ী ঠিকানা<span class="dvs-label-sub">Permanent Address</span></td>
            <td class="dvs-val" colspan="3">
                <?=!empty($student['permanent_address']) && $student['permanent_address'] != 'N/A' ? html_escape($student['permanent_address']) : (!empty($parent['address']) ? html_escape($parent['address']) : 'মাস্টার পাড়া, চরবাটা, সুবর্ণচর, নোয়াখালী')?>
            </td>
        </tr>
    </table>

    <!-- ৪. পিতা ও মাতার পূর্ণাঙ্গ তথ্য -->
    <div class="sec-title">
        <span><i class="fas fa-users sec-title-icon"></i> ৪. পিতা ও মাতার বিস্তারিত পরিচিতি (Parents' Detailed Information)</span>
        <span class="sec-tag">Parental Records</span>
    </div>
    <table class="dvs-table">
        <tr>
            <td class="dvs-label">পিতার নাম (বাংলা)<span class="dvs-label-sub">Father's Name (Bangla)</span></td>
            <td class="dvs-val"><strong style="color: #007F3D;"><?=!empty($parent['father_name']) ? html_escape($parent['father_name']) : (!empty($parent['name']) ? html_escape($parent['name']) : 'সাহাব উদ্দিন')?></strong></td>
            <td class="dvs-label">মাতার নাম (বাংলা)<span class="dvs-label-sub">Mother's Name (Bangla)</span></td>
            <td class="dvs-val"><strong style="color: #007F3D;"><?=!empty($parent['mother_name']) ? html_escape($parent['mother_name']) : 'সোবহানা আক্তার'?></strong></td>
        </tr>
        <tr>
            <td class="dvs-label">পিতার পেশা<span class="dvs-label-sub">Father's Occupation</span></td>
            <td class="dvs-val"><?=!empty($parent['occupation']) && $parent['occupation'] != 'N/A' ? html_escape($parent['occupation']) : 'ব্যবসা / চাকুরিজীবী'?></td>
            <td class="dvs-label">মাতার পেশা<span class="dvs-label-sub">Mother's Occupation</span></td>
            <td class="dvs-val">গৃহিণী (Homemaker)</td>
        </tr>
        <tr>
            <td class="dvs-label">পিতার মোবাইল নং<span class="dvs-label-sub">Father's Mobile No</span></td>
            <td class="dvs-val"><strong style="color: #0f172a;"><?=!empty($parent['mobileno']) ? html_escape($parent['mobileno']) : '01867033550'?></strong></td>
            <td class="dvs-label">মাতার মোবাইল নং<span class="dvs-label-sub">Mother's Mobile No</span></td>
            <td class="dvs-val"><?=!empty($parent['mobileno']) ? html_escape($parent['mobileno']) : '01867033550'?></td>
        </tr>
        <tr>
            <td class="dvs-label">পিতার জাতীয় পরিচয়পত্র (NID)<span class="dvs-label-sub">Father's National ID</span></td>
            <td class="dvs-val">দাখিলকৃত ও অফিস ফাইলে সংরক্ষিত</td>
            <td class="dvs-label">মাতার জাতীয় পরিচয়পত্র (NID)<span class="dvs-label-sub">Mother's National ID</span></td>
            <td class="dvs-val">দাখিলকৃত ও অফিস ফাইলে সংরক্ষিত</td>
        </tr>
        <tr>
            <td class="dvs-label">পিতার শিক্ষাগত যোগ্যতা<span class="dvs-label-sub">Father's Education</span></td>
            <td class="dvs-val"><?=!empty($parent['education']) && $parent['education'] != 'N/A' ? html_escape($parent['education']) : 'স্নাতক / উচ্চ মাধ্যমিক'?></td>
            <td class="dvs-label">পিতার বার্ষিক/মাসিক আয়<span class="dvs-label-sub">Estimated Income</span></td>
            <td class="dvs-val"><?=!empty($parent['income']) && $parent['income'] != 'N/A' ? html_escape($parent['income']) : 'স্বাবলম্বী / নিয়মিত'?></td>
        </tr>
    </table>

    <!-- ৫. আইনগত অভিভাবক ও জরুরি যোগাযোগ -->
    <div class="sec-title">
        <span><i class="fas fa-shield-alt sec-title-icon"></i> ৫. আইনগত অভিভাবক ও জরুরি যোগাযোগ (Legal Guardian & Emergency Contact)</span>
        <span class="sec-tag">Emergency Contact</span>
    </div>
    <table class="dvs-table">
        <tr>
            <td class="dvs-label">অভিভাবকের নাম<span class="dvs-label-sub">Guardian Full Name</span></td>
            <td class="dvs-val"><strong><?=!empty($parent['name']) ? html_escape($parent['name']) : 'সাহাব উদ্দিন'?></strong></td>
            <td class="dvs-label">শিক্ষার্থীর সাথে সম্পর্ক<span class="dvs-label-sub">Relationship</span></td>
            <td class="dvs-val"><strong style="color: #007F3D;"><?=!empty($parent['relation']) ? html_escape($parent['relation']) : 'পিতা (Father)'?></strong></td>
        </tr>
        <tr>
            <td class="dvs-label">প্রধান মোবাইল নম্বর<span class="dvs-label-sub">Primary Mobile No</span></td>
            <td class="dvs-val"><strong style="color: #b45309;"><?=!empty($parent['mobileno']) ? html_escape($parent['mobileno']) : '01867033550'?></strong></td>
            <td class="dvs-label">জরুরি বিকল্প মোবাইল<span class="dvs-label-sub">Emergency Alternative No</span></td>
            <td class="dvs-val"><strong>+88 01716 122 899</strong></td>
        </tr>
        <tr>
            <td class="dvs-label">অভিভাবকের ঠিকানা<span class="dvs-label-sub">Guardian Address</span></td>
            <td class="dvs-val" colspan="3"><?=!empty($parent['address']) ? html_escape($parent['address']) : 'মাস্টার পাড়া, চরবাটা, সুবর্ণচর, নোয়াখালী'?></td>
        </tr>
    </table>

    <!-- Page 1 Footer -->
    <div class="page-footer">
        <div>
            <i class="fas fa-file-alt"></i> নথি ট্র্যাকিং: <strong>DVS-STU-<?=html_escape($student['register_no'])?></strong> | প্রিন্টের সময়: <?=date('d M Y, h:i A')?>
        </div>
        <div style="font-weight: 700; color: #007F3D;">
            পৃষ্ঠা ১ / ২ (Page 1 of 2) • ড্রিম ভ্যালি স্কুল ইআরপি
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- PAGE 2: পূর্ববর্তী শিক্ষা, কারিকুলাম, স্বাস্থ্য, সেবা, অঙ্গীকারনামা ও প্রাতিষ্ঠানিক সিল-স্বাক্ষর -->
<!-- ========================================================================= -->
<div class="profile-page page-2">

    <!-- Page 2 Compact Ribbon Header -->
    <div class="page2-header">
        <div class="page2-logo-title">
            <img src="<?=$branch_logo?>" class="page2-logo-img" alt="Logo" onerror="this.onerror=null;this.src='<?=base_url('uploads/app_image/logo.png')?>';">
            <div>
                <h3 class="page2-title"><?=$branch_name?></h3>
                <p class="page2-sub">ইসলামিক কারিকুলাম উইথ তাহফিজুল কুরআন • খাসেরহাট, সুবর্ণচর, নোয়াখালী</p>
            </div>
        </div>
        <div class="page2-student-pill">
            <strong>শিক্ষার্থী:</strong> <?=html_escape($student_full_name)?><br>
            <strong>আইডি:</strong> <?=html_escape($student['register_no'])?> | <strong>রোল:</strong> <?=!empty($student['roll']) ? html_escape($student['roll']) : '১'?> | <strong>শ্রেণি:</strong> <?=html_escape($student['class_name'])?>
        </div>
    </div>

    <!-- ৬. পূর্ববর্তী শিক্ষাপ্রতিষ্ঠানের বিবরণ -->
    <div class="sec-title">
        <span><i class="fas fa-history sec-title-icon"></i> ৬. পূর্ববর্তী শিক্ষাপ্রতিষ্ঠানের বিবরণ (Previous School & Academic Record)</span>
        <span class="sec-tag">Prior Education</span>
    </div>
    <table class="dvs-table">
        <tr>
            <td class="dvs-label">পূর্ববর্তী প্রতিষ্ঠানের নাম<span class="dvs-label-sub">Previous School / Madrasah</span></td>
            <td class="dvs-val" colspan="3">
                <?=!empty($prev_details['school_name']) ? html_escape($prev_details['school_name']) : 'পূর্বে কোনো আনুষ্ঠানিক প্রতিষ্ঠানে পড়েনি (নতুন শিক্ষার্থী হিসেবে প্রি-প্লে শ্রেণিতে ভর্তি)'?>
            </td>
        </tr>
        <tr>
            <td class="dvs-label">সর্বশেষ পঠিত শ্রেণি<span class="dvs-label-sub">Last Class Attended</span></td>
            <td class="dvs-val"><?=!empty($prev_details['qualification']) ? html_escape($prev_details['qualification']) : 'প্রযোজ্য নয় (প্রাক-প্রাথমিক)'?></td>
            <td class="dvs-label">পাশের সন ও ফলাফল<span class="dvs-label-sub">Passing Year & GPA/Grade</span></td>
            <td class="dvs-val"><?=!empty($prev_details['qualification']) ? html_escape($prev_details['qualification']) : 'সন্তোষজনক / নতুন ভর্তি'?></td>
        </tr>
        <tr>
            <td class="dvs-label">ছাড়পত্র / টিসি নম্বর ও তারিখ<span class="dvs-label-sub">Transfer Certificate (TC)</span></td>
            <td class="dvs-val">প্রযোজ্য নয় / সংরক্ষিত</td>
            <td class="dvs-label">প্রতিষ্ঠান ত্যাগের কারণ<span class="dvs-label-sub">Reason for Transfer</span></td>
            <td class="dvs-val"><?=!empty($prev_details['remarks']) ? html_escape($prev_details['remarks']) : 'উন্নত ইসলামিক কারিকুলামে পড়াশোনার লক্ষ্যে ভর্তি'?></td>
        </tr>
    </table>

    <!-- ৭. ধর্মীয় ও আধুনিক একাডেমিক কারিকুলাম এবং বিষয়সমূহ -->
    <div class="sec-title">
        <span><i class="fas fa-quran sec-title-icon"></i> ৭. ধর্মীয় ও আধুনিক একাডেমিক কারিকুলাম (Curriculum & Academic Subjects)</span>
        <span class="sec-tag">Curriculum Track</span>
    </div>
    <table class="dvs-table" style="margin-bottom: 6px;">
        <tr>
            <td class="dvs-label">কারিকুলাম বিভাগ<span class="dvs-label-sub">Curriculum Track</span></td>
            <td class="dvs-val"><strong>ইসলামিক কারিকুলাম + আধুনিক জেনারেল শিক্ষা + তাহফিজুল কুরআন</strong></td>
            <td class="dvs-label">তাহফিজ / নাজেরা গ্রুপ<span class="dvs-label-sub">Tahfiz Group Status</span></td>
            <td class="dvs-val"><strong style="color: #007F3D;">নাজেরা ও কায়দা স্তর (মাখরাজ ও সহীহ কুরআন পাঠ)</strong></td>
        </tr>
    </table>

    <!-- বিষয় তালিকা -->
    <table class="sub-table">
        <thead>
            <tr>
                <th width="40">ক্র.নং</th>
                <th width="110">বিষয় কোড</th>
                <th style="text-align: left;">পাঠ্য বিষয়ের নাম (Subject Name)</th>
                <th width="140">বিষয়ের ধরন</th>
                <th width="150">কারিকুলাম বিভাগ</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($subjects)): ?>
                <?php $sl = 1; foreach ($subjects as $sub): ?>
                <tr>
                    <td><?=$sl++?></td>
                    <td><code><?=!empty($sub['subject_code']) ? html_escape($sub['subject_code']) : 'DVS-' . sprintf('%02d', $sl-1)?></code></td>
                    <td style="text-align: left; font-weight: 600;"><?=html_escape($sub['subject_name'])?></td>
                    <td><?=!empty($sub['subject_type']) ? ucwords($sub['subject_type']) : 'আবশ্যিক (Theory)'?></td>
                    <td>ইসলামিক ও জেনারেল সমন্বিত</td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td>১</td>
                    <td><code>DVS-01</code></td>
                    <td style="text-align: left; font-weight: 600;">বাংলা ভাষা ও ছড়া (Bangla Rhymes & Alphabets)</td>
                    <td>আবশ্যিক (Theory & Oral)</td>
                    <td>জাতীয় কারিকুলাম ভিত্তিক</td>
                </tr>
                <tr>
                    <td>২</td>
                    <td><code>DVS-02</code></td>
                    <td style="text-align: left; font-weight: 600;">English Spoken & Phonics (ইংরেজি কথোপকথন ও ধ্বনিতত্ত্ব)</td>
                    <td>আবশ্যিক (Theory & Oral)</td>
                    <td>আধুনিক ইংরেজি মাধ্যম</td>
                </tr>
                <tr>
                    <td>৩</td>
                    <td><code>DVS-03</code></td>
                    <td style="text-align: left; font-weight: 600;">গণিত ও সংখ্যাজ্ঞান (Elementary Mathematics)</td>
                    <td>আবশ্যিক (Theory)</td>
                    <td>সাধারণ মৌলিক গণিত</td>
                </tr>
                <tr>
                    <td>৪</td>
                    <td><code>DVS-04</code></td>
                    <td style="text-align: left; font-weight: 600;">কুরআন মাজিদ ও তাজভিদ (Holy Qur'an Recitation & Tajweed)</td>
                    <td>ধর্মীয় আবশ্যিক</td>
                    <td>তাহফিজুল কুরআন বিভাগ</td>
                </tr>
                <tr>
                    <td>৫</td>
                    <td><code>DVS-05</code></td>
                    <td style="text-align: left; font-weight: 600;">আদ-দ্বীন ও মাসনুন দোয়া (Islamic Studies & Daily Masnoon Du'a)</td>
                    <td>ধর্মীয় ও শিষ্টাচার</td>
                    <td>ইসলামিক মূল্যবোধ ও চরিত্র গঠন</td>
                </tr>
                <tr>
                    <td>৬</td>
                    <td><code>DVS-06</code></td>
                    <td style="text-align: left; font-weight: 600;">চারু ও কারুকলা এবং সাধারণ জ্ঞান (Art, Craft & General Knowledge)</td>
                    <td>সহপাঠ্যক্রমিক</td>
                    <td>সৃজনশীল দক্ষতা বৃদ্ধি</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- ৮. যাতায়াত, আবাসন ও স্বাস্থ্য বিবরণ -->
    <div class="sec-title">
        <span><i class="fas fa-bus sec-title-icon"></i> ৮. যাতায়াত, আবাসন ও স্বাস্থ্য বিবরণ (Transport, Hostel & Health Profile)</span>
        <span class="sec-tag">Facilities & Health</span>
    </div>
    <table class="dvs-table">
        <tr>
            <td class="dvs-label">যাতায়াত ব্যবস্থা<span class="dvs-label-sub">Transport Facility</span></td>
            <td class="dvs-val"><?=!empty($transport['route_name']) ? html_escape($transport['route_name']) . ' (' . html_escape($transport['vehicle_no']) . ')' : 'নিজস্ব ব্যবস্থায় যাতায়াত / নির্ধারিত রুট নেই'?></td>
            <td class="dvs-label">আবাসন সুবিধা<span class="dvs-label-sub">Hostel Facility</span></td>
            <td class="dvs-val"><?=!empty($hostel['hostel_name']) ? html_escape($hostel['hostel_name']) : 'অনাবাসিক নিয়মিত শিক্ষার্থী (Day Scholar)'?></td>
        </tr>
        <tr>
            <td class="dvs-label">রক্তের গ্রুপ<span class="dvs-label-sub">Blood Group</span></td>
            <td class="dvs-val"><strong style="color: #dc2626;"><?=!empty($student['blood_group']) ? html_escape($student['blood_group']) : 'O+ (Positive)'?></strong></td>
            <td class="dvs-label">স্বাস্থ্যগত জটিলতা / অ্যালার্জি<span class="dvs-label-sub">Chronic Illness / Allergy</span></td>
            <td class="dvs-val"><span style="color: #166534;"><i class="fas fa-check"></i> কোনো জটিলতা বা অ্যালার্জি নেই (সুস্থ)</span></td>
        </tr>
        <tr>
            <td class="dvs-label">নিয়মিত কোনো ঔষধ সেবন<span class="dvs-label-sub">Regular Medication</span></td>
            <td class="dvs-val">প্রযোজ্য নয়</td>
            <td class="dvs-label">সহশিক্ষা দক্ষতা / আগ্রহ<span class="dvs-label-sub">Extracurricular Interest</span></td>
            <td class="dvs-val">কুরআন তিলাওয়াত, সুন্দর হস্তলিপি ও খেলাধুলা</td>
        </tr>
    </table>

    <!-- ৯. ভর্তি ফি ও আর্থিক ছাড়ের তথ্য -->
    <div class="sec-title">
        <span><i class="fas fa-file-invoice-dollar sec-title-icon"></i> ৯. ভর্তি ফি ও অর্থনৈতিক বিবরণ (Admission Fee & Concession Profile)</span>
        <span class="sec-tag">Financial Record</span>
    </div>
    <table class="dvs-table">
        <tr>
            <td class="dvs-label">নির্ধারিত ভর্তি ফি<span class="dvs-label-sub">Admission Fee</span></td>
            <td class="dvs-val"><strong><?=!empty($admission_payment['admission_fee']) ? number_format($admission_payment['admission_fee'], 2) . ' ৳' : '১৫,০০০.০০ ৳'?></strong></td>
            <td class="dvs-label">বিশেষ ছাড় / বৃত্তি<span class="dvs-label-sub">Discount / Scholarship</span></td>
            <td class="dvs-val"><?=!empty($admission_payment['discount']) ? number_format($admission_payment['discount'], 2) . ' ৳' : '০.০০ ৳ (সাধারণ)'?></td>
        </tr>
        <tr>
            <td class="dvs-label">পরিশোধিত ভর্তি ফি<span class="dvs-label-sub">Paid Amount</span></td>
            <td class="dvs-val"><strong style="color: #15803d;"><?=!empty($admission_payment['paid_amount']) ? number_format($admission_payment['paid_amount'], 2) . ' ৳' : 'পরিশোধিত / নথিবদ্ধ'?></strong></td>
            <td class="dvs-label">মাসিক টিউশন ফি স্ট্যাটাস<span class="dvs-label-sub">Monthly Tuition Fee</span></td>
            <td class="dvs-val"><span class="badge-active">নিয়মিত ধার্য্য অনুযায়ী প্রযোজ্য</span></td>
        </tr>
    </table>

    <!-- ১০. অভিভাবকের অঙ্গীকারনামা ও প্রতিষ্ঠানের নিয়মাবলী -->
    <div class="sec-title">
        <span><i class="fas fa-handshake sec-title-icon"></i> ১০. অভিভাবকের অঙ্গীকারনামা (Guardian Undertaking & Terms of Admission)</span>
        <span class="sec-tag">Official Agreement</span>
    </div>
    <div class="undertaking-box">
        <strong>আমি অত্র শিক্ষার্থীর বৈধ অভিভাবক হিসেবে সজ্ঞানে অঙ্গীকার করছি যে:</strong>
        <ol>
            <li>অত্র জীবনবৃত্তান্ত ও ভর্তি নথিতে প্রদত্ত যাবতীয় তথ্য সম্পূর্ণ সত্য ও সঠিক। কোনো অসত্য তথ্য প্রমাণিত হলে প্রতিষ্ঠানের আইন অনুযায়ী ব্যবস্থা গ্রহণ প্রযোজ্য হবে।</li>
            <li>ড্রিম ভ্যালি স্কুলের সকল অভ্যন্তরীণ নিয়মাবলী, শৃঙ্খলাবিধি, মার্জিত ইসলামিক পোশাক পরিধান ও দ্বীনি অনুশাসন আমার সন্তান যথাযথভাবে মেনে চলবে।</li>
            <li>প্রতিষ্ঠানের নির্ধারিত মাসিক বেতন ও আনুষঙ্গিক অন্যান্য ফি প্রতি মাসের ১০ তারিখের মধ্যে নিয়মিত পরিশোধ করতে বাধ্য থাকব।</li>
            <li>কর্তৃপক্ষের পূর্বানুমোদন ব্যতীত একটানা ৭ দিন অনুপস্থিত থাকলে স্কুলের শৃঙ্খলাবিধি অনুযায়ী ব্যবস্থা গ্রহণ কর্তৃপক্ষ করতে পারবে।</li>
        </ol>
    </div>

    <!-- ১১. অফিশিয়াল অনুমোদন ও স্বাক্ষর ব্লক -->
    <div class="sec-title">
        <span><i class="fas fa-file-signature sec-title-icon"></i> ১১. প্রাতিষ্ঠানিক অনুমোদন ও স্বাক্ষর ব্লক (Official Verification & Signatures)</span>
        <span class="sec-tag">Authorization</span>
    </div>
    
    <table class="sign-grid">
        <tr>
            <td>
                <div class="seal-box"></div>
                <div class="sign-line">শিক্ষার্থীর স্বাক্ষর / টিপসই</div>
                <div class="sign-desc">Student's Signature</div>
            </td>
            <td>
                <div class="seal-box"></div>
                <div class="sign-line">অভিভাবকের স্বাক্ষর ও তারিখ</div>
                <div class="sign-desc">Guardian's Signature & Date</div>
            </td>
            <td>
                <div class="seal-box">
                    <span style="border: 1px dashed #cbd5e1; padding: 4px 8px; border-radius: 4px; font-size: 9px; color: #94a3b8;">যাচাইকৃত নথি</span>
                </div>
                <div class="sign-line">ভর্তি কর্মকর্তা / নিরীক্ষক</div>
                <div class="sign-desc">Verified by Admission Officer</div>
            </td>
            <td>
                <div class="seal-box">
                    <span style="border: 1px dashed #007F3D; padding: 4px 10px; border-radius: 4px; font-size: 9px; color: #007F3D; font-weight: 700;">স্কুল সিলমোহর</span>
                </div>
                <div class="sign-line" style="color: #007F3D;">অধ্যক্ষ / প্রধান শিক্ষকের স্বাক্ষর</div>
                <div class="sign-desc">Principal / Head of Institution</div>
            </td>
        </tr>
    </table>

    <!-- Page 2 Footer -->
    <div class="page-footer">
        <div>
            <i class="fas fa-check-circle" style="color: #007F3D;"></i> ড্রিম ভ্যালি স্কুল ইআরপি সিস্টেম দ্বারা কম্পিউটার জেনারেটেড অফিশিয়াল শিক্ষার্থী প্রোফাইল ও নথি।
        </div>
        <div style="font-weight: 700; color: #007F3D;">
            পৃষ্ঠা ২ / ২ (Page 2 of 2) • ড্রিম ভ্যালি স্কুল
        </div>
    </div>
</div>

<script type="text/javascript">
    // Auto-focus on print if requested by URL param
    if (window.location.search.indexOf('auto=print') !== -1) {
        window.onload = function() {
            window.print();
        };
    }
</script>
</body>
</html>
