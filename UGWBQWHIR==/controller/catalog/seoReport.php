<?php
class ControllerCatalogSeoReport extends Controller {

    public function index() {
        $this->language->load('catalog/seoReport');
        $this->load->model('catalog/seoReport');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addLink("view/stylesheet/allseo.css","stylesheet");

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/seoReport', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

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
        
        $data['cancel'] =  $this->url->link('catalog/seoReport', 'token=' . $this->session->data['token'], 'SSL');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['seordata'] = $this->language->get('seordata');
        $data['token'] = $this->session->data['token'];
        $data['create_report'] = $this->language->get('create_report');
        $data['total'] = $this->language->get('total');
        $data['mk'] = $this->language->get('mk');
        $data['md'] = $this->language->get('md');
        $data['sd'] = $this->language->get('sd');
       
        $data['sitemap'] = $this->language->get('sitemap');
        $data['sitemapt'] = $this->language->get('sitemapt');
        $data['sitemapnt'] = $this->language->get('sitemapnt');
        $data['sitemapnso'] = $this->language->get('sitemapnso');
        $data['sitemapfound']  =  file_exists('../sitemap.xml');
        $data['sitemapso'] = sprintf($this->language->get('sitemapso'),$this->url->link('catalog/sitemap', 'token=' . $this->session->data['token'], 'SSL'));

        
        $data['robots'] = $this->language->get('robots');
        $data['robotst'] = sprintf($this->language->get('robotst'),HTTP_CATALOG."robots.txt");
        $data['robotsnt'] = $this->language->get('robotsnt');
        $data['robotsso'] = $this->language->get('robotsso');
        $data['robotsnso'] = $this->language->get('robotsnso');
        $data['robotsfound']  =  file_exists('../robots.txt');

        

        if ($this->request->server['HTTPS']) {
            $data['catalog'] = HTTPS_CATALOG;
        } else {
            $data['catalog'] = HTTPS_CATALOG;
        }
        $data['productreport'] = $this->model_catalog_seoReport->getreport1();
        $data['productmetatitle'] = $this->model_catalog_seoReport->getreport7('product_description');
        $data['productmetadesc'] = $this->model_catalog_seoReport->getreport8('product_description');
        $data['productmetakey'] = $this->model_catalog_seoReport->getreport9('product_description');

        $data['catreport'] = $this->model_catalog_seoReport->getreport2();
        $data['categorymetatitle'] = $this->model_catalog_seoReport->getreport7('category_description');
        $data['categorymetadesc'] = $this->model_catalog_seoReport->getreport8('category_description');
        $data['categorymetakey'] = $this->model_catalog_seoReport->getreport9('category_description');

        $data['inforeport'] = $this->model_catalog_seoReport->getreport3();
        $data['informationmetatitle'] = $this->model_catalog_seoReport->getreport7('information_description');
        $data['informationmetadesc'] = $this->model_catalog_seoReport->getreport8('information_description');
        $data['informationmetakey'] = $this->model_catalog_seoReport->getreport9('information_description');

        $data['manreport'] = $this->model_catalog_seoReport->getreport4();
        $data['genreport'] = $this->model_catalog_seoReport->getreport5();
        $data['seokeyword'] = $this->model_catalog_seoReport->getreport6();
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/seoReport.tpl', $data));
    }

    public function getreport() {
        $this->load->model('catalog/seoReport');
        
        $results1 = $this->model_catalog_seoReport->getreport1();
        $results2 = $this->model_catalog_seoReport->getreport2();
        $results3 = $this->model_catalog_seoReport->getreport3();
        $results4 = $this->model_catalog_seoReport->getreport4();
        $sitemap  =  file_exists('../sitemap.xml');
        $results = array('products' => $results1, 'categories' => $results2 ,'information' => $results3,'manufacturer' => $results4,'sitemap' => $sitemap);
        $this->response->setOutput(json_encode($results));
    }

     public function getreport1() {
        $this->load->model('catalog/seoReport');
        
        $results1 = $this->model_catalog_seoReport->getreport1();
        $results2 = $this->model_catalog_seoReport->getreport2();
        $results3 = $this->model_catalog_seoReport->getreport3();
        $results4 = $this->model_catalog_seoReport->getreport4();
        $sitemap  =  file_exists('../sitemap.xml');
        $results = array('products' => $results1, 'categories' => $results2 ,'information' => $results3,'manufacturer' => $results4,'sitemap' => $sitemap);
        $this->response->setOutput(json_encode($results));
    }

}
?>