<?php

class Admin_Media_Controller extends System {

    public function __construct() {

        parent::__construct();
    }

    public function index() {

        $icons = array("Ø", "N");
        $selection = 'media';
        $id = 'media';
        $disable = false;
        $breadcrumbs = 'admin-media';
        $template = 'admin-home';


        $this->Admin_Model->render($icons, $selection, $id, $disable, $breadcrumbs, $template, '');
    }

    public function files() {
        $icons = '';
        $selection = 'media-files';
        $id = 'files';
        $disable = true;
        $breadcrumbs = 'admin-media-files';
        $template = 'admin-elfinder';
        // $page_data['nav'] = $this->Admin_Media_Model->file_uploads();
//        $page_data['info'] = '';
        $page_data['info'] = $this->Admin_Media_Model->tree_menu();
        $marker = 'elfinder';
        $this->Admin_Model->render($icons, $selection, $id, $disable, $breadcrumbs, $template, $page_data, $marker);
    }

    /**
     * pages_router - All Admin Settings are Called with ajax this method catches patameters and route method needed
     * @param array $args
     * @return string
     */
    public function request() {

        include_once MODULES . 'Elfinder/php/elFinderConnector.class.php';
        include_once MODULES . 'Elfinder/php/elFinder.class.php';
        include_once MODULES . 'Elfinder/php/elFinderVolumeDriver.class.php';
        include_once MODULES . 'Elfinder/php/elFinderVolumeLocalFileSystem.class.php';

        $opts = array(
            // 'debug' => true,
            'roots' => array(
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => SITE_ROOT . 'inc/uploads/files', // path to files (REQUIRED)
                    'URL' => SITE_URL . 'inc/uploads/files' // URL to files (REQUIRED)
                ),
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => SITE_ROOT . 'inc/uploads/images', // path to files (REQUIRED)
                    'URL' => SITE_URL . 'inc/uploads/images', // URL to files (REQUIRED)
                )
            )
        );

        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    }

      public function images() {
         $icons = '';
        $selection = 'media-files';
        $id = 'files';
        $disable = true;
        $breadcrumbs = 'admin-media-files';
        $template = 'admin-elfinder';
        // $page_data['nav'] = $this->Admin_Media_Model->file_uploads();
       $page_data['info'] = '';
        $page_data;
        $marker = '';
        $this->Admin_Model->render($icons, $selection, $id, $disable, $breadcrumbs, $template, $page_data, $marker);
      }

}