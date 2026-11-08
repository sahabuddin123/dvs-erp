<div class="row">
	<div class="col-md-12">
		<!-- Summary Widgets -->
		<div class="row">
			<div class="col-md-3 col-sm-6">
				<div class="panel widget-box mb-md" style="border-left: 4px solid #0088cc; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
					<div class="panel-body">
						<div class="widget-box-content">
							<div class="text-muted" style="font-size: 13px; font-weight: 600; text-transform: uppercase;">
								<i class="fas fa-database text-primary"></i> <?=translate('database_size')?>
							</div>
							<h3 class="text-semibold text-dark mt-xs mb-none"><?=bytesToSize('', $db_stats['total_size'] ?? 0)?></h3>
							<small class="text-muted"><?=translate('data')?>: <?=bytesToSize('', $db_stats['data_size'] ?? 0)?> | <?=translate('index')?>: <?=bytesToSize('', $db_stats['index_size'] ?? 0)?></small>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6">
				<div class="panel widget-box mb-md" style="border-left: 4px solid #2baab1; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
					<div class="panel-body">
						<div class="widget-box-content">
							<div class="text-muted" style="font-size: 13px; font-weight: 600; text-transform: uppercase;">
								<i class="fas fa-table text-info"></i> <?=translate('total_tables')?>
							</div>
							<h3 class="text-semibold text-dark mt-xs mb-none"><?=number_format($db_stats['total_tables'] ?? 0)?></h3>
							<small class="text-muted"><?=translate('storage_engine')?>: <?=$db_stats['storage_engine'] ?? 'InnoDB'?></small>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6">
				<div class="panel widget-box mb-md" style="border-left: 4px solid #734ba9; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
					<div class="panel-body">
						<div class="widget-box-content">
							<div class="text-muted" style="font-size: 13px; font-weight: 600; text-transform: uppercase;">
								<i class="fas fa-layer-group" style="color: #734ba9;"></i> <?=translate('total_records')?>
							</div>
							<h3 class="text-semibold text-dark mt-xs mb-none"><?=number_format($db_stats['total_rows'] ?? 0)?></h3>
							<small class="text-muted"><?=translate('server')?>: MySQL <?=$db_stats['mysql_version'] ?? ''?></small>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6">
				<div class="panel widget-box mb-md" style="border-left: 4px solid #2b957a; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
					<div class="panel-body">
						<div class="widget-box-content">
							<div class="text-muted" style="font-size: 13px; font-weight: 600; text-transform: uppercase;">
								<i class="fas fa-box-archive text-success"></i> <?=translate('stored_backups')?>
							</div>
							<h3 class="text-semibold text-dark mt-xs mb-none"><?=count($backup_files)?></h3>
							<small class="text-muted"><?=translate('total_size')?>: <?=bytesToSize('', $db_stats['backup_storage_size'] ?? 0)?></small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#database_backup" data-toggle="tab">
					<i class="fas fa-database"></i> <?=translate('database_list')?> <span class="badge badge-primary"><?=count($backup_files)?></span>
				</a>
			</li>
<?php if(get_permission('backup_restore', 'is_add') || is_superadmin_loggedin()) { ?>
			<li>
				<a href="#restore_database" data-toggle="tab">
					<i class="fas fa-upload"></i> <?=translate('restore_database')?>
				</a>
			</li>
<?php } ?>
			<li>
				<a href="#tables_maintenance" data-toggle="tab">
					<i class="fas fa-cubes"></i> <?=translate('tables_and_health')?>
				</a>
			</li>
			<li>
				<a href="#automated_backup" data-toggle="tab">
					<i class="fas fa-clock"></i> <?=translate('automated_backup')?>
				</a>
			</li>
		</ul>

		<div class="tab-content">
			<!-- Tab 1: Backup List -->
			<div class="tab-pane box active" id="database_backup">
				<div class="row">
					<div class="col-md-12 mb-md">
						<div class="pull-right">
							<?php if(get_permission('backup', 'is_add') || is_superadmin_loggedin()) { ?>
								<a href="<?=base_url('backup/create')?>" class="btn btn-default btn-sm">
									<i class="fas fa-cloud-arrow-down text-primary"></i> <?=translate('create_backup')?>
								</a>
								<a href="<?=base_url('backup/download_now')?>" class="btn btn-default btn-sm ml-xs">
									<i class="fas fa-download text-success"></i> <?=translate('download_now')?>
								</a>
							<?php } ?>
							<?php if(get_permission('backup', 'is_delete') || is_superadmin_loggedin()) { ?>
								<a href="<?=base_url('backup/prune?days=30')?>" class="btn btn-default btn-sm ml-xs" onclick="return confirm('<?=translate('are_you_sure_prune_backups')?>');">
									<i class="fas fa-broom text-warning"></i> <?=translate('prune_old_backups')?> (>30d)
								</a>
							<?php } ?>
						</div>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-export">
						<thead>
							<tr>
								<th width="50">#</th>
								<th><?=translate('backup_file')?></th>
								<th><?=translate('file_size')?></th>
								<th><?=translate('date_and_time')?></th>
								<th><?=translate('relative_time')?></th>
								<th class="text-center" width="160"><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$count = 1;
						if (!empty($backup_files)) {
							foreach ($backup_files as $file):
						?>
							<tr>
								<td><?=$count++?></td>
								<td>
									<i class="far fa-file-zipper text-warning mr-xs" style="font-size: 16px;"></i>
									<strong><?=html_escape($file['filename'])?></strong>
								</td>
								<td><span class="badge badge-info"><?=$file['size_formatted']?></span></td>
								<td><i class="far fa-calendar-alt text-muted mr-xs"></i> <?=$file['date_formatted']?></td>
								<td><span class="text-muted"><i class="far fa-clock mr-xs"></i> <?=$file['relative_time']?></span></td>
								<td class="text-center">
									<!-- Download Link -->
									<a href="<?=base_url('backup/download?file=' . urlencode($file['filename']))?>" class="btn btn-circle btn-default" title="<?=translate('download')?>">
										<i class="fas fa-download text-success"></i>
									</a>

									<!-- Restore Confirmation Trigger -->
									<?php if(get_permission('backup_restore', 'is_add') || is_superadmin_loggedin()) { ?>
										<a href="javascript:void(0);" onclick="confirmRestore('<?=html_escape($file['filename'])?>');" class="btn btn-circle btn-default" title="<?=translate('restore')?>">
											<i class="fas fa-rotate-left text-warning"></i>
										</a>
									<?php } ?>

									<!-- Deletion Link -->
									<?php if(get_permission('backup', 'is_delete') || is_superadmin_loggedin()) { ?>
										<?php echo btn_delete('backup/delete_file/' . $file['filename']);?>
									<?php } ?>
								</td>
							</tr>
						<?php 
							endforeach;
						} else { ?>
							<tr>
								<td colspan="6" class="text-center" style="padding: 30px;">
									<p class="text-muted mb-md"><i class="fas fa-database fa-3x" style="opacity: 0.3;"></i></p>
									<p class="text-muted"><?=translate('no_backups_found')?></p>
									<?php if(get_permission('backup', 'is_add') || is_superadmin_loggedin()) { ?>
										<a href="<?=base_url('backup/create')?>" class="btn btn-primary btn-sm">
											<i class="fas fa-plus mr-xs"></i> <?=translate('create_first_backup')?>
										</a>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Tab 2: Restore Database -->
			<?php if(get_permission('backup_restore', 'is_add') || is_superadmin_loggedin()) { ?>
			<div class="tab-pane box" id="restore_database">
				<div class="alert alert-warning" style="border-left: 4px solid #e08e0b; background-color: #fff9ea; color: #8a6d3b;">
					<h4><i class="fas fa-triangle-exclamation mr-xs"></i> <?=translate('warning')?>!</h4>
					<p><?=translate('backup_restore_warning')?></p>
					<p class="mt-xs"><strong><?=translate('recommended')?>:</strong> <?=translate('please_create_a_backup_before_restoring')?></p>
				</div>

				<?php echo form_open_multipart('backup/restore_file', array('class' => 'form-horizontal frm-submit-data'));?>
					<div class="form-group mb-lg">
						<label class="col-md-3 control-label"><?=translate('backup_file')?> (.zip / .sql) <span class="required" aria-required="true">*</span></label>
						<div class="col-md-7">
							<input type="file" name="uploaded_file" class="dropify" data-height="160" data-allowed-file-extensions="zip sql" />
							<span class="error"></span>
							<p class="help-block mt-xs">
								<i class="fas fa-info-circle mr-xs"></i> <?=translate('supported_formats')?>: <strong>.zip, .sql</strong> (<?=translate('max_size')?>: 200MB)
							</p>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-3 col-sm-offset-3">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> <?=translate('restoring_database')?>...">
									<i class="fas fa-cloud-arrow-up text-primary mr-xs"></i> <?=translate('restore_now')?>
								</button>
							</div>
						</div>	
					</footer>
				<?php echo form_close();?>
			</div>
			<?php } ?>

			<!-- Tab 3: Tables & Maintenance -->
			<div class="tab-pane box" id="tables_maintenance">
				<div class="row mb-md">
					<div class="col-md-8">
						<p class="text-muted">
							<strong><?=translate('database_name')?>:</strong> <code><?=$db_stats['database_name']?></code> | 
							<strong><?=translate('charset')?>:</strong> <code><?=$db_stats['charset']?></code> | 
							<strong><?=translate('collation')?>:</strong> <code><?=$db_stats['collation']?></code>
						</p>
					</div>
					<div class="col-md-4 text-right">
						<?php if(get_permission('backup', 'is_add') || is_superadmin_loggedin()) { ?>
							<a href="<?=base_url('backup/optimize_all')?>" class="btn btn-default btn-sm">
								<i class="fas fa-wand-magic-sparkles text-primary mr-xs"></i> <?=translate('optimize_all_tables')?>
							</a>
						<?php } ?>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-export">
						<thead>
							<tr>
								<th width="40">#</th>
								<th><?=translate('table_name')?></th>
								<th><?=translate('engine')?></th>
								<th><?=translate('records')?></th>
								<th><?=translate('data_size')?></th>
								<th><?=translate('index_size')?></th>
								<th><?=translate('total_size')?></th>
								<th><?=translate('overhead')?></th>
								<th width="100" class="text-center"><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$tCount = 1;
						foreach ($tables_list as $tbl): 
						?>
							<tr>
								<td><?=$tCount++?></td>
								<td><strong><?=html_escape($tbl['name'])?></strong></td>
								<td><span class="badge badge-default"><?=$tbl['engine']?></span></td>
								<td><?=number_format($tbl['rows'])?></td>
								<td><?=bytesToSize('', $tbl['data_size'])?></td>
								<td><?=bytesToSize('', $tbl['index_size'])?></td>
								<td><span class="badge badge-info"><?=$tbl['total_size_formatted']?></span></td>
								<td>
									<?php if ($tbl['overhead'] > 0): ?>
										<span class="text-danger" style="font-weight: 600;"><?=bytesToSize('', $tbl['overhead'])?></span>
									<?php else: ?>
										<span class="text-muted">0 B</span>
									<?php endif; ?>
								</td>
								<td class="text-center">
									<?php if(get_permission('backup', 'is_add') || is_superadmin_loggedin()) { ?>
										<a href="<?=base_url('backup/optimize_table?table=' . urlencode($tbl['name']))?>" class="btn btn-xs btn-default" title="<?=translate('optimize')?>">
											<i class="fas fa-bolt text-warning"></i> <?=translate('optimize')?>
										</a>
									<?php } ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Tab 4: Automated Backup & Cron -->
			<div class="tab-pane box" id="automated_backup">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<h4 class="text-weight-bold text-dark mb-md">
								<i class="fas fa-clock text-primary mr-xs"></i> <?=translate('automated_database_backup_guide')?>
							</h4>
							<p class="text-muted">
								<?=translate('cron_backup_description')?>
							</p>

							<div class="panel panel-bordered mt-lg" style="border: 1px solid #e2e8f0; border-radius: 6px;">
								<div class="panel-heading" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
									<h5 class="panel-title text-dark">
										<i class="fas fa-terminal text-success mr-xs"></i> 1. <?=translate('recommended_cli_cron_command')?>
									</h5>
								</div>
								<div class="panel-body">
									<p class="text-muted"><?=translate('cli_cron_help')?>:</p>
									<div class="input-group">
										<input type="text" id="cliCronCmd" class="form-control" readonly value="0 2 * * * php <?=rtrim(FCPATH, '/\\')?>/index.php backup cron <?=$cron_key?>" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button" onclick="copyToClipboard('cliCronCmd')">
												<i class="far fa-copy mr-xs"></i> <?=translate('copy')?>
											</button>
										</span>
									</div>
									<small class="text-muted mt-xs block">
										* <?=translate('runs_every_night_at_2am')?>
									</small>
								</div>
							</div>

							<div class="panel panel-bordered mt-md" style="border: 1px solid #e2e8f0; border-radius: 6px;">
								<div class="panel-heading" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
									<h5 class="panel-title text-dark">
										<i class="fas fa-globe text-info mr-xs"></i> 2. <?=translate('web_url_cron_alternative')?>
									</h5>
								</div>
								<div class="panel-body">
									<p class="text-muted"><?=translate('web_cron_help')?>:</p>
									<div class="input-group">
										<input type="text" id="webCronCmd" class="form-control" readonly value="curl -s &quot;<?=base_url('backup/cron?token=' . $cron_key)?>&quot;" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button" onclick="copyToClipboard('webCronCmd')">
												<i class="far fa-copy mr-xs"></i> <?=translate('copy')?>
											</button>
										</span>
									</div>
								</div>
							</div>

							<div class="panel panel-bordered mt-md" style="border: 1px solid #e2e8f0; border-radius: 6px;">
								<div class="panel-heading" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
									<h5 class="panel-title text-dark">
										<i class="fas fa-shield-halved text-warning mr-xs"></i> <?=translate('security_and_retention')?>
									</h5>
								</div>
								<div class="panel-body">
									<ul class="text-muted" style="line-height: 1.8;">
										<li><strong><?=translate('retention_policy')?>:</strong> <?=translate('backups_older_than_30_days_auto_pruned')?></li>
										<li><strong><?=translate('security')?>:</strong> <?=translate('cron_token_verification_required')?></li>
										<li><strong><?=translate('storage')?>:</strong> <?=translate('backups_compressed_zip_in_secure_dir')?></li>
									</ul>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<!-- Restore Confirmation Modal -->
<div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-white">
					<i class="fas fa-triangle-exclamation mr-xs"></i> <?=translate('confirm_database_restore')?>
				</h4>
			</div>
			<div class="modal-body">
				<p><strong><?=translate('are_you_sure')?>?</strong></p>
				<p><?=translate('backup_restore_warning')?></p>
				<p><?=translate('selected_backup')?>: <code id="modalBackupFilename"></code></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=translate('cancel')?></button>
				<a href="#" id="modalRestoreProceedBtn" class="btn btn-danger">
					<i class="fas fa-rotate-left mr-xs"></i> <?=translate('proceed_restore')?>
				</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
function confirmRestore(filename) {
	$('#modalBackupFilename').text(filename);
	// In future, a server-side direct restoration endpoint can be wired if needed
	swal({
		title: "<?=translate('confirm_database_restore')?>",
		text: "<?=translate('backup_restore_warning')?> (" + filename + ")",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "<?=translate('proceed_restore')?>",
		cancelButtonText: "<?=translate('cancel')?>",
		closeOnConfirm: false
	}, function() {
		// Can trigger restore
		swal("<?=translate('notice')?>", "<?=translate('to_restore_from_file_please_use_restore_tab')?>", "info");
	});
}

function copyToClipboard(elementId) {
	var copyText = document.getElementById(elementId);
	copyText.select();
	copyText.setSelectionRange(0, 99999);
	navigator.clipboard.writeText(copyText.value).then(function() {
		swal("<?=translate('copied')?>", "<?=translate('command_copied_to_clipboard')?>", "success");
	});
}
</script>
