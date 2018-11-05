<?php
class ControllerCommonLogin extends Controller {
	private $error = array();

	public function index() {
		
			$query = $this->db->query("SHOW COLUMNS FROM ".DB_PREFIX."product LIKE 'supquantity'");
			if (!$query->num_rows){
				//DB is not ready yet
				$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."supplier'");
				if(!$query->num_rows)
				{
				   $this->db->query("CREATE TABLE ".DB_PREFIX."supplier (   supplier_id INT(11) NOT NULL AUTO_INCREMENT,   name VARCHAR(64) NOT NULL DEFAULT '',   firstname VARCHAR(32) NOT NULL DEFAULT '',   lastname VARCHAR(32) NOT NULL DEFAULT '',   address1 VARCHAR(128) NOT NULL DEFAULT '',   address2 VARCHAR(128) NULL,   city VARCHAR(128) NOT NULL,   postcode VARCHAR(10) NOT NULL,   country_id INT(11),   zone_id INT(11),   email VARCHAR(96) NOT NULL DEFAULT '',   telephone VARCHAR(32) NOT NULL DEFAULT '',   tax INT(1) NOT NULL DEFAULT 1,   status INT(1) NOT NULL DEFAULT 1,   currency_id INT(11),   tax_rate_id INT(11),   date_added DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',   comments TEXT,   PRIMARY KEY (supplier_id) ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_bin");
				   $this->db->query("ALTER TABLE `".DB_PREFIX."supplier` ADD    `maxshipdays` int(11) DEFAULT NULL");
				   $this->db->query("INSERT INTO ".DB_PREFIX."order_status(language_id,name) VALUES (1,'PO Sent')");
				   $this->db->query("ALTER TABLE `".DB_PREFIX."supplier` ADD `dropship_fee` DECIMAL(15,4) NULL DEFAULT 0");
				   $this->db->query("ALTER TABLE `".DB_PREFIX."supplier` ADD `user_id` INT(11) NULL");
				   $this->db->query("ALTER TABLE `".DB_PREFIX."supplier` ADD `cred_token` VARCHAR(64) NULL");
				   $this->db->query("ALTER TABLE `".DB_PREFIX."supplier` ADD `gp_percent` DECIMAL(15,4) NULL DEFAULT 0");
				   $this->db->query("ALTER TABLE `".DB_PREFIX."supplier` ADD `exportpath` VARCHAR(250) NULL DEFAULT ''");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."supplier ADD supplierurl VARCHAR(100) NULL");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."supplier ADD orderurl VARCHAR(250) NULL;");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."supplier ADD im VARCHAR(25) NULL");
				   $this->db->query("INSERT INTO ".DB_PREFIX."setting SET store_id=0,`code`='config',`key`='config_po_installed',value='1',serialized='0'");
				   $this->db->query("INSERT INTO ".DB_PREFIX."order_status(language_id,name) VALUES (1,'PO Reminder Sent')");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."supplier ADD COLUMN notes text");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."supplier ADD itemdel_fee decimal(15,4) DEFAULT 0");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."supplier ADD fileattach VARCHAR(100) DEFAULT ''");
				   $this->db->query("CREATE VIEW suppliercompact AS SELECT s.supplier_id, s.name AS supplier FROM ".DB_PREFIX."supplier s");
				}
				$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."supplier_to_store'");
				if(!$query->num_rows)
				{
				   $this->db->query("CREATE TABLE ".DB_PREFIX."supplier_to_store (   supplier_id INT(11) NOT NULL,   store_id INT(11) NOT NULL,   PRIMARY KEY (supplier_id, store_id) ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_bin");
				}
				$query = $this->db->query("SHOW COLUMNS FROM ".DB_PREFIX."product LIKE 'supplier_id'");
				if (!$query->num_rows){
					$this->db->query("ALTER table ".DB_PREFIX."product ADD supplier_id INT(11) NULL");
				}
				$query = $this->db->query("SHOW COLUMNS FROM ".DB_PREFIX."product LIKE 'cost'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE ".DB_PREFIX."product ADD cost DECIMAL(15,4) NULL DEFAULT 0.00 AFTER price");
				}
				$query = $this->db->query("SHOW COLUMNS FROM ".DB_PREFIX."product LIKE 'cost_currency_id'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE ".DB_PREFIX."product ADD cost_currency_id INT(11) NULL AFTER cost");
				}
				$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."purchase_order'");
				if(!$query->num_rows)
				{
				   $this->db->query("CREATE TABLE `".DB_PREFIX."purchase_order` (   `order_id` int(11) NOT NULL AUTO_INCREMENT,   `invoice_no` int(11) NOT NULL DEFAULT '0',   `invoice_prefix` varchar(26) COLLATE utf8_bin NOT NULL,   `poinvoice_ref` varchar(26) COLLATE utf8_bin DEFAULT '',   `store_id` int(11) NOT NULL DEFAULT '0',   `store_name` varchar(64) COLLATE utf8_bin NOT NULL,   `store_url` varchar(255) COLLATE utf8_bin NOT NULL,   `supplier_id` int(11) NOT NULL DEFAULT '0',   `total_weight` decimal(15,4) DEFAULT '0.0000',   `total` decimal(15,4) NOT NULL DEFAULT '0.0000',   `delivery_charge` decimal(15,4) DEFAULT '0.0000',   `vat` decimal(15,4) DEFAULT '0.0000',   `sales_order_id` int(11) DEFAULT '0',   `order_status_id` int(11) NOT NULL DEFAULT '0',   `send_to` int(1) NOT NULL,   `currency_id` int(11) NOT NULL,   `currency_code` varchar(3) COLLATE utf8_bin NOT NULL,   `currency_value` decimal(15,8) NOT NULL,   `date_added` datetime NOT NULL,   `date_modified` datetime NOT NULL,   `tracking_id_s2s` varchar(32) COLLATE utf8_bin DEFAULT '',   `tracking_id_s2c` varchar(32) COLLATE utf8_bin DEFAULT '',   `delivery_time_s2s` varchar(32) COLLATE utf8_bin DEFAULT '',   `delivery_time_s2c` varchar(32) COLLATE utf8_bin DEFAULT '',   `comments` text COLLATE utf8_bin,   PRIMARY KEY (`order_id`) ) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."purchase_order  ADD `po_seq_no` int(11) NULL  DEFAULT 1");
				   $this->db->query("ALTER TABLE `".DB_PREFIX."purchase_order` ADD `auth_token` VARCHAR(64) NULL");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."purchase_order ADD inv_value DECIMAL(15,4) NULL");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."purchase_order ADD updatestock INT(1) DEFAULT 0");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."purchase_order ADD itemdel_fee decimal(15,4) DEFAULT 0");
				}
				$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."purchase_order_product'");
				if(!$query->num_rows)
				{
				   $this->db->query("CREATE TABLE `".DB_PREFIX."purchase_order_product` (   `order_product_id` int(11) NOT NULL auto_increment,   `order_id` int(11) NOT NULL,   `product_id` int(11) NOT NULL,   `sale_order_product_id` int(11) NULL DEFAULT 0,   `name` varchar(255) collate utf8_bin NOT NULL,   `model` varchar(24) collate utf8_bin NOT NULL,   `quantity` int(4) NOT NULL,   `price` decimal(15,4) NOT NULL default '0.0000',   `total` decimal(15,4) NOT NULL default '0.0000',   PRIMARY KEY  (`order_product_id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				   $this->db->query("ALTER TABLE ".DB_PREFIX."purchase_order_product ADD stock_added tinyint(1) NOT NULL DEFAULT 0");
				}
				$query = $this->db->query("SHOW COLUMNS FROM ".DB_PREFIX."order_product LIKE 'po_product_id'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE ".DB_PREFIX."order_product  ADD po_product_id INT(11) NULL DEFAULT 0");
				}
				$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."purchase_order_option'");
				if(!$query->num_rows)
				{
				   $this->db->query("CREATE TABLE `".DB_PREFIX."purchase_order_option` (   `order_option_id` int(11) NOT NULL AUTO_INCREMENT,   `order_id` int(11) NOT NULL,   `order_product_id` int(11) NOT NULL,   `product_option_id` int(11) NOT NULL,   `product_option_value_id` int(11) NOT NULL DEFAULT '0',   `name` varchar(255) COLLATE utf8_bin NOT NULL,   `value` text COLLATE utf8_bin NOT NULL,   `type` varchar(32) COLLATE utf8_bin NOT NULL,   PRIMARY KEY (`order_option_id`) ) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
				}
				$query = $this->db->query("SHOW COLUMNS FROM ".DB_PREFIX."product_option_value LIKE 'cost'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE `".DB_PREFIX."product_option_value` ADD   `cost` decimal(15,4) NOT NULL, ADD   `cost_prefix` varchar(1) COLLATE utf8_bin NOT NULL");
				}
				$query = $this->db->query("SHOW COLUMNS FROM ".DB_PREFIX."product LIKE 'gp_percent'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE `".DB_PREFIX."product` ADD `gp_percent` DECIMAL(15,4) NULL DEFAULT 0");
				}
				$query = $this->db->query("SHOW COLUMNS FROM ".DB_PREFIX."product LIKE 'reorder'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE `".DB_PREFIX."product` ADD `reorder` int(11) NULL DEFAULT 0");
				}
				$query = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."order` LIKE 'parent_order_id'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE `".DB_PREFIX."order`  ADD parent_order_id INT(11) NOT NULL DEFAULT 0");
				}
				$query = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."customer` LIKE 'agent_id'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE ".DB_PREFIX."customer ADD agent_id INT(11) NULL");
				}
				$query = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."product` LIKE 'supproducturl'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE ".DB_PREFIX."product ADD supproducturl VARCHAR(250) NULL DEFAULT ''");
				}
				$query = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."product` LIKE 'supproducttitle'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE `".DB_PREFIX."product` ADD `supproducttitle` varchar(250) DEFAULT ''");
				}
				$query = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."product` LIKE 'supquantity'");
				if (!$query->num_rows){
					$this->db->query("ALTER TABLE `".DB_PREFIX."product` ADD supquantity int(4) NOT NULL DEFAULT '0'");
				}			
			}
			
		$this->load->language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
					
			$this->load->model('pomgmt/po');
			if (!$this->model_pomgmt_po->EventExists('pomgmt',  'post.order.history.add', 'pomgmt/po/generatepo')) {
				$this->load->model('extension/event');
				$this->model_extension_event->addEvent('pomgmt', 'post.order.history.add', 'pomgmt/po/generatepo');
			}
			
			$this->load->model('pomgmt/supplier');
			$supplierInfo = $this->model_pomgmt_supplier->getSupplierForUser($this->user->getId());
			if (!empty($supplierInfo) && !empty($supplierInfo['cred_token'])) {
				$this->model_pomgmt_supplier->resetSupplierCredToken($supplierInfo['supplier_id']);
				$this->response->redirect($this->url->link('user/changepwd', 'token=' . $this->session->data['token'], 'SSL'));
			}	
			else
				$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
			
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['token'] = md5(mt_rand());

			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || strpos($this->request->post['redirect'], HTTPS_SERVER) === 0 )) {
				$this->response->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
			} else {
						
			$this->load->model('pomgmt/po');
			if (!$this->model_pomgmt_po->EventExists('pomgmt',  'post.order.history.add', 'pomgmt/po/generatepo')) {
				$this->load->model('extension/event');
				$this->model_extension_event->addEvent('pomgmt', 'post.order.history.add', 'pomgmt/po/generatepo');
			}
			
			$this->load->model('pomgmt/supplier');
			$supplierInfo = $this->model_pomgmt_supplier->getSupplierForUser($this->user->getId());
			if (!empty($supplierInfo) && !empty($supplierInfo['cred_token'])) {
				$this->model_pomgmt_supplier->resetSupplierCredToken($supplierInfo['supplier_id']);
				$this->response->redirect($this->url->link('user/changepwd', 'token=' . $this->session->data['token'], 'SSL'));
			}	
			else
				$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
			
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_login'] = $this->language->get('text_login');
		$data['text_forgotten'] = $this->language->get('text_forgotten');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_uksb_2fa'] = $this->language->get('entry_uksb_2fa');
		$data['help_uksb_2fa'] = $this->language->get('help_uksb_2fa');


		$data['button_login'] = $this->language->get('button_login');

		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
			$this->error['warning'] = $this->language->get('error_token');
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('common/login', '', 'SSL');

		if (isset($this->request->post['uksb_2fa'])) {
			$data['uksb_2fa'] = $this->request->post['uksb_2fa'];
		} else {
			$data['uksb_2fa'] = '';
		}


		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} else {
			$data['username'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];

			unset($this->request->get['route']);
			unset($this->request->get['token']);

			$url = '';

			if ($this->request->get) {
				$url .= http_build_query($this->request->get);
			}

			$data['redirect'] = $this->url->link($route, $url, 'SSL');
		} else {
			$data['redirect'] = '';
		}

		if ($this->config->get('config_password')) {
			$data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
		} else {
			$data['forgotten'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/login.tpl', $data));
	}

	protected function validate() {
		if (!isset($this->request->post['username']) || !isset($this->request->post['password']) || !$this->user->login($this->request->post['username'], $this->request->post['password'], $this->request->post['uksb_2fa'])) {
			$this->error['warning'] = $this->language->get('error_login');
		}

		return !$this->error;
	}

	public function check() {
		$route = '';

		if (isset($this->request->get['route'])) {
			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}
		}

		$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset'
		);

		if (!$this->user->isLogged() && !in_array($route, $ignore)) {
					
			if (!isset($this->request->get['auth_id']))
				//return $this->forward('common/login');
				return new Action('common/login');
			if ($route=="pomgmt/po" && isset($this->request->get['auth_id']) && $this->request->get['auth_id']!="") {		

				if (isset($this->request->get['order_id']) && $this->request->get['order_id']!="")   {
					$order_id = $this->request->get['order_id'];
					$auth_id = $this->request->get['auth_id'];
					$po_query = $this->db->query("SELECT u.user_id,u.username,auth_token FROM " . DB_PREFIX . "purchase_order po 
							INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = po.supplier_id 
							INNER JOIN `" . DB_PREFIX . "user` u ON u.user_id = s.user_id 
							WHERE auth_token='" . $this->db->escape($auth_id) . "' AND order_id='" . (int)$order_id . "'");					
					if ($po_query->num_rows) {
						$this->user->forceLogin($po_query->row['username']);
						$this->session->data['token'] = $po_query->row['auth_token'];
					}
					else
						//return $this->forward('common/login');
						return new Action('common/login');
				}
				else
					//return $this->forward('common/login');
					return new Action('common/login');
			}
			else
				//return $this->forward('common/login');
				return new Action('common/login');
			
		}

		if (isset($this->request->get['route'])) {
			$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			$config_ignore = array();

			if ($this->config->get('config_token_ignore')) {
				$config_ignore = unserialize($this->config->get('config_token_ignore'));
			}

			$ignore = array_merge($ignore, $config_ignore);

			if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
						
			if (!isset($this->request->get['auth_id']))
				//return $this->forward('common/login');
				return new Action('common/login');
			if ($route=="pomgmt/po" && isset($this->request->get['auth_id']) && $this->request->get['auth_id']!="") {		

				if (isset($this->request->get['order_id']) && $this->request->get['order_id']!="")   {
					$order_id = $this->request->get['order_id'];
					$auth_id = $this->request->get['auth_id'];
					$po_query = $this->db->query("SELECT u.user_id,u.username,auth_token FROM " . DB_PREFIX . "purchase_order po 
							INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = po.supplier_id 
							INNER JOIN `" . DB_PREFIX . "user` u ON u.user_id = s.user_id 
							WHERE auth_token='" . $this->db->escape($auth_id) . "' AND order_id='" . (int)$order_id . "'");					
					if ($po_query->num_rows) {
						$this->user->forceLogin($po_query->row['username']);
						$this->session->data['token'] = $po_query->row['auth_token'];
					}
					else
						//return $this->forward('common/login');
						return new Action('common/login');
				}
				else
					//return $this->forward('common/login');
					return new Action('common/login');
			}
			else
				//return $this->forward('common/login');
				return new Action('common/login');
			
			}
		} else {
			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
						
			if (!isset($this->request->get['auth_id']))
				//return $this->forward('common/login');
				return new Action('common/login');
			if ($route=="pomgmt/po" && isset($this->request->get['auth_id']) && $this->request->get['auth_id']!="") {		

				if (isset($this->request->get['order_id']) && $this->request->get['order_id']!="")   {
					$order_id = $this->request->get['order_id'];
					$auth_id = $this->request->get['auth_id'];
					$po_query = $this->db->query("SELECT u.user_id,u.username,auth_token FROM " . DB_PREFIX . "purchase_order po 
							INNER JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = po.supplier_id 
							INNER JOIN `" . DB_PREFIX . "user` u ON u.user_id = s.user_id 
							WHERE auth_token='" . $this->db->escape($auth_id) . "' AND order_id='" . (int)$order_id . "'");					
					if ($po_query->num_rows) {
						$this->user->forceLogin($po_query->row['username']);
						$this->session->data['token'] = $po_query->row['auth_token'];
					}
					else
						//return $this->forward('common/login');
						return new Action('common/login');
				}
				else
					//return $this->forward('common/login');
					return new Action('common/login');
			}
			else
				//return $this->forward('common/login');
				return new Action('common/login');
			
			}
		}
	}
}