<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title">
			<i class="fas fa-edit"></i> <?php echo translate('edit') . " " . translate('board_results'); ?>
		</h4>
	</header>
	<?php echo form_open_multipart('frontend/board_results/edit/' . $board_result['id'], array('class' => 'form-horizontal form-bordered frm-submit-data')); ?>
		<div class="panel-body">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="form-group">
				<label class="col-md-3 control-label"><?=translate('branch')?> <span class="required">*</span></label>
				<div class="col-md-6">
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $board_result['branch_id']), "class='form-control' data-width='100%' data-plugin-selectTwo");
					?>
					<span class="error"><?=form_error('branch_id')?></span>
				</div>
			</div>
			<?php endif; ?>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('exam_name'); ?> (বাংলা) <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="exam_name" value="<?=set_value('exam_name', $board_result['exam_name'])?>" placeholder="যেমন: দাখিল পরীক্ষা" required />
					<span class="error"><?=form_error('exam_name')?></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('exam_name'); ?> (English)</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="exam_name_en" value="<?=set_value('exam_name_en', $board_result['exam_name_en'])?>" placeholder="e.g. Dakhil Examination" />
					<span class="error"><?=form_error('exam_name_en')?></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('year'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="year" value="<?=set_value('year', $board_result['year'])?>" min="2000" max="2099" placeholder="যেমন: 2024" required />
					<span class="error"><?=form_error('year')?></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('total_students'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="total_students" id="total_students" value="<?=set_value('total_students', $board_result['total_students'])?>" placeholder="মোট পরীক্ষার্থী সংখ্যা" required />
					<span class="error"><?=form_error('total_students')?></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('passed_students'); ?> <span class="required">*</span></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="passed_students" id="passed_students" value="<?=set_value('passed_students', $board_result['passed_students'])?>" placeholder="উত্তীর্ণ পরীক্ষার্থী সংখ্যা" required />
					<span class="error"><?=form_error('passed_students')?></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('failed_students'); ?></label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="failed_students" id="failed_students" value="<?=set_value('failed_students', $board_result['failed_students'])?>" placeholder="অনুত্তীর্ণ পরীক্ষার্থী সংখ্যা" />
					<span class="error"><?=form_error('failed_students')?></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('pass_percentage'); ?> (%)</label>
				<div class="col-md-6">
					<input type="number" step="0.01" class="form-control" name="pass_percentage" id="pass_percentage" value="<?=set_value('pass_percentage', $board_result['pass_percentage'])?>" placeholder="যেমন: 96.50" />
					<span class="error"><?=form_error('pass_percentage')?></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('grade_breakdown'); ?></label>
				<div class="col-md-6">
					<div class="row">
						<div class="col-xs-6 col-md-4 mb-sm">
							<label class="small text-muted"><i class="fas fa-star text-warning"></i> GPA 5.0 (A+)</label>
							<input type="number" class="form-control" name="gpa_5" value="<?=set_value('gpa_5', $board_result['gpa_5'])?>" />
						</div>
						<div class="col-xs-6 col-md-4 mb-sm">
							<label class="small text-muted">Grade A (4.0)</label>
							<input type="number" class="form-control" name="grade_a" value="<?=set_value('grade_a', $board_result['grade_a'])?>" />
						</div>
						<div class="col-xs-6 col-md-4 mb-sm">
							<label class="small text-muted">Grade A- (3.5)</label>
							<input type="number" class="form-control" name="grade_a_minus" value="<?=set_value('grade_a_minus', $board_result['grade_a_minus'])?>" />
						</div>
						<div class="col-xs-6 col-md-4 mb-sm">
							<label class="small text-muted">Grade B (3.0)</label>
							<input type="number" class="form-control" name="grade_b" value="<?=set_value('grade_b', $board_result['grade_b'])?>" />
						</div>
						<div class="col-xs-6 col-md-4 mb-sm">
							<label class="small text-muted">Grade C (2.0)</label>
							<input type="number" class="form-control" name="grade_c" value="<?=set_value('grade_c', $board_result['grade_c'])?>" />
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('result_file'); ?> (PDF / Image)</label>
				<div class="col-md-6">
					<input type="file" name="result_file" class="dropify" data-height="120" data-allowed-file-extensions="pdf jpg jpeg png" 
						<?php if (!empty($board_result['result_file'])): ?>
							data-default-file="<?php echo base_url('uploads/frontend/board_results/' . $board_result['result_file']); ?>"
						<?php endif; ?>
					/>
					<small class="text-muted">অনুমোদিত ফরম্যাট: PDF, JPG, PNG (পরিবর্তন না করতে চাইলে খালি রাখুন)</small>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('description'); ?> / বিবরণ</label>
				<div class="col-md-6">
					<textarea class="form-control" name="description" rows="3"><?=set_value('description', $board_result['description'])?></textarea>
					<span class="error"><?=form_error('description')?></span>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"><?php echo translate('status'); ?></label>
				<div class="col-md-6">
					<select name="status" class="form-control">
						<option value="1" <?=set_select('status', '1', $board_result['status'] == 1)?>>Active (ওয়েবসাইটে প্রদর্শিত হবে)</option>
						<option value="0" <?=set_select('status', '0', $board_result['status'] == 0)?>>Inactive (লুকায়িত থাকবে)</option>
					</select>
				</div>
			</div>
		</div>

		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-offset-3 col-md-6">
					<button type="submit" name="save" value="1" class="btn btn-default">
						<i class="fas fa-check-circle"></i> <?php echo translate('update'); ?>
					</button>
					<a href="<?php echo base_url('frontend/board_results'); ?>" class="btn btn-default">
						<i class="fas fa-arrow-left"></i> <?php echo translate('back'); ?>
					</a>
				</div>
			</div>
		</footer>
	<?php echo form_close(); ?>
</section>

<script type="text/javascript">
	$(document).ready(function () {
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
