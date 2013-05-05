<?php


    /**
    * TIDEngione installation real path
    * @var string $realpath
    */
    $realpath = realpath( dirname(__FILE__) );

    /**
    * TIDEngine host name and server path
    * @var string $host - Host Name
    * @var string $self - Script Path
    */
    $host = $_SERVER['HTTP_HOST'];
    $path = $_SERVER['PHP_SELF'];


    /**
    * Get runtime helpers
    */
    require( 'framework/helpers/core_helper.php' );


    /**
    * Define paths
    */
    site_address( $host, $path, $realpath );


    /**
    * Load Application configuration file
    */
    require( 'inc/config/configuration.php' );

    /**
    *
    * @TODO SECURITY OVDE ILI U RUTERU PITANJE JE SADA
    */
      /**
    * Init required Routing Classes
    */
    init_class( CORE .     'System.php');
    init_class( CORE .     'Uri.php');
    $Router = init_class( CORE .     'Router.php');

    /**
    * Include Controller and Model Class
    */
    require( CORE . 'Controller.php' );
    require( CORE . 'Model.php' );
    require( CORE . 'View.php' );


    /**@todo check debugging options  */
//       if(DEBUG){
//            // log php errors
        ini_set('log_errors','On'); // enable or disable php error logging (use 'On' or 'Off')
        ini_set('display_errors','On'); // enable or disable public display of errors (use 'On' or 'Off')
        ini_set('error_log',  'cache/logs/error.log'); // path to server-writable log file
//    }

    /** Router Class function run() for routing */
    $Router->run();



