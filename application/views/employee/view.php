<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<?php
			$this->db->where_not_in('id', array(1,6,7));
			$roles = $this->db->get('roles')->result();
			foreach ($roles as $role){
			?> 	
			<li class="<?php if ($role->id == $act_role) echo 'active'; ?>">
				<a href="<?php echo base_url('employee/view/' . $role->id); ?>">
					<i class="far fa-user-circle"></i> <?php echo $role->name?>
				</a>
			</li>
			<?php } ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane box active">
				<div class="export_title">
					<?php echo translate('employee') . " " . translate('list'); ?>
					<?php if (get_permission('employee', 'is_delete')): ?>
						<button class="btn btn-default btn-circle pull-right" id="employee_bulk_delete" style="margin-bottom: 10px;" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-trash-alt text-danger"></i> <?=translate('bulk_delete')?>
						</button>
					<?php endif; ?>
				</div>
				<table class="table table-bordered table-hover table-condensed table-export" cellspacing="0" width="100%">
					<thead>
						<tr>
						<?php if (get_permission('employee', 'is_delete')): ?>
							<th width="10" class="no-sort">
								<div class="checkbox-replace">
									<label class="i-checks"><input type="checkbox" id="selectAllchkbox"><i></i></label>
								</div>
							</th>
						<?php endif; ?>
							<th><?php echo translate('sl'); ?></th>
							<th class="no-sort"><?php echo translate('photo'); ?></th>
							<th><?php echo translate('branch'); ?></th>
							<th><?php echo translate('staff_id'); ?></th>
							<th><?php echo translate('name'); ?></th>
							<th><?php echo translate('designation'); ?></th>
							<th><?php echo translate('department'); ?></th>
							<th><?php echo translate('email'); ?></th>
							<th><?php echo translate('mobile_no'); ?></th>
						<?php
						if (!is_superadmin_loggedin()){
							$show_custom_fields = custom_form_table('employee', get_loggedin_branch_id());
							if (count($show_custom_fields)) {
								foreach ($show_custom_fields as $fields) {
							?>
							<th><?=$fields['field_label']?></th>
						<?php } } } ?>
							<th><?php echo translate('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1; foreach ($stafflist as $row): ?>
						<tr>
						<?php if (get_permission('employee', 'is_delete')): ?>
							<td class="checked-area">
								<div class="checkbox-replace">
									<label class="i-checks"><input type="checkbox" class="cb_bulkdelete" value="<?=$row->id?>" <?php echo ($row->id == get_loggedin_user_id()) ? 'disabled title="Current User"' : ''; ?>><i></i></label>
								</div>
							</td>
						<?php endif; ?>
							<td><?php echo $i++; ?></td>
							<td class="center">
								<img src="<?php echo get_image_url('staff', $row->photo); ?>" height="50" />
							</td>
							<td><?php echo get_type_name_by_id('branch', $row->branch_id);?></td>
							<td><?php echo $row->staff_id; ?></td>
							<td><?php echo $row->name; ?></td>
							<td><?php echo $row->designation_name; ?></td>
							<td><?php echo $row->department_name; ?></td>
							<td><?php echo $row->email; ?></td>
							<td><?php echo $row->mobileno; ?></td>
						<?php
						if (!is_superadmin_loggedin()){
							if (count($show_custom_fields)) {
								foreach ($show_custom_fields as $fields) {
							?>
							<td><?php echo get_table_custom_field_value($fields['id'], $row->id);?></td>
						<?php } } } ?>
							<td class="min-w-c">
							<?php if (get_permission('employee', 'is_edit')): ?>
								<a href="<?php echo base_url('employee/profile/'.$row->id); ?>" class="btn btn-circle btn-default icon" data-toggle="tooltip" 
								data-original-title="<?=translate('profile')?>">
									<i class="far fa-arrow-alt-circle-right"></i>
								</a>
							<?php endif; if (get_permission('employee', 'is_delete')): ?>
								<?php echo btn_delete('employee/delete/' . $row->id); ?>
							<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<?php if (get_permission('employee', 'is_delete')): ?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#selectAllchkbox').on('change', function() {
			var isChecked = this.checked;
			$("input[type='checkbox'].cb_bulkdelete:not(:disabled)").each(function () {
				this.checked = isChecked;
			});
		});

		$('#employee_bulk_delete').on('click', function() {
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
						url: base_url + "employee/bulk_delete",
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