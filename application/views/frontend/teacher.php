<?php if (is_superadmin_loggedin()): ?>
	<?php $this->load->view('frontend/branch_select'); ?>
<?php endif; ?>

<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#list" data-toggle="tab">
					<i class="fas fa-list-ul"></i> <?=translate('teachers') . " " . translate('list')?>
				</a>
			</li>
			<li>
				<a href="#create" data-toggle="tab">
					<i class="far fa-edit"></i> <?=translate('add') . " " . translate('teacher')?>
				</a>
			</li>
		</ul>
		<div class="pull-right" style="margin-top: -38px; margin-right: 15px;">
			<a href="<?php echo base_url('frontend/teacher/sync' . (!empty($branch_id) ? '?branch_id=' . $branch_id : '')); ?>" class="btn btn-default btn-sm" data-toggle="tooltip" title="কর্মকর্তা ও কর্মচারী থেকে শিক্ষক তালিকা সিঙ্ক করুন">
				<i class="fas fa-sync-alt text-primary"></i> কর্মকর্তা ও কর্মচারী থেকে সিঙ্ক করুন
			</a>
		</div>
		<div class="tab-content">
			<div class="tab-pane active" id="list">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
								<th><?=translate('photo')?></th>
								<th><?=translate('name')?></th>
								<th>Title / Designation</th>
								<th>Subject</th>
								<th>Phone / Email</th>
								<th>Display Order</th>
								<th>Show on Homepage</th>
								<th><?=translate('status')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if (!empty($teacherlist)):
								foreach ($teacherlist as $row):
									$img_src = base_url('uploads/app_image/default.png');
									if (!empty($row['image'])) {
										if (file_exists(FCPATH . 'uploads/frontend/teacher/' . $row['image'])) {
											$img_src = base_url('uploads/frontend/teacher/' . $row['image']);
										} elseif (file_exists(FCPATH . 'uploads/images/staff/' . $row['image'])) {
											$img_src = base_url('uploads/images/staff/' . $row['image']);
										} elseif (file_exists(FCPATH . 'uploads/frontend/founder/' . $row['image'])) {
											$img_src = base_url('uploads/frontend/founder/' . $row['image']);
										}
									}
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td class="center">
									<img src="<?php echo $img_src; ?>" style="height: 50px; width: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;" alt="Photo" />
								</td>
								<td>
									<strong><?php echo html_escape($row['name']); ?></strong>
									<?php if (!empty($row['name_bn']) && $row['name_bn'] != $row['name']): ?>
										<br><small class="text-muted"><?php echo html_escape($row['name_bn']); ?></small>
									<?php elseif (!empty($row['name_en']) && $row['name_en'] != $row['name']): ?>
										<br><small class="text-muted"><?php echo html_escape($row['name_en']); ?></small>
									<?php endif; ?>
								</td>
								<td>
									<?php echo html_escape($row['title']); ?>
									<?php if (!empty($row['designation']) && $row['designation'] != $row['title']): ?>
										<br><span class="badge badge-info" style="font-size: 11px;"><?php echo html_escape($row['designation']); ?></span>
									<?php endif; ?>
								</td>
								<td><?php echo html_escape($row['subject']); ?></td>
								<td>
									<?php if (!empty($row['phone'])): ?>
										<i class="fas fa-phone-alt text-muted"></i> <?php echo html_escape($row['phone']); ?><br>
									<?php endif; ?>
									<?php if (!empty($row['email'])): ?>
										<i class="fas fa-envelope text-muted"></i> <?php echo html_escape($row['email']); ?>
									<?php endif; ?>
								</td>
								<td class="center"><span class="badge badge-primary" style="font-size: 12px; padding: 4px 8px;"><?php echo (int)$row['position']; ?></span></td>
								<td class="center">
									<?php if ($row['show_on_homepage'] == 1): ?>
										<span class="label label-success"><i class="fas fa-check"></i> দৃশ্যমান (Visible)</span>
									<?php else: ?>
										<span class="label label-default"><i class="fas fa-eye-slash"></i> লুকানো (Hidden)</span>
									<?php endif; ?>
								</td>
								<td class="center">
									<?php if ($row['active'] == 1): ?>
										<span class="label label-success"><?=translate('active')?></span>
									<?php else: ?>
										<span class="label label-danger"><?=translate('inactive')?></span>
									<?php endif; ?>
								</td>
								<td class="center">
									<a href="<?php echo base_url('frontend/teacher/edit/' . $row['id']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?=translate('edit')?>">
										<i class="fas fa-pen-nib"></i>
									</a>
									<?php echo btn_delete('frontend/teacher/delete/' . $row['id']); ?>
								</td>
							</tr>
							<?php
								endforeach;
							endif;
							?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="tab-pane" id="create">
				<?php echo form_open_multipart('frontend/teacher', array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
					<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $branch_id), "class='form-control' data-plugin-selectTwo
								data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"></span>
						</div>
					</div>
					<?php endif; ?>

					<div class="form-group">
						<label class="col-md-3 control-label">Name (Bangla) <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="name_bn" placeholder="শিক্ষকের পূর্ণ নাম (বাংলা)" value="<?php echo set_value('name_bn'); ?>" required />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Name (English)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="name_en" placeholder="Teacher full name (English)" value="<?php echo set_value('name_en'); ?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Title / পদবী (Bangla)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title_bn" placeholder="যেমন: সহকারী শিক্ষক / সহকারী মৌলভী" value="<?php echo set_value('title_bn'); ?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Title (English)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title_en" placeholder="e.g. Assistant Teacher / Senior Teacher" value="<?php echo set_value('title_en'); ?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Designation (Bangla)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="designation_bn" placeholder="যেমন: সহকারী মৌলভী / সিনিয়র শিক্ষক" value="<?php echo set_value('designation_bn'); ?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Designation (English)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="designation_en" placeholder="e.g. Assistant Teacher / Lecturer" value="<?php echo set_value('designation_en'); ?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Subject / বিভাগ (Bangla)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="subject_bn" placeholder="যেমন: আরবি, ইংরেজি, গণিত" value="<?php echo set_value('subject_bn'); ?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Subject (English)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="subject_en" placeholder="e.g. Arabic, English, Mathematics" value="<?php echo set_value('subject_en'); ?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Display Order on Frontend / পজিশন <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="position" value="<?php echo set_value('position', '1'); ?>" min="1" required />
							<span class="text-muted small"><i class="fas fa-info-circle text-info"></i> ওয়েবসাইটে কার পরে কাকে দেখানো হবে সেই ক্রমিক নম্বর (১, ২, ৩...)</span>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Phone</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="phone" placeholder="e.g. 017xxxxxxxx" value="<?php echo set_value('phone'); ?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Email</label>
						<div class="col-md-6">
							<input type="email" class="form-control" name="email" placeholder="Enter email address" value="<?php echo set_value('email'); ?>" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Message / Bio (Bangla)</label>
						<div class="col-md-8">
							<textarea class="form-control" id="teacher_message_bn" name="message_bn" rows="5" placeholder="শিক্ষকের বার্তা বা জীবনবৃত্তান্ত (বাংলা)"><?php echo set_value('message_bn'); ?></textarea>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Message / Bio (English)</label>
						<div class="col-md-8">
							<textarea class="form-control" id="teacher_message_en" name="message_en" rows="5" placeholder="Teacher bio or message to students (English)"><?php echo set_value('message_en'); ?></textarea>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Image</label>
						<div class="col-md-4">
							<input type="file" name="image" class="dropify" data-height="160" data-allowed-file-extensions="jpg jpeg png gif webp" />
							<span class="text-muted small">Accepted formats: JPEG, PNG, JPG, GIF, WEBP (Max 2MB). Optional (system default image will be used if left blank).</span>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-offset-3 col-md-6">
							<div class="checkbox-custom checkbox-primary mb-xs">
								<input type="checkbox" name="show_on_homepage" id="show_on_homepage_create" value="1" checked />
								<label for="show_on_homepage_create"><b>Show on Homepage (হোমপেজে দৃশ্যমান)</b> - চেক করা থাকলে মূল হোমপেজে "শিক্ষকমণ্ডলী" সেকশনে দেখাবে</label>
							</div>
							<div class="checkbox-custom checkbox-success">
								<input type="checkbox" name="active" id="active_create_teacher" value="1" checked />
								<label for="active_create_teacher"><b>Active (সক্রিয়)</b> - আনচেক করলে ওয়েবসাইট থেকে সাময়িকভাবে লুকানো থাকবে</label>
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
		</div>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {
    if ($.fn.dropify) {
        $('.dropify').dropify();
    }
    if (typeof CKEDITOR !== 'undefined') {
        if ($('#teacher_message_bn').length) {
            CKEDITOR.replace('teacher_message_bn', { height: 180 });
        }
        if ($('#teacher_message_en').length) {
            CKEDITOR.replace('teacher_message_en', { height: 180 });
        }
    }

    $('form.frm-submit-data').on('submit', function() {
        if (typeof CKEDITOR !== 'undefined') {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
    });
});
</script>

