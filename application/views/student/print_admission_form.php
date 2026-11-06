<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ভর্তি ফরম ও ফি রশিদ - <?=html_escape($student['first_name'] . ' ' . $student['last_name'])?> (<?=html_escape($student['register_no'])?>)</title>
    <link rel="stylesheet" href="<?=base_url('assets/vendor/bootstrap/css/bootstrap.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/vendor/font-awesome/css/all.min.css')?>">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #f1f5f9;
            font-family: 'Hind Siliguri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 20px 0;
            font-size: 13px;
            line-height: 1.45;
        }
        .admission-paper {
            max-width: 860px;
            margin: 0 auto;
            background: #fff;
            padding: 30px 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-radius: 8px;
            position: relative;
        }
        
        /* Floating Action Bar (Hidden on print) */
        .no-print-bar {
            max-width: 860px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .btn-action-print {
            background: #007F3D;
            color: #fff;
            font-weight: 700;
            padding: 8px 24px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn-action-print:hover {
            background: #005f2e;
            color: #fff;
        }
        .btn-action-sec {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            text-decoration: none;
            font-size: 13px;
            margin-left: 8px;
            display: inline-block;
        }
        .btn-action-sec:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* School Branding Header */
        .school-header-table {
            width: 100%;
            border-bottom: 2px solid #007F3D;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .school-logo-img {
            max-height: 85px;
            max-width: 130px;
            object-fit: contain;
        }
        .school-info-col {
            text-align: center;
            padding: 0 10px;
        }
        .school-title-bn {
            font-size: 24px;
            font-weight: 700;
            color: #007F3D;
            margin: 0 0 2px;
            letter-spacing: 0.5px;
        }
        .school-title-en {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 3px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .school-tagline {
            font-size: 12.5px;
            font-weight: 600;
            color: #047857;
            margin: 0 0 4px;
        }
        .school-contact-line {
            font-size: 11.5px;
            color: #475569;
            margin: 0;
        }

        /* Form Title Badge */
        .form-title-badge {
            text-align: center;
            margin: 10px 0 16px;
        }
        .form-title-badge span {
            background: #007F3D;
            color: #fff;
            padding: 5px 22px;
            font-size: 13.5px;
            font-weight: 700;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .form-meta-tag {
            font-size: 11.5px;
            color: #64748b;
            margin-top: 4px;
            font-weight: 600;
        }

        /* Photo Box */
        .photo-frame-box {
            width: 105px;
            height: 125px;
            border: 2px dashed #94a3b8;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: #f8fafc;
            overflow: hidden;
            margin-left: auto;
        }
        .photo-frame-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-placeholder-text {
            font-size: 10px;
            color: #64748b;
            line-height: 1.25;
            padding: 4px;
            font-weight: 600;
        }

        /* Section Headings */
        .dvs-sec-title {
            background: #f1f5f9;
            border-left: 4px solid #007F3D;
            padding: 5px 12px;
            margin-top: 14px;
            margin-bottom: 8px;
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .dvs-sec-title i {
            color: #007F3D;
            margin-right: 6px;
        }

        /* Grid Data Table */
        .info-grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .info-grid-table td {
            padding: 4px 8px;
            vertical-align: top;
            font-size: 12px;
            border: 1px solid #e2e8f0;
        }
        .info-grid-table td.td-label {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            width: 22%;
        }
        .info-grid-table td.td-val {
            color: #0f172a;
            font-weight: 600;
            width: 28%;
        }

        /* Fee Summary Table */
        .fee-card-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            border: 2px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
        }
        .fee-card-table th {
            background: #007F3D;
            color: #fff;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
        }
        .fee-card-table td {
            padding: 7px 10px;
            font-size: 12.5px;
            border: 1px solid #e2e8f0;
            text-align: center;
            font-weight: 600;
        }
        .fee-val-highlight {
            font-weight: 700;
            font-size: 13.5px;
        }
        .badge-status {
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-paid { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .badge-partial { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .badge-due { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
        .badge-free { background: #e0e7ff; color: #3730a3; border: 1px solid #a5b4fc; }

        /* Terms & Undertaking */
        .undertaking-box {
            background: #fafafa;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            margin-top: 12px;
            font-size: 11px;
            color: #334155;
            line-height: 1.55;
        }
        .undertaking-title {
            font-weight: 700;
            color: #007F3D;
            margin-bottom: 5px;
            font-size: 12px;
        }

        /* Signatures Section */
        .signature-grid {
            margin-top: 35px;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        .sign-col {
            flex: 1;
            text-align: center;
            padding: 0 8px;
        }
        .sign-line {
            border-top: 1px dashed #64748b;
            margin-bottom: 4px;
            width: 85%;
            margin-left: auto;
            margin-right: auto;
        }
        .sign-title {
            font-size: 11.5px;
            font-weight: 700;
            color: #1e293b;
        }
        .sign-sub {
            font-size: 10px;
            color: #64748b;
        }

        /* Print Specific Styling */
        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 11.5px !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .admission-paper {
                box-shadow: none !important;
                padding: 10px 15px !important;
                max-width: 100% !important;
                border-radius: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 8mm 10mm;
            }
            .dvs-sec-title {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .fee-card-table th {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: #007F3D !important;
                color: #fff !important;
            }
            .form-title-badge span {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: #007F3D !important;
                color: #fff !important;
            }
            .badge-status {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Top Action Bar (Hidden on print) -->
    <div class="no-print-bar">
        <div>
            <span style="font-weight: 700; color: #007F3D; font-size: 15px;">
                <i class="fas fa-file-invoice"></i> ড্রিম ভ্যালি স্কুল - ভর্তি ফরম ও মানি রিসিট
            </span>
            <span class="text-muted" style="margin-left: 10px; font-size: 12px;">(রেজিস্টার নং: <?=html_escape($student['register_no'])?>)</span>
        </div>
        <div>
            <button type="button" class="btn-action-print" onclick="window.print();">
                <i class="fas fa-print"></i> ফরমটি প্রিন্ট করুন (Print Now)
            </button>
            <a href="<?=base_url('student/profile/' . (!empty($student['enrollid']) ? $student['enrollid'] : $student['id']))?>" class="btn-action-sec">
                <i class="fas fa-user"></i> প্রোফাইল
            </a>
            <a href="<?=base_url('student/add')?>" class="btn-action-sec">
                <i class="fas fa-user-plus"></i> নতুন ভর্তি
            </a>
        </div>
    </div>

    <!-- Main Printable Admission Document -->
    <div class="admission-paper">
        
        <!-- Header Branding Table -->
        <table class="school-header-table">
            <tr>
                <td style="width: 18%; vertical-align: middle; text-align: left;">
                    <?php
                    $logo_url = base_url('uploads/app_image/logo.png');
                    ?>
                    <img src="<?=$logo_url?>" alt="Logo" class="school-logo-img" onerror="this.style.display='none';">
                </td>
                <td class="school-info-col" style="width: 64%; vertical-align: middle;">
                    <h1 class="school-title-bn">ড্রিম ভ্যালি স্কুল</h1>
                    <h2 class="school-title-en">DREAM VALLEY SCHOOL</h2>
                    <p class="school-tagline">ইসলামিক কারিকুলাম উইথ তাহফিজুল কুরআন (Islamic Curriculum with Tahfeezul Quran)</p>
                    <p class="school-contact-line">
                        খাসেরহাট মেইন রোড, চরবাটা, সুবর্ণচর, নোয়াখালী | মোবাইল: 01716-122899, 01712-886988<br>
                        ওয়েবসাইট: dreamvalleyschool.com | ইমেইল: dreamvalleyschoolbd@gmail.com
                    </p>
                </td>
                <td style="width: 18%; vertical-align: middle; text-align: right;">
                    <div class="photo-frame-box">
                        <?php 
                        $photo_src = !empty($student['photo']) ? base_url('uploads/images/student/' . $student['photo']) : '';
                        if (!empty($student['photo']) && file_exists(FCPATH . 'uploads/images/student/' . $student['photo'])): ?>
                            <img src="<?=$photo_src?>" alt="Student Photo">
                        <?php else: ?>
                            <div class="photo-placeholder-text">
                                <i class="fas fa-user-circle" style="font-size: 24px; color: #94a3b8; display: block; margin-bottom: 2px;"></i>
                                পাসপোর্ট সাইজ ছবি<br>(Passport Photo)
                            </div>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Document Title -->
        <div class="form-title-badge">
            <span>STUDENT ADMISSION FORM & MONEY RECEIPT (ভর্তি ফরম ও ফি রশিদ)</span>
            <div class="form-meta-tag">
                ভর্তি আইডি / রেজি: <strong><?=html_escape($student['register_no'])?></strong> &nbsp;|&nbsp; 
                ভর্তির তারিখ: <strong><?=(!empty($student['admission_date']) ? date("d M, Y", strtotime($student['admission_date'])) : date("d M, Y"))?></strong> &nbsp;|&nbsp;
                মুদ্রণ তারিখ: <strong><?=date("d M, Y, h:i A")?></strong>
            </div>
        </div>

        <?php
        // Helper to extract custom field value safely
        $cf_vals = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $cf) {
                $cf_vals[$cf['custom_field_id']] = $cf['field_value'];
            }
        }
        $name_en = isset($cf_vals[1]) ? $cf_vals[1] : '';
        $father_en = isset($cf_vals[2]) ? $cf_vals[2] : '';
        $mother_en = isset($cf_vals[3]) ? $cf_vals[3] : '';
        $birth_reg = isset($cf_vals[4]) ? $cf_vals[4] : '';
        $age_txt = isset($cf_vals[12]) ? $cf_vals[12] : '';
        ?>

        <!-- 1. ACADEMIC & ENROLLMENT DETAILS -->
        <div class="dvs-sec-title">
            <i class="fas fa-graduation-cap"></i> ১. একাডেমিক ও শ্রেণি সংক্রান্ত বিবরণ (Academic Information)
        </div>
        <table class="info-grid-table">
            <tr>
                <td class="td-label">শিক্ষাবর্ষ (Academic Year):</td>
                <td class="td-val"><?=html_escape(!empty($student['year']) ? $student['year'] : (get_session_name() ? get_session_name() : date('Y')))?></td>
                <td class="td-label">ভর্তি রেজি: নং (Register No):</td>
                <td class="td-val" style="color: #007F3D; font-size: 13px;"><?=html_escape($student['register_no'])?></td>
            </tr>
            <tr>
                <td class="td-label">শ্রেণি (Class):</td>
                <td class="td-val"><?=html_escape(!empty($student['class_name']) ? $student['class_name'] : 'N/A')?></td>
                <td class="td-label">শাখা (Section):</td>
                <td class="td-val"><?=html_escape(!empty($student['section_name']) ? $student['section_name'] : 'N/A')?></td>
            </tr>
            <tr>
                <td class="td-label">রোল নং (Roll No):</td>
                <td class="td-val"><?=html_escape(!empty($student['roll']) ? $student['roll'] : 'অপেক্ষমাণ')?></td>
                <td class="td-label">ভর্তি স্ট্যাটাস (Status):</td>
                <td class="td-val">
                    <?php if (!empty($payment['status']) && $payment['status'] == 'draft'): ?>
                        <span class="badge-status badge-partial">ড্রাফট (Draft)</span>
                    <?php else: ?>
                        <span class="badge-status badge-paid">নিশ্চিত (Confirmed)</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <!-- 2. STUDENT'S PERSONAL DETAILS -->
        <div class="dvs-sec-title">
            <i class="fas fa-user-graduate"></i> ২. শিক্ষার্থীর ব্যক্তিগত তথ্য (Student's Details)
        </div>
        <table class="info-grid-table">
            <tr>
                <td class="td-label">শিক্ষার্থীর নাম (বাংলায়):</td>
                <td class="td-val" colspan="3" style="font-size: 13.5px; color: #007F3D;">
                    <?=html_escape(trim($student['first_name'] . ' ' . $student['last_name']))?>
                </td>
            </tr>
            <tr>
                <td class="td-label">Name (in English):</td>
                <td class="td-val" colspan="3" style="text-transform: uppercase;">
                    <?=html_escape(!empty($name_en) ? $name_en : trim($student['first_name'] . ' ' . $student['last_name']))?>
                </td>
            </tr>
            <tr>
                <td class="td-label">জন্ম নিবন্ধন নম্বর (BRN):</td>
                <td class="td-val"><?=html_escape(!empty($birth_reg) ? $birth_reg : 'N/A')?></td>
                <td class="td-label">জন্ম তারিখ ও বয়স:</td>
                <td class="td-val">
                    <?=(!empty($student['birthday']) ? date("d M, Y", strtotime($student['birthday'])) : 'N/A')?>
                    <?=(!empty($age_txt) ? ' (' . html_escape($age_txt) . ')' : '')?>
                </td>
            </tr>
            <tr>
                <td class="td-label">লিঙ্গ (Gender):</td>
                <td class="td-val"><?=html_escape(!empty($student['gender']) ? ucfirst($student['gender']) : 'N/A')?></td>
                <td class="td-label">রক্তের গ্রুপ (Blood Group):</td>
                <td class="td-val"><?=html_escape(!empty($student['blood_group']) ? $student['blood_group'] : 'N/A')?></td>
            </tr>
            <tr>
                <td class="td-label">ধর্ম (Religion):</td>
                <td class="td-val"><?=html_escape(!empty($student['religion']) ? $student['religion'] : 'ইসলাম (Islam)')?></td>
                <td class="td-label">শিক্ষার্থীর মোবাইল নং:</td>
                <td class="td-val"><?=html_escape(!empty($student['mobileno']) ? $student['mobileno'] : 'N/A')?></td>
            </tr>
        </table>

        <!-- 3. PARENTS & GUARDIAN DETAILS -->
        <div class="dvs-sec-title">
            <i class="fas fa-user-friends"></i> ৩. অভিভাবক ও পারিবারিক তথ্য (Parents & Guardian Information)
        </div>
        <table class="info-grid-table">
            <tr>
                <td class="td-label">পিতার নাম (Father's Name):</td>
                <td class="td-val">
                    <?=html_escape(!empty($parent['father_name']) ? $parent['father_name'] : (!empty($parent['name']) ? $parent['name'] : 'N/A'))?>
                    <?=(!empty($father_en) ? ' / ' . html_escape($father_en) : '')?>
                </td>
                <td class="td-label">পিতার মোবাইল নম্বর:</td>
                <td class="td-val"><?=html_escape(!empty($parent['father_mobileno']) ? $parent['father_mobileno'] : (!empty($parent['mobileno']) ? $parent['mobileno'] : 'N/A'))?></td>
            </tr>
            <tr>
                <td class="td-label">মাতার নাম (Mother's Name):</td>
                <td class="td-val">
                    <?=html_escape(!empty($parent['mother_name']) ? $parent['mother_name'] : 'N/A')?>
                    <?=(!empty($mother_en) ? ' / ' . html_escape($mother_en) : '')?>
                </td>
                <td class="td-label">মাতার মোবাইল নম্বর:</td>
                <td class="td-val"><?=html_escape(!empty($parent['mother_mobileno']) ? $parent['mother_mobileno'] : 'N/A')?></td>
            </tr>
            <tr>
                <td class="td-label">মূল অভিভাবক ও সম্পর্ক:</td>
                <td class="td-val">
                    <?=html_escape(!empty($parent['name']) ? $parent['name'] : 'N/A')?>
                    <?=(!empty($parent['relation']) ? ' (' . html_escape($parent['relation']) . ')' : '')?>
                </td>
                <td class="td-label">অভিভাবকের পেশা:</td>
                <td class="td-val"><?=html_escape(!empty($parent['occupation']) ? $parent['occupation'] : 'N/A')?></td>
            </tr>
            <tr>
                <td class="td-label">বর্তমান ঠিকানা (Present Address):</td>
                <td class="td-val" colspan="3"><?=html_escape(!empty($student['current_address']) ? $student['current_address'] : (!empty($parent['address']) ? $parent['address'] : 'N/A'))?></td>
            </tr>
            <tr>
                <td class="td-label">স্থায়ী ঠিকানা (Permanent Address):</td>
                <td class="td-val" colspan="3"><?=html_escape(!empty($student['permanent_address']) ? $student['permanent_address'] : (!empty($student['current_address']) ? $student['current_address'] : 'N/A'))?></td>
            </tr>
        </table>

        <!-- 4. ADMISSION FEE & PAYMENT STATEMENT -->
        <div class="dvs-sec-title">
            <i class="fas fa-receipt"></i> ৪. ভর্তি ফি ও পরিশোধ বিবরণী (Admission Fee & Payment Statement)
        </div>
        
        <?php
        $fee_amount = isset($payment['admission_fee']) ? (float)$payment['admission_fee'] : 0.00;
        $disc_amount = isset($payment['discount']) ? (float)$payment['discount'] : 0.00;
        $net_amount = isset($payment['net_amount']) ? (float)$payment['net_amount'] : max(0, $fee_amount - $disc_amount);
        $paid_amount = isset($payment['paid_amount']) ? (float)$payment['paid_amount'] : 0.00;
        $due_amount = isset($payment['due_amount']) ? (float)$payment['due_amount'] : max(0, $net_amount - $paid_amount);
        $pay_via = !empty($payment['pay_via']) ? $payment['pay_via'] : 'Cash';
        $ref_no = !empty($payment['reference_no']) ? $payment['reference_no'] : 'N/A';
        $pay_date = !empty($payment['payment_date']) ? date("d M, Y", strtotime($payment['payment_date'])) : date("d M, Y");
        
        if ($disc_amount >= $fee_amount && $fee_amount > 0) {
            $status_badge = '<span class="badge-status badge-free">১০০% ছাড় / ফ্রি ভর্তি</span>';
        } elseif ($due_amount <= 0 && $paid_amount > 0) {
            $status_badge = '<span class="badge-status badge-paid">পরিশোধিত (PAID)</span>';
        } elseif ($paid_amount > 0 && $due_amount > 0) {
            $status_badge = '<span class="badge-status badge-partial">আংশিক পরিশোধিত (PARTIAL)</span>';
        } else {
            $status_badge = '<span class="badge-status badge-due">বকেয়া (DUE)</span>';
        }
        ?>

        <table class="fee-card-table">
            <thead>
                <tr>
                    <th>নির্ধারিত ভর্তি ফি</th>
                    <th>বিশেষ ছাড় / মওকুফ</th>
                    <th>চূড়ান্ত প্রদেয় টাকা</th>
                    <th>আদায়কৃত / পরিশোধিত</th>
                    <th>অবশিষ্ট বকেয়া</th>
                    <th>পেমেন্ট স্ট্যাটাস</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fee-val-highlight">৳<?=number_format($fee_amount, 2)?></td>
                    <td style="color: #047857;">৳<?=number_format($disc_amount, 2)?></td>
                    <td class="fee-val-highlight" style="color: #0f172a;">৳<?=number_format($net_amount, 2)?></td>
                    <td class="fee-val-highlight" style="color: #007F3D; background: #f0fdf4;">৳<?=number_format($paid_amount, 2)?></td>
                    <td class="fee-val-highlight" style="color: #dc2626; background: #fef2f2;">৳<?=number_format($due_amount, 2)?></td>
                    <td><?=$status_badge?></td>
                </tr>
            </tbody>
        </table>

        <table class="info-grid-table" style="margin-top: 6px;">
            <tr>
                <td class="td-label">পেমেন্ট মাধ্যম (Payment Method):</td>
                <td class="td-val" style="color: #007F3D;"><strong><?=html_escape($pay_via)?></strong></td>
                <td class="td-label">ট্রানজেকশন / রসিদ নং:</td>
                <td class="td-val"><?=html_escape($ref_no)?></td>
            </tr>
            <tr>
                <td class="td-label">পেমেন্টের তারিখ (Payment Date):</td>
                <td class="td-val"><?=$pay_date?></td>
                <td class="td-label">মন্তব্য / নোট (Remarks):</td>
                <td class="td-val"><?=html_escape(!empty($payment['remarks']) ? $payment['remarks'] : 'ভর্তি নিশ্চিতকরণ ফি গ্রহণ করা হলো।')?></td>
            </tr>
        </table>

        <!-- 5. TERMS & CONDITIONS / UNDERTAKING -->
        <div class="undertaking-box">
            <div class="undertaking-title">
                <i class="fas fa-scroll"></i> শিক্ষার্থী ও অভিভাবকের অঙ্গীকারনামা (Terms & Conditions):
            </div>
            <div style="white-space: pre-line;">
                <?=!empty($settings['terms_conditions']) ? html_escape($settings['terms_conditions']) : "১. বিদ্যালয়ের নির্ধারিত পোশাক পরিধান করে নিয়মিত ও সময়মতো উপস্থিত হতে হবে।\n২. ইসলামী তাহযীব ও শৃঙ্খলা মেনে চলতে হবে।\n৩. মোবাইল ফোন আনা সম্পূর্ণ নিষিদ্ধ।\n৪. ভর্তি ফি একবার পরিশোধের পর কোনো অবস্থাতেই অফেরতযোগ্য।" ?>
            </div>
            <div style="margin-top: 6px; font-weight: 600; color: #1e293b;">
                * আমি এই মর্মে অঙ্গীকার করছি যে, উপরে প্রদত্ত সকল তথ্য সত্য ও নির্ভুল। আমি ড্রিম ভ্যালি স্কুলের যাবতীয় নিয়ম-কানুন ও কর্তৃপক্ষের সিদ্ধান্ত সম্পূর্ণরূপে মেনে চলতে বাধ্য থাকিব।
            </div>
        </div>

        <!-- 6. SIGNATURE BOXES -->
        <div class="signature-grid">
            <div class="sign-col">
                <div class="sign-line"></div>
                <div class="sign-title">শিক্ষার্থীর স্বাক্ষর ও তারিখ</div>
                <div class="sign-sub">Student's Signature</div>
            </div>
            <div class="sign-col">
                <div class="sign-line"></div>
                <div class="sign-title">অভিভাবকের স্বাক্ষর ও তারিখ</div>
                <div class="sign-sub">Guardian's Signature</div>
            </div>
            <div class="sign-col">
                <div class="sign-line"></div>
                <div class="sign-title">ভর্তি শাখা / হিসাব শাখা</div>
                <div class="sign-sub">Admission / Accounts Desk</div>
            </div>
            <div class="sign-col">
                <div class="sign-line"></div>
                <div class="sign-title">অধ্যক্ষ / প্রধান শিক্ষক</div>
                <div class="sign-sub">Principal's Seal & Signature</div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 25px; padding-top: 8px; border-top: 1px solid #e2e8f0; font-size: 10.5px; color: #94a3b8;">
            ড্রিম ভ্যালি স্কুল ইআরপি সিস্টেম কর্তৃক স্বয়ংক্রিয়ভাবে প্রস্তুতকৃত অফিসিয়াল ভর্তি আবেদন ফরম ও মানি রিসিট।
        </div>

    </div>

</body>
</html>
