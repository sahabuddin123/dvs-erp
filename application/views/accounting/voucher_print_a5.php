<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $is_expense = ($voucher['type'] == 'expense');
    $v_type_bn = $is_expense ? 'খরচ ভাউচার (ডেবিট)' : 'জমা ভাউচার (ক্রেডিট)';
    $v_type_en = $is_expense ? 'DEBIT VOUCHER' : 'CREDIT VOUCHER';
    $v_code = 'DVS-' . ($is_expense ? 'EXP' : 'DEP') . '-' . str_pad($voucher['id'], 5, '0', STR_PAD_LEFT);
    $theme_color = $is_expense ? '#b91c1c' : '#007F3D';
    $theme_bg = $is_expense ? '#fef2f2' : '#f0fdf4';
    $theme_border = $is_expense ? '#fca5a5' : '#86efac';
    ?>
    <title><?=$v_type_en?> #<?=$v_code?> - ড্রিম ভ্যালি স্কুল</title>
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
            font-size: 12px;
            line-height: 1.4;
        }

        /* Top Action Bar (Hidden when printing) */
        .no-print-bar {
            max-width: 820px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .btn-action-print {
            background: <?=$theme_color?>;
            color: #fff;
            font-weight: 700;
            padding: 7px 22px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 13.5px;
            transition: all 0.2s;
        }
        .btn-action-print:hover {
            opacity: 0.9;
            color: #fff;
        }
        .btn-action-sec {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            text-decoration: none;
            font-size: 12.5px;
            margin-left: 6px;
            display: inline-block;
        }
        .btn-action-sec:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* A5 Landscape Voucher Card */
        .voucher-a5-card {
            max-width: 820px;
            min-height: 520px;
            margin: 0 auto;
            background: #fff;
            padding: 22px 28px 18px;
            border: 2px solid #0f172a;
            border-radius: 6px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
        }

        /* Header Area */
        .v-header-table {
            width: 100%;
            border-bottom: 2px double #334155;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .v-logo-img {
            max-height: 65px;
            max-width: 110px;
            object-fit: contain;
        }
        .v-school-title-bn {
            font-size: 21px;
            font-weight: 700;
            color: #007F3D;
            margin: 0 0 1px;
            line-height: 1.2;
        }
        .v-school-title-en {
            font-size: 14.5px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 2px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .v-school-tagline {
            font-size: 11px;
            font-weight: 600;
            color: #047857;
            margin: 0 0 2px;
        }
        .v-school-contact {
            font-size: 10px;
            color: #475569;
            margin: 0;
            line-height: 1.3;
        }

        /* Voucher Badge & Metadata */
        .v-type-badge-container {
            text-align: center;
            margin: 6px 0 10px;
        }
        .v-type-badge {
            background: <?=$theme_color?>;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 4px 24px;
            border-radius: 18px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: inline-block;
        }

        /* Info Grid Table */
        .v-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .v-info-table td {
            padding: 3.5px 8px;
            font-size: 11.5px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }
        .v-info-table td.lbl {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            width: 18%;
        }
        .v-info-table td.val {
            color: #0f172a;
            font-weight: 600;
            width: 32%;
        }

        /* Particulars Table */
        .v-particulars-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border: 1px solid #334155;
        }
        .v-particulars-table th {
            background: #f1f5f9;
            color: #0f172a;
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 8px;
            border: 1px solid #334155;
            text-align: center;
        }
        .v-particulars-table td {
            padding: 6px 8px;
            font-size: 11.5px;
            border: 1px solid #cbd5e1;
        }
        .v-particulars-table td.amount-cell {
            text-align: right;
            font-weight: 700;
            font-size: 13px;
            width: 25%;
            color: #0f172a;
        }
        .v-particulars-table tr.total-row td {
            background: <?=$theme_bg?>;
            border-top: 2px solid <?=$theme_color?>;
            font-weight: 700;
            font-size: 13px;
        }

        /* Amount in Words */
        .v-words-box {
            background: #f8fafc;
            border: 1px dashed #94a3b8;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 25px;
            font-size: 11px;
            color: #1e293b;
        }
        .v-words-box strong {
            color: <?=$theme_color?>;
        }

        /* Signatures */
        .v-sign-row {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 5px;
        }
        .v-sign-col {
            flex: 1;
            text-align: center;
            padding: 0 5px;
        }
        .v-sign-line {
            border-top: 1px dashed #475569;
            width: 80%;
            margin: 0 auto 3px;
        }
        .v-sign-title {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
        }
        .v-sign-sub {
            font-size: 9.5px;
            color: #64748b;
        }

        /* Print Specific Styling */
        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 11px !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .voucher-a5-card {
                box-shadow: none !important;
                border: 2px solid #000 !important;
                padding: 10px 18px 12px !important;
                max-width: 100% !important;
                border-radius: 0 !important;
                min-height: auto !important;
            }
            @page {
                size: A5 landscape;
                margin: 6mm 8mm;
            }
            .v-type-badge {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: <?=$theme_color?> !important;
                color: #fff !important;
            }
            .v-particulars-table th {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: #f1f5f9 !important;
            }
            .v-particulars-table tr.total-row td {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: <?=$theme_bg?> !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Toolbar (Hidden when printing) -->
    <div class="no-print-bar">
        <div>
            <span style="font-weight: 700; color: <?=$theme_color?>; font-size: 14.5px;">
                <i class="fas fa-file-invoice-dollar mr-xs"></i> <?=$v_type_bn?> #<?=$v_code?>
            </span>
            <span class="text-muted" style="margin-left: 8px; font-size: 12px;">(A5 Single Voucher Format)</span>
        </div>
        <div>
            <button type="button" class="btn-action-print" onclick="window.print();">
                <i class="fas fa-print mr-xs"></i> ভাউচারটি প্রিন্ট করুন (Print A5)
            </button>
            <?php if ($is_expense): ?>
                <a href="<?=base_url('accounting/voucher_expense')?>" class="btn-action-sec">
                    <i class="fas fa-list-ul"></i> খরচ তালিকা (Expense List)
                </a>
            <?php else: ?>
                <a href="<?=base_url('accounting/voucher_deposit')?>" class="btn-action-sec">
                    <i class="fas fa-list-ul"></i> জমা তালিকা (Deposit List)
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main A5 Single Voucher Document -->
    <div class="voucher-a5-card">
        
        <!-- Header Branding Table -->
        <table class="v-header-table">
            <tr>
                <td style="width: 18%; vertical-align: middle; text-align: left;">
                    <?php $logo_url = base_url('uploads/app_image/logo.png'); ?>
                    <img src="<?=$logo_url?>" alt="Logo" class="v-logo-img" onerror="this.style.display='none';">
                </td>
                <td style="width: 64%; vertical-align: middle; text-align: center;">
                    <h1 class="v-school-title-bn">ড্রিম ভ্যালি স্কুল</h1>
                    <h2 class="v-school-title-en">DREAM VALLEY SCHOOL</h2>
                    <p class="v-school-tagline">ইসলামিক কারিকুলাম উইথ তাহফিজুল কুরআন (Islamic Curriculum with Tahfeezul Quran)</p>
                    <p class="v-school-contact">
                        খাসেরহাট মেইন রোড, চরবাটা, সুবর্ণচর, নোয়াখালী | মোবাইল: 01716-122899, 01712-886988<br>
                        ওয়েবসাইট: dreamvalleyschool.com | ইমেইল: dreamvalleyschoolbd@gmail.com
                    </p>
                </td>
                <td style="width: 18%; vertical-align: middle; text-align: right;">
                    <div style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 6px; background: #f8fafc; display: inline-block; text-align: right;">
                        <span style="font-size: 10px; color: #64748b; display: block;">ভাউচার নং (Voucher No)</span>
                        <strong style="font-size: 12.5px; color: <?=$theme_color?>;"><?=$v_code?></strong>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Voucher Badge & Meta Info -->
        <div class="v-type-badge-container">
            <span class="v-type-badge"><?=$v_type_en?> (<?=$v_type_bn?>)</span>
        </div>

        <?php
        $payer = !empty($voucher['pay_by']) ? $voucher['pay_by'] : ($is_expense ? 'ড্রিম ভ্যালি স্কুল (অফিস ফান্ড)' : 'N/A');
        $receiver = !empty($voucher['received_by']) ? $voucher['received_by'] : ($is_expense ? 'N/A' : 'ড্রিম ভ্যালি স্কুল (হিসাব শাখা)');
        $pay_method = !empty($voucher['via_name']) ? $voucher['via_name'] : (!empty($voucher['pay_via']) ? $voucher['pay_via'] : 'Cash');
        ?>

        <!-- Voucher Metadata Grid -->
        <table class="v-info-table">
            <tr>
                <td class="lbl">ভাউচার নং (Vch No):</td>
                <td class="val" style="color: <?=$theme_color?>;"><?=$v_code?></td>
                <td class="lbl">তারিখ (Date):</td>
                <td class="val"><?=date('d M, Y', strtotime($voucher['date']))?></td>
            </tr>
            <tr>
                <td class="lbl">অ্যাকাউন্ট / ফান্ড:</td>
                <td class="val"><?=html_escape(!empty($voucher['ac_name']) ? $voucher['ac_name'] : 'School Cash')?></td>
                <td class="lbl">ভাউচার খাত (Head):</td>
                <td class="val"><?=html_escape(!empty($voucher['v_head']) ? $voucher['v_head'] : 'General')?></td>
            </tr>
            <tr>
                <td class="lbl">পরিশোধকারী (Pay By):</td>
                <td class="val"><?=html_escape($payer)?></td>
                <td class="lbl">গ্রহীতা (Received By):</td>
                <td class="val"><?=html_escape($receiver)?></td>
            </tr>
            <tr>
                <td class="lbl">পেমেন্ট মাধ্যম (Pay Via):</td>
                <td class="val" style="color: #007F3D;"><strong><?=html_escape($pay_method)?></strong></td>
                <td class="lbl">রেফারেন্স নং (Ref No):</td>
                <td class="val"><?=!empty($voucher['ref']) ? html_escape($voucher['ref']) : 'N/A'?></td>
            </tr>
        </table>

        <!-- Particulars & Amount Table -->
        <table class="v-particulars-table">
            <thead>
                <tr>
                    <th style="width: 8%;">ক্র. (SL)</th>
                    <th>বিবরণ ও উদ্দেশ্য (Particulars / Description)</th>
                    <th style="width: 25%;">টাকার পরিমাণ (Amount ৳)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: bold;">০১</td>
                    <td style="vertical-align: top;">
                        <strong style="color: #0f172a; font-size: 12px; display: block; margin-bottom: 2px;">
                            <?=html_escape(!empty($voucher['v_head']) ? $voucher['v_head'] : ($is_expense ? 'অফিস খরচ' : 'অফিস জমা'))?>
                        </strong>
                        <div style="color: #334155; line-height: 1.45;">
                            <?=!empty($voucher['description']) ? nl2br(html_escape($voucher['description'])) : 'কোনো অতিরিক্ত মন্তব্য প্রদান করা হয়নি।'?>
                        </div>
                    </td>
                    <td class="amount-cell" style="vertical-align: middle;">
                        ৳<?=number_format($voucher['amount'], 2)?>
                    </td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" style="text-align: right; padding-right: 12px;">
                        সর্বমোট টাকা (Total Payable / Receivable):
                    </td>
                    <td class="amount-cell" style="color: <?=$theme_color?>;">
                        ৳<?=number_format($voucher['amount'], 2)?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Amount In Words -->
        <div class="v-words-box">
            <strong>কথায় (In Words):</strong> 
            <?=dvs_number_to_words_bangla($voucher['amount'])?> 
            <span style="color: #64748b; font-size: 10px;">(<?=dvs_number_to_words_english($voucher['amount'])?>)</span>
        </div>

        <!-- 4 Official Signature Blocks -->
        <div class="v-sign-row">
            <div class="v-sign-col">
                <div class="v-sign-line"></div>
                <div class="v-sign-title">প্রস্তুতকারী</div>
                <div class="v-sign-sub">Prepared By</div>
            </div>
            <div class="v-sign-col">
                <div class="v-sign-line"></div>
                <div class="v-sign-title">হিসাবরক্ষক / নিরীক্ষক</div>
                <div class="v-sign-sub">Checked By</div>
            </div>
            <div class="v-sign-col">
                <div class="v-sign-line"></div>
                <div class="v-sign-title">গ্রহীতা / প্রাপকের স্বাক্ষর</div>
                <div class="v-sign-sub">Received By (Payee)</div>
            </div>
            <div class="v-sign-col">
                <div class="v-sign-line"></div>
                <div class="v-sign-title">অনুমোদনকারী / অধ্যক্ষ</div>
                <div class="v-sign-sub">Approved By (Principal)</div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 15px; border-top: 1px dotted #cbd5e1; padding-top: 4px; font-size: 9.5px; color: #94a3b8;">
            ড্রিম ভ্যালি স্কুল ইআরপি - অফিসিয়াল হিসাব শাখা কর্তৃক কম্পিউটার জেনারেটেড A5 ভাউচার কপি।
        </div>

    </div>

</body>
</html>
