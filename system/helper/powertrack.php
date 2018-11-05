<?php

function generateRandomString($length = 4) {
    //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters = '23456789abcdefghjkmnpqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


/**
 * see below
 */
function get_company_slug_by_uuid($args, $config){
    $powertrack_companies = $config->get('powertrack_companies');
    foreach($powertrack_companies as $company){
        if ($company['company_id'] === $args['carrierCode']){
            return isset($company['aftership_slug']) ? $company['aftership_slug'] : "";
        }
    }
    return "";
}


/**
 * http://www.codeigniter.com/userguide2/general/helpers.html
 * 
 * tracking_url may be empty
 */
function get_tracking_url_and_carrier_name($args, $config){
    
    $result = array('tracking_url' => '', 'carrier_name' => '');

    if (empty($args['carrierCode'])) {
        return $result;
    }
    
    
    $find = array('{tracking_code}', '{shipping_postcode}', '{payment_postcode}');
    $replace = array(
        'tracking_code'     => $args['trackingCode'],
        'shipping_postcode' => $args['shipping_postcode'],
        'payment_postcode'  => $args['payment_postcode'],
    );
    
    $powertrack_companies = $config->get('powertrack_companies');
    foreach($powertrack_companies as $company){
        if ($company['company_id'] === $args['carrierCode']){
            $company_url = $company['company_url'];
            if(! empty($company_url)){
                $company_url = str_replace($find, $replace, $company_url);
            }
            
            $result['tracking_url'] = $company_url;
            $result['carrier_name'] = $company['company_name'];
            break;
        }
    }
    
    return $result;
}



function h_shorten_url($url, $This){

    error_log("url 1 : " . $url);

    $api_key = $This->config->get('powertrack_cfg_goo_gl_api_key');
    if ($This->config->get('powertrack_cfg_log')) $This->log->write("[powertrack] API key: " . $api_key);

    if(! $api_key) {
        if ($This->config->get('powertrack_cfg_log')) $This->log->write("[powertrack] No goo.gl api key found. Aborting!");
        return $url;
    }

    // Shorten URL
    require_once(DIR_SYSTEM . 'helper/powertrack/libs/Googl.class.php');

    $googl = new GoogleURLAPI($api_key);

    $short_url = $googl->shorten($url);
    unset($googl);

    if ($This->config->get('powertrack_cfg_log')) $This->log->write("[powertrack] Shortened URL: " . $short_url);
    return $short_url;
}



/**
 * smshare integration.
 * Method is in this file because of OC2.1.0.1

 * Must be deleted and use the one from catalog but after refactore into a component class
 */ 
function replace_powertrack_variables_in_sms(&$smshare_message, $post_data, $order_info, $This){
     
    if(isset($post_data['powertrack_trackcode'])){
        if ($This->config->get('config_error_log')) $This->log->write("[powertrack] Tracking number found: " . $post_data['powertrack_trackcode'] . ", Going to merge it.");
        $smshare_message = str_replace('{tracking_code}', $post_data['powertrack_trackcode'], $smshare_message);
    }else{
        if ($This->config->get('config_error_log')) $This->log->write("[powertrack] No tracking number found, No merge.");
    }
     
    if(isset($post_data['powertrack_carrier'])){

        /*
         * 
         */
        $carrier_uuid = trim($post_data['powertrack_carrier']);

        if(empty($carrier_uuid)) {
            //try get default.
            $carrier_uuid = $This->config->get('powertrack_cfg_default_company_id');
            if ($This->config->get('config_error_log')) $This->log->write("[powertrack] Using default carrier: " . $carrier_uuid);
        }

        if(empty($carrier_uuid)) {
            if ($This->config->get('config_error_log')) $This->log->write("[powertrack] No Carrier found. Aborting!");
            return $smshare_message;
        }

         
        /*
         * get company name by carrier code
         */
        $args = array(
            'carrierCode'       => $carrier_uuid,
            'trackingCode'      => $post_data['powertrack_trackcode'],
            'shipping_postcode' => $order_info['shipping_postcode'],
            'payment_postcode'  => $order_info['payment_postcode'],
        );
        $tracking_url_and_name = get_tracking_url_and_carrier_name($args, $This->config);
         
        $smshare_message = str_replace('{tracking_company}', $tracking_url_and_name['carrier_name'], $smshare_message);
        $smshare_message = str_replace('{tracking_url}', $tracking_url_and_name['tracking_url'], $smshare_message);

        //error_log("url_and_name: " . print_r($tracking_url_and_name, true));
        //error_log('url 0: ' . $tracking_url_and_name['tracking_url'] );

        //goo.gl hook
        //hook_585eb
    }

    return $smshare_message;
}