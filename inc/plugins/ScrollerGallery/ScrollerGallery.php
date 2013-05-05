<?php

class ScrollerGallery extends System{

    public function __construct() {
        parent::__construct();
    }

    public function plugin(){
        $images_source = PLUGIN . 'Galleries/images';

        $html[0] = '';

    }
    public function scripts(){
        $css = array(
           '0'=> PLUGIN . 'Galleries/css/demo1.css',
           '1'=>PLUGIN . 'Galleries/css/demo2.css',
           '2'=>PLUGIN . 'Galleries/css/demo3.css'
        );
        $js = array(
            PLUGIN . 'Galleries/js/jquery.easing.js',
            PLUGIN . 'Galleries/js/scroller.js',
            PLUGIN . 'Galleries/js/init.js',
        );
        return $css;
    }

    public function admin(){

    }

    private function html($type){
        $html = '<div class="scroller '. $type .'"><div class="inside">';

        foreach ($array as $key => $value) {

            $html .= '<a href="#"><img src="assets/img1.jpg" alt="asdasdadadasdas" /></a>';

        }

        $html .= '</div></div>';

    }


}
