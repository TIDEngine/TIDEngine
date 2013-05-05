<?php

class Admin_Media_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function file_manager() {

    }

    public function file_uploads() {
        $path = "" . SITE_URL . "inc/uploads/";
        return '<div id="files">aaa</div>
            <div id="demo1" style="width:500px"></div>';
    }

    public function tree_menu() {


        $r = '

';


        return $r;
    }

}