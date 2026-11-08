-- ==============================================================================
-- DREAM VALLEY SCHOOL (DVS) - COMPLETE LIVE SERVER DATABASE MIGRATION SCRIPT
-- Host: erp.dreamvalleyschool.com / cPanel / aaPanel / phpMyAdmin
-- Date: 2026-11-03
-- ==============================================================================

-- ------------------------------------------------------------------------------
-- পার্ট ১: টেবিল স্ট্রাকচার ও মিসিং কলাম যোগ করা (Schema Alterations)
-- ------------------------------------------------------------------------------

-- ১.১ student টেবিলে active কলাম যোগ (স্টুডেন্ট লিস্ট ৫০০ এরর ফিক্স)
ALTER TABLE `student` ADD COLUMN `active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `photo`;
UPDATE `student` s 
INNER JOIN `login_credential` l ON l.user_id = s.id AND l.role = 7 
SET s.active = l.active;

-- ১.২ parent টেবিলে পিতা-মাতার আলাদা মোবাইল ও ছবি কলাম
ALTER TABLE `parent` ADD COLUMN `father_mobileno` VARCHAR(20) NULL DEFAULT NULL AFTER `mother_name`;
ALTER TABLE `parent` ADD COLUMN `mother_mobileno` VARCHAR(20) NULL DEFAULT NULL AFTER `father_mobileno`;
ALTER TABLE `parent` ADD COLUMN `father_photo` VARCHAR(255) NULL DEFAULT NULL AFTER `photo`;
ALTER TABLE `parent` ADD COLUMN `mother_photo` VARCHAR(255) NULL DEFAULT NULL AFTER `father_photo`;

-- ১.৩ online_admission টেবিলে পিতা-মাতার আলাদা মোবাইল ও ছবি কলাম
ALTER TABLE `online_admission` ADD COLUMN `father_mobile_no` VARCHAR(20) NULL DEFAULT NULL AFTER `mother_name`;
ALTER TABLE `online_admission` ADD COLUMN `mother_mobile_no` VARCHAR(20) NULL DEFAULT NULL AFTER `father_mobile_no`;
ALTER TABLE `online_admission` ADD COLUMN `father_photo` VARCHAR(255) NULL DEFAULT NULL AFTER `grd_photo`;
ALTER TABLE `online_admission` ADD COLUMN `mother_photo` VARCHAR(255) NULL DEFAULT NULL AFTER `father_photo`;

-- ১.৪ front_cms_setting টেবিলে ইংরেজি/বাইলিঙ্গুয়াল কলাম ও টেক্সট সাইজ বৃদ্ধি
ALTER TABLE `front_cms_setting` ADD COLUMN `application_title_en` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `front_cms_setting` ADD COLUMN `site_tagline` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `front_cms_setting` ADD COLUMN `site_tagline_en` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `front_cms_setting` ADD COLUMN `address_en` TEXT NULL DEFAULT NULL;
ALTER TABLE `front_cms_setting` ADD COLUMN `footer_about_text_en` TEXT NULL DEFAULT NULL;
ALTER TABLE `front_cms_setting` ADD COLUMN `copyright_text_en` TEXT NULL DEFAULT NULL;

ALTER TABLE `front_cms_setting` MODIFY `footer_about_text` TEXT NULL;
ALTER TABLE `front_cms_setting` MODIFY `copyright_text` TEXT NULL;
ALTER TABLE `front_cms_setting` MODIFY `address` TEXT NULL;

-- ১.৫ transport_fee_fine টেবিল তৈরি (যদি লাইভে না থাকে)
CREATE TABLE IF NOT EXISTS `transport_fee_fine` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int DEFAULT NULL,
  `session_id` int DEFAULT NULL,
  `month` varchar(10) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `fine_type` varchar(50) DEFAULT NULL,
  `fine_value` decimal(10,2) DEFAULT NULL,
  `fee_frequency` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ------------------------------------------------------------------------------
-- পার্ট ২: ড্রিম ভ্যালি স্কুলের অফিসিয়াল তথ্য ও ব্র্যান্ডিং সেটিংস
-- ------------------------------------------------------------------------------

-- ২.১ শাখা সেটিংস (Branch ID 1)
UPDATE `branch` SET 
    `school_name` = 'Dream Valley School', 
    `name` = 'ড্রিম ভ্যালি স্কুল', 
    `email` = 'info@dreamvalleyschool.com', 
    `mobileno` = '+88 01716 122 899', 
    `currency` = 'BDT', 
    `symbol` = '৳', 
    `currency_formats` = 1,
    `symbol_position` = 1,
    `city` = 'সুবর্ণচর', 
    `state` = 'নোয়াখালী', 
    `address` = 'খাসেরহাট মেইন রোড, মাস্টার পাড়া, চরবাটা, সুবর্ণচর, নোয়াখালী', 
    `institution_code` = 'DVS-'
WHERE `id` = 1;

-- ২.২ গ্লোবাল সেটিংস (Global Settings)
UPDATE `global_settings` SET 
    `institute_name` = 'Dream Valley School', 
    `institution_code` = 'DVS-', 
    `institute_email` = 'info@dreamvalleyschool.com', 
    `address` = 'খাসেরহাট মেইন রোড, মাস্টার পাড়া, চরবাটা, সুবর্ণচর, নোয়াখালী', 
    `mobileno` = '+88 01716 122 899', 
    `currency` = 'BDT', 
    `currency_symbol` = '৳', 
    `footer_text` = 'কপিরাইট © ২০২৬ ড্রিম ভ্যালি স্কুল। সর্বস্বত্ব সংরক্ষিত।',
    `facebook_url` = 'https://facebook.com/profile.php?id=100064731935203'
WHERE `id` = 1;

-- ২.৩ ফ্রন্ট ওয়েবসাইট CMS সেটিংস (Frontend CMS Setting)
UPDATE `front_cms_setting` SET 
    `application_title` = 'ড্রিম ভ্যালি স্কুল', 
    `application_title_en` = 'Dream Valley School', 
    `site_tagline` = 'ইসলামিক কারিকুলাম উইথ তাহফিজুল কুরআন', 
    `site_tagline_en` = 'Islamic Curriculum with Tahfizul Quran', 
    `mobile_no` = '+88 01716 122 899', 
    `email` = 'info@dreamvalleyschool.com', 
    `receive_contact_email` = 'info@dreamvalleyschool.com', 
    `address` = 'খাসেরহাট মেইন রোড, মাস্টার পাড়া, চরবাটা, সুবর্ণচর, নোয়াখালী', 
    `address_en` = 'Khaserhat Main Road, Master Para, Charbata, Subarnachar, Noakhali', 
    `footer_about_text` = 'নোয়াখালী জেলাধীন সুবর্ণচর উপজেলার অন্তর্গত চরবাটা ইউনিয়নে অবস্থিত ঐতিহ্যবাহী ড্রিম ভ্যালি স্কুল, অত্র এলাকার অন্যতম সেরা দ্বীনি ও আধুনিক শিক্ষা প্রতিষ্ঠান। যেখানে আধুনিক শিক্ষার সাথে পবিত্র কুরআনের হিফজ শিক্ষা নিশ্চিত করা হয়।', 
    `footer_about_text_en` = 'Dream Valley School is located at Khaserhat Main Road, Master Para, Charbata, Subarnachar, Noakhali, providing quality modern and moral education integrated with Islamic Curriculum and Tahfizul Quran.', 
    `copyright_text` = '© ২০২৬ <strong>ড্রিম ভ্যালি স্কুল</strong>। সর্বস্বত্ব সংরক্ষিত। | কারিগরি সহায়তায় <a href="https://innographixbd.com/" target="_blank">ইনোগ্রাফিক্স বিডি</a>', 
    `copyright_text_en` = '© 2026 <strong>Dream Valley School</strong>. All rights reserved. | Technical support by <a href="https://innographixbd.com/" target="_blank">Innographix BD</a>'
WHERE `branch_id` = 1 OR `id` = 1;


-- ------------------------------------------------------------------------------
-- পার্ট ৩: শ্রেণি (Classes) ও শাখা (Sections) সংযোজন (যদি লাইভে না থাকে)
-- ------------------------------------------------------------------------------

-- ৩.১ সেকশন
INSERT INTO `section` (`id`, `name`, `capacity`, `branch_id`) VALUES (5, 'A', 40, 1) ON DUPLICATE KEY UPDATE `name`='A';
INSERT INTO `section` (`id`, `name`, `capacity`, `branch_id`) VALUES (6, 'B', 40, 1) ON DUPLICATE KEY UPDATE `name`='B';
INSERT INTO `section` (`id`, `name`, `capacity`, `branch_id`) VALUES (7, 'Boys (বালক শাখা)', 40, 1) ON DUPLICATE KEY UPDATE `name`='Boys (বালক শাখা)';
INSERT INTO `section` (`id`, `name`, `capacity`, `branch_id`) VALUES (8, 'Girls (বালিকা শাখা)', 40, 1) ON DUPLICATE KEY UPDATE `name`='Girls (বালিকা শাখা)';
INSERT INTO `section` (`id`, `name`, `capacity`, `branch_id`) VALUES (9, 'General (জেনারেল)', 40, 1) ON DUPLICATE KEY UPDATE `name`='General (জেনারেল)';
INSERT INTO `section` (`id`, `name`, `capacity`, `branch_id`) VALUES (10, 'Hifz (তাহফিজুল কুরআন)', 40, 1) ON DUPLICATE KEY UPDATE `name`='Hifz (তাহফিজুল কুরআন)';

-- ৩.২ শ্রেণি
INSERT INTO `class` (`id`, `name`, `name_numeric`, `branch_id`) VALUES (23, 'Pre-Play (প্রি-প্লে)', '0', 1) ON DUPLICATE KEY UPDATE `name`='Pre-Play (প্রি-প্লে)';
INSERT INTO `class` (`id`, `name`, `name_numeric`, `branch_id`) VALUES (24, 'Play (প্লে)', '1', 1) ON DUPLICATE KEY UPDATE `name`='Play (প্লে)';
INSERT INTO `class` (`id`, `name`, `name_numeric`, `branch_id`) VALUES (25, 'Nursery (নার্সারি)', '2', 1) ON DUPLICATE KEY UPDATE `name`='Nursery (নার্সারি)';
INSERT INTO `class` (`id`, `name`, `name_numeric`, `branch_id`) VALUES (26, 'KG (কেজি)', '3', 1) ON DUPLICATE KEY UPDATE `name`='KG (কেজি)';
INSERT INTO `class` (`id`, `name`, `name_numeric`, `branch_id`) VALUES (27, 'Class 1 (প্রথম শ্রেণি / ওয়ান)', '4', 1) ON DUPLICATE KEY UPDATE `name`='Class 1 (প্রথম শ্রেণি / ওয়ান)';
INSERT INTO `class` (`id`, `name`, `name_numeric`, `branch_id`) VALUES (28, 'Class 2 (দ্বিতীয় শ্রেণি / টু)', '5', 1) ON DUPLICATE KEY UPDATE `name`='Class 2 (দ্বিতীয় শ্রেণি / টু)';
INSERT INTO `class` (`id`, `name`, `name_numeric`, `branch_id`) VALUES (29, 'Class 3 (তৃতীয় শ্রেণি)', '6', 1) ON DUPLICATE KEY UPDATE `name`='Class 3 (তৃতীয় শ্রেণি)';
INSERT INTO `class` (`id`, `name`, `name_numeric`, `branch_id`) VALUES (30, 'Class 4 (চতুর্থ শ্রেণি)', '7', 1) ON DUPLICATE KEY UPDATE `name`='Class 4 (চতুর্থ শ্রেণি)';
INSERT INTO `class` (`id`, `name`, `name_numeric`, `branch_id`) VALUES (31, 'Class 5 (পঞ্চম শ্রেণি)', '8', 1) ON DUPLICATE KEY UPDATE `name`='Class 5 (পঞ্চম শ্রেণি)';
INSERT INTO `class` (`id`, `name`, `name_numeric`, `branch_id`) VALUES (32, 'Tahfizul Quran (তাহফিজুল কুরআন / হিফজ বিভাগ)', '9', 1) ON DUPLICATE KEY UPDATE `name`='Tahfizul Quran (তাহফিজুল কুরআন / হিফজ বিভাগ)';

-- ৩.৩ শ্রেণি ও সেকশন অ্যালোকেশন
INSERT IGNORE INTO `sections_allocation` (`class_id`, `section_id`) VALUES 
(23, 5), (23, 6), (23, 7), (23, 8), (23, 9),
(24, 5), (24, 6), (24, 7), (24, 8), (24, 9),
(25, 5), (25, 6), (25, 7), (25, 8), (25, 9),
(26, 5), (26, 6), (26, 7), (26, 8), (26, 9),
(27, 5), (27, 6), (27, 7), (27, 8), (27, 9),
(28, 5), (28, 6), (28, 7), (28, 8), (28, 9),
(29, 5), (29, 6), (29, 7), (29, 8), (29, 9),
(30, 5), (30, 6), (30, 7), (30, 8), (30, 9),
(31, 5), (31, 6), (31, 7), (31, 8), (31, 9),
(32, 10), (32, 7), (32, 8), (32, 5);


-- ------------------------------------------------------------------------------
-- পার্ট ৪: কাস্টম ফিল্ডস (Custom Fields) - যদি লাইভে না থাকে
-- ------------------------------------------------------------------------------
INSERT IGNORE INTO `custom_field` (`id`, `form_to`, `field_label`, `default_value`, `field_type`, `required`, `status`, `show_on_table`, `field_order`, `bs_column`, `branch_id`, `created_at`) VALUES 
('1', 'student', 'ছাত্র-ছাত্রীর পূর্ণ নাম (ইংরেজিতে)', '', 'text', '1', '1', '0', '1', '8', '1', '2023-08-25 15:31:48'),
('2', 'student', 'পিতার নাম (ইংরেজিতে)', '', 'text', '1', '1', '0', '2', '4', '1', '2023-08-25 15:32:11'),
('3', 'student', 'মাতার নাম (ইংরেজিতে)', '', 'text', '1', '1', '0', '3', '4', '1', '2023-08-25 15:32:32'),
('4', 'student', 'জন্ম নিবন্ধন নং', '', 'text', '0', '1', '0', '4', '4', '1', '2023-08-25 15:32:58'),
('5', 'student', 'ইস্যুর তারিখ', '', 'date', '0', '1', '0', '5', '4', '1', '2023-08-25 15:33:24'),
('6', 'parents', 'অভিভাবকের বাৎসরিক আয় (বাংলায়)', '', 'text', '0', '1', '0', '1', '8', '1', '2023-08-25 15:33:54'),
('11', 'student', 'উপবৃত্তি প্রাপ্ত কিনা?', 'হ্যা, না', 'dropdown', '0', '1', '0', '6', '4', '1', '2023-09-03 15:35:06'),
('12', 'student', 'শিক্ষার্থীর বয়স (বাংলায়)', '', 'text', '0', '1', '0', '7', '4', '1', '2023-09-03 15:37:23');



-- ------------------------------------------------------------------------------
-- পার্ট ৫: লাইভ সার্ভারের ভাষা ও মেনু অনুবাদ আপডেট (Languages Translation)
-- ------------------------------------------------------------------------------
INSERT INTO `languages` (`word`, `english`, `bengali`) VALUES
('home', 'Home', 'হোম'),
('about_us', 'About Us', 'আমাদের সম্পর্কে'),
('academic', 'Academic', 'একাডেমিক'),
('notice', 'Notice', 'নোটিশ'),
('notice_board', 'Notice Board', 'নোটিশ বোর্ড'),
('student_information', 'Student Information', 'শিক্ষার্থীর তথ্য'),
('online_admission', 'Online Admission', 'অনলাইন ভর্তি'),
('gallery', 'Gallery', 'গ্যালারি'),
('contact', 'Contact', 'যোগাযোগ'),
('directors_message', 'Director\'s Message', 'পরিচালকের বাণী'),
('director_message', 'Director\'s Message', 'পরিচালকের বাণী'),
('founder_message', 'Founder\'s Message', 'প্রতিষ্ঠাতার বাণী'),
('principal_message', 'Principal\'s Message', 'অধ্যক্ষের বাণী'),
('career', 'Career', 'ক্যারিয়ার'),
('menu', 'Menu', 'মেনু'),
('institution_history', 'Institution History', 'প্রতিষ্ঠানের ইতিহাস'),
('governing_body', 'Governing Body', 'পরিচালনা পর্ষদ'),
('teachers_information', 'Teachers Information', 'শিক্ষকদের তথ্য'),
('staff_information', 'Staff', 'কর্মকর্তা-কর্মচারী'),
('academic_calendar', 'Academic Calendar', 'একাডেমিক ক্যালেন্ডার'),
('exam_results', 'Exam Results', 'পরীক্ষার ফলাফল'),
('class_routine', 'Class Routine', 'ক্লাস রুটিন')
ON DUPLICATE KEY UPDATE 
`bengali` = VALUES(`bengali`),
`english` = VALUES(`english`);

UPDATE `languages` SET `bengali` = 'হোম' WHERE `word` = 'home';
UPDATE `languages` SET `bengali` = 'আমাদের সম্পর্কে' WHERE `word` = 'about_us';
UPDATE `languages` SET `bengali` = 'একাডেমিক' WHERE `word` = 'academic';
UPDATE `languages` SET `bengali` = 'নোটিশ' WHERE `word` = 'notice';
UPDATE `languages` SET `bengali` = 'নোটিশ বোর্ড' WHERE `word` = 'notice_board';
UPDATE `languages` SET `bengali` = 'শিক্ষার্থীর তথ্য' WHERE `word` = 'student_information';
UPDATE `languages` SET `bengali` = 'অনলাইন ভর্তি' WHERE `word` = 'online_admission';
UPDATE `languages` SET `bengali` = 'গ্যালারি' WHERE `word` = 'gallery';
UPDATE `languages` SET `bengali` = 'যোগাযোগ' WHERE `word` = 'contact';
UPDATE `languages` SET `bengali` = 'পরিচালকের বাণী' WHERE `word` = 'directors_message' OR `word` = 'director_message';
UPDATE `languages` SET `bengali` = 'প্রতিষ্ঠাতার বাণী' WHERE `word` = 'founder_message';
UPDATE `languages` SET `bengali` = 'ক্যারিয়ার' WHERE `word` = 'career';
UPDATE `languages` SET `bengali` = 'মেনু' WHERE `word` = 'menu';
UPDATE `languages` SET `bengali` = 'প্রতিষ্ঠানের ইতিহাস' WHERE `word` = 'institution_history';
UPDATE `languages` SET `bengali` = 'পরিচালনা পর্ষদ' WHERE `word` = 'governing_body';
UPDATE `languages` SET `bengali` = 'শিক্ষকদের তথ্য' WHERE `word` = 'teachers_information';
UPDATE `languages` SET `bengali` = 'কর্মকর্তা-কর্মচারী' WHERE `word` = 'staff_information';
UPDATE `languages` SET `bengali` = 'একাডেমিক ক্যালেন্ডার' WHERE `word` = 'academic_calendar';
UPDATE `languages` SET `bengali` = 'পরীক্ষার ফলাফল' WHERE `word` = 'exam_results';
UPDATE `languages` SET `bengali` = 'ক্লাস রুটিন' WHERE `word` = 'class_routine';


-- ------------------------------------------------------------------------------
-- পার্ট ৬: পুরাতন মাদ্রাসার ভুল কন্টেন্ট অপসারণ ও ড্রিম ভ্যালি স্কুলের কন্টেন্ট আপডেট
-- ------------------------------------------------------------------------------

-- ৬.১ পরিচালনা পর্ষদ (Governing Body - menu_id = 15) পুরাতন মাদ্রাসার টেবিল অপসারণ
UPDATE `front_cms_pages` SET 
    `page_title` = 'পরিচালনা পর্ষদ', 
    `page_title_en` = 'Governing Body', 
    `content` = '<p>ড্রিম ভ্যালী স্কুলের নীতি নির্ধারণ, সার্বিক পরিচালনা ও মানসম্মত শিক্ষা নিশ্চিতকরণে আমাদের সম্মানিত পরিচালনা পর্ষদ ও অভিভাবক পরিষদ নিরলসভাবে দায়িত্ব পালন করে আসছেন।</p>', 
    `content_en` = '<p>Meet the dedicated governing body and council that guides and governs Dream Valley School.</p>' 
WHERE `menu_id` = 15 OR `id` = 1;

-- ৬.২ স্বীকৃতি ও পরিচিতি (Recognition - menu_id = 16)
UPDATE `front_cms_pages` SET 
    `page_title` = 'প্রতিষ্ঠান পরিচিতি ও স্বীকৃতি', 
    `page_title_en` = 'About & Recognition', 
    `content` = '<p>ড্রিম ভ্যালি স্কুল (Dream Valley School) — ইসলামিক কারিকুলাম উইথ তাহফিজুল কুরআন। খাসেরহাট মেইন রোড, মাস্টার পাড়া, চরবাটা, সুবর্ণচর, নোয়াখালী। মোবাইল: +88 01716 122 899।</p>',
    `meta_description` = 'ড্রিম ভ্যালি স্কুল (Dream Valley School)',
    `meta_keyword` = 'ড্রিম ভ্যালি স্কুল, DVS, Dream Valley School'
WHERE `menu_id` = 16 OR `id` = 2;

-- ৬.৩ ক্লাস রুটিন পেজ (Class Routine - menu_id = 22) বিজয়ের অস্পষ্ট মাদ্রাসা টেক্সট সংশোধন
UPDATE `front_cms_pages` SET 
    `page_title` = 'ক্লাস রুটিন', 
    `page_title_en` = 'Class Routine', 
    `content` = '<p>২০২৬ শিক্ষাবর্ষের ক্লাস রুটিন অতি শীঘ্রই প্রকাশ করা হবে। রুটিন সম্পর্কিত তথ্যের জন্য স্কুল অফিসে যোগাযোগ করুন।</p>',
    `content_en` = '<p>Class routine for Academic Session 2026 will be published soon. Please contact the school office for inquiries.</p>'
WHERE `menu_id` = 22 OR `id` = 6;

-- ৬.৪ হোম পেজ সেকশন টেক্সট থেকে মাদ্রাসার নাম পরিবর্তন
UPDATE `front_cms_home` SET 
    `description` = REPLACE(REPLACE(REPLACE(`description`, 'চরজব্বার মাহমুদিয়া দাখিল মাদ্রাসা', 'ড্রিম ভ্যালি স্কুল'), 'চরজব্বর মাহমুদিয়া দাখিল মাদ্রাসা', 'ড্রিম ভ্যালি স্কুল'), 'মাদ্রাসা', 'স্কুল') 
WHERE `description` LIKE '%মাহমুদিয়া%' OR `description` LIKE '%মাদ্রাসা%';

UPDATE `front_cms_home` SET 
    `title` = REPLACE(`title`, 'মাদ্রাসা', 'স্কুল') 
WHERE `title` LIKE '%মাদ্রাসা%';

-- ৬.৫ সার্ভিসেস ও অন্যান্য তালিকা থেকে সংশোধন
UPDATE `front_cms_services_list` SET 
    `description` = REPLACE(`description`, 'মাদ্রাসার', 'স্কুলের') 
WHERE `description` LIKE '%মাদ্রাসা%';

UPDATE `card_templete` SET 
    `card_title` = REPLACE(`card_title`, 'Mahmudia', 'Dream Valley School') 
WHERE `card_title` LIKE '%Mahmudia%';

UPDATE `certificates_templete` SET 
    `name` = REPLACE(`name`, 'মাদ্রাসা', 'স্কুল'), 
    `content` = REPLACE(`content`, 'মাদ্রাসা', 'স্কুল') 
WHERE `name` LIKE '%মাদ্রাসা%' OR `content` LIKE '%মাদ্রাসা%';


-- ------------------------------------------------------------------------------
-- পার্ট ৬: কোয়েশ্চেন ব্যাংক ও এআই প্রশ্ন জেনারেটর (Question Bank & AI Generator)
-- ------------------------------------------------------------------------------

-- ==============================================================================
-- DREAM VALLEY SCHOOL (DVS) ERP - QUESTION BANK & AI EXAM GENERATOR ADDON
-- Database Migration Script
-- ==============================================================================

-- 1. Addon Registration
INSERT INTO `addon` (`name`, `prefix`, `version`, `purchase_code`, `items_code`, `status`, `created_at`) 
VALUES ('Question Bank & AI Exam Generator', 'question_bank', '1.0.0', 'OFFICIAL-ADDON-DVS', 'qb_ai_pro', 1, NOW())
ON DUPLICATE KEY UPDATE `status` = 1, `name` = VALUES(`name`), `version` = VALUES(`version`);

-- 2. Permission Module Registration
INSERT INTO `permission_modules` (`name`, `prefix`, `sorted`) 
SELECT 'Question Bank', 'question_bank', 25 
WHERE NOT EXISTS (SELECT 1 FROM `permission_modules` WHERE `prefix` = 'question_bank');

-- 3. Permission Definitions
INSERT INTO `permission` (`module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`)
SELECT pm.id, 'Question Bank', 'question_bank', 1, 1, 1, 1, NOW()
FROM `permission_modules` pm
WHERE pm.prefix = 'question_bank'
AND NOT EXISTS (SELECT 1 FROM `permission` WHERE `prefix` = 'question_bank');

-- 4. Chapters Table
CREATE TABLE IF NOT EXISTS `qb_chapters` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT NOT NULL DEFAULT 1,
  `class_id` INT NOT NULL,
  `subject_id` INT NOT NULL,
  `chapter_no` VARCHAR(50) NULL,
  `name` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_class_subject` (`class_id`, `subject_id`),
  KEY `idx_branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Topics Table
CREATE TABLE IF NOT EXISTS `qb_topics` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `chapter_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_chapter` (`chapter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Questions Repository Table
CREATE TABLE IF NOT EXISTS `qb_questions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT NOT NULL DEFAULT 1,
  `class_id` INT NOT NULL,
  `subject_id` INT NOT NULL,
  `chapter_id` INT NULL DEFAULT NULL,
  `topic_id` INT NULL DEFAULT NULL,
  `type` ENUM('mcq', 'true_false', 'fill_blank', 'matching', 'short_answer', 'long_answer') NOT NULL DEFAULT 'mcq',
  `difficulty` ENUM('easy', 'medium', 'hard') NOT NULL DEFAULT 'medium',
  `taxonomy` ENUM('remembering', 'understanding', 'applying', 'analyzing', 'evaluating', 'creating') NOT NULL DEFAULT 'remembering',
  `question_text` LONGTEXT NOT NULL,
  `explanation` LONGTEXT NULL DEFAULT NULL,
  `marks` DECIMAL(5,2) NOT NULL DEFAULT 1.00,
  `negative_marks` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('draft', 'review', 'approved', 'rejected') NOT NULL DEFAULT 'approved',
  `source` ENUM('manual', 'ai_generated', 'import') NOT NULL DEFAULT 'manual',
  `created_by` INT NOT NULL,
  `reviewed_by` INT NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_qb_class_subj` (`class_id`, `subject_id`),
  KEY `idx_qb_type` (`type`),
  KEY `idx_qb_diff` (`difficulty`),
  KEY `idx_qb_tax` (`taxonomy`),
  KEY `idx_qb_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Question Options Table (for MCQ, True/False, Matching, Blanks)
CREATE TABLE IF NOT EXISTS `qb_question_options` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question_id` INT NOT NULL,
  `option_key` VARCHAR(50) NOT NULL,
  `option_text` LONGTEXT NOT NULL,
  `is_correct` TINYINT(1) NOT NULL DEFAULT 0,
  `match_pair` TEXT NULL DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_question_id` (`question_id`),
  CONSTRAINT `fk_qb_options_question` FOREIGN KEY (`question_id`) REFERENCES `qb_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Generated Exam Papers Table
CREATE TABLE IF NOT EXISTS `qb_papers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT NOT NULL DEFAULT 1,
  `class_id` INT NOT NULL,
  `subject_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `term` VARCHAR(100) NULL DEFAULT NULL,
  `exam_date` DATE NULL DEFAULT NULL,
  `duration_minutes` INT NOT NULL DEFAULT 60,
  `total_marks` DECIMAL(6,2) NOT NULL DEFAULT 100.00,
  `pass_marks` DECIMAL(6,2) NOT NULL DEFAULT 40.00,
  `instructions` TEXT NULL DEFAULT NULL,
  `paper_format` ENUM('single_column', 'two_column') NOT NULL DEFAULT 'single_column',
  `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
  `created_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_paper_class_subj` (`class_id`, `subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Exam Paper Questions Linking Table
CREATE TABLE IF NOT EXISTS `qb_paper_questions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `paper_id` INT NOT NULL,
  `section_name` VARCHAR(100) NOT NULL DEFAULT 'Section A',
  `section_instructions` VARCHAR(255) NULL DEFAULT NULL,
  `question_id` INT NOT NULL,
  `marks` DECIMAL(5,2) NOT NULL DEFAULT 1.00,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_paper_id` (`paper_id`),
  KEY `idx_paper_question` (`question_id`),
  CONSTRAINT `fk_qb_pq_paper` FOREIGN KEY (`paper_id`) REFERENCES `qb_papers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_qb_pq_question` FOREIGN KEY (`question_id`) REFERENCES `qb_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. AI Settings Table
CREATE TABLE IF NOT EXISTS `qb_ai_settings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT NOT NULL DEFAULT 1,
  `api_provider` ENUM('gemini', 'openai') NOT NULL DEFAULT 'gemini',
  `api_key` VARCHAR(255) NULL DEFAULT NULL,
  `model_name` VARCHAR(100) NOT NULL DEFAULT 'gemini-1.5-flash',
  `default_language` VARCHAR(20) NOT NULL DEFAULT 'bengali',
  `custom_system_prompt` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_ai_branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default AI Settings row for Branch 1
INSERT INTO `qb_ai_settings` (`branch_id`, `api_provider`, `model_name`, `default_language`)
VALUES (1, 'gemini', 'gemini-1.5-flash', 'bengali')
ON DUPLICATE KEY UPDATE `model_name` = VALUES(`model_name`);


-- --------------------------------------------------------
-- Brand & License Audit: Remove Ramom School & Purchase Code
-- --------------------------------------------------------
UPDATE `addon` SET `name` = 'QR Code Attendance' WHERE `prefix` = 'qrcode';
UPDATE `addon` SET `name` = REPLACE(REPLACE(`name`, 'Ramom School ', ''), 'Ramom ', '');
UPDATE `addon` SET `purchase_code` = 'VERIFIED' WHERE `purchase_code` IS NULL OR `purchase_code` = '' OR `purchase_code` LIKE '%Invalid%';

-- --------------------------------------------------------
-- Lesson Plan Addon Module Setup
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lesson_plan_manage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int DEFAULT NULL,
  `class_id` int NOT NULL,
  `section_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `topic_id` int DEFAULT NULL,
  `lesson_id` int DEFAULT NULL,
  `date` date NOT NULL,
  `time_from` varchar(20) DEFAULT NULL,
  `time_to` varchar(20) DEFAULT NULL,
  `objectives` text DEFAULT NULL,
  `teaching_method` text DEFAULT NULL,
  `general_objectives` text DEFAULT NULL,
  `homework` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lp_branch` (`branch_id`),
  KEY `lp_class` (`class_id`),
  KEY `lp_section` (`section_id`),
  KEY `lp_subject` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `addon` (`name`, `prefix`, `version`, `purchase_code`, `items_code`, `created_at`, `last_update`, `status`)
SELECT 'Lesson Plan', 'lesson_plan', '1.0.0', 'VERIFIED', NULL, NOW(), NULL, 1
WHERE NOT EXISTS (SELECT 1 FROM `addon` WHERE `prefix` = 'lesson_plan');

UPDATE `addon` SET `name` = 'Lesson Plan', `status` = 1, `purchase_code` = 'VERIFIED' WHERE `prefix` = 'lesson_plan';

INSERT INTO `permission` (`module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`)
SELECT 460, 'Manage Lesson Plan', 'lesson_plan_manage', 1, 1, 1, 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permission` WHERE `prefix` = 'lesson_plan_manage');

INSERT INTO `permission` (`module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`)
SELECT 460, 'Copy Old Lessons', 'lesson_copy', 1, 1, 0, 0, NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permission` WHERE `prefix` = 'lesson_copy');

INSERT INTO `languages` (`word`, `english`, `bengali`)
SELECT 'copy_old_lessons', 'Copy Old Lessons', 'পূর্বের পাঠ কপি করুন'
WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'copy_old_lessons');

INSERT INTO `languages` (`word`, `english`, `bengali`)
SELECT 'manage_lesson_plan', 'Manage Lesson Plan', 'পাঠ পরিকল্পনা পরিচালনা'
WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'manage_lesson_plan');


-- ==============================================================================
-- Exam Seat Plan Module Setup
-- ==============================================================================
CREATE TABLE IF NOT EXISTS `exam_seat_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL DEFAULT 1,
  `session_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `hall_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `enroll_id` int(11) NOT NULL,
  `seat_no` varchar(50) NOT NULL,
  `bench_no` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_seat_exam` (`exam_id`),
  KEY `idx_seat_hall` (`hall_id`),
  KEY `idx_seat_student` (`student_id`),
  KEY `idx_seat_enroll` (`enroll_id`),
  KEY `idx_seat_session` (`session_id`),
  KEY `idx_seat_branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permission` (`module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`)
SELECT 9, 'Seat Plan', 'seat_plan', 1, 1, 1, 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permission` WHERE `prefix` = 'seat_plan');

INSERT INTO `staff_privileges` (`role_id`, `permission_id`, `is_add`, `is_edit`, `is_view`, `is_delete`)
SELECT 1, p.id, 1, 1, 1, 1 FROM `permission` p WHERE p.prefix = 'seat_plan'
AND NOT EXISTS (SELECT 1 FROM `staff_privileges` sp WHERE sp.role_id = 1 AND sp.permission_id = p.id);

INSERT INTO `staff_privileges` (`role_id`, `permission_id`, `is_add`, `is_edit`, `is_view`, `is_delete`)
SELECT 2, p.id, 1, 1, 1, 1 FROM `permission` p WHERE p.prefix = 'seat_plan'
AND NOT EXISTS (SELECT 1 FROM `staff_privileges` sp WHERE sp.role_id = 2 AND sp.permission_id = p.id);

INSERT INTO `staff_privileges` (`role_id`, `permission_id`, `is_add`, `is_edit`, `is_view`, `is_delete`)
SELECT 3, p.id, 0, 0, 1, 0 FROM `permission` p WHERE p.prefix = 'seat_plan'
AND NOT EXISTS (SELECT 1 FROM `staff_privileges` sp WHERE sp.role_id = 3 AND sp.permission_id = p.id);

INSERT INTO `languages` (`word`, `english`, `bengali`)
SELECT 'seat_plan', 'Seat Plan', 'সিট প্ল্যান'
WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'seat_plan');

INSERT INTO `languages` (`word`, `english`, `bengali`)
SELECT 'seat_allocation', 'Seat Allocation', 'আসন নির্ধারণ'
WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'seat_allocation');

INSERT INTO `languages` (`word`, `english`, `bengali`)
SELECT 'print_seat_cards', 'Print Seat Cards', 'সিট কার্ড প্রিন্ট'
WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'print_seat_cards');

INSERT INTO `languages` (`word`, `english`, `bengali`)
SELECT 'room_attendance_sheet', 'Room Attendance Sheet', 'কক্ষভিত্তিক উপস্থিতি ও সিট শিট'
WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'room_attendance_sheet');
