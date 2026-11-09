<?php if (is_superadmin_loggedin() ): ?>
	<?php $this->load->view('frontend/branch_select'); ?>
<?php endif; if (!empty($branch_id)): 

// Safe fallbacks for all homepage sections
if (empty($wellcome)) $wellcome = array('title' => 'স্বাগতম', 'title_en' => 'Welcome', 'subtitle' => '', 'subtitle_en' => '', 'description' => '', 'description_en' => '', 'elements' => '', 'color1' => '#000', 'active' => 1);
$well_ele = !empty($wellcome['elements']) ? json_decode($wellcome['elements'], true) : array('image' => '');

if (empty($notice_sec)) $notice_sec = array('title' => 'নোটিশ বোর্ড', 'title_en' => 'Notice Board', 'subtitle' => '', 'subtitle_en' => '', 'active' => 1, 'elements' => '');
$notice_ele = !empty($notice_sec['elements']) ? json_decode($notice_sec['elements'], true) : array('limit' => 5);

if (empty($leadership_sec)) $leadership_sec = array('title' => 'নেতৃত্বের বার্তা', 'title_en' => 'Messages from Leadership', 'subtitle' => '', 'subtitle_en' => '', 'active' => 1);

if (empty($teachers)) $teachers = array('title' => 'শিক্ষকমণ্ডলী', 'title_en' => 'Our Teachers', 'subtitle' => '', 'subtitle_en' => '', 'active' => 1);

if (empty($events_sec)) $events_sec = array('title' => 'সর্বশেষ ইভেন্ট', 'title_en' => 'Latest Events', 'subtitle' => '', 'subtitle_en' => '', 'active' => 1);

if (empty($video_sec)) $video_sec = array('title' => 'ভিডিও গ্যালারি', 'title_en' => 'Video Gallery', 'subtitle' => '', 'subtitle_en' => '', 'active' => 1);

if (empty($gallery_sec)) $gallery_sec = array('title' => 'ফটো গ্যালারি', 'title_en' => 'Photo Gallery', 'subtitle' => '', 'subtitle_en' => '', 'active' => 1);

if (empty($statistics)) $statistics = array('title' => 'আমাদের অর্জন ও পরিসংখ্যান', 'title_en' => 'Our Achievements & Statistics', 'subtitle' => '', 'subtitle_en' => '', 'active' => 1);

if (empty($hero_sec)) $hero_sec = array('title' => '', 'title_en' => '', 'subtitle' => '', 'subtitle_en' => '', 'description' => '', 'description_en' => '', 'active' => 1, 'elements' => '');
$hero_ele = !empty($hero_sec['elements']) ? json_decode($hero_sec['elements'], true) : array();

if (empty($home_seo)) $home_seo = array('page_title' => '', 'meta_keyword' => '', 'meta_description' => '');
?>
<div class="row">
	<div class="col-md-3 mb-md">
		<?php include 'sidebar.php'; ?>
	</div>
	<div class="col-md-9">
		<section class="panel">
			<div class="tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#welcome" data-toggle="tab"><i class="fas fa-hands-praying"></i> <?php echo (get_current_lang() == 'en' ? 'Welcome Message' : 'স্বাগতম বার্তা'); ?></a>
					</li>
					<li>
						<a href="#slider" data-toggle="tab"><i class="fas fa-images"></i> <?php echo (get_current_lang() == 'en' ? 'Banner Slider' : 'ব্যানার স্লাইডার'); ?></a>
					</li>
					<li>
						<a href="#notice" data-toggle="tab"><i class="fas fa-clipboard-list"></i> <?php echo (get_current_lang() == 'en' ? 'Notice Board' : 'নোটিশ বোর্ড'); ?></a>
					</li>
					<li>
						<a href="#leadership" data-toggle="tab"><i class="fas fa-comments"></i> <?php echo (get_current_lang() == 'en' ? 'Leadership' : 'নেতৃত্বের বার্তা'); ?></a>
					</li>
					<li>
						<a href="#teachers" data-toggle="tab"><i class="fas fa-chalkboard-teacher"></i> <?php echo (get_current_lang() == 'en' ? 'Our Teachers' : 'শিক্ষকমণ্ডলী'); ?></a>
					</li>
					<li>
						<a href="#events" data-toggle="tab"><i class="fas fa-calendar-alt"></i> <?php echo (get_current_lang() == 'en' ? 'Latest Events' : 'সর্বশেষ ইভেন্ট'); ?></a>
					</li>
					<li>
						<a href="#video_gallery" data-toggle="tab"><i class="fas fa-video"></i> <?php echo (get_current_lang() == 'en' ? 'Video Gallery' : 'ভিডিও গ্যালারি'); ?></a>
					</li>
					<li>
						<a href="#gallery" data-toggle="tab"><i class="fas fa-camera"></i> <?php echo (get_current_lang() == 'en' ? 'Photo Gallery' : 'ফটো গ্যালারি'); ?></a>
					</li>
					<li>
						<a href="#statistics" data-toggle="tab"><i class="fas fa-chart-line"></i> <?php echo (get_current_lang() == 'en' ? 'Statistics' : 'অর্জন ও পরিসংখ্যান'); ?></a>
					</li>
					<li>
						<a href="#hero" data-toggle="tab"><i class="fas fa-bolt"></i> <?php echo (get_current_lang() == 'en' ? 'Hero & Buttons' : 'হিরো ও বাটন'); ?></a>
					</li>
					<li>
						<a href="#options" data-toggle="tab"><i class="fas fa-cog"></i> <?php echo (get_current_lang() == 'en' ? 'SEO' : 'এসইও'); ?></a>
					</li>
				</ul>
				<div class="tab-content">

					<!-- 1. WELCOME MESSAGE TAB -->
					<div class="tab-pane active" id="welcome">
						<?php echo form_open_multipart('frontend/section/home_wellcome' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (বাংলা) <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="wel_title" value="<?php echo set_value('wel_title', $wellcome['title']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="wel_title_en" value="<?php echo set_value('wel_title_en', isset($wellcome['title_en']) ? $wellcome['title_en'] : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (বাংলা)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle" value="<?php echo set_value('subtitle', $wellcome['subtitle']); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle_en" value="<?php echo set_value('subtitle_en', isset($wellcome['subtitle_en']) ? $wellcome['subtitle_en'] : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('description'); ?> (বাংলা) <span class="required">*</span></label>
								<div class="col-md-7">
									<textarea class="form-control" name="description" rows="5"><?php echo set_value('description', $wellcome['description']); ?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('description'); ?> (English)</label>
								<div class="col-md-7">
									<textarea class="form-control" name="description_en" rows="5"><?php echo set_value('description_en', isset($wellcome['description_en']) ? $wellcome['description_en'] : ''); ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('photo'); ?></label>
								<div class="col-md-4">
									<?php
										$well_img_val = !empty($well_ele['image']) ? $well_ele['image'] : '';
										$well_preview_url = '';
										if (!empty($well_img_val)) {
											if (file_exists(FCPATH . 'uploads/frontend/home_page/' . $well_img_val)) {
												$well_preview_url = base_url('uploads/frontend/home_page/' . $well_img_val);
											} elseif (file_exists(FCPATH . 'assets/frontend/dvs/storage/uploads/settings/' . $well_img_val)) {
												$well_preview_url = base_url('assets/frontend/dvs/storage/uploads/settings/' . $well_img_val);
											}
										}
									?>
									<input type="hidden" name="old_photo" value="<?php echo html_escape($well_img_val); ?>">
									<input type="file" name="photo" class="dropify" data-height="150" <?php if (!empty($well_preview_url)) { ?>data-default-file="<?php echo $well_preview_url; ?>"<?php } ?> data-allowed-file-extensions="jpg jpeg png gif webp" />
									<div class="checkbox-custom checkbox-default mt-xs">
										<input type="checkbox" name="remove_photo" id="remove_welcome_photo" value="1">
										<label for="remove_welcome_photo"><?php echo (get_current_lang() == 'en' ? 'Remove current photo' : 'বর্তমান ছবি মুছে ফেলুন'); ?></label>
									</div>
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Title Text Color</label>
								<div class="col-md-4">
									<input type="text" class="complex-colorpicker form-control" name="title_text_color" value="<?php echo !empty($wellcome['color1']) ? $wellcome['color1'] : 'rgb(220, 49, 49)'; ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Show Website</label>
								<div class="col-md-4">
									<div class="material-switch mt-xs">
										<input id="isvisiblewell" name="isvisible" type="checkbox" <?php echo (!isset($wellcome['active']) || $wellcome['active'] == 1) ? 'checked' : ''; ?> />
										<label for="isvisiblewell" class="label-primary"></label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>

					<!-- 2. BANNER SLIDER TAB -->
					<div class="tab-pane" id="slider">
						<?php echo form_open_multipart('frontend/section/home_slider_save' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (বাংলা / English) <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="title" value="" placeholder="স্লাইডারের শিরোনাম..." />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('description'); ?></label>
								<div class="col-md-7">
									<textarea class="form-control" name="description" rows="2" placeholder="সংক্ষিপ্ত বিবরণ (ঐচ্ছিক)..."></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">বাটন ১ টেক্সট ও লিংক</label>
								<div class="col-md-3">
									<input type="text" class="form-control" name="button_text_1" placeholder="Button Text..." />
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" name="button_url_1" placeholder="URL (e.g. admission)..." />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('photo'); ?> <span class="required">*</span></label>
								<div class="col-md-4">
									<input type="file" name="photo" class="dropify" data-height="150" data-allowed-file-extensions="jpg jpeg png gif webp" />
									<span class="error"></span>
									<small class="text-muted">প্রস্তাবিত সাইজ: 1920 x 460 বা অনুরূপ প্রশস্ত ছবি</small>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>

						<hr>
						<h4 class="mb-md"><i class="fas fa-list text-primary"></i> বর্তমান স্লাইডার তালিকা (Current Sliders)</h4>
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th width="80"><?php echo translate('photo'); ?></th>
									<th><?php echo translate('title'); ?></th>
									<th>বাটন</th>
									<th width="100"><?php echo translate('action'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
								if (!empty($sliderlist)):
									foreach ($sliderlist as $sl):
										$sl_ele = !empty($sl['elements']) ? json_decode($sl['elements'], true) : [];
										$sl_img = !empty($sl_ele['image']) ? $sl_ele['image'] : '';
										$sl_img_url = base_url('uploads/app_image/default.png');
										if (!empty($sl_img) && file_exists(FCPATH . 'uploads/frontend/slider/' . $sl_img)) {
											$sl_img_url = base_url('uploads/frontend/slider/' . $sl_img);
										}
								?>
								<tr>
									<td><img src="<?php echo $sl_img_url; ?>" style="width: 70px; height: 40px; object-fit: cover; border-radius: 4px;"></td>
									<td><strong><?php echo html_escape($sl['title']); ?></strong></td>
									<td><?php echo !empty($sl_ele['button_text1']) ? html_escape($sl_ele['button_text1']) : '<span class="text-muted">N/A</span>'; ?></td>
									<td>
										<a href="<?php echo base_url('frontend/section/home_slider_delete/' . $sl['id']); ?>" class="btn btn-danger btn-xs" onclick="return confirm('আপনি কি নিশ্চিত এই স্লাইডারটি মুছে ফেলতে চান?');">
											<i class="fas fa-trash-alt"></i> <?php echo translate('delete'); ?>
										</a>
									</td>
								</tr>
								<?php endforeach; else: ?>
								<tr><td colspan="4" class="text-center text-muted py-3">বর্তমানে কোনো স্লাইডার নেই। নতুন স্লাইডার যুক্ত করুন।</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>

					<!-- 3. NOTICE BOARD TAB -->
					<div class="tab-pane" id="notice">
						<div class="alert alert-info">
							<i class="fas fa-info-circle"></i> হোমপেজে ব্যানার স্লাইডারের ডানপাশে নোটিশ বোর্ডটি প্রদর্শিত হয়। এখান থেকে এর শিরোনাম ও সংখ্যা নিয়ন্ত্রণ করুন এবং নিচের বাটন দিয়ে সকল নোটিশ পরিচালনা করুন।
						</div>
						<?php echo form_open('frontend/section/home_notice' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (বাংলা) <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="notice_title" value="<?php echo set_value('notice_title', !empty($notice_sec['title']) ? $notice_sec['title'] : 'নোটিশ বোর্ড'); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="notice_title_en" value="<?php echo set_value('notice_title_en', isset($notice_sec['title_en']) ? $notice_sec['title_en'] : 'Notice Board'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">হোমপেজে সর্বোচ্চ প্রদর্শিত নোটিশ সংখ্যা</label>
								<div class="col-md-3">
									<input type="number" class="form-control" name="notice_limit" min="1" max="15" value="<?php echo isset($notice_ele['limit']) ? intval($notice_ele['limit']) : 5; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Show Website</label>
								<div class="col-md-4">
									<div class="material-switch mt-xs">
										<input id="isvisiblenotice" name="isvisible" type="checkbox" <?php echo (!isset($notice_sec['active']) || $notice_sec['active'] == 1) ? 'checked' : ''; ?> />
										<label for="isvisiblenotice" class="label-primary"></label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>

						<hr>
						<div class="d-flex justify-content-between align-items-center mb-md" style="display: flex; justify-content: space-between; align-items: center;">
							<h4 class="mb-0"><i class="fas fa-bullhorn text-primary"></i> সর্বশেষ প্রকাশিত নোটিশসমূহ</h4>
							<a href="<?php echo base_url('frontend/news'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> নোটিশ যুক্ত ও পরিচালনা করুন (Manage All Notices)</a>
						</div>
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th width="60">SI</th>
									<th>শিরোনাম</th>
									<th width="140">তারিখ</th>
									<th width="100">ওয়েবসাইটে প্রদর্শন</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($notices_list)): foreach ($notices_list as $n_idx => $nl): ?>
								<tr>
									<td><?php echo $n_idx + 1; ?></td>
									<td><strong><?php echo html_escape($nl['title']); ?></strong></td>
									<td><?php echo date('d M, Y', strtotime($nl['date'])); ?></td>
									<td><span class="label label-<?php echo ($nl['show_web'] == 1) ? 'success' : 'default'; ?>"><?php echo ($nl['show_web'] == 1) ? 'সক্রিয়' : 'লুকানো'; ?></span></td>
								</tr>
								<?php endforeach; else: ?>
								<tr><td colspan="4" class="text-center text-muted">কোনো নোটিশ পাওয়া যায়নি।</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>

					<!-- 4. LEADERSHIP MESSAGES TAB -->
					<div class="tab-pane" id="leadership">
						<div class="alert alert-info">
							<i class="fas fa-info-circle"></i> পরিচালনা পরিষদ ও প্রতিষ্ঠাতা সদস্যদের বাণী সম্বলিত সেকশন। এখান থেকে শিরোনাম ও সক্রিয় অবস্থা নিয়ন্ত্রণ করুন এবং নিচের বাটন থেকে সদস্যদের তথ্য, বার্তা ও ছবি এডিট করুন।
						</div>
						<?php echo form_open('frontend/section/home_leadership' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (বাংলা) <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="lead_title" value="<?php echo set_value('lead_title', !empty($leadership_sec['title']) ? $leadership_sec['title'] : 'নেতৃত্বের বার্তা'); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="lead_title_en" value="<?php echo set_value('lead_title_en', isset($leadership_sec['title_en']) ? $leadership_sec['title_en'] : 'Messages from Leadership'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (বাংলা)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle" value="<?php echo set_value('subtitle', isset($leadership_sec['subtitle']) ? $leadership_sec['subtitle'] : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle_en" value="<?php echo set_value('subtitle_en', isset($leadership_sec['subtitle_en']) ? $leadership_sec['subtitle_en'] : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Show Website</label>
								<div class="col-md-4">
									<div class="material-switch mt-xs">
										<input id="isvisiblelead" name="isvisible" type="checkbox" <?php echo (!isset($leadership_sec['active']) || $leadership_sec['active'] == 1) ? 'checked' : ''; ?> />
										<label for="isvisiblelead" class="label-primary"></label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>

						<hr>
						<div class="d-flex justify-content-between align-items-center mb-md" style="display: flex; justify-content: space-between; align-items: center;">
							<h4 class="mb-0"><i class="fas fa-user-tie text-primary"></i> পরিচালনা পরিষদ ও প্রতিষ্ঠাতাবৃন্দ</h4>
							<a href="<?php echo base_url('frontend/founder'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> প্রতিষ্ঠাতা ও পরিচালনা পরিষদ পরিচালনা করুন (Manage Founders)</a>
						</div>
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th width="70">ছবি</th>
									<th>নাম (বাংলা / English)</th>
									<th>পদবী / খেতাব</th>
									<th width="80">ক্রম (Order)</th>
									<th width="100">স্ট্যাটাস</th>
									<th width="80">অ্যাকশন</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($leaders_list)): foreach ($leaders_list as $ldr): 
									$ldr_img = !empty($ldr['image']) && file_exists(FCPATH . 'uploads/frontend/founder/' . $ldr['image']) ? base_url('uploads/frontend/founder/' . $ldr['image']) : base_url('uploads/app_image/default.png');
								?>
								<tr>
									<td><img src="<?php echo $ldr_img; ?>" style="width: 45px; height: 50px; object-fit: cover; border-radius: 4px;"></td>
									<td><strong><?php echo html_escape($ldr['name']); ?></strong><?php if (!empty($ldr['name_en'])) echo ' <span class="text-muted">(' . html_escape($ldr['name_en']) . ')</span>'; ?></td>
									<td><span class="label label-primary"><?php echo html_escape($ldr['designation']); ?></span></td>
									<td><?php echo $ldr['position']; ?></td>
									<td><span class="label label-<?php echo ($ldr['active'] == 1) ? 'success' : 'default'; ?>"><?php echo ($ldr['active'] == 1) ? 'সক্রিয়' : 'লুকানো'; ?></span></td>
									<td><a href="<?php echo base_url('frontend/founder/edit/' . $ldr['id']); ?>" class="btn btn-default btn-xs"><i class="fas fa-pen-nib"></i> এডিট</a></td>
								</tr>
								<?php endforeach; else: ?>
								<tr><td colspan="6" class="text-center text-muted">কোনো সদস্য পাওয়া যায়নি।</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>

					<!-- 5. OUR TEACHERS TAB -->
					<div class="tab-pane" id="teachers">
						<div class="alert alert-info">
							<i class="fas fa-info-circle"></i> হোমপেজে প্রদর্শিত "শিক্ষকমণ্ডলী" সেকশনের শিরোনাম ও সক্রিয় অবস্থা নিয়ন্ত্রণ করুন। শিক্ষকদের বিস্তারিত প্রোফাইল, ছবি ও সিরিয়াল নিচের বাটন থেকে সাজান।
						</div>
						<?php echo form_open('frontend/section/home_teachers' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (বাংলা) <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="tea_title" value="<?php echo set_value('tea_title', !empty($teachers['title']) ? $teachers['title'] : 'শিক্ষকমণ্ডলী'); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="tea_title_en" value="<?php echo set_value('tea_title_en', isset($teachers['title_en']) ? $teachers['title_en'] : 'Our Teachers'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (বাংলা)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle" value="<?php echo set_value('subtitle', isset($teachers['subtitle']) ? $teachers['subtitle'] : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle_en" value="<?php echo set_value('subtitle_en', isset($teachers['subtitle_en']) ? $teachers['subtitle_en'] : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Show Website</label>
								<div class="col-md-4">
									<div class="material-switch mt-xs">
										<input id="isvisibletea" name="isvisible" type="checkbox" <?php echo (!isset($teachers['active']) || $teachers['active'] == 1) ? 'checked' : ''; ?> />
										<label for="isvisibletea" class="label-primary"></label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>

						<hr>
						<div class="d-flex justify-content-between align-items-center mb-md" style="display: flex; justify-content: space-between; align-items: center;">
							<h4 class="mb-0"><i class="fas fa-chalkboard-teacher text-primary"></i> শিক্ষকবৃন্দ তালিকা</h4>
							<a href="<?php echo base_url('frontend/teacher'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> শিক্ষকবৃন্দ তালিকা, ক্রম ও ছবি পরিচালনা করুন (Manage Teachers)</a>
						</div>
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th width="65">ছবি</th>
									<th>নাম</th>
									<th>পদবী</th>
									<th>বিষয় / বিভাগ</th>
									<th width="80">ক্রম (Order)</th>
									<th width="110">হোমপেজে প্রদর্শন</th>
									<th width="80">অ্যাকশন</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($teachers_list)): foreach ($teachers_list as $t_item): 
									$t_img = !empty($t_item['image']) && file_exists(FCPATH . 'uploads/frontend/teacher/' . $t_item['image']) ? base_url('uploads/frontend/teacher/' . $t_item['image']) : base_url('uploads/app_image/default.png');
								?>
								<tr>
									<td><img src="<?php echo $t_img; ?>" style="width: 45px; height: 50px; object-fit: cover; border-radius: 4px;"></td>
									<td><strong><?php echo html_escape($t_item['name']); ?></strong></td>
									<td><?php echo html_escape($t_item['designation']); ?></td>
									<td><?php echo html_escape($t_item['subject']); ?></td>
									<td><?php echo $t_item['position']; ?></td>
									<td><span class="label label-<?php echo ($t_item['show_on_homepage'] == 1) ? 'success' : 'default'; ?>"><?php echo ($t_item['show_on_homepage'] == 1) ? 'হোমপেজে সক্রিয়' : 'লুকানো'; ?></span></td>
									<td><a href="<?php echo base_url('frontend/teacher/edit/' . $t_item['id']); ?>" class="btn btn-default btn-xs"><i class="fas fa-pen-nib"></i> এডিট</a></td>
								</tr>
								<?php endforeach; else: ?>
								<tr><td colspan="7" class="text-center text-muted">কোনো শিক্ষক পাওয়া যায়নি।</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>

					<!-- 6. LATEST EVENTS TAB -->
					<div class="tab-pane" id="events">
						<div class="alert alert-info">
							<i class="fas fa-info-circle"></i> প্রতিষ্ঠানের আসন্ন ও গুরুত্বপূর্ণ অনুষ্ঠান বা পরীক্ষার তথ্য হোমপেজে "সর্বশেষ ইভেন্ট" সেকশনে প্রদর্শিত হয়।
						</div>
						<?php echo form_open('frontend/section/home_events' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (বাংলা) <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="events_title" value="<?php echo set_value('events_title', !empty($events_sec['title']) ? $events_sec['title'] : 'সর্বশেষ ইভেন্ট'); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="events_title_en" value="<?php echo set_value('events_title_en', isset($events_sec['title_en']) ? $events_sec['title_en'] : 'Latest Events'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (বাংলা)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle" value="<?php echo set_value('subtitle', isset($events_sec['subtitle']) ? $events_sec['subtitle'] : 'আমাদের যাবতীয় আসন্ন অনুষ্ঠান, পরীক্ষা ও কার্যক্রম সম্পর্কে অবগত থাকুন।'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle_en" value="<?php echo set_value('subtitle_en', isset($events_sec['subtitle_en']) ? $events_sec['subtitle_en'] : 'Stay informed about our upcoming programs, examinations and events.'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Show Website</label>
								<div class="col-md-4">
									<div class="material-switch mt-xs">
										<input id="isvisibleevents" name="isvisible" type="checkbox" <?php echo (!isset($events_sec['active']) || $events_sec['active'] == 1) ? 'checked' : ''; ?> />
										<label for="isvisibleevents" class="label-primary"></label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>

						<hr>
						<div class="d-flex justify-content-between align-items-center mb-md" style="display: flex; justify-content: space-between; align-items: center;">
							<h4 class="mb-0"><i class="fas fa-calendar-alt text-primary"></i> সাম্প্রতিক ইভেন্ট তালিকা</h4>
							<a href="<?php echo base_url('event'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> ইভেন্ট যুক্ত ও পরিচালনা করুন (Manage Events)</a>
						</div>
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>ইভেন্টের শিরোনাম</th>
									<th>তারিখ</th>
									<th>ধরন / Type</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($events_list)): foreach ($events_list as $ev): ?>
								<tr>
									<td><strong><?php echo html_escape($ev['title']); ?></strong></td>
									<td><?php echo date('d M, Y', strtotime($ev['start_date'])); ?></td>
									<td><span class="label label-info"><?php echo is_numeric($ev['type']) ? get_type_name_by_id('event_types', $ev['type']) : $ev['type']; ?></span></td>
								</tr>
								<?php endforeach; else: ?>
								<tr><td colspan="3" class="text-center text-muted">কোনো ইভেন্ট পাওয়া যায়নি।</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>

					<!-- 7. VIDEO GALLERY TAB -->
					<div class="tab-pane" id="video_gallery">
						<div class="alert alert-info">
							<i class="fas fa-info-circle"></i> হোমপেজে প্রদর্শিত ভিডিও গ্যালারির শিরোনাম ও স্ট্যাটাস এখান থেকে নিয়ন্ত্রণ করুন।
						</div>
						<?php echo form_open('frontend/section/home_video_gallery' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (বাংলা) <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="vid_title" value="<?php echo set_value('vid_title', !empty($video_sec['title']) ? $video_sec['title'] : 'ভিডিও গ্যালারি'); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="vid_title_en" value="<?php echo set_value('vid_title_en', isset($video_sec['title_en']) ? $video_sec['title_en'] : 'Video Gallery'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (বাংলা)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle" value="<?php echo set_value('subtitle', isset($video_sec['subtitle']) ? $video_sec['subtitle'] : 'আমাদের শিক্ষা কার্যক্রম ও আয়োজনের ভিডিও চিত্র'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle_en" value="<?php echo set_value('subtitle_en', isset($video_sec['subtitle_en']) ? $video_sec['subtitle_en'] : 'Browse video gallery'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Show Website</label>
								<div class="col-md-4">
									<div class="material-switch mt-xs">
										<input id="isvisiblevideo" name="isvisible" type="checkbox" <?php echo (!isset($video_sec['active']) || $video_sec['active'] == 1) ? 'checked' : ''; ?> />
										<label for="isvisiblevideo" class="label-primary"></label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>

						<hr>
						<div class="d-flex justify-content-between align-items-center mb-md" style="display: flex; justify-content: space-between; align-items: center;">
							<h4 class="mb-0"><i class="fas fa-video text-primary"></i> বর্তমান ভিডিও তালিকা</h4>
							<a href="<?php echo base_url('frontend/gallery'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> নতুন ভিডিও ও ছবি যুক্ত করুন (Manage Gallery)</a>
						</div>
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th width="80">থাম্বনেইল</th>
									<th>ভিডিও শিরোনাম</th>
									<th>ওয়েবসাইট লিংক</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($videos_list)): foreach ($videos_list as $vl): 
									$v_thumb = !empty($vl['thumb_image']) && file_exists(FCPATH . 'uploads/frontend/gallery/' . $vl['thumb_image']) ? base_url('uploads/frontend/gallery/' . $vl['thumb_image']) : base_url('uploads/app_image/default.png');
									$v_ele = !empty($vl['elements']) ? json_decode($vl['elements'], true) : [];
								?>
								<tr>
									<td><img src="<?php echo $v_thumb; ?>" style="width: 70px; height: 45px; object-fit: cover; border-radius: 4px;"></td>
									<td><strong><?php echo html_escape($vl['title']); ?></strong></td>
									<td><a href="<?php echo !empty($v_ele['video_url']) ? $v_ele['video_url'] : '#'; ?>" target="_blank" class="text-primary"><i class="fas fa-play-circle"></i> ভিডিও দেখুন</a></td>
								</tr>
								<?php endforeach; else: ?>
								<tr><td colspan="3" class="text-center text-muted">কোনো ভিডিও পাওয়া যায়নি।</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>

					<!-- 8. PHOTO GALLERY TAB -->
					<div class="tab-pane" id="gallery">
						<div class="alert alert-info">
							<i class="fas fa-info-circle"></i> হোমপেজে প্রদর্শিত "ফটো গ্যালারি" সেকশনের শিরোনাম ও সক্রিয় অবস্থা এখান থেকে পরিবর্তন করুন।
						</div>
						<?php echo form_open('frontend/section/home_gallery' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (বাংলা) <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="gal_title" value="<?php echo set_value('gal_title', !empty($gallery_sec['title']) ? $gallery_sec['title'] : 'ফটো গ্যালারি'); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="gal_title_en" value="<?php echo set_value('gal_title_en', isset($gallery_sec['title_en']) ? $gallery_sec['title_en'] : 'Photo Gallery'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (বাংলা)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle" value="<?php echo set_value('subtitle', isset($gallery_sec['subtitle']) ? $gallery_sec['subtitle'] : 'প্রতিষ্ঠানের বিভিন্ন স্মরণীয় মুহূর্তের ছবি'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle_en" value="<?php echo set_value('subtitle_en', isset($gallery_sec['subtitle_en']) ? $gallery_sec['subtitle_en'] : 'See moments captured'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Show Website</label>
								<div class="col-md-4">
									<div class="material-switch mt-xs">
										<input id="isvisiblegal" name="isvisible" type="checkbox" <?php echo (!isset($gallery_sec['active']) || $gallery_sec['active'] == 1) ? 'checked' : ''; ?> />
										<label for="isvisiblegal" class="label-primary"></label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>

						<hr>
						<div class="d-flex justify-content-between align-items-center mb-md" style="display: flex; justify-content: space-between; align-items: center;">
							<h4 class="mb-0"><i class="fas fa-camera text-primary"></i> বর্তমান ফটো তালিকা</h4>
							<a href="<?php echo base_url('frontend/gallery'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> ছবি যুক্ত ও অ্যালবাম পরিচালনা করুন (Manage Photos)</a>
						</div>
						<div class="row">
							<?php if (!empty($photos_list)): foreach ($photos_list as $pl): 
								$p_thumb = !empty($pl['thumb_image']) && file_exists(FCPATH . 'uploads/frontend/gallery/' . $pl['thumb_image']) ? base_url('uploads/frontend/gallery/' . $pl['thumb_image']) : base_url('uploads/app_image/default.png');
							?>
							<div class="col-xs-6 col-sm-3 col-md-2 mb-md text-center">
								<img src="<?php echo $p_thumb; ?>" style="width: 100%; height: 90px; object-fit: cover; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
								<div class="small text-truncate mt-xs" title="<?php echo html_escape($pl['title']); ?>"><strong><?php echo html_escape($pl['title']); ?></strong></div>
							</div>
							<?php endforeach; else: ?>
							<div class="col-md-12 text-center text-muted py-3">কোনো ছবি পাওয়া যায়নি।</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- 9. ACHIEVEMENTS & STATISTICS TAB -->
					<div class="tab-pane" id="statistics">
						<div class="alert alert-info">
							<i class="fas fa-info-circle"></i> হোমপেজের "আমাদের অর্জন ও পরিসংখ্যান" সেকশনে লাইভ ইআরপি কাউন্টার (মোট শিক্ষার্থী, শ্রেণীকক্ষ, শাখা, শিক্ষক ও পরিদর্শনকারী সংখ্যা) স্বয়ংক্রিয়ভাবে প্রদর্শিত হয়। এখান থেকে সেকশন শিরোনাম ও সক্রিয় অবস্থা নির্ধারণ করুন।
						</div>
						<?php echo form_open('frontend/section/home_statistics' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (বাংলা) <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="sta_title" value="<?php echo set_value('sta_title', !empty($statistics['title']) ? $statistics['title'] : 'আমাদের অর্জন ও পরিসংখ্যান'); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('title'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="sta_title_en" value="<?php echo set_value('sta_title_en', isset($statistics['title_en']) ? $statistics['title_en'] : 'Our Achievements & Statistics'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (বাংলা)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle" value="<?php echo set_value('subtitle', isset($statistics['subtitle']) ? $statistics['subtitle'] : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('subtitle'); ?> (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle_en" value="<?php echo set_value('subtitle_en', isset($statistics['subtitle_en']) ? $statistics['subtitle_en'] : ''); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Show Website</label>
								<div class="col-md-4">
									<div class="material-switch mt-xs">
										<input id="isvisiblesta" name="isvisible" type="checkbox" <?php echo (!isset($statistics['active']) || $statistics['active'] == 1) ? 'checked' : ''; ?> />
										<label for="isvisiblesta" class="label-primary"></label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>

						<hr>
						<h4><i class="fas fa-chart-pie text-primary"></i> লাইভ ইআরপি পরিসংখ্যান প্রিভিউ</h4>
						<div class="row mt-md">
							<?php 
							$p_stud = $this->home_model->getStatisticsCounter('student', $branch_id);
							$p_cls  = $this->home_model->getStatisticsCounter('class', $branch_id);
							$p_sec  = $this->home_model->getStatisticsCounter('section', $branch_id);
							$p_tea  = $this->home_model->getStatisticsCounter('teacher', $branch_id);
							?>
							<div class="col-sm-3 col-xs-6 text-center mb-sm">
								<div class="well well-sm">
									<div class="text-primary font-weight-bold" style="font-size: 20px; font-weight: bold;"><?php echo $p_stud; ?></div>
									<small>মোট শিক্ষার্থী</small>
								</div>
							</div>
							<div class="col-sm-3 col-xs-6 text-center mb-sm">
								<div class="well well-sm">
									<div class="text-success font-weight-bold" style="font-size: 20px; font-weight: bold;"><?php echo $p_cls; ?></div>
									<small>মোট শ্রেণি</small>
								</div>
							</div>
							<div class="col-sm-3 col-xs-6 text-center mb-sm">
								<div class="well well-sm">
									<div class="text-warning font-weight-bold" style="font-size: 20px; font-weight: bold;"><?php echo $p_sec; ?></div>
									<small>মোট শাখা</small>
								</div>
							</div>
							<div class="col-sm-3 col-xs-6 text-center mb-sm">
								<div class="well well-sm">
									<div class="text-danger font-weight-bold" style="font-size: 20px; font-weight: bold;"><?php echo $p_tea; ?></div>
									<small>মোট শিক্ষক</small>
								</div>
							</div>
						</div>
					</div>

					<!-- 10. HERO & ACTION BUTTONS TAB -->
					<div class="tab-pane" id="hero">
						<div class="alert alert-info">
							<i class="fas fa-info-circle"></i> হোমপেজের শীর্ষে বড় ব্যানার শিরোনাম, স্লোগান এবং ৪টি প্রধান অ্যাকশন বাটনের টেক্সট ও লিংক এখান থেকে পরিচালনা করুন।
						</div>
						<?php echo form_open('frontend/section/home_hero' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label">প্রতিষ্ঠানের নাম / ব্যানার শিরোনাম (বাংলা) <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="hero_title" value="<?php echo set_value('hero_title', !empty($hero_sec['title']) ? $hero_sec['title'] : 'চর হাসান ইসলামিয়া দাখিল মাদ্রাসা'); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">প্রতিষ্ঠানের নাম / ব্যানার শিরোনাম (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="hero_title_en" value="<?php echo set_value('hero_title_en', isset($hero_sec['title_en']) ? $hero_sec['title_en'] : 'Char Hasan Islamia Dakhil Madrasah'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">ঠিকানা / সাবটাইটেল (বাংলা)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle" value="<?php echo set_value('subtitle', isset($hero_sec['subtitle']) ? $hero_sec['subtitle'] : 'গ্রাম:চর হাসান,পো:-পশ্চিম চর জব্বর, সুবর্ণচর,নোয়াখালী।'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">ঠিকানা / সাবটাইটেল (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="subtitle_en" value="<?php echo set_value('subtitle_en', isset($hero_sec['subtitle_en']) ? $hero_sec['subtitle_en'] : 'Char Hasan, Subarnachar, Noakhali'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">স্লোগান / ট্যাগলাইন (বাংলা)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="tagline" value="<?php echo set_value('tagline', isset($hero_sec['description']) ? $hero_sec['description'] : 'একটি অত্যাধুনিক দ্বীনি শিক্ষা প্রতিষ্ঠান'); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">স্লোগান / ট্যাগলাইন (English)</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="tagline_en" value="<?php echo set_value('tagline_en', isset($hero_sec['description_en']) ? $hero_sec['description_en'] : 'A Modern Islamic Educational Institution'); ?>" />
								</div>
							</div>

							<div class="headers-line mt-md"><i class="fas fa-link"></i> হিরো ব্যানার বাটনসমূহ (Hero Buttons)</div>
							<div class="form-group">
								<label class="col-md-3 control-label">বাটন ১ (ভর্তি আবেদন)</label>
								<div class="col-md-3">
									<input type="text" class="form-control" name="hero_btn_1_text" placeholder="বাটন টেক্সট..." value="<?php echo isset($hero_ele['hero_btn_1_text']) ? $hero_ele['hero_btn_1_text'] : 'ভর্তির জন্য আবেদন করুন'; ?>" />
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" name="hero_btn_1_url" placeholder="লিংক..." value="<?php echo isset($hero_ele['hero_btn_1_url']) ? $hero_ele['hero_btn_1_url'] : 'admission'; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">বাটন ২ (আমাদের ইতিহাস)</label>
								<div class="col-md-3">
									<input type="text" class="form-control" name="hero_btn_2_text" placeholder="বাটন টেক্সট..." value="<?php echo isset($hero_ele['hero_btn_2_text']) ? $hero_ele['hero_btn_2_text'] : 'আমাদের ইতিহাস'; ?>" />
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" name="hero_btn_2_url" placeholder="লিংক..." value="<?php echo isset($hero_ele['hero_btn_2_url']) ? $hero_ele['hero_btn_2_url'] : 'about'; ?>" />
								</div>
							</div>

							<div class="headers-line mt-md"><i class="fas fa-th"></i> ৪টি প্রধান হাইলাইটেড বাটন (Quick Action Buttons)</div>
							<div class="form-group">
								<label class="col-md-3 control-label">বাটন ১ (নীল - নোটিশ)</label>
								<div class="col-md-3">
									<input type="text" class="form-control" name="quick_btn_1_text" value="<?php echo isset($hero_ele['quick_btn_1_text']) ? $hero_ele['quick_btn_1_text'] : 'সর্বশেষ নোটিশ'; ?>" />
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" name="quick_btn_1_url" value="<?php echo isset($hero_ele['quick_btn_1_url']) ? $hero_ele['quick_btn_1_url'] : 'notice'; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">বাটন ২ (সবুজ - ফলাফল)</label>
								<div class="col-md-3">
									<input type="text" class="form-control" name="quick_btn_2_text" value="<?php echo isset($hero_ele['quick_btn_2_text']) ? $hero_ele['quick_btn_2_text'] : 'পরীক্ষার ফলাফল'; ?>" />
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" name="quick_btn_2_url" value="<?php echo isset($hero_ele['quick_btn_2_url']) ? $hero_ele['quick_btn_2_url'] : 'exam_results'; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">বাটন ৩ (কমলা - অনলাইন আবেদন)</label>
								<div class="col-md-3">
									<input type="text" class="form-control" name="quick_btn_3_text" value="<?php echo isset($hero_ele['quick_btn_3_text']) ? $hero_ele['quick_btn_3_text'] : 'অনলাইন আবেদন'; ?>" />
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" name="quick_btn_3_url" value="<?php echo isset($hero_ele['quick_btn_3_url']) ? $hero_ele['quick_btn_3_url'] : 'admission'; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">বাটন ৪ (লাল - ক্যারিয়ার)</label>
								<div class="col-md-3">
									<input type="text" class="form-control" name="quick_btn_4_text" value="<?php echo isset($hero_ele['quick_btn_4_text']) ? $hero_ele['quick_btn_4_text'] : ' ক্যারিয়ার পোর্টাল'; ?>" />
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" name="quick_btn_4_url" value="<?php echo isset($hero_ele['quick_btn_4_url']) ? $hero_ele['quick_btn_4_url'] : 'career'; ?>" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label">Show Website</label>
								<div class="col-md-4">
									<div class="material-switch mt-xs">
										<input id="isvisiblehero" name="isvisible" type="checkbox" <?php echo (!isset($hero_sec['active']) || $hero_sec['active'] == 1) ? 'checked' : ''; ?> />
										<label for="isvisiblehero" class="label-primary"></label>
									</div>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>

					<!-- 11. OPTIONS & SEO TAB -->
					<div class="tab-pane" id="options">
						<?php echo form_open('frontend/section/home_options' . get_request_url(), array('class' => 'form-horizontal frm-submit-data')); ?>
							<input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?php echo translate('page_title'); ?> <span class="required">*</span></label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="page_title" value="<?php echo set_value('page_title', isset($home_seo['page_title']) ? $home_seo['page_title'] : ''); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Meta Keywords</label>
								<div class="col-md-7">
									<input type="text" class="form-control" name="meta_keyword" value="<?php echo set_value('meta_keyword', isset($home_seo['meta_keyword']) ? $home_seo['meta_keyword'] : ''); ?>" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Meta Description</label>
								<div class="col-md-7">
									<textarea class="form-control" name="meta_description" rows="4"><?php echo set_value('meta_description', isset($home_seo['meta_description']) ? $home_seo['meta_description'] : ''); ?></textarea>
									<span class="error"></span>
								</div>
							</div>
							<footer class="panel-footer mt-lg">
								<div class="row">
									<div class="col-md-2 col-md-offset-3">
										<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close(); ?>
					</div>

				</div>
			</div>
		</section>
	</div>
</div>
<?php endif; ?>

<script type="text/javascript">
    $(".complex-colorpicker").asColorPicker({
		readonly: false,
		lang: 'en',
		mode: 'complex',
		color: {
			reduceAlpha: true,
			zeroAlphaAsTransparent: false
		},
    });

	// Auto switch tab if URL has hash (e.g. #slider, #notice, #teachers)
	if (window.location.hash) {
		$('.nav-tabs a[href="' + window.location.hash + '"]').tab('show');
	}

	$(document).ready(function() {
		var drEvent = $('.dropify').dropify();
		drEvent.on('dropify.afterClear', function(event, element){
			$('input[name="old_photo"]').val('');
			$('#remove_welcome_photo').prop('checked', true);
		});
	});
</script>
