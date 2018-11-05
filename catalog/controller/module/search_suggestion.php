<?php
/**
 * This file is part of FreeCart.
 *
 * @copyright  sv2109 <sv2109@gmail.com>
 * @link http://freecart.pro
*/

class ControllerModuleSearchSuggestion extends Controller {

	public function index() {

		$this->id = 'search_suggestion';

		$this->document->addScript('catalog/view/javascript/search_suggestion.js');
		
		$this->document->addStyle('catalog/view/javascript/jquery/ui/jquery-ui.css');
		$this->document->addScript('catalog/view/javascript/jquery/ui/jquery-ui.js');

		$options = $this->config->get('search_suggestion_options');
		$css = $options['search_css'];
		foreach ($options['search_field'] as $field_name => $field_options ) {
			if (isset($field_options['css']) && $field_options['css']) {
				$css .= '.ui-autocomplete .' . $field_name . ' {' . PHP_EOL . $field_options['css'] . PHP_EOL .  '} ' . PHP_EOL;
			}
		}
		$data['css'] = $css;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/search_suggestion.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/search_suggestion.tpl', $data);
        } else {
            return $this->load->view('default/template/module/search_suggestion.tpl', $data);
		}
	}
	
	public function ajax() {

		$this->language->load('module/search_suggestion');

		$json = array();
		$data['products'] = array();

		if (isset($this->request->get['keyword'])) {

			$options = $this->config->get('search_suggestion_options');

			$this->load->model('catalog/search_suggestion');

			if (isset($options['search_where']['name'])) {
				$data_search['filter_name'] = $this->request->get['keyword'];
			}

			if (isset($options['search_order'])) {
				if ($options['search_order'] == 'rating') {
					$data_search['sort'] = 'rating';
				} else if ($options['search_order'] == 'name') {
					$data_search['sort'] = 'pd.name';
				} else if ($options['search_order'] == 'relevance') {
					$data_search['sort'] = 'relevance';
				}
			}
			if (isset($options['search_order_dir'])) {
				if ($options['search_order_dir'] == 'asc') {
					$data_search['order'] = 'ASC';
				} else if ($options['search_order_dir'] == 'desc') {
					$data_search['order'] = 'DESC';
				}
			}
			if (isset($options['search_limit'])) {
				$data_search['limit'] = $options['search_limit'];
			}
			$data_search['start'] = 0;

			$search_model = 'model_catalog_search_suggestion';

			// if sort is by relevance and module "search with morphology and relevance" exists 
			if ($data_search['sort'] == 'relevance' && $this->config->get('search_mr_options')) {
				$this->load->model('catalog/search_mr');
				$data_search['search_mr_options'] = $this->config->get('search_mr_options');
				// for relevance use DESC order
				$data_search['order'] = 'DESC';
				$search_model = 'model_catalog_search_mr';
			}
			
			$results = $this->$search_model->getProducts($data_search);

			$product_total = $this->$search_model->getTotalProducts($data_search);

			if ($product_total) {
				$this->load->model('tool/image');

				foreach ($results as $result) {
					
					$fields = array();
					foreach ($options['search_field'] as $field_name => $field_options ) {
						
						$fields[$field_name] = '';
						if (isset($field_options['show'])) {
							$fields[$field_name] = array( 
								'location' => $field_options['location'],
								'sort' => $field_options['sort'],
								'label' => array (
									'show' => isset($field_options['show_field_name']) ? $field_options['show_field_name'] : 0,
									'label' => $this->language->get($field_name)
								)	
							);

							if ($field_name == 'price') {
								$text = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
								if ((float) $result['special']) {
									$fields[$field_name]['special'] = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
								}
							} else {
								$text = htmlspecialchars_decode($result[$field_name]);
							}
							
							$fields[$field_name][$field_name] = $text;
						}
					}
					
					$data['products'][] = array(
						'fields' => $fields,
						'href' => str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $result['product_id']))
					);
				}
			}
		}

		if (empty($data['products'])) {
			$data['products'][] = array(
				'fields' => array( 
					'no_results' => array( 
						'no_results' => $this->language->get('text_no_result')
					),
				),
				'href' => ''
			);
		} else if ($product_total > count($data['products'])) {
			$remainder_cnt = $product_total - count($data['products']);
			if ($remainder_cnt > 0) {
				$data['products'][] = array(
					'fields' => array( 
						'more' => array( 
							'more' => $remainder_cnt . $this->language->get('more_results')
						),
					),
					'href' => str_replace('&amp;', '&', $this->url->link('product/search', 'search=' . $this->request->get['keyword']))
				);
			}
		}

		$this->response->setOutput(json_encode($data['products']));
	}	
}