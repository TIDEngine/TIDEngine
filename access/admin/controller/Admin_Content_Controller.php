<?php

class Admin_Content_Controller extends System {

    public function __construct() {

        parent::__construct();
    }

    public function index() {

        $icons = array(">", "\\", "c", "[", "y", "È", "Ø", "i");
        $selection = 'content';
        $id = 'content';
        $disable = false;
        $breadcrumbs = 'admin-content';
        $template = 'admin-home';
        $page_data = '';
        $this->Admin_Model->render($icons, $selection, $id, $disable, $breadcrumbs, $template, $page_data);

    }

}