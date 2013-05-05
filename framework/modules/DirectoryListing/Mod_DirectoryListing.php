<?php

class Mod_DirectoryListing extends System{

    public function __construct() {
        parent::__construct();
    }


    public function directory_list($url){
        pr($url);
    }
}
