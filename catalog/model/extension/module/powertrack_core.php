<?php
class ModelExtensionModulePowertrackCore extends Model {

    /**
     * Minimum vqmod code
     */
    public function get_powertrack_info($args) {

        /*
         * Arguments checking
         */
        $tracking_code = $args['tracking_code'];
        $carrier_uuid  = $args['carrier_uuid'];
        $order_info    = $args['order_info'];

        /*
         * Initialize my variables
         */
        $company_url_and_name     = array();
        $powertrack_email_comment = "";

        /*
         * Honor default carrier setting
         */
        if ($this->config->get('powertrack_cfg_log')) $this->log->write('carrier: ' . $carrier_uuid . ' code: ' . $tracking_code);

        if (empty($carrier_uuid) && !empty($tracking_code)) {
            $powertrack_cfg_default_company_id = $this->config->get('powertrack_cfg_default_company_id');
            if ($powertrack_cfg_default_company_id) {
                $carrier_uuid = $powertrack_cfg_default_company_id;
                if ($this->config->get('powertrack_cfg_log')) $this->log->write('["powertrack"] Default carrier uuid set to be used: ' . $carrier_uuid);
            }
        }

        /*
         * Generate the EMAIL notification comment with the tracking number and carrier
         */
        $this->load->helper('powertrack');
        $args                 = array(
            'trackingCode'      => $tracking_code,
            'carrierCode'       => $carrier_uuid,
            'shipping_postcode' => $order_info['shipping_postcode'],
            'payment_postcode'  => $order_info['payment_postcode'],
        );
        $company_url_and_name = get_tracking_url_and_carrier_name($args, $this->config);

        if (! empty($company_url_and_name['carrier_name'])) {
            $powertrack_email_comment = "Shipped by " . $company_url_and_name['carrier_name'] . "\n";
            $powertrack_email_comment .= $company_url_and_name['tracking_url'];
        }


        /*
         *
         */
        $result = array(
            "tracking_url"  => $company_url_and_name['tracking_url'],
            "carrier_name"  => $company_url_and_name['carrier_name'],
            "tracking_code" => $tracking_code,
            "carrier_uuid"  => $carrier_uuid,
            "email_comment" => $powertrack_email_comment,
        );


        //aftership eventually
        //hook_4b21b


        if ($this->config->get('powertrack_cfg_log')) $this->log->write('Returning powertrack_info: ' . print_r($result, true));
        return $result;
    }


    /**
     *
     */
    public function loop_over_order_histories_and_return_last_tracking_info($order_info) {

        $powertrack_info = array();

        $this->load->model('account/order');
        $my_order_histories = $this->model_account_order->getOrderHistories($order_info['order_id']);

        /*
         * loop over all order history so that we finish by finding the last one with tracking number.
         */
        foreach ($my_order_histories as $my_order_history) {

            if (isset($my_order_history['powertrack_carrier']) || isset($my_order_history['powertrack_trackcode'])) {

                if ($this->config->get('powertrack_cfg_log')) $this->log->write('carrier: ' . $my_order_history['powertrack_carrier'] . ' code: ' . $my_order_history['powertrack_trackcode']);

                $my_args         = array(
                    'tracking_code' => $my_order_history['powertrack_trackcode'],
                    'carrier_uuid'  => $my_order_history['powertrack_carrier'],
                    'order_info'    => $order_info,
                );
                $powertrack_info = $this->get_powertrack_info($my_args);
            }
        }

        return $powertrack_info;
    }


}