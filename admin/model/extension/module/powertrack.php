<?php

class ModelExtensionModulePowertrack extends Model {

    
    /**
     * 
     */
    public function install(){
        
        $this->install_default_settings();
        
        $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "order_history ADD COLUMN `powertrack_carrier`   VARCHAR(64) NULL");
        $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "order_history ADD COLUMN `powertrack_trackcode` VARCHAR(64) NULL");
    }

    /**
     * 
     */
    private function install_default_settings(){
        $defaultSettings = array(
            'powertrack_companies' => array(
                array('company_id' => 'aramex'          , 'company_name' => 'Aramex',           'company_url' => 'https://www.aramex.com/express/track-results-multiple.aspx?ShipmentNumber={tracking_code}',                                                                        ),
                array('company_id' => "bluedart"        , 'company_name' => "BlueDart",         'company_url' => "http://www.bluedart.com/servlet/RoutingServlet?action=awbquery&awb=awb&handler=tnt&numbers={tracking_code}",                                                       ),
                array('company_id' => "canadapost"      , 'company_name' => "Canada Post",      'company_url' => "http://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber={tracking_code}",                                                           ),
                array('company_id' => 'canpar'          , 'company_name' => 'Canpar Courier',   'company_url' => 'https://www.canpar.com/en/track/TrackingAction.do?locale=en&type=0&reference={tracking_code}',                                                                     ),
                array('company_id' => 'ups'             , 'company_name' => 'UPS',              'company_url' => 'http://wwwapps.ups.com/etracking/tracking.cgi?tracknums_displayed=25&TypeOfInquiryNumber=T&HTMLVersion=4.0&InquiryNumber={tracking_code}',                         ),
                array('company_id' => 'usps'            , 'company_name' => 'USPS',             'company_url' => 'https://tools.usps.com/go/TrackConfirmAction.action?tLabels={tracking_code}',                                                                                      ),
                array('company_id' => 'purolator'       , 'company_name' => 'Purolator',        'company_url' => 'http://shipnow.purolator.com/shiponline/track/purolatortrack.asp?pinno={tracking_code}',                                                                           ),
                array('company_id' => 'dhl'             , 'company_name' => 'DHL',              'company_url' => 'http://www.dhl-usa.com/content/us/en/express/tracking.shtml?brand=DHL&AWB={tracking_code}',                                                                        ),
                array('company_id' => 'econt'           , 'company_name' => 'Econt',            'company_url' => 'http://www.econt.com/tracking/?num={tracking_code}',                                                                                                               ),
                array('company_id' => 'citylinkexpress' , 'company_name' => 'City-Link Express','company_url' => 'http://[YOUR_OPENCART_FRONT_URL]/index.php?route=extension/module/powertrack_bridge&tracking_url=http://www.citylinkexpress.com/MY/ShipmentTrack.aspx&tracking_num={tracking_code}',),
            ),
            'powertrack_cfg_show_tracking_column_in_order_list' => true,
            'powertrack_cfg_log'                                => false,
            'powertrack_cfg_default_company_id'                 => '',
            'powertrack_cfg_goo_gl_api_key'                     => '',
            'powertrack_cfg_aftership_api_key'                  => '',
            'powertrack_cfg_show_popup_for_these_statuses'	    => array(),
        );

        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('powertrack', $defaultSettings);
    }
    
    
    /**
     * 
     */
    public function create_deliverydate_column(){
        
        $result = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order_history` LIKE 'powertrack_deliverydate'");
        
        $exists = ($result->num_rows) ? true:false;
        if(!$exists) {
            $query = $this->db->query("ALTER TABLE " . DB_PREFIX . "order_history ADD COLUMN `powertrack_deliverydate`   VARCHAR(64) NULL");
        }
        
        return $exists;
    }


    /**
     *
     */
    public function get_common_data_for_the_settings_page_controller() {

         /*
          * Aftership (inject selected couriers)
          */
        $data['powertrack_aftership_couriers'] = array();
        //hook_36aa3

        return $data;
    }


    
    /**
     * 
     */
    public function get_tracking_info_by_order_id($order_id){

        //$query = $this->db->query("SELECT oh.powertrack_trackcode, oh.powertrack_carrier FROM " . DB_PREFIX . "order_history oh WHERE oh.order_id = '" . (int)$order_id . "'");
        //select * to be able to select deliverydate if column exists..
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_history oh WHERE oh.order_id = '" . (int)$order_id . "'");
        return $query->rows;
    }

    

    /**
     * 
     */
    public function get_tracking_info_as_anchor_tags($odr){
        
        $this->load->model('sale/order');
        $order = $this->model_sale_order->getOrder($odr['order_id']);
        $rows  = $this->get_tracking_info_by_order_id($odr['order_id']);
        
        $this->load->helper('powertrack');
        
        $html_components = array();
        foreach($rows as $row){
            if(!empty($row['powertrack_trackcode'])){
                $args = array(
                    'carrierCode'       => $row['powertrack_carrier']  ,
                    'trackingCode'      => $row['powertrack_trackcode'], 
                    'shipping_postcode' => $order['shipping_postcode'] ,
                    'payment_postcode'  => $order['payment_postcode']  ,
                );
                
                $tracking_url_and_name = get_tracking_url_and_carrier_name($args, $this->config);

                /*
                 * remove me 
                 */
                //$html_component = '<a href="' . $tracking_url_and_name['tracking_url'] . '">'  . 
                //                                $tracking_url_and_name['carrier_name'] . " - " . 
                //                                $row["powertrack_trackcode"]           . '</a>';
                $data = array();
                $data['tracking_url']  = $tracking_url_and_name['tracking_url'];
                $data['carrier_name']  = $tracking_url_and_name['carrier_name'];
                $data['tracking_code'] = $row["powertrack_trackcode"];
                
                $html_component = $this->load->controller('extension/powertrack_sale/build_tracking_info_for_td_in_order_list', $data);

                
                //Add anything to html_component #deliverydate
                //hook_ea25b


                $html_components[] = $html_component;
            }
        }
        
        return $html_components;
    }


    /**
     * 
     */
    public function get_deliverydate_as_anchor_tags($odr){
        $this->load->model('sale/order');
        $order = $this->model_sale_order->getOrder($odr['order_id']);
        $rows  = $this->get_tracking_info_by_order_id($odr['order_id']);
        
        $html_components = array();
        foreach($rows as $row){
            if(!empty($row['powertrack_deliverydate'])){
                $html_component = '<span>' . $row['powertrack_deliverydate'] . '</span>';
                $html_components[] = $html_component;
            }
        }

        return implode("<br />", $html_components);
    }
    
    
}