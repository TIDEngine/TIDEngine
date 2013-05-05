<?php

/**
 * Model Class
 * Contains various Model specific functionality.
 *
 */
class Model extends System {

    public $theme;
    private $structure;
    private $language;
    public $main_conf;

    protected function __construct() {

        parent::__construct();
        $this->get_settings();/** Get Settings */
    }

    /**
     * get_settings - Locad theme settings
     *
     * @throws Exception - When default configuration file do not exists.
     */
    public function get_settings() {

        if (!$this->main_conf = json_decode(file_get_contents(CONFIG . 'tidengine.json'), true)) {
            throw new Exception("Site configuration file do not exist. Can not continue");
        } else {

            if (!$this->theme = @json_decode(file_get_contents(CONFIG . 'inc/themes/' . $this->main_conf['site_theme'] . '/theme.json'), true)) {

                // echo $main_conf['site_theme'] . " theme settings file do not exist. Default theme will be loaded!";
                $this->theme = json_decode(file_get_contents(INC . 'core/theme/theme.json'), true);
                $this->theme['admin']['theme'] = 'default';
                $this->theme['public']['theme'] = 'default';
            } else {
                $this->theme['admin']['theme'] = $main_conf['site_theme'];
                $this->theme['public']['theme'] = $main_conf['site_theme'];
                if (!isset($this->theme['admin'])) {
                    $this->theme = json_decode(file_get_contents(INC . 'core/theme/theme.xml'), true);
                    $this->theme['admin']['theme'] = 'default';
                }
            }
        }
    }

//    /**
//     * theme - Get theme data from Model and
//     * @return type
//     */
//    public function theme($selection) {
//
//        return $this->theme[$selection];
//    }

    public function structure($selection) {

        if ($selection == 'admin') {

            $data = file_get_contents(STRUCTURE . '/' . $selection . '_structure.json');

            $data = json_decode($data, true);
        } elseif ($selection == 'public') {

        }
        $this->structure = $data;
        return $data;
    }

    public function language($selection) {

        if ($selection == 'admin') {

            $data = file_get_contents(LANG . LANG_ACTIVE . '/' . $selection . '.json');

            $data = json_decode($data, true);

        } elseif ($selection == 'public') {

        }
        $this->language = $data;
        return $data;
    }

    public function breadcrumbs($selection) {

        $parts = explode('-', $selection);

        $this->language($parts[0]);

        $n = count($parts);
        $d = ">>";

        $a[0] = 'admin';
        $html = '<ul class="breadcrumb" id="t_crumb">';
        if ($n == 1) {
            $a[0] = 'admin-home';
        }


        for ($i = 0; $i < $n; $i++) {

            if($i == 0){
                $a[$i] = $parts[$i];
            }else{
                $a[$i] = $a[$i - 1] . '-' . $parts[$i];
            }


            $link = format_urls($a[$i]);

            if (end($parts) !== $parts[$i]) {
                $html .= '<li><a href="' . $link . '">' . $this->language[$parts[$i]] . '</a><span class="divider">/</span></li>';
            } else {
                $html .= '<li class="active">' . $this->language[$parts[$i]] . '</li>';
            }
        }

        $html .= '</ul>';
        return $html;
    }

}