<?php

class ControllerExtensionModuleIngeCouponModule extends Controller
{
    private $codename = 'inge_coupon_module';
    private $route = 'extension/module/inge_coupon_module';
    private $sub_versions = array('lite', 'light', 'free');
    private $config_file = '';
    private $store_id = 0;
    private $error = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->language($this->route);
        $this->load->model($this->route);
        $this->load->model('setting/setting');
        $this->load->model('extension/inge_coupon_module/category');
        $this->load->model('extension/ws_opencart_patch/load');
//
//        $this->d_shopunity = (file_exists(DIR_SYSTEM.'library/ws_shopunity/extension/ws_shopunity.json'));
//        $this->ws_opencart_patch = (file_exists(DIR_SYSTEM.'library/ws_shopunity/extension/ws_opencart_patch.json'));
//        if($this->ws_opencart_patch){
//            $this->load->model('extension/ws_opencart_patch/url');
//            $this->load->model('extension/ws_opencart_patch/user');
//            $this->load->model('extension/ws_opencart_patch/store');
//        }
//        $this->ws_twig_manager = (file_exists(DIR_SYSTEM.'library/ws_shopunity/extension/ws_twig_manager.json'));
//        $this->ws_event_manager = (file_exists(DIR_SYSTEM.'library/ws_shopunity/extension/ws_event_manager.json'));
//        $this->extension = json_decode(file_get_contents(DIR_SYSTEM.'library/ws_shopunity/extension/ws_blog_module.json'), true);
//
        if (isset($this->request->get['store_id'])) {
            $this->store_id = $this->request->get['store_id'];
        }
//
//        // give some permissions
        $this->permission_handler('main');

//        $this->config_file = $this->model_extension_module_inge_coupon_module->getConfigFile($this->codename, $this->sub_versions);
    }

    public function index()
    {

        $this->load->language('extension/module/inge_coupon_module');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/inge_coupon_module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
//            $this->model_setting_setting->editSetting('captcha_basic', $this->request->post);
            $this->model_extension_module_inge_coupon_module->editSetting($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=message', true));
        }


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=captcha', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/inge_coupon_module', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/inge_coupon_module', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=captcha', true);


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/inge_coupon_module', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/inge_coupon_module')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    /**
     * 安装
     */
    public function install() {
        //进行安装

        $this->load->model('extension/module/inge_coupon_module');

        $this->addEvent();
//        $this->load->model('extension/module/ws_blog_module');
        $this->model_extension_module_inge_coupon_module->createTables();

//        $this->load->model('extension/ws_shopunity/mbooth');
//        $this->model_extension_ws_shopunity_mbooth->installDependencies($this->codename);

        $this->permission_handler('all');
    }

    private function permission_handler($perm = 'main')
    {
        $this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'access', 'extension/'.$this->codename);
        $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'modify', 'extension/'.$this->codename);

        if ($perm == 'all') {
            $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'access', 'extension/'.$this->codename.'/category');
            $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'modify', 'extension/'.$this->codename.'/category');
            $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'access', 'extension/'.$this->codename.'/news');
            $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'modify', 'extension/'.$this->codename.'/news');
            $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'access', 'extension/'.$this->codename.'/review');
            $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'modify', 'extension/'.$this->codename.'/review');
            $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'access', 'extension/'.$this->codename.'/author');
            $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'modify', 'extension/'.$this->codename.'/author');
            $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'access', 'extension/'.$this->codename.'/author_group');
            $this->model_user_user_group->addPermission($this->model_extension_module_inge_coupon_module->getGroupId(), 'modify', 'extension/'.$this->codename.'/author_group');
        }
    }

    private function addEvent()
    {

        $this->load->model('setting/event');

        $this->model_setting_event->addEvent('inge_coupon_module', 'admin/view/common/column_left/before','extension/event/inge_coupon_module/view_common_column_left_before');
        $this->model_setting_event->addEvent('inge_coupon_module', 'admin/view/setting/setting/before', 'extension/event/inge_coupon_module/view_setting_setting_captcha_before');
        $this->model_setting_event->addEvent('inge_coupon_module', 'catalog/view/common/header/before', 'extension/event/inge_coupon_module/view_common_header_before');
        $this->model_setting_event->addEvent('inge_coupon_module', 'catalog/view/common/menu/before', 'extension/event/inge_coupon_module/view_common_menu_before');
        $this->model_setting_event->addEvent('inge_coupon_module', 'admin/model/localisation/language/addLanguage/after', 'extension/event/inge_coupon_module/model_localisation_language_addLanguage_after');
        $this->model_setting_event->addEvent('inge_coupon_module', 'admin/model/localisation/language/deleteLanguage/after', 'extension/event/inge_coupon_module/model_localisation_language_deleteLanguage_after');
        $this->model_setting_event->addEvent('inge_coupon_module', 'catalog/model/design/layout/getLayout/after', 'extension/event/inge_coupon_module/model_design_layout_getLayout_after');

    }

    public function uninstall()
    {
        if($this->validate()){
            $this->uninstallEvents();
        }

        $this->load->model('extension/module/ws_event_manager');
        $this->model_extension_module_ws_event_manager->deleteEvent($this->codename);
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('inge_coupon_module');
        $this->model_setting_setting->deleteSetting('module_inge_coupon_module');
    }

    private function uninstallEvents()
    {
        $this->load->model('extension/module/ws_event_manager');
        $this->model_extension_module_ws_event_manager->deleteEvent($this->codename);
    }
}