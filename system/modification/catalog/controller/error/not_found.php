<?php
class ControllerErrorNotFound extends Controller {
	public function index() {
if($this->config->get('config_redirect_status')) {
          if($_SERVER['SERVER_PORT'] == 80){
              $url1 = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
          } else {
              $url1 = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
          }
          $url1 = str_replace("&", "&amp;", $url1);
          $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "redirect_manager WHERE fromUrl = '" .$url1."' and status = 1");
          if($query->num_rows) {
            $this->db->query("UPDATE " . DB_PREFIX . "redirect_manager SET times_used = times_used + '1' WHERE fromUrl = '" .$url1."'");
            $mode = '301 Moved Permanently';
            header("HTTP/1.1 ".$mode); 
            header("Location:".str_replace("amp;", "", $query->row['toUrl']));
            exit;
          }
      }

		$this->load->language('error/not_found');

		$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['route'])) {
			$url_data = $this->request->get;

			unset($url_data['_route_']);

			$route = $url_data['route'];

			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link($route, $url, $this->request->server['HTTPS'])
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_error'] = $this->language->get('text_error');

		$data['button_continue'] = $this->language->get('button_continue');

		$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

 

          if($_SERVER['SERVER_PORT'] == 80){
  $url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
} else {
  $url = 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}

if ((strpos($url,'.js') == '') && (strpos($url,'.css') == '') ){
   $this->load->model('tool/redirect');
   $this->model_tool_redirect->redirectData($url);
}
  		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
		}
	}
}