<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// return translation
function translate($word = '')
{
    if (empty($word)) {
        return '';
    }
    $CI = &get_instance();
    if ($CI->session->has_userdata('set_lang')) {
        $set_lang = $CI->session->userdata('set_lang');
    } else {
        $set_lang = get_global_setting('translation');
    }

    if ($set_lang == '') {
        $set_lang = 'bengali';
    }

    $safe_word = $CI->db->escape_str($word);
    $safe_lang = $CI->db->escape_str($set_lang);
    $sql = "SELECT `english`,`" . $safe_lang . "` FROM `languages` WHERE `word` = '$safe_word'";
    $query = $CI->db->query($sql);
    if ($query && $query->num_rows() > 0) {
        $row = $query->row();
        $val = ($row && isset($row->$set_lang) && $row->$set_lang !== null) ? trim((string)$row->$set_lang) : '';
        $fallback = dvs_language_fallback($word, $set_lang);

        if ($val !== '') {
            if (dvs_is_bad_translation($val, $word, $set_lang)) {
                return ($fallback !== null) ? $fallback : dvs_clean_bilingual($val, $set_lang);
            }
            return dvs_clean_bilingual($val, $set_lang);
        } else {
            if ($fallback !== null) {
                return $fallback;
            }
            if ($set_lang === 'english' && !empty($row->english)) {
                return dvs_clean_bilingual($row->english, 'english');
            }
            return ucwords(str_replace('_', ' ', $word));
        }
    } else {
        $fallback_en = dvs_language_fallback($word, 'english');
        $fallback_bn = dvs_language_fallback($word, 'bengali');
        $english_label = ($fallback_en !== null) ? $fallback_en : ucwords(str_replace('_', ' ', $word));
        $bengali_label = ($fallback_bn !== null) ? $fallback_bn : $english_label;

        // Non-blocking insert that tolerates strict mode / missing language columns
        try {
            $arrayData = array(
                'word' => $word,
                'english' => $english_label,
                'bengali' => $bengali_label,
            );
            @$CI->db->insert('languages', $arrayData);
        } catch (Exception $e) {}

        if ($set_lang === 'bengali') {
            return $bengali_label;
        }
        return $english_label;
    }
}

function dvs_clean_bilingual($text, $lang = 'bengali')
{
    $text = trim((string)$text);
    if ($text === '') {
        return '';
    }
    $lang = ($lang === 'en' || $lang === 'english') ? 'english' : 'bengali';
    $has_bn = (bool)preg_match('/[\x{0980}-\x{09FF}]/u', $text);
    $has_en = (bool)preg_match('/[a-zA-Z]/', $text);

    // If text does not mix scripts, return as is
    if (!$has_bn || !$has_en) {
        return $text;
    }

    // 1. Check outer parenthesis pattern: "Text (Text)" e.g. "Pre-Play (প্রি-প্লে)", "ভর্তি সেটিংস ও ফি (Admission Settings)"
    if (preg_match('/^([^\(]+)\((.+)\)$/u', $text, $m)) {
        $part1 = trim($m[1]);
        $part2 = trim($m[2]);
        $p1_has_bn = (bool)preg_match('/[\x{0980}-\x{09FF}]/u', $part1);
        $p2_has_bn = (bool)preg_match('/[\x{0980}-\x{09FF}]/u', $part2);
        $p1_has_en = (bool)preg_match('/[a-zA-Z]/', $part1);
        $p2_has_en = (bool)preg_match('/[a-zA-Z]/', $part2);

        if ($lang === 'bengali') {
            if ($p1_has_bn && !$p1_has_en) return $part1;
            if ($p2_has_bn && !$p2_has_en) return $part2;
            if ($p1_has_bn) return $part1;
            if ($p2_has_bn) return $part2;
        } else {
            if ($p1_has_en && !$p1_has_bn) return $part1;
            if ($p2_has_en && !$p2_has_bn) return $part2;
            if ($p1_has_en) return $part1;
            if ($p2_has_en) return $part2;
        }
    }

    // 2. Check slash pattern: "English / বাংলা" or "বাংলা / English"
    if (strpos($text, '/') !== false) {
        $parts = explode('/', $text);
        if ($lang === 'bengali') {
            $bn_parts = [];
            foreach ($parts as $p) {
                $p = trim($p);
                if (preg_match('/[\x{0980}-\x{09FF}]/u', $p)) {
                    $bn_parts[] = $p;
                }
            }
            if (!empty($bn_parts)) return implode(' / ', $bn_parts);
        } else {
            $en_parts = [];
            foreach ($parts as $p) {
                $p = trim($p);
                if (preg_match('/[a-zA-Z]/', $p) && !preg_match('/[\x{0980}-\x{09FF}]/u', $p)) {
                    $en_parts[] = $p;
                }
            }
            if (!empty($en_parts)) return implode(' / ', $en_parts);
        }
    }

    // 3. Fallback: script character filter
    if ($lang === 'bengali') {
        $cleaned = preg_replace('/[a-zA-Z]+/u', '', $text);
        $cleaned = preg_replace('/\s*[\(\)\/]\s*/u', ' ', $cleaned);
        $cleaned = trim($cleaned, " /()-:;,");
        return !empty($cleaned) ? $cleaned : $text;
    } else {
        $cleaned = preg_replace('/[\x{0980}-\x{09FF}]+/u', '', $text);
        $cleaned = preg_replace('/\s*[\(\)\/]\s*/u', ' ', $cleaned);
        $cleaned = trim($cleaned, " /()-:;,");
        return !empty($cleaned) ? $cleaned : $text;
    }
}

function dvs_is_bad_translation($val, $word, $lang = 'bengali')
{
    $val = trim((string)$val);
    if ($val === '') {
        return true;
    }
    if (strpos($val, '??') !== false) {
        return true;
    }

    $has_bn = (bool)preg_match('/[\x{0980}-\x{09FF}]/u', $val);
    $has_en = (bool)preg_match('/[a-zA-Z]/', $val);

    // If both scripts are present with slash or parenthesis, it's a legacy bilingual string
    if ($has_bn && $has_en && (strpos($val, '/') !== false || strpos($val, '(') !== false)) {
        return true;
    }

    // If English is selected but string has Bengali characters
    if ($lang === 'english' && $has_bn) {
        return true;
    }

    $bad_samples = array(
        'ছাঁকনি',
        'স্থাপন',
        'চাদর',
        'ট্যাবলেট শীট',
        'সূচনাফলক',
        'মূল যোগ করুন',
        'খোলার ভারসাম্য',
        'ভারসাম্য',
        'গ্রাউন্ড নির্বাচন করুন',
        'নির্ধারণ করা',
        'নির্বাচন করা',
        'ক্লাস প্রথম নির্বাচন করুন',
        'সংযুক্তি বই',
        'ছাত্র বিস্তারিত',
        'ছাত্র অ্যাকাউন্টিং',
        'অফিস একাউন্টিং',
        'অফিস অ্যাকাউন্টিং',
        'রক্ষণাবেক্ষণ',
        'মাতাপিতা',
        'পিতামাতার তালিকা',
        'একাধিক আমদানি',
        'পারিশ্রমিক ফি চালান',
        'বেতনের',
        'তারাতারি দেখা',
        'উপস্থিতি পরিদর্শন',
        'আমার বার্ষিক উপস্থিতি পরিদর্শন',
    );
    if (in_array($val, $bad_samples)) {
        return true;
    }
    if ($word === 'section' && ($val === 'অধ্যায়' || $val === 'অধ্যায়')) {
        return true;
    }
    if ($word === 'type' && $val === 'আদর্শ') {
        return true;
    }
    if ($word === 'action' && $val === 'কর্ম') {
        return true;
    }
    if ($word === 'student' && $val === 'ছাত্র') {
        return true;
    }
    if ($lang === 'bengali' && (strcasecmp($val, $word) === 0 || strcasecmp($val, str_replace('_', ' ', $word)) === 0)) {
        return true;
    }
    return false;
}

function dvs_language_fallback($word, $lang = 'bengali')
{
    static $en_dict = array(
        // Core & Navigation
        'dashboard' => 'Dashboard',
        'branch' => 'Branch',
        'all_branches' => 'All Branches',
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'enabled' => 'Enabled',
        'disabled' => 'Disabled',
        'keep_enabled' => 'Keep Enabled',
        'disable_now' => 'Disable',
        'save' => 'Save',
        'filter' => 'Filter',
        'reset' => 'Reset',
        'action' => 'Action',
        'type' => 'Type',
        'date' => 'Date',
        'method' => 'Method',
        'user' => 'User',
        'role' => 'Role',
        'class' => 'Class',
        'section' => 'Section',
        'all_select' => 'All',
        'select' => 'Select',
        'select_class_first' => 'Select Class First',
        'settings' => 'Settings',
        'reports' => 'Reports',
        'templates' => 'Templates',
        'information_has_been_updated_successfully' => 'Information has been updated successfully',

        // Accounting
        'copy_old_lessons' => 'Copy Old Lessons',
        'manage_lesson_plan' => 'Manage Lesson Plan',
        'manage_syllabus_status' => 'Manage Syllabus Status',
        'lesson' => 'Lesson',
        'topic' => 'Topic',
        'office_accounting' => 'Office Accounting',
        'account' => 'Account',
        'new_deposit' => 'New Deposit',
        'new_expense' => 'New Expense',
        'all_transactions' => 'All Transactions',
        'voucher_head' => 'Voucher Head',
        'pay_by' => 'Pay By',
        'received_by' => 'Received By',
        'payer_name' => 'Payer Name',
        'receiver_name' => 'Receiver Name',
        'print_voucher' => 'Print Voucher',
        'debit_voucher' => 'Debit Voucher',
        'credit_voucher' => 'Credit Voucher',
        'voucher_no' => 'Voucher No',
        'account_fund' => 'Account / Fund',
        'particulars_description' => 'Particulars / Description',
        'amount_bdt' => 'Amount (৳)',
        'total_payable_receivable' => 'Total Amount',
        'in_words' => 'In Words',
        'prepared_by' => 'Prepared By',
        'checked_by' => 'Checked By',
        'received_by_signature' => 'Received By',
        'approved_by' => 'Approved By',
        'computer_generated_voucher_note' => 'Dream Valley School ERP - Official computer-generated voucher copy.',

        // Admission Settings
        'admission_settings' => 'Admission Settings',
        'draft_admissions' => 'Draft Admissions',
        'draft_admission' => 'Draft Admission',
        'save_as_draft' => 'Save as Draft',
        'resume_admission' => 'Resume Admission',
        'draft_saved_successfully' => 'Admission draft saved successfully',
        'draft_deleted_successfully' => 'Admission draft deleted successfully',
        'last_saved_step' => 'Last Completed Step',
        'draft_list' => 'Draft List',
        'clear_draft' => 'Delete Draft',
        'admission_settings_fee' => 'Admission Fee & Settings',
        'default_admission_fee' => 'Default Admission Fee',
        'default_admission_fee_help' => 'This initial fee will be displayed in Step 5 of the student admission form.',
        'minimum_paid_required' => 'Minimum Paid Required',
        'minimum_paid_required_help' => 'Minimum payment required to confirm admission. Less than this will keep the application in draft status.',
        'allow_100_percent_free_admission' => 'Allow Free Admission',
        'allow_free_admission_help_yes' => 'Admin can grant free admission under special consideration',
        'allow_free_admission_help_no' => 'Minimum fee payment is mandatory',
        'allow_free_admission_help_note' => 'When enabled, the admission form will have an option for free admission under special consideration.',
        'default_payment_method' => 'Default Payment Method',
        'terms_conditions_undertaking' => 'Terms, Conditions & Undertaking',
        'terms_conditions_help' => 'These terms and undertaking will be automatically printed at the bottom of the student admission form and fee receipts.',

        // Behaviour Records
        'behaviour_records' => 'Behaviour Records',
        'assign_behaviour' => 'Assign Behaviour',
        'incidents' => 'Incidents',
        'incident' => 'Incident',
        'incident_title' => 'Incident Title',
        'point' => 'Point',
        'points' => 'Points',
        'positive' => 'Positive',
        'negative' => 'Negative',
        'predefined_incident' => 'Predefined Incident',
        'custom_incident' => 'Custom Incident',
        'comment_details' => 'Detailed Comment',
        'student_name' => 'Student Name',
        'view_report' => 'View Report',

        // Thermal Print
        'thermal_print' => 'Thermal Print',
        'thermal_print_settings' => 'Thermal Print Settings',
        'thermal_print_configuration' => 'Thermal Print Configuration',
        'printer_paper_size' => 'Printer Paper Size',
        'standard_pos' => '3-Inch Standard POS (80mm)',
        'mini_pos' => '2-Inch Mini POS (58mm)',
        'printer_paper_size_help' => 'Select paper roll width for your thermal printer (80mm standard recommended).',
        'receipt_options' => 'Receipt Options',
        'show_school_logo' => 'Show School Logo',
        'show_barcode_invoice_code' => 'Show Barcode / Invoice Code',
        'auto_trigger_print_dialog' => 'Auto-trigger Print Dialog',
        'header_title' => 'Header Title',
        'header_subtitle' => 'Subtitle',
        'school_address' => 'School Address',
        'contact_info' => 'Contact / Phone',
        'footer_message_terms' => 'Footer Message / Terms',
        'test_print' => 'Test Print',
        'thermal_receipt_live_preview' => 'Thermal Receipt Preview',
        'receipt_voucher' => 'Payment Receipt',
        'receipt_no' => 'Receipt No',

        // WhatsApp Messaging
        'whatsapp_messaging' => 'WhatsApp Messaging',
        'compose_whatsapp_message' => 'Compose WhatsApp Message',
        'send_message' => 'Send Message',
        'recipient_type' => 'Recipient Type',
        'recipient' => 'Recipient',
        'recipients' => 'Recipients',
        'recipient_name' => 'Recipient Name',
        'custom_numbers' => 'Custom Numbers',
        'staff_role' => 'Staff Role',
        'mobile_no' => 'Mobile No',
        'mobile_numbers' => 'Mobile Numbers',
        'mobile_numbers_help' => 'Enter comma separated phone numbers (e.g. 01716122899, 01800000000)',
        'message_template' => 'Message Template',
        'select_message_template' => '-- Select Message Template --',
        'insert_tags' => 'Insert Tags',
        'tag_click_help' => 'Click any tag to append it into your message; tags are replaced automatically upon dispatch.',
        'message_body' => 'Message Body',
        'message_body_placeholder' => 'Write your WhatsApp message here...',
        'character_count' => 'Character Count',
        'start_whatsapp_dispatch' => 'Start WhatsApp Dispatch',
        'whatsapp_live_preview' => 'WhatsApp Live Preview',
        'whatsapp_preview_placeholder' => 'Your message preview will appear here...',
        'whatsapp_templates' => 'WhatsApp Message Templates',
        'whatsapp_templates_list' => 'WhatsApp Templates List',
        'add_new_template' => 'Add New Template',
        'edit_template' => 'Edit Template',
        'template_description' => 'Template Description',
        'whatsapp_reports' => 'WhatsApp Message Reports',
        'whatsapp_dispatch_logs' => 'WhatsApp Dispatch Logs',
        'all_roles' => '-- All Roles --',
        'gateway_medium' => 'Gateway Medium',
        'sent' => 'Sent',
        'failed' => 'Failed',
        'ready' => 'Ready',
        'whatsapp_settings' => 'WhatsApp Settings',
        'gateway_type' => 'Gateway Type',
        'web_whatsapp_desc' => 'Web WhatsApp / Click to Chat (Free via Browser / App)',
        'custom_api_desc' => 'Custom WhatsApp API Gateway (Automated API)',
        'gateway_help_text' => 'Using "Web WhatsApp" mode is 100% free with no 3rd-party subscription, opening WhatsApp Web or App directly.',
        'country_code' => 'Country Code',
        'country_code_help' => 'Default country dial code (880 for Bangladesh). Local numbers are auto-formatted.',
        'message_header_text' => 'Message Header Text',
        'message_footer_text' => 'Message Footer Text',
        'whatsapp_queue' => 'WhatsApp Dispatch Queue',
        'send_via_whatsapp' => 'Send via WhatsApp',
        'queue_instructions' => 'Recipients are queued below. Clicking "Send via WhatsApp" opens WhatsApp with prefilled message.',
        'message_preview' => 'Message Preview',
        'view_message_reports' => 'View Message Reports',
        'api_endpoint_url' => 'API Endpoint URL',
        'api_key_token' => 'API Key / Token',
        'instance_id' => 'Instance ID',
        'customer_user' => 'Valued User',
        'please_enter_message_text' => 'Please enter message text',

        // Two Factor Authentication
        'two_factor_auth' => 'Two-Factor Authentication',
        'two_factor_authentication' => 'Two-Factor Authentication',
        'two_factor_auth_desc' => 'Two-Factor Security Verification',
        'enter_otp_code' => 'Enter 6-digit Security Code',
        'verify_and_login' => 'Verify & Login',
        'resend_code' => 'Resend Code',
        'back_to_login' => 'Back to Login',
        'time_remaining' => 'Time Remaining',
        'code_expired' => 'Code Expired',
        'resend_cooldown_wait' => 'Please wait before resending',
        'seconds' => 'seconds',
        'security_code_sent_email' => 'A 6-digit security code has been sent to your registered email %s.',
        'security_code_sent_sms' => 'A 6-digit security code has been sent to your mobile %s.',
        'security_code_sent_both' => 'A security code has been sent to your email %s and mobile %s.',
        'please_enter_6_digit_security_code' => 'Please enter the 6-digit security code',
        'new_security_code_sent' => 'New security code sent successfully!',
        'please_wait_resend_cooldown' => 'Please wait %s more seconds and try again.',
        'master_bypass_pin' => 'Master Bypass PIN',
        'master_bypass_pin_help' => 'Emergency bypass PIN for Super Admin if mail or SMS gateway is offline.',
        'otp_expiry_time' => 'OTP Expiry Time',
        'minutes' => 'Minutes',
        'otp_expiry_help' => 'Validity period for sent security codes (default: 10 minutes).',
        'enforced_roles' => 'Enforced User Roles',
        'select_all' => 'Select All',
        'deselect_all' => 'Deselect All',
        'enforced_roles_help' => 'Selected roles will be required to verify 2FA security OTP during login.',
        'verification_method' => 'Verification Method',
        'email_otp' => 'Email OTP',
        'sms_otp' => 'SMS OTP',
        'both_email_sms' => 'Both Email & SMS',
        'verified' => 'Verified',
        'expired' => 'Expired',
        'pending' => 'Pending',
        'sent_to' => 'Sent To',
        'security_code' => 'Security Code',
        'ip_address' => 'IP Address',
        'user_agent' => 'Browser / User Agent',
        'date_time' => 'Date & Time',
        'activity_logs' => 'Activity Logs',
        'account_security_profile' => 'Account Security Profile',
        'my_account_security' => 'My Account Security',
        'tfa_security_status' => '2FA Security Status',
        'how_it_works' => 'How it works?',
        'how_it_works_2fa_desc' => 'When you log in with your username and password, a 6-digit OTP code is sent to your registered email or phone. Entering that code allows you to securely access your account.',
        'two_fa_account_desc' => 'Keep two-factor verification enabled to prevent unauthorized access to your account.',
        'no_information_available' => 'No information available',

        // Seat Plan
        'seat_plan' => 'Seat Plan',
        'seat_allocation' => 'Seat Allocation',
        'seat_plan_allocation' => 'Seat Plan Allocation',
        'generate_seat_plan' => 'Generate Seat Plan',
        'print_seat_cards' => 'Print Seat Cards',
        'seat_card' => 'Seat Card',
        'room_attendance_sheet' => 'Room Attendance Sheet',
        'hall_wise_seat_plan' => 'Hall-Wise Seat Plan',
        'allocation_strategy' => 'Allocation Strategy',
        'sequential_roll' => 'Sequential by Roll',
        'alternate_classes' => 'Alternating by Class (Anti-Cheating)',
        'alternate_sections' => 'Alternating by Section',
        'random_shuffle' => 'Random Shuffle (Anti-Cheating)',
        'seats_per_bench' => 'Seats per Bench',
        'bench_prefix' => 'Bench Prefix',
        'starting_seat' => 'Starting Seat No',
        'clear_seat_plan' => 'Clear Seat Plan',
        'clear_plan_confirm' => 'Are you sure you want to clear the seat plan for this exam?',
        'seat_plan_generated_successfully' => 'Seat plan has been generated successfully',
        'seat_plan_cleared_successfully' => 'Seat plan has been cleared successfully',
        'allocated_students' => 'Allocated Students',
        'unallocated_students' => 'Unallocated Students',
        'total_halls' => 'Total Halls',
        'total_capacity' => 'Total Capacity',
        'available_seats' => 'Available Seats',
        'invigilator_signature' => "Invigilator's Signature",
        'room_supervisor' => 'Room Supervisor',
        'additional_sheet_no' => 'Additional Sheet No',
        'script_no' => 'Main Script No',
        'student_signature' => "Student's Signature",
        'card_per_page' => 'Cards Per Page',
        'bench_no' => 'Bench No',
        'seat_no' => 'Seat No',

        // 2FA Security Enhanced
        'two_factor_authentication_settings' => 'Two Factor Authentication Settings',
        '2fa_show_remember_browser' => '2fa Show Remember Browser',
        '2fa_cookie_expiry' => '2fa Cookie Expiry',
        'email_instruction' => 'Email Instruction',
        'app_instruction' => 'App Instruction',
        'my_2fa_setup' => 'My 2FA Setup',
        'saved_login' => 'Saved Login',
        'two_factor_authentication_app' => 'Two Factor Authentication (APP)',
        'two_factor_authentication_email' => 'Two Factor Authentication (Email)',
        'you_have_two_factor_authentication_enabled' => 'You have two factor authentication enabled.',
        'you_have_two_factor_authentication_disabled' => 'You have two factor authentication disabled.',
        'scan_qr_code_with_authenticator_app' => 'Scan QR code with Google, Microsoft or Authy Authenticator app.',
        'secret_key' => 'Secret Key',
        'secret_key_copied_to_clipboard' => 'Secret key copied to clipboard!',
        'disable_two_factor_authentication' => 'Disable Two Factor Authentication',
        'enable_two_factor_authentication' => 'Enable Two Factor Authentication',
        '2fa_code_will_be_delivered_to_this_email' => '2FA verification code will be sent to this email address.',
        'email_authentication_is_currently_disabled' => 'Email authentication is currently disabled.',
        'enter_verification_code_from_your_authenticator_app' => 'Enter verification code from your Authenticator APP',
        'enter_verification_code_sent_to_your_email' => 'Enter verification code sent to your Email',
        'login_saved_browser_list' => 'Login Saved Browser List',
        'saved_browser_removed_successfully' => 'Saved browser removed successfully',
        'no_saved_browsers_found' => 'No saved browsers found for your account.',
        'login_date_time' => 'Login Date Time',
        'authentication_code' => 'Authentication Code',
        'do_not_ask_again_on_this_browser' => 'Do Not Ask Again On This Browser.',
        'download_google_authenticator_app_in_your_mobile_device' => 'Download Google Authenticator App in your mobile device.',
        'a_unique_code_has_been_sent_to_your_email_address' => 'A unique code has been sent to your email address. Please check your inbox for the 2FA verification code.',
        'authenticator_app_2fa_enabled_successfully' => 'Authenticator App 2FA enabled successfully.',
        'authenticator_app_2fa_disabled_successfully' => 'Authenticator App 2FA disabled successfully.',
        'email_2fa_enabled_successfully' => 'Email 2FA enabled successfully.',
        'email_2fa_disabled_successfully' => 'Email 2FA disabled successfully.',
        'invalid_verification_code_from_authenticator_app' => 'Invalid verification code from Authenticator App. Please check your app.',
        'invalid_or_expired_email_verification_code' => 'Invalid or expired email verification code. Please request a new code.',

        // School Subscription (SaaS)
        'school_subscription' => 'School Subscription',
        'subscription' => 'Subscription',
        'pending_request' => 'Pending Request',
        'plan' => 'Plan',
        'custom_domain' => 'Custom Domain',
        'transactions' => 'Transactions',
        'package_list' => 'Package List',
        'add_package' => 'Add Package',
        'edit_package' => 'Edit Package',
        'school_list' => 'School List',
        'add_school' => 'Add School',
        'student_limit' => 'Student Limit',
        'parents_limit' => 'Parents Limit',
        'staff_limit' => 'Staff Limit',
        'teacher_limit' => 'Teacher Limit',
        'subscription_period' => 'Subscription Period',
        'module_enabled' => 'Module Enabled?',
        'free_trial' => 'Free Trial',
        'recommended' => 'Recommended',
        'period_type' => 'Period Type',
        'show_on_website' => 'Show on Website',
        'show_hide_package_on_website' => 'Show / Hide Package On Website',
        'automatic_subscription_approval' => 'Automatic Subscription Request Approval',
        'offline_payments' => 'Offline Payments',
        'google_recaptcha' => 'Google Recaptcha (School Register)',
        'terms_and_conditions' => 'Terms & Conditions',
        'pwa_enable' => 'Progressive Web Apps (PWA)',
        'slider_overlay_image' => 'Slider Overlay Image',
        'overlay_image_enable_disable' => 'Slider Overlay Image Enable/Disable',
        'saas_email_test' => 'SaaS Email Test',
        'paytm' => 'Paytm',
        'bkash' => 'bKash',
        'nagad' => 'Nagad',
        'dns_instruction' => 'DNS Instructions',
        'upgrade_plan' => 'Upgrade Plan',
        'renew_subscription' => 'Renew Subscription',
        'subscription_payment_success' => 'Subscription Payment Successful',
        'subscription_transactions_history' => 'Subscription Transactions History',
        'seo_title' => 'SEO Title',
        'meta_keyword' => 'Meta Keyword',
        'meta_description' => 'Meta Description',
        'google_analytics' => 'Google Analytics',
        'parent_count_fix' => 'Parent Wrong Count in Front-end',
        'lifetime' => 'Lifetime',
        'days' => 'Days',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'pending' => 'Pending',
        'last_upgrade' => 'Last Upgrade',
        'start_date' => 'Start Date',
        'expiry_date' => 'Expiry Date',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'reject_reason' => 'Reject Reason',
        'domain_request' => 'Domain Request',
        'request_domain' => 'Request Custom Domain',
        'cname_record' => 'CNAME Record',
        'a_record' => 'A Record',

        // Database Backup Module
        'database_backup' => 'Database Backup',
        'database_list' => 'Backup List',
        'restore_database' => 'Restore Database',
        'tables_and_health' => 'Tables & Health',
        'automated_backup' => 'Automated Backup',
        'database_size' => 'Database Size',
        'total_tables' => 'Total Tables',
        'total_records' => 'Total Records',
        'stored_backups' => 'Stored Backups',
        'create_backup' => 'Create Backup',
        'download_now' => 'Direct Download',
        'prune_old_backups' => 'Prune Old Backups',
        'backup_file' => 'Backup File',
        'file_size' => 'File Size',
        'date_and_time' => 'Date & Time',
        'relative_time' => 'Age',
        'create_first_backup' => 'Create Your First Backup',
        'no_backups_found' => 'No backups found yet',
        'backup_restore_warning' => 'Warning: Restoring a database backup will overwrite existing database tables and records. Ensure you download a current backup before proceeding.',
        'please_create_a_backup_before_restoring' => 'Please create a current backup before restoring to prevent accidental data loss.',
        'restoring_database' => 'Restoring Database',
        'restore_now' => 'Upload & Restore Now',
        'supported_formats' => 'Supported Formats',
        'max_size' => 'Maximum File Size',
        'database_name' => 'Database Name',
        'storage_engine' => 'Storage Engine',
        'charset' => 'Character Set',
        'collation' => 'Collation',
        'optimize_all_tables' => 'Optimize All Tables',
        'table_name' => 'Table Name',
        'engine' => 'Engine',
        'records' => 'Records',
        'data_size' => 'Data Size',
        'index_size' => 'Index Size',
        'overhead' => 'Overhead',
        'optimize' => 'Optimize',
        'automated_database_backup_guide' => 'Automated Database Backup Setup',
        'cron_backup_description' => 'You can configure an automated daily or weekly backup cron job on your server (cPanel, aaPanel, or Linux crontab) using either CLI or Web URL.',
        'recommended_cli_cron_command' => 'Recommended CLI Command (Crontab)',
        'cli_cron_help' => 'Run every night at 2:00 AM without HTTP request timeouts',
        'runs_every_night_at_2am' => 'Scheduled to execute at 2:00 AM every day.',
        'web_url_cron_alternative' => 'Web URL Cron Alternative (cURL / Wget)',
        'web_cron_help' => 'Trigger automated backup via HTTP GET request using secret token',
        'security_and_retention' => 'Security & Retention Policy',
        'retention_policy' => 'Retention Policy',
        'backups_older_than_30_days_auto_pruned' => 'Backups older than 30 days are automatically pruned during cron execution.',
        'cron_token_verification_required' => 'Secret token verification is strictly required for remote cron execution.',
        'backups_compressed_zip_in_secure_dir' => 'Backups are compressed into secure ZIP archives stored in non-public directory.',
        'confirm_database_restore' => 'Confirm Database Restore',
        'selected_backup' => 'Selected Backup',
        'proceed_restore' => 'Proceed Restore',
        'to_restore_from_file_please_use_restore_tab' => 'To restore from an existing backup or uploaded dump, use the Restore Database tab.',
        'old_backups_pruned' => 'old backups were successfully removed.',
        'tables_optimized_successfully' => 'All database tables have been optimized successfully.',
        'database_backup_completed' => 'Database backup created successfully.',
        'database_backup_failed' => 'Failed to create database backup.',
        'backup_restore_completed' => 'Database restored successfully.',
        'backup_restore_failed' => 'Database restoration failed.',
        'backup_deleted_successfully' => 'Backup file has been deleted successfully.',
    );

    static $bn_dict = array(
        // Core
        'save' => 'সংরক্ষণ করুন',
        'active' => 'চালু',
        'inactive' => 'বন্ধ',
        'filter' => 'ফিল্টার',
        'status' => 'স্ট্যাটাস',

        // Thermal Print
        'thermal_print' => 'থার্মাল প্রিন্ট',
        'thermal_print_settings' => 'থার্মাল প্রিন্ট সেটিংস ও কনফিগারেশন',
        'thermal_print_configuration' => 'থার্মাল প্রিন্ট কনফিগারেশন সেটিংস',
        'printer_paper_size' => 'প্রিন্টার পেপার সাইজ',
        'standard_pos' => '৮০ মিমি স্ট্যান্ডার্ড পিওএস',
        'mini_pos' => '৫৮ মিমি মিনি পিওএস',
        'printer_paper_size_help' => 'আপনার থার্মাল প্রিন্টারের রোল অনুযায়ী সাইজ নির্বাচন করুন (সাধারণত 80mm সবচেয়ে জনপ্রিয়)।',
        'receipt_options' => 'রসিদ অপশনসমূহ',
        'show_school_logo' => 'স্কুল লোগো প্রদর্শন করুন',
        'show_barcode_invoice_code' => 'বারকোড ও ইনভয়েস কোড যুক্ত করুন',
        'auto_trigger_print_dialog' => 'স্বয়ংক্রিয় প্রিন্ট ডায়লগ ওপেন করুন',
        'header_title' => 'হেডার শিরোনাম',
        'header_subtitle' => 'উপ-শিরোনাম',
        'school_address' => 'স্কুল ঠিকানা',
        'contact_info' => 'যোগাযোগ ও মোবাইল',
        'footer_message_terms' => 'ফুটার বার্তা ও শর্তাবলী',
        'test_print' => 'টেস্ট প্রিন্ট দিন',
        'thermal_receipt_live_preview' => 'থার্মাল রসিদ প্রিভিউ',
        'receipt_voucher' => 'ফি আদায়ের রসিদ',
        'receipt_no' => 'রসিদ নং',

        // WhatsApp Messaging
        'whatsapp_messaging' => 'হোয়াটসঅ্যাপ মেসেজিং',
        'compose_whatsapp_message' => 'WhatsApp বার্তা তৈরি করুন',
        'send_message' => 'বার্তা পাঠান',
        'recipient_type' => 'প্রাপকের ধরন',
        'recipient' => 'প্রাপক',
        'recipients' => 'প্রাপকবৃন্দ',
        'recipient_name' => 'প্রাপকের নাম',
        'custom_numbers' => 'সরাসরি নম্বর',
        'staff_role' => 'কর্মচারী রোল',
        'mobile_no' => 'মোবাইল নম্বর',
        'mobile_numbers' => 'মোবাইল নম্বরসমূহ',
        'mobile_numbers_help' => 'কমা দিয়ে পৃথক করে একাধিক নম্বর লিখুন, যেমন: 01716122899, 01800000000',
        'message_template' => 'মেসেজ টেমপ্লেট',
        'select_message_template' => '-- টেমপ্লেট থেকে মেসেজ নির্বাচন করুন --',
        'insert_tags' => 'ট্যাগ যুক্ত করুন',
        'tag_click_help' => 'যেকোনো ট্যাগে ক্লিক করলে তা মেসেজে যুক্ত হবে এবং স্বয়ংক্রিয়ভাবে সংশ্লিষ্ট তথ্য দিয়ে প্রতিস্থাপিত হবে।',
        'message_body' => 'মেসেজের বিবরণ',
        'message_body_placeholder' => 'আপনার WhatsApp বার্তা এখানে লিখুন...',
        'character_count' => 'অক্ষর সংখ্যা',
        'start_whatsapp_dispatch' => 'WhatsApp বার্তা প্রেরণ প্রক্রিয়া শুরু করুন',
        'whatsapp_live_preview' => 'WhatsApp মেসেজ লাইভ প্রিভিউ',
        'whatsapp_preview_placeholder' => 'আপনার বার্তার নমুনা এখানে প্রদর্শিত হবে...',
        'whatsapp_templates' => 'WhatsApp মেসেজ টেমপ্লেট',
        'whatsapp_templates_list' => 'WhatsApp মেসেজ টেমপ্লেট তালিকা',
        'add_new_template' => 'নতুন টেমপ্লেট যুক্ত করুন',
        'edit_template' => 'টেমপ্লেট সংশোধন',
        'template_description' => 'টেমপ্লেট বিবরণ',
        'whatsapp_reports' => 'WhatsApp মেসেজ লগ ও রিপোর্ট',
        'whatsapp_dispatch_logs' => 'WhatsApp বার্তা প্রেরণ লগ ও রিপোর্ট',
        'all_roles' => '-- সকল রোল --',
        'gateway_medium' => 'মাধ্যম',
        'sent' => 'পাঠানো হয়েছে',
        'failed' => 'ব্যর্থ',
        'ready' => 'প্রস্তুত',
        'whatsapp_settings' => 'WhatsApp কনফিগারেশন ও গেটওয়ে সেটিংস',
        'gateway_type' => 'প্রেরণ মাধ্যম',
        'web_whatsapp_desc' => 'ওয়েব হোয়াটসঅ্যাপ (ব্রাউজার ও অ্যাপ)',
        'custom_api_desc' => 'কাস্টম হোয়াটসঅ্যাপ গেটওয়ে API',
        'gateway_help_text' => 'ডিফল্টভাবে "Web WhatsApp" মোড ব্যবহার করলে কোনো থার্ড-পার্টি সাবস্ক্রিপশন ছাড়াই সরাসরি WhatsApp Web / Mobile App দিয়ে ফ্রিতে বার্তা পাঠানো যায়।',
        'country_code' => 'কান্ট্রি কোড',
        'country_code_help' => 'বাংলাদেশের জন্য 880। স্থানীয় নম্বরসমূহ স্বয়ংক্রিয়ভাবে আন্তর্জাতিক ফরম্যাটে রূপান্তরিত হবে।',
        'message_header_text' => 'মেসেজ হেডার টেক্সট',
        'message_footer_text' => 'মেসেজ ফুটার টেক্সট',
        'whatsapp_queue' => 'WhatsApp বার্তা প্রেরণ কিউ',
        'send_via_whatsapp' => 'WhatsApp-এ পাঠান',
        'queue_instructions' => 'নিচে প্রাপকদের জন্য বার্তা প্রস্তুত রয়েছে। "WhatsApp-এ পাঠান" বাটনে ক্লিক করলে স্বয়ংক্রিয়ভাবে WhatsApp Web বা মোবাইল অ্যাপে প্রাপকের নম্বর ও সাজানো মেসেজসহ ওপেন হবে।',
        'message_preview' => 'বার্তা প্রিভিউ',
        'view_message_reports' => 'মেসেজ রিপোর্ট দেখুন',
        'api_endpoint_url' => 'API এন্ডপয়েন্ট URL',
        'api_key_token' => 'API Key ও টোকেন',
        'instance_id' => 'ইনস্ট্যান্স আইডি',
        'customer_user' => 'সম্মানিত গ্রাহক',
        'please_enter_message_text' => 'অনুগ্রহ করে মেসেজের বিবরণ লিখুন',
        'reset' => 'রিসেট',
        'filter_search' => 'ফিল্টার ও অনুসন্ধান',

        // Two Factor Authentication
        'two_factor_auth' => 'দ্বি-স্তর বিশিষ্ট নিরাপত্তা',
        'two_factor_authentication' => 'দ্বি-স্তর বিশিষ্ট নিরাপত্তা',
        'two_factor_auth_desc' => 'দ্বি-স্তর বিশিষ্ট নিরাপত্তা যাচাইকরণ',
        'enter_otp_code' => '৬-সংখ্যার সিকিউরিটি কোডটি লিখুন',
        'verify_and_login' => 'যাচাই করুন এবং প্রবেশ করুন',
        'resend_code' => 'পুনরায় কোড পাঠান',
        'back_to_login' => 'লগইন পেজে ফিরে যান',
        'time_remaining' => 'মেয়াদ বাকি',
        'code_expired' => 'মেয়াদ শেষ',
        'resend_cooldown_wait' => 'পুনরায় কোড পাঠাতে অপেক্ষা করুন',
        'seconds' => 'সেকেন্ড',
        'security_code_sent_email' => 'আপনার নিবন্ধিত ইমেইল %s-এ ৬-সংখ্যার সিকিউরিটি কোড পাঠানো হয়েছে।',
        'security_code_sent_sms' => 'আপনার মোবাইল নম্বর %s-এ ৬-সংখ্যার সিকিউরিটি কোড পাঠানো হয়েছে।',
        'security_code_sent_both' => 'আপনার ইমেইল %s ও মোবাইল %s-এ সিকিউরিটি কোড পাঠানো হয়েছে।',
        'please_enter_6_digit_security_code' => 'অনুগ্রহ করে ৬-সংখ্যার সিকিউরিটি কোডটি লিখুন',
        'new_security_code_sent' => 'নতুন সিকিউরিটি কোড সফলভাবে পাঠানো হয়েছে!',
        'please_wait_resend_cooldown' => 'অনুগ্রহ করে আরও %s সেকেন্ড অপেক্ষা করে আবার চেষ্টা করুন।',
        'enforced_roles' => 'প্রযোজ্য ইউজার রোল',
        'select_all' => 'সব নির্বাচন করুন',
        'deselect_all' => 'সব বাতিল করুন',
        'enforced_roles_help' => 'যেসব রোল সিলেক্ট করা থাকবে, তাদের লগইনের সময় অবশ্যই ২-স্টেপ সিকিউরিটি ওটিপি কোড যাচাই করতে হবে।',
        'otp_expiry_time' => 'ওটিপি কোডের মেয়াদ',
        'minutes' => 'মিনিট',
        'otp_expiry_help' => 'প্রেরিত সিকিউরিটি কোডটি কত মিনিট পর্যন্ত বৈধ থাকবে (ডিফল্ট: ১০ মিনিট)।',
        'master_bypass_pin' => 'মাস্টার বাইপাস পিন',
        'master_bypass_pin_help' => 'জরুরি অবস্থায় (যেমন স্কুল মেইল বা এসএমএস গেটওয়ে সাময়িক অফলাইনে থাকলে) সুপার এডমিন এই পিন দিয়ে সরাসরি লগইন করতে পারবেন। এটি অত্যন্ত সুরক্ষিত রাখুন।',
        'verification_method' => 'যাচাইকরণ মাধ্যম',
        'email_otp' => 'ইমেইল ওটিপি',
        'sms_otp' => 'এসএমএস ওটিপি',
        'both_email_sms' => 'উভয় মাধ্যম',
        'verified' => 'যাচাইকৃত',
        'expired' => 'মেয়াদোত্তীর্ণ',
        'pending' => 'অপেক্ষমাণ',
        'sent_to' => 'প্রেরিত ঠিকানা ও নম্বর',
        'security_code' => 'সিকিউরিটি কোড',
        'ip_address' => 'আইপি অ্যাড্রেস',
        'user_agent' => 'ব্রাউজার ও ইউজার এজেন্ট',
        'date_time' => 'তারিখ ও সময়',
        'activity_logs' => 'অ্যাক্টিভিটি লগ',
        'account_security_profile' => 'অ্যাকাউন্ট সিকিউরিটি প্রোফাইল',
        'my_account_security' => 'আমার অ্যাকাউন্ট নিরাপত্তা',
        'tfa_security_status' => '2FA নিরাপত্তা স্ট্যাটাস',
        'keep_enabled' => 'সক্রিয় রাখুন',
        'disable_now' => 'নিষ্ক্রিয় করুন',
        'how_it_works' => 'কিভাবে কাজ করে?',
        'how_it_works_2fa_desc' => 'যখন আপনি আপনার ইউজারনেম এবং পাসওয়ার্ড দিয়ে লগইন করবেন, তখন আপনার নিবন্ধিত ইমেইল বা মোবাইলে একটি ৬-সংখ্যার ওটিপি কোড পাঠানো হবে। সেই কোডটি প্রবেশ করালে আপনি নিরাপদে অ্যাকাউন্টে প্রবেশ করতে পারবেন।',
        'two_fa_account_desc' => 'আপনার অ্যাকাউন্টের অননুমোদিত অ্যাক্সেস রোধে দ্বি-স্তর বিশিষ্ট যাচাইকরণ ব্যবস্থা সক্রিয় রাখুন।',

        // Accounting & Behaviour
        'pay_by' => 'পরিশোধকারী',
        'received_by' => 'গ্রহীতা',
        'payer_name' => 'পরিশোধকারীর নাম',
        'receiver_name' => 'গ্রহীতার নাম',
        'print_voucher' => 'ভাউচার প্রিন্ট করুন',
        'debit_voucher' => 'খরচ ভাউচার (ডেবিট)',
        'credit_voucher' => 'জমা ভাউচার (ক্রেডিট)',
        'voucher_no' => 'ভাউচার নং',
        'account_fund' => 'অ্যাকাউন্ট',
        'particulars_description' => 'বিবরণ ও উদ্দেশ্য',
        'amount_bdt' => 'টাকার পরিমাণ (৳)',
        'total_payable_receivable' => 'সর্বমোট টাকা',
        'in_words' => 'কথায়',
        'prepared_by' => 'প্রস্তুতকারী',
        'checked_by' => 'হিসাবরক্ষক',
        'received_by_signature' => 'গ্রহীতার স্বাক্ষর',
        'approved_by' => 'অনুমোদনকারী',
        'computer_generated_voucher_note' => 'ড্রিম ভ্যালি স্কুল ইআরপি - অফিসিয়াল হিসাব শাখা কর্তৃক কম্পিউটার জেনারেটেড ভাউচার কপি।',

        // Admission Settings & Drafts
        'admission_settings' => 'ভর্তি সেটিংস',
        'draft_admissions' => 'ড্রাফট ভর্তি তালিকা',
        'draft_admission' => 'ড্রাফট ভর্তি',
        'save_as_draft' => 'ড্রাফট সংরক্ষণ',
        'resume_admission' => 'ভর্তি সম্পন্ন করুন',
        'draft_saved_successfully' => 'ভর্তি ড্রাফট সফলভাবে সংরক্ষিত হয়েছে',
        'draft_deleted_successfully' => 'ভর্তি ড্রাফট মুছে ফেলা হয়েছে',
        'last_saved_step' => 'সর্বশেষ সম্পন্ন ধাপ',
        'draft_list' => 'ড্রাফট তালিকা',
        'clear_draft' => 'ড্রাফট মুছুন',
        'admission_settings_fee' => 'ভর্তি ফি ও শর্তাবলী কনফিগারেশন',
        'default_admission_fee' => 'নির্ধারিত ভর্তি ফি',
        'default_admission_fee_help' => 'নতুন শিক্ষার্থী ভর্তির সময় ফরমের ৫নং ধাপে এই ফি-টি প্রারম্ভিক ভর্তি ফি হিসেবে প্রদর্শিত হবে।',
        'minimum_paid_required' => 'ভর্তি নিশ্চিতের জন্য সর্বনিম্ন ফি',
        'minimum_paid_required_help' => 'শিক্ষার্থী ভর্তি নিশ্চিত করতে অন্তত এই পরিমাণ টাকা দিতে হবে। এর চেয়ে কম দিলে আবেদন ড্রাফট হিসেবে থাকবে।',
        'allow_100_percent_free_admission' => 'বিনামূল্যে ভর্তি অনুমোদন',
        'allow_free_admission_help_yes' => 'অ্যাডমিন বিশেষ বিবেচনায় বিনামূল্যে ভর্তি করাতে পারবেন',
        'allow_free_admission_help_no' => 'সর্বনিম্ন ফি প্রদান বাধ্যতামূলক',
        'allow_free_admission_help_note' => 'এটি চালু থাকলে ভর্তি ফরমে বিশেষ বিবেচনায় বিনামূল্যে ভর্তি অপশন থাকবে যা নির্বাচন করলে ০ টাকাতেও ভর্তি নিশ্চিত করা যাবে।',
        'default_payment_method' => 'ডিফল্ট পেমেন্ট মাধ্যম',
        'terms_conditions_undertaking' => 'ভর্তির শর্তাবলী ও অঙ্গীকারনামা',
        'terms_conditions_help' => 'এই শর্তাবলী ও অঙ্গীকারনামাটি শিক্ষার্থীর চূড়ান্ত ভর্তি ফরম ও ফি রসিদের নিচে স্বয়ংক্রিয়ভাবে মুদ্রিত হবে।',
        'behaviour_records' => 'শিক্ষার্থীর আচরণ মূল্যায়ন',
        'assign_behaviour' => 'আচরণ রেকর্ড',
        'incidents' => 'আচরণের ধারা',
        'incident' => 'আচরণ',
        'incident_title' => 'আচরণের বিবরণ',
        'point' => 'পয়েন্ট',
        'points' => 'পয়েন্ট',
        'positive' => 'ইতিবাচক',
        'negative' => 'নেতিবাচক',
        'predefined_incident' => 'সংরক্ষিত ধারা',
        'custom_incident' => 'ম্যানুয়ালি লিখুন',
        'comment_details' => 'বিস্তারিত মন্তব্য',
        'student_name' => 'শিক্ষার্থীর নাম',
        'view_report' => 'রিপোর্ট দেখুন',

        // Core system
        'dashboard' => 'ড্যাশবোর্ড',
        'branch' => 'শাখা',
        'all_branches' => 'সকল শাখা',
        'inventory' => 'ইনভেন্টরি',
        'frontend' => 'ওয়েবসাইট সিএমএস',
        'reception' => 'ফ্রন্ট ডেস্ক ও রিসেপশন',
        'admission' => 'ভর্তি কার্যক্রম',
        'student_details' => 'শিক্ষার্থী তথ্য ও তালিকা',
        'parents' => 'অভিভাবক বৃন্দ',
        'employee' => 'কর্মকর্তা ও কর্মচারী',
        'card_management' => 'আইডি ও প্রবেশপত্র কার্ড',
        'certificate' => 'সার্টিফিকেট ও সনদ',
        'certificates' => 'সার্টিফিকেট সমূহ',
        'human_resource' => 'মানব সম্পদ',
        'hrm' => 'মানব সম্পদ',
        'academic' => 'একাডেমিক ব্যবস্থাপনা',
        'live_class_rooms' => 'অনলাইন ও লাইভ ক্লাস',
        'live_class' => 'লাইভ ক্লাস',
        'live_class_reports' => 'লাইভ ক্লাস রিপোর্ট',
        'attachments_book' => 'ডাউনলোড সেন্টার ও ফাইল',
        'attachment_type' => 'ফাইলের ধরন',
        'upload_content' => 'ফাইল আপলোড',
        'homework' => 'বাড়ির কাজ',
        'evaluation_report' => 'মূল্যায়ন প্রতিবেদন',
        'supervision' => 'হোস্টেল ও পরিবহন',
        'attendance' => 'হাজিরা ও উপস্থিতি',
        'attendance_reports' => 'হাজিরা রিপোর্ট',
        'qr_code_attendance' => 'কিউআর কোড হাজিরা',
        'biometric_attendance' => 'বায়োমেট্রিক হাজিরা',
        'biometric' => 'বায়োমেট্রিক ডিভাইস',
        'devices' => 'ডিভাইস সমূহ',
        'logs' => 'হাজিরা লগ',
        'user_mapping' => 'ইউজার ম্যাপিং',
        'lesson_plan' => 'পাঠ পরিকল্পনা',
        'library' => 'লাইব্রেরি',
        'events' => 'ইভেন্টস ও নোটিশ',
        'event_type' => 'ইভেন্টের ধরন',
        'notice' => 'নোটিশ',
        'notice_board' => 'নোটিশ বোর্ড',
        'bulk_sms_and_email' => 'এসএমএস ও ইমেইল প্রেরণ',
        'sms' => 'এসএমএস',
        'email' => 'ই-মেইল',
        'student_accounting' => 'শিক্ষার্থী ফি ও হিসাব',
        'office_accounting' => 'স্কুল আয়-ব্যয় হিসাব',
        'message' => 'বার্তা আদান-প্রদান',
        'report' => 'রিপোর্ট ও পরিসংখ্যান',
        'reports' => 'রিপোর্ট সমূহ',
        'repots' => 'রিপোর্ট সমূহ',
        'alumni' => 'প্রাক্তন শিক্ষার্থী',
        'manage_alumni' => 'অ্যালামনাই ব্যবস্থাপনা',
        'alumni_event' => 'অ্যালামনাই ইভেন্ট',
        'addon_manager' => 'অ্যাড-অন ম্যানেজার',
        'modules' => 'মডিউল সমূহ',
        'settings' => 'সিস্টেম সেটিংস',
        'setting' => 'সেটিংস',
        'school_settings' => 'স্কুল সেটিংস',
        'session_settings' => 'শিক্ষাবর্ষ সেটিংস',
        'role_permission' => 'রোল ও পারমিশন',
        'translations' => 'ভাষা ও অনুবাদ',
        'cron_job' => 'স্বয়ংক্রিয় শিডিউলার',
        'custom_field' => 'কাস্টম ফিল্ড',
        'database_backup' => 'ডাটাবেজ ব্যাকআপ',
        'system_update' => 'সিস্টেম আপডেট',
        'system_student_field' => 'শিক্ষার্থী ফিল্ড সেটিংস',
        'menu' => 'মেনু',
        'page' => 'পেজ সমূহ',
        'manage_page' => 'পেজ ব্যবস্থাপনা',
        'page_section' => 'পেজ সেকশন',
        'slider' => 'ব্যানার স্লাইডার',
        'features' => 'বৈশিষ্ট্যসমূহ',
        'testimonial' => 'প্রশংসাপত্র ও মতামত',
        'service' => 'সেবাসমূহ',
        'faq' => 'সাধারণ জিজ্ঞাসা',
        'gallery_category' => 'গ্যালারি ক্যাটাগরি',
        'gallery' => 'ছবি গ্যালারি',
        'news' => 'সংবাদ ও বিজ্ঞপ্তি',
        'admission_enquiry' => 'ভর্তি অনুসন্ধান',
        'postal_record' => 'চিঠিপত্র ও ডাক রেকর্ড',
        'call_log' => 'কল লগ',
        'visitor_log' => 'দর্শনার্থী রেজিস্টার',
        'complaint' => 'অভিযোগ বক্স',
        'product' => 'পণ্য ও মালামাল',
        'store' => 'স্টোর ও গুদাম',
        'supplier' => 'সরবরাহকারী',
        'unit' => 'পরিমাপের একক',
        'purchase' => 'মালামাল ক্রয়',
        'sales' => 'মালামাল বিক্রয়',
        'issue' => 'মালামাল ইস্যু',
        'issues' => 'ইস্যু সমূহ',
        'stock' => 'স্টক ও মজুদ',
        'my_issued_book' => 'আমার ইস্যুকৃত বই',
        'id_card' => 'আইডি কার্ড',
        'admit_card' => 'প্রবেশপত্র',
        'templete' => 'কার্ড টেমপ্লেট',
        'template' => 'টেমপ্লেট',
        'class' => 'শ্রেণি',
        'section' => 'শাখা',
        'subject' => 'বিষয় সমূহ',
        'class_assign' => 'শ্রেণি শিক্ষক বরাদ্দ',
        'assign_class_teacher' => 'শ্রেণি শিক্ষক নিয়োগ',
        'subject_wise' => 'বিষয়ভিত্তিক',
        'subject_wise_by' => 'বিষয়ভিত্তিক নির্বাচন',
        'subject_wise_reports' => 'বিষয়ভিত্তিক রিপোর্ট',
        'class_routine' => 'ক্লাস রুটিন',
        'control_classes' => 'শ্রেণি নিয়ন্ত্রণ',
        'create_admission' => 'নতুন শিক্ষার্থী ভর্তি',
        'online_admission' => 'অনলাইন ভর্তি',
        'multi_class' => 'মাল্টি ক্লাস',
        'multiple_import' => 'একত্রে শিক্ষার্থী ইমপোর্ট',
        'category' => 'ক্যাটাগরি',
        'student_list' => 'শিক্ষার্থী তালিকা',
        'deactivate_reason' => 'স্থগিতের কারণ',
        'login_credential' => 'লগইন তথ্য',
        'disable_authentication' => 'লগইন নিষ্ক্রিয়করণ',
        'student' => 'শিক্ষার্থী',
        'previous_school_details' => 'পূর্ববর্তী স্কুলের বিবরণ',
        'sibling_report' => 'সহোদর ভাই-বোন রিপোর্ট',
        'class_&_section_report' => 'শ্রেণি ও শাখা ভিত্তিক রিপোর্ট',
        'admission_report' => 'ভর্তি রিপোর্ট',
        'student_admission' => 'শিক্ষার্থী ভর্তি',
        'parents_list' => 'অভিভাবক তালিকা',
        'add_parent' => 'অভিভাবক যোগ করুন',
        'guardian' => 'অভিভাবক',
        'guardian_name' => 'অভিভাবকের নাম',
        'father_name' => 'পিতার নাম',
        'mother_name' => 'মাতার নাম',
        'employee_list' => 'কর্মকর্তা-কর্মচারী তালিকা',
        'add_employee' => 'নতুন কর্মী যোগ করুন',
        'department' => 'বিভাগ',
        'add_department' => 'বিভাগ যোগ করুন',
        'designation' => 'পদবী',
        'add_designation' => 'পদবী যোগ করুন',
        'payroll' => 'বেতন-ভাতা',
        'payroll_summary' => 'বেতন সারসংক্ষেপ',
        'salary_template' => 'বেতন স্কেল টেমপ্লেট',
        'salary_assign' => 'বেতন নির্ধারণ',
        'salary_payment' => 'বেতন প্রদান',
        'advance_salary' => 'অগ্রিম বেতন',
        'leave_manage' => 'ছুটি ব্যবস্থাপনা',
        'award' => 'পুরস্কার ও সম্মাননা',
        'teacher' => 'শিক্ষক',
        'teachers' => 'শিক্ষকবৃন্দ',
        'fees_group' => 'ফি গ্রুপ',
        'fees_type' => 'ফি এর ধরন',
        'fees_fine_setup' => 'জরিমানা নির্ধারণ',
        'fees_allocation' => 'ফি বরাদ্দকরণ',
        'due_fees_invoice' => 'বকেয়া ফি ইনভয়েস',
        'due_fees_report' => 'বকেয়া ফি রিপোর্ট',
        'fees_reminder' => 'ফি বকেয়া রিমাইন্ডার',
        'payments_history' => 'ফি পরিশোধ ও চালান',
        'offline_payments' => 'অফলাইন পেমেন্ট',
        'fees_report' => 'ফি আদায়ের রিপোর্ট',
        'fees_reports' => 'ফি রিপোর্ট সমূহ',
        'fees_history' => 'ফি ইতিহাস',
        'fees_setup' => 'ফি সেটআপ',
        'fees_progress' => 'ফি পরিশোধ অগ্রগতি',
        'account' => 'ব্যাংক ও ক্যাশ একাউন্ট',
        'new_deposit' => 'নতুন জমা',
        'new_expense' => 'নতুন খরচ',
        'all_transactions' => 'সকল লেনদেন বিবরণী',
        'voucher' => 'ভাউচার',
        'balance' => 'অবশিষ্ট ব্যালেন্স',
        'opening_balance' => 'প্রারম্ভিক ব্যালেন্স',
        'receipts_report' => 'আদায় ও প্রাপ্তি রিপোর্ট',
        'expense' => 'ব্যয় ও খরচ',
        'hostel' => 'হোস্টেল',
        'hostel_master' => 'হোস্টেল ব্যবস্থাপনা',
        'hostel_room' => 'হোস্টেল রুম',
        'hostel_category' => 'হোস্টেল ক্যাটাগরি',
        'allocation_report' => 'হোস্টেল বরাদ্দ রিপোর্ট',
        'transport' => 'পরিবহন ব্যবস্থাপনা',
        'route_master' => 'রুট মাস্টার',
        'vehicle_master' => 'যানবাহন মাস্টার',
        'assign_vehicle' => 'যানবাহন বরাদ্দ',
        'transport_route' => 'পরিবহন রুট',
        'transport_details' => 'পরিবহন বিবরণ',
        'stoppage' => 'স্টপেজ',
        'pickup_point' => 'পিকআপ পয়েন্ট',
        'vehicle_no' => 'যানবাহন নং',
        'exam' => 'পরীক্ষা',
        'examination' => 'পরীক্ষা ব্যবস্থাপনা',
        'exam_term' => 'পরীক্ষার মেয়াদ',
        'exam_setup' => 'পরীক্ষা সেটআপ',
        'exam_result' => 'পরীক্ষার ফলাফল',
        'exam_master' => 'পরীক্ষা মাস্টার',
        'exam_hall' => 'পরীক্ষা হল',
        'marksheet' => 'মার্কশীট ও নম্বরপত্র',
        'tabulation_sheet' => 'টেবুলেশন শীট',
        'sheet' => 'শীট',
        'generate_position' => 'মেধাক্রম নির্ধারণ',
        'position' => 'মেধাক্রম',
        'online_exam' => 'অনলাইন পরীক্ষা',
        'question_bank' => 'প্রশ্ন ব্যাংক',
        'question_group' => 'প্রশ্ন গ্রুপ',
        'copy_old_lessons' => 'পূর্বের পাঠ কপি করুন',
        'manage_lesson_plan' => 'পাঠ পরিকল্পনা পরিচালনা',
        'lesson' => 'পাঠ (লেসন)',
        'topic' => 'টপিক (বিষয়বস্তু)',
        'manage_lesson' => 'পাঠ ব্যবস্থাপনা',
        'manage_topic' => 'টপিক ব্যবস্থাপনা',
        'manage_syllabus' => 'সিলেবাস ব্যবস্থাপনা',
        'manage_syllabus_status' => 'সিলেবাস অগ্রগতি ব্যবস্থাপনা',
        'overview_reports' => 'সার্বিক প্রতিবেদন',
        'daily_reports' => 'দৈনিক রিপোর্ট',
        'progress' => 'অগ্রগতি',
        'period' => 'পিরিয়ড',
        'total_topics' => 'মোট টপিক',
        'total_lessons' => 'মোট পাঠ',
        'completion_date' => 'সমাপ্তির তারিখ',
        'update_lesson_status' => 'পাঠের অগ্রগতি আপডেট',
        'topic_name' => 'টপিকের নাম',
        'lesson_name' => 'পাঠের নাম',
        'syllabus_status' => 'সিলেবাসের অবস্থা',
        'syllabus_status_list' => 'সিলেবাস অবস্থা তালিকা',
        'overall_syllabus_completion_rate' => 'সার্বিক সিলেবাস সমাপ্তির হার',
        'syllabus_overview_progress' => 'সিলেবাস সারসংক্ষেপ ও অগ্রগতি',
        'no_lessons_found_for_this_topic' => 'এই টপিকের জন্য কোনো পাঠ পাওয়া যায়নি',
        'topic_list' => 'টপিক তালিকা',
        'lesson_list' => 'পাঠ তালিকা',
        'in_progress' => 'চলমান',
        'completed' => 'সম্পন্ন',
        'time_from' => 'শুরুর সময়',
        'time_to' => 'শেষের সময়',
        'optional_note_or_summary' => 'সংক্ষিপ্ত নোট বা বিবরণ (ঐচ্ছিক)',
        'allowed_types' => 'অনুমোদিত ফাইলের ধরন',
        'edit_lesson_status' => 'পাঠের অবস্থা সংশোধন',
        'books' => 'বই তালিকা',
        'books_category' => 'বইয়ের ক্যাটাগরি',
        'book_issue/return' => 'বই ইস্যু ও জমা',
        'filter' => 'ফিল্টার',
        'select_ground' => 'অনুসন্ধানের শর্ত নির্বাচন করুন',
        'select' => 'নির্বাচন করুন',
        'select_class_first' => 'প্রথমে শ্রেণি নির্বাচন করুন',
        'select_subject_first' => 'প্রথমে বিষয় নির্বাচন করুন',
        'select_topic_first' => 'প্রথমে টপিক নির্বাচন করুন',
        'select_class_and_section_first' => 'প্রথমে শ্রেণি ও শাখা নির্বাচন করুন',
        'quick_view' => 'দ্রুত দেখুন',
        'action' => 'অ্যাকশন',
        'type' => 'ধরন',
        'age' => 'বয়স',
        'date_of_birth' => 'জন্ম তারিখ',
        'birthday' => 'জন্ম তারিখ',
        'gender' => 'লিঙ্গ',
        'male' => 'পুরুষ',
        'female' => 'মহিলা',
        'roll' => 'রোল নং',
        'photo' => 'ছবি',
        'profile_picture' => 'প্রোফাইল ছবি',
        'name' => 'নাম',
        'mobile_no' => 'মোবাইল নং',
        'blood_group' => 'রক্তের গ্রুপ',
        'religion' => 'ধর্ম',
        'address' => 'ঠিকানা',
        'present_address' => 'বর্তমান ঠিকানা',
        'permanent_address' => 'স্থায়ী ঠিকানা',
        'occupation' => 'পেশা',
        'education' => 'শিক্ষাগত যোগ্যতা',
        'qualification' => 'শিক্ষাগত যোগ্যতা',
        'city' => 'শহর ও জেলা',
        'state' => 'বিভাগ',
        'remarks' => 'মন্তব্য',
        'status' => 'স্ট্যাটাস',
        'active' => 'সক্রিয়',
        'inactive' => 'নিষ্ক্রিয়',
        'close' => 'বন্ধ করুন',
        'cancel' => 'বাতিল',
        'send' => 'পাঠান',
        'payments' => 'পরিশোধ সমূহ',
        'manage' => 'ব্যবস্থাপনা',
        'user_login_log' => 'ইউজার লগইন হিস্ট্রি',
        'usename' => 'ইউজারনেম',
        'username' => 'ব্যবহারকারীর নাম',
        'password' => 'পাসওয়ার্ড',
        'retype_password' => 'পাসওয়ার্ড পুনরায় লিখুন',
        'register_no' => 'রেজিস্টার নং',
        'academic_year' => 'শিক্ষাবর্ষ',
        'admission_date' => 'ভর্তির তারিখ',
        'school_name' => 'প্রতিষ্ঠানের নাম',
        'yes_continue' => 'হ্যাঁ, এগিয়ে যান',
        'are_you_sure' => 'আপনি কি নিশ্চিত?',
        'delete_this_information' => 'আপনি কি এই তথ্যটি মুছে ফেলতে চান?',
        'deleted_note' => '* দ্রষ্টব্য: এই তথ্যটি স্থায়ীভাবে মুছে যাবে',
        'bulk_delete' => 'একত্রে মুছুন',
        'distribution' => 'বিতরণ',
        'schedule' => 'সময়সূচি',
        'transitions' => 'লেনদেন',
        'statement' => 'বিবরণী',
        'security_code' => 'সিকিউরিটি কোড',
        'verification_code' => 'যাচাইকরণ কোড',
        'enter_verification_code' => 'যাচাইকরণ কোড লিখুন',
        'no_information_available' => 'কোনো তথ্য পাওয়া যায়নি',

        // Seat Plan
        'seat_plan' => 'সিট প্ল্যান',
        'seat_allocation' => 'আসন নির্ধারণ',
        'seat_plan_allocation' => 'সিট প্ল্যান নির্ধারণ',
        'generate_seat_plan' => 'সিট প্ল্যান তৈরি করুন',
        'print_seat_cards' => 'সিট কার্ড প্রিন্ট',
        'seat_card' => 'সিট কার্ড',
        'room_attendance_sheet' => 'কক্ষভিত্তিক উপস্থিতি ও সিট শিট',
        'hall_wise_seat_plan' => 'হলভিত্তিক সিট বিন্যাস',
        'allocation_strategy' => 'আসন বণ্টন পদ্ধতি',
        'sequential_roll' => 'রোল ক্রম অনুযায়ী',
        'alternate_classes' => 'বিকল্প শ্রেণি অনুযায়ী (নকল প্রতিরোধ)',
        'alternate_sections' => 'বিকল্প শাখা অনুযায়ী',
        'random_shuffle' => 'র‍্যান্ডম / দৈবচয়ন (নকল প্রতিরোধ)',
        'seats_per_bench' => 'প্রতি বেঞ্চে আসন সংখ্যা',
        'bench_prefix' => 'বেঞ্চ প্রিফিক্স',
        'starting_seat' => 'প্রারম্ভিক আসন নং',
        'clear_seat_plan' => 'সিট প্ল্যান মুছুন',
        'clear_plan_confirm' => 'আপনি কি নিশ্চিত যে এই পরীক্ষার সিট প্ল্যান মুছে ফেলতে চান?',
        'seat_plan_generated_successfully' => 'সিট প্ল্যান সফলভাবে প্রস্তুত করা হয়েছে',
        'seat_plan_cleared_successfully' => 'সিট প্ল্যান সফলভাবে মুছে ফেলা হয়েছে',
        'allocated_students' => 'আসন নির্ধারিত শিক্ষার্থী',
        'unallocated_students' => 'আসনবিহীন শিক্ষার্থী',
        'total_halls' => 'মোট কক্ষ/হল',
        'total_capacity' => 'মোট আসন ক্ষমতা',
        'available_seats' => 'খালি আসন',
        'invigilator_signature' => 'কক্ষ পরিদর্শকের স্বাক্ষর',
        'room_supervisor' => 'কক্ষ পরিদর্শক',
        'additional_sheet_no' => 'অতিরিক্ত খাতা নং',
        'script_no' => 'মূল খাতা নং',
        'student_signature' => 'শিক্ষার্থীর স্বাক্ষর',
        'card_per_page' => 'প্রতি পেজে কার্ড সংখ্যা',
        'bench_no' => 'বেঞ্চ নং',
        'seat_no' => 'আসন নং',

        // 2FA Security Enhanced
        'two_factor_authentication_settings' => 'টু-ফ্যাক্টর অথেনটিকেশন সেটিংস',
        '2fa_show_remember_browser' => 'ব্রাউজার মনে রাখার অপশন প্রদর্শন',
        '2fa_cookie_expiry' => 'কুকির মেয়াদকাল',
        'email_instruction' => 'ইমেইল নির্দেশনা',
        'app_instruction' => 'অ্যাপ নির্দেশনা',
        'my_2fa_setup' => 'আমার 2FA সেটআপ',
        'saved_login' => 'সংরক্ষিত লগইন',
        'two_factor_authentication_app' => 'টু-ফ্যাক্টর অথেনটিকেশন (অ্যাপ)',
        'two_factor_authentication_email' => 'টু-ফ্যাক্টর অথেনটিকেশন (ইমেইল)',
        'you_have_two_factor_authentication_enabled' => 'আপনার টু-ফ্যাক্টর অথেনটিকেশন সক্রিয় রয়েছে।',
        'you_have_two_factor_authentication_disabled' => 'আপনার টু-ফ্যাক্টর অথেনটিকেশন নিষ্ক্রিয় রয়েছে।',
        'scan_qr_code_with_authenticator_app' => 'Google, Microsoft বা Authy অথেনটিকেটর অ্যাপ দিয়ে QR কোড স্ক্যান করুন।',
        'secret_key' => 'সিক্রেট কি (গোপন কোড)',
        'secret_key_copied_to_clipboard' => 'সিক্রেট কি ক্লিপবোর্ডে কপি করা হয়েছে!',
        'disable_two_factor_authentication' => 'টু-ফ্যাক্টর অথেনটিকেশন বন্ধ করুন',
        'enable_two_factor_authentication' => 'টু-ফ্যাক্টর অথেনটিকেশন চালু করুন',
        '2fa_code_will_be_delivered_to_this_email' => 'এই ইমেইল ঠিকানায় ২এফএ ভেরিফিকেশন কোড পাঠানো হবে।',
        'email_authentication_is_currently_disabled' => 'ইমেইল অথেনটিকেশন বর্তমানে বন্ধ রয়েছে।',
        'enter_verification_code_from_your_authenticator_app' => 'আপনার অথেনটিকেটর অ্যাপের ভেরিফিকেশন কোডটি লিখুন',
        'enter_verification_code_sent_to_your_email' => 'আপনার ইমেইলে প্রেরিত ভেরিফিকেশন কোডটি লিখুন',
        'login_saved_browser_list' => 'লগইন সংরক্ষিত ব্রাউজারের তালিকা',
        'saved_browser_removed_successfully' => 'সংরক্ষিত ব্রাউজার সফলভাবে মুছে ফেলা হয়েছে',
        'no_saved_browsers_found' => 'কোনো সংরক্ষিত ব্রাউজার পাওয়া যায়নি।',
        'login_date_time' => 'লগইনের তারিখ ও সময়',
        'authentication_code' => 'অথেনটিকেশন কোড',
        'do_not_ask_again_on_this_browser' => 'এই ব্রাউজারে আর জিজ্ঞাসা করবেন না।',
        'download_google_authenticator_app_in_your_mobile_device' => 'আপনার মোবাইল ডিভাইসে Google Authenticator অ্যাপ ডাউনলোড করুন।',
        'a_unique_code_has_been_sent_to_your_email_address' => 'আপনার ইমেইল ঠিকানায় একটি কোড পাঠানো হয়েছে। অনুগ্রহ করে ইনবক্স চেক করুন।',
        'authenticator_app_2fa_enabled_successfully' => 'অথেনটিকেটর অ্যাপ 2FA সফলভাবে চালু হয়েছে।',
        'authenticator_app_2fa_disabled_successfully' => 'অথেনটিকেটর অ্যাপ 2FA বন্ধ করা হয়েছে।',
        'email_2fa_enabled_successfully' => 'ইমেইল 2FA সফলভাবে চালু হয়েছে।',
        'email_2fa_disabled_successfully' => 'ইমেইল 2FA বন্ধ করা হয়েছে।',
        'invalid_verification_code_from_authenticator_app' => 'অথেনটিকেটর অ্যাপের কোডটি সঠিক নয়। অনুগ্রহ করে অ্যাপ দেখে পুনরায় লিখুন।',
        'invalid_or_expired_email_verification_code' => 'ইমেইল কোডটি ভুল অথবা মেয়াদ শেষ হয়ে গেছে।',

        // School Subscription (SaaS) Bengali
        'school_subscription' => 'স্কুল সাবস্ক্রিপশন (SaaS)',
        'subscription' => 'সাবস্ক্রিপশন',
        'pending_request' => 'অপেক্ষমাণ অনুরোধ',
        'plan' => 'প্ল্যান ও প্যাকেজ',
        'custom_domain' => 'কাস্টম ডোমেইন',
        'transactions' => 'লেনদেনের ইতিহাস',
        'package_list' => 'প্যাকেজের তালিকা',
        'add_package' => 'নতুন প্যাকেজ তৈরি করুন',
        'edit_package' => 'প্যাকেজ সম্পাদনা',
        'school_list' => 'স্কুল তালিকা',
        'add_school' => 'নতুন স্কুল যোগ করুন',
        'student_limit' => 'সর্বোচ্চ শিক্ষার্থী সীমা',
        'parents_limit' => 'সর্বোচ্চ অভিভাবক সীমা',
        'staff_limit' => 'সর্বোচ্চ কর্মকর্তা/কর্মচারী সীমা',
        'teacher_limit' => 'সর্বোচ্চ শিক্ষক সীমা',
        'subscription_period' => 'সাবস্ক্রিপশনের মেয়াদকাল',
        'module_enabled' => 'সক্রিয় মডিউলসমূহ',
        'free_trial' => 'ফ্রি ট্রায়াল প্যাকেজ',
        'recommended' => 'জনপ্রিয় বা প্রস্তাবিত',
        'period_type' => 'মেয়াদের ধরন',
        'show_on_website' => 'ওয়েবসাইটে প্রদর্শন করুন',
        'show_hide_package_on_website' => 'ওয়েবসাইটে প্যাকেজ প্রদর্শন বা লুকান',
        'automatic_subscription_approval' => 'স্বয়ংক্রিয় সাবস্ক্রিপশন অনুরোধ অনুমোদন',
        'offline_payments' => 'অফলাইন পেমেন্ট',
        'google_recaptcha' => 'গুগল রিক্যাপচা (স্কুল নিবন্ধন)',
        'terms_and_conditions' => 'শর্তাবলী ও নীতিমালা',
        'pwa_enable' => 'পিডব্লিউএ মোবাইল অ্যাপ (PWA)',
        'slider_overlay_image' => 'স্লাইডার ওভারলে ছবি',
        'overlay_image_enable_disable' => 'স্লাইডার ওভারলে ছবি প্রদর্শন/লুকান',
        'saas_email_test' => 'SaaS ইমেইল পরীক্ষা',
        'paytm' => 'Paytm পেমেন্ট',
        'bkash' => 'বিকাশ (bKash)',
        'nagad' => 'নগদ (Nagad)',
        'dns_instruction' => 'ডিএনএস (DNS) নির্দেশিকা',
        'upgrade_plan' => 'প্ল্যান আপগ্রেড করুন',
        'renew_subscription' => 'সাবস্ক্রিপশন নবায়ন করুন',
        'subscription_payment_success' => 'সাবস্ক্রিপশন পেমেন্ট সফল হয়েছে',
        'subscription_transactions_history' => 'সাবস্ক্রিপশন লেনদেনের ইতিহাস',
        'seo_title' => 'এসইও টাইটেল',
        'meta_keyword' => 'মেটা কী-ওয়ার্ড',
        'meta_description' => 'মেটা বিবরণ',
        'google_analytics' => 'গুগল অ্যানালিটিক্স কোড',
        'parent_count_fix' => 'ফ্রন্ট-এন্ড অভিভাবক গণনা সংশোধন',
        'lifetime' => 'আজীবন (Lifetime)',
        'days' => 'দিন',
        'monthly' => 'মাসিক',
        'yearly' => 'বাৎসরিক',
        'approved' => 'অনুমোদিত',
        'rejected' => 'প্রত্যাখ্যাত',
        'pending' => 'অপেক্ষমাণ',
        'last_upgrade' => 'সর্বশেষ আপগ্রেড',
        'start_date' => 'শুরুর তারিখ',
        'expiry_date' => 'মেয়াদোত্তীর্ণের তারিখ',
        'approve' => 'অনুমোদন করুন',
        'reject' => 'প্রত্যাখ্যান করুন',
        'reject_reason' => 'প্রত্যাখ্যানের কারণ',
        'domain_request' => 'ডোমেইন অনুরোধ',
        'request_domain' => 'কাস্টম ডোমেইন অনুরোধ করুন',
        'cname_record' => 'CNAME রেকর্ড',
        'a_record' => 'A রেকর্ড',

        // Database Backup Module
        'database_backup' => 'ডাটাবেজ ব্যাকআপ',
        'database_list' => 'ব্যাকআপ তালিকা',
        'restore_database' => 'ডাটাবেজ রিস্টোর',
        'tables_and_health' => 'টেবিল ও স্বাস্থ্য',
        'automated_backup' => 'স্বয়ংক্রিয় ব্যাকআপ',
        'database_size' => 'ডাটাবেজ সাইজ',
        'total_tables' => 'মোট টেবিল',
        'total_records' => 'মোট রেকর্ড',
        'stored_backups' => 'সংরক্ষিত ব্যাকআপ',
        'create_backup' => 'ব্যাকআপ তৈরি করুন',
        'download_now' => 'সরাসরি ডাউনলোড',
        'prune_old_backups' => 'পুরোনো ব্যাকআপ মুছুন',
        'backup_file' => 'ব্যাকআপ ফাইল',
        'file_size' => 'ফাইল সাইজ',
        'date_and_time' => 'তারিখ ও সময়',
        'relative_time' => 'সময়কাল',
        'create_first_backup' => 'প্রথম ব্যাকআপ তৈরি করুন',
        'no_backups_found' => 'কোনো ব্যাকআপ ফাইল পাওয়া যায়নি',
        'backup_restore_warning' => 'সতর্কতা: ব্যাকআপ রিস্টোর করলে বর্তমানের সকল ডাটাবেজ টেবিল এবং রেকর্ড মুছে গিয়ে ব্যাকআপের ডাটা প্রতিস্থাপিত হবে। রিস্টোর করার পূর্বে বর্তমান ডাটার একটি ব্যাকআপ ডাউনলোড করে রাখা জরুরি।',
        'please_create_a_backup_before_restoring' => 'অসাবধানতাবশত ডাটা ক্ষতি রোধে রিস্টোরের আগে বর্তমান ডাটাবেজের ব্যাকআপ নিন।',
        'restoring_database' => 'ডাটাবেজ রিস্টোর হচ্ছে',
        'restore_now' => 'আপলোড ও রিস্টোর করুন',
        'supported_formats' => 'সমর্থিত ফরম্যাট',
        'max_size' => 'সর্বোচ্চ ফাইলের আকার',
        'database_name' => 'ডাটাবেজ নাম',
        'storage_engine' => 'স্টোরেজ ইঞ্জিন',
        'charset' => 'ক্যারেক্টার সেট',
        'collation' => 'কোলেশন',
        'optimize_all_tables' => 'সকল টেবিল অপ্টিমাইজ করুন',
        'table_name' => 'টেবিলের নাম',
        'engine' => 'ইঞ্জিন',
        'records' => 'রেকর্ড সংখ্যা',
        'data_size' => 'ডাটা সাইজ',
        'index_size' => 'ইনডেক্স সাইজ',
        'overhead' => 'অব্যবহৃত মেমোরি',
        'optimize' => 'অপ্টিমাইজ',
        'automated_database_backup_guide' => 'স্বয়ংক্রিয় ডাটাবেজ ব্যাকআপ নির্দেশিকা',
        'cron_backup_description' => 'আপনি আপনার সার্ভারে (cPanel, aaPanel অথবা Linux Crontab) সিএলআই অথবা ওয়েব ক্রন ব্যবহার করে নিয়মিত স্বয়ংক্রিয় ব্যাকআপ নির্ধারণ করতে পারেন।',
        'recommended_cli_cron_command' => 'সুপারিশকৃত সিএলআই ক্রন কমান্ড (Crontab)',
        'cli_cron_help' => 'কোন প্রকার এইচটিটিপি টাইমআউট ছাড়াই প্রতি রাতে রাত ২টায় চলবে',
        'runs_every_night_at_2am' => 'প্রতিদিন রাত ২:০০ টায় স্বয়ংক্রিয়ভাবে ব্যাকআপ তৈরি করবে।',
        'web_url_cron_alternative' => 'বিকল্প ওয়েব ক্রন ইউআরএল (cURL / Wget)',
        'web_cron_help' => 'সিক্রেট টোকেন ব্যবহার করে এইচটিটিপি গেট রিকোয়েস্টের মাধ্যমে ব্যাকআপ চলবে',
        'security_and_retention' => 'নিরাপত্তা ও ধারণনীতি',
        'retention_policy' => 'ধারণনীতি (Retention)',
        'backups_older_than_30_days_auto_pruned' => '৩০ দিনের বেশি পুরোনো ব্যাকআপ স্বয়ংক্রিয়ভাবে ক্রনের সময় মুছে যায়।',
        'cron_token_verification_required' => 'অননুমোদিত অ্যাক্সেস রোধে রিমোট ক্রনে সিক্রেট টোকেন বাধ্যতামূলক।',
        'backups_compressed_zip_in_secure_dir' => 'ব্যাকআপ ফাইলসমূহ জিপ আর্কাইভ হিসেবে নিরাপদ ডিরেক্টরিতে সংরক্ষিত থাকে।',
        'confirm_database_restore' => 'ডাটাবেজ রিস্টোর নিশ্চিতকরণ',
        'selected_backup' => 'নির্বাচিত ব্যাকআপ ফাইল',
        'proceed_restore' => 'রিস্টোর শুরু করুন',
        'to_restore_from_file_please_use_restore_tab' => 'ব্যাকআপ রিস্টোর করতে অনুগ্রহ করে ডাটাবেজ রিস্টোর ট্যাব ব্যবহার করুন।',
        'old_backups_pruned' => 'টি পুরোনো ব্যাকআপ ফাইল সফলভাবে মুছে ফেলা হয়েছে।',
        'tables_optimized_successfully' => 'সকল ডাটাবেজ টেবিল সফলভাবে অপ্টিমাইজ করা হয়েছে।',
        'database_backup_completed' => 'ডাটাবেজ ব্যাকআপ সফলভাবে সম্পন্ন হয়েছে।',
        'database_backup_failed' => 'ডাটাবেজ ব্যাকআপ তৈরি করতে ব্যর্থ হয়েছে।',
        'backup_restore_completed' => 'ডাটাবেজ ব্যাকআপ সফলভাবে রিস্টোর হয়েছে।',
        'backup_restore_failed' => 'ডাটাবেজ রিস্টোর ব্যর্থ হয়েছে।',
        'backup_deleted_successfully' => 'ব্যাকআপ ফাইলটি সফলভাবে মুছে ফেলা হয়েছে।',
    );

    if ($lang === 'english') {
        return isset($en_dict[$word]) ? $en_dict[$word] : ucwords(str_replace('_', ' ', $word));
    }

    return isset($bn_dict[$word]) ? $bn_dict[$word] : null;
}

function moduleIsEnabled($prefix)
{
    $ci = &get_instance();
    // Global addon status check: if addon is turned off from Addons Manager, it is disabled
    $addon = $ci->db->select('status')->where('prefix', $prefix)->get('addon')->row();
    if ($addon && isset($addon->status) && $addon->status == 0) {
        return 0;
    }
    $role_id = $ci->session->userdata('loggedin_role_id');
    $branchID = $ci->session->userdata('loggedin_branch');
    if ($role_id == 1) {
        return 1;
    }
    $sql = "SELECT IF(`oaf`.`isEnabled` is NULL, 1, `oaf`.`isEnabled`) as `status` FROM `permission_modules` LEFT JOIN `modules_manage` as `oaf` ON `oaf`.`modules_id` = `permission_modules`.`id` AND `oaf`.`branch_id` = " . $ci->db->escape($branchID) . " WHERE `permission_modules`.`prefix` = " . $ci->db->escape($prefix);
    $result = $ci->db->query($sql)->row();
    if (empty($result)) {
        return 1;
    } else {
        return $result->status;
    }
}

function checkSaasLimit($prefix)
{
    $ci = &get_instance();
    $role_id = $ci->session->userdata('loggedin_role_id');
    $branchID = $ci->session->userdata('loggedin_branch');
    if ($role_id == 1) {
        return 1;
    }

    $ci = &get_instance();
    $sql = "SELECT `sb`.`expire_date`, `sb`.`school_id`, `student_limit`, `staff_limit`, `teacher_limit`, `parents_limit` FROM `branch` as `b` LEFT JOIN `saas_subscriptions` AS `sb` ON `sb`.`school_id` = `b`.`id` LEFT JOIN `saas_package` as `sp` ON `sp`.`id` = `sb`.`package_id` WHERE `sb`.`school_id` = " . $ci->db->escape($branchID);
    $row = $ci->db->query($sql)->row();
    if (empty($row)) {
        return 1;
    }

    if ($prefix == 'student') {
        $ci->db->where('branch_id', $branchID);
        $ci->db->group_by('student_id');
        $total_student = $ci->db->count_all_results('enroll');
        if ($total_student > $row->student_limit) {
            return 0;
        } else {
            return 1;
        }
    }

    if ($prefix == 'staff' || $prefix == 'teacher') {
        $ci->db->select('IFNULL(COUNT(staff.id), 0) as snumber');
        $ci->db->from('staff');
        $ci->db->join('login_credential', 'login_credential.user_id = staff.id', 'inner');
        if ($prefix == 'teacher') {
            $ci->db->where('login_credential.role', 3);
        } else {
            $ci->db->where_not_in('login_credential.role', array(1, 3, 6, 7));
        }
        $ci->db->where('staff.branch_id', $branchID);
        $total_staff = $ci->db->get()->row()->snumber;

        if ($prefix == 'teacher') {
            $limit = $row->teacher_limit;
        } else {
            $limit = $row->staff_limit;
        }
        if ($total_staff > $limit) {
            return 0;
        } else {
            return 1;
        }
    }

    if ($prefix == 'parent') {
        $ci->db->where('branch_id', $branchID);
        $total_parents = $ci->db->count_all_results('parent');
        if ($total_parents > $row->parents_limit) {
            return 0;
        } else {
            return 1;
        }
    }
}

function isEnabledSubscription($schoolID = '')
{
    $ci = &get_instance();
    $row = $ci->db->select('id')->where('school_id', $schoolID)->get('saas_subscriptions')->row();
    if (empty($row)) {
        return false;
    } else {
        return true;
    }
}

function get_permission($permission, $can = '')
{
    $ci = &get_instance();
    $role_id = $ci->session->userdata('loggedin_role_id');
    if ($role_id == 1) {
        return true;
    }
    $permissions = get_staff_permissions($role_id);
    foreach ($permissions as $permObject) {
        if ($permObject->permission_prefix == $permission && $permObject->$can == '1') {
            return true;
        }
    }
    return false;
}

function get_staff_permissions($id)
{
    $ci = &get_instance();
    $sql = "SELECT `staff_privileges`.*, `permission`.`id` as `permission_id`, `permission`.`prefix` as `permission_prefix` FROM `staff_privileges` JOIN `permission` ON `permission`.`id`=`staff_privileges`.`permission_id` WHERE `staff_privileges`.`role_id` = " . $ci->db->escape($id);
    $result = $ci->db->query($sql)->result();
    return $result;
}

function get_session_id()
{
    $CI = &get_instance();
    if ($CI->session->has_userdata('set_session_id')) {
        $session_id = $CI->session->userdata('set_session_id');
    } else {
        $session_id = get_global_setting('session_id');
    }
    return $session_id;
}

function is_secure($url)
{
    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) {
        $val = 'https://' . $url;
    } else {
        $val = 'http://' . $url;
    }
    return $val;
}

function get_global_setting($name = '')
{
    static $global_settings_cache = null;
    $CI = &get_instance();
    if ($global_settings_cache === null) {
        $q = $CI->db->query("SELECT * FROM `global_settings` WHERE `id` = 1");
        if ($q && $q->num_rows() > 0) {
            $global_settings_cache = $q->row();
        } else {
            $global_settings_cache = new stdClass();
        }
    }
    $name = trim($name);
    return isset($global_settings_cache->$name) ? $global_settings_cache->$name : null;
}

// is superadmin logged in @return boolean
function is_superadmin_loggedin()
{
    $CI = &get_instance();
    if ($CI->session->userdata('loggedin_role_id') == 1) {
        return true;
    }
    return false;
}

// is admin logged in @return boolean
function is_admin_loggedin()
{
    $CI = &get_instance();
    if ($CI->session->userdata('loggedin_role_id') == 2) {
        return true;
    }
    return false;
}

// is teacher logged in @return boolean
function is_teacher_loggedin()
{
    $CI = &get_instance();
    if ($CI->session->userdata('loggedin_role_id') == 3) {
        return true;
    }
    return false;
}

// is accountant logged in @return boolean
function is_accountant_loggedin()
{
    $CI = &get_instance();
    if ($CI->session->userdata('loggedin_role_id') == 4) {
        return true;
    }
    return false;
}

// is librarian logged in @return boolean
function is_librarian_loggedin()
{
    $CI = &get_instance();
    if ($CI->session->userdata('loggedin_role_id') == 5) {
        return true;
    }
    return false;
}

// is parent logged in @return boolean
function is_parent_loggedin()
{
    $CI = &get_instance();
    if ($CI->session->userdata('loggedin_role_id') == 6) {
        return true;
    }
    return false;
}

// is parent logged in @return boolean
function is_student_loggedin()
{
    $CI = &get_instance();
    if ($CI->session->userdata('loggedin_role_id') == 7) {
        return true;
    }
    return false;
}

// get logged in user id - login credential DB id
function get_loggedin_id()
{
    $ci = &get_instance();
    return $ci->session->userdata('loggedin_id');
}

// get staff db id
function get_loggedin_user_id()
{
    $ci = &get_instance();
    return $ci->session->userdata('loggedin_userid');
}

// get session loggedin
function is_loggedin()
{
    $CI = &get_instance();
    if ($CI->session->has_userdata('loggedin')) {
        return true;
    }
    return false;
}

// get loggedin role name
function loggedin_role_name()
{
    $CI = &get_instance();
    $roleID = $CI->session->userdata('loggedin_role_id');
    return $CI->db->select('name')->where('id', $roleID)->get('roles')->row()->name;
}

function loggedin_role_id()
{
    $ci = &get_instance();
    return $ci->session->userdata('loggedin_role_id');
}

// get logged in user type
function get_loggedin_user_type()
{
    $CI = &get_instance();
    return $CI->session->userdata('loggedin_type');
}

// Check if institution has multiple branches
function is_multiple_branches()
{
    return false;
}

// Check if institution has only a single branch
function is_single_branch()
{
    return true;
}

// Get the default single branch ID
function get_single_branch_id()
{
    static $branch_id = null;
    if ($branch_id !== null) {
        return $branch_id;
    }
    $CI = &get_instance();
    $row = $CI->db->select('id')->order_by('id', 'ASC')->limit(1)->get('branch')->row();
    $branch_id = !empty($row->id) ? (int)$row->id : 1;
    return $branch_id;
}

// get logged in user branch ID
function get_loggedin_branch_id()
{
    $CI = &get_instance();
    $branch = $CI->session->userdata('loggedin_branch');
    if (empty($branch) && is_single_branch()) {
        return get_single_branch_id();
    }
    return $branch;
}

// get parent selected active children Id
function get_activeChildren_id()
{
    $CI = &get_instance();
    return $CI->session->userdata('myChildren_id');
}

// get table name by type and id
function get_type_name_by_id($table, $type_id = '', $field = 'name')
{
    $CI = &get_instance();
    $get = $CI->db->select($field)->from($table)->where('id', $type_id)->limit(1)->get()->row_array();
    if (empty($get) || !isset($get[$field])) {
        return '';
    }
    $val = $get[$field];
    if (in_array($table, ['class', 'section', 'branch', 'roles'])) {
        $cur_lang = (get_current_lang() == 'en') ? 'english' : 'bengali';
        return dvs_clean_bilingual($val, $cur_lang);
    }
    return $val;
}

// set session alert / flashdata
function set_alert($type, $message)
{
    $CI = &get_instance();
    $CI->session->set_flashdata('alert-message-' . $type, $message);
}

// generate md5 hash
function app_generate_hash()
{
    return md5(rand() . microtime() . time() . uniqid());
}

// generate encryption key
function generate_encryption_key()
{
    $CI = &get_instance();
    // In case accessed from my_functions_helper.php
    $CI->load->library('encryption');
    $key = bin2hex($CI->encryption->create_key(16));
    return $key;
}

// generate get image url
function get_image_url($role = '', $file_name = '')
{
    if ($file_name == 'defualt.png' || empty($file_name)) {
        $image_url = base_url('uploads/app_image/defualt.png');
    } else {
        if (file_exists('uploads/images/' . $role . '/' . $file_name)) {
            $image_url = base_url('uploads/images/' . $role . '/' . $file_name);
        } else {
            $image_url = base_url('uploads/app_image/defualt.png');
        }
    }
    return $image_url;
}

// get date format config
function _d($date)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00') {
        return '';
    }
    $formats = 'Y-m-d';
    $get_format = get_global_setting('date_format');
    if ($get_format != '') {
        $formats = $get_format;
    }
    return date($formats, strtotime($date));
}

// delete url
function btn_delete($uri)
{
    return "<button type='button' class='btn btn-danger icon btn-circle' onclick=confirm_modal('" . base_url($uri) . "') ><i class='fas fa-trash-alt'></i></button>";
}

// delete url
function csrf_jquery_token()
{
    $csrf = [get_instance()->security->get_csrf_token_name() => get_instance()->security->get_csrf_hash()];
    return $csrf;
}

function check_hash_restrictions($table, $id, $hash)
{
    $CI = &get_instance();
    if (!$table || !$id || !$hash) {
        show_404();
    }

    $query = $CI->db->select('hash')->from($table)->where('id', $id)->get();
    if ($query->num_rows() > 0) {
        $get_hash = $query->row()->hash;
    } else {
        $get_hash = '';
    }
    if (empty($hash) || ($get_hash != $hash)) {
        show_404();
    }
}

function get_nicetime($date)
{
    $get_format = get_global_setting('date_format');
    if (empty($date)) {
        return "Unknown";
    }
    // Current time as MySQL DATETIME value
    $csqltime = date('Y-m-d H:i:s');
    // Current time as Unix timestamp
    $ptime = strtotime($date);
    $ctime = strtotime($csqltime);

    //Now calc the difference between the two
    $timeDiff = floor(abs($ctime - $ptime) / 60);

    //Now we need find out whether or not the time difference needs to be in
    //minutes, hours, or days
    if ($timeDiff < 2) {
        $timeDiff = "Just now";
    } elseif ($timeDiff > 2 && $timeDiff < 60) {
        $timeDiff = floor(abs($timeDiff)) . " minutes ago";
    } elseif ($timeDiff > 60 && $timeDiff < 120) {
        $timeDiff = floor(abs($timeDiff / 60)) . " hour ago";
    } elseif ($timeDiff < 1440) {
        $timeDiff = floor(abs($timeDiff / 60)) . " hours ago";
    } elseif ($timeDiff > 1440 && $timeDiff < 2880) {
        $timeDiff = floor(abs($timeDiff / 1440)) . " day ago";
    } elseif ($timeDiff > 2880) {
        $timeDiff = date($get_format, $ptime);
    }
    return $timeDiff;
}

function bytesToSize($path, $filesize = '')
{
    if (!is_numeric($filesize)) {
        $bytes = sprintf('%u', filesize($path));
    } else {
        $bytes = $filesize;
    }
    if ($bytes > 0) {
        $unit = intval(log($bytes, 1024));
        $units = [
            'B',
            'KB',
            'MB',
            'GB',
        ];
        if (array_key_exists($unit, $units) === true) {
            return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
        }
    }
    return $bytes;
}

function array_to_object($array)
{
    if (!is_array($array) && !is_object($array)) {
        return new stdClass();
    }
    return json_decode(json_encode((object) $array));
}

function access_denied()
{
    set_alert('error', translate('access_denied'));
    redirect(site_url('dashboard'));
}

function ajax_access_denied()
{
    set_alert('error', translate('access_denied'));
    $array = array('status' => 'access_denied');
    echo json_encode($array);
    exit();
}

function slugify($text)
{
    // replace non letter or digits by _
    $text = preg_replace('~[^\pL\d]+~u', '_', $text);

    // transliterate if possible to ASCII
    if (function_exists('iconv')) {
        $trans = @iconv('utf-8', 'us-ascii//TRANSLIT//IGNORE', $text);
        if ($trans !== false && trim(preg_replace('~[^-\w]+~', '', $trans), '_') !== '') {
            $text = preg_replace('~[^-\w]+~', '', $trans);
        }
    }

    // lowercase
    if (function_exists('mb_strtolower')) {
        $text = mb_strtolower($text, 'UTF-8');
    } else {
        $text = strtolower($text);
    }

    // remove unwanted characters (allow unicode letters, numbers, _, -)
    $text = preg_replace('~[^\pL\d_-]+~u', '', $text);

    // trim
    $text = trim($text, '_');

    // remove duplicated - symbols
    $text = preg_replace('~[_-]+~', '_', $text);

    return $text;
}

// website menu list
function web_menu_list($publish = '', $default = '', $branchID = '')
{
    $CI = &get_instance();
    if (empty($branchID)) {
        $branchID = $CI->home_model->getDefaultBranch();
    }
    $CI->db->select('*,if(front_cms_menu_visible.name is null, front_cms_menu.title, front_cms_menu_visible.name) as title, front_cms_menu_visible.invisible');
    $CI->db->from('front_cms_menu');
    $CI->db->join('front_cms_menu_visible', 'front_cms_menu_visible.menu_id = front_cms_menu.id and front_cms_menu_visible.branch_id = ' . $branchID, 'left');
    if ($publish != '') {
        $CI->db->where('front_cms_menu.publish', $publish);
    }
    if ($default != '') {
        $CI->db->where('front_cms_menu.system', $default);
    }
    $CI->db->order_by('front_cms_menu.ordering', 'asc');
    $CI->db->where_in('front_cms_menu.branch_id', array(0, $branchID));
    $result = $CI->db->get()->result_array();
    return $result;
}

function get_request_url()
{
    $url = $_SERVER['QUERY_STRING'];
    $url = (!empty($url) ? '?' . $url : '');
    return $url;
}

function delete_dir($dirPath)
{
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            delete_dir($file);
        } else {
            unlink($file);
        }
    }
    if (rmdir($dirPath)) {
        return true;
    }
    return false;
}

function currencyFormat($amount = 0)
{
    $CI = &get_instance();
    $array              = $CI->data['global_config'];
    $currency           = $array['currency'];
    $currency_symbol    = $array['currency_symbol'];
    $currency_formats   = $array['currency_formats'];
    $symbol_position    = $array['symbol_position'];

    $amount = empty($amount) ? 0 : $amount;
    $value = $amount;
    if ($currency_formats == 1) {
        $value = number_format($amount, 2, '.', '');
    } elseif ($currency_formats == 2) {
        $value = moneyFormatIndia($amount);
    } elseif ($currency_formats == 3) {
        $value = number_format($amount, 3, '.', ',');
    } elseif ($currency_formats == 4) {
        $value = number_format($amount, 2, ',', '.');
    } elseif ($currency_formats == 5) {
        $value = number_format($amount, 2, '.', ',');
    } elseif ($currency_formats == 6) {
        $value = number_format($amount, 2, ',', ' ');
    } elseif ($currency_formats == 7) {
        $value = number_format($amount, 2, '.', ' ');
    } elseif ($currency_formats == 8) {
        $value = $amount;
    }

    if ($symbol_position == 1) {
        $value = $currency_symbol . $value; 
    } elseif ($symbol_position == 2) {
        $value = $value . $currency_symbol;
    } elseif ($symbol_position == 3) {
        $value = $currency_symbol . " " . $value;
    } elseif ($symbol_position == 4) {
        $value = $value . " " . $currency_symbol;
    } elseif ($symbol_position == 5) {
        $value = $currency . " " . $value;
    } elseif ($symbol_position == 6) {
        $value = $value . " " . $currency;
    }
    return $value;
}

function moneyFormatIndia($num)
{
    $explrestunits = "" ;
    $num = preg_replace('/,+/', '', $num);
    $words = explode(".", $num);
    $des = "00";
    if(count($words)<=2){
        $num=$words[0];
        if(count($words)>=2){$des=$words[1];}
        if(strlen($des)<2){$des="$des";}else{$des=substr($des,0,2);}
    }
    if(strlen($num)>3){
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++){
            // creates each of the 2's group and adds a comma to the end
            if($i==0)
            {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            }else{
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return "$thecash.$des"; // writes the final format where $currency is the currency symbol.
}

function getEnrollToStudentID($enroll_id = '')
{
    $CI = &get_instance();
    $get = $CI->db->select('student_id')->from('enroll')->where('id', $enroll_id)->limit(1)->get()->row()->student_id;
    return $get;
}

function version_combine()
{
    $custom_css = FCPATH . 'assets/css/custom-style.css';
    $ramom_css = FCPATH . 'assets/css/ramom.css';
    $time = 0;
    if (file_exists($custom_css)) {
        $time = max($time, filemtime($custom_css));
    }
    if (file_exists($ramom_css)) {
        $time = max($time, filemtime($ramom_css));
    }
    return $time > 0 ? $time : md5(APP_VERSION); 
}

function img_reload()
{
    return "?src=" . time();
}

function get_profile_bg_url()
{
    $file = 'assets/images/profile_bg.jpg';
    $path = FCPATH . $file;
    if (file_exists($path)) {
        return base_url($file) . '?v=' . filemtime($path);
    }
    return base_url($file) . '?v=' . time();
}

function get_frontend_logo_url($type = 'light')
{
    $CI = &get_instance();
    $branch_id = 1;
    if (!empty($CI->home_model) && method_exists($CI->home_model, 'getDefaultBranch')) {
        $branch_id = $CI->home_model->getDefaultBranch();
    } elseif (!empty($CI->application_model) && method_exists($CI->application_model, 'get_branch_id')) {
        $branch_id = $CI->application_model->get_branch_id();
    }

    // 1. Check CMS setting logo from database
    if (isset($CI->db) && $CI->db->table_exists('front_cms_setting')) {
        $setting = $CI->db->select('logo')->where('branch_id', $branch_id)->get('front_cms_setting')->row_array();
        if (!empty($setting['logo'])) {
            $logo_file = $setting['logo'];
            if (file_exists(FCPATH . 'uploads/frontend/images/' . $logo_file)) {
                return base_url('uploads/frontend/images/' . $logo_file);
            }
            if (file_exists(FCPATH . 'uploads/app_image/' . $logo_file)) {
                return base_url('uploads/app_image/' . $logo_file);
            }
        }
    }

    // 2. Check branch image from application_model
    if (!empty($CI->application_model) && method_exists($CI->application_model, 'getBranchImage')) {
        $branch_logo = $CI->application_model->getBranchImage($branch_id, 'logo');
        if (!empty($branch_logo)) {
            return $branch_logo;
        }
        $print_logo = $CI->application_model->getBranchImage($branch_id, 'printing-logo');
        if (!empty($print_logo)) {
            return $print_logo;
        }
    }

    // 3. Check standard branch logo filenames
    $branch_candidates = [
        "uploads/frontend/images/logo{$branch_id}.png",
        "uploads/frontend/images/logo{$branch_id}.jpg",
        "uploads/frontend/images/logo{$branch_id}.jpeg",
        "uploads/app_image/logo-{$branch_id}.png",
        "uploads/app_image/logo-{$branch_id}.jpg"
    ];
    foreach ($branch_candidates as $cand) {
        if (file_exists(FCPATH . $cand)) {
            return base_url($cand);
        }
    }

    // 4. Default fallbacks
    if (file_exists(FCPATH . 'uploads/app_image/logo.png')) {
        return base_url('uploads/app_image/logo.png');
    }
    if (file_exists(FCPATH . 'uploads/app_image/logo-1.png')) {
        return base_url('uploads/app_image/logo-1.png');
    }

    return base_url('uploads/app_image/logo.png');
}

function get_current_lang()
{
    $CI = &get_instance();
    if ($CI->session->has_userdata('set_lang')) {
        $set_lang = $CI->session->userdata('set_lang');
    } else {
        $set_lang = get_global_setting('translation');
    }
    if (empty($set_lang)) {
        $set_lang = 'bengali';
    }
    return ($set_lang == 'english' || $set_lang == 'en') ? 'en' : 'bn';
}

function get_lang_field($row, $field, $default = '')
{
    $lang = get_current_lang();
    if (is_object($row)) {
        $row = (array) $row;
    }
    if (!is_array($row)) {
        return $default;
    }
    if ($lang == 'en') {
        if (!empty($row[$field . '_en'])) {
            return $row[$field . '_en'];
        }
        if (!empty($row[$field])) {
            return $row[$field];
        }
    } else {
        if (!empty($row[$field])) {
            return $row[$field];
        }
        if (!empty($row[$field . '_en'])) {
            return $row[$field . '_en'];
        }
    }
    return $default;
}


function get_frontend_visitor_stats()
{
    $CI = &get_instance();
    $ip = $CI->input->ip_address();
    $today = date('Y-m-d');
    $this_month = date('Y-m');

    $total_tracked = 0;
    $today_count = 0;
    $month_count = 0;

    if (!empty($ip) && $CI->db->table_exists('front_cms_visitor_stats')) {
        $exists = $CI->db->get_where('front_cms_visitor_stats', ['ip_address' => $ip, 'visit_date' => $today])->row_array();
        if (!$exists) {
            $CI->db->insert('front_cms_visitor_stats', [
                'ip_address' => $ip,
                'visit_date' => $today,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
        $total_tracked = $CI->db->count_all('front_cms_visitor_stats');
        $today_count = $CI->db->where('visit_date', $today)->count_all_results('front_cms_visitor_stats');
        $month_count = $CI->db->like('visit_date', $this_month, 'after')->count_all_results('front_cms_visitor_stats');
    }

    $baseline_total = 672;
    $baseline_month = 54;
    $baseline_today = 13;

    return [
        'total' => $baseline_total + $total_tracked,
        'month' => $baseline_month + $month_count,
        'today' => $baseline_today + $today_count,
    ];
}

// --------------------------------------------------
// Redis Cache Helpers (radius cache)
// --------------------------------------------------
if (!function_exists('redis_cache')) {
    function redis_cache()
    {
        static $instance = null;
        if ($instance === null) {
            $CI =& get_instance();
            if (isset($CI->redis_cache)) {
                $instance = $CI->redis_cache;
            } else {
                if (file_exists(APPPATH . 'libraries/Redis_cache.php')) {
                    require_once APPPATH . 'libraries/Redis_cache.php';
                    $instance = new Redis_cache();
                }
            }
        }
        return $instance;
    }
}

if (!function_exists('redis_get')) {
    function redis_get($key, $default = null)
    {
        $cache = redis_cache();
        return $cache ? $cache->get($key, $default) : $default;
    }
}

if (!function_exists('redis_set')) {
    function redis_set($key, $value, $ttl = 3600)
    {
        $cache = redis_cache();
        return $cache ? $cache->set($key, $value, $ttl) : false;
    }
}

if (!function_exists('redis_delete')) {
    function redis_delete($key)
    {
        $cache = redis_cache();
        return $cache ? $cache->delete($key) : false;
    }
}

if (!function_exists('redis_remember')) {
    function redis_remember($key, $ttl, $callback)
    {
        $cache = redis_cache();
        return $cache ? $cache->remember($key, $ttl, $callback) : call_user_func($callback);
    }
}

if (!function_exists('get_office_accounting_payvia_list')) {
    function get_office_accounting_payvia_list()
    {
        $ci = &get_instance();
        // Desired exact order: Cash, Bkash, Nagad, Bank Transfer, Cheque, Card, Others
        $ordered_names = array('Cash', 'Bkash', 'Nagad', 'Bank Transfer', 'Cheque', 'Card', 'Others');
        
        $types = $ci->db->get('payment_types')->result_array();
        $map = array();
        foreach ($types as $t) {
            $map[strtolower(trim($t['name']))] = $t;
        }

        $is_bn = (function_exists('get_current_lang') && get_current_lang() === 'bn');
        $bn_names = array(
            'Cash' => 'ক্যাশ / নগদ',
            'Bkash' => 'বিকাশ',
            'Nagad' => 'নগদ',
            'Bank Transfer' => 'ব্যাংক ট্রান্সফার',
            'Cheque' => 'চেক',
            'Card' => 'কার্ড',
            'Others' => 'অন্যান্য'
        );

        $list = array('' => translate('select'));
        foreach ($ordered_names as $name) {
            $key = strtolower(trim($name));
            $disp = ($is_bn && isset($bn_names[$name])) ? $bn_names[$name] : $name;
            if ($key == 'others' && !isset($map['others']) && isset($map['other'])) {
                $list[$map['other']['id']] = $disp;
                continue;
            }
            if (isset($map[$key])) {
                $list[$map[$key]['id']] = $disp;
            } else {
                // If missing in DB, insert it
                $ci->db->insert('payment_types', array('name' => $name, 'branch_id' => 0));
                $new_id = $ci->db->insert_id();
                $list[$new_id] = $disp;
                $map[$key] = array('id' => $new_id, 'name' => $name);
            }
        }
        return $list;
    }
}

if (!function_exists('dvs_number_to_words_bangla')) {
    function dvs_number_to_words_bangla($number) {
        $words = array(
            0 => 'শূন্য', 1 => 'এক', 2 => 'দুই', 3 => 'তিন', 4 => 'চার', 5 => 'পাঁচ',
            6 => 'ছয়', 7 => 'সাত', 8 => 'আট', 9 => 'নয়', 10 => 'দশ',
            11 => 'এগারো', 12 => 'বারো', 13 => 'তেরো', 14 => 'চৌদ্দ', 15 => 'পনেরো',
            16 => 'ষোলো', 17 => 'সতেরো', 18 => 'আঠারো', 19 => 'উনিশ', 20 => 'বিশ',
            21 => 'একুশ', 22 => 'বাইশ', 23 => 'তেইশ', 24 => 'চব্বিশ', 25 => 'পঁচিশ',
            26 => 'ছাব্বিশ', 27 => 'সাতাশ', 28 => 'আঠাশ', 29 => 'উনত্রিশ', 30 => 'ত্রিশ',
            31 => 'একত্রিশ', 32 => 'বত্রিশ', 33 => 'তেত্রিশ', 34 => 'চৌত্রিশ', 35 => 'পঁয়ত্রিশ',
            36 => 'ছত্রিশ', 37 => 'সাঁইত্রিশ', 38 => 'আটত্রিশ', 39 => 'উনচল্লিশ', 40 => 'চল্লিশ',
            41 => 'একচল্লিশ', 42 => 'বিয়াল্লিশ', 43 => 'তেতাল্লিশ', 44 => 'চুয়াল্লিশ', 45 => 'পঁয়তাল্লিশ',
            46 => 'ছেচল্লিশ', 47 => 'সাতচল্লিশ', 48 => 'আটচল্লিশ', 49 => 'উনপঞ্চাশ', 50 => 'পঞ্চাশ',
            51 => 'একান্ন', 52 => 'বায়ান্ন', 53 => 'তিপ্পান্ন', 54 => 'চুয়ান্ন', 55 => 'পঞ্চান্ন',
            56 => 'ছাপ্পান্ন', 57 => 'সাতান্ন', 58 => 'আটান্ন', 59 => 'উনষাট', 60 => 'ষাট',
            61 => 'একষট্টি', 62 => 'বাষট্টি', 63 => 'তেষট্টি', 64 => 'চৌষট্টি', 65 => 'পঁয়ষট্টি',
            66 => 'ছেষট্টি', 67 => 'সাতষট্টি', 68 => 'আটষট্টি', 69 => 'উনসত্তর', 70 => 'সত্তর',
            71 => 'একাত্তর', 72 => 'বাহাত্তর', 73 => 'তিয়াত্তর', 74 => 'চুয়াত্তর', 75 => 'পঁচাত্তর',
            76 => 'ছিয়াত্তর', 77 => 'সাতাত্তর', 78 => 'আটাত্তর', 79 => 'উনআশি', 80 => 'আশি',
            81 => 'একাশি', 82 => 'বিরাশি', 83 => 'তিরাশি', 84 => 'চুরাশি', 85 => 'পঁচাশি',
            86 => 'ছিয়াশি', 87 => 'সাতাশি', 88 => 'আটাশি', 89 => 'উননব্বই', 90 => 'নব্বই',
            91 => 'একানব্বই', 92 => 'বিরানব্বই', 93 => 'তিরানব্বই', 94 => 'চুরানব্বই', 95 => 'পঁচানব্বই',
            96 => 'ছিয়ানব্বই', 97 => 'সাতানব্বই', 98 => 'আটানব্বই', 99 => 'নিরানব্বই'
        );
        $number = (float)$number;
        $integer = (int)floor($number);
        $fraction = (int)round(($number - $integer) * 100);

        if ($integer == 0 && $fraction == 0) {
            return 'শূন্য টাকা মাত্র';
        }

        $crore = (int)floor($integer / 10000000);
        $integer %= 10000000;
        $lakh = (int)floor($integer / 100000);
        $integer %= 100000;
        $thousand = (int)floor($integer / 1000);
        $integer %= 1000;
        $hundred = (int)floor($integer / 100);
        $remainder = $integer % 100;

        $result = '';
        if ($crore > 0) {
            $result .= dvs_number_to_words_bangla($crore) . ' কোটি ';
        }
        if ($lakh > 0) {
            $result .= (isset($words[$lakh]) ? $words[$lakh] : $lakh) . ' লাখ ';
        }
        if ($thousand > 0) {
            $result .= (isset($words[$thousand]) ? $words[$thousand] : $thousand) . ' হাজার ';
        }
        if ($hundred > 0) {
            $result .= (isset($words[$hundred]) ? $words[$hundred] : $hundred) . ' শত ';
        }
        if ($remainder > 0) {
            $result .= (isset($words[$remainder]) ? $words[$remainder] : $remainder) . ' ';
        }

        $result = trim($result) . ' টাকা';
        if ($fraction > 0) {
            $result .= ' ও ' . (isset($words[$fraction]) ? $words[$fraction] : $fraction) . ' পয়সা';
        }
        return $result . ' মাত্র';
    }
}

if (!function_exists('dvs_number_to_words_english')) {
    function dvs_number_to_words_english($num) {
        $ones = array(
            0 => "Zero", 1 => "One", 2 => "Two", 3 => "Three", 4 => "Four", 5 => "Five",
            6 => "Six", 7 => "Seven", 8 => "Eight", 9 => "Nine", 10 => "Ten",
            11 => "Eleven", 12 => "Twelve", 13 => "Thirteen", 14 => "Fourteen", 15 => "Fifteen",
            16 => "Sixteen", 17 => "Seventeen", 18 => "Eighteen", 19 => "Nineteen"
        );
        $tens = array(
            2 => "Twenty", 3 => "Thirty", 4 => "Forty", 5 => "Fifty",
            6 => "Sixty", 7 => "Seventy", 8 => "Eighty", 9 => "Ninety"
        );

        $num = (float)$num;
        $integer = (int)floor($num);
        $fraction = (int)round(($num - $integer) * 100);

        if ($integer == 0) {
            $out = "Zero Taka";
        } else {
            $out = "";
            $crore = (int)floor($integer / 10000000);
            $integer %= 10000000;
            $lakh = (int)floor($integer / 100000);
            $integer %= 100000;
            $thousand = (int)floor($integer / 1000);
            $integer %= 1000;
            $hundred = (int)floor($integer / 100);
            $remainder = $integer % 100;

            if ($crore > 0) {
                $out .= dvs_number_to_words_english($crore) . " Crore ";
            }
            if ($lakh > 0) {
                if ($lakh < 20) {
                    $out .= $ones[$lakh] . " Lakh ";
                } else {
                    $out .= $tens[(int)($lakh / 10)] . " " . ($lakh % 10 ? $ones[$lakh % 10] : "") . " Lakh ";
                }
            }
            if ($thousand > 0) {
                if ($thousand < 20) {
                    $out .= $ones[$thousand] . " Thousand ";
                } else {
                    $out .= $tens[(int)($thousand / 10)] . " " . ($thousand % 10 ? $ones[$thousand % 10] : "") . " Thousand ";
                }
            }
            if ($hundred > 0) {
                $out .= $ones[$hundred] . " Hundred ";
            }
            if ($remainder > 0) {
                if ($remainder < 20) {
                    $out .= $ones[$remainder] . " ";
                } else {
                    $out .= $tens[(int)($remainder / 10)] . " " . ($remainder % 10 ? $ones[$remainder % 10] : "") . " ";
                }
            }
            $out = trim(preg_replace('/\s+/', ' ', $out)) . " Taka";
        }

        if ($fraction > 0) {
            $out .= " and " . $fraction . "/100 Paisa";
        }
        return $out . " Only";
    }
}

if (!function_exists('get_draft_admission_count')) {
    function get_draft_admission_count($branch_id = null)
    {
        $ci = &get_instance();
        if (!isset($ci->db)) {
            return 0;
        }
        if (!$ci->db->table_exists('student_admission_draft')) {
            return 0;
        }
        if (empty($branch_id)) {
            $branch_id = $ci->session->userdata('loggedin_branch_id');
        }
        $ci->db->from('student_admission_draft');
        if (!empty($branch_id)) {
            $ci->db->where('branch_id', (int)$branch_id);
        }
        return (int)$ci->db->count_all_results();
    }
}

