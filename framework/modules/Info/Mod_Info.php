<?php

class Mod_Info extends System {

    public $data;


    public function __construct() {

        $this->get_php_version();
        $this->php_version_compare();
        $this->get_apache_version();
        $this->host_os_info();
        $this->data['mysql_version'] = 'todo';
    }

    public function get_php_version() {

        $this->data['php_version'] = PHP_VERSION;
    }

    public function php_version_compare($check_version = false) {
        $this->data['php_compatibility'] = false;
        if ($check_version) {
            $php_version = $check_version;
        } else {
            $php_version = $this->data['php_version'];
        }

        if (version_compare($php_version, PHP_MIN) > 0) {

            $this->data['php_compatibility'] = true;
        }
    }

    public function host_os_info() {
        $this->data['host_os'] = php_uname("s");
        $this->data['host_os_paltform'] = php_uname("s");
        $this->data['host_os_version'] = php_uname("v");
        $this->data['host_name'] = php_uname("n");
        $this->data['extensions'] = get_loaded_extensions();
    }

    public function get_apache_version() {
        $this->data['apache_version'] = explode(" ", apache_get_version());

    }

    public function get_mysql_info(){
//        /$this->data['host_name'] =
    }

}