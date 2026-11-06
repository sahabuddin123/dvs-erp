<?php
$width = (isset($settings->printer_width) && $settings->printer_width == '58mm') ? '58mm' : '80mm';
$basic = $thermal_data['basic'];
$items = $thermal_data['items'];
$total_amount = $thermal_data['total_amount'];
$total_discount = $thermal_data['total_discount'];
$total_fine = $thermal_data['total_fine'];
$total_paid = $thermal_data['total_paid'];
$total_balance = $thermal_data['total_balance'];
$invoice_no = 'INV-' . date('Ymd') . '-' . $basic['id'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Thermal Invoice - <?=$invoice_no?></title>
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
		table.receipt-table {
			width: 100%;
			border-collapse: collapse;
			margin: 4px 0;
		}
		table.receipt-table th {
			border-top: 1px dashed #000000;
			border-bottom: 1px dashed #000000;
			padding: 4px 0;
			font-size: 10.5px;
			text-align: left;
		}
		table.receipt-table td {
			padding: 3px 0;
			font-size: 10.5px;
			vertical-align: top;
		}
	</style>
</head>
<body <?=(isset($settings->auto_print) && $settings->auto_print == 1) ? 'onload="window.print()"' : ''?>>
	<div class="thermal-receipt">
		<!-- School Header -->
		<div class="text-center">
			<?php if (isset($settings->show_logo) && $settings->show_logo == 1): ?>
				<img src="<?=$this->application_model->getBranchImage($basic['branch_id'], 'printing-logo')?>" style="max-height: 40px; max-width: 150px; object-fit: contain; filter: grayscale(100%); margin-bottom: 3px;" alt="Logo" />
			<?php endif; ?>
			<div class="text-bold" style="font-size: 13px; text-transform: uppercase;">
				<?=(!empty($settings->header_title) ? html_escape($settings->header_title) : html_escape($basic['school_name']))?>
			</div>
			<?php if (!empty($settings->header_subtitle)): ?>
				<div style="font-size: 9.5px;"><?=html_escape($settings->header_subtitle)?></div>
			<?php endif; ?>
			<div style="font-size: 9px; margin-top: 2px;">
				<?=(!empty($settings->address) ? html_escape($settings->address) : html_escape($basic['school_address']))?>
			</div>
			<div style="font-size: 9px;">
				<?=(!empty($settings->contact_info) ? html_escape($settings->contact_info) : html_escape($basic['school_mobileno']))?>
			</div>
		</div>

		<div class="double-divider"></div>

		<!-- Title -->
		<div class="text-center text-bold" style="font-size: 11px; letter-spacing: 0.5px;">
			** ফি চালান ও ইনভয়েস / FEES INVOICE **
		</div>

		<div class="dashed-divider"></div>

		<!-- Student & Meta Info -->
		<table style="width: 100%; font-size: 10.5px;">
			<tr>
				<td class="text-left"><strong>ইনভয়েস নং:</strong> <?=$invoice_no?></td>
				<td class="text-right"><strong>তারিখ:</strong> <?=_d(date('Y-m-d'))?></td>
			</tr>
			<tr>
				<td class="text-left"><strong>আইডি নং:</strong> <?=html_escape($basic['register_no'])?></td>
				<td class="text-right"><strong>রোল:</strong> <?=html_escape($basic['roll'])?></td>
			</tr>
			<tr>
				<td colspan="2" class="text-left"><strong>শিক্ষার্থী:</strong> <?=html_escape($basic['first_name'] . ' ' . $basic['last_name'])?></td>
			</tr>
			<tr>
				<td colspan="2" class="text-left"><strong>শ্রেণি:</strong> <?=html_escape($basic['class_name'])?> (<?=html_escape($basic['section_name'])?>)</td>
			</tr>
		</table>

		<!-- Items Table -->
		<table class="receipt-table">
			<thead>
				<tr>
					<th width="48%">ফি শিরোনাম</th>
					<th width="24%" class="text-right">নির্ধারিত</th>
					<th width="28%" class="text-right">বকেয়া</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($items as $item): ?>
				<tr>
					<td><?=html_escape($item['title'])?></td>
					<td class="text-right"><?=number_format($item['amount'], 2)?></td>
					<td class="text-right text-bold"><?=number_format($item['balance'], 2)?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="dashed-divider"></div>

		<!-- Summary & Totals -->
		<table style="width: 100%; font-size: 11px;">
			<tr>
				<td class="text-left">মোট ফি (Total Amount):</td>
				<td class="text-right"><?=currencyFormat($total_amount)?></td>
			</tr>
			<?php if ($total_discount > 0): ?>
			<tr>
				<td class="text-left">মোট ছাড় (Discount):</td>
				<td class="text-right"><?=currencyFormat($total_discount)?></td>
			</tr>
			<?php endif; ?>
			<?php if ($total_paid > 0): ?>
			<tr>
				<td class="text-left">জমা হয়েছে (Paid):</td>
				<td class="text-right"><?=currencyFormat($total_paid)?></td>
			</tr>
			<?php endif; ?>
			<tr style="font-size: 12.5px;" class="text-bold">
				<td class="text-left">মোট বকেয়া (Due Balance):</td>
				<td class="text-right"><?=currencyFormat($total_balance)?></td>
			</tr>
		</table>

		<div class="double-divider"></div>

		<!-- Footer & Signatures -->
		<table style="width: 100%; margin-top: 15px; font-size: 9.5px;">
			<tr>
				<td style="width: 50%; text-align: center;">
					-----------------------<br/>
					হিসাবরক্ষক
				</td>
				<td style="width: 50%; text-align: center;">
					-----------------------<br/>
					অধ্যক্ষ / প্রধান শিক্ষক
				</td>
			</tr>
		</table>

		<?php if (!empty($settings->footer_text)): ?>
			<div class="text-center" style="font-size: 9px; margin-top: 10px; font-style: italic;">
				<?=nl2br(html_escape($settings->footer_text))?>
			</div>
		<?php endif; ?>

		<div class="text-center" style="font-size: 8px; color: #555; margin-top: 4px;">
			প্রিন্ট সময়: <?=date('d/m/Y h:i A')?>
		</div>
	</div>
</body>
</html>
