<?php

class ModelExtensionModulePowertrackLookup extends Model {

    /**
     * 
     */
	public function get_tracking_info_by_order_id($order_id){
	    $query = $this->db->query("SELECT oh.powertrack_trackcode, oh.powertrack_carrier, o.email FROM " . DB_PREFIX . "order_history oh JOIN `" . DB_PREFIX . "order` o ON oh.order_id = o.order_id WHERE oh.order_id = '" . (int)$order_id . "'");
	    return $query->rows;
	}
}