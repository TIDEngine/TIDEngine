<?php

    /**
     * Base Singleton
     */
    function init_class($class) {

        $segments = explode(URL_SEPARATOR, $class);

        $class_name = ucwords(substr(end($segments), 0, -4));

        if (!file_exists($class)) {

            throw new Exception("$class_name cannot be found in $class.");
        }


        if (!class_exists($class_name)) {
            require($class);
        }

        return $class_name::get_instance();
    }

    /**
     *
     * Get and Define real web site address and real path
     * @param string $host 			- resolved in index.php
     * @param string $self			- resolved in index.php
     * @param string $real_path		- resolved in index.php
     * @param string $nav_path
     */
    function site_address($host, $self, $real_path, $nav_path = '') {

        /**
         * Check if we are on development
         */
        if ($host == 'localhost') {

            $domain = parse_url('http://' . $host . $self);
            $path = str_replace('/index.php', '', $domain['path']);

            $url = $domain['scheme'] . '://' . $domain['host'] . $path . '/';

            /**
             * If we have production installation we must determine and set site URL
             */
        } else {

            $domain = parse_url('http:/' . $host . $self . '/');

            $url = $domain['scheme'] . '://' . $domain['host'] . '/';
        }

        /**
         * Depending of configuration we can use friendlyh URL's
         */

        if (!empty($nav_path) && !NICE_URL) {

            $url = $url . '?u=' . $nav_path;

        } else {

            $url = $url . $nav_path;
        }

        /**
         * Define Site URL
         */
        define('SITE_URL', $url);

        /**
         * Define Site Real Path
         */
        $real_path = os_separator($real_path . '\\');


        define('SITE_ROOT', $real_path);
    }

    /**
     * os_separator() - OS dependent slashes
     *
     * @param string $path
     * @return string
     */
    function os_separator($path) {

        $os = PHP_OS;
        switch ($os) {
            case ("Linux" || "Darwin"):
                $paths = str_replace("\\", "/", $path);
                break;
            case ("WINNT" || "WIN32" || "Windows") :
                $paths = str_replace("/", "\\", $path);
                break;
            default:
                $paths = str_replace("\\", "/", $path);
                break;
        }
        return $paths;
    }


    function pr($data) {
        print_r('<pre>');
        print_r($data);
        print_r('</pre>');
    }

    function format_urls($url){
        $url = str_replace('-', '/', $url);

        if(!NICE_URL){
            $url = '?u=' . $url;
        }

        return $url;
    }