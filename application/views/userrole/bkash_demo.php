<!doctype html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash Payment - <?=html_escape($this->data['global_config']['institute_name'] ?? 'Dream Valley School')?></title>
    <link rel="stylesheet" href="<?=base_url('assets/vendor/bootstrap/css/bootstrap.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/vendor/font-awesome/css/all.min.css')?>">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            font-family: 'Hind Siliguri', 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        .bkash-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(226, 19, 110, 0.15), 0 5px 15px rgba(0, 0, 0, 0.05);
            max-width: 440px;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(226, 19, 110, 0.12);
        }
        .bkash-header {
            background: linear-gradient(135deg, #E2136E 0%, #b80f58 100%);
            color: #ffffff;
            padding: 24px 20px;
            text-align: center;
            position: relative;
        }
        .bkash-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .bkash-amount-box {
            background: #ffffff;
            color: #E2136E;
            padding: 12px 20px;
            border-radius: 10px;
            margin-top: 15px;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }
        .bkash-amount-box .amount {
            font-size: 26px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }
        .bkash-body {
            padding: 28px 24px;
        }
        .invoice-info {
            background: #fdf2f7;
            border-left: 4px solid #E2136E;
            padding: 12px 16px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 22px;
            font-size: 13.5px;
        }
        .form-group label {
            font-weight: 600;
            font-size: 13px;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-control-bkash {
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .form-control-bkash:focus {
            border-color: #E2136E;
            outline: none;
            box-shadow: 0 0 0 3px rgba(226, 19, 110, 0.12);
        }
        .btn-bkash-confirm {
            background: linear-gradient(135deg, #E2136E 0%, #c4105f 100%);
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(226, 19, 110, 0.3);
        }
        .btn-bkash-confirm:hover {
            background: linear-gradient(135deg, #c4105f 0%, #9e0d4c 100%);
            color: #fff;
            transform: translateY(-1px);
        }
        .btn-bkash-cancel {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            display: inline-block;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-bkash-cancel:hover {
            background: #e5e7eb;
            color: #1f2937;
            text-decoration: none;
        }
        .bkash-footer {
            text-align: center;
            padding: 12px 20px 20px;
            font-size: 12px;
            color: #9ca3af;
        }
        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #059669;
            font-size: 12px;
            font-weight: 600;
            margin-top: 14px;
        }
    </style>
</head>
<body>

<div class="bkash-card">
    <div class="bkash-header">
        <div class="bkash-badge"><i class="fas fa-flask"></i> bKash Sandbox / Demo Mode</div>
        <h4 style="margin: 0; font-weight: 700; font-size: 20px; letter-spacing: -0.3px;">bKash পেমেন্ট গেটওয়ে</h4>
        <small style="opacity: 0.9; font-size: 12px;"><?=html_escape($this->data['global_config']['institute_name'] ?? 'Dream Valley School')?></small>
        
        <div class="bkash-amount-box">
            <small style="display: block; font-size: 11px; text-transform: uppercase; font-weight: 600; color: #6b7280;">মোট প্রদেয় ফি</small>
            <span class="amount">৳ <?=number_format(floatval(($params['amount'] ?? 0) + ($params['fine'] ?? 0)), 2)?></span>
        </div>
    </div>

    <div class="bkash-body">
        <div class="invoice-info">
            <div><strong>শিক্ষার্থী:</strong> <?=html_escape($params['student_name'] ?? 'Student')?></div>
            <div><strong>ইনভয়েস নং:</strong> #<?=html_escape($params['invoice_no'] ?? '')?></div>
            <div><strong>পেমেন্ট রেফারেন্স:</strong> Student Fees Deposit</div>
        </div>

        <form action="<?=base_url('feespayment/bkash_callback')?>" method="GET">
            <input type="hidden" name="status" value="success">
            <input type="hidden" name="paymentID" value="DEMO_BKASH_<?=strtoupper(substr(md5(uniqid()), 0, 12))?>">
            
            <div class="form-group mb-3">
                <label><i class="fas fa-mobile-alt text-danger"></i> বিকাশ ওয়ালেট নম্বর (bKash Wallet Number)</label>
                <input type="text" class="form-control form-control-bkash" value="01700000000" placeholder="01XXXXXXXXX" required>
                <small class="text-muted" style="font-size: 11px;">স্যান্ডবক্স টেস্টের জন্য ডেমো নম্বর দেওয়া হয়েছে</small>
            </div>

            <div class="form-group mb-3">
                <label><i class="fas fa-key text-muted"></i> ভেরিফিকেশন ওটিপি (Verification OTP)</label>
                <input type="text" class="form-control form-control-bkash" value="1234" placeholder="Enter 4-digit OTP" required>
            </div>

            <div class="form-group mb-4">
                <label><i class="fas fa-lock text-muted"></i> বিকাশ গোপন পিন (bKash PIN)</label>
                <input type="password" class="form-control form-control-bkash" value="12345" placeholder="Enter 5-digit PIN" required>
            </div>

            <button type="submit" class="btn btn-bkash-confirm">
                <i class="fas fa-check-circle"></i> বিকাশ পেমেন্ট সম্পন্ন করুন (Confirm Payment)
            </button>
            
            <a href="<?=base_url('feespayment/bkash_callback?status=cancel')?>" class="btn-bkash-cancel">
                <i class="fas fa-times-circle"></i> বাতিল করুন (Cancel)
            </a>
        </form>

        <div class="secure-badge">
            <i class="fas fa-shield-alt"></i> 100% সুরক্ষিত এনক্রিপ্টেড পেমেন্ট গেটওয়ে
        </div>
    </div>

    <div class="bkash-footer">
        Powered by <strong>bKash Merchant Payment Gateway</strong> &amp; DVS ERP
    </div>
</div>

</body>
</html>

