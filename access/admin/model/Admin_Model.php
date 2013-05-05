<?php

class Admin_Model extends Model {

    private $data;
    private $language;
    private $breadcrumbs;
    private $count = 0;

    public function __construct() {
        parent::__construct();
        //$this->language = $this->Core_Model->language('admin');
        $this->data = $this->Core_Model->structure('admin');
        $this->language = $this->Core_Model->language('admin');
    }

    /**
     * page_data - Create all elements for page.
     * @return array
     */
    public function page_data($icons, $selection, $id = false, $disable=false) {

        $page_data = array();
        if(!$disable){
            $page_data['nav'] = $this->main_page_nav($icons, $selection, $id);
            $page_data['info'] = $this->server_info();
        }
        $page_data['menu'] = $this->create_menu($this->data['admin']);
        $page_data['logout'] = $this->logout();
        $page_data['footer'] = $this->footer();

        return $page_data;
    }

    /**
     * logout - Logout link
     * @return string
     */
    private function logout() {
        return '<div class="ca-icon" id="logout"><a href="' . format_urls('admin-home-logout') . '">v</a></div>';
    }

    /**
     * create_menu - Creates admin selection navigation
     * @param array $data
     * @param array $sel
     * @return string
     */
    private function create_menu($data) {

        if($this->count == 0){

            $list = '<ul id="nav"><li class="current"><a href="' . format_urls('admin-home') . '">'. $this->language['admin'] .'</a></li>';

        }else{

            $list = '<ul>';

        }
        foreach ($data as $key => $value) {

            $this->count++;
            $name = ucwords($key);

            if(is_array($value)){

                $link = format_urls($value['link']);
                $list .= '<li><a href="' . $link . '">' . $name . ' </a> ';
                if(!empty($value['child'])){
                    $list .= $this->create_menu($value['child']);
                }
                $list .= '</li>';

            }else{

                $link = format_urls($value);
                $list .= '<li><a href="' . $link . '">' . $name . '</a></li>';
            }
        }

        $list .= '</ul>';

        return $list;
    }

    /**
     * main_page_nav - Create admin home page navigation
     * @return string
     */
    private function main_page_nav($icons, $selection=false, $id=false) {

        $html = '';
        $cnt = 0;

        if ($id) {

            $id = 'id="' . $id . '"';

        }else{

            $id = '';
        }

        $html .= '<ul class="ca-menu" ' . $id . '>';
         $data = $this->data['admin'];
        if(!$selection){



        }else{
            $parts = explode('-', $selection);

            $n = count($parts);

             for ($i = 0; $i < $n; $i++) {

                $data = $data[$parts[$i]]['child'];
             }


        }

        foreach ($data as $key => $value) {

            $html .= '<li>';

            $link = format_urls($value['link']);

            $name = ucwords($key);
            $html .= ' <a href="' . $link . '"><span class="ca-icon">' . $icons[$cnt] . '</span><div class="ca-content">
<h2 class="ca-main">' . $name . '</h2>
<h3 class="ca-sub">' . $this->language[$key] . '</h3>
</div></a>';

            $html .= '</li>';
            $cnt++;
        }

        $html .= '</ul>';


        return $html;
    }

    /**
     * server_info - Basic server info
     * @return string
     */
    private function server_info() {

        $server_data = (array) $this->Module_Info->data;

        $s_data["cms"] = CMS;
        $s_data["admin_theme"] = $this->theme['admin']['theme'];
        $s_data["theme"] = $this->theme['public']['theme'];

        $html = '<div id="site_info_header">' . $this->language['menu_header'] . '</div>';

        foreach ($this->language['server'] as $key => $value) {

            if ($key !== 'site_data') {
                $html .= ' <div class="server_info">' . $value . '</div>';

                if (is_array($server_data[$key])) {

                    //$infos = implode("<br />", $server_data[$key]);
                    $infos = $server_data[$key][0];

                } else {

                    $infos = $server_data[$key];
                }
                $html .= ' <div class="server_info_data">' . $infos . '</div>';
            } else {

                foreach ($value as $keys => $values) {

                    $html .= ' <div class="server_info">' . $values . '</div>';
                    $html .= ' <div class="server_info_data">' . ucfirst($s_data[$keys]) . '</div>';
                }
            }
        }

        return $html;
    }

    /**
     * footer - Create admin page footer
     * @return string
     */
    private function footer() {

        return '<div id="copyright">' . $this->language['copyright'] . '</div>';

    }

    public function render($icons, $selection, $id, $disable, $breadcrumbs, $template, $extra=false, $marker=false){

        $page_data = $this->page_data($icons, $selection, $id, $disable);

        $page_data['breadcrumbs'] = $this->breadcrumbs($breadcrumbs);

        if($extra){

            $page_data = array_merge($page_data, $extra);

        }
       if($marker){
        $this->{$marker}();
       }
//        if($marker == 'editor'){
//            switch ($this->main_conf['editor']) {
//                case 'ckeditor':
//                    $js = SCRIPT_VENDOR . $this->main_conf['editor'] . '/' . $this->main_conf['editor'] . '.js';
//                    array_push( $this->theme['admin']['js']['footer'], $js);
//                    break;
//                case 'tinymce':
//                    $js = SCRIPT_VENDOR . $this->main_conf['editor'] . '/tinymce.min.js';
//                    $this->theme['admin']['js']['head'][0] = $js;
//                    break;
//
//            }
//        }
       if($id == 'home'){
          $id = 'admin';
       }
        $this->theme['admin']['meta_data']['title'] = $this->language[$id];

        $this->Module_Template->generate_page($this->theme['admin']['meta_data'], $page_data, $this->theme['admin']['theme'], $template, $this->theme['admin']['elements'], $this->theme['admin']['css'], $this->theme['admin']['js']);

    }

    public function nav($selection) {

        $html = '<div class="cssmenu" id="'.$selection.'" ><ul>';


        $parts = explode('-', $selection);

        $n = count($parts);

        $data = $this->data;

        for ($i = 0; $i < $n; $i++) {

            $data = $data[$parts[$i]]['child'];
        }
$cnt = 0;
        foreach ($data as $key => $value) {

            $html .= '<li>';

            $link = format_urls($value);

            $name = ucwords($key);
            $html .= ' <a href="' . $link . '" class="menu_highlight"><b class="ic">=</b>' . $name . '</a>';

            $html .= '</li>';
            $cnt++;
        }

        $html .= '</ul></div>';


        return $html;
    }

    private function editor() {
        switch ($this->main_conf['editor']) {
            case 'ckeditor':
                $js = SCRIPT_VENDOR .'editors/'. $this->main_conf['editor'] . '/' . $this->main_conf['editor'] . '.js';
                array_push($this->theme['admin']['js']['footer'], $js);
                break;
            case 'tinymce':
                $js = SCRIPT_VENDOR .'editors/'. $this->main_conf['editor'] . '/tinymce.min.js';
                $this->theme['admin']['js']['head'][10] = $js;
                break;
        }
    }

    private function elfinder() {

       // $js[0] = MODULES . 'FileUpload/js/ajaxupload-min.js';
       // $js[1] = MODULES . 'FileUpload/js/tideupload.js';
        $js[2] = MODULES . 'Elfinder/jquery/jquery-ui-1.10.1.custom.min.js';
        $js[3] = MODULES . 'Elfinder/js/elfinder.min.js';

        //$js[4] = MODULES . 'Elfinder/js/i18n/elfinder.ru.js';


        $this->theme['admin']['js']['footer'] = array_merge($this->theme['admin']['js']['footer'], $js);



       // $css[0] = MODULES . 'FileUpload/css/classicTheme/style.css';
        $css[1] = MODULES . 'Elfinder/jquery/smoothness/jquery-ui-1.10.1.custom.min.css';
        $css[2] = MODULES . 'Elfinder/css/elfinder.min.css';
        //$css[3] = MODULES . 'Elfinder/css/theme.css';
        $this->theme['admin']['css'] = array_merge($this->theme['admin']['css'], $css);

    }
}
