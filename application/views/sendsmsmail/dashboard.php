<?php
$currency_symbol = $global_config['currency_symbol'] ?? '৳';
?>
<div class="row">
    <!-- Stat 1: Remaining Balance -->
    <div class="col-md-3 col-sm-6">
        <section class="panel panel-featured-left panel-featured-warning">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-warning" style="background-color: #f59e0b; color: #fff;">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">অবশিষ্ট ব্যালেন্স (Balance)</h4>
                            <div class="info">
                                <strong class="amount" id="live_sms_balance">
                                    ৳ <?=number_format($balanceData['balance'] ?? 0, 2)?>
                                </strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <span class="text-muted" style="font-size: 11px;">M-RAM গেটওয়ে</span>
                            <button type="button" class="btn btn-default btn-xs pull-right" onclick="refreshSmsBalance()" title="ব্যালেন্স রিফ্রেশ করুন">
                                <i class="fas fa-sync-alt" id="refresh_icon"></i> রিফ্রেশ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Stat 2: Total Sent -->
    <div class="col-md-3 col-sm-6">
        <section class="panel panel-featured-left panel-featured-primary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-primary">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">সর্বমোট প্রেরিত (Total Sent)</h4>
                            <div class="info">
                                <strong class="amount"><?=number_format($stats['total_sent'] ?? 0)?></strong>
                                <span class="text-muted" style="font-size: 11px;"> টি SMS</span>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <span class="text-muted" style="font-size: 11px;">মোট ট্রাই: <?=number_format($stats['total'] ?? 0)?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Stat 3: Today's Sent -->
    <div class="col-md-3 col-sm-6">
        <section class="panel panel-featured-left panel-featured-success">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-success">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">আজকের প্রেরিত (Today)</h4>
                            <div class="info">
                                <strong class="amount"><?=number_format($stats['today_sent'] ?? 0)?></strong>
                                <span class="text-muted" style="font-size: 11px;"> টি SMS</span>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <span class="text-muted" style="font-size: 11px;">চলতি মাস: <?=number_format($stats['month_sent'] ?? 0)?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Stat 4: Delivery Rate -->
    <div class="col-md-3 col-sm-6">
        <section class="panel panel-featured-left panel-featured-info">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-info">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">সাকসেস রেট (Success Rate)</h4>
                            <div class="info">
                                <strong class="amount"><?=$stats['success_rate'] ?? 100?>%</strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <span class="text-muted" style="font-size: 11px;">ব্যর্থ: <?=number_format($stats['failed'] ?? 0)?> টি</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <!-- Quick SMS Send Panel -->
    <div class="col-md-7">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-paper-plane"></i> কুইক এসএমএস পাঠান (Quick Send SMS)</h4>
            </header>
            <div class="panel-body">
                <form id="quickSmsForm" method="post">
                    <div class="form-group">
                        <label class="control-label"><strong>প্রাপক নির্বাচন (Select Recipient):</strong> <span class="required">*</span></label>
                        <div class="radio-custom radio-inline">
                            <input type="radio" id="target_custom" name="recipient_target" value="custom" checked onchange="toggleRecipientType()">
                            <label for="target_custom"><i class="fas fa-phone"></i> কাস্টম মোবাইল নম্বর</label>
                        </div>
                        <div class="radio-custom radio-inline">
                            <input type="radio" id="target_students" name="recipient_target" value="students" onchange="toggleRecipientType()">
                            <label for="target_students"><i class="fas fa-user-graduate"></i> সকল শিক্ষার্থী</label>
                        </div>
                        <div class="radio-custom radio-inline">
                            <input type="radio" id="target_parents" name="recipient_target" value="parents" onchange="toggleRecipientType()">
                            <label for="target_parents"><i class="fas fa-users"></i> সকল অভিভাবক</label>
                        </div>
                        <div class="radio-custom radio-inline">
                            <input type="radio" id="target_staff" name="recipient_target" value="staff" onchange="toggleRecipientType()">
                            <label for="target_staff"><i class="fas fa-chalkboard-teacher"></i> সকল শিক্ষক ও স্টাফ</label>
                        </div>
                    </div>

                    <div class="form-group" id="custom_numbers_group">
                        <label class="control-label"><strong>মোবাইল নম্বরসমূহ (Mobile Numbers):</strong> <span class="required">*</span></label>
                        <textarea class="form-control" name="custom_numbers" id="custom_numbers" rows="2" placeholder="নম্বর লিখুন (যেমন: 01712345678, 01812345679 কমা দিয়ে একাধিক নম্বর দেওয়া যাবে)"></textarea>
                        <small class="text-muted">কমা (,), স্পেস বা সেমিকোলন দিয়ে একাধিক নম্বর যুক্ত করতে পারেন।</small>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><strong>মেসেজ লিখুন (Message Content):</strong> <span class="required">*</span></label>
                        <textarea class="form-control" name="message" id="sms_message" rows="4" placeholder="আপনার এসএমএস বার্তাটি এখানে লিখুন..." required onkeyup="updateCharCount()"></textarea>
                        <div class="row mt-xs">
                            <div class="col-xs-6">
                                <span class="text-muted" style="font-size: 12px;">
                                    অক্ষর: <strong id="char_count">0</strong> | 
                                    এসএমএস পার্ট: <strong id="parts_count">1</strong> 
                                    (<span id="encoding_type">English Text</span>)
                                </span>
                            </div>
                            <div class="col-xs-6 text-right">
                                <span class="text-muted" style="font-size: 11px;">English: 160 chars/SMS, বাংলা: 70 chars/SMS</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary" id="btn_send_sms">
                            <i class="fas fa-paper-plane"></i> এসএমএস পাঠান (Send SMS)
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <!-- Gateway & Sender Details -->
    <div class="col-md-5">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-satellite-dish"></i> এসএমএস গেটওয়ে তথ্য (Gateway Details)</h4>
            </header>
            <div class="panel-body">
                <table class="table table-bordered table-striped" style="margin-bottom: 0;">
                    <tbody>
                        <tr>
                            <th width="40%">গেটওয়ে নাম (Gateway)</th>
                            <td><span class="badge" style="background:#2563eb; color:#fff;">M-RAM Technologies Ltd</span></td>
                        </tr>
                        <tr>
                            <th>Sender ID (প্রেরক আইডি)</th>
                            <td><strong class="text-primary"><?=getenv('SMS_MRAM_SENDER_ID') ?: '8809601017199'?></strong></td>
                        </tr>
                        <tr>
                            <th>API URL (এন্ডপয়েন্ট)</th>
                            <td><small style="word-break: break-all;"><?=getenv('SMS_MRAM_API_URL') ?: 'https://msg.mram.com.bd/smsapi'?></small></td>
                        </tr>
                        <tr>
                            <th>API Key</th>
                            <td>
                                <code><?=substr(getenv('SMS_MRAM_API_KEY') ?: 'C40002956aa1a646106c41.09766335', 0, 10)?>••••••••••••</code>
                            </td>
                        </tr>
                        <tr>
                            <th>বর্তমান স্ট্যাটাস</th>
                            <td>
                                <span class="label label-success"><i class="fas fa-check-circle"></i> Active & Connected</span>
                            </td>
                        </tr>
                        <tr>
                            <th>কনফিগারেশন সোর্স</th>
                            <td><code>.env</code> & <code>sms_credential</code> (Database)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<!-- SMS Logs Table -->
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="<?=base_url('sendsmsmail/dashboard')?>" class="btn btn-default btn-xs" title="রিলোড করুন">
                        <i class="fas fa-redo"></i> রিফ্রেশ
                    </a>
                </div>
                <h4 class="panel-title"><i class="fas fa-history"></i> এসএমএস প্রেরণের বিবরণ ও হিস্ট্রি (Delivery Logs)</h4>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-condensed mb-none table-export">
                        <thead>
                            <tr>
                                <th width="40">#</th>
                                <th width="140">তারিখ ও সময় (Date)</th>
                                <th width="130">প্রাপক (Recipient)</th>
                                <th width="110">প্রাপকের ধরন</th>
                                <th>মেসেজ (Message)</th>
                                <th width="80" class="text-center">SMS সংখ্যা</th>
                                <th width="90">গেটওয়ে</th>
                                <th width="90" class="text-center">স্ট্যাটাস</th>
                                <th>গেটওয়ে রেসপন্স</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs)): ?>
                                <?php $count = 1; foreach ($logs as $log): ?>
                                <tr>
                                    <td><?=$count++?></td>
                                    <td><?=date('d M Y, h:i A', strtotime($log['created_at']))?></td>
                                    <td><strong><?=html_escape($log['recipient'])?></strong></td>
                                    <td>
                                        <span class="label label-default">
                                            <?=ucfirst(html_escape($log['recipient_type'] ?? 'General'))?>
                                        </span>
                                    </td>
                                    <td style="max-width: 320px; word-break: break-word;">
                                        <?=nl2br(html_escape($log['message']))?>
                                    </td>
                                    <td class="text-center"><?=$log['sms_count'] ?? 1?></td>
                                    <td><?=html_escape(strtoupper($log['gateway'] ?? 'M-RAM'))?></td>
                                    <td class="text-center">
                                        <?php if ($log['status'] === 'sent'): ?>
                                            <span class="label label-success"><i class="fas fa-check"></i> Sent</span>
                                        <?php else: ?>
                                            <span class="label label-danger"><i class="fas fa-times"></i> Failed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted" style="word-break: break-all;"><?=html_escape($log['response'])?></small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted" style="padding: 24px;">
                                        <i class="fas fa-info-circle fa-2x mb-xs" style="display:block;"></i>
                                        কোনো প্রেরিত এসএমএস রেকর্ড পাওয়া যায়নি।
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<script type="text/javascript">
function toggleRecipientType() {
    var selected = $('input[name="recipient_target"]:checked').val();
    if (selected === 'custom') {
        $('#custom_numbers_group').slideDown();
    } else {
        $('#custom_numbers_group').slideUp();
    }
}

function updateCharCount() {
    var text = $('#sms_message').val();
    var length = text.length;
    var isUnicode = /[^ -]/.test(text);

    $('#char_count').text(length);

    if (isUnicode) {
        $('#encoding_type').text('বাংলা / Unicode').css('color', '#10b981');
        var parts = length <= 70 ? 1 : Math.ceil(length / 67);
        $('#parts_count').text(parts);
    } else {
        $('#encoding_type').text('English Text').css('color', '#3b82f6');
        var parts = length <= 160 ? 1 : Math.ceil(length / 153);
        $('#parts_count').text(parts);
    }
}

function refreshSmsBalance() {
    var $icon = $('#refresh_icon');
    $icon.addClass('fa-spin');
    $.ajax({
        url: "<?=base_url('sendsmsmail/get_balance_ajax')?>",
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            $icon.removeClass('fa-spin');
            if (res.status === 'success' && res.balance !== null) {
                $('#live_sms_balance').html('৳ ' + parseFloat(res.balance).toFixed(2));
                swal("ব্যালেন্স আপডেট", "বর্তমান ব্যালেন্স: ৳ " + parseFloat(res.balance).toFixed(2), "success");
            } else {
                swal("নোটিশ", res.raw || "ব্যালেন্স তথ্য পাওয়া যায়নি", "info");
            }
        },
        error: function() {
            $icon.removeClass('fa-spin');
            swal("এরর", "ব্যালেন্স রিফ্রেশ করা যায়নি।", "error");
        }
    });
}

$(document).ready(function() {
    $('#quickSmsForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#btn_send_sms');
        var originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> পাঠানো হচ্ছে...');

        $.ajax({
            url: "<?=base_url('sendsmsmail/send_quick_sms_ajax')?>",
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html(originalText);
                if (res.status === 'success') {
                    swal("সফল!", res.message, "success");
                    $('#sms_message').val('');
                    if ($('input[name="recipient_target"]:checked').val() === 'custom') {
                        $('#custom_numbers').val('');
                    }
                    updateCharCount();
                    // refresh balance & reload logs
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    swal("ব্যর্থ", res.message || "এসএমএস পাঠানো ব্যর্থ হয়েছে।", "error");
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalText);
                swal("এরর", "সার্ভার এরর দেখা দিয়েছে। আবার চেষ্টা করুন।", "error");
            }
        });
    });
});
</script>
