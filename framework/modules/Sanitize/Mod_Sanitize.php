<?php

class Mod_Sanitize extends System {

    public function __construct() {
        parent::__construct();
        $this->alowed_Chars = array(" ", "-", "_", "@", "#", "&", "+");
    }

    public function strip_scripts($data) {
        // begin hacker defense
        $notAllowedExp = array(
            '/<[^>]*script.*\"?[^>]*>/', '/<[^>]*style.*\"?[^>]*>/',
            '/<[^>]*object.*\"?[^>]*>/', '/<[^>]*iframe.*\"?[^>]*>/',
            '/<[^>]*applet.*\"?[^>]*>/', '/<[^>]*window.*\"?[^>]*>/',
            '/<[^>]*docuemnt.*\"?[^>]*>/', '/<[^>]*cookie.*\"?[^>]*>/',
            '/<[^>]*meta.*\"?[^>]*>/', '/<[^>]*alert.*\"?[^>]*>/',
            '/<[^>]*form.*\"?[^>]*>/', '/<[^>]*php.*\"?[^>]*>/', '/<[^>]*img.*\"?[^>]*>/'
        ); //not allowed in the system

        foreach ($data as $postvalue) { //checking posts
            foreach ($notAllowedExp as $exp) { //checking there's no matches
                if (preg_match($exp, $postvalue)) {
                    return false;

                }
            }
        }
        return true;
    }

    public function sanitize_request($input_field_arrays) {

        foreach ($input_field_arrays as $key => $value) {

            if (ctype_alnum(str_replace($this->alowed_Chars, "", $value))) {

                $this->cleaned_input[$key] = $value;

            } else {

                $this->cleaned_input[$key] = 'error';
                
            }
        }
        return $this->cleaned_input;
    }

}
