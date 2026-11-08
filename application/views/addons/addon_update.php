<section class="panel">
	<div class="panel-heading">
		<div class="panel-btn">
			<a href="<?php echo base_url('addons/manage') ?>"  class="btn btn-circle btn-default mb-sm"><i class="fa-solid fa-left-long"></i> <?=translate('addon_manager')?></a>
		</div>
		<h4 class="panel-title"> <i class="fas fa-puzzle-piece"></i> <?=translate('addon')?> Details</h4>
	</div>
	<div class="panel-body">
		<?php if ($current_version != $latest_version && $latest_version > $current_version) { ?>
			<div class="alert alert-info text-center mt-md">
				Before performing an update, it is <b>strongly recommended to create a full backup</b> of your current installation <b>(files and database)</b> and review the changelog.
			</div>
		<?php } ?>
		<div class="col-md-8 col-md-offset-2">
			<div class="table-responsive mt-md mb-md">
				<table class="table sys-update table-striped table-bordered mb-none">
					<tbody>
					<?php if (!empty($update_errors)) {?>
						<tr>
							<td colspan="2"><div class="alert alert-danger mb-none"><?php echo $update_errors ?></div></td>
						</tr>
					<?php } ?>
						<tr>
							<th><i class="far fa-circle"></i> Addon Name</th>
							<td><strong><?php echo html_escape(str_ireplace(['Ramom School ', 'Ramom '], '', $addon->name)); ?></strong></td>
						</tr>
						<tr>
							<th><i class="far fa-circle"></i> Your version</th>
							<td><?php echo wordwrap($current_version, 1, '.', true); ?></td>
						</tr>
						<tr>
							<th><i class="far fa-circle"></i> Latest Version</th>
							<td><?php echo wordwrap($latest_version, 1, '.', true) ?></td>
						</tr>
						<tr>
							<th><i class="far fa-circle"></i> Status</th>
							<td><span class="badge badge-success"><i class="fas fa-check"></i> Activated</span></td>
						</tr>
						<tr>
							<th><i class="far fa-circle"></i> Php version </th>
							<td><?php echo phpversion(); ?></td>
						</tr>
						<tr>
							<th><i class="far fa-circle"></i> Zip Extension </th>
							<td><?php 
							if ($zip_extension) {
								echo '<span class="badge badge-success">Enabled</span>';
							} else {
								echo '<span class="badge badge-danger">Not Enabled</span>';
							}
							?></td>
						</tr>
						<tr>
							<th><i class="far fa-circle"></i> cURL Extension </th>
							<td><?php 
							if ($curl_extension) {
								echo '<span class="badge badge-success">Enabled</span>';
							} else {
								echo '<span class="badge badge-danger">Not Enabled</span>';
							}
							?></td>
						</tr>
					<?php if ($current_version != $latest_version && $latest_version > $current_version) { ?>
						<tr>
							<td colspan="2">
								<?php if (isset($get_update_info->update_history) && !empty($get_update_info->update_history)) {
									echo $get_update_info->update_history;
								} ?>
								<div class="col-md-offset-4 col-md-4">
									<button id="update_app" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
										<i class="far fa-arrow-alt-circle-down"></i> Update Now
									</button>
								</div>	
								<div class="col-md-12">
									<div id="update_messages"></div>
								</div>
							</td>
						</tr>
					<?php } else { ?>
						<tr>
							<td colspan="2" class="text-center text-success" style="font-weight: 600; padding: 12px;">
								<i class="fas fa-check-circle"></i> <?=translate('you_are_now_using_the_latest_version')?>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$('#update_app').on('click', function (e) {
		e.preventDefault();
		$('#update_messages').html("");
		var btn = $(this);
		var latest_version = "<?=$latest_version?>";
		var items = "<?=$items?>";
		$.ajax({
		    url: base_url + 'addons/update_install',
		    type: "POST",
		    data: {
		    	'latest_version': latest_version,
		    	'items': items
		    },
		    dataType: 'json',
		    beforeSend: function () {
		        btn.button('loading');
		    },
		    success: function (res) {
		    	if (res.status) {
		            $('#update_messages').html('<div class="alert alert-success mt-lg"></div>');
		            $('#update_messages .alert').append('<p>' + res.message + '</p>');
                    setTimeout(function () {
                        window.location.reload();
                    }, 5000);
		    	} else {
		            $('#update_messages').html('<div class="alert alert-danger mt-lg"></div>');
		            $('#update_messages .alert').append('<p>' + res.message + '</p>');
		            btn.button('reset'); 
		    	}
		    },
            error: function () {
                btn.button('reset');
            }
		});
	});
</script>
