<?php

class Router extends System {

    private $segments = array();
    public $controllers;
    private $layers;
    private $segments_number;

    public function run() {

        /**
         * There are 3 types of segments, there are:
         * 1. Selection segment eg. admin|public
         * 2. Method segment
         * 3. Method arguments segments
         */
        /** Get all segments from Uri*/
        $this->segments = $this->Core_Uri->segments;

        /** Get segments number from Uri*/
        $this->segments_number = $this->Core_Uri->segments_number;
        /** Set selection eg admin|public*/
        $this->selection = $this->segments[0];

        if($this->selection == 'admin'){
            $this->layers = 3;
        }else{
            $this->layers = 2;
        }
        /** Set controller */
        $this->controller = ucwords($this->segments[1]);
        /** Set function */
        if(!isset($this->segments[2])){
             $this->function =  'index';
        }else{
            $this->function = $this->segments[2];
        }

        /** Load Controllers*/
        return $this->load_controller();
    }

    /**
     *
     * Load Controller Functions
     */
    public function load_controller() {

        /** Get Function variables array */
        $variables = $this->get_variables();

        /** Construct path */
        $path = APP .  $this->selection . '/controller/'. ucwords($this->selection) . '_' . $this->controller . '_Controller.php';

        /** Check if If Class exist. If not new class instance  */
        $c = init_class($path);

        /** Get class methods */
        $class_methods = get_class_methods($this->controller);

        /** Check if Method/Function exist */
        if (!method_exists($c, $this->function)) {

            $this->Module_Error->show_error_info('100');

        }

        return call_user_func_array(array($c, $this->function), array($variables));
    }

    /**
     *
     * Remove Controller and Controller Function $this->segments array keys because we do not need them.
     * Return just Function variables.
     * @param array $layers - default 2 - 1. Controller 2. Controller Function
     */
    public function get_variables() {

        if ($this->segments_number > $this->layers) {

            $segments = array_slice($this->segments, $this->layers);

        } else {

            $segments = array();
        }

        return $segments;
    }

}