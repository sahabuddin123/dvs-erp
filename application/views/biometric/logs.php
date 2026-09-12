<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading" style="display:flex; justify-content:space-between; align-items:center;">
                <h4 class="panel-title"><i class="fas fa-list-alt"></i> Biometric Attendance Punch Logs</h4>
                <form class="form-inline" method="get" action="<?=base_url('biometric/logs')?>">
                    <div class="form-group">
                        <label>Filter Date: &nbsp;</label>
                        <input type="date" name="date" class="form-control input-sm" value="<?=$selected_date?>" onchange="this.form.submit()" />
                    </div>
                </form>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-none" id="tableExport">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Device User ID</th>
                                <th>Punch Timestamp</th>
                                <th>User Type</th>
                                <th>Mapped ID</th>
                                <th>Punch Type</th>
                                <th>Device</th>
                                <th>Status</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs)) { $i = 1; foreach ($logs as $log) { ?>
                            <tr>
                                <td><?=$i++?></td>
                                <td><code><?=html_escape($log['device_user_id'])?></code></td>
                                <td><strong><?=date('d M Y, h:i:s A', strtotime($log['punch_time']))?></strong></td>
                                <td>
                                    <?php if ($log['user_type'] == 'student') { ?>
                                        <span class="label label-primary">Student</span>
                                    <?php } elseif ($log['user_type'] == 'staff') { ?>
                                        <span class="label label-success">Staff</span>
                                    <?php } else { ?>
                                        <span class="label label-default">Unknown</span>
                                    <?php } ?>
                                </td>
                                <td><?=$log['mapped_id'] ? '#' . $log['mapped_id'] : '—'?></td>
                                <td>
                                    <?php if ($log['punch_type'] == 1) { ?>
                                        <span class="badge badge-warning">Check-Out</span>
                                    <?php } else { ?>
                                        <span class="badge badge-info">Check-In</span>
                                    <?php } ?>
                                </td>
                                <td><?=html_escape($log['device_name'] ?: 'ADMS / CSV')?></td>
                                <td>
                                    <?php if ($log['status'] == 'synced') { ?>
                                        <span class="label label-success">Synced to Attendance</span>
                                    <?php } else { ?>
                                        <span class="label label-warning">Unmapped PIN</span>
                                    <?php } ?>
                                </td>
                                <td><?=html_escape($log['remark'])?></td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">No attendance logs found for <?=date('d M Y', strtotime($selected_date))?>.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
