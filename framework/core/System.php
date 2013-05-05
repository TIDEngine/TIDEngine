<?php

/*
 * System Class
 * Provides Singleton model for child classes
 */

class System {

    protected $_classes = array();/** Arrat that holds locaded classes */

    public $_class_directories = array(
        'Core' => SYS, /** Framework Classes call */
        'App' => APP, /** Application Classes call */
        'Module' => MODULES, /** Modules Classes call */
        'Admin' => ADM, /** Admin Selection Classes call */
        'Public' => PUB, /** Admin Selection Classes call */
        'Vendor' => VENDOR, /** Admin Selection Classes call */
        'Plugin' => PLUGIN, /** Admin Selection Classes call */
    );

    protected function __construct() {

    }

    /**
     *
     * __get() - Magic Method Lasy Load checks for paths defined in $this->_class_directories array
     * and load and ini undefined Class
     *
     * @param string $name - undefined Class
     */
    public function __get($name) {

        $segments = explode('_', ucwords($name));/** Class name segments */
        $path_segments = explode('_', strtolower($name));/** Path segments */

        /** Check if  $path_segments[0] is admin|public to be able to construct path or Framework Core and Modules */
        if ($path_segments[0] == ADMIN_DIR || $path_segments[0] == PUBLIC_DIR) {

            if(isset($path_segments[2] )){
                $s =  $path_segments[2] . '/';
            }else{
                $s = $path_segments[1] . '/';
            }

            $class_path = $this->_class_directories[$segments[0]] . $s . $name . '.php';
            $class = $name;
        } else if ($path_segments[0] == 'module') {

            $class_path = $this->_class_directories[$segments[0]] . $segments[1] . '/Mod_' . $segments[1] . '.php';
            $class = $segments[1];

        } else if ($path_segments[0] == 'vendor') {

            $class_path = $this->_class_directories[$segments[0]] . $segments[1] . '/' . $segments[2] . '.php';
            $class = $segments[2];

        } else if ($path_segments[0] == 'plugin') {
            /** @todo - viddeti kada bude trebalo */
            $class_path = $this->_class_directories[$segments[0]] . $segments[1] . '/' . $segments[1] . '.php';
            $class = $name;

        } else {

            $class_path = $this->_class_directories[$segments[0]] . $path_segments[0] . '/' . $segments[1] . '.php';
            $class = $segments[1];
        }

        /** If we do not have Class locaded init new instance */
        if (!isset($this->_classes[$name])) {

            /** Init Class instance */
            return $this->_classes[$class] = init_class($class_path);
        } else {

            /** return existing instance */
            return $this->_classes[$class];
        }
    }

    public static function get_instance() {

        static $aoInstance = array();

        $calledClassName = get_called_class();

        if (!isset($aoInstance[$calledClassName])) {

            $aoInstance[$calledClassName] = new $calledClassName();
        }

        return $aoInstance[$calledClassName];
    }

}