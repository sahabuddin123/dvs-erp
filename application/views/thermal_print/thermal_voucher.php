<?php
$width = (isset($settings->printer_width) && $settings->printer_width == '58mm') ? '58mm' : '80mm';
$type_label = (isset($voucher['type']) && $voucher['type'] == 'expense') ? 'ব্যয় ভাউচার / EXPENSE VOUCHER' : 'জমা ভাউচার / DEPOSIT VOUCHER';
$branch_id = isset($voucher['branch_id']) ? $voucher['branch_id'] : $this->application_model->get_branch_id();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Thermal Voucher - #<?=(isset($voucher['voucher_no']) ? $voucher['voucher_no'] : $voucher['id'])?></title>
	<style type="text/css">
		@page {
			size: <?=$width?> auto;
			margin: 0mm !important;
		}
		@media print {
			html, body {
				width: <?=$width?> !important;
				margin: 0 auto !important;
				padding: 0 !important;
				background: #ffffff !important;
			}
			.no-print {
				display: none !important;
			}
		}
		body {
			width: <?=$width?>;
			max-width: <?=$width?>;
			margin: 0 auto;
			padding: 4mm 3mm;
			background: #ffffff;
			font-family: 'Courier New', Courier, monospace, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			font-size: 11px;
			line-height: 1.35;
			color: #000000;
			-webkit-print-color-adjust: exact;
		}
		.text-center { text-align: center; }
		.text-right { text-align: right; }
		.text-left { text-align: left; }
		.text-bold { font-weight: 700; }
		.dashed-divider {
			border-top: 1px dashed #000000;
			margin: 5px 0;
		}
		.double-divider {
			border-top: 2px solid #000000;
			margin: 5px 0;
		}
	</style>
</head>
<body <?=(isset($settings->auto_print) && $settings->auto_print == 1) ? 'onload="window.print()"' : ''?>>
	<div class="thermal-receipt">
		<!-- Header -->
		<div class="text-center">
			<?php if (isset($settings->show_logo) && $settings->show_logo == 1): ?>
				<img src="<?=$this->application_model->getBranchImage($branch_id, 'printing-logo')?>" style="max-height: 40px; max-width: 150px; object-fit: contain; filter: grayscale(100%); margin-bottom: 3px;" alt="Logo" />
			<?php endif; ?>
			<div class="text-bold" style="font-size: 13px; text-transform: uppercase;">
				<?=(!empty($settings->header_title) ? html_escape($settings->header_title) : 'ড্রিম ভ্যালি স্কুল')?>
			</div>
			<?php if (!empty($settings->header_subtitle)): ?>
				<div style="font-size: 9.5px;"><?=html_escape($settings->header_subtitle)?></div>
			<?php endif; ?>
			<div style="font-size: 9px; margin-top: 2px;">
				<?=html_escape($settings->address)?>
			</div>
		</div>

		<div class="double-divider"></div>

		<div class="text-center text-bold" style="font-size: 11px;">
			** <?=$type_label?> **
		</div>

		<div class="dashed-divider"></div>

		<table style="width: 100%; font-size: 10.5px;">
			<tr>
				<td class="text-left"><strong>ভাউচার নং:</strong> #<?=(isset($voucher['voucher_no']) ? $voucher['voucher_no'] : $voucher['id'])?></td>
				<td class="text-right"><strong>তারিখ:</strong> <?=_d($voucher['date'])?></td>
			</tr>
			<tr>
				<td colspan="2" class="text-left"><strong>খাত (Head):</strong> <?=get_type_name_by_id('voucher_head', $voucher['voucher_head_id'])?></td>
			</tr>
			<tr>
				<td colspan="2" class="text-left"><strong>অ্যাকাউন্ট:</strong> <?=get_type_name_by_id('accounts', $voucher['account_id'])?></td>
			</tr>
			<?php if (!empty($voucher['pay_by'])): ?>
			<tr>
				<td colspan="2" class="text-left"><strong>পরিশোধকারী:</strong> <?=html_escape($voucher['pay_by'])?></td>
			</tr>
			<?php endif; ?>
			<?php if (!empty($voucher['received_by'])): ?>
			<tr>
				<td colspan="2" class="text-left"><strong>গ্রহীতা:</strong> <?=html_escape($voucher['received_by'])?></td>
			</tr>
			<?php endif; ?>
			<?php if (!empty($voucher['ref'])): ?>
			<tr>
				<td colspan="2" class="text-left"><strong>রেফারেন্স:</strong> <?=html_escape($voucher['ref'])?></td>
			</tr>
			<?php endif; ?>
		</table>

		<div class="dashed-divider"></div>

		<table style="width: 100%; font-size: 11.5px;">
			<tr class="text-bold" style="font-size: 13px;">
				<td class="text-left">মোট পরিমাণ (Amount):</td>
				<td class="text-right"><?=currencyFormat($voucher['amount'])?></td>
			</tr>
			<tr>
				<td class="text-left">পরিশোধের মাধ্যম:</td>
				<td class="text-right text-bold"><?=html_escape($voucher['pay_via'])?></td>
			</tr>
		</table>

		<?php if (!empty($voucher['description'])): ?>
			<div class="dashed-divider"></div>
			<div style="font-size: 10px; color: #333;">
				<strong>বিবরণ:</strong> <?=nl2br(html_escape($voucher['description']))?>
			</div>
		<?php endif; ?>

		<div class="double-divider"></div>

		<table style="width: 100%; margin-top: 15px; font-size: 9.5px;">
			<tr>
				<td style="width: 50%; text-align: center;">
					-----------------------<br/>
					গ্রহীতার স্বাক্ষর
				</td>
				<td style="width: 50%; text-align: center;">
					-----------------------<br/>
					কর্তৃপক্ষের স্বাক্ষর
				</td>
			</tr>
		</table>

		<div class="text-center" style="font-size: 8px; color: #555; margin-top: 10px;">
			প্রিন্ট সময়: <?=date('d/m/Y h:i A')?>
		</div>
	</div>
</body>
</html>

