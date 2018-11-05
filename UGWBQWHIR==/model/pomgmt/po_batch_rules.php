<?php 
class ModelPomgmtPoBatchRules extends Model {
	public function get_supplier_order_product($supplier_id,$store_id){
	$query = $this->db->query("SELECT o.order_id,GROUP_CONCAT(op.order_product_id SEPARATOR ',') AS order_product_ids"
				." FROM `".DB_PREFIX."order` o "
				." INNER JOIN ".DB_PREFIX."order_product op ON (o.order_id = op.order_id) "
				." INNER JOIN ".DB_PREFIX."product p ON (op.product_id = p.product_id) "
				." LEFT JOIN " .DB_PREFIX."supplier s ON (p.supplier_id = s.supplier_id) "
				. " WHERE o.store_id = '".$store_id."' AND op.po_product_id = '0' "
				. " AND o.order_status_id = 1 "
				. " AND s.supplier_id = '".$supplier_id."'"
				." GROUP BY o.order_id "
				." ORDER BY o.order_id")->rows;
                return $query;
	}
}
?>