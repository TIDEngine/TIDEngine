<?php

/**
 *
 * class Uri -  Segments Handler check for segments and construct $segmets array
 *
 * @author bogyvet
 *
 */
class Uri extends System {

    public $segments = array();
    public $segments_number;

    public function __construct() {

        $this->_get_set_segments();
    }

    /**
     *
     * Handle GET segments and after send it to Router Class to be able to call proper Controller and Function
     * @throws Exception In case that PUBLIC_URL is not specified in config.php
     */
    private function _get_set_segments() {

        /**
         * We check for GET segments if is set GET
         */
        if (isset($_GET['u'])) {

            /** Split and sanitize segments into array */
            $cleaned_segments = $this->sanitize_segments($_GET['u']);
            
            /** Sanitized segmnts */
            $segments = $cleaned_segments[0];

            /** Number of sanitized segments */
            $this->segments_number = $cleaned_segments[1];

            /** Redirect home or error page */
           $redirect = $cleaned_segments[2];

            if(!$redirect ){

                /** Check if we have any segments if not set public/home access */
                if ($this->segments_number > 0) {

                    /** Loop over segments */
                    for ($i = 0; $i < $this->segments_number; $i++) {

                        /** Verify segment for irregular chars */
                        $verify_segment = $this->_is_valid_segment($segments[$i]);

                        /** Valid segment */
                        if ($verify_segment) {

                            $this->segments[$i] = $segments[$i];

                        } else {

                            break;
                        }
                    }

                } else {

                    $this->error_page();
                }

            }else{

                 $this->error_page();
            }

        } else {

            $this->error_page();

        }

        if ($this->segments[0] !== ADMIN_DIR && $this->segments[0] !== PUBLIC_DIR) {

            $this->error_page();

        } else {

            if ( $this->segments_number < 2) {

                $this->segments[1] = 'home';
            }
        }
    }

    private function error_page() {

        if(HOME_REDIRECT == 'home'){

             $this->segments = array('public', 'home');

        }else{

            /** Page do not exist */
            unset($this->segments);
            $this->segments = array('public','error', '100');

        }

    }

    private function sanitize_segments($path) {

        /** Empty array*/
        $clean = array();

        /** Split $_GET to segments array*/
        $segments = explode(URL_SEPARATOR, trim($path, ' /'));

        /** Count number of segn]ments */
        $number = count($segments);

        /** Loop over segments trim and check for empty segments */
        for ($i = 0; $i < $number; $i++) {

            $clean[$i] = trim($segments[$i], ' /');

            if (strlen($clean[$i]) == 0) {

                unset($clean[$i]);
                break;

            }
        }

        /** Count number of segments after sanitize */
        $clean_number = count($clean);
        /** Base redirect to home page */;

        $redirect = false;
        if($number !== $clean_number){

            $redirect = true;

        }

        return array($clean, $clean_number, $redirect);

    }

    /**
     *
     * _is_valid_segment - Check for segments validity. For irregullar characters. Legal characters are defined in preg_match.
     * @param string $segment
     * @throws Exception - If we have illegeal characters.
     */
    private function _is_valid_segment($segment) {

        /** Check for unalowed chars in segments */
        if (!preg_match("/^[" . ALLOWED_URL_CHARS . "]+$/", $segment)) {
            /** Segment is not valid */
            $segment = false;
        }

        return $segment;
    }

}

