<?php
class ControllerInformationContact extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('information/contact');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
// Clear Thinking: mailchimp_integration.xml
				if (!empty($this->request->post['newsletter'])) {
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session);
					$mailchimp_integration->send(array_merge($this->request->post, array('customer_id' => $this->customer->getId())));
				}
				// end: mailchimp_integration.xml
				// Clear Thinking: mailchimp_integration.xml
				if (!empty($this->request->post['newsletter'])) {
					if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
					$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session);
					$mailchimp_integration->send(array_merge($this->request->post, array('customer_id' => $this->customer->getId())));
				}
				// end: mailchimp_integration.xml
			$mail = new Mail($this->config->get('config_mail'));
			// $mail->setTo('chinhktr@gmail.com');
			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			// $mail->setSender($this->request->post['name'].' '.$this->request->post['lastname']);
			$mail->setSender('Contact Form');
			// $mail->setSubject(sprintf($this->language->get('email_subject'), $this->request->post['name'].' '.$this->request->post['lastname']));
			$mail->setSubject($this->request->post['name'].' '.$this->request->post['lastname']);
			// $mail->setText('Preferred Contact Time: '.$this->request->post['preferred'].'<br/>'.strip_tags($this->request->post['enquiry']));

			$html = 'Full Name:' . $this->request->post['name'].' '.$this->request->post['lastname'].'<br>';
			$html .='Phone : '. $this->request->post['phone'].'<br>';
			$html .='Email : '. $this->request->post['email'].'<br>';
			$html .='Preferred Contact Time: '.$this->request->post['preferred'].'<br/>';
			$html .= 'Content : '.strip_tags($this->request->post['enquiry']);
			$mail->setHtml($html);
			$mail->send();

			$this->response->redirect($this->url->link('information/contact/success'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_location'] = $this->language->get('text_location');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_open'] = $this->language->get('text_open');
		$data['text_comment'] = $this->language->get('text_comment');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_enquiry'] = $this->language->get('entry_enquiry');
		$data['entry_captcha'] = $this->language->get('entry_captcha');

		$data['button_map'] = $this->language->get('button_map');

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = 'Last Name must be between 3 and 32 characters!';
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['preferred'])) {
			$data['error_preferred'] = 'Preferred Contact Time must be between 3 and 32 characters!';
		} else {
			$data['error_preferred'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['phone'])) {
			$data['error_phone'] = 'Please enter number phone!';
		} else {
			$data['error_phone'] = '';
		}

		if (isset($this->error['enquiry'])) {
			$data['error_enquiry'] = $this->error['enquiry'];
		} else {
			$data['error_enquiry'] = '';
		}

		// if (isset($this->error['captcha'])) {
		// 	$data['error_captcha'] = $this->error['captcha'];
		// } else {
		// 	$data['error_captcha'] = '';
		// }

		if (isset($this->error['rcaptcha'])) {
			$data['error_rcaptcha'] = $this->error['rcaptcha'];
		} else {
			$data['error_rcaptcha'] = '';
		}

		$data['button_continue'] = $this->language->get('button_continue');

		$data['action'] = $this->url->link('information/contact');

		$this->load->model('tool/image');

		if ($this->config->get('config_image')) {
			$data['image'] = $this->model_tool_image->resize($this->config->get('config_image'), $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
		} else {
			$data['image'] = false;
		}

		$data['store'] = $this->config->get('config_name');
		$data['address'] = nl2br($this->config->get('config_address'));
		$data['geocode'] = $this->config->get('config_geocode');
		$data['telephone'] = $this->config->get('config_telephone');
		$data['fax'] = $this->config->get('config_fax');
		$data['open'] = $this->config->get('config_open');
		$data['comment'] = $this->config->get('config_comment');

		$data['locations'] = array();

		$this->load->model('localisation/location');

		foreach((array)$this->config->get('config_location') as $location_id) {
			$location_info = $this->model_localisation_location->getLocation($location_id);

			if ($location_info) {
				if ($location_info['image']) {
					$image = $this->model_tool_image->resize($location_info['image'], $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
				} else {
					$image = false;
				}

				$data['locations'][] = array(
					'location_id' => $location_info['location_id'],
					'name'        => $location_info['name'],
					'address'     => nl2br($location_info['address']),
					'geocode'     => $location_info['geocode'],
					'telephone'   => $location_info['telephone'],
					'fax'         => $location_info['fax'],
					'image'       => $image,
					'open'        => $location_info['open'],
					'comment'     => $location_info['comment']
				);
			}
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} else {
			$data['name'] = $this->customer->getFirstName();
		}	

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} else {
			$data['lastname'] = '';
		}		

		if (isset($this->request->post['preferred'])) {
			$data['preferred'] = $this->request->post['preferred'];
		} else {
			$data['preferred'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = $this->customer->getEmail();
		}

		if (isset($this->request->post['phone'])) {
			$data['phone'] = $this->request->post['phone'];
		} else {
			$data['phone'] = '';
		}

		if (isset($this->request->post['enquiry'])) {
			$data['enquiry'] = $this->request->post['enquiry'];
		} else {
			$data['enquiry'] = '';
		}

		// if (isset($this->request->post['captcha'])) {
		// 	$data['captcha'] = $this->request->post['captcha'];
		// } else {
		// 	$data['captcha'] = '';
		// }



		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/contact.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/contact.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/information/contact.tpl', $data));
		}
	}

	public function success() {
		$this->load->language('information/contact');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_message'] = $this->language->get('text_success');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
	}

	protected function validate() {
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}		

		if ((utf8_strlen($this->request->post['lastname']) < 3) || (utf8_strlen($this->request->post['lastname']) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['preferred']) < 3) || (utf8_strlen($this->request->post['preferred']) > 32)) {
			$this->error['preferred'] = $this->language->get('error_preferred');
		}

		if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['phone']) < 6) || (utf8_strlen($this->request->post['phone']) > 15)) {
			$this->error['phone'] = $this->language->get('error_phone');
		}

		if ((utf8_strlen($this->request->post['enquiry']) < 10) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
			$this->error['enquiry'] = $this->language->get('error_enquiry');
		}

		// if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
		// 	$this->error['captcha'] = $this->language->get('error_captcha');
		// }
		
		if (!empty($this->request->post['g-recaptcha-response'])) {
			$captcha = $this->request->post['g-recaptcha-response'];
			// $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfFHxEUAAAAAJ0eEnpQBI2JpVanix9H4GUvX1Ft&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
			$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfFHxEUAAAAAJ0eEnpQBI2JpVanix9H4GUvX1Ft&response=".$captcha."&remoteip="), true);
			if($response['success'] == FALSE){
				$this->error['rcaptcha'] = $this->language->get('error_captcha');
			}
		}else{
			$this->error['rcaptcha'] = $this->language->get('error_captcha');
		}		

		return !$this->error;
	}
}