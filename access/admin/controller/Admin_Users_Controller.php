<?php

class Admin_Users_Controller extends System {

    public function __construct() {

        parent::__construct();
    }

    public function index() {

        $icons = array("L", "ÙÚ", "K");
        $selection = 'users';
        $id = 'users';
        $disable = false;
        $breadcrumbs = 'admin-users';
        $template = 'admin-home';
        $page_data = '';
        $this->Admin_Model->render($icons, $selection, $id, $disable, $breadcrumbs, $template, $page_data);

    }

}