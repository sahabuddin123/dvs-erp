<!DOCTYPE html>
<?php
$current_lang = $this->session->userdata('set_lang');
if (empty($current_lang)) {
    $current_lang = get_global_setting('translation');
}
if (empty($current_lang)) {
    $current_lang = 'bengali';
}
$is_bn = ($current_lang === 'bengali');
$is_expense = ($voucher['type'] == 'expense');

$v_type_bn = $is_expense ? 'খরচ ভাউচার (ডেবিট)' : 'জমা ভাউচার (ক্রেডিট)';
$v_type_en = $is_expense ? 'DEBIT VOUCHER' : 'CREDIT VOUCHER';
$v_type_title = $is_bn ? $v_type_bn : $v_type_en;

$v_code = 'DVS-' . ($is_expense ? 'EXP' : 'DEP') . '-' . str_pad($voucher['id'], 5, '0', STR_PAD_LEFT);
$theme_color = $is_expense ? '#b91c1c' : '#007F3D';
$theme_bg = $is_expense ? '#fef2f2' : '#f0fdf4';
$theme_border = $is_expense ? '#fca5a5' : '#86efac';

$payer = !empty($voucher['pay_by']) ? dvs_clean_bilingual($voucher['pay_by'], $is_bn ? 'bengali' : 'english') : ($is_expense ? ($is_bn ? 'ড্রিম ভ্যালি স্কুল (অফিস ফান্ড)' : 'Dream Valley School (Office Fund)') : 'N/A');
$receiver = !empty($voucher['received_by']) ? dvs_clean_bilingual($voucher['received_by'], $is_bn ? 'bengali' : 'english') : ($is_expense ? 'N/A' : ($is_bn ? 'ড্রিম ভ্যালি স্কুল (হিসাব শাখা)' : 'Dream Valley School (Accounts)'));
$pay_method = !empty($voucher['via_name']) ? dvs_clean_bilingual($voucher['via_name'], $is_bn ? 'bengali' : 'english') : (!empty($voucher['pay_via']) ? dvs_clean_bilingual($voucher['pay_via'], $is_bn ? 'bengali' : 'english') : ($is_bn ? 'ক্যাশ / নগদ' : 'Cash'));
$voucher_head_title = !empty($voucher['v_head']) ? dvs_clean_bilingual($voucher['v_head'], $is_bn ? 'bengali' : 'english') : ($is_expense ? ($is_bn ? 'অফিস খরচ' : 'Office Expense') : ($is_bn ? 'অফিস জমা' : 'Office Deposit'));
?>
<html lang="<?=$is_bn ? 'bn' : 'en'?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$v_type_title?> #<?=$v_code?> - <?=$is_bn ? 'ড্রিম ভ্যালি স্কুল' : 'Dream Valley School'?></title>
    <link rel="stylesheet" href="<?=base_url('assets/vendor/bootstrap/css/bootstrap.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/vendor/font-awesome/css/all.min.css')?>">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        <?php if ($is_bn): ?>
        @font-face {
            font-family: 'DVSNumerals';
            src: local('Noto Sans Bengali'), local('Hind Siliguri');
            unicode-range: U+0030-0039, U+09E6-09EF;
        }
        body, table, th, td, h1, h2, h3, p, span, div, strong {
            font-family: 'DVSNumerals', 'Hind Siliguri', 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
        }
        <?php else: ?>
        body, table, th, td, h1, h2, h3, p, span, div, strong {
            font-family: 'Signika', 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
        }
        <?php endif; ?>
        body {
            background-color: #f1f5f9;
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
        .v-school-title {
            font-size: 21px;
            font-weight: 700;
            color: #007F3D;
            margin: 0 0 2px;
            line-height: 1.2;
        }
        .v-school-tagline {
            font-size: 11.5px;
            font-weight: 600;
            color: #047857;
            margin: 0 0 3px;
        }
        .v-school-contact {
            font-size: 10px;
            color: #475569;
            margin: 0;
            line-height: 1.35;
        }

        /* Voucher Badge */
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
            padding: 4px 8px;
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
            margin: 0 auto 5px;
        }
        .v-sign-title {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
        }

        /* Print Specific Styling */
        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
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
                <i class="fas fa-file-invoice-dollar mr-xs"></i> <?=$v_type_title?> #<?=$v_code?>
            </span>
        </div>
        <div>
            <button type="button" class="btn-action-print" onclick="window.print();">
                <i class="fas fa-print mr-xs"></i> <?=translate('print_voucher')?>
            </button>
            <?php if ($is_expense): ?>
                <a href="<?=base_url('accounting/voucher_expense')?>" class="btn-action-sec">
                    <i class="fas fa-list-ul"></i> <?=translate('new_expense')?>
                </a>
            <?php else: ?>
                <a href="<?=base_url('accounting/voucher_deposit')?>" class="btn-action-sec">
                    <i class="fas fa-list-ul"></i> <?=translate('new_deposit')?>
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
                    <?php if ($is_bn): ?>
                        <h1 class="v-school-title">ড্রিম ভ্যালি স্কুল</h1>
                        <p class="v-school-tagline">ইসলামিক কারিকুলাম উইথ তাহফিজুল কুরআন</p>
                        <p class="v-school-contact">
                            খাসেরহাট মেইন রোড, চরবাটা, সুবর্ণচর, নোয়াখালী | মোবাইল: ০১৭১৬-১২২৮৯৯, ০১৭১২-৮৮৬৯৮৮<br>
                            ওয়েবসাইট: dreamvalleyschool.com | ইমেইল: info@dreamvalleyschool.com
                        </p>
                    <?php else: ?>
                        <h1 class="v-school-title">DREAM VALLEY SCHOOL</h1>
                        <p class="v-school-tagline">Islamic Curriculum with Tahfeezul Quran</p>
                        <p class="v-school-contact">
                            Khaserhat Main Road, Charbata, Subarnachar, Noakhali | Mobile: +88 01716 122 899<br>
                            Website: dreamvalleyschool.com | Email: info@dreamvalleyschool.com
                        </p>
                    <?php endif; ?>
                </td>
                <td style="width: 18%; vertical-align: middle; text-align: right;">
                    <div style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 6px; background: #f8fafc; display: inline-block; text-align: right;">
                        <span style="font-size: 10px; color: #64748b; display: block;"><?=translate('voucher_no')?></span>
                        <strong style="font-size: 12.5px; color: <?=$theme_color?>;"><?=$v_code?></strong>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Voucher Badge -->
        <div class="v-type-badge-container">
            <span class="v-type-badge"><?=$v_type_title?></span>
        </div>

        <!-- Voucher Metadata Grid -->
        <table class="v-info-table">
            <tr>
                <td class="lbl"><?=translate('voucher_no')?>:</td>
                <td class="val" style="color: <?=$theme_color?>;"><?=$v_code?></td>
                <td class="lbl"><?=translate('date')?>:</td>
                <td class="val"><?=date('d M, Y', strtotime($voucher['date']))?></td>
            </tr>
            <tr>
                <td class="lbl"><?=translate('account_fund')?>:</td>
                <td class="val"><?=html_escape(!empty($voucher['ac_name']) ? dvs_clean_bilingual($voucher['ac_name'], $is_bn ? 'bengali' : 'english') : ($is_bn ? 'অফিস ক্যাশ' : 'School Cash'))?></td>
                <td class="lbl"><?=translate('voucher_head')?>:</td>
                <td class="val"><?=html_escape($voucher_head_title)?></td>
            </tr>
            <tr>
                <td class="lbl"><?=translate('pay_by')?>:</td>
                <td class="val"><?=html_escape($payer)?></td>
                <td class="lbl"><?=translate('received_by')?>:</td>
                <td class="val"><?=html_escape($receiver)?></td>
            </tr>
            <tr>
                <td class="lbl"><?=translate('pay_via')?>:</td>
                <td class="val" style="color: #007F3D;"><strong><?=html_escape($pay_method)?></strong></td>
                <td class="lbl"><?=translate('ref_no')?>:</td>
                <td class="val"><?=!empty($voucher['ref']) ? html_escape($voucher['ref']) : ($is_bn ? 'প্রযোজ্য নয়' : 'N/A')?></td>
            </tr>
        </table>

        <!-- Particulars & Amount Table -->
        <table class="v-particulars-table">
            <thead>
                <tr>
                    <th style="width: 8%;"><?=translate('sl')?></th>
                    <th><?=translate('particulars_description')?></th>
                    <th style="width: 25%;"><?=translate('amount_bdt')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; vertical-align: top; font-weight: bold;"><?=$is_bn ? '০১' : '01'?></td>
                    <td style="vertical-align: top;">
                        <strong style="color: #0f172a; font-size: 12px; display: block; margin-bottom: 2px;">
                            <?=html_escape($voucher_head_title)?>
                        </strong>
                        <div style="color: #334155; line-height: 1.45;">
                            <?=!empty($voucher['description']) ? nl2br(html_escape($voucher['description'])) : ($is_bn ? 'কোনো অতিরিক্ত মন্তব্য নেই।' : 'No additional description.')?>
                        </div>
                    </td>
                    <td class="amount-cell" style="vertical-align: middle;">
                        ৳<?=number_format($voucher['amount'], 2)?>
                    </td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" style="text-align: right; padding-right: 12px;">
                        <?=translate('total_payable_receivable')?>:
                    </td>
                    <td class="amount-cell" style="color: <?=$theme_color?>;">
                        ৳<?=number_format($voucher['amount'], 2)?>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Amount In Words -->
        <div class="v-words-box">
            <strong><?=translate('in_words')?>:</strong> 
            <?php if ($is_bn): ?>
                <?=dvs_number_to_words_bangla($voucher['amount'])?>
            <?php else: ?>
                <?=dvs_number_to_words_english($voucher['amount'])?>
            <?php endif; ?>
        </div>

        <!-- 4 Official Signature Blocks -->
        <div class="v-sign-row">
            <div class="v-sign-col">
                <div class="v-sign-line"></div>
                <div class="v-sign-title"><?=translate('prepared_by')?></div>
            </div>
            <div class="v-sign-col">
                <div class="v-sign-line"></div>
                <div class="v-sign-title"><?=translate('checked_by')?></div>
            </div>
            <div class="v-sign-col">
                <div class="v-sign-line"></div>
                <div class="v-sign-title"><?=translate('received_by_signature')?></div>
            </div>
            <div class="v-sign-col">
                <div class="v-sign-line"></div>
                <div class="v-sign-title"><?=translate('approved_by')?></div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 15px; border-top: 1px dotted #cbd5e1; padding-top: 4px; font-size: 9.5px; color: #94a3b8;">
            <?=translate('computer_generated_voucher_note')?>
        </div>

    </div>

</body>
</html>
