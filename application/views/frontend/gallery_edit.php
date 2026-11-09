<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li>
				<a href="<?php echo base_url('frontend/gallery'); ?>"><i class="fas fa-list-ul"></i> <?php echo translate('gallery') . " " . translate('list'); ?></a>
			</li>
			<li class="active">
				<a href="#edit" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('edit') . " " . translate('gallery'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="edit">
			    <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
					<input type="hidden" name="gallery_id" value="<?php echo $gallery['id']; ?>">
					<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, $gallery['branch_id'], "class='form-control' data-plugin-selectTwo
								data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"></span>
						</div>
					</div>
					<?php endif; ?>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('type'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<select class="form-control" name="file_type" id="gallery_file_type" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
								<option value="image" <?php echo ($gallery['file_type'] != 'video' ? 'selected' : ''); ?>>📷 Photo Album (ফটো অ্যালবাম)</option>
								<option value="video" <?php echo ($gallery['file_type'] == 'video' ? 'selected' : ''); ?>>🎥 Video Gallery / Video (ভিডিও গ্যালারি)</option>
							</select>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group video_url_field" style="<?php echo ($gallery['file_type'] == 'video' ? '' : 'display: none;'); ?>">
						<label class="col-md-3 control-label">YouTube Video URL <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="video_url" id="video_url_input" placeholder="e.g. https://www.youtube.com/watch?v=xxxx-xx or https://youtu.be/xxxx" value="<?php echo set_value('video_url', $gallery['video_url'] ?? ''); ?>" />
							<small class="text-muted">Enter standard YouTube video link. Thumbnail will be auto-fetched if no custom image is uploaded.</small>
							<span class="error"><?php echo form_error('video_url'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('gallery') . " " . translate('title'); ?> (বাংলা) <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="gallery_title" value="<?php echo set_value('gallery_title', $gallery['title']); ?>" />
							<span class="error"><?php echo form_error('gallery_title'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('gallery') . " " . translate('title'); ?> (English)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="gallery_title_en" value="<?php echo set_value('gallery_title_en', $gallery['title_en'] ?? ''); ?>" placeholder="e.g. Annual Sports Competition" />
							<span class="error"><?php echo form_error('gallery_title_en'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('description'); ?> (বাংলা) <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" id="description" name="description" placeholder="" rows="3"><?php echo set_value('description', $gallery['description']); ?></textarea>
							<span class="error"><?php echo form_error('description'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('description'); ?> (English)</label>
						<div class="col-md-6">
							<textarea class="form-control" id="description_en" name="description_en" placeholder="Short description in English" rows="2"><?php echo set_value('description_en', $gallery['description_en'] ?? ''); ?></textarea>
							<span class="error"><?php echo form_error('description_en'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('category'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayCategory = $this->app_lib->getSelectByBranch('front_cms_gallery_category', $gallery['branch_id']);
								echo form_dropdown("category_id", $arrayCategory, set_value('category_id', $gallery['category_id']), "class='form-control' id='category_id' data-minimum-results-for-search='Infinity'
								data-plugin-selectTwo data-width='100%'");
							?>
							<span class="error"><?php echo form_error('type'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('thumb_image'); ?></label>
						<div class="col-md-4">
							<input type="hidden" name="old_photo" value="<?php echo $gallery['thumb_image']; ?>">
							<?php
								$is_vid = (!empty($gallery['file_type']) && $gallery['file_type'] == 'video');
								$thumb_preview = $is_vid ? $this->gallery_model->get_video_thumbnail($gallery['video_url'] ?? '', $gallery['thumb_image']) : $this->gallery_model->get_image_url($gallery['thumb_image']);
							?>
							<input type="file" name="thumb_image" class="dropify" data-height="150" data-default-file="<?php echo $thumb_preview; ?>" />
							<span class="error"><?php echo form_error('thumb_image'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('show_website')?></label>
						<div class="col-md-6">
							<div class="material-switch ml-xs">
								<input id="switch_1" name="show_website" type="checkbox" <?php echo $gallery['show_web'] == 1 ? 'checked' : ""; ?> />
								<label for="switch_1" class="label-primary"></label>
							</div>
						</div>
					</div>

					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-edit"></i> <?php echo translate('update'); ?>
								</button>
							</div>
						</div>	
					</footer>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
		$('#gallery_file_type').on('change', function() {
			if ($(this).val() === 'video') {
				$('.video_url_field').slideDown(200);
			} else {
				$('.video_url_field').slideUp(200);
			}
		});
	});
</script>
