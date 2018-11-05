<?php 
class ControllerExtensionPowertrackSale extends Controller {

    /**
     * order history in order info page
     */
	public function build_html_fields(){

		$this->load->language('module/powertrack_pub');
        $data['powertrack_text_tracking_code'] = $this->language->get('powertrack_text_tracking_code');
        $data['powertrack_text_ship_company']  = $this->language->get('powertrack_text_ship_company');
        
        //Inject companies
        $data['powertrack_companies'] = $this->config->get('powertrack_companies');


        $data['other_fields'] = "";
        //aftership add his fields here:
        //hook_72daa


		return $this->load->view('sale/powertrack_fields.tpl', $data);
	}


	/**
	 * 
	 */
	public function build_request_params_for_add_history_ajax(){

        /*
         * 
         */
        $params_for_add_history_fn = "'&powertrack_carrier=' + encodeURIComponent($('select[name=\'powertrack_carrier\']').val())" . 
        						  " + '&powertrack_trackcode=' + encodeURIComponent($('input[name=\'powertrack_trackcode\']').val())" ;

        //Aftership adds its parameters to ↑
		//hook_243e8

		return $params_for_add_history_fn;
	}

	
	/**
	 * 
	 */
	public function build_tracking_info_for_td_in_order_list($data){
		return $this->load->view('module/powertrack/sale/tracking_info_in_order_list.tpl', $data);
	}
	
	
}