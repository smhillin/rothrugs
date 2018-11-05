<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}
                if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		
		$data['base'] = $server;
                
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
                $top_category = $this->model_catalog_category->getTopFourCategoryBySlug('katespick');
                if($top_category)
                {
                    foreach($top_category as $category)
                    {
                        if($category['image']){
                            $top_category_image = $this->model_tool_image->resize($category['image'], 575,348);
                        }else{
                            $top_category_image = $this->model_tool_image->resize('placeholder.png', 575,348);
                        }
                        $data['top_category'][]= array(
				'name'  => $category['name'],
				'href'  => $this->url->link('product/category', 'path=' . $category['parent_id'] . '_' . $category['category_id']),
				'thumb' =>$top_category_image);
                    }
                }
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
		}
	}
}