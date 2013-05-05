<?php

class Admin_Home_Controller extends Controller {

    public function __construct() {

        parent::__construct();
    }

    /**
     * index - Admin Home Page
     */
    public function index() {
        /** Icons for page navigation*/
        $icons = array("Ü", "Û", "K", "B", "Ø", "Ñ", "a", "`");

        $selection = false;
        $id = 'home';
        $disable = false;
        $breadcrumbs = 'admin';
        $template = 'admin-home';

        $this->Admin_Model->render($icons, $selection, $id, $disable, $breadcrumbs, $template);

    }

}