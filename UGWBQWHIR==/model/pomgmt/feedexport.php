<?php
class ModelPomgmtFeedExport extends Model {

	public function getOrders($data = array()) {
		$sql = "SELECT p.order_id,o.date_added,shipping_firstname,shipping_lastname,c.email,c.telephone,shipping_address_1,
shipping_address_2,shipping_city,shipping_postcode,shipping_country,shipping_zone,
pd.name,pro.model,pop.quantity,pop.price,pop.quantity*pop.price
FROM purchase_order p
INNER JOIN `order` o ON o.order_id=p.sales_order_id
INNER JOIN customer c ON c.customer_id = o.customer_id
INNER JOIN purchase_order_product pop ON p.order_id=pop.order_id
INNER JOIN product pro ON pro.product_id=pop.product_id
INNER JOIN product_description pd ON pro.product_id = pd.product_id";
		
		
			$query = $this->db->query($sql);

			$order_data = $query->rows;
		
			return $order_data;		
	}
		
}
?>