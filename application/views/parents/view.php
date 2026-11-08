<?php if (is_superadmin_loggedin() ): ?>
	<section class="panel">
		<header class="panel-heading">
			<h4 class="panel-title"><?=translate('select_ground')?></h4>
		</header>
		<?php echo form_open($this->uri->uri_string(), array('class' => 'validate'));?>
		<div class="panel-body">
			<div class="row mb-sm">
				<div class="col-md-offset-3 col-md-6">
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-offset-10 col-md-2">
					<button type="submit" name="search" value="1" class="btn btn-default btn-block"> <i class="fas fa-filter"></i> <?=translate('filter')?></button>
				</div>
			</div>
		</footer>
		<?php echo form_close();?>
	</section>
<?php endif; ?>
<?php if (!empty($branch_id)): ?>
<?php if (is_superadmin_loggedin()) { ?>
<div class="row appear-animation" data-appear-animation="<?=$global_config['animations'] ?>" data-appear-animation-delay="100">
<?php } else { ?>
<div class="row">
<?php } ?>	
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
			<?php if (get_permission('parent', 'is_delete')): ?>
				<div class="panel-btn">
					<button class="btn btn-default btn-circle" id="parent_bulk_delete" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-trash-alt"></i> <?=translate('bulk_delete')?>
					</button>
				</div>
			<?php endif; ?>
				<h4 class="panel-title">
					<i class="fas fa-users"></i> <?=translate('parents_list')?>
				</h4>
			</header>
			<div class="panel-body">
				<div class="mb-md mt-md">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
							<?php if (get_permission('parent', 'is_delete')): ?>
								<th width="10" class="no-sort">
									<div class="checkbox-replace">
										<label class="i-checks"><input type="checkbox" id="selectAllchkbox"><i></i></label>
									</div>
								</th>
							<?php endif; ?>
								<th><?=translate('sl')?></th>
							<?php if (is_superadmin_loggedin()) { ?>
								<th><?=translate('branch')?></th>
							<?php } ?>
								<th><?=translate('guardian_name')?></th>
								<th><?=translate('occupation')?></th>
								<th><?=translate('mobile_no')?></th>
								<th><?=translate('email')?></th>
							<?php
							$show_custom_fields = custom_form_table('parents', $branch_id);
							if (count($show_custom_fields)) {
								foreach ($show_custom_fields as $fields) {
							?>
								<th><?=$fields['field_label']?></th>
							<?php } } ?>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							$parentslist = $this->parents_model->getParentList($branch_id);
							if (count($parentslist)) {
								foreach($parentslist as $row):
							?>	
							<tr>
							<?php if (get_permission('parent', 'is_delete')): ?>
								<td class="checked-area">
									<div class="checkbox-replace">
										<label class="i-checks"><input type="checkbox" class="cb_bulkdelete" value="<?=$row->id?>"><i></i></label>
									</div>
								</td>
							<?php endif; ?>
								<td><?php echo $count++; ?></td>
							<?php if (is_superadmin_loggedin()) { ?>
								<td><?php echo get_type_name_by_id('branch', $row->branch_id);?></td>
							<?php } ?>
								<td><?php echo $row->name;?></td>
								<td><?php echo $row->occupation;?></td>
								<td><?php echo $row->mobileno;?></td>
								<td><?php echo $row->email;?></td>
							<?php
							if (count($show_custom_fields)) {
								foreach ($show_custom_fields as $fields) {
							?>
								<td><?php echo get_table_custom_field_value($fields['id'], $row->id);?></td>
							<?php } } ?>
								<td class="min-w-xs">
								<?php if (get_permission('parent', 'is_edit')): ?>
									<!-- update link -->
									<a href="<?php echo base_url('parents/profile/' . $row->id);?>" class="btn btn-default btn-circle icon" data-toggle="tooltip"
									data-original-title="<?=translate('profile')?>">
										<i class="far fa-arrow-alt-circle-right"></i>
									</a>
								<?php endif; if (get_permission('parent', 'is_delete')): ?>
									<!-- delete link -->
									<?php echo btn_delete('parents/delete/' . $row->id);?>
								<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; };?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>
<?php if (get_permission('parent', 'is_delete')): ?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#selectAllchkbox').on('change', function() {
			var isChecked = this.checked;
			$("input[type='checkbox'].cb_bulkdelete").each(function () {
				this.checked = isChecked;
			});
		});

		$('#parent_bulk_delete').on('click', function() {
			var btn = $(this);
			var arrayID = [];
			$("input[type='checkbox'].cb_bulkdelete").each(function () {
				if(this.checked) {
					arrayID.push($(this).val());
				}
			});
			if (arrayID.length === 0) {
				swal({
					title: "<?=translate('no_selection') ?? 'No Record Selected'?>",
					text: "<?=translate('please_select_at_least_one_record') ?? 'Please select at least one record to delete.'?>",
					type: "warning"
				});
				return false;
			}
			swal({
				title: "<?=translate('are_you_sure')?>",
				text: "<?=translate('delete_this_information')?> (" + arrayID.length + " records)",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn btn-danger",
				confirmButtonText: "<?=translate('yes_continue')?>",
				cancelButtonText: "<?=translate('cancel')?>",
				showLoaderOnConfirm: true,
				closeOnConfirm: false
			}, function (isConfirm) {
				if (isConfirm) {
					btn.button('loading');
					$.ajax({
						url: base_url + "parents/bulk_delete",
						type: "POST",
						dataType: "json",
						data: { array_id : arrayID },
						success: function(data) {
							btn.button('reset');
							if (data.status == 'success') {
								swal({
									title: "<?=translate('deleted')?>",
									text: data.message,
									type: "success"
								}, function () {
									location.reload();
								});
							} else {
								swal("Error", data.message, "error");
							}
						},
						error: function() {
							btn.button('reset');
							swal("Error", "Server error occurred", "error");
						}
					});
				}
			});
		});
	});
</script>
<?php endif; ?>
<?php endif; ?>