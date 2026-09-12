<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading" style="display:flex; justify-content:space-between; align-items:center;">
                <h4 class="panel-title"><i class="fas fa-users-cog"></i> Biometric Device User ID Mapping</h4>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addMapModal">
                        <i class="fas fa-plus"></i> Add User Mapping
                    </button>
                    <button type="button" class="btn btn-success btn-sm" id="btnAutoMap">
                        <i class="fas fa-magic"></i> Auto-Map All Users
                    </button>
                </div>
            </header>
            <div class="panel-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>How Mapping Works:</strong> Each person enrolled on the ZKTeco biometric machine has a <strong>Device User ID (PIN/Card Number)</strong>. This screen maps that PIN to a student's enrollment record or staff member. You can use <strong>Auto-Map All Users</strong> to automatically map all students by their <strong>Register No / Roll</strong> and staff by their <strong>Staff ID</strong> with a single click!
                </div>

                <div class="table-responsive mt-md">
                    <table class="table table-bordered table-striped table-hover mb-none" id="tableExport">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Device User ID (PIN)</th>
                                <th>User Type</th>
                                <th>Mapped Person Name</th>
                                <th>Code / Reg No</th>
                                <th>Class & Section / Role</th>
                                <th>Mapped On</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($user_maps)) { $i = 1; foreach ($user_maps as $map) { ?>
                            <tr>
                                <td><?=$i++?></td>
                                <td><code><?=html_escape($map['device_user_id'])?></code></td>
                                <td>
                                    <?php if ($map['user_type'] == 'student') { ?>
                                        <span class="label label-primary">Student</span>
                                    <?php } else { ?>
                                        <span class="label label-success">Staff</span>
                                    <?php } ?>
                                </td>
                                <td><strong><?=html_escape($map['full_name'] ?: 'N/A')?></strong></td>
                                <td><?=html_escape($map['code_no'] ?: 'N/A')?></td>
                                <td>
                                    <?=html_escape($map['user_type'] == 'student' ? ($map['class_name'] . ' (' . $map['section_name'] . ')') : $map['role_name'])?>
                                </td>
                                <td><?=date('d M Y', strtotime($map['created_at']))?></td>
                                <td class="text-center">
                                    <a href="<?=base_url('biometric/delete_mapping/' . $map['id'])?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete this mapping?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    No mappings found. Click <strong>"Auto-Map All Users"</strong> above to generate mappings automatically.
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal Add Mapping -->
<div class="modal fade" id="addMapModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formAddMap">
            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fas fa-link"></i> Add User Mapping</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-md">
                        <label>Device User ID / PIN on ZKTeco Machine <span class="required">*</span></label>
                        <input type="text" class="form-control" name="device_user_id" placeholder="e.g. 101 or 1002" required />
                    </div>

                    <div class="form-group mb-md">
                        <label>User Type <span class="required">*</span></label>
                        <select name="user_type" id="userTypeSelect" class="form-control" required>
                            <option value="student">Student</option>
                            <option value="staff">Staff / Teacher</option>
                        </select>
                    </div>

                    <div class="form-group mb-md">
                        <label>Target User (Enrollment ID / Staff ID) <span class="required">*</span></label>
                        <input type="number" class="form-control" name="user_id" placeholder="Enter Enroll ID for student or Staff ID for staff" required />
                        <small class="text-muted">Enter student's Enrollment ID or Staff member's numeric ID.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Mapping</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#btnAutoMap').on('click', function() {
        if (confirm('Auto-map all students (by Register No) and all staff (by Staff ID) for biometric attendance?')) {
            var btn = $(this);
            btn.button('loading');
            $.ajax({
                url: '<?=base_url('biometric/auto_map')?>',
                type: 'POST',
                data: {
                    'mode': 'register_no',
                    '<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash()?>'
                },
                dataType: 'json',
                success: function(res) {
                    btn.button('reset');
                    alert(res.message);
                    location.reload();
                },
                error: function() {
                    btn.button('reset');
                    alert('Error connecting to server.');
                }
            });
        }
    });

    $('#formAddMap').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?=base_url('biometric/save_mapping')?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('Error: ' + JSON.stringify(res.error));
                }
            }
        });
    });
});
</script>
