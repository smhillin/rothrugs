<?php
class ModelShippingDynamicDropshipping extends Model {
	public function getQuote($address) {
		$this->language->load('shipping/dynamic_dropshipping');
		//echo("<pre>");print_r($this->cart->getProducts());die('</pre>');
		$DropshipChargeTotal  = 0;
		$supDelCharge = array();
		$this->load->model('pomgmt/po');
		
		foreach ($this->cart->getProducts() as $cartItem) {
			if ($cartItem['supplier_id']) {
				$supplierInfo = $this->model_pomgmt_po->getSupplier($cartItem['supplier_id']);
				if (!isset($supDelCharge[$cartItem['supplier_id']])) {
					$supDelCharge[$cartItem['supplier_id']] = $supplierInfo['dropship_fee'];
					$DropshipChargeTotal +=  $supplierInfo['dropship_fee'];
					$DropshipChargeTotal += ($cartItem['quantity']-1) * $supplierInfo['itemdel_fee'];
				}
				else {
					$DropshipChargeTotal += ($cartItem['quantity']) * $supplierInfo['itemdel_fee'];
				}
			}
		}
		
		$method_data = array();
		$quote_data = array();
		
		$quote_data['dynamic_dropshipping'] = array(
			'code'         => 'dynamic_dropshipping.dynamic_dropshipping',
			'title'        => $this->language->get('text_description'),
			'cost'         => $DropshipChargeTotal,
			'tax_class_id' => $this->config->get('flat_tax_class_id'),
			'text'         => $this->currency->format($DropshipChargeTotal)
		);
		
		$method_data = array(
			'code'       => 'dynamic_dropshipping',
			'title'      => $this->language->get('text_title'),
			'quote'      => $quote_data,
			'sort_order' => $this->config->get('dynamic_dropshipping_sort_order'),
			'error'      => false
		);
			
		return $method_data;
	}

}
?>