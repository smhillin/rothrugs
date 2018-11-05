<?php 
class ModelExtensionModulePowertrackDao extends Model {


	/**
     * 
     */
    public function get_orders_by_statuses($statuses){

        $st_join = join("' or order_status_id = '", $statuses);


        $sql = "SELECT order_id, shipping_postcode, payment_postcode FROM `" . DB_PREFIX . "order` 
        		WHERE `customer_id` = '" . (int)$this->customer->getId() . "' and (order_status_id =  '" . $st_join . "')  ORDER BY `order_id` DESC";

        if($this->config->get('powertrack_cfg_log')) $this->log->write('★ SQL: ' . $sql);

        $query = $this->db->query($sql);
        if(! $query->num_rows){
            if($this->config->get('powertrack_cfg_log')) $this->log->write("[powertrack] no shipped orders");
            return null;
        }
        return $query->rows;
    }

    
    
    /**
     *
     */
    public function edit_history($order_history_id, $tracking_code, $carrier_code) {
        $sql = "UPDATE " . DB_PREFIX . "order_history SET
               `powertrack_carrier` = '" . $this->db->escape($carrier_code) . "',
               `powertrack_trackcode` = '" . $this->db->escape($tracking_code) . "'
                WHERE `order_history_id` = '" . $order_history_id . "'";
    
        if($this->config->get('powertrack_cfg_log')) $this->log->write('[powertrack] SQL is: ' . $sql);
        $this->db->query($sql);
    }



    /**
     * 
     */
    public function update_deliverydate($delivery_date, $order_history_id){
        $sql = "UPDATE " . DB_PREFIX . "order_history SET
               `powertrack_deliverydate` = '" . $this->db->escape($delivery_date) . "' 
                WHERE `order_history_id` = '" . $order_history_id . "'";
        
        //if($this->config->get('powertrack_cfg_log')) $this->log->write('[powertrack] SQL is: ' . $sql);
        $this->db->query($sql);
    }
    
    
}