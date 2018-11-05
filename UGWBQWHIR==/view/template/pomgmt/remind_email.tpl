<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>
<div style="page-break-after: always;">
Dear Mart Balderson,<BR /><BR />
Today's date: 2012/10/22<br/>
PO date: 2012-10-21 13:08:35<br/>
PO Number: 10212012-002-00034<br/>
<br/>
<table>
    <?php 
	$i=1;
	foreach ($order['product'] as $product) { ?>
    <tr>
      <td><?php echo "(".$i.") ". $product['name']; ?>
        <?php foreach ($product['option'] as $option) { ?>
        <br />
        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
        <?php } ?></td>
    </tr>
    <?php 
	$i++;
	} ?>
</table>
</br><br/>
Ramesh Krishnamoorthy<br />dsdf<br />Vine Street Chambers - 16 Vine Street,<br />Vine Street Chambers - 16 Vine Street,<br />Jersey admin<br />Ceredigion<br />United Kingdom<br />Telephone:448001123614
<br/>
<br/>
The above order was passed to you on 2012-10-21 13:08:35, and we had
informed the customer that the item would leave our warehouse
in 3-5 business days from 2012-10-21 13:08:35.
<br/>
<br/>
We have not received tracking for you for this order and/or this 
customer is dissatisfied and has contacted us regarding the missing item. 
Please supply us with the tracking number as soon as possible.
<br/>
<br/>
We await your response. Thanks in advance for your prompt
attention to this matter
<br/><br/><br/>
Amy Strycula, CatsPlay
<br/>
<br/>
<br/>
</div>
</body>
</html>