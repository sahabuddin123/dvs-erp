<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=html_escape($student['first_name'] . ' ' . $student['last_name'])?> - Student Profile</title>
    <link rel="stylesheet" href="<?=base_url('assets/vendor/bootstrap/css/bootstrap.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/vendor/font-awesome/css/all.min.css')?>">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
        }
        .profile-container {
            max-width: 850px;
            margin: 20px auto;
            background: #fff;
            padding: 35px 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        .header-title {
            text-align: center;
            border-bottom: 2px solid #2e353b;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-title h2 {
            margin: 5px 0;
            font-size: 24px;
            font-weight: 700;
            color: #1d2124;
            text-transform: uppercase;
        }
        .header-title p {
            margin: 0;
            font-size: 13px;
            color: #666;
        }
        .badge-title {
            background: #2e353b;
            color: #fff;
            display: inline-block;
            padding: 4px 18px;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 3px;
            margin-top: 8px;
        }
        .photo-box {
            width: 130px;
            height: 150px;
            border: 2px solid #ddd;
            padding: 4px;
            border-radius: 4px;
            background: #fafafa;
            display: block;
            margin: 0 auto;
            object-fit: cover;
        }
        .section-header {
            background: #f1f3f5;
            color: #212529;
            font-size: 14px;
            font-weight: 700;
            padding: 6px 12px;
            margin-top: 20px;
            margin-bottom: 12px;
            border-left: 4px solid #0088cc;
            text-transform: uppercase;
        }
        .table-info {
            width: 100%;
            margin-bottom: 0;
        }
        .table-info td {
            padding: 6px 10px;
            font-size: 13px;
            border-bottom: 1px solid #f0f0f0;
        }
        .table-info td.field-name {
            font-weight: 600;
            color: #555;
            width: 22%;
            background: #fafafa;
        }
        .table-info td.field-val {
            color: #111;
            width: 28%;
        }
        .sign-box {
            margin-top: 50px;
            padding-top: 10px;
            border-top: 1px dashed #777;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
        }
        @media print {
            body {
                background: #fff;
                margin: 0;
            }
            .profile-container {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
                padding: 15px 25px;
            }
            .no-print {
                display: none !important;
            }
            .section-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-title {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<div class="container no-print" style="max-width: 850px; margin-top: 15px;">
    <div class="pull-right">
        <button onclick="window.print();" class="btn btn-primary btn-sm"><i class="fas fa-print"></i> Print Profile</button>
        <button onclick="window.close();" class="btn btn-default btn-sm"><i class="fas fa-times"></i> Close</button>
    </div>
    <div class="clearfix"></div>
</div>

<div class="profile-container">
    <!-- Header -->
    <div class="header-title">
        <div class="row">
            <div class="col-xs-2 text-left">
                <img src="<?=$this->application_model->getBranchImage($student['branch_id'], 'printing-logo')?>" style="max-height: 80px; max-width: 110px;" alt="School Logo">
            </div>
            <div class="col-xs-10 text-center" style="padding-right: 80px;">
                <h2><?=get_type_name_by_id('branch', $student['branch_id'])?></h2>
                <p><?=$global_config['address'] ?? ''?> | Phone: <?=$global_config['mobileno'] ?? ''?> | Email: <?=$global_config['email'] ?? ''?></p>
                <div>
                    <span class="badge-title">Official Student Profile & Bio-Data</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Snapshot -->
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-xs-3 text-center">
            <img src="<?=get_image_url('student', $student['photo'])?>" class="photo-box" alt="Student Photo">
        </div>
        <div class="col-xs-9">
            <table class="table-info">
                <tr>
                    <td class="field-name">Full Name</td>
                    <td class="field-val"><strong><?=html_escape($student['first_name'] . ' ' . $student['last_name'])?></strong></td>
                    <td class="field-name">Register No</td>
                    <td class="field-val"><code><?=html_escape($student['register_no'])?></code></td>
                </tr>
                <tr>
                    <td class="field-name">Class & Section</td>
                    <td class="field-val"><?=$student['class_name'] . ' (' . $student['section_name'] . ')'?></td>
                    <td class="field-name">Roll No</td>
                    <td class="field-val"><?=html_escape($student['roll'] ?: 'N/A')?></td>
                </tr>
                <tr>
                    <td class="field-name">Admission Date</td>
                    <td class="field-val"><?=_d($student['admission_date'])?></td>
                    <td class="field-name">Academic Session</td>
                    <td class="field-val"><?=get_type_name_by_id('schoolyear', get_session_id(), 'school_year')?></td>
                </tr>
                <tr>
                    <td class="field-name">Category</td>
                    <td class="field-val"><?=html_escape($student['category_name'] ?: 'General')?></td>
                    <td class="field-name">Status</td>
                    <td class="field-val"><?=($student['active'] == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>')?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Personal Details -->
    <div class="section-header"><i class="fas fa-user"></i> Personal Information</div>
    <table class="table-info">
        <tr>
            <td class="field-name">Date of Birth</td>
            <td class="field-val"><?=_d($student['birthday'])?></td>
            <td class="field-name">Gender</td>
            <td class="field-val"><?=ucwords($student['gender'] ?: 'N/A')?></td>
        </tr>
        <tr>
            <td class="field-name">Blood Group</td>
            <td class="field-val"><?=html_escape($student['blood_group'] ?: 'N/A')?></td>
            <td class="field-name">Religion</td>
            <td class="field-val"><?=html_escape($student['religion'] ?: 'N/A')?></td>
        </tr>
        <tr>
            <td class="field-name">Mother Tongue</td>
            <td class="field-val"><?=html_escape($student['mother_tongue'] ?: 'N/A')?></td>
            <td class="field-name">Caste</td>
            <td class="field-val"><?=html_escape($student['caste'] ?: 'N/A')?></td>
        </tr>
        <tr>
            <td class="field-name">Mobile No</td>
            <td class="field-val"><?=html_escape($student['mobileno'] ?: 'N/A')?></td>
            <td class="field-name">Email</td>
            <td class="field-val"><?=html_escape($student['email'] ?: 'N/A')?></td>
        </tr>
        <tr>
            <td class="field-name">Present Address</td>
            <td class="field-val" colspan="3"><?=html_escape($student['current_address'] ?: 'N/A')?></td>
        </tr>
        <tr>
            <td class="field-name">Permanent Address</td>
            <td class="field-val" colspan="3"><?=html_escape($student['permanent_address'] ?: 'N/A')?></td>
        </tr>
    </table>

    <!-- Parent / Guardian Information -->
    <div class="section-header"><i class="fas fa-users"></i> Parents & Guardian Information</div>
    <table class="table-info">
        <tr>
            <td class="field-name">Father's Name</td>
            <td class="field-val"><?=html_escape($parent['father_name'] ?? 'N/A')?></td>
            <td class="field-name">Father Occupation</td>
            <td class="field-val"><?=html_escape($parent['father_profession'] ?? 'N/A')?></td>
        </tr>
        <tr>
            <td class="field-name">Mother's Name</td>
            <td class="field-val"><?=html_escape($parent['mother_name'] ?? 'N/A')?></td>
            <td class="field-name">Mother Occupation</td>
            <td class="field-val"><?=html_escape($parent['mother_profession'] ?? 'N/A')?></td>
        </tr>
        <tr>
            <td class="field-name">Guardian Name</td>
            <td class="field-val"><strong><?=html_escape($parent['name'] ?? 'N/A')?></strong></td>
            <td class="field-name">Relation</td>
            <td class="field-val"><?=html_escape($parent['relation'] ?? 'Guardian')?></td>
        </tr>
        <tr>
            <td class="field-name">Guardian Phone</td>
            <td class="field-val"><?=html_escape($parent['mobileno'] ?? 'N/A')?></td>
            <td class="field-name">Guardian Email</td>
            <td class="field-val"><?=html_escape($parent['email'] ?? 'N/A')?></td>
        </tr>
        <tr>
            <td class="field-name">Guardian Address</td>
            <td class="field-val" colspan="3"><?=html_escape($parent['address'] ?? 'N/A')?></td>
        </tr>
    </table>

    <!-- Previous School Details -->
    <?php 
    $prev = !empty($student['previous_details']) ? json_decode($student['previous_details'], true) : [];
    if (!empty($prev['school_name'])) { ?>
    <div class="section-header"><i class="fas fa-history"></i> Previous School History</div>
    <table class="table-info">
        <tr>
            <td class="field-name">Previous School</td>
            <td class="field-val"><?=html_escape($prev['school_name'] ?? 'N/A')?></td>
            <td class="field-name">Qualification</td>
            <td class="field-val"><?=html_escape($prev['qualification'] ?? 'N/A')?></td>
        </tr>
        <tr>
            <td class="field-name">Remarks</td>
            <td class="field-val" colspan="3"><?=html_escape($prev['remarks'] ?? 'N/A')?></td>
        </tr>
    </table>
    <?php } ?>

    <!-- Signature Row -->
    <div class="row" style="margin-top: 60px;">
        <div class="col-xs-4">
            <div class="sign-box">
                Student's Signature
            </div>
        </div>
        <div class="col-xs-4">
            <div class="sign-box">
                Guardian's Signature
            </div>
        </div>
        <div class="col-xs-4">
            <div class="sign-box">
                Principal / Head Teacher
            </div>
        </div>
    </div>

    <div class="text-center text-muted" style="margin-top: 25px; font-size: 11px;">
        Printed on: <?=date('d M Y, h:i A')?> | <?=get_type_name_by_id('branch', $student['branch_id'])?>
    </div>
</div>

</body>
</html>
