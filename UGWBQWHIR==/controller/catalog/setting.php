<?php  
class ControllerCatalogsetting extends Controller {  
    private $error = array();
   
    public function index() {
        $this->load->language('catalog/setting');
        $this->getForm();
    }
    
    public function insert() {    
        $this->load->language('catalog/setting');
        $this->load->model('setting/setting');
        $this->document->setTitle($this->language->get('heading_title'));
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            
            if(!isset($this->request->post['nerdherd_direct_links'])){
                $this->request->post['nerdherd_direct_links'] = 0;
            } else {
                 $this->request->post['nerdherd_direct_links'] = 1;
            }
            if(!isset($this->request->post['nerdherd_breadcrumblink'])){
                $this->request->post['nerdherd_breadcrumblink'] = 0;
            } else {
                 $this->request->post['nerdherd_breadcrumblink'] = 1;
            }

            if(!isset($this->request->post['nerdherd_self_generate'])){
                $this->request->post['nerdherd_self_generate'] = 0;
            } else {
                 $this->request->post['nerdherd_self_generate'] = 1;
            }

           
            $this->request->post['config_multilang_lang'] = $this->config->get("config_language");
            $this->model_setting_setting->editSetting('nerdherd', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('catalog/setting', 'token=' . $this->session->data['token'], 'SSL'));
        }
        $this->getForm();
    }
    
    private function getForm() {
       
        $this->load->language('catalog/setting');
         if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }
        $this->document->addLink("view/stylesheet/allseo.css","stylesheet");
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled'); 
        $data['text_about'] = $this->language->get('text_about'); 
        $data['tab_company'] = $this->language->get('tab_company');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel'); 
        $data['text_multi_lang'] = $this->language->get('text_multi_lang'); 
        $data['text_direct_links'] = $this->language->get('text_direct_links'); 
        $data['text_self_generate'] = $this->language->get('text_self_generate'); 
        $data['help_multi_lang'] = $this->language->get('help_multi_lang');
        $data['help_self_generate'] = $this->language->get('help_self_generate'); 
        $data['help_direct_links'] = $this->language->get('help_direct_links'); 
        $data['token'] = $this->session->data['token'];

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('catalog/setting', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $data['action'] = $this->url->link('catalog/setting/insert', 'token=' . $this->session->data['token'], 'SSL');
       
        
        if (isset($this->request->post['nerdherd_breadcrumblink'])) {
            $data['nerdherd_breadcrumblink'] = $this->request->post['nerdherd_breadcrumblink'];
        } elseif ($this->config->get('nerdherd_breadcrumblink')) {
            $data['nerdherd_breadcrumblink'] = $this->config->get('nerdherd_breadcrumblink');
        } else {
            $data['nerdherd_breadcrumblink'] = '';
        }
       
        if (isset($this->request->post['nerdherd_direct_links'])) {
            $data['nerdherd_direct_links'] = $this->request->post['nerdherd_direct_links'];
        } elseif ($this->config->get('nerdherd_direct_links')) {
            $data['nerdherd_direct_links'] = $this->config->get('nerdherd_direct_links');
        } else {
            $data['nerdherd_direct_links'] = '';
        }

        if (isset($this->request->post['nerdherd_self_generate'])) {
            $data['nerdherd_self_generate'] = $this->request->post['nerdherd_self_generate'];
        } elseif ($this->config->get('nerdherd_self_generate')) {
            $data['nerdherd_self_generate'] = $this->config->get('nerdherd_self_generate');
        } else {
            $data['nerdherd_self_generate'] = '';
        }

         
        $data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'); 
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();

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
 
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/setting.tpl', $data));
    }
}
?>