<?php

class Admin_Structure_Controller extends System {

    public function __construct() {

        parent::__construct();
    }

    public function index() {
        $icons = array("E", "Ì");
        $selection = 'structure';
        $id = 'structure';
        $disable = false;
        $breadcrumbs = 'admin-structure';
        $template = 'admin-home';

        $this->Admin_Model->render($icons, $selection, $id, $disable, $breadcrumbs, $template);

    }

    public function pages() {

        $icons = '';
        $selection = 'structure-pages';
        $id = 'pages';
        $disable = true;
        $breadcrumbs = 'admin-structure-pages';
        $template = 'admin-home';
        $page_data['info'] = $this->Admin_Model->nav('pages');
        $marker = 'editor';
        $this->Admin_Model->render($icons, $selection, $id, $disable, $breadcrumbs, $template, $page_data, $marker);




    }

    /**
     * pages_router - All Admin Settings are Called with ajax this method catches patameters and route method needed
     * @param array $args
     * @return string
     */
    public function router($args) {
        return call_user_func_array(array($this->Admin_Structure_Model, $args[0]), array($args));
    }


}