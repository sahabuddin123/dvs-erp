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
        if ($val !== '') {
            $fallback = dvs_language_fallback($word, $set_lang);
            if ($fallback !== null && dvs_is_bad_translation($val, $word)) {
                return $fallback;
            }
            return $val;
        } else {
            $fallback = dvs_language_fallback($word, $set_lang);
            if ($fallback !== null) {
                return $fallback;
            }
            return ($row && !empty($row->english)) ? $row->english : ucwords(str_replace('_', ' ', $word));
        }
    } else {
        $fallback = dvs_language_fallback($word, $set_lang);
        $english_label = ucwords(str_replace('_', ' ', $word));
        $arrayData = array(
            'word' => $word,
            'english' => $english_label,
            'bengali' => ($fallback !== null) ? $fallback : $english_label,
        );
        $CI->db->insert('languages', $arrayData);
        if ($set_lang == 'bengali' && $fallback !== null) {
            return $fallback;
        }
        return $english_label;
    }
}

function dvs_is_bad_translation($val, $word)
{
    $val = trim((string)$val);
    if ($val === '') {
        return true;
    }
    if (strpos($val, '??') !== false) {
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
    if (strcasecmp($val, $word) === 0 || strcasecmp($val, str_replace('_', ' ', $word)) === 0) {
        return true;
    }
    return false;
}

function dvs_language_fallback($word, $lang = 'bengali')
{
    if ($lang !== 'bengali') {
        return null;
    }
    static $bn_dict = array(
        'dashboard' => 'ড্যাশবোর্ড',
        'branch' => 'শাখা',
        'all_branches' => 'সকল শাখা',
        'inventory' => 'ইনভেন্টরি / মালামাল',
        'frontend' => 'ওয়েবসাইট সিএমএস',
        'reception' => 'রিসেপশন ও ফ্রন্ট ডেস্ক',
        'admission' => 'ভর্তি কার্যক্রম',
        'student_details' => 'শিক্ষার্থী তথ্য ও তালিকা',
        'parents' => 'অভিভাবক বৃন্দ',
        'employee' => 'কর্মকর্তা ও কর্মচারী',
        'card_management' => 'আইডি ও প্রবেশপত্র কার্ড',
        'certificate' => 'সার্টিফিকেট ও সনদ',
        'certificates' => 'সার্টিফিকেট সমূহ',
        'human_resource' => 'মানব সম্পদ (HR)',
        'hrm' => 'মানব সম্পদ (HRM)',
        'academic' => 'একাডেমিক ব্যবস্থাপনা',
        'live_class_rooms' => 'অনলাইন / লাইভ ক্লাস',
        'live_class' => 'লাইভ ক্লাস',
        'live_class_reports' => 'লাইভ ক্লাস রিপোর্ট',
        'attachments_book' => 'ডাউনলোড সেন্টার / ফাইল',
        'attachment_type' => 'ফাইলের ধরন',
        'upload_content' => 'ফাইল আপলোড',
        'homework' => 'বাড়ির কাজ (Homework)',
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
        'lesson_plan' => 'পাঠ পরিকল্পনা (Lesson Plan)',
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
        'alumni' => 'প্রাক্তন শিক্ষার্থী (Alumni)',
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
        'cron_job' => 'স্বয়ংক্রিয় শিডিউলার (Cron)',
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
        'faq' => 'সাধারণ জিজ্ঞাসা (FAQ)',
        'gallery_category' => 'গ্যালারি ক্যাটাগরি',
        'gallery' => 'ছবি গ্যালারি',
        'news' => 'সংবাদ ও বিজ্ঞপ্তি',
        'admission_enquiry' => 'ভর্তি অনুসন্ধান',
        'postal_record' => 'চিঠিপত্র ও ডাক রেকর্ড',
        'call_log' => 'কল লগ',
        'visitor_log' => 'দর্শনার্থী রেজিস্টার',
        'complaint' => 'অভিযোগ বক্স',
        'product' => 'পণ্য / মালামাল',
        'store' => 'স্টোর / গুদাম',
        'supplier' => 'সরবরাহকারী',
        'unit' => 'পরিমাপের একক',
        'purchase' => 'মালামাল ক্রয়',
        'sales' => 'মালামাল বিক্রয়',
        'issue' => 'মালামাল ইস্যু',
        'issues' => 'ইস্যু সমূহ',
        'stock' => 'স্টক / মজুদ',
        'my_issued_book' => 'আমার ইস্যুকৃত বই',
        'id_card' => 'আইডি কার্ড (ID Card)',
        'admit_card' => 'প্রবেশপত্র (Admit Card)',
        'templete' => 'কার্ড টেমপ্লেট',
        'template' => 'টেমপ্লেট',
        'class' => 'শ্রেণি',
        'section' => 'শাখা / সেকশন',
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
        'category' => 'ক্যাটাগরি / বিভাগ',
        'student_list' => 'শিক্ষার্থী তালিকা',
        'deactivate_reason' => 'স্থগিতের কারণ',
        'login_credential' => 'লগইন তথ্য (ক্রেডেনশিয়াল)',
        'disable_authentication' => 'লগইন নিষ্ক্রিয়করণ',
        'student' => 'শিক্ষার্থী',
        'previous_school_details' => 'পূর্ববর্তী স্কুলের বিবরণ',
        'sibling_report' => 'সহোদর / ভাই-বোন রিপোর্ট',
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
        'payroll' => 'বেতন-ভাতা (Payroll)',
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
        'new_deposit' => 'নতুন জমা / আয়',
        'new_expense' => 'নতুন খরচ / ব্যয়',
        'all_transactions' => 'সকল লেনদেন বিবরণী',
        'voucher' => 'ভাউচার',
        'balance' => 'অবশিষ্ট ব্যালেন্স',
        'opening_balance' => 'প্রারম্ভিক ব্যালেন্স',
        'receipts_report' => 'আদায় ও প্রাপ্তি রিপোর্ট',
        'expense' => 'ব্যয় / খরচ',
        'hostel' => 'হোস্টেল / ছাত্রাবাস',
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
        'stoppage' => 'স্টপেজ / বিরতি',
        'pickup_point' => 'পিকআপ পয়েন্ট',
        'vehicle_no' => 'যানবাহন নং',
        'exam' => 'পরীক্ষা',
        'examination' => 'পরীক্ষা ব্যবস্থাপনা',
        'exam_term' => 'পরীক্ষার মেয়াদ / টার্ম',
        'exam_setup' => 'পরীক্ষা সেটআপ',
        'exam_result' => 'পরীক্ষার ফলাফল',
        'exam_master' => 'পরীক্ষা মাস্টার',
        'exam_hall' => 'পরীক্ষা হল',
        'marksheet' => 'মার্কশীট / নম্বরপত্র',
        'tabulation_sheet' => 'টেবুলেশন শীট',
        'sheet' => 'শীট',
        'generate_position' => 'মেধাক্রম / পজিশন নির্ধারণ',
        'position' => 'মেধাক্রম',
        'online_exam' => 'অনলাইন পরীক্ষা',
        'question_bank' => 'প্রশ্ন ব্যাংক',
        'question_group' => 'প্রশ্ন গ্রুপ',
        'lesson' => 'পাঠ / অধ্যায়',
        'topic' => 'টপিক / বিষয়বস্তু',
        'manage_lesson' => 'পাঠ ব্যবস্থাপনা',
        'manage_topic' => 'টপিক ব্যবস্থাপনা',
        'manage_syllabus' => 'সিলেবাস ব্যবস্থাপনা',
        'manage_syllabus_status' => 'সিলেবাস অগ্রগতি ব্যবস্থাপনা',
        'overview_reports' => 'সার্বিক প্রতিবেদন',
        'daily_reports' => 'দৈনিক রিপোর্ট',
        'progress' => 'অগ্রগতি',
        'period' => 'পিরিয়ড / সময়',
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
        'action' => 'পদক্ষেপ / অ্যাকশন',
        'type' => 'প্রকার / ধরন',
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
        'city' => 'শহর / জেলা',
        'state' => 'বিভাগ / রাজ্য',
        'remarks' => 'মন্তব্য',
        'status' => 'অবস্থা',
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
        'transitions' => 'ট্রানজিশন / লেনদেন',
        'statement' => 'বিবরণী',
    );
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

// get logged in user type
function get_loggedin_branch_id()
{
    $CI = &get_instance();
    return $CI->session->userdata('loggedin_branch');
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
    return $get[$field];
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
    if ($type === 'dark') {
        $candidates = [
            'assets/frontend/dvs/storage/uploads/settings/logo_dark_1781374935.png',
            'assets/frontend/dvs/storage/uploads/settings/logo_dark_1773127288.png',
            'assets/frontend/dvs/storage/uploads/settings/logo_dark_1761174935.png',
            'uploads/app_image/logo.png',
            'uploads/app_image/logo-1.png'
        ];
    } else {
        $candidates = [
            'assets/frontend/dvs/storage/uploads/settings/logo_light_1773127288.png',
            'assets/frontend/dvs/storage/uploads/settings/logo_light_1763242171.png',
            'uploads/app_image/logo.png',
            'uploads/app_image/logo-1.png'
        ];
    }
    foreach ($candidates as $cand) {
        if (file_exists(FCPATH . $cand)) {
            return base_url($cand);
        }
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

        $list = array('' => translate('select'));
        foreach ($ordered_names as $name) {
            $key = strtolower(trim($name));
            if ($key == 'others' && !isset($map['others']) && isset($map['other'])) {
                $list[$map['other']['id']] = 'Others';
                continue;
            }
            if (isset($map[$key])) {
                $list[$map[$key]['id']] = $name;
            } else {
                // If missing in DB, insert it
                $ci->db->insert('payment_types', array('name' => $name, 'branch_id' => 0));
                $new_id = $ci->db->insert_id();
                $list[$new_id] = $name;
                $map[$key] = array('id' => $new_id, 'name' => $name);
            }
        }
        return $list;
    }
}
