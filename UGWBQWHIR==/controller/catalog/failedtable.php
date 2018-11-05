<?php 
class ControllerCatalogFailedTable extends Controller { 
	private $error = array();
	
	public function index() {		

		$this->load->model('catalog/seomanager');
		$this->model_catalog_seomanager->createTablesInDatabse();
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'index';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->load->language('catalog/failedtable');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addLink("view/stylesheet/allseo.css","stylesheet");
		
		$data['heading_title'] = $this->language->get('heading_title');
		 
		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/failedtable', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
      		'separator' => ' :: '
   		);

   		$data['failed_url'] = $this->language->get('failed_url');
   		$data['date'] = $this->language->get('date');
   		$data['count'] = $this->language->get('count');
   		$data['create_redirect'] = $this->language->get('create_redirect');
   		$data['insert_redirect'] = $this->language->get('insert_redirect');
   		$data['text_no_results'] = $this->language->get('text_no_results');
   		$data['button_cancel'] = $this->language->get('button_cancel');
   		$data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
   		$data['clear_all_urls'] = $this->language->get('clear_all_urls');
   		$data['clear_all'] = $this->url->link('catalog/failedtable/clear', 'token=' . $this->session->data['token'], 'SSL'); 
   		$data['cancel'] = $this->url->link('catalog/seomanager', 'token=' . $this->session->data['token'], 'SSL'); 

    	$data['token'] = $this->session->data['token'];
    	$data['redirectstatus'] = $this->config->get('config_redirect_status');
    	
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

		$data1 = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('catalog/seomanager');
		$this->model_catalog_seomanager->createTablesInDatabse();
		$data['redirectlist'] = $this->model_catalog_seomanager->getListt($data1);
		foreach ($data['redirectlist'] as $key => $value) {
			$data['redirectlist'][$key]['action'] = $this->url->link('catalog/seomanager', 'token=' . $this->session->data['token'], 'SSL');
		}
		
		$data['redirecttotal'] = $this->model_catalog_seomanager->getTotalListt($data1);

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_date'] = $this->url->link('catalog/failedtable', 'token=' . $this->session->data['token'] . '&sort=date' . $url, 'SSL');
		$data['sort_failed_url'] = $this->url->link('catalog/failedtable', 'token=' . $this->session->data['token'] . '&sort=failed_Url' . $url, 'SSL');
		$data['sort_count'] = $this->url->link('catalog/failedtable', 'token=' . $this->session->data['token'] . '&sort=count' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['links'][] = array(
                'text'      => "Auto Generate Tool",
                'href'      => $this->url->link('catalog/seo/autogenerate', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/product.png'
        );

         $data['links'][] = array(
                'text'      => "Seo Advanced Editor",
                'href'      => $this->url->link('catalog/seo/customize', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/log.png'
        );

        $data['links'][] = array(
                'text'      => "Dynamic Seo Report",
                'href'      => $this->url->link('catalog/seoReport', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/report.png'
        );

        $data['links'][] = array(
                'text'      => "Complete Rich Snippet",
                'href'      => $this->url->link('catalog/grsnippet', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/feed.png'
        );

        $data['links'][] = array(
                'text'      => "Sitemap Generator Pro",
                'href'      => $this->url->link('catalog/sitemap', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/review.png'
        );

        $data['links'][] = array(
                'text'      => "Clear Seo Tool",
                'href'      => $this->url->link('catalog/clearseo', 'token=' . $this->session->data['token'], 'SSL'),
                'image'     => 'view/image/error.png'
        );

        $data['links'][] = array(
                    'text'      => "Seo Redirect Manager",
                    'href'      => $this->url->link('catalog/seomanager', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/error.png'
        );

        $data['links'][] = array(
                    'text'      => "General Setting",
                    'href'      => $this->url->link('catalog/setting', 'token=' . $this->session->data['token'], 'SSL'),
                    'image'     => 'view/image/setting.png'
        );

		$pagination = new Pagination();
		$pagination->total =$data['redirecttotal'];
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/failedtable', 'token=' . $this->session->data['token'] . $url .'&page={page}', 'SSL');
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['pagination'] = $pagination->render();
		

		$data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/failedtable.tpl', $data));
	}

	public function clear(){
		$this->load->model('catalog/seomanager');
		$this->load->language('catalog/failedtable');
		$this->model_catalog_seomanager->clear();
		$this->session->data['success'] = $this->language->get('text_success');
		$this->response->redirect($this->url->link('catalog/failedtable', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
}
?>