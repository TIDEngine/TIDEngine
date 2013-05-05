<?php


class Public_Home_Controller extends Controller {

	public function  __construct() {
		parent::__construct();

	}

	public function index($args) {
            echo 'asdasdasd';
            pr($args);
            $t = $this->Plugin_ScrollerGallery->scripts();
		pr($t);
	}
}