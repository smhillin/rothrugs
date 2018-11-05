<?php

class ControllerQuiz extends Controller {

	public function index()
	{
		$this->load->model('catalog/product');
		$this->load->model('catalog/filter');
        
		$this->document->setTitle('Find Your Perfect Rug with our Online Quiz | Roth Rugs');
		$this->document->setDescription('So many decisions between pile, color, texture, size, and durability! Narrow it down and find your perfect rug with our online rug quiz at Roth Rugs.');
        $this->document->setKeywords('Find Your Perfect Rug with our Online Quiz | Roth Rugs');

		$data['rug_options'] = $this->model_catalog_product->getAllOptions();
		$data['rug_filters'] = $this->model_catalog_filter->getAllFilters(null);
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/quiz.tpl', $data));
	}

    public function result()
    {
        $this->load->model('quiz/quiz');
        $combination = $this->request->post['combination'];
        $category_id = $this->model_quiz_quiz->getCategory($combination);
        if($category_id > 0){
            $keyword = $this->model_quiz_quiz->getkeyword($category_id);
        }
        echo $keyword;        
    }

}
