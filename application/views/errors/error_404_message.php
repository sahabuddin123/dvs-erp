<!doctype html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>404 - পৃষ্ঠাটি খুঁজে পাওয়া যায়নি | Dream Valley School</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #007F3D;
            --primary-light: #ecfdf5;
            --primary-dark: #005f2e;
            --dark: #111827;
            --gray: #6b7280;
            --light: #f9fafb;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', 'Hind Siliguri', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #f9fafb 100%);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px -15px rgba(0, 127, 61, 0.12), 0 0 1px 1px rgba(0, 0, 0, 0.05);
            max-width: 520px;
            width: 100%;
            padding: 48px 36px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #007F3D, #10b981, #059669);
        }
        .icon-wrapper {
            width: 110px;
            height: 110px;
            margin: 0 auto 24px;
            background: #ecfdf5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 48px;
            box-shadow: 0 0 0 12px rgba(0, 127, 61, 0.08);
            animation: pulse 2.5s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 10px rgba(0, 127, 61, 0.08); }
            50% { transform: scale(1.04); box-shadow: 0 0 0 18px rgba(0, 127, 61, 0.04); }
        }
        .error-code {
            font-size: 72px;
            font-weight: 800;
            letter-spacing: -2px;
            line-height: 1;
            background: linear-gradient(135deg, #007F3D 0%, #047857 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }
        .error-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }
        .error-desc {
            font-size: 14px;
            color: var(--gray);
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s ease;
            border: none;
        }
        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 127, 61, 0.25);
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 127, 61, 0.35);
            color: #ffffff;
        }
        .btn-outline {
            background-color: transparent;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        .btn-outline:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
            color: #111827;
        }
        .footer-note {
            margin-top: 30px;
            font-size: 11.5px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="icon-wrapper">
            <i class="fas fa-compass"></i>
        </div>
        <div class="error-code">404</div>
        <h1 class="error-title">পৃষ্ঠাটি খুঁজে পাওয়া যায়নি</h1>
        <p class="error-desc">
            আপনি যে পৃষ্ঠাটি অনুসন্ধান করছেন তা সরানো হয়েছে, নাম পরিবর্তন করা হয়েছে অথবা সাময়িকভাবে অনুপলব্ধ রয়েছে।
            <br><span style="font-size: 12px; color: #9ca3af;">(The requested page was not found or has been moved.)</span>
        </p>
        <div class="btn-group">
            <button class="btn btn-outline" onclick="history.back();">
                <i class="fas fa-arrow-left"></i> পূর্ববর্তী পেজে ফিরে যান
            </button>
            <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary">
                <i class="fas fa-home"></i> ড্যাশবোর্ডে যান
            </a>
        </div>
        <div class="footer-note">
            Dream Valley School ERP &bull; নিরাপদ ও নির্ভরযোগ্য
        </div>
    </div>
</body>
</html>
