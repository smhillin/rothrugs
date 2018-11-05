<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $heading_title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>
<div style="page-break-after: always;">
	<h1><?php echo $heading_title; ?></h1>      
	<table class="product">
	  <tr class="heading">
		<?php if ($filter_orderwise) { ?>
			<td class="right"><?php echo $column_ponumber; ?></td>
			<td class="right"><?php echo $column_povalue; ?></td>
			<td class="right"><?php echo $column_purinvno; ?></td>
			<td class="right"><?php echo $column_purinvvalue; ?></td>
			<td class="right"><?php echo $column_salesorder; ?></td>
		<?php } else {?>
			<td class="left"><?php echo $column_store; ?></td>
			<td class="right"><?php echo $column_ordercount; ?></td>
			<td class="right"><?php echo $column_povalue; ?></td>
			<td class="right"><?php echo $column_purinvvalue; ?></td>
		<?php } ?>
	  </tr>
	  <?php 
	  if ($suppreporting) { ?>
	  <?php foreach ($suppreporting as $suppreport) { ?>
	  <tr>
		<?php if ($filter_orderwise) { ?>
			<td class="right"><?php echo $suppreport['order_id']; ?></td>
			<td class="right"><?php echo $suppreport['pur_value']; ?></td>
			<td class="right"><?php echo $suppreport['poinvoice_ref']; ?></td>
			<td class="right"><?php echo $suppreport['inv_value']; ?></td>
			<td class="right">
			<?php echo $suppreport['sales_order_id']; ?>
		<?php } else {?>
			<td class="left"><?php echo $suppreport['store_name']; ?></td>
			<td class="right"><?php echo $suppreport['ordercount']; ?></td>
			<td class="right"><?php echo $suppreport['pur_value']; ?></td>
			<td class="right"><?php echo $suppreport['inv_value']; ?></td>
		<?php } ?>
	  </tr>
	  <?php } ?>
	  <?php } else { ?>
	  <tr>
		<td class="center" colspan="<?php if ($filter_orderwise) echo '5'; else echo '4'; ?>"><?php echo $text_no_results; ?></td>
	  </tr>
	  <?php } ?>
	  <tr>
			<?php if ($filter_orderwise) { ?>
				<td class="right"><strong>Total</strong></td>
				<td class="right"><strong><?php echo $poTotal; ?></strong></td>
				<td class="right"></td>
				<td class="right"><strong><?php echo $poValueTotal; ?></strong></td>
				<td class="right"></td>
			<?php } else {?>
				<td class="right"><strong>Total</strong></td>
				<td class="right"></td>
				<td class="right"><strong><?php echo $poTotal; ?></strong></td>
				<td class="right"><strong><?php echo $poValueTotal; ?></strong></td>
			<?php } ?>
	  </tr>
	</table>
</div>