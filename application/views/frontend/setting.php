<div class="row">
	<div class="col-md-12">
<?php if (is_superadmin_loggedin() ): ?>
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><?=translate('select_ground')?></h4>
		</header>
		<?php echo form_open($this->uri->uri_string(), array('id' => 'frmsection', 'class' => 'validate'));?>
		<div class="panel-body">
			<div class="row mb-sm">
				<div class="col-md-offset-3 col-md-6">
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, $branch_id, "class='form-control' id='branch_id' required
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-offset-10 col-md-2">
					<button type="submit" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close();?>
	</section>
<?php endif; if (!empty($branch_id)): ?>
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-globe"></i> <?php echo translate('website') . " " . translate('settings'); ?></h4>
			</header>
            <?php echo form_open_multipart('frontend/setting/save' . get_request_url(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
				<div class="panel-body">
					<div class="form-group mt-md">
						<label class="col-md-3 control-label"><?php echo translate('cms') . " " . translate('title'); ?> (বাংলা) <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="application_title" value="<?php echo set_value('application_title', $setting['application_title']); ?>" />
							<span class="error"><?php echo form_error('application_title'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('cms') . " " . translate('title'); ?> (English)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="application_title_en" value="<?php echo set_value('application_title_en', isset($setting['application_title_en']) ? $setting['application_title_en'] : ''); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Tagline (বাংলা)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="site_tagline" value="<?php echo set_value('site_tagline', isset($setting['site_tagline']) ? $setting['site_tagline'] : ''); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Tagline (English)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="site_tagline_en" value="<?php echo set_value('site_tagline_en', isset($setting['site_tagline_en']) ? $setting['site_tagline_en'] : ''); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('cms') . " " . translate('url_alias'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="url_alias" value="<?php echo set_value('url_alias', $setting['url_alias']); ?>" />
							<span class="error"><?php echo form_error('url_alias'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('cms_frontend'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="radio-custom radio-success radio-inline mb-xs">
								<input type="radio" value="1" name="cms_frontend_status" <?=($setting['cms_active'] == 1 ? 'checked' : '')?> id="sstatus_1">
								<label for="sstatus_1"><?=translate('enabled')?></label>
							</div>

							<div class="radio-custom radio-danger radio-inline">
								<input type="radio" value="0" name="cms_frontend_status" <?=($setting['cms_active'] == 0 ? 'checked' : '')?> id="sstatus_2">
								<label for="sstatus_2"><?=translate('disabled')?></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('online_admission'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="radio-custom radio-success radio-inline mb-xs">
								<input type="radio" value="1" <?=($setting['online_admission'] == 1 ? 'checked' : '')?> name="online_admission" id="astatus_1">
								<label for="astatus_1"><?=translate('enabled')?></label>
							</div>

							<div class="radio-custom radio-danger radio-inline">
								<input type="radio" value="0" <?=($setting['online_admission'] == 0 ? 'checked' : '')?> name="online_admission" id="astatus_2">
								<label for="astatus_2"><?=translate('disabled')?></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('receive_email_to'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="receive_email_to" value="<?php echo set_value('receive_email_to', $setting['receive_contact_email']); ?>" />
							<span class="error"><?php echo form_error('receive_email_to'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('captcha_status'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$array = array(
									"" => translate('select'),
									"disable" => translate('disable'),
									"enable" => translate('enable')
								);
								echo form_dropdown("captcha_status", $array, set_value('captcha_status', $setting['captcha_status']), "class='form-control' data-plugin-selectTwo
								data-width='100%' id='captchaStatus' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"><?php echo form_error('captcha_status'); ?></span>
						</div>
					</div>
					<div class="form-group" id="recaptcha_site_key">
						<label  class="col-md-3 control-label"><?php echo translate('recaptcha_site_key'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="recaptcha_site_key" value="<?php echo set_value('recaptcha_site_key', $setting['recaptcha_site_key']); ?>" />
							<span class="error"><?php echo form_error('recaptcha_site_key'); ?></span>
						</div>
					</div>
					<div class="form-group" id="recaptcha_secret_key">
						<label  class="col-md-3 control-label"><?php echo translate('recaptcha_secret_key'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="recaptcha_secret_key" value="<?php echo set_value('recaptcha_secret_key', $setting['recaptcha_secret_key']); ?>" />
							<span class="error"><?php echo form_error('recaptcha_secret_key'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('working_hours'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" id="working_hours" name="working_hours" placeholder="" rows="3" ><?php echo set_value('working_hours', $setting['working_hours']); ?></textarea>
							<span class="error"><?php echo form_error('working_hours'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('logo'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="file" name="logo" class="dropify dre-render" data-height="90" data-allowed-file-extensions="png jpg jpeg" data-default-file="<?php echo base_url('uploads/frontend/images/' . $setting['logo'] . img_reload()); ?>" />
							<span class="error"><?php echo form_error('logo'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('fav_icon'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="file" name="fav_icon" class="dropify dre-render" data-height="90" data-allowed-file-extensions="png ico" data-default-file="<?php echo base_url('uploads/frontend/images/' . $setting['fav_icon'] . img_reload()); ?>" />
							<span class="error"><?php echo form_error('fav_icon'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('page_background_image') ?? 'Page / Banner Background Image'; ?></label>
						<div class="col-md-6">
							<input type="file" name="page_background" class="dropify dre-render" data-height="110" data-allowed-file-extensions="png jpg jpeg webp" data-default-file="<?php echo !empty($setting['page_background']) ? base_url('uploads/frontend/images/' . $setting['page_background'] . img_reload()) : ''; ?>" />
							<span class="help-block" style="font-size:12px; color:#6b7280;"><?=translate('configurable_page_header_and_body_background_image') ?? 'Configurable background image for frontend page headers and banners.'?></span>
						</div>
					</div>

					<!-- Header Quick Buttons Section -->
					<div class="form-group" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding-top: 20px; padding-bottom: 20px;">
						<label class="col-md-3 control-label">
							<strong>হেডার কুইক বাটনসমূহ</strong><br>
							<small class="text-muted">লোগোর ডানপাশে প্রদর্শিত বাটনসমূহ (Add, Edit, Remove, Link)</small>
						</label>
						<div class="col-md-9">
							<?php
								$raw_saved_btns = !empty($setting['header_quick_buttons']) ? $setting['header_quick_buttons'] : '';
								$saved_buttons = !empty($raw_saved_btns) ? json_decode($raw_saved_btns, true) : null;
								if ($saved_buttons === null) {
									$saved_buttons = array(
										array('text' => 'পরিচালকের বাণী', 'text_en' => "Director's Message", 'url' => 'message-founder/1', 'icon' => 'fas fa-user-tie', 'target' => '_self', 'status' => 1),
										array('text' => 'ক্যারিয়ার', 'text_en' => 'Career', 'url' => 'career', 'icon' => 'fas fa-briefcase', 'target' => '_self', 'status' => 1)
									);
								}
							?>
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-condensed bg-white" id="tblHeaderButtons" style="margin-bottom: 10px;">
									<thead>
										<tr style="background: #edf2f7; font-size: 12px;">
											<th width="20%">বাটন টেক্সট (বাংলা)</th>
											<th width="20%">বাটন টেক্সট (English)</th>
											<th width="24%">লিংক / URL</th>
											<th width="15%">আইকন (FontAwesome)</th>
											<th width="11%">টার্গেট</th>
											<th width="10%">স্ট্যাটাস</th>
											<th width="5%" class="text-center">মুছুন</th>
										</tr>
									</thead>
									<tbody id="quickButtonsContainer">
										<!-- Rendered via PHP and managed with JS -->
										<?php if (!empty($saved_buttons)): 
											foreach ($saved_buttons as $idx => $btn): 
										?>
										<tr class="btn-row">
											<td>
												<input type="text" name="quick_buttons[<?php echo $idx; ?>][text]" value="<?php echo html_escape($btn['text'] ?? ''); ?>" class="form-control input-sm" placeholder="যেমন: পরিচালকের বাণী" />
											</td>
											<td>
												<input type="text" name="quick_buttons[<?php echo $idx; ?>][text_en]" value="<?php echo html_escape($btn['text_en'] ?? ''); ?>" class="form-control input-sm" placeholder="e.g. Director's Message" />
											</td>
											<td>
												<input type="text" name="quick_buttons[<?php echo $idx; ?>][url]" value="<?php echo html_escape($btn['url'] ?? ''); ?>" class="form-control input-sm" placeholder="e.g. message-founder/1 বা career" />
											</td>
											<td>
												<input type="text" name="quick_buttons[<?php echo $idx; ?>][icon]" value="<?php echo html_escape($btn['icon'] ?? 'fas fa-link'); ?>" class="form-control input-sm" placeholder="fas fa-user-tie" />
											</td>
											<td>
												<select name="quick_buttons[<?php echo $idx; ?>][target]" class="form-control input-sm">
													<option value="_self" <?php echo (isset($btn['target']) && $btn['target'] == '_self') ? 'selected' : ''; ?>>Same Tab</option>
													<option value="_blank" <?php echo (isset($btn['target']) && $btn['target'] == '_blank') ? 'selected' : ''; ?>>New Tab</option>
												</select>
											</td>
											<td>
												<select name="quick_buttons[<?php echo $idx; ?>][status]" class="form-control input-sm">
													<option value="1" <?php echo (!isset($btn['status']) || $btn['status'] == 1) ? 'selected' : ''; ?>>Active</option>
													<option value="0" <?php echo (isset($btn['status']) && $btn['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
												</select>
											</td>
											<td class="text-center align-middle">
												<button type="button" class="btn btn-danger btn-xs btn-remove-hbtn" title="মুছে ফেলুন">
													<i class="fas fa-trash-alt"></i>
												</button>
											</td>
										</tr>
										<?php endforeach; endif; ?>
									</tbody>
								</table>
							</div>
							
							<div class="d-flex justify-content-between align-items-center">
								<button type="button" class="btn btn-default btn-sm" id="btnAddHeaderBtn" style="font-weight: 600;">
									<i class="fas fa-plus-circle text-success"></i> + নতুন বাটন যোগ করুন
								</button>
								<span class="help-block text-muted" style="font-size: 11px; margin-top: 5px;">
									<i class="fas fa-info-circle text-info"></i> ওয়েবসাইটের ভেতরের পেজের জন্য আপেক্ষিক লিংক (যেমন: <code>message-founder/1</code>, <code>career</code>, <code>admission</code>) অথবা পূর্ণাঙ্গ লিংক দিতে পারেন।
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('address'); ?> (বাংলা) <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" id="address" name="address" placeholder="" rows="3" ><?php echo set_value('address', $setting['address']); ?></textarea>
							<span class="error"><?php echo form_error('address'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('address'); ?> (English)</label>
						<div class="col-md-6">
							<textarea class="form-control" id="address_en" name="address_en" placeholder="" rows="3" ><?php echo set_value('address_en', isset($setting['address_en']) ? $setting['address_en'] : ''); ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('google_analytics'); ?></label>
						<div class="col-md-6">
							<textarea class="form-control" id="google_analytics" name="google_analytics" placeholder="" rows="3" ><?php echo set_value('address', $setting['google_analytics']); ?></textarea>
							<span class="error"><?php echo form_error('google_analytics'); ?></span>
						</div>
					</div>


					<div class="row theme_option">
						<label class="col-md-3 control-label">Theme <span class="required">*</span></label>
						<div class="col-md-6">
							<section class="panel pg-fw mb-md">
		                        <div class="panel-body">
		                            <h5 class="chart-title mb-xs">Theme Options</h5>
		                            <div class="mt-lg">
										<div class="form-group">
											<label class="col-md-4 control-label">Primary Color <span class="required">*</span></label>
											<div class="col-md-6">
												<input type="text" class="complex-colorpicker form-control" name="primary_color" value="<?php echo set_value('primary_color', $setting['primary_color']) ?>" />
												<span class="error"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Menu BG Color <span class="required">*</span></label>
											<div class="col-md-6">
												<input type="text" class="complex-colorpicker form-control" name="menu_color" value="<?php echo set_value('menu_color', $setting['menu_color']) ?>" />
												<span class="error"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Button Hover Color <span class="required">*</span></label>
											<div class="col-md-6">
												<input type="text" class="complex-colorpicker form-control" name="btn_hover" value="<?php echo set_value('btn_hover', $setting['hover_color']) ?>" />
												<span class="error"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Text Color <span class="required">*</span></label>
											<div class="col-md-6">
												<input type="text" class="complex-colorpicker form-control" name="text_color" value="<?php echo set_value('text_color', $setting['text_color']) ?>" />
												<span class="error"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Text Secondary Color <span class="required">*</span></label>
											<div class="col-md-6">
												<input type="text" class="complex-colorpicker form-control" name="secondary_text_color" value="<?php echo set_value('secondary_text_color', $setting['text_secondary_color']) ?>" />
												<span class="error"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Footer BG Color <span class="required">*</span></label>
											<div class="col-md-6">
												<input type="text" class="complex-colorpicker form-control" name="footer_bg_color" value="<?php echo set_value('footer_bg_color', $setting['footer_background_color']) ?>" />
												<span class="error"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Footer Text Color <span class="required">*</span></label>
											<div class="col-md-6">
												<input type="text" class="complex-colorpicker form-control" name="footer_text_color" value="<?php echo set_value('footer_text_color', $setting['footer_text_color']) ?>" />
												<span class="error"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Copyright BG Color <span class="required">*</span></label>
											<div class="col-md-6">
												<input type="text" class="complex-colorpicker form-control" name="copyright_bg_color" value="<?php echo set_value('copyright_bg_color', $setting['copyright_bg_color']) ?>" />
												<span class="error"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Copyright Text Color <span class="required">*</span></label>
											<div class="col-md-6">
												<input type="text" class="complex-colorpicker form-control" name="copyright_text_color" value="<?php echo set_value('copyright_text_color', $setting['copyright_text_color']) ?>" />
												<span class="error"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Border Radius <span class="required">*</span></label>
											<div class="col-md-6 mb-md">
												<input type="text" class="form-control" name="border_radius" autocomplete="off" value="<?php echo set_value('border_radius', $setting['border_radius']) ?>" />
												<span class="error"></span>
											</div>
										</div>

		                            </div>
		                        </div>
		                    </section>
		                </div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('mobile_no'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="mobile_no" value="<?php echo set_value('mobile_no', $setting['mobile_no']); ?>" />
							<span class="error"><?php echo form_error('mobile_no'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('email'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="email" value="<?php echo set_value('email', $setting['email']); ?>" />
							<span class="error"><?php echo form_error('email'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('fax'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="fax" value="<?php echo set_value('fax', $setting['fax']); ?>" />
							<span class="error"><?php echo form_error('fax'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('footer_about_text'); ?> (বাংলা) <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" id="footer_about_text" name="footer_about_text" placeholder="" rows="3" ><?php echo set_value('footer_about_text', $setting['footer_about_text']); ?></textarea>
							<span class="error"><?php echo form_error('footer_about_text'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('footer_about_text'); ?> (English)</label>
						<div class="col-md-6">
							<textarea class="form-control" id="footer_about_text_en" name="footer_about_text_en" placeholder="" rows="3" ><?php echo set_value('footer_about_text_en', isset($setting['footer_about_text_en']) ? $setting['footer_about_text_en'] : ''); ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('copyright_text'); ?> (বাংলা) <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" id="copyright_text" name="copyright_text" placeholder="" rows="3" ><?php echo set_value('copyright_text', $setting['copyright_text']); ?></textarea>
							<span class="error"><?php echo form_error('copyright_text'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('copyright_text'); ?> (English)</label>
						<div class="col-md-6">
							<textarea class="form-control" id="copyright_text_en" name="copyright_text_en" placeholder="" rows="3" ><?php echo set_value('copyright_text_en', isset($setting['copyright_text_en']) ? $setting['copyright_text_en'] : ''); ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('facebook_url'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="facebook_url" value="<?php echo set_value('facebook_url', $setting['facebook_url']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('twitter_url'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="twitter_url" value="<?php echo set_value('twitter_url', $setting['twitter_url']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('youtube_url'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="youtube_url" value="<?php echo set_value('youtube_url', $setting['youtube_url']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('google_plus'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="google_plus" value="<?php echo set_value('google_plus', $setting['google_plus']); ?>" />
							<span class="error"><?php echo form_error('google_plus'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('linkedin_url'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="linkedin_url" value="<?php echo set_value('linkedin_url', $setting['linkedin_url']); ?>" />
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?php echo translate('pinterest_url'); ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="pinterest_url" value="<?php echo set_value('pinterest_url', $setting['pinterest_url']); ?>" />
						</div>
					</div>
					<div class="form-group <?php if (form_error('instagram_url')) echo 'has-error'; ?>">
						<label  class="col-md-3 control-label"><?php echo translate('instagram_url'); ?></label>
						<div class="col-md-6 mb-md">
							<input type="text" class="form-control" name="instagram_url" value="<?php echo set_value('instagram_url', $setting['instagram_url']); ?>" />
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-2 col-md-offset-3">
							<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
								<i class="fas fa-plus-circle"></i> <?php echo translate('save'); ?>
							</button>
						</div>
					</div>
				</div>
			<?php echo form_close(); ?>
		</section>
	</div>
</div>

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

	// frontend setting captcha status 
	$('#captchaStatus').on('change', function(){
	    var status = $(this).val();
	    if(status == "enable") {
	        $('#recaptcha_site_key').show(300); 
	        $('#recaptcha_secret_key').show(300);  
	    } else {
	        $('#recaptcha_site_key').hide(300); 
	        $('#recaptcha_secret_key').hide(300); 
	    }
	});

	<?php if($setting['captcha_status'] == "enable") { ?>
		$('#recaptcha_site_key').show(); 
		$('#recaptcha_secret_key').show();
	<?php } else { ?>
		$('#recaptcha_site_key').hide(); 
		$('#recaptcha_secret_key').hide(); 
	<?php } ?>

	// Header Quick Buttons Repeater
	var nextBtnIdx = <?php echo !empty($saved_buttons) ? count($saved_buttons) : 0; ?>;
	$('#btnAddHeaderBtn').on('click', function() {
		var rowHtml = '<tr class="btn-row">' +
			'<td><input type="text" name="quick_buttons[' + nextBtnIdx + '][text]" class="form-control input-sm" placeholder="যেমন: পরিচালকের বাণী" /></td>' +
			'<td><input type="text" name="quick_buttons[' + nextBtnIdx + '][text_en]" class="form-control input-sm" placeholder="e.g. Director\'s Message" /></td>' +
			'<td><input type="text" name="quick_buttons[' + nextBtnIdx + '][url]" class="form-control input-sm" placeholder="e.g. message-founder/1" /></td>' +
			'<td><input type="text" name="quick_buttons[' + nextBtnIdx + '][icon]" value="fas fa-link" class="form-control input-sm" placeholder="fas fa-link" /></td>' +
			'<td><select name="quick_buttons[' + nextBtnIdx + '][target]" class="form-control input-sm"><option value="_self">Same Tab</option><option value="_blank">New Tab</option></select></td>' +
			'<td><select name="quick_buttons[' + nextBtnIdx + '][status]" class="form-control input-sm"><option value="1">Active</option><option value="0">Inactive</option></select></td>' +
			'<td class="text-center align-middle"><button type="button" class="btn btn-danger btn-xs btn-remove-hbtn" title="মুছে ফেলুন"><i class="fas fa-trash-alt"></i></button></td>' +
			'</tr>';
		$('#quickButtonsContainer').append(rowHtml);
		nextBtnIdx++;
	});

	$(document).on('click', '.btn-remove-hbtn', function() {
		$(this).closest('tr').remove();
	});
</script>

<?php endif; ?>