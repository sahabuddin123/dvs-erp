<div class="row">
	<div class="col-md-3">
		<ul class="nav nav-pills nav-stacked">
			<li>
				<a href="<?php echo base_url('saas/settings_general'); ?>"><i class="fas fa-cog"></i> <?php echo translate('general_settings'); ?></a>
			</li>
			<li class="active">
				<a href="<?php echo base_url('saas/settings_website'); ?>"><i class="fas fa-globe"></i> <?php echo translate('website_settings'); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url('saas/settings_payment'); ?>"><i class="fas fa-dollar-sign"></i> <?php echo translate('payment_settings'); ?></a>
			</li>
			<li>
				<a href="<?php echo base_url('saas/settings_email'); ?>"><i class="fas fa-envelope"></i> <?php echo translate('email_settings'); ?></a>
			</li>
		</ul>
	</div>

	<div class="col-md-9">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-sliders-h"></i> <?php echo translate('website_settings'); ?></h4>
			</header>
			<?php echo form_open('saas/settings_website', array('class' => 'form-horizontal form-bordered validate')); ?>
				<div class="panel-body">
					<!-- Theme Options -->
					<div class="headers-line mt-none">
						<i class="fas fa-paint-brush"></i> Theme Options
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Primary Color <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="primary_color" value="<?php echo html_escape($settings['primary_color']); ?>" required />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Heading Text Color <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="heading_text_color" value="<?php echo html_escape($settings['heading_text_color']); ?>" required />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Text Color <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="text_color" value="<?php echo html_escape($settings['text_color']); ?>" required />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Menu BG Color <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="menu_bg_color" value="<?php echo html_escape($settings['menu_bg_color']); ?>" required />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Menu Text Color <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="menu_text_color" value="<?php echo html_escape($settings['menu_text_color']); ?>" required />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Footer BG Color <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="footer_bg_color" value="<?php echo html_escape($settings['footer_bg_color']); ?>" required />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Footer Text Color <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="footer_text_color" value="<?php echo html_escape($settings['footer_text_color']); ?>" required />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Footer Copyright BG Color <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="copyright_bg_color" value="<?php echo html_escape($settings['copyright_bg_color']); ?>" required />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Footer Copyright Text Color <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="copyright_text_color" value="<?php echo html_escape($settings['copyright_text_color']); ?>" required />
						</div>
					</div>

					<!-- Social Links -->
					<div class="headers-line">
						<i class="fas fa-share-alt"></i> Social Links
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Facebook Url</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="button_url_1" value="<?php echo html_escape($settings['button_url_1']); ?>" placeholder="https://www.facebook.com/username" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Twitter Url</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="button_url_2" value="<?php echo html_escape($settings['button_url_2']); ?>" placeholder="https://www.twitter.com/username" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Linkedin Url</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="receive_contact_email" value="<?php echo html_escape($settings['receive_contact_email']); ?>" placeholder="https://www.linkedin.com/username" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Instagram Url</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="contact_title" value="<?php echo html_escape($settings['contact_title']); ?>" placeholder="https://www.instagram.com/username" />
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Youtube Url</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="contact_button" value="<?php echo html_escape($settings['contact_button']); ?>" placeholder="https://www.youtube.com/username" />
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-2 col-md-offset-3">
							<button type="submit" name="submit" value="save" class="btn btn-default btn-block"><i class="fas fa-check"></i> <?php echo translate('save'); ?></button>
						</div>
					</div>
				</footer>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>
