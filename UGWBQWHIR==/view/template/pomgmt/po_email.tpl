<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>
Dear ABC Natural Health Co,<BR /><BR />
Please find a purchase order from 'Your Store' with our reference PO-2014-00163. This reference should be used in all communication regarding this order.<br/>
<br/>
If there has been a price change for any product that is different from the prices listed on this Purchase Order Please notify us immediately.<br/>
<br/>
Can you please arrange for the shipping of the following products as per this purchase order to the address specified below.<br/>
<br/>
Can you please update us ASAP if any products are not available to ship with approximate ETA if available.<br/>
<br/>

<br/>
<table class="address">
    <tr class="heading">
      <td width="50%"><b>Shipping Address</b></td>
    </tr>
    <tr>
      <td>Ramesh Krishnamoorthy<br />Vine Street Chambers - 16 Vine Street,<br />Tambaram admin<br />Ceredigion<br />United Kingdom<br />Telephone:88623423423</td>
    </tr>
  </table>
  <table class="product">
    <tr class="heading">
      <td><b>Product</b></td>
      <td><b>Model</b></td>
	  <td><b>MPN</b></td>
      <td align="right"><b>Qty</b></td>
      <td align="right"><b>Unit Price</b></td>
      <td align="right"><b>Total</b></td>
    </tr>
    <?php foreach ($order['product'] as $product) { ?>
    <tr>
      <td><?php echo $product['name']; ?>
        <?php foreach ($product['option'] as $option) { ?>
        <br />
        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
        <?php } ?>
		<!-- <br/><?php echo $product['description']; ?> -->
		</td>
      <td><?php echo $product['model']; ?></td>
	  <td><?php echo $product['mpn']; ?></td>
      <td align="right"><?php echo $product['quantity']; ?></td>
      <td align="right"><?php echo $product['price']; ?></td>
      <td align="right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
    <tr>
      <td align="right" colspan="5"><b><?php echo $text_delivery_charge; ?>:</b></td>
      <td align="right"><?php echo $order['delivery_charge']; ?></td>
    </tr>
    <tr>
      <td align="right" colspan="5"><b><?php echo $text_vat; ?>:</b></td>
      <td align="right"><?php echo $order['vat']; ?></td>
    </tr>	
    <tr>
      <td align="right" colspan="5"><b><?php echo $text_total; ?>:</b></td>
      <td align="right">2.50</td>
    </tr>
  </table>
  <table class="comment">
    <tr>
      <td><b>Notes:</b></td>
    </tr>
    <tr>
      <td>Please group orders on a weekly basis from Monday to Sunday.<br />
<br />
Invoice need to done in the name of 'My Health store'<br />
<br />
</td>
    </tr>
  </table>
<br/>
<br/>
<br/>
When the order has shipped can you please;<br/>
<br/>
1. Email us with shipping Confirmation and any relevant Tracking Information stating the Purchase Order PO-2014-00163 as a reference.<br/>
2. Email us a Purchase Invoice which shows our Purchase Order, PO-2014-00163., as a reference which also includes all relevant shipping charges.
<br/><br/>
<?php if (true) { ?>You can view the order online <a href="http://localhost/oc/156/admin/index.php?route=pomgmt/po/update&order_id=163&auth_id=a942c8e69cc89e8fa99d21f9faaff9e9">here</a><br/><?php } ?><br/>
Thank you,<br/>

<br/>
Your Store Purchasing Department.<br/>
</body>
</html>