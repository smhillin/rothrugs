<?php
/*
===================================
© Copyright 2007-2015 © webgility Inc. all rights reserved.
----------------------------------------
This file and the source code contained herein are the property of Webgility LLC
and are protected by United States copyright law. All usage is restricted as per 
the terms & conditions of Webgility License Agreement. You may not alter or remove 
any trademark, copyright or other notice from copies of the content.

The code contained herein may not be reproduced, copied, modified or redistributed in any form
without the express written consent by an officer of Webgility LLC.

===================================
*/


ini_set("display_errors","OFF");
//error_reporting(E_ALL);	

global $admin_folder;
require_once('../'.$admin_folder.'/config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

class EccController extends Controller { 

 function getVersion()
 {
		global $admin_folder;
		
		if(file_exists(realpath(dirname(dirname(__FILE__))."/../")."/".$admin_folder."/language/en-gb/common/footer.php")){	
			require_once (realpath(dirname(dirname(__FILE__))."/../")."/".$admin_folder."/language/en-gb/common/footer.php");
		}else{		
			require_once (realpath(dirname(dirname(__FILE__))."/../")."/".$admin_folder."/language/english/common/footer.php");
		}
		
		$opencart_version = "";
		if(isset($_['text_version']) && !empty($_['text_version']))
		{
			$opencart_version=$_['text_version'];
		}else{
			$opencart_version=$_['text_footer'];
		}
		
		if(empty($opencart_version) && isset($opencart_version))//changes on 30-12-14
		{
			return 0;
		}
		else
		{
			$versionArray = explode("Version",$opencart_version,2);//changes on 30-12-14
		
				if(trim($versionArray[1]) == "%s")
				{ 
					if(file_exists(realpath(dirname(dirname(__FILE__))."/../")."/".$admin_folder."/index.php"))
					{
						
						
						$handle = file_get_contents("../".$admin_folder."/index.php");
						$f = explode("\n",$handle,4);
						
						/****************** FOLLOWING CODE HAS BEEN ADDED TO AVOID VERSION ISSUE WHILE CONNECTING TO STORE ****************/
								$str = 'define';
							foreach($f  as $k=>$v)
							{
							if(strpos($v,"VERSION")!== false){
							   $index = $k;
							   $define_text = $f[$index];
							   break;
							  }
							}
							if(!empty($index))
							$define_text = $f[$index];
							else
							$define_text = $f[2];
							   
							  
					   	/****************** ABOVE CODE HAS BEEN ADDED TO AVOID VERSION ISSUE WHILE CONNECTING TO STORE ****************/
						
						$arr1 = explode(",", $define_text );//replace split to explode
						$arr2 = explode("'",$arr1[1]);//replace split to explode
						$version = $arr2[1];
						return $version;
					
					}
					
				}
				else
				{ 
					return $versionArray[1];
				}
		}	
	}
 } 
error_reporting(E_ALL && ~E_NOTICE && '~E_STRICT' && '~E_WARNING');
$registry=new Registry();
$objEcc = new EccController($registry);

$version = trim($objEcc->getVersion());

//$version = "2.0.1.1";
#################################  Only for version 1.3.4. For lower version mention that particular version here. as in 1.3 series we are not able to fing out exact version############
##$version = '1.3.4';
#####################################################################################################################################################################
if($version>='2.2.0.0'){
	require_once(DIR_SYSTEM . 'library/cart/customer.php');
	require_once(DIR_SYSTEM . 'library/cart/affiliate.php');
	require_once(DIR_SYSTEM . 'library/cart/currency.php');
	require_once(DIR_SYSTEM . 'library/cart/tax.php');
	require_once(DIR_SYSTEM . 'library/cart/weight.php');
	require_once(DIR_SYSTEM . 'library/cart/length.php');
	require_once(DIR_SYSTEM . 'library/cart/cart.php');
	$registry = new Registry();



	// Loader
	$loader = new Loader($registry);
	$registry->set('load', $loader);

	// Config
	$config = new Config();
	$registry->set('config', $config);

	// Database
	
	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	$registry->set('db', $db);

	// Settings
	$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");
	 
	foreach ($query->rows as $setting) 
	{
		$config->set($setting['key'], $setting['value']);
	}

	// Log 
	$log = new Log($config->get('config_error_filename'));
	$registry->set('log', $log);


	// Request
	$request = new Request();
	$registry->set('request', $request);

	// Response
	$response = new Response();
	$response->addHeader('Content-Type: text/xml; charset=utf-8');

	$registry->set('response', $response); 

	// Session
	$registry->set('session', new Session());

	// Cache
	$registry->set('cache', new Cache('file'));

	// Document
	$registry->set('document', new Document());

	// Language
	$languages = array();

	$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 

	foreach ($query->rows as $result) {
		$languages[$result['code']] = array(
			'language_id' => $result['language_id'],
			'name'        => $result['name'],
			'code'        => $result['code'],
			'locale'      => $result['locale'],
			'directory'   => $result['directory'],
			'filename'    => $result['filename']
		);
	}

	$config->set('config_language_id', $languages[$config->get('config_language')]['language_id']);

	$language = new Language($languages[$config->get('config_language')]['directory']);
	//$language->load($languages[$config->get('config_language')]['filename']);	
	$language->load('default');
	$registry->set('language', $language);

	//$registry->set('language', $language);

	// Currency
	$registry->set('currency', new Cart\Currency($registry));

	// Weight
	$registry->set('weight', new Cart\Weight($registry));

	// Length
	$registry->set('length', new Cart\Length($registry));

	// User
	$registry->set('user', new Cart\User($registry));
	
	/* // Customer
	$registry->set('customer', new Cart\Customer($registry)); */
	
	$event = new Event($registry);
	$registry->set('event', $event);
	// Front Controller
	$controller = new Front($registry);

	$objEcc = new EccController($registry);
	
}elseif($version>='2.0.0.0' && $version<'2.2.0.0'){
// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/affiliate.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');

$registry = new Registry();



// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");
 
foreach ($query->rows as $setting) 
{
	$config->set($setting['key'], $setting['value']);
}

// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);


// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/xml; charset=utf-8');

$registry->set('response', $response); 

// Session
$registry->set('session', new Session());

// Cache
$registry->set('cache', new Cache('file'));

// Document
$registry->set('document', new Document());

// Language
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = array(
		'language_id' => $result['language_id'],
		'name'        => $result['name'],
		'code'        => $result['code'],
		'locale'      => $result['locale'],
		'directory'   => $result['directory'],
		'filename'    => $result['filename']
	);
}

$config->set('config_language_id', $languages[$config->get('config_language')]['language_id']);

$language = new Language($languages[$config->get('config_language')]['directory']);
//$language->load($languages[$config->get('config_language')]['filename']);	
$language->load('default');
$registry->set('language', $language);

//$registry->set('language', $language);

// Currency
$registry->set('currency', new Currency($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// User
$registry->set('user', new User($registry));

$event = new Event($registry);
$registry->set('event', $event);
// Front Controller
$controller = new Front($registry);

$objEcc = new EccController($registry);

}elseif($version > '1.3.4' && $version<='2.0.0.0'){  
// Application Classes
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/user.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');

$registry = new Registry();


// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");
 
foreach ($query->rows as $setting) 
{
	$config->set($setting['key'], $setting['value']);
}

// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);


// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/xml; charset=utf-8');

$registry->set('response', $response); 

// Session
$registry->set('session', new Session());

// Cache
$registry->set('cache', new Cache('file'));

// Document
$registry->set('document', new Document());

// Language
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = array(
		'language_id' => $result['language_id'],
		'name'        => $result['name'],
		'code'        => $result['code'],
		'locale'      => $result['locale'],
		'directory'   => $result['directory'],
		'filename'    => $result['filename']
	);
}

$config->set('config_language_id', $languages[$config->get('config_language')]['language_id']);

$language = new Language($languages[$config->get('config_language')]['directory']);
$language->load($languages[$config->get('config_language')]['filename']);	
$registry->set('language', $language);

// Currency
$registry->set('currency', new Currency($registry));

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// User
$registry->set('user', new User($registry));

if($version>='2.0.1.0'){
//Event //add version 2011
$event = new Event($registry);
$registry->set('event', $event);
}

// Front Controller
$controller = new Front($registry);

$objEcc = new EccController($registry);

}
else
{
	require_once(DIR_SYSTEM . 'library/customer.php');
	require_once(DIR_SYSTEM . 'library/currency.php');
	require_once(DIR_SYSTEM . 'library/tax.php');
	require_once(DIR_SYSTEM . 'library/weight.php');
	require_once(DIR_SYSTEM . 'library/measurement.php');

	require_once(DIR_SYSTEM . 'library/cart.php');
	require_once(DIR_SYSTEM . 'library/user.php');
	
	// Loader
	$loader = new Loader();
	Registry::set('load', $loader);
	
	// Config
	$config = new Config();
	Registry::set('config', $config);
	
	// Database 
	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	Registry::set('db', $db);
	
	// Settings
	$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");
	
	foreach ($query->rows as $setting) {
		$config->set($setting['key'], $setting['value']);
	}
	
	$log = new Logger($config->get('config_error_filename'));
	Registry::set('log', $log);
	
	$request = new Request();
	
	Registry::set('request', $request);
	
	// Response
	$response = new Response();
	$response->addHeader('Content-Type', 'text/html; charset=utf-8');
	Registry::set('response', $response);
	
	// Cache
	Registry::set('cache', new Cache('file'));
	
	// Url
	Registry::set('url', new Url());
	
	// Session
	$session = new Session();
	Registry::set('session', $session);
		
	// Document
	Registry::set('document', new Document());
	
	// Language		
	$language = new Language();
	Registry::set('language', $language);
	
	// Customer
	Registry::set('customer', new Customer());
	
	// Currency
	Registry::set('currency', new Currency());
	
	// Tax
	Registry::set('tax', new Tax());
	
	// Weight
	Registry::set('weight', new Weight());
	
	// Weight
	Registry::set('measurement', new Measurement());
	
	// User
	Registry::set('user', new User());
	// Cart
	Registry::set('cart', new Cart());
	
	// Front Controller 
	$controller = new Front();

}
require_once('D.WgCommon.php'); 
class opencart extends WgCommon
{



	function Last_updated_date($username,$password)
	{ 
		$xmlResponse = new xml_doc();
		$xmlResponse->version='1.0';
		$xmlResponse->encoding='UTF-8';
		$root = $xmlResponse->createTag("RESPONSE", array('Version'=>'1.0'));
		#check for authorisation
		
		$status = auth_user($username,$password,$xmlResponse,$root);
	
		if($status!==0)
		{
		  return $status;
		}
		$pMethodNodes = $xmlResponse->createTag("UpdateDate", array(), '', $root);
		$xmlResponse->createTag("StatusCode", array(), "0", $pMethodNodes, __ENCODE_RESPONSE);
		$xmlResponse->createTag("StatusMessage", array(), "All Ok", $pMethodNodes, __ENCODE_RESPONSE);
		
		//$date = date("m-d-Y");
		$date = date("10-26-2010");
	
		$xmlResponse->createTag('LatestUpdatedDate',  array(), $date, $pMethodNodes, __ENCODE_RESPONSE);
			
		return $xmlResponse->generate();
	}
	#
# Update Orders shipping status method
# Will update Order Notes and tracking number of  order
# Input parameter Username,Password, array (OrderID,ShippedOn,ShippedVia,ServiceUsed,TrackingNumber)
#
    
	function AutoSyncOrder($username,$password,$data,$statustype='',$storeid,$others)
	{ 
		//print_r($statustype);die("dsf");
		global $registry,$language,$objEcc,$version;
		$status = $this->CheckUser($username,$password);
		if($status!==0)
		{
		  return $status;
		}
	
	
		if($version> '1.3.4')
		{
			$objEcc->load->model('sale/order');	
			$objEcc->load->language('sale/order');	
		}
		else
		{
			$objEcc->load->language('customer/order');
			$objEcc->load->model('customer/order');	
		}
		
		if($version < '1.5.0')	
		{
			$objEcc->document->title = $objEcc->language->get('heading_title');
		}
		
		
		$Orders = new WG_Orders();		
		
		$response_array = $data; 
		if (!is_array($response_array))
		{
			$Orders->setStatusCode("9997");
			$Orders->setStatusMessage("Unknown request or request not in proper format");	
			return $this->response($Orders->getOrders());exit();				
		}
		if (count($response_array) == 0)
		{
			$Orders->setStatusCode("9996");
			$Orders->setStatusMessage("REQUEST array(s) doesnt have correct input format");				
			return $this->response($Orders->getOrders());exit();
		}
		if(count($response_array) == 0) {
			$no_orders = true;
		}else {
			$no_orders = false;
		}
		$Orders->setStatusCode($no_orders?"1000":"0");
		$Orders->setStatusMessage($no_orders?"No new orders.":"All Ok");
		if ($no_orders){
			return json_encode($response_array);
		}
        $status_array = $this->OrderStatusWG();
		if(is_array($status_array))
		{
			foreach ($status_array as $status)
				{	
					$iInfo = $this->parseSpecCharsA($status);
					
						
					$status_array_new[$status['name']]=$status['order_status_id'];
					$status_array_order[$status['order_status_id']]=$status['name'];
					
				}
		}
		$i=0;
	
		foreach($response_array as $k=>$order)//request
		{
					$status_id = 0;	
					//$order_id=$order['Orderno'];
					$order_id=$order['OrderID'];	
						foreach($status_array_new as $sKeys => $sValues)
						{
							if( htmlentities($sKeys) == htmlentities($order['OrderStatus']))
							{
				
								$status_id = $status_array_new[$order['OrderStatus']];
								break;
							}
						}
					if($status_id==0)
					{			
						if($version >'1.3.4')
						{
							$oInfo =$objEcc->model_sale_order->getOrder($order_id);
						}
						else
						{ 
							$oInfo	= $objEcc->model_customer_order->getOrder($order_id);
						}
					        $status_id = $oInfo['order_status_id'];
					}	
					
			switch($statustype)
			{
			
			case 'shipmentUpdate':
					$isupdated = "error";
			break;		
			
			case 'paymentUpdate':
					$isupdated = "error";
			break;		
				
			case 'statusUpdate':
			break;
			case 'notesUpdate':
				
				try{
					$result = 'Success';
					$data['filter_order_id']=$order_id;
					$isOrderExists =0;
					if($version > '1.3.4')
					{ 
						$isOrderExists	= $objEcc->model_sale_order->getTotalOrders($data);
					}
					else
					{
						$isOrderExists	= $objEcc->model_customer_order->getTotalOrders($data);
					}
					unset($data);
					
					if (!$isOrderExists) 
					{
						$result = 'Order not found';
					}	
					elseif(!$status_id)
					{			
						$result = 'Status not found';
					}
					else
					{
						
						$info =" \n".$order['OrderNotes']."\n";
						$data['order_status_id'] = $status_id;
							## Send email to customer.
							$data['notify'] = 0;
							if ($result=='Success' && $order['IsNotifyCustomer']=='Y')
							{
								$data['notify'] =1;
							}
						$data ['comment'] =$info;
						$data['append'] = 1;
						
						if($version> '1.3.4')
						{
							if(method_exists($objEcc->model_sale_order,addOrderHistory))			
								$objEcc->model_sale_order->addOrderHistory($order_id,$data);
							elseif(method_exists($objEcc->model_sale_order,editOrder))
							{
									$objEcc->model_sale_order->editOrder($order_id,$data);
							}else
							{
									$this->__addOrderHistory($order['OrderID'],$data);
							}
						}
						else
						{ 
							if(method_exists($objEcc->model_customer_order,addOrderHistory))			
								$objEcc->model_customer_order->addOrderHistory($order_id, $data );
							else
								$objEcc->model_customer_order->editOrder($order_id, $data );
						}
							
						unset($data);
					}
						
					    $isupdated = "success";
					}catch(Exception $e)
					{
						$isupdated = "error";
					}		
			break;		
			}
		
					if($version >'1.3.4')
					{
						$oInfo =$objEcc->model_sale_order->getOrder($order_id);
					}
					else
					{ 
						$oInfo	= $objEcc->model_customer_order->getOrder($order_id);
					}
					
					$oInfo = $this->parseSpecChars($oInfo);
					
					$date_modified=$oInfo['date_modified'];	
					$date_added=$oInfo['date_added'];
					$date_added=date("m-d-Y H:i:s",strtotime($date_added));	
					$date_modified=date("m-d-Y H:i:s",strtotime($date_modified));
						
			        $Order = new WG_Order();
					$Order->setOrderID($order_id);
					$Order->setStatus($result);
					$Order->setLastModifiedDate($date_modified?$date_modified:$date_added);
					$Order->setOrderNotes($order['OrderNotes']);	
					$Order->setOrderStatus($status_array_order[$status_id]);
					$Orders->setOrders($Order->getOrder());	
		$i++;
	   }	
	return $this->response($Orders->getOrders());
	}

	
	public function __getOrder($order_id) {
		global $registry,$language,$objEcc,$version;
		
		$order_query = $objEcc->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$country_query = $objEcc->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $objEcc->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$country_query = $objEcc->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $objEcc->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}
			
			$query = $objEcc->db->query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$affiliate_id . "'");
			$affiliate_info = $query->row;

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}
			
			$objEcc->load->model('localisation/language');

			$language_info = $objEcc->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
				$language_filename = $language_info['filename'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_filename = '';
				$language_directory = '';
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				//'reward'                  => $order_query->row['reward'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'ip'                      => $order_query->row['ip']
			);
		} else {
			return false;
		}
	}
		
	public function __addOrderHistory($order_id, $data) {
	global $registry,$language,$objEcc,$version;
	
		$objEcc->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

		$objEcc->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $objEcc->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

		$order_info = $this->__getOrder($order_id);

		if ($data['notify']) {
			
			$language = new Language($order_info['language_directory']);
				
		
			$language->load('default');
			
			$_['text_update_subject']       = '%s - Order Update %s';
			$_['text_update_order']         = 'Order ID:';
			$_['text_update_date_added']    = 'Date Ordered:';
			$_['text_update_order_status']  = 'Your order has been updated to the following status:';
			$_['text_update_comment']       = 'The comments for your order are:';
			$_['text_update_link']          = 'To view your order click on the link below:';
			$_['text_update_footer']        = 'Please reply to this email if you have any questions.';

			
			$subject = sprintf($_['text_update_subject'], $order_info['store_name'], $order_id);
			$message  = $_['text_update_order'] . ' ' . $order_id . "\n";
			$message .= $_['text_update_date_added'] . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";

			$order_status_query = $objEcc->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'");

			if ($order_status_query->num_rows) {
				$message .= $_['text_update_order_status'] . "\n";

				$message .= $order_status_query->row['name'] . "\n\n";
			}

			if ($order_info['customer_id']) {
				$message .= $_['text_update_link']  . "\n";

				$message .= html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
			}

			if ($data['comment']) {
				$message .= $_['text_update_comment']  . "\n\n";

				$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			}

			$message .= $_['text_update_footer'];
			$mail = new Mail($objEcc->config->get('config_mail'));
			$mail->setTo($order_info['email']);
			$mail->setFrom($objEcc->config->get('config_email'));
			$mail->setSender($order_info['store_name']);
			$mail->setSubject($subject);
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			
		}
	}
	function UpdateOrdersShippingStatus($username,$password,$data,$statustype='Cancel',$OptionsBoost)
	{
		global $registry,$language,$objEcc,$version,$ModelCheckoutOrder;
		
		$set_option_boost = $OptionsBoost;
		
		$status = $this->CheckUser($username,$password);
		if($status!==0)
		{
		  return $status;
		}
		if($version> '1.3.4')
		{
		
			$objEcc->load->model('sale/order');	
			$objEcc->load->language('sale/order');
		}
		else
		{
			$objEcc->load->language('customer/order');
			$objEcc->load->model('customer/order');	
		}
		
		if($version < '1.5.0')	
		{
			$objEcc->document->title = $objEcc->language->get('heading_title');
		}
		
		
		$Orders = new WG_Orders();		
		$requestArray=$data;
		
		if (!is_array($requestArray))
		{
			$Orders->setStatusCode("9997");
			$Orders->setStatusMessage("Unknown request or request not in proper format");	
			return $this->response($Orders->getOrders());exit();				
		}
		if (count($requestArray) == 0)
		{
			$Orders->setStatusCode("9996");
			$Orders->setStatusMessage("REQUEST array(s) doesnt have correct input format");				
			return $this->response($Orders->getOrders());exit();
		}
		if(count($requestArray) == 0) {
			$no_orders = true;
		}else {
			$no_orders = false;
		}
		$Orders->setStatusCode($no_orders?"1000":"0");
		$Orders->setStatusMessage($no_orders?"No new orders.":"All Ok");	
	
		
		if ($no_orders)
		{ 
			return $this->response($Orders->getOrders());
		}
		
		$status_array = $this->OrderStatusWG();
		if(is_array($status_array))
		{
			foreach ($status_array as $status)
				{	
					$iInfo = $this->parseSpecCharsA($status);
											
					$status_array_new[$status['name']]=$status['order_status_id'];
					$status_array_order[$status['order_status_id']]=$status['name'];
					
				}
		}
		
			if($requestArray[0]['IsCreateRefund']==1){
			
				$orderrefund = $this->CreateOrderRefund($username, $password ,$requestArray,$set_option_boost);
				
				$Order = new WG_Order();
				$Order->setOrderID($orderrefund['OrderID']);
				$Order->setStatus($orderrefund['result']);
				$Order->setOrderNotes('');
				$Order->setLastModifiedDate($orderrefund['last_modfied_date']);
				$Order->setOrderStatus($orderrefund['OrderStatus']);
				$Orders->setOrders($Order->getOrder());	
	
		}else {
	
		foreach($requestArray as $k=>$v)//request
		{
				$j=0;	
				foreach($v as $k3=>$v3)
				{
					$order[$k3] = $v3;
				} 
			
			$update_note = $order['UpdateOrderNote'];
			$result = 'Success';
			
			if($update_note=="Y")
			{
					$data['filter_order_id'] = $order['OrderID'];
					$order_id = $order['OrderID'];
					$isOrderExists =0;
					if($version > '1.3.4')
					{ 
						$isOrderExists	= $objEcc->model_sale_order->getTotalOrders($data);
					}
					else
					{
						$isOrderExists	= $objEcc->model_customer_order->getTotalOrders($data);
					}
					unset($data);
					
					if (!$isOrderExists) 
					{
						$result = 'Order not found';
					}	
					else
					{
						$status_id = 0;		
							$strstatusstring = "";
							foreach($status_array_new as $sKeys => $sValues)
							{
								$strstatusstring = $strstatusstring.$sKeys." : ".$sValues;
							
								if( htmlentities($sKeys) == htmlentities($order['OrderStatus']))
								{
					
									$status_id = $status_array_new[$order['OrderStatus']];
									break;
								}
							}
						
						$data['order_status_id'] = 2;
						$data ['comment'] =$order['OrderNotes'];
						$data['append'] = 1;
						
						if($version> '1.3.4')
						{
							
							if(method_exists($objEcc->model_sale_order,addOrderHistory))
							{	

								$objEcc->model_sale_order->addOrderHistory($order['OrderID'], $data );
							}
							elseif(method_exists($objEcc->model_sale_order,editOrder))
							{

								$objEcc->model_sale_order->editOrder($order['OrderID'], $data );
							}else
							{
								
								$this->__addOrderHistory($order['OrderID'],$data);
		
							}
						}
						else
						{ 
							if(method_exists($objEcc->model_customer_order,addOrderHistory))			
								$objEcc->model_customer_order->addOrderHistory($order['OrderID'], $data );
							else
								$objEcc->model_customer_order->editOrder($order['OrderID'], $data );
						}
						$result = 'Success';	
						unset($data);
					
					}
			}
			else
			{

			$status_id = 0;		
			
			foreach($status_array_new as $sKeys => $sValues)
			{
	
				if( htmlentities($sKeys) == htmlentities($order['OrderStatus']))
				{
	
					$status_id = $status_array_new[$order['OrderStatus']];
					break;
				}
			}
	
			$data['filter_order_id'] = $order['OrderID'];
			$order_id = $order['OrderID'];
			$isOrderExists =0;
			if($version > '1.3.4')
			{ 
				$isOrderExists	= $objEcc->model_sale_order->getTotalOrders($data);
			}
			else
			{
				$isOrderExists	= $objEcc->model_customer_order->getTotalOrders($data);
			}
			unset($data);
			
			if (!$isOrderExists) 
			{
				$result = 'Order not found';
			}	
			elseif(!$status_id)
			{			
				$result = 'Status not found';
			}
			else
			{
				
			$info = '';	
				$info .= "\nOrder Shipped ";
				if ($order['ShippedOn']!="")
				$info .= " on ". substr($order['ShippedOn'],0,10);
				
				
				if ($order['ServiceUsed']!="" )
				$info .= " via ".$order['ServiceUsed'];
				
				if ($order['TrackingNumber']!="")
				$info .= " Tracking Number is: ".$order['TrackingNumber'];
				
				$info .=" \n".$order['OrderNotes']."\n";
				$data['order_status_id'] = $status_id;
				
				## Send email to customer.
			
				
				$data['notify'] = 0;
				if ($result=='Success' && $order['IsNotifyCustomer']=='Y')
				{
					$data['notify'] =1;
				}
				
				$data ['comment'] =$info;
				$data['append'] = 1;
				
				if($version> '1.3.4')
				{
					if(method_exists($objEcc->model_sale_order,addOrderHistory))			
						$objEcc->model_sale_order->addOrderHistory($order['OrderID'], $data );
					elseif(method_exists($objEcc->model_sale_order,editOrder))
					{
						$objEcc->model_sale_order->editOrder($order['OrderID'], $data );
					}else
					{
						$this->__addOrderHistory($order['OrderID'],$data);
					}
				}
				else
				{ 
					if(method_exists($objEcc->model_customer_order,addOrderHistory))			
						$objEcc->model_customer_order->addOrderHistory($order['OrderID'], $data );
					else
						$objEcc->model_customer_order->editOrder($order['OrderID'], $data );
				}
					
				unset($data);
						
			}
					if($version >'1.3.4')
					{
						$oInfo =$objEcc->model_sale_order->getOrder($order_id);
					}
					else
					{ 
						$oInfo	= $objEcc->model_customer_order->getOrder($order_id);
					}
					
					$oInfo = $this->parseSpecChars($oInfo);
					
					$date_modified=$oInfo['date_modified'];	
					$date_added=$oInfo['date_added'];
					$date_added=date("m-d-Y H:i:s",strtotime($date_added));	
					$date_modified=date("m-d-Y H:i:s",strtotime($date_modified));
						
			        
				}	
					$Order = new WG_Order();
					$Order->setOrderID($order_id);
					$Order->setStatus($result);
					$Order->setLastModifiedDate($date_modified?$date_modified:$date_added);
					$Order->setOrderNotes($order['OrderNotes']);	
					$Order->setOrderStatus($status_array_order[$status_id]);
					$Orders->setOrders($Order->getOrder());		
		}
		}
		return $this->response($Orders->getOrders());
	}
	

	function UpdateOrdersStatusAcknowledge($username,$password,$data) 
	{
	
		global $registry,$language,$objEcc,$version;
		$status = $this->CheckUser($username,$password);
		if($status!==0)
		{
		  return $status;
		}
		if($version> '1.3.4')
		{
			$objEcc->load->model('sale/order');	
			$objEcc->load->language('sale/order');	
		}
		else
		{
			$objEcc->load->language('customer/order');
			$objEcc->load->model('customer/order');	
		}
		
		$requestArray=$data;
		$Orders = new WG_Orders();
		//$requestArray = json_decode($data,true);
		if (!is_array($requestArray))
		{
			$Orders->setStatusCode("9997");
			$Orders->setStatusMessage("Unknown request or request not in proper format");	
			return $this->response($Orders->getOrders());exit();				
		}
		
		if (count($requestArray) == 0)
		{
			$Orders->setStatusCode("9996");
			$Orders->setStatusMessage("REQUEST array(s) doesnt have correct input format");				
			return $this->response($Orders->getOrders());exit();
		}
		if(count($requestArray) == 0) 
		{
			$no_orders = true;
		}else {
			$no_orders = false;
		}
	
		$Orders->setStatusCode($no_orders?"1000":"0");
		$Orders->setStatusMessage($no_orders?"No new orders.":"All Ok");	
	
		if ($no_orders){
			return $this->response($Orders->getOrders());
		}
	
		//$ordersNode = $xmlResponse->createTag("Orders", array(), '', $root);
		
		$status_array = $this->OrderStatusWG();
		if(is_array($status_array))
			{
			foreach ($status_array as $status)
				{	
					$iInfo = $this->parseSpecCharsA($status);
											
					$status_array_new[$status['name']]=$status['order_status_id'];
					$status_array_order[$status['order_status_id']]=$status['name'];
					
				}
			}
	
		foreach($requestArray as $k=>$v)//request
		{
			
					foreach($v as $k3=>$v3)
					{
						$order[$k3] = $v3;
					} 
			
			$status_id = 0;		
			$result = 'Success';
	
			foreach($status_array_new as $sKeys => $sValues)
			{
	
				if( htmlentities($sKeys) == htmlentities($order['OrderStatus']))
				{
	
					$status_id = $status_array_new[$order['OrderStatus']];
					break;
				}
			}
			$data['filter_order_id'] = $order['OrderID'];
			$order_id = $order['OrderID'];
			$isOrderExists =0;
			if($version > '1.3.4')
			{
				$isOrderExists	= $objEcc->model_sale_order->getTotalOrders($data);
			}
			else
			{
				$isOrderExists	= $objEcc->model_customer_order->getTotalOrders($data);
			}
			unset($data);
			
			if (!$isOrderExists) 
			{
				$result = 'Order not found';
			}	
			elseif(!$status_id)
			{			
				$result = 'Status not found';
			}
			else
			{
			
				$data['order_status_id'] = $status_id;			
				
				
				if($version > '1.3.4')
				{
					if(method_exists($objEcc->model_sale_order,addOrderHistory))			
						$objEcc->model_sale_order->addOrderHistory($order['OrderID'], $data );
					elseif(method_exists($objEcc->model_sale_order,editOrder))
					{
						$objEcc->model_sale_order->editOrder($order['OrderID'], $data );
					}else
					{
						$this->__addOrderHistory($order['OrderID'],$data);
					}
				}
				else
				{
					if(method_exists($objEcc->model_customer_order,addOrderHistory))			
						$objEcc->model_customer_order->addOrderHistory($order['OrderID'], $data );
					else
						$objEcc->model_customer_order->editOrder($order['OrderID'], $data );
				}
					
				unset($data);
				
			}		
					if($version >'1.3.4')
					{
						$oInfo =$objEcc->model_sale_order->getOrder($order_id);
					}
					else
					{ 
						$oInfo	= $objEcc->model_customer_order->getOrder($order_id);
					}
					
					$oInfo = $this->parseSpecChars($oInfo);
					
					$date_modified=$oInfo['date_modified'];	
					$date_added=$oInfo['date_added'];
					$date_added=date("m-d-Y H:i:s",strtotime($date_added));	
					$date_modified=date("m-d-Y H:i:s",strtotime($date_modified));
						
			        $Order = new WG_Order();
					$Order->setOrderID($order_id);
					$Order->setStatus($result);
					$Order->setLastModifiedDate($date_modified?$date_modified:$date_added);
					$Order->setOrderNotes($order['OrderNotes']);	
					$Order->setOrderStatus($status_array_order[$status_id]);
					$Orders->setOrders($Order->getOrder());		
		}	
		return $this->response($Orders->getOrders());
	}

	# Functions to  Sync the Items and the Varients with the QB
	function synchronizeItems($username,$password,$data,$storeid=1,$OptionsBoost,$others)
	{
		global $registry,$language,$objEcc,$version;
		#check for authorisation
		$set_option_boost = $OptionsBoost;
		$status = $this->CheckUser($username,$password);
		
		if($status!==0)
		{
			return $status;
		 }
			
		$Items = new WG_Items();
			
		$requestArray = $data;
	   $pos = strpos($others,'/');
       if($pos)
       {
               $array_others = explode("/",$others);
			   
       }else{
	   		    $array_others=array();
				$array_others[0]=$others;       
		}
		if (!is_array($requestArray))
		{
				$Items->setStatusCode('9997');
				$Items->setStatusMessage('Unknown request or request not in proper format');				
				return $this->response($Items->getItems());				
		}
	
		if (count($requestArray) == 0)
		{
				$Items->setStatusCode('9996');
				$Items->setStatusMessage('REQUEST array(s) doesnt have correct input format');				
				return $this->response($Items->getItems());				
		}
		 $Items->setStatusCode('0');
		 $Items->setStatusMessage('All Ok');
		 $itemsCount = 0;
		 $itemsProcessed = 0;
		 // Go throught items
	
		 $_err_message_arr = Array();
		 foreach($requestArray as $k=>$v4)//request
		{
				$itemsCount++;
				$Item = new WG_Item();
				$status ="Success";
				$productID = $v4['ProductID'];
				$sku = $v4['Sku'];
				$productName = $v4['ProductName'];
				$qty = $v4['Qty'];
				$price = $v4['Price'];
				$updated_attrib=0;
				
				if(isset($v4['ItemVariants']) && is_array($v4['ItemVariants']))
				{
					foreach($v4['ItemVariants'] as $var)//request
					{
						$v4['Sku'] = $vsku =$var['Sku'];   
						$varient_id = $var['VarientID'];
						$varient_qty = $var['Quantity'];
						$varient_price = $var['UnitPrice'];
						if(isset($set_option_boost) && $set_option_boost!='')
						{
							if(isset($varient_price) && $varient_price!='')
							{
								$sql = "update " . DB_PREFIX . "product_option_value set price = '".$varient_price."' WHERE product_option_value_id ='".$varient_id."' and ob_sku= '".$vsku."'";
								$query = $objEcc->db->query($sql);
							}
							if(isset($varient_qty) && $varient_qty!='')
							{
								$sql = "update " . DB_PREFIX . "product_option_value set quantity =  '" . $varient_qty . "' WHERE product_option_value_id ='".$varient_id."' and ob_sku= '".$vsku."'";
								$query = $objEcc->db->query($sql);
							}	
	
							if ($status =="") $status ="Success";	
							$Item->setStatus($status);
							$Item->setProductID($v4['ProductID']);
							$Item->setSku(htmlentities($v4['Sku']));							
							$Items->setItems($Item->getItem());									
	
						}						
					}
					continue;
				}
				
				foreach($array_others as $ot)
				{
				if ($updated_attrib ==0)
				{
					$status ="";
					$sql = "SELECT count(*) as isProductExist FROM " . DB_PREFIX . "product p WHERE product_id ='".$productID."'";
					$query = $objEcc->db->query($sql);
		
					
					$itemFound = true;
					
					if (!$query->row['isProductExist']) 
					{
						$status ="Product not found";
						$itemFound = false;
					}
				
					if(($others=="QTY" || $others=="BOTH"|| $ot=="QTY")&& $itemFound)
					{			
						
					$sql= "UPDATE " . DB_PREFIX . "product SET quantity = '".$qty."' where product_id='".$productID."'";
						$objEcc->db->query($sql);
					}

					if(($others=="PRICE" || $others=="BOTH"|| $ot=="PRICE") && $itemFound)
					{			
						$sql= "UPDATE " . DB_PREFIX . "product SET price = '".$price."' where product_id='".$productID."'";
					
						$objEcc->db->query($sql);
					}	
					$itemsProcessed++; 	
					if ($status =="") $status ="Success";	
	
					$Item->setStatus($status);
					$Item->setProductID($v4['ProductID']);
					$Item->setSku(htmlentities($v4['Sku']));							
					$Items->setItems($Item->getItem());			
				}
				else if($updated_attrib == $k+1)
				{
					$itemsProcessed++; 
				}
				}
		 }
		return $this->response($Items->getItems());
	}
	
	
	# Returns All the Payment Methods used by the store
	function getPaymentMethods($username,$password)
	{
		global $registry,$language,$objEcc,$version;
		
			
		$WgBaseResponse = new WgBaseResponse();	
		$PaymentMethods = new WG_PaymentMethods();
		$responseArray = array();
		$status = $this->CheckUser($username,$password);
		
	   if($status!="0"){ //login name invalid
			if($status=="1"){
				$WgBaseResponse->setStatusCode('1');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			if($status=="2"){ //password invalid
				$WgBaseResponse->setStatusCode('2');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			$response=$this->response($WgBaseResponse->getBaseResponse());
				return $response;
		}	
		
					 $PaymentMethods->setStatusCode('0');		 
					 $PaymentMethods->setStatusMessage('All Ok');
		   
		$objEcc->load->language('extension/payment');
		
		$files = glob(DIR_APPLICATION . 'controller/payment/*.php');
		$j=1;
		foreach ($files as $file) {
			if(file_exists($file))
			{
			$extension = basename($file, '.php');				
			$objEcc->load->language('payment/' . $extension);
			
					$PaymentMethod = new WG_PaymentMethod();
					$PaymentMethod->setMethodId($j++);					
					$PaymentMethod->setMethod(($objEcc->language->get('heading_title')));	
					$PaymentMethods->setPaymentMethods($PaymentMethod->getPaymentMethod());
					
			}		
		}
	
		   
	   return $this->response($PaymentMethods->getPaymentMethods());
	}
	
	# Returns all the shipping methods 
	function getShippingMethods($username,$password)
	{
		global $registry,$language,$objEcc,$version;
		
		$WgBaseResponse = new WgBaseResponse();	
		//$ShippingMethods = new ShippingMethods();
		$responseArray = array();
		$status = $this->CheckUser($username,$password);
		
	   if($status!="0")
		{ //login name invalid
			if($status=="1")
			{
				$WgBaseResponse->setStatusCode('1');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			if($status=="2")
			{ //password invalid
			$WgBaseResponse->setStatusCode('2');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			$response=$this->response($WgBaseResponse->getBaseResponse());
				return $response;
		}	
		
				$ShippingMethods = new WG_ShippingMethods();
				$ShippingMethods->setStatusCode('0');
				$ShippingMethods->setStatusMessage('All Ok');
						
		
		if($version<'2.0.1.1'){ // changes done on 30-12-14
			$objEcc->load->model('setting/extension');
			
			$extensions = $objEcc->model_setting_extension->getInstalled('shipping');
		}// changes done on 30-12-14
	
		$files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
		
		foreach ($files as $file) {
				if(file_exists($file))
					{
						$extension = basename($file, '.php');					
						$objEcc->load->language('shipping/' . $extension);
						
						$ShippingMethod = new WG_ShippingMethod();
						$ShippingMethod->setCarrier(htmlspecialchars( htmlentities( strip_tags($objEcc->language->get('heading_title')))));
						
						switch($extension)
						{
							case "auspost":
							
								$sighn = array(":",":-");
								
								
								$ShippingMethod->setMethods(htmlentities(str_replace($sighn,"",$objEcc->language->get('entry_express')),ENT_QUOTES));
								$ShippingMethod->setMethods(htmlentities(str_replace($sighn,"",$objEcc->language->get('entry_standard')),ENT_QUOTES));
								
							break;
							
							case "citylink":
								
								
								$ShippingMethod->setMethods(htmlentities(str_replace($sighn,"",$objEcc->language->get('heading_title')),ENT_QUOTES));
	
							break;
							
							case "flat":
							
								$ShippingMethod->setMethods(htmlentities(str_replace($sighn,"",$objEcc->language->get('heading_title')),ENT_QUOTES));
								$ShippingMethod->setMethods("Flat Shipping Rate");
								
													
							break;
							
							case "free":												
							case "item":						
							case "parcelforce_48":							
							case "pickup":
							case"weight":
								
								$ShippingMethod->setMethods(htmlentities(str_replace($sighn,"",$objEcc->language->get('heading_title')),ENT_QUOTES));
							break;
							
							case "royal_mail":	
								# objEcc varibale can use for find out the shipping methods is enabled or not
	
								$methodsName= array();
								array_push($methodsName,$objEcc->language->get('text_1st_class_standard'),
								$objEcc->language->get('text_1st_class_recorded'),
								$objEcc->language->get('text_2nd_class_standard'),
								$objEcc->language->get('text_2nd_class_recorded'),
								$objEcc->language->get('text_standard_parcels'),
								$objEcc->language->get('text_airmail'),
								$objEcc->language->get('text_international_signed'),
								$objEcc->language->get('text_airsure'),
								$objEcc->language->get('text_surface'));
								
								for($j=count($methodsName)-1;$j>=0;$j--)
								{
									
								
								$ShippingMethod->setMethods(htmlentities(str_replace($sighn,"",$objEcc->language->get('heading_title')),ENT_QUOTES));
								}
										
							break;
							
							case"ups":
							$methodsName= array();
								array_push($methodsName,$objEcc->language->get('text_next_day_air'),
								$objEcc->language->get('text_2nd_day_air'),
								$objEcc->language->get('text_ground'),
								$objEcc->language->get('text_worldwide_express'),
								$objEcc->language->get('text_worldwide_express_plus'),
								$objEcc->language->get('text_worldwide_expedited'),
								$objEcc->language->get('text_express'),
								$objEcc->language->get('text_standard'),
								$objEcc->language->get('text_3_day_select'),
								$objEcc->language->get('text_next_day_air_saver'),
								$objEcc->language->get('text_next_day_air_early_am'),
								$objEcc->language->get('text_expedited'),
								$objEcc->language->get('text_standard'),
								$objEcc->language->get('text_saver'),
								$objEcc->language->get('text_express_early_am'),
								$objEcc->language->get('text_express_plus'),
								$objEcc->language->get('text_today_standard'),
								$objEcc->language->get('text_today_dedicated_courier'),
								$objEcc->language->get('text_today_intercity'),
								$objEcc->language->get('text_today_express'),
								$objEcc->language->get('text_today_express_saver'));
								
								for($j=count($methodsName)-1;$j>=0;$j--)
								{
								
									$ShippingMethod->setMethods(htmlentities(str_replace($sighn,"",$methodsName[$j]),ENT_QUOTES));
								
								}
							
							break;
							
							case"usps":
								$methodsName= array();
								array_push($methodsName,$objEcc->language->get('text_domestic_0'),
									$objEcc->language->get('text_domestic_1'),
									$objEcc->language->get('text_domestic_2'),
									$objEcc->language->get('text_domestic_3'),
									$objEcc->language->get('text_domestic_4'),
									$objEcc->language->get('text_domestic_5'),
									$objEcc->language->get('text_domestic_6'),
									$objEcc->language->get('text_domestic_7'),
									$objEcc->language->get('text_domestic_12'),
									$objEcc->language->get('text_domestic_13'),
									$objEcc->language->get('text_domestic_16'),
									$objEcc->language->get('text_domestic_17'),
									$objEcc->language->get('text_domestic_18'),
									$objEcc->language->get('text_domestic_19'),
									$objEcc->language->get('text_domestic_22'),
									$objEcc->language->get('text_domestic_23'),
									$objEcc->language->get('text_domestic_25'),
									$objEcc->language->get('text_domestic_27'),
									$objEcc->language->get('text_domestic_28'),
									$objEcc->language->get('text_international_1'),
									$objEcc->language->get('text_international_2'),
									$objEcc->language->get('text_international_4'),
									$objEcc->language->get('text_international_5'),
									$objEcc->language->get('text_international_6'),
									$objEcc->language->get('text_international_7'),
									$objEcc->language->get('text_international_8'),
									$objEcc->language->get('text_international_9'),
									$objEcc->language->get('text_international_10'),
									$objEcc->language->get('text_international_11'),
									$objEcc->language->get('text_international_12'),
									$objEcc->language->get('text_international_13'),
									$objEcc->language->get('text_international_14'),
									$objEcc->language->get('text_international_15'),
									$objEcc->language->get('text_international_16'),
									$objEcc->language->get('text_international_21'));
									
	
							for($j=count($methodsName)-1;$j>=0;$j--)
								{
									
								$ShippingMethod->setMethods(htmlentities(str_replace($sighn,"",$methodsName[$j]),ENT_QUOTES));
								}
							break;
							
							default:
							$ShippingMethod->setMethods("");
							break;
						}	
					}
				$ShippingMethods->setShippingMethods($ShippingMethod->getShippingMethod());		
				}	
				
	  return $this->response($ShippingMethods->getShippingMethods());
	
	}
	

	function getCategory($username,$password)
	{
		global $registry,$language,$objEcc,$version;
		$WgBaseResponse = new WgBaseResponse();	
		$responseArray = array();
		$status = $this->CheckUser($username,$password);
		if($status!="0")
		{ //login name invalid
			if($status=="1")
			{
				$WgBaseResponse->setStatusCode('1');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			if($status=="2")
			{ //password invalid
			 $WgBaseResponse->setStatusCode('2');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			$response=$this->response($WgBaseResponse->getBaseResponse());
				return $response;
		}
		
			$order="ASC";
			$sort= "name";
			
			$objEcc->load->language('catalog/category');
			if($version < '1.5.0')	
			{
				$objEcc->document->title = $objEcc->language->get('heading_title');
			}
			$objEcc->language->get('heading_title');
			
			$objEcc->load->model('catalog/category');
			
			$allCategories = $objEcc->model_catalog_category->getCategories(0);
		
	 
					$Categories = new WG_Categories();
					$Categories->setStatusCode('0');
					$Categories->setStatusMessage('All Ok');
		
	
		 if(is_array($allCategories))
		 foreach($allCategories as $values)
		 {
			
			$parentId_array = $objEcc->model_catalog_category->getCategory($values['category_id']);
			$CategoryDesc_array = ($objEcc->model_catalog_category->getCategoryDescriptions($values['category_id']));
			$catIndex = key($CategoryDesc_array);
			
		
			$Category =new WG_Category();
							$Category->setCategoryID($values['category_id']);
							$cat1234 = str_replace('&nbsp;','',$values['name']);
							$Category->setCategoryName(utf8_decode(html_entity_decode(htmlspecialchars_decode($cat1234))));
							
							$Category->setParentID($valuse['parent_id']);
							$Categories->setCategories($Category->getCategory());	
			 
		 
		 }
			return $this->response($Categories->getCategories());
	}
	
	function OrderStatusWG()
		{
			global $registry,$language,$objEcc,$version;
		
			$order="ASC";
			$sort= "name";
			$objEcc->load->language('localisation/order_status');
			
			if($version < '1.5.0')	
			{	
				$objEcc->document->title = $objEcc->language->get('heading_title');
			}
			
			$objEcc->load->model('localisation/order_status');
				
			$order_status_total = $objEcc->model_localisation_order_status->getTotalOrderStatuses();
			$data = array(
					'sort'  => $sort,
					'order' => $order,
					'start' => 0,
					'limit' => $order_status_total
				);
			
			return $results = $objEcc->model_localisation_order_status->getOrderStatuses($data);
			
		}
	# Retrive all order status of the store
	function getOrderStatus($username,$password)
	{
		global $registry,$language,$objEcc,$version;
		
		$OrderStatuses = array();
		$OrderStatus = array();
		$WgBaseResponse = new WgBaseResponse();	
		$responseArray = array();
		$status = $this->CheckUser($username,$password);
		if($status!="0")
		{ //login name invalid
			if($status=="1")
			{
				$WgBaseResponse->setStatusCode('1');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			if($status=="2")
			{ //password invalid
				$WgBaseResponse->setStatusCode('2');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			$response=$this->response($WgBaseResponse->getBaseResponse());
				return $response;
		}
		$status_array = $this->OrderStatusWG();
		
		if(count($status_array))
		{
					$OrderStatuses = new WG_OrderStatuses();
					$OrderStatuses->setStatusCode('0');
					$OrderStatuses->setStatusMessage('All Ok');
			foreach ($status_array as $status)
			{	
				$iInfo =$this->parseSpecCharsA($status);
			
							$OrderStatus =new WG_OrderStatus();
							$OrderStatus->setOrderStatusID($iInfo['order_status_id']);
							$OrderStatus->setOrderStatusName($iInfo['name']);
							$OrderStatuses->setOrderStatuses($OrderStatus->getOrderStatus());	
			}
		}else{
			$OrderStatuses->setStatusCode('0');
			$WgBaseResponse->setStatusMessage('No order status returned');
		
		}	
		return $this->response($OrderStatuses->getOrderStatuses());
	} 

#
# function to return the store Manufacturer list so synch with QB inventory
#

	function getManufacturers($username,$password)
	{
		global $registry,$language,$objEcc,$version;
		$WgBaseResponse = new WgBaseResponse();	
		$Manufacturers = new WG_Manufacturers();
		$responseArray = array();
		$status = $this->CheckUser($username,$password);
		if($status!="0")
		{ //login name invalid
			if($status=="1")
			{
				$WgBaseResponse->setStatusCode('1');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			if($status=="2")
			{ //password invalid
			$WgBaseResponse->setStatusCode('2');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			$response=$this->response($WgBaseResponse->getBaseResponse());
				return $response;
		}
		$order="ASC";
		$sort= "name";	
			
		$objEcc->load->language('catalog/manufacturer');
		if($version < '1.5.0')	
		{
			$objEcc->document->title = $objEcc->language->get('heading_title');
		}
		 
		$objEcc->load->model('catalog/manufacturer');
			
		$manufacturersList_count = $objEcc->model_catalog_manufacturer->getTotalManufacturers();
		$data = array(
				'sort'  => $sort,
				'order' => $order,
				'start' => 0,
				'limit' => $manufacturersList_count
			);
		$manufacturersList = $objEcc->model_catalog_manufacturer->getManufacturers($data);
		
		$ctr=0;	
		
			$Manufacturers->setStatusCode('0');
			$Manufacturers->setStatusMessage('All Ok');
	
		# fetch manufacturers;
		if($manufacturersList_count)
		foreach($manufacturersList as $values) 
		{
		
			
			$values =$this->parseSpecCharsA($values);
			$Manufacturer =new WG_Manufacturer();	
			$Manufacturer->setManufacturerID($values['manufacturer_id']);
			$Manufacturer->setManufacturerName(addslashes(htmlentities($values['name'], ENT_QUOTES)));
			$Manufacturers->setManufacturers($Manufacturer->getManufacturer());
			
		}
	return $this->response($Manufacturers->getManufacturers());
	}
#
# function to return the store tax list so synch with QB inventory
#
	function getTaxes($username,$password)
	{
		global $registry,$language,$objEcc,$version;
		$WgBaseResponse = new WgBaseResponse();	
		//$CompanyInfo = new CompanyInfo();
		$Taxes = new WG_Taxes();
		$responseArray = array();
		$status = $this->CheckUser($username,$password);
		
	   if($status!="0")
		{ //login name invalid
			if($status=="1")
			{
				$WgBaseResponse->setStatusCode('1');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			if($status=="2")
			{ //password invalid
			$WgBaseResponse->setStatusCode('2');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
			}
			$response=$this->response($WgBaseResponse->getBaseResponse());
				return $response;
		}
		
			$order="ASC";
			$sort= "title";
			
			$objEcc->load->language('localisation/tax_class');
			if($version < '1.5.0')	
			{	
				$objEcc->document->title = $objEcc->language->get('heading_title');
			}
			
			$objEcc->load->model('localisation/tax_class');
				
			$tax_class_total = $objEcc->model_localisation_tax_class->getTotalTaxClasses();
			
			
			$data = array(
					'sort'  => $sort,
					'order' => $order,
					'start' => 0,
					'limit' => $tax_class_total
				);
			$taxesList = $objEcc->model_localisation_tax_class->getTaxClasses($data);	
	
					$Taxes->setStatusCode('0');
					$Taxes->setStatusMessage('All Ok');
	
		if(count($taxesList))
		foreach($taxesList as $values)
		{
		
							$Tax =new WG_Tax();												
							$Tax->setTaxID($values['tax_class_id']);
							$Tax->setTaxName($this->parseSpecCharsA($values['title']));
							$Taxes->setTaxes($Tax->getTax());
			  
		}   
	   return $this->response($Taxes->getTaxes());
	}
	function GetImage($username,$password,$data,$storeid=1,$others) {
	
		global $registry,$language,$objEcc,$version;
		$WgBaseResponse = new WgBaseResponse();	
		$Items = new WG_Items();
		$responseArray = array();
		$status = $this->CheckUser($username,$password);
	
		$objEcc->load->language('catalog/product');
		
		if($version > '1.3.4')
		{
			$objEcc->load->model('setting/store');
		}
		$objEcc->load->language('catalog/product');
		$objEcc->load->model('catalog/product');
		
		if($status!="0")
		{ //login name invalid
			return $status;
		}
		$requestArray = $data;
		if (!is_array($requestArray)) 
		{
			$Items->setStatusCode('9997');
			$Items->setStatusMessage('Unknown request or request not in proper format');				
			return $this->response($Items->getItems());
		}
	
		if (count($requestArray) == 0) 
		{
			$Items->setStatusCode('9996');
			$Items->setStatusMessage('REQUEST tag(s) doesnt have correct input format');
			return $this->response($Items->getItems());
		}
		$Items->setStatusCode('0');
		$Items->setStatusMessage('All Ok');
		
		$itemsCount = 0;
		$itemsProcessed = 0;
	
		 // Go throught items
		 $_err_message_arr = Array();
	
		if($version > '1.3.4')
		{
			$storeInfo = $objEcc->model_setting_store->getStores();
			
			$storeInfo = $storeInfo[0];
			
			$storeId =7;
			$stock_status_id = 6;
			
			$storeId = ((int)$storeInfo['store_id']<0) ? '7' : $storeInfo['store_id'];
			$stock_status_id = $storeInfo['stock_status_id'];
		}
		
		foreach($requestArray as $kv=>$vItem)//request
		{
					$status ="Success";
					$productID = $vItem['ItemID'];
					$objEcc->load->language('catalog/product');
					$objEcc->load->model('catalog/product');
					$ProductImage=$objEcc->model_catalog_product->getProductImages($productID);
					if($ProductImage[0]['image'])
					{
					$responseArray = array();
					$responseArray['ItemID']=	$productID;
					$responseArray['Image']	=	base64_encode(file_get_contents(DIR_IMAGE.$ProductImage[0]['image']));
					$Items->setItems($responseArray);
					break;
					}else{
					break;
					}
		
		} //End of Items foreach loop
		return $this->response($Items->getItems());
	}

  function addItemImage($itemid,$image,$image2,$storeid=1) {
		
		global $registry,$language,$objEcc,$version;
		
		if($image2) {
			
			$image_name2 = time().'1'.'.jpg';
			$str2	=	base64_decode($image2);
		}
		$objEcc->load->model('catalog/product');

		
		$image_name = time().'.jpg';
		
		
		
		//Base 64 encoded string $image
		$str	=	base64_decode($image);
	
		
		if(substr(decoct(fileperms(DIR_IMAGE.'cache/catalog/')),2) == '777') {
		
			$fp = fopen(DIR_IMAGE.'cache/catalog/'.$image_name, 'w+');
		
			fwrite($fp, $str);
			fclose($fp);
			
			if($image2) {
				$fp2 = fopen(DIR_IMAGE.'cache/catalog/'.$image_name2, 'w+');
				fwrite($fp2, $str2);
				fclose($fp2);
				
				$product_image2 = 'cache/catalog/'.$image_name2;
				
				$objEcc->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$itemid . "', image = '" . $objEcc->db->escape($product_image2) . "'");		
				
				$sql2= "UPDATE " . DB_PREFIX . "product SET image = '".$product_image2."' where product_id ='".$itemid."'";
					$objEcc->db->query($sql2);
			}
			#create the item's image
			$product_image = 'cache/catalog/'.$image_name;
			
			
			$objEcc->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$itemid . "', image = '" . $objEcc->db->escape($product_image) . "'");		
			
			

			$sql= "UPDATE " . DB_PREFIX . "product SET image = '".$product_image."' where product_id ='".$itemid."'";
					$objEcc->db->query($sql);
					
		
			
			//@unlink($_SERVER['DOCUMENT_ROOT'].'/opencart149/upload/image/'.$products_image);
			
			$image_node_array = array();
			$image_url = $objEcc->config->get('config_url').'image/';
			$image_node_array['ItemImages']=array('ItemID'=>$itemid, 'ItemImageID'=>$itemid, 'ItemImageFileName'=>$image_name, 'ItemImageUrl'=>$image_url.$product_image);
			
			return true;
			
		} else {
		
		return false;
		}
		
		
		
		
		return $this->response($Items->getItems());
   }
# Function to add the product in the store which found in QB

	function addProduct($username,$password,$data)
	{
	
		global $registry,$language,$objEcc,$version;
		$WgBaseResponse = new WgBaseResponse();	
		$Items = new WG_Items();
		$responseArray = array();
		$status = $this->CheckUser($username,$password);
	
		$objEcc->load->language('catalog/product');
		
		if($version > '1.3.4')
		{
			$objEcc->load->model('setting/store');
		}
		$objEcc->load->language('catalog/product');
		$objEcc->load->model('catalog/product');
		
	
		if($status!="0")
		{ //login name invalid
			return $status;
		}
		$requestArray = $data;
		if (!is_array($requestArray)) 
		{
			$Items->setStatusCode('9997');
			$Items->setStatusMessage('Unknown request or request not in proper format');				
			return $this->response($Items->getItems());
		}
	
		if (count($requestArray) == 0) 
		{
			$Items->setStatusCode('9996');
			$Items->setStatusMessage('REQUEST tag(s) doesnt have correct input format');
			return $this->response($Items->getItems());
		}
		$Items->setStatusCode('0');
		$Items->setStatusMessage('All Ok');
		
		$itemsCount = 0;
		$itemsProcessed = 0;
		 // Go throught items
		 $_err_message_arr = Array();
	
		if($version > '1.3.4')
		{
			$storeInfo = $objEcc->model_setting_store->getStores();
			
			$storeInfo = $storeInfo[0];
			
			$storeId =7;
			$stock_status_id = 6;
			
			$storeId = ((int)$storeInfo['store_id']<0) ? '7' : $storeInfo['store_id'];
			$stock_status_id = $storeInfo['stock_status_id'];
		}
		
		
		foreach($requestArray as $kv=>$vItem)//request
		{
				$itemsCount++;		
				$productcode=$vItem['ItemCode'];
				$product=$vItem['ItemName'];
				$descr=$vItem['ItemDesc'];
				$free_shipping=$vItem['FreeShipping'];
				$free_tax=$vItem['TaxExempt'];
				$tax_id=$vItem['TaxID'];
				$item_match=$vItem['ItemMatchBy'];
				$manufacturerid=$vItem['ManufacturerID'];
				$avail_qty=$vItem['Quantity'];
				$price=$vItem['UnitPrice'];
				$weight=$vItem['Weight'];
			
				if(is_array($vItem['Categories']))
				{
					$arrayCategories=$vItem['Categories'];
					$categoryid = array();
					foreach($arrayCategories as $k3=>$vCategories)//Categories
					{
						if(isset($vCategories['CategoryId'])&& $vCategories['CategoryId']!='')
						{ 
							$categoryid[] =  $vCategories['CategoryId'];				
						}
					}
				}
				 
				($free_shipping=="Y"||$free_shipping == "y")?$free_shipping=1:$free_shipping=0;
				
			
				##  insert product
				
				$sql = "select product_id  from ".DB_PREFIX."product WHERE model=".$this->mySQLSafe($productcode)."";
				$query = $objEcc->db->query($sql);
				$product_id =0;
				if(is_object($query ) && $query->num_rows)
					{
						$product_id = $query->row['product_id'];
						
					}	
						
	
				if(!$query->num_rows)
				{
				  $product_info['product_description'] =array("1"=>array("name"=>$product,"meta_description"=>"","description"=> $descr,"meta_title"=>$product));
					$product_info['model'] = $productcode;
					$product_info['sku'] = $productcode;
					$product_info['location'] = '';
					$product_info['keyword']='';
					$product_info['image']='';
					$product_info['manufacturer_id'] = (!$manufacturerid)?'0':$manufacturerid;
					$product_info['shipping'] =$free_shipping;
					//$product_info['date_available'] =date("Y-m-d");
					$product_info['date_available'] = date('Y-m-d', time() - 86400);
					$product_info['quantity'] = (int)$avail_qty;
					$product_info['stock_status_id'] = 6;
					$product_info['status'] = 1;
					$product_info['tax_class_id'] = (!$tax_id)?0:$tax_id;
					$product_info['price'] = round($price,4);
					$product_info['length'] = 0;
					$product_info['width'] = 0;
					$product_info['height'] = 0;
					$product_info['length_class_id'] = 1;
					$product_info['weight'] = round($weight,2);
					$product_info['weight_class_id'] = 5;
					$product_info['product_category']  = $categoryid;
					
					
					$product_info['product_store'] = array(0=>$storeId);
						
					#create the items
					$objEcc->model_catalog_product->addProduct($product_info);
					
					$sql = "select MAX(product_id) as product_id  from ".DB_PREFIX."product ";
					$query = $objEcc->db->query($sql);
	
					//$product_id =1;
					if($query->num_rows)
						{
							$product_id = $query->row['product_id'];
							
						}		
	
					#Calling function for add image
					if($vItem['Image']) {
						
						$this->addItemImage($product_id,$vItem['Image'],$vItem['Image2'],$storeid=1);
					}
								
					$Item = new WG_Item();
					$Item->setStatus('Success');
					$Item->setProductID($product_id);
					$Item->setSku(htmlentities($productcode));
					$Item->setProductName(htmlentities($product));
					$Items->setItems($Item->getItem());
					
					unset($product_id, $categoryid, $product_info);
				}
				else
				{
					
					$Item = new WG_Item();
					$Item->setStatus('Failed');
					$Item->setProductID($product_id);
					$Item->setSku(htmlentities($productcode));
					$Item->setProductName(htmlentities($product));
					$Items->setItems($Item->getItem());
				}	
			} //End of Items foreach loop
		return $this->response($Items->getItems());
	} // addProduct

	function asc_array_unique($a)
	{
		$res = array();
		foreach($a as $key => $value)
		{
			if(!array_key_exists(md5(serialize($value)), $res))
			{
				$res[md5(serialize($value))] = $value;
			}
		}
		return $res;
	}
# Return the Orders to sync with the QB according to the date and the staus and order id.
function getOrders($username,$password,$datefrom,$start_order_no,$ecc_excl_list,$order_per_response="25",$LastModifiedDate,$storeid=1,$others,$ccdetails,$MaxOrderNoInBatch,$OptionsBoost)
{	

	global $registry,$language,$objEcc,$version;
	global $common_sku_for_coupon_voucher;
		$set_option_boost = $OptionsBoost;
	    $orderlist='';
		foreach($others as $k=>$v)
		{
		$orderlist = $orderlist?($orderlist.",'".$v['OrderId']."'"):"'".$v['OrderId']."'";
		}
	#check for authorisation
    $status = $this->CheckUser($username,$password);
    if($status!==0)
    {
      return $status;
    }

			if($LastModifiedDate)
			{
				$datefrom2 = explode(" ",$LastModifiedDate);
				$datetime1 = explode("-",$datefrom2[0]);			
				$LastModifiedDate = $datetime1[2]."-".$datetime1[0]."-".$datetime1[1];			
				$LastModifiedDate .=" ".$datefrom2[1]; 

			}else
			{
				if(!isset($datefrom) or empty($datefrom)) 
				{
				$datefrom=date('Y-m-d');
				}
				else
				{
				$date = explode("-",$datefrom);
				$datefrom = $date[2] ."-" .$date[0] ."-" . $date[1];
				}
			}
	

	$st = explode(',',$ecc_excl_list);
	$status_ids = array();
	$st = str_replace("'",'',$st);
	
	$status_array = $this->OrderStatusWG();
	if(is_array($status_array))
		{
		foreach ($status_array as $status)
			{	
				$iInfo = $this->parseSpecCharsA($status);
					
					
				$status_array_new[$status['name']]=$status['order_status_id'];
				$status_array_order[$status['order_status_id']]=$status['name'];
				
			}
		}

	$str_excl_status='';	
	foreach($st as $statusName)	
		{
			if(array_key_exists($statusName,$status_array_new))
				{
					$str_excl_status[]=$status_array_new[$statusName];
				
				}
		
		}
	$str_excl_status =	"'".implode("','",$str_excl_status)."'";
	
	
	if($version >'1.3.4')
	{ 
		$objEcc->load->language('sale/order');
		$objEcc->load->model('sale/order');
	}
	else
	{ 
		$objEcc->load->language('customer/order');
		$objEcc->load->model('customer/order');
		
	}
	$objEcc->load->model('sale/order');
	
	$objEcc->load->language('catalog/product');
	$objEcc->load->model('catalog/product');
	$objEcc->load->language('catalog/manufacturer');	 
	$objEcc->load->model('catalog/manufacturer');
	$objEcc->load->model('localisation/weight_class');
	
	#Open cart 2.1.0.1 compliance issue.
	if($version >'1.3.4' && $version<'2.1.0.1')
	{
		$objEcc->load->model('sale/customer_group');
	}else{
		$objEcc->load->model('customer/customer_group');
	}
	
	$objEcc->load->model('localisation/length_class');

    if(!$orderlist && $LastModifiedDate)
	{

		if(!isset($MaxOrderNoInBatch) || $MaxOrderNoInBatch=='')
        {   
            	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` as o where order_status_id in (".$str_excl_status.") and DATE_FORMAT('".$LastModifiedDate."', '%Y-%m-%d %H:%i:%s' ) < DATE_FORMAT( o.date_modified, '%Y-%m-%d %H:%i:%s' ) ORDER BY o.date_modified,o.order_id ASC";
				
				$query_count = $objEcc->db->query($sql);
 	
				$sql = "SELECT o.order_id,o.total FROM `".DB_PREFIX."order` o WHERE o.order_status_id in (".$str_excl_status.") and DATE_FORMAT('".$LastModifiedDate."','%Y-%m-%d %H:%i:%s') < DATE_FORMAT( o.date_modified, '%Y-%m-%d %H:%i:%s' ) ORDER BY o.date_modified,o.order_id ASC LIMIT 0,$order_per_response";

			
        }else
		{
			$sql = "SELECT COUNT(*)+(SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` as o where order_status_id in (".$str_excl_status.") AND DATE_FORMAT('".$LastModifiedDate."', '%Y-%m-%d %H:%i:%s' ) < DATE_FORMAT( o.date_modified, '%Y-%m-%d %H:%i:%s' ) 
			ORDER BY `date_modified`,o.order_id ASC) AS total FROM `" . DB_PREFIX . "order` as o where order_status_id in (".$str_excl_status.") and order_id >".(int)$MaxOrderNoInBatch." 
			AND DATE_FORMAT('".$LastModifiedDate."', '%Y-%m-%d %H:%i:%s' ) = DATE_FORMAT( o.date_modified, '%Y-%m-%d %H:%i:%s' ) 
			ORDER BY `date_modified`,o.order_id ASC";
			
			$query_count = $objEcc->db->query($sql);
 			
			$sql = "
			select * from (
			SELECT o.order_id,o.total,o.date_modified FROM `".DB_PREFIX."order` o WHERE o.order_status_id in (".$str_excl_status.") and order_id >".(int)$MaxOrderNoInBatch." 
			and DATE_FORMAT('".$LastModifiedDate."', '%Y-%m-%d %H:%i:%s' ) = DATE_FORMAT( o.date_modified, '%Y-%m-%d %H:%i:%s' ) 
			union
			SELECT o.order_id,o.total,o.date_modified FROM `".DB_PREFIX."order` o WHERE o.order_status_id in (".$str_excl_status.") and DATE_FORMAT('".$LastModifiedDate."', '%Y-%m-%d %H:%i:%s' ) < DATE_FORMAT( o.date_modified, '%Y-%m-%d %H:%i:%s' ) 
			) as c 
			ORDER BY 3,1
			LIMIT 0,$order_per_response";
			
			
		}
		
	}elseif(!$orderlist && $datefrom)
	{
	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` as o where order_status_id in (".$str_excl_status.") and  order_id >".(int)$start_order_no." and DATE('".$datefrom."') <=DATE(o.date_added) ORDER BY o.order_id ASC";
	$query_count = $objEcc->db->query($sql);
	$sql = "SELECT o.order_id,o.total FROM `".DB_PREFIX."order` o WHERE o.order_status_id in (".$str_excl_status.") and order_id >".(int)$start_order_no." and DATE('".$datefrom."') <= 	DATE(o.date_added)  ORDER BY o.order_id ASC LIMIT 0,$order_per_response";
	
	}else{
	$sql = "SELECT COUNT(*) AS total FROM `".DB_PREFIX."order` o WHERE o.order_id in (".$orderlist.") ORDER BY o.order_id ASC ";
	$query_count = $objEcc->db->query($sql);
	$sql = "SELECT o.order_id,o.total FROM `".DB_PREFIX."order` o WHERE o.order_id in (".$orderlist.") ORDER BY o.order_id ASC ";
	}
		$query = $objEcc->db->query($sql); 
	    $orders_ids	= $query->rows;
		
        $no_orders = false;

        if(!$query_count->row['total'])
        {   
            $no_orders = true;
        }
		if(empty($orders_ids))
		{
		    $no_orders = true;
        }
		$Orders = new WG_Orders();
		$Orders->setStatusCode($no_orders?"9999":"0");
		$Orders->setStatusMessage($no_orders?"No Orders returned":"Total Orders:".$query_count->row['total']);
       
        if($no_orders){   
           return $this->response($Orders->getOrders());	
        }
		$sighn = array("{","}");
   if(is_array($orders_ids))
   foreach($orders_ids as $order)    
    { 
		$order_id = $order['order_id'];
		
		if($version >'1.3.4')
		{
			$oInfo =$objEcc->model_sale_order->getOrder($order_id);
		}
		else
		{ 
			$oInfo	= $objEcc->model_customer_order->getOrder($order_id);
		}
        $oInfo = $this->parseSpecChars($oInfo);	

		$Order = new WG_Order();
		$Order->setOrderId($order_id);
		$Order->setTitle("");
		$Order->setFirstName($oInfo['firstname']);
		$Order->setLastName($oInfo['lastname']);
		$Order->setDate(date("m-d-Y", strtotime($oInfo['date_added'])));
		$Order->setTime(date("H:i:s",strtotime($oInfo['date_added'])));

		$date_modified=$oInfo['date_modified'];	
		$date_added=$oInfo['date_added'];
		$date_added=date("m-d-Y H:i:s",strtotime($date_added));	
		$date_modified=date("m-d-Y H:i:s",strtotime($date_modified));
					
		$Order->setLastModifiedDate($date_modified?$date_modified:$date_added);

		$Order->setStoreID($oInfo['store_id']);
		$Order->setStoreName($oInfo['store_name']);
		$Order->setCurrency($oInfo['currency']);
		$Order->setWeight_Symbol('lbs');
		$Order->setWeight_Symbol_Grams('453.6');
		$Order->setStatus(strip_tags($status_array_order[$oInfo['order_status_id']]));
		
		if($version<= '1.3.4')
		{
			$OrderHistoryResults = $objEcc->model_customer_order->getOrderHistory($order_id);
			$OrderHistoryResults = end($OrderHistoryResults);
		}
		else
		{
			if($version < '1.5.0')	
			{
				$OrderHistoryResults = $objEcc->model_sale_order->getOrderHistory($order_id);
				$OrderHistoryResults = end($OrderHistoryResults);
			}
			else
			{
				$oInfo['comment'];
			}
		}
		
		if(class_exists("ModelSaleOrder"))
		{
			if(method_exists("ModelSaleOrder","getOrderHistories"))
			{
			
				$OrderHistoryResults = $objEcc->model_sale_order->getOrderHistories($order_id, $start = 0, $limit = 1000);
				foreach($OrderHistoryResults as $k=>$val)
				{
					$OrderHistoryResults['comment']=$val['comment'];
				}
			}
		}
		
		$Order->setNotes(strip_tags($OrderHistoryResults['comment']));
		unset($OrderHistoryResults['comment']);
		$Order->setFax($oInfo['fax']?$oInfo['fax']:'');
		$Order->setComment(strip_tags($oInfo['comment']));
		
		$Bill = new WG_Bill();

		$query = $objEcc->db->query("SELECT oh.comment FROM " . DB_PREFIX . "order_history oh WHERE oh.order_id = '" . (int)$order_id . "' ORDER BY oh.order_history_id ");
	
        if($oInfo['payment_method']!='Cash On Delivery' && $query->num_rows>0 )
        {
			$cardDataArray  = array();
			$newLineBreakup = array();
			
			foreach($query->rows as $commentsArray)
				{
					
					$newLineBreakup = explode("\n",$commentsArray['comment']);
					if(count($newLineBreakup))
						foreach($newLineBreakup as $fields)
						{
							$fieldsExtract = explode(":",$fields,2);
							
							if(count($fieldsExtract)>1)
								{
									$cardDataArray[$fieldsExtract[0]] = $fieldsExtract[1];
								
								}
						}
				}
			
			$paymentTranscationKeys = array("TRANSACTIONID","VPSTxId","Transaction ID");
			$paymentCardNameKeys	= array("CardType");
			$transactionID ='';
			foreach($paymentTranscationKeys as $PValues)
				{
					if(array_key_exists($PValues,$cardDataArray))
						{
							
								$transactionID = str_replace($sighn,"",$cardDataArray[$PValues]);
								break;
						}
				
				}
			$cartdName ='';
			foreach($paymentCardNameKeys as $CValues)	
				{
					if(array_key_exists($CValues,$cardDataArray))
					{
						$cartdName = $cardDataArray[$CValues];
					}
				}
				
			$CreditCard = new WG_CreditCard();
		
		if($ccdetails!=='DONOTSEND')
		{	
			$CreditCard->setCreditCardType($cartdName);
			$CreditCard->setCreditCardCharge(100);
			$CreditCard->setExpirationDate('');
			$CreditCard->setCreditCardName('');
            if  ( $cardDataArray['Last4Digits'])
            {
				$CreditCard->setCreditCardNumber($cardDataArray['Last4Digits']);
            }
            else
            {
				$CreditCard->setCreditCardNumber("");
           
            }
			$CreditCard->setCVV2('');
			$CreditCard->setAdvanceInfo("");
			
		}
                
                //CODE TO FETCH TRANSACTION ID, ONLY IF PAYMENT METHOD IS PAYPAL 
            if(stripos($oInfo['payment_method'],'paypal') !== false){
                $objEcc->load->model('payment/'.$oInfo['payment_code']);
                $model_name = "model_payment_".$oInfo['payment_code'];
                $paypal_order = $objEcc->$model_name->getOrder($order_id);
                //print_r($paypal_order);
                //die('ok');
                if(!empty($paypal_order))
                $transactionID = $paypal_order['authorization_id'];
            }
                
            $CreditCard->setTransactionId($transactionID);
			$CreditCard->getCreditCard();
			$Bill->setCreditCardInfo($CreditCard->getCreditCard());
		   
		   
        }
        // Orders/Bill info
   
   		$Bill->setPayMethod($oInfo['payment_method']);
		$Bill->setPayStatus(strip_tags($status_array_order[$oInfo['order_status_id']]));
		$Bill->setTitle("");
		$Bill->setFirstName($oInfo['payment_firstname']!=""?$oInfo['payment_firstname']:"");
		$Bill->setLastName($oInfo['payment_lastname']!=""?$oInfo['payment_lastname']:"");
		$Bill->setCompanyName($oInfo['payment_company']);
		$Bill->setAddress1($oInfo['payment_address_1']?$oInfo['payment_address_1']:'');				
		$Bill->setAddress2($oInfo['payment_address_2']?$oInfo['payment_address_2']:'');				
		$Bill->setCity($oInfo['payment_city']);				
		$Bill->setState($oInfo['payment_zone']);				
		$Bill->setZip($oInfo['payment_postcode']);				
		$Bill->setCountry($oInfo['payment_country']);				
		$Bill->setEmail($oInfo['email']);	
		$Bill->setPhone($oInfo['telephone']?$oInfo['telephone']:"");				
		$Bill->setPONumber('');	
		
		
		if(class_exists("ModelSaleCustomerGroup"))
		{
			if(method_exists("ModelSaleCustomerGroup","getCustomerGroup"))
			{
			    $oInfo['customer_group_id']=!empty($oInfo['customer_group_id'])?$oInfo['customer_group_id']:0;
				$UserGroup_Info	= $objEcc->model_sale_customer_group->getCustomerGroup($oInfo['customer_group_id']);
			}else{
			    $UserGroup_Info['name']="";
			}
		}else{
		   	$UserGroup_Info['name']="";
		}
		$Bill->setGroupName($UserGroup_Info['name']);							
		
		$Order->setOrderBillInfo($Bill->getBill());	
  
        // Orders/Ship info
        $ship_method = $oInfo['shipping_method'];
        $ship_method = preg_replace("/&\w+;/is", "", $ship_method);
        $ship_method = htmlentities($ship_method, ENT_QUOTES);
   
        $ship_method = preg_replace("/([^(]+)(\([^)]*?\))?\s*(\([^)]+\))/is", "$1$3", $ship_method);
   		$Ship =new WG_Ship();
		$Ship->setShipMethod($ship_method?$ship_method:'');
		$Ship->setCarrier($ship_method?$ship_method:'');
		$Ship->setTrackingNumber("");
		$Ship->setTitle("");
		$Ship->setFirstName($oInfo['shipping_firstname']?$oInfo['shipping_firstname']:"");
		$Ship->setLastName($oInfo['shipping_lastname']?$oInfo['shipping_lastname']:"");
		$Ship->setCompanyName($oInfo['shipping_company']);
		
		$Ship->setAddress1($oInfo['shipping_address_1']?$oInfo['shipping_address_1']:'');
		$Ship->setAddress2($oInfo['shipping_address_2']?$oInfo['shipping_address_2']:'');
		$Ship->setCity($oInfo['shipping_city']);
		$Ship->setState($oInfo['shipping_zone']);
		$Ship->setZip($oInfo['shipping_postcode']);
		$Ship->setCountry($oInfo['shipping_country']);
		$Ship->setEmail($oInfo['email']);
		$Ship->setPhone($oInfo['telephone']?$oInfo['telephone']:"");
		$Order->setOrderShipInfo($Ship->getShip());
		

   		if($version >'1.3.4')
		{
   			$products = $objEcc->model_sale_order->getOrderProducts($order_id);
		}
		else
		{
			$products = $objEcc->model_customer_order->getOrderProducts($order_id);
		}

        # fetch item of given order
		if(is_array($products ))
        foreach($products as $product)
        {
           
           	$product_id = (int)$product['product_id'];
           	$product_info  = $objEcc->model_catalog_product->getProduct($product_id);

			$options = array();
		 	if($version > '1.3.4')
			{
		 		$options = $objEcc->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
			}
			else
			{
				$options = $objEcc->model_customer_order->getOrderOptions($order_id, $product['order_product_id']);
			}
			 
			if($set_option_boost)
			{		
				if(count($options)) 
				{
				
					foreach($options as $option)
					{
							$Value_array=explode("[",$option['value']);
							$Req_sku_array=explode("]",$Value_array[1]);
							$Req_sku=html_entity_decode(strip_tags($Req_sku_array[0]));
							
					}
				}		               
			} 
			 
			 
			$Item = new WG_Item();	
			
				if(!empty($Req_sku))
				{
				$sku=html_entity_decode($Req_sku);
				}else{
				$sku=html_entity_decode($product['model']);
				
				}
			$Item->setItemID($product_id);
			$Item->setItemCode($sku);
			$Item->setItemDescription(html_entity_decode(strip_tags($product['name'])));
			$Item->setItemShortDescr(strip_tags(html_entity_decode(substr($product_info['description'],0,4000))));
			
		
				$manufacturerArray = $objEcc->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
	
				$Item->setManufacturer($manufacturerArray['name']);	
				$Item->setQuantity($product['quantity']);
				$Item->setUnitPrice($product['price']);
				$Item->setWeight($product_info['weight']);
				$Item->setFreeShipping(($product_info['shipping'])?'Y':'N');
				
				$isdiscounted=$objEcc->model_catalog_product->getProductDiscounts($product_id);
	
				$Item->setDiscounted(count($isdiscounted)?"Y":"N");
				$Item->setshippingFreight('0');
				
				
				if($version > '1.3.4')
				{
					$weightUnit = $objEcc->model_localisation_weight_class->getWeightClass($product_info['weight_class_id']);
				}
				else
				{
					$weightUnit = $objEcc->model_localisation_weight_class->getWeightClasses($product_info['weight_class_id']);
				}
				$Item->setWeight_Symbol($weightUnit['unit']);
				$Item->setWeight_Symbol_Grams(round($weightUnit['value'],2));
				$Item->setTaxExempt((!$product_info['tax_class_id'])?'Y':'N');
				$Item->setOneTimeCharge('0.00');
				$Item->setItemTaxAmount("");
				
				$Dimention['Length']=(float)$product_info['length'];
				$Dimention['Width']=(float)$product_info['width'];
				$Dimention['Height']=(float)$product_info['height'];
	
				if(class_exists("ModelLocalisationLengthClass"))
				{
					if(method_exists("ModelLocalisationLengthClass","getLengthClassDescriptions"))
					{
					
						$product_info['length_class_id']=!empty($product_info['length_class_id'])?$product_info['length_class_id']:"";
						$Unit_array = $objEcc->model_localisation_length_class->getLengthClassDescriptions($product_info['length_class_id']);
						
					}else{
						$Unit_array['1']['title']="";
					}
				}else{
					$Unit_array['1']['title']="";
				}
				
				$Unit=$Unit_array['1']['title'];
				$Dimention['Unit'] = empty($Unit)?"":$Unit;
				$Item->setDimention($Dimention);
				
				
				$responseArray['ItemOptions']=array();
				
				$Itemoption = new WG_Itemoption();
				if(count($options)) 
				foreach($options as $option)
				{
					$Itemoption->setOptionName(html_entity_decode(strip_tags($option['name'])));
					$Itemoption->setOptionValue(html_entity_decode(strip_tags($option['value'])));	
					$Item->setItemOptions($Itemoption->getItemoption());
				}
	 
			$Order->setOrderItems($Item->getItem());	
			} // end items
	
			if($version > '1.3.4')
			{
				$orders_arr = $objEcc->model_sale_order->getOrderTotals($order_id);
			}
			else
			{
				$orders_arr = $objEcc->model_customer_order->getOrderTotals($order_id);
			}
			unset($tax,$shipping);
			
	
			$flage_for_multipal_tax=0;
			if(array_key_exists('code',$orders_arr))
			{
				foreach($orders_arr as $values)
				{	
				
					if($values['code']=='shipping')
					{
						$shipping = $objEcc->currency->format(round($values['value'],2), $oInfo['currency'], $oInfo['value'],false);
					}else if($values['code']=='tax' && $flage_for_multipal_tax==0)
					{
					
						$tax = $objEcc->currency->format(round($values['value'],2), $oInfo['currency'], $oInfo['value'],false); 
						  $flage_for_multipal_tax=1;          
					}else if($values['code']!='total' && $values['code']!='sub_total')
					{
							$Item = new WG_Item();	
							
							$Item->setItemCode(html_entity_decode(strip_tags($values['code'])));
							
							$title=str_replace(":","",$values['title']);
							$Item->setItemDescription(html_entity_decode(strip_tags($title)));
							$Item->setQuantity('1');
							$Item->setUnitPrice('-'.abs($values['value']));
							$Item->setWeight('0');
							$Item->setTaxExempt('N');
							$Order->setOrderItems($Item->getItem());		
					
					}else if($values['code']=='tax'  &&  $flage_for_multipal_tax==1)
					{
							$Item = new WG_Item();	
							
							$Item->setItemCode(html_entity_decode(strip_tags($values['code'])));
								$title=str_replace(":","",$values['title']);
							$Item->setItemDescription(html_entity_decode(strip_tags($title)));
						
							//$Item->setItemDescription(str_replace(":","",$values['title']));
							$Item->setQuantity('1');
							$Item->setUnitPrice('-'.abs($values['value']));
							$Item->setWeight('0');
							$Item->setTaxExempt('N');
							$Order->setOrderItems($Item->getItem());	
					}
				}
			
			}else
			{
				
				foreach($orders_arr as $values)
				{	
				#Ex:-
				//UPS Ground &nbsp;&nbsp;&nbsp;(1 to 5 Days):
				//UPS Ground (1 to 5 Days)
				$values['title']=str_replace("&nbsp;","",$values['title']);
				
			
	if(strstr(strtolower($values['title']),'shipping') || strstr(strtolower($values['title']),strtolower($ship_method)))
					{
						
						$shipping = $objEcc->currency->format(round($values['value'],2), $oInfo['currency'], $oInfo['value'],false);
					}elseif(strstr(strtolower($values['title']),'tax') || strstr(strtolower($values['title']),'vat') && $flage_for_multipal_tax==0)
					{
						
						$tax = $objEcc->currency->format(round($values['value'],2), $oInfo['currency'], $oInfo['value'],false); 
						$flage_for_multipal_tax=1;     
					}elseif(!strstr(strtolower($values['title']),'total') && !strstr(strtolower($values['title']),'sub-total'))
					{
						
						$Item = new WG_Item();
						
						if($common_sku_for_coupon_voucher == true)
						{
							$common_sku = explode('(', $values['title']);
							
							$Item->setItemCode(html_entity_decode(strip_tags($common_sku[0])));
						}
						else
						{
							$Item->setItemCode(strtolower(str_replace(":","",$values['title'])));
						}
						
						$title=str_replace(":","",$values['title']);
						$Item->setItemDescription(html_entity_decode(strip_tags($title)));
						$Item->setQuantity('1');
						$Item->setUnitPrice($values['value']);
						$Item->setWeight('0');
						$Item->setTaxExempt('N');
						$Order->setOrderItems($Item->getItem());				
					}else if(strstr(strtolower($values['title']),'tax')  &&  $flage_for_multipal_tax==1)
					{
							$Item = new WG_Item();	
							$Item->setItemCode(html_entity_decode(strip_tags($values['code'])));
							$title=str_replace(":","",$values['title']);
							$Item->setItemDescription(html_entity_decode(strip_tags($title)));
							$Item->setQuantity('1');
							$Item->setUnitPrice('-'.abs($values['value']));
							$Item->setWeight('0');
							$Item->setTaxExempt('N');
							$Order->setOrderItems($Item->getItem());	
					}	
				}			
			
			}  
				$charges =new WG_Charges();
				$charges->setDiscount("0.00");
				$charges->setStoreCredit('0.00');
				$charges->setTax($tax?$tax:'0.00');
				$charges->setShipping($shipping?$shipping:'0.00');
							
				$total_price='0.0'; 
				$total_price = $objEcc->currency->format(round($oInfo['total'],2), $oInfo['currency'], $oInfo['value'],false);
				
				$charges->setTotal($total_price);
				$Order->setOrderChargeInfo($charges->getCharges());
				
				$Order->setShippedOn(date("m-d-Y", strtotime($oInfo['date_added'])));
				$Order->setShippedVia($ship_method?$ship_method:'');
				
	
			// echo "<li>".$order_id."====".$tax."======".$shipping;
			$MaxOrderNoInBatch=$order_id;
			$dat_modified=explode(" ",$oInfo['date_modified']);;
			$MaxdateNoInBatch=$dat_modified[0];
			
			$Orders->setOrders($Order->getOrder());     
		} 

		 $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` as o where order_status_id in (".$str_excl_status.") and order_id >".(int)$MaxOrderNoInBatch."  and DATE_FORMAT('".$MaxdateNoInBatch."', '%Y-%m-%d ' ) = DATE_FORMAT( o.date_modified, '%Y-%m-%d' ) ORDER BY o.date_modified,o.order_id ASC";
		$query = $objEcc->db->query($sql);
		if($query->row['total']>0)
		{
			$Orders->setMaxOrderNoInBatch($MaxOrderNoInBatch); 
		}else
		{
				$Orders->setMaxOrderNoInBatch(""); 
		
		}

		return $this->response($Orders->getOrders());
	} 
 

public function getProductIdByorderProductId_wg($orderProductID,$objEcc) {
		$order_product_query = $objEcc->db->query("SELECT * FROM " . DB_PREFIX . "order_product op where  order_product_id ='".$orderProductID."' ");
		foreach ($order_product_query->rows as $product_data) {
		$productid=$product_data['product_id'];
		}	
		return $productid;
	}

	public function getProductOptions_wg($product_id,$objEcc) {
		$product_option_data = array();
		
		$product_option_query = $objEcc->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$objEcc->config->get('config_language_id') . "'");
		
		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();	
				
				$product_option_value_query = $objEcc->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$objEcc->config->get('config_language_id') . "'");
				
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'image'                   => $product_option_value['image'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],						
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix'],
						'ob_sku'                  => $product_option_value['ob_sku']						
					);
				}
			
				$product_option_data[] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'product_option_value' => $product_option_value_data,
					'required'             => $product_option['required']
				);				
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
				);				
			}
		}	
		
		return $product_option_data;
	}
# Returns the Products with the Options and the Variants

	function getItems($username,$password,$DownloadType,$UpdatedDate,$start=0,$limit=500,$datefrom,$OptionsBoost,$others) {
		global $registry,$language,$objEcc,$version;
		$date_time = "";
		$set_option_boost = $OptionsBoost;
		$status = $this->CheckUser($username,$password);
	   
		if($status!==0)
		{
		  return $status;
		}
		
		if($UpdatedDate!="")
				{
				
					$UpdatedDate=explode(".0",$UpdatedDate);
					$ini_date=explode(" ",$UpdatedDate[0]);
					$mid_date=explode("/",$ini_date[0]);
					$final_date=$mid_date[2]."-".$mid_date[0]."-".$mid_date[1];
					$date_time=$final_date;
	
				}
		
	
		$objEcc->load->language('catalog/product');
		$objEcc->load->language('catalog/category');
		$objEcc->load->model('catalog/category');
		$objEcc->load->model('catalog/product');
		$objEcc->load->model('catalog/manufacturer');
		
		$objEcc->load->model('localisation/weight_class');
                
                $selectedProducts = "";
                //$others[0]['ItemCode'] = "'model3','model15','Product 5'";
                
                if(isset($others[0]['ItemCode']) && trim($others[0]['ItemCode'])!='') {
                    $selectedProducts = $others[0]['ItemCode'];
                }
                
                $product_total =$this->getTotalProducts_updatedate($objEcc,$date_time,$selectedProducts);
                
                $data = array(
                    'sort'  => 'pd.name',
                    'order' => 'ASC',
                    "datemodified"=>$date_time,
                    'filter_model' => $selectedProducts,
                    'start' => $start,
                    'limit' => $limit
                    );//die('test1');
                $AllProducts = $this->getProducts_updatedate($data,$objEcc,$selectedProducts);
		
		$allWeight= $objEcc->model_localisation_weight_class->getWeightClasses();
		
	
		$manufacturersList = $objEcc->model_catalog_manufacturer->getManufacturers();
		$allCategories = $objEcc->model_catalog_category->getCategories(0); 
	
				$Items = new WG_Items();
				
				
				$system_date_val=date("m/d/Y , H:i:s");
				$Items->setServertime($system_date_val);
		   
			if((int)$product_total>0)
			{
					$Items->setStatusCode('0');
					$Items->setStatusMessage('All Ok');
					$Items->setTotalRecordFound((int)$product_total?(int)$product_total:'0');
				foreach($AllProducts as $key => $values)
				{
					$iInfo =$values;
					$Item = new WG_Item();    
					$desc = substr($iInfo['description'],0,4000);
					$desc = empty($desc)?$iInfo['Name']:$desc;
					$product_id = $iInfo['product_id'];
					$Item->setItemID($product_id);
					$Item->setItemCode(empty($iInfo['model'])?html_entity_decode(strip_tags($iInfo['name'])):html_entity_decode(strip_tags($iInfo['model'])));
					$Item->setItemDescription(html_entity_decode(strip_tags($iInfo['name']))?html_entity_decode(strip_tags($iInfo['name'])):"");
					$Item->setItemShortDescr(html_entity_decode(strip_tags($desc))?html_entity_decode(strip_tags($desc)):"");
					$iInfo = $this->parseSpecCharsA($values);
					$ProductCategories = $objEcc->model_catalog_product->getProductCategories($product_id);
					
					if(is_array($allCategories) && is_array($ProductCategories))
					$categoriesI = 0;
					foreach($allCategories as $cValue)
					{
						if(in_array($cValue['category_id'],$ProductCategories))
						{
							$parentId_array = $objEcc->model_catalog_category->getCategory($cValue['category_id']);
							$CategoryDesc_array = ($objEcc->model_catalog_category->getCategoryDescriptions($cValue['category_id']));
							$catIndex = key($CategoryDesc_array);
							unset($catArray);
							$catArray['CategoryId'] = $cValue['category_id'];
							$catArray['CategoryName'] =  $CategoryDesc_array[$catIndex]['name'];
							$catArray['ParentId'] = $parentId_array['parent_id'];
							$Item->setCategories($catArray);
							$categoriesI++;
						
						}
					}
	
					if($iInfo['manufacturer_id']>0 && is_array($manufacturersList))
					{
						
						foreach($manufacturersList as $mValue)
						{
							
							if($mValue['manufacturer_id'] == $iInfo['manufacturer_id'])
								{
	
									$Item->setManufacturer(htmlspecialchars($mValue['name']));						
									break;
								}		
						}
					}
					else{
						
						$Item->setManufacturer("");
					}
				  
					$options = $objEcc->model_catalog_product->getProductOptions($product_id);
	
					$inventory =0;
					$inventory = $iInfo['quantity'];
	
					$Item->setQuantity(intval($inventory)); 
				
					$Item->setUnitPrice($iInfo['price']);
					$Item->setListPrice($iInfo['price']);
					$Item->setWeight($iInfo['weight']);
					$Item->setLowQtyLimit('0');
					
					$Item->setCreatedAt(date("d-m-Y", strtotime($iInfo['date_added'])));
					
					if((int)$iInfo['shipping'])
						$Item->setFreeShipping('Y');
					else
						$Item->setFreeShipping('N');
					   
					
				   if(count($objEcc->model_catalog_product->getProductDiscounts($product_id)))
						$Item->setDiscounted("Y");
					else
						$Item->setDiscounted("N");
					
				 
				   $Item->setShippingFreight('0');
					
					$lFlag = false;
					if(is_array($allWeight))
					foreach($allWeight as $wValues)
						{
							if ($wValues['weight_class_id'] == $iInfo['weight_class_id'])
								{								
									$lFlag= true;
									break;
								}
						}
						
					$Item->setWeight_Symbol($wValues['title']);
					$Item->setWeight_Symbol_Grams( round($wValues['value'],2));
				   
					if($iInfo['tax_class_id'])
					{
					   // $xmlResponse->createTag("TaxExempt", array(), 'Y',$itemNode, __ENCODE_RESPONSE);
					   $Item->setTaxExempt('Y');
					}else{
						//$xmlResponse->createTag("TaxExempt", array(), 'N',$itemNode, __ENCODE_RESPONSE);
						$Item->setTaxExempt('N');
					}
				 
					$Variants = new WG_Variants();
					if($set_option_boost)
					{
						$All0ptions = $this->getProductOptions_wg($product_id,$objEcc); 
					}else{
						$All0ptions = $objEcc->model_catalog_product->getProductOptions($product_id); 
					}
					$Options = new WG_Options();
					if(is_array($All0ptions))
					{
						foreach($All0ptions as $oValues)
						{
							
							$oindex = key($oValues['language']);
							
							if(is_array($oValues['product_option_value']))
							{
						
								foreach($oValues['product_option_value'] as $opValue)
								{
						if($set_option_boost)
                        {
									$VariantArray['ItemCode']=$opValue['ob_sku'];
									$VariantArray['VarientID']=$opValue['product_option_value_id'];
									$VariantArray['Quantity']=$opValue['quantity']?$opValue['quantity']:0;
									$VariantArray['UnitPrice']=$opValue['price'];
									$VariantArray['Weight'] = number_format($opValue['weight'], 2 );
									$Item->setItemVariants($VariantArray);
                        }else{
 	                                $oPindex=1;
									$oPindex = key($opValue['language']);
									$optionArray['ItemOption']['ID'] = $oValues['product_option_id'];
									$optionArray['ItemOption']['Value'] = $opValue['language'][$oPindex]['name'];
									$optionArray['ItemOption']['Name'] = $oValues['language'][$oindex]['name'];
									$Item->setItemOptions($optionArray);
						}							
							
								}
							}
						
						}
					}
					$Items->setItems($Item->getItem()); 
				} // Items
		   
			}
			else
			{
				$Items->setStatusCode('0');
				$Items->setStatusMessage('All Ok');
			}
	
		  
		return $this->response($Items->getItems());
	}

# Returns the Company Info of the Store
	function getCompanyInfo($username,$password)
	{
		global $registry,$language,$objEcc,$version ;
		
		$WgBaseResponse = new WgBaseResponse();	
		$CompanyInfo = new WG_CompanyInfo();
		$responseArray = array();
		$status = $this->CheckUser($username,$password);
		#check for authorisation
		
		if($status!==0)
		{
		  return $status;
		}
		
		$Address1=''; $Address2='';$City='';$State='';$Country='';$Zipcode='';$Phone='';$Fax='';$Email='';$Website='';
		
		$add_arr = explode("\n",$objEcc->config->get('config_address'));
		
		if(is_array($add_arr))
			{
				$AddInfoArray= array();
				foreach($add_arr as $values)
					{
					
						//echo "\n==".$values;
						 $AddInfoArray[] = htmlspecialchars($values);
					}
			
			}
					
		$Address1	= empty($AddInfoArray[0])?'':($AddInfoArray[0]);
		$Address2	= empty($AddInfoArray[1])?'':($AddInfoArray[1]);
		$City		= empty($AddInfoArray[2])?'':($AddInfoArray[2]);	
		$State 		= empty($AddInfoArray[3])?'':($AddInfoArray[3]);
		$Zipcode	= empty($AddInfoArray[4])?'':($AddInfoArray[4]);
		$Country	= empty($AddInfoArray[5])?'':($AddInfoArray[5]);
		$Phone		= htmlspecialchars($objEcc->config->get('config_telephone'));
		$Fax		= $objEcc->config->get('config_fax');
		$Email		= htmlspecialchars($objEcc->config->get('config_email'));
		$Website	= htmlspecialchars($objEcc->config->get('config_url'));
		
		$CompanyInfo->setStatusCode('0');
		$CompanyInfo->setStatusMessage('All Ok');
		$CompanyInfo->setStoreID($objEcc->config->get('config_owner'));
		$CompanyInfo->setStoreName($objEcc->config->get('config_owner'));
		$CompanyInfo->setAddress(htmlspecialchars($Address1, ENT_NOQUOTES));
		$CompanyInfo->setAddress2(htmlspecialchars($Address2, ENT_NOQUOTES));
		$CompanyInfo->setCity($City);
		$CompanyInfo->setState($State);
		$CompanyInfo->setZipcode((int)$Zipcode);
		$CompanyInfo->setCountry($Country);
		$CompanyInfo->setPhone((int)$Phone);
		$CompanyInfo->setFax($Fax);
		$CompanyInfo->setEmail($Email);
		$CompanyInfo->setWebsite($Website);
		
		return $this->response($CompanyInfo->getCompanyInfo());	
	}


# Function to check the admin username and password and also the eCC Version and Store Version
	function checkAccessInfo($username,$password)
	{
		global $registry,$language,$objEcc,$version ;
		$WgBaseResponse = new WgBaseResponse();	
		$responseArray = array();
		$status = $this->CheckUser($username,$password);
		
		if($status!="0")
		{  
			return $status;	
		}
		else {
			$code = "0";
			$WgBaseResponse->setStatusCode('0');
			$message = "Successfully connected to your online store.";
	
			if($version < "1.3.4" || $version > "2.2.0.0")
			{
				$WgBaseResponse->setStatusMessage($message ." However, your store version is " . $version ." which hasn't been fully tested with eCC. If you'd still like to continue, click OK to continue or contact Webgility to confirm compatibility.");
				
			}
			else
			{
				
				$WgBaseResponse->setStatusMessage($message);
			}
		}
		return $this->response($WgBaseResponse->getBaseResponse());
	}

# private function to authenticate user with every method.
	public  function CheckUser($username,$password)
	{

	global $registry ,$db,$language,$objEcc;
	
		$responseArray = array();
		$WgBaseResponse = new WgBaseResponse();		

	$result = $objEcc->user->login($username,$password); 
	if ($result==false)
	{
		
					$WgBaseResponse->setStatusCode('1');
					$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
					$response=$this->response($WgBaseResponse->getBaseResponse());
					return $response;		
	}
	
	return 0;

	}

	# function to escape html entity characters
	function parseSpecCharsA($arr)
	{   
	   if (is_array($arr))
	   {       		
		   foreach($arr as $k=>$v)
		   {	
			 if(is_array($v))
				parseSpecCharsA($v);
			 else
				$arr[$k] = addslashes(htmlentities($v, ENT_QUOTES));
		   }
		  return $arr; 
	   }
	   else  return addslashes(htmlentities($arr, ENT_QUOTES));
	}

	function parseSpecChars($obj) 
	{
	  foreach($obj as $k=>$v)
	  {
		$obj->$k = addslashes(htmlentities($v, ENT_QUOTES)); 
	  }
	  return $obj;
	}

	function getCustomersNew($username,$password,$datefrom,$customerid,$limit,$storeid=1,$others)
	{

	global $registry,$language,$objEcc,$version ;
	$datefrom =$datefrom ?$datefrom:0;		
	$status = $this->CheckUser($username,$password);
	if($status!="0")
	{		
		return $status;
	}
	# Open cart 2.1.0.1 compliance issue.
	if($version> '1.3.4' && $version<'2.1.0.1')
	{
		$objEcc->load->model('sale/customer');	
		$objEcc->load->language('sale/customer');	
	}else{
		$objEcc->load->model('customer/customer');	
		$objEcc->load->language('customer/customer');	
	} 						 
	$Customers = new WG_Customers();
	
		# Open cart 2.1.0.1 compliance issue.
		if($version> '1.3.4' && $version<'2.1.0.1'){
			$customers_count=$objEcc->model_sale_customer->getCustomers($data);
		}else{
			$customers_count=$objEcc->model_customer_customer->getCustomers($data);
		}
		$total_count=count($customers_count);	
		$data=array();
		$data['customerid']=intval($customerid);
		$data['start']=0;
		$data['limit']=$limit;
			
		$customersArray=$this->getCustomers_wg($objEcc,$data);
		

		$no_customer =false;
		
		if($total_count<=0)
		{
			$no_customer = true;
		}
		$Customers->setStatusCode($no_customer?"0":"0");	
		$Customers->setStatusMessage($no_customer?"No Customer returned":"Total Customer:".count($customersArray));	
		$Customers->setTotalRecordFound($total_count?$total_count:'0');	
		$Customers->setTotalRecordSent(count($customersArray)?count($customersArray):'0');	
		$i=0;
		foreach($customersArray as $customer)
		{
	
		if($customer['address_id']!=0)
		{
			$add_query="SELECT * FROM " . DB_PREFIX . "address where customer_id='".$customer["customer_id"]."' and address_id='".$customer['address_id']."' ";
		}else{
			$add_query="SELECT * FROM " . DB_PREFIX . "address where customer_id='".$customer["customer_id"]."' ";
		}
		$query_add= $objEcc->db->query($add_query);
			
			$query_country = $objEcc->db->query("SELECT name FROM " . DB_PREFIX . "country where country_id='".$query_add->row['country_id']."' " );
			$country_code=$query_country->row['name'];
			
			$query_Zone= $objEcc->db->query("SELECT name  FROM " . DB_PREFIX . "zone where zone_id='".$query_add->row['zone_id']."' " );
			$region=$query_Zone->row['name'];
			
			$CustomerObj = new WG_Customer();
			$CustomerObj->setCustomerId($customer["customer_id"]);
			$CustomerObj->setFirstName($customer["firstname"]);
			$CustomerObj->setMiddleName("");
			$CustomerObj->setLastName($customer["lastname"]);
			$CustomerObj->setCustomerGroup($customer["customer_group_id"]);
			$CustomerObj->setcompany($query_add->row['company']);
			$CustomerObj->setemail($customer["email"]);
			if($customer['newsletter']==1){
			$CustomerObj->setsubscribedToEmail("true");
			}else{
			$CustomerObj->setsubscribedToEmail("false");
			}
			$CustomerObj->setAddress1($query_add->row['address_1']);
			$CustomerObj->setAddress2($query_add->row['address_2']);
			$CustomerObj->setCity($query_add->row['city']);
			$CustomerObj->setState($region);
			$CustomerObj->setZip($query_add->row['postcode']);
			$CustomerObj->setCountry($country_code);
			$CustomerObj->setPhone($customer["telephone"]);
		
			if(!isset($customer["date_added"]) || $customer["date_added"]=='') {
			$customer["date_added"] = '2007-01-01 00:00:00';
			}
			$CustomerObj->setCreatedAt($customer["date_added"]);
			$CustomerObj->setUpdatedAt("2007-01-01 00:00:00");
			$CustomerObj->setGroupName($customer['customer_group']);
			$Customers->setCustomer($CustomerObj->getCustomer());
			unset($country_code,$region);
		}
	return $this->response($Customers->getCustomers());	
	}
	function getCustomers_wg($objEcc,$data)
	{
		  global $version;
		  
		  if($version>="1.5.3")
		  {
			$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c
			LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) 
			LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id)
			where c.customer_id>".$data['customerid']." order by c.customer_id ASC ";
		  }else{
			$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) where c.customer_id>".$data['customerid']." order by c.customer_id ASC ";
		   }		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				
		
			$query = $objEcc->db->query($sql);
			
			return $query->rows;	
		
	}
#Add new functions related to customer
			function addCustomers($username,$password,$data,$storeid=1,$others='') {

					global $registry,$language,$objEcc,$version;
					
					$status = $this->CheckUser($username,$password);
					if($status!="0")
					{		
						return $status;
					}
					#Open cart 2.1.0.1 compliance issue.
					if($version> '1.3.4' && $version<'2.1.0.1')
					{
						$objEcc->load->model('sale/customer');	
						$objEcc->load->language('sale/customer');	
					}else{
						$objEcc->load->model('customer/customer');	
						$objEcc->load->language('customer/customer');	
					} 
						$Customers = new WG_Customers();
						$Customers->setStatusCode('0');
						$Customers->setStatusMessage('All Ok');
					
					$requestArray = $data;
					
					if (!is_array($requestArray)) {
						$Items->setStatusCode('9997');
						$Items->setStatusMessage('Unknown request or request not in proper format');				
						return $this->response($Items->getItems());
					}
			
					if (count($requestArray) == 0) {
						$Items->setStatusCode('9996');
						$Items->setStatusMessage('REQUEST tag(s) doesnt have correct input format');
						return $this->response($Items->getItems());
					}
						$_['text_subject']  = '%s - Thank you for registering';
						$_['text_welcome']  = 'Welcome and thank you for registering at %s!';
						$_['text_login']    = 'Your account has now been created and you can log in by using your email address and password by visiting our website or at the following URL:';
						$_['text_approval'] = 'Your account must be approved before you can login. Once approved you can log in by using your email address and password by visiting our website or at the following URL:';
						$_['text_services'] = 'Upon logging in, you will be able to access other services including reviewing past orders, printing invoices and editing your account information.';
						$_['text_thanks']   = 'Thanks,';
								
								
					$URL=$objEcc->config->get('config_url');
					$message='';
					foreach($requestArray as $k=>$vCustomer) {
						
							$Email			=	$vCustomer['Email'];
							$CustomerId		=	$vCustomer['CustomerId'];
							$firstname		=	$vCustomer['FirstName'];
							$middlename		=	$vCustomer['MiddleName'];
							$lastname		=	$vCustomer['LastName'];
							$company		=	$vCustomer['Company'];
							$street1		=	$vCustomer['Address1'];
							$street2		=	$vCustomer['Address2'];
							$city			=	$vCustomer['City'];
							$region			=	$vCustomer['State'];
							$postcode		=	$vCustomer['Zip'];
							$country_code	=	$vCustomer['Country'];
							$tel			=	$vCustomer['Phone'];
							$group			=	$vCustomer['CustomerGroup'];
							
							$query_country = $objEcc->db->query("SELECT country_id FROM " . DB_PREFIX . "country where iso_code_3='".$country_code."' or name='".$country_code."'" );
							$country_code=$query_country->row['country_id'];
							$query_Zone= $objEcc->db->query("SELECT zone_id FROM " . DB_PREFIX . "zone where name='".$region."' " );
							$region=$query_Zone->row['zone_id'];
							$customerData=array();
					        $customerData['email']=$Email;
							$customerData['CustomerId']=$CustomerId;
							$customerData['firstname']=$firstname;
						    $customerData['lastname']=$lastname;
							$customerData['telephone']=$tel;
							$password=$this->generatePassword();
							$customerData['password']=$password;
						    $customerData['customer_group_id']=$group;//default
							$customerData['address']['1']['firstname']=$firstname;
							$customerData['address']['1']['lastname']=$lastname;
							$customerData['address']['1']['company']=$company;
							$customerData['address']['1']['address_1']=$street1;
							$customerData['address']['1']['address_2']=$street2;
							$customerData['address']['1']['city']=$city;
							$customerData['address']['1']['zone_id']=$region;
							$customerData['address']['1']['postcode']=$postcode;
							$customerData['address']['1']['country_id']=$country_code;
						
							if(function_exists(getCustomerByEmail))
							{
								#Open cart 2.1.0.1 compliance issue.
								if($version> '1.3.4' && $version<'2.1.0.1')
								{
									$customerVal=$objEcc->model_sale_customer->getCustomerByEmail($Email);
								}else{
									$customerVal=$objEcc->model_customer_customer->getCustomerByEmail($Email);
								}
							}else{
							
								$query = $objEcc->db->query("SELECT email FROM " . DB_PREFIX . "customer WHERE email = '" . $Email . "'");
							
								$customerVal=$query->row['email'];
							}
							if(empty($customerVal)) {
								#Open cart 2.1.0.1 compliance issue.
								if($version> '1.3.4' && $version<'2.1.0.1')
								{
									$customerID=$objEcc->model_sale_customer->addCustomer($customerData);
									$customerAddressID=$objEcc->model_sale_customer->db->getLastId(); 
								}else{
									$customerID=$objEcc->model_customer_customer->addCustomer($customerData);
									//$customerAddressID=$objEcc->model_customer_customer->db->getLastId(); 
								}	
								//$query = $objEcc->db->query("SELECT customer_id FROM " . DB_PREFIX . "address where address_id='".$customerAddressID."' " );
								//$customerID=$query->row['customer_id'];
								$sql= "UPDATE " . DB_PREFIX . "customer SET approved ='1',status='1' where customer_id='".$customerID."'";
								$objEcc->db->query($sql);		
								$Customer = new WG_Customer();
								$Customer->setCustomerId($customerID);
								$Customer->setStatus('Success');
								$Customer->setFirstName($firstname);
								$Customer->setMiddleName($middlename);
								$Customer->setLastName($lastname);
								$Customer->setCustomerGroup($group);
								$Customer->setemail($Email);
								$Customer->setCompany($company);
								$Customer->setAddress1($customerData['address']['1']['address_1']);
								$Customer->setAddress2($customerData['address']['1']['address_2']);
								$Customer->setCity($city);
								$Customer->setState($region);
								$Customer->setZip($postcode);
								$Customer->setCountry($country_code);
								$Customer->setPhone($tel);
								$Customers->setCustomer($Customer->getCustomer());
								
								########################## MAIL ##########################################
								if($vCustomer['IsNotifyCustomer']=='Y')
								{
									$subject = sprintf($_['text_subject'], $objEcc->config->get('config_name'));
									$message = sprintf($_['text_welcome'], $objEcc->config->get('config_name')) . "\n\n";
									$message .= $_['text_login'] . "\n";
									$message .=$URL.'index.php?route=account/login'. "\n\n";
									$message .= "User Name: $Email" . "\n\n";
									$message .= "Password: $password" . "\n\n";
									$message .= $_['text_services']  . "\n\n";
									$message .= $_['text_thanks'] . "\n";
									$mail = new Mail($objEcc->config->get('config_mail'));
									
									$mail->setTo($Email);
									$mail->setFrom($objEcc->config->get('config_email'));
									$mail->setSender($objEcc->config->get('config_name'));
									$mail->setSubject($subject);
									$mail->setText($message);
									$mail->send();
								}
								############################################################################
								
								} else {

								$Customer = new WG_Customer();
								$Customer->setStatus('Customer email already exist');
								$Customer->setCustomerId($customerVal['customer_id']);
								$Customer->setFirstName($firstname);
								$Customer->setLastName($lastname);
								$Customer->setemail($Email);
								$Customer->setCompany($company);
								$Customers->setCustomer($Customer->getCustomer());
				
								}
					} 
			return $this->response($Customers->getCustomers());					
	}
	
	
	//function getCustomerGroup($username,$password,$storeid=1,$others)
	
	function getCustomerGroup($username,$password,$storeid=1,$others)
	{
	global $registry,$language,$objEcc,$version ;
		$status = $this->CheckUser($username,$password);
		if($status!="0")
		{		
			return $status;
		}
					#Open cart 2.1.0.1 compliance issue  
					if($version> '1.3.4' && $version<'2.1.0.1')
					{
						$objEcc->load->model('sale/customer_group');	
						$objEcc->load->language('sale/customer_group');	
					}else{
						$objEcc->load->model('customer/customer_group');	
						$objEcc->load->language('customer/customer_group');	
					}
					
		$Groupsets = new Groupsets();
		$Groupsets->setStatusCode('0');
		$Groupsets->setStatusMessage('All Ok');
		$data=array();
		
		#Open cart 2.1.0.1 compliance issue  
		if($version> '1.3.4' && $version<'2.1.0.1'){
			$customerGroup=$objEcc->model_sale_customer_group->getCustomerGroups($data);
		}else{
			$customerGroup=$objEcc->model_customer_customer_group->getCustomerGroups($data);
		}
		
		
		if(count($customerGroup)>0)
		{
			$i =0;
			foreach($customerGroup as $aSet_value)
			{
				
				$Groupset =new Groupset();
				$Groupset->setGroupsetID($aSet_value['customer_group_id']);
				$Groupset->setGroupsetName($aSet_value['name']);
				$Groupsets->setGroupsets($Groupset->getGroupset());
				$i++;
			}
		}
		return $this->response($Groupsets->getGroupsets());
	}
    function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
   }	
   
   	function CreateOrderRefund($username, $password ,$requestArray,$set_option_boost){
	
		global $registry,$language,$objEcc,$version,$ModelCheckoutOrder;
		
			foreach($requestArray as $k=>$v)//request
			{
					
				foreach($v as $k3=>$v3)
				{
					$order[$k3] = $v3;
				} 
			$Orders = new WG_Orders();	
			$result = 'Success';
			$status_array = $this->OrderStatusWG();
			if(is_array($status_array))
			{
				foreach ($status_array as $status)
				{	
					$iInfo = $this->parseSpecCharsA($status);
											
					$status_array_new[$status['name']]=$status['order_status_id'];
					$status_array_order[$status['order_status_id']]=$status['name'];
				}
			}
			
			$status_id = 0;		
			
			foreach($status_array_new as $sKeys => $sValues)
			{
	
				if( htmlentities($sKeys) == htmlentities($order['OrderStatus']))
				{
	
					$status_id = $status_array_new[$order['OrderStatus']];
					break;
				}
			}
			$data['filter_order_id'] = $order['OrderID'];
			$order_id = $order['OrderID'];
			$data['order_status_id'] = $status_id;
			$data['notify'] = 0;
				if ($result=='Success' && $order['IsNotifyCustomer']=='Y')
				{
					$data['notify'] =1;
				}
				
				//$data ['comment'] =$info;
				$data['append'] = 1;
				
				if($version> '1.3.4')
				{
					if(method_exists($objEcc->model_sale_order,addOrderHistory))			
						$objEcc->model_sale_order->addOrderHistory($order['OrderID'], $data );
					elseif(method_exists($objEcc->model_sale_order,editOrder))
					{
						$objEcc->model_sale_order->editOrder($order['OrderID'], $data );
					}else
					{
						$this->__addOrderHistory($order['OrderID'],$data);
					}
				}
				else
				{ 
					if(method_exists($objEcc->model_customer_order,addOrderHistory))			
						$objEcc->model_customer_order->addOrderHistory($order['OrderID'], $data );
					else
						$objEcc->model_customer_order->editOrder($order['OrderID'], $data );
				}
					
				unset($data);
			foreach($order['CancelItemDetail'] as $k4=>$v4) {
	  
				foreach($v4 as $k5=>$v5)
				{	
					$orderRefund[$k5]= $v5 ;
				}
				
				$itemID = $orderRefund['ItemID'];
				$SKU = $orderRefund['SKU'];
				$PrdouctName = $orderRefund['ProductName'];
				$itemCancelQty = $orderRefund['QtyCancel'];
				$ItemPrice = $orderRefund['ItemPrice'];
				$QtyInOrder = $orderRefund['QtyInOrder'];
				
				
				$sql1 = "SELECT quantity FROM " . DB_PREFIX . "product  WHERE product_id ='".$itemID."'";
				$query = $objEcc->db->query($sql1);
				
				$qty = $query->row['quantity'] ;
				$totalQty = $qty + $QtyInOrder ;
				
				$sql= "UPDATE " . DB_PREFIX . "product SET quantity = '".$totalQty."' where product_id='".$itemID."'";
				$result = $objEcc->db->query($sql);
				
				if(isset($set_option_boost) && $set_option_boost!='' && $result!=1)
				{
					$Vsql = "SELECT quantity FROM " . DB_PREFIX . "product_option_value  WHERE product_option_value_id ='".$itemID."' and sku= '".$SKU."'";
					$query = $objEcc->db->query($Vsql);
					
					$vqty = $query->row['quantity'] ;
					$totalQty = $qty + $QtyInOrder ;
					
					$sql = "update " . DB_PREFIX . "product_option_value set quantity =  '" . $totalQty . "' WHERE product_option_value_id ='".$itemID."' and sku= '".$SKU."'";
					$query = $objEcc->db->query($sql);
				}
				$result = 'Success';
				
			}
				//$date_added=date("m-d-Y H:i:s",strtotime($date_added));	
				//$date_modified=date("m-d-Y H:i:s",strtotime($date_modified));
					
					
					$RefundResponse = array();
					$RefundResponse['OrderID']		=	$order_id;
					$RefundResponse['result']		=   $result ;
					$RefundResponse['OrderStatus'] =   $status_array_order[$status_id] ;
					$RefundResponse['last_modfied_date'] =  $date_modified ;
		
			}
					return $RefundResponse;
		
		}
	
   
    public function mySQLSafe($value, $quote = "'")
     {
     	//We are going to do this to keep the functions from contantly running
     	if (empty($this->magic))
     	{
     		$this->magic = (bool)get_magic_quotes_gpc();
     	}
     	if (empty($this->escape))
     	{
     
     		if (function_exists('mysql_real_escape_string'))
     		{
     			$this->escape = 'mysql_real_escape_string';
     		}
     		else
     		{
     			$this->escape = 'mysql_escape_string';
     		}
     	}
     
     	if (empty($value))
     	{
     		return $quote.$quote;
     	}
     
     	## Stripslashes
     	if ($this->magic)
     		{
     		$value = stripslashes($value);
     }
     
     ## Strip quotes if already in
     $value = str_replace(array("\\'","'"), "&#39;", $value);
     
     ## Quote value
     if ($this->escape == 'mysql_real_escape_string' && !empty($this->db))
     {
     $value = mysql_real_escape_string($value, $this->db);
     }
     else
     {
     $value = mysql_escape_string($value);
     }
     
     $value = $quote . trim($value) . $quote;
     
     return $value;
     }
      
	  
	 public function getTotalProducts_updatedate($objEcc,$datemodified) {
            $whereCase = "WHERE pd.language_id = '" . (int)$objEcc->config->get('config_language_id') . "'";
            
           if($selectedProducts != ""){
                $whereCase .= " and LCASE(p.model) in (".$selectedProducts.")";
           }
           
           if($datemodified != ""){
                $whereCase .= " and (p.date_modified>='".$datemodified."' or p.date_added>='".$datemodified."')";
           }
            
          $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) $whereCase ";
          $query = $objEcc->db->query($sql);
          return $query->row['total'];
		
      }
		
	 public function getProducts_updatedate($data,$objEcc){
		
		
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$objEcc->config->get('config_language_id') . "'"; 
		
			if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
				$sql .= " AND LCASE(pd.name) LIKE '" . $objEcc->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
			}

                        if (isset($data['filter_model']) && !is_null($data['filter_model']) && $data['filter_model'] != "") {
			        $sql .= " AND LCASE(p.model) IN (".mb_strtolower($data['filter_model'], 'UTF-8').")";
			}
                        
                        if (isset($data['datemodified']) && !is_null($data['datemodified'])  && $data['datemodified'] != "") {
				$sql .= "and ( p.date_modified>='".$data['datemodified']."' or p.date_added>='".$data['datemodified']."' )"; 
			}
                        
			if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
				$sql .= " AND p.price LIKE '" . $objEcc->db->escape($data['filter_price']) . "%'";
			}
			
			if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
				$sql .= " AND p.quantity = '" . $objEcc->db->escape($data['filter_quantity']) . "'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
			}
             
			$sort_data = array(
				'pd.name',
				'p.model',
				'p.price',
				'p.quantity',
				'p.status',
				'p.sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY pd.name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
		
			$query = $objEcc->db->query($sql);
		
			return $query->rows;
		}
	
			return $product_data;
		}	
}

if(isset($_REQUEST['request'])) {
    $objOpencart = new opencart();
	$objOpencart->parseRequest();
}	

?>