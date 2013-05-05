<?php

class Mod_Error extends System {

    public function __construct() {

        $this->error_list = json_decode(file_get_contents(LANG . LANG_ACTIVE . '/errors.json'), true);
    }

    public function show_error_info($error, $extra_data = false) {

        if (is_numeric($error)) {

            $error_info = $this->error_list[$error];
        } else {

            $error_info = $error;
        }

        if ($extra_data) {

            foreach ($extra_data as $shortcode => $info) {

                $error_info = str_replace($shortcode, $info, $error_info);
            }
        }

        //@TODO UGRADITI TEMPLATE SYSTEM
        echo $error_info;
        exit;
    }

}