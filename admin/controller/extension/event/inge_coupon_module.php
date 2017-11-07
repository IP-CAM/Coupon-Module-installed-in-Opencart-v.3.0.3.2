<?php
class ControllerExtensionEventIngeCouponModule extends Controller {
    public function view_common_column_left_before(&$route, &$data, &$output) {

        $this->load->language('extension/event/inge_coupon_module');

        $inge_coupon_module = array();
        $this->load->model('extension/ws_opencart_patch/url');
        $inge_coupon_module[] = array(
            'name'     => $this->language->get('text_news_post'),
            'href'     => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/coupon'),
            'children' => array()
        );
        $inge_coupon_module[] = array(
            'name'     => $this->language->get('text_news_category'),
            'href'     => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/category'),
            'children' => array()
        );
//        $inge_coupon_module[] = array(
//            'name'     => $this->language->get('text_news_review'),
//            'href'     => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/review'),
//            'children' => array()
//        );
//        $inge_coupon_module[] = array(
//            'name'     => $this->language->get('text_news_author'),
//            'href'     => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/author'),
//            'children' => array()
//        );
//        $inge_coupon_module[] = array(
//            'name'     => $this->language->get('text_news_author_group'),
//            'href'     => $this->model_extension_ws_opencart_patch_url->link('extension/inge_coupon_module/author_group'),
//            'children' => array()
//        );
//
//        $inge_coupon_module[] = array(
//            'name'     => $this->language->get('text_news_settings'),
//            'href'     => $this->model_extension_ws_opencart_patch_url->link('extension/module/inge_coupon_module'),
//            'children' => array()
//        );

        $insert['menus'][] = array(
            'id'       => 'menu-news',
            'icon'     => 'fa fa-newspaper-o fa-fw',
            'name'     => $this->language->get('text_news'),
            'href'     => '',
            'children' => $inge_coupon_module
        );
        if(VERSION > '2.2.0.0'){

            array_splice( $data['menus'], 2, 0, $insert['menus'] );
        } else {
            $html = $this->load->view('extension/event/inge_coupon_module', $insert);
            $html_dom = new ws_simple_html_dom();
            $html_dom->load($data['menu'], $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);
            $html_dom->find('#catalog', 0)->innertext .= $html;

            $data['menu'] = $html_dom;
        }

    }

    public function view_setting_setting_captcha_before(&$route, &$data, &$output){
        $this->load->language('extension/event/inge_coupon_module');

        $data['captcha_pages'][] = array(
            'text'  => $this->language->get('text_news'),
            'value' => 'news_module'
        );

    }

    //admin/model/localisation/language/addLanguage/after
    public function model_localisation_language_addLanguage_after($route, $data, $output){
        $this->load->model('extension/module/inge_coupon_module');

        $data = $data[0];
        $data['language_id'] = $output;

        $this->model_extension_module_inge_coupon_module->addLanguage($data);
    }

    //admin/model/localisation/language/deleteLanguage/after
    public function model_localisation_language_deleteLanguage_after($route, $data, $output){
        $this->load->model('extension/module/inge_coupon_module');

        $language_id = $data[0];
        $data['language_id'] = $language_id;

        $this->model_extension_module_inge_coupon_module->deleteLanguage($data);
    }

    public function view_category_after(&$route, &$data, &$output){

        $html_dom = new ws_simple_html_dom();
        $html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            $html_dom->find('textarea[name^="category_description['.$language['language_id'].'][description]"]', 0)->class .=' ws_visual_designer';
        }

        $this->load->model('extension/ws_visual_designer/designer');
        if($this->model_extension_ws_visual_designer_designer->checkPermission()){
            $html_dom->find('head', 0)->innertext  .= '<script src="view/javascript/ws_visual_designer/ws_visual_designer.js?'.$this->extension['version'].'" type="text/javascript"></script>';
        }

        $output = (string)$html_dom;
    }


    public function view_post_after(&$route, &$data, &$output){

        $html_dom = new ws_simple_html_dom();
        $html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            $html_dom->find('textarea[name^="post_description['.$language['language_id'].'][description]"]', 0)->class .=' ws_visual_designer';
        }

        $this->load->model('extension/ws_visual_designer/designer');
        if($this->model_extension_ws_visual_designer_designer->checkPermission()){
            $html_dom->find('head', 0)->innertext  .= '<script src="view/javascript/ws_visual_designer/ws_visual_designer.js?'.$this->extension['version'].'" type="text/javascript"></script>';
        }

        $output = (string)$html_dom;
    }

    public function view_author_after(&$route, &$data, &$output){

        $html_dom = new ws_simple_html_dom();
        $html_dom->load((string)$output, $lowercase = true, $stripRN = false, $defaultBRText = DEFAULT_BR_TEXT);

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        foreach ($languages as $language) {
            $html_dom->find('textarea[name^="author_description['.$language['language_id'].'][description]"]', 0)->class .=' ws_visual_designer';
        }

        $this->load->model('extension/ws_visual_designer/designer');
        if($this->model_extension_ws_visual_designer_designer->checkPermission()){
            $html_dom->find('head', 0)->innertext  .= '<script src="view/javascript/ws_visual_designer/ws_visual_designer.js?'.$this->extension['version'].'" type="text/javascript"></script>';
        }

        $output = (string)$html_dom;
    }

}