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
    `name` = 'ড্রিম ভ্যালি স্কুল (মূল শাখা)', 
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
-- পার্ট ৭: অ্যাডমিন সাইডবার ও ইউআই বাংলা অনুবাদ সংশোধন (Sidebar & UI Translations Fix)
-- ------------------------------------------------------------------------------
SET NAMES utf8mb4;

INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'dashboard', 'Dashboard', 'ড্যাশবোর্ড' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'dashboard');
UPDATE `languages` SET `bengali` = 'ড্যাশবোর্ড' WHERE `word` = 'dashboard';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'branch', 'Branch', 'শাখা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'branch');
UPDATE `languages` SET `bengali` = 'শাখা' WHERE `word` = 'branch';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'all_branches', 'All Branches', 'সকল শাখা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'all_branches');
UPDATE `languages` SET `bengali` = 'সকল শাখা' WHERE `word` = 'all_branches';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'inventory', 'Inventory', 'ইনভেন্টরি / মালামাল' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'inventory');
UPDATE `languages` SET `bengali` = 'ইনভেন্টরি / মালামাল' WHERE `word` = 'inventory';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'frontend', 'Frontend', 'ওয়েবসাইট সিএমএস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'frontend');
UPDATE `languages` SET `bengali` = 'ওয়েবসাইট সিএমএস' WHERE `word` = 'frontend';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'reception', 'Reception', 'রিসেপশন ও ফ্রন্ট ডেস্ক' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'reception');
UPDATE `languages` SET `bengali` = 'রিসেপশন ও ফ্রন্ট ডেস্ক' WHERE `word` = 'reception';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'admission', 'Admission', 'ভর্তি কার্যক্রম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'admission');
UPDATE `languages` SET `bengali` = 'ভর্তি কার্যক্রম' WHERE `word` = 'admission';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'student_details', 'Student Details', 'শিক্ষার্থী তথ্য ও তালিকা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'student_details');
UPDATE `languages` SET `bengali` = 'শিক্ষার্থী তথ্য ও তালিকা' WHERE `word` = 'student_details';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'parents', 'Parents', 'অভিভাবক বৃন্দ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'parents');
UPDATE `languages` SET `bengali` = 'অভিভাবক বৃন্দ' WHERE `word` = 'parents';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'employee', 'Employee', 'কর্মকর্তা ও কর্মচারী' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'employee');
UPDATE `languages` SET `bengali` = 'কর্মকর্তা ও কর্মচারী' WHERE `word` = 'employee';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'card_management', 'Card Management', 'আইডি ও প্রবেশপত্র কার্ড' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'card_management');
UPDATE `languages` SET `bengali` = 'আইডি ও প্রবেশপত্র কার্ড' WHERE `word` = 'card_management';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'certificate', 'Certificate', 'সার্টিফিকেট ও সনদ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'certificate');
UPDATE `languages` SET `bengali` = 'সার্টিফিকেট ও সনদ' WHERE `word` = 'certificate';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'certificates', 'Certificates', 'সার্টিফিকেট সমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'certificates');
UPDATE `languages` SET `bengali` = 'সার্টিফিকেট সমূহ' WHERE `word` = 'certificates';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'human_resource', 'Human Resource', 'মানব সম্পদ (HR)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'human_resource');
UPDATE `languages` SET `bengali` = 'মানব সম্পদ (HR)' WHERE `word` = 'human_resource';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'hrm', 'Hrm', 'মানব সম্পদ (HRM)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'hrm');
UPDATE `languages` SET `bengali` = 'মানব সম্পদ (HRM)' WHERE `word` = 'hrm';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'academic', 'Academic', 'একাডেমিক ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'academic');
UPDATE `languages` SET `bengali` = 'একাডেমিক ব্যবস্থাপনা' WHERE `word` = 'academic';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'live_class_rooms', 'Live Class Rooms', 'অনলাইন / লাইভ ক্লাস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'live_class_rooms');
UPDATE `languages` SET `bengali` = 'অনলাইন / লাইভ ক্লাস' WHERE `word` = 'live_class_rooms';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'live_class', 'Live Class', 'লাইভ ক্লাস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'live_class');
UPDATE `languages` SET `bengali` = 'লাইভ ক্লাস' WHERE `word` = 'live_class';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'live_class_reports', 'Live Class Reports', 'লাইভ ক্লাস রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'live_class_reports');
UPDATE `languages` SET `bengali` = 'লাইভ ক্লাস রিপোর্ট' WHERE `word` = 'live_class_reports';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'attachments_book', 'Attachments Book', 'ডাউনলোড সেন্টার / ফাইল' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'attachments_book');
UPDATE `languages` SET `bengali` = 'ডাউনলোড সেন্টার / ফাইল' WHERE `word` = 'attachments_book';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'attachment_type', 'Attachment Type', 'ফাইলের ধরন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'attachment_type');
UPDATE `languages` SET `bengali` = 'ফাইলের ধরন' WHERE `word` = 'attachment_type';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'upload_content', 'Upload Content', 'ফাইল আপলোড' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'upload_content');
UPDATE `languages` SET `bengali` = 'ফাইল আপলোড' WHERE `word` = 'upload_content';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'homework', 'Homework', 'বাড়ির কাজ (Homework)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'homework');
UPDATE `languages` SET `bengali` = 'বাড়ির কাজ (Homework)' WHERE `word` = 'homework';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'evaluation_report', 'Evaluation Report', 'মূল্যায়ন প্রতিবেদন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'evaluation_report');
UPDATE `languages` SET `bengali` = 'মূল্যায়ন প্রতিবেদন' WHERE `word` = 'evaluation_report';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'supervision', 'Supervision', 'হোস্টেল ও পরিবহন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'supervision');
UPDATE `languages` SET `bengali` = 'হোস্টেল ও পরিবহন' WHERE `word` = 'supervision';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'attendance', 'Attendance', 'হাজিরা ও উপস্থিতি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'attendance');
UPDATE `languages` SET `bengali` = 'হাজিরা ও উপস্থিতি' WHERE `word` = 'attendance';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'attendance_reports', 'Attendance Reports', 'হাজিরা রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'attendance_reports');
UPDATE `languages` SET `bengali` = 'হাজিরা রিপোর্ট' WHERE `word` = 'attendance_reports';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'qr_code_attendance', 'Qr Code Attendance', 'কিউআর কোড হাজিরা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'qr_code_attendance');
UPDATE `languages` SET `bengali` = 'কিউআর কোড হাজিরা' WHERE `word` = 'qr_code_attendance';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'biometric_attendance', 'Biometric Attendance', 'বায়োমেট্রিক হাজিরা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'biometric_attendance');
UPDATE `languages` SET `bengali` = 'বায়োমেট্রিক হাজিরা' WHERE `word` = 'biometric_attendance';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'biometric', 'Biometric', 'বায়োমেট্রিক ডিভাইস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'biometric');
UPDATE `languages` SET `bengali` = 'বায়োমেট্রিক ডিভাইস' WHERE `word` = 'biometric';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'devices', 'Devices', 'ডিভাইস সমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'devices');
UPDATE `languages` SET `bengali` = 'ডিভাইস সমূহ' WHERE `word` = 'devices';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'logs', 'Logs', 'হাজিরা লগ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'logs');
UPDATE `languages` SET `bengali` = 'হাজিরা লগ' WHERE `word` = 'logs';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'user_mapping', 'User Mapping', 'ইউজার ম্যাপিং' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'user_mapping');
UPDATE `languages` SET `bengali` = 'ইউজার ম্যাপিং' WHERE `word` = 'user_mapping';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'lesson_plan', 'Lesson Plan', 'পাঠ পরিকল্পনা (Lesson Plan)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'lesson_plan');
UPDATE `languages` SET `bengali` = 'পাঠ পরিকল্পনা (Lesson Plan)' WHERE `word` = 'lesson_plan';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'library', 'Library', 'লাইব্রেরি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'library');
UPDATE `languages` SET `bengali` = 'লাইব্রেরি' WHERE `word` = 'library';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'events', 'Events', 'ইভেন্টস ও নোটিশ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'events');
UPDATE `languages` SET `bengali` = 'ইভেন্টস ও নোটিশ' WHERE `word` = 'events';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'event_type', 'Event Type', 'ইভেন্টের ধরন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'event_type');
UPDATE `languages` SET `bengali` = 'ইভেন্টের ধরন' WHERE `word` = 'event_type';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'notice', 'Notice', 'নোটিশ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'notice');
UPDATE `languages` SET `bengali` = 'নোটিশ' WHERE `word` = 'notice';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'notice_board', 'Notice Board', 'নোটিশ বোর্ড' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'notice_board');
UPDATE `languages` SET `bengali` = 'নোটিশ বোর্ড' WHERE `word` = 'notice_board';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'bulk_sms_and_email', 'Bulk Sms And Email', 'এসএমএস ও ইমেইল প্রেরণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'bulk_sms_and_email');
UPDATE `languages` SET `bengali` = 'এসএমএস ও ইমেইল প্রেরণ' WHERE `word` = 'bulk_sms_and_email';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'sms', 'Sms', 'এসএমএস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'sms');
UPDATE `languages` SET `bengali` = 'এসএমএস' WHERE `word` = 'sms';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'email', 'Email', 'ই-মেইল' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'email');
UPDATE `languages` SET `bengali` = 'ই-মেইল' WHERE `word` = 'email';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'student_accounting', 'Student Accounting', 'শিক্ষার্থী ফি ও হিসাব' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'student_accounting');
UPDATE `languages` SET `bengali` = 'শিক্ষার্থী ফি ও হিসাব' WHERE `word` = 'student_accounting';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'office_accounting', 'Office Accounting', 'স্কুল আয়-ব্যয় হিসাব' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'office_accounting');
UPDATE `languages` SET `bengali` = 'স্কুল আয়-ব্যয় হিসাব' WHERE `word` = 'office_accounting';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'message', 'Message', 'বার্তা আদান-প্রদান' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'message');
UPDATE `languages` SET `bengali` = 'বার্তা আদান-প্রদান' WHERE `word` = 'message';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'report', 'Report', 'রিপোর্ট ও পরিসংখ্যান' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'report');
UPDATE `languages` SET `bengali` = 'রিপোর্ট ও পরিসংখ্যান' WHERE `word` = 'report';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'reports', 'Reports', 'রিপোর্ট সমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'reports');
UPDATE `languages` SET `bengali` = 'রিপোর্ট সমূহ' WHERE `word` = 'reports';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'repots', 'Repots', 'রিপোর্ট সমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'repots');
UPDATE `languages` SET `bengali` = 'রিপোর্ট সমূহ' WHERE `word` = 'repots';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'alumni', 'Alumni', 'প্রাক্তন শিক্ষার্থী (Alumni)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'alumni');
UPDATE `languages` SET `bengali` = 'প্রাক্তন শিক্ষার্থী (Alumni)' WHERE `word` = 'alumni';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'manage_alumni', 'Manage Alumni', 'অ্যালামনাই ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'manage_alumni');
UPDATE `languages` SET `bengali` = 'অ্যালামনাই ব্যবস্থাপনা' WHERE `word` = 'manage_alumni';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'alumni_event', 'Alumni Event', 'অ্যালামনাই ইভেন্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'alumni_event');
UPDATE `languages` SET `bengali` = 'অ্যালামনাই ইভেন্ট' WHERE `word` = 'alumni_event';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'addon_manager', 'Addon Manager', 'অ্যাড-অন ম্যানেজার' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'addon_manager');
UPDATE `languages` SET `bengali` = 'অ্যাড-অন ম্যানেজার' WHERE `word` = 'addon_manager';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'modules', 'Modules', 'মডিউল সমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'modules');
UPDATE `languages` SET `bengali` = 'মডিউল সমূহ' WHERE `word` = 'modules';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'settings', 'Settings', 'সিস্টেম সেটিংস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'settings');
UPDATE `languages` SET `bengali` = 'সিস্টেম সেটিংস' WHERE `word` = 'settings';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'setting', 'Setting', 'সেটিংস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'setting');
UPDATE `languages` SET `bengali` = 'সেটিংস' WHERE `word` = 'setting';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'school_settings', 'School Settings', 'স্কুল সেটিংস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'school_settings');
UPDATE `languages` SET `bengali` = 'স্কুল সেটিংস' WHERE `word` = 'school_settings';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'session_settings', 'Session Settings', 'শিক্ষাবর্ষ সেটিংস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'session_settings');
UPDATE `languages` SET `bengali` = 'শিক্ষাবর্ষ সেটিংস' WHERE `word` = 'session_settings';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'role_permission', 'Role Permission', 'রোল ও পারমিশন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'role_permission');
UPDATE `languages` SET `bengali` = 'রোল ও পারমিশন' WHERE `word` = 'role_permission';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'translations', 'Translations', 'ভাষা ও অনুবাদ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'translations');
UPDATE `languages` SET `bengali` = 'ভাষা ও অনুবাদ' WHERE `word` = 'translations';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'cron_job', 'Cron Job', 'স্বয়ংক্রিয় শিডিউলার (Cron)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'cron_job');
UPDATE `languages` SET `bengali` = 'স্বয়ংক্রিয় শিডিউলার (Cron)' WHERE `word` = 'cron_job';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'custom_field', 'Custom Field', 'কাস্টম ফিল্ড' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'custom_field');
UPDATE `languages` SET `bengali` = 'কাস্টম ফিল্ড' WHERE `word` = 'custom_field';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'database_backup', 'Database Backup', 'ডাটাবেজ ব্যাকআপ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'database_backup');
UPDATE `languages` SET `bengali` = 'ডাটাবেজ ব্যাকআপ' WHERE `word` = 'database_backup';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'system_update', 'System Update', 'সিস্টেম আপডেট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'system_update');
UPDATE `languages` SET `bengali` = 'সিস্টেম আপডেট' WHERE `word` = 'system_update';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'system_student_field', 'System Student Field', 'শিক্ষার্থী ফিল্ড সেটিংস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'system_student_field');
UPDATE `languages` SET `bengali` = 'শিক্ষার্থী ফিল্ড সেটিংস' WHERE `word` = 'system_student_field';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'menu', 'Menu', 'মেনু' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'menu');
UPDATE `languages` SET `bengali` = 'মেনু' WHERE `word` = 'menu';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'page', 'Page', 'পেজ সমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'page');
UPDATE `languages` SET `bengali` = 'পেজ সমূহ' WHERE `word` = 'page';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'manage_page', 'Manage Page', 'পেজ ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'manage_page');
UPDATE `languages` SET `bengali` = 'পেজ ব্যবস্থাপনা' WHERE `word` = 'manage_page';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'page_section', 'Page Section', 'পেজ সেকশন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'page_section');
UPDATE `languages` SET `bengali` = 'পেজ সেকশন' WHERE `word` = 'page_section';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'slider', 'Slider', 'ব্যানার স্লাইডার' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'slider');
UPDATE `languages` SET `bengali` = 'ব্যানার স্লাইডার' WHERE `word` = 'slider';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'features', 'Features', 'বৈশিষ্ট্যসমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'features');
UPDATE `languages` SET `bengali` = 'বৈশিষ্ট্যসমূহ' WHERE `word` = 'features';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'testimonial', 'Testimonial', 'প্রশংসাপত্র ও মতামত' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'testimonial');
UPDATE `languages` SET `bengali` = 'প্রশংসাপত্র ও মতামত' WHERE `word` = 'testimonial';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'service', 'Service', 'সেবাসমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'service');
UPDATE `languages` SET `bengali` = 'সেবাসমূহ' WHERE `word` = 'service';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'faq', 'Faq', 'সাধারণ জিজ্ঞাসা (FAQ)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'faq');
UPDATE `languages` SET `bengali` = 'সাধারণ জিজ্ঞাসা (FAQ)' WHERE `word` = 'faq';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'gallery_category', 'Gallery Category', 'গ্যালারি ক্যাটাগরি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'gallery_category');
UPDATE `languages` SET `bengali` = 'গ্যালারি ক্যাটাগরি' WHERE `word` = 'gallery_category';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'gallery', 'Gallery', 'ছবি গ্যালারি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'gallery');
UPDATE `languages` SET `bengali` = 'ছবি গ্যালারি' WHERE `word` = 'gallery';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'news', 'News', 'সংবাদ ও বিজ্ঞপ্তি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'news');
UPDATE `languages` SET `bengali` = 'সংবাদ ও বিজ্ঞপ্তি' WHERE `word` = 'news';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'admission_enquiry', 'Admission Enquiry', 'ভর্তি অনুসন্ধান' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'admission_enquiry');
UPDATE `languages` SET `bengali` = 'ভর্তি অনুসন্ধান' WHERE `word` = 'admission_enquiry';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'postal_record', 'Postal Record', 'চিঠিপত্র ও ডাক রেকর্ড' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'postal_record');
UPDATE `languages` SET `bengali` = 'চিঠিপত্র ও ডাক রেকর্ড' WHERE `word` = 'postal_record';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'call_log', 'Call Log', 'কল লগ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'call_log');
UPDATE `languages` SET `bengali` = 'কল লগ' WHERE `word` = 'call_log';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'visitor_log', 'Visitor Log', 'দর্শনার্থী রেজিস্টার' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'visitor_log');
UPDATE `languages` SET `bengali` = 'দর্শনার্থী রেজিস্টার' WHERE `word` = 'visitor_log';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'complaint', 'Complaint', 'অভিযোগ বক্স' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'complaint');
UPDATE `languages` SET `bengali` = 'অভিযোগ বক্স' WHERE `word` = 'complaint';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'product', 'Product', 'পণ্য / মালামাল' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'product');
UPDATE `languages` SET `bengali` = 'পণ্য / মালামাল' WHERE `word` = 'product';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'store', 'Store', 'স্টোর / গুদাম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'store');
UPDATE `languages` SET `bengali` = 'স্টোর / গুদাম' WHERE `word` = 'store';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'supplier', 'Supplier', 'সরবরাহকারী' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'supplier');
UPDATE `languages` SET `bengali` = 'সরবরাহকারী' WHERE `word` = 'supplier';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'unit', 'Unit', 'পরিমাপের একক' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'unit');
UPDATE `languages` SET `bengali` = 'পরিমাপের একক' WHERE `word` = 'unit';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'purchase', 'Purchase', 'মালামাল ক্রয়' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'purchase');
UPDATE `languages` SET `bengali` = 'মালামাল ক্রয়' WHERE `word` = 'purchase';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'sales', 'Sales', 'মালামাল বিক্রয়' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'sales');
UPDATE `languages` SET `bengali` = 'মালামাল বিক্রয়' WHERE `word` = 'sales';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'issue', 'Issue', 'মালামাল ইস্যু' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'issue');
UPDATE `languages` SET `bengali` = 'মালামাল ইস্যু' WHERE `word` = 'issue';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'issues', 'Issues', 'ইস্যু সমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'issues');
UPDATE `languages` SET `bengali` = 'ইস্যু সমূহ' WHERE `word` = 'issues';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'stock', 'Stock', 'স্টক / মজুদ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'stock');
UPDATE `languages` SET `bengali` = 'স্টক / মজুদ' WHERE `word` = 'stock';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'my_issued_book', 'My Issued Book', 'আমার ইস্যুকৃত বই' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'my_issued_book');
UPDATE `languages` SET `bengali` = 'আমার ইস্যুকৃত বই' WHERE `word` = 'my_issued_book';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'id_card', 'Id Card', 'আইডি কার্ড (ID Card)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'id_card');
UPDATE `languages` SET `bengali` = 'আইডি কার্ড (ID Card)' WHERE `word` = 'id_card';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'admit_card', 'Admit Card', 'প্রবেশপত্র (Admit Card)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'admit_card');
UPDATE `languages` SET `bengali` = 'প্রবেশপত্র (Admit Card)' WHERE `word` = 'admit_card';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'templete', 'Templete', 'কার্ড টেমপ্লেট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'templete');
UPDATE `languages` SET `bengali` = 'কার্ড টেমপ্লেট' WHERE `word` = 'templete';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'template', 'Template', 'টেমপ্লেট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'template');
UPDATE `languages` SET `bengali` = 'টেমপ্লেট' WHERE `word` = 'template';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'class', 'Class', 'শ্রেণি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'class');
UPDATE `languages` SET `bengali` = 'শ্রেণি' WHERE `word` = 'class';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'section', 'Section', 'শাখা / সেকশন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'section');
UPDATE `languages` SET `bengali` = 'শাখা / সেকশন' WHERE `word` = 'section';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'subject', 'Subject', 'বিষয় সমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'subject');
UPDATE `languages` SET `bengali` = 'বিষয় সমূহ' WHERE `word` = 'subject';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'class_assign', 'Class Assign', 'শ্রেণি শিক্ষক বরাদ্দ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'class_assign');
UPDATE `languages` SET `bengali` = 'শ্রেণি শিক্ষক বরাদ্দ' WHERE `word` = 'class_assign';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'assign_class_teacher', 'Assign Class Teacher', 'শ্রেণি শিক্ষক নিয়োগ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'assign_class_teacher');
UPDATE `languages` SET `bengali` = 'শ্রেণি শিক্ষক নিয়োগ' WHERE `word` = 'assign_class_teacher';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'subject_wise', 'Subject Wise', 'বিষয়ভিত্তিক' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'subject_wise');
UPDATE `languages` SET `bengali` = 'বিষয়ভিত্তিক' WHERE `word` = 'subject_wise';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'subject_wise_by', 'Subject Wise By', 'বিষয়ভিত্তিক নির্বাচন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'subject_wise_by');
UPDATE `languages` SET `bengali` = 'বিষয়ভিত্তিক নির্বাচন' WHERE `word` = 'subject_wise_by';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'subject_wise_reports', 'Subject Wise Reports', 'বিষয়ভিত্তিক রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'subject_wise_reports');
UPDATE `languages` SET `bengali` = 'বিষয়ভিত্তিক রিপোর্ট' WHERE `word` = 'subject_wise_reports';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'class_routine', 'Class Routine', 'ক্লাস রুটিন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'class_routine');
UPDATE `languages` SET `bengali` = 'ক্লাস রুটিন' WHERE `word` = 'class_routine';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'control_classes', 'Control Classes', 'শ্রেণি নিয়ন্ত্রণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'control_classes');
UPDATE `languages` SET `bengali` = 'শ্রেণি নিয়ন্ত্রণ' WHERE `word` = 'control_classes';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'create_admission', 'Create Admission', 'নতুন শিক্ষার্থী ভর্তি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'create_admission');
UPDATE `languages` SET `bengali` = 'নতুন শিক্ষার্থী ভর্তি' WHERE `word` = 'create_admission';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'online_admission', 'Online Admission', 'অনলাইন ভর্তি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'online_admission');
UPDATE `languages` SET `bengali` = 'অনলাইন ভর্তি' WHERE `word` = 'online_admission';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'multi_class', 'Multi Class', 'মাল্টি ক্লাস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'multi_class');
UPDATE `languages` SET `bengali` = 'মাল্টি ক্লাস' WHERE `word` = 'multi_class';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'multiple_import', 'Multiple Import', 'একত্রে শিক্ষার্থী ইমপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'multiple_import');
UPDATE `languages` SET `bengali` = 'একত্রে শিক্ষার্থী ইমপোর্ট' WHERE `word` = 'multiple_import';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'category', 'Category', 'ক্যাটাগরি / বিভাগ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'category');
UPDATE `languages` SET `bengali` = 'ক্যাটাগরি / বিভাগ' WHERE `word` = 'category';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'student_list', 'Student List', 'শিক্ষার্থী তালিকা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'student_list');
UPDATE `languages` SET `bengali` = 'শিক্ষার্থী তালিকা' WHERE `word` = 'student_list';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'deactivate_reason', 'Deactivate Reason', 'স্থগিতের কারণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'deactivate_reason');
UPDATE `languages` SET `bengali` = 'স্থগিতের কারণ' WHERE `word` = 'deactivate_reason';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'login_credential', 'Login Credential', 'লগইন তথ্য (ক্রেডেনশিয়াল)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'login_credential');
UPDATE `languages` SET `bengali` = 'লগইন তথ্য (ক্রেডেনশিয়াল)' WHERE `word` = 'login_credential';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'disable_authentication', 'Disable Authentication', 'লগইন নিষ্ক্রিয়করণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'disable_authentication');
UPDATE `languages` SET `bengali` = 'লগইন নিষ্ক্রিয়করণ' WHERE `word` = 'disable_authentication';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'student', 'Student', 'শিক্ষার্থী' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'student');
UPDATE `languages` SET `bengali` = 'শিক্ষার্থী' WHERE `word` = 'student';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'previous_school_details', 'Previous School Details', 'পূর্ববর্তী স্কুলের বিবরণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'previous_school_details');
UPDATE `languages` SET `bengali` = 'পূর্ববর্তী স্কুলের বিবরণ' WHERE `word` = 'previous_school_details';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'sibling_report', 'Sibling Report', 'সহোদর / ভাই-বোন রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'sibling_report');
UPDATE `languages` SET `bengali` = 'সহোদর / ভাই-বোন রিপোর্ট' WHERE `word` = 'sibling_report';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'class_&_section_report', 'Class & Section Report', 'শ্রেণি ও শাখা ভিত্তিক রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'class_&_section_report');
UPDATE `languages` SET `bengali` = 'শ্রেণি ও শাখা ভিত্তিক রিপোর্ট' WHERE `word` = 'class_&_section_report';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'admission_report', 'Admission Report', 'ভর্তি রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'admission_report');
UPDATE `languages` SET `bengali` = 'ভর্তি রিপোর্ট' WHERE `word` = 'admission_report';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'student_admission', 'Student Admission', 'শিক্ষার্থী ভর্তি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'student_admission');
UPDATE `languages` SET `bengali` = 'শিক্ষার্থী ভর্তি' WHERE `word` = 'student_admission';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'parents_list', 'Parents List', 'অভিভাবক তালিকা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'parents_list');
UPDATE `languages` SET `bengali` = 'অভিভাবক তালিকা' WHERE `word` = 'parents_list';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'add_parent', 'Add Parent', 'অভিভাবক যোগ করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'add_parent');
UPDATE `languages` SET `bengali` = 'অভিভাবক যোগ করুন' WHERE `word` = 'add_parent';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'guardian', 'Guardian', 'অভিভাবক' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'guardian');
UPDATE `languages` SET `bengali` = 'অভিভাবক' WHERE `word` = 'guardian';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'guardian_name', 'Guardian Name', 'অভিভাবকের নাম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'guardian_name');
UPDATE `languages` SET `bengali` = 'অভিভাবকের নাম' WHERE `word` = 'guardian_name';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'father_name', 'Father Name', 'পিতার নাম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'father_name');
UPDATE `languages` SET `bengali` = 'পিতার নাম' WHERE `word` = 'father_name';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'mother_name', 'Mother Name', 'মাতার নাম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'mother_name');
UPDATE `languages` SET `bengali` = 'মাতার নাম' WHERE `word` = 'mother_name';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'employee_list', 'Employee List', 'কর্মকর্তা-কর্মচারী তালিকা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'employee_list');
UPDATE `languages` SET `bengali` = 'কর্মকর্তা-কর্মচারী তালিকা' WHERE `word` = 'employee_list';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'add_employee', 'Add Employee', 'নতুন কর্মী যোগ করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'add_employee');
UPDATE `languages` SET `bengali` = 'নতুন কর্মী যোগ করুন' WHERE `word` = 'add_employee';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'department', 'Department', 'বিভাগ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'department');
UPDATE `languages` SET `bengali` = 'বিভাগ' WHERE `word` = 'department';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'add_department', 'Add Department', 'বিভাগ যোগ করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'add_department');
UPDATE `languages` SET `bengali` = 'বিভাগ যোগ করুন' WHERE `word` = 'add_department';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'designation', 'Designation', 'পদবী' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'designation');
UPDATE `languages` SET `bengali` = 'পদবী' WHERE `word` = 'designation';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'add_designation', 'Add Designation', 'পদবী যোগ করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'add_designation');
UPDATE `languages` SET `bengali` = 'পদবী যোগ করুন' WHERE `word` = 'add_designation';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'payroll', 'Payroll', 'বেতন-ভাতা (Payroll)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'payroll');
UPDATE `languages` SET `bengali` = 'বেতন-ভাতা (Payroll)' WHERE `word` = 'payroll';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'payroll_summary', 'Payroll Summary', 'বেতন সারসংক্ষেপ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'payroll_summary');
UPDATE `languages` SET `bengali` = 'বেতন সারসংক্ষেপ' WHERE `word` = 'payroll_summary';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'salary_template', 'Salary Template', 'বেতন স্কেল টেমপ্লেট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'salary_template');
UPDATE `languages` SET `bengali` = 'বেতন স্কেল টেমপ্লেট' WHERE `word` = 'salary_template';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'salary_assign', 'Salary Assign', 'বেতন নির্ধারণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'salary_assign');
UPDATE `languages` SET `bengali` = 'বেতন নির্ধারণ' WHERE `word` = 'salary_assign';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'salary_payment', 'Salary Payment', 'বেতন প্রদান' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'salary_payment');
UPDATE `languages` SET `bengali` = 'বেতন প্রদান' WHERE `word` = 'salary_payment';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'advance_salary', 'Advance Salary', 'অগ্রিম বেতন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'advance_salary');
UPDATE `languages` SET `bengali` = 'অগ্রিম বেতন' WHERE `word` = 'advance_salary';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'leave_manage', 'Leave Manage', 'ছুটি ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'leave_manage');
UPDATE `languages` SET `bengali` = 'ছুটি ব্যবস্থাপনা' WHERE `word` = 'leave_manage';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'award', 'Award', 'পুরস্কার ও সম্মাননা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'award');
UPDATE `languages` SET `bengali` = 'পুরস্কার ও সম্মাননা' WHERE `word` = 'award';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'teacher', 'Teacher', 'শিক্ষক' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'teacher');
UPDATE `languages` SET `bengali` = 'শিক্ষক' WHERE `word` = 'teacher';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'teachers', 'Teachers', 'শিক্ষকবৃন্দ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'teachers');
UPDATE `languages` SET `bengali` = 'শিক্ষকবৃন্দ' WHERE `word` = 'teachers';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'fees_group', 'Fees Group', 'ফি গ্রুপ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'fees_group');
UPDATE `languages` SET `bengali` = 'ফি গ্রুপ' WHERE `word` = 'fees_group';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'fees_type', 'Fees Type', 'ফি এর ধরন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'fees_type');
UPDATE `languages` SET `bengali` = 'ফি এর ধরন' WHERE `word` = 'fees_type';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'fees_fine_setup', 'Fees Fine Setup', 'জরিমানা নির্ধারণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'fees_fine_setup');
UPDATE `languages` SET `bengali` = 'জরিমানা নির্ধারণ' WHERE `word` = 'fees_fine_setup';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'fees_allocation', 'Fees Allocation', 'ফি বরাদ্দকরণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'fees_allocation');
UPDATE `languages` SET `bengali` = 'ফি বরাদ্দকরণ' WHERE `word` = 'fees_allocation';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'due_fees_invoice', 'Due Fees Invoice', 'বকেয়া ফি ইনভয়েস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'due_fees_invoice');
UPDATE `languages` SET `bengali` = 'বকেয়া ফি ইনভয়েস' WHERE `word` = 'due_fees_invoice';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'due_fees_report', 'Due Fees Report', 'বকেয়া ফি রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'due_fees_report');
UPDATE `languages` SET `bengali` = 'বকেয়া ফি রিপোর্ট' WHERE `word` = 'due_fees_report';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'fees_reminder', 'Fees Reminder', 'ফি বকেয়া রিমাইন্ডার' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'fees_reminder');
UPDATE `languages` SET `bengali` = 'ফি বকেয়া রিমাইন্ডার' WHERE `word` = 'fees_reminder';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'payments_history', 'Payments History', 'ফি পরিশোধ ও চালান' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'payments_history');
UPDATE `languages` SET `bengali` = 'ফি পরিশোধ ও চালান' WHERE `word` = 'payments_history';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'offline_payments', 'Offline Payments', 'অফলাইন পেমেন্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'offline_payments');
UPDATE `languages` SET `bengali` = 'অফলাইন পেমেন্ট' WHERE `word` = 'offline_payments';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'fees_report', 'Fees Report', 'ফি আদায়ের রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'fees_report');
UPDATE `languages` SET `bengali` = 'ফি আদায়ের রিপোর্ট' WHERE `word` = 'fees_report';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'fees_reports', 'Fees Reports', 'ফি রিপোর্ট সমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'fees_reports');
UPDATE `languages` SET `bengali` = 'ফি রিপোর্ট সমূহ' WHERE `word` = 'fees_reports';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'fees_history', 'Fees History', 'ফি ইতিহাস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'fees_history');
UPDATE `languages` SET `bengali` = 'ফি ইতিহাস' WHERE `word` = 'fees_history';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'fees_setup', 'Fees Setup', 'ফি সেটআপ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'fees_setup');
UPDATE `languages` SET `bengali` = 'ফি সেটআপ' WHERE `word` = 'fees_setup';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'fees_progress', 'Fees Progress', 'ফি পরিশোধ অগ্রগতি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'fees_progress');
UPDATE `languages` SET `bengali` = 'ফি পরিশোধ অগ্রগতি' WHERE `word` = 'fees_progress';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'account', 'Account', 'ব্যাংক ও ক্যাশ একাউন্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'account');
UPDATE `languages` SET `bengali` = 'ব্যাংক ও ক্যাশ একাউন্ট' WHERE `word` = 'account';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'new_deposit', 'New Deposit', 'নতুন জমা / আয়' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'new_deposit');
UPDATE `languages` SET `bengali` = 'নতুন জমা / আয়' WHERE `word` = 'new_deposit';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'new_expense', 'New Expense', 'নতুন খরচ / ব্যয়' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'new_expense');
UPDATE `languages` SET `bengali` = 'নতুন খরচ / ব্যয়' WHERE `word` = 'new_expense';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'all_transactions', 'All Transactions', 'সকল লেনদেন বিবরণী' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'all_transactions');
UPDATE `languages` SET `bengali` = 'সকল লেনদেন বিবরণী' WHERE `word` = 'all_transactions';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'voucher', 'Voucher', 'ভাউচার' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'voucher');
UPDATE `languages` SET `bengali` = 'ভাউচার' WHERE `word` = 'voucher';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'balance', 'Balance', 'অবশিষ্ট ব্যালেন্স' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'balance');
UPDATE `languages` SET `bengali` = 'অবশিষ্ট ব্যালেন্স' WHERE `word` = 'balance';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'opening_balance', 'Opening Balance', 'প্রারম্ভিক ব্যালেন্স' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'opening_balance');
UPDATE `languages` SET `bengali` = 'প্রারম্ভিক ব্যালেন্স' WHERE `word` = 'opening_balance';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'receipts_report', 'Receipts Report', 'আদায় ও প্রাপ্তি রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'receipts_report');
UPDATE `languages` SET `bengali` = 'আদায় ও প্রাপ্তি রিপোর্ট' WHERE `word` = 'receipts_report';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'expense', 'Expense', 'ব্যয় / খরচ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'expense');
UPDATE `languages` SET `bengali` = 'ব্যয় / খরচ' WHERE `word` = 'expense';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'hostel', 'Hostel', 'হোস্টেল / ছাত্রাবাস' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'hostel');
UPDATE `languages` SET `bengali` = 'হোস্টেল / ছাত্রাবাস' WHERE `word` = 'hostel';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'hostel_master', 'Hostel Master', 'হোস্টেল ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'hostel_master');
UPDATE `languages` SET `bengali` = 'হোস্টেল ব্যবস্থাপনা' WHERE `word` = 'hostel_master';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'hostel_room', 'Hostel Room', 'হোস্টেল রুম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'hostel_room');
UPDATE `languages` SET `bengali` = 'হোস্টেল রুম' WHERE `word` = 'hostel_room';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'hostel_category', 'Hostel Category', 'হোস্টেল ক্যাটাগরি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'hostel_category');
UPDATE `languages` SET `bengali` = 'হোস্টেল ক্যাটাগরি' WHERE `word` = 'hostel_category';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'allocation_report', 'Allocation Report', 'হোস্টেল বরাদ্দ রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'allocation_report');
UPDATE `languages` SET `bengali` = 'হোস্টেল বরাদ্দ রিপোর্ট' WHERE `word` = 'allocation_report';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'transport', 'Transport', 'পরিবহন ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'transport');
UPDATE `languages` SET `bengali` = 'পরিবহন ব্যবস্থাপনা' WHERE `word` = 'transport';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'route_master', 'Route Master', 'রুট মাস্টার' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'route_master');
UPDATE `languages` SET `bengali` = 'রুট মাস্টার' WHERE `word` = 'route_master';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'vehicle_master', 'Vehicle Master', 'যানবাহন মাস্টার' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'vehicle_master');
UPDATE `languages` SET `bengali` = 'যানবাহন মাস্টার' WHERE `word` = 'vehicle_master';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'assign_vehicle', 'Assign Vehicle', 'যানবাহন বরাদ্দ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'assign_vehicle');
UPDATE `languages` SET `bengali` = 'যানবাহন বরাদ্দ' WHERE `word` = 'assign_vehicle';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'transport_route', 'Transport Route', 'পরিবহন রুট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'transport_route');
UPDATE `languages` SET `bengali` = 'পরিবহন রুট' WHERE `word` = 'transport_route';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'transport_details', 'Transport Details', 'পরিবহন বিবরণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'transport_details');
UPDATE `languages` SET `bengali` = 'পরিবহন বিবরণ' WHERE `word` = 'transport_details';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'stoppage', 'Stoppage', 'স্টপেজ / বিরতি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'stoppage');
UPDATE `languages` SET `bengali` = 'স্টপেজ / বিরতি' WHERE `word` = 'stoppage';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'pickup_point', 'Pickup Point', 'পিকআপ পয়েন্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'pickup_point');
UPDATE `languages` SET `bengali` = 'পিকআপ পয়েন্ট' WHERE `word` = 'pickup_point';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'vehicle_no', 'Vehicle No', 'যানবাহন নং' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'vehicle_no');
UPDATE `languages` SET `bengali` = 'যানবাহন নং' WHERE `word` = 'vehicle_no';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'exam', 'Exam', 'পরীক্ষা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'exam');
UPDATE `languages` SET `bengali` = 'পরীক্ষা' WHERE `word` = 'exam';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'examination', 'Examination', 'পরীক্ষা ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'examination');
UPDATE `languages` SET `bengali` = 'পরীক্ষা ব্যবস্থাপনা' WHERE `word` = 'examination';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'exam_term', 'Exam Term', 'পরীক্ষার মেয়াদ / টার্ম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'exam_term');
UPDATE `languages` SET `bengali` = 'পরীক্ষার মেয়াদ / টার্ম' WHERE `word` = 'exam_term';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'exam_setup', 'Exam Setup', 'পরীক্ষা সেটআপ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'exam_setup');
UPDATE `languages` SET `bengali` = 'পরীক্ষা সেটআপ' WHERE `word` = 'exam_setup';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'exam_result', 'Exam Result', 'পরীক্ষার ফলাফল' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'exam_result');
UPDATE `languages` SET `bengali` = 'পরীক্ষার ফলাফল' WHERE `word` = 'exam_result';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'exam_master', 'Exam Master', 'পরীক্ষা মাস্টার' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'exam_master');
UPDATE `languages` SET `bengali` = 'পরীক্ষা মাস্টার' WHERE `word` = 'exam_master';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'exam_hall', 'Exam Hall', 'পরীক্ষা হল' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'exam_hall');
UPDATE `languages` SET `bengali` = 'পরীক্ষা হল' WHERE `word` = 'exam_hall';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'marksheet', 'Marksheet', 'মার্কশীট / নম্বরপত্র' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'marksheet');
UPDATE `languages` SET `bengali` = 'মার্কশীট / নম্বরপত্র' WHERE `word` = 'marksheet';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'tabulation_sheet', 'Tabulation Sheet', 'টেবুলেশন শীট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'tabulation_sheet');
UPDATE `languages` SET `bengali` = 'টেবুলেশন শীট' WHERE `word` = 'tabulation_sheet';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'sheet', 'Sheet', 'শীট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'sheet');
UPDATE `languages` SET `bengali` = 'শীট' WHERE `word` = 'sheet';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'generate_position', 'Generate Position', 'মেধাক্রম / পজিশন নির্ধারণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'generate_position');
UPDATE `languages` SET `bengali` = 'মেধাক্রম / পজিশন নির্ধারণ' WHERE `word` = 'generate_position';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'position', 'Position', 'মেধাক্রম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'position');
UPDATE `languages` SET `bengali` = 'মেধাক্রম' WHERE `word` = 'position';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'online_exam', 'Online Exam', 'অনলাইন পরীক্ষা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'online_exam');
UPDATE `languages` SET `bengali` = 'অনলাইন পরীক্ষা' WHERE `word` = 'online_exam';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'question_bank', 'Question Bank', 'প্রশ্ন ব্যাংক' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'question_bank');
UPDATE `languages` SET `bengali` = 'প্রশ্ন ব্যাংক' WHERE `word` = 'question_bank';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'question_group', 'Question Group', 'প্রশ্ন গ্রুপ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'question_group');
UPDATE `languages` SET `bengali` = 'প্রশ্ন গ্রুপ' WHERE `word` = 'question_group';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'lesson', 'Lesson', 'পাঠ / অধ্যায়' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'lesson');
UPDATE `languages` SET `bengali` = 'পাঠ / অধ্যায়' WHERE `word` = 'lesson';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'topic', 'Topic', 'টপিক / বিষয়বস্তু' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'topic');
UPDATE `languages` SET `bengali` = 'টপিক / বিষয়বস্তু' WHERE `word` = 'topic';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'manage_lesson', 'Manage Lesson', 'পাঠ ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'manage_lesson');
UPDATE `languages` SET `bengali` = 'পাঠ ব্যবস্থাপনা' WHERE `word` = 'manage_lesson';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'manage_topic', 'Manage Topic', 'টপিক ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'manage_topic');
UPDATE `languages` SET `bengali` = 'টপিক ব্যবস্থাপনা' WHERE `word` = 'manage_topic';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'manage_syllabus', 'Manage Syllabus', 'সিলেবাস ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'manage_syllabus');
UPDATE `languages` SET `bengali` = 'সিলেবাস ব্যবস্থাপনা' WHERE `word` = 'manage_syllabus';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'manage_syllabus_status', 'Manage Syllabus Status', 'সিলেবাস অগ্রগতি ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'manage_syllabus_status');
UPDATE `languages` SET `bengali` = 'সিলেবাস অগ্রগতি ব্যবস্থাপনা' WHERE `word` = 'manage_syllabus_status';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'overview_reports', 'Overview Reports', 'সার্বিক প্রতিবেদন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'overview_reports');
UPDATE `languages` SET `bengali` = 'সার্বিক প্রতিবেদন' WHERE `word` = 'overview_reports';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'daily_reports', 'Daily Reports', 'দৈনিক রিপোর্ট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'daily_reports');
UPDATE `languages` SET `bengali` = 'দৈনিক রিপোর্ট' WHERE `word` = 'daily_reports';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'progress', 'Progress', 'অগ্রগতি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'progress');
UPDATE `languages` SET `bengali` = 'অগ্রগতি' WHERE `word` = 'progress';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'period', 'Period', 'পিরিয়ড / সময়' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'period');
UPDATE `languages` SET `bengali` = 'পিরিয়ড / সময়' WHERE `word` = 'period';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'total_topics', 'Total Topics', 'মোট টপিক' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'total_topics');
UPDATE `languages` SET `bengali` = 'মোট টপিক' WHERE `word` = 'total_topics';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'total_lessons', 'Total Lessons', 'মোট পাঠ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'total_lessons');
UPDATE `languages` SET `bengali` = 'মোট পাঠ' WHERE `word` = 'total_lessons';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'completion_date', 'Completion Date', 'সমাপ্তির তারিখ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'completion_date');
UPDATE `languages` SET `bengali` = 'সমাপ্তির তারিখ' WHERE `word` = 'completion_date';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'update_lesson_status', 'Update Lesson Status', 'পাঠের অগ্রগতি আপডেট' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'update_lesson_status');
UPDATE `languages` SET `bengali` = 'পাঠের অগ্রগতি আপডেট' WHERE `word` = 'update_lesson_status';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'topic_name', 'Topic Name', 'টপিকের নাম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'topic_name');
UPDATE `languages` SET `bengali` = 'টপিকের নাম' WHERE `word` = 'topic_name';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'lesson_name', 'Lesson Name', 'পাঠের নাম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'lesson_name');
UPDATE `languages` SET `bengali` = 'পাঠের নাম' WHERE `word` = 'lesson_name';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'syllabus_status', 'Syllabus Status', 'সিলেবাসের অবস্থা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'syllabus_status');
UPDATE `languages` SET `bengali` = 'সিলেবাসের অবস্থা' WHERE `word` = 'syllabus_status';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'syllabus_status_list', 'Syllabus Status List', 'সিলেবাস অবস্থা তালিকা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'syllabus_status_list');
UPDATE `languages` SET `bengali` = 'সিলেবাস অবস্থা তালিকা' WHERE `word` = 'syllabus_status_list';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'overall_syllabus_completion_rate', 'Overall Syllabus Completion Rate', 'সার্বিক সিলেবাস সমাপ্তির হার' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'overall_syllabus_completion_rate');
UPDATE `languages` SET `bengali` = 'সার্বিক সিলেবাস সমাপ্তির হার' WHERE `word` = 'overall_syllabus_completion_rate';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'syllabus_overview_progress', 'Syllabus Overview Progress', 'সিলেবাস সারসংক্ষেপ ও অগ্রগতি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'syllabus_overview_progress');
UPDATE `languages` SET `bengali` = 'সিলেবাস সারসংক্ষেপ ও অগ্রগতি' WHERE `word` = 'syllabus_overview_progress';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'no_lessons_found_for_this_topic', 'No Lessons Found For This Topic', 'এই টপিকের জন্য কোনো পাঠ পাওয়া যায়নি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'no_lessons_found_for_this_topic');
UPDATE `languages` SET `bengali` = 'এই টপিকের জন্য কোনো পাঠ পাওয়া যায়নি' WHERE `word` = 'no_lessons_found_for_this_topic';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'topic_list', 'Topic List', 'টপিক তালিকা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'topic_list');
UPDATE `languages` SET `bengali` = 'টপিক তালিকা' WHERE `word` = 'topic_list';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'lesson_list', 'Lesson List', 'পাঠ তালিকা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'lesson_list');
UPDATE `languages` SET `bengali` = 'পাঠ তালিকা' WHERE `word` = 'lesson_list';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'in_progress', 'In Progress', 'চলমান' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'in_progress');
UPDATE `languages` SET `bengali` = 'চলমান' WHERE `word` = 'in_progress';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'completed', 'Completed', 'সম্পন্ন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'completed');
UPDATE `languages` SET `bengali` = 'সম্পন্ন' WHERE `word` = 'completed';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'time_from', 'Time From', 'শুরুর সময়' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'time_from');
UPDATE `languages` SET `bengali` = 'শুরুর সময়' WHERE `word` = 'time_from';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'time_to', 'Time To', 'শেষের সময়' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'time_to');
UPDATE `languages` SET `bengali` = 'শেষের সময়' WHERE `word` = 'time_to';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'optional_note_or_summary', 'Optional Note Or Summary', 'সংক্ষিপ্ত নোট বা বিবরণ (ঐচ্ছিক)' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'optional_note_or_summary');
UPDATE `languages` SET `bengali` = 'সংক্ষিপ্ত নোট বা বিবরণ (ঐচ্ছিক)' WHERE `word` = 'optional_note_or_summary';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'allowed_types', 'Allowed Types', 'অনুমোদিত ফাইলের ধরন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'allowed_types');
UPDATE `languages` SET `bengali` = 'অনুমোদিত ফাইলের ধরন' WHERE `word` = 'allowed_types';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'edit_lesson_status', 'Edit Lesson Status', 'পাঠের অবস্থা সংশোধন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'edit_lesson_status');
UPDATE `languages` SET `bengali` = 'পাঠের অবস্থা সংশোধন' WHERE `word` = 'edit_lesson_status';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'books', 'Books', 'বই তালিকা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'books');
UPDATE `languages` SET `bengali` = 'বই তালিকা' WHERE `word` = 'books';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'books_category', 'Books Category', 'বইয়ের ক্যাটাগরি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'books_category');
UPDATE `languages` SET `bengali` = 'বইয়ের ক্যাটাগরি' WHERE `word` = 'books_category';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'book_issue/return', 'Book Issue/return', 'বই ইস্যু ও জমা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'book_issue/return');
UPDATE `languages` SET `bengali` = 'বই ইস্যু ও জমা' WHERE `word` = 'book_issue/return';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'filter', 'Filter', 'ফিল্টার' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'filter');
UPDATE `languages` SET `bengali` = 'ফিল্টার' WHERE `word` = 'filter';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'select_ground', 'Select Ground', 'অনুসন্ধানের শর্ত নির্বাচন করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'select_ground');
UPDATE `languages` SET `bengali` = 'অনুসন্ধানের শর্ত নির্বাচন করুন' WHERE `word` = 'select_ground';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'select', 'Select', 'নির্বাচন করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'select');
UPDATE `languages` SET `bengali` = 'নির্বাচন করুন' WHERE `word` = 'select';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'select_class_first', 'Select Class First', 'প্রথমে শ্রেণি নির্বাচন করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'select_class_first');
UPDATE `languages` SET `bengali` = 'প্রথমে শ্রেণি নির্বাচন করুন' WHERE `word` = 'select_class_first';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'select_subject_first', 'Select Subject First', 'প্রথমে বিষয় নির্বাচন করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'select_subject_first');
UPDATE `languages` SET `bengali` = 'প্রথমে বিষয় নির্বাচন করুন' WHERE `word` = 'select_subject_first';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'select_topic_first', 'Select Topic First', 'প্রথমে টপিক নির্বাচন করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'select_topic_first');
UPDATE `languages` SET `bengali` = 'প্রথমে টপিক নির্বাচন করুন' WHERE `word` = 'select_topic_first';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'select_class_and_section_first', 'Select Class And Section First', 'প্রথমে শ্রেণি ও শাখা নির্বাচন করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'select_class_and_section_first');
UPDATE `languages` SET `bengali` = 'প্রথমে শ্রেণি ও শাখা নির্বাচন করুন' WHERE `word` = 'select_class_and_section_first';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'quick_view', 'Quick View', 'দ্রুত দেখুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'quick_view');
UPDATE `languages` SET `bengali` = 'দ্রুত দেখুন' WHERE `word` = 'quick_view';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'action', 'Action', 'পদক্ষেপ / অ্যাকশন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'action');
UPDATE `languages` SET `bengali` = 'পদক্ষেপ / অ্যাকশন' WHERE `word` = 'action';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'type', 'Type', 'প্রকার / ধরন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'type');
UPDATE `languages` SET `bengali` = 'প্রকার / ধরন' WHERE `word` = 'type';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'age', 'Age', 'বয়স' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'age');
UPDATE `languages` SET `bengali` = 'বয়স' WHERE `word` = 'age';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'date_of_birth', 'Date Of Birth', 'জন্ম তারিখ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'date_of_birth');
UPDATE `languages` SET `bengali` = 'জন্ম তারিখ' WHERE `word` = 'date_of_birth';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'birthday', 'Birthday', 'জন্ম তারিখ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'birthday');
UPDATE `languages` SET `bengali` = 'জন্ম তারিখ' WHERE `word` = 'birthday';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'gender', 'Gender', 'লিঙ্গ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'gender');
UPDATE `languages` SET `bengali` = 'লিঙ্গ' WHERE `word` = 'gender';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'male', 'Male', 'পুরুষ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'male');
UPDATE `languages` SET `bengali` = 'পুরুষ' WHERE `word` = 'male';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'female', 'Female', 'মহিলা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'female');
UPDATE `languages` SET `bengali` = 'মহিলা' WHERE `word` = 'female';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'roll', 'Roll', 'রোল নং' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'roll');
UPDATE `languages` SET `bengali` = 'রোল নং' WHERE `word` = 'roll';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'photo', 'Photo', 'ছবি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'photo');
UPDATE `languages` SET `bengali` = 'ছবি' WHERE `word` = 'photo';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'profile_picture', 'Profile Picture', 'প্রোফাইল ছবি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'profile_picture');
UPDATE `languages` SET `bengali` = 'প্রোফাইল ছবি' WHERE `word` = 'profile_picture';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'name', 'Name', 'নাম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'name');
UPDATE `languages` SET `bengali` = 'নাম' WHERE `word` = 'name';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'mobile_no', 'Mobile No', 'মোবাইল নং' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'mobile_no');
UPDATE `languages` SET `bengali` = 'মোবাইল নং' WHERE `word` = 'mobile_no';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'blood_group', 'Blood Group', 'রক্তের গ্রুপ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'blood_group');
UPDATE `languages` SET `bengali` = 'রক্তের গ্রুপ' WHERE `word` = 'blood_group';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'religion', 'Religion', 'ধর্ম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'religion');
UPDATE `languages` SET `bengali` = 'ধর্ম' WHERE `word` = 'religion';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'address', 'Address', 'ঠিকানা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'address');
UPDATE `languages` SET `bengali` = 'ঠিকানা' WHERE `word` = 'address';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'present_address', 'Present Address', 'বর্তমান ঠিকানা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'present_address');
UPDATE `languages` SET `bengali` = 'বর্তমান ঠিকানা' WHERE `word` = 'present_address';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'permanent_address', 'Permanent Address', 'স্থায়ী ঠিকানা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'permanent_address');
UPDATE `languages` SET `bengali` = 'স্থায়ী ঠিকানা' WHERE `word` = 'permanent_address';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'occupation', 'Occupation', 'পেশা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'occupation');
UPDATE `languages` SET `bengali` = 'পেশা' WHERE `word` = 'occupation';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'education', 'Education', 'শিক্ষাগত যোগ্যতা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'education');
UPDATE `languages` SET `bengali` = 'শিক্ষাগত যোগ্যতা' WHERE `word` = 'education';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'qualification', 'Qualification', 'শিক্ষাগত যোগ্যতা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'qualification');
UPDATE `languages` SET `bengali` = 'শিক্ষাগত যোগ্যতা' WHERE `word` = 'qualification';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'city', 'City', 'শহর / জেলা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'city');
UPDATE `languages` SET `bengali` = 'শহর / জেলা' WHERE `word` = 'city';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'state', 'State', 'বিভাগ / রাজ্য' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'state');
UPDATE `languages` SET `bengali` = 'বিভাগ / রাজ্য' WHERE `word` = 'state';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'remarks', 'Remarks', 'মন্তব্য' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'remarks');
UPDATE `languages` SET `bengali` = 'মন্তব্য' WHERE `word` = 'remarks';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'status', 'Status', 'অবস্থা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'status');
UPDATE `languages` SET `bengali` = 'অবস্থা' WHERE `word` = 'status';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'active', 'Active', 'সক্রিয়' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'active');
UPDATE `languages` SET `bengali` = 'সক্রিয়' WHERE `word` = 'active';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'inactive', 'Inactive', 'নিষ্ক্রিয়' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'inactive');
UPDATE `languages` SET `bengali` = 'নিষ্ক্রিয়' WHERE `word` = 'inactive';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'close', 'Close', 'বন্ধ করুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'close');
UPDATE `languages` SET `bengali` = 'বন্ধ করুন' WHERE `word` = 'close';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'cancel', 'Cancel', 'বাতিল' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'cancel');
UPDATE `languages` SET `bengali` = 'বাতিল' WHERE `word` = 'cancel';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'send', 'Send', 'পাঠান' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'send');
UPDATE `languages` SET `bengali` = 'পাঠান' WHERE `word` = 'send';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'payments', 'Payments', 'পরিশোধ সমূহ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'payments');
UPDATE `languages` SET `bengali` = 'পরিশোধ সমূহ' WHERE `word` = 'payments';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'manage', 'Manage', 'ব্যবস্থাপনা' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'manage');
UPDATE `languages` SET `bengali` = 'ব্যবস্থাপনা' WHERE `word` = 'manage';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'user_login_log', 'User Login Log', 'ইউজার লগইন হিস্ট্রি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'user_login_log');
UPDATE `languages` SET `bengali` = 'ইউজার লগইন হিস্ট্রি' WHERE `word` = 'user_login_log';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'usename', 'Usename', 'ইউজারনেম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'usename');
UPDATE `languages` SET `bengali` = 'ইউজারনেম' WHERE `word` = 'usename';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'username', 'Username', 'ব্যবহারকারীর নাম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'username');
UPDATE `languages` SET `bengali` = 'ব্যবহারকারীর নাম' WHERE `word` = 'username';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'password', 'Password', 'পাসওয়ার্ড' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'password');
UPDATE `languages` SET `bengali` = 'পাসওয়ার্ড' WHERE `word` = 'password';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'retype_password', 'Retype Password', 'পাসওয়ার্ড পুনরায় লিখুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'retype_password');
UPDATE `languages` SET `bengali` = 'পাসওয়ার্ড পুনরায় লিখুন' WHERE `word` = 'retype_password';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'register_no', 'Register No', 'রেজিস্টার নং' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'register_no');
UPDATE `languages` SET `bengali` = 'রেজিস্টার নং' WHERE `word` = 'register_no';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'academic_year', 'Academic Year', 'শিক্ষাবর্ষ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'academic_year');
UPDATE `languages` SET `bengali` = 'শিক্ষাবর্ষ' WHERE `word` = 'academic_year';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'admission_date', 'Admission Date', 'ভর্তির তারিখ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'admission_date');
UPDATE `languages` SET `bengali` = 'ভর্তির তারিখ' WHERE `word` = 'admission_date';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'school_name', 'School Name', 'প্রতিষ্ঠানের নাম' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'school_name');
UPDATE `languages` SET `bengali` = 'প্রতিষ্ঠানের নাম' WHERE `word` = 'school_name';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'yes_continue', 'Yes Continue', 'হ্যাঁ, এগিয়ে যান' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'yes_continue');
UPDATE `languages` SET `bengali` = 'হ্যাঁ, এগিয়ে যান' WHERE `word` = 'yes_continue';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'are_you_sure', 'Are You Sure', 'আপনি কি নিশ্চিত?' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'are_you_sure');
UPDATE `languages` SET `bengali` = 'আপনি কি নিশ্চিত?' WHERE `word` = 'are_you_sure';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'delete_this_information', 'Delete This Information', 'আপনি কি এই তথ্যটি মুছে ফেলতে চান?' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'delete_this_information');
UPDATE `languages` SET `bengali` = 'আপনি কি এই তথ্যটি মুছে ফেলতে চান?' WHERE `word` = 'delete_this_information';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'deleted_note', 'Deleted Note', '* দ্রষ্টব্য: এই তথ্যটি স্থায়ীভাবে মুছে যাবে' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'deleted_note');
UPDATE `languages` SET `bengali` = '* দ্রষ্টব্য: এই তথ্যটি স্থায়ীভাবে মুছে যাবে' WHERE `word` = 'deleted_note';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'bulk_delete', 'Bulk Delete', 'একত্রে মুছুন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'bulk_delete');
UPDATE `languages` SET `bengali` = 'একত্রে মুছুন' WHERE `word` = 'bulk_delete';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'distribution', 'Distribution', 'বিতরণ' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'distribution');
UPDATE `languages` SET `bengali` = 'বিতরণ' WHERE `word` = 'distribution';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'schedule', 'Schedule', 'সময়সূচি' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'schedule');
UPDATE `languages` SET `bengali` = 'সময়সূচি' WHERE `word` = 'schedule';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'transitions', 'Transitions', 'ট্রানজিশন / লেনদেন' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'transitions');
UPDATE `languages` SET `bengali` = 'ট্রানজিশন / লেনদেন' WHERE `word` = 'transitions';
INSERT INTO `languages` (`word`, `english`, `bengali`) SELECT 'statement', 'Statement', 'বিবরণী' WHERE NOT EXISTS (SELECT 1 FROM `languages` WHERE `word` = 'statement');
UPDATE `languages` SET `bengali` = 'বিবরণী' WHERE `word` = 'statement';