<?php if (is_superadmin_loggedin()): ?>
	<?php $this->load->view('frontend/branch_select'); ?>
<?php endif; ?>

<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('founders') . " " . translate('list'); ?></a>
			</li>
			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('add') . " " . translate('founder'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane active">
				<table class="table table-bordered table-hover table-condensed table_default" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th width="40"><?php echo translate('sl'); ?></th>
							<th width="80"><?php echo translate('photo'); ?></th>
							<th><?php echo translate('name') . ' (Bangla / English)'; ?></th>
							<th><?php echo translate('designation') . ' / ' . translate('title'); ?></th>
							<th width="80"><?php echo translate('position'); ?></th>
							<th width="90"><?php echo translate('status'); ?></th>
							<th width="100"><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$count = 1;
							if (!empty($founderlist)) {
								foreach ($founderlist as $row):
									$img_url = '';
									if (!empty($row['image'])) {
										if (file_exists(FCPATH . 'uploads/frontend/founder/' . $row['image'])) {
											$img_url = base_url('uploads/frontend/founder/' . $row['image']);
										}
									}
									if (empty($img_url)) {
										$img_url = base_url('uploads/app_image/default.png');
									}
						?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td class="text-center">
								<img class="img-rounded" src="<?php echo $img_url; ?>" style="height: 55px; width: 50px; object-fit: cover;" onerror="this.src='<?php echo base_url('uploads/app_image/defualt.png'); ?>';"/>
							</td>
							<td>
								<strong><?php echo html_escape($row['name_bn']); ?></strong>
								<?php if (!empty($row['name_en'])): ?>
									<br><small class="text-muted"><?php echo html_escape($row['name_en']); ?></small>
								<?php endif; ?>
							</td>
							<td>
								<?php echo !empty($row['designation_bn']) ? html_escape($row['designation_bn']) : html_escape($row['title_bn']); ?>
								<?php if (!empty($row['designation_en'])): ?>
									<br><small class="text-muted"><?php echo html_escape($row['designation_en']); ?></small>
								<?php endif; ?>
							</td>
							<td class="text-center">
								<span class="badge" style="background-color: #64748b;"><?php echo $row['position']; ?></span>
							</td>
							<td class="text-center">
								<?php if ($row['active'] == 1): ?>
									<span class="label label-success"><?=translate('active')?></span>
								<?php else: ?>
									<span class="label label-danger"><?=translate('inactive')?></span>
								<?php endif; ?>
							</td>
							<td class="min-w-xs text-center">
								<a href="<?php echo base_url('frontend/founder/edit/' . $row['id']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
									<i class="fas fa-pen-nib"></i>
								</a>
								<?php echo btn_delete('frontend/founder/delete/' . $row['id']); ?>
							</td>
						</tr>
						<?php endforeach; } ?>
					</tbody>
				</table>
			</div>

			<div class="tab-pane" id="create">
			    <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
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
							<input type="text" class="form-control" name="name_bn" value="<?php echo set_value('name_bn'); ?>" placeholder="যেমন: মাওলানা মোঃ সাইফুল ইসলাম" required />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Name (English) <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="name_en" value="<?php echo set_value('name_en'); ?>" placeholder="e.g. Md. Saiful Islam" required />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Title (Bangla)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title_bn" value="<?php echo set_value('title_bn'); ?>" placeholder="যেমন: পরিচালক / সভাপতি" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Title (English)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title_en" value="<?php echo set_value('title_en'); ?>" placeholder="e.g. Chairman / Director" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Designation (Bangla)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="designation_bn" value="<?php echo set_value('designation_bn'); ?>" placeholder="যেমন: প্রতিষ্ঠাতা ও চেয়ারম্যান" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Designation (English)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="designation_en" value="<?php echo set_value('designation_en'); ?>" placeholder="e.g. Founder & Chairman" />
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Position <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="position" value="<?php echo set_value('position', '1'); ?>" required />
							<span class="text-muted small">Display order on frontend</span>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Message (Bangla)</label>
						<div class="col-md-8">
							<textarea class="form-control" id="message_bn" name="message_bn" rows="6"><?php echo set_value('message_bn'); ?></textarea>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Message (English)</label>
						<div class="col-md-8">
							<textarea class="form-control" id="message_en" name="message_en" rows="6"><?php echo set_value('message_en'); ?></textarea>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Image <span class="required">*</span></label>
						<div class="col-md-4">
							<input type="file" name="image" class="dropify" data-height="160" data-allowed-file-extensions="jpg jpeg png gif" required />
							<span class="text-muted small">Accepted formats: JPEG, PNG, JPG, GIF (Max 2MB)</span>
							<span class="error"></span>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-offset-3 col-md-6">
							<div class="checkbox-custom checkbox-success">
								<input type="checkbox" name="active" id="active_create" value="1" checked />
								<label for="active_create"><b>Active</b> (Check this to make the founder visible on the frontend)</label>
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
        if ($('#message_bn').length) {
            CKEDITOR.replace('message_bn', { height: 220 });
        }
        if ($('#message_en').length) {
            CKEDITOR.replace('message_en', { height: 220 });
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
