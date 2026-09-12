<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading" style="display:flex; justify-content:space-between; align-items:center;">
                <h4 class="panel-title"><i class="fas fa-fingerprint"></i> ZKTeco Biometric Attendance Management</h4>
                <div>
                    <a href="<?=base_url('biometric/sync')?>" class="btn btn-primary btn-sm" onclick="return confirm('Do you want to pull attendance from all active ZKTeco devices now?');">
                        <i class="fas fa-sync-alt"></i> Sync All Devices
                    </a>
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#csvModal">
                        <i class="fas fa-file-csv"></i> Import USB Logs (CSV)
                    </button>
                    <a href="<?=base_url('biometric/devices')?>" class="btn btn-default btn-sm">
                        <i class="fas fa-plus"></i> Manage Devices
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <!-- Status Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="panel panel-featured panel-featured-primary">
                            <div class="panel-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-primary"><i class="fas fa-hdd"></i></div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Total Devices</h4>
                                            <div class="info"><strong class="amount"><?=count($devices)?></strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-featured panel-featured-success">
                            <div class="panel-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-success"><i class="fas fa-user-check"></i></div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Today's Biometric Punches</h4>
                                            <div class="info"><strong class="amount"><?=count($recent_logs)?></strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-featured panel-featured-info">
                            <div class="panel-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-info"><i class="fas fa-id-card"></i></div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">User Mapping</h4>
                                            <div class="info"><a href="<?=base_url('biometric/mapping')?>" class="btn btn-xs btn-default mt-xs">Manage Mappings</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-featured panel-featured-warning">
                            <div class="panel-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-warning"><i class="fas fa-satellite-dish"></i></div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">ADMS Push URL</h4>
                                            <div class="info"><small style="word-break:break-all;"><?=base_url('biometric/adms')?></small></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Devices Table -->
                <h4 class="mt-lg mb-md"><i class="fas fa-network-wired"></i> Configured Biometric Devices</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-none">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Device Name</th>
                                <th>IP Address</th>
                                <th>Port</th>
                                <th>Type</th>
                                <th>Branch</th>
                                <th>Status</th>
                                <th>Last Synced</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($devices)) { $i = 1; foreach ($devices as $row) { ?>
                            <tr>
                                <td><?=$i++?></td>
                                <td><strong><?=html_escape($row['name'])?></strong></td>
                                <td><code><?=html_escape($row['ip_address'])?></code></td>
                                <td><?=html_escape($row['port'])?></td>
                                <td><span class="label label-info"><?=ucfirst($row['device_type'])?></span></td>
                                <td><?=html_escape($row['branch_name'] ?? 'Default Branch')?></td>
                                <td>
                                    <?php if ($row['status']) { ?>
                                        <span class="label label-success">Active</span>
                                    <?php } else { ?>
                                        <span class="label label-danger">Inactive</span>
                                    <?php } ?>
                                </td>
                                <td><?=$row['last_sync'] ? date('d M Y, h:i A', strtotime($row['last_sync'])) : 'Never'?></td>
                                <td class="text-center">
                                    <button class="btn btn-default btn-xs btn-test-conn" data-id="<?=$row['id']?>" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Testing...">
                                        <i class="fas fa-plug"></i> Test Connection
                                    </button>
                                    <a href="<?=base_url('biometric/sync/' . $row['id'])?>" class="btn btn-primary btn-xs">
                                        <i class="fas fa-sync"></i> Pull Attendance
                                    </a>
                                    <a href="<?=base_url('biometric/device_edit/' . $row['id'])?>" class="btn btn-default btn-xs">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="9" class="text-center">
                                    No ZKTeco biometric devices added yet. <a href="<?=base_url('biometric/devices')?>">Click here to add your first device</a>.
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Recent Punches -->
                <div class="row mt-lg">
                    <div class="col-md-12">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <h4 class="mb-md"><i class="fas fa-clock"></i> Recent Biometric Punches Today</h4>
                            <a href="<?=base_url('biometric/logs')?>" class="btn btn-default btn-xs">View All Logs</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Device User ID</th>
                                        <th>Punch Time</th>
                                        <th>User Type</th>
                                        <th>Device</th>
                                        <th>Sync Status</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recent_logs)) { foreach ($recent_logs as $log) { ?>
                                    <tr>
                                        <td><code><?=html_escape($log['device_user_id'])?></code></td>
                                        <td><?=date('d M Y, h:i:s A', strtotime($log['punch_time']))?></td>
                                        <td><span class="label label-default"><?=ucfirst($log['user_type'])?></span></td>
                                        <td><?=html_escape($log['device_name'] ?: 'ADMS / Direct')?></td>
                                        <td>
                                            <?php if ($log['status'] == 'synced') { ?>
                                                <span class="label label-success">Synced</span>
                                            <?php } else { ?>
                                                <span class="label label-warning">Unmapped</span>
                                            <?php } ?>
                                        </td>
                                        <td><?=html_escape($log['remark'])?></td>
                                    </tr>
                                    <?php } } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No punch logs recorded today yet.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Import CSV -->
<div class="modal fade" id="csvModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?=base_url('biometric/import_csv')?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fas fa-file-csv"></i> Import Attendance From USB/CSV</h4>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Upload the log file exported from ZKTeco device USB stick (CSV/TXT format: <code>UserID, YYYY-MM-DD HH:MM:SS</code>).</p>
                    <div class="form-group">
                        <label>Select CSV/DAT Log File</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv,.txt,.dat" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Process Logs</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Test Connection Result -->
<div class="modal fade" id="testResultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fas fa-info-circle"></i> Connection Test Result</h4>
            </div>
            <div class="modal-body" id="testResultContent">
                <div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i> Connecting to device...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.btn-test-conn').on('click', function() {
        var btn = $(this);
        var id = btn.data('id');
        btn.button('loading');
        $('#testResultContent').html('<div class="text-center p-md"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Pinging ZKTeco device at port 4370...</div>');
        $('#testResultModal').modal('show');

        $.ajax({
            url: '<?=base_url('biometric/test_connection/')?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                btn.button('reset');
                if (res.status === 'success') {
                    var html = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' + res.message + '</div>';
                    html += '<table class="table table-bordered">';
                    html += '<tr><th>Device Name</th><td>' + (res.data.name || 'ZKTeco Terminal') + '</td></tr>';
                    html += '<tr><th>IP & Port</th><td>' + res.data.ip + ':' + res.data.port + '</td></tr>';
                    html += '<tr><th>Serial Number</th><td><code>' + (res.data.serial || 'N/A') + '</code></td></tr>';
                    html += '<tr><th>Firmware Version</th><td>' + (res.data.version || 'N/A') + '</td></tr>';
                    html += '</table>';
                    $('#testResultContent').html(html);
                } else {
                    $('#testResultContent').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ' + res.message + '</div>');
                }
            },
            error: function() {
                btn.button('reset');
                $('#testResultContent').html('<div class="alert alert-danger"><i class="fas fa-times-circle"></i> Server communication error.</div>');
            }
        });
    });
});
</script>
