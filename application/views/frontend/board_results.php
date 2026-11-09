<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('board_results') . " " . translate('list'); ?></a>
			</li>
			<li class="<?php echo (isset($validation_error) ? 'active' : ''); ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('add') . " " . translate('board_results'); ?></a>
			</li>
		</ul>
		<div class="tab-content">
			<!-- Results List Tab -->
			<div id="list" class="tab-pane <?php echo (!isset($validation_error) ? 'active' : ''); ?>">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed table_default" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th width="40"><?php echo translate('sl'); ?></th>
								<?php if (is_superadmin_loggedin()): ?>
									<th><?=translate('branch')?></th>
								<?php endif; ?>
								<th><?php echo translate('exam_name'); ?></th>
								<th><?php echo translate('year'); ?></th>
								<th><?php echo translate('total_students'); ?></th>
								<th><?php echo translate('passed'); ?></th>
								<th><?php echo translate('failed'); ?></th>
								<th><?php echo translate('pass_percentage'); ?></th>
								<th><?php echo translate('gpa_5'); ?></th>
								<th><?php echo translate('result_file'); ?></th>
								<th><?php echo translate('status'); ?></th>
								<th width="100"><?php echo translate('action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if (!empty($results_list)) {
								foreach ($results_list as $row):
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<?php if (is_superadmin_loggedin()): ?>
									<td><?php echo html_escape($row['branch_name']); ?></td>
								<?php endif; ?>
								<td>
									<strong><?php echo html_escape($row['exam_name']); ?></strong>
									<?php if (!empty($row['exam_name_en'])): ?>
										<br><small class="text-muted"><?php echo html_escape($row['exam_name_en']); ?></small>
									<?php endif; ?>
								</td>
								<td><span class="label label-primary"><?php echo html_escape($row['year']); ?></span></td>
								<td><strong><?php echo html_escape($row['total_students']); ?></strong></td>
								<td><span class="text-success fw-bold"><?php echo html_escape($row['passed_students']); ?></span></td>
								<td><span class="text-danger"><?php echo html_escape($row['failed_students']); ?></span></td>
								<td>
									<span class="label label-success" style="font-size: 12px; font-weight: 600;">
										<?php echo html_escape($row['pass_percentage']); ?>%
									</span>
								</td>
								<td>
									<span class="badge label label-warning" style="background-color: #f59e0b; color: #fff;">
										<i class="fas fa-star"></i> <?php echo html_escape($row['gpa_5']); ?>
									</span>
								</td>
								<td>
									<?php if (!empty($row['result_file'])): ?>
										<a href="<?php echo base_url('uploads/frontend/board_results/' . $row['result_file']); ?>" target="_blank" class="btn btn-default btn-xs" data-toggle="tooltip" title="Download Sheet">
											<i class="fas fa-file-pdf text-danger"></i> PDF
										</a>
									<?php else: ?>
										<span class="text-muted small">None</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($row['status'] == 1): ?>
										<a href="<?php echo base_url('frontend/board_results/change_status/' . $row['id'] . '/0'); ?>" class="label label-success" data-toggle="tooltip" title="Click to Inactive">
											Active
										</a>
									<?php else: ?>
										<a href="<?php echo base_url('frontend/board_results/change_status/' . $row['id'] . '/1'); ?>" class="label label-danger" data-toggle="tooltip" title="Click to Active">
											Inactive
										</a>
									<?php endif; ?>
								</td>
								<td class="action">
									<a href="<?php echo base_url('frontend/board_results/edit/' . $row['id']); ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="<?php echo translate('edit'); ?>"> 
										<i class="fas fa-pen-nib"></i>
									</a>
									<a href="<?php echo base_url('frontend/board_results/delete/' . $row['id']); ?>" class="btn btn-danger btn-circle icon" onclick="return confirm('<?php echo translate('delete_confirm'); ?>');" data-toggle="tooltip" data-original-title="<?php echo translate('delete'); ?>"> 
										<i class="fas fa-trash-alt"></i>
									</a>
								</td>
							</tr>
							<?php endforeach; } ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Add Result Tab -->
			<div id="create" class="tab-pane <?php echo (isset($validation_error) ? 'active' : ''); ?>">
				<?php echo form_open_multipart('frontend/board_results', array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
					<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $branch_id), "class='form-control' data-width='100%' data-plugin-selectTwo");
							?>
							<span class="error"><?=form_error('branch_id')?></span>
						</div>
					</div>
					<?php endif; ?>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('exam_name'); ?> (বাংলা) <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="exam_name" value="<?=set_value('exam_name', 'দাখিল পরীক্ষা')?>" placeholder="যেমন: দাখিল পরীক্ষা" required />
							<span class="error"><?=form_error('exam_name')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('exam_name'); ?> (English)</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="exam_name_en" value="<?=set_value('exam_name_en', 'Dakhil Examination')?>" placeholder="e.g. Dakhil Examination" />
							<span class="error"><?=form_error('exam_name_en')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('year'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="year" value="<?=set_value('year', date('Y'))?>" min="2000" max="2099" placeholder="যেমন: 2024" required />
							<span class="error"><?=form_error('year')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('total_students'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="total_students" id="total_students" value="<?=set_value('total_students')?>" placeholder="মোট পরীক্ষার্থী সংখ্যা" required />
							<span class="error"><?=form_error('total_students')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('passed_students'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="passed_students" id="passed_students" value="<?=set_value('passed_students')?>" placeholder="উত্তীর্ণ পরীক্ষার্থী সংখ্যা" required />
							<span class="error"><?=form_error('passed_students')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('failed_students'); ?></label>
						<div class="col-md-6">
							<input type="number" class="form-control" name="failed_students" id="failed_students" value="<?=set_value('failed_students')?>" placeholder="অনুত্তীর্ণ পরীক্ষার্থী সংখ্যা (খালি রাখলে স্বয়ংক্রিয় হিসাব হবে)" />
							<span class="error"><?=form_error('failed_students')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('pass_percentage'); ?> (%)</label>
						<div class="col-md-6">
							<input type="number" step="0.01" class="form-control" name="pass_percentage" id="pass_percentage" value="<?=set_value('pass_percentage')?>" placeholder="যেমন: 96.50 (খালি রাখলে স্বয়ংক্রিয় হিসাব হবে)" />
							<span class="error"><?=form_error('pass_percentage')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('grade_breakdown'); ?></label>
						<div class="col-md-6">
							<div class="row">
								<div class="col-xs-6 col-md-4 mb-sm">
									<label class="small text-muted"><i class="fas fa-star text-warning"></i> GPA 5.0 (A+)</label>
									<input type="number" class="form-control" name="gpa_5" value="<?=set_value('gpa_5', 0)?>" placeholder="0" />
								</div>
								<div class="col-xs-6 col-md-4 mb-sm">
									<label class="small text-muted">Grade A (4.0)</label>
									<input type="number" class="form-control" name="grade_a" value="<?=set_value('grade_a', 0)?>" placeholder="0" />
								</div>
								<div class="col-xs-6 col-md-4 mb-sm">
									<label class="small text-muted">Grade A- (3.5)</label>
									<input type="number" class="form-control" name="grade_a_minus" value="<?=set_value('grade_a_minus', 0)?>" placeholder="0" />
								</div>
								<div class="col-xs-6 col-md-4 mb-sm">
									<label class="small text-muted">Grade B (3.0)</label>
									<input type="number" class="form-control" name="grade_b" value="<?=set_value('grade_b', 0)?>" placeholder="0" />
								</div>
								<div class="col-xs-6 col-md-4 mb-sm">
									<label class="small text-muted">Grade C (2.0)</label>
									<input type="number" class="form-control" name="grade_c" value="<?=set_value('grade_c', 0)?>" placeholder="0" />
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('result_file'); ?> (PDF / Image)</label>
						<div class="col-md-6">
							<input type="file" name="result_file" class="dropify" data-height="120" data-allowed-file-extensions="pdf jpg jpeg png" />
							<small class="text-muted">অনুমোদিত ফরম্যাট: PDF, JPG, PNG (সর্বোচ্চ ১০ মেগাবাইট)</small>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('description'); ?> / সংক্ষিপ্ত বিবরণ</label>
						<div class="col-md-6">
							<textarea class="form-control" name="description" rows="3" placeholder="পরীক্ষার ফলাফল সম্পর্কে সংক্ষিপ্ত বিবরণ বা কৃতিত্বের কথা লিখুন..."><?=set_value('description')?></textarea>
							<span class="error"><?=form_error('description')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('status'); ?></label>
						<div class="col-md-6">
							<select name="status" class="form-control">
								<option value="1" <?=set_select('status', '1', true)?>>Active (ওয়েবসাইটে প্রদর্শিত হবে)</option>
								<option value="0" <?=set_select('status', '0')?>>Inactive (লুকায়িত থাকবে)</option>
							</select>
						</div>
					</div>

					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" name="save" value="1" class="btn btn-default btn-block">
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
	$(document).ready(function () {
		// Auto calculate fail and pass percentage
		$('#total_students, #passed_students').on('input', function() {
			var total = parseInt($('#total_students').val()) || 0;
			var passed = parseInt($('#passed_students').val()) || 0;
			if (total > 0 && passed <= total) {
				var failed = Math.max(0, total - passed);
				$('#failed_students').val(failed);
				var pct = ((passed / total) * 100).toFixed(2);
				$('#pass_percentage').val(pct);
			}
		});
	});
</script>

